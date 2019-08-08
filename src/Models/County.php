<?php

namespace tricciardi\ptzipcodes\Models;
use Illuminate\Database\Eloquent\Model;
// 943
class County extends Model
{

  protected $fillable = [
      'name',
      'code',
      'district_id',
  ];
  protected $filterable = [
      'name',
      'code',
      'district_id',
  ];

}
