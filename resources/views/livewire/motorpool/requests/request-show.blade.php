<div>

	<div id="print_to" class="col-span-2 print-bg-white">
		<div class="flex w-full justify-between border-b-4 border-black p-6 print:flex">
			<div id="header" class="ml-3 flex w-full text-left">
				<div class="my-auto inline"><img src="{{ asset('images/sksulogo.png') }}" alt="sksu logo"
						class="h-full w-20 object-scale-down">
				</div>
				<div class="my-auto ml-3">
					<div class="block">
						<span class="text-left text-sm font-semibold tracking-wide text-black">Republic of the
							Philippines</span>
					</div>
					<div class="block">
						<span class="text-primary-600 text-left text-sm font-semibold uppercase tracking-wide">sultan
							kudarat state university</span>
					</div>
					<div class="block">
						<span class="text-sm font-semibold tracking-wide text-black">ACCESS, EJC Montilla, 9800 City of
							Tacurong</span>
					</div>
					<div class="block">
						<span class="text-sm font-semibold tracking-wide text-black">Province of Sultan Kudarat</span>
					</div>
				</div>
			</div>
			<div class="relative right-0">
				<div class="m-auto">
					{{-- <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $travel_order->tracking_code }}&amp;size=100x100"
                        alt="" title="" />
                    <span class="flex justify-center  text-[11px] font-xs">{{ $travel_order->tracking_code }}</span> --}}
				</div>
			</div>
		</div>

		<div class="w-full">
			<div class="m-2">
				<div class="flex h-auto w-full items-start px-6 pt-4 print:pb-0 print:block">
					<div id="header" class="block w-full items-start space-y-2 text-left">
						<div class="flex justify-end">
							<div>
								<span class="text-md print:text-md mr-10 font-bold text-black underline">{{ date('F d, Y') }}</span>
							</div>
							<div>
								{{-- <span class="text-sm font-bold text-black print:text-md">(Date)</span> --}}
							</div>
						</div>
						<div class="flex justify-end">
							<div>
								<span class="text-md print:text-md mr-32 font-bold text-black">P.O. # </span>
								{{-- <span class="text-md font-bold text-black underline print:text-md">____________</span> --}}
							</div>
						</div>
						<div class="flex">
							<span class="mx-auto mb-6 text-lg font-extrabold uppercase tracking-wide text-black print:text-lg">Driver's
								Trip Ticket</span>

						</div>
						<div class="block">
							{{-- <span
                                class="text-sm font-semibold tracking-wide text-left text-black">{{ $travel_order->created_at == '' ? 'Date Not Set' : $travel_order->created_at->format('F d, Y') }}</span> --}}
						</div>
						<div class="grid grid-cols-4">
							<span class="col-span-4 text-sm font-semibold tracking-wide text-black">
								C. To be filled up by Administrative Officials, Authorizing Official Travel:</span>
							<div class="col-span-3 text-sm font-semibold uppercase tracking-wide text-black">
								{{-- @foreach ($travel_order->applicants as $applicant)
                                    <h4 class="whitespace-nowrap">{{ $applicant->employee_information->full_name }}</h4>
                                @endforeach --}}
							</div>
						</div>
					</div>
				</div>
				<div id="contents" class="flex h-auto w-full px-6 ml-2 pt-2 print:pt-1 print:text-12">
					<div id="header" class="block w-full items-start text-left">
                        {{--  dont delete "&nbsp" --}}
						<div class="block flex-wrap -space-y-1">
							7. Name of the driver of the vehicle:
                            <span class="capitalize tracking-wide underline">
								&nbsp
								insert driver name here
								&nbsp
							</span>
						</div>
						<div class="block flex-wrap -space-y-1">
							8. Government car use, Plate Number:
                            <span class="capitalize tracking-wide underline">
								&nbsp
								insert the thing here
								&nbsp
							</span>
						</div>
						<div class="block flex-wrap -space-y-1">
							<span class="uppercase">9. <strong class="tracking-widest"> NAME OF AUTHORIZED passenger/S:</strong></span>
							<span class="capitalize tracking-wide underline">
								&nbsp
								insert foreach here, haha, names here bruv
								&nbsp
							</span>
						</div>
						<div class="block flex-wrap -space-y-1">
							10. Place and Office to be visited:
                            <span class=" tracking-wide underline">
								&nbsp
								insert the thing here
								&nbsp
							</span>
						</div>
						<div class="block flex-wrap -space-y-1">
							11. Purpose:
                            <span class=" tracking-wide underline">
								&nbsp
								insert the thing here
								&nbsp
							</span>
						</div>
						<div class="block flex-wrap -space-y-1">
							12. Date of Travel:
                            <span class=" tracking-wide underline">
								&nbsp
								insert the thing here
								&nbsp
							</span>
						</div>
                        <div class="grid grid-cols-5">
                            <div class="col-span-3 col-start-3 border-b border-black py-3"></div>
                            <div class="col-span-3 col-start-3 row-start-2 text-center">(Head of Office or his Duly Authorized Representative)</div>
                        </div>
					</div>
				</div>

			</div>
            <div class="m-2">
				<div class="flex h-auto w-full items-start px-6 pt-4 print:pb-0 print:pt-0 print:block">
					<div id="header" class="block w-full items-start space-y-2 text-left">
						
						<div class="grid grid-cols-4">
							<span class="col-span-4 text-sm font-semibold tracking-wide text-black">
								D. To be filled up by the Driver</span>
							<div class="col-span-3 text-sm font-semibold uppercase tracking-wide text-black">
								{{-- @foreach ($travel_order->applicants as $applicant)
                                    <h4 class="whitespace-nowrap">{{ $applicant->employee_information->full_name }}</h4>
                                @endforeach --}}
							</div>
						</div>
					</div>
				</div>
				<div id="contents" class="flex h-auto w-full px-6 ml-2 pt-2 print:pt-1 print:text-12">
					<div id="header" class="block w-full items-start text-left">
                        {{--  dont delete "&nbsp" --}}
						<div class="flex -space-y-1 columns-2">
							<div class="inline whitespace-nowrap">11. Time of departure from the office/garage </div> <div class="border-b border-black block w-full"></div>                            
						</div>
                        <div class="flex -space-y-1 columns-2">
							<div class="inline whitespace-nowrap">12. Time of arrival at (Para4, above)</div> <div class="border-b border-black block w-full"></div>                            
						</div>
                        <div class="flex -space-y-1 columns-2">
							<div class="inline whitespace-nowrap">13. Time of departure from (Para 4, above)</div> <div class="border-b border-black block w-full"></div>                            
						</div>
                        <div class="flex -space-y-1 columns-2">
							<div class="inline whitespace-nowrap">14. Time of arrival back ic office/garage</div> <div class="border-b border-black block w-full"></div>                            
						</div>
                        <div class="flex -space-y-1 columns-2">
							<div class="inline whitespace-nowrap">15. Approximate distance to traveled to and from</div> <div class="border-b border-black block w-full"></div>                            
						</div>
                        <div class="flex -space-y-1 columns-2">
							<div class="inline whitespace-nowrap">16. Gasoline issued purchased and use</div> <div class="border-b border-black block w-full"></div>                            
						</div>

                        <div class="ml-6">

                            <div class="flex columns-3">
                                <div class="inline whitespace-nowrap ">f. Balance in tank</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                            </div>
                            <div class="flex columns-3">
                                <div class="inline whitespace-nowrap ">g. Issued office from stock</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                            </div>
                            <div class="flex columns-3">
                                <div class="inline whitespace-nowrap ">h. Add-purchased during trip</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                            </div>
                            <div class="flex columns-3 ml-48">
                                <div class="inline whitespace-nowrap ">Total</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                            </div>
                            <div class="flex columns-3">
                                <div class="inline whitespace-nowrap ">i. Deduct - used trip to and from</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                            </div>
                            <div class="flex columns-3">
                                <div class="inline whitespace-nowrap ">j. Balance in tank at the end of the trip</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                            </div>
                            
                        </div>

                        <div class="flex columns-3">
                            <div class="inline whitespace-nowrap ">17. Gear oil issued</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                        </div>
                        <div class="flex columns-3">
                            <div class="inline whitespace-nowrap ">18. Lubrication oil issued</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                        </div>
                        <div class="flex columns-3">
                            <div class="inline whitespace-nowrap ">19. Grease issued</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                        </div>
                        <div class="flex columns-3">
                            <div class="inline whitespace-nowrap ">20. Remarks</div> <div class="border-b border-black block w-full"></div><div class="inline whitespace-nowrap pl-1">Liter/s</div>                            
                        </div>
						<div class="block pt-5">
                            <p class="indent-5">
                                I hereby certify to the correction of the above statement of the record of travel and bind myself fully responsible for the car used and safety of this government vehicle being used of the trip.
                            </p>
                            <p class="indent-5 mt-3">
                                I further certify that I know that the passenger is entitled to government transportation. that the trip is an official one that I must follow strictly the official nature of the trip without myself to be a party <strong class="text-red-500">[missing word/s on copy]</strong> the misuse or abuse of this vehicle
                            </p>
                        </div>
                        <div class="grid grid-cols-7 pt-4">
                            <div class="col-span-3 col-start-1 border-b border-black py-3"></div>
                            <div class="col-span-3 col-start-1 row-start-2 text-center">(Signature of Passengers)</div>
                            <div class="col-span-3 col-start-5 border-b border-black py-3"></div>
                            <div class="col-span-3 col-start-5 row-start-2 text-center">(Signature of Driver)</div>
                        </div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="flex justify-center">
		<button type="button" value="click" onclick="printDiv('print_to')" id="printto"
			class="w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 max-w-sm rounded-full px-4 py-2 font-semibold tracking-wider text-white active:text-white">
			Print Travel Order
		</button>
	</div>
	@push('scripts')
		<script>
			function printDiv(divName) {
				var printContents = document.getElementById(divName).innerHTML;
				var originalContents = document.body.innerHTML;

				document.body.innerHTML = printContents;

				window.print();

				document.body.innerHTML = originalContents;

			}
		</script>
	@endpush
</div>
