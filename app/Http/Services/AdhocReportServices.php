<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Resources\AdhocReportResources;
use App\Http\Traits\ResponseTrait;
use App\Models\Report;


class AdhocReportServices
{
    use ResponseTrait;
    
    public static function create($data){
        try{
            $data = self::uploadDoc($data);

            $create = Report::create($data);

            $return = new AdhocReportResources($create);

            return self::success('Success', $return);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(Report $report,$data){

        try{
            $data = self::uploadDoc($data);

            $update = $report->update($data);

            $return = new AdhocReportResources($report);

            return self::success('Success', $return);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function delete(Report $adhoc_report){
        $adhoc_report->delete();
        
        return self::success('Success', true);
    }

    public static function uploadDoc($data){

        $destination = storage_path('app/private/report/non_default'); 

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }
        
        $disk = config('filesystems.default');

        $document = $data['file_name'];

        if($document instanceof \Illuminate\Http\UploadedFile && $document->isValid()) {

            $mimeType = $document->getClientOriginalExtension();

            $file_name = time() . '_' . Str::random(10).'.'.$mimeType;

            $path = 'report/non_default/'.$file_name;

            Storage::disk($disk)->put(
                $path,
                file_get_contents($document->getRealPath())
            );

            $data['file_name'] = $file_name;
            $data['path'] = $path;
        }
        return $data;
        
    }
}