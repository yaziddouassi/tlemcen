div>

 @if(!$client)
  <div class="m-auto max-w-[400px]">

    <form wire:submit="valider">
      <div class="text-center font-bold text-[22px] text-[darkblue]">
        Completer le formulaire
      </div>
      @include('tlemcen::widgets.inputText',[
       'required' => true ,
       'label' => 'Prenom' ,
       'field' => 'prenom' ,
      ])

       @include('tlemcen::widgets.inputText',[
       'required' => true ,
       'label' => 'Telephone' ,
       'field' => 'telephone' ,
      ])

       @include('tlemcen::widgets.inputText',[
       'required' => true ,
       'label' => 'Adresse' ,
       'field' => 'adresse' ,
      ])

      <div class="mt-[15px]">
        <button class="w-full h-[48px] rounded-[5px] bg-[blue] text-[white] text-[20px]
               font-bold">
            Valider
        </button>
      </div>

     </form> 
  </div>
  @endif

   @if($client)
     @include('tlemcen::widgets.central4')
   @endif
</div>