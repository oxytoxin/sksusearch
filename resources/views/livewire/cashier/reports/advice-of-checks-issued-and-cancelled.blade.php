<div class="bg-white p-6 text-gray-900">
    <div class="text-right italic text-xs">Appendix 39</div>

    <div class="text-center mt-2">
        <div class="text-sm">Entity Name</div>
        <h2 class="text-base font-semibold tracking-wide mt-1">ADVICE OF CHECKS ISSUED AND CANCELLED</h2>
    </div>

    <div class="grid grid-cols-3 gap-6 mt-6 text-sm">
        <div class="col-span-2 space-y-2">
            <div class="flex items-baseline gap-2">
                <span class="font-medium">To:</span>
                <span>The Bank Manager</span>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">Bank Account No. : <span
                        class="inline-block border-b border-gray-500 w-64 align-bottom"></span></div>
                <div>Date : <span class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
            </div>
        </div>
        <div class="border border-gray-800 p-3 space-y-1">
            <div class="flex justify-between"><span>ACIC No. :</span><span
                    class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
            <div class="flex justify-between"><span>Organization Code :</span><span
                    class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
            <div class="flex justify-between"><span>Fund Cluster :</span><span
                    class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
            <div class="flex justify-between"><span>Area Code :</span><span
                    class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
            <div class="flex justify-between"><span>NCA No. :</span><span
                    class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full border border-gray-800 text-xs">
            <thead>
            <tr>
                <th class="border border-gray-800 text-center px-2 py-2">CHECK NO.</th>
                <th class="border border-gray-800 text-center px-2 py-2">DATE OF<br>ISSUE</th>
                <th class="border border-gray-800 text-center px-2 py-2">PAYEE</th>
                <th class="border border-gray-800 text-center px-2 py-2">AMOUNT</th>
                <th class="border border-gray-800 text-center px-2 py-2">UACS<br>OBJECT<br>CODE</th>
                <th colspan="2" class="border border-gray-800 text-center px-2 py-2">FOR GSB USE ONLY</th>
            </tr>
            <tr>
                <th class="border border-gray-800"></th>
                <th class="border border-gray-800"></th>
                <th class="border border-gray-800"></th>
                <th class="border border-gray-800"></th>
                <th class="border border-gray-800"></th>
                <th class="border border-gray-800 text-center px-2 py-1">DATE NEGTD.</th>
                <th class="border border-gray-800 text-center px-2 py-1">REMARKS</th>
            </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < 12; $i++)
                <tr class="h-8">
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800 text-right pr-2"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                </tr>
            @endfor
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="border border-gray-800 text-right font-medium pr-2">Total ACIC Amount</td>
                <td class="border border-gray-800"></td>
                <td class="border border-gray-800" colspan="3"></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-2 text-xs flex gap-8">
        <div>Total number of checks: <span class="inline-block border-b border-gray-500 w-24 align-bottom"></span></div>
        <div>Amount in words <span class="inline-block border-b border-gray-500 w-96 align-bottom"></span></div>
    </div>

    <div class="grid grid-cols-2 gap-6 mt-8">
        <div>
            <div class="text-center font-semibold text-sm border border-gray-800 py-1">CANCELLED CHECK</div>
            <table class="min-w-full border border-gray-800 text-xs">
                <thead>
                <tr>
                    <th class="border border-gray-800 text-center px-2 py-1">Check No.</th>
                    <th class="border border-gray-800 text-center px-2 py-1">Date Issued</th>
                    <th class="border border-gray-800 text-center px-2 py-1">Remarks</th>
                </tr>
                </thead>
                <tbody>
                @for ($i = 0; $i < 5; $i++)
                    <tr class="h-8">
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                        <td class="border border-gray-800"></td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
        <div class="grid grid-rows-2 gap-4">
            <div class="border border-gray-800 text-xs">
                <div class="grid grid-cols-2 divide-x divide-gray-800">
                    <div class="p-2">
                        <div class="font-medium">Certified Correct By:</div>
                        <div class="mt-6 border-b border-gray-500"></div>
                        <div class="mt-1">Signature over Printed Name of Disbursing Officer/Cashier/Head of
                            Cash/Treasury Unit
                        </div>
                    </div>
                    <div class="p-2">
                        <div class="font-medium">Received by:</div>
                        <div class="mt-6 border-b border-gray-500"></div>
                        <div class="mt-1">Signature over Printed Name of GSB personnel who received the ACIC</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 divide-x divide-gray-800 border-t border-gray-800">
                    <div class="p-2">
                        <div class="font-medium">Approved by:</div>
                        <div class="mt-6 border-b border-gray-500"></div>
                        <div class="mt-1">Signature over Printed Name of Head of Office/Unit or his/her authorized
                            representative
                        </div>
                    </div>
                    <div class="p-2">
                        <div class="font-medium">Delivered by:</div>
                        <div class="mt-6 border-b border-gray-500"></div>
                        <div class="mt-1">Signature over Printed Name of Agency personnel who delivered the ACIC to the
                            GSB
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="text-center tracking-widest text-xs font-semibold">REPORT SUMMARY</div>
        <div class="grid grid-cols-2 gap-8 text-sm mt-3">
            <div class="space-y-2">
                <div>Number of ACIC(s) : <span class="inline-block border-b border-gray-500 w-40 align-bottom"></span>
                </div>
                <div>Grand Total : <span class="inline-block border-b border-gray-500 w-40 align-bottom"></span></div>
                <div>Amount in Words : <span class="inline-block border-b border-gray-500 w-80 align-bottom"></span>
                </div>
            </div>
            <div class="space-y-6 text-xs">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="font-medium">Certified Correct by :</div>
                        <div class="mt-6 border-b border-gray-500"></div>
                        <div class="mt-1">Signature over Printed Name of Disbursing Officer/Cashier/Head of
                            Cash/Treasury Unit
                        </div>
                    </div>
                    <div>
                        <div class="font-medium">Received by :</div>
                        <div class="mt-6 border-b border-gray-500"></div>
                        <div class="mt-1">Signature over Printed Name of GSB personnel who received the ACIC</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="font-medium">Approved by :</div>
                        <div class="mt-6 border-b border-gray-500"></div>
                        <div class="mt-1">Signature over Printed Name of Head of Office/Unit or his/her authorized</div>
                    </div>
                    <div>
                        <div class="font-medium">Delivered by :</div>
                        <div class="mt-6 border-b border-gray-500"></div>
                        <div class="mt-1">Signature over Printed Name of Agency personnel who delivered the ACIC to the
                            GSB
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
