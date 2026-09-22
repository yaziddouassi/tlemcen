<div
    x-data="{
        selectedDate: null,

        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),

        minDate: new Date(new Date().setHours(0, 0, 0, 0)),

        months: [
            'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Août',
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre'
        ],

        days: [
            'Lu',
            'Ma',
            'Me',
            'Je',
            'Ve',
            'Sa',
            'Di'
        ],

        /* ================= INIT ================= */

        init() {

            /*
             * Initialisation depuis Livewire.
             *
             * On passe toujours par parseDate() pour garder
             * une seule source de vérité (validation incluse).
             */
            if ($wire.currentdate) {

                const incoming = this.parseDate($wire.currentdate)

                if (incoming && incoming >= this.minDate) {
                    this.selectedDate = incoming
                    this.currentYear = incoming.getFullYear()
                    this.currentMonth = incoming.getMonth()
                }
            }

            /*
             * Surveillance de selectedDate
             *
             * IMPORTANT :
             * Alpine/Livewire peut parfois nous retourner une string
             * au lieu d'un objet Date.
             */
            this.$watch('selectedDate', (value) => {
                if (!value) {
                    return
                }

                /*
                 * Toujours convertir en Date
                 */
                if (!(value instanceof Date)) {
                    const converted = this.parseDate(value)

                    if (!converted) {
                        this.selectedDate = null
                        return
                    }

                    value = converted
                    this.selectedDate = converted
                }

                /*
                 * Vérification Date valide
                 */
                if (isNaN(value.getTime())) {
                    this.selectedDate = null
                    return
                }

                /*
                 * Normalisation de l'heure
                 */
                value.setHours(0, 0, 0, 0)

                /*
                 * Impossible de sélectionner une date passée
                 */
                if (value < this.minDate) {
                    return
                }

                /*
                 * Synchronisation avec Livewire
                 */
                const iso = this.isoDate()

                if (iso && iso !== String($wire.currentdate ?? '')) {
                    $wire.set('currentdate', iso)
                }
            })

            /*
             * Synchronisation si currentdate change côté Livewire
             * (navigation, retour arrière, mise à jour externe...)
             */
            this.$watch('$wire.currentdate', (value) => {
                if (!value) {
                    return
                }

                const incoming = this.parseDate(value)

                if (!incoming) {
                    return
                }

                if (incoming < this.minDate) {
                    return
                }

                /*
                 * Évite de réassigner inutilement la même date
                 */
                if (
                    this.selectedDate instanceof Date &&
                    this.selectedDate.getTime() === incoming.getTime()
                ) {
                    return
                }

                this.selectedDate = incoming

                this.currentYear = incoming.getFullYear()
                this.currentMonth = incoming.getMonth()
            })

            /*
             * Filet de sécurité supplémentaire : si le composant
             * Alpine est recréé lors d'une navigation wire:navigate,
             * on force une resynchro complète.
             */
            document.addEventListener('livewire:navigated', () => {

                if (!$wire.currentdate) {
                    return
                }

                const incoming = this.parseDate($wire.currentdate)

                if (incoming && incoming >= this.minDate) {
                    this.selectedDate = incoming
                    this.currentYear = incoming.getFullYear()
                    this.currentMonth = incoming.getMonth()
                }
            })
        },

        /* ================= DATE PARSER ================= */

        parseDate(value) {
            if (!value) {
                return null
            }

            /*
             * Déjà un Date
             */
            if (value instanceof Date) {
                const date = new Date(value.getTime())
                date.setHours(0, 0, 0, 0)

                return isNaN(date.getTime()) ? null : date
            }

            /*
             * String YYYY-MM-DD (ou YYYY-M-D)
             *
             * On évite new Date('YYYY-MM-DD')
             * car cela peut être interprété en UTC et décaler
             * la date d'un jour selon le fuseau horaire local.
             *
             * NOTE : un seul antislash devant \d, on est dans un
             * regex littéral JS, pas dans une string échappée.
             */
            if (typeof value === 'string') {
                const match = value.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/)

                if (match) {
                    const y = Number(match[1])
                    const m = Number(match[2])
                    const d = Number(match[3])

                    const date = new Date(y, m - 1, d)
                    date.setHours(0, 0, 0, 0)

                    /*
                     * Vérification supplémentaire pour éviter
                     * les dates du type 2026-02-31.
                     */
                    if (
                        date.getFullYear() !== y ||
                        date.getMonth() !== m - 1 ||
                        date.getDate() !== d
                    ) {
                        return null
                    }

                    return date
                }

                /*
                 * Autres formats éventuellement reçus.
                 * Fallback volontairement en dernier recours.
                 */
                const parsed = new Date(value)

                if (!isNaN(parsed.getTime())) {
                    parsed.setHours(0, 0, 0, 0)
                    return parsed
                }
            }

            return null
        },

        /* ================= DATE HELPERS ================= */

        daysInMonth() {
            return new Date(
                this.currentYear,
                this.currentMonth + 1,
                0
            ).getDate()
        },

        firstDayOffset() {
            /*
             * JavaScript :
             * Dimanche = 0
             * Lundi    = 1
             * ...
             * Samedi   = 6
             *
             * Notre calendrier commence lundi.
             */
            const day = new Date(
                this.currentYear,
                this.currentMonth,
                1
            ).getDay()

            return day === 0 ? 6 : day - 1
        },

        formattedDate() {
            if (!(this.selectedDate instanceof Date)) {
                return '—'
            }

            if (isNaN(this.selectedDate.getTime())) {
                return '—'
            }

            return this.selectedDate.toLocaleDateString('fr-FR', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            })
        },

        isoDate() {
            if (!(this.selectedDate instanceof Date)) {
                return ''
            }

            if (isNaN(this.selectedDate.getTime())) {
                return ''
            }

            const y = this.selectedDate.getFullYear()

            const m = String(
                this.selectedDate.getMonth() + 1
            ).padStart(2, '0')

            const d = String(
                this.selectedDate.getDate()
            ).padStart(2, '0')

            return `${y}-${m}-${d}`
        },

        /* ================= STATES ================= */

        isSelected(day) {
            if (!(this.selectedDate instanceof Date)) {
                return false
            }

            return (
                day === this.selectedDate.getDate() &&
                this.currentMonth === this.selectedDate.getMonth() &&
                this.currentYear === this.selectedDate.getFullYear()
            )
        },

        isPast(day) {
            const date = new Date(
                this.currentYear,
                this.currentMonth,
                day
            )

            date.setHours(0, 0, 0, 0)

            return date < this.minDate
        },

        isToday(day) {
            const today = new Date()
            today.setHours(0, 0, 0, 0)

            return (
                day === today.getDate() &&
                this.currentMonth === today.getMonth() &&
                this.currentYear === today.getFullYear()
            )
        },

        /* ================= ACTIONS ================= */

        selectDate(day) {
            if (this.isPast(day)) {
                return
            }

            const date = new Date(
                this.currentYear,
                this.currentMonth,
                day
            )

            date.setHours(0, 0, 0, 0)

            this.selectedDate = date
        },

        prevMonth() {
            if (!this.canGoPrevMonth()) {
                return
            }

            this.currentMonth--

            if (this.currentMonth < 0) {
                this.currentMonth = 11
                this.currentYear--
            }
        },

        nextMonth() {
            this.currentMonth++

            if (this.currentMonth > 11) {
                this.currentMonth = 0
                this.currentYear++
            }
        },

        canGoPrevMonth() {
            const previousMonth = new Date(
                this.currentYear,
                this.currentMonth - 1,
                1
            )

            const minimumMonth = new Date(
                this.minDate.getFullYear(),
                this.minDate.getMonth(),
                1
            )

            return previousMonth >= minimumMonth
        }
    }"
    class="w-80 mx-auto select-none"
