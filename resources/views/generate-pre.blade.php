@php
    $quarters = DB::table('supplemental_quarters')->get();
    $fundClusterWfps = App\Models\FundCluster::where('position', '!=', 0)->orderBy('position', 'asc')->get();
    $wfp_types = App\Models\WpfType::all();
@endphp
<x-app-layout>
    <div class="space-y-2">
        <div class="flex justify-between items-center">
            <h2 class="font-light capitalize text-primary-600">Generate PRE</h2>
        </div>
        <div>
            <div class="mt-2 inline-flex flex-row">
                <a href="{{ request()->fullUrlWithQuery(['supplementalQuarterId' => null]) }}"
                    @class([
                        'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                        'bg-white -mt-2 text-primary-600' => !request()->has(
                            'supplementalQuarterId'),
                    ])>
                    WFP
                </a>
                @foreach ($quarters as $item)
                    <a wire:key='{{ $item->id }}'
                        href="{{ request()->fullUrlWithQuery(['supplementalQuarterId' => $item->id]) }}"
                        @class([
                            'mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300 ',
                            'bg-white -mt-2 text-primary-600' =>
                                $item->id == request()->input('supplementalQuarterId'),
                        ])>
                        {{ $item->name }}
                    </a>
                @endforeach
            </div>
            <div class="origin-top-left bg-white p-4 rounded-b-lg rounded-r-lg">
                <div class="hidden sm:block">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        @foreach ($fundClusterWfps as $fund)
                            <a href="{{ request()->fullUrlWithQuery(['fundClusterWfpId' => $fund->id, 'sksuLabel' => $fund->name, 'campusId' => null]) }}"
                                @class([
                                    'rounded-md px-3 py-2 text-sm text-start font-medium w-auto',
                                    'bg-green-500 text-white' =>
                                        request()->input('fundClusterWfpId') == $fund->id,
                                    'text-gray-800 hover:text-green-700' =>
                                        request()->input('fundClusterWfpId') != $fund->id,
                                ])>
                                Fund {{ $fund->name }}
                            </a>
                        @endforeach
                    </nav>
                </div>
                <div x-data="{ selectedType: '{{ request()->input('selectedType') || 1 }}' }" class="flex my-2">
                    <select x-model="selectedType" name="selectedType" id="selectedType"
                        x-on:change="window.location.href = '{{ request()->fullUrl() }}&selectedType=' + $event.target.value"
                        class="block w-1/2 rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @foreach ($wfp_types as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->description }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{--  --}}
                @if (request()->input('fundClusterWfpId') == 2)
                    <div class="p-4">
                        <div class="grid gap-2 justify-center">
                            <a href="{{ request()->fullUrlWithQuery(['title' => 'Sultan Kudarat State University', 'showPre' => true, 'mfoId' => 6]) }}"
                                class="bg-green-800 hover:bg-green-700 text-white text-center  font-bold py-1.5 px-8 rounded-lg">
                                SKSU {{ request()->input('sksuLabel') }} PRE
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['title' => 'Sultan Kudarat State University', 'showPre' => false, 'mfoId' => 6]) }}"
                                class="bg-green-800 hover:bg-green-700 text-white text-center  font-bold py-1.5 px-8 rounded-lg">
                                SKSU {{ request()->input('sksuLabel') }}
                            </a>
                        </div>
                        @php
                            $campuses = App\Models\Campus::where('campus_code', '!=', 'ADMN')->get();
                        @endphp
                        <div class="grid grid-cols-7 space-x-4 mt-3">
                            @foreach ($campuses as $item)
                                <a href="{{ request()->fullUrlWithQuery(['title' => 'Sultan Kudarat State University', 'campusId' => $item->id, 'mfoId' => 6, 'showPre' => false]) }}"
                                    class="bg-green-800 hover:bg-green-700 text-center text-white font-bold py-1.5 px-3 rounded-lg uppercase">
                                    {{ $item->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-4">
                        <div class="grid gap-2 justify-center">
                            <a href="{{ request()->fullUrlWithQuery(['title' => 'Sultan Kudarat State University', 'showPre' => true, 'mfoId' => null]) }}"
                                class="bg-green-800 hover:bg-green-700 text-white text-center font-bold py-1.5 px-8 rounded-lg">
                                SKSU {{ request()->input('sksuLabel') }} PRE
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['showPre' => false, 'title' => 'Sultan Kudarat State University', 'mfoId' => null]) }}"
                                class="bg-green-800 hover:bg-green-700 text-white text-center  font-bold py-1.5 px-8 rounded-lg">
                                SKSU {{ request()->input('sksuLabel') }}
                            </a>
                        </div>
                        <div class="flex justify-center space-x-4 mt-3">
                            <a href="{{ request()->fullUrlWithQuery(['showPre' => false, 'mfoId' => 1, 'title' => 'General Admission and Support Services (GASS)']) }}"
                                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                General Admission and Support Services (GASS)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['showPre' => false, 'mfoId' => 2, 'title' => 'Higher Education Services (HES)']) }}"
                                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                Higher Education Services (HES)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['showPre' => false, 'mfoId' => 3, 'title' => 'Advanced Education Services (AES)']) }}"
                                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                Advanced Education Services (AES)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['showPre' => false, 'mfoId' => 4, 'title' => 'Research and Development (RD)']) }}"
                                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                Research and Development (RD)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['showPre' => false, 'mfoId' => 5, 'title' => 'Extension Services (ES)']) }}"
                                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                Extension Services (ES)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['showPre' => false, 'mfoId' => 6, 'title' => 'Local Fund Projects (LFP)']) }}"
                                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                Local Fund Projects (LFP)
                            </a>
                        </div>
                    </div>
                @endif
                @livewire('w-f-p.generate-ppmp')
            </div>
        </div>
    </div>
</x-app-layout>
