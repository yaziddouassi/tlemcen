<div  class="livewire-myrendezvous-haut-div1"
x-data="{
   open : false ,
}">
                  
                  <div @click="open = !open"
                  class="text-center h-[44px] font-bold border-[1px]
                   border-[#555] rounded-[4px] pt-[5px] mb-[5px]">
                       {{$journee}}
                  </div>

                  <div x-show="open"
                  class="grid gap-3 grid-cols-3">
                  
 
                        @if($lesheures != '')

                         @foreach ($lesheures  as $lesheure)
                             
                         <div wire:click="valider('{{$lesheure->id}}')"
                         class="border-[1px] text-center border-[#999] py-1 rounded-[4px]">
                         
                                {{$lesheure->debut}}
                         
                         </div> 
                            
                        @endforeach 

                        @endif

                  </div>
       

     </div>



