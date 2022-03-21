<template>
    <div class="fm-body">
        <div class="fm-content alert alert-dismissible fade show text-center" v-bind:class="status" role="alert">
            <strong></strong> {{message}}
        </div>
    </div>
</template>
<script>
export default {
    props:['message', 'status'],
    mouted(){
    
    },
}
</script>
