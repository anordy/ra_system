<?php

namespace App\Http\Livewire\Settings\CertificateSignature;

use App\Enum\CustomMessage;
use App\Models\BankAccount;
use App\Models\CertificateSignature;
use App\Traits\CustomAlert;
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
                ->sortable()
                ->searchable(),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Start Date', 'start_date')
                ->sortable()
                ->searchable(),
            Column::make('End Date', 'end_date')
                ->sortable()
                ->searchable(),
            Column::make('Added On', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->view('settings.certificate-signature.includes.actions')
        ];
    }


    public function delete($id)
    {
        if (!Gate::allows('setting-bank-delete')) {
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
            $data = (object) $value['data'];

            $signature = CertificateSignature::find($data->id);

            if(is_null($signature)){
                abort(404);
            }

            $deleted = $signature->delete();

            if (!$deleted) throw new Exception('Failed to Deleted Signature');

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
