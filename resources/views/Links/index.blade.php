<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Enlaces') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('links.store') }}" method="POST" class="mb-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" name="title" placeholder="Título" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                        </div>
                        <div>
                            <input type="url" name="url" placeholder="https://..." required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition ease-in-out duration-150">
                            + Agregar Link
                        </button>
                    </div>
                </form>

                <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tus enlaces:</h3>
                <div class="grid gap-4">
                    @forelse ($links as $link)
                        <div class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm">
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $link->title }}</p>
                                <a href="{{ $link->url }}" target="_blank" class="text-indigo-400 hover:underline text-sm">
                                    {{ $link->url }}
                                </a>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $link->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center">No hay enlaces todavía.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>