@php
    $quarters = DB::table('supplemental_quarters')->get();
@endphp
<x-app-layout>
    <div class="space-y-2">
        <div class="flex justify-between items-center">
            <h2 class="font-light capitalize text-primary-600">Generate PRE</h2>
        </div>
        <div>
            <div class="mt-2 inline-flex flex-row">
                <a href="/wfp/wfp-pre" @class([
                    'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                    'bg-white -mt-2 text-primary-600' => !request()->has(
                        'supplementalQuarterId'),
                ])>
                    WFP
                </a>
                @foreach ($quarters as $item)
                    <a href="/wfp/wfp-pre?supplementalQuarterId={{ $item->id }}" @class([
                        'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                        'bg-white -mt-2 text-primary-600' =>
                            request()->has('supplementalQuarterId') === true &&
                            $item->id == request()->input('supplementalQuarterId'),
                    ])>
                        {{ $item->name }}
                    </a>
                @endforeach
            </div>
            @if (!request()->has('supplementalQuarterId'))
                <div class="origin-top-left bg-white p-4 rounded-b-lg rounded-r-lg">
                    <div>
                        <livewire:w-f-p.generate-ppmp />
                    </div>
                </div>
            @else
                <div class="origin-[10%_0] bg-white p-4 rounded-b-lg rounded-r-lg">
                    <div>
                        @livewire('w-f-p.generate-ppmp-q1', ['supplementalQuarterId' => request()->input('supplementalQuarterId')])
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
