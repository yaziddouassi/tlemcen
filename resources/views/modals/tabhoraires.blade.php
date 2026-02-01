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

        addHoraires(a) {
            const horaires = $wire.tabHoraires

            const debuts = new Set()
            const fins = new Set()

            for (let i = 0; i < horaires.length; i++) {
                const { debut, fin } = horaires[i]
                const ligne = i + 1

                // 1. Champs obligatoires
                if (!debut || !fin) {
                    alert(`Ligne ${ligne} : Début et fin sont obligatoires`)
                    return
                }

                // 2. Début ≠ Fin
                if (debut === fin) {
                    alert(`Ligne ${ligne} : l'heure de fin doit être différente du début`)
                    return
                }

                // 3. Fin > Début
                if (fin <= debut) {
                    alert(`Ligne ${ligne} : l'heure de fin doit être supérieure au début`)
                    return
                }

                // 4. Début unique
                if (debuts.has(debut)) {
                    alert(`Ligne ${ligne} : l'heure de début ${debut} est déjà utilisée`)
                    return
                }

                // 5. Fin unique
                if (fins.has(fin)) {
                    alert(`Ligne ${ligne} : l'heure de fin ${fin} est déjà utilisée`)
                    return
                }

                debuts.add(debut)
                fins.add(fin)
            }

            // ✅ Tout est valide → envoi serveur
            $wire.addHoraires(a)
        },
    }"
 class="w-full max-w-[1100px] pb-[50px]">
    <!-- Controls -->
    <div class="flex gap-2 mb-4 w-[320px]">
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
                class="bg-blue-600 hover:bg-blue-700 text-white px-1 py-2 rounded w-full"
            >
                Ajouter Lignes
            </button>
        </div>
    </div>

    <!-- Horaires -->
    <div class="grid   grid-cols-1  min-[600px]:grid-cols-2  min-[800px]:grid-cols-3 
     min-[1100px]:grid-cols-4">
    <template x-for="(item, index) in $wire.tabHoraires" :key="index">
        <div class="mb-4">
            <div class="flex gap-4 items-center">
                
                <div>
                    <label class="block text-sm">Début :</label>
                    <input
                        type="time"
                        x-model="$wire.tabHoraires[index].debut"
                        class="border rounded px-2 py-1"
                    >
                </div>

                <div>
                    <label class="block text-sm">Fin :</label>
                    <input
                        type="time"
                        x-model="$wire.tabHoraires[index].fin"
                        class="border rounded px-2 py-1"
                    >
                </div>

                <div class="mt-5">
                    <button
                        type="button"
                        @click="removeLine(index)"
                        :disabled="$wire.tabHoraires.length === 1"
                        :class="$wire.tabHoraires.length === 1 
                            ? 'bg-gray-300 cursor-not-allowed' 
                            : 'text-red-600 font-bold border-[2px] font-bold border-red-600 hover:bg-red-700 hover:text-white'"
                        class="text-white px-3 py-2 rounded"
                    >
                        ✕
                    </button>
                </div>
            </div>
        </div>
    </template>
    </div>
    <!-- Submit -->
    <div class="w-[320px]">
        <button 
            type="button"
            @click="addHoraires('non')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-3 rounded w-full"
        >
            Ajouter Heures
        </button>
    </div>

     <div class="mt-[10px] w-[320px]">
        <button 
            type="button"
            @click="addHoraires('oui')"
            class="bg-blue-900 hover:bg-blue-700 text-white px-3 py-3 rounded w-full"
        >
            Ajouter Heures et activer
        </button>
    </div>


</div>
