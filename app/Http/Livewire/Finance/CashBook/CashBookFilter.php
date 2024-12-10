<?php

namespace App\Http\Livewire\Finance\CashBook;

use App\Models\TaxType;
use App\Traits\CustomAlert;
use Livewire\Component;
use PDF;

class CashBookFilter extends Component
{
    use CustomAlert;

    public $tableName;
    public $data;
    public $currency;
    public $tax_types;
    public $tax_type_id;
    public $range_start;
    public $range_end;

    protected $rules = [
        'range_start' => 'required|strip_tag',
        'range_end' => 'required|strip_tag'
    ];

    public function mount($tablename)
    {
        $this->tax_types = TaxType::where('category', 'main')->get();
        $this->tableName = $tablename;
        $this->range_start = date('Y-m-d', strtotime(now()));
        $this->range_end = date('Y-m-d', strtotime(now()));
    }

    public function filter()
    {
        $this->validate();
        $filters = [
            'tax_type_id' => $this->tax_type_id,
            'range_start' => date('Y-m-d 00:00:00', strtotime($this->range_start)),
            'range_end'   => date('Y-m-d 23:59:59', strtotime($this->range_end)),
        ];
        $this->data = $filters;
        
        if ($this->tableName == 'cash-book-table') {
            $this->emitTo('App\Http\Livewire\Finance\CashBook\CashBookTable', 'filterData', $filters);
        }
    }


    public function render()
    {
        return view('livewire.finance.cashbook.cashbook-filter');
    }
}
