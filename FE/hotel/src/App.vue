<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'

const theroute = useRoute()
const isnotlogin = computed(() => {
  return theroute.name != 'login' ? true : false
})
</script>

<template>
    <div class="container">
        <div class="">
            <router-view></router-view>
        </div>
    </div>
</template>
