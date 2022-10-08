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
    <div class="box-header with-border">接单规则</div>
    <div class="box-body">
        <?php $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 item">
            <label class="item-label"><?php echo e($row['name'], false); ?></label>
            <div class="item-body"><?php echo e($row['value'], false); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/custom/offer/offer-rules.blade.php ENDPATH**/ ?>