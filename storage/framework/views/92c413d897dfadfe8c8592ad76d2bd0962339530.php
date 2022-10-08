<div class="form-group">
    <label><?php echo e($label, false); ?></label>
    <textarea name="<?php echo e($name, false); ?>" class="form-control <?php echo e($class, false); ?>" rows="<?php echo e($rows, false); ?>" placeholder="<?php echo e($placeholder, false); ?>" <?php echo $attributes; ?> ><?php echo e(old($column, $value), false); ?></textarea>
    <?php echo $__env->make('admin::actions.form.help-block', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/admin/actions/form/textarea.blade.php ENDPATH**/ ?>