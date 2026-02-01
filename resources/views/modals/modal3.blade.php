<div x-data x-cloak class="fixed inset-0 z-50 min-h-full overflow-y-auto bg-[#eee] text-black"
x-show="$wire.open1">

      <div class="flex justify-end">
         <div class="p-[10px]">
             <svg @click="$wire.open1=false"
             width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Cercle blanc -->
                            <circle cx="18" cy="18" r="18" fill="blue"/>
                            <!-- Croix noire -->
                            <path d="M12 12L24 24M24 12L12 24" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
         </div>
      </div>
         
      <div class="flex items-center justify-center p-4">
         @include('tlemcen::modals.tabhoraires')
      </div>
        
</div>