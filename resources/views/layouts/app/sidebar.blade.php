<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-white dark:border-zinc-700/80 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    @if(auth()->user()?->is_admin)
                    <flux:sidebar.item
                        icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate
                    >
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    @endif

                    <flux:sidebar.item
                        icon="layout-grid"
                        :href="route('catalog')"
                        :current="request()->routeIs('catalog') || request()->routeIs('products.show')"
                        wire:navigate
                    >
                        {{ __('Katalog Produk') }}
                    </flux:sidebar.item>

                    @if(auth()->user()?->is_admin)
                    <flux:sidebar.item
                        icon="cog"
                        :href="route('admin.products')"
                        :current="request()->routeIs('admin.*')"
                        wire:navigate
                    >
                        {{ __('Admin Produk') }}
                    </flux:sidebar.item>
                    @endif
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <flux:header class="lg:hidden bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700/80">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <flux:dropdown position="top" align="end">
                <flux:button variant="ghost" size="sm" class="gap-2 !text-sm">
                    {{ auth()->user()->name }}
                    <flux:icon.chevron-down class="size-4" />
                </flux:button>
                <flux:menu>
                    <flux:menu.item href="{{ route('profile.edit') }}" icon="user" wire:navigate>
                        {{ __('Profile') }}
                    </flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item type="submit" icon="arrow-right-start-on-rectangle" variant="danger">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
