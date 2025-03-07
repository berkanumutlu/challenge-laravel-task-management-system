<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('MyTasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($errors->any())
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <x-errors :errors="$errors"></x-errors>
                </div>
            @endif
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <a href="{{ route('tasks.create') }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">{{ __('NewTask') }}</a>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @if ($records->isEmpty())
                    <div class="p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700" role="alert">
                        <p>{{ __('You do not have any tasks yet.') }}</p>
                    </div>
                @else
                    @php
                        $statusList = [
                            'in_progress' => 'In Progress',
                            'pending' => 'Pending',
                            'completed' => 'Completed'
                        ];
                    @endphp
                    @foreach ($statusList as $statusKey => $statusLabel)
                        @php
                            $filteredTasks = $records->where('status', $statusKey);
                        @endphp
                        @if ($filteredTasks->isNotEmpty())
                            <div class="mb-12">
                                <h2 class="text-xl font-semibold mb-2">{{ $statusLabel }}</h2>
                                <hr class="my-2">
                                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach ($filteredTasks as $task)
                                            <li class="p-4 hover:bg-gray-100">
                                                <div class="flex flex-col xl:flex-row justify-between items-center">
                                                    <div class="w-full">
                                                        <h3 class="text-lg font-medium">{{ $task->title }}</h3>
                                                        <p class="py-6 text-sm text-gray-600">{{ $task->description }}</p>
                                                        <span
                                                            class="text-xs text-gray-500">{{ __('Created at') }} <strong>{{ $task->created_at->format('d M Y H:i') }}</strong></span>
                                                    </div>
                                                    <div class="w-24">
                                                        <div
                                                            class="flex items-center {{ $statusKey !== 'completed' ? 'justify-center' : 'justify-end' }}">
                                                            @if($statusKey !== 'completed')
                                                                <div>
                                                                    <x-secondary-button
                                                                        as="a"
                                                                        href="{{ route('tasks.edit', $task->id) }}"
                                                                        class="mr-1 !p-2 flex items-center gap-1 bg-gray-200">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="16" height="16" fill="currentColor"
                                                                             class="bi bi-pencil-square"
                                                                             viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                                            <path fill-rule="evenodd"
                                                                                  d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                                        </svg>
                                                                    </x-secondary-button>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <x-danger-button
                                                                    x-data="" class="!p-2"
                                                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-task-deletion-{{ $task->id }}')"
                                                                >
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                         height="16" fill="currentColor"
                                                                         class="bi bi-trash" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                                        <path
                                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                                    </svg>
                                                                </x-danger-button>

                                                                <x-modal name="confirm-task-deletion-{{ $task->id }}"
                                                                         focusable>
                                                                    <form method="POST"
                                                                          action="{{ route('tasks.destroy', $task->id) }}"
                                                                          class="p-6">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                                            {{ __('Are you sure you want to delete this task?') }}
                                                                        </h2>

                                                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                                            {{ __('Once this task is deleted, it cannot be recovered.') }}
                                                                        </p>

                                                                        <div class="mt-6 flex justify-end">
                                                                            <x-secondary-button
                                                                                x-on:click="$dispatch('close')">
                                                                                {{ __('Cancel') }}
                                                                            </x-secondary-button>

                                                                            <x-danger-button class="ms-3">
                                                                                {{ __('DeleteTask') }}
                                                                            </x-danger-button>
                                                                        </div>
                                                                    </form>
                                                                </x-modal>
                                                            </div>
                                                        </div>
                                                        <div class="w-full mt-2">
                                                            @if ($task->status === 'in_progress')
                                                                <form action="{{ route('tasks.complete', $task->id) }}"
                                                                      method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit"
                                                                            class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                                                                        {{ __('Complete') }}
                                                                    </button>
                                                                </form>
                                                            @elseif ($task->status === 'pending')
                                                                <form
                                                                    action="{{ route('tasks.mark_in_progress', $task->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit"
                                                                            class="px-4 py-2 text-sm font-semibold text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition">
                                                                        {{ __('InProgress') }}
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
