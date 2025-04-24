<?php

namespace App\Http\Services;
use App\Models\Category;

class CategoryServices
{
    public static function create($data){

        $data = self::processData($data);

        $create = Category::create($data);

        return $create;
    }

    public static function update(Category $category,$data){

        $data = self::processData($data);

        $create = $category->update($data);

        return $create;
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

    public static function processData($data){

        if(isset($data['category_id'])){

            $get_category = Category::where('id',$data['category_id'])->first();

            $data['level'] = $get_category->level + 1;
            $data['code'] = self::getCode($data,$get_category->code);
        }

        else{
            $data['level'] = 1;
            $data['code'] = self::getCode();
        }

        return $data;
    }
}