<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Item Reports</h2>
        {{-- <a href="{{ route('wfp.request-supply') }}"
            class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">New
            Request</a> --}}
    </div>
    <div>
        {{ $this->table }}
    </div>
</div>
