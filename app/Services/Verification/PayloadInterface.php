<?php

namespace App\Services\Verification;

interface PayloadInterface {
    public static function getPayloadColumns(): array;
    public static function getTableName(): string;

    // Maybe get id + get ci_payload column
}