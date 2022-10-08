<?php

namespace App\Http\Controllers;

use App\Support\Code;
use App\Models\Cinema;
use App\Models\Setting;
use App\Support\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
/**
 * 开放接口
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $setting = [];

    protected $city_code;
    
    public function __construct(){
        
        $this->setting = Setting::getSettings();
        
        // $this->city_code = request('city_code','110100');
    }
        
    /**
     * 获取小程序、公众号
     *
     * @param integer $type 1小程序 2公众号 3支付
     * @return void
     */
    protected function getApp($type=1,$comId = 0){
        if($type == 1){
            $config = $comId ? config('wechat.mini_program.default1'):config('wechat.mini_program.default');
            return \EasyWeChat\Factory::miniProgram($config);   
        }elseif($type == 2){
            $config = config('wechat.official_account.default');
            return \EasyWeChat\Factory::officialAccount($config);        
        }elseif($type == 3){
            $config = $comId ? config('wechat.payment.default1'):config('wechat.payment.default');
            return \EasyWeChat\Factory::payment($config);
        }
    }

    public function agreement(Request $req){
        $id = $req->input('id',0);
        $content = \App\Models\Agreement::find($id);
        return $this->success('',$content);
    }

    protected function error($msg,$data = []){
        return Code::setCode(Code::REQ_ERROR,$msg,$data); 
    }

    protected function success($msg,$data=[]){
        return Code::setCode(Code::SUCC,$msg,$data); 
    }
}
