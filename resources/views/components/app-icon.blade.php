@props([
    'name',
    'class' => 'w-5 h-5',
    'type' => 'outline', // outline, solid, mini
])

@php
    $normalized = strtolower(trim((string) $name));
    // تنظيف أسماء FontAwesome القديمة إن وُجدت
    $normalized = preg_replace('/^(fa-solid\s+|fa-regular\s+|fa-brands\s+|fa-)/', '', $normalized);
    $normalized = str_replace([' ', '_'], '-', $normalized);
@endphp

@switch($normalized)
    {{-- 1. أيقونات مخصصة من Lucide عند عدم توفرها في Heroicons (Fallback 1: Lucide) --}}
    @case('crown')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
            <path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.735H5.81a1 1 0 0 1-.957-.735L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"/>
            <path d="M5 21h14"/>
        </svg>
        @break

    @case('pill')
    @case('medicine')
    @case('pills')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
            <path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/>
            <path d="m8.5 8.5 7 7"/>
        </svg>
        @break

    @case('handshake')
    @case('handshake-angle')
    @case('hand-holding-hand')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
            <path d="m11 17 2 2a1 1 0 0 0 1.4 0l6.6-6.6a2 2 0 0 0 0-2.8l-1.6-1.6a2 2 0 0 0-2.8 0L14 10.6"/>
            <path d="m18 14 1.5 1.5a1 1 0 0 1 0 1.4l-2 2a1 1 0 0 1-1.4 0L14 16.8"/>
            <path d="m13 7-2-2a1 1 0 0 0-1.4 0L3 11.6a2 2 0 0 0 0 2.8l1.6 1.6a2 2 0 0 0 2.8 0l2.6-2.6"/>
            <path d="m6 10-1.5-1.5a1 1 0 0 1 0-1.4l2-2a1 1 0 0 1 1.4 0L10 7.2"/>
        </svg>
        @break

    @case('hand-holding-heart')
    @case('hand-heart')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
            <path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"/>
            <path d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/>
            <path d="m2 15 6 6"/>
            <path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.75 2.75 0 0 0 18.25 3c-1.3 0-2.3 1-2.75 1.8-.45-.8-1.45-1.8-2.75-1.8A2.75 2.75 0 0 0 10 5.8c0 1.1.8 2 1.5 2.7l4 3.9z"/>
        </svg>
        @break

    @case('history')
    @case('clock-rotate-left')
    @case('audit-log')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
            <path d="M3 3v5h5"/>
            <path d="M12 7v5l4 2"/>
        </svg>
        @break

    @case('medical')
    @case('kit-medical')
    @case('stethoscope')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
            <path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/>
            <path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"/>
            <circle cx="20" cy="10" r="2"/>
        </svg>
        @break

    {{-- 2. أيقونات مخصصة من Tabler (Fallback 2: Tabler) --}}
    @case('broom')
    @case('home-help')
    @case('cleaning')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
            <path d="M4 19a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z"/>
            <path d="M5 18l5 -13a2 2 0 0 1 3.5 0l5 13"/>
            <path d="M11 5l1 -3l1 3"/>
            <path d="M9 14h6"/>
        </svg>
        @break

    @case('walking')
    @case('person-walking')
    @case('medical-escort')
    @case('escort')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
            <circle cx="13" cy="4" r="1"/>
            <path d="M7 21l3 -4"/>
            <path d="M16 21l-2 -4l-3 -3l1 -6"/>
            <path d="M6 12l2 -3l4 -1l3 3l3 1"/>
        </svg>
        @break

    {{-- 3. أيقونات Heroicons الرسمية (Primary Choice: Heroicons) --}}
    @case('chart-pie')
    @case('chart-line')
        @if ($type === 'solid') <x-heroicon-s-chart-pie :class="$class" {{ $attributes }} /> @else <x-heroicon-o-chart-pie :class="$class" {{ $attributes }} /> @endif
        @break

    @case('user-check')
    @case('approved')
        @if ($type === 'solid') <x-heroicon-s-user-plus :class="$class" {{ $attributes }} /> @else <x-heroicon-o-user-plus :class="$class" {{ $attributes }} /> @endif
        @break

    @case('clipboard-list')
    @case('requests')
        @if ($type === 'solid') <x-heroicon-s-clipboard-document-list :class="$class" {{ $attributes }} /> @else <x-heroicon-o-clipboard-document-list :class="$class" {{ $attributes }} /> @endif
        @break

    @case('shield-halved')
    @case('shield-check')
    @case('shield')
    @case('shield-heart')
    @case('security')
        @if ($type === 'solid') <x-heroicon-s-shield-check :class="$class" {{ $attributes }} /> @else <x-heroicon-o-shield-check :class="$class" {{ $attributes }} /> @endif
        @break

    @case('shield-exclamation')
    @case('complaints')
        @if ($type === 'solid') <x-heroicon-s-shield-exclamation :class="$class" {{ $attributes }} /> @else <x-heroicon-o-shield-exclamation :class="$class" {{ $attributes }} /> @endif
        @break

    @case('users')
    @case('user-group')
    @case('social-visit')
        @if ($type === 'solid') <x-heroicon-s-user-group :class="$class" {{ $attributes }} /> @else <x-heroicon-o-user-group :class="$class" {{ $attributes }} /> @endif
        @break

    @case('user')
    @case('profile')
        @if ($type === 'solid') <x-heroicon-s-user :class="$class" {{ $attributes }} /> @else <x-heroicon-o-user :class="$class" {{ $attributes }} /> @endif
        @break

    @case('user-shield')
        @if ($type === 'solid') <x-heroicon-s-shield-check :class="$class" {{ $attributes }} /> @else <x-heroicon-o-shield-check :class="$class" {{ $attributes }} /> @endif
        @break

    @case('sliders')
    @case('settings')
    @case('adjustments')
        @if ($type === 'solid') <x-heroicon-s-adjustments-horizontal :class="$class" {{ $attributes }} /> @else <x-heroicon-o-adjustments-horizontal :class="$class" {{ $attributes }} /> @endif
        @break

    @case('bell')
    @case('notifications')
        @if ($type === 'solid') <x-heroicon-s-bell :class="$class" {{ $attributes }} /> @else <x-heroicon-o-bell :class="$class" {{ $attributes }} /> @endif
        @break

    @case('calendar')
    @case('calendar-days')
    @case('calendar-check')
    @case('calendar-xmark')
    @case('calendar-plus')
        @if ($type === 'solid') <x-heroicon-s-calendar-days :class="$class" {{ $attributes }} /> @else <x-heroicon-o-calendar-days :class="$class" {{ $attributes }} /> @endif
        @break

    @case('location-dot')
    @case('map-pin')
    @case('location')
        @if ($type === 'solid') <x-heroicon-s-map-pin :class="$class" {{ $attributes }} /> @else <x-heroicon-o-map-pin :class="$class" {{ $attributes }} /> @endif
        @break

    @case('clock')
    @case('time')
        @if ($type === 'solid') <x-heroicon-s-clock :class="$class" {{ $attributes }} /> @else <x-heroicon-o-clock :class="$class" {{ $attributes }} /> @endif
        @break

    @case('phone')
        @if ($type === 'solid') <x-heroicon-s-phone :class="$class" {{ $attributes }} /> @else <x-heroicon-o-phone :class="$class" {{ $attributes }} /> @endif
        @break

    @case('check')
        @if ($type === 'solid') <x-heroicon-s-check :class="$class" {{ $attributes }} /> @else <x-heroicon-o-check :class="$class" {{ $attributes }} /> @endif
        @break

    @case('check-circle')
    @case('circle-check')
        @if ($type === 'solid') <x-heroicon-s-check-circle :class="$class" {{ $attributes }} /> @else <x-heroicon-o-check-circle :class="$class" {{ $attributes }} /> @endif
        @break

    @case('check-badge')
    @case('check-double')
        @if ($type === 'solid') <x-heroicon-s-check-badge :class="$class" {{ $attributes }} /> @else <x-heroicon-o-check-badge :class="$class" {{ $attributes }} /> @endif
        @break

    @case('x-mark')
    @case('xmark')
    @case('close')
    @case('times')
        @if ($type === 'solid') <x-heroicon-s-x-mark :class="$class" {{ $attributes }} /> @else <x-heroicon-o-x-mark :class="$class" {{ $attributes }} /> @endif
        @break

    @case('x-circle')
    @case('circle-xmark')
        @if ($type === 'solid') <x-heroicon-s-x-circle :class="$class" {{ $attributes }} /> @else <x-heroicon-o-x-circle :class="$class" {{ $attributes }} /> @endif
        @break

    @case('chat')
    @case('chat-bubble')
    @case('comments')
    @case('comment')
        @if ($type === 'solid') <x-heroicon-s-chat-bubble-left-right :class="$class" {{ $attributes }} /> @else <x-heroicon-o-chat-bubble-left-right :class="$class" {{ $attributes }} /> @endif
        @break

    @case('download')
    @case('arrow-down-tray')
        <x-heroicon-o-arrow-down-tray :class="$class" {{ $attributes }} />
        @break

    @case('upload')
    @case('arrow-up-tray')
        <x-heroicon-o-arrow-up-tray :class="$class" {{ $attributes }} />
        @break

    @case('exclamation-triangle')
    @case('triangle-exclamation')
    @case('warning')
        @if ($type === 'solid') <x-heroicon-s-exclamation-triangle :class="$class" {{ $attributes }} /> @else <x-heroicon-o-exclamation-triangle :class="$class" {{ $attributes }} /> @endif
        @break

    @case('exclamation-circle')
    @case('circle-exclamation')
    @case('alert')
        @if ($type === 'solid') <x-heroicon-s-exclamation-circle :class="$class" {{ $attributes }} /> @else <x-heroicon-o-exclamation-circle :class="$class" {{ $attributes }} /> @endif
        @break

    @case('info')
    @case('circle-info')
        @if ($type === 'solid') <x-heroicon-s-information-circle :class="$class" {{ $attributes }} /> @else <x-heroicon-o-information-circle :class="$class" {{ $attributes }} /> @endif
        @break

    @case('star')
        @if ($type === 'solid') <x-heroicon-s-star :class="$class" {{ $attributes }} /> @else <x-heroicon-o-star :class="$class" {{ $attributes }} /> @endif
        @break

    @case('bolt')
    @case('flash')
    @case('lightning')
        @if ($type === 'solid') <x-heroicon-s-bolt :class="$class" {{ $attributes }} /> @else <x-heroicon-o-bolt :class="$class" {{ $attributes }} /> @endif
        @break

    @case('trophy')
    @case('award')
    @case('medal')
        @if ($type === 'solid') <x-heroicon-s-trophy :class="$class" {{ $attributes }} /> @else <x-heroicon-o-trophy :class="$class" {{ $attributes }} /> @endif
        @break

    @case('academic-cap')
    @case('certificate')
        @if ($type === 'solid') <x-heroicon-s-academic-cap :class="$class" {{ $attributes }} /> @else <x-heroicon-o-academic-cap :class="$class" {{ $attributes }} /> @endif
        @break

    @case('shopping-cart')
    @case('cart-shopping')
    @case('grocery')
        @if ($type === 'solid') <x-heroicon-s-shopping-cart :class="$class" {{ $attributes }} /> @else <x-heroicon-o-shopping-cart :class="$class" {{ $attributes }} /> @endif
        @break

    @case('shopping-bag')
        @if ($type === 'solid') <x-heroicon-s-shopping-bag :class="$class" {{ $attributes }} /> @else <x-heroicon-o-shopping-bag :class="$class" {{ $attributes }} /> @endif
        @break

    @case('home')
    @case('house')
        @if ($type === 'solid') <x-heroicon-s-home :class="$class" {{ $attributes }} /> @else <x-heroicon-o-home :class="$class" {{ $attributes }} /> @endif
        @break

    @case('heart')
        @if ($type === 'solid') <x-heroicon-s-heart :class="$class" {{ $attributes }} /> @else <x-heroicon-o-heart :class="$class" {{ $attributes }} /> @endif
        @break

    @case('envelope')
    @case('mail')
        @if ($type === 'solid') <x-heroicon-s-envelope :class="$class" {{ $attributes }} /> @else <x-heroicon-o-envelope :class="$class" {{ $attributes }} /> @endif
        @break

    @case('magnifying-glass')
    @case('search')
        @if ($type === 'solid') <x-heroicon-s-magnifying-glass :class="$class" {{ $attributes }} /> @else <x-heroicon-o-magnifying-glass :class="$class" {{ $attributes }} /> @endif
        @break

    @case('funnel')
    @case('filter')
        @if ($type === 'solid') <x-heroicon-s-funnel :class="$class" {{ $attributes }} /> @else <x-heroicon-o-funnel :class="$class" {{ $attributes }} /> @endif
        @break

    @case('arrow-right')
        <x-heroicon-o-arrow-right :class="$class" {{ $attributes }} />
        @break

    @case('arrow-left')
        <x-heroicon-o-arrow-left :class="$class" {{ $attributes }} />
        @break

    @case('chevron-left')
        <x-heroicon-o-chevron-left :class="$class" {{ $attributes }} />
        @break

    @case('chevron-right')
        <x-heroicon-o-chevron-right :class="$class" {{ $attributes }} />
        @break

    @case('chevron-down')
        <x-heroicon-o-chevron-down :class="$class" {{ $attributes }} />
        @break

    @case('chevron-up')
        <x-heroicon-o-chevron-up :class="$class" {{ $attributes }} />
        @break

    @case('arrow-path')
    @case('refresh')
    @case('arrows-rotate')
    @case('rotate')
    @case('rotate-right')
        <x-heroicon-o-arrow-path :class="$class" {{ $attributes }} />
        @break

    @case('arrow-right-start-on-rectangle')
    @case('arrow-right-from-bracket')
    @case('logout')
        <x-heroicon-o-arrow-right-start-on-rectangle :class="$class" {{ $attributes }} />
        @break

    @case('bars')
    @case('bars-3')
    @case('menu')
        <x-heroicon-o-bars-3 :class="$class" {{ $attributes }} />
        @break

    @case('eye')
        @if ($type === 'solid') <x-heroicon-s-eye :class="$class" {{ $attributes }} /> @else <x-heroicon-o-eye :class="$class" {{ $attributes }} /> @endif
        @break

    @case('eye-slash')
        @if ($type === 'solid') <x-heroicon-s-eye-slash :class="$class" {{ $attributes }} /> @else <x-heroicon-o-eye-slash :class="$class" {{ $attributes }} /> @endif
        @break

    @case('lock-closed')
    @case('lock')
        @if ($type === 'solid') <x-heroicon-s-lock-closed :class="$class" {{ $attributes }} /> @else <x-heroicon-o-lock-closed :class="$class" {{ $attributes }} /> @endif
        @break

    @case('key')
        @if ($type === 'solid') <x-heroicon-s-key :class="$class" {{ $attributes }} /> @else <x-heroicon-o-key :class="$class" {{ $attributes }} /> @endif
        @break

    @case('trash')
        @if ($type === 'solid') <x-heroicon-s-trash :class="$class" {{ $attributes }} /> @else <x-heroicon-o-trash :class="$class" {{ $attributes }} /> @endif
        @break

    @case('pencil-square')
    @case('edit')
    @case('pen')
    @case('pen-to-square')
        @if ($type === 'solid') <x-heroicon-s-pencil-square :class="$class" {{ $attributes }} /> @else <x-heroicon-o-pencil-square :class="$class" {{ $attributes }} /> @endif
        @break

    @case('plus')
        <x-heroicon-o-plus :class="$class" {{ $attributes }} />
        @break

    @case('document-text')
    @case('file-lines')
    @case('file-text')
    @case('file-pdf')
    @case('note-sticky')
        @if ($type === 'solid') <x-heroicon-s-document-text :class="$class" {{ $attributes }} /> @else <x-heroicon-o-document-text :class="$class" {{ $attributes }} /> @endif
        @break

    @case('identification')
    @case('id-card')
        @if ($type === 'solid') <x-heroicon-s-identification :class="$class" {{ $attributes }} /> @else <x-heroicon-o-identification :class="$class" {{ $attributes }} /> @endif
        @break

    @case('ban')
    @case('suspended')
        <x-heroicon-o-no-symbol :class="$class" {{ $attributes }} />
        @break

    @case('hand-raised')
        @if ($type === 'solid') <x-heroicon-s-hand-raised :class="$class" {{ $attributes }} /> @else <x-heroicon-o-hand-raised :class="$class" {{ $attributes }} /> @endif
        @break

    @case('sparkles')
    @case('robot')
    @case('ai')
        @if ($type === 'solid') <x-heroicon-s-sparkles :class="$class" {{ $attributes }} /> @else <x-heroicon-o-sparkles :class="$class" {{ $attributes }} /> @endif
        @break

    @case('speaker-wave')
    @case('volume-high')
    @case('volume')
        @if ($type === 'solid') <x-heroicon-s-speaker-wave :class="$class" {{ $attributes }} /> @else <x-heroicon-o-speaker-wave :class="$class" {{ $attributes }} /> @endif
        @break

    @case('microphone')
    @case('mic')
        @if ($type === 'solid') <x-heroicon-s-microphone :class="$class" {{ $attributes }} /> @else <x-heroicon-o-microphone :class="$class" {{ $attributes }} /> @endif
        @break

    @case('paper-airplane')
    @case('paper-plane')
    @case('send')
        @if ($type === 'solid') <x-heroicon-s-paper-airplane :class="$class" {{ $attributes }} /> @else <x-heroicon-o-paper-airplane :class="$class" {{ $attributes }} /> @endif
        @break

    @case('route')
    @case('map')
        @if ($type === 'solid') <x-heroicon-s-map :class="$class" {{ $attributes }} /> @else <x-heroicon-o-map :class="$class" {{ $attributes }} /> @endif
        @break

    @default
        <x-heroicon-o-sparkles :class="$class" {{ $attributes }} />
@endswitch
