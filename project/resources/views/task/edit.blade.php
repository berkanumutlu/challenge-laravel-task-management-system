<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('EditTask') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <x-errors :errors="$errors"></x-errors>
                @if(empty($record))
                    <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700" role="alert">
                        <p>{{ __('Record not found.') }}</p>
                    </div>
                @else
                    <form action="{{ route('tasks.update', $record->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Title')"/>
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                          :value="old('title', $record->title)" required autofocus
                                          autocomplete="title"/>
                            <x-input-error class="mt-2" :messages="$errors->get('title')"/>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')"/>
                            <x-textarea-input id="description" name="description" class="mt-1 block w-full"
                                              :value="old('description', $record->description)" rows="4"/>
                            <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')"/>
                            <select name="status" id="status"
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="pending" {{ $record->status === 'pending' ? 'selected' : '' }}>
                                    {{ __('Pending') }}
                                </option>
                                <option value="in_progress" {{ $record->status === 'in_progress' ? 'selected' : '' }}>
                                    {{ __('InProgress') }}
                                </option>
                                <option value="completed" {{ $record->status === 'completed' ? 'selected' : '' }}>
                                    {{ __('Completed') }}
                                </option>
                            </select>
                        </div>
                        <div class="flex justify-between items-center mt-8">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                            <a href="{{ route('tasks.index') }}"
                               class="text-gray-600 hover:underline">{{ __('GoBack') }}</a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
