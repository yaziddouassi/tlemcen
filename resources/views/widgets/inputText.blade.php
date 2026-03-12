<div>
   <div class="w-full mb-[5px]">
      <span class="text-black font-bold">{{$label}}</span><span class="text-[red]">@if($required==true)*@endif</span> 
   </div>
   <div>
      <input type="text" wire:model="{{$field}}" class="w-full rounded-[4px] h-[50px]
     border-gray-800 border-[1px] ">
   </div>

   @error("field")
   <div class="text-[red] pt-[5px]">
        <span class="error">{{ $message }}</span> 
   </div>
   @enderror

</div>