<template>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5 img-profile" v-bind:src="avatar()">
                <span class="font-weight-bold">{{ post.fname }} {{ post.lname }}</span><span class="text-black-50">{{ post.email }}</span>
                <span> </span>
            </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="labels">Name</label>
                        <input type="text" v-model="post.fname" class="form-control" placeholder="first name">
                        <span class="errors-material" v-if="errors.fname">{{errors.fname[0]}}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="labels">Last name</label>
                        <input type="text" v-model="post.lname" class="form-control" placeholder="last name">
                        <span class="errors-material" v-if="errors.lname">{{errors.lname[0]}}</span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="labels">Address</label>
                        <input type="text" v-model="post.address" class="form-control" placeholder="enter home address">
                        <span class="errors-material" v-if="errors.address">{{errors.address[0]}}</span>                  
                   </div>
                    <div class="col-md-12">
                        <label class="labels">Mobile</label>
                        <input type="text" v-model="post.mobile_number" class="form-control" placeholder="enter phone number">
                        <span class="errors-material" v-if="errors.mobile_number">{{errors.mobile_number[0]}}</span>                                      
                    </div>
                    <div class="col-md-12">
                        <label class="labels">Sex</label>
                        <select class="form-control" v-model="post.gender">
                            <option value="male">male</option>
                            <option value="female">female</option>
                        </select>
                        <!-- <input type="text"  class="form-control" placeholder="enter gender"> -->
                        <span class="errors-material" v-if="errors.gender">{{errors.gender[0]}}</span>                  
                    </div>
                    <div class="col-md-12">
                        <label class="labels">Position</label>
                        <input type="text" v-model="post.position" class="form-control" placeholder="enter position">
                        <span class="errors-material" v-if="errors.position">{{errors.position[0]}}</span>                  
                    </div>
                    <div class="col-md-12">
                        <label class="labels">Email</label>
                        <input type="text" v-model="post.email" class="form-control" placeholder="enter email">
                        <span class="errors-material" v-if="errors.email">{{errors.email[0]}}</span>                  
                    </div>
                   
                </div>
                <div class="row mt-3">
                    <!-- <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" placeholder="country" value=""></div>
                    <div class="col-md-6"><label class="labels">State/Region</label><input type="text" class="form-control" value="" placeholder="state"></div> -->
                </div>
                <div class="mt-5 text-center">
                    <button class="btn btn-success" type="button" @click="saveProfile()">{{ btn_cap}}</button></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience">
                    <span>Change Password</span>
                    <!-- <span class="border px-3 p-1 add-experience">
                    <i class="fa fa-plus"></i>&nbsp;Password </span> -->
                </div>
                    <br>
                <div class="col-md-12"><label class="labels">Current</label>
                <input type="password" v-model="post.current_password" @change="checkCurrentPassword()" class="form-control" placeholder="Enter Current Password">
                <span class="errors-material" v-if="errors.current_password">{{errors.current_password[0]}}</span>
                </div> <br>
                <div class="col-md-12"><label class="labels">New</label>
                <input type="password" :disabled="btn_dpass" v-model="post.password" class="form-control" placeholder="Enter New Password">
                <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                </div>
                <div class="col-md-12"><label class="labels">Confirm</label>
                <input type="password" :disabled="btn_dpass" v-model="post.password_confirmation" class="form-control" placeholder="Enter Re-Password">
                 <span class="errors-material" v-if="errors.password_confirmation">{{errors.confirmed_password[0]}}</span>
                </div>
                <div class="mt-5 text-center">
                    <button class="btn btn-success btn-block" @click="checkConfirm()" :disabled="this.btn_dpass"  type="button">{{ btn_pass}}</button>
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
            post:{},
            errors:[],
            btn_cap:'Save Profile',
            btn_dpass: true,
            btn_pass:'Save'
        }
    },
    methods:{
        saveProfile(){
            // let id = this.$route.params.id;
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.btn_cap = 'Saving...'
                this.post['category_id'] = this.id;
                this.$axios.put('api/profile/'+this.post.id, this.post).then(res=>{
                    console.log(res)
                    // this.post = res.data.data;
                    this.btn_cap ='Save Profile'
                   
                    // this.$router.push({name:'orgs', params:{'id':this.id}});
                }).catch(err=>{
                    this.errors = err.response.data.errors
                    this.error = '';
                    this.btn_cap ='Save Profile'
                });
            })
        },
        checkCurrentPassword(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                // this.btn_cap = 'Saving...'
                // this.post['category_id'] = this.id;
                this.$axios.post('api/profile/check',this.post).then(res=>{
                    // this.btn_cap ='Save Profile'
                   this.btn_dpass = false;
                   this.errors = [];
                    // this.$router.push({name:'orgs', params:{'id':this.id}});
                }).catch(err=>{
                    this.post.password_confirmation = '';
                    this.post.password = '';
                    this.errors = err.response.data.errors
                    this.error = '';
                    this.btn_dpass = true;
                });
            })
        },
        checkConfirm(){
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.btn_pass = 'Saving...'
                this.$axios.post('api/profile/newpass',this.post).then(res=>{
                   this.btn_pass ='Save'
                   this.post.password_confirmation = '';
                   this.post.password = '';
                   this.post.current_password = '';
                   this.errors = [];
                    // this.$router.push({name:'orgs', params:{'id':this.id}});
                }).catch(err=>{
                    this.btn_pass ='Save'
                       this.btn_dpass = false;
                    this.errors = err.response.data.errors
                    this.error = '';
                });
            })
        },
        avatar(){
            
            if(this.post.gender == 'male'){
                return "/css/male.png";
            }else if(this.post.gender == 'female'){
                return "/css/female.png";
            }
            return "/css/male.png"
           
        }
    },
    created(){
        this.post = window.Laravel.user;
    }
}
</script>

<style>
.img-profile{
    width: 150px
}
body {
    background: rgb(245, 245, 245)
}

.form-control:focus {
    box-shadow: none;
    border-color: #BA68C8
}

.profile-button {
    background: rgb(99, 39, 120);
    box-shadow: none;
    border: none
}

.profile-button:hover {
    background: #682773
}

.profile-button:focus {
    background: #682773;
    box-shadow: none
}

.profile-button:active {
    background: #682773;
    box-shadow: none
}

.back:hover {
    color: #682773;
    cursor: pointer
}

.labels {
    font-size: 11px
}

.add-experience:hover {
    background: #BA68C8;
    color: #fff;
    cursor: pointer;
    border: solid 1px #BA68C8
}
</style>
