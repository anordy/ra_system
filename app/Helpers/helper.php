<?php

use App\Models\Role;
use App\Models\User;

function getOperators($owner, $operator_type, $operators)
{
    $data = '';

    if ($owner == 'taxpayer') return 'taxpayer';
    if ($operator_type == 'role') {
        $data = Role::whereIn('id', $operators)->get()->pluck('name')->implode(', ');
    } elseif ($operator_type == 'user') {
        $data = User::whereIn('id', $operators)->get()->pluck('fname', 'lname')->implode(', ');
    }


    return $data;
}
