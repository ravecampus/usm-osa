<template>
    <div class="container">


     <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 >&nbsp;<i class="fa fa-registered"></i> <strong>{{ siteTitle }}</strong></h1>
                <div class="card">
                    <div class="card-body">
                        <div class="row p-0">
                            
                             <div class="col-md-12">
                                <div class="alert alert-danger p-0" v-if="errors.email||errors.password ">
                                    <div class="alert errors-material m-0">
                                        <div v-if="errors.email"><strong>*</strong>{{errors.email[0]}}</div>
                                        <div v-if="errors.password"><strong>*</strong>{{errors.password[0]}}</div>
                                        </div>
                                </div>
                                <form @submit.prevent="handleSubmit">
                                    <div class="form-material">
                                        <input type="text" v-model="post.email"/>
                                        <label>Email</label>
                                    </div>
                                    <div class="form-material">
                                        <input type="password" v-model="post.password"/>
                                        <label>Password</label>
                                    </div>
                                    <div class="form-material">
                                        <input type="password" v-model="post.password_confirmation"/>
                                        <label>Confirmed Password</label>
                                    </div>
                                    <button type="submit" :disabled="btn_disabled" class="btn btn-item-default btn-block">{{ register }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            post:{ email:'', password:'', password_confirmation:''},
            errors:[], 
            error: '', 
            register:'Create Account', 
            siteTitle:'',
            btn_disabled: false  
          }
    },
    methods: {
        handleSubmit(e) {
            axios.get('sanctum/csrf-cookie').then(response => {
                this.register = "Creating...";
                axios.post('api/register',this.post)
                    .then(response => {
                        this.post = {};  
                        this.register = "Create Account"
                        window.location.href = "/login"
                    })
                    .catch(err=>{
                        this.register = "Create Account";
                        this.btn_disabled = false;
                        if(err.response.status == 422){
                            this.errors = err.response.data.errors
                            this.error = '';
                        }else{
                            this.error = err.response.data.message;
                            this.errors = [];
                        }
                    });
            })
            
        }
    },
    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next('/');
        }
        next();
    },
    mounted(){
        this.siteTitle = "CREATE ACCOUNT";
    }
}
</script>