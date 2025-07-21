<!-- Header -->
<div class="py-1 px-2 bg-blue-600 dark:bg-primary-dark h-14">
   <div class="flex items-center justify-between p-2">
      <button @click="toggleSidebarMenu" >
         <x-fas-bars class="h-5 w-5 text-white"/>
      </button>

      <div class="flex space-x-4">

        {{-- Tombol toggle dark mode --}}
         <x-button-circle class="bg-transparent hover:bg-primary-dark dark:hover:bg-dark" color="transparent" @click="toggleTheme">
            <x-fas-moon x-show="!isDark" class="h-4 w-4 text-white" />
            <x-fas-sun x-show="isDark" class="h-4 w-4 text-white" />
         </x-button-circle>

		<livewire:layout.navigation/>

      </div>
   </div>
</div>
