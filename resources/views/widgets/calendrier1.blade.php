<div class="w-80 mx-auto select-none">

    {{-- ========================================================= --}}
    {{-- DATE SÉLECTIONNÉE --}}
    {{-- ========================================================= --}}

    <div class="text-center text-sm text-gray-600 mb-2">

        Date choisie :

        <strong>
            {{ $formattedDate }}
        </strong>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMATIONS PHP --}}
    {{-- ========================================================= --}}

    


    {{-- ========================================================= --}}
    {{-- NAVIGATION --}}
    {{-- ========================================================= --}}

    <div
        class="
            flex
            items-center
            justify-between
            px-3
            py-2
            bg-gray-100
            rounded-t-lg
            border-b
        "
    >

        {{-- MOIS PRÉCÉDENT --}}

        <button
            type="button"
            wire:click="$dispatch('previousMonth')"
            class="
                p-2
                rounded-full
                hover:bg-gray-200
                focus:outline-none
            "
        >
            ←
        </button>


        {{-- MOIS / ANNÉE --}}

        <div class="text-lg font-semibold">

            {{ $ladate->locale('fr')->translatedFormat('F Y') }}

        </div>


        {{-- MOIS SUIVANT --}}

        <button
            type="button"
            wire:click="$dispatch('nextMonth')"
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


    {{-- ========================================================= --}}
    {{-- CALENDRIER --}}
    {{-- ========================================================= --}}

    <div
        class="
            bg-white
            rounded-b-lg
            shadow
            p-4
        "
    >

        {{-- ===================================================== --}}
        {{-- JOURS DE LA SEMAINE --}}
        {{-- ===================================================== --}}

        <div
            class="
                grid
                grid-cols-7
                gap-1
                text-center
                text-xs
                font-medium
                text-gray-500
                mb-2
            "
        >

            <div>Lu</div>
            <div>Ma</div>
            <div>Me</div>
            <div>Je</div>
            <div>Ve</div>
            <div>Sa</div>
            <div>Di</div>

        </div>


        {{-- ===================================================== --}}
        {{-- GRILLE --}}
        {{-- ===================================================== --}}

        <div
            class="
                grid
                grid-cols-7
                gap-1
                text-center
                text-sm
            "
        >

            @php

                /*
                |--------------------------------------------------------------------------
                | PREMIER JOUR DU MOIS
                |--------------------------------------------------------------------------
                */

                $premierJour = Carbon\Carbon::create(
                    $annee,
                    $mois,
                    1
                );

                /*
                |--------------------------------------------------------------------------
                | Carbon :
                |
                | 1 = lundi
                | 7 = dimanche
                |--------------------------------------------------------------------------
                */

                $offset = $premierJour->dayOfWeekIso - 1;

                /*
                |--------------------------------------------------------------------------
                | NOMBRE DE JOURS DANS LE MOIS
                |--------------------------------------------------------------------------
                */

                $nombreJours = $premierJour->daysInMonth;

                /*
                |--------------------------------------------------------------------------
                | DATE DU JOUR
                |--------------------------------------------------------------------------
                */

                $aujourdhui = Carbon\Carbon::now(
                    'Europe/Paris'
                );

            @endphp


            {{-- ================================================= --}}
            {{-- CASES VIDES --}}
            {{-- ================================================= --}}

            @for($i = 0; $i < $offset; $i++)

                <div class="h-10"></div>

            @endfor


            {{-- ================================================= --}}
            {{-- JOURS --}}
            {{-- ================================================= --}}

            @for($day = 1; $day <= $nombreJours; $day++)

                @php

                    $dateJour = Carbon\Carbon::create(
                        $annee,
                        $mois,
                        $day
                    );

                    $estSelectionne =
                        $day == $jour;

                    $estAujourdHui =
                        $dateJour->isSameDay($aujourdhui);

                @endphp


                <a
                    href="{{ url(
                        '/admin/rendez-vous/' .
                        $annee . '-' .
                        $mois . '-' .
                        $day .
                        '/journee'
                    ) }}"
                     wire:navigate
                    class="
                        h-10
                        w-10
                        rounded-full
                        flex
                        items-center
                        justify-center
                        transition
                        focus:outline-none

                        {{ $estSelectionne
                            ? 'bg-indigo-600 text-white font-bold shadow'
                            : 'hover:bg-gray-100'
                        }}

                        {{ $estAujourdHui && !$estSelectionne
                            ? 'ring-2 ring-indigo-300'
                            : ''
                        }}
                    "
                >

                    {{ $day }}

                </a>

            @endfor

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INPUT CACHÉ --}}
    {{-- ========================================================= --}}

    <input
        type="hidden"
        name="selected_date"
        value="{{ $annee }}-{{ $mois }}-{{ $jour }}"
    >


    {{-- ========================================================= --}}
    {{-- VARIABLES UTILISABLES --}}
    {{-- ========================================================= --}}

    

</div>