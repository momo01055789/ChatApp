<template>
    <div>
        <li :class="ClassName" class="list-group-item">{{ message }}<span id="time">{{ time }}</span></li>
        <small :class="badgeClass">{{ user }}</small>
    </div>
</template>

<script>
export default {
    props: ['message','color','user','time'],
    computed: {
        ClassName() {
            return 'list-group-item-'+this.color 
        },
        badgeClass(){
            return `badge rounded-pill bg-${this.color} float-end`
        }
    },
  
}
</script>

<style>
#time{font-size: 9px;margin: 5px;font-weight: bold;}
</style>