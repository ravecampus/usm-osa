<template>
    <div class="card-body">
            <div class="col-md-12 mt-2">
                <div class="row search-head">
                    <div class="col-md-10 mb-2">
                        <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @input="listOfOrganization()">
                    </div>
                    <div class="col-md-2">
                        <div class="col-md-12 pull-right">
                            <select class="form-control" aria-placeholder="sort" v-model="tableData.length" @change="listOfOrganization()">
                                <option value="5" selected="selected">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <data-table :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                <tbody>
                    <tr v-for = "(list, index) in orgs" :key="index" class="linkTable">
                        
                        <td><span class="fa fa-folder"></span> <strong>{{ list.description }}</strong></td>
                        <td class="align-right">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" v-show="!noData(orgs)">
                            No Result Found!
                        </td>
                    </tr>
                </tbody>
                </data-table>
                <div class="table-footer">
                <pagination :pagination="pagination"
                    @prev="listOfOrganization(pagination.prevPageUrl)"
                    @next="listOfOrganization(pagination.nextPageUrl)"
                    v-show="noData(orgs)">
                </pagination>
            </div>
        </div>
</template>

<script>
import DataTable from '../../table/DataTable'
import PaginationVue from '../../table/Pagination';

export default {
    components:{
        dataTable:DataTable,
        pagination:PaginationVue
    },
     data() {
        let sortOrders = {};
        let columns =[
            {label:'Sort', name:'description'},
            {label:'', name:null},
            ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return {
            orgs:[],
            columns:columns,
            sortOrders:sortOrders,
            sortKey:'created_at',
            tableData:{
                draw:0,
                length:10,
                search:'',
                column:0,
                dir:'desc',
                // activate:1
            },
            pagination:{
                lastPage:'',
                currentPage:'',
                total:'',
                lastPageUrl:'',
                nextPageurl:'',
                prevPageUrl:'',
                from:'',
                to:''
            },
            
            not_found:false
        }
    },
    created() {
        this.listOfOrganization();
    },
    methods: {
        listOfOrganization(url='api/org'){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.orgs = data.data.data;
                        this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    }
                
                }).catch(err=>{
                   
                });
            });
        },
        configPagination(data){
            this.pagination.lastPage = data.last_page;
            this.pagination.currentPage = data.current_page;
            this.pagination.total = data.total;
            this.pagination.lastPageUrl = data.last_page_url;
            this.pagination.nextPageUrl = data.next_page_url;
            this.pagination.prevPageUrl = data.prev_page_url;
            this.pagination.from = data.from;
            this.pagination.to = data.to;
        },
        sortBy(key){
            if(key != null){
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, 'name', key);
                this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
                this.listOfOrganization();
            }
            
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
    },
}
</script>

<style>

</style>
