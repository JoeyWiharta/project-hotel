<template>
    <div class="mt-28 w-full mb-20">
    <h1 class="text-center font-poppins mb-10 md:hidden block">Order History</h1>
      <div class="md:flex w-full justify-center items-center ">
        <div class="lg:w-1/2 w-full">
            <img src="/images/order.png" alt="" class=" lg:mr-20 w-full max-w-30">
        </div>
        

        <div class="lg:w-1/2 mr-20 w-full px-10 lg:px-0">
            <div class="w-full">
                <h2>Your Transaction</h2>
                <p>Explore your transaction activities while in our hotel</p>
                <div v-for="order in sortedOrders" :key="order.id" class="order-card mt-4 w-full rounded-xl shadow-xl" >
                    <div class=" w-full flex ">

                        <div class="w-1/3">
                            <p class="font-poppins text-lg md:text-2xl font-bold">{{ order.type }}</p>
                            <p class="font-bold md:text-lg">{{ order.roomnumber }}</p>
                            <p class="text-[#1366D9] font-poppins font-bold md:text-lg">{{ order.checkin_date }}</p>
                        </div>

                        <div class="w-1/3 flex justify-center items-center">
                            <img src="../../assets/orderIcon.svg" alt="">
                        </div>

                        <div class="w-1/3">
                            <p class="font-poppins text-lg md:text-2xl font-bold">{{ order.type }}</p>
                            <p class="font-bold md:text-lg">{{ order.roomnumber }}</p>
                            <p  class="text-[#1366D9] font-bold md:text-lg font-poppins" >{{ order.checkout_date }}</p>
                        </div>

                    
                        
                    </div>
                <div class="flex justify-between mt-4">
                    <p class="md:text-xl"><strong class="font-poppins ">Status:</strong> <span class="text-[#357C3C] font-bold font-poppins">{{ order.order_status }}</span></p>
                    <p class="lg:mr-24 md:text-xl"> <span class="text-[#F72C5B] font-bold font-poppins">{{ formatPrice(order.total_price) }}</span></p>
                </div>
                    
                </div>
            </div>
        
      </div>


      </div>
      
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import axios from 'axios';
  import { useUserStore } from '@/stores/counter';
  
  // Setup store and orders data
  const store = useUserStore();
  const orders = ref([]);
  
  // Fetch orders on mount
  onMounted(async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/history/user', {
        headers: {
          'Authorization': `Bearer ${store.token}`,
          'Content-Type': 'application/json',
        }
      });
      orders.value = response.data.data;
    } catch (error) {
      console.error('Error fetching order history:', error);
    }
  });
  
  // Computed property for sorted orders
  const sortedOrders = computed(() => {
    return orders.value.slice().sort((a, b) => {
      if (a.order_status === 'Booked' && b.order_status !== 'Booked') return -1;
      if (a.order_status !== 'Booked' && b.order_status === 'Booked') return 1;
      return 0;
    });
  });

  function formatPrice(price) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 2, // Menampilkan dua digit desimal
    maximumFractionDigits: 2
  }).format(price);
}

  </script>
  
  <style scoped>

  .order-card {
    border: 1px solid #ccc;
    padding: 16px;
    background-color: #f9f9f9;
    flex: 1 1 300px; /* Adjust card size */
  }
  .order-card-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  </style>