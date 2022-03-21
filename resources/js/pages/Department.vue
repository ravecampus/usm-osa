<template>
    <div class="container">
       <div class="justify-content-center">
           <div class="card">
               <div class="card-body">
                   <div class="btn-group">
                        <button type="button" class="btn btn-light" @click="showModal">
                        <span class="fa fa-plus-circle"></span>
                        SET UP DEPARTMENT
                        </button>
                   </div>
               </div>
           
                <div class="card-body">
                    <div class="col-md-12 mt-2">
                        <div class="row search-head">
                            <div class="col-md-10 mb-2">
                                <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @keyup.enter="listOfDepartment()">
                            </div>
                            <div class="col-md-2">
                                <div class="col-md-12 pull-right">
                                    <select class="form-control" aria-placeholder="sort" v-model="tableData.length" @change="listOfDepartment()">
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
                            <tr v-for = "(list, index) in departments" :key="index" class="linkTable" @click="showEdit(list, index)">
                                
                                <td><strong>{{ list.department }}</strong></td>
                                <td>{{ list.shortname }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" v-show="!noData(departments)">
                                    No Result Found!
                                </td>
                            </tr>
                        </tbody>
                        </data-table>
                        <div class="table-footer">
                        <pagination :pagination="pagination"
                            @prev="listOfDepartment(pagination.prevPageUrl)"
                            @next="listOfDepartment(pagination.nextPageUrl)"
                            v-show="noData(departments)">
                        </pagination>
                    </div>
                </div>

           </div>
       </div>

        <div class="modal add-department">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>DEPARTMENT</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Department</label>
                                    <input type="text" v-model="post.department" class="form-control">
                                    <span class="errors-material" v-if="errors.department">{{errors.department[0]}}</span>
                                </div>

                                <div class="form-group">
                                    <label>Short Name (Abbreviation) </label>
                                    <input type="text" v-model="post.shortname" class="form-control">
                                    <span class="errors-material" v-if="errors.shortname">{{errors.shortname[0]}}</span>
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
                            <button type="button" :disabled="btn_enable" @click="saveDepart"  class="btn btn-success">{{ btn_cap }}</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    import DataTable from '../table/DataTable'
    import PaginationVue from '../table/Pagination';
    
    export default {
        components:{
            dataTable:DataTable,
            pagination:PaginationVue
        },
        data(){
            let sortOrders = {};
            let columns =[
                {label:'Department', name:'department'},
                {label:'Short Name', name:'shortname'},
                ];
            
            columns.forEach(column=>{
                sortOrders[column.name] = -1;
            });
            return{
                post:{department:'', shortname:''},
                indx:0,
                errors:[],
                btn_cap:"Save",
                btn_enable: false,
                departments:[],
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
        created(){
            this.listOfDepartment();
        },
        methods:{
            saveDepart(){
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.btn_cap = 'Saving...'
                    if(this.post.id == undefined){
                        this.$axios.post('api/department', this.post).then(res=>{
                            this.post = {};
                            this.btn_cap ='Save'
                            this.departments.unshift(res.data);
                            $('.add-department').modal('hide')
                        }).catch(err=>{
                            this.errors = err.response.data.errors
                            this.error = '';
                            this.btn_cap ='Save'
                        });
                    }else{
                         this.$axios.put('api/department/'+this.post.id, this.post).then(res=>{
                            this.post = {};
                            this.btn_cap ='Save';
                            $('.add-department').modal('hide');
                        }).catch(err=>{
                            this.errors = err.response.data.errors
                            this.error = '';
                            this.btn_cap ='Save'                         
                        });
                    }

                })
            },
            showModal(){
                this.post = {};
                this.btn_cap ='Save'
                $('.add-department').modal('show');
            },
            listOfDepartment(url='api/department'){
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.tableData.draw ++;
                    this.$axios.get(url,{params:this.tableData}).then(res=>{
                    let data = res.data;
                        if(this.tableData.draw == data.draw){
                            this.departments = data.data.data;
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
                    this.listOfDepartment();
                }
                
            },
            getIndex(array, key, value){
                return array.findIndex(i=>i[key] == value)
            },
            noData(data){
                return data == undefined ? true : (data.length > 0) ? true : false;
            },
            showEdit(data, ind){
                this.btn_cap = 'Save Changes';
                this.post = data;
                this.errors = {};
                this.indx = ind;
                $('.add-department').modal('show');
            },
        }
    }
</script>

<style>

</style>
