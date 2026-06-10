<div class="mt-4">
    @forelse ($advisories as $advisory)
        <div class="mb-3 w-full rounded-lg border border-gray-200 bg-white p-5 shadow-sm text-left">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $advisory->title }}</h3>
                    <p class="mt-1 text-xs font-medium uppercase tracking-wide text-green-600">
                        {{ \Carbon\Carbon::parse($advisory->published_at)->format('F d, Y') }}
                    </p>
                    <p class="mt-2 whitespace-pre-line text-sm text-gray-600">{{ $advisory->description }}</p>
                </div>
                <div class="shrink-0">
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($advisory->file_path) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:bg-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        View PDF
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="flex justify-center items-center mt-52">
            <div class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 text-center focus:outline-none">
                <svg class="mx-auto h-24 w-24 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                </svg>
                <span class="mt-2 block text-2xl font-semibold text-gray-600">No advisories posted yet</span>
            </div>
        </div>
    @endforelse
</div>
