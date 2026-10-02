@props(['type' => null])

@php
    $icon = match ($type) {
        'grocery' => 'fa-solid fa-cart-shopping',
        'medicine' => 'fa-solid fa-pills',
        'medical_escort' => 'fa-solid fa-hospital-user',
        'social_visit' => 'fa-solid fa-user-group',
        'home_help' => 'fa-solid fa-house-circle-check',
        'support_request' => 'fa-solid fa-screwdriver-wrench',
        default => 'fa-solid fa-hand-holding-heart',
    };
@endphp

<i {{ $attributes->class([$icon]) }} aria-hidden=true></i>