>

    <!-- ================= DATE SÉLECTIONNÉE ================= -->

    <div class="text-center text-sm text-gray-600 mb-2">
        Date choisie :
        <strong x-text="formattedDate()"></strong>
    </div>


    <!-- ================= HEADER ================= -->

    <div
        class="flex items-center justify-between px-3 py-2
               bg-gray-100 rounded-t-lg border-b"
    >

        <!-- Mois précédent -->

        <button
            type="button"
            @click="prevMonth()"
            :disabled="!canGoPrevMonth()"
            :class="{
                'opacity-30 cursor-not-allowed': !canGoPrevMonth()
            }"
            class="p-2 rounded-full hover:bg-gray-200"
        >
            ←
        </button>


        <!-- Mois actuel -->

        <div class="text-lg font-semibold">
            <span x-text="months[currentMonth]"></span>
            <span x-text="currentYear"></span>
        </div>


        <!-- Mois suivant -->

        <button
            type="button"
            @click="nextMonth()"
            class="p-2 rounded-full hover:bg-gray-200"
        >
            →
        </button>

    </div>


    <!-- ================= CALENDAR ================= -->

    <div class="bg-white rounded-b-lg shadow p-4">


        <!-- JOURS DE LA SEMAINE -->

        <div
            class="grid grid-cols-7 gap-1 text-center
                   text-xs font-medium text-gray-500 mb-2"
        >

            <template
                x-for="d in days"
                :key="d"
            >
                <div x-text="d"></div>
            </template>

        </div>


        <!-- JOURS -->

        <div
            class="grid grid-cols-7 gap-1
                   text-center text-sm"
        >

            <!-- Cases vides avant le 1er -->

            <template
                x-for="i in firstDayOffset()"
                :key="'empty-' + i"
            >
                <div class="h-10"></div>
            </template>


            <!-- Jours du mois -->

            <template
                x-for="day in daysInMonth()"
                :key="day"
            >

                <button
                    type="button"
                    @click="selectDate(day)"
                    :disabled="isPast(day)"

                    class="h-10 w-10 rounded-full
                           flex items-center justify-center
                           transition focus:outline-none"

                    :class="{

                        /*
                         * Date sélectionnée
                         */
                        'bg-indigo-600 text-white font-bold shadow':
                            isSelected(day),

                        /*
                         * Date d'aujourd'hui
                         */
                        'ring-2 ring-indigo-300':
                            isToday(day) && !isSelected(day),

                        /*
                         * Date passée
                         */
                        'text-gray-300 cursor-not-allowed':
                            isPast(day),

                        /*
                         * Date disponible
                         */
                        'hover:bg-gray-100':
                            !isPast(day) && !isSelected(day)

                    }"
                >

                    <span x-text="day"></span>

                </button>

            </template>

        </div>

    </div>


    <!-- ================= INPUT LIVEWIRE ================= -->

    <input
        type="hidden"
        name="selected_date"
        :value="isoDate()"
    >

</div>