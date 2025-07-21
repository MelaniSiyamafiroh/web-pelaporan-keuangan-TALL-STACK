@props(['type'=>'button', 'onclick'=>'', 'wireclick'=>'', 'color'=>'primary'])

@php($button_class = "flex items-center justify-center space-x-4 px-4 py-2 font-medium text-center
            text-white transition-colorsduration-200 rounded-md bg-".$color." hover:bg-".$color."-dark
            focus:outline-none focus:ring-2 focus:ring-".$color." focus:ring-offset-1
            dark:focus:ring-offset-darker")

<button {{ $attributes->merge(['type' => $type, 'class' => $button_class]) }}
    @click="{{$onclick!='' ? $onclick : ''}}"
    @if($wireclick!='') wire:click="{{ $wireclick }}" @endif
>
    {{$slot}}
</button>
