<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acknowledgment Receipt — {{ $transmittal->transmittal_number }}</title>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}">
</head>
<body class="p-8 text-gray-900">
    {{-- Letterhead (mirrors the CTC print) --}}
    <div class="flex justify-between w-full pb-4 mb-4 border-b-4 border-black">
        <div class="flex ml-3 text-left">
            <div class="my-auto">
                <img class="object-scale-down w-20 h-full" src="{{ asset('images/sksulogo.png') }}" alt="sksu logo">
            </div>
            <div class="flex flex-col my-auto ml-3">
                <span class="text-sm font-semibold">Republic of the Philippines</span>
                <span class="text-sm font-semibold uppercase text-primary-600">Sultan Kudarat State University</span>
                <span class="text-sm font-semibold">ACCESS, EJC Montilla, 9800 City of Tacurong</span>
                <span class="text-sm font-semibold">Province of Sultan Kudarat</span>
            </div>
        </div>
    </div>

    @php $itemCount = $transmittal->disbursement_vouchers->count(); @endphp

    <div class="text-center my-4">
        <h2 class="text-xl font-bold uppercase tracking-wide">Acknowledgment Receipt</h2>
        <p class="text-sm">{{ $itemCount === 1 ? 'Individual Transmittal' : 'Batch Transmittal' }} — No. {{ $transmittal->transmittal_number }}</p>
    </div>

    <p class="text-sm mb-4 leading-relaxed">
        This is to acknowledge receipt of the following Disbursement Voucher{{ $itemCount === 1 ? '' : 's' }}
        transmitted to <span class="font-semibold">{{ $transmittal->recipient }}</span>
        on {{ optional($transmittal->created_at)->format('F d, Y') }}.
    </p>

    <table class="w-full text-left border border-gray-400 text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-3 py-2 border border-gray-400">#</th>
                <th class="px-3 py-2 border border-gray-400">Tracking No.</th>
                <th class="px-3 py-2 border border-gray-400">Disbursement Sub Type</th>
                <th class="px-3 py-2 border border-gray-400">Payee</th>
                <th class="px-3 py-2 border border-gray-400 text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transmittal->disbursement_vouchers as $i => $dv)
                <tr>
                    <td class="px-3 py-2 border border-gray-400">{{ $i + 1 }}</td>
                    <td class="px-3 py-2 border border-gray-400">{{ $dv->tracking_number }}</td>
                    <td class="px-3 py-2 border border-gray-400">{{ $dv->voucher_subtype?->name ?? 'N/A' }}</td>
                    <td class="px-3 py-2 border border-gray-400">{{ $dv->payee ?? 'N/A' }}</td>
                    <td class="px-3 py-2 border border-gray-400 text-right">₱{{ number_format($dv->disbursement_voucher_particulars->sum('amount'), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="grid grid-cols-2 gap-8 mt-16 text-sm">
        <div>
            <p class="mb-10">Released by:</p>
            <p class="font-semibold border-t border-gray-500 pt-1">{{ $transmittal->prepared_by_user->employee_information->full_name ?? '' }}</p>
            <p class="text-xs text-gray-500">Prepared By</p>
        </div>
        <div>
            <p class="mb-10">Acknowledged / Received by:</p>
            <p class="font-semibold border-t border-gray-500 pt-1">{{ $transmittal->acknowledged_by ?? '' }}</p>
            <p class="text-xs text-gray-500">
                Received By
                @if ($transmittal->acknowledged_at)
                    — {{ $transmittal->acknowledged_at->format('F d, Y g:i A') }}
                @endif
            </p>
        </div>
    </div>

    <div class="mt-10 text-center print:hidden">
        <button onclick="window.print()" class="bg-primary-600 text-white px-6 py-2 rounded">Print</button>
    </div>
</body>
</html>
