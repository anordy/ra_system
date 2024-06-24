<?php

namespace App\Http\Livewire\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Taxpayer;
use App\Traits\VerificationTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxpayersTable extends DataTableComponent
{
    use CustomAlert, VerificationTrait;
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['first_name', 'middle_name', 'last_name', 'is_first_login', 'failed_verification']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    protected $listeners = [
        'sendCredential', 'amendmentDetailsRequest', 'verifyAccount'
    ];

    public function builder(): Builder
    {
        return Taxpayer::query()->with('country', 'region','street')
            ->orderBy('taxpayers.created_at', 'desc');
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
                    $searchTerms = explode(" ", $searchTerm);
                    foreach ($searchTerms as $term) {
                        $query->orWhereRaw(DB::raw("LOWER(first_name) like '%' || LOWER('$term') || '%'"))
                            ->orWhereRaw(DB::raw("LOWER(middle_name) like '%' || LOWER('$term') || '%'"))
                            ->orWhereRaw(DB::raw("LOWER(last_name) like '%' || LOWER('$term') || '%'"));
                    }
                }),
            Column::make('Mobile No', 'mobile')->searchable(),
            Column::make('Email Address', 'email')->searchable(),
            Column::make('Nationality', 'country_id')
                ->format(fn ($value, $row) => $row->country->nationality ?? '')->searchable(),
            Column::make('Location', 'region.name')->searchable(),
            Column::make('Street', 'street.name')->searchable(),
            Column::make('Action', 'id')->view('taxpayers.actions')
        ];
    }

    public function sendCredential($value)
    {
        if (isset($value['data'])) {
            $data = (object) $value['data'];
        } else {
            abort(400);
        }

        $taxpayer = Taxpayer::find($data->id);
        if(is_null($taxpayer)){
            abort(404);
        }

        try {
            $password = Str::random(8);
            $taxpayer->password = Hash::make($password);
            $taxpayer->save();
        } catch (\Exception $exception) {
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }

        event(new SendSms('taxpayer-registration', $taxpayer->id, ['code' => $password]));
        if ($taxpayer->email) {
            event(new SendMail('taxpayer-registration', $taxpayer->id, ['code' => $password]));
        }
        $this->flash('success', 'Credentials re-send successfully', [], redirect()->back()->getTargetUrl());

    }

    public function resendCredential($id)
    {
        $this->customAlert('warning', 'Are you sure you want to re-send new user credentials ?', [
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

    public function openVerifyAccountModal($id)
    {
        $this->customAlert('warning', 'Are you sure you want to verify user account ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'verifyAccount',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],
        ]);
    }
    public function verifyAccount($value)
    {
        if (isset($value['data'])) {
            $data = (object) $value['data'];
        } else {
            abort(404);
        }

        $taxpayer = Taxpayer::find($data->id);
        if(is_null($taxpayer)){
            abort(404);
        }

        try {
            $taxpayer->failed_verification = false;
            $taxpayer->save();
            $this->sign($taxpayer);
            $this->customAlert('success', 'Account verified successfully', ['timer' => 2000]);
        } catch (\Exception $exception) {
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }

    }

}
