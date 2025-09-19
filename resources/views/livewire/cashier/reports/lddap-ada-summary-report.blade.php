<div class="bg-white p-6 text-gray-900">
    <div class="text-right italic text-xs">Appendix 42</div>

    <div class="text-center mt-2">
        <h2 class="text-base font-semibold tracking-wide">LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE - ADVICE TO DEBIT ACCOUNTS (LDDAP-ADA)</h2>
    </div>

    <div class="grid grid-cols-3 gap-6 mt-6 text-sm">
        <div class="space-y-1">
            <div>Department : <span class="inline-block border-b border-gray-500 w-64 align-bottom"></span></div>
            <div>Entity Name : <span class="inline-block border-b border-gray-500 w-64 align-bottom"></span></div>
            <div>Operating Unit : <span class="inline-block border-b border-gray-500 w-64 align-bottom"></span></div>
        </div>
        <div></div>
        <div class="border border-gray-800 p-3 space-y-1">
            <div class="flex justify-between"><span>LDDAP-ADA No. :</span><span class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
            <div class="flex justify-between"><span>Date :</span><span class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
            <div class="flex justify-between"><span>Fund Cluster :</span><span class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
        </div>
    </div>

    <div class="mt-3 text-sm">
        <div>MDS-GSB BRANCH/MDS SUB ACCOUNT NO.: <span class="inline-block border-b border-gray-500 w-80 align-bottom"></span></div>
    </div>

    <div class="mt-4">
        <div class="text-center font-semibold text-sm border border-gray-800 py-1">I. LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE (LDDAP)</div>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-800 text-[11px]">
                <thead>
                <tr>
                    <th rowspan="2" class="border border-gray-800 text-center px-2 py-2 w-64">CREDITOR<br><span class="font-normal">NAME / PREFERRED SERVICING BANKS/SAVINGS/CURRENT ACCOUNT NO.</span></th>
                    <th rowspan="2" class="border border-gray-800 text-center px-2 py-2 w-40">Obligation Request and Status No.</th>
                    <th rowspan="2" class="border border-gray-800 text-center px-2 py-2 w-24">ALLOTMENT CLASS per (UACS)</th>
                    <th colspan="3" class="border border-gray-800 text-center px-2 py-2">In Pesos</th>
                    <th rowspan="2" class="border border-gray-800 text-center px-2 py-2 w-28">REMARKS<br><span class="font-normal">FOR MDS-GSB USE ONLY</span></th>
                </tr>
                <tr>
                    <th class="border border-gray-800 text-center px-2 py-1 w-24">GROSS AMOUNT</th>
                    <th class="border border-gray-800 text-center px-2 py-1 w-24">WITHHOLDING TAX</th>
                    <th class="border border-gray-800 text-center px-2 py-1 w-24">NET AMOUNT</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="7" class="border border-gray-800 font-medium px-2 py-1">I. Current Year A/Ps</td>
                </tr>
                @for ($i = 0; $i < 8; $i++)
                    <tr class="h-8">
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                    </tr>
                @endfor
                <tr>
                    <td class="border border-gray-800 text-right px-2 py-1 font-medium" colspan="3">Sub-total</td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                </tr>
                <tr>
                    <td colspan="7" class="border border-gray-800 font-medium px-2 py-1">II. Prior Year's A/Ps</td>
                </tr>
                @for ($i = 0; $i < 4; $i++)
                    <tr class="h-8">
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                    </tr>
                @endfor
                <tr>
                    <td class="border border-gray-800 text-right px-2 py-1 font-medium" colspan="3">Sub-total</td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td class="border border-gray-800 text-right px-2 py-1 font-semibold" colspan="3">TOTAL</td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mt-6 text-[11px]">
        <div class="border border-gray-800 p-3 h-full">
            <div class="leading-5">I hereby warrant that the above List of Due and Demandable A/Ps was prepared in accordance with existing budgeting, accounting and auditing rules and regulations.</div>
            <div class="mt-8">
                <div class="border-b border-gray-500 w-64"></div>
                <div class="mt-1">Certified Correct:</div>
                <div class="mt-1">(Signature over Printed Name)</div>
                <div>Head of Accounting Division/Unit</div>
            </div>
        </div>
        <div class="border border-gray-800 p-3 h-full">
            <div class="leading-5">I hereby assume full responsibility for the veracity and accuracy of the listed claims, and the authenticity of the supporting documents as submitted by the claimants.</div>
            <div class="mt-8">
                <div class="border-b border-gray-500 w-64"></div>
                <div class="mt-1">Approved:</div>
                <div class="mt-1">(Signature over Printed Name)</div>
                <div>Head of Agency or Authorized Official</div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="text-center font-semibold text-sm border border-gray-800 py-1">II. ADVICE TO DEBIT ACCOUNT (ADA)</div>
        <div class="border border-gray-800 p-3 text-sm space-y-2">
            <div>To: MDS-GSB of the Agency</div>
            <div>Please debit MDS Sub-Account Number : <span class="inline-block border-b border-gray-500 w-64 align-bottom"></span></div>
            <div>Please credit the accounts of the above listed creditors to cover payment of accounts payable</div>
            <div class="grid grid-cols-2 gap-6 mt-2">
                <div class="flex items-baseline gap-2"><span class="font-medium">TOTAL AMOUNT :</span> <span class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
                <div></div>
                <div class="col-span-2">(In Words) <span class="inline-block border-b border-gray-500 w-[32rem] align-bottom"></span></div>
            </div>
            <div class="mt-6">
                <div class="text-center font-medium">Agency Authorized Signatories</div>
                <div class="grid grid-cols-2 gap-12 mt-4 text-xs">
                    <div>
                        <div class="border-b border-gray-500 w-full"></div>
                        <div class="mt-1 text-center">1.</div>
                    </div>
                    <div>
                        <div class="border-b border-gray-500 w-full"></div>
                        <div class="mt-1 text-center">2.</div>
                    </div>
                </div>
                <div class="text-center italic text-[10px] mt-6">(Erasures shall invalidate this document)</div>
            </div>
        </div>
    </div>
</div>
