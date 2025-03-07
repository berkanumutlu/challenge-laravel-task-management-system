<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('CreateTask') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <x-errors :errors="$errors"></x-errors>
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="title" :value="__('Title')"/>
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                      :value="old('title')" required autofocus autocomplete="title"/>
                        <x-input-error class="mt-2" :messages="$errors->get('title')"/>
                    </div>
                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Description')"/>
                        <x-textarea-input id="description" name="description" class="mt-1 block w-full"
                                          :value="old('description')" rows="4"/>
                        <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                    </div>
                    <div class="flex justify-between items-center mt-8">
                        <x-primary-button>{{ __('Create') }}</x-primary-button>
                        <a href="{{ route('tasks.index') }}"
                           class="text-gray-600 hover:underline">{{ __('GoBack') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
