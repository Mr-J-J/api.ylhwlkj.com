<?php
namespace App\Support\Api;

interface ApiInterface
{
    static function getInstance();
    /**
     * 获取城市列表
     *
     * @return array
     */
    public function getCityList(); 

    /**
     * 获取热映影片
     *
     * @return array
     */
    public function getHotFilmList($cityId);
    
    /**
     * 获取即将上映
     *
     * @return array
     */
    public function getRightNowFilmList($cityId);
   
    /**
     * 获得影院列表
     *
     * @return array
     */
    public function getCinemaList($cityId,$last_key='');

    /**
     * 查询影院下的电影
     *
     * @return array
     */
    public function getFilmOfCinema($cinema_id);
    
    /**
     * 获取电影排期信息
     *
     * @return array
     */
    public function getPaiqiList($cinema_id,$film_id='',$last_key='');

    /**
     * 获取座位图
     *
     * @return array
     */
    public function getSeatList($paiqi_id);

    /**
     * 锁座 /下单
     *
     * @return mixed
     */
    public function lockSeat($account_id,$seat_names = '',$paiqi_id,$seat_ids,$phone_num,$seat_areas);

    /**
     * 取消锁座/刷新锁座
     *
     * @return mixed
     */
    public function unLockSeat($order_id);


    /**
     * 下单
     *
     * @return void
     */
    // public function addOrder();

    /**
     * 接口订单支付
     *
     * @param [type] $order_id  接口订单号
     * @param [type] $remote_order_id 我方订单号
     * @return void
     */
    public function payOrder($order_id,$remote_order_id);

    /**
     * 接口订单查询
     *
     * @param [type] $order_id 接口订单号
     * @return void
     */
    public function getOrder($order_id);

}