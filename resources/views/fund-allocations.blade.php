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
                    request()->has('supplementalQuaterId') === false,
            ])>
                WFP
            </a>
            @foreach ($quarters as $item)
                @if (config('features.VERSION') === '10.0.0.1')
                    <a href="/wfp/fund-allocation/1?filter_is_supplemental=true&supplementalQuaterId={{ $item->id }}"
                        @class([
                            'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                            'bg-white -mt-2 text-primary-600' =>
                                request()->has('supplementalQuaterId') === true &&
                                $item->id == request()->input('supplementalQuaterId'),
                        ])>
                        {{ $item->name }}
                    </a>
                @else
                    @if ($loop->first)
                        <a href="/wfp/fund-allocation/1?filter_is_supplemental=true&supplementalQuaterId={{ $item->id }}"
                            @class([
                                'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                                'bg-white -mt-2 text-primary-600' =>
                                    request()->has('supplementalQuaterId') === true &&
                                    $item->id == request()->input('supplementalQuaterId'),
                            ])>
                            {{ $item->name }}
                        </a>
                    @endif
                @endif
            @endforeach
        </div>
        @livewire('w-f-p.fund-allocation', ['filter' => request()->input('filter')])
    </div>

</x-app-layout>
