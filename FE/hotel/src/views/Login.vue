<template>
  <div class="flex min-h-screen items-center justify-center mx-auto">
        <div class="flex w-full max-w-5xl shadow-lg">

            <!-- Logo -->
            <div class="w-1/2 relative ">
                <img src="/images/overview.png" alt="" class="">
                <h1 class="absolute inset-0 flex items-center justify-center text-white md:text-7xl  text-3xl font-grand">hello!</h1>
            </div>

            <div class="w-1/2 border-t border-r border-b border-slate-400 rounded-lg">

                <!-- header -->
                <div class="flex flex-col justify-center items-center">   
                    <img src="/images/iconHotel.png" alt="" class="max-w-[20px] lg:max-w-[3rem] md:mb-6 md:mt-4">
                    <h1 class="text-sm md:text-2xl lg:text-4xl font-semibold">Welcome Back!</h1>
                    <p class="text-[10px] lg:text-base text-slate-700 md:mt-2">Please Enter You Details</p>
                </div>

                <!-- form login -->
                <form action="" class="flex flex-col justify-center items-center md:mt-16 mt-4" @submit.prevent="login">
                    <!-- email -->
                    <div class="flex flex-col md:w-80 w-40">
                        <label for="email" class="mb-2 font-semibold text-xs md:text-lg hidden lg:block">Email</label>
                        <input 
                        name="email"
                        type="email" 
                        class="rounded-lg md:p-2 h-8 md:h-full text-[10px] md:text-base" 
                        placeholder="Email"
                        v-model="username" required
                        >
                    </div> 
                    <!-- password -->
                    <div class="flex flex-col w-40 md:w-80 mt-2 md:mt-5">
                        <label for="password" class="mb-2 font-semibold text-xs md:text-lg hidden lg:block">Password</label>
                        <input 
                        type="password" 
                        name="password"
                        class="rounded-lg md:p-2 h-8 md:h-full text-[10px] md:text-base"
                        placeholder="Password"
                        v-model="password" required>
                    </div>
                    
                    <!-- Login button -->
                    <button class="border p-2 md:p-3 mt-4 md:mt-8 text-xs md:text-xl border-slate-700 rounded-lg bg-gray-800 text-white">
                        Log In
                    </button>
                </form>

            <!-- Register -->
             <div class="mt-6 md:mt-10 flex justify-center">
                <p class="md:text-base text-[10px]">Don't Have an Account?</p>
                <router-link to="/register" class="text-blue-400 ml-2 underline md:text-base text-[10px]">
                    Sign Up
                </router-link>
             </div>
                 


            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'

// Atur konfigurasi Axios
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const router = useRouter()
const auth = ref(null)
const username = ref('')
const password = ref('')
const store = useUserStore()

const customConfig = {
  'Content-Type': 'application/json',
};

const bodyParameters = computed(() => {
  return {'email': username.value, 'password': password.value}
});

function login() {
  axios({
    url: 'http://localhost:8000/api/login', // Sesuaikan URL dengan server Laravel Anda
    method: 'post',
    data: bodyParameters.value,
    headers: customConfig
  }).then(response => {
    auth.value = response.data
    console.log(response.data) // hanya untuk pengembangan

    if (auth.value.success === true) {
      store.email = username.value
      store.token = auth.value.data.token
      store.role = auth.value.data.role // Menyimpan role pengguna di store

      // Set token sebagai header default untuk semua permintaan Axios
      axios.defaults.headers.common['Authorization'] = `Bearer ${store.token}`

      console.log('Role:', store.role) // Debugging tambahan
      console.log('Navigating to dashboard') // Debugging tambahan

      if (store.role === 'admin') {
        router.push('/admin-dashboard')
      } else {
        router.push('/user-dashboard')
      }
    }
  })
  .catch(error => {
    console.log('AJAX ' + error)
  })
  .finally()
}
</script>
