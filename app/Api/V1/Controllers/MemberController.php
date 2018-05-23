<?php
namespace App\Api\V1\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

use App\Api\V1\Repositories\MemberRepository;
use App\Api\V1\Validators\MemberValidator;
use App\Api\V1\Transformers\MemberTransformer;
class MemberController extends BaseController
{
    protected $validator;
    protected $repository;

    public function __construct(MemberRepository $memberRepository,MemberValidator $memberValidator){
        $this->repository = $memberRepository;
        $this->validator = $memberValidator;
    }

    public function login(Request $request){
        $credentials = $request->only(['email','password']);
        try{
            $this->validator->with($credentials)->passesOrFail('login');
            try {
                // array_push($credentials,false);
                if (! $token = JWTAuth::attempt($credentials)) {
                    return $this->responseError(trans("messages.auth_error"),$this->status_login_error);
                }
            } catch (JWTException $e) {
                return $this->responseError(trans("messages.auth_error"),$this->status_login_error);
            }
            $user = $this->repository->getByEmail($credentials['email']);
            $user->token = $token;
            return $this->response()->item($user,new MemberTransformer());
        }catch (ValidatorException $e){
            return $this->responseError($e->getMessageBag()->first(),$this->status_validator_error,422);
        }

    }

    public function signUp(Request $request)
    {
        $credentials = $request->all();
        try{
            $this->validator->with($credentials)->passesOrFail('create');
            $result = $this->repository->signUp($credentials);
            if($result){
                return array('success' => true);
            }else{
                return array('success' => false);
            }
        }catch (ValidatorException $e){
            return $this->responseError($e->getMessageBag()->first(),$this->status_validator_error,422);
        }
    }

    public function userInfo(Request $request){
        $user_id = $this->getAuthUserId();
        $userInfo = $this->repository->getById($user_id);
        return $this->response()->item($userInfo,new MemberTransformer());
    }

    public function updateUser(Request $request){
        $credentials = $request->all();
        $user = $this->getAuthUser();
        $this->repository->updateProfile($credentials,$user);
        return array();
    }

    public function changepassword(Request $request){
        $credentials = $request->only(
            'old_password', 'password', 'password_confirmation'
        );
        //get jwt auth user info
        $user = $this->getAuthUser();
        $hashed_password = $user->password;
        $message = array(
            'old_password.password_hash_check' => "舊密碼不正確",
        );
        $validator = Validator::make($credentials, [
            'old_password' => 'required|password_hash_check:'.$hashed_password,
            'password' => 'required|confirmed|min:6',
        ],$message);

        if($validator->fails()) {
            return $this->responseError($validator->errors()->first(),422);
        }
        $user->password = $credentials['password'];
        $user->save();
        return array();
    }

    public function logout(Request $request){
        $token = JWTAuth::getToken();
        try {
            JWTAuth::setToken($token)->invalidate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->responseError(trans("messages.logout_success"),$this->status_jwt_invalidate,401);
        } catch (JWTException $e) {
            return $this->responseError(trans("messages.logout_success"),$this->status_jwt_invalidate,401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e){
            return $this->responseError(trans("messages.logout_success"),$this->status_jwt_invalidate,401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return $this->responseError(trans("messages.logout_success"),$this->status_jwt_invalidate,401);
        }
        return array();
    }
}