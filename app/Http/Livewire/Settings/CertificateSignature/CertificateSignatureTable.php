<?php

namespace App\Http\Livewire\Settings\CertificateSignature;

use App\Enum\CustomMessage;
use App\Models\CertificateSignature;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CertificateSignatureTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): Builder
    {
        return CertificateSignature::query()
            ->orderBy('certificate_signatures.created_at', 'desc');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('id')) {
                return [
                    'style' => 'width: 20%;',
                ];
            }

            return [];
        });
    }

    protected $listeners = [
        'confirmed'
    ];

    public function columns(): array
    {
        return [
            Column::make('Title', 'title')
                ->searchable(),
            Column::make('Name', 'name')
                ->searchable(),
            Column::make('Start Date', 'start_date')
                ->format(function ($value) {
                    return Carbon::create($value)->format('d M Y');
                }),
            Column::make('End Date', 'end_date')
                ->format(function ($value) {
                    return Carbon::create($value)->format('d M Y');
                }),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Updated</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Approved</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<<HTML
                            <span class="badge badge-danger p-2 rounded-0" >Rejected</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Added On', 'created_at')
                ->format(function ($value) {
                    return Carbon::create($value)->format('d M Y');
                }),
            Column::make('Action', 'id')
                ->view('settings.certificate-signature.includes.actions'),
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('setting-certificate-signature-delete')) {
            abort(403);
        }

        $id = decrypt($id);

        $this->customAlert('warning', 'Are you sure you want to delete ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Delete',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object)$value['data'];

            $signature = CertificateSignature::find($data->id);

            if (is_null($signature)) {
                abort(404);
            }

            $deleted = $signature->delete();

            if (!$deleted) throw new Exception('Failed to Delete Signature');

            $this->flash('success', 'Signature Deleted Successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('warning', CustomMessage::ERROR);
        }
    }
}
