<?php

namespace App\Imports;

use App\Models\Category;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class CategoryImport implements ToModel
{
    public function model(array $row)
    {
        $data['name'] = $row[0];
        $data['description'] = $row[0];

        if(isset($row[1])){

            $get_category = Category::where('name',$row[1])->first();

            $data['level'] = $get_category->level + 1;
            $data['category_id'] = $get_category->id;
            $data['code'] = self::getCode($data,$get_category->code);
        }

        else{
            $data['level'] = 1;
            $data['code'] = self::getCode();
        }

        $create = Category::create($data);
    }

    public static function getCode($data = null,$old_code = null){

        if($data){
            $get_category = Category::select('code')->where('level',$data['level'])->where('category_id',$data['category_id'])->orderBy('code','desc')->first();

            if($get_category){
                $formattedNumber = explode('-', $get_category->code);

                $formattedNumber[count($formattedNumber) - 1] = str_pad($formattedNumber[count($formattedNumber) - 1] + 1, 2, '0', STR_PAD_LEFT);

                return implode('-', $formattedNumber);
            }
            else{
                $formattedNumber = $old_code.'-'.'01';
            }

            return $formattedNumber;
        }
        else{
            $get_category = Category::select('code')->where('level',1)->orderBy('code','desc')->first();

            $get_category = $get_category?->code;

            $nextNumber = $get_category + 1;

            $formattedNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            return $formattedNumber;

        }

    }
}
