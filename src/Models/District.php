<?php

namespace tricciardi\ptzipcodes\Models;
use Illuminate\Database\Eloquent\Model;
// 943
class District extends Model
{

  protected $fillable = [
      'name', 'code',
  ];
  protected $filterable = [
      'name', 'code',
  ];

}
