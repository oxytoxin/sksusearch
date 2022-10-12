<div class="flex-col px-4 py-3 space-y-1 text-left w-72">
    
    @foreach ($getRecord()->disbursement_voucher_particulars as $particular)
        <p class="break-words truncate group-hover:whitespace-normal" x-data={} x-tooltip.raw="{{ $particular['purpose'] }}">{{ $particular['purpose'] }}</p>
    @endforeach
 </div>