<template>
    <div class="card-body card-height">
        <div class="row">
            <div class="col-md-6">
                <div class="card">  
                    <div class="card-body">
                        <h4><span class="fa fa-plus"></span> File Information</h4>
                        <div class="form-group">
                            <label>File Name</label>
                            <input type="text" v-model="post.filename" class="form-control form-control-sm">
                            <span class="errors-material" v-if="errors.filename">{{errors.filename[0]}}</span>
                        </div>
                        <div class="form-group">
                            <label>Description (Optional)</label>
                            <textarea v-model="post.description" class="form-control form-control-sm"></textarea>
                            
                        </div>
                        <!-- <div class="col-md-12"> -->
                        <span class="errors-material" v-if="errors.file">{{errors.file[0]}}</span>
                        <div class="box" v-bind="getRootProps()" v-if="editme">
                            <input v-bind="getInputProps()" >
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
                            <button type="button" @click="cancelEdit" v-else="editme" class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4><span class="fa fa-list"></span> List of files</h4>
                <data-table :sortKey="sortKey" >
                    <tbody>
                        <tr v-for = "(list, index) in filedata" :key="index" class="linkTable"  >
                            
                            <td class="hand" @click="viewInfo(list)" >
                                <!-- <img class="img-icon" src="css/folder.png" alt=""> -->
                                <span class="badge badge-success">
                                    <i class="fa fa-file"></i>&nbsp;
                                    {{ list.files.length }}
                                </span>
                                &nbsp;<strong>{{ list.filename }}</strong>
                                &nbsp;<i>{{ truncate(list.description, 30,'...')}}</i > 
                            </td>
                            <td>
                                <div class="pull-right" >
                                    <div class="btn-group">
                                        <button type="button" @click="showEditFile(list)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Modify">
                                            <span class="fa fa-edit"></span>
                                        </button>
                                        <!-- <button type="button" @click="navButton(1,list.id)" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="View Files">
                                            <span class="fa fa-eye"></span>
                                        </button> -->
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Upload Files">
                                            <span class="fa fa-upload"></span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" v-show="!noData(filedata)">
                                No Result Found!
                            </td>
                        </tr>
                    </tbody>
                </data-table>
                <div class="col-md-12">
                <pagination :pagination="pagination"
                    @prev="listOfCategory(pagination.prevPageUrl)"
                    @next="listOfCategory(pagination.nextPageUrl)"
                    v-show="noData(filedata)">
                </pagination>
            </div>

             <div class="modal fade view-file">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- <div class="modal-header">
                            <h4>ORGANIZATION</h4>
                        </div> -->
                        <div class="modal-body">
                            <h4>FILES</h4>
                            <div class="card card-body">
                              <strong>Filename: {{ uploads.filename }} </strong>
                              <i>Description: {{ uploads.description }}</i>
                            </div>
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
                                                <tr v-for ="(list,x) in uploads.files" :key="x">
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
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Delete">
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
                        <div class="modal-footer">
                            <!-- <div class="btn-group">
                                <button type="button"  @click="updateOrg"  class="btn btn-success">{{ btn_cap }}</button>
                                <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>


    </div>
</template>

<script>
import { ref, reactive } from 'vue'
import { useDropzone } from 'vue3-dropzone'
import { useRoute } from "vue-router";

import DataTable from '../../table/DataTable'
import PaginationVue from '../../table/Pagination';

export default {
components:{
        dataTable:DataTable,
        pagination:PaginationVue
},
 data(){
     return{
         sfile:[],
         docs:[],
     
     }
 },
props:{

},
 setup() {
        const route = useRoute();
        let docs = ref(null);
        const post = reactive({});
        const errors = ref([]);
        const btn_cap = ref("Save");
        let upls = [];
        let filedata = ref(null);
        const editme = ref(true);
        const uploads = ref({}); 
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
                   
                    formData.append('filename',_name);
                    formData.append('description',_file_description);
                    formData.append('org_id',org_id);
                    for (var x = 0; x < upls.length; x++) {
                        formData.append("file[]", upls[x]);
                    }
                
                    if(post.id > 0){
                        axios.put(url+post.id, post).then((res)=>{
                            cancelEdit()
                            listFile();
                            btn_cap.value ='Save'
                        }).catch(err=>{
                            if(err){
                                errors.value = err.response.data.errors
                            }
                               btn_cap.value ='Save'
                        })
                    }else{
                        axios.post(url, formData, {
                            headers: {
                                "Content-Type": "multipart/form-data",}
                        }).then((res) => {
                            errors.value = [];
                            upls = [];
                            docs.value = [];
                            Object.assign(post, {
                                id:0,
                                filename:"",
                                description:""
                            });
                            listFile();listFile();
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
                description:data.description
             });
        }

        const cancelEdit = ()=>{
             btn_cap.value ="Save";
             editme.value = true;
             errors.value = [];
             Object.assign(post, {
                id:0,
                filename:"",
                description:""
             });
        }

        const viewInfo = (data)=>{
            console.log(data);
            uploads.value = data;
            $('.view-file').modal('show');

        }

        const downloadFile = (data)=>{
            console.log(data);
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
                    //   window.open(res.data)

                    // let blob = new Blob([res.data])
                    // let link = document.createElement('a')
                    // link.href = window.URL.createObjectURL(blob)
                    // link.setAttribute('download', data.original_name+'.'+data.extension);
                    // // link.download = data.original_name+'.'+data.extension;
                    // document.body.appendChild(link);
                    // link.click()
                    //  URL.revokeObjectURL(link.href)
                })
                
            });
            // window.document.body.appendChild(this.$el)
        }

        const { getRootProps, getInputProps, ...rest } = useDropzone({ onDrop, onDropAccepted });
      
        return {
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
             showEditFile,
             editme,
             cancelEdit,
             viewInfo,
             uploads,
             downloadFile,
             viewFile
        }
    },
    methods:{
        truncate(text, length, suffix) {
            if (text.length > length) {
                return text.substring(0, length) + suffix;
            } else {
                return text;
            } 
        },
        cancel(){
            let id = this.$route.params.org_id;
            this.$router.push({name:'orgs', params:{'id':id}});
        }
    },
    created(){
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
