<div class="form-group">
    <label><?php echo e($label, false); ?></label>
    <input <?php echo $attributes; ?>>
    <?php echo $__env->make('admin::actions.form.help-block', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/admin/actions/form/text.blade.php ENDPATH**/ ?>