<template>
      <div class="card-body card-height">
          <div class="row">
              <div class="col-md-12">
                <data-table :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                    <tbody>
                        <tr v-for = "(list, index) in categories" :key="index">
                            
                            <td class="hand" @click="filedocpage(list)">
                                <img class="img-icon" src="/../css/archive.png" alt="">&nbsp;<i>({{ list.description }})</i>&nbsp;
                                <strong> {{ list.abbreviation}}</strong > 
                            </td>
                            <td>
                                <div class="pull-right" >
                                    <div class="btn-group">
                                        <button type="button" @click="restoreModal(list)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Restore">
                                            <span class="fa fa-undo"></span>
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
              </div>
          </div>

         <div class="modal fade restore-categ">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <!-- <div class="modal-header">
                        <h4>ORGANIZATION CATEGORY</h4>
                    </div> -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                Do you want to restore <strong>{{ post.description }}</strong>?
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <div class="btn-group">
                            <button type="button"  @click="restoreCategory(post)"  class="btn btn-danger btn-sm">Yes</button>
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
            post:{},
            categories:[],
            columns:columns,
            sortOrders:sortOrders,
            sortKey:'created_at',
            tableData:{
                draw:0,
                length:10,
                search:'',
                column:0,
                archive:1,
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
    methods:{
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
        // restoreCategory(){
        //     this.$axios.get('sanctum/csrf-cookie').then(response => {
        //         // this.btn_cap = 'Saving...'
        //         this.$axios.put('api/category/'+this.post.id, this.post).then(res=>{
        //             this.post = {};
        //             // this.btn_cap ='Save Changes';
        //             // $('.edit-categ').modal('hide');
        //         }).catch(err=>{
        //             this.errors = err.response.data.errors
        //             // this.error = '';
        //             // this.btn_cap ='Save Changes'                         
        //         });
        //     })
        // },
        restoreModal(data){
            this.post = data;
            $('.restore-categ').modal('show');
        },
        restoreCategory(data){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/category/restore/'+data.id).then(res=>{
                    this.post = {};
                    this.listOfCategory();
                    $('.restore-categ').modal('hide');
                }).catch(err=>{

                });
            });
        }

    },
    mounted(){
        this.listOfCategory();
    }
}
</script>

<style>

</style>
