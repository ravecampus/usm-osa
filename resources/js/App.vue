<template>
        <div class="container-fluid" id="main-page">
            <flashmessage :message="message" :status="status"></flashmessage>
            <div class="fixed-top header-nav" >
                    <nav class="navbar navbar-expand-md navbar-light navbar-main sm-navigation">

                        <div class="container">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="navbar-nav m-auto">
                                <div class="nav-item text-white">
                                    <router-link class="nav-link" :to="{name:'home'}"><strong>{{title}}</strong><span v-if="secure"></span>
                                    </router-link>
                                    <!-- <router-link :to="{name:'home'}" class="nav-link" href="#" ><strong>{{title}}</strong>  <span v-if="secure">Dashboard</span></router-link> -->
                                </div>
                            </div>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <!-- Left Side Of Navbar -->
                                    
                                <ul class="navbar-nav ml-auto">
                                    <!-- <li class="nav-item">
                                        <router-link class="nav-link" :to="{name:'home'}"><i class="fa fa-home"></i>&nbsp;Home</router-link>
                                    </li> -->
                                  
                                    <li v-for="(nav,index) in navigation_open" :key="index" v-if="!secure" class="nav-item">
                                        <router-link class="nav-link" :to="{name:nav.link}"><i class="fa" :class="nav.icon"></i>&nbsp;{{ nav.caption }}</router-link>
                                    </li>
                                   
                                   <!-- <li class="nav-item dropdown" v-if="secure">
                                        <a id="navbarDrop1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-hdd-o"></i> 
                                            <span class="caret"> Invetory</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDrop1">
                                            <router-link  v-for="(inv,indx) in inventory" :key="indx" class="dropdown-item" :to="{name:inv.link}"><i class="fa" :class="inv.icon"></i> {{ inv.caption }}</router-link>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown" v-if="secure">
                                        <a id="navbarDrop1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-product-hunt"></i> 
                                            <span class="caret"> Sales</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDrop1">
                                            <router-link  v-for="(sa,indx) in sales" :key="indx" class="dropdown-item" :to="{name:sa.link}"><i class="fa" :class="sa.icon"></i> {{ sa.caption }}</router-link>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown" v-if="secure">
                                        <a id="navbarDrop1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-money"></i> 
                                            <span class="caret"> Finances</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDrop1">
                                            <router-link  v-for="(fin,indx) in finance" :key="indx" class="dropdown-item" :to="{name:fin.link}"><i class="fa" :class="fin.icon"></i> {{ fin.caption }}</router-link>
                                        </div>
                                    </li>

                                    <li class="nav-item dropdown"  v-if="secure">
                                        <a id="navbarDrop1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-list"></i> 
                                            <span class="caret"> References</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDrop1">
                                            <router-link  v-for="(ref,indx) in references" :key="indx" class="dropdown-item" :to="{name:ref.link}"><i class="fa" :class="ref.icon"></i> {{ ref.caption }}</router-link>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown"  v-if="secure">
                                        <a id="navbarDrop1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-registered"></i> 
                                            <span class="caret"> Reports</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDrop1">
                                            <router-link  v-for="(ref,indx) in reports" :key="indx" class="dropdown-item" :to="{name:ref.link}"><i class="fa" :class="ref.icon"></i> {{ ref.caption }}</router-link>
                                        </div>
                                    </li> -->
                                    <li class="nav-item"  v-if="secure">
                                        <router-link class="nav-link" href="#" :to="{name:'category'}" role="button" >
                                            <i class="fa fa-dashboard"></i> 
                                            <span> DASHBOARD</span>
                                        </router-link>
                                       
                                    </li>
                                    <!-- <li class="nav-item"  v-if="secure">
                                        <router-link class="nav-link" href="#" :to="{name:'department'}" role="button" >
                                            <i class="fa fa-plus-circle"></i> 
                                            <span> COLLEGE DEPARTMENT</span>
                                        </router-link>
                                       
                                    </li> -->
                                    <li class="nav-item dropdown" v-if="secure">
                                        <a id="navbarDrop1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-gear"></i> 
                                            <span> SETTINGS</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDrop1">
                                            <a class="dropdown-item"><i class="fa fa-user-plus"></i> Users</a>
                                            <a class="dropdown-item"><i class="fa fa-user-circle-o"></i> Profile</a>
                                            <a class="dropdown-item"  href="#" v-on:click="logout">
                                                <i class="fa fa-sign-out"></i>
                                                Logout
                                            </a>
                                        </div>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </nav>
                </div>
            <main class="main-body">
                <div class="container-fluid">
                    <div class="m-top">
                        <router-view></router-view>
                    </div>
                </div>
            </main>
        </div>
