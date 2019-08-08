<?php

namespace tricciardi\ptzipcodes\Console\Commands;

use Illuminate\Console\Command;
use File;
use Excel;
use Storage;
use DB;

use tricciardi\ptzipcodes\Imports\DistrictImport;
use tricciardi\ptzipcodes\Imports\CountyImport;
use tricciardi\ptzipcodes\Imports\ZipCodeImport;

class ZipCodes extends Command
{
    protected $batchId;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ptzipcodes:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all files in import folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $folder = date("YmdHis");
        $import_id = uniqid();
        $importLocations = [
          'distritos',
          'concelhos',
          'todos_cp',
        ];

         $imported_anything = false;
         $disk = 'local';
         foreach($importLocations as $f) {
           $filepath = $this->getFilePath($f, false);

           if($filepath) {
             try {
               switch($f) {
                 case 'distritos':
                   echo "Importing: DistrictImport\r\n";
                   $time = microtime(true);
                   DistrictImport::import($filepath, $f);
                   echo (microtime(true) - $time) . " elapsed \r\n";
                   $imported_anything = true;
                   break;
                 case 'concelhos':
                   echo "Importing: CountyImport\r\n";
                   $time = microtime(true);
                   CountyImport::import($filepath, $f);
                   echo (microtime(true) - $time) . " elapsed \r\n";
                   $imported_anything = true;
                   break;
                 case 'todos_cp':
                   echo "Importing: Zipcodes\r\n";
                   $time = microtime(true);
                   ZipCodeImport::import($filepath, $f);
                   // Excel::import(new ZipCodeImport, $filepath,$disk,\Maatwebsite\Excel\Excel::CSV);
                   echo (microtime(true) - $time) . " elapsed \r\n";
                   $imported_anything = true;
                   break;
               }
               if($filepath) {
                 $this->archiveFile($folder, $filepath);
               }
             } catch (\Exception $e) {
               dd($e);
               if($filepath) {
                 $this->archiveError($folder, $filepath);
               }
             }
           }
         }
    }

    private function getImportBatch() {
      if(!$this->batchId) {
        $this->batchId = new ImportBatch;
        $this->batchId->files_count = 0;
        $this->batchId->save();
      }
      return $this->batchId;
    }

    private function incrementBatchFiles() {
      $this->getImportBatch();
      $this->batchId->files_count++;
      $this->batchId->save();
    }

    public function moveFileToImport($file) {
      $path = pathinfo($file);
      Storage::makeDirectory('importing');
      if(Storage::exists('importing/'.$path['basename'])) {
        Storage::delete('importing/'.$path['basename']);
      }
      Storage::copy($file, 'importing/'.$path['basename']);
    }
    public function archiveFile($folder, $file) {
      $path = pathinfo($file);
      echo "Import OK ".$file."\r\n";
      Storage::makeDirectory('imported');
      Storage::makeDirectory('imported/'.$folder, 0775, true);
      Storage::move($file, 'imported/'.$folder.'/'.$path['basename']);
    }
    public function archiveError($folder, $file) {
      $path = pathinfo($file);
      echo "Error on ".$file."\r\n";
      Storage::makeDirectory('errors');
      Storage::makeDirectory('errors/'.$folder, 0775, true);
      Storage::move($file, 'errors/'.$folder.'/'.$path['basename']);
    }

    private function getFilePath($name) {
      $files = Storage::allFiles('import');

      foreach($files as $file) {
        $path = pathinfo($file);
        $filename = $name;
        if($filename == $path['filename']) {
          $this->moveFileToImport($file);
          return 'importing/'.$path['basename'];
        }

      }
    }

}
