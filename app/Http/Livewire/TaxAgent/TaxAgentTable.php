<?php

namespace App\Http\Livewire\TaxAgent;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\TaPaymentConfiguration;
use App\Models\Taxpayer;
use App\Models\User;
use App\Models\ZmBill;
use App\Notifications\DatabaseNotification;
use App\Services\ZanMalipo\ZmCore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class TaxAgentTable extends DataTableComponent
{
	use LivewireAlert;


//	protected $model = TaxAgent::class;

	public function builder(): Builder
	{
		return TaxAgent::query()->where('status', '=', 'pending');
	}

	public function configure(): void
    {
        $this->setPrimaryKey('id');

	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);

//
    }

    public function columns(): array
    {
        return [
            Column::make("TIN No", "tin_no")
                ->sortable(),
          Column::make("Town", "town")
	        ->sortable(),
          Column::make("Region", "region")
	        ->sortable(),
	        Column::make("Created At", "created_at")
	          ->sortable(),
	        Column::make('Status', 'status')
          ->view('taxagents.includes.status'),
          Column::make('Action', 'id')
	        ->view('taxagents.includes.actionReq')

        ];
    }



}
