<template>
    <div class="mt-28 md:px-40">
        <div class="md:flex h-screen w-full px-4">

            <div class="flex flex-col md:w-1/2 w-full">
                <h1 class="font-poppins">Contact Our team</h1>
                <p class="text-[#808080]">We really love your input, suggestions and opinions about our hotel</p>
                <div class="md:mt-40 mt-10">

                    <div class="flex justify-between border-2 border-slate-500 rounded-lg p-2 shadow-lg">
                        <div>
                            <h4 class="font-poppins">Start Live Chat</h4>
                            <p class="text-[#808080]">Need urgent help? We are always here to help you</p>
                        </div>
                        <div class="flex justify-center items-center">
                            <img src="../../assets/Call.svg" alt="">
                        </div>
                    </div>

                    <div class="flex justify-between border-2 border-slate-500 rounded-lg p-2 shadow-lg mt-6">
                        <div>
                            <h4 class="font-poppins">Hard to Find</h4>
                            <p class="text-[#808080]">We always provide the best direction for you</p>
                        </div>
                        <div class="flex justify-center items-center">
                            <img src="../../assets/Maps.svg" alt="">
                        </div>
                    </div>

                </div>
            </div>

            <div class="md:w-1/2 md:ml-40 w-full mt-10 md:mt-0 ml-10">
                <form action="" class="border-2 border-slate-500 rounded-xl shadow-lg p-4 w-3/4">
                    <div class="flex flex-col">
                        <label for="name">Name</label>
                        <input type="text" placeholder="Enter Your Name" class="p-2 border-2 mt-2 rounded-lg">
                    </div>
                    <div class="flex flex-col mt-4">
                        <label for="name">Email</label>
                        <input type="email" placeholder="Enter Your Email" class="p-2  border-2 mt-2 rounded-lg">
                    </div>
                    <div class="flex flex-col mt-4">
                        <label for="name">Phone Number</label>
                        <input type="number" placeholder="Enter Your Phone Number" class="p-2  border-2 mt-2 rounded-lg">
                    </div>
                    <p class="mt-4 text-center font-poppins">By contacting us, you agree to our <br><span class="text-[#3883C8] underline">Terms of service</span> and <span class="text-[#3883C8] underline">Privacy Policy</span> </p>
                    <div class="flex justify-center mt-2 mb-3">
                        <button class="mt-2 bg-[#3883C8] text-white p-2 rounded-lg text-center text-lg font-poppins">Submit</button>
                    </div>
    
                </form>
            </div>
        </div>
    </div>
</template>