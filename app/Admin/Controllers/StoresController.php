<?php

namespace App\Admin\Controllers;

use App\Models\Store;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
// use Illuminate\Support\Facades\DB;

use App\Admin\Actions\Store\ChargeBalance;
use Encore\Admin\Controllers\AdminController;

class StoresController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '代理商管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Store());
        $grid->model()->where('level',1)->latest();
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('store_name','代理商名称');
            $filter->like('phone','联系方式');
            $filter->like('remark','备注');
        });
        $grid->model()->latest();
        $grid->column('store_name', __('代理商'));
        $grid->column('phone', __('联系方式'));
        $grid->column('account', __('平台登陆账号'));              
        $grid->column('balance', __('预存款(元)'));
        $grid->column('balance_detail','查看明细')->display(function(){
            return "<a href='/admin/stores-balance-detail?store_id={$this->id}'>查看变动明细</a>";
        });
        $grid->column('created_at', __('添加时间'));
        $grid->actions(function($action){
            $action->disableDelete();
            $action->disableView();
            $action->add(new ChargeBalance());
        });

        return $grid;
    }

    

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Store::findOrFail($id));

        $show->field('id', __('Id'));        
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Store());
        $form->display('id','ID_No');
        $form->text('store_name','代理商名称');
        $form->text('phone','手机号');
        $form->text('account','平台登录账号')->rules('required|min:6',['min'=>'登录账号长度最少6位'])->help('登录账号最少6位字符长度');
        $form->password('password', '平台登录密码')->rules('confirmed|required');      
        $form->hidden('level')->value(1);
        $form->text('remark','备注');
        $form->ignore(['repassword']);
        $form->saving(function($form){
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = \Illuminate\Support\Facades\Hash::make($form->password);
            }
            // if($form->repassword){
            //     if(strlen($form->repassword) < 6){
            //         $error = new \Illuminate\Support\MessageBag([
            //             'title'   => '密码长度最少6位',
            //             'message' => '请重新设置密码',
            //         ]);
            //         return back()->with(compact('error'));
            //     }
            //     $form->password = \Illuminate\Support\Facades\Hash::make($form->password);
            // }
            if($form->account){
                if(!(new \App\Observers\UserObserver)->checkUserName($form->account,$form->model()->id)){
                    $error = new \Illuminate\Support\MessageBag([
                        'title'   => '登录账号不可用',
                        'message' => '请更换后重新提交',
                    ]);
                    return back()->with(compact('error'));
                }

            }
        })  ;
        return $form;
    }
}
