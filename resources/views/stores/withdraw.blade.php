@extends('layouts.app')

@section('content')
<style>
    .col-form-label{text-align: right}
    .store_logo{
        width: 75px;
        height: 75px;
        border-radius: 100%;
    }
</style>

<div class="page-header">账户余额提现</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div>
            <ul class="page-tab nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="/stores/settle" >结款明细</a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="/stores/withdraw" >申请提现</a>
                  </li>
                  <li class="nav-item" role="presentation">
                      <a class="nav-link" href="/stores/withdrawList">提现记录</a>
                  </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <form action="/stores/dowithdraw" method="post">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">分销商：</label>
                                <div class="col-sm-8">                                
                                    <div class="form-control-plaintext">{{$store_name}}({{$id}})</div>
                                </div>
                            </div>                                               
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">可提现金额</label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext">{{$balance}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">支付宝姓名：</label>
                                <div class="col-sm-8">
                                    <input type="text" name="alipay_name" class="form-control"  placeholder="请输入支付宝姓名" value="{{$alipay_name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">支付宝账号：</label>
                                <div class="col-sm-8">
                                    <input type="text"  name="alipay_account"  class="form-control" placeholder="请输入支付宝账号" value="{{$alipay_account}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">提现金额：</label>
                                <div class="col-sm-8">
                                    <input type="number"  name="money"  class="form-control" placeholder="请输入提现金额" value="{{$balance}}">
                                </div>
                            </div>
                            
                            <div class="form-group row" style="margin-top: 30px">
                                <label  class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext">限每天提现一次，提现金额大于1元。</div>
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top: 30px">
                                <label  class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" class="btn btn-md btn-primary">确认提现</button>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
              </div>
        </div>
    </div>
</div>
@endsection
