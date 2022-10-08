<?php

namespace App\Observers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;
/**
 * 用于后台多角色账号建立时的事件监听
 * 
 * 同步添加、删除、更新管理员信息
 */
class UserObserver
{

    //用户类型=>管理员角色
    static $roles = [
        1=>11 ,//代理商
        2=> 12,//分销商
    ];
    static $disableName = ['admin','mananger'];
    /**
     * Handle the store "created" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function created(Store $store)
    {        
        $this->bindAdminUser($store);
    }
    /**
     * 检查登录名是否可用
     *
     * @param Store $store
     * @return void
     */
    function checkUserName($username,$exceptId){
        $AdminClass = config('admin.database.users_model');
        if(stripos($username,'admin') > -1){
            return false;
        }
        $adminUser = $AdminClass::where(['username'=>$username])
                        ->when($exceptId,function($query,$exceptId){
                            return $query->where('user_id','!=',$exceptId);
                        })
                        ->first();
        if($adminUser || in_array($username,self::$disableName)){
            return false;
        }
        return true;
    }
    /**
     * 更新添加管理员
     *
     * @param Store $store
     * @return void
     */
    function bindAdminUser(Store $store){
        if(!Auth::guard('admin')->user()->isAdministrator()){
            return false;
        }
        $AdminClass = config('admin.database.users_model');
        $adminUser = $AdminClass::firstOrNew(['username'=>$store->account]);
        $adminUser->name = $store->store_name;
        $adminUser->password = $store->password;
        $adminUser->avatar = $store->avatar;
        $adminUser->user_id = $store->id;
        $adminUser->save();
        $roleId = self::$roles[$store->level] ?? 0;
        if($roleId){
            $adminUser->roles()->detach();
            $adminUser->roles()->attach(explode(',',$roleId));
        }
    }

    /**
     * Handle the store "updated" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function updated(Store $store)
    {
        $this->bindAdminUser($store);
    }

    /**
     * Handle the store "deleted" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function deleted(Store $store)
    {
        $AdminClass = config('admin.database.users_model');
        $adminUser = $AdminClass::where('username',$store->account)->where('user_id',$store->id)->first();
        if(!empty($adminUser)){
            $adminUser->delete();
        }
    }

    /**
     * Handle the store "restored" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function restored(Store $store)
    {
        //
    }

    /**
     * Handle the store "force deleted" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function forceDeleted(Store $store)
    {
        //
    }
}
