<div>
    <style>
        /* Screen View - Larger for easy viewing (2x scale) */
        #print_to {
            width: 210mm !important;
            /* 2x the print size for readability */
            max-width: 210mm !important;
            height: auto !important;
            margin: 20px auto !important;
            padding: 15px !important;
            background: white !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
        }

        /* Readable text sizes for screen */
        #print_to * {
            font-size: 10pt !important;
            line-height: 1.4 !important;
        }

        /* Header logo - larger for screen */
        #print_to #header img {
            width: 50px !important;
            height: auto !important;
        }

        #print_to #header {
            padding: 8px !important;
            margin: 4px !important;
        }

        /* Title - readable size */
        #print_to .text-lg {
            font-size: 16pt !important;
            font-weight: bold !important;
        }

        #print_to .font-bold {
            font-size: 11pt !important;
        }

        /* Borders */
        #print_to .border-b-4 {
            border-bottom-width: 2px !important;
            padding: 8px !important;
        }

        /* Spacing adjustments for screen */
        #print_to .mt-8 {
            margin-top: 8px !important;
        }

        #print_to .mt-14 {
            margin-top: 14px !important;
        }

        #print_to .mb-6 {
            margin-bottom: 6px !important;
        }

        #print_to .p-6 {
            padding: 8px !important;
        }

        #print_to .pt-4 {
            padding-top: 6px !important;
        }

        #print_to .px-6 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        #print_to .m-2 {
            margin: 4px !important;
        }

        #print_to .ml-3 {
            margin-left: 6px !important;
        }

        #print_to .space-x-4>*+* {
            margin-left: 8px !important;
        }

        #print_to .space-x-5>*+* {
            margin-left: 10px !important;
        }

        #print_to .space-x-8>*+* {
            margin-left: 12px !important;
        }

        /* Table - readable on screen */
        #print_to table {
            border-collapse: collapse !important;
            font-size: 10pt !important;
        }

        #print_to table th,
        #print_to table td {
            padding: 6px !important;
            font-size: 10pt !important;
        }

        /* Checkbox size - visible on screen */
        #print_to input[type="checkbox"] {
            width: 14px !important;
            height: 14px !important;
            margin: 0 4px !important;
        }

        /* Print specific styles - LOCKED to 1/4 A4 Size */
        @media print {
            @page {
                size: 105mm 148.5mm;
                /* Exact 1/4 A4 (A6 size) */
                margin: 0;
                /* No margins - we control them */
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* Hide everything except print_to div */
            body * {
                visibility: hidden !important;
            }

            /* Show only the print slip and its contents */
            #print_to,
            #print_to * {
                visibility: visible !important;
            }

            html {
                width: 105mm !important;
                height: 148.5mm !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
            }

            body {
                width: 105mm !important;
                height: 148.5mm !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
                position: relative !important;
                background: white !important;
            }

            /* Force wrapper to be invisible and take no space */
            body>div {
                all: unset !important;
                display: block !important;
                width: 105mm !important;
                height: 148.5mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Lock the print container to EXACT dimensions */
            #print_to {
                width: 105mm !important;
                min-width: 105mm !important;
                max-width: 105mm !important;
                height: 148.5mm !important;
                min-height: 148.5mm !important;
                max-height: 148.5mm !important;
                margin: 0 !important;
                padding: 3mm !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: white !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                overflow: hidden !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
                box-sizing: border-box !important;
                transform: none !important;
                transform-origin: top left !important;
                zoom: 1 !important;
                -webkit-transform: none !important;
                -moz-transform: none !important;
            }

            /* Even smaller text for print to fit everything */
            #print_to * {
                font-size: 5pt !important;
                line-height: 1.1 !important;
                box-sizing: border-box !important;
            }

            #print_to .text-lg,
            #print_to .font-extrabold {
                font-size: 7pt !important;
            }

            #print_to .font-bold {
                font-size: 6pt !important;
            }

            /* Minimize logo even more */
            #print_to #header img {
                width: 12mm !important;
                height: auto !important;
                max-height: 12mm !important;
            }

            /* Reduce all spacing further */
            #print_to .mt-8 {
                margin-top: 1.5mm !important;
            }

            #print_to .mt-14 {
                margin-top: 3mm !important;
            }

            #print_to .mb-6 {
                margin-bottom: 1mm !important;
            }

            #print_to .p-6,
            #print_to .px-6 {
                padding: 1mm !important;
            }

            #print_to .pt-4 {
                padding-top: 0.5mm !important;
            }

            #print_to .m-2 {
                margin: 0.5mm !important;
            }

            #print_to .ml-3 {
                margin-left: 0.5mm !important;
            }

            #print_to .border-b-4 {
                border-bottom-width: 0.5px !important;
                padding: 1mm !important;
            }

            #print_to .space-x-4>*+* {
                margin-left: 1mm !important;
            }

            #print_to .space-x-5>*+* {
                margin-left: 1mm !important;
            }

            #print_to .space-x-8>*+* {
                margin-left: 2mm !important;
            }

            /* Table optimization */
            #print_to table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 5pt !important;
            }

            #print_to table th,
            #print_to table td {
                padding: 0.5mm !important;
                font-size: 5pt !important;
                border-width: 0.5px !important;
            }

            /* Checkbox optimization */
            #print_to input[type="checkbox"] {
                width: 2mm !important;
                height: 2mm !important;
                margin: 0 0.5mm !important;
            }

            /* Hide print button and extra container */
            button,
            .flex.justify-center.py-5 {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                height: 0 !important;
                width: 0 !important;
                position: absolute !important;
            }

            /* Prevent page breaks within content */
            #print_to table,
            #print_to .flex-col {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            /* Prevent any scaling from browser */
            @page {
                size: 105mm 148.5mm;
                margin: 0;
            }

            /* Override any print dialog scaling attempts */
            html {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
    <div id="print_to" class="bg-gray-50 col-span-2 print-bg-white border border-gray-800">
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
                                class="mx-auto mb-6 text-lg font-extrabold uppercase tracking-wide text-black print:text-lg">Fuel
                                Requisition Slip</span>
                        </div>
                        <div class="flex justify-between">
                            <div class="space-x-4">
                                <div class="">
                                    <span class="mr-8 text-sm font-semibold tracking-wide text-black">Date:</span>
                                    <span
                                        class="text-sm font-semibold tracking-wide text-black ">{{ Carbon\Carbon::parse($request->created_at)->format('F d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-start">
                            <div class="space-x-4">
                                <div class="grid grid-cols-2">
                                    <span class="col-span-1 text-sm font-semibold tracking-wide text-red-600">Slip
                                        No.</span>
                                    <span
                                        class="col-span-1 text-sm font-semibold tracking-wide text-black ">{{ $request->slip_number }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-col mt-8">
                            <div class="space-x-4">
                                <span class="text-sm font-semibold tracking-wide text-black">Name of Supplier:</span>
                                <span
                                    class="text-sm font-semibold tracking-wide text-black ">{{ $request->supplier->name }}</span>
                            </div>
                            <div class="space-x-5">
                                <span class="text-sm font-semibold tracking-wide text-black">Address:</span>
                                <span
                                    class="text-sm font-semibold tracking-wide text-black">{{ $request->supplier->address }}</span>
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
                                            <span>{{ $request->article === 'Gasoline' || $request->article === 'Diesel' ? $request->quantity : '' }}</span>
                                        </td>
                                        <td class="border-r border-t border-gray-800 text-center">
                                            <span>{{ $request->article === 'Gasoline' || $request->article === 'Diesel' ? $request->unit : '' }}</span>
                                        </td>
                                        <td class="border-t border-gray-800 text-center">
                                            <div class="flex space-x-8 justify-center items-center">
                                                <div>
                                                    <input disabled
                                                        {{ $request->article === 'Gasoline' ? 'checked' : '' }}
                                                        type="checkbox">
                                                    <label for="">Gasoline</label>
                                                </div>
                                                <div>
                                                    <input disabled
                                                        {{ $request->article === 'Diesel' ? 'checked' : '' }}
                                                        type="checkbox">
                                                    <label for="">Diesel</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="p-2 ">
                                        <td class="border-r border-t border-gray-800 text-center">
                                            <span>{{ $request->article === 'Others' ? $request->quantity : '' }}</span>
                                        </td>
                                        <td class="border-r border-t border-gray-800 text-center">
                                            <span>{{ $request->article === 'Others' ? $request->unit : '' }}</span>
                                        </td>
                                        <td class="border-t border-gray-800 text-center">
                                            <div class="flex space-x-8 justify-center items-center">
                                                <div>
                                                    <input disabled
                                                        {{ $request->article === 'Others' ? 'checked' : '' }}
                                                        type="checkbox">
                                                    <label for="">Others :
                                                        {{ $request->article === 'Others' ? $request->other_article : '' }}</label>
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
                                    <span
                                        class="text-sm font-semibold tracking-wide text-black ">{{ $request->purpose }}</span>
                                </div>
                                <div class="space-x-5">
                                    <span class="text-sm font-semibold tracking-wide text-black">Requested By : /
                                        Driver: </span>
                                    <span
                                        class="text-sm font-semibold tracking-wide text-black">{{ $request->user->name }}</span>
                                </div>
                            </div>
                        </div>
                        @php
                            $president = App\Models\EmployeeInformation::where('position_id', 34)
                                ->where('office_id', 51)
                                ->first();
                        @endphp
                        <div class="mt-14 flex justify-center items-center">
                            <span class="font-bold text-lg tracking-wide">{{ $president->full_name }}</span>

                        </div>
                        <p class="font-normal text-md tracking-wide text-center">University President</p>
                    </div>
                </div>


            </div>

        </div>

    </div>
    <div class="flex justify-center py-5">
        <button type="button" value="click" onclick="printDiv('print_to')" id="printto"
            class="w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 max-w-sm rounded-full px-4 py-2 font-semibold tracking-wider text-white active:text-white">
            Print Fuel Requisition Slip
        </button>
    </div>

    <script>
        function printDiv(divId) {
            const printContent = document.getElementById(divId);
            const windowPrint = window.open('', '', 'width=900,height=1000');

            windowPrint.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Print Fuel Requisition Slip</title>
                    <style>
                        /* Screen preview - larger for viewing */
                        body {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            min-height: 100vh;
                            background: #f3f4f6;
                            padding: 20px;
                        }

                        #${divId} {
                            transform: scale(2);
                            transform-origin: center;
                            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        }

                        /* Print styles - LOCKED to 1/4 A4 */
                        @media print {
                            body {
                                background: white;
                                padding: 0;
                                display: block;
                            }

                            #${divId} {
                                transform: scale(1) !important;
                                box-shadow: none !important;
                            }
                        }

                        @page {
                            size: 105mm 148.5mm;
                            margin: 0;
                        }

                        * {
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                            color-adjust: exact !important;
                            box-sizing: border-box !important;
                        }

                        html, body {
                            width: 105mm !important;
                            height: 148.5mm !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            overflow: hidden !important;
                            background: white !important;
                        }

                        #${divId} {
                            width: 105mm !important;
                            min-width: 105mm !important;
                            max-width: 105mm !important;
                            height: 148.5mm !important;
                            min-height: 148.5mm !important;
                            max-height: 148.5mm !important;
                            margin: 0 !important;
                            padding: 3mm !important;
                            border: none !important;
                            background: white !important;
                            overflow: hidden !important;
                            box-sizing: border-box !important;
                        }

                        /* Text sizing for print */
                        #${divId} * {
                            font-size: 5pt !important;
                            line-height: 1.1 !important;
                        }

                        #${divId} .text-lg,
                        #${divId} .font-extrabold {
                            font-size: 7pt !important;
                            font-weight: bold !important;
                        }

                        #${divId} .font-bold {
                            font-size: 6pt !important;
                            font-weight: bold !important;
                        }

                        #${divId} .font-semibold {
                            font-weight: 600 !important;
                        }

                        /* Logo */
                        #${divId} img {
                            width: 12mm !important;
                            height: auto !important;
                            max-height: 12mm !important;
                        }

                        /* Spacing */
                        #${divId} .mt-8 { margin-top: 1.5mm !important; }
                        #${divId} .mt-14 { margin-top: 3mm !important; }
                        #${divId} .mb-6 { margin-bottom: 1mm !important; }
                        #${divId} .p-6 { padding: 1mm !important; }
                        #${divId} .px-6 { padding-left: 1mm !important; padding-right: 1mm !important; }
                        #${divId} .pt-4 { padding-top: 0.5mm !important; }
                        #${divId} .m-2 { margin: 0.5mm !important; }
                        #${divId} .ml-3 { margin-left: 0.5mm !important; }
                        #${divId} .space-x-4 > * + * { margin-left: 1mm !important; }
                        #${divId} .space-x-5 > * + * { margin-left: 1mm !important; }
                        #${divId} .space-x-8 > * + * { margin-left: 2mm !important; }

                        /* Borders */
                        #${divId} .border { border: 0.5px solid #1f2937 !important; }
                        #${divId} .border-b-4 { border-bottom: 0.5px solid black !important; padding: 1mm !important; }
                        #${divId} .border-black { border-color: black !important; }
                        #${divId} .border-gray-800 { border-color: #1f2937 !important; }
                        #${divId} .border-r { border-right: 0.5px solid #1f2937 !important; }
                        #${divId} .border-t { border-top: 0.5px solid #1f2937 !important; }

                        /* Layout */
                        #${divId} .flex { display: flex !important; }
                        #${divId} .flex-col { display: flex !important; flex-direction: column !important; }
                        #${divId} .block { display: block !important; }
                        #${divId} .inline { display: inline !important; }
                        #${divId} .justify-between { justify-content: space-between !important; }
                        #${divId} .justify-center { justify-content: center !important; }
                        #${divId} .justify-start { justify-content: flex-start !important; }
                        #${divId} .items-center { align-items: center !important; }
                        #${divId} .items-start { align-items: flex-start !important; }
                        #${divId} .text-center { text-align: center !important; }
                        #${divId} .text-left { text-align: left !important; }
                        #${divId} .uppercase { text-transform: uppercase !important; }
                        #${divId} .tracking-wide { letter-spacing: 0.025em !important; }
                        #${divId} .w-full { width: 100% !important; }
                        #${divId} .my-auto { margin-top: auto !important; margin-bottom: auto !important; }
                        #${divId} .mx-auto { margin-left: auto !important; margin-right: auto !important; }

                        /* Table */
                        #${divId} table {
                            width: 100% !important;
                            border-collapse: collapse !important;
                            font-size: 5pt !important;
                        }

                        #${divId} table th,
                        #${divId} table td {
                            padding: 0.5mm !important;
                            font-size: 5pt !important;
                            border: 0.5px solid #1f2937 !important;
                        }

                        /* Grid */
                        #${divId} .grid { display: grid !important; }
                        #${divId} .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
                        #${divId} .col-span-1 { grid-column: span 1 / span 1 !important; }

                        /* Colors */
                        #${divId} .text-black { color: black !important; }
                        #${divId} .text-red-600 { color: #dc2626 !important; }
                        #${divId} .text-primary-600 { color: #2563eb !important; }

                        /* Checkbox */
                        #${divId} input[type="checkbox"] {
                            width: 2mm !important;
                            height: 2mm !important;
                            margin: 0 0.5mm !important;
                        }

                        /* Prevent page breaks */
                        table, .flex-col {
                            page-break-inside: avoid !important;
                            break-inside: avoid !important;
                        }
                    </style>
                </head>
                <body>
                    ${printContent.outerHTML}
                </body>
                </html>
            `);

            windowPrint.document.close();
            windowPrint.focus();

            setTimeout(() => {
                windowPrint.print();
                windowPrint.close();
            }, 250);
        }
    </script>
</div>
