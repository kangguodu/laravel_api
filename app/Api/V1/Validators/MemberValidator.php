<?php
namespace App\Api\V1\Validators;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

class MemberValidator extends LaravelValidator
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'email' => 'required|unique:member',
            'password' => 'required|min:6',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'email' => 'required',
            'password' => 'required',
        ],
        'change_password' => [
            'old_password' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',
        ],
        'login' => [
            'email' => 'required',
            'password' => 'required',
        ]
    ];
    protected $attributes = [
        'email' => '電郵',
        'password' => '密碼',
        'old_password' => '舊密碼',
        'password_confirmation' => '確認密碼',
    ];
}