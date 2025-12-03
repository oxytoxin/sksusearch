<div class="px-4">
    {{-- {{ $getRecord()->applicants() }} --}}
     @foreach ($getRecord()->applicants()->get() as $index => $applicant)
        {{ $applicant->employee_information->full_name }}
        @if ($index < count($getRecord()->applicants()->get()) - 1)
                                ,
        @endif
        @endforeach
</div>
