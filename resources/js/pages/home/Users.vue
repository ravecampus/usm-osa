<template>
    <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-12 col-sm-8 col-lg-6">
            <!-- Section Heading-->
            <div class="section_heading text-center wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
              <h3>Our Creative <span> Team</span></h3>
              <p>Education is about creating leaders for tomorrow.</p>
              <div class="line">
                  <button type="button" @click="showAddUsers()" class="btn btn-success">
                      <span class="fa fa-plus"></span>
                  </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12 row mb-3">

                <div class="col-md-8">
                    <input type="text" autocomplete="off" class="form-control" v-model="tableData.search"  placeholder="Search ..." @input="listOfUser()">
                </div>
                <div class="col-md-4">
                    <pagination :pagination="pagination"
                        @prev="listOfUser(pagination.prevPageUrl)"
                        @next="listOfUser(pagination.nextPageUrl)"
                        v-show="noData(users)">
                    </pagination>
                </div>
            </div>
          <div v-for="(list, idx) in users" :key="idx" class="col-12 col-sm-6 col-lg-3">
            <div class="single_advisor_profile wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
              <div class="advisor_thumb"><img width="315px" v-bind:src="avatar(list)">
                <div class="social-info"><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-linkedin"></i></a></div>
              </div>
              <div class="single_advisor_details_info">
                <h6><strong>{{ list.fname}} &nbsp; {{ list.lname }}</strong></h6>
                <p class="designation mb-3">{{ list.email }}</p>
                <div class="btn-group">
                    <button type="button" @click="editUser(list)" class="btn btn-success btn-sm">
                        <span class="fa fa-edit"></span>
                    </button>
                    <button type="button" @click="passwordChange(list)" class="btn btn-success btn-sm">
                        <span class="fa fa-lock"></span>
                    </button>
                </div>
              </div>
            </div>
          </div>

           
        
        </div>

        <div class="modal fade add-users">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Users</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label>Name</label>
                                        <input type="text" v-model="post.fname" class="form-control" placeholder="first name">
                                        <span class="errors-material" v-if="errors.fname">{{errors.fname[0]}}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Last name</label>
                                        <input type="text" v-model="post.lname" class="form-control" placeholder="last name">
                                        <span class="errors-material" v-if="errors.lname">{{errors.lname[0]}}</span>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label>Sex</label>
                                        <select class="form-control" v-model="post.gender">
                                            <option value="male">male</option>
                                            <option value="female">female</option>
                                        </select>
                                        <!-- <input type="text"  class="form-control" placeholder="enter gender"> -->
                                        <span class="errors-material" v-if="errors.gender">{{errors.gender[0]}}</span>                  
                                    </div>
                                    <div class="col-md-12">
                                        <label>Email</label>
                                        <input type="text" v-model="post.email" class="form-control" placeholder="enter Email">
                                        <span class="errors-material" v-if="errors.email">{{errors.email[0]}}</span>                  
                                    </div>
                                    <div class="col-md-12">
                                        <label>Password</label>
                                        <input type="text" v-model="post.password" class="form-control" placeholder="enter Password">
                                        <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>                                      
                                    </div>
                                    <div class="col-md-12">
                                        <label>Confirmed</label>
                                        <input type="text" v-model="post.password_confirmation" class="form-control" placeholder="enter Confirmation">
                                        <span class="errors-material" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>                                      
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button" @click="saveUsers()"  class="btn btn-success">{{ btn_cap }}</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

          <div class="modal fade edit-users" ref="modalref">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Users</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button" @click="updateUser()"  class="btn btn-success">{{ btn_cap2 }}</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade password-change">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Changes Password</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12"><label>New</label>
                                <input type="password"  v-model="post.password" class="form-control" placeholder="Enter New Password">
                                <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                                </div>
                                <div class="col-md-12"><label>Confirm</label>
                                <input type="password" v-model="post.password_confirmation" class="form-control" placeholder="Enter Re-Password">
                                <span class="errors-material" v-if="errors.password_confirmation">{{errors.confirmed_password[0]}}</span>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <div class="btn-group">
                            <button type="button"  @click="savePassword(post)"  class="btn btn-success">{{btn_pass}}</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
      </div>
</template>

