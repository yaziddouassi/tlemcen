<div
  x-data="{
    selectedDate: null,
    currentMonth: new Date().getMonth(),
    currentYear: new Date().getFullYear(),

    minDate: new Date(new Date().setHours(0,0,0,0)),

    months: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
    days: ['Lu','Ma','Me','Je','Ve','Sa','Di'],

    /* ================= INIT ================= */
    init() {
      if ($wire.currentdate) {
        const [y, m, d] = $wire.currentdate.split('-').map(Number)
        const incoming = new Date(y, m - 1, d)

        if (incoming >= this.minDate) {
          this.selectedDate = incoming
          this.currentYear = y
          this.currentMonth = m - 1
        }
      }

      this.$watch('selectedDate', (value, oldValue) => {
        if (!value) return
        if (value < this.minDate) return
        if (oldValue && value.getTime() === oldValue.getTime()) return
        $wire.set('currentdate', this.isoDate())
      })
    },

    /* ================= DATE HELPERS ================= */
    daysInMonth() {
      return new Date(this.currentYear, this.currentMonth + 1, 0).getDate()
    },

    firstDayOffset() {
      let d = new Date(this.currentYear, this.currentMonth, 1).getDay()
      return d === 0 ? 6 : d - 1
    },

    formattedDate() {
      if (!this.selectedDate) return '—'
      return this.selectedDate.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })
    },

    isoDate() {
      if (!this.selectedDate) return ''
      let y = this.selectedDate.getFullYear()
      let m = this.selectedDate.getMonth() + 1
      let d = this.selectedDate.getDate()
      return `${y}-${m}-${d}`
    },

    /* ================= STATES ================= */
    isSelected(day) {
      if (!this.selectedDate) return false
      return day === this.selectedDate.getDate()
        && this.currentMonth === this.selectedDate.getMonth()
        && this.currentYear === this.selectedDate.getFullYear()
    },

    isPast(day) {
      const d = new Date(this.currentYear, this.currentMonth, day)
      return d < this.minDate
    },

    /* ================= ACTIONS ================= */
    selectDate(day) {
      if (this.isPast(day)) return
      this.selectedDate = new Date(this.currentYear, this.currentMonth, day)
    },

    prevMonth() {
      if (!this.canGoPrevMonth()) return
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
      const prev = new Date(this.currentYear, this.currentMonth - 1, 1)
      return prev >= new Date(this.minDate.getFullYear(), this.minDate.getMonth(), 1)
    }
  }"
  class="w-80 mx-auto select-none"
>

  <!-- Date sélectionnée -->
  <div class="text-center text-sm text-gray-600 mb-2">
    Date choisie :
    <strong x-text="formattedDate()"></strong>
  </div>

  <!-- Header -->
  <div class="flex items-center justify-between px-3 py-2 bg-gray-100 rounded-t-lg border-b">
    <button
      @click="prevMonth"
      :class="{ 'opacity-30 cursor-not-allowed': !canGoPrevMonth() }"
      class="p-2 rounded-full hover:bg-gray-200"
    >
      ←
    </button>

    <div class="text-lg font-semibold">
      <span x-text="months[currentMonth]"></span>
      <span x-text="currentYear"></span>
    </div>

    <button
      @click="nextMonth"
      class="p-2 rounded-full hover:bg-gray-200"
    >
      →
    </button>
  </div>

  <!-- Calendar -->
  <div class="bg-white rounded-b-lg shadow p-4">

    <!-- Days header -->
    <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-2">
      <template x-for="d in days" :key="d">
        <div x-text="d"></div>
      </template>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-7 gap-1 text-center text-sm">

      <template x-for="i in firstDayOffset()" :key="'e'+i">
        <div class="h-10"></div>
      </template>

      <template x-for="day in daysInMonth()" :key="day">
        <button
          @click="selectDate(day)"
          :disabled="isPast(day)"
          class="h-10 w-10 rounded-full flex items-center justify-center transition
                 focus:outline-none"
          :class="{
            'bg-indigo-600 text-white font-bold shadow': isSelected(day),
            'text-gray-300 cursor-not-allowed': isPast(day),
            'hover:bg-gray-100': !isPast(day)
          }"
        >
          <span x-text="day"></span>
        </button>
      </template>

    </div>
  </div>

  <!-- Input caché -->
  <input type="hidden" name="selected_date" :value="isoDate()">

</div>
