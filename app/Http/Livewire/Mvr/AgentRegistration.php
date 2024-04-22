<?php

namespace App\Http\Livewire\Mvr;

use App\Enum\GeneralConstant;
use App\Models\MvrAgent;
use App\Models\Taxpayer;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AgentRegistration extends Component
{

    use CustomAlert;


    public $zin;
    public $taxpayer;
    public $lookup_fired = false;
    public $companyName;


    public function submit()
    {
        $this->validate(['companyName' => 'required|string|strip_tag']);
        if (empty($this->taxpayer)) {
            $this->customAlert(GeneralConstant::ERROR, 'Please provide valid Z-Number and confirm details by doing lookup');
            return;
        }
        if (!empty($this->taxpayer->transport_agent)) {
            $this->customAlert(GeneralConstant::ERROR, 'This taxpayer has already been registered as Transport Agent');
            return;
        }
        try {
            $last_agent = MvrAgent::query()->lockForUpdate()->latest()->first();

            DB::beginTransaction();
            if (!empty($last_agent)) {
                $last_number = preg_replace('/ZTA\d{2}(\d{6})/', '$1', $last_agent->agent_number);
                $yy = preg_replace('/ZTA(\d{2})(\d{6})/', '$1', $last_agent->agent_number);
                if ($yy != date('y')) {
                    $last_number = 1;
                }
            } else {
                $last_number = 1;
            }
            $agent_number = 'ZTA' . date('y') . sprintf('%06s', $last_number + 1);
            MvrAgent::query()->create(
                [
                    'taxpayer_id' => $this->taxpayer->id,
                    'registration_date' => Carbon::now(),
                    'agent_number' => $agent_number,
                    'status' => 'ACTIVE',
                    'company_name' => $this->companyName ?? null
                ]
            );
            DB::commit();
            return redirect()->to(route('mvr.agent'));
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('MVR-AGENT-REGISTRATION', [$exception]);
            $this->customAlert(GeneralConstant::ERROR, 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.agent-registration');
    }

    public function lookup()
    {
        $this->validate([
            'zin' => 'required|string|strip_tag',
        ]);
        $this->lookup_fired = true;
        $this->companyName = null;
        $this->taxpayer = Taxpayer::query()->where(['reference_no' => $this->zin])
            ->first();
    }
}