<script>
import PaginationVue from '../../table/Pagination';
export default {
    components:{
        pagination:PaginationVue
    },
    data(){
        return{
            post:{},
            btn_cap:'Save',
            btn_cap2:'Save Changes',
            btn_pass:'Save',
            errors:[],
            users:[],
            modalref:null,
            tableData:{
                draw:0,
                length:9,
                search:'',
                column:0,
                archive:0,
                dir:'desc',
                // activate:1
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
    methods:{
        showAddUsers(){
            $('.add-users').modal('show');
        },
        saveUsers(){
            this.$axios.get('sanctum/csrf-cookie/').then(res=>{
                this.btn_cap = "Saving...";
                this.$axios.post('api/profile/users', this.post).then(res=>{
                    this.btn_cap = "Save";
                    this.post = {};
                    this.errors = [];
                    this.listOfUser();
                    $('.add-users').modal('hide');
                }).catch(err=>{
                    this.btn_cap = "Save";
                    this.errors = err.response.data.errors
                    this.error = '';
                });
            });
        },
        async  listOfUser(url='api/profile/list'){
           await this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;  
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.users = data.data.data;
                        this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    } 
                })
                
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
        avatar(data){
            if(data.gender == "male"){
                return '/css/male.png';
            }else{
                return '/css/female.png';
            }
        },
        editUser(data){
            this.post = data;
            $('.edit-users').modal('show');
        },
        updateUser(){
           this.$axios.get('sanctum/csrf-cookie/').then(res=>{
                this.btn_cap2 = "Saving...";
                this.$axios.put('api/profile/users/'+this.post.id, this.post).then(res=>{
                    this.btn_cap2 = "Save Changes";
                    this.post = {};
                    this.errors = [];
                    this.listOfUser();
                    $('.edit-users').modal('hide');
                }).catch(err=>{
                    this.btn_cap2 = "Save Changes";
                    this.errors = err.response.data.errors
                    this.error = '';
                });
            }); 
        },
        passwordChange(data){
            this.post = data;
            $('.password-change').modal('show');
        },
        savePassword(data){
            this.$axios.get('sanctum/csrf-cookie/').then(res=>{
                this.btn_pass = "Saving...";
                this.$axios.put('api/profile/pass/'+data.id, data).then(res=>{
                    this.btn_pass = "Save";
                    this.post = {};
                    this.errors = [];
                    this.listOfUser();
                    $('.password-change').modal('hide');
                }).catch(err=>{
                    this.btn_pass = "Save";
                    this.errors = err.response.data.errors
                    this.error = '';
                });
            }); 
        },
         noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },

    },
    mounted(){
        $(this.$refs.modalref).on("hidden.bs.modal", () => {
                this.post = {};
                this.listOfUser()
        })
    },
    created(){
        this.listOfUser();
    }
}
</script>

<style>
.single_advisor_profile {
    position: relative;
    margin-bottom: 50px;
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    z-index: 1;
    border-radius: 15px;
    -webkit-box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
    box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
}
.single_advisor_profile .advisor_thumb {
    position: relative;
    z-index: 1;
    border-radius: 15px 15px 0 0;
    margin: 0 auto;
    padding: 30px 30px 0 30px;
    background-color: #29ac6e;
    overflow: hidden;
}
.single_advisor_profile .advisor_thumb > img {
   width: 200px;
}
.single_advisor_profile .advisor_thumb::after {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    position: absolute;
    width: 150%;
    height: 80px;
    bottom: -45px;
    left: -25%;
    content: "";
    background-color: #ffffff;
    -webkit-transform: rotate(-15deg);
    transform: rotate(-15deg);
}
@media only screen and (max-width: 575px) {
    .single_advisor_profile .advisor_thumb::after {
        height: 160px;
        bottom: -90px;
    }
}
.single_advisor_profile .advisor_thumb .social-info {
    position: absolute;
    z-index: 1;
    width: 100%;
    bottom: 0;
    right: 30px;
    text-align: right;
}
.single_advisor_profile .advisor_thumb .social-info a {
    font-size: 14px;
    color: #020710;
    padding: 0 5px;
}
.single_advisor_profile .advisor_thumb .social-info a:hover,
.single_advisor_profile .advisor_thumb .social-info a:focus {
    color: #29ac6e;
}
.single_advisor_profile .advisor_thumb .social-info a:last-child {
    padding-right: 0;
}
.single_advisor_profile .single_advisor_details_info {
    position: relative;
    z-index: 1;
    padding: 30px;
    text-align: right;
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    border-radius: 0 0 15px 15px;
    background-color: #ffffff;
}
.single_advisor_profile .single_advisor_details_info::after {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    position: absolute;
    z-index: 1;
    width: 50px;
    height: 3px;
    background-color: #29ac6e;
    content: "";
    top: 12px;
    right: 30px;
}
.single_advisor_profile .single_advisor_details_info h6 {
    margin-bottom: 0.25rem;
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
}
@media only screen and (min-width: 768px) and (max-width: 991px) {
    .single_advisor_profile .single_advisor_details_info h6 {
        font-size: 14px;
    }
}
.single_advisor_profile .single_advisor_details_info p {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    margin-bottom: 0;
    font-size: 14px;
}
@media only screen and (min-width: 768px) and (max-width: 991px) {
    .single_advisor_profile .single_advisor_details_info p {
        font-size: 12px;
    }
}
.single_advisor_profile:hover .advisor_thumb::after,
.single_advisor_profile:focus .advisor_thumb::after {
    background-color: #0e5a4d;
}
.single_advisor_profile:hover .advisor_thumb .social-info a,
.single_advisor_profile:focus .advisor_thumb .social-info a {
    color: #ffffff;
}
.single_advisor_profile:hover .advisor_thumb .social-info a:hover,
.single_advisor_profile:hover .advisor_thumb .social-info a:focus,
.single_advisor_profile:focus .advisor_thumb .social-info a:hover,
.single_advisor_profile:focus .advisor_thumb .social-info a:focus {
    color: #ffffff;
}
.single_advisor_profile:hover .single_advisor_details_info,
.single_advisor_profile:focus .single_advisor_details_info {
    background-color: #0e5a4d;
}
.single_advisor_profile:hover .single_advisor_details_info::after,
.single_advisor_profile:focus .single_advisor_details_info::after {
    background-color: #ffffff;
}
.single_advisor_profile:hover .single_advisor_details_info h6,
.single_advisor_profile:focus .single_advisor_details_info h6 {
    color: #ffffff;
}
.single_advisor_profile:hover .single_advisor_details_info p,
.single_advisor_profile:focus .single_advisor_details_info p {
    color: #ffffff;
}
</style>
