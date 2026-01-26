 <!-- Top Things to Do in Barcelona -->
 <section class="max-w-7xl mx-auto px-4 pt-16 scroll-animate fade-up">
     <h2 class="text-[35px] font-bold mb-5">Top Things to Do in Barcelona</h2>

     <!-- Filter Buttons -->
     <div class="flex flex-wrap gap-3 mb-6">
         <button class="flex items-center space-x-2 px-6 py-2 bg-white text-gray-700 border boorder-gray-300 rounded-full font-medium hover:bg-gray-900 hover:text-white transition">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
             </svg>
             <span>Explore</span>
         </button>
         <button class="flex items-center space-x-2 px-6 py-2 bg-white text-gray-700 border boorder-gray-300 rounded-full font-medium hover:bg-gray-900 hover:text-white transition">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
             </svg>
             <span>Beach</span>
         </button>
         <button class="flex items-center space-x-2 px-6 py-2 bg-white text-gray-700 border boorder-gray-300 rounded-full font-medium hover:bg-gray-900 hover:text-white transition">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
             </svg>
             <span>Museum</span>
         </button>
         <button class="flex items-center space-x-2 px-6 py-2 bg-white text-gray-700 border boorder-gray-300 rounded-full font-medium hover:bg-gray-900 hover:text-white transition">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
             </svg>
             <span>Show</span>
         </button>
         <button class="flex items-center space-x-2 px-6 py-2 bg-white text-gray-700 border boorder-gray-300 rounded-full font-medium hover:bg-gray-900 hover:text-white transition">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
             </svg>
             <span>Food</span>
         </button>
         <button class="flex items-center space-x-2 px-6 py-2 bg-white text-gray-700 border boorder-gray-300 rounded-full font-medium hover:bg-gray-900 hover:text-white transition">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
             </svg>
             <span>Night Life</span>
         </button>
     </div>

     <!-- Attractions Grid -->
     <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-16">
         <!-- Sagrada Familia -->
         <div class="group cursor-pointer">
             <div class="relative rounded-2xl overflow-hidden mb-3 aspect-square">
                 <img src="https://images.unsplash.com/photo-1583422409516-2895a77efded?w=400&q=80" alt="Sagrada Familia" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
             </div>
             <h3 class="font-semibold text-sm">Sagrada Familia</h3>
         </div>

         <!-- Park Guell -->
         <div class="group cursor-pointer">
             <div class="relative rounded-2xl overflow-hidden mb-3 aspect-square">
                 <img src="https://images.unsplash.com/photo-1539037116277-4db20889f2d4?w=400&q=80" alt="Park Guell" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
             </div>
             <h3 class="font-semibold text-sm">Park Guell</h3>
         </div>

         <!-- Casa Mila -->
         <div class="group cursor-pointer">
             <div class="relative rounded-2xl overflow-hidden mb-3 aspect-square">
                 <img src="https://images.unsplash.com/photo-1523531294919-4bcd7c65e216?w=400&q=80" alt="Casa Mila" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
             </div>
             <h3 class="font-semibold text-sm">Casa Mila</h3>
         </div>

         <!-- Sacred Heart Temple -->
         <div class="group cursor-pointer">
             <div class="relative rounded-2xl overflow-hidden mb-3 aspect-square">
                 <img src="https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=400&q=80" alt="Sacred Heart Temple" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
             </div>
             <h3 class="font-semibold text-sm">Sacred Heart Temple</h3>
         </div>

         <!-- Arc de Triomf -->
         <div class="group cursor-pointer">
             <div class="relative rounded-2xl overflow-hidden mb-3 aspect-square">
                 <img src="https://images.unsplash.com/photo-1511527661048-7fe73d85e9a4?w=400&q=80" alt="Arc de Triomf" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
             </div>
             <h3 class="font-semibold text-sm">Arc de Triomf</h3>
         </div>

         <!-- Casa Batllo -->
         <div class="group cursor-pointer">
             <div class="relative rounded-2xl overflow-hidden mb-3 aspect-square">
                 <img src="https://images.unsplash.com/photo-1523531294919-4bcd7c65e216?w=400&q=80" alt="Casa Batllo" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
             </div>
             <h3 class="font-semibold text-sm">Casa Batllo</h3>
         </div>
     </div>
 </section>