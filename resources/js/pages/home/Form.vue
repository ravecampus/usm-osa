<template>
    <div class="card-body card-height">
        <div class="card">
            <div class="card-body">
                 <h3>Organization Information - <i>{{ category.description }}</i> </h3> 
                <div class="row mt-3">
                    <div class="col-md-6">
                        <form>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Registration Number</label>
                                    <input type="text" v-model="post.registration_number" class="form-control">
                                    <span class="errors-material" v-if="errors.registration_number">{{errors.registration_number[0]}}</span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Organization First Registered</label>
                                    <Datepicker v-model="post.organization_first_registered" :format="format"/>
                                    <!-- <input type="date" v-model="post.organization_first_registered" class="form-control"> -->
                                    <span class="errors-material" v-if="errors.organization_first_registered">{{errors.organization_first_registered[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" v-model="post.name" class="form-control">
                                <span class="errors-material" v-if="errors.name">{{errors.name[0]}}</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Semester</label>
                                    <select v-model="post.semester" class="form-control">
                                        <option  v-for="(list, idx) in semesters" :key="idx" :value="list.val">{{ list.label }}</option>
                                    </select>
                                    <span class="errors-material" v-if="errors.semester">{{errors.semester[0]}}</span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Year</label>
                                    <select v-model="post.year" class="form-control">
                                        <!-- <option value="0">Year</option> -->
                                        <option v-for="(year, index) in years" :key="index" 
                                        :value="year">{{ year }}</option>
                                    </select>
                                    <span class="errors-material" v-if="errors.year">{{errors.year[0]}}</span>
                                </div>
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
                            <div class="form-group">
                                <label>Adviser</label>
                                <input type="text" v-model="post.adviser" class="form-control">
                                <span class="errors-material" v-if="errors.adviser">{{errors.adviser[0]}}</span>
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
import { ref,reactive } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
export default {
    components: { Datepicker },
     setup() {
        // In case of a range picker, you'll receive [Date, Date]
        const format = (d) => {
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();

            return  month+ "-" + day  + "-" + year;
        }
        
        return {
            format,
        }
    },
    computed : {
        years () {
            const year = new Date().getFullYear()
            const date_ = 2000;
            return Array.from({length: year - date_}, (value, index) => (date_+ 1) + index)
        }
    },
    data(){
        return{
           semesters:[
                {'label':'First Semester', 'val':1},
                {'label':'Second Semester', 'val':2}
            ],
           errors:[],
           id:0,
           category:{},
            post:{
                semester:1,
                organization_first_registered: new Date(),
                year: new Date().getFullYear(),
            }
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
        },
        
    },
    created(){
        this.id = this.$route.params.id;
        this.loadCategory();
    }
}
</script>

<style>

</style>
