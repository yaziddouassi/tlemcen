<div class="h-[70px] bg-[#EEE] p-[10px] pt-[10px]"
    x-data="{
         rediriger1() {
           window.Livewire.navigate('/admin/rendez-vous');
           },
         rediriger2() {
             const aujourd_hui = new Date();
             const annee = aujourd_hui.getFullYear();
             const mois = aujourd_hui.getMonth() + 1; // getMonth() retourne 0-11
             const jour = aujourd_hui.getDate();
             
             const url = `/admin/rendez-vous/${annee}-${mois}-${jour}/journee`;
             
             // Utiliser Livewire navigate
             window.Livewire.navigate(url);
         },
          rediriger3() {
             const aujourd_hui = new Date();
             const annee = aujourd_hui.getFullYear();
             const mois = aujourd_hui.getMonth() + 1; // getMonth() retourne 0-11
             const jour = aujourd_hui.getDate();
             
             const url = `/admin/rendez-vous/${annee}-${mois}-${jour}/mois`;
             
             // Utiliser Livewire navigate
             window.Livewire.navigate(url);
         },

       }">

         <div class="max-w-[500px] m-auto  grid grid-cols-3 gap-3">
              
               <div @click="rediriger1()"
                class="h-[50px] bg-[blue] text-center text-white
             font-bold text-[20px] pt-[8px] rounded-[3px] cursor-pointer">Accueil</div>

              <div @click="rediriger2()"
              class="h-[50px] border-[blue] border-[2px] text-center text-[blue]
             font-bold text-[20px] pt-[8px] rounded-[3px] cursor-pointer">Journée</div>

              <div @click="rediriger3()"
               class="h-[50px] border-[blue] border-[2px] text-center text-[blue]
             font-bold text-[20px] pt-[8px] rounded-[3px] cursor-pointer">Mensuel</div>
            
         </div>

</div>