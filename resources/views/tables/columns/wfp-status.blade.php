<div>
    @php
        $status = $getRecord()->wfp;
        if($status == null)
        {
            $status = 'No WFP';
        }else{
            switch($status->is_approved)
        {
            case 1:
                $status = 'Approved';
                break;
            case 0:
                $status = 'Pending';
                break;
            case 500:
                $status = 'For Modification';
                break;
        }
        }

    @endphp
    {{ $status }}
</div>
