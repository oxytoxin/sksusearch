<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Forms\Components\Flatpickr;
use App\Models\EmployeeInformation;
use App\Models\PhilippineCity;
use App\Models\PhilippineProvince;
use App\Models\PhilippineRegion;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TravelOrdersCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $travel_order_type_id;

    public $applicants = [];

    public $signatories = [];

    public $purpose;

    public $date_from;

    public $date_to;

    public $region_code;

    public $province_code;

    public $city_code;

    public $other_details;

    public $has_registration = false;

    public $registration_amount;

    protected function getFormSchema()
    {
        return [
            Select::make('travel_order_type_id')
                ->label('Travel order type')
                ->options(TravelOrderType::pluck('name', 'id'))
                ->reactive()
                ->required(),
            MultiSelect::make('applicants')
                ->required()
                ->options(EmployeeInformation::pluck('full_name', 'user_id')),
            MultiSelect::make('signatories')
                ->required()
                ->options(EmployeeInformation::pluck('full_name', 'user_id')),
            Textarea::make('purpose')
                ->required(),
            Grid::make(2)->schema([
                Toggle::make('has_registration')->reactive()->default(false),
                TextInput::make('registration_amount')
                    ->numeric()
                    ->visible(fn ($get) => $get('has_registration') == true)
                    ->required(fn ($get) => $get('has_registration') == true),
            ]),
            Grid::make(2)->schema([
                Flatpickr::make('date_from')
                    ->disableTime()
                    ->required(),
                Flatpickr::make('date_to')
                    ->disableTime()
                    ->afterOrEqual(fn ($get) => $get('date_to'))
                    ->required(),
            ]),
            Fieldset::make('Destination')->schema([
                Grid::make(2)->schema([
                    Select::make('region_code')
                        ->reactive()
                        ->label('Region')
                        ->required(fn ($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS)
                        ->options(PhilippineRegion::pluck('region_description', 'region_code')),
                    Select::make('province_code')
                        ->reactive()
                        ->label('Province')
                        ->visible(fn ($get) => $get('region_code'))
                        ->required(fn ($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS)
                        ->options(fn ($get) => PhilippineProvince::where('region_code', $get('region_code'))->pluck('province_description', 'province_code')),
                    Select::make('city_code')
                        ->label('City')
                        ->visible(fn ($get) => $get('province_code'))
                        ->required(fn ($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS)
                        ->options(fn ($get) => PhilippineCity::where('province_code', $get('province_code'))->pluck('city_municipality_description', 'city_municipality_code')),
                    TextInput::make('other_details')->nullable(),
                ]),
            ])->visible(fn ($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS),
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->date_from > $this->date_to) {
            Notification::make()->title('Operation Failed')->body('Invalid Dates. Please Check again')->danger()->send();
        } else {
            DB::beginTransaction();
            $to = TravelOrder::create([
                'tracking_code' => TravelOrder::generateTrackingCode(),
                'travel_order_type_id' => $this->travel_order_type_id,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'purpose' => $this->purpose,
                'has_registration' => $this->has_registration,
                'registration_amount' => $this->registration_amount,
                'philippine_region_id' => PhilippineRegion::firstWhere('region_code', $this->region_code)?->id,
                'philippine_province_id' => PhilippineProvince::firstWhere('province_code', $this->province_code)?->id,
                'philippine_city_id' => PhilippineCity::firstWhere('city_municipality_code', $this->city_code)?->id,
                'other_details' => $this->other_details,
            ]);
            $to->applicants()->sync($this->applicants);
            $to->signatories()->sync($this->signatories);
            DB::commit();
            Notification::make()->title('Operation Success')->body('Travel Order has been created.')->success()->send();

            return redirect()->route('requisitioner.travel-orders.show', $to);
        }
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-create');
    }
}
