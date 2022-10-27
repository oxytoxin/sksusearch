<div x-data>
    <div x-ref="printContainer" class="max-w-xl bg-white border-2 border-black divide-y-2 divide-black" style="font-family: 'Times New Roman', Times, serif">
        <div class="flex font-bold divide-x-2 divide-black">
            <div class="w-3/5 p-2">
                <h3 class="text-lg text-center">PETTY CASH VOUCHER</h3>
                <p class="flex mt-4"><span class="whitespace-nowrap">Entity Name : </span><span class="flex-1 px-2 mx-2 border-b border-black">&nbsp;{{ $pcv->entity_name }}</span></p>
                <p class="flex"><span class="whitespace-nowrap">Fund Cluster : </span><span class="flex-1 px-2 mx-2 border-b border-black">&nbsp;{{ $pcv->fund_cluster->name }}</span></p>
            </div>
            <div class="flex flex-col items-center w-2/5">
                <p class="flex items-start flex-1"><span class="whitespace-nowrap">No. : </span><span class="min-w-[8rem] px-2 border-b border-black">&nbsp;{{ $pcv->pcv_number }}</span></p>
                <p class="flex items-start flex-1"><span class="whitespace-nowrap">Date : </span><span class="min-w-[8rem] px-2 border-b text-center border-black">&nbsp;{{ $pcv->pcv_date->format('m/d/Y') }}</span></p>
            </div>
        </div>
        <div class="flex font-bold divide-x-2 divide-black">
            <div class="w-3/5 p-2">
                <p class="flex"><span class="whitespace-nowrap">Payee/Office : </span><span class="flex-1 px-2 mx-2 border-b border-black">&nbsp;{{ $pcv->payee }}</span></p>
                <p class="flex"><span class="whitespace-nowrap">Address : </span><span class="flex-1 px-2 mx-2 border-b border-black">&nbsp;{{ $pcv->address }}</span></p>
            </div>
            <div class="flex flex-col items-center w-2/5 py-2 justify-evenly">
                <p class="whitespace-nowrap">Responsibility Center Code: </p>
                <p class="flex"><span class="min-w-[8rem] px-2 border-b text-center border-black">&nbsp;{{ $pcv->responsibility_center }}</span></p>
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
                        @foreach ($pcv->particulars as $particular)
                            <tr class="divide-x-2 divide-black">
                                <td class="px-2 text-left">{{ $particular['name'] }}</td>
                                <td class="px-2 text-right">{{ number_format($particular['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                        @if (7 - count($pcv->particulars) > 0)
                            @foreach (range(0, 7 - count($pcv->particulars) - 1) as $item)
                                <tr class="divide-x-2 divide-black">
                                    <td class="text-left">&nbsp;</td>
                                    <td class="text-right">&nbsp;</td>
                                </tr>
                            @endforeach
                        @endif

                        <tr class="divide-x-2 divide-black">
                            <td class="px-2 text-left">Total</td>
                            <td class="px-2 text-right">{{ number_format($pcv->amount_granted, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex-1 py-4 space-y-4 text-sm">
                <div class="flex items-end px-2">
                    <p class="flex-1 text-center">Total Amount Granted</p>
                    <p class="flex-1 text-right border-b border-black">{{ $pcv->is_liquidated ? number_format($pcv->amount_granted, 2) : '' }}</p>
                </div>
                <div class="flex items-end px-2 justify-evenly">
                    <p class="flex-1 text-center">Total Amount Paid per OR/Invoice No.</p>
                    <p class="flex-1 text-right border-b border-black">{{ $pcv->is_liquidated ? number_format($pcv->amount_paid, 2) : '' }}</p>
                </div>
                <div class="flex items-end px-2 justify-evenly">
                    <p class="flex-1 text-center">Amount Refunded / (Reimbursed)</p>
                    <p class="flex-1 text-right border-b border-black">{{ $pcv->is_liquidated ? number_format($pcv->net_amount, 2) : '' }}</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 grid-rows-2 border-collapse">
            <div class="min-h-[16rem] flex flex-col border-black border-r border-b">
                <div>
                    <div class="flex gap-2">
                        <div class="grid w-6 h-6 border-b border-r border-black place-items-center">A</div>
                        <p class="italic">Requested by:</p>
                    </div>
                    <div class="w-3/4 mx-auto mt-4 text-center">
                        <div class="border-b border-black">&nbsp;{{ $pcv->requisitioner->employee_information->full_name }}</div>
                        <p>Signature over Printed Name</p>
                    </div>
                </div>
                <div class="flex-1"></div>
                <div class="mt-4">
                    <div class="flex gap-2">
                        <div class="grid w-6 h-6 place-items-center">&nbsp;</div>
                        <p class="italic">Approved by:</p>
                    </div>
                    <div class="w-3/4 mx-auto mt-4 mb-4 text-center">
                        <div class="border-b border-black">&nbsp;{{ $pcv->signatory->employee_information->full_name }}</div>
                        <p>Signature over Printed Name</p>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class="min-h-[16rem] flex flex-col border-black border-b border-l">
                <div class="grid w-6 h-6 border-b border-r border-black place-items-center">C</div>
                <div class="flex gap-2 pl-8 mt-2 italic">
                    <div class="relative">
                        <div class="w-8 h-6 border-2 border-black @if ($pcv->is_liquidated && $pcv->net_amount > 0) bg-primary-400 @endif">&nbsp;</div>
                        @if ($pcv->is_liquidated && $pcv->net_amount > 0)
                            <x-ri-check-line class="absolute top-0 left-1" />
                        @endif
                    </div>
                    <p>Received Refund</p>
                </div>
                <div class="flex gap-2 pl-8 mt-2 italic">
                    <div class="relative">
                        <div class="w-8 h-6 border-2 border-black @if ($pcv->is_liquidated && $pcv->net_amount < 0) bg-primary-400 @endif">&nbsp;</div>
                        @if ($pcv->is_liquidated && $pcv->net_amount < 0)
                            <x-ri-check-line class="absolute top-0 left-1" />
                        @endif
                    </div>
                    <p>Reimbursement Paid</p>
                </div>
                <div class="flex-1"></div>
                <div class="w-3/4 mx-auto mt-8 mb-4 text-center">
                    <div class="border-b border-black">&nbsp;{{ $pcv->custodian->employee_information->full_name }}</div>
                    <p>Signature over Printed Name</p>
                    <p>Petty Cash Custodian</p>
                </div>
            </div>
            <div class="min-h-[16rem] border-black border-t border-r">
                <div>
                    <div class="flex gap-2">
                        <div class="grid w-6 h-6 border-b border-r border-black place-items-center">B</div>
                        <p class="italic">Paid by:</p>
                    </div>
                    <div class="w-3/4 mx-auto mt-4 mb-4 text-center">
                        <div class="border-b border-black">&nbsp;{{ $pcv->custodian->employee_information->full_name }}</div>
                        <p>Signature over Printed Name</p>
                        <p>Petty Cash Custodian</p>
                    </div>
                </div>
                <div>
                    <div class="flex gap-2">
                        <div class="grid w-6 h-6 place-items-center">&nbsp;</div>
                        <p class="italic">Cash Received by:</p>
                    </div>
                    <div class="w-3/4 mx-auto mt-4 mb-4 text-center">
                        <div class="border-b border-black">&nbsp;{{ $pcv->payee }}</div>
                        <p>Signature over Printed Name</p>
                        <p>Payee</p>
                        <p class="flex justify-center">
                            <span>Date: </span>
                            <span class="border-b border-black min-w-[6rem] ml-2">&nbsp;</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="min-h-[16rem] flex flex-col border-black border-l border-t">
                <div class="grid w-6 h-6 border-b border-r border-black place-items-center">D</div>
                <div class="flex gap-2 pl-8 mt-2">
                    <div class="relative">
                        <div class="w-8 h-6 border-2 border-black @if ($pcv->is_liquidated) bg-primary-400 @endif">&nbsp;</div>
                        @if ($pcv->is_liquidated)
                            <x-ri-check-line class="absolute top-0 left-1" />
                        @endif
                    </div>
                    <p class="italic">Liquidation Submitted</p>
                </div>
                <div class="flex gap-2 pl-8 mt-2">
                    <div class="relative">
                        <div class="w-8 h-6 border-2 border-black @if ($pcv->is_liquidated && $pcv->net_amount < 0) bg-primary-400 @endif">&nbsp;</div>
                        @if ($pcv->is_liquidated && $pcv->net_amount < 0)
                            <x-ri-check-line class="absolute top-0 left-1" />
                        @endif
                    </div>
                    <p class="italic">Reimbursement Received by:</p>
                </div>
                <div class="flex-1"></div>
                <div class="w-3/4 mx-auto mt-4 mb-4 text-center">
                    <div class="border-b border-black">&nbsp;{{ $pcv->payee }}</div>
                    <p>Signature over Printed Name</p>
                    <p>Payee</p>
                    <p class="flex justify-center">
                        <span>Date: </span>
                        <span class="border-b border-black min-w-[6rem] ml-2">&nbsp;</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-end mt-4">
        <x-filament::button @click="printOut($refs.printContainer.outerHTML);">Print</x-filament::button>
    </div>
</div>