</template>
<script>
    import FlashMessage from './pages/FlashMessage.vue';
    export default{
        components:{
            flashmessage: FlashMessage,
        },
        data(){
            return{
                message:'',
                status:'',
                title:'',
                secure:false,
                inventory:[
                     {caption:'Products', link:'products', icon:'fa-th-large'},
                     {caption:'New Stock / Stock In', link:'newstock', icon:'fa-th-large'},
                     {caption:'Product List', link:'productlist', icon:'fa-th-large'},
                    
                    ],
                references:[
                     {caption:'Category', link:'category', icon:'fa-th-large'},
                     {caption:'Units', link:'units', icon:'fa-th-large'},
                     {caption:'Terms', link:'terms', icon:'fa-th-large'},
                     {caption:'Supplier', link:'supplier', icon:'fa-users'},
                     {caption:'Customers', link:'customers', icon:'fa-users'},
                    ],
                sales:[
                     {caption:'Sales Order / Stock Out', link:'salesorder', icon:'fa-th-large'},
                     {caption:'Sales Return', link:'salesreturn', icon:'fa-th-large'},
                    ],
                reports:[
                    //  {caption:'Consumable Summary', link:'consumable', icon:'fa-th-large'},
                     {caption:'Inventory Summary', link:'inventory', icon:'fa-th-large'},
                    ],
                finance:[
                     {caption:'Account Receivable', link:'accountreceivable', icon:'fa-th-large'},
                     {caption:'Expenses', link:'expenses', icon:'fa-th-large'},
                    //  {caption:'Payment History', link:'paymenthistory', icon:'fa-th-large'},
                    ],
                   
                navigation_open:[
                    {caption:'LOGIN', link:'login',  icon:'fa-unlock'},
                    {caption:'CREATE ACCOUNT', link:'register',  icon:'fa-registered'},
                ],
                isLoggedIn: false,
            }
        },
        mounted(){
          
        },
        watch:{
            nav:(val)=>{
                let body = $('#main-page').parent().find('.main-body');
                if(val){
                   
                    body.addClass('m-container-size');   
                }else{
        
                    body.removeClass('m-container-size'); 
                }
            },
            '$route' () {
                $('#navbarSupportedContent').collapse('hide');
            }
        },
        methods:{
            getAuthUser(){
                  this.user = window.Laravel.user;          
            },
            logout(){
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('/api/logout')
                        .then(response => {
                            if (response.data.success) {
                                window.location.href = "/login"
                            } 
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
            },
            showMessage(data){
                console.log("wow");
                this.message = data.message;
                if(data.status == 1){
                    this.status = 'alert-success';
                }else if(data.status == 2){
                    this.status = 'alert-warning';
                }else if(data.status == 3){
                    this.status = 'alert-danger';
                }else if(data.status == 4){
                    this.status = 'alert-secondary';
                }else if(data.status == 5){
                    this.status = 'alert-dark';
                }else if(data.status == 6){
                    this.status = 'alert-light';
                }else if(data.status == 7){
                    this.status = 'alert-primary';
                }
                $('.fm-body').show();
                setTimeout(() => {
                    $('.fm-body').fadeOut("slow");
                }, 3000);
            },
            resendVerify(){
                $('#resend').html('Sending...');
                axios.post('/resend-verify').then(res=>{
                    $('#resend').html('Resend');
                    this.$root.$emit('show',{'message':'Email Verification has been sent !', 'status':6});
                });
            },
            shortCut(e){
                // console.log(e.key);
            }

        },
        created(){
            this.title = window.Title.app_name;
            if(window.Laravel.isLoggedin){
                this.getAuthUser();
                window.isAuth = true;
                this.secure = true;
            }
       
        },
    }
</script>

