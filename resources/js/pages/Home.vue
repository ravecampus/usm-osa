<template>
    <div class="container">
        <div class="masthead mb-5">
            <div class="d-flex align-items-center " v-if="!auth">
                <div class="container px-4 px-lg-5 text-center">
                    <h1 class="mb-1">University of Southern Mindanao</h1>
                    <h3 class="mb-5"><em>Office of Student Affairs</em></h3>
                    <router-link :to="{name:'login'}" class="btn btn-success btn-lg text-white" href="#">Sign in</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data(){
        return{
            auth:false,
        }
    },

    mounted(){
    
        if(window.Laravel.isLoggedin){
            this.auth = true;
            // window.location.href="/dashboard";
            this.$router.push({name:'dashboard'});
        }

        
    }
}
</script>
<style>
.masthead{
    padding-top: 80px;
}
</style>
