<script setup>

const {pathImage, title, review, name} = defineProps({
    pathImage: String,
    title: String,
    review: String,
    name: String,
})

</script>

<template>
    <div class="container ">
        <div class="max-w-90 shadow-lg rounded-lg p-6 ">
            <img v-bind:src="pathImage" alt="" class="rounded-tl-lg rounded-tr-lg w-full">
            <div class="text-start">
                <h3 class="font-poppins">{{ title }}</h3>
                <p>{{ review }}</p>
                <div class="flex justify-between items-center text-center">
                    <p>{{ name }}</p>
                    <img src="/images/Rate.png" alt="" class=""> 
                </div>
            </div>
        </div>
    </div>
</template>