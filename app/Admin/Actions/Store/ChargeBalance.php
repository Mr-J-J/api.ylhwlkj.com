<?php

namespace App\Admin\Actions\Store;

use Encore\Admin\Actions\RowAction;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ChargeBalance extends RowAction
{
    public $name = '充值预存款';

    public function handle(Model $model,Request $request)
    {
        $action = $type = (int)$request->input('type',1);
        $money = round($request->input('money',0));
        $remark = $request->input('remark','');
        $title = '后台充值';
        if($type == 2)
        {
            if($money > $model->balance)
            {
                return $this->response()->error('预存款余额不足')->refresh();
            }
            $action = -1;
            $title = '后台扣除';
        }
        $money = $money * $action;
        
        $balance = $model->balance + $money;
        $model->balance = $balance > 0 ?$balance : 0;
        $model->save();
        \App\Models\Store\StoreBalanceDetail::add($model,$money,$type,$title,$remark);
        return $this->response()->success('充值成功')->refresh();
    }

    public function form(Model $model)
    {
        $this->text('商家名称')->value(function()use ($model){
            return $model->store_name;
        })->readonly();
        $this->radio('type', '操作类型')->options([1=>'充值',2=>'扣除'])->default(1); 
        $this->text('money','金额')->required();
        $this->text('remark','操作备注')->required();
    }    
    public function render()
    {
        if ($href = $this->href()) {
            return "<a href='{$href}' class='btn btn-twitter btn-xs {$this->getElementClass()}'>{$this->name()}</a>";
        }

        $this->addScript();

        $attributes = $this->formatAttributes();

        return sprintf(
            "<a data-_key='%s' href='javascript:void(0);' class='btn btn-twitter btn-xs  %s' {$attributes}>%s</a>",
            $this->getKey(),
            $this->getElementClass(),
            $this->asColumn ? $this->display($this->row($this->column->getName())) : $this->name()
        );
        
    }

}