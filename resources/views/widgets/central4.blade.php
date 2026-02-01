<div class="p-[10px] pt-[20px]">

         <div class=" max-w-[1100px] m-auto min-[800px]:flex gap-[20px]">

           <div>
                <div class="w-full min-[800px]:min-w-[340px] min-[800px]:max-w-[340px] max-[799px]:mb-[15px]
                  border-[1px] border-[#bbb] rounded-[8px] p-[10px]">
                  @include('tlemcen::widgets.calendrier3')
                </div>
            </div>


            <div class="w-full">
               <div class="w-full min-h-[378px] 
                 border-[1px] border-[#bbb] rounded-[8px]">

                 <div class="p-[10px] grid gap-3 grid-cols-1  min-[800px]:grid-cols-2  min-[1100px]:grid-cols-3">
                   @foreach ($lesjours as $key => $jour)

                    @livewire('tlemcen.petitrdv',
                    ['ladate' => $jour->ladate,
                        'journee' => $jour->journee,
                         'annee' => $jour->annee ,
                         'mois' => $jour->mois ,
                         'jour' => $jour->jour],
                      key($jour->id) )
                   
                  @endforeach
                 </div>

                   <div class="p-[10px] pt-[20px]">
                 {{ $lesjours->links('tlemcen::pagination.rendezvous') }}
               </div>

               </div>
            </div>



         </div>

</div>