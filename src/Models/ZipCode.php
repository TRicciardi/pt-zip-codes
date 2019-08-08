<?php

namespace tricciardi\ptzipcodes\Models;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
  protected $table = 'zipcodes';

  protected $fillable = [
    'zip_code',
    'district_id',
    'county_id',
    'localidade',
    'cp4',
    'cp3',
    'ART_COD',
    'ART_TIPO',
    'PRI_PREP',
    'ART_TITULO',
    'SEG_PREP',
    'ART_DESIG',
    'ART_LOCAL',
    'TROCO',
    'PORTA',
    'CLIENTE',
    'CPALF',
  ];
  protected $filterable = [
      'zipcode',
  ];
}
