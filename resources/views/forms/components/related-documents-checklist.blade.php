<x-forms::field-wrapper :id="$getId()" :label="$getLabel()" :label-sr-only="$isLabelHidden()" :helper-text="$getHelperText()" :hint="$getHint()" :hint-icon="$getHintIcon()" :required="$isRequired()" :state-path="$getStatePath()">
    <div
        x-data="{
            documents: @js($getDocuments()),
            items: @entangle($getStatePath()).defer,
            expanded: {},
            init() {
                // Seed items array from documents list if empty or out of sync
                const seeded = this.documents.map(doc => {
                    const existing = (this.items || []).find(i => i && i.document === doc);
                    return {
                        document: doc,
                        status: existing?.status ?? 'required',
                        remarks: existing?.remarks ?? '',
                    };
                });
                this.items = seeded;
                // Auto-expand notes that already have content
                this.items.forEach((item, i) => {
                    if (item.remarks) {
                        this.expanded[i] = true;
                    }
                });
            },
            setStatus(index, status) {
                this.items[index].status = status;
            },
            isChecked(index, status) {
                return this.items?.[index]?.status === status;
            },
            isExpanded(index) {
                return this.expanded[index] || (this.items?.[index]?.remarks ?? '').length > 0;
            },
            openNote(index) {
                this.expanded[index] = true;
                this.$nextTick(() => {
                    const ta = document.getElementById('rdc-note-' + index);
                    if (ta) ta.focus();
                });
            },
            closeNote(index) {
                this.expanded[index] = false;
            },
        }"
        class="space-y-3"
    >
        {{-- Legend --}}
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 px-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg">
            <span class="font-semibold uppercase tracking-wide">Legend:</span>
            <span class="flex items-center gap-1">
                <span class="inline-block w-4 h-4 border-2 border-primary-600 bg-primary-600 rounded text-white text-center leading-none text-[10px] font-bold">✓</span>
                Required (verified present)
            </span>
            <span class="flex items-center gap-1">
                <span class="inline-block w-4 h-4 border-2 border-red-500 bg-red-500 rounded text-white text-center leading-none text-[10px] font-bold">−</span>
                For Compliance
            </span>
            <span class="flex items-center gap-1">
                <span class="inline-block w-4 h-4 border-2 border-amber-500 bg-amber-500 rounded text-white text-center leading-none text-[10px] font-bold">✗</span>
                Not Applicable
            </span>
        </div>

        {{-- Rows --}}
        <div class="border border-gray-200 rounded-lg divide-y divide-gray-200">
            <template x-for="(item, index) in items" :key="index">
                <div class="p-3 hover:bg-gray-50">
                    <div class="grid grid-cols-12 gap-3 items-center">
                        {{-- 3 status boxes --}}
                        <div class="col-span-2 flex items-center gap-1.5">
                            <button type="button"
                                @click="setStatus(index, 'required')"
                                :class="isChecked(index, 'required') ? 'bg-primary-600 border-primary-600 text-white' : 'bg-white border-gray-300 text-transparent hover:border-primary-400'"
                                class="w-6 h-6 border-2 rounded flex items-center justify-center text-xs font-bold transition-colors"
                                title="Required (verified present)">
                                ✓
                            </button>
                            <button type="button"
                                @click="setStatus(index, 'not_required')"
                                :class="isChecked(index, 'not_required') ? 'bg-red-500 border-red-500 text-white' : 'bg-white border-gray-300 text-transparent hover:border-red-400'"
                                class="w-6 h-6 border-2 rounded flex items-center justify-center text-xs font-bold transition-colors"
                                title="For Compliance">
                                −
                            </button>
                            <button type="button"
                                @click="setStatus(index, 'not_applicable')"
                                :class="isChecked(index, 'not_applicable') ? 'bg-amber-500 border-amber-500 text-white' : 'bg-white border-gray-300 text-transparent hover:border-amber-400'"
                                class="w-6 h-6 border-2 rounded flex items-center justify-center text-xs font-bold transition-colors"
                                title="Not Applicable">
                                ✗
                            </button>
                        </div>

                        {{-- Document name --}}
                        <div class="col-span-7 text-sm text-gray-800 leading-snug" x-text="item.document"></div>

                        {{-- Note button or note preview --}}
                        <div class="col-span-3 flex justify-end">
                            <template x-if="!isExpanded(index)">
                                <button type="button"
                                    @click="openNote(index)"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs border border-gray-300 text-gray-600 bg-white hover:bg-gray-100 rounded-md transition-colors">
                                    <span>+</span>
                                    <span>Add note</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Expanded note field --}}
                    <div x-show="isExpanded(index)" x-cloak class="mt-2 pl-[calc(16.6667%+0.75rem)]">
                        <div class="relative">
                            <textarea
                                :id="'rdc-note-' + index"
                                x-model="item.remarks"
                                rows="2"
                                placeholder="Note about this document..."
                                class="block w-full text-xs border-gray-300 rounded-md shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 resize-y pr-7"></textarea>
                            <button type="button"
                                @click="closeNote(index)"
                                x-show="!item.remarks"
                                class="absolute top-1 right-1 w-5 h-5 text-gray-400 hover:text-gray-700"
                                title="Close">
                                ×
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Empty state --}}
            <template x-if="!documents || documents.length === 0">
                <div class="p-4 text-sm text-center text-gray-500">
                    No documentary requirements defined for this voucher type.
                </div>
            </template>
        </div>
    </div>
</x-forms::field-wrapper>
