<?php
namespace app\rules;

class LoginRule extends \Rakit\Validation\Rule
{
    protected $message = '<strong>Username</strong> dan <strong>Password</strong> tidak cocok.';

    protected $fillableParams = ['usr_username'];

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['usr_username']);

        // getting parameters
        $username = $this->parameter('usr_username');
        $password = $value;

        $detail = \app\models\User::where('usr_username', '=', $username)
            ->first()
            ->toArray();

        $count =
            $password ==
            \app\helpers\Functions::decrypt($detail['usr_password'])
                ? 1
                : 0;

        // true for valid, false for invalid
        return $count !== 0;
    }
}
