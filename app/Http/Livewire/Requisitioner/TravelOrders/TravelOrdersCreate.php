<?php

    namespace App\Http\Livewire\Requisitioner\TravelOrders;

    use App\Models\User;
    use Livewire\Component;
    use App\Jobs\SendSmsJob;
    use App\Http\Controllers\NotificationController;
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

        public $travel_order;

        public bool $is_editing = false;

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
                TableRepeater::make('signatories')
                    ->hideLabels()
                    ->schema([
                        Select::make('user_id')
                            ->label('Signatory')
                            ->required()->searchable()->preload()
                            ->options(EmployeeInformation::whereNot('user_id', auth()->id())->pluck('full_name', 'user_id')),
                        TextInput::make('heading')->required(),
                        TextInput::make('designation')->required(),
                    ])
                    ->minItems(1),
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
                            ->options(PhilippineRegion::pluck('region_description', 'region_code')),
                        Select::make('province_code')
                            ->reactive()
                            ->label('Province')
                            ->visible(fn($get) => $get('region_code'))
                            ->options(fn($get) => PhilippineProvince::where('region_code', $get('region_code'))->pluck('province_description', 'province_code')),
                        Select::make('city_code')
                            ->label('City')
                            ->visible(fn($get) => $get('province_code'))
                            ->options(fn($get) => PhilippineCity::where('province_code', $get('province_code'))->pluck('city_municipality_description', 'city_municipality_code')),
                        TextInput::make('other_details')->nullable(),
                    ]),
                ]),
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
                'submitted_at' => now(),
                'has_registration' => $this->data['has_registration'] ?? false,
                'needs_vehicle' => ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS && isset($this->data['needs_vehicle'])) ? $this->data['needs_vehicle'] : false,
                'registration_amount' => $this->data['registration_amount'] ?? 0,
                'philippine_region_id' => isset($this->data['region_code']) ? PhilippineRegion::firstWhere('region_code', $this->data['region_code'])?->id : null,
                'philippine_province_id' => isset($this->data['province_code']) ? PhilippineProvince::firstWhere('province_code', $this->data['province_code'])?->id : null,
                'philippine_city_id' => isset($this->data['city_code']) ? PhilippineCity::firstWhere('city_municipality_code', $this->data['city_code'])?->id : null,
                'other_details' => $this->data['other_details'] ?? null,
            ]);

            $this->persistAttachments($to);

            return $to;
        }

        protected function updateTravelOrder()
        {
            $this->travel_order->update([
                'travel_order_type_id' => $this->data['travel_order_type_id'],
                'date_from' => $this->data['date_from'],
                'date_to' => $this->data['date_to'],
                'purpose' => $this->data['purpose'],
                'submitted_at' => now(),
                'has_registration' => $this->data['has_registration'] ?? false,
                'needs_vehicle' => ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS && isset($this->data['needs_vehicle'])) ? $this->data['needs_vehicle'] : false,
                'registration_amount' => $this->data['registration_amount'] ?? 0,
                'philippine_region_id' => isset($this->data['region_code']) ? PhilippineRegion::firstWhere('region_code', $this->data['region_code'])?->id : null,
                'philippine_province_id' => isset($this->data['province_code']) ? PhilippineProvince::firstWhere('province_code', $this->data['province_code'])?->id : null,
                'philippine_city_id' => isset($this->data['city_code']) ? PhilippineCity::firstWhere('city_municipality_code', $this->data['city_code'])?->id : null,
                'other_details' => $this->data['other_details'] ?? null,
            ]);

            $this->persistAttachments($this->travel_order, true);

            return $this->travel_order;
        }

        protected function persistAttachments(TravelOrder $to, bool $replaceExisting = false): void
        {
            if ($replaceExisting) {
                $to->attachments()->delete();
            }

            foreach (($this->data['attachments'] ?? []) as $attachment) {
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
                        \Log::error('File upload error: '.$e->getMessage());
                        continue;
                    }
                } // Handle string path (already stored)
                else {
                    if (is_string($filePath)) {
                        $filename = basename($filePath);
                        $path = $filePath;
                    } else {
                        continue;
                    }
                }

                $to->attachments()->create([
                    'file_name' => $filename,
                    'path' => $path,
                    'description' => $attachment['description'] ?? '',
                ]);
            }
        }

        protected function fetchSignatories()
        {
            $signatories = [];
            foreach ($this->data['signatories'] as $key => $signatory) {
                $this->data['signatories'][$key]['role'] = str($signatory['designation'])->replace('/', '_')->lower()->replace(' ', '_')->value();
                $signatories[$signatory['user_id']] = $this->data['signatories'][$key] + [
                        'is_approved' => false,
                        'approved_at' => null,
                        'approved_by_oic_id' => null,
                    ];
            }

            return $signatories;

//            if ($this->data['travel_order_type_id'] == TravelOrderType::OFFICIAL_BUSINESS) {
//                if (isset($this->data['region_code']) && $this->data['region_code'] != 12) {
//                    $president = EmployeeInformation::whereRelation('position', 'description', 'University President')->first();
//                    if (!$president) {
//                        Notification::make()->title('Operation Failed')
//                            ->body('University President not found. Please contact site administrator.')
//                            ->danger()
//                            ->send();
//                        return null;
//                    }
//                    $signatories[$president->user_id] = ['role' => 'university_president'];
//                }
//            }
//            return $signatories;
        }

        public function save()
        {
            $this->form->validate();
            if (in_array(auth()->user()->id, $this->data['applicants'])) {
                DB::beginTransaction();
                try {
                    $to = $this->is_editing ? $this->updateTravelOrder() : $this->createTravelOrder();
                    $to->applicants()->sync($this->data['applicants']);

                    $signatories = $this->fetchSignatories();
                    if ($signatories === null) {
                        DB::rollBack();
                        return;
                    }

                    $to->signatories()->sync($signatories);

                    [$signatoryUsers, $message] = $this->notifySignatories($to, array_keys($signatories));

                    DB::commit();

                    // ========== REALTIME NOTIFICATION ==========
                    try {
                        foreach ($signatoryUsers as $signatory) {
                            NotificationController::sendGeneralNotification(
                                'travel_order_signatory_notification',
                                'Travel Order for Approval',
                                $message,
                                $signatory,
                                route('signatory.travel-orders.view', $to)
                            );
                        }
                    } catch (\Exception $e) {
                        \Log::error('Realtime notification failed: '.$e->getMessage());
                    }
                    // ========== REALTIME NOTIFICATION END ==========

                    Notification::make()
                        ->title('Operation Success')
                        ->body($this->is_editing ? 'Travel Order has been resubmitted.' : 'Travel Order has been created.')
                        ->success()
                        ->send();

                    if (!$this->is_editing && $to->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
                        return redirect()->route('requisitioner.itinerary.create', ['travel_order' => $to]);
                    }

                    if ($this->is_editing && $to->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
                        $itinerary = $to->itineraries()
                            ->where('user_id', auth()->id())
                            ->whereIsActual(false)
                            ->whereNull('submitted_at')
                            ->first();

                        if ($itinerary) {
                            return redirect()->route('requisitioner.itinerary.edit', ['itinerary' => $itinerary]);
                        }
                    }

                    return redirect()->route('requisitioner.travel-orders.view', $to);
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('Travel Order creation failed: '.$e->getMessage());
                    Notification::make()->title('Operation Failed')->body('Failed to create travel order: '.$e->getMessage())->danger()->send();
                }
            } else {
                Notification::make()->title('Operation Failed')->body('Travel order applicants must include yourself.')->danger()->send();
            }
        }

        private function notifySignatories(TravelOrder $to, array $signatoryIds): array
        {
            // Send SMS notifications to all signatories
            $signatoryUsers = User::whereIn('id', $signatoryIds)
                ->with('employee_information')
                ->get();

            $makerName = auth()->user()->employee_information->full_name;
            $message = "A travel order and its accompanying itinerary have been submitted to the SEARCH system by {$makerName} for your approval. Tracking Code: {$to->tracking_code}";

            // ========== SMS NOTIFICATION ==========
            foreach ($signatoryUsers as $signatory) {
                // Check if employee information and contact number exist
                if (!config('services.semaphore.api_key')) {
                    break;
                }
                if ($signatory->employee_information && !empty($signatory->employee_information->contact_number)) {

                    SendSmsJob::dispatch(
                        $signatory->employee_information->contact_number,
                        $message,
                        'travel_order_signatory_notification',
                        $signatory->id,
                        auth()->id()
                    );
                }
            }
            // ========== SMS NOTIFICATION END ==========

            return [$signatoryUsers, $message];
        }

        public function mount()
        {
            $travel_order = TravelOrder::find(request()->route('travel_order'));
            $this->form->fill();
            if ($travel_order?->exists) {
                $travel_order->load([
                    'applicants',
                    'signatories',
                    'philippine_region',
                    'philippine_province',
                    'philippine_city',
                    'attachments',
                ]);

                if (!$travel_order->applicants->contains('id', auth()->id()) || filled($travel_order->submitted_at)) {
                    abort(403);
                }

                $this->travel_order = $travel_order;
                $this->is_editing = true;

                $this->form->fill([
                    'travel_order_type_id' => $travel_order->travel_order_type_id,
                    'applicants' => $travel_order->applicants->pluck('id')->toArray(),
                    'signatories' => $travel_order->signatories->sortBy('pivot.id')->map(fn($signatory) => [
                        'user_id' => $signatory->id,
                        'heading' => $signatory->pivot->heading,
                        'designation' => $signatory->pivot->designation,
                    ])->values()->toArray(),
                    'purpose' => $travel_order->purpose,
                    'has_registration' => $travel_order->has_registration,
                    'needs_vehicle' => $travel_order->needs_vehicle,
                    'registration_amount' => $travel_order->registration_amount,
                    'date_from' => $travel_order->date_from?->toDateString(),
                    'date_to' => $travel_order->date_to?->toDateString(),
                    'region_code' => $travel_order->philippine_region?->region_code,
                    'province_code' => $travel_order->philippine_province?->province_code,
                    'city_code' => $travel_order->philippine_city?->city_municipality_code,
                    'other_details' => $travel_order->other_details,
                    'attachments' => $travel_order->attachments->map(fn($attachment) => [
                        'path' => $attachment->path,
                        'description' => $attachment->description,
                    ])->values()->toArray(),
                ]);

                return;
            }

            $this->form->fill([
                'applicants' => [auth()->id()],
                'signatories' => [
                    [
                        'user_id' => null,
                        'heading' => 'Noted:',
                        'designation' => 'Immediate Supervisor',
                    ],
                    [
                        'user_id' => null,
                        'heading' => 'Recommending Approval:',
                        'designation' => 'VPAA / VPRDEX / VPFARG',
                    ],
                ],
            ]);
        }

        public function render()
        {
            return view('livewire.requisitioner.travel-orders.travel-orders-create');
        }
    }
