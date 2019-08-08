<?php

namespace tricciardi\ptzipcodes\Imports;

use League\Csv\Reader;
use League\Csv\CharsetConverter;
use League\Csv\Statement;
use tricciardi\ptzipcodes\Models\ImportResult;

class CsvReader {

  protected $reader;
  protected $encoding;
  protected $importResult;
  protected $bulkInsert = false;
  public $tables;

  public function __construct($file, $filename, $bulkInsert=false, $delimiter='|', $encoding='ISO-8859-1') {
    $this->encoding = $encoding;
    $this->reader = Reader::createFromPath(storage_path().'/app/'.$file, 'r');
    $this->reader->setDelimiter($delimiter);
    $this->bulkInsert = $bulkInsert;
  }

  public function count() {
    return count($this->reader);
  }

  public function getBatch($offset=0, $limit=null) {
    $stmt = (new Statement())
            ->offset($offset)
            ->limit($limit);
    $records = $stmt->process($this->reader);
    $encoder = (new CharsetConverter())->inputEncoding($this->encoding);
    $records = $encoder->convert($records);
    return $records;
  }

  public function importBatch() {}

  public function logError($error) {}
  public function logImportedRows($num_rows) {}

  public function process($limit=1000) {
    $count = $this->count();
    echo 'importing '.$count;
    $i=0;
    $imported = 0;
    while($i<$count) {
      $data = [];
      $records= $this->getBatch($i, $limit);
      $total = 0;
      foreach($records as $k=>$row) {
        $total++;
        try {
          $item = $this->getRow($row);
          $data[] = $item;
          if(!$this->bulkInsert) {
            $this->insertOne($item);
            $imported++;
            $this->logImportedRows($imported);
          }
        } catch(\Exception $e) {
          $error = "Erro na linha \r\n";
          $error .= json_encode($e->getMessage());
          $error .= "\r\n".json_encode($row);
          $this->logError($error);
        }
      }
      if($this->bulkInsert) {
        try {
          $this->insertBatch($data);
          $imported += count($data);
          $this->logImportedRows($imported);
        } catch(\Exception $e) {
          $error = "Erro ao inserir na BD\r\n";
          $error .= json_encode($e->getMessage());
          $this->logError($error);
        }
      }
      $i += $total;
    }
    $this->logImportedRows($imported);
  }

  public function insertBatch($data) {}
  public function insertOne($data) {}
  public function getRow($row) {}

}
