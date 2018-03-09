<?php
namespace App\Http\Controllers\Helper;

class Error
{
    /**
     * getError
     *
     * @param validator $validator
     * @return array
     */
    public static function getError($validator)
    {

        $err=$validator->errors()->getMessages();
        
        foreach ($err as $msg) {
            foreach ($msg as $alt) {
                $newErr[] = $alt;
            }
        }
        return $newErr;
    }
}
