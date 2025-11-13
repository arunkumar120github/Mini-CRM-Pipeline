@props(['id' => null, 'maxWidth' => 'md'])
<div
    x-data="{ show: @entangle($attributes->wire('model')) }"
    x-show="show"
    class="fixed  inset-0 z-50 flex items-center justify-center bg-gray-900/60"
    x-transition>
    <div class="bg-white rounded shadow-lg w-3/4 lg:w-1/2 max-w-{{ $maxWidth }} p-4">
        {{ $slot }}
    </div>
</div>
