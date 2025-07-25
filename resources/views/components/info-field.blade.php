@props(['label', 'value'])

<div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
    <dt class="font-medium">{{ $label }}</dt>
    <dd class="text-right">{{ $value }}</dd>
</div>
