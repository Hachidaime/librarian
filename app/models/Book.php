<?php

namespace app\models;
use Illuminate\Database\Eloquent\Model;

/**
 * @desc this class will handle User model
 *
 * @class UserModel
 * @author Hachidaime
 */
class Book extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lib_book';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getTableName()
    {
        return with(new static())->getTable();
    }
}
