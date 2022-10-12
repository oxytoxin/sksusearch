<div>
    <ul role="list" class="divide-y divide-gray-200">
        @foreach ($dv->scanned_documents as $scanned_document)
            <li class="px-4 py-4 sm:px-0" x-data="{open:true}">
            <div class="flex justify-between">
              {{ $scanned_document->document_name }}
            </div>
            <div class="flex" x-cloak x-show="open">
                <iframe src="{{ asset($scanned_document->path) }}" frameborder="0" class="flex"></iframe>
            </div>
          </li> 
        @endforeach
        
           
        
      </ul>
      
</div>
