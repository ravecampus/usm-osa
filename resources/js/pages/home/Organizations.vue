<template>
    <div class="card-body">
        <div class="row">      
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body card-height">
                        <h4>ORGANIZATIONS <i v-if="category != {}">({{ category.description }})</i></h4>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @input="loadOrganization()">
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success text-white">Semesters</span>
                                        </div>
                                        <select v-model="semesterPost" class="form-control" @change="semesterData(semesterPost)">
                                            <option value="0" :selected="'0'">All</option>
                                            <option v-for="(acc,index) in semesterFilter" :key="index" v-text="acc.label"
                                                :value="{'sem':acc.sem, 'year':acc.year}"
                                                ></option>    
                                        </select>
                                        <!-- <span class="input-group-append">
                                            <button type="button" @click="semesterData(tableData.semester)" class="btn btn-success"><i class="fa fa-filter"></i></button>
                                        </span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-success text-white">Status</span>
                                        </div>
                                        <select v-model="tableData.filter" class="form-control">
                                            <option v-for="(acc,index) in accredited" :key="index" v-text="acc.label"
                                                :value="acc.val"
                                                :selected="acc.val == '0'"
                                                ></option>
                                                
                                        </select>
                                        <span class="input-group-append">
                                            <button type="button" @click="filterAccredited()" class="btn btn-success"><i class="fa fa-filter"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-default mb-2" @click="addOrg()"><span class="fa fa-plus-circle"></span> Organization</button>
                        <button type="button" class="btn btn-default mb-2" @click="archiveOrg()"><span class="fa fa-archive"></span> Archive</button>
                        <data-table >
                            <tbody>
                                <tr v-for= "(list, index) in organizations" :key="index" class="linkTable" >
                                    
                                    <td @click="fileUpload(list)" class="hand" data-toggle="tooltip" data-placement="top" title="Add files">
                                         <ul class="list-inline">
                                            <li class="list-inline-item"><span class="fa fa-group"></span>
                                            <strong>&nbsp; {{ list.registration_number }} </strong></li>
                                            <li class="list-inline-item">{{ list.name }}</li>
                                            <li class="list-inline-item">({{ list.abbreviation }})</li>
                                        </ul>
                                        
                                    </td>
                                    <td>
                                        <ul class="list-inline text-success">
                                            <li>{{truncate(list.description, 30, '...')}}</li>
                                            <li>{{ format(new Date(list.organization_first_registered)) }}</li>
                                            <li> <strong>{{ list.adviser }}</strong></li>
                                        </ul>
                                        
                                    </td>
                                    <td>
                                        <ul class="list-inline">
                                            <li class="list-inline-item text-success">{{displayOrganization(list)}}</li>
                                        </ul>
                                    </td>
                                    <td>   
                                        <ul class="list-inline">
                                            <li><label :class="txtAccredit(list)">{{ labelAccredited(list) }}</label></li>
                                            <li >
                                                <label class="switch">
                                                    <input type="checkbox" v-model="filterdata[list.id]" @change="switchAccredited(list)" :checked="setValue(list.accredited)">
                                                    <span class="slider round"></span>
                                                </label>
                                            </li>
                                        </ul>
                                    </td>
                                    <td><div class="pull-right">
                                            <div class="btn-group">
                                                <!-- <button type="button" class="btn btn-default btn-sm" ><span class="fa fa-upload"></span></button> -->
                                                <button type="button" class="btn btn-default btn-sm" @click="UpdateModal(list)" ><span class="fa fa-edit"></span></button>
                                                <button type="button" class="btn btn-default btn-sm" @click="archiveModal(list)" ><span class="fa fa-archive"></span></button>
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
            <div class="modal fade edit-org" ref="editorg">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>ORGANIZATION</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Registration Number</label>
                                                <input type="text" v-model="post.registration_number" class="form-control">
                                                <span class="errors-material" v-if="errors.registration_number">{{errors.registration_number[0]}}</span>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Organization First Registered</label>
                                                <Datepicker v-model="post.organization_first_registered" :format="format"/>
                                                <!-- <input type="date" v-model="post.organization_first_registered" class="form-control"> -->
                                                <span class="errors-material" v-if="errors.organization_first_registered">{{errors.organization_first_registered[0]}}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" v-model="post.name" class="form-control">
                                            <span class="errors-material" v-if="errors.name">{{errors.name[0]}}</span>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Semester</label>
                                                <select v-model="post.semester" class="form-control">
                                                    <option  v-for="(list, idx) in semesters" :key="idx" :value="list.val">{{ list.label }}</option>
                                                </select>
                                                <span class="errors-material" v-if="errors.semester">{{errors.semester[0]}}</span>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Year</label>
                                                <select v-model="post.year" class="form-control">
                                                    <!-- <option value="0">Year</option> -->
                                                    <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                                                </select>
                                                <span class="errors-material" v-if="errors.year">{{errors.year[0]}}</span>
                                            </div>
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
                                        <div class="form-group">
                                            <label>Adviser</label>
                                            <input type="text" v-model="post.adviser" class="form-control">
                                            <span class="errors-material" v-if="errors.adviser">{{errors.adviser[0]}}</span>
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

            <div class="modal fade archive-org">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <!-- <div class="modal-header">
                            <h4>ORGANIZATION CATEGORY</h4>
                        </div> -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    Do you want to move <strong>{{ post.name }}</strong> on archive?
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                                <div class="btn-group">
                                <button type="button"  @click="revomeOrganization(post)"  class="btn btn-danger btn-sm">Yes</button>
                                <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade switch-accredit" ref="switchaccred">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <!-- <div class="modal-header">
                            <h4>ORGANIZATION CATEGORY</h4>
                        </div> -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    Do you want to switch status <strong>{{ post.name }} </strong> to  <strong>{{showMessageStatus()}}</strong>?
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                                <div class="btn-group">
                                <button type="button"  @click="updateStatus(post)"  class="btn btn-danger btn-sm">Yes</button>
                                <button type="button" @click="cancelStatus()"  class="btn btn-default btn-sm">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</template>

