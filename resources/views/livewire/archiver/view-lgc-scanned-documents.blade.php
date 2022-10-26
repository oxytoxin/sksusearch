<div>
    <ul role="list" class="divide-y divide-gray-200">
        <div class="flex-col">
          <h1 class="font-sans text-xl font-bold tracking-widest capitalize text-primary-700">Scanned Documents</h1>
          <h2 class="font-sans text-xs font-medium tracking-widest text-gray-600 capitalize">Click document names to open.</h2>
        </div>
        @foreach ($dv->scanned_documents as $scanned_document)
            <li class="px-4 py-4 sm:px-0">
            <a class="flex w-fit hover:text-primary-600 hover:text-lg hover:font-extrabold" href="{{ asset('storage/'.$scanned_document->path) }}" target="_blank"  x-data="{}" x-tooltip.raw="Click to view file!">
              <span class="flex mr-3 text-md">  {{ $scanned_document->document_name }}</span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="flex w-5 h-5 my-auto">
                <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd" />
              </svg>
            </a>
            </li> 
        @endforeach        
      </ul>
      
  </div>
  