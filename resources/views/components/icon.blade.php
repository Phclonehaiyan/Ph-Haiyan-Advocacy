@props(['name'])

<span {{ $attributes->class('inline-flex items-center justify-center') }}>
    @switch($name)
        @case('mail')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M4 6h16v12H4z" />
                <path d="m4 7 8 6 8-6" />
            </svg>
            @break
        @case('settings')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="m12 3 1.5 2.7 3 .6-.6 3L18 12l-2.1 2.7.6 3-3 .6L12 21l-1.5-2.7-3-.6.6-3L6 12l2.1-2.7-.6-3 3-.6L12 3Z" />
                <circle cx="12" cy="12" r="3.2" />
            </svg>
            @break
        @case('logout')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M15 3h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-3" />
                <path d="M10 17 15 12 10 7" />
                <path d="M15 12H4" />
            </svg>
            @break
        @case('plus')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M12 5v14M5 12h14" />
            </svg>
            @break
        @case('edit')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="m4 20 4.5-1 9-9a2.1 2.1 0 0 0-3-3l-9 9L4 20Z" />
                <path d="m13.5 6.5 3 3" />
            </svg>
            @break
        @case('trash')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M4 7h16" />
                <path d="m9 7 .5-2h5L15 7" />
                <path d="M7 7v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V7" />
                <path d="M10 11v6M14 11v6" />
            </svg>
            @break
        @case('save')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M5 4h11l3 3v13H5z" />
                <path d="M8 4v6h8V4" />
                <path d="M9 18h6" />
            </svg>
            @break
        @case('phone')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M5 4h4l2 5-2.5 1.8a15.4 15.4 0 0 0 4.7 4.7L15 13l5 2v4a2 2 0 0 1-2.2 2A17 17 0 0 1 3 6.2 2 2 0 0 1 5 4Z" />
            </svg>
            @break
        @case('map-pin')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M12 21s6-5.5 6-11a6 6 0 1 0-12 0c0 5.5 6 11 6 11Z" />
                <circle cx="12" cy="10" r="2.2" />
            </svg>
            @break
        @case('eye')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6S2 12 2 12Z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            @break
        @case('users')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                <circle cx="9.5" cy="8" r="3.5" />
                <path d="M19 21v-2a4 4 0 0 0-3-3.85" />
                <path d="M16 4.5a3.5 3.5 0 0 1 0 7" />
            </svg>
            @break
        @case('sprout')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M12 22v-8" />
                <path d="M12 14c0-4.5 3.5-8 8-8 0 4.5-3.5 8-8 8Z" />
                <path d="M12 12C7.5 12 4 8.5 4 4c4.5 0 8 3.5 8 8Z" />
            </svg>
            @break
        @case('leaf')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M19 3C10.5 3 5 8.5 5 17c0 2 1 4 3 4 8.5 0 14-5.5 14-14 0-2-1-4-3-4Z" />
                <path d="M7 17c3-1 7-5 10-10" />
            </svg>
            @break
        @case('shield')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M12 3 5 6v6c0 5 3 8.5 7 10 4-1.5 7-5 7-10V6l-7-3Z" />
            </svg>
            @break
        @case('spark')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="m12 2 1.8 4.7L18.5 8.5l-4.7 1.8L12 15l-1.8-4.7L5.5 8.5l4.7-1.8L12 2Z" />
                <path d="m19 15 .9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9L19 15Z" />
            </svg>
            @break
        @case('megaphone')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M4 13v-2l11-5v12L4 13Z" />
                <path d="M15 9h3a2 2 0 0 1 0 6h-3" />
                <path d="m6 14 1.5 5h2L8 13" />
            </svg>
            @break
        @case('speaker')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M11 5 6 9H3v6h3l5 4V5Z" />
                <path d="M15.5 8.5a5 5 0 0 1 0 7" />
                <path d="M18 6a8.5 8.5 0 0 1 0 12" />
            </svg>
            @break
        @case('search')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <circle cx="11" cy="11" r="6.5" />
                <path d="m16 16 5 5" />
            </svg>
            @break
        @case('heart')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="m12 20-7-6.5A4.5 4.5 0 0 1 11.5 7L12 7.5l.5-.5A4.5 4.5 0 0 1 19 13.5L12 20Z" />
            </svg>
            @break
        @case('handshake')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="m8 12 3 3a2.2 2.2 0 0 0 3.1 0l2.7-2.7a2.2 2.2 0 0 0 0-3.1L14.6 7" />
                <path d="m3 9 3-3 4.5 4.5" />
                <path d="m21 9-3-3-4.5 4.5" />
                <path d="m7.5 13.5-2.8 2.8" />
                <path d="m16.5 13.5 2.8 2.8" />
            </svg>
            @break
        @case('calendar')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M7 2v4M17 2v4M3 9h18" />
                <rect x="3" y="5" width="18" height="16" rx="2" />
            </svg>
            @break
        @case('clock')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 7v5l3 2" />
            </svg>
            @break
        @case('file-text')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
                <path d="M14 3v5h5" />
                <path d="M9 13h6" />
                <path d="M9 17h6" />
                <path d="M9 9h2" />
            </svg>
            @break
        @case('play')
            <svg viewBox="0 0 24 24" fill="currentColor" class="h-full w-full">
                <path d="m8 6 10 6-10 6V6Z" />
            </svg>
            @break
        @case('image')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <rect x="3" y="5" width="18" height="14" rx="2" />
                <circle cx="9" cy="10" r="1.4" />
                <path d="m5 17 4.5-4.5 3.5 3.5 2.5-2.5 3.5 3.5" />
            </svg>
            @break
        @case('chart')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M4 19h16" />
                <path d="M7 16V9" />
                <path d="M12 16V5" />
                <path d="M17 16v-4" />
            </svg>
            @break
        @case('download')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M12 4v10" />
                <path d="m8 10 4 4 4-4" />
                <path d="M5 20h14" />
            </svg>
            @break
        @case('arrow-up-right')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M7 17 17 7" />
                <path d="M9 7h8v8" />
            </svg>
            @break
        @case('arrow-left')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>
            @break
        @case('chevron-down')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="m6 9 6 6 6-6" />
            </svg>
            @break
        @case('menu')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="M4 7h16M4 12h16M4 17h16" />
            </svg>
            @break
        @case('close')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <path d="m6 6 12 12M18 6 6 18" />
            </svg>
            @break
        @case('facebook')
            <svg viewBox="0 0 24 24" fill="currentColor" class="h-full w-full">
                <path d="M13.5 21v-7h2.3l.5-3h-2.8V9.3c0-.9.3-1.6 1.7-1.6H16V5.1c-.3 0-1-.1-1.9-.1-2.6 0-4.1 1.5-4.1 4.3V11H7.5v3H10v7h3.5Z" />
            </svg>
            @break
        @case('youtube')
            <svg viewBox="0 0 24 24" fill="currentColor" class="h-full w-full">
                <path d="M21.2 7.2a2.8 2.8 0 0 0-2-2C17.5 4.7 12 4.7 12 4.7s-5.5 0-7.2.5a2.8 2.8 0 0 0-2 2A29.7 29.7 0 0 0 2.3 12a29.7 29.7 0 0 0 .5 4.8 2.8 2.8 0 0 0 2 2c1.7.5 7.2.5 7.2.5s5.5 0 7.2-.5a2.8 2.8 0 0 0 2-2 29.7 29.7 0 0 0 .5-4.8 29.7 29.7 0 0 0-.5-4.8ZM10 15.5v-7l6 3.5-6 3.5Z" />
            </svg>
            @break
        @case('instagram')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-full w-full">
                <rect x="4" y="4" width="16" height="16" rx="4.5" />
                <circle cx="12" cy="12" r="3.5" />
                <circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none" />
            </svg>
            @break
    @endswitch
</span>
