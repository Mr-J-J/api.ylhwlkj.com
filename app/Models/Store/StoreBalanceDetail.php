<?php

namespace App\Models\store;

use App\Models\Store;
use App\Support\Helpers;
use Illuminate\Database\Eloquent\Model;
/**
 * 代理商/分销商余额明细
 */
class StoreBalanceDetail extends Model
{
    protected $table = 'sa_store_balance_detail';

    static function add(Store $store,$money,$type,$title,$remark = ''){
        $detail = new StoreBalanceDetail;
        $detail->store_id = $store->id;
        $detail->order_sn = Helpers::makeOrderNo();
        $detail->title = $title;
        $detail->money = $money;
        $detail->type = $type;
        $detail->state = 1;
        $detail->after_balance = $store->balance;
        $detail->remark = $remark;
        $detail->save();
    }


    public function stores(){
        return $this->belongsTo(Store::class,'store_id');
    }
}