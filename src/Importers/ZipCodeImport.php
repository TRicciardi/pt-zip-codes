<?php

namespace tricciardi\ptzipcodes\Imports;

use tricciardi\ptzipcodes\Models\County;
use tricciardi\ptzipcodes\Models\ZipCode;
use tricciardi\ptzipcodes\Models\District;
use tricciardi\ptzipcodes\Imports\CsvReader;

class ZipCodeImport extends CsvReader
{
  public $districts_table;
  public $counties_table;

  public static function import($file, $filename) {
    $importer = new ZipCodeImport($file, $filename, true, ';');
    $time = microtime(true);
    ZipCode::truncate();
    $importer->districts_table = District::pluck('id','code')->all();
    $importer->counties_table = County::pluck('id','code')->all();

    $importer->process(1000);
    echo "ZipCode imported in: ".(microtime(true) - $time) ."seconds\r\n";
  }

  public function getRow($row) {
    $district_code = trim($row[0]);
    $county_code = trim($row[0]).'|'.trim($row[1]);
    $locality_code = trim($row[2]);
    $localidade = (string) trim($row[3]);
    $ART_COD = (string) trim($row[4]);
    $ART_TIPO = (string) trim($row[5]);
    $PRI_PREP = (string) trim($row[6]);
    $ART_TITULO = (string) trim($row[7]);
    $SEG_PREP = (string) trim($row[8]);
    $ART_DESIG = (string) trim($row[9]);
    $ART_LOCAL = (string) trim($row[10]);
    $TROCO = (string) trim($row[11]);
    $PORTA = (string) trim($row[12]);
    $CLIENTE = (string) trim($row[13]);
    $cp4 = (string) trim($row[14]);
    $cp3 = (string) trim($row[15]);
    $CPALF = (string) trim($row[16]);
    $zip = $cp4.'-'.$cp3;

    $district_id = (isset($this->districts_table[$district_code]))?$this->districts_table[$district_code]:null;
    $county_id = (isset($this->counties_table[$county_code]))?$this->counties_table[$county_code]:null;


    $item = [
      'zip_code'=>$zip,
      'district_id'=>$district_id,
      'county_id'=>$county_id,
      'localidade'=>$localidade,
      'cp4'=>$cp4,
      'cp3'=>$cp3,
      'ART_COD'=>$ART_COD,
      'ART_TIPO'=>$ART_TIPO,
      'PRI_PREP'=>$PRI_PREP,
      'ART_TITULO'=>$ART_TITULO,
      'SEG_PREP'=>$SEG_PREP,
      'ART_DESIG'=>$ART_DESIG,
      'ART_LOCAL'=>$ART_LOCAL,
      'TROCO'=>$TROCO,
      'PORTA'=>$PORTA,
      'CLIENTE'=>$CLIENTE,
      'CPALF'=>$CPALF,
    ];

    return $item;
  }

  public function insertBatch($data) {
    ZipCode::insert($data);
  }

}
