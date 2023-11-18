<?php

namespace App\Http\Livewire\PropertyTax\PaymentExtension;

use App\Enum\PropertyTypeStatus;
use App\Models\PropertyTax\PaymentExtension;
use App\Models\PropertyTax\PropertyPayment;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PaymentExtensionTable extends DataTableComponent
{
    public $status;
    public function mount($status){
        $this->status = $status;
    }
    public function builder(): Builder
    {
        return  PaymentExtension::query()->where('status', $this->status)->orderByDesc('created_at');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['requested_by_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Property Name', 'property_payment_id')
                ->searchable()
                ->format(function ($value, $row) {
                    if ($row->propertyPayment->property->type != PropertyTypeStatus::CONDOMINIUM) {
                        return $row->propertyPayment->property->name ?? 'N/A';
                    } else {
                        return "{$row->propertyPayment->property->name} - {$row->propertyPayment->property->unit->name}";
                    }
                }),
            Column::make('Control Number', 'id')
                ->searchable()
                ->format(function ($value, $row) {
                   return $row->propertyPayment->latestBill->control_number;
                }),
            Column::make('Tax Amount', 'created_at')
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($row->propertyPayment->total_amount, 2);
                }),
            Column::make('Requested By', 'requested_by_type')
                ->format(function ($value, $row) {
                    if (!$value) {
                        return 'N/A';
                    }
                    $requestedBy = $row->requested_by_type::find($row->requested_by_id);
                    if($row->requested_by_type == Taxpayer::class){
                        $name = $requestedBy->first_name .' '. $requestedBy->middle_name .' '. $requestedBy->last_name;
                    } else {
                        $name = $requestedBy->name;
                    }
                    return $name ?? 'N/A';
                }),
            Column::make('Current Due Date', 'extension_from')
                ->format(function ($value, $row) {
                    if (!$value) {
                        return 'N/A';
                    }

                    return Carbon::parse($value)->toFormattedDateString() ?? 'N/A';
                }),
            Column::make('Status', 'status')
                ->view('property-tax.payment-extension.includes.status'),
            Column::make('Action', 'updated_at')
                ->view('property-tax.payment-extension.includes.actions'),

        ];
    }

//    public function render()
//    {
//        return view('livewire.property-tax.payment-extension-table');
//    }
}
