<?php

namespace App\Http\Livewire\Payments;

use App\Enum\StatementStatus;
use App\Models\BankAccount;
use App\Models\PBZStatement;
use App\Services\Api\ApiAuthenticationService;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Validators\ValidationException;

class PBZStatementRequestModal extends Component
{
    use CustomAlert, WithFileUploads;

    public $statementDate, $path, $currency;
    public $bankAccount, $bankAccounts;

    public function mount(){
        $this->bankAccounts = BankAccount::query()
            ->select('id', 'account_name', 'account_number')
            ->get();

        $this->statementDate = Carbon::yesterday()->toDateString();
    }

    protected function rules(){
        return [
            'statementDate' => ['required', 'date'],
            'bankAccount' => 'required|numeric|exists:bank_accounts,id',
        ];
    }

    public function submit()
    {
//        if (!Gate::allows('bank-recon-import')) {
//            abort(403);
//        }

        $this->validate();

        try {
            $bankAccount = BankAccount::findOrFail($this->bankAccount);

            // check with date, and account
            $statement = PBZStatement::query()
                ->where('account_no', $bankAccount->account_number)
                ->where('stmdt', $this->statementDate)
                // check status ??
                ->exists();

            if ($statement){
                $this->customAlert('error', 'Bank statement for this date and account already exists.');
                return;
            }

            DB::beginTransaction();

            // Create statement
            $statement = PBZStatement::create([
                'stmdt' => $this->statementDate,
                'account_no' => $bankAccount->account_number,
                'status' => StatementStatus::PENDING
            ]);

            DB::commit();

            // Send to API
            $statement = $this->sendRequest($statement);

            if ($statement){
                session()->flash('success', 'Statement request has been submitted successful.');
                $this->redirect(route('payments.pbz.statements'));
            } else {
                session()->flash('error', 'Statement failed to submit, please contact your system administrator for support.');
                $this->redirect(route('payments.pbz.statements'));
            }

        } catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong, please contact support for assistance.');
        }
    }

    public function sendRequest($statement){
        $zanmalipo_internal = config('modulesconfig.api_url') . '/pbz/request-statement';
        $access_token = (new ApiAuthenticationService)->getAccessToken();

        if ($access_token == null) {
            $statement->update([
                'status' => StatementStatus::FAILED_SUBMISSION,
                'error' => 'Failed to obtain internal api access token'
            ]);
            return null;
        } else {
            $authorization = "Authorization: Bearer ". $access_token;
            $payload = ['statementId' => $statement->id];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $zanmalipo_internal,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_CONNECTTIMEOUT => 30,

                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    $authorization
                ),
            ));

            $response = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                Log::error(curl_error($curl));
                curl_close($curl);
                throw new \Exception($response);
            }

            curl_close($curl);
            $res = json_decode($response, true);

            if ($res['data']['status_code'] === 'RGS001') {
                $statement->update([
                    'status' => StatementStatus::SUBMITTED,
                ]);
                return $statement;
            } else {
                $error = $res['data']['error'] ?? 'error description not received';
                $statement->update([
                    'status' => StatementStatus::FAILED_SUBMISSION,
                    'error' => $error
                ]);
                return null;
            }
        }
    }


    public function render()
    {
        return view('livewire.payments.pbz-statement-request-modal');
    }
}
