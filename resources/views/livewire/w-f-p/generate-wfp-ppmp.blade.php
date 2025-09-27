<div class="space-y-2">
    <div>
        <div>
            <!-- Dropdown for small screens -->
            <div class="flex justify-end gap-2">
                {{-- /reports/generate-wfp-ppmp?wfp_type_id={{$selectedType}}&fund_cluster_id={{$fundClusterWfpId}}&m_f_o_s_id={{ $mfoId }} --}}
                <a href="{{ route('generate-wfp-ppmp-report', [
                    'wfp_type_id' => $selectedType,
                    'fund_cluster_id' => $fundClusterWfpId,
                    'm_f_o_s_id' => $mfoId,
                    'campus_id' => $campusId,
                    'supplemental_quarter_id' => $supplementalQuarterId,
                ]) }}"
                   target="_blank"
                   class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                    Export PPMP
                </a>
                <button @click="printOut($refs.printContainer.outerHTML);" type="button"
                        class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                    Print PPMP
                </button>
            </div>
            <!-- Content for each tab -->
            <div class="mt-4">
                @if ($fundClusterWfpId === 2)
                    @include('fund-views-ppmp.163')
                @else
                    @include('fund-views-ppmp.101')
                @endif
                {{-- <div x-show="selectedTab === '101'">

                    <div class="flex justify-center mt-10">
                        <div wire:loading class="loader">
                        </div>
                    </div>
                </div>
                <div x-show="selectedTab === '161'">
                    @include('fund-views-ppmp.161')
                    <div class="flex justify-center mt-10">
                        <div wire:loading class="loader">
                        </div>
                    </div>
                </div>
                <div x-show="selectedTab === '163'">
                    <div class="flex justify-center mt-10">
                        <div wire:loading class="loader">
                        </div>
                    </div>
                </div>
                <div x-show="selectedTab === '164T / FHE'">
                    @include('fund-views-ppmp.164T')
                    <div class="flex justify-center mt-10">
                        <div wire:loading class="loader">
                        </div>
                    </div>
                </div>
                <div x-show="selectedTab === '164T / Non-FHE'">
                    @include('fund-views-ppmp.164TNonFHE') <!-- Include the view for tab 164T -->
                    <div class="flex justify-center mt-10">
                        <div wire:loading class="loader">
                        </div>
                    </div>
                </div>
                <div x-show="selectedTab === '164OSF'">
                    @include('fund-views-ppmp.164OSF')
                    <div class="flex justify-center mt-10">
                        <div wire:loading class="loader">
                        </div>
                    </div>
                </div>
                <div x-show="selectedTab === '164MF'">
                    @include('fund-views-ppmp.164MF')
                    <div class="flex justify-center mt-10">
                        <div wire:loading class="loader">
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="mt-4">
            {{-- {{ $this->table }} --}}
        </div>
        {{-- @else
        <div class="flex justify-center items-center h-64">
            <h2 class="font-light text-gray-500">-- No WFP Period Added --</h2>
        </div>
        @endif --}}
    </div>
</div>
