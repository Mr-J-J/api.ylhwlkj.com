@extends('layouts.app')

@section('content')
<style>
    .data-item .data{color:#FD6B31;font-size:24px;}
    .total{margin-top: 30px}
    .total >p{margin-bottom: 5px;font-weight: 600;}
    .total .data{color:#FD6B31;}


</style>
<div class="page-header">首页</div>
<div class="row justify-content-center">
    <div class="col-md-12">        
        <div>
            <ul class="page-tab nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">今日数据</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">昨日数据</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">本月数据</a>
                </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active row" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="d-flex col-md-6">
                        <div class="data-item col">
                            <div>分销佣金</div>
                            <div class="data">{{$today['commision']}}元</div>
                        </div>
                        <div class="data-item col">
                            <div>订单</div>
                            <div class="data">{{$today['order']}}</div>
                        </div>
                        <div class="data-item col">
                            <div>新增用户</div>
                            <div class="data">{{$today['member']}}</div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade row" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="d-flex col-md-6">
                        <div class="data-item col">
                            <div>分销佣金</div>
                            <div class="data">{{$yestday['commision']}}元</div>
                        </div>
                        <div class="data-item col">
                            <div>订单</div>
                            <div class="data">{{$yestday['order']}}</div>
                        </div>
                        <div class="data-item col">
                            <div>新增用户</div>
                            <div class="data">{{$yestday['member']}}</div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade row" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="d-flex col-md-6">
                        <div class="data-item col">
                            <div>分销佣金</div>
                            <div class="data">{{$month['commision']}}元</div>
                        </div>
                        <div class="data-item col">
                            <div>订单</div>
                            <div class="data">{{$month['order']}}</div>
                        </div>
                        <div class="data-item col">
                            <div>新增用户</div>
                            <div class="data">{{$month['member']}}</div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
        
        <div class="total">
            <p>分销总佣金：<span class="data">{{$store->total_money}}</span>元</p>
            <p>已结金额：<span class="data">{{$store->settle_money}}</span>元</p>
            <p>账户余额：<span class="data">{{$store->balance}}</span>元</p>
        </div>
        <div class="page-header">公众号推广小程序  <span>示例</span></div>
        <div>
            <p>第一步：公众号关联小程序</p>
            <div class="tips-item">①进入公众号 → 广告与服务 → 小程序管理 → 关联小程序 (小程序APPID:<span class="copy-text">{{config('wechat.mini_program.default1.app_id')}}</span>)  <button type="button"  class="copy-btn btn btn-sm btn-outline-info">复制</button></div>
            <p>第二步: 配置公众号自定义菜单</p>
            <div class="tips-item">①配置菜单内容为“跳转到小程序”。</div>
            <div class="tips-item">②跳转到小程序首页路径：<span class="copy-text">pages/index/index?com_id={{$store->id}}</span>  <button type="button"   class="copy-btn btn btn-sm btn-outline-info">复制</button></div>
        </div>
    </div>
</div>


<script>
    
    var clipboard = new Clipboard('.copy-btn',{
        text:function(trigger){
            _text = $(trigger).prev('.copy-text').text()
            toast('toast-success','内容已复制')
            return _text;
        }        
    });
    clipboard.on('success', function(e) {
        e.clearSelection();
    });
</script>
@endsection
