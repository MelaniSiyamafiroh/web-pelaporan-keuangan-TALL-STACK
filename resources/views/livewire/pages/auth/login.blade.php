<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirect(
            session('url.intended', RouteServiceProvider::HOME),
            navigate: true
        );
    }
};
?>

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">

    <!-- Background with semi-transparent overlay -->
    <div class="absolute inset-0 -z-10">
        <img src="{{ asset('images/bg.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-white bg-opacity-85"></div>
    </div>

    <!-- Form Container -->
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg dark:bg-dark">
        <div class="flex justify-center pb-8">
            <img src="{{ asset('images/logo_biru.png') }}" alt="Logo" class="h-16">
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login">
            <div class="flex flex-col space-y-3">
                <x-input label="Email" model="form.email" inline="false" />
                <x-input type="password" label="Password" model="form.password" inline="false" />
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="rounded border-gray-300 dark:bg-darker dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember">
                    <span class="ms-2 text-sm">{{ __('Remember me') }}</span>
                </label>
                <x-button type="submit">Login</x-button>
            </div>
        </form>
    </div>
</div>
