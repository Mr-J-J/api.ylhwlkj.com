<p>用户手机：<?php echo e($phone, false); ?> (<?php echo e($user_type==1?'商家':'用户', false); ?>)</p>
<p>反馈类型：<?php echo e($type, false); ?></p>
<p>反馈时间：<?php echo e($created_at, false); ?></p>
<p>反馈内容：<?php echo e($content, false); ?></p>
<p>反馈图片：
    <div class="row">
        <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-3">
            <a href="<?php echo e($img, false); ?>" target="_blank"><img src="<?php echo e($img, false); ?>"></a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</p><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/custom/admin/suggestion-show.blade.php ENDPATH**/ ?>