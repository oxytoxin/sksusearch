<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\User;
use Livewire\Component;
use App\Jobs\SendSmsJob;
use App\Models\TravelOrder;
use App\Models\PhilippineCity;
use App\Models\TravelOrderType;
use App\Models\PhilippineRegion;
use App\Models\PhilippineProvince;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

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
            TableRepeater::make('attachments')
                ->hideLabels()
                ->schema([
                    FileUpload::make('path')
                        ->required()
                        ->removeUploadedFileButtonPosition('right')
                        ->validationAttribute('file')
                        ->maxFiles(1),
                    Textarea::make('description')
                        ->rows(3)
                        ->required(),
                ]),
        ];
    }

    protected function createTravelOrder()
    {
        $to = TravelOrder::create([
            'tracking_code' => TravelOrder::generateTrackingCode(),
            'travel_order_type_id' => $this->data['travel_order_type_id'],
            'date_from' => $this->data['date_from'],
            'date_to' => $this->data['date_to'],
            'purpose' => $this->data['purpose'],
            'has_registration' => $this->data['has_registration'] ?? false,
            'needs_vehicle' => ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS && isset($this->data['needs_vehicle'])) ? $this->data['needs_vehicle'] : false,
            'registration_amount' => $this->data['registration_amount'] ?? 0,
            'philippine_region_id' => ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS && isset($this->data['region_code'])) ? PhilippineRegion::firstWhere('region_code', $this->data['region_code'])?->id : null,
            'philippine_province_id' => ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS && isset($this->data['province_code'])) ? PhilippineProvince::firstWhere('province_code', $this->data['province_code'])?->id : null,
            'philippine_city_id' => ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS && isset($this->data['city_code'])) ? PhilippineCity::firstWhere('city_municipality_code', $this->data['city_code'])?->id : null,
            'other_details' => $this->data['other_details'] ?? null,
        ]);

        foreach ($this->data['attachments'] as $key => $attachment) {
            // Skip if path is not set
            if (!isset($attachment['path']) || empty($attachment['path'])) {
                continue;
            }

            $filePath = $attachment['path'];

            // Livewire stores uploads as array even with maxFiles(1)
            if (is_array($filePath)) {
                $filePath = collect($filePath)->filter()->first();
            }

            // Skip if still empty after filtering
            if (!$filePath) {
                continue;
            }

            // Handle TemporaryUploadedFile object
            if (is_object($filePath)) {
                try {
                    $filename = $filePath->getClientOriginalName();
                    $path = $filePath->store('travel_order_attachments', 'public');
                } catch (\Exception $e) {
                    \Log::error('File upload error: ' . $e->getMessage());
                    continue;
                }
            }
            // Handle string path (already stored)
            else if (is_string($filePath)) {
                $filename = basename($filePath);
                $path = $filePath;
            } else {
                continue;
            }

            $to->attachments()->create([
                'file_name' => $filename,
                'path' => $path,
                'description' => $attachment['description'] ?? '',
            ]);
        }

        return $to;
    }

    protected function fetchSignatories()
    {
        $signatories = [
            $this->data['immediate_supervisor'] => ['role' => 'immediate_supervisor'],
            $this->data['recommending_approval'] => ['role' => 'recommending_approval'],
        ];
        if ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS) {
            if (isset($this->data['region_code']) && $this->data['region_code'] != 12) {
                $president = EmployeeInformation::whereRelation('position', 'description', 'University President')->first();
                if (!$president) {
                    Notification::make()->title('Operation Failed')
                        ->body('University President not found. Please contact site administrator.')
                        ->danger()
                        ->send();
                    return null;
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
            try {
                $to = $this->createTravelOrder();
                $to->applicants()->sync($this->data['applicants']);

                $signatories = $this->fetchSignatories();
                if ($signatories === null) {
                    DB::rollBack();
                    return;
                }

                $to->signatories()->sync($signatories);

                // Send SMS notifications to all signatories
                $signatoryUsers = User::whereIn('id', array_keys($signatories))
                    ->with('employee_information')
                    ->get();

                $makerName = auth()->user()->employee_information->full_name;
                $message = "A travel order and its accompanying itinerary have been submitted to the SEARCH system by {$makerName} for your approval. Tracking Code: {$to->tracking_code}";


                // ========== SMS NOTIFICATION (COMMENTED OUT) ==========
                // foreach ($signatoryUsers as $signatory) {
                //     // Check if employee information and contact number exist
                //     if ($signatory->employee_information && !empty($signatory->employee_information->contact_number)) {
                //         SendSmsJob::dispatch(
                //             "09366303145",
                //             // $signatory->employee_information->contact_number,
                //             $message,
                //             'travel_order_signatory_notification',
                //             $signatory->id,
                //             auth()->id()
                //         );
                //     }
                // ========== SMS NOTIFICATION END ==========
                }

                DB::commit();

                Notification::make()->title('Operation Success')->body('Travel Order has been created.')->success()->send();

                if ($to->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
                    if ($to->needs_vehicle) {
                        return redirect()->route('requisitioner.motorpool.create', ['travel_order' => $to]);
                    }
                    return redirect()->route('requisitioner.itinerary.create', ['travel_order' => $to]);
                }

                return redirect()->route('requisitioner.travel-orders.index');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Travel Order creation failed: ' . $e->getMessage());
                Notification::make()->title('Operation Failed')->body('Failed to create travel order: ' . $e->getMessage())->danger()->send();
            }
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
