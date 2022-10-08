<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Support\Facades\Hash;
/**
 * 代理商model
 */
class Store extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'sa_stores';
    static $level_enum = [1=>'代理商',2=>'分销商'];
      
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = Hash::make($value);
    // }
}
