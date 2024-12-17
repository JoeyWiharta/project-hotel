<script setup>
  import { ref } from 'vue';
      // Props untuk judul dropdown
const {pathImage, title, content, space} = defineProps({
    pathImage: String,
    title: String,
    content: String,
    space: String,
})

// State untuk toggle dropdown
const isOpen = ref(false);

// Fungsi untuk toggle dropdown
const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

</script>


<template>

<div class="border rounded-3xl shadow-lg overflow-hidden md:w-1/3">
    <div class="p-4 rounded-xl">
        <img v-bind:src="pathImage" alt="" class="w-full rounded-xl">
    <!-- Header Section -->
    <button
      @click="toggleDropdown"
      class="w-full flex justify-between items-center p-4 bg-gray-100 hover:bg-gray-200 transition duration-300"
    >
      <span class="font-bold text-xl font-poppins">{{ title }}</span>
      <span
        :class="[
          isOpen ? 'rotate-180' : 'rotate-0',
          'transition-transform duration-300'
        ]"
      class="font-bold text-white text-xl bg-black rounded-full p-2">
        ^
      </span>
    </button>
    </div>
    

    <!-- Content Section -->
    <div
      v-show="isOpen"
      class="px-4 py-2 bg-white transition-all duration-500 ease-in-out"
    >
      <p class="font-bold">Feature:</p>
      <p>{{ content }}</p>

      <p class="font-bold mt-4">Space:</p>
      <p>{{ space }}</p>
    </div>
  </div>

</template>