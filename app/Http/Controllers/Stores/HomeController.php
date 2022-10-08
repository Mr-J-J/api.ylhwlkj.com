<?php

namespace App\Http\Controllers\Stores;

use App\CardModels\CardOrder;
use App\CardModels\Cards;
use Illuminate\Http\Request;
use App\CardModels\CardPrice;
use App\CardModels\RsWithDraw;
use App\CardModels\SettleList;
use App\CardModels\StoreBalanceDetail;
use App\CardModels\WalletDetail;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\WebController;
use App\Models\TicketUser;

class HomeController extends WebController
{

    public function __construct()
    {

        // $this->middleware('auth');
    }
   
    public function index(Request $request){
        $store = $request->user();
        $arr = array('commision'=>0,'order'=>0,'member'=>0);
        //今日数据
        $nowdate = date('Y-m-d');
        $today = $arr;
        $today['commision'] = StoreBalanceDetail::where('com_id',$store->id)->whereDate('created_at',$nowdate)->sum('money');
        $today['order'] = CardOrder::where('com_id',$store->id)->where('order_status',CardOrder::SUCCESS)->whereDate('created_at',$nowdate)->count('id');
        $today['member'] = TicketUser::where('com_id',$store->id)->whereDate('created_at',$nowdate)->count('id');
        //昨日数据
        $yestdate = date('Y-m-d',strtotime('-1day'));
        $yestday = $arr;
        $yestday['commision'] = StoreBalanceDetail::where('com_id',$store->id)->whereDate('created_at',$yestdate)->sum('money');
        $yestday['order'] = CardOrder::where('com_id',$store->id)->where('order_status',CardOrder::SUCCESS)->whereDate('created_at',$yestdate)->count('id');
        $yestday['member'] = TicketUser::where('com_id',$store->id)->whereDate('created_at',$yestdate)->count('id');
        //本月数据
        $currentMonth = date('m');
        
        $month = $arr;
        $month['commision'] = StoreBalanceDetail::where('com_id',$store->id)->whereMonth('created_at',$currentMonth)->sum('money');
        $month['order'] = CardOrder::where('com_id',$store->id)->where('order_status',CardOrder::SUCCESS)->whereMonth('created_at',$currentMonth)->count('id');
        $month['member'] = TicketUser::where('com_id',$store->id)->whereMonth('created_at',$currentMonth)->count('id');
        // $appid = config('wechat.mini_program.default1.app_id')
        return view('stores.home',compact('today','yestday','month','store'));
    }

    /**
     * 分销商资料
     *
     * @return void
     */
    public function profile(Request $request){        
        return view('stores.profile',$request->user());
    }

    /**
     * 账户提现
     *
     * @return void
     */
    public function account(Request $request){
        $storeInfo = $request->user();
        $comId = $storeInfo->id;
        $orderNo = $request->input('keywords','');
        $limit = $request->input('limit',20);
        $cardList = Cards::getList()->pluck('short_title','id');
        $type = (int)$request->input('type',0);
        if(!$comId){
            $list = array();
        }else{
            $created_at = $request->input('created_at',[]);
        
            $created_at = array_filter((array)$created_at, function ($val) {
                return $val !== '';
            });
            $list = StoreBalanceDetail::when($orderNo,function($query,$orderNo){
                return $query->where('order_sn','like',"%{$orderNo}%");
            })
            ->when($type,function($query,$type){
                return $query->where('type',$type);
            })
            ->when($created_at,function($query,$created_at){
                if(!isset($created_at['start'])){
                    return $query->where('created_at','<=',$created_at['end']);
                }
                if(!isset($created_at['end'])){
                    return $query->where('created_at','>=',$created_at['start']);
                }
                return $query->whereBetween('created_at',$created_at);
            })
            ->where('com_id',$comId)->latest()->paginate($limit);
        }
        return view('stores.account',compact('storeInfo','list','cardList'));
    }
    //
    public function settle(Request $request){
        $storeInfo = $request->user();
        $comId = $storeInfo->id;
        $limit = $request->input('limit',20);
        $keywords = $request->input('keywords','');
        if(!$comId){
            $list = array();
        }else{
            $created_at = $request->input('created_at',[]);        
            $created_at = array_filter((array)$created_at, function ($val) {
                return  !empty($val);
            });            
            $list = SettleList::when($created_at,function($query,$created_at){
                if(!isset($created_at['start'])){
                    return $query->where('created_at','<=',$created_at['end']);
                }
                if(!isset($created_at['end'])){
                    return $query->where('created_at','>=',$created_at['start']);
                }
                return $query->whereBetween('created_at',$created_at);
            })
            ->when($keywords,function($query,$keywords){
                return $query->whereRaw('settle_sn like ?',"%$keywords%");
            })
            ->where('com_id',$comId)->latest()->paginate($limit);
        }
        return view('stores.settle',compact('storeInfo','list'));
    }

