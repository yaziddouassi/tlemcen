<div class="p-[10px] pt-[20px]"
 x-data="{
    rediriger(annee,mois,jour) {
           const url = `/admin/rendez-vous/${annee}-${mois}-${jour}/journee`;
             
             // Utiliser Livewire navigate
             window.Livewire.navigate(url);
    }
 }">

         <div class=" max-w-[1100px] m-auto min-[800px]:flex gap-[20px]">

           <div>
                <div class="w-full min-[800px]:min-w-[340px] min-[800px]:max-w-[340px] max-[799px]:mb-[15px]
                  border-[1px] border-[#bbb] rounded-[8px] p-[10px]">
                   @include('tlemcen::widgets.calendrier2')
                </div>
            </div>


            <div class="w-full">
               <div class="w-full min-h-[378px] 
                 border-[1px] border-[#bbb] rounded-[8px]">

                 <div class="p-[10px] grid gap-3 grid-cols-1  min-[800px]:grid-cols-2  min-[1100px]:grid-cols-3">
                   @foreach ($lesjours as $key => $jour)
                    <div @click="rediriger({{$jour->annee}},{{$jour->mois}},{{$jour->jour}})"
                    
                    class="p-[10px] bg-white text-center border-[1px] border-[#BBB] rounded-[5px] cursor-pointer">
                         <div class="font-bold">
                          {{$jour->journee}}
                         </div>
                         <div class="text-[darkblue]">
                          Horaires disponibles : {{$jour->nbheuredispo}}
                         </div>
                         <div class="text-[darkblue]">
                          Horaires reservées : {{$jour->nbheureserve}}
                         </div>
                    </div>
                  @endforeach
                 </div>

       

               </div>
            </div>



         </div>

</div>