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
                        <p>You do not have any tasks yet.</p>
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
                                                <div
                                                    class="flex flex-col xl:flex-row justify-between items-center">
                                                    <div class="w-full">
                                                        <h3 class="text-lg font-medium">{{ $task->title }}</h3>
                                                        <p class="py-6 text-sm text-gray-600">{{ $task->description }}</p>
                                                        <span
                                                            class="text-xs text-gray-500">Created at <strong>{{ $task->created_at->format('d M Y H:i') }}</strong></span>
                                                    </div>
                                                    <div class="w-24">
                                                        <div class="text-right">
                                                            @if($statusKey !== 'completed')
                                                                <a href="{{ route('tasks.edit', $task->id) }}"
                                                                   class="text-blue-600 hover:text-blue-800 text-sm mr-2">
                                                                    {{ __('Edit') }}
                                                                </a>
                                                            @endif
                                                            <form action="{{ route('tasks.destroy', $task->id) }}"
                                                                  method="POST" class="inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="text-red-600 hover:text-red-800 text-sm">
                                                                    {{ __('Delete') }}
                                                                </button>
                                                            </form>
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
