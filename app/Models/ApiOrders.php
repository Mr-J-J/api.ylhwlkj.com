<?php

namespace App\Models;

use App\Models\user\TicketCode;
use App\Support\WpApi;
use Illuminate\Database\Eloquent\Model;
use Workerman\Protocols\Ws;

/**
 * 网票网出票订单
 */
class ApiOrders extends Model
{
    
    protected $table = 'api_orders';
    
    protected $guarded  = [];
    // protected $fillable = ['*'];

    protected $hidden = ['updated_at'];

    protected $total_rtimes = 5;

    /**
     * 创建出票订单
     *
     * @return void
     */
    public function createOrder(string $sid,string $order_no,$mobile,$uprice,$order_amount,$pay_no){
        // $mobile = WpApi::ssl_encrypt($mobile);
        $data = array(
            'sid'=>$sid,
            'order_no'=> $order_no,
            'mobile'=> $mobile,
            'msgtype'=>1,
            'p_amount'=>$uprice,
            'p_user_amount'=>$order_amount,
            'pay_no'=>'',
            'plat_form_payno'=>$pay_no,
            'pay_flag'=>0,
            'remark'=>''
        );
       return ApiOrders::updateOrcreate(['sid'=>$sid],$data);
    }


    /**
     * 申请购票
     *
     * @return void
     */
    public function buyTicket(){        
        $api_order = $this;
        if($api_order->state == 1){
            return true;
        }
        //申请下单
        $getOrder = WpApi::applyTicket($api_order->sid,$api_order->mobile,$api_order->p_amount,$api_order->p_user_amount);
        if(!$getOrder['status']){ //下单失败
            $api_order->remark = $getOrder['msg'];
            $api_order->save();
            return false;
        }        
        $result = $getOrder['data'];
        $payNo = $result[0]['PayNo'];
        if($payNo != ''){
            $buyTicket = WpApi::buyTicket($api_order->sid,$payNo,$api_order->plat_form_payno);
            if(!$buyTicket['status']){
                $api_order->remark = $buyTicket['msg'];
                $api_order->save();
                return false;
            }
            $api_order->rtimes = $api_order->rtimes + 1;
            $api_order->pay_no = $payNo;
            $api_order->state = 1;
            $api_order->save();
        }
        return true;
    }

    /**
     * 重发验票码
     *
     * @return void
     */
    public function resendMsg(){
        $api_order = $this;
        if($api_order->state != 3){
            return false;
        }
        WpApi::reSendMsg($api_order->sid);
    }
    /**
     * 订单查询并更新
     *
     * @return void
     */
    public function searchOrder(){
        $api_order = $this;
        if($api_order->state == 3){
            return true;
        }
        $buyOrder = UserOrder::getOrderByOrderNo($api_order->order_no) ;  
        if($api_order->rtimes >= $this->total_rtimes){            
            if($buyOrder->order_status != UserOrder::PAY_SUCCESS && $buyOrder->order_status != UserOrder::ORDER_SUCCESS){ //停止写票                
                WpApi::stopBuyTicket($api_order->sid);
            }
            return false;
        }
        $result = WpApi::searchOrderInfoBySID($api_order->sid);
        if(!$result['status'] || empty($result['data'])){
            return false;
        }
        $result = $result['data'];
        
        $api_order->rtimes = $api_order->rtimes + 1;
        $api_order->save();
        $ticket_info = $result["QRCodeText"]?$result["QRCodeText"]:$result["TicketID"];
        if(empty($result) || empty($ticket_info) ){
            \App\Jobs\Wangpiao\OutTicketJob::dispatch($api_order)->delay($api_order->rtimes*5);
            return false;
        }
        $res = array(
            'pay_flag'=> $result["PayFlag"],
            'cinema_id'=> $result["CinemaID"],
            'film_id'=> $result["FilmID"],
            'stype'=> $result["Stype"],
            's_time'=> $result["Stime"],
            // ''=> $result["Mobile"] => "15303122197",
            'cinema_name'=> $result["CinemaName"],
            'hall_name'=> $result["HallName"],
            'film_time'=> $result["FilmTime"],
            'amount'=> $result["Amount"],
            'film_name'=> $result["FilmName"],
            'effective_time'=> $result["EffectiveTime"],// => "2022-03-08 17:41:00",
            'ticket_id'=> $result["QRCodeText"]?$result["QRCodeText"]:$result["TicketID"],
            'pwd'=> $result["Pwd"],
            'seat_info'=> $result["SeatInfo"],// => "3:2",
            'payment_no'=> $result["PaymentNo"] ,//=> "1231321231547987943541541351",
            'pay_type'=> $result["PayType"],
            // ''=> $result["FilmPhoto"]?:'',
            // ''=> $result["PayNo"] => "",
            // ''=> $result["QRCodeText"] => "",
            'ticket_info'=> $result["TicketInfo"],// => "凭序号999999验票码999999至影院柜台或影城自动出票机取票。",
        );
        // -1表示用户取消订单，0 未支付 1，4正在处理中 2支付失败 3购票成功 ,5准备退款，6退款成功
        // if(!in_array($res['pay_flag'],[3,5,6])){
        //     return false;
        // }
        
        $res['state'] = 3;
        $res['remark'] = '';
        $api_order->fill($res)->save();    
        
        TicketCode::where('order_id',$buyOrder->id)->where('user_id',$buyOrder->user_id)->delete();      
        //出票
        TicketCode::addCode(array(
            'user_id'=>$buyOrder->user_id,
            'store_id'=>0,
            'order_id'=>$buyOrder->id,
            'order_no'=>$api_order->order_no,
            'ticket_code'=> $res['ticket_id'],
            'valid_code'=>$res['pwd'],
            'remark'=>$res['ticket_info']
        ));
        if($buyOrder->order_status == UserOrder::PAY_SUCCESS ){
            UserOrder::outTicket($api_order->order_no);
            try {
                $showtime = date('Y/m/d H:i',$buyOrder->show_time);
                $codeArr = TicketCode::where("order_id",$buyOrder->id)->pluck('ticket_code')->toArray();              
                $cinemaName = $buyOrder->cinemas;
                $movie_name = "《{$buyOrder->movie_name}》";
                $cloudsms = new \App\Support\CloudSms;
                $message = $cloudsms->ticket_count_templet($showtime.$cinemaName.$movie_name,implode(',',$codeArr));
                $cloudsms->send_sms($buyOrder->buyer_phone,$message);
            } catch (\Throwable $th) {
                logger("短信发送失败：{$api_order->order_no},".$th->getMessage());
            }
        }
        
        return $api_order;
    }

    /**
     * 获取订单信息
     *
     * @param string $sid
     * @return ApiOrders
     */
    static function getOrder(string $sid){
        return self::where('sid',$sid)->first();
    }
    
}
