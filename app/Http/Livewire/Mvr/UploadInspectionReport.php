<?php

namespace App\Http\Livewire\Mvr;


use App\Models\BusinessLocation;
use App\Models\Country;
use App\Models\MvrBodyType;
use App\Models\MvrClass;
use App\Models\MvrColor;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleOwner;
use App\Models\MvrOwnershipStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrTransmissionType;
use App\Models\MvrVehicleStatus;
use App\Models\Taxpayer;
use App\Services\TRA\ServiceRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadInspectionReport extends Component
{

    use CustomAlert,WithFileUploads;


    public string $chassis;
    /**
     * @var  TemporaryUploadedFile
     */
    public $inspection_report;
    public $mileage;
    public $inspection_date;
    private ?string $inspection_report_path = null;


    public function mount($chassis)
    {
        $this->chassis = $chassis;
    }

    protected function rules()
    {
        return [
            'inspection_report'=>'required|mimes:pdf|max:1024|max_file_name_length:100',
            'inspection_date'=>'required|date|before_or_equal:today',
            'mileage'=>'required|numeric',
        ];
    }


    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $data = $this->prepareMotorVehicleData();


            $id = MvrMotorVehicle::query()->create($data['motor_vehicle'])->id;
            MvrMotorVehicleOwner::query()->create([
                'mvr_motor_vehicle_id'=>$id,
                'taxpayer_id'=> $data['owner'],
                'mvr_ownership_status_id'=>$this->getForeignKey(MvrOwnershipStatus::STATUS_CURRENT_OWNER,MvrOwnershipStatus::class,true),
            ]);
            DB::commit();
            $this->flash('success', 'Inspection Report Uploaded');
            return redirect()->route('mvr.show', encrypt($id));
        } catch (Exception $e) {
            if (Storage::disk('local')->exists($this->inspection_report_path)) Storage::disk('local')->delete($this->inspection_report_path);
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.inspection-report-upload-modal');
    }

    /**
     * @throws Exception
     */
    private function prepareMotorVehicleData(): array
    {
        $result = ServiceRequest::searchMotorVehicleByChassis($this->chassis);
        if ($result['status']!='success'){
            throw new \Exception("Could not fetch motor vehicle details");
        }


        $cert_number = 'ZBSIQC-0001-'.date('y');

        $last_mv = MvrMotorVehicle::query()->orderBy('certificate_number','desc')->first();
        if (!empty($last_mv) && !empty($last_mv->certificate_number)){
            $yy = explode('-',$last_mv->certificate_number)[2] ?? null;
            $n = explode('-',$last_mv->certificate_number)[1] ?? null;
            if ($yy == date('y')){
                $cert_number = 'ZBSIQC-'.str_pad($n+1,4,'0',STR_PAD_LEFT).'-'.date('y');
            }
        }

        $motor_vehicle = $result['data'];
        $path = "MVR-Inspection-Report-{$this->chassis}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->inspection_report->extension();
        $inspection_report_path = $this->inspection_report->store( $path,'local');

        $mv_data = [
            'registration_number'=>'Z-'.str_pad(MvrMotorVehicle::query()->count().rand(10,99),9,'0',STR_PAD_LEFT),
            'mileage'=>$this->mileage,
            'certificate_number'=>$cert_number,
            'inspection_date'=>$this->inspection_date,
            'chassis_number'=>$motor_vehicle['chassis_number'],
            'inspection_report_path'=>$inspection_report_path,
            'registration_date' => Carbon::now()->format('Y-m-d'),
            'mvr_registration_status_id'=>$this->getForeignKey('INSPECTION',MvrRegistrationStatus::class,true)
        ];
        return ['motor_vehicle'=>$mv_data, 'owner'=>$motor_vehicle['importer_tin']];
    }


    private function getForeignKey(string $value, string $class,$auto_create=false)
    {
        $item = $class::query()->where(['name'=>$value])->first();
        if (empty($item) && $auto_create){
            return $class::query()->create(['name'=>$value])->id;
        }else if (empty($item)){
            $class = preg_replace('/(.+\\\\)(\S+)/','$2',$class);
            throw new \Exception("Field value {$value} returned from API does not exist on {$class} Table");
        }
        return $item->id;
    }
}
