<?php

namespace App\Http\Livewire\WFP;

use Livewire\Component;
use App\Models\WfpDetail;
use App\Models\WpfType;

class GenerateWfpPpmp extends Component
{
    public $record;
    public $wfpDetails;
    public $ppmp_details;
    public $is_active = false;
    public $title;
    public $total;
    public $wfp_types;
    public $selectedType;

    public $fundClusterWfpId = null;
    public $supplementalQuarterId = null;
    public $mfoId = null;
    public $campusId = null;

    protected $queryString = ['fundClusterWfpId', 'supplementalQuarterId', 'mfoId','title','campusId'];

    public function mount()
    {
        $this->wfp_types = WpfType::all();
        $this->selectedType = 1;
         $this->is_active = true;
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
            ->where('is_approved', 1)
            ->where('fund_cluster_w_f_p_s_id', $this->fundClusterWfpId)
            ->when($this->supplementalQuarterId, function ($query) {
                $query->where('supplemental_quarter_id', $this->supplementalQuarterId);
            })
            ->when(!$this->supplementalQuarterId, function ($query) {
                $query->where('is_supplemental', 0);
            })
            ->when($this->mfoId || $this->campusId, function ($query) {
                $query->whereHas('costCenter', function($query) {
                    $query->when($this->mfoId, function ($query) {
                        $query->where('m_f_o_s_id', $this->mfoId);
                    })
                    ->when($this->campusId, function ($query) {
                        $query->whereHas('office', function($query) {
                            $query->where('campus_id', $this->campusId);
                        });
                    });
                });
            });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months
            foreach (json_decode($record->merged_quantities) as $quantities) {
                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
    }
     //101
     public function sksuPpmp()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Sultan Kudarat State University';
         $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id',$this->selectedType)
             ->where('fund_cluster_w_f_p_s_id', 1)
             ->where('is_approved', 1)
            ->where('is_supplemental', 0);
         })
         ->with(['supply', 'categoryItem','wfp','budgetCategory'])  // Load both relationships
         ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
         ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
         ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function gasPpmp()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'General Admission and Support Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 1);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
        dd($this->record);
     }

     public function hesPpmp()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Higher Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_supplemental', 0)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 2);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function aesPpmp()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Advanced Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 3);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function rdPpmp()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Research and Development';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 4);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function extensionPpmp()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Extension Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 5);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function lfPpmp()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Local Fund Projects';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 6);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     //161
     public function sksuPpmp161()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Sultan Kudarat State University';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 3)
            ->where('is_supplemental', 0)->where('is_approved', 1);
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function gasPpmp161()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'General Admission and Support Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 1);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function hesPpmp161()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Higher Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 2);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function aesPpmp161()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Advanced Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 3);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function rdPpmp161()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Research and Development';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 4);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function extensionPpmp161()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Extension Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 5);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function lfPpmp161()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Local Fund Projects';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 6);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     //163
     public function sksuPpmp163()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Sultan Kudarat State University';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 2)
            ->where('is_supplemental', 0)->where('is_approved', 1);
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function accessPpmp163()
     {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'ACCESS Campus';
       $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
        $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
        ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 1);
            });
            });
       })
       ->with(['supply', 'categoryItem'])  // Load both relationships
       ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
       ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
       ->get();
       $this->total = $this->record->sum('estimated_budget');
       foreach ($this->record as $record) {
           $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

           foreach (json_decode($record->merged_quantities) as $quantities) {

               $quantitiesArray = json_decode(json_encode($quantities), true);
               foreach ($quantitiesArray as $monthIndex => $value) {
                   $mergedQuantities[$monthIndex] += (int) $value;
               }
           }
           $record->merged_quantities = $mergedQuantities;
       }
     }

     public function tacurongPpmp163()
     {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Tacurong Campus';
       $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
        $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
        ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 2);
            });
            });
       })
       ->with(['supply', 'categoryItem'])  // Load both relationships
       ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
       ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
       ->get();
       $this->total = $this->record->sum('estimated_budget');
       foreach ($this->record as $record) {
           $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

           foreach (json_decode($record->merged_quantities) as $quantities) {

               $quantitiesArray = json_decode(json_encode($quantities), true);
               foreach ($quantitiesArray as $monthIndex => $value) {
                   $mergedQuantities[$monthIndex] += (int) $value;
               }
           }
           $record->merged_quantities = $mergedQuantities;
       }
     }

     public function isulanPpmp163()
     {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Isulan Campus';
       $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
        $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
        ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 3);
            });
            });
       })
       ->with(['supply', 'categoryItem'])  // Load both relationships
       ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
       ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
       ->get();
       $this->total = $this->record->sum('estimated_budget');
       foreach ($this->record as $record) {
           $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

           foreach (json_decode($record->merged_quantities) as $quantities) {

               $quantitiesArray = json_decode(json_encode($quantities), true);
               foreach ($quantitiesArray as $monthIndex => $value) {
                   $mergedQuantities[$monthIndex] += (int) $value;
               }
           }
           $record->merged_quantities = $mergedQuantities;
       }
     }

     public function kalamansigPpmp163()
     {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Kalamansig Campus';
       $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
        $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
        ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 4);
            });
            });
       })
       ->with(['supply', 'categoryItem'])  // Load both relationships
       ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
       ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
       ->get();
       $this->total = $this->record->sum('estimated_budget');
       foreach ($this->record as $record) {
           $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

           foreach (json_decode($record->merged_quantities) as $quantities) {

               $quantitiesArray = json_decode(json_encode($quantities), true);
               foreach ($quantitiesArray as $monthIndex => $value) {
                   $mergedQuantities[$monthIndex] += (int) $value;
               }
           }
           $record->merged_quantities = $mergedQuantities;
       }
     }

     public function lutayanPpmp163()
     {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Lutayan Campus';
       $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
        $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
        ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 5);
            });
            });
       })
       ->with(['supply', 'categoryItem'])  // Load both relationships
       ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
       ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
       ->get();
       $this->total = $this->record->sum('estimated_budget');
       foreach ($this->record as $record) {
           $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

           foreach (json_decode($record->merged_quantities) as $quantities) {

               $quantitiesArray = json_decode(json_encode($quantities), true);
               foreach ($quantitiesArray as $monthIndex => $value) {
                   $mergedQuantities[$monthIndex] += (int) $value;
               }
           }
           $record->merged_quantities = $mergedQuantities;
       }
     }

     public function palimbangPpmp163()
     {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Palimbang Campus';
       $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
        $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
        ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 6);
            });
            });
       })
       ->with(['supply', 'categoryItem'])  // Load both relationships
       ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
       ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
       ->get();
       $this->total = $this->record->sum('estimated_budget');
       foreach ($this->record as $record) {
           $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

           foreach (json_decode($record->merged_quantities) as $quantities) {

               $quantitiesArray = json_decode(json_encode($quantities), true);
               foreach ($quantitiesArray as $monthIndex => $value) {
                   $mergedQuantities[$monthIndex] += (int) $value;
               }
           }
           $record->merged_quantities = $mergedQuantities;
       }
     }

     public function bagumbayanPpmp163()
     {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Bagumbayan Campus';
       $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
        $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
        ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 7);
            });
            });
       })
       ->with(['supply', 'categoryItem'])  // Load both relationships
       ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
       ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
       ->get();
       $this->total = $this->record->sum('estimated_budget');
       foreach ($this->record as $record) {
           $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

           foreach (json_decode($record->merged_quantities) as $quantities) {

               $quantitiesArray = json_decode(json_encode($quantities), true);
               foreach ($quantitiesArray as $monthIndex => $value) {
                   $mergedQuantities[$monthIndex] += (int) $value;
               }
           }
           $record->merged_quantities = $mergedQuantities;
       }
     }

     //164T
     public function sksuPpmp164T()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Sultan Kudarat State University';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 4)->where('is_approved', 1);
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function gasPpmp164T()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'General Admission and Support Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 1);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function hesPpmp164T()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Higher Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 2);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function aesPpmp164T()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Advanced Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 3);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function rdPpmp164T()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Research and Development';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 4);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function extensionPpmp164T()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Extension Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 5);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }


     public function lfPpmp164T()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Local Fund Projects';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 6);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

          //164T-NonFHE
          public function sksuPpmp164TN()
          {
              $this->is_active = false;
              $this->is_active = true;
              $this->title = 'Sultan Kudarat State University';
             $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
                 $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                 ->where('is_supplemental', 0)->where('is_approved', 1);
             })
             ->with(['supply', 'categoryItem'])  // Load both relationships
             ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
             ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
             ->get();
             $this->total = $this->record->sum('estimated_budget');
             foreach ($this->record as $record) {
                 $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

                 foreach (json_decode($record->merged_quantities) as $quantities) {

                     $quantitiesArray = json_decode(json_encode($quantities), true);
                     foreach ($quantitiesArray as $monthIndex => $value) {
                         $mergedQuantities[$monthIndex] += (int) $value;
                     }
                 }
                 $record->merged_quantities = $mergedQuantities;
             }
          }

          public function gasPpmp164TN()
          {
              $this->is_active = false;
              $this->is_active = true;
              $this->title = 'General Admission and Support Services';
             $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
                 $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
                 ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
                     $query->where('m_f_o_s_id', 1);
                     });
             })
             ->with(['supply', 'categoryItem'])  // Load both relationships
             ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
             ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
             ->get();
             $this->total = $this->record->sum('estimated_budget');
             foreach ($this->record as $record) {
                 $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

                 foreach (json_decode($record->merged_quantities) as $quantities) {

                     $quantitiesArray = json_decode(json_encode($quantities), true);
                     foreach ($quantitiesArray as $monthIndex => $value) {
                         $mergedQuantities[$monthIndex] += (int) $value;
                     }
                 }
                 $record->merged_quantities = $mergedQuantities;
             }
          }

          public function hesPpmp164TN()
          {
              $this->is_active = false;
              $this->is_active = true;
              $this->title = 'Higher Education Services';
             $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
                 $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
                 ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
                     $query->where('m_f_o_s_id', 2);
                     });
             })
             ->with(['supply', 'categoryItem'])  // Load both relationships
             ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
             ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
             ->get();
             $this->total = $this->record->sum('estimated_budget');
             foreach ($this->record as $record) {
                 $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

                 foreach (json_decode($record->merged_quantities) as $quantities) {

                     $quantitiesArray = json_decode(json_encode($quantities), true);
                     foreach ($quantitiesArray as $monthIndex => $value) {
                         $mergedQuantities[$monthIndex] += (int) $value;
                     }
                 }
                 $record->merged_quantities = $mergedQuantities;
             }
          }

          public function aesPpmp164TN()
          {
              $this->is_active = false;
              $this->is_active = true;
              $this->title = 'Advanced Education Services';
             $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
                 $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
                 ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
                     $query->where('m_f_o_s_id', 3);
                     });
             })
             ->with(['supply', 'categoryItem'])  // Load both relationships
             ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
             ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
             ->get();
             $this->total = $this->record->sum('estimated_budget');
             foreach ($this->record as $record) {
                 $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

                 foreach (json_decode($record->merged_quantities) as $quantities) {

                     $quantitiesArray = json_decode(json_encode($quantities), true);
                     foreach ($quantitiesArray as $monthIndex => $value) {
                         $mergedQuantities[$monthIndex] += (int) $value;
                     }
                 }
                 $record->merged_quantities = $mergedQuantities;
             }
          }

          public function rdPpmp164TN()
          {
              $this->is_active = false;
              $this->is_active = true;
              $this->title = 'Research and Development';
             $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
                 $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
                 ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
                     $query->where('m_f_o_s_id', 4);
                     });
             })
             ->with(['supply', 'categoryItem'])  // Load both relationships
             ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
             ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
             ->get();
             $this->total = $this->record->sum('estimated_budget');
             foreach ($this->record as $record) {
                 $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

                 foreach (json_decode($record->merged_quantities) as $quantities) {

                     $quantitiesArray = json_decode(json_encode($quantities), true);
                     foreach ($quantitiesArray as $monthIndex => $value) {
                         $mergedQuantities[$monthIndex] += (int) $value;
                     }
                 }
                 $record->merged_quantities = $mergedQuantities;
             }
          }

          public function extensionPpmp164TN()
          {
              $this->is_active = false;
              $this->is_active = true;
              $this->title = 'Extension Services';
             $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
                 $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
                 ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
                     $query->where('m_f_o_s_id', 5);
                     });
             })
             ->with(['supply', 'categoryItem'])  // Load both relationships
             ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
             ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
             ->get();
             $this->total = $this->record->sum('estimated_budget');
             foreach ($this->record as $record) {
                 $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

                 foreach (json_decode($record->merged_quantities) as $quantities) {

                     $quantitiesArray = json_decode(json_encode($quantities), true);
                     foreach ($quantitiesArray as $monthIndex => $value) {
                         $mergedQuantities[$monthIndex] += (int) $value;
                     }
                 }
                 $record->merged_quantities = $mergedQuantities;
             }
          }


          public function lfPpmp164TN()
          {
              $this->is_active = false;
              $this->is_active = true;
              $this->title = 'Local Fund Projects';
             $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
                 $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
                 ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
                     $query->where('m_f_o_s_id', 6);
                     });
             })
             ->with(['supply', 'categoryItem'])  // Load both relationships
             ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
             ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
             ->get();
             $this->total = $this->record->sum('estimated_budget');
             foreach ($this->record as $record) {
                 $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

                 foreach (json_decode($record->merged_quantities) as $quantities) {

                     $quantitiesArray = json_decode(json_encode($quantities), true);
                     foreach ($quantitiesArray as $monthIndex => $value) {
                         $mergedQuantities[$monthIndex] += (int) $value;
                     }
                 }
                 $record->merged_quantities = $mergedQuantities;
             }
          }

     //164OSF
     public function sksuPpmp164OSF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Sultan Kudarat State University';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
            ->where('is_supplemental', 0)->where('is_approved', 1);
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function gasPpmp164OSF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'General Admission and Support Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 1);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function hesPpmp164OSF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Higher Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 2);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function aesPpmp164OSF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Advanced Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 3);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function rdPpmp164OSF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Research and Development';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 4);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function extensionPpmp164OSF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Extension Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 5);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function lfPpmp164OSF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Local Fund Projects';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 6);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     //164MF
     public function sksuPpmp164MF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Sultan Kudarat State University';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
            ->where('is_supplemental', 0)->where('is_approved', 1);
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function gasPpmp164MF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'General Admission and Support Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 1);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function hesPpmp164MF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Higher Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 2);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function aesPpmp164MF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Advanced Education Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 3);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function rdPpmp164MF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Research and Development';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 4);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function extensionPpmp164MF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Extension Services';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 5);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }

     public function lfPpmp164MF()
     {
         $this->is_active = false;
         $this->is_active = true;
         $this->title = 'Local Fund Projects';
        $this->record = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)
            ->where('is_supplemental', 0)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 6);
                });
        })
        ->with(['supply', 'categoryItem'])  // Load both relationships
        ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
        ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
        ->get();
        $this->total = $this->record->sum('estimated_budget');
        foreach ($this->record as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months

            foreach (json_decode($record->merged_quantities) as $quantities) {

                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }
     }


     public function resetPrintable()
     {
         $this->is_active = false;
     }

    public function render()
    {
        return view('livewire.w-f-p.generate-wfp-ppmp');
    }
}
