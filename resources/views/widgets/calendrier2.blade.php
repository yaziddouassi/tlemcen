<div
    x-data="{
        selectedDate: null,

        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),

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
             * Fonction de synchro réutilisable.
             *
             * Formats acceptés :
             * 2026-1-24
             * 2026-01-24
             */
            const sync = (value) => {

                if (!value) {
                    return
                }

                const incoming = this.parseDate(value)

                if (incoming) {
                    this.selectedDate = incoming

                    this.currentYear = incoming.getFullYear()
                    this.currentMonth = incoming.getMonth()
                }
            }


           
            this.$watch('$wire.currentdate', (value) => {
                sync(value)
            })


            document.addEventListener(
                'livewire:navigated',
                () => sync($wire.currentdate)
            )
        },


        /* ================= DATE PARSER ================= */

        parseDate(value) {

            if (!value) {
                return null
            }


            /*
             * Si c'est déjà un objet Date
             */
            if (value instanceof Date) {

                const date = new Date(value.getTime())

                date.setHours(0, 0, 0, 0)

                return isNaN(date.getTime())
                    ? null
                    : date
            }


            /*
             * String YYYY-M-D
             * ou YYYY-MM-DD
             */
            if (typeof value === 'string') {

                const match = value.match(
                    /^(\d{4})-(\d{1,2})-(\d{1,2})$/
                )

                if (match) {

                    const y = Number(match[1])
                    const m = Number(match[2])
                    const d = Number(match[3])

                    const date = new Date(y, m - 1, d)

                    date.setHours(0, 0, 0, 0)


                    /*
                     * Vérification de la date.
                     *
                     * Empêche par exemple :
                     * 2026-2-31
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

            const day = new Date(
                this.currentYear,
                this.currentMonth,
                1
            ).getDay()


            /*
             * JavaScript :
             *
             * Dimanche = 0
             * Lundi    = 1
             * ...
             * Samedi   = 6
             *
             * Calendrier commençant lundi.
             */
            return day === 0
                ? 6
                : day - 1
        },


        formattedDate() {

            if (!(this.selectedDate instanceof Date)) {
                return '—'
            }

            if (isNaN(this.selectedDate.getTime())) {
                return '—'
            }

            return this.selectedDate.toLocaleDateString(
                'fr-FR',
                {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }
            )
        },


        /*
         * Format utilisé dans l'URL :
         *
         * 2026-1-24
         *
         * Sans zéro devant le mois et le jour.
         */
        isoDate() {

            if (!(this.selectedDate instanceof Date)) {
                return ''
            }

            if (isNaN(this.selectedDate.getTime())) {
                return ''
            }

            const y =
                this.selectedDate.getFullYear()

            const m =
                this.selectedDate.getMonth() + 1

            const d =
                this.selectedDate.getDate()

            return `${y}-${m}-${d}`
        },


        /* ================= STATES ================= */

        isSelected(day) {

            if (!(this.selectedDate instanceof Date)) {
                return false
            }

            if (isNaN(this.selectedDate.getTime())) {
                return false
            }

            return (
                day === this.selectedDate.getDate() &&
                this.currentMonth === this.selectedDate.getMonth() &&
                this.currentYear === this.selectedDate.getFullYear()
            )
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

            const date = new Date(
                this.currentYear,
                this.currentMonth,
                day
            )

            date.setHours(0, 0, 0, 0)

            /*
             * On met d'abord à jour la date sélectionnée.
             */
            this.selectedDate = date


            /*
             * Puis on redirige explicitement.
             *
             * PAS de $watch ici.
             */
            this.rediriger2()
        },


        prevMonth() {

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


        /* ================= REDIRECTION ================= */

        rediriger2() {

            /*
             * Sécurité
             */
            if (!(this.selectedDate instanceof Date)) {
                return
            }

            if (isNaN(this.selectedDate.getTime())) {
                return
            }


            const annee =
                this.selectedDate.getFullYear()

            const mois =
                this.selectedDate.getMonth() + 1

            const jour =
                this.selectedDate.getDate()


            /*
             * URL :
             *
             * /admin/rendez-vous/2026-1-24/mois
             */
            const url =
                `/admin/rendez-vous/${annee}-${mois}-${jour}/mois`


            /*
             * Navigation Livewire
             */
            window.Livewire.navigate(url)
        }
    }"

    class="w-80 mx-auto select-none"
>


    <!-- ================= DATE SÉLECTIONNÉE ================= -->

    <div
        class="text-center text-sm text-gray-600 mb-2"
    >

        Date choisie :

        <strong
            x-text="formattedDate()"
        ></strong>

    </div>


    <!-- ================= HEADER ================= -->

    <div
        class="
            flex items-center justify-between
            px-3 py-2
            bg-gray-100
            rounded-t-lg
            border-b
        "
    >

        <!-- Mois précédent -->

        <button
            type="button"
            @click="prevMonth()"
            class="
                p-2
                rounded-full
                hover:bg-gray-200
                focus:outline-none
            "
        >
            ←
        </button>


        <!-- Mois / Année -->

        <div
            class="text-lg font-semibold"
        >

            <span
                x-text="months[currentMonth]"
            ></span>

            <span
                x-text="currentYear"
            ></span>

        </div>


        <!-- Mois suivant -->

        <button
            type="button"
            @click="nextMonth()"
            class="
                p-2
                rounded-full
                hover:bg-gray-200
                focus:outline-none
            "
        >
            →
        </button>

    </div>


    <!-- ================= CALENDAR ================= -->

    <div
        class="
            bg-white
            rounded-b-lg
            shadow
            p-4
        "
    >


        <!-- ================= JOURS ================= -->

        <div
            class="
                grid grid-cols-7
                gap-1
                text-center
                text-xs
                font-medium
                text-gray-500
                mb-2
            "
        >

            <template
                x-for="d in days"
                :key="d"
            >

                <div
                    x-text="d"
                ></div>

            </template>

        </div>


        <!-- ================= GRILLE ================= -->

        <div
            class="
                grid grid-cols-7
                gap-1
                text-center
                text-sm
            "
        >


            <!-- Cases vides -->

            <template
                x-for="i in firstDayOffset()"
                :key="'empty-' + i"
            >

                <div
                    class="h-10"
                ></div>

            </template>


            <!-- Jours du mois -->

            <template
                x-for="day in daysInMonth()"
                :key="day"
            >

                <button
                    type="button"

                    @click="selectDate(day)"

                    class="
                        h-10
                        w-10
                        rounded-full
                        flex
                        items-center
                        justify-center
                        transition
                        hover:bg-gray-100
                        focus:outline-none
                    "

                    :class="{

                        /*
                         * Date sélectionnée
                         */
                        'bg-indigo-600 text-white font-bold shadow':
                            isSelected(day),

                        /*
                         * Aujourd'hui
                         */
                        'ring-2 ring-indigo-300':
                            isToday(day) && !isSelected(day)

                    }"
                >

                    <span
                        x-text="day"
                    ></span>

                </button>

            </template>

        </div>

    </div>


    <!-- ================= INPUT CACHÉ ================= -->

    <input
        type="hidden"
        name="selected_date"
        :value="isoDate()"
    >

</div>