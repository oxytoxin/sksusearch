@php
    $quarters = DB::table('supplemental_quarters')->get();
@endphp
<x-app-layout>
    <div>
        <div class="flex justify-between items-center">
            <h2 class="font-light capitalize text-primary-600">Fund Allocation</h2>
        </div>
        <div class="mt-2 flex items-center">
            <a href="/wfp/fund-allocation/1?filter_is_supplemental=false" @class([
                'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                'bg-white -mt-2 text-primary-600' =>
                    request()->has('supplementalQuarterId') === false,
            ])>
                WFP
            </a>
            @foreach ($quarters as $item)
                <a href="/wfp/fund-allocation/1?filter_is_supplemental=true&supplementalQuarterId={{ $item->id }}"
                    @class([
                        'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                        'bg-white -mt-2 text-primary-600' =>
                            request()->has('supplementalQuarterId') === true &&
                            $item->id == request()->input('supplementalQuarterId'),
                    ])>
                    {{ $item->name }}
                </a>
            @endforeach
        </div>
        @livewire('w-f-p.fund-allocation', ['filter' => request()->input('filter')])
    </div>

</x-app-layout>
