<?php $__env->startSection('content'); ?>
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
                        <?php echo csrf_field(); ?>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">分销商：</label>
                                <div class="col-sm-8">                                
                                    <div class="form-control-plaintext"><?php echo e($store_name, false); ?>(<?php echo e($id, false); ?>)</div>
                                </div>
                            </div>                                               
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">可提现金额</label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext"><?php echo e($balance, false); ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">支付宝姓名：</label>
                                <div class="col-sm-8">
                                    <input type="text" name="alipay_name" class="form-control"  placeholder="请输入支付宝姓名" value="<?php echo e($alipay_name, false); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">支付宝账号：</label>
                                <div class="col-sm-8">
                                    <input type="text"  name="alipay_account"  class="form-control" placeholder="请输入支付宝账号" value="<?php echo e($alipay_account, false); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">提现金额：</label>
                                <div class="col-sm-8">
                                    <input type="number"  name="money"  class="form-control" placeholder="请输入提现金额" value="<?php echo e($balance, false); ?>">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/stores/withdraw.blade.php ENDPATH**/ ?>