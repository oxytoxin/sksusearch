<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <table style="width: 100%">
        <thead>
            <tr>
                <th style="font-weight: bold" bgcolor="#82f5ac" width="40" colspan="2">Receipts</th>
                <th style="font-weight: bold" bgcolor="#82f5ac" width="80" colspan="3">Expenditure</th>
                <th style="font-weight: bold" bgcolor="#82f5ac" width="40" colspan="2">Balance</th>
            </tr>
            <tr>
                <td>
                    MFO Fee
                </td>
                <td>
                    Allocation
                </td>
                <td width="15">
                    UACS Code
                </td>
                <td width="50">
                    Account Title
                </td>
                <td width="15">
                    Budget
                </td>
                <td>
                    Programmed
                </td>
                <td>

                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($cost_centers as $cost_center)
                <tr>
                    <td bgcolor="#cccccc" colspan="100%">
                        {{ $cost_center->name }}
                    </td>
                </tr>
                @foreach ($cost_center->fund_allocations as $fund_allocation)
                    <tr>
                        <td>
                            {{ $fund_allocation['name'] }}
                        </td>
                        <td>
                            {{ number_format($fund_allocation['initial_amount'], 2) }}
                        </td>
                        <td colspan="2"></td>
                        <td></td>
                        <td>
                            {{ number_format($cost_center->wfpDetails->where('mfo_fee_id', $fund_allocation['mfo_fee_id'])->sum('total_budget_per_uacs'), 2) }}
                        </td>
                        <td>
                            {{ number_format($fund_allocation['initial_amount'] - $cost_center->wfpDetails->where('mfo_fee_id', $fund_allocation['mfo_fee_id'])->sum('total_budget_per_uacs'), 2) }}
                        </td>
                    </tr>
                    @foreach ($cost_center->wfpDetails->where('mfo_fee_id', $fund_allocation['mfo_fee_id']) as $wfpDetail)
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                {{ $wfpDetail->budget_uacs }}
                            </td>
                            <td>
                                {{ $wfpDetail->budget_name }}
                            </td>
                            <td>
                                {{ number_format($wfpDetail->total_budget_per_uacs, 2) }}
                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="100%" bgcolor="#cccccc"></td>
            </tr>
            <tr>
                <td style="font-weight: bold">
                    Grand Total
                </td>
                <td style="font-weight: bold">
                    {{ number_format($cost_centers->sum('total_initial_amount'), 2) }}
                </td>
                <td colspan="3"></td>
                <td style="font-weight: bold">
                    {{ number_format($cost_centers->sum('totalWfpDetails'), 2) }}
                </td>
                <td style="font-weight: bold">
                    {{ number_format($cost_centers->sum('total_initial_amount') - $cost_centers->sum('totalWfpDetails'), 2) }}
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
