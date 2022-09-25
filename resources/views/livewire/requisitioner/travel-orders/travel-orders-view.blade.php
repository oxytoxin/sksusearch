<div>
   <!-- This example requires Tailwind CSS v2.0+ -->
<div class="border-b border-gray-200 rounded-md bg-white px-4 py-5 sm:px-6">
  <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
    <div class="ml-4 mt-4">
      <h3 class="text-lg font-medium leading-6 text-gray-900">Travel Order Details</h3>
      <p class="mt-4 text-sm text-gray-500">Tracking Code: {{$travel_order->tracking_code}}</p>
      <p class="mt-1 text-sm text-gray-500">Travel Order Type: {{$travel_order->travel_order_type->name}}</p>
      <p class="mt-1 text-sm text-gray-500">Date Range: {{($travel_order->date_from)->format('F d Y')}} to {{($travel_order->date_to)->format('F d Y')}}</p>
      @if ($travel_order->travel_order_type_id == 1)
      @if ($travel_order->other_details == "")
         <p class="mt-1 text-sm text-gray-500">Destination: {{$travel_order->philippine_city->city_municipality_description}}, 
        {{$travel_order->philippine_province->province_description}}, {{$travel_order->philippine_region->region_description}}</p>
      @else
         <p class="mt-1 text-sm text-gray-500">Destination: {{$travel_order->other_details}}, {{$travel_order->philippine_city->city_municipality_description}}, 
        {{$travel_order->philippine_province->province_description}}, {{$travel_order->philippine_region->region_description}}</p>  
      @endif
      @endif
      <p class="mt-1 text-sm text-gray-500">Purpose: {{$travel_order->purpose}}</p>
    </div>
  </div>
</div>

<div class="mt-5 border-b border-gray-200 rounded-md bg-white px-4 py-5 sm:px-6">
  <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
    <div class="ml-4 mt-4">
      <h3 class="text-lg font-medium leading-6 text-gray-900">Status</h3>
      <p class="mt-4 text-sm text-gray-500">Tracking Code: {{$travel_order->tracking_code}}</p>
      <p class="mt-1 text-sm text-gray-500">Travel Order Type: {{$travel_order->travel_order_type->name}}</p>
      <p class="mt-1 text-sm text-gray-500">Date Range: {{($travel_order->date_from)->format('F d Y')}} to {{($travel_order->date_to)->format('F d Y')}}</p>
      @if ($travel_order->travel_order_type_id == 1)
      @if ($travel_order->other_details == "")
         <p class="mt-1 text-sm text-gray-500">Destination: {{$travel_order->philippine_city->city_municipality_description}}, 
        {{$travel_order->philippine_province->province_description}}, {{$travel_order->philippine_region->region_description}}</p>
      @else
         <p class="mt-1 text-sm text-gray-500">Destination: {{$travel_order->other_details}}, {{$travel_order->philippine_city->city_municipality_description}}, 
        {{$travel_order->philippine_province->province_description}}, {{$travel_order->philippine_region->region_description}}</p>  
      @endif
      @endif
      <p class="mt-1 text-sm text-gray-500">Purpose: {{$travel_order->purpose}}</p>
    </div>
  </div>
</div>

</div>
