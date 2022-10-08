<?php

namespace App\Http\Controllers\MiniPro;

use App\Models\Movie;
use App\Models\Cinema;

use App\Support\Helpers;
use App\Models\UserOrder;
use Illuminate\Http\Request;
use App\ApiModels\Wangpiao as Api;
use App\CardModels\OlCard;
use App\CardModels\UserWallet;
use App\Models\Store;

class OrderController extends UserBaseController
{
    public function index(Request $req){

        $type = $req->input('type');
        try {
            $result = UserOrder::orderList($this->user->id,intval($type));
        } catch (\Exception $e) {
            return $this->error('失败:'.$e->getMessage());
        }

        return $this->success('成功',$result);
    }

    /**
     * 订单详情
     *
     * @param Request $req
     * @return void
     */
    public function info(Request $req){
        $orderId = $req->input('order_no');

        $info = UserOrder::where('order_no',$orderId)->where('user_id',$this->user->id)->first();
        
        if(empty($info)){
            return $this->error('失败：订单信息不存在');
        }
        if($info->order_status == 20){
            $apiorder = \App\Models\ApiOrders::getOrder($info->api_lock_sid);
            if(!empty($apiorder)){
                $result = $apiorder->searchOrder();
                if(!$result){
                    $info = UserOrder::where('order_no',$orderId)->where('user_id',$this->user->id)->first();
                }
            }
        }
        if($info->ol_card_id == 0){
            
            if($info->order_status == 10){
                $info = $info->checkOrder($this->getApp(3,$this->user->com_id),$info);
            }
            // if(request()->ip() == '27.186.194.154'){
            //     $info->ol_card_id = '';
            // }
        }else{
            $info->ol_card_id = OlCard::where('id',$info->ol_card_id)->value('card_no');            
        }
        $info->show_date = date('Y-m-d',$info->show_time);
        $info->show_time = date('H:i',$info->show_time);
        $info->close_time = date('H:i',$info->close_time);
        $info->seat_names = str_replace(',',' ',$info->seat_names );
        if(!empty($info->old_seat_names)){
            $info->seat_names = str_replace(',',' ',$info->seat_names ) . ' 已调座';
        }
        $info->kefu_tel = trim(Helpers::getSetting('kefu_tel'));//
        $info->store_tel = '';
        if($info->order_status == 30){
            $res = $info->code;
            $storeId = !empty($res[0]) ?$res[0]->store_id:0;
            $images = \App\Models\user\TicketImg::where('order_no',$info->order_no)->first();
            if($images){
                if(!empty($images->images)){
                    $info->images = explode(',',$images->images);
                }
            }else{
                $imglist = [];
                foreach($res as $code){
                    $imglist[] = url('api/showqrcode?text='.$code->ticket_code.'&r='.time());
                }
                $info->images = $imglist;
            }
            $storeInfo = Store::where('id',$storeId)->first();
            if(!empty($storeInfo)){
                $info->store_tel = $storeInfo->store_phone;
            }
            
        }
        return $this->success('创建成功',$info);
    }
    /**
     * 购票下单 
     *
     * @param Request $request
     * @return void
     */
    public function addOrder(Request $request){
        $data = $request->post();
        //com_id        
        $ol_card = false;
        if(!empty($data['ol_card'])){
            $ol_card = OlCard::where('id',(int)$data['ol_card'])->where('user_id',$this->user->id)->first();
            if(empty($ol_card)){
                return $this->error('影城卡无效，请重新选择');
            }
            try {
                $ol_card->canUseCard($this->user->id,$data);
            } catch (\Throwable $th) {
                $ol_card = false;
            }
        }
        
        $pay_param = $result =  array();
        
        try {
            
            //锁座
            $order = UserOrder::createOrder($data,$this->user);
            $result = array(
                'order_no'=>$order->order_no,
                'movie_name'=>$order->movie_name
            );
            $seat_ids = str_replace(',','|',$order->seat_ids);
            $content = [
                'seat_ids' => $seat_ids,
                'paiqi_id'=> $order->paiqi_id,
                'cinema_id' => $order->cinema_id,
            ];
            $param = [
                'user_id'=> $this->user->id,
                'paiqi_id'=> $order->paiqi_id,
                'cinema_id'=> $order->cinema_id,
                'seat_ids' => $seat_ids
            ];
            
            $return = \App\Support\WpApi::lockSeat($param);
            
            $remark = '';
            if($return['ErrNo'] != 0 || empty($return['Data'])){
                $remark = '座位锁定失败: '.json_encode($return,JSON_UNESCAPED_UNICODE);
                \App\Models\OrderLog::addLogs($order->id,$order->order_no,$remark,json_encode($content,256));
                UserOrder::where('id',$order->id)->delete();
                return $this->error('座位锁定失败'.$return['Msg']);
            }
            if($order->use_card && $order->discount_price){
                UserWallet::walletKouFee($this->user,$order);
                $order->retailCardPaySuccess();
            }
            
            if(!empty($return['Data'])){
                $remark = '锁座成功'.json_encode($return,JSON_UNESCAPED_UNICODE);
                $sids = array_column($return['Data'],'SID');
                $order->api_lock_sid = implode(',',$sids);
                $order->save();
                \App\Models\OrderLog::addLogs($order->id,$order->order_no,$remark,json_encode($content,256));
            }

            if($ol_card){
                $order->olCardPaySuccess($ol_card);
                return $this->success('购买成功',['order_info'=>$result,'pay_param'=>$pay_param]);
            }

            $total_fee =round($order->amount * 100);

            if($total_fee > 0 ){
                $app = $this->getApp(3,$this->user->com_id);
                $payResult = $app->order->unify([
                    'body' => $result['movie_name'].'-电影票',
                    'out_trade_no' => $result['order_no'],                    
                    'total_fee' => round($order->amount * 100),                    
                    'notify_url' => route('notify',['comId'=>$this->user->com_id]), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                    'trade_type' => 'JSAPI', //请对应换成你的支付方式对应的值类型
                    'openid' => $this->user->openid,
                ]);
                if(empty($payResult['prepay_id'])){
                    logger('订单创建失败 '.json_encode($payResult,256).','.$nowtime);
                }
                $pay_param = $app->jssdk->bridgeConfig($payResult['prepay_id'], false);
           }
        } catch (\Exception $e) {
            $nowtime = time();
            logger('订单创建失败 '.json_encode($result,256).','.$nowtime.':'.$e->getMessage());
            // throw $e;
            return $this->error($e->getMessage());
            // return $this->error("订单创建失败[{$nowtime}]");
        }        
        return $this->success('创建成功',['order_info'=>$result,'pay_param'=>$pay_param]);
    }