<script>
import { ref,reactive } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import DataTable from '../../table/DataTable'
import PaginationVue from '../../table/Pagination';

export default {
    components:{
        Datepicker,
        dataTable:DataTable,
        pagination:PaginationVue
    },
    setup() {
        // In case of a range picker, you'll receive [Date, Date]
        const format = (d) => {
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();

            return  month+ "-" + day  + "-" + year;
        }
        
        return {
            format,
        }
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
           
            filterdata:[],
            semesterFilter:[],
            category:{},
            id:0,
            post :{
                year: new Date()
                },
            errors:[],
            btn_cap:"Save Changes",
            organizations : [],
            columns:columns,
            sortOrders:sortOrders,
            sortKey:'created_at',
            semesterPost:{},
            not_found:false,
            semesters:[
                {'label':'First Semester', 'val':1},
                {'label':'Second Semester', 'val':2}
            ],
            accredited:[
                {'label':'All','val':0},
                {'label':'Accredited','val':1},
                {'label':'Unaccredited','val':2},
            ],
            tableData:{
                draw:0,
                length:5,
                search:'',
                column:0,
                dir:'desc',
                archive:0,
                filter:0,
                sem:0,
                yr:0,
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

        }
    },
    computed : {
        years () {
            const year = new Date().getFullYear()
            const date_ = 2000;
            return Array.from({length: year - date_}, (value, index) => (date_+ 1) + index)
        }
    },
    methods:{
        async loadOrganization(url = 'api/org/'+this.id){
             await this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.organizations = data.data.data;
                        this.configPagination(data.data);
                        this.loadListOrg();
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
        // sortBy(key){
        //     if(key != null){
        //         this.sortKey = key;
        //         this.sortOrders[key] = this.sortOrders[key] * -1;
        //         this.tableData.column = this.getIndex(this.columns, 'name', key);
        //         this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
        //         this.loadOrganization();
        //     }
            
        // },
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
        archiveOrg(){
                this.$router.push({name:'arcorg', params:{'id':this.id}});
        },
        fileUpload(list){
            this.$router.push({name:'listfiles',
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
        },
        archiveModal(data){
            this.post = data;
            $('.archive-org').modal('show');
        },
        revomeOrganization(data){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.delete('api/org/'+data.id).then(res=>{
                    this.post = {};
                    this.loadOrganization();
                    this.loadListOrg();
                    $('.archive-org').modal('hide');
                }).catch(err=>{

                });
            });
        },
        filterAccredited(){
           this.loadOrganization();
        },
        switchAccredited(data){
            this.post = data;
            if(data.accredited == 1){
                 this.post.accredited  = 0;
            }else{
                 this.post.accredited  = 1;
            }
           
            $('.switch-accredit').modal({
                backdrop: 'static',
                keyboard: false
            })
        },
        setValue(data){
            if(data == 1){
                return true;
            }else{
                return false;
            }
        },
        updateStatus(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.put('api/org/accredit/'+this.post.id, this.post).then(res=>{
                    this.post = {};
                    this.loadOrganization();
                    $('.switch-accredit').modal('hide');
                }).catch(err=>{

                });
            });
        },
        cancelStatus(){
            if(this.filterdata[this.post.id] == true){
                this.filterdata[this.post.id] = false;
            }else{
                this.filterdata[this.post.id] = true;
            }
            $('.switch-accredit').modal('hide');
        },
        showMessageStatus(){
              if(this.post.accredited == 0){
                  return "Unaccredited";
              }else{
                  return "Accredited";
              }
        },
        displayOrganization(data){
            let ret = data.name == undefined ? '' :' ('+this.semesterDisplay(data.semester)+', '+ this.parseYear(data.year)+')';
            return ret;
        },
        semesterDisplay(data){
            return data == 1 ? 'Fisrt Semester' : 'Second Semester';
        },
        parseYear(data){
            let re = data +' - '+parseInt(data+1);
            let pp = data == undefined ? ' ' : re; 
            return pp;
        },
        labelAccredited(list){
            return list.accredited == 1 ? 'ACCREDITED' : 'UNACCREDITED';
        },
        txtAccredit(data){
            return data.accredited == 1 ? 'text-success':'text-grey';
        },
        loadListOrg(){
             let id = this.$route.params.id;
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/org/list/'+id).then(res=>{
                    // console.log(res.data);
                    this.loadFilter(res.data);    
                }).catch(err=>{
                   
                });
            });
        },
        loadFilter(data){
            let unique = data
            .map(item => {
                let dis = this.semesterExt(item.semester) +', '+item.year+' - '+ parseInt(item.year + 1)
                return ({
                    'label':dis,
                    'sem':item.semester,
                    'year':item.year,
                    'id':item.year+'-'+item.semester,
                    });
            })
            this.semesterFilter = this.useUnique(unique,(a, b) => a.id == b.id);
        },
        useUnique(vl,fn){
           let arr =  vl.reduce((acc, v) => {
                if (!acc.some(x => fn(v, x))) acc.push(v);
                return acc;
            },[])
            return this.sortOrder(arr); 
        },
        extractMe(data){
            return _.uniq(_.map(data, 'label'))
        },
        sortOrder(data){
            return _.orderBy(data, 'year', 'desc');
        },
        semesterExt(data){
            return data == 1? 'First Semester' : 'Second Semester';
        },
        semesterData(data){
            this.tableData['sem'] = data.sem;
            this.tableData['yr'] = data.year;
            this.loadOrganization();
        }

    },
     mounted(){
        
        $(this.$refs.switchaccred).on("hidden.bs.modal", () => {
            this.loadOrganization();
        })

        $(this.$refs.editorg).on('hidden.bs.modal', ()=>{
            this.loadOrganization();
        });
    },
    created(){
        this.id = this.$route.params.id;
        this.loadOrganization();
        this.loadCategory();
        this. loadListOrg();
    },
}
</script>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #38c172;
}

input:focus + .slider {
  box-shadow: 0 0 1px #38c172;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
