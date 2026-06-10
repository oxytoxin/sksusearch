@if($documents->isEmpty())
    <p class="text-sm text-gray-400">No supporting documents uploaded.</p>
@else
    <ul class="divide-y divide-gray-200">
        @foreach($documents as $doc)
            <li class="px-2 py-3">
                <a class="flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 hover:font-semibold"
                   href="{{ asset('storage/' . $doc->path) }}" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 shrink-0">
                        <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd" />
                    </svg>
                    {{ $doc->document_name }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
