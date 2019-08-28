<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'update_date';
    protected $primaryKey = 'id';
    protected $table = "medicine";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name','type', 'count'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];

    public static function getValidation(){
        return [
            "code" => 'max:4|required|unique:medicine',
            "name" => 'required',
            "type" => 'required',
            'count' => 'required'
        ];
    }
}
