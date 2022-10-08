<?php $__env->startSection('content'); ?>
<div class="page-header">会员消费明细</div>
<div class="row">
    <div class="col">
        <span class="text-muted">昵称：</span><span><?php echo e($nickname, false); ?></span>  <span class="text-muted ml-3">手机号：</span><span><?php echo e($mobile, false); ?></span>
    </div>    
</div>
<div class="row">
    <div class="table-list table-responsive m-4">
        <table class="table">
            <thead>
                <tr>
                  <th scope="col">订单号</th>
                  <th scope="col">下单时间</th>
                  <th scope="col">影旅卡类型</th>
                  <th scope="col">消费金额</th>
                </tr>
              </thead>
              <tbody>
                  <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php echo e($detail->order_no, false); ?>

                        </td>
                        <td><?php echo e($detail->created_at, false); ?></td>
                        <td>
                            <?php if(!empty($cardList[$detail->card_id])): ?>
                                <?php echo e($cardList[$detail->card_id], false); ?>

                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo e($detail->money, false); ?>

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/stores/card-detail.blade.php ENDPATH**/ ?>