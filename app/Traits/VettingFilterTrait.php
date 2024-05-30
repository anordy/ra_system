<?php

namespace App\Traits;

use App\Models\Returns\ReturnStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait VettingFilterTrait
{
    //filter data according to user criteria
    public function dataFilter($filter, $data, $returnTable)
    {
        if ($data == []) {
            $filter->whereMonth($returnTable . '.created_at', '=', date('m'));
            $filter->whereYear($returnTable . '.created_at', '=', date('Y'));
        }
        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['year']) && $data['year'] != 'All' && $data['year'] != 'Custom Range') {
            $filter->whereYear($returnTable . '.created_at', '=', $data['year']);
        }
        if (isset($data['month']) && $data['month'] != 'all' && $data['year'] != 'Custom Range') {
            $filter->whereMonth($returnTable . '.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] == 'Custom Range') {
            $from = Carbon::create($data['from'])->startOfDay();
            $to   = Carbon::create($data['to'])->endOfDay();
            $filter->whereBetween($returnTable . '.created_at', [$from, $to]);
        }
        
        return $filter;
    }

}
