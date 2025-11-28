<?php

    namespace App\Http\Livewire\WFP;

    use App\Models\CategoryGroup;
    use App\Models\FundAllocation;
    use App\Models\CostCenter;
    use App\Models\WpfType;
    use Livewire\Component;
    use WireUi\Traits\Actions;
    use Filament\Notifications\Notification;
    use App\Jobs\SendSmsJob;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;

    class AllocateFunds extends Component
    {
        use Actions;

        public $record;
        public $category_groups;
        public $wfp_type;
        public $selectedType;
        public $fundInitialAmount;
        public $fund_description;
        public $amounts = [];

        public function mount($record)
        {
            $this->record = CostCenter::find($record);
            $this->category_groups = CategoryGroup::where('is_active', 1)->get();
            $this->wfp_type = WpfType::all();
            $this->selectedType = "";
            $this->amounts = array_fill_keys($this->category_groups->pluck('id')->toArray(), 0);

        }


        public function calculateSubTotal($categoryGroupId)
        {
            // Return the amount associated with the given category group ID
            if ($this->amounts[$categoryGroupId] < 0) {
                $this->amounts[$categoryGroupId] = 0;
            } else {
                $this->amounts[$categoryGroupId] = $this->amounts[$categoryGroupId];
            }
            return $this->amounts[$categoryGroupId] ?? 0;
        }

        public function calculateTotal()
        {
            // Calculate the total of all amounts
            return array_sum($this->amounts);
        }


        public function confirmAllocation()
        {
            $this->dialog()->confirm([
                'title' => 'Are you Sure?',
                'description' => 'Do you really want to save this information?',
                'acceptLabel' => 'Yes, save it',
                'method' => 'submitAllocation',
            ]);
        }

        public function submitAllocation()
        {
            if ($this->selectedType === "" || $this->selectedType === null) {
                Notification::make()->title('Please Select a WFP Period')->danger()->send();
            } else {
                //save the data
                if (FundAllocation::where('cost_center_id', $this->record->id)
                    ->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', $this->record->fundClusterWFP->id)
                    ->exists()) {
                    Notification::make()->title('Fund Allocation already exists')->danger()->send();
                    return;
                } else {
                    // Calculate total amount for SMS
                    $totalAmount = array_sum($this->amounts);

                    foreach ($this->amounts as $categoryGroupId => $amount) {
                        FundAllocation::create([
                            'cost_center_id' => $this->record->id,
                            'wpf_type_id' => $this->selectedType,
                            'fund_cluster_id' => $this->record->fundClusterWFP->id,
                            'category_group_id' => $categoryGroupId,
                            'initial_amount' => $amount,
                        ]);
                    }

                    // ========== SMS NOTIFICATION START ==========
                    // COMMENTED OUT - TO BE CONFIRMED BY ACCOUNTANT
                    // try {
                    //     // Validate cost center exists
                    //     if (!$this->record) {
                    //         Log::warning('SMS notification skipped: Cost center not found', [
                    //             'context' => 'FUND_ALLOCATION'
                    //         ]);
                    //     } else {
                    //         $costCenter = $this->record;
                    //
                    //         // Check if office relationship exists
                    //         if (!$costCenter->office) {
                    //             Log::warning('SMS notification skipped: Office not found for cost center', [
                    //                 'cost_center_id' => $costCenter->id,
                    //                 'cost_center_name' => $costCenter->name,
                    //                 'context' => 'FUND_ALLOCATION'
                    //             ]);
                    //         } else {
                    //             $office = $costCenter->office;
                    //
                    //             // Check if head employee exists
                    //             if (!$office->head_employee) {
                    //                 Log::warning('SMS notification skipped: Head employee not found for office', [
                    //                     'office_id' => $office->id,
                    //                     'office_name' => $office->office_name ?? 'N/A',
                    //                     'cost_center_id' => $costCenter->id,
                    //                     'context' => 'FUND_ALLOCATION'
                    //                 ]);
                    //             } else {
                    //                 $headEmployee = $office->head_employee;
                    //
                    //                 // Check if user relationship exists
                    //                 if (!$headEmployee->user) {
                    //                     Log::warning('SMS notification skipped: User not found for head employee', [
                    //                         'employee_id' => $headEmployee->id,
                    //                         'employee_name' => $headEmployee->first_name . ' ' . $headEmployee->last_name,
                    //                         'office_id' => $office->id,
                    //                         'context' => 'FUND_ALLOCATION'
                    //                     ]);
                    //                 } else {
                    //                     $user = $headEmployee->user;
                    //
                    //                     // Get phone number with null safety
                    //                     $phone = $headEmployee->contact_number ?? null;
                    //                     // $phone = "09366303145"; // TEST PHONE - Uncomment for testing
                    //
                    //                     if (!$phone) {
                    //                         Log::warning('SMS notification skipped: No contact number for head employee', [
                    //                             'user_id' => $user->id,
                    //                             'user_name' => $user->name,
                    //                             'employee_id' => $headEmployee->id,
                    //                             'context' => 'FUND_ALLOCATION'
                    //                         ]);
                    //                     } else {
                    //                         // Prepare data with null safety
                    //                         $amount = $totalAmount > 0 ? number_format($totalAmount, 2) : '0.00';
                    //                         $fundName = $costCenter->fundClusterWFP->name ?? 'N/A';
                    //                         $mfoName = $costCenter->mfo->name ?? 'N/A';
                    //                         $costCenterName = $costCenter->name ?? 'N/A';
                    //
                    //                         // Get WFP period name
                    //                         $wpfType = WpfType::find($this->selectedType);
                    //                         $wfpPeriod = $wpfType ? $wpfType->name : 'N/A';
                    //
                    //                         // Build SMS message
                    //                         $message = "You have been allocated a fund of ₱{$amount} under Fund {$fundName} {$mfoName} {$costCenterName} for the financial period {$wfpPeriod}. Please program your expenditures immediately in close coordination with your supervisor and with the Finance Division.";
                    //
                    //                         // Dispatch SMS job
                    //                         SendSmsJob::dispatch(
                    //                             $phone,
                    //                             $message,
                    //                             'FUND_ALLOCATION',
                    //                             $user->id,
                    //                             Auth::id()
                    //                         );
                    //
                    //                         Log::info('Fund allocation SMS queued successfully', [
                    //                             'phone' => $phone,
                    //                             'user_id' => $user->id,
                    //                             'user_name' => $user->name,
                    //                             'cost_center_id' => $costCenter->id,
                    //                             'cost_center_name' => $costCenterName,
                    //                             'amount' => $amount,
                    //                             'wfp_period' => $wfpPeriod,
                    //                             'context' => 'FUND_ALLOCATION'
                    //                         ]);
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //     }
                    // } catch (\Exception $e) {
                    //     Log::error('Fund allocation SMS notification failed', [
                    //         'error' => $e->getMessage(),
                    //         'line' => $e->getLine(),
                    //         'file' => $e->getFile(),
                    //         'cost_center_id' => $this->record->id ?? null,
                    //         'context' => 'FUND_ALLOCATION'
                    //     ]);
                    // }
                    // ========== SMS NOTIFICATION END ==========

                    Notification::make()->title('Successfully Saved')->success()->send();
                    return redirect()->route('wfp.fund-allocation', ['filter' => $this->record->fundClusterWFP->id]);
                }

            }

        }

        public function confirmAllocation161()
        {
            $this->dialog()->confirm([
                'title' => 'Are you Sure?',
                'description' => 'Do you really want to save this information?',
                'acceptLabel' => 'Yes, save it',
                'method' => 'submitAllocation161',
            ]);
        }

        public function submitAllocation161()
        {
            if ($this->selectedType === "" || $this->selectedType === null) {
                Notification::make()->title('Please Select a WFP Period')->danger()->send();
            } else {
                $this->validate([
                    'fundInitialAmount' => 'required|numeric|min:100',
                    'fund_description' => 'required'
                ],
                    [
                        'fundInitialAmount.required' => 'The amount field is required',
                        'fundInitialAmount.numeric' => 'The amount field must be a number',
                        'fundInitialAmount.min' => 'The amount field must be at least 100',
                        'fund_description.required' => 'The description field is required'

                    ]);

                FundAllocation::create([
                    'cost_center_id' => $this->record->id,
                    'wpf_type_id' => $this->selectedType,
                    'fund_cluster_id' => $this->record->fundClusterWFP->id,
                    'initial_amount' => $this->fundInitialAmount,
                    'description' => $this->fund_description
                ]);

                // ========== SMS NOTIFICATION START (FUND 161) ==========
                // COMMENTED OUT - TO BE CONFIRMED BY ACCOUNTANT
                // try {
                //     // Validate cost center exists
                //     if (!$this->record) {
                //         Log::warning('SMS notification skipped: Cost center not found', [
                //             'context' => 'FUND_ALLOCATION_161'
                //         ]);
                //     } else {
                //         $costCenter = $this->record;
                //
                //         // Check if office relationship exists
                //         if (!$costCenter->office) {
                //             Log::warning('SMS notification skipped: Office not found for cost center', [
                //                 'cost_center_id' => $costCenter->id,
                //                 'cost_center_name' => $costCenter->name,
                //                 'context' => 'FUND_ALLOCATION_161'
                //             ]);
                //         } else {
                //             $office = $costCenter->office;
                //
                //             // Check if head employee exists
                //             if (!$office->head_employee) {
                //                 Log::warning('SMS notification skipped: Head employee not found for office', [
                //                     'office_id' => $office->id,
                //                     'office_name' => $office->office_name ?? 'N/A',
                //                     'cost_center_id' => $costCenter->id,
                //                     'context' => 'FUND_ALLOCATION_161'
                //                 ]);
                //             } else {
                //                 $headEmployee = $office->head_employee;
                //
                //                 // Check if user relationship exists
                //                 if (!$headEmployee->user) {
                //                     Log::warning('SMS notification skipped: User not found for head employee', [
                //                         'employee_id' => $headEmployee->id,
                //                         'employee_name' => $headEmployee->first_name . ' ' . $headEmployee->last_name,
                //                         'office_id' => $office->id,
                //                         'context' => 'FUND_ALLOCATION_161'
                //                     ]);
                //                 } else {
                //                     $user = $headEmployee->user;
                //
                //                     // Get phone number with null safety
                //                     $phone = $headEmployee->contact_number ?? null;
                //                     // $phone = "09366303145"; // TEST PHONE - Uncomment for testing
                //
                //                     if (!$phone) {
                //                         Log::warning('SMS notification skipped: No contact number for head employee', [
                //                             'user_id' => $user->id,
                //                             'user_name' => $user->name,
                //                             'employee_id' => $headEmployee->id,
                //                             'context' => 'FUND_ALLOCATION_161'
                //                         ]);
                //                     } else {
                //                         // Prepare data with null safety
                //                         $amount = $this->fundInitialAmount > 0 ? number_format($this->fundInitialAmount, 2) : '0.00';
                //                         $fundName = $costCenter->fundClusterWFP->name ?? 'N/A';
                //                         $mfoName = $costCenter->mfo->name ?? 'N/A';
                //                         $costCenterName = $costCenter->name ?? 'N/A';
                //
                //                         // Get WFP period name
                //                         $wpfType = WpfType::find($this->selectedType);
                //                         $wfpPeriod = $wpfType ? $wpfType->name : 'N/A';
                //
                //                         // Build SMS message
                //                         $message = "You have been allocated a fund of ₱{$amount} under Fund {$fundName} {$mfoName} {$costCenterName} for the financial period {$wfpPeriod}. Please program your expenditures immediately in close coordination with your supervisor and with the Finance Division.";
                //
                //                         // Dispatch SMS job
                //                         SendSmsJob::dispatch(
                //                             $phone,
                //                             $message,
                //                             'FUND_ALLOCATION_161',
                //                             $user->id,
                //                             Auth::id()
                //                         );
                //
                //                         Log::info('Fund 161 allocation SMS queued successfully', [
                //                             'phone' => $phone,
                //                             'user_id' => $user->id,
                //                             'user_name' => $user->name,
                //                             'cost_center_id' => $costCenter->id,
                //                             'cost_center_name' => $costCenterName,
                //                             'amount' => $amount,
                //                             'wfp_period' => $wfpPeriod,
                //                             'context' => 'FUND_ALLOCATION_161'
                //                         ]);
                //                     }
                //                 }
                //             }
                //         }
                //     }
                // } catch (\Exception $e) {
                //     Log::error('Fund 161 allocation SMS notification failed', [
                //         'error' => $e->getMessage(),
                //         'line' => $e->getLine(),
                //         'file' => $e->getFile(),
                //         'cost_center_id' => $this->record->id ?? null,
                //         'context' => 'FUND_ALLOCATION_161'
                //     ]);
                // }
                // ========== SMS NOTIFICATION END (FUND 161) ==========

                Notification::make()->title('Successfully Saved')->success()->send();
                return redirect()->route('wfp.fund-allocation', ['filter' => $this->record->fundClusterWFP->id]);
            }

        }

        public function render()
        {

            return view('livewire.w-f-p.allocate-funds', [
                'category_groups' => $this->category_groups
            ]);
        }
    }
