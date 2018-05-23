<?php
namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;

class MemberTransformer extends TransformerAbstract
{
    public function transform($object){
        $object->avatar = $object->avatarstatus?url('/images/avatar/').'/default.png':$object->avatar;
        $result = array(
            'uid' => $object->uid,
            'email' => $object->email,
            'avatar' =>  $object->avatar,
            'status' => intval($object->status),
            'gender' => $object->gender,
            'nickname' => $object->nickname,
            'birthday' => $object->birthday,
            'credits' => $object->credits,
        );
        if(isset($object->token)){
            $result['token'] = $object->token;
        }
        return $result;
    }
}