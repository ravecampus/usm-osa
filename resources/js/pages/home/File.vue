<template>
    <div class="card-body card-height">
        <div class="blockquote bg-success text-white p-2"><a @click="categories()" class="text-white" href="#">{{ category.description }}</a> > {{ displayOrganization(organization) }} </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">  
                    <div class="card-body">
                        <h4><span class="fa fa-plus"></span> File Information</h4>
                        <div class="form-group">
                            <label>File Name</label>
                            <input type="text" v-model="post.filename" class="form-control">
                            <span class="errors-material" v-if="errors.filename">{{errors.filename[0]}}</span>
                        </div>
                        <div class="form-group">
                            <label>Description (Optional)</label>
                            <textarea v-model="post.description" class="form-control"></textarea>             
                        </div>
                        <span class="errors-material" v-if="errors.file">{{errors.file[0]}}</span>
                        <div class="box" v-bind="getRootProps()" v-if="editme" >
                            <input v-bind="getInputProps()" :acceptedFiles="['exe']">
                            <h5 v-if="isDragActive">Drop the files here ...</h5>
                            <h5 v-else>Drag and Drop some files here, or click to select files</h5>
                            
                        </div>
                        <ul class="list-group list-group-flush" v-if="editme">
                            <li  class="list-group-item" v-for="(lst, indx) in docs" :key="indx">
                                <strong>{{ truncate(lst.path, 30,'...')}} </strong> &nbsp;
                                <button type="button" @click="removeMe(indx)" class="btn btn-light btn-sm" >
                                <span class="fa fa-times"></span></button>
                            </li>
                        </ul>
                        <div class="btn-group mt-4">
                            <button type="button" @click="saveFiles" class="btn btn-success">{{btn_cap}}</button>
                            <button type="button" @click="cancel" class="btn btn-default" v-if="editme">Cancel</button>
                            <button type="button" @click="cancelEdit" v-else-if="!editme" class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
               
            </div>
        </div>


        <div class="modal fade view-file" ref="viewfilemodal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- <div class="modal-header">
                        <h4>ORGANIZATION</h4>
                    </div> -->
                    <div class="modal-body" >
                        
                    </div>
                    <div class="modal-body" v-if="viewfilemodal_">
                        <div class="card card-body">
                            <div class="btn-group" v-if="showDownload">
                                <button type="button" @click="downloadFile(data_)" Class="btn btn-success"><span class="fa fa-download"> </span> Download</button>
                                <button type="button" @click="closeimgFile" Class="btn btn-default"> Cancel</button>
                            </div>  
                            <img  class="img-responsive" :src="image_file" v-if="!showDownload"/>
                            <button type="button" @click="closeimgFile" v-if="!showDownload" Class="btn btn-default">close</button>
                        </div>
                    </div>
                    <div class="modal-body" v-if="!viewfilemodal_">
                        <h4>FILES</h4>
                        <!-- <div class="card card-body"> -->
                            <ul class="list-inline">
                            <li class="list-inline-item"><strong>{{ uploads.filename }}</strong></li>
                            <li class="list-inline-item">{{ uploads.description }}</li>
                            <li class="list-inline-item"> {{ loadSemester(uploads.semester) }} - {{uploads.year}}</li>
                            </ul>
                        <!-- </div> -->
                        <div class="row">
                            <div class="col-md-12">                       
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Original Name</th>
                                                <th>Extension</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for ="(list,x) in uploads_files" :key="x">
                                                <td>{{list.original_name}}</td>
                                                <td>{{list.extension}}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" @click="viewFile(list)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="View Files">
                                                            <span class="fa fa-eye"></span>
                                                        </button>
                                                        <button type="button" @click="downloadFile(list)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Download">
                                                            <span class="fa fa-download"></span>
                                                        </button>
                                                        <button type="button" @click="showDeleteFile(list, x)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <span class="fa fa-trash"></span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        
                                    </table>        
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button"  @click="updateOrg"  class="btn btn-success">{{ btn_cap }}</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="modal fade upload-file" ref="uploadme">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <span class="errors-material" v-if="errors.file">{{errors.file[0]}}</span>
                        <div class="box" v-bind="getRootProps()">
                            <input v-bind="getInputProps()" >
                            <h5 v-if="isDragActive">Drop the files here ...</h5>
                            <h5 v-else>Drag and Drop some files here, or click to select files</h5>
                            
                        </div>
                        <ul class="list-group list-group-flush">
                            <li  class="list-group-item" v-for="(lst, indx) in docs" :key="indx">
                                <strong>{{ truncate(lst.path, 30,'...')}} </strong> &nbsp;
                                <button type="button" @click="removeMe(indx)" class="btn btn-light btn-sm" >
                                <span class="fa fa-times"></span></button>
                            </li>
                        </ul>

                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button"  @click="addFileUpload()"  class="btn btn-success">{{ btn_cap_ }}</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal delete-file" ref="deletefile">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <!-- <div class="modal-header">
                        <h4>ORGANIZATION CATEGORY</h4>
                    </div> -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                Do you want to remove <strong>{{ d_file.original_name }}</strong> permanently?
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <div class="btn-group">
                            <button type="button"  @click="deletePFile(d_file)"  class="btn btn-danger btn-sm">Yes</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
