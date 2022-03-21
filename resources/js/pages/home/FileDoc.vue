<template>
    <div>
        <button type="button" @click="loadMesage" class="btn btn-primary">hello</button>
    </div>
</template>

<script>
export default {

    data(){
        return{
            
        }
    },
    methods:{
        loadMesage(){
            this.$emit('show',{'message':'Sales Order has been saved!', 'status':6});
        }
    }
}
</script>

<style>

</style>
