<style>
    .item{
        margin-bottom: 20px;
        display:flex;
    }
    .item-label{
        margin-right:10px;
        color: #777
    }
    .item-body{
        color: #000
    }
</style>
<div class="box box-info">
    <div class="box-header with-border">订单竞价</div>
    <div class="box-body">
        <?php if($list): ?>
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 item">
                <label class="item-label"><?php echo e($row->offer_amount, false); ?></label>
                <div class="item-body"><?php echo e($row->store_id, false); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            暂无报价
        <?php endif; ?>
    </div>
</div><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/custom/offer/offer-list.blade.php ENDPATH**/ ?>