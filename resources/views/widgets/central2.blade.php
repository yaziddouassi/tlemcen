<div class="p-[10px] pt-[20px]"
 x-data="{
   openModal1(a, b ,c) {
      $wire.heureDebut = a ;
      $wire.heureFin = b ;
      $wire.heureId = c ;
      $wire.open2 = true
   },
    openModal2(a, b , c) {
      $wire.heureDebut = a ;
      $wire.heureFin = b ;
      $wire.heureId = c ;
      $wire.open3 = true
   },
 }"
>

         <div class=" max-w-[1100px] m-auto min-[800px]:flex gap-[20px]">

           <div>
                <div class="w-full min-[800px]:min-w-[340px] min-[800px]:max-w-[340px] max-[799px]:mb-[15px]
                  border-[1px] border-[#bbb] rounded-[8px] p-[10px]">
                   @include('tlemcen::widgets.calendrier1')
                </div>
            </div>


            <div class="w-full">
               <div class="w-full min-h-[378px] 
                 border-[1px] border-[#bbb] rounded-[8px]">

                  <div class="flex justify-center">
                    <div class="mt-[15px]">
                      <svg @click="$wire.open1=true"
                       width="50" height="50" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                         <circle cx="25" cy="25" r="23" fill="none" stroke="darkblue" stroke-width="2"/>
                         <line x1="25" y1="15" x2="25" y2="35" stroke="darkblue" stroke-width="2"/>
                         <line x1="15" y1="25" x2="35" y2="25" stroke="darkblue" stroke-width="2"/>
                      </svg>
                    </div>
                 </div>  

                 <div class="p-[10px] grid gap-3  grid-cols-1  min-[800px]:grid-cols-2  min-[1100px]:grid-cols-3">
                 @foreach ($lesheures as $key => $heure)
                     @if($heure->userid == 0)
                      <div>
                      <div class="bg-[#ddd] rounded-[5px] pt-[10px] pb-[10px]">
                        <div class="text-center flex justify-center">
                          <div><span class="material-icons">alarm<span></div>
                          <div class="pl-[10px]"></span>{{ $heure->debut }} - {{ $heure->fin }}</div>
                        </div>
                        <div class="pl-[10px] pr-[10px]">
                            <div class="cursor-pointer"
                            @click="openModal1('{{$heure->debut}}','{{$heure->fin}}','{{$heure->id}}')"
                            >- Ajouter un rendez-vous</div>
                            <div class="cursor-pointer"
                            wire:confirm="voulez vous supprimer l heure?"
                            wire:click="supprimerHeure2({{$heure->id}})"
                            >- Supprimer l'heure</div>
                        </div>
                      </div>
                      </div>
                     @endif

                      @if($heure->userid != 0)
                      <div>
                      <div class="bg-[#1D4ED8] text-white rounded-[5px] pb-[20px]">
                        <div class="text-center pt-[10px] pb-[10px] flex justify-center">
                          <div><span class="material-icons">alarm<span></div>
                          <div class="pl-[10px]"></span>{{ $heure->debut }} - {{ $heure->fin }}</div>
                        </div>

                        <div class="pl-[20px] pr-[20px] text-center">
                          {{$heure->userprenom}}  {{$heure->usernom}}
                        </div>
                        <div class="pl-[20px] pr-[20px] pb-[10px] text-center">
                          {{$heure->usermail}}  
                        </div>
                        <div class="pl-[10px] pr-[10px]">
                            <div class="cursor-pointer"
                            @click="openModal2('{{$heure->debut}}','{{$heure->fin}}','{{$heure->id}}')"
                            >- Modifier le rendez-vous</div>
                            <div class="cursor-pointer"
                             wire:confirm="voulez vous supprimer l heure?"
                             wire:click="supprimerHeure({{$heure->id}})"
                            >- Supprimer l'heure</div>
                            <div class="cursor-pointer"
                            wire:confirm="voulez vous supprimer le rendez-vous?"
                            wire:click="supprimerRdv({{$heure->id}})">- Supprimer le rendez-vous</div>
                        </div>
                      </div>
                      </div>
                     @endif
                 @endforeach
                 </div>

               </div>
            </div>



         </div>

</div>