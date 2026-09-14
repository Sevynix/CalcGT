{{--
    Shared navigation link list.
    Used by both the mobile drawer and the desktop sidebar to avoid duplication.

    Props (passed via @include):
      $linkClass   - CSS classes for each <a> element
      $labelClass  - CSS classes for the label <span>
      $showLabel   - true/false, whether to render the label text
      $onClickAttr - optional Alpine @click attribute string (for mobile close)
--}}

@php
    $nav = [
        [
            'group'  => null,
            'items'  => [
                ['route' => 'dashboard',   'label' => 'Dashboard',   'img' => 'images/items/guestbook.png',    'alt' => 'Guestbook'],
            ],
        ],
        [
            'group'  => 'Popular Search',
            'items'  => [
                ['route' => 'surgery-tools',      'label' => 'MSurg Tools',        'img' => 'images/surgery/surg_crate.png',          'alt' => 'Surg Crate'],
                ['route' => 'gala-tools',         'label' => 'Startopia Tools',    'img' => 'images/startopia/crate.png',             'alt' => 'Startopia'],
                ['route' => 'crime-pack',         'label' => 'Crime Pack',         'img' => 'images/crime/crate.png',                 'alt' => 'Crime Wave'],
                ['route' => 'ssp-pack',           'label' => 'SSP',                'img' => 'images/ssp/ssp.png',                     'alt' => 'Small Seed Pack'],
                ['route' => 'rsp-pack',           'label' => 'RSP',                'img' => 'images/rsp/2010.png',                    'alt' => 'Rare Seed Pack'],
                ['route' => 'autoclave',          'label' => 'Autoclave',          'img' => 'images/surgery/autoclave.png',           'alt' => 'Autoclave'],
                ['route' => 'nanoforge',          'label' => 'Nanoforge',          'img' => 'images/startopia/nanoforge.png',         'alt' => 'Nanoforge'],
            ],
        ],
        [
            'group'  => 'Provider',
            'items'  => [
                ['route' => 'bbm-provider',       'label' => 'BBM',                'img' => 'images/bbm/bbm.png',                     'alt' => 'Building Blocks Machine'],
                ['route' => 'science-provider',   'label' => 'Science Station',    'img' => 'images/science/science_station.png',     'alt' => 'Science Station'],
                ['route' => 'tackle-box',         'label' => 'Tackle Box',         'img' => 'images/fishing/fishing_pack/tackle.png', 'alt' => 'Tackle Box'],
                ['route' => 'atm-calc',           'label' => 'ATM Machine',        'img' => 'images/city/atm.png',                    'alt' => 'ATM'],
                ['route' => 'awkward-unicorn',    'label' => 'Awkward Unicorn',    'img' => 'images/unicorn/image_2.png',             'alt' => 'Awkward Unicorn'],
            ],
        ],

        [
            'group'  => 'Player Tools',
            'items'  => [
                ['route' => 'level-calculator',   'label' => 'Player Level',       'img' => 'images/items/exp_potion.png',            'alt' => 'EXP Potion'],
                ['route' => 'roles-calculator',   'label' => 'Roles Calculator',   'img' => 'images/roles/farmer.png',                'alt' => 'Roles'],
                ['route' => 'backpack-calculator','label' => 'Backpack Upgrades',  'img' => 'images/items/backpack_upgrade.png',      'alt' => 'Backpack'],
            ],
        ],
        [
            'group'  => 'Guild Tools',
            'items'  => [
                ['route' => 'guild-level',        'label' => 'Guild Level',        'img' => 'images/lock/guild_lock.png',             'alt' => 'Guild'],
            ],
        ],
        [
            'group'  => 'Event',
            'items'  => [
                ['route' => 'valentine-pack',     'label' => 'Valentine\'s Week',  'img' => 'images/event/valentine/valentine_card.png', 'alt' => 'Valentine'],
                ['route' => 'stpatrick-pack',     'label' => 'St. Patrick\'s Week', 'img' => 'images/items/clover.png', 'alt' => 'St. Patrick'],
                ['route' => 'easter-pack',        'label' => 'Easter Week',        'img' => 'images/event/easter/crate.png', 'alt' => 'Easter'],
                ['route' => 'cinco-pack',         'label' => 'Cinco De Mayo Week', 'img' => 'images/event/cinco/whistle.png', 'alt' => 'Cinco'],
                ['route' => 'summerfest-pack',    'label' => 'SummerFest',         'img' => 'images/event/summer/fireworks.png',      'alt' => 'SummerFest'],
            ],
        ],
        [
            'group'  => 'Carnival',
            'items'  => [
                ['route' => 'shatter-crystal',    'label' => 'Shatter Crystal',          'img' => 'images/carnival/shatter/2246.png',       'alt' => 'Shatter Crystal'],
                ['route' => 'fish-training',      'label' => 'Fish Training',            'img' => 'images/fishTraining/trainingPort.png',    'alt' => 'Fish Training'],
            ],
        ],
        [
            'group'  => 'Store',
            'items'  => [
                ['route' => 'fishin-pack',        'label' => 'Fishing Pack',       'img' => 'images/fishing/crate.png',               'alt' => 'Fishing'],
                ['route' => 'painter-pack',       'label' => 'Painter Pack',       'img' => 'images/paint/red.png',                   'alt' => 'Painter'],
                ['route' => 'city-pack',          'label' => 'City Pack',          'img' => 'images/city/hedge.png',                  'alt' => 'City'],
                ['route' => 'firefighter-pack',   'label' => 'Firefighter Pack',   'img' => 'images/firefighter/house.png',           'alt' => 'Firehouse'],
                ['route' => 'farm-pack',          'label' => 'Farm Pack',          'img' => 'images/farm/tractor.png',                'alt' => 'Farm'],
                ['route' => 'paleontologist-kit', 'label' => 'Paleontologist Kit', 'img' => 'images/paleontologist/hammer.png',       'alt' => 'Paleo'],
                ['route' => 'science-kit',        'label' => 'Science Kit',        'img' => 'images/science/chem/r.png',              'alt' => 'Science'],
                ['route' => 'boo-pack',           'label' => 'B.O.O. Pack',        'img' => 'images/boo/jar.png',                     'alt' => 'BOO'],
            ],
        ],
    ];
@endphp

@foreach ($nav as $section)
    {{-- Section group header --}}
    @if ($section['group'])
        @if ($isMobile ?? false)
            <div class="pt-5 pb-2"><p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ $section['group'] }}</p></div>
        @else
            <div class="pt-6 pb-2" x-show="sidebarOpen">
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest truncate">{{ $section['group'] }}</p>
            </div>
            <div class="pt-6 pb-2 flex justify-center" x-show="!sidebarOpen">
                <div class="w-6 border-t-[3px] rounded-full border-slate-200"></div>
            </div>
        @endif
    @endif

    {{-- Navigation links --}}
    @foreach ($section['items'] as $item)
        @php
            $isActive = request()->routeIs($item['route']);
            $activeClass = 'bg-[#f0f9ff] dark:bg-brand-500/20 text-slate-800 dark:text-brand-200 font-bold shadow-sm';
            $inactiveClass = 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-700 dark:hover:text-slate-200 font-medium';
        @endphp

        @if ($isMobile ?? false)
            <a href="{{ route($item['route']) }}"
               @click="mobileNavOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition duration-200 whitespace-nowrap {{ $isActive ? $activeClass : $inactiveClass }}">
                <span class="shrink-0 flex items-center justify-center w-5 h-5">
                    <img loading="lazy" src="{{ asset($item['img']) }}" alt="{{ $item['alt'] }}" class="w-full h-full object-contain">
                </span>
                <span class="text-[14.5px]">{{ $item['label'] }}</span>
            </a>
        @else
            <a href="{{ route($item['route']) }}"
               class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ $isActive ? $activeClass : $inactiveClass }}"
               :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img loading="lazy" src="{{ asset($item['img']) }}" alt="{{ $item['alt'] }}" class="w-full h-full object-contain">
                </span>
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>{{ $item['label'] }}</span>
            </a>
        @endif
    @endforeach
@endforeach

