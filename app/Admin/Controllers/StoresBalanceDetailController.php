<?php

namespace App\Admin\Controllers;

use App\Models\Store\StoreBalanceDetail;
use App\Models\Store;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Controllers\AdminController;

class StoresBalanceDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '预存款明细';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new StoreBalanceDetail());
        $grid->model()->latest();
        $storeId = (int)request('store_id',0);
        if($storeId)
        {
            $grid->model()->where('store_id',$storeId);
        }
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('stores.store_name','代理商名称');
            $filter->like('phone','联系方式');
            $filter->like('remark','备注');
            $filter->between('created_at','时间')->date();
        });
        $grid->column('order_sn', __('流水号'));
        $grid->column('stores.store_name', __('代理商'));
        $grid->column('money', __('变动金额'));
        $grid->column('type', __('变动类型'))->using([1=>'收入',2=>'支出'])->label([1=>'success',2=>'danger']);
        $grid->column('title', __('备注'));
        $grid->column('remark', __('说明'));
        $grid->column('created_at', __('变动时间'));
        
        $grid->disableActions();
        $grid->disablebatchActions();
        $grid->disableCreateButton();

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
        $show = new Show(StoreBalanceDetail::findOrFail($id));

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
        $form = new Form(new StoreBalanceDetail());
        $form->display('id','ID_No');
       
        
        return $form;
    }
}
