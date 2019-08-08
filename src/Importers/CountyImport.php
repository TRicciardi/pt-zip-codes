<?php

namespace tricciardi\ptzipcodes\Imports;

use tricciardi\ptzipcodes\Models\County;
use tricciardi\ptzipcodes\Models\District;

class CountyImport extends CsvReader
{

  public static function import($file, $filename) {
    $importer = new CountyImport($file, $filename, false, ';');

    $tables = new \stdClass;
    $tables->districts_table = District::pluck('id','code')->all();
    $importer->tables = $tables;

    $time = microtime(true);
    $importer->process(1000);
  }

  public function getRow($row) {
    $district_code = trim($row[0]);
    $code = trim($row[0]).'|'.trim($row[1]);
    $name = (string) trim($row[2]);

    $district_id = (isset($this->tables->districts_table[$district_code]))?$this->tables->districts_table[$district_code]:null;

    $item = [
      'code'=>$code,
      'name'=>$name,
      'district_id'=>$district_id,
    ];
    return $item;
  }

  public function insertOne($item) {
    $exists = County::where('code',$item['code'])->first();
    if($exists) {
      County::where('id',$exists->id)->update($item);
    } else {
      County::create($item);
    }
  }

}
