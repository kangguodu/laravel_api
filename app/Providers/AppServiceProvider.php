<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Validator;
use DB;
use Illuminate\Support\Facades\Hash ;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @link http://stackoverflow.com/a/35573652/5840474
     * @link https://laravel.com/docs/5.3/validation#custom-validation-rules
     * @return void
     */
    public function boot()
    {
        //
         Schema::defaultStringLength(191);  //新增的

        //Laravel Validator 自定义验证规则,验证旧密码是否正确
        Validator::extend('password_hash_check', function($attribute, $value, $parameters, $validator) {
            // Save the query to file
            return Hash::check($value , $parameters[0]) ;
        });

        Validator::replacer('password_hash_check', function($message, $attribute, $rule, $parameters) {
            return $message?$message:"舊密碼不正確";
        });

        //@link http://stackoverflow.com/a/24426642/5840474
        //Laravel Validator 自定义验证规则,验证电邮或电话号码是否已存在
        Validator::extend('not_exists', function($attribute, $value, $parameters)
        {
            return DB::table($parameters[0])
                    ->where($parameters[1], '=', $value)
                    ->count() > 0;
        });

        Validator::replacer('not_exists', function($message, $attribute, $rule, $parameters) {
            return $message?$message:"{$attribute} 不存在";
        });

//        $str = $_SERVER['REQUEST_METHOD']."\t\t".$_SERVER['REQUEST_URI']."\t\t".json_encode($_REQUEST);
//        \Log::info($str);

        // 如果要放入日志文件中
        DB::enableQueryLog();
        DB::listen( function ($sql) {
            foreach ($sql->bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else {
                    if (is_string($binding)) {
                        $sql->bindings[$i] = "'$binding'";
                    }
                }
            }
            // Insert bindings into query
            $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
            $query = vsprintf($query, $sql->bindings);
            // Save the query to file
            $logFile = fopen(
                storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '_query.log'),
                'a+'
            );
            fwrite($logFile, date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL);
            fclose($logFile);
        }
        );

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
