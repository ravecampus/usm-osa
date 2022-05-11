<template>
    <div class="container">
        <div class="justify-content-center" v-if="auth">
            <div class="card rebor">
                <div class="card-body card-height">
                    <div class="row">
                        <div class="col-md-12 border-bottom mb-3 rebor">
                            <div class="d-flex flex-column align-items-center text-center mt-2 mb-3">
                                <h3 class="text-center">Office of Student Affairs</h3>
                                <h3 class="text-center">Accredited Campus Organizations in University of Southern Mindanao</h3>
                                <h5 class="text-center">{{ semdis }}</h5>
                            </div>
                             <ul class="list-inline">
                                <li class="list-inline-item"></li>
                                <li class="list-inline-item"></li>
                                <li class="list-inline-item"></li>
                            </ul>
                            <!-- <div class="d-flex flex-column">
                                <a href="#">
                                    <span class="fa fa-chart"></span> ORGANIZATION
                                </a>
                                 <a href="#">
                                    <span class="fa fa-chart"></span> ORGANIZATION
                                </a>
                            </div> -->
                        </div>
                        <div class="col-md-12 border-bottom">
                           <div class="table-responsive">
                                <div class="d-flex flex-column align-items-center mb-3 d-print-none btn-group">
                                    <div class="btn-group">
                                        <button type="button" @click="showGenerate" class="btn btn-success btn-lg">
                                            Show Generator Report
                                            <span class="fa fa-"></span>
                                        </button>
                                        <button type="button" @click="PrintWeb" v-if="rep" class="btn btn-success btn-lg">
                                            Print
                                            <span class="fa fa-print"></span>
                                        </button>
                                        <button type="button" @click="ExportExcel" v-if="rep" class="btn btn-success btn-lg">
                                            Export
                                            <span class="fa fa-file-excel-o"></span>
                                        </button>
                                        <button type="button" @click="Reset" v-if="rep" class="btn btn-success btn-lg">
                                            Reset
                                            <span class="fa fa-refresh"></span>
                                        </button>
                                    </div>
                                </div>
                               <table class="table table-striped table-sm" v-if="rep">
                                   <thead>
                                       <tr>
                                           <th>REGISTRATION #</th>
                                           <th>CAMPUS ORGANIZATION NAME</th>
                                           <th>CATEGORY</th>
                                           <th>ORG. 1ST REG.</th>
                                           <th>ACRONYM</th>
                                           <th>ADVISER</th>
                                           <th>FILES</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <tr v-for="(list, index) in organizations" :key="index">
                                           <td>{{ list.registration_number }}</td>
                                           <td>{{ list.name }}</td>
                                           <td>{{ extractCategory(list.category_id) }}</td>
                                           <td>{{ list.organization_first_registered }}</td>
                                           <td>{{ list.abbreviation }}</td>
                                           <td>{{ list.adviser }}</td>
                                           <td><strong>{{ list.docs.length }}</strong> doc/s.</td>
                                       </tr>
                                   </tbody>
                               </table>
                              
                           </div>
                           
                        </div>
                        <div class="col-md-12">
                            Printed By: {{ user.fname }} {{ user.lname }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" ref="showGen">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Generate Report</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                               <div class="card">
                                   <div class="card-body">
                                       <label>Filter:</label>
                                       <div class="row">
                                            <div class="col-md-6">
                                               <div class="input-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white">Category</span>
                                                        </div>
                                                        <select v-model="post.category" class="form-control" @change="categoryUpdate(post)">
                                                            <option value="0" :selected="0">All</option>
                                                            <option v-for="(list,index) in categories" :key="index" v-text="list.description"
                                                                :value="list.id"
                                                                ></option>    
                                                        </select>
                                                    </div>
                                                </div>
                                           </div>

                                           <div class="col-md-6">
                                               <div class="input-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white">Semesters</span>
                                                        </div>
                                                        <select v-model="post.semester" class="form-control">
                                                            <option value="0" :selected="0">All</option>
                                                            <option v-for="(acc,index) in semesterFilter" :key="index" v-text="acc.label"
                                                                :value="{'sem':acc.sem, 'year':acc.year, 'label':acc.label}"
                                                                ></option>    
                                                        </select>
                                                    </div>
                                                </div>
                                           </div>
                                           
                                       </div>
                                   </div>
                                    <div class="card-body">
                                       <label>Sort by:</label>
                                       <div class="row mb-5">
                                           <div class="col-md-6">
                                               <div class="input-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white">Field</span>
                                                        </div>
                                                        <select v-model="post.sort" class="form-control">
                                                            <option v-for="(list,index) in sortBy" :key="index" v-text="list.lbl"
                                                                :value="list.val"
                                                                ></option>    
                                                        </select>
                                                    </div>
                                                </div>
                                           </div>
                                            <div class="col-md-6">
                                               <div class="input-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white">Sort</span>
                                                        </div>
                                                        <select v-model="post.key" class="form-control">
                                                            <option v-for="(list,index) in keys" :key="index" v-text="list.lbl"
                                                                :value="list.val"
                                                                ></option>    
                                                        </select>
                                                    </div>
                                                </div>
                                           </div>

                                       </div>
                                   </div>
                                   <div class="d-flex flex-column align-items-center mb-5">
                                       <div class="btn-group mb-5">
                                           <button type="button" @click="generateData(post.semester)" class="btn btn-success btn-lg">Filter</button>
                                           <button type="button" data-dismiss="modal" class="btn btn-default btn-lg">Cancel</button>
                                       </div> 
                                       <div calss="col-md-12">
                                           <button type="button" @click="reported()" :disabled="dis" class="btn btn-default" v-if="ls">
                                                Generate Report <span class="badge badge-success">{{ lenShow }}</span>
                                           </button>
                                      
                                       </div>
                                   </div>

                               </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                            <div class="btn-group">
                            <button type="button"  @click="revomeOrganization(post)"  class="btn btn-danger btn-sm">Yes</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">No</button>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data(){
        return {
            auth:false,
            semesterFilter:[],
            lenShow:'',
            category_id:0,
            ls:false,
            rep:false,
            dis:false,
            generatedList:[],
            semdis:'',
            post:{
               sort: 0,
               key:'asc',
               category:0,
               sem:0,
               yr:0,
               semester:0
            },
            organizations:[],
            categories:[],
            sortBy:[
                {'lbl':'Organization name', 'val':0},
                {'lbl':'Registration #', 'val':1},
                {'lbl':'category', 'val':2},
                {'lbl':'Registration', 'val':3},
                {'lbl':'Acronym', 'val':4},
            ],
            keys:[
                {'lbl':'Asc','val':'asc'},
                {'lbl':'Desc','val':'desc'},
            ]
            
        }
    },
    methods:{
        showGenerate(){
            $(this.$refs.showGen).modal('show');
        },
        loadListOrg(){
             let id = this.category_id == undefined ? 0 : this.category_id;
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/report/org/'+id).then(res=>{
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
            // this.tableData['sem'] = data.sem;
            // this.tableData['yr'] = data.year;
           
        },
        loadCategory(){  
           this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/report/categories').then(res=>{
                    this.categories = res.data;
                });
            }) 
        },
        generateData(data){
            this.post['yr'] = data.year;
            this.post['sem'] = data.sem;
           this.lenShow = '...';
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/report/filter',{params:this.post}).then(res=>{
                    this.showLen(res.data);
                    this.generatedList = res.data;
                });
            });
        },
        showLen(data){
            let ret = '';
            if(data.length > 0){
                ret = data.length;
                this.ls = true;
                this.dis = false;
            }else{
                this.ls = true;
                this.dis = true;
                ret = 'No Record found!';
            }
            this.lenShow = ret;
        },
        reported(){
            this.semdis = this.post.semester.label;
            this.organizations = this.generatedList;
            this.rep = true;
            $(this.$refs.showGen).modal('hide');
        },
        PrintWeb(){
            const print = window;
            print.focus();
            print.print();
            print.close();
        },
        Reset(){
            this.ls = false;
            this.organizations = [];
            this.rep = false;
            this.semdis = '';
        },
        extractCategory(id){
            let ret ='';
            this.categories.forEach(val => {
                if(val.id == id){
                    ret = val.description;
                }
            });
            return ret;
        },
        categoryUpdate(data){
            this.category_id = data.category;
            this.loadListOrg(data);
        },
        ExportExcel(){
           this.post['sem_lbl'] = this.semdis;
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/report/export',{params:this.post,responseType: 'blob',}).then(res=>{
                    let blob = new Blob([res.data])
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.setAttribute('download', 'organization.xlsx');
                    // link.download = data.original_name+'.'+data.extension;
                    document.body.appendChild(link);
                    link.click()

                    
                });
            });
        }
    },
    mounted(){
         if(window.Laravel.isLoggedin){
            this.auth = true;
            this.user = window.Laravel.user
            this.loadListOrg();
            this.loadCategory();
        } 

    }
}
</script>

<style>
    @media print {
        .rebor{
            border: 0px !important;
        }
    }
</style>
