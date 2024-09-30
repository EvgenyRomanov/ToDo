<x-layout>
    <div class="bg-gray-100 min-h-screen p-6">
        <div class="max-w-6xl mx-auto">
            <div class="mb-4 text-center">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">{{ __('create_task.create_task') }}</h2>
                <a href="{{ route('tasks.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-300 ease-in-out">{{ __('create_task.back') }}</a>
            </div>

            <form method="POST" action="{{ route('tasks.store') }}" class="bg-white p-6 rounded-lg shadow-lg">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-semibold">{{ __('create_task.title') }}</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="w-full px-4 py-2 border rounded-lg @error('title') border-red-500 @enderror"
                        value="{{ old('title') }}"
                        required
                    />
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-semibold">{{ __('create_task.description') }}</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @enderror"
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-gray-800 text-white text-sm font-semibold py-2 rounded-lg hover:bg-gray-600 transition duration-300 ease-in-out">{{ __('create_task.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
