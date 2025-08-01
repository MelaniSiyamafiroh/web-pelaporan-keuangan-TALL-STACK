<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>{{ config('app.name', 'Laravel') }}</title>

      <!-- Favicon -->
        <link rel="icon" href="{{ asset('storage/images/logo_biru.png') }}" type="image/x-icon"/>
		<link rel="shortcut icon" type="images/logo_biru_crop.png" href="{{ url(asset('storage/setting/favicon.png')) }}">

      <!-- Fonts -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

      <!-- Scripts -->
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      <script src="{{ asset('js/theme.js') }}" defer></script>
   </head>
   <body>
      <div x-data="setup()"
            x-init="$refs.loading.classList.add('hidden');"
            :class="{ 'dark': isDark}"
        >

        {{-- Loading indicator --}}
         <div x-ref="loading"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-100 dark:bg-darker"
         >
            <img src="images/loading1.gif" width="32" class="animate-spin">
         </div>

         <div class="flex w-screen h-screen overflow-hidden text-gray-900 bg-gray-100
            dark:bg-darker dark:text-light">

            @include('layouts.sidebar')

            <div class="flex-1 overflow-hidden">
               <div class="flex flex-col h-screen">
                  <header>
                     @include('layouts.header')
                  </header>

                  <main class="flex-1 px-4 py-4 overflow-y-auto">
                     {{ $slot }}
                  </main>
               </div>
            </div>

         </div>
      </div>
   </body>
</html>
