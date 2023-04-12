<?php

namespace App\Traits;

use App\Enum\BillStatus;
use App\Exports\PaymentReportExport;
use App\Exports\ReturnReportExport;
use App\Models\RenewTaxAgentRequest;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\TaxReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxAgent;
use App\Models\ZmBill;
use App\Models\ZmEgaCharge;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

trait PaymentReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['payment_category'] == 'consultant') {
            $model = ZmBill::query()->whereIn('billable_type', [TaxAgent::class, RenewTaxAgentRequest::class]);
            if ($parameters['status'] == 'paid') {
                $data = clone $model->where('status', $parameters['status']);
            } elseif ($parameters['status'] == 'pending') {
                $data = clone $model->where('status', $parameters['status'])->get();
                dd($data);
            } else {
                $data = clone $model->whereIn('status', ['pending', 'paid']);
            }
        } else {
            if ($parameters['tax_type_id'] == 'all') {
                $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id');
            } else {
                $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')->where('tax_returns.tax_type_id', $parameters['tax_type_id']);
            }
            if ($parameters['status'] == 'paid') {
                $data = clone $model->where('payment_status', BillStatus::COMPLETE);
            } elseif ($parameters['status'] == 'pending') {
                $data = clone $model->where('payment_status', BillStatus::CN_GENERATED);
            } else {
                $data = clone $model->whereIn('payment_status', [BillStatus::CN_GENERATED, BillStatus::COMPLETE]);
            }
        }
        return $this->getSelectedRecords($data, $parameters);
    }

    public function getSelectedRecords($data, $parameters)
    {
        $dates = $parameters['dates'];
        if ($dates == []) {
            if ($parameters['payment_category'] == 'returns') {
                return $data->orderBy('tax_returns.created_at', 'asc');
            } else {
                return $data->orderBy('created_at', 'asc');
            }
        }
        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            if ($parameters['payment_category'] == 'returns') {
                return $data->orderBy('tax_returns.created_at', 'asc');
            } else {
                return $data->orderBy('created_at', 'asc');
            }
        }
        if ($parameters['payment_category'] == 'returns') {
            return $data->whereBetween('tax_returns.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('tax_returns.created_at', 'asc');
        } else {
            return $data->whereBetween('created_at', [$dates['startDate'], $dates['endDate']])->orderBy('created_at', 'asc');
        }
    }

    public function exportExcelReport($parameters)
    {
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->customAlert('error', 'No Records Found in the selected criteria');
            return;
        }

        if ($parameters['year'] == 'all') {
            $fileName = $parameters['status'] . ' ' . $parameters['payment_category'] . '.xlsx';
            $title = $parameters['status'] . ' ' . $parameters['payment_category'];
        } else {
            $fileName = $parameters['status'] . ' ' . $parameters['payment_category'] . ' - ' . $parameters['year'] . '.xlsx';
            $title = $parameters['status'] . ' ' . $parameters['payment_category'] . ' ' . $parameters['year'];
        }
        $this->customAlert('success', 'Exporting Excel File');
        return Excel::download(new PaymentReportExport($records, $title, $parameters), $fileName);
    }

    public function exportPdfReport($parameters)
    {
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->customAlert('error', 'No Records Found in the selected criteria');

            return;
        }
        $this->customAlert('success', 'Exporting Pdf File');

        return redirect()->route('reports.payments.download.pdf', encrypt(json_encode($parameters)));
    }

    public function getModel()
    {
        $models = [
            VatReturn::class,
            HotelReturn::class,
            StampDutyReturn::class,
            MnoReturn::class,
            BfoReturn::class,
            LumpSumReturn::class,
            PortReturn::class,
            TaxAgent::class,
            PetroleumReturn::class,
            MmTransferReturn::class,
            EmTransactionReturn::class,
        ];
        return $models;
    }

    public function getEgaChargesQuery($range_start, $range_end, $currency, $payment_status, $charges_type)
    {
        $query = ZmEgaCharge::join('zm_bills', 'zm_ega_charges.zm_bill_id', 'zm_bills.id')->whereBetween('zm_ega_charges.created_at', [
            Carbon::parse($range_start)->startOfDay()->toDateTimeString(),
            Carbon::parse($range_end)->endOfDay()->toDateTimeString()
        ]);

        if ($currency != 'all') {
            $query->where('zm_ega_charges.currency', $currency);
        }

        if ($payment_status != 'all') {
            switch ($payment_status) {
                case 'paid':
                    $query->where('zm_bills.paid_amount', '>', 0);
                    break;
                case 'unpaid':
                    $query->where('zm_bills.paid_amount', '<=', 0);
                    break;
            }
        }

        if ($charges_type != 'all') {
            switch ($charges_type) {
                case 'charges-included':
                    $query->where('zm_ega_charges.ega_charges_included', true);
                    break;
                case 'charges-excluded':
                    $query->where('zm_ega_charges.ega_charges_included', false);
                    break;
            }
        }

        return $query;
    }
}
