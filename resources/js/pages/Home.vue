<template>
    <div class="container">
       <div class="justify-content-center" v-if="auth">
           <div class="card">
               <div class="card-body">
                   <div class="btn-group">
                        <button type="button" class="btn btn-success" @click="showAddModalOrg">
                        <span class="fa fa-plus-circle"></span>
                        ADD ORGANIZATION
                        </button>
                        <button type="button" class="btn btn-success" @click="navButton(1)">
                        <span class="fa fa-list"></span>
                         ORGS
                        </button>
                        <button type="button" class="btn btn-success" @click="navButton(2)">
                        <span class="fa fa-list"></span>
                         DOCS
                        </button>
                   </div>
               </div>

            <router-view/>
           </div>
       </div>

        <div class="modal add-org">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4>ORGANIZATION</h4>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <label>Description</label>
                                  <input type="text" v-model="post.description" class="form-control">
                                  <span class="errors-material" v-if="errors.description">{{errors.description[0]}}</span>
                              </div>
                               <!-- <div class="form-group">
                                  <label>Header Title</label>
                                  <input type="text"  class="form-control">
                              </div> -->
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <div class="btn-group">
                          <button type="button" :disabled="btn_enable" @click="saveOrg"  class="btn btn-success">{{ btn_cap }}</button>
                          <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>

    </div>
</template>

<script>

export default {
    name: "Home",
     components:
    {
        
    },
    data() {
        return {
            orgs:[],
            post:{ description:'',},
            btn_enable:false,
            btn_cap:'Save',
            errors:[],
            auth:false,
        }
    },
    created() {
      this.hiddenModal()
    },
    methods: {
        showAddModalOrg(){
            $('.add-org').modal('show')
        },
        saveOrg(){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.btn_cap = 'Saving...'
                this.$axios.post('api/org', this.post).then(res=>{
                    this.post = {};
                    this.btn_cap ='Save'
                    this.orgs.unshift(res.data);
                    $('.add-org').modal('hide')
                }).catch(err=>{
                    this.errors = err.response.data.errors
                    this.error = '';
                    this.btn_cap ='Save'
                });
            })
        },

        navButton(num){
            if(num == 1){
                // router.push({ name: 'home', params: { username: 'erina' } })
                this.$router.push({ name: 'orgs', });
            }else if(num == 2){
                this.$router.push({ name: 'filedocs', });
            }
        },
        hiddenModal(){
            // $('.add-org').on('hidden.bs.modal', function (e) {
            //     console.log('hello');
            //     this.post = {};
            //     this.errors = [];
            // });
        }
       
    },
    mounted(){
    
        if(window.Laravel.isLoggedin){
            this.auth = true;
        }

        
    }
}
</script>