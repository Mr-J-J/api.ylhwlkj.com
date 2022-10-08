<?php
namespace App\Support\Api;

use App\Support\Api\ApiInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * 聚福宝Api
 */
class MApi implements ApiInterface
{
    

    // const BASEURI = 'http://sandbox-c.jufubao.cn';
    // const ACCOUNT_ID = 3;
    // const SECRET = 'qwcf123456'; // 553323e710d7090371d08988375aba0b
    // const DES_SECRET = '123456789';

    const BASEURI = 'https://c.jufubao.cn';
    const ACCOUNT_ID = 187118;
    const SECRET = 'oUTlHsu8yGg0EiBzrfk7Wndxb5jYt6aP'; // 553323e710d7090371d08988375aba0b
    const DES_SECRET = 'yh7TDoearctjpxiK5RMmlB9q';

    
//     卡号：681001000000007 
// 密码：924843973087    （100元）

    static $instance = null;

    private function __construct()
    {
        
    }

    static function getInstance(){
        if(is_null(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone()
    {
        
    }

    /**
     * 获取城市列表
     *
     * @return array
     */
    public function getCityList()
    {
        $uri = self::makeUri('/api/film/city/list');
        $query = array(
            'token'=> self::getToken()
        );
        $result = self::get($uri,$query);
        return $result;
    }

    /**
     * 获取热映影片
     *
     * @return array
     */
    public function getHotFilmList($cityId)
    {
        $uri = self::makeUri('/api/film/hot/list');
        $query = array(
            'token'=> self::getToken(),
            'city_code'=>intval($cityId)
        );
        $result = self::get($uri,$query);
        return $result;
    }
    
    /**
     * 获取即将上映
     *
     * @return array
     */
    public function getRightNowFilmList($cityId)
    {
        $uri = self::makeUri('/api/film/rightnow/list');
        $query = array(
            'token'=> self::getToken(),
            'city_code'=>intval($cityId)
        );
        $result = self::get($uri,$query);
        return $result;
    }
   
    /**
     * 获得影院列表 
     *
     * @return array
     */
    public function getCinemaList($cityId,$last_key='')
    {
        $uri = self::makeUri('/api/film/cinema/list');
        $query = array(
            'token'=> self::getToken(),
            'city_code'=>intval($cityId),
            'last_key'=>$last_key,
            'page_size'=>20,
            'region_code'=>''
        );
        $result = self::get($uri,$query);
        return $result;
    }

    /**
     * 查询影院下的电影
     *
     * @return array
     */
    public function getFilmOfCinema($cinema_id){
        $uri = self::makeUri('/api/film/list');
        $query = array(
            'token'=> self::getToken(),
            'cinema_id'=>intval($cinema_id),
        );
        $result = self::get($uri,$query);
        return $result;
    }
    
    /**
     * 获取电影排期信息
     *
     * @return array
     */
    public function getPaiqiList($cinema_id,$film_id='',$last_key=''){
        $uri = self::makeUri('/api/film/paiqi/list');
        $query = array(
            'token'=> self::getToken(),
            'cinema_id'=>intval($cinema_id),
            'last_key'=>$last_key,
        );
        if($film_id != ''){
            $query['film_id'] = $film_id;
        }
        $result = self::get($uri,$query);
        return $result;
    }

    /**
     * 获取座位图 
     *
     * @return array
     */
    public function getSeatList($paiqi_id){
        $uri = self::makeUri('/api/film/seat/list');
        $query = array(
            'token'=> self::getToken(),
            'paiqi_id'=>$paiqi_id,
        );
        $result = self::get($uri,$query);
        
        return $result;
}

    /**
     * 锁座 /下单
     *
     * @return mixed
     */
    public function lockSeat($account_id,$seat_names = '',$paiqi_id,$seat_ids,$phone_num,$seat_areas){
        $uri = self::makeUri('/api/film/seat/lock');
        $data = array(
            'token'=> self::getToken(),
            'account_id'=>intval($account_id),
            'seat_names'=>$seat_names,
            'paiqi_id'=>$paiqi_id,
            'seat_ids'=>$seat_ids,
            'phone_num'=>$phone_num,
            'seat_areas'=>$seat_areas
        );

        $result = self::post($uri,$data);
        return $result;
    }

    /**
     * 取消锁座/刷新锁座
     *
     * @return mixed
     */
    public function unLockSeat($order_id){
        $uri = self::makeUri('/api/film/seat/refresh');
        $data = array(
            'token'=> self::getToken(),
            'order_id'=>$order_id,
        );        
        $result = self::post($uri,$data);
        return $result;
    }


    /**
     * 下单
     *
     * @return void
     */
    // public function addOrder();

    /**
     * 接口订单支付
     *
     * @return void
     */
    public function payOrder($order_id,$channel_order_id){
        $uri = self::makeUri('/api/film/notify');
        $data = array(
            'token'=> self::getToken(),
            'order_id'=>$order_id,
            'channel_order_id'=>$channel_order_id
        );        
        $result = self::post($uri,$data);
        return $result;
    }

    /**
     * 接口订单查询
     *
     * @return void
     */
    public function getOrder($order_id)
    {
        // "channel_order_id": "",
        // "order_id": "506614354417282241",
        // "phone_num": "15303122197",
        // "count": 2,
        // "price": 9600,
        // "placed_time": 1658893474,
        // "state": 2003, //订单状态（失败订单：2002， 出票中：2001，已出票：2000，已取消：2003）
        // "film_id": "1308",
        // "paiqi_id": "F3642573495",
        // "seat_names": "",
        // "ticket_code": ""
        $uri = self::makeUri('/api/film/ticket/get');
        $query = array(
            'token'=> self::getToken(),
            'order_id'=>$order_id,
        );
        $result = self::get($uri,$query);
        if(!empty($result['ticket_code'])){
            $result = array_merge($result,$this->decrypt_code($result['ticket_code']));
        }
        return $result;
    }
    
    private function decrypt_code($encrypt_str)
    {
        $key = str_pad(self::DES_SECRET, 24, '0');
        $data    = base64_decode($encrypt_str);
        $decData = openssl_decrypt($data,'des-ede3',$key,OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
        $text = $decData;
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) {
            return false;
        };
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        };
        $decData = substr($text, 0, -1 * $pad);
        $result = explode('|',$decData);
        $ticketcode = '';
        $validcode= '';
        if(!empty($result[0])){
            $ticketcode = substr($result[0],strpos($result[0],':')+1);
        }
        if(!empty($result[1])){
            $validcode = substr($result[1],strpos($result[1],':')+1);
        }
        return compact('ticketcode','validcode');
    }        
    
    /**
     *  取token
     *
     * @param boolean $force 强制更新
     * @return void
     */
    static function getToken($force=true){
        $cache = Cache::get('mapi_token',false);        
        if(!$cache || $force){
            return self::getApiToken();
        };
        return $cache;
    }
    
    /**
     * 
     *
     * @return void
     */
    private static function getApiToken(){
        $request = new Client;
        $uri = self::makeUri('/oauth/access-token');
        $data =  array(
            'account_id'=>self::ACCOUNT_ID,
            'secret'=> md5(self::SECRET)
        );
        $result = self::post($uri,$data);          
        Cache::put('mapi_token',$result['access_token'],3200);
        return $result['access_token'];
    }

    // /**
    //  * 缓存数据
    //  *
    //  * @param [type] $key
    //  * @param [type] $value
    //  * @param [type] $time
    //  * @return void
    //  */
    // private static function setDataCache($key,$value,$time=''){
    //     if($time){
    //        return Cache::put($key,$value,$time);
    //     }
    //     return Cache::put($key,$value);
    // }

    // private static function getCacheData($key){
    //     return Cache::get($key,false);
    // }

    /**
     * get请求
     *
     * @param [type] $uri
     * @param array $query
     * @return void
     */
    private static function get($uri,$query = []){
        $request = new Client;  
        $result = [];
        
        try {
            $response = $request->get($uri,[
                'query'=>$query,
                'timeout' => 30,
                'connect_timeout' => 30,
                'read_timeout' => 30
            ]);
            // dd($response);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            Log::debug($message);
            return [];
            // throw $e;
            
        }
        $result = json_decode((string)$response->getBody(),true);
        if($result['code'] != 200){
            return [];
        }
        return $result['result'];
    }

    /**
     * post请求
     *
     * @param [type] $uri
     * @param array $data
     * @return void
     */
    private static function post($uri,$data=[]){
        $request = new Client;                
        try {
            $response = $request->post($uri,[
                'form_params'=>$data,
                'timeout' => 30,
                'connect_timeout' => 30,
                'read_timeout' => 30
            ]);
            $result = json_decode((string)$response->getBody(),true);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            Log::debug($message);
            return [];
        }

        if($result['code'] != 200){
            return [];
        }
        return $result['result'];
    }

    private static function makeUri($uri){
        return self::BASEURI . $uri;
    }
    private static function setToken($token){
        Cache::put('mapi_token',$token,3600);        
    }

    /**
     * 签名
     *
     * @param [type] $params
     * @return void
     */
    static function makeSign($params){
        sort($params);
        $originalStr = http_build_query($params) . self::SECRET;
        return md5($originalStr);
    }
}