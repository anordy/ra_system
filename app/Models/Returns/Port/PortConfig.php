<?php

namespace App\Models\Returns\Port;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortConfig extends Model
{
  use HasFactory;

  const AIR_PORT_HEADER_CODES = [
    'NFAT',
    'NLAT',
    'NFSF',
    'NLSF',
    'IT',
  ];

  const SEA_PORT_HEADER_CODES = [
    'NFSP',
    'NLTM',
    'ITTM',
    'NLZNZ',
    'ITZNZ',
    'NSUS',
    'NSTZ',
  ];

  const TLATZS = "TLATZS";

  protected $guarded = [];
}
