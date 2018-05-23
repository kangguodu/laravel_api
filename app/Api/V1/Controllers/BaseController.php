<?php
namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class BaseController extends Controller
{
    use Helpers;

    public $status_success                           = 200;
    public $status_unkown                            = 100000; //程序内部错误：未知错误
    public $status_mysql_disconnect                = 100001; //程序内部错误: mysql 连接失败
    public $status_redis_disconnect                = 100002; //程序内部错误: redis 连接失败
    public $status_mongo_disconnect                = 100003; //程序内部错误: mongodb 连接失败

    public $status_signup_error                     = 200002; //用户注册失败
    public $status_login_error                      = 200003; //用户登录失败
    public $status_jwt_invalidate                  = 400001; // jwt token 超时，已失效
    public $status_validator_error                 = 422001; //数据验证失败
    public $status_not_found_error                 = 404001; //数据未找到
    public $status_save_fail_error                 = 500001; //数据保存失败
    public $status_remove_fail_error               = 500002; //数据删除失败

    public $ab_test = true;
    //分頁屬性
    public $current_page=1;//當前頁
    public $per_page=10;//每頁顯示條數

    protected $response_data = [
        'success'=> true,
        'error_code' => 0,
        'data'   => [],
        'error_msg' => ''
    ];



    public function responseSuccess($data = array()){
        return response()->json($data);
    }

    public function notFoundResponse($object){
        return $this->responseError(
            sprintf(trans("messages.can_not_found"),$object),
            $this->status_not_found_error,
            400);
    }

    public function saveFailResponse($object){
        return $this->responseError(
            sprintf(trans("messages.object_save_fail"),$object),
            $this->status_save_fail_error,
            400);
    }

    public function removeFailResponse($object){
        return $this->responseError(
            sprintf(trans("messages.object_save_fail"),$object),
            $this->status_remove_fail_error,
            400);
    }

    public function responseError($message,$code = 1,$status_code = 400){
        $this->response_data['success'] = false;
        $this->response_data['error_msg'] = $message;
        $this->response_data['error_code'] = $code;
        if($code == 422){
            $status_code = 422;
        }
        return response()->json($this->response_data,$status_code);
    }

    public function getAuthUser(){
        return  $this->auth->user();
    }

    public function getAuthUserId(){
        $user = $this->getAuthUser();
        return $user->uid;
    }

}