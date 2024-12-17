<template>
  <swiper
    :spaceBetween="50"
    :centeredSlides="true"
    :autoplay="{
      delay: 2500,
      disableOnInteraction: false,
    }"
    :pagination="{
      clickable: true,
    }"
    :navigation="true"
    :modules="modules"
    class="mySwiper "
  >
    <swiper-slide><img src="/images/Slide Item — 1.png" alt="" ></swiper-slide>
    <swiper-slide><img src="/images/Slide Item — 2.png" alt=""></swiper-slide>
    <swiper-slide><img src="/images/Slide Item — 3.png" alt=""></swiper-slide>
    <swiper-slide><img src="/images/Slide Item — 4.png" alt=""></swiper-slide>
    <swiper-slide><img src="/images/Slide Item — 5.png" alt=""></swiper-slide>
  </swiper>
</template>
<script>
  // Import Swiper Vue.js components
  import { Swiper, SwiperSlide } from 'swiper/vue';

  // Import Swiper styles
  import 'swiper/css';

  import 'swiper/css/pagination';
  import 'swiper/css/navigation';

  // import required modules
  import { Autoplay, Pagination, Navigation } from 'swiper/modules';

  export default {
    components: {
      Swiper,
      SwiperSlide,
    },
    setup() {
      return {
        modules: [Autoplay, Pagination, Navigation],
      };
    },
  };
</script>

<style scoped>

#app {
  height: 100%;
}
html,
body {
  position: relative;
  height: 100%;
}

body {
  background: #eee;
  font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
  font-size: 14px;
  color: #000;
  margin: 0;
  padding: 0;
}

.swiper {
  width: 100%;
  height: 100%;
}

.swiper-slide {
  text-align: center;
  font-size: 18px;
  background: #fff;

  /* Center slide text vertically */
  display: flex;
  justify-content: center;
  align-items: center;
}

.swiper-slide img {
  display: block; 
  width: 100%;
  height: 100%;
  object-fit: cover;
}


</style>
