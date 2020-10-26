<?php

namespace app\rules;
use Illuminate\Database\Capsule\Manager as DB;

class UniqueRule extends \Rakit\Validation\Rule
{
    protected $message = ':attribute :value has been used';

    protected $fillableParams = ['table', 'column', 'except'];

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['table', 'column']);

        // getting parameters
        $column = $this->parameter('column');
        $table = $this->parameter('table');
        $except = $this->parameter('except');
        $key = $this->getAttribute()->getKey();

        $count = DB::table($table)
            ->where($key, $value)
            ->where($column, '!=', $except)
            ->count();

        // true for valid, false for invalid
        return intval($count) === 0;
    }
}