import { ref, reactive,onMounted } from 'vue'
import { useDropzone } from 'vue3-dropzone'
import { useRouter, useRoute } from "vue-router";
// import Datepicker from '@vuepic/vue-datepicker';
// import '@vuepic/vue-datepicker/dist/main.css';

import DataTable from '../../table/DataTable'
import PaginationVue from '../../table/Pagination';

export default {
components:{
        dataTable:DataTable,
        pagination:PaginationVue,
},
data(){
    return{
        semesters:[
            {'label':'First Semester', 'val':1},
            {'label':'Second Semester', 'val':2}
        ],
        sfile:[],
        docs:[],
        category:{},
        organization:{},
        editme_:false,
        post:{
            year: new Date()
        }
    }
},
props:{

},
computed : {
    years () {
        const year = new Date().getFullYear()
        const date_ = 2000;
        return Array.from({length: year - date_}, (value, index) => (date_+ 1) + index)
    }
},
 setup() {
        const route = useRoute();
        const router = useRouter()
        let docs = ref(null);
        const post = reactive({});
        const errors = ref([]);
        const btn_cap = ref("Save");
        const btn_cap_ = ref("Save");
        let upls = [];
        let filedata = ref(null);
        const editme = ref(true);
        const not_found = ref(true);
        const uploads = ref({}); 
        let uploads_files = ref(null);
        const data_ = ref({}); 
        const d_file = ref({}); 
        const uploadme = ref(null);
        const deletefile = ref(null);
        const viewfilemodal = ref(null);
        const viewfilemodal_ = ref(false);
        const showDownload = ref(false);
        const image_file = ref();

        function onDrop(acceptFiles, rejectReasons) {  
            
            if(upls.length  > 0){
                for (var x = 0; x < acceptFiles.length; x++) {
                let fil = upls.filter((a)=>a.name == acceptFiles[x].name);
                    if(fil.length == 0){
                        upls.unshift(acceptFiles[x]);
                    }
                }
            }else{
                upls = acceptFiles ;
            }
            docs.value = JSON.parse(JSON.stringify(upls));
            // localStorage.setItem('files',unwrapped);
        }
        function onDropAccepted(){
            // docs.value = JSON.parse(localStorage.getItem('files'));
        }

        const removeMe = (indx)=>{
            upls.splice(indx, 1);
            docs.value = JSON.parse(JSON.stringify(upls));
        }
        let org_id = route.params.id;
        const url = "api/filed-docs/"; // Your url on the server side
            const saveFiles = () => {
            btn_cap.value = "Saving..."
            axios.get('sanctum/csrf-cookie').then(response => {
                //    let ls_  = dataURItoBlob(JSON.parse(localStorage.getItem('files')));
                    const formData = new FormData(); // pass data as a form
                    let _name = post.filename == undefined ? "" : post.filename;
                    let _file_description = post.description == undefined ? "" : post.description;
                    let _year = post.year == undefined ? "" : post.year;
                    let _semester = post.semester == undefined ? "" : post.semester;
                   
                    // formData.append('year',_year);
                    // formData.append('semester',_semester);
                    formData.append('filename',_name);
                    formData.append('description',_file_description);
                    formData.append('org_id',org_id);
                    for (var x = 0; x < upls.length; x++) {
                        formData.append("file[]", upls[x]);
                    }
                
                    if(post.id > 0){
                        // axios.put(url+post.id, post).then((res)=>{
                        //     cancelEdit()
                        //     listFile();
                        //     btn_cap.value ='Save'
                        // }).catch(err=>{
                        //     if(err){
                        //         errors.value = err.response.data.errors
                        //     }
                        //        btn_cap.value ='Save'
                        // })
                    }else{
                        axios.post(url, formData, {
                            headers: {
                                "Content-Type": "multipart/form-data",}
                        }).then((res) => {
                            errors.value = [];
                            upls = [];
                            docs.value = [];
                            // Object.assign(post, {
                            //     id:0,
                            //     filename:"",
                            //     description:"",
                            //     year:"",
                            //     semester:""
                            // });
                            // listFile();
                            let orgid = route.params.org_id;
                            let id = route.params.id;
                            router.push({name:'listfiles',params:{'org_id':orgid, 'id':id}})

                            btn_cap.value ='Save'
                            // filedata.value.unshift(res.data)
                        }).catch((err) => {
                            if(err){
                                errors.value = err.response.data.errors
                            }
                               btn_cap.value ='Save'
                        });
                    }
                    
                });
            };
       
        let sortOrders = {};
        let columns =[
            {label:'File name', name:'filename'},
            {label:'', name:null},
            ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });

        const pagination = reactive({
            lastPage:'',
            currentPage:'',
            total:'',
            lastPageUrl:'',
            nextPageurl:'',
            prevPageUrl:'',
            from:'',
            to:''
        });

        const    sortKey  = ref('created_at')
        const     tableData  = reactive({
                draw:0,
                length:6,
                search:'',
                column:0,
                dir:'desc',
                // activate:1
            })

        const listFile = async (url='api/filed-docs/'+org_id) =>{
           await axios.get('sanctum/csrf-cookie').then(response => {
                tableData.draw ++;  
                axios.get(url,{params:tableData}).then(res=>{
                let data = res.data;
                    if(tableData.draw == data.draw){
                        filedata.value = data.data.data;
                        configPagination(data.data);
                    }else{
                        not_found.value = true;
                    } 
                })
                
            });
        }
        listFile()
        const  configPagination =(data)=>{
            pagination.lastPage = data.last_page;
            pagination.currentPage = data.current_page;
            pagination.total = data.total;
            pagination.lastPageUrl = data.last_page_url;
            pagination.nextPageUrl = data.next_page_url;
            pagination.prevPageUrl = data.prev_page_url;
            pagination.from = data.from;
            pagination.to = data.to;
        }

        const sortBy = (key)=>{
            if(key != null){
                sortKey.value = key;
                sortOrders[key] = sortOrders[key] * -1;
                tableData.column = getIndex(columns, 'name', key);
                tableData.dir = sortOrders[key] === 1 ? 'asc' : 'desc';
                listFile();
            }
            
        }

        const getIndex = (array, key, value)=>{
            return array.findIndex(i=>i[key] == value)
        }
        const noData = (data)=>{
            return data == undefined ? true : (data.length > 0) ? true : false;
        }
        const showEditFile = (data)=>{
            btn_cap.value ="Save Changes";
            editme.value = false;
            errors.value = [];
             Object.assign(post, {
                id:data.id,
                filename:data.filename,
                description:data.description,
                year:data.year,
                semester:data.semester
             });
        }

        const cancelEdit = ()=>{
             btn_cap.value ="Save";
             editme.value = true;
             errors.value = [];
             Object.assign(post, {
                id:0,
                filename:"",
                description:"",
                year:"",
                semester:""
             });
        }

        const viewInfo = (data)=>{
            uploads.value = data;
            uploads_files.value = data.files
            $('.view-file').modal('show');

        }

        const downloadFile = (data)=>{
             axios.get('sanctum/csrf-cookie').then(response => {
                axios.get('api/download-file/'+data.id,{responseType: 'blob',}).then(res=>{
    
                    let blob = new Blob([res.data])
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.setAttribute('download', data.original_name+'.'+data.extension);
                    // link.download = data.original_name+'.'+data.extension;
                    document.body.appendChild(link);
                    link.click()
                    //  URL.revokeObjectURL(link.href)
                })
                
            });
        }

        const viewFile = (data) =>{
             axios.get('sanctum/csrf-cookie').then(response => {
                axios.get('api/download-file/'+data.id,{responseType: 'blob',}).then(res=>{
            
                    let str = res.data.type;
                    var image = new Image();
                    var reader = new FileReader();
                    var filter = str.substring(0, str.lastIndexOf("/"));
                    var xtn = str.substring(str.lastIndexOf("/")+1, str.length);
                    if(xtn == 'pdf'){
                        let blob = new Blob([res.data], {type: 'application/pdf'})
                        var url = window.URL.createObjectURL(blob)
                        window.open(url);
                     return
                    }else if(filter == 'image'){
                      
                        reader.readAsDataURL(res.data); 
                        reader.onloadend = function() {
                            var base64data = reader.result;                    
                            image.src = base64data;
                            image_file.value = base64data;
                            viewfilemodal_.value = true;
                            showDownload.value = false;
                            // var w = window.open("");
                            // w.document.write(image.outerHTML);

                        }
                        return;
                    }else{
                        data_.value = data;
                        viewfilemodal_.value = true;
                        showDownload.value = true;
                    }
                })
                
            });
           
        }

        const adduploadfile = (data)=>{
            Object.assign(post, {
                file_id:data.id,
             });
            editme.value = false;
            $(uploadme.value).modal({
                backdrop: 'static',
                keyboard: false
            })
        }
    

        onMounted(() => {
            $(uploadme.value).on("hidden.bs.modal", () => {
                editme.value = true;
                docs.value = {};
                upls = [];
                errors.value = [];
            })

            $(uploadme.value).on('shown.bs.modal', () =>{
                editme.value = false;
                docs.value = {};
                upls = [];
            })

            $(viewfilemodal.value).on('hidden.bs.modal', () =>{
                image_file.value ="";
                viewfilemodal_.value = false;
                showDownload.value = false;
                errors.value = [];
            })
        });
        const addFileUpload = ()=>{
                const formData = new FormData(); // pass data as a form
                formData.append('file_id',post.file_id);
                for (var x = 0; x < upls.length; x++) {
                    formData.append("file[]", upls[x]);
                }
                axios.get('sanctum/csrf-cookie').then(res=>{
                    btn_cap_.value = "Saving...";
                    axios.post('api/uploads',formData,{
                        headers: {
                            "Content-Type": "multipart/form-data",}
                    }).then(res=>{
                          btn_cap_.value = "Save";
                          $(uploadme.value).modal('hide');
                          listFile();
                    }).catch(err=>{
                          btn_cap_.value = "Save";
                         if(err){
                             errors.value = err.response.data.errors
                         }
                    });
                });
        }

        const closeimgFile = ()=>{
            image_file.value ="";
            viewfilemodal_.value = false;
            showDownload.value = false;
        }
        // const format = (d) => {
        //     const year =  d.getFullYear();
        //     return ''+year;
        // }
        // const flow = ref(['year']);
        
        const showDeleteFile = (data)=>{
            d_file.value = data;
            $('.delete-file').modal('show');
        }

        const deletePFile = (data)=>{
            axios.get('sanctum/csrf-cookie').then(()=>{
                axios.delete('api/uploads/'+data.id).then((res)=>{
                    let count = 0;
                    for (var x = 0; x < uploads_files.value.length; x++) {
                        if(data.id == uploads_files.value[x].id ){
                              uploads_files.value.splice(count, 1);
                        }
                        count++;
                    }
                  
                    listFile();
                    $('.delete-file').modal('hide');
                }).catch(err=>{
                  
                })
            });
        }
        
       
        const options = reactive({
        onDropAccepted,
        multiple: true,
        onDrop,
        accept: '.jpg,.pdf,.doc,.docx,.xlsx,.xls,.png,.jpeg,.ppt,.ppsm,.ppsx,.pptm,.pptx',
        })
        
        const { getRootProps, getInputProps, ...rest } = useDropzone(options);
      
        return {
            showDeleteFile,
            deletefile,
            addFileUpload,
            docs,
            getRootProps,
            getInputProps,
            ...rest,
            saveFiles,
            removeMe,
            post,
            sortOrders:sortOrders,
            columns:columns,
            pagination,
            sortKey,
            configPagination,
            sortBy,
            noData,
            listFile,
            filedata,
             errors,
             btn_cap,
             btn_cap_,
             showEditFile,
             editme,
             cancelEdit,
             viewInfo,
             uploads,
             downloadFile,
             viewFile,
             not_found,
             adduploadfile,
             uploadme,
             image_file,
             viewfilemodal,
             viewfilemodal_,
             closeimgFile,
             data_,
             showDownload,
             tableData,
             d_file,
             deletePFile,
             uploads_files
            // format,
            // flow,
        }
    },
   
    methods:{
        truncate(text, length, suffix) {
           if(text != null){
                if (text.length > length) {
                    return text.substring(0, length) + suffix;
                } else {
                    return text;
                } 
           }
        },
        cancel(){
            let id = this.$route.params.id;
            let orgid = this.$route.params.org_id;
            this.$router.push({name:'listfiles', params:{'org_id':orgid, 'id':id}});
        },
        loadCategory(){  
            let id = this.$route.params.org_id;
           this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/category/'+id).then(res=>{
                    this.category = res.data;
                });
            }) 
        },
        loadOrganization(){  
                let id = this.$route.params.id;
                this.$axios.get('sanctum/csrf-cookie').then(response => {
                    this.$axios.get('api/org/individual/'+id).then(res=>{
                        this.organization = res.data;
                    });
                }) 
            },
        loadSemester(data){
            if(data == 1){
                return "First Semester";
            }else{
                return "Second Semester";

            }
        },
         viewListFile(data){
            let orgid = this.$route.params.org_id;
            let id = this.$route.params.id;
            this.$router.push({name:'listfiles',params:{'org_id':orgid, 'id':id, 'sem':data.val}})
        },
        categories(){
            let id = this.$route.params.org_id;
            this.$router.push({name:'orgs',params:{'id':id}});
        },
        displayOrganization(data){
            let ret = data.name == undefined ? '' : data.name+' ('+this.semesterDisplay(data.semester)+', '+ this.parseYear(data.year)+')';
            return ret;
        },
        semesterDisplay(data){
            return data == 1 ? 'Fisrt Semester' : 'Second Semester';
        },
        parseYear(data){
            let re = data +' - '+parseInt(data+1);
            let pp = data == undefined ? ' ' : re; 
            return pp;
        }

        },
    created(){
        this.loadCategory();
        this.loadOrganization();
        // this.docs = JSON.parse(localStorage.getItem('files'));
    },
    mounted(){
      
    }



}
</script>

<style>

.box {
  border: 3px dashed #666;
  background-color: #ddd;
  border-radius: .5em;
  padding: 10px;
  min-height: 150px !important;
  cursor: move;

}
</style>
