<x-layout>
    <!-- Navigation -->
    <nav class="bg-gray-800 p-4">
        @guest
        <div class="container mx-auto flex justify-end items-center">
            <a href="{{ route('user.loginDisplay') }}" class="text-white hover:text-gray-300 px-3 py-2 rounded-lg hover:bg-gray-600 transition duration-300 ease-in-out">{{ __('home.login') }}</a>
            <a href="{{ route('user.registerDisplay') }}" class="text-white hover:text-gray-300 px-3 py-2 rounded-lg hover:bg-gray-600 transition duration-300 ease-in-out">{{ __('home.register') }}</a>
        </div>
        @endguest
    </nav>
    <!-- Page Content -->
    <div class="bg-gray-100 min-h-screen">
        <div class="container mx-auto mt-12">
            <div class="text-center">
                <h1 class="text-4xl font-semibold text-gray-800">{{ __('home.header') }}</h1>
                <p class="mt-2 text-gray-600">{{ __('home.header_description') }}</p>
            </div>

            <!-- Static Content -->
            <div class="mt-12 p-6 bg-white rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">{{ __('home.welcome') }}</h2>
                <p class="text-gray-700">
                    {{ __('home.paragraph_one') }}
                </p>
                <p class="text-gray-700 mt-2">
                    {{ __('home.paragraph_two') }}
                </p>
            </div>
        </div>
    </div>
</x-layout>
