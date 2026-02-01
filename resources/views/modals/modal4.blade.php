<div x-data x-cloak class="fixed inset-0 z-50 min-h-full overflow-y-auto bg-[#eee] text-black"
x-show="$wire.open2">
      <div class="flex justify-end">
         <div class="p-[10px]">
             <svg @click="$wire.open2=false"
             width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Cercle blanc -->
                            <circle cx="18" cy="18" r="18" fill="blue"/>
                            <!-- Croix noire -->
                            <path d="M12 12L24 24M24 12L12 24" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
         </div>
      </div>
         
      <div class="flex items-center justify-center p-4">
            <div class="w-full max-w-[1000px]">


              <div class="w-full max-w-[340px] bg-white h-[50px] rounded-[25px] m-auto flex">
                  <div class="w-full pt-[4px] pl-[16px] pr-[8px]">
                      <input wire:model.live.debounce.500ms="search"
                         placeholder="Recherche ..."
                         type="text" 
                         class="w-full border border-white focus:border-white focus:ring-0 outline-none">
                  </div>

                   <div class="min-w-[40px] max-w-[40px] pt-[11px]">
                    <svg wire:click="annulerSearch"
                    width="28" height="28" viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg">
                       <circle cx="14" cy="14" r="14" fill="#2e78c2"/>
                         <line x1="9" y1="9" x2="19" y2="19" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"/>
                         <line x1="19" y1="9" x2="9" y2="19" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                   </div>
              </div>

              <div class="mt-[10px]">
                
                  <div class="p-[10px] grid gap-3 grid-cols-1 min-[600px]:grid-cols-2
                    min-[900px]:grid-cols-3">
                      @foreach ($users as $key => $user)
                      <div class="bg-white p-[10px] rounded-[5px] flex">
                         <div class="w-full">
                            <div>- {{$user->prenom}} {{$user->name}}</div>
                            <div class="pl-[11px]">{{$user->email}}</div>
                         </div>
                         <div class="min-w-[55px]">
                             <button wire:click="ajouterRdv({{$user->id}},'{{$user->name}}',
                             '{{$user->prenom}}','{{$user->email}}','{{$user->telephone}}',
                             '{{$user->adresse}}')"
                             class="bg-[blue] text-white px-3 py-2 rounded-[3px]">
                              RDV </button>
                         </div>
                      </div>
                      @endforeach
                  </div>

              </div>

               <div class="p-[10px] pt-[20px]">
                 {{ $users->links('tlemcen::pagination.rendezvous') }}
               </div>

            </div>
      </div>
        
</div>