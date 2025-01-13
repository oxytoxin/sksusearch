<div>
    <a href="{{ route('wfp.wfp-history') }}"
            class="my-5 hover:bg-gray-500 p-2 bg-gray-600 rounded-md font-light capitalize text-white text-sm">
            Back</a>
    <ul role="list" class="space-y-3 mt-10">
        @foreach ($record->wfpApprovalRemarks as $item)
        <li class="overflow-hidden rounded-md bg-white px-6 py-4 shadow">
            <p class="text-md py-3"><span class="font-semibold">Approver:</span> {{$item->user->employee_information->full_name}}</p>
            <p class="text-md py-3"><span class="font-semibold">Remarks: </span><span class="ml-5">{!!$item->remarks!!}</span></p>
            <p class="text-md py-3"><span class="font-semibold">Date: </span>{{Carbon\Carbon::parse($item->created_at)->format('F d, Y h:i A')}}</p>
        </li>
      </ul>
      @endforeach
</div>