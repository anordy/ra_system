<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrMotorVehicleRegistration
 *
 * @property int $id
 * @property int $mvr_plate_size_id
 * @property int $mvr_plate_color_id
 * @property string|null $plate_number
 * @property int $mvr_plate_number_status_id
 * @property int $mvr_motor_vehicle_id
 * @property int $mvr_registration_type_id
 *
 * @property MvrColor $mvr_color
 * @property MvrMotorVehicle $mvr_motor_vehicle
 * @property MvrPlateNumberStatus $mvr_plate_number_status
 * @property MvrPlateSize $mvr_plate_size
 * @property MvrRegistrationType $mvr_registration_type
 *
 * @package App\Models
 */
class MvrMotorVehicleRegistration extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'mvr_vehicle_registration';
    public $timestamps = true;

    protected $casts = [
        'id' => 'int',
        'mvr_plate_size_id' => 'int',
        'mvr_plate_color_id' => 'int',
        'mvr_plate_number_status_id' => 'int',
        'mvr_motor_vehicle_id' => 'int',
        'mvr_registration_type_id' => 'int'
    ];

    protected $fillable = [
        'mvr_plate_size_id',
        'mvr_plate_color_id',
        'plate_number',
        'mvr_plate_number_status_id',
        'mvr_motor_vehicle_id',
        'registration_date',
        'mvr_registration_type_id'
    ];

    public function motor_vehicle()
    {
        return $this->belongsTo(MvrMotorVehicle::class, 'mvr_motor_vehicle_id');
    }

    public function plate_number_status()
    {
        return $this->belongsTo(MvrPlateNumberStatus::class, 'mvr_plate_number_status_id');
    }

    public function plate_size()
    {
        return $this->belongsTo(MvrPlateSize::class, 'mvr_plate_size_id');
    }

    public function registration_type()
    {
        return $this->belongsTo(MvrRegistrationType::class, 'mvr_registration_type_id');
    }

    public function current_personalized_registration()
    {
        return $this->hasOne(MvrPersonalizedPlateNumberRegistration::class, 'mvr_motor_vehicle_registration_id')->latest();
    }

    public function current_active_personalized_registration()
    {
        return $this->hasOne(MvrPersonalizedPlateNumberRegistration::class, 'mvr_motor_vehicle_registration_id')->where(['status' => 'ACTIVE'])->latest();
    }

    public function plate_number_collection()
    {
        return $this->hasOne(MvrPlateNumberCollection::class, 'mvr_registration_id')->latest();
    }

    public function get_latest_bill()
    {
        $bill_item = $this->morphOne(ZmBillItem::class, 'billable')->latest()->first();
        return $bill_item->bill ?? null;
    }


    public static function getGoldenPlateNumbers()
    {
        $regs = MvrRegistration::query()
            ->whereHas('plate_type', fn($query) => $query->where('name', MvrPlateNumberType::SPECIAL_NAME))
            ->whereRaw('substring(plate_number,2,3)%111=0')
            ->orderBy(DB::raw('concat(substring(plate_number,5,2),substring(plate_number,2,3))'), 'desc')
            ->lockForUpdate()
            ->limit(20) //20 last Golden plate numbers - acceptable range will be 40
            ->get()->pluck('plate_number');

        if (empty($regs[0])) {
            $last_special = MvrRegistration::query()->where([
                'mvr_plate_number_type_id' => MvrRegistrationType::TYPE_PRIVATE_GOLDEN
            ])
                ->first()
                ->initial_plate_number;
            if (!$last_special) {
                $last_special = 'Z111AA';
            }
        } else {
            $last_special = $regs[count($regs) - 1];
        }

        $plate_numbers = [];
        for ($i = 0; $i < 40; ++$i) {
            $number = preg_replace('/Z(\d{3})([A-Z]{2})/', '$1', $last_special);
            $alpha = preg_replace('/Z(\d{3})([A-Z]{2})/', '$2', $last_special);
            if ($number == 999) {
                $number = 111;
                $alpha = preg_match('/[A-Z]Z/', $alpha) ? chr(ord(substr($alpha, 0, 1)) + 1) . 'A' : substr($alpha, 0, 1) . chr(ord(substr($alpha, 1, 1)) + 1);
            } else {
                $number += 111;
            }
            $number = str_pad($number, 3, '0', STR_PAD_LEFT);
            $last_special = 'Z' . $number . $alpha;
            //1st 20 have to be checked
            if (MvrMotorVehicleRegistration::query()->where(['plate_number' => $last_special])->exists()) {
                continue;
            }
            $plate_numbers[] = $last_special;

            if (count($plate_numbers) >= 20) {
                break;
            }
        }


        return $plate_numbers;
    }

}
