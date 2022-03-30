<template>
    <div class="card-body card-height">
        <div class="card">
            <div class="card-body">
                 <h3>Organization Information - <i>{{ category.description }}</i> </h3> 
                <div class="row">
                    <div class="col-md-6">
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
                            <div class="btn-group">
                                <button type="button" @click="saveOrganization()" class="btn btn-success">Save</button>
                                <button type="button" @click="cancel()" class="btn btn-default">Cancel</button>
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    
    data(){
        return{
           post :{},
           errors:[],
           id:0,
           category:{},
        }
    },
    methods:{
        
        saveOrganization(){
            // let id = this.$route.params.id;
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.btn_cap = 'Saving...'
                this.post['category_id'] = this.id;
                this.$axios.post('api/org', this.post ).then(res=>{
                   
                    this.post = {};
                    this.btn_cap ='Save'
                   
                    this.$router.push({name:'orgs', params:{'id':this.id}});
                }).catch(err=>{
                    this.errors = err.response.data.errors
                    this.error = '';
                    this.btn_cap ='Save'
                });
            })
        },
        loadCategory(){
           this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/category/'+this.id).then(res=>{
                    this.category = res.data;
                });
            }) 
        },
        cancel(){
            this.$router.push({name:'orgs', params:{'id':this.id}});
        }
    },
    created(){
        this.id = this.$route.params.id;
        this.loadCategory();
    }
}
</script>

<style>

</style>
