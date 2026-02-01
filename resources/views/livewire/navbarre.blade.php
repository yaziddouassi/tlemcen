<div x-data class="h-[70px]  bg-[#1D4ED8] flex">

      <div class="min-w-[50px] min-w-[50px] pt-[11px]">
          <svg @click="$store.navbarre1.toggle1()"
          width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
  <line x1="8" y1="12" x2="32" y2="12" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
  <line x1="8" y1="20" x2="32" y2="20" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
  <line x1="8" y1="28" x2="32" y2="28" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
</svg>


      </div>


       <div class="w-full pt-[7px]">
         <div class="text-white text-center text-[24px] font-bold">Rendez-vous </div>
         <div class="text-white text-center mt-[-10px]">Appointement</div>
       </div>

       <div class="min-w-[45px] min-w-[45px] pt-[11px]"
       @click="$store.navbarre1.toggle2()">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="20" cy="14" r="6" stroke="#ffffff" stroke-width="3"/>
  <path d="M8 32c0-6 6-10 12-10s12 4 12 10" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
</svg>

       </div>
          

</div>