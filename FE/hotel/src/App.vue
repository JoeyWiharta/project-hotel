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
        <div class="row" style="margin-top: 65px;">
            <router-view></router-view>
        </div>
    </div>
</template>
