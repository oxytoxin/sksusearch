@php
$travel_order = App\Models\TravelOrder::with(['travel_order_type', 'philippine_region', 'philippine_province', 'philippine_city'])->find($getLivewire()->travel_order_id);
$itinerary_entries = $getLivewire()->itinerary_entries;
$amount = $travel_order->registration_amount;
foreach ($itinerary_entries as $value) {
    $amount += $value['data']['per_diem'];
}
@endphp

<div>
    <h4>Tracking Code: {{ $travel_order->tracking_code }}</h4>
    <h4>Purpose: {{ $travel_order->purpose }}</h4>
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
            <p>Registration Fee: {{ $travel_order->registration_amount ?? 'None provided.' }}</p>
            <p>Total Amount: {{ $amount }}</p>
        </div>
    @endif
</div>
