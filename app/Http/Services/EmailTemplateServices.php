<?php

namespace App\Http\Services;


use App\Http\Traits\ResponseTrait;
use App\Http\Resources\EmailTemplateResources;
use App\Models\EmailTemplate;

class EmailTemplateServices
{
    use ResponseTrait;

    public static function create($data){
        try{
            $create = EmailTemplate::create($data);

            $inactive_other_email = EmailTemplate::where('id','!=',$create->id)->update(['is_active' => 0]);

            $return = new EmailTemplateResources($create);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(EmailTemplate $email_template,$data){
        try{

            $create = $email_template->update($data);

            if($data['is_active']){
                $inactive_other_email = EmailTemplate::where('id','!=',$email_template->id)->update(['is_active' => 0]);
            }

            $return = new EmailTemplateResources($email_template);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function delete(EmailTemplate $email_template){

        $email_template->delete();

        return self::success('Success', null);
    }
}

    
 