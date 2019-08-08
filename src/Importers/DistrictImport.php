<?php

namespace tricciardi\ptzipcodes\Imports;

use tricciardi\ptzipcodes\Models\District;

class DistrictImport extends CsvReader
{

  public static function import($file, $filename) {
    $importer = new DistrictImport($file, $filename, false, ';');
    $time = microtime(true);
    $importer->process(1000);
  }

  public function getRow($row) {
    $code = trim($row[0]);
    $name = (string) trim($row[1]);

    $item = [
      'code'=>$code,
      'name'=>$name
    ];
    return $item;
  }

  public function insertOne($item) {
    $exists = District::where('code',$item['code'])->first();
    if($exists) {
      District::where('id',$exists->id)->update($item);
    } else {
      District::create($item);
    }
  }

}
