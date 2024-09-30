<x-layout>
    <div class="bg-gray-100 min-h-screen p-6">
        <div class="max-w-6xl mx-auto">
            <div class="mb-4 text-center">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">{{ __('edit_task.edit') }}</h2>
                <a href="{{ route('tasks.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-300 ease-in-out">{{ __('edit_task.back') }}</a>
            </div>

            <form method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}" class="bg-white p-6 rounded-lg shadow-lg">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-semibold">{{ __('edit_task.title') }}</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="w-full px-4 py-2 border rounded-lg @error('title') border-red-500 @enderror"
                        value="{{ old('title', $task->title) }}"
                        required
                    />
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-semibold">{{ __('edit_task.description') }}</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @enderror"
                        required
                    >{{ old('description', $task->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="inline text-gray-700 text-sm font-semibold mr-2">{{ __('edit_task.completed') }}</label>
                    <input
                        type="checkbox"
                        id="completed"
                        name="completed"
                        {{ $task->completed ? 'checked' : '' }}
                        class="border rounded-lg @error('completed') border-red-500 @enderror"
                    />
                    @error('completed')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-gray-800 text-white text-sm font-semibold py-2 rounded-lg hover:bg-gray-600 transition duration-300 ease-in-out">{{ __('edit_task.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