    /**
     * 立即付款
     *
     * @param Request $request
     * @return void
     */
    public function payOrder(Request $request){
        $orderNo = $request->input('order_no','');
        $order = UserOrder::getOrderByOrderNo($orderNo);

        if(empty($order)){
           return $this->error('订单信息不存在');
        }

        if($order->order_status != 10 || $order->amount == 0){
           return $this->error('支付失败,'.UserOrder::statusTxt($order->order_status).'不能支付');
        }
        if($order->use_card && $order->discount_price){
            UserWallet::walletKouFee($this->user,$order);
        }
        $result = array(
            'order_no'=>$order->order_no,
            'movie_name'=>$order->movie_name
        );
        $app = $this->getApp(3,$this->user->com_id);
        $payResult = $app->order->unify([
            'body' => $result['movie_name'].'-电影票',
            'out_trade_no' => $result['order_no'],
            // 'total_fee' => 1,
            'total_fee' => round($order->amount * 100),
            // 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => route('notify',['comId'=>$this->user->com_id]), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', //请对应换成你的支付方式对应的值类型
            'openid' => $this->user->openid,
        ]);
        
        $pay_param = $app->jssdk->bridgeConfig($payResult['prepay_id'], false);
        return $this->success('成功',['order_info'=>$result,'pay_param'=>$pay_param]);
    }

