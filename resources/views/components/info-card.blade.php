@props(['title'])

<div class="bg-white shadow rounded-lg p-4 dark:bg-gray-800">
    <h2 class="text-lg font-semibold mb-4 dark:text-white">{{ $title }}</h2>
    <dl class="space-y-2">
        {{ $slot }}
    </dl>
</div>
