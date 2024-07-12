<?php

namespace App\Services\SequenceGenerator;

use App\Models\Sequence;

class AuditAssessment
{
    private $prefixes = [
        'DTD' => 'EC.405/607/',
        'NTRD' => 'FD.405/607/',
        'LTD' => 'DE.338/405/',
        'unguja' => 'DE.338/405/',
    ];

    /**
     * @throws \Exception
     */
    public function generateSequence($type): string
    {
        if (!array_key_exists($type, $this->prefixes)) {
            throw new \InvalidArgumentException("Invalid tax region type");
        }

        $prefix = $this->prefixes[$type];

        $lastSequence = Sequence::query()
            ->where('name', Sequence::ASSESSMENT_NUMBER)
            ->firstOrFail();

        $newSequence = $this->incrementSequence($lastSequence->next_sequence);

        $lastSequence->next_sequence = $newSequence;
        $updated = $lastSequence->save();

        if (!$updated){
            throw new \Exception("Failed to updated last sequence.");
        }

        return $prefix . $newSequence;
    }

    private function incrementSequence($lastSequence)
    {
        list($part1, $part2) = explode('/', $lastSequence);

        if ($part2 === '100') {
            $part1 = $this->incrementPart1($part1);
            $part2 = '001';
        } else {
            $part2 = str_pad((int)$part2 + 1, 3, '0', STR_PAD_LEFT);
        }

        return $part1 . '/' . $part2;
    }

    private function incrementPart1($part)
    {
        if (strlen($part) === 2 && ctype_digit($part)) {
            // If it's just a two-digit number, add 'A'
            return $part . 'A';
        }

        $numeric = substr($part, 0, 2);
        $alpha = substr($part, 2);

        if (empty($alpha)) {
            // This shouldn't happen, but just in case
            return str_pad((int)$numeric + 1, 2, '0', STR_PAD_LEFT);
        }

        if ($alpha === 'Z') {
            // If we've reached Z, increment the numeric part and start over
            return str_pad((int)$numeric + 1, 2, '0', STR_PAD_LEFT);
        }

        // Otherwise, increment the letter
        return $numeric . chr(ord($alpha) + 1);
    }
}