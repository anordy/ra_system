<?php

namespace App\Traits;

use App\Models\MvrPlateNumberStatus;
use App\Models\MvrPlateNumberType;
use App\Models\MvrRegistrationType;
use App\Models\Sequence;
use Carbon\Carbon;

trait MvrRegistrationTrait
{
    public function updateNextPlateNumber($regType, $class, $mvr): mixed
    {
        $plateNumberType = $mvr->plate_type->code ?? null;

        if ($plateNumberType === MvrPlateNumberType::PERSONALIZED) {
            $mvr->personalized_name = $mvr->plate_number;
            if (!$mvr->save()) throw new \Exception('Failed to save mvr information');
        }

        if ($regType->name == MvrRegistrationType::TYPE_CORPORATE || $regType == MvrRegistrationType::TYPE_GOVERNMENT_SLS) {
            $currentNumber = Sequence::where('name', Sequence::SLS_PLATE_NUMBER)->firstOrFail()->next_sequence;
            $nextNumber = $currentNumber + 1;
            Sequence::where('name', Sequence::SLS_PLATE_NUMBER)->update(['next_sequence' => $nextNumber]);
            // check if plate number exits? except the current one
            $plate_number = 'SLS' . $currentNumber . $class->category;
        } elseif ($regType->name == MvrRegistrationType::TYPE_GOVERNMENT_SMZ) {
            $currentNumber = Sequence::where('name', Sequence::SMZ_PLATE_NUMBER)->firstOrFail()->next_sequence;
            $nextNumber = (int) $currentNumber + 1;
            Sequence::where('name', Sequence::SMZ_PLATE_NUMBER)->update(['next_sequence' => $nextNumber]);
            $plate_number = 'SMZ' . $currentNumber . $class->category;
        } else {
            $currentNumber = Sequence::where('name', Sequence::PLATE_NUMBER)->firstOrFail();
            $currentNumber = (int) $currentNumber->next_sequence;
            $currentAlphabet = Sequence::where('name', Sequence::PLATE_ALPHABET)->firstOrFail()->next_sequence;

            if ($currentNumber == 998 || $currentNumber == 999) {
                $newNumber = 1;
                $newAlpha = $this->incrementAlpha($currentAlphabet);
            } else {
                $newNumber = $currentNumber + 1;
                $newAlpha = $currentAlphabet;
            }

            //check special number
            if ($newNumber % 111 == 0) $newNumber++;

            $currentNumber = str_pad($currentNumber, 3, '0', STR_PAD_LEFT);
            $plate_number = 'Z' . $currentNumber . $currentAlphabet;

            // check if plate number exits? except the current one
            Sequence::where('name', Sequence::PLATE_NUMBER)->update(['next_sequence' => $newNumber]);
            Sequence::where('name', Sequence::PLATE_ALPHABET)->update(['next_sequence' => $newAlpha]);
        }
        return $mvr->update([
            'plate_number' => $plate_number,
            'registration_number' => 'Z-' . str_pad($mvr->id, 6, "0", STR_PAD_LEFT),
            'mvr_plate_number_status' => MvrPlateNumberStatus::STATUS_GENERATED,
            'registered_at' => Carbon::now()
        ]);
    }

    function incrementAlpha($alpha): string
    {
        $length = strlen($alpha);
        $allZ = true;

        // Check if all characters are 'Z'
        for ($i = 0; $i < $length; $i++) {
            if ($alpha[$i] !== 'Z') {
                $allZ = false;
                break;
            }
        }

        // If all characters are 'Z', return 'A' repeated length+1 times
        if ($allZ) {
            return str_repeat('A', $length + 1);
        }

        // Convert string to array of characters
        $chars = str_split($alpha);

        // Start from the end and increment characters
        for ($i = $length - 1; $i >= 0; $i--) {
            if ($chars[$i] === 'Z') {
                $chars[$i] = 'A';
            } else {
                $chars[$i] = chr(ord($chars[$i]) + 1);
                break;
            }
        }

        // Convert array back to string
        return implode('', $chars);
    }
}

