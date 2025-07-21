@props(['link'])
<a wire:navigate href="{{ $link }}" role="menuitem" class="flex items-center p-2 text-gray-500 transition rounded justify-start dark:text-light hover:bg-primary
dark:hover:bg-primary-dark hover:text-light
{!! request()->is(str_replace('/','',$link)) ? 'bg-primary text-white dark:bg-primary-dark' : '' !!}" >
    {{ $slot }}
</a>
