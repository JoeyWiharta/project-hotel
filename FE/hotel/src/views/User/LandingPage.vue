<template>
    <div class="bg-cover bg-[url('/images/bgLanding.png')] min-h-screen w-full flex justify-center" >

      <div class="mt-20 text-center">
        <!-- header -->
        <div class="flex justify-center">
          <img src="/images/iconHotelWhite.png" alt="">
        </div>  
        <h1 class="mt-1 text-white font-serif">ALICIA HOTEL</h1>
        <p class="text-white">- Away from Monotonous Life -</p>
        
        <!-- body -->
        <p class="mt-20 text-white px-10 lg:mx-72">Experience luxury and comfort by the beach, a place where every moment of your stay is a special experience. Choose from a variety of room types designed to meet your privacy, lifestyle and budget needs, all with a touch of inspiring elegance.</p>

        <!-- button explore -->
         <button style="background-color: #4E85BD; color: white;" class="mt-20 p-3 rounded-lg text-xl" @click="NavigateToHomePage">
            Start Exploring
         </button>
      </div>
    </div>  
  
  </template>
  
  <script setup>
  import { useRouter } from 'vue-router';
  const router = useRouter();
    function NavigateToHomePage() {
      router.push('/homepage')
    }
  </script>
  