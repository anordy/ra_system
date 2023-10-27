<?php

namespace App\Http\Livewire\PropertyTax\Condominium;

use App\Enum\CondominiumStatus;
use App\Models\District;
use App\Models\PropertyTax\Condominium;
use App\Models\PropertyTax\CondominiumStorey;
use App\Models\PropertyTax\CondominiumUnit;
use App\Models\Region;
use App\Models\Street;
use App\Models\Ward;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CondominiumEdit extends Component
{
    use CustomAlert;

    public $condominium;
    public $name, $region_id, $district_id, $ward_id, $street_id, $status;
    public $streets = [];
    public $wards = [];
    public $districts = [];
    public $regions = [];
    public $storeys = [];

    public function mount($id)
    {
        $this->condominium = Condominium::findOrFail(decrypt($id));

        $this->region_id = $this->condominium->region_id;
        $this->district_id = $this->condominium->district_id;
        $this->ward_id = $this->condominium->ward_id;
        $this->street_id = $this->condominium->street_id;
        $this->name = $this->condominium->name;
        $this->status = $this->condominium->status;

        $regions = Region::select('id', 'name')
            ->where('is_approved', 1)
            ->get();

        $this->regions = json_decode($regions, true);

        if ($this->region_id){
            $this->districts = District::where('region_id', $this->region_id)
                ->select('id', 'name', 'region_id')
                ->approved()
                ->get();
        }

        if ($this->district_id){
            $this->wards = Ward::where('district_id', $this->district_id)->select('id', 'name', 'district_id')
                ->approved()
                ->get();
        }

        if ($this->ward_id){
            $this->streets = Street::where('ward_id', $this->ward_id)->select('id', 'name', 'ward_id')->approved()->get();
        } elseif ($this->street_id){
            $this->streets = Street::where('ward_id', $this->street->ward_id)
                ->select('id', 'name', 'ward_id')
                ->approved()
                ->get();
        }

        // Patch Storeys with Units
        $storeys = $this->condominium->storeys;
        foreach ($storeys as $i => $storey) {
            $this->storeys[$i] = [
                'name' => $storey->name
            ];

            foreach ($storey->units as $j => $unit) {
                $this->storeys[$i][$j] = [
                    'name' => $unit->name,
                ];
            }
        }
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

            $this->condominium->update([
                'name' => $this->name,
                'region_id' => $this->region_id,
                'district_id' => $this->district_id,
                'ward_id' => $this->ward_id,
                'street_id' => $this->street_id,
                'status' => $this->status,
            ]);

            $this->condominium->storeys()->delete();
            $this->condominium->units()->delete();

            foreach ($this->storeys as $i => $storey) {
                // Create Storey
                $condominiumStorey = CondominiumStorey::create(
                    [
                        'number' => $i+1,
                        'condominium_id' => $this->condominium->id
                    ]
                );

                foreach ($storey as $unit) {
                    // Create Storey Units
                    CondominiumUnit::create(
                        [
                            'condominium_storey_id' => $condominiumStorey->id,
                            'condominium_id' => $this->condominium->id,
                            'name' => $unit['name'],
                            'status' => CondominiumStatus::UNREGISTERED
                        ]
                    );
                }
            }

            DB::commit();
            $this->customAlert('success', 'Property Updated Successful');
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
            'name' => ['required', 'unique:condominium,name,'.$this->condominium->id, 'strip_tag'],
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
