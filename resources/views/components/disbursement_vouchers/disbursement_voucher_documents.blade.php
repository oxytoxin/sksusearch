<div>
    <div x-data="{ tab: 'related_docs' }">
        <div class="flex">
            <button :class="tab == 'related_docs' ? 'bg-primary-400 text-white' : ''" class="p-2 border border-collapse" @click="tab = 'related_docs'" type="button">Related Documents</button>
            <button :class="tab == 'verified_docs' ? 'bg-primary-400 text-white' : ''" class="p-2 border border-collapse" @click="tab = 'verified_docs'" type="button">Documents Verified by ICU</button>
        </div>
        <div class="p-4 border border-collapse" x-show="tab=='related_docs'">
            @include('components.disbursement_vouchers.related_documents', [
                'voucher_subtype' => $disbursement_voucher->voucher_subtype,
            ])
        </div>
        <div class="p-4 border border-collapse" x-show="tab=='verified_docs'">
            @include('components.disbursement_vouchers.icu_verified_documents', [
                'disbursement_voucher' => $disbursement_voucher,
            ])
        </div>
    </div>
</div>
