<div class="max-w-xl bg-white divide-y-2 divide-black" style="font-family: 'Times New Roman', Times, serif">
    <div class="flex font-bold divide-x-2 divide-black">
        <div class="w-3/5 p-2">
            <h3 class="text-lg text-center">PETTY CASH VOUCHER</h3>
            <p class="flex mt-4"><span class="whitespace-nowrap">Entity Name : </span><span class="flex-1 px-2 mx-2 border-b border-black">&nbsp;</span></p>
            <p class="flex"><span class="whitespace-nowrap">Fund Cluster : </span><span class="flex-1 px-2 mx-2 border-b border-black">&nbsp;</span></p>
        </div>
        <div class="flex flex-col items-center w-2/5">
            <p class="flex items-start flex-1"><span class="whitespace-nowrap">No. : </span><span class="min-w-[8rem] px-2 border-b border-black">&nbsp;</span></p>
            <p class="flex items-start flex-1"><span class="whitespace-nowrap">Date : </span><span class="min-w-[8rem] px-2 border-b border-black">&nbsp;</span></p>
        </div>
    </div>
    <div class="flex font-bold divide-x-2 divide-black">
        <div class="w-3/5 p-2">
            <p class="flex"><span class="whitespace-nowrap">Payee/Office : </span><span class="flex-1 px-2 mx-2 border-b border-black">&nbsp;</span></p>
            <p class="flex"><span class="whitespace-nowrap">Address : </span><span class="flex-1 px-2 mx-2 border-b border-black">&nbsp;</span></p>
        </div>
        <div class="flex flex-col items-center w-2/5 py-2 justify-evenly">
            <p class="whitespace-nowrap">Responsibility Center Code: </p>
            <p class="flex"><span class="min-w-[8rem] px-2 border-b border-black">&nbsp;</span></p>
        </div>
    </div>
    <div class="flex divide-x-2 divide-black">
        <div class="flex-1 px-2 py-4 ">
            <p class="italic font-bold">I. To be filled out upon request</p>
        </div>
        <div class="flex-1 px-2 py-4 ">
            <p class="italic font-bold">II. To be filled out upon liquidation</p>
        </div>
    </div>
    <div class="flex divide-x-2 divide-black">
        <div class="flex-1">
            <table class="w-full divide-y-2 divide-black">
                <thead class="">
                    <tr class="divide-x-2 divide-black">
                        <th class="w-3/5">Particulars</th>
                        <th class="w-2/5">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-black">
                    <tr class="divide-x-2 divide-black">
                        <td class="px-2 text-left">Representation</td>
                        <td class="px-2 text-right">{{ number_format(2000, 2) }}</td>
                    </tr>
                    <tr class="divide-x-2 divide-black">
                        <td class="text-left">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr class="divide-x-2 divide-black">
                        <td class="text-left">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr class="divide-x-2 divide-black">
                        <td class="text-left">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr class="divide-x-2 divide-black">
                        <td class="text-left">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr class="divide-x-2 divide-black">
                        <td class="text-left">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr class="divide-x-2 divide-black">
                        <td class="text-left">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr class="divide-x-2 divide-black">
                        <td class="px-2 text-left">Total</td>
                        <td class="px-2 text-right">{{ number_format(2000, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex-1 py-4 space-y-4">
            <div class="flex items-end px-2">
                <p class="flex-1 text-center">Total Amount Granted</p>
                <p class="flex-1 text-right border-b border-black">{{ number_format(2000, 2) }}</p>
            </div>
            <div class="flex items-end px-2 justify-evenly">
                <p class="flex-1 text-center">Total Amount Paid per OR/Invoice No.</p>
                <p class="flex-1 text-right border-b border-black">{{ number_format(2000, 2) }}</p>
            </div>
            <div class="flex items-end px-2 justify-evenly">
                <p class="flex-1 text-center">Amount Refunded / (Reimbursed)</p>
                <p class="flex-1 text-right border-b border-black">{{ number_format(0, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 grid-rows-2 border-collapse">
        <div class="min-h-[16rem] border-black border-r border-b">
            <div class="flex gap-2">
                <div class="grid w-6 h-6 border-b border-r border-black place-items-center">A</div>
                <p class="italic">Requested by:</p>
            </div>
        </div>
        <div class="min-h-[16rem] border-black border-b border-l">
            <div class="grid w-6 h-6 border-b border-r border-black place-items-center">C</div>
        </div>
        <div class="min-h-[16rem] border-black border-t border-r">
            <div class="flex gap-2">
                <div class="grid w-6 h-6 border-b border-r border-black place-items-center">B</div>
                <p class="italic">Paid by:</p>
            </div>
        </div>
        <div class="min-h-[16rem] border-black border-l border-t">
            <div class="grid w-6 h-6 border-b border-r border-black place-items-center">D</div>
        </div>
    </div>
</div>
