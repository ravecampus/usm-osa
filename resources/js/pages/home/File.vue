<template>
    <div class="card-body card-height">
        <div class="row">
            <div class="col-md-6">
                <div class="card">  
                    <div class="card-body">
                        <h4>File Information</h4>
                        <div class="form-group">
                            <label>File Name</label>
                            <input type="text" v-model="post.name" class="form-control form-control-sm">
                            <span class="errors-material" v-if="errors.name">{{errors.name[0]}}</span>
                        </div>
                        <div class="form-group">
                            <label>Description (Optional)</label>
                            <textarea v-model="post.file_description" class="form-control form-control-sm"></textarea>
                            
                        </div>
                        <!-- <div class="col-md-12"> -->
                        <span class="errors-material" v-if="errors.file">{{errors.file[0]}}</span>
                        <div class="box" v-bind="getRootProps()">
                            <input v-bind="getInputProps()" >
                            <h5 v-if="isDragActive">Drop the files here ...</h5>
                            <h5 v-else>Drag and Drop some files here, or click to select files</h5>
                            
                        </div>
                        <ul class="list-group list-group-flush">
                            <li  class="list-group-item" v-for="(lst, indx) in docs" :key="indx">{{ truncate(lst.path, 30,'...')}} &nbsp;
                                <button type="button" @click="removeMe(indx)" class="btn btn-light btn-sm" >
                                <span class="fa fa-times"></span></button>
                                </li>
                        </ul>
                        <div class="btn-group mt-4">
                            <button type="button" @click="saveFiles" class="btn btn-success">Save</button>
                            <button type="button" @click="cancel" class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive } from 'vue'
import { useDropzone } from 'vue3-dropzone'
export default {

 data(){
     return{
        //  post:{},
         sfile:[],
        //  errors:[],
         docs:[],
        //  post:{}
     }
 },
props:{

},
 setup() {
        let docs = ref(null);
        const post = reactive({});
        const errors = ref([]);
        let upls = [];
        function onDrop(acceptFiles, rejectReasons) {
           
            if(upls.length  > 0){
                upls.push(acceptFiles);
            }else{
                upls = acceptFiles ;
            }
            docs.value = JSON.parse(JSON.stringify(acceptFiles));
            for (var x = 0; x < upls.length; x++) {
            }
            // localStorage.setItem('files',unwrapped);
        }
        function onDropAccepted(){
            // docs.value = JSON.parse(localStorage.getItem('files'));
        }

        const removeMe = (indx)=>{
            upls.splice(indx, 1);
            docs.value = JSON.parse(JSON.stringify(upls));
        }
        const url = "api/filed-docs"; // Your url on the server side
            const saveFiles = () => {
            axios.get('sanctum/csrf-cookie').then(response => {
                //    let ls_  = dataURItoBlob(JSON.parse(localStorage.getItem('files')));
                    const formData = new FormData(); // pass data as a form
                    let _name = post.name == undefined ? "" : post.name;
                    let _file_description = post.file_description == undefined ? "" : post.name;
                    formData.append('name',_name);
                    formData.append('file_description',_file_description);
                    for (var x = 0; x < upls.length; x++) {
                        formData.append("file[]", upls[x]);
                    }
                
                    axios.post(url, formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",}
                    }).then((response) => {
                            // console.info(response.data);
                    }).catch((err) => {
                          errors.value = err.response.data.errors
                            //btn_cap ='Save'
                    });
                });
            };
       
      

        const { getRootProps, getInputProps, ...rest } = useDropzone({ onDrop, onDropAccepted });
      
        return {
            docs,
            getRootProps,
            getInputProps,
            ...rest,
            saveFiles,
            removeMe,
            post,
            errors
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