    //余额提现
    public function withdraw(Request $request){
        $storeInfo = $request->user();
        return view('stores.withdraw',$storeInfo);
    }
    //提现处理
    public function dowithdraw(Request $request){
        $storeInfo = $request->user();
        
        $alipay_account = $request->input('alipay_account','');
        $alipay_name = $request->input('alipay_name','');
        $money = round($request->input('money',0),2);
        $return = [ 'status'=> 'false', 'message'=>'提现申请失败'];
        if(empty($alipay_account)){
            $return['message']='请填写提现支付宝账号';
            return redirect('/stores/withdraw')->with($return);
        }
        if(empty($alipay_name)){
            $return['message']='请填写提现支付宝账号姓名';
            return redirect('/stores/withdraw')->with($return);
        }
        if(empty($money) || $money < 1){
            $return['message']='提现金额必须大于1元';
            return redirect('/stores/withdraw')->with($return);
        }
        if($money > $storeInfo->balance){
            $return['message']='可提现余额不足';
            return redirect('/stores/withdraw')->with($return);
        }
        $storeInfo->alipay_account = $alipay_account;
        $storeInfo->alipay_name = $alipay_name;
        $storeInfo->save();
        $hasdraw = RsWithDraw::where('store_id',$storeInfo->id)->where('state','!=',2)->whereTime('created_at',date('Y-m-d'))->count();
        if($hasdraw){
            $return['message']='每天只能提现一次';
            return redirect('/stores/withdraw')->with($return);
        }
        try {
            RsWithDraw::addDraw($storeInfo,2,$money);
        } catch (\Throwable $th) {
            return redirect('/stores/withdraw')->with($return);
        }
        $return = [ 'status'=> true, 'message'=>'提现申请已提交' ];
        return redirect('/stores/withdraw')->with($return);
    }
    //提现记录
    public function withdrawList(Request $request){
        $storeInfo = $request->user();
        $created_at = $request->input('created_at',[]);
        
        $created_at = array_filter((array)$created_at, function ($val) {
            return $val !== '';
        });
        // dd($created_at);
        $list = $list = RsWithDraw::select('id','store_id','title','money','draw_account','account_name','created_at','state')
                ->when($created_at,function($query,$created_at){
                    if(!isset($created_at['start'])){
                        return $query->where('created_at','<=',$created_at['end']);
                    }
                    if(!isset($created_at['end'])){
                        return $query->where('created_at','>=',$created_at['start']);
                    }
                    return $query->whereBetween('created_at',$created_at);
                })
                ->where('store_id',$storeInfo->id)
                ->orderBy('created_at','desc')
                ->paginate(10);
        return view('stores.withdraw-list',compact('list'));
    }

    /**
     * 影旅卡列表
     *
     * @return void
     */
    public function card(Request $request){
        $store_name = $request->user()->store_name;
        $list = Cards::getList();
        $priceList = CardPrice::getCardPrice($request->user()->id);

        return view('stores.card',compact('list','store_name','priceList'));
    }

    public function card_handle(Request $request){
        $saleprice = round($request->input('saleprice',0),2);
        $cardId = $request->input('id',0);
        
        $cardInfo = Cards::where('id',$cardId)->first();
        if($saleprice && $saleprice < $cardInfo->price){
            //价格不能小于成本价
            // return response()->json([
            //     'status'    => false,
            //     'message'   => '商城价格不能小于成本价',
            //     'display'   => [],
            // ]);
        }
        CardPrice::editCardPrice($request->user()->id,$cardInfo,$saleprice);
        return response()->json([
            'status'    => true,
            'message'   => '价格已设置',
            'display'   => [],
        ]);
    }

    /**
     * 消费明细
     *
     * @param Request $request
     * @param integer $userId
     * @return void
     */
    public function card_detail(Request $request,int $userId){
        $comId = $request->user()->id;
        $cardList = Cards::getList()->pluck('short_title','id');
        $userInfo = TicketUser::select(['id','nickname','mobile'])->where('id',$userId)->first();
        $list = array();
        if(!empty($userInfo)){
            $nickname = $userInfo->nickname;
            $mobile = $userInfo->mobile;
            $mobile = str_replace(substr($mobile,3,4),'****',$mobile);

            $list = WalletDetail::getStoreWalletDetail($comId,$userId);
        }
        return view('stores.card-detail',compact('list','mobile','nickname','cardList'));
    }

    /**
     * 影旅卡订单
     *
     * @return void
     */
    public function order(Request $request){
        $limit = (int)$request->input('limit',20);
        $orderNo = $request->input('keywords','');
        $cardId = (int)$request->input('card_id',0);
        $comId = $request->user()->id;
        $cardList = Cards::getList()->pluck('short_title','id');
        $list = CardOrder::when($cardId,function($query,$cardId){
            return $query->where('card_id',$cardId);
        })->when($orderNo,function($query,$orderNo){
            return $query->where('order_sn','like',"%{$orderNo}%");
        })->where('com_id',$comId)->latest()->paginate($limit);
        return view('stores.order',compact('list','cardList'));
    }

    /**
     * 会员管理
     *
     * @return void
     */
    public function member(Request $request){
        $comId = $request->user()->id;
        $limit = (int)$request->input('limit',20);
        $mobile = trim($request->input('keywords',''));        
        if(!$comId){
            $list = array();
        }else{
            $list = TicketUser::when($mobile,function($query,$mobile){
                return $query->where('mobile',$mobile);
            })->where('com_id',$comId)->latest()->paginate($limit);
        }
        
        return view('stores.member',compact('list'));
    }


}
