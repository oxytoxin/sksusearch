<div x-data class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">PRE Report</h2>
        <div class="flex space-x-3">
            <button @click="printOut($refs.printContainer.outerHTML);" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
               Print
            </button>
            <button onclick="window.history.back()" class="flex hover:bg-gray-500 p-2 bg-gray-600 rounded-md font-light capitalize text-white text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.49 12 3.74 8.248m0 0 3.75-3.75m-3.75 3.75h16.5V19.5" />
                </svg>
                Back
            </button>
        </div>
    </div>
    <div x-ref="printContainer" class="w-full bg-gray-50 px-2 py-4 rounded-md">
            <div class="text-center">
                <p class="text-2xl font-medium">
                    Program of Receipts and Expenditures (PRE)
                </p>
                <p class="text-md font-normal">{{$cost_center->office->name}} - {{$cost_center->name}}</p>
                <p class="text-md font-normal">{{$title}} - {{$record->fund_description}}</p>
            </div>
            <div>
                <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th class="border border-black">UACS Code</th>
                            <th class="border border-black">Account Title</th>
                            <th class="border border-black">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ppmp_details as $item)
                        <tr>
                            <td class="border border-black px-2">{{$item->categoryItem->uacs_code}}</td>
                            <td class="border border-black px-2">{{$item->categoryItem->name}}</td>
                            <td class="border border-black text-right px-2">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{number_format($item->total_budget, 2)}}</span>
                                </div>
                            </td>
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
