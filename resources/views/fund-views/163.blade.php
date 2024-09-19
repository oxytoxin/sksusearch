<div>
    <div class="p-4">
        <div class="flex justify-center">
            <button wire:click="sksuPpmp161" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-8 rounded-lg">
                SKSU 163
            </button>
        </div>
        <div class="flex justify-center space-x-4 mt-3">
            <button wire:click="accessPpmp163" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                ACCESS CAMPUS
            </button>
            <button wire:click="tacurongPpmp163" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                TACURONG CAMPUS
            </button>
            <button wire:click="isulanPpmp163" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                ISULAN CAMPUS
            </button>
            <button wire:click="kalamansigPpmp163" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                KALAMANSIG CAMPUS
            </button>
            <button wire:click="bagumbayanPpmp163" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                BAGUMBAYAN CAMPUS
            </button>
            <button wire:click="lutayanPpmp163" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                LUTAYAN CAMPUS
            </button>
            <button wire:click="palimbangPpmp163" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                PALIMBANG CAMPUS
            </button>
        </div>
    </div>
    <div class="bg-gray-50">
        @if($is_active)
        <div class="flex justify-end p-4">
            <button onclick="printDiv('printarea')" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                Print PRE
             </button>
        </div>
        <div id="printarea" class="w-full bg-gray-50 px-2 py-4 rounded-md">
            <div class="text-center">
                <p class="text-2xl font-medium">
                    PRE 163
                </p>
                <p class="text-md font-normal">{{$title}}</p>
            </div>
            <div>
                <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th class="border border-black">UACS Code</th>
                            <th class="border border-black">Account Title</th>
                            {{-- <th class="border border-black">Total Quantity</th>
                            <th class="border border-black">Total Cost</th> --}}
                            <th class="border border-black">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ppmp_details as $item)
                        <tr>
                            <td class="border border-black px-2">{{$item->categoryItem->uacs_code}}</td>
                            <td class="border border-black px-2">{{$item->categoryItem->name}}</td>
                            <td class="border border-black text-right px-2">₱ {{number_format($item->total_budget, 2)}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="border border-black text-center py-3 italic" colspan="3">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tr>
                        <td class="border border-black text-left font-semibold p-1" colspan="2">Grand Total</td>
                        <td class="border border-black text-right font-semibold px-2">₱ {{$total === null ? 0 : number_format($total->total_budget, 2)}}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

        }
    </script>
</div>
