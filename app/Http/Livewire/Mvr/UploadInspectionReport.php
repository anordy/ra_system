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
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadInspectionReport extends Component
{

    use LivewireAlert,WithFileUploads;


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
            'inspection_report'=>'required|mimes:pdf',
            'inspection_date'=>'required|date|before_or_equal:today',
            'mileage'=>'required|Numeric',
        ];
    }


    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $data = $this->prepareMotorVehicleData();
            $taxpayer = Taxpayer::query()->where(['reference_no'=>$data['owner']['z_number']])->first();
            if (empty($taxpayer)){
                Storage::disk('local-admin')->delete($this->inspection_report_path);
                DB::rollBack();
                $this->alert('error', "Could not find owner/taxpayer with Z number {$data['owner']['z_number']}");
                return;
            }

            $taxpayer_agent = Taxpayer::query()->where(['reference_no'=>$data['agent']['z_number']])->first();
            if (empty($taxpayer_agent->transport_agent)){
                $this->alert('error', "Could not find agent/taxpayer with Z number {$data['agent']['z_number']}");
                Storage::disk('local-admin')->delete($this->inspection_report_path);
                DB::rollBack();
                return;
            }
            $data['motor_vehicle']['mvr_agent_id'] = $taxpayer_agent->transport_agent->id;
            $id = MvrMotorVehicle::query()->create($data['motor_vehicle'])->id;
            MvrMotorVehicleOwner::query()->create([
                'mvr_motor_vehicle_id'=>$id,
                'taxpayer_id'=>$taxpayer->id,
                'mvr_ownership_status_id'=>$this->getForeignKey(MvrOwnershipStatus::STATUS_CURRENT_OWNER,MvrOwnershipStatus::class,true),
            ]);
            DB::commit();
            $this->flash('success', 'Inspection Report Uploaded');
            return redirect()->route('mvr.show', encrypt($id));
        } catch (Exception $e) {
            Log::error($e);
            if (Storage::disk('local-admin')->exists($this->inspection_report_path)) Storage::disk('local-admin')->delete($this->inspection_report_path);
            DB::rollBack();
            $this->alert('error', 'Something went wrong: '.$e->getMessage());
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
        $inspection_report_path = $this->inspection_report->store( $path,'local-admin');
        $mv_data = [
            'registration_number'=>'Z-'.str_pad(MvrMotorVehicle::query()->count().rand(10,99),9,'0',STR_PAD_LEFT),
            'number_of_axle'=>$motor_vehicle['number_of_axle'],
            'mileage'=>$this->mileage,
            'certificate_number'=>$cert_number,
            'inspection_date'=>$this->inspection_date,
            'chassis_number'=>$motor_vehicle['chassis_number'],
            'year_of_manufacture'=>$motor_vehicle['year'],
            'engine_number'=>$motor_vehicle['engine_number'],
            'gross_weight'=>$motor_vehicle['gross_weight'],
            'engine_capacity'=>$motor_vehicle['engine_capacity'],
            'seating_capacity'=>$motor_vehicle['seating_capacity'],
            'mvr_vehicle_status_id'=>$this->getForeignKey('Imported',MvrVehicleStatus::class,true),
            'imported_from_country_id'=>$this->getForeignKey($motor_vehicle['imported_from'],Country::class),
            'mvr_color_id'=>$this->getForeignKey($motor_vehicle['color'],MvrColor::class),
            'mvr_class_id'=>MvrClass::query()->where(['code'=>$motor_vehicle['class']])->first()->id,
            'mvr_model_id'=>$this->getForeignKey($motor_vehicle['model'],MvrModel::class),
            'mvr_fuel_type_id'=>$this->getForeignKey($motor_vehicle['fuel_type'],MvrFuelType::class),
            'mvr_transmission_id'=>$this->getForeignKey($motor_vehicle['transmission_type'],MvrTransmissionType::class),
            'mvr_body_type_id'=>$this->getForeignKey($motor_vehicle['body_type'],MvrBodyType::class),
            'inspection_report_path'=>$inspection_report_path,
            'mvr_registration_status_id'=>$this->getForeignKey('INSPECTION',MvrRegistrationStatus::class,true)
        ];
        return ['motor_vehicle'=>$mv_data,'owner'=>$motor_vehicle['owner'],'agent'=>$motor_vehicle['agent']];
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
