@php
    if ($travel_order && $itinerary_entries) {
        $amount = $travel_order->registration_amount;
        foreach ($itinerary_entries as $value) {
            $amount += $value['data']['per_diem'];
            foreach ($value['data']['itinerary_entries'] as $key => $entry) {
                $amount += (float) $entry['transportation_expenses'] + (float) $entry['other_expenses'];
            }
        }
    }
@endphp
@if ($travel_order && $itinerary_entries)
    <div>
        <h4>Tracking Code: <span class="font-bold">{{ $travel_order->tracking_code }}</span></h4>
        <h4>Purpose:</h4>
        <p class="whitespace-pre-line">{{ $travel_order->purpose }}</p>
        <h4>Type: {{ $travel_order->travel_order_type->name }}</h4>
        <h4>From: {{ $travel_order->date_from->format('M d, Y') }}</h4>
        <h4>To: {{ $travel_order->date_to->format('M d, Y') }}</h4>
        @if ($travel_order->travel_order_type_id == App\Models\TravelOrderType::OFFICIAL_BUSINESS)
            <div>
                <h3>Destination</h3>
                <p>Region: {{ $travel_order->philippine_region->region_description }}</p>
                <p>Province: {{ $travel_order->philippine_province->province_description }}</p>
                <p>City: {{ $travel_order->philippine_city->city_municipality_description }}</p>
                <p>Other Details: {{ $travel_order->other_details ?? 'None provided.' }}</p>
                <p>Registration Fee: {{ number_format($travel_order->registration_amount, 2) }}</p>
                <p>Total Amount: P{{ number_format($amount, 2) }}</p>
            </div>
        @endif
    </div>
@endif
