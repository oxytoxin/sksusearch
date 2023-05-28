<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\EmployeeInformation;
use App\Models\PhilippineCity;
use App\Models\PhilippineProvince;
use App\Models\PhilippineRegion;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
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

    public $data;

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('travel_order_type_id')
                ->label('Travel order type')
                ->options(TravelOrderType::pluck('name', 'id'))
                ->reactive()
                ->required(),
            Select::make('applicants')
                ->multiple()
                ->required()
                ->options(EmployeeInformation::pluck('full_name', 'user_id')),
            Select::make('immediate_supervisor')
                ->required()->searchable()->preload()
                ->options(EmployeeInformation::whereNot('user_id', auth()->id())->pluck('full_name', 'user_id')),
            Select::make('recommending_approval')
                ->required()->searchable()->preload()
                ->options(EmployeeInformation::whereNot('user_id', auth()->id())->pluck('full_name', 'user_id')),
            Textarea::make('purpose')
                ->required(),
            Grid::make(2)->schema([
                Toggle::make('has_registration')
                    ->reactive()
                    ->label('With Registration')
                    ->default(false),
                Toggle::make('needs_vehicle')
                    ->reactive()
                    ->visible(fn($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS)
                    ->label('Request Vehicle')
                    ->default(false),
                TextInput::make('registration_amount')
                    ->numeric()
                    ->visible(fn($get) => $get('has_registration') == true)
                    ->required(fn($get) => $get('has_registration') == true),
            ]),
            Grid::make(2)->schema([
                DatePicker::make('date_from')
                    ->withoutTime()
                    ->required(),
                DatePicker::make('date_to')
                    ->withoutTime()
                    ->afterOrEqual(fn($get) => $get('date_from'))
                    ->required(),
            ]),
            Fieldset::make('Destination')->schema([
                Grid::make(2)->schema([
                    Select::make('region_code')
                        ->reactive()
                        ->label('Region')
                        ->required(fn($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS)
                        ->options(PhilippineRegion::pluck('region_description', 'region_code')),
                    Select::make('province_code')
                        ->reactive()
                        ->label('Province')
                        ->visible(fn($get) => $get('region_code'))
                        ->required(fn($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS)
                        ->options(fn($get) => PhilippineProvince::where('region_code', $get('region_code'))->pluck('province_description', 'province_code')),
                    Select::make('city_code')
                        ->label('City')
                        ->visible(fn($get) => $get('province_code'))
                        ->required(fn($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS)
                        ->options(fn($get) => PhilippineCity::where('province_code', $get('province_code'))->pluck('city_municipality_description', 'city_municipality_code')),
                    TextInput::make('other_details')->nullable(),
                ]),
            ])->visible(fn($get) => $get('travel_order_type_id') == TravelOrderType::OFFICIAL_BUSINESS),
        ];
    }

    protected function createTravelOrder()
    {
        return TravelOrder::create([
            'tracking_code' => TravelOrder::generateTrackingCode(),
            'travel_order_type_id' => $this->data['travel_order_type_id'],
            'date_from' => $this->data['date_from'],
            'date_to' => $this->data['date_to'],
            'purpose' => $this->data['purpose'],
            'has_registration' => $this->data['has_registration'],
            'needs_vehicle' => $this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS ? $this->data['needs_vehicle'] : false,
            'registration_amount' => $this->data['registration_amount'],
            'philippine_region_id' => $this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS ? PhilippineRegion::firstWhere('region_code', $this->data['region_code'])?->id : null,
            'philippine_province_id' => $this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS ? PhilippineProvince::firstWhere('province_code', $this->data['province_code'])?->id : null,
            'philippine_city_id' => $this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS ? PhilippineCity::firstWhere('city_municipality_code', $this->data['city_code'])?->id : null,
            'other_details' => $this->data['other_details'],
        ]);
    }

    protected function fetchSignatories()
    {
        $signatories = [
            $this->data['immediate_supervisor'] => ['role' => 'immediate_supervisor'],
            $this->data['recommending_approval'] => ['role' => 'recommending_approval'],
        ];
        if ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS) {
            if ($this->data['region_code'] != 12) {
                $president = EmployeeInformation::whereRelation('position', 'description', 'University President')->first();
                if (!$president) {
                    Notification::make()->title('Operation Failed')
                        ->body('University President not found. Please contact site administrator.')
                        ->danger()
                        ->send();
                    return;
                }
                $signatories[$president->user_id] = ['role' => 'university_president'];
            }
        }
        return $signatories;
    }

    public function save()
    {
        $this->form->validate();
        if (in_array(auth()->user()->id, $this->data['applicants'])) {
            DB::beginTransaction();
            $to = $this->createTravelOrder();
            $to->applicants()->sync($this->data['applicants']);
            $to->signatories()->sync($this->fetchSignatories());
            DB::commit();
            Notification::make()->title('Operation Success')->body('Travel Order has been created.')->success()->send();
            if ($to->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
                if ($to->needs_vehicle) {
                    return redirect()->route('requisitioner.motorpool.create', ['travel_order' => $to]);
                }
                return redirect()->route('requisitioner.itinerary.create', ['travel_order' => $to]);
            }

            return redirect()->route('requisitioner.travel-orders.index');
        } else {
            Notification::make()->title('Operation Failed')->body('Travel order applicants must include yourself.')->danger()->send();
        }
    }

    public function mount()
    {
        $this->form->fill();
        $this->data['applicants'] = [auth()->id()];
    }

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-create');
    }
}
