<div x-data>
    <div class="p-4">
        <div class="flex justify-center">
            <button @click="showPrintable = true" wire:click="sksuPpmp164OSF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-8 rounded-lg">
                SKSU 164OSF
            </button>
        </div>
        <div class="flex justify-center space-x-4 mt-3">
            <button @click="showPrintable = true" wire:click="gasPpmp164OSF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                General Admission and Support Services (GASS)
            </button>
            <button @click="showPrintable = true" wire:click="hesPpmp164OSF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Higher Education Services (HES)
            </button>
            <button @click="showPrintable = true" wire:click="aesPpmp164OSF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Advanced Education Services (AES)
            </button>
            <button @click="showPrintable = true" wire:click="rdPpmp164OSF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Research and Development (RD)
            </button>
            <button @click="showPrintable = true" wire:click="extensionPpmp164OSF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Extension Services (ES)
            </button>
            <button @click="showPrintable = true" wire:click="lfPpmp164OSF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Local Fund Projects (LFP)
            </button>
        </div>
    </div>
    <div x-show="showPrintable" class="bg-gray-50">
        @if($is_active)
        <div class="flex justify-end p-4">
            <button @click="printOut($refs.printContainer.outerHTML);" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                Print PRE
             </button>
        </div>
        <div x-ref="printContainer" class="w-full bg-gray-50 px-2 py-4 rounded-md">
            <div class="text-center">
                <p class="text-2xl font-medium">
                    Program of Receipts & Expenditures (PRE)
                </p>
                <p class="text-xl font-medium">
                    Fund 164OSF
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
                        <td class="border border-black text-right font-semibold px-2">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{$total === null ? 0 : number_format($total->total_budget, 2)}}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
             {{-- signatories --}}
             @php
             $president = App\Models\EmployeeInformation::where('position_id', 34)->where('office_id', 51)->first();
             $vp_finance = App\Models\EmployeeInformation::where('position_id', 29)->where('office_id', 8)->first();
             $budget = App\Models\EmployeeInformation::where('position_id', 15)->where('office_id', 2)->first();
             @endphp
              <div class="grid grid-cols-3 space-x-3 mt-5">
                 <div class="col-span-1">
                     <div class="">
                         <div class="flex justify-center mt-5">
                             Noted by:
                           </div>
                           <div class="flex justify-center underline font-semibold">
                             {{$budget->full_name}}
                           </div>
                           <div class="flex justify-center">
                             Budget Officer
                           </div>
                     </div>
                 </div>
                 <div class="col-span-1">
                     <div class="">
                         <div class="flex justify-center mt-5">
                             Recommending Approval:
                           </div>
                           <div class="flex justify-center underline font-semibold">
                             {{$vp_finance->full_name}}
                           </div>
                           <div class="flex justify-center">
                             VP Finance
                           </div>
                     </div>
                 </div>
                 <div class="col-span-1">
                     <div class="">
                         <div class="flex justify-center mt-5">
                             Approved by:
                           </div>
                           <div class="flex justify-center underline font-semibold">
                             {{$president->full_name}}
                           </div>
                           <div class="flex justify-center">
                             University President
                           </div>
                     </div>
                 </div>
       </div>
        </div>
        @endif
    </div>
    <script>
        function printOut(data) {
        var mywindow = window.open('', '', 'height=1000,width=1000');
        mywindow.document.write('<html><head>');
        mywindow.document.write('<title></title>');
        mywindow.document.write(`<link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}" />`);
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close();
        mywindow.focus();
        setTimeout(() => {
            mywindow.print();
            return true;
        }, 1000);
    }
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

        }
    </script>
</div>
