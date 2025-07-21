@props(['label'=>'', 'model'=>'', 'inline'=>'true'])
<div class="{{$inline=='true' ? 'md:flex' : '' }}  justify-start" x-data=""
   x-init=" IMask( $refs.input, {
      mask: Number,
      thousandsSeparator: '.'
   })"
>
   @if($label!='')
   <div class="w-full {{$inline=='true' ? 'md:w-48' : '' }}">
      <label>{{$label}}</label>
   </div>
   @endif

   <div class="flex-1">

@php($input_class = "w-full px-4 py-2 shadow-sm border rounded-md border-gray-300 dark:bg-darker dark:border-gray-600 focus:outline-none focus:border-none focus:ring focus:ring-primary focus:ring-opacity-50 dark:focus:ring-border-darker")

      <input wire:model="{{$model}}"
         x-ref="input"
         x-on:change="$dispatch('input', $refs.input.value)"
         onfocus="this.select();"
         {{ $attributes->merge(['class' => $input_class]) }}
      />
      @error($model)
         <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
      @enderror
   </div>
</div>
