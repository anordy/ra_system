<?php

namespace App\Http\Livewire\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxpayersTable extends DataTableComponent
{
    use LivewireAlert;
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['first_name', 'middle_name', 'last_name', 'is_first_login']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    protected $listeners = [
        'sendCredential', 'amendmentDetailsRequest'
    ];

    public function builder(): Builder
    {
        return Taxpayer::query()->with('country', 'region','street')
            ->orderBy('taxpayers.id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Reference No.', 'reference_no')
                ->searchable()
                ->sortable(),
            Column::make('Name')
                ->label(fn ($row) => $row->fullname())
                ->sortable()
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                }),
            Column::make('Mobile No', 'mobile'),
            Column::make('Email Address', 'email'),
            Column::make('Nationality', 'country_id')
                ->format(fn ($value, $row) => $row->country->nationality ?? ''),
            Column::make('Location', 'region.name'),
            Column::make('Street', 'street.name'),
            Column::make('Action', 'id')->view('taxpayers.actions')
        ];
    }

    public function sendCredential($value)
    {
        $data = (object) $value['data'];
        $taxpayer = Taxpayer::find($data->id);
        if(is_null($taxpayer)){
            abort(404);
        }
        $permitted_chars = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ!@#%';
        $password = substr(str_shuffle($permitted_chars), 0, 8);
        $taxpayer->password = Hash::make($password);
        $taxpayer->save();

        event(new SendSms('taxpayer-registration', $taxpayer->id, ['code' => $password]));
        if ($taxpayer->email) {
            event(new SendMail('taxpayer-registration', $taxpayer->id, ['code' => $password]));
        }
        $this->flash('success', 'Credentials re-send successfully', [], redirect()->back()->getTargetUrl());

    }

    public function resendCredential($id)
    {
  
        $this->alert('warning', 'Are you sure you want to re-send new user credentials ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'sendCredential',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],
        ]);
    }
}
