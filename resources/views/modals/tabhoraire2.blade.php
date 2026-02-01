<div 
    x-data="{
        nbLignes: 1,
        addLines() {
            for (let i = 0; i < this.nbLignes; i++) {
                $wire.tabHoraires.push({
                    debut: '',
                    fin: ''
                })
            }
        },
        removeLine(index) {
            if ($wire.tabHoraires.length > 1) {
                $wire.tabHoraires.splice(index, 1)
            }
        },
        addHoraires() {
          $wire.addHoraires();
        },
    }"
>
    <!-- Controls -->
    <div class="flex gap-2 mb-4">
        <div class="w-full">
            <input 
                type="number"
                min="1"
                max="30"
                x-model.number="nbLignes"
                class="border rounded px-2 py-1 w-full h-[40px]"
            >
        </div>
        <div class="w-full">
            <button
                type="button"
                @click="addLines"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded w-full"
            >
              Ajouter Lignes
            </button>
        </div>
    </div>

    <!-- Horaires -->
    <template x-for="(item, index) in $wire.tabHoraires" :key="index">
        <div class="mb-4">
            <div class="flex gap-4 items-center">
               
                <div>
                    <label>Début :</label>
                    <input
                        type="time"
                        x-model="$wire.tabHoraires[index].debut"
                    >
                </div>

                <div>
                    <label>Fin :</label>
                    <input
                        type="time"
                        x-model="$wire.tabHoraires[index].fin"
                    >
                </div>

                <div>
                    <button
                        type="button"
                        @click="removeLine(index)"
                        :disabled="$wire.tabHoraires.length === 1"
                        :class="$wire.tabHoraires.length === 1 ? 'bg-gray-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700'"
                        class="text-white px-3 py-2 rounded">
                        x
                    </button>
                </div>
            </div>
        </div>
    </template>

    <div>
        <button @click="addHoraires()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-3 rounded w-full">
            Ajouter Heures</button>
    </div>


</div>