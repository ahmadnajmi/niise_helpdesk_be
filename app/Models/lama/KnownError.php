<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class KnownError extends Model
{
    public $timestamps = false;

    public $incrementing = false;
    
    protected $table = 'HD_Known_Error';
    
    protected $primaryKey = 'ke_ID';

    public function getAllKnownError(){
        return $this->select('ke_ID','ke_category','ke_keyword','ke_problem','ke_resolution')
                    ->orderby('ke_ID','ASC');
    }

    public function getKnownError($errrorID){
        $result = $this->select('ke_ID','ke_category','ke_keyword','ke_problem','ke_resolution','Ct_Description')
                    ->where('ke_ID',$errrorID)
                    ->leftJoin('RefCategory', 'ke_category', '=', 'RefCategory.Ct_Code')->get()
                    ->map(function($item) {
                        // Set default values for null fields
                        $item->ke_category = $item->ke_category ?? ' ';
                        $item->ke_keyword = $item->ke_keyword ?? ' ';
                        $item->ke_problem = $item->ke_problem ?? 'No problem description';
                        $item->ke_resolution = $item->ke_resolution ?? 'No resolution provided';
                        $item->Ct_Description = $item->Ct_Description ?? ' ';
                        return $item;
                    });
        
        log::info($result);
        return $result;
    }

}
