<x-filament::page>
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-2">
            <x-filament::card>
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Select Existing DV</h2>
                        <p class="text-sm text-gray-500">Search by tracking number, payee, requisitioner, DV number, or current step.</p>
                    </div>

                    <form wire:submit.prevent="selectVoucher" class="space-y-4">
                        {{ $this->selectVoucherForm }}

                        <x-filament::button type="submit">
                            Load Disbursement Voucher
                        </x-filament::button>
                    </form>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Quick Create Test DV</h2>
                        <p class="text-sm text-gray-500">Defaults are pre-filled for a realistic workflow test and can be edited before creation.</p>
                    </div>

                    <form wire:submit.prevent="quickCreate" class="space-y-4">
                        {{ $this->quickCreateForm }}

                        <x-filament::button type="submit">
                            Create And Load Test DV
                        </x-filament::button>
                    </form>
                </div>
            </x-filament::card>
        </div>

        @if ($this->selectedVoucher)
            @php
                $voucher = $this->selectedVoucher;
                $stepLabel = trim(($voucher->current_step->process ?? '').' '.($voucher->current_step->recipient ?? ''));
            @endphp

            <x-filament::card>
                <div class="space-y-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h2 class="text-xl font-bold tracking-tight">{{ $voucher->tracking_number }}</h2>
                            <p class="text-sm text-gray-500">{{ $voucher->voucher_subtype->voucher_type->name ?? 'Voucher' }} - {{ $voucher->voucher_subtype->name ?? 'Unknown subtype' }}</p>
                        </div>

                        <div class="rounded-md bg-primary-50 px-3 py-2 text-sm font-medium text-primary-700">
                            {{ $stepLabel }}
                        </div>
                    </div>

                    <dl class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Requisitioner</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $voucher->user->employee_information->full_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Signatory</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $voucher->signatory->employee_information->full_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Payee</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $voucher->payee ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Amount</dt>
                            <dd class="text-sm font-semibold text-gray-900">PHP {{ number_format($voucher->totalSumDisbursementVoucherParticular(), 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">DV Number</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $voucher->dv_number ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">ORS/BURS</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $voucher->ors_burs ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Cheque/ADA</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $voucher->cheque_number ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Pending Return</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $voucher->pending_return_step->recipient ?? 'None' }}</dd>
                        </div>
                    </dl>
                </div>
            </x-filament::card>

            <div class="grid gap-6 xl:grid-cols-3">
                <div class="space-y-6 xl:col-span-2">
                    <x-filament::card>
                        <div class="space-y-4">
                            <div>
                                <h2 class="text-lg font-bold tracking-tight">Available Workflow Actions</h2>
                                <p class="text-sm text-gray-500">Actions here run quietly for testing and do not send SMS or realtime notifications.</p>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                @if ($this->canReceive())
                                    <x-filament::button wire:click="receive">
                                        Receive
                                    </x-filament::button>
                                @endif

                                @if ($this->canCertify())
                                    <x-filament::button wire:click="certify" color="success">
                                        Certify
                                    </x-filament::button>
                                @endif
                            </div>

                            @if ($this->canForward())
                                <div class="rounded-md border border-gray-200 p-4">
                                    <form wire:submit.prevent="forward" class="space-y-4">
                                        <h3 class="text-sm font-bold">Forward</h3>
                                        {{ $this->forwardForm }}
                                        <x-filament::button type="submit">
                                            Forward
                                        </x-filament::button>
                                    </form>
                                </div>
                            @endif

                            @if ($this->canReturn())
                                <div class="rounded-md border border-danger-200 p-4">
                                    <form wire:submit.prevent="returnDocument" class="space-y-4">
                                        <h3 class="text-sm font-bold text-danger-700">Return Document</h3>
                                        {{ $this->returnForm }}
                                        <x-filament::button type="submit" color="danger">
                                            Mark For Return
                                        </x-filament::button>
                                    </form>
                                </div>
                            @endif

                            @if ($this->canReleaseReturn())
                                <div class="rounded-md border border-success-200 p-4">
                                    <form wire:submit.prevent="releaseReturn" class="space-y-4">
                                        <h3 class="text-sm font-bold text-success-700">Release Returned Document</h3>
                                        {{ $this->releaseForm }}
                                        <x-filament::button type="submit" color="success">
                                            Release Document
                                        </x-filament::button>
                                    </form>
                                </div>
                            @endif

                            @if ($this->canVerifyRelatedDocuments())
                                <div class="rounded-md border border-gray-200 p-4">
                                    <form wire:submit.prevent="verifyRelatedDocuments" class="space-y-4">
                                        <h3 class="text-sm font-bold">Verify Related Documents</h3>
                                        {{ $this->relatedDocumentsForm }}
                                        <x-filament::button type="submit">
                                            Verify Related Documents
                                        </x-filament::button>
                                    </form>
                                </div>
                            @endif

                            @if ($this->canAssignOrsBurs())
                                <div class="rounded-md border border-gray-200 p-4">
                                    <form wire:submit.prevent="assignOrsBurs" class="space-y-4">
                                        <h3 class="text-sm font-bold">Assign ORS/BURS</h3>
                                        {{ $this->orsBursForm }}
                                        <x-filament::button type="submit">
                                            Save ORS/BURS
                                        </x-filament::button>
                                    </form>
                                </div>
                            @endif

                            @if ($this->canRecordAccounting())
                                <div class="rounded-md border border-gray-200 p-4">
                                    <form wire:submit.prevent="recordAccounting" class="space-y-4">
                                        <h3 class="text-sm font-bold">Accounting Verification</h3>
                                        {{ $this->accountingForm }}
                                        <x-filament::button type="submit">
                                            Record Accounting Details
                                        </x-filament::button>
                                    </form>
                                </div>
                            @endif

                            @if ($this->canMakeChequeAda())
                                <div class="rounded-md border border-gray-200 p-4">
                                    <form wire:submit.prevent="makeChequeAda" class="space-y-4">
                                        <h3 class="text-sm font-bold">Cheque/ADA</h3>
                                        {{ $this->chequeAdaForm }}
                                        <x-filament::button type="submit">
                                            Record Cheque/ADA
                                        </x-filament::button>
                                    </form>
                                </div>
                            @endif

                            @if (
                                ! $this->canReceive()
                                && ! $this->canForward()
                                && ! $this->canReturn()
                                && ! $this->canReleaseReturn()
                                && ! $this->canVerifyRelatedDocuments()
                                && ! $this->canAssignOrsBurs()
                                && ! $this->canRecordAccounting()
                                && ! $this->canCertify()
                                && ! $this->canMakeChequeAda()
                            )
                                <div class="rounded-md border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                                    No simulator action is currently available for this DV state.
                                </div>
                            @endif
                        </div>
                    </x-filament::card>

                    <x-filament::card>
                        <h2 class="mb-4 text-lg font-bold tracking-tight">Activity Timeline</h2>
                        @include('components.timeline_views.activity_logs', ['record' => $voucher, 'safeColors' => true])
                    </x-filament::card>
                </div>

                <div>
                    <x-filament::card>
                        <h2 class="mb-4 text-lg font-bold tracking-tight">Workflow Progress</h2>
                        @include('components.timeline_views.progress_logs', [
                            'record' => $voucher,
                            'steps' => \App\Models\DisbursementVoucherStep::whereEnabled(true)->where('id', '>', 2000)->get(),
                            'safeColors' => true,
                        ])
                    </x-filament::card>
                </div>
            </div>
        @else
            <x-filament::card>
                <div class="py-6 text-center text-sm text-gray-500">
                    Select or create a disbursement voucher to start simulating the flow.
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament::page>
