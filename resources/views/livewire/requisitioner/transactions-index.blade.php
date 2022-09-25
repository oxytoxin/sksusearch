<div>
    <div class="flex gap-4">
        <a class="underline" href="{{ route('requisitioner.travel-orders.create') }}">Create Travel Order</a>
        <a class="underline" href="{{ route('requisitioner.itenerary.create') }}">Create Itenerary</a>
    </div>
    @foreach ($categories as $category)
        <h4>{{ $category->name }}</h4>
        @foreach ($category->voucher_types as $type)
            @if ($type->voucher_subtypes->count() == 1)
                <a href="{{ route('requisitioner.disbursement-vouchers.create', ['voucher_subtype' => $type->voucher_subtypes->first()->id]) }}">
                    <h5 class="px-6">{{ $type->name }}</h5>
                </a>
            @else
                <h5 class="px-6">{{ $type->name }}</h5>
                @foreach ($type->voucher_subtypes as $subtype)
                    <a href="{{ route('requisitioner.disbursement-vouchers.create', ['voucher_subtype' => $subtype->id]) }}">
                        <h6 class="px-12">{{ $subtype->name }}</h6>
                    </a>
                @endforeach
            @endif
        @endforeach
    @endforeach
</div>
