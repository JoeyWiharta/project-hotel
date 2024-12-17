<template>
    <!-- <div>
      <h2>Register</h2>
      <form @submit.prevent="register">
        <div>
          <label>Name:</label>
          <input type="text" v-model="name" required>
        </div>
        <div>
          <label>Email:</label>
          <input type="email" v-model="email" required>
        </div>
        <div>
          <label>Password:</label>
          <input type="password" v-model="password" required>
        </div>
        <button type="submit">Register</button>
      </form>
    </div> -->

    <div class="flex min-h-screen items-center justify-center mx-auto">
        <div class="flex w-full max-w-5xl shadow-lg">

            <!-- Logo -->
            <div class="w-1/2 relative ">
                <img src="/images/overview.png" alt="" class="">
                <h1 class="absolute inset-0 flex items-center justify-center text-white text-3xl md:text-7xl font-grand">hello!</h1>
            </div>

            <div class="w-1/2 border-t border-r border-b border-slate-400 rounded-lg">

                <!-- header -->
                <div class="flex flex-col justify-center items-center ">   
                    <img src="/images/iconHotel.png" alt="" class="max-w-[20px] lg:max-w-[3rem] md:mb-6 md:mt-4">
                    <h1 class="text-xs md:text-2xl lg:text-4xl font-semibold">Welcome To Alica Hotel</h1>
                    <p class="text-[10px] lg:text-base text-slate-700 md:mt-2">Please Enter You Details</p>
                </div>

                <!-- form Register -->
                <form action="" class="flex flex-col justify-center items-center mt-2" @submit.prevent="register">

                     <!-- Name  -->
                     <div class="flex flex-col w-40 md:w-80 mt-2 md:mt-4">
                        <label for="username" class="mb-2 font-semibold text-xs md:text-lg hidden lg:block">Full Name</label>
                        <input 
                        type="text" 
                        name="username"
                        class="rounded-lg md:p-2 h-8 md:h-full text-[10px] md:text-base"
                        placeholder="Username"
                        v-model="name" required>
                    </div>

                    <!-- email -->
                    <div class="flex flex-col md:w-80 w-40 mt-2">
                        <label for="email" class="mb-2 font-semibold text-xs md:text-lg hidden lg:block">Email</label>
                        <input 
                        name="email"
                        type="email" 
                        class="rounded-lg md:p-2 h-8 md:h-full text-[10px] md:text-base" 
                        placeholder="Email"
                        v-model="email" required
                        >
                    </div> 

                    <!-- password -->
                    <div class="flex flex-col w-40 md:w-80 mt-2 md:mt-4">
                        <label for="password" class="mb-2 font-semibold text-xs md:text-lg hidden lg:block">Password</label>
                        <input 
                        type="password" 
                        name="password"
                        class="rounded-lg md:p-2 h-8 md:h-full text-[10px] md:text-base"
                        placeholder="Password"
                        v-model="password" required
                        >
                    </div>

                    <!-- Register button -->
                    <button class="border p-2 md:p-3 mt-4 md:mt-8 text-xs md:text-xl border-slate-700 rounded-lg bg-gray-800 text-white">
                        Register
                    </button>
                </form>

            <!-- Register -->
             <div class="mt-4 md:mt-6 flex justify-center">
                <p class="md:text-base text-[10px]">Have an Account?</p>
                <router-link to="/" class="text-blue-400 ml-2 underline md:text-base text-[10px]">
                    Sign In
                </router-link>
             </div>
            </div>
        </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  import { useUserStore } from '../stores/counter'
  
  // Atur konfigurasi Axios
  axios.defaults.withCredentials = true;
  axios.defaults.withXSRFToken = true;
  
  const router = useRouter()
  const name = ref('')
  const email = ref('')
  const password = ref('')
  const store = useUserStore()
  
  const customConfig = {
    'Content-Type': 'application/json',
  };
  
  function register() {
    axios({
      url: 'http://localhost:8000/api/register', // Sesuaikan URL dengan server Laravel Anda
      method: 'post',
      data: {
        name: name.value,
        email: email.value,
        password: password.value
      },
      headers: customConfig
    }).then(response => {
      console.log(response.data) // only for development
  
      if (response.data.success === true) {
        store.email = email.value
        store.token = response.data.data
        router.push('/')
      }
    })
    .catch(error => {
      console.log('AJAX ' + error)
    })
    .finally()
  }
  </script>
  