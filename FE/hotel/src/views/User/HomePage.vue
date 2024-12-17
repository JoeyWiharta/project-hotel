<script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router'
  import { useUserStore } from '../../stores/counter'
  import axios from 'axios'
  import Slider from '@/components/Slider.vue';
  import CardRating from '@/components/CardRating.vue';
  import UserBooking from './UserBooking.vue';
import CardRoom from '@/components/CardRoom.vue';
  
  const router = useRouter()
  const store = useUserStore()

  const userRating = [
    {id:1, title:'Restroom', pathImage:'/images/restroom.png', review:'“The restrooms are clean, luxurious, comfortable, and always well-maintained”' ,name:'Hosanna Megan Putra Wibawa'},

    {id:2, title:'Views', pathImage:'/images/view.png', review:'“The view of the hotel from the outside is very charming and the design is very elegant”' ,name:'Joey Liauw Wiharta'},

    {id:3, title:'Bedroom', pathImage:'/images/luxury2.png', review:'“The design is luxurious, the facilities are complete, and its very comfortable to relax”' , name:'Richard Yohanes'},

    {id:4, title:'Playground', pathImage:'/images/billiard2.png', review:'“The atmosphere is pleasant, and it is perfect for relaxing while playing with family”' ,name:'Muhammad Edlyn'},

    {id:5, title:'Ocean', pathImage:'/images/snorkeling2.png', review:'“The underwater scenery is amazing”' ,name:'Fathan Rafa Kamil'},

    {id:6, title:'Lobby', pathImage:'/images/lobby.png', review:'“The hotel lobby is grand and elegant, creating a very classy first impression”' ,name:'Farrel Radithya Krishna'},
  ];

  const StyleRooms = [
    {
      id:1, title:'Single Room', 
      pathImage:'/images/single.png', 
      content:'Comfortable single bed, Minimalist modern interior, Full amenities with ambient lighting and a, small table, Large windows with beach view, Automatic air conditioning for maximum comfort.',
      space:'One person only.'
    },
    {
      id:2, title:'Double Room', 
      pathImage:'/images/double.png', 
      content:'This room features a comfortable double bed with premium bedding, complemented by a modern minimalist interior and extra seating. It offers full amenities, including ambient lighting and a cozy workspace, with large windows that showcase breathtaking beach views. Automatic air conditioning ensures an ideal climate for a relaxing stay.',
      space:'Perfect for two guests.'
    },
    {
      id:3, title:'Standard Room', 
      pathImage:'/images/standard.png', 
      content:'This room offers comfortable double or twin beds with quality bedding, a stylish modern interior, and ample space for relaxation. It includes full amenities like adjustable ambient lighting, a family-friendly seating area, large windows with stunning beach views, and automatic air conditioning for optimal comfort.',
      space:'Ideal for small families or up to three guests'
    },
    {
      id:4, title:'Deluxe Room', 
      pathImage:'/images/deluxe.png', 
      content:'This room features a premium king-size bed with family-friendly bedding options, an elegant interior with sophisticated decor, and full amenities, including ambient lighting, a spacious seating area, and a work desk. Enjoy panoramic beach views through large windows and advanced air conditioning for ultimate comfort.',
      space:'Ideal for large families, accommodating up to 6 guest'
    },
  ]
  
  function logout() {
    axios({
      url: 'http://localhost:8000/api/logout', // Sesuaikan URL dengan server Laravel Anda
      method: 'post',
      headers: {
        'Authorization': `Bearer ${store.token}`,
        'Content-Type': 'application/json'
      }
    }).then(response => {
      console.log(response.data) // hanya untuk pengembangan
      store.logout() // Panggil fungsi logout dari store
      router.push('/') // Arahkan ke halaman login setelah logout
    })
    .catch(error => {
      console.log('AJAX ' + error)
    })
  }

  function NavigateOrderUser () {
    router.push('/user-booking')
  }

</script>


<template>
    <div class="relative w-full ">

          <div class=" mt-24 flex justify-center text-center">
              <h1 class="text-xl md:text-3xl font-poppins">Enjoy the natural beauty <br> and various luxurious facilities from us</h1>
          </div>
          <p class="text-center mb-4 text-md md:text-xl text-slate-400">Best quality, best service</p>

        <div class="px-10  md:flex md:justify-center">
          <div class="flex min-w-full md:h-[500px] hover:border-[4px] hover:border-slate-600 transition-all duration-100 ease-in-out object-left-top rounded-xl">
            <Slider/>
          </div>
        </div>

        <!-- why -->
        <div class="md:flex w-full lg:px-20 relative mt-10 md:mt-28 bg-[#F7F7F9] py-10">
              <div class="md:w-1/2 flex justify-start mr-20 ml-20">
                <div>

                  <h2 class="font-poppins">Why Alicia Hotel? </h2>
                  <p class="text-[#7E7E7E] mt-8">Our hotel-related product offers a seamless, user-focused experience with intuitive design, personalized recommendations, and transparent pricing. Here’s the reason why users using our products:</p>
                  <div class="mt-20">
                    <p class="text-[#586474] ">Exceptional Service, Unmatched Hospitality</p>

                    <div class="w-3/4 mt-8">
                      <div class="bg-[#EDEFF0] p-2 rounded-lg items-center flex">
                      <p>Your Beachfront Escape Awaits</p>
                      </div>
                      <div class=" p-2 rounded-lg items-center flex">
                      <p>Luxury Living by the Sea</p>
                      </div>
                      <div class="bg-[#EDEFF0] p-2 rounded-lg items-center flex">
                      <p>Your Home Away from Home</p>
                      </div>
                      
                    </div >
                   
                  </div>
                </div>
                
              </div>    
              
              <div class="md:w-1/2">
                <img src="/images/whyAlicia.png" alt="" class="max-w-lg">
              </div>
          </div>

          <!-- Rating -->
           <div class="text-center mb-10 w-full mt-28">
              <h2 class="font-serif font-semibold">What People Say About Us?</h2>
              <div class="grid grid-cols-1  md:grid-cols-2 lg:grid-cols-3 gap-4 lg:px-14 mt-8">
                <CardRating 
                v-for = "card in userRating"
                :key="card.id"
                :title="card.title"
                :pathImage="card.pathImage"
                :review="card.review"
                :name="card.name"
                class="hover:scale-105 ease-in-out transform duration-200"
                />
              </div>
           </div>

          <!-- room Style  -->
           <div class="mt-28 text-center">
              <h1 class="font-poppins ">Discover Room Style</h1>    

              <div class=" w-full flex flex-wrap justify-center gap-10 mt-12"  >
                <CardRoom 
                v-for="room in StyleRooms"
                :key="room.id"
                :title="room.title"
                :pathImage="room.pathImage"
                :content="room.content"
                :space="room.space"
                class=""/>
              </div>
           </div> 

           <div class="mt-36 text-center  bg-[url('/images/homepageBottom.png')] w-full h-[70vh]">
              <h1 class="font-poppins">So, What Are <br> You Waiting For? </h1>
              <div class="flex justify-center mt-20 ">
                <button class="bg-[#4E85BD] text-white text-3xl p-2 rounded-lg" @click="NavigateOrderUser">
                  Book Now
                </button>
              </div>
            </div>
    </div>     
</template>

