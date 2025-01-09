<?php

namespace App\Http\Livewire\WFP;

use App\Models\Wfp;
use App\Models\WfpDetail;
use Livewire\Component;

class GeneratePpmp extends Component
{
    public $wfp_type;
    public $ppmp_details;
    public $is_active = false;
    public $title;
    public $total;

    //101
    public function sksuPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Sultan Kudarat State University';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)->where('is_approved', 1);
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)->where('is_approved', 1);
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function gasPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'General Admission and Support Services';
         // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->whereHas('costCenter', function($query) {
        //         $query->where('m_f_o_s_id', 1);
        //     });
        // })->get();
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 1);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 1);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

    }

    public function hesPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Higher Education Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 2);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 2);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Advanced Education Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 3);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 3);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Research and Development';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 4);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 4);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Extension Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 5);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 5);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Local Fund Projects';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    //161
    public function sksuPpmp161()
    {
        $this->is_active = true;
        $this->title = 'Sultan Kudarat State University';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('fund_cluster_w_f_p_s_id', 2)->where('is_approved', 1);
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('fund_cluster_w_f_p_s_id', 2)->where('is_approved', 1);
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function gasPpmp161()
    {
        // $this->is_active = false;
        $this->is_active = true;
        $this->title = 'General Admission and Support Services';
         // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->whereHas('costCenter', function($query) {
        //         $query->where('m_f_o_s_id', 1);
        //     });
        // })->get();
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 1);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 1);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

    }

    public function hesPpmp161()
    {
        // $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Higher Education Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 2);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 2);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp161()
    {
        // $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Advanced Education Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 3);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 3);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp161()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Research and Development';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 4);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 4);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp161()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Extension Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 5);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 5);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp161()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->title = 'Local Fund Projects';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    //163
    public function sksuPpmp163()
    {
        $this->is_active = true;
        $this->title = 'Sultan Kudarat State University';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3);
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3);
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function accessPpmp163()
    {
        $this->is_active = true;
        $this->title = 'ACCESS Campus';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 1);
            });
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 1);
            });
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function tacurongPpmp163()
    {
        $this->is_active = true;
        $this->title = 'Tacurong Campus';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 2);
            });
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 2);
            });
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function isulanPpmp163()
    {
        $this->is_active = true;
        $this->title = 'Isulan Campus';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 3);
            });
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 3);
            });
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function kalamansigPpmp163()
    {
        $this->is_active = true;
        $this->title = 'Kalamansig Campus';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 4);
            });
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 4);
            });
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lutayanPpmp163()
    {
        $this->is_active = true;
        $this->title = 'Lutayan Campus';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 5);
            });
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 5);
            });
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function palimbangPpmp163()
    {
        $this->is_active = true;
        $this->title = 'Palimbang Campus';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 6);
            });
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 6);
            });
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function bagumbayanPpmp163()
    {
        $this->is_active = true;
        $this->title = 'Bagumbayan Campus';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 7);
            });
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
                $query->where('campus_id', 7);
            });
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    //164T
    public function sksuPpmp164T()
    {
        $this->is_active = true;
        $this->title = 'Sultan Kudarat State University';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('fund_cluster_w_f_p_s_id', 4)->where('is_approved', 1);
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('fund_cluster_w_f_p_s_id', 4)->where('is_approved', 1);
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function gasPpmp164T()
    {
        $this->is_active = true;
        $this->title = 'General Admission and Support Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 1);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 1);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

    }

    public function hesPpmp164T()
    {
        $this->is_active = true;
        $this->title = 'Higher Education Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 2);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 2);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp164T()
    {
        $this->is_active = true;
        $this->title = 'Advanced Education Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 3);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 3);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp164T()
    {
        $this->is_active = true;
        $this->title = 'Research and Development';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 4);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 4);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp164T()
    {
        $this->is_active = true;
        $this->title = 'Extension Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 5);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 5);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp164T()
    {
        $this->is_active = true;
        $this->title = 'Local Fund Projects';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

     //164T-NonFHE
     public function sksuPpmp164TN()
     {
         $this->is_active = true;
         $this->title = 'Sultan Kudarat State University';
         $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
             $query->where('fund_cluster_w_f_p_s_id', 7)->where('is_approved', 1);
         })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
         ->groupBy('category_item_id')
         ->get();
         $this->total = WfpDetail::whereHas('wfp', function($query) {
             $query->where('fund_cluster_w_f_p_s_id', 7)->where('is_approved', 1);
         })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
     }

     public function gasPpmp164TN()
     {
         $this->is_active = true;
         $this->title = 'General Admission and Support Services';
         $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 1);
             });
         })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
         ->groupBy('category_item_id')
         ->get();
         $this->total = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 1);
             });
         })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

     }

     public function hesPpmp164TN()
     {
         $this->is_active = true;
         $this->title = 'Higher Education Services';
         $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 2);
             });
         })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
         ->groupBy('category_item_id')
         ->get();
         $this->total = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 2);
             });
         })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
     }

     public function aesPpmp164TN()
     {
         $this->is_active = true;
         $this->title = 'Advanced Education Services';
         $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 3);
             });
         })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
         ->groupBy('category_item_id')
         ->get();
         $this->total = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 3);
             });
         })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
     }

     public function rdPpmp164TN()
     {
         $this->is_active = true;
         $this->title = 'Research and Development';
         $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 4);
             });
         })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
         ->groupBy('category_item_id')
         ->get();
         $this->total = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 4);
             });
         })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
     }

     public function extensionPpmp164TN()
     {
         $this->is_active = true;
         $this->title = 'Extension Services';
         $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 5);
             });
         })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
         ->groupBy('category_item_id')
         ->get();
         $this->total = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 5);
             });
         })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
     }

     public function lfPpmp164TN()
     {
         $this->is_active = true;
         $this->title = 'Local Fund Projects';
         $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 6);
             });
         })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
         ->groupBy('category_item_id')
         ->get();
         $this->total = WfpDetail::whereHas('wfp', function($query) {
             $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
             $query->where('m_f_o_s_id', 6);
             });
         })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
     }


    //164OSF
        public function sksuPpmp164OSF()
        {
            $this->is_active = true;
            $this->title = 'Sultan Kudarat State University';
            $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
                $query->where('fund_cluster_w_f_p_s_id', 5)->where('is_approved', 1);
            })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->groupBy('category_item_id')
            ->get();
            $this->total = WfpDetail::whereHas('wfp', function($query) {
                $query->where('fund_cluster_w_f_p_s_id', 5)->where('is_approved', 1);
            })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function gasPpmp164OSF()
        {
            $this->is_active = true;
            $this->title = 'General Admission and Support Services';
            $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 1);
                });
            })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->groupBy('category_item_id')
            ->get();
            $this->total = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 1);
                });
            })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        }

        public function hesPpmp164OSF()
        {
            $this->is_active = true;
            $this->title = 'Higher Education Services';
            $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 2);
                });
            })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->groupBy('category_item_id')
            ->get();
            $this->total = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 2);
                });
            })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function aesPpmp164OSF()
        {
            $this->is_active = true;
            $this->title = 'Advanced Education Services';
            $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 3);
                });
            })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->groupBy('category_item_id')
            ->get();
            $this->total = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 3);
                });
            })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function rdPpmp164OSF()
        {
            $this->is_active = true;
            $this->title = 'Research and Development';
            $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 4);
                });
            })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->groupBy('category_item_id')
            ->get();
            $this->total = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 4);
                });
            })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function extensionPpmp164OSF()
        {
            $this->is_active = true;
            $this->title = 'Extension Services';
            $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 5);
                });
            })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->groupBy('category_item_id')
            ->get();
            $this->total = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 5);
                });
            })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lfPpmp164OSF()
        {
            $this->is_active = true;
            $this->title = 'Local Fund Projects';
            $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 6);
                });
            })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->groupBy('category_item_id')
            ->get();
            $this->total = WfpDetail::whereHas('wfp', function($query) {
                $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
                $query->where('m_f_o_s_id', 6);
                });
            })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

    //164MF
    public function sksuPpmp164MF()
    {
        $this->is_active = true;
        $this->title = 'Sultan Kudarat State University';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('fund_cluster_w_f_p_s_id', 6)->where('is_approved', 1);
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('fund_cluster_w_f_p_s_id', 6)->where('is_approved', 1);
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function gasPpmp164MF()
    {
        $this->is_active = true;
        $this->title = 'General Admission and Support Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 1);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 1);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

    }

    public function hesPpmp164MF()
    {
        $this->is_active = true;
        $this->title = 'Higher Education Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 2);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 2);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp164MF()
    {
        $this->is_active = true;
        $this->title = 'Advanced Education Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 3);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 3);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp164MF()
    {
        $this->is_active = true;
        $this->title = 'Research and Development';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 4);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 4);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp164MF()
    {
        $this->is_active = true;
        $this->title = 'Extension Services';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 5);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 5);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp164MF()
    {
        $this->is_active = true;
        $this->title = 'Local Fund Projects';
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6);
            });
        })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
            $query->where('m_f_o_s_id', 6);
            });
        })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }


    public function resetPrintable()
    {
        $this->is_active = false;
    }

    public function render()
    {
        return view('livewire.w-f-p.generate-ppmp');
    }
}
