<template>
    <div class="container">


     <div class="row justify-content-center">
            <div class="col-md-4">
                <h1 >&nbsp;<i class="fa fa-unlock"></i> <strong>{{ siteTitle }}</strong></h1>
                <div class="card">
                    <div class="card-body">
                        <div class="row p-0">
                            <!-- <div class="col-md-6 mt-3 d-flex flex-wrap justify-content-around">
                                <img class="logo" src="/images/logo.png">
                               
                            </div> -->
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
                                    <button type="submit" :disabled="btn_disabled" class="btn btn-item-default btn-block">{{ login }}</button>
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
            post:{ email:'', password:''},
            errors:[], 
            error: '', 
            login:'Sign In', 
            siteTitle:'',
            btn_disabled: false
        }
    },
    methods: {
        handleSubmit(e) {
            // e.preventDefault()
            
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.login = "Logging...";
                    this.$axios.post('api/login',this.post)
                        .then(response => {
                            this.post = {}
                            if (response.data.success) {
                                this.login = "Sign In";
                               window.location.href="/";
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(err=>{
                            this.login = "Sign In";
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
         this.siteTitle = "LOG IN";
    }
}
</script>