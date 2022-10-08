<?php

namespace App\MallModels;


use Overtrue\Pinyin\Pinyin;

use Illuminate\Database\Eloquent\Model;
/**
 * 城市列表
 */
class Region extends Model
{
    protected $table = 'mall_cities';
    protected $primaryKey = 'city_code';
    
    protected $guarded  = [];

    protected $hidden = ['created_at','updated_at'];

    static function getRegions(int $parent_id,$level = 1,$columns = ['*']){
        return self::select($columns)->where('parent_city_code',$parent_id)->where('city_level',(int)$level)->get();
    }
}
