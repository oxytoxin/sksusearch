<div id="print_to" class="col-span-2 print-bg-white border border-gray-800">
<div class="flex w-full justify-between border-b-4 border-black p-6 print:flex">
            <div id="header" class="ml-3 flex w-full text-left">
                <div class="my-auto inline"><img src="{{ asset('images/sksulogo.png') }}" alt="sksu logo"
                        class="h-full w-20 object-scale-down">
                </div>
                <div class="my-auto ml-3">
                    <div class="block">
                        <span class="text-left text-sm font-semibold tracking-wide text-black">Republic of the
                            Philippines</span>
                    </div>
                    <div class="block">
                        <span class="text-primary-600 text-left text-sm font-semibold uppercase tracking-wide">sultan
                            kudarat state university</span>
                    </div>
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-black">ACCESS, EJC Montilla, 9800 City of
                            Tacurong</span>
                    </div>
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-black">Province of Sultan Kudarat</span>
                    </div>
                </div>
            </div>
            <div class="relative right-0">

            </div>
        </div>
        <div class="w-full">
            <div class="m-2">
                <div class="flex h-auto w-full items-start px-6 pt-4 print:pb-0 print:block">
                    <div id="header" class="block w-full items-start text-left">
                        <div class="flex">
                            <span
                                class="mx-auto mb-6 text-lg font-extrabold uppercase tracking-wide text-black print:text-lg">Fuel Requisition Slip</span>
                        </div>
                        <div class="flex justify-between">
                            <div class="space-x-4">
                                <div class="">
                                        <span class="mr-8 text-sm font-semibold tracking-wide text-black">Date:</span>
                                        <span class="text-sm font-semibold tracking-wide text-black ">{{Carbon\Carbon::parse($fuel_request->created_at)->format('F d, Y')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-start">
                            <div class="space-x-4">
                                <div class="grid grid-cols-2">
                                    <span class="col-span-1 text-sm font-semibold tracking-wide text-red-600">Slip No.</span>
                                    <span class="col-span-1 text-sm font-semibold tracking-wide text-black ">{{$fuel_request->slip_number}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-col mt-8">
                            <div class="space-x-4">
                            <span class="text-sm font-semibold tracking-wide text-black">Name of Supplier:</span>
                            <span class="text-sm font-semibold tracking-wide text-black ">{{$fuel_request->supplier->name}}</span>
                            </div>
                            <div class="space-x-5">
                            <span class="text-sm font-semibold tracking-wide text-black">Address:</span>
                            <span class="text-sm font-semibold tracking-wide text-black">{{$fuel_request->supplier->address}}</span>
                            </div>
                        </div>

                        <div>
                            <table class="mt-8 w-full border border-gray-800">
                                <tr class="p-2 ">
                                    <th class="text-center border-r border-gray-800" width="100px">
                                        <span>Qty.</span>
                                    </th>
                                    <th class="text-center border-r border-gray-800" width="100px">
                                        <span>Unit</span>
                                    </th>
                                    <th class="text-center">
                                        <span>Articles</span>
                                    </th>
                                </tr>
                                    <tbody>
                                        <tr class="p-2 ">
                                            <td class="border-r border-t border-gray-800 text-center">
                                                <span>{{ $fuel_request->article === 'Gasoline' || $fuel_request->article === 'Diesel' ? $fuel_request->quantity : '' }}</span>
                                            </td>
                                            <td class="border-r border-t border-gray-800 text-center">
                                                <span>{{ $fuel_request->article === 'Gasoline' || $fuel_request->article === 'Diesel' ? $fuel_request->unit : '' }}</span>
                                            </td>
                                            <td class="border-t border-gray-800 text-center">
                                                <div class="flex space-x-8 justify-center items-center">
                                                    <div>
                                                        <input disabled {{ $fuel_request->article === 'Gasoline' ? 'checked' : '' }} type="checkbox">
                                                        <label for="">Gasoline</label>
                                                    </div>
                                                    <div>
                                                        <input disabled {{ $fuel_request->article === 'Diesel' ? 'checked' : '' }} type="checkbox">
                                                        <label for="">Diesel</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="p-2 ">
                                            <td class="border-r border-t border-gray-800 text-center">
                                                <span>{{ $fuel_request->article === 'Others'  ? $fuel_request->quantity : '' }}</span>
                                            </td>
                                            <td class="border-r border-t border-gray-800 text-center">
                                                <span>{{ $fuel_request->article === 'Others'  ? $fuel_request->unit : '' }}</span>
                                            </td>
                                            <td class="border-t border-gray-800 text-center">
                                                <div class="flex space-x-8 justify-center items-center">
                                                    <div>
                                                        <input disabled {{ $fuel_request->article === 'Others' ? 'checked' : '' }} type="checkbox">
                                                        <label for="">Others :</label>
                                                    </div>
                                                    <div>
                                                        <span></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>

                            <div class="flex-col mt-8">
                                <div class="space-x-4">
                                <span class="text-sm font-semibold tracking-wide text-black">Purpose:</span>
                                <span class="text-sm font-semibold tracking-wide text-black ">{{$fuel_request->purpose}}</span>
                                </div>
                                <div class="space-x-5">
                                <span class="text-sm font-semibold tracking-wide text-black">Requested By : / Driver: </span>
                                <span class="text-sm font-semibold tracking-wide text-black">{{$fuel_request->user->name}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-center items-center">
                            <span class="font-bold text-lg tracking-wide">Samson L. Molao</span>

                        </div>
                            <p class="font-normal text-md tracking-wide text-center">University President</p>
                    </div>
                </div>


            </div>

        </div>
        <div class="flex justify-center py-5">
            {{-- <button type="button" value="click" onclick="printDiv('print_to')" id="printto"
                class="w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 max-w-sm rounded-full px-4 py-2 font-semibold tracking-wider text-white active:text-white">
                Print Fuel Requisition Slip
            </button> --}}
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
</div>
