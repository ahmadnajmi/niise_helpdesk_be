<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentDocument;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Storage;

class IncidentDocumentController extends Controller
{
    use ResponseTrait;

    public function show(IncidentDocument $incident_document){

        if (Storage::disk('local')->exists($incident_document->path)) { 
            return Storage::disk('local')->download($incident_document->path);
        }

        return $this->error('File not found');
    }

    public function destroy(IncidentDocument $incident_document){
        $incident_document->delete();

        return $this->success('Success', null);
    }

    public function downloadAssetFile($incident_no){
        $incident =  Incident::where('incident_no',$incident_no)->first();
        

        $filePath = 'incident/'.$incident->asset_file; 

        if (Storage::disk('local')->exists($filePath)) { 
            return Storage::disk('local')->download($filePath);
        }

        return $this->error('File not found');

    }
}
