<?php $__env->startSection('content'); ?>
<style>
    .account-info{font-size:1rem;align-items: center}
    .balance{font-size:2rem;}
    .money{color:#FD6B31;font-weight:600}
</style>
<div class="page-header">账户累计收入</div>
<div class="row">
   <div class="col row justify-content-between account-info">
       <div class="col text-muted"><span class="balance mr-1 money"><?php echo e($storeInfo->total_money, false); ?></span>元</div>
       <div class="col text-right text-muted">
            <span class="label">待结算：</span><span class="money"><?php echo e($storeInfo->balance, false); ?></span>元
            <span class="label ml-4">已结算：</span><span class="money"><?php echo e($storeInfo->settle_money, false); ?></span>元
       </div>
   </div>
</div>
<div class="row mt-2">
    <div class="col">
        <ul class="page-tab nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link active" href="/stores/account"  >分成明细</a>
            </li>
        </ul>                    
    </div>
</div>
<div class="row">
    <div class="col">
        <form action="/stores/account" method="get" pjax-container class="row form-inline justify-content-end">
            <div class="form-group mr-3">
                <?php
                    $type = (int)request('type',0);
                ?>
                <select name="type"  class="form-control">
                    <option value="">按类别筛选</option>
                    <option value="1" <?php if($type == 1): ?> selected <?php endif; ?>>影旅卡订单分成</option>
                    <option value="2"  <?php if($type == 2): ?> selected <?php endif; ?>>电影票订单分成</option>
                </select>
            </div>
            <div class="form-group mr-3">
                <input type="text" class="form-control" value="<?php echo e(request('keywords',''), false); ?>" name="keywords" placeholder="输入订单号搜索">
            </div>
            <div class="form-group mr-4">
                <button type="submit" class="btn btn-primary search-btn">搜索</button>
                <a href="/<?php echo e(request()->path(), false); ?>" class="btn btn-light reset-btn">重置</a>
            </div>
        </form>
    </div>    
</div>

<div class="row">
    <div class="table-list table-responsive m-4">
        <table class="table">
            <thead>
                <tr>
                  <th scope="col">描述</th>
                  <th scope="col">订单号</th>
                  <th scope="col">返佣金额/余额</th>
                  <th scope="col">结算状态</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">卡类型</th>
                </tr>
              </thead>
              <tbody>
                  <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php echo e($detail->remark, false); ?>

                        </td>
                        <td>
                            <?php echo e($detail->order_sn, false); ?>

                        </td>
                        <td><?php echo e($detail->money, false); ?> / <?php echo e($detail->after_balance, false); ?></td>
                        <td>
                            <?php if($detail->state): ?>
                                <span class="badge badge-success">已结算</span>
                            <?php else: ?>
                                <span class="badge badge-light">待结算</span>
                            <?php endif; ?>                            
                        </td>
                        <td>
                            <?php echo e($detail->created_at, false); ?>

                        </td>
                        <td>
                            <?php if(!empty($cardList[$detail->card_id])): ?>
                                <?php echo e($cardList[$detail->card_id], false); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
              </tbody>
          </table>
    </div>
</div>
<div class="row justify-content-center">
    <?php echo e($list->links(), false); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/stores/account.blade.php ENDPATH**/ ?>