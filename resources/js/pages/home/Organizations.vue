<template>
    <div class="card-body">
        <div class="row">      
            <div class="col-md-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control form-control-sm" v-model="tableData.search"  placeholder="Search ..." @keyup.enter="loadDepartment()">
                            </div>
                        </div>
                    </div> -->
                   
                    <div class="card-body card-height">
                        <h4>ORGANIZATIONS <i v-if="category != {}">({{ category.description }})</i></h4>
                        <div class="card card-header mb-4">
                            <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @input="loadOrganization()">
                        </div>
                        <button type="button" class="btn btn-default mb-2" @click="addOrg()"><span class="fa fa-plus-circle"></span> Organization</button>
                        <data-table>
                            <tbody>
                                <tr v-for= "(list, index) in organizations" :key="index" class="linkTable" >
                                    
                                    <td @click="fileUpload(list)" class="hand" data-toggle="tooltip" data-placement="top" title="Add files"><span class="fa fa-angle-up">
                                        </span> <strong>{{ list.name }} </strong>
                                        <i>&nbsp; {{ list.abbreviation }}</i>
                                    </td>
                                    <td>{{ truncate(list.description, 30, '...') }}</td>
                                    <td><div class="pull-right">
                                            <div class="btn-group">
                                                <!-- <button type="button" class="btn btn-default btn-sm" ><span class="fa fa-upload"></span></button> -->
                                                <button type="button" class="btn btn-default btn-sm" @click="UpdateModal(list)" ><span class="fa fa-edit"></span></button>
                                                <button type="button" class="btn btn-default btn-sm" ><span class="fa fa-archive"></span></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" v-show="!noData(organizations)">
                                        No Result Found!
                                    </td>
                                </tr>
                            </tbody>
                            </data-table>
                            <div class="table-footer">
                                <pagination :pagination="pagination"
                                    @prev="loadOrganization(pagination.prevPageUrl)"
                                    @next="loadOrganization(pagination.nextPageUrl)"
                                    v-show="noData(organizations)">
                                </pagination>
                            </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                
            </div>
        </div>  
            <div class="modal fade edit-org">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>ORGANIZATION</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" v-model="post.name" class="form-control">
                                            <span class="errors-material" v-if="errors.name">{{errors.name[0]}}</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Description (Optional)</label>
                                            <textarea v-model="post.description" class="form-control"></textarea>
                                            <span class="errors-material" v-if="errors.description">{{errors.description[0]}}</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Abbreviation</label>
                                            <input type="text" v-model="post.abbreviation" class="form-control">
                                            <span class="errors-material" v-if="errors.abbreviation">{{errors.abbreviation[0]}}</span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button type="button"  @click="updateOrg"  class="btn btn-success">{{ btn_cap }}</button>
                                <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
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
    data(){
        let sortOrders = {};
        let columns =[
            {label:'Sort', name:'description'},
            {label:'', name:null},
            ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            category:{},
            id:0,
            post :{},
            errors:[],
            btn_cap:"Save Changes",
            organizations : [],
            columns:columns,
            sortOrders:sortOrders,
            sortKey:'created_at',
            tableData:{
                draw:0,
                length:5,
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
    created(){
        this.id = this.$route.params.id;
        this.loadOrganization();
        this.loadCategory();
    },
    methods:{
        loadOrganization(url = 'api/org/'){
             let id = this.$route.params.id;
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(url+id,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.organizations = data.data.data;
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
                this.loadOrganization();
            }
            
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        truncate(text, length, suffix) {
            if(text != null ){
                if (text.length > length) {
                    return text.substring(0, length) + suffix;
                } else {
                    return text;
                } 
            }
        },
        loadCategory(){  
           this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/category/'+this.id).then(res=>{
                    this.category = res.data;
                });
            }) 
        },
        addOrg(){
            this.$router.push({name:'fillupform', params:{'id':this.id}});
        },
        fileUpload(list){
            this.$router.push({name:'files',
             params:{'id':list.id, 'org_id':this.id}
             });
        },
        UpdateModal(data){
            this.post = data;
            $('.edit-org').modal('show');
        },
        updateOrg(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.btn_cap = 'Saving...'
                this.$axios.put('api/org/'+this.post.id, this.post ).then(res=>{
                    this.post = {};
                    this.btn_cap ='Save Changes'
                    $('.edit-org').modal('hide');
                }).catch(err=>{
                    this.errors = err.response.data.errors
                    this.error = '';
                    this.btn_cap ='Save Changes'
                });
            })
        }


    },
   
}
</script>

<style>

</style>
