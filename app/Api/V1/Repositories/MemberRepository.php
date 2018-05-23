<?php
namespace App\Api\V1\Repositories;

use App\User;

class MemberRepository
{
    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }


    public function signUp($request){
        $email = $request['email'];

        $userData = array(
            'email' => $request['email'],
            'name' => $request['email'].date('Ymd').mt_rand(1,9),
            'mobile' => $request['email'],
            'nickname' => '',
            'password' => bcrypt($request['password']),
            'avatar' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'groupid' => 1, //默认普通会员
        );
        User::unguard();
        $user = User::create($userData);
        User::reguard();
        if(!$user->uid) {
            return false;
        }else{
            return true;
        }
    }

    public function getByEmail($email){
        return $this->user->select('uid','email','avatar','gender','status','nickname','birthday','avatarstatus','credits')
            ->where('email', $email)
            ->first();
    }

    public function getById($id){
        return $this->user->select('uid','email','avatar','gender','status','nickname','birthday','avatarstatus','credits')
            ->where('uid', $id)
            ->first();
    }

    private function updateDataFilter($request,$keys){
        $data = array();
        foreach ($keys as $key=>$value){
            if(isset($request[$value])){
                $data[$value] = $request[$value];
            }
        }
        return $data;
    }

    public function updateProfile($request,$user){
        $update_field = array('nickname','avatar','birthday','gender');
        $data = $this->updateDataFilter($request,$update_field);
        if(count($data)){
            (new User())->where('uid','=',$user->uid)
                ->update($data);
        }
    }

}