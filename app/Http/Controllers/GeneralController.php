<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Services\GeneralServices;

class GeneralController extends Controller
{
    use ResponseTrait;

    public function dynamicOption(Request $request)
    {
        $data = GeneralServices::dynamicOption($request);
            
        return $this->success('Success', $data);

    }

}
