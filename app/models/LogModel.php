<?php
namespace app\models;
use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lib_log';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
