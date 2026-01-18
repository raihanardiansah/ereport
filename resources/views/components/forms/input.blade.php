@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors']) !!}>
