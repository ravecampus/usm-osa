<template>
    <div class="card-body card-height">
            <div class="col-md-12 mt-2">
                <h4>ORGANIZATION CATEGORIES</h4>
                <div class="row search-head">
                    <div class="col-md-8">
                        <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @input="listOfCategory()">
                    </div>
                    <div class="col-md-4">
                        <pagination :pagination="pagination"
                            @prev="listOfCategory(pagination.prevPageUrl)"
                            @next="listOfCategory(pagination.nextPageUrl)"
                            v-show="noData(categories)">
                        </pagination>
                    </div>
                    <!-- <div class="col-md-2">
                        <div class="col-md-12 pull-right">
                            <select class="form-control" aria-placeholder="sort" v-model="tableData.length" @change="listOfOrganization()">
                                <option value="5" selected="selected">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                            </select>
                        </div>
                    </div> -->
                </div>
            </div>
            
            <data-table :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                <tbody>
                    <tr v-for = "(list, index) in categories" :key="index" class="linkTable"  >
                        
                        <td class="hand" @click="filedocpage(list)">
                            <img class="img-icon" src="css/folder.png" alt="">&nbsp;<i>({{ list.description }})</i>
                            <strong>&nbsp;{{ list.abbreviation}}</strong > 
                        </td>
                        <td>
                            <div class="pull-right" >
                                <div class="btn-group">
                                    <button type="button" @click="showDetails(list)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Modify">
                                        <span class="fa fa-info-circle"></span>
                                    </button>
                                    <button type="button" @click="showAddModalOrg(list)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Modify">
                                        <span class="fa fa-edit"></span>
                                    </button>
                                    <button type="button" @click="navButton(1,list.id)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Add Organization">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                     <button type="button" @click="archiveModal(list)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Archive">
                                        <span class="fa fa-archive"></span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" v-show="!noData(categories)">
                            No Result Found!
                        </td>
                    </tr>
                </tbody>
                </data-table>
                
                <div class="modal fade edit-categ">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>ORGANIZATION CATEGORY</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" v-model="post.description" class="form-control">
                                            <span class="errors-material" v-if="errors.description">{{errors.description[0]}}</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Abbreviation</label>
                                            <input type="text" v-model="post.abbreviation" class="form-control">
                                            <span class="errors-material" v-if="errors.abbreviation">{{errors.abbreviation[0]}}</span>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label>Header Title</label>
                                            <input type="text"  class="form-control">
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="btn-group">
                                    <button type="button"  @click="updateCategory"  class="btn btn-success">{{ btn_cap }}</button>
                                    <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade archive-categ">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <!-- <div class="modal-header">
                                <h4>ORGANIZATION CATEGORY</h4>
                            </div> -->
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                       Do you want to move <strong>{{ post.description }}</strong> on archive?
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                 <div class="btn-group">
                                    <button type="button"  @click="revomeCategory(post)"  class="btn btn-danger btn-sm">Yes</button>
                                    <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">No</button>
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
let catg = new Set();

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
            post:{},
            errors:[],
            btn_cap:"Save Changes",
            categories:[],
            category_id : 0,
            columns:columns,
            sortOrders:sortOrders,
            sortKey:'created_at',
            tableData:{
                draw:0,
                length:10,
                search:'',
                column:0,
                archive:0,
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
        this.listOfCategory();
    },
    methods: {
     async  listOfCategory(url='api/category'){
           await this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;  
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.categories = data.data.data;
                        this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    } 
                })
                
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
                this.listOfCategory();
            }
            
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        filedocpage(data){
            this.$router.push({name:'orgs', params:{'id':data.id}})
        },
        navButton(num,id){
            if(num == 1){
                this.$router.push({ name: 'fillupform',params:{'id':id }});
            }else if(num == 2){
                this.$router.push({ name: 'orgs', });
            }
        },
        showAddModalOrg(data){
            this.post = data;
            $('.edit-categ').modal('show');
        },
        updateCategory(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.btn_cap = 'Saving...'
                this.$axios.put('api/category/'+this.post.id, this.post).then(res=>{
                    this.post = {};
                    this.btn_cap ='Save Changes';
                    $('.edit-categ').modal('hide');
                }).catch(err=>{
                    this.errors = err.response.data.errors
                    this.error = '';
                    this.btn_cap ='Save Changes'                         
                });
            })
        },
        archiveModal(data){
            this.post = data;
            $('.archive-categ').modal('show');
        },
        revomeCategory(data){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.delete('api/category/'+data.id).then(res=>{
                    this.post = {};
                    this.listOfCategory();
                    $('.archive-categ').modal('hide');
                }).catch(err=>{

                });
            });
        }
    },
}
</script>

<style>

</style>
