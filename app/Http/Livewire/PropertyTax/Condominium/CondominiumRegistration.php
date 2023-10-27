<?php

namespace App\Http\Livewire\PropertyTax\Condominium;

use App\Enum\CondominiumStatus;
use App\Models\District;
use App\Models\PropertyTax\Condominium;
use App\Models\PropertyTax\CondominiumStorey;
use App\Models\PropertyTax\CondominiumUnit;
use App\Models\Street;
use App\Models\Ward;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CondominiumRegistration extends Component
{
    use CustomAlert;

    public $name, $region_id, $district_id, $ward_id, $street_id, $status;
    public $streets = [];
    public $wards = [];
    public $districts = [];
    public $regions = [];
    public $storeys = [
        [
            [
                'name' => '',
            ]
        ]
    ];

    public function mount()
    {
        $regions = DB::table('regions')
            ->select('id', 'name')
            ->where('is_approved', 1)
            ->get();
        $this->regions = json_decode($regions, true);
    }

    public function addStorey()
    {
        $this->storeys[] = [
                [
                    'name' => '',
                ]
        ];
    }

    public function addUnit($i)
    {
        $this->storeys[$i][] = [
            'name' => '',
        ];
    }

    public function removeStorey($i)
    {
        unset($this->storeys[$i]);
    }

    public function removeUnit($i, $j)
    {
        unset($this->storeys[$i][$j]);
    }

    public function submit() {
        $this->validate();
        try {
            DB::beginTransaction();

            $condominium = Condominium::create([
                'name' => $this->name,
                'region_id' => $this->region_id,
                'district_id' => $this->district_id,
                'ward_id' => $this->ward_id,
                'street_id' => $this->street_id,
                'status' => $this->status,
                'staff_id' => Auth::user()->id
            ]);


            foreach ($this->storeys as $i => $storey) {
                $condominiumStorey = CondominiumStorey::create([
                    'number' => $i+1,
                    'condominium_id' => $condominium->id
                ]);

                foreach ($storey as $unit) {
                    CondominiumUnit::create([
                        'condominium_storey_id' => $condominiumStorey->id,
                        'condominium_id' => $condominium->id,
                        'name' => $unit['name'],
                        'status' => CondominiumStatus::UNREGISTERED
                    ]);
                }
            }

            DB::commit();
            $this->customAlert('success', 'Property Registered Successful');
            return redirect()->route('property-tax.condominium.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->customAlert('error', 'Something went wrong');
            throw new \Exception($exception);
        }

    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'unique:condominium,name', 'strip_tag'],
            'region_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
            'street_id' => 'required',
            'status' => 'required',
            'storeys.*.*.name' => 'required|distinct|strip_tag',
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region_id') {
            $this->reset('district_id', 'ward_id', 'wards', 'street_id', 'streets');
            $districts = District::where('region_id', $this->region_id)
                ->where('is_approved', 1)
                ->select('id', 'name')
                ->get();
            $this->districts = json_decode($districts, true);
        }

        if ($propertyName === 'district_id') {
            $this->reset('ward_id', 'streets', 'street_id');
            $wards = Ward::where('district_id', $this->district_id)
                ->where('is_approved', 1)
                ->select('id', 'name')
                ->get();
            $this->wards = json_decode($wards, true);
        }

        if ($propertyName === 'ward_id') {
            $this->reset('street_id');
            $streets = Street::where('ward_id', $this->ward_id)
                ->where('is_approved', 1)
                ->select('id', 'name')
                ->get();
            $this->streets = json_decode($streets, true);
        }
    }

    public function render()
    {
        return view('livewire.property-tax.condominium.condominium-registration');
    }
}
