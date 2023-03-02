<div>
    @if (!$disbursement_voucher->log_number || !$disbursement_voucher->documents_verified_at)
        <div class="mb-4 space-y-2 bg-white rounded-lg p-4">
            {{ $this->form }}
            <div class="flex justify-end">
                <x-filament-support::button wire:click="save" wire:target="save">Save</x-filament-support::button>
            </div>
        </div>
    @endif
    <div id="dvPrint">
        <div class="flex flex-col max-w-fit mx-auto divide-y-2 divide-black border-collapse border-4 border-black items-center print:w-[220mm] print:h-[297mm] print:max-w-[220mm] print:max-h-[297mm]">
            <div class="grid grid-cols-8 border-collapse divide-x-2 divide-black w-full">
                <div class="col-span-6">
                    <div class="flex justify-between min-w-full place-items-center">
                        <div class="flex mt-1 ml-1">
                            <div class="flow-root my-auto">
                                <div class="inline-block mr-2">
                                    <img class="object-scale-down h-full mx-auto w-14" src="{{ asset('images/sksulogo.png') }}" alt="sksu logo">
                                    <span class="text-xs text-center text-black print:text-8">SKSU Works for Success!</span>
                                    {{-- <span class="text-xs font-bold text-center text-black"> ISO 9001:2015</span> --}}
                                </div>
                            </div>
                            <div class="flex place-items-center">
                                <div class="text-left">
                                    <span class="block text-sm font-bold text-black uppercase">Republic of the Philippines</span>
                                    <span class="block text-sm font-bold text-green-600 uppercase">SULTAN KUDARAT STATE UNIVERSITY</span>
                                    <span class="block text-sm text-black">ACCESS, EJC Montilla, 9800 City of Tacurong</span>
                                    <span class="block text-sm text-black">Province of Sultan Kudarat</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="m-3 text-center">
                                <img class="w-12 h-auto mx-auto" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $disbursement_voucher->tracking_number }}" alt="N/A">
                                <span class="flex justify-center text-xs font-normal">{{ $disbursement_voucher->tracking_number }}</span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="row-span-1 pb-2 space-y-4 p-2">
                        <p class="mx-auto ml-1 font-serif text-xs font-extrabold text-black print:text-12">
                            Date: <span class="ml-2"> {{ $disbursement_voucher->documents_verified_at?->format('m/d/Y') }}</span>
                        </p>
                        <p class="mx-auto ml-1 font-serif text-xs font-extrabold text-black print:text-12">
                            Log No.: <span class="block mt-2 ml-4"> {{ $disbursement_voucher->log_number }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full p-4">
                @if ($disbursement_voucher->related_documents && filled($disbursement_voucher->related_documents))
                    <h4 class="text-center capitalize">{{ str($disbursement_voucher->voucher_subtype->voucher_type->name)->singular() }} for {{ $disbursement_voucher->voucher_subtype->name }}</h4>
                    <h5 class="mt-4 text-sm italic">Checklist for Documentary Requirements</h5>
                    <ul class="mt-4 space-y-1">
                        @forelse ($disbursement_voucher->voucher_subtype->related_documents_list?->documents as $document)
                            <li class="flex gap-2">
                                <span class="w-6 flex-shrink-0">
                                    @if (in_array($document, $disbursement_voucher->related_documents['verified_documents']))
                                        <x-ri-checkbox-circle-fill class="text-primary-400" />
                                    @else
                                        <x-ri-close-circle-fill class="text-red-500" />
                                    @endif
                                </span>
                                <span>{{ $document }}</span>
                            </li>
                        @empty
                            <li>
                                No related documents required.
                            </li>
                        @endforelse
                    </ul>
                    <div class="mt-4 space-y-4">
                        <h6>Remarks:</h6>
                        @if ($disbursement_voucher->related_documents && filled($disbursement_voucher->related_documents['remarks']))
                            <div>
                                {!! $disbursement_voucher->related_documents['remarks'] !!}
                            </div>
                        @else
                            <p>No remarks.</p>
                        @endif
                    </div>
                @else
                    <p>Disbursement Voucher documents are not yet verified by ICU.</p>
                @endif
            </div>
            <div class="flex-1"></div>
            <div class="w-full p-4">
                <div>
                    <p>Reviewed/Checked By:</p>
                </div>
                <div>
                    <span class="block mt-12 font-semibold tracking-wide text-center text-black underline text-md">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ auth()->user()->employee_information->full_name }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </span>
                    <span class="block mt-2 tracking-wide text-center text-black text-md">
                        {{ auth()->user()->employee_information->position->description }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <button class="inline-flex items-center px-4 py-2 mt-2 text-xs font-medium text-white border border-transparent rounded-md shadow-sm bg-primary-500 hover:bg-primary-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
            type="button" onclick="printDiv('dvPrint')">
        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd"
                  d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 003 3h.27l-.155 1.705A1.875 1.875 0 007.232 22.5h9.536a1.875 1.875 0 001.867-2.045l-.155-1.705h.27a3 3 0 003-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0018 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM16.5 6.205v-2.83A.375.375 0 0016.125 3h-8.25a.375.375 0 00-.375.375v2.83a49.353 49.353 0 019 0zm-.217 8.265c.178.018.317.16.333.337l.526 5.784a.375.375 0 01-.374.409H7.232a.375.375 0 01-.374-.409l.526-5.784a.373.373 0 01.333-.337 41.741 41.741 0 018.566 0zm.807-3.97a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H18a.75.75 0 01-.75-.75V10.5zM15 9.75a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V10.5a.75.75 0 00-.75-.75H15z"
                  clip-rule="evenodd" />
        </svg>
        Print Report
    </button>
    <style>
        @page {
            size: auto;
            size: A4;
            margin: 0mm;
        }
    </style>
    @push('scripts')
        <script>
            function printDiv(divName) {
                var originalContents = document.body.innerHTML;
                var element = document.getElementById("toPrint");
                var printContents = document.getElementById(divName).innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
    @endpush
</div>