    /**
     * 确认订单信息接口
     *
     * @param Request $req
     * @return void
     */
    public function confirmOrder(Request $req){
        $paiqi_id = $req->input('paiqi_id','');
        $seat_ids = $req->input('seat_ids','');
        $seat_names = $req->input('seat_names','');
        $seat_flag = $req->input('seat_flag','');
        $comId = $req->input('com_id',0);
        
        try {
            $scheulesInfo = Api\Schedules::where('show_index',$paiqi_id)->firstOrFail();
        } catch (\Exception $e) {
            return $this->error('请选择观影场次');
        }
        
        $stopTime = (int)Helpers::getSetting('stop_order'); //放映前多少分钟
        if($stopTime){
            $showtime = strtotime("- {$stopTime} minute",$scheulesInfo->show_time);
            if($showtime <= time()){
                
              // return $this->error('请选择观影场次');//影片开场时间太近票商无法为您出票，请换场次');
            }
        }
        
        
        if(!$scheulesInfo->film){
            return $this->error('电影信息未找到');
        }
        
        
        if(empty($seat_ids)){
            return $this->error('请选择座位');
        }
        
        $discount = Helpers::getSetting('tehui_price_rate') / 10;
        
        $originalPrice = round($scheulesInfo->getOriginal('price') / 100,2);
        $marketPrice = $originalPrice;
        $kuaisu_price = $scheulesInfo->price;
        $tehui_price = $scheulesInfo->local_price;
        $discountPrice = round($originalPrice-$tehui_price,2);
        
        $cinemaBrand = Api\CinemasBrand::where('id',$scheulesInfo->cinema->brand_id)->first(); 
        //影旅卡价格
        if($comId && $cinemaBrand){
            // $marketPrice = $scheulesInfo->local_price;
            $kuaisu_price = $originalPrice;
            $discountPrice = $cinemaBrand->calcDiscountMoney($kuaisu_price);
            // $discountPrice = $cinemaBrand->calcDiscountMoney($tehui_price);
            $userWalletBalance = UserWallet::UserCardList($this->user->id)->sum('balance');
            if($userWalletBalance && $userWalletBalance >= $discountPrice){
                $tehui_price = $kuaisu_price - $discountPrice;
            }else{
                $discountPrice = 0;
                $tehui_price = $kuaisu_price;
            }
        }

        //影城卡
        $myOlCard = OlCard::getCardList($this->user->id,$cinemaBrand->id,$scheulesInfo->cinema->id);
        
        $result = array(
            'film_name'=>$scheulesInfo->film->show_name,
            'poster'=>$scheulesInfo->film->poster,
            'seat_ids' => $seat_ids,
            'seat_names' => $seat_names,
            'seat_flag' => $seat_flag,
            'seat_names_txt' => str_replace(',',' ',$seat_names),
            'show_time' => $scheulesInfo->show_date . ' ' . $scheulesInfo->show_time_txt,
            'show_version' => $scheulesInfo->show_version,
            'hall_name' => $scheulesInfo->hall_name,
            'cinema_name'=> $scheulesInfo->cinema->cinema_name,
            'market_price'=> $marketPrice,
            'kuaisu_price'=>$kuaisu_price,
            'discount'=> $discountPrice,
            'tehui_price'=> round($tehui_price,2),
            'phone'=> $this->user->mobile,
            'count'=> count(explode(',',$seat_ids)),
            'cardlist'=> $myOlCard?:[],
            'kuaisu_show'=>(int)Helpers::getSetting('kusu_show'), //0不显示快速购票 1显示快速购票
        );
        return $this->success('',$result);

    }

    /**
     * 取消订单
     *
     * @param Request $req
     * @return void
     */
    public function cancelOrder(Request $req){
        $order_no = $req->input('order_no','');
        $order = UserOrder::getOrderByOrderNo($order_no);        
        $order->cancelOrder($order);
        return $this->success('订单取消成功');                
    }



    /**
     * 吃喝玩乐订单 [订单列表]
     */
    public function list(Request $requst){
        $list = array();
        return $this->success('',$list);
    }

    
}
