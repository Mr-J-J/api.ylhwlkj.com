
<div class="row">
    <div class="<?php echo e($viewClass['label'], false); ?>"><h4 class="pull-right"><?php echo e($label, false); ?></h4></div>
    <div class="<?php echo e($viewClass['field'], false); ?>"></div>
</div>

<hr style="margin-top: 0px;">

<div id="embed-<?php echo e($column, false); ?>" class="embed-<?php echo e($column, false); ?>">

    <div class="embed-<?php echo e($column, false); ?>-forms">

        <div class="embed-<?php echo e($column, false); ?>-form fields-group">

            <?php $__currentLoopData = $form->fields(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $field->render(); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>
</div>

<hr style="margin-top: 0px;"><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/admin/form/embeds.blade.php ENDPATH**/ ?>