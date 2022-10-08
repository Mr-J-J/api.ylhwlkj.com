<?php $__env->startSection('content'); ?>
<div class="page-header">用户管理</div>
<div class="row">
    <div class="col">
        <form action="/stores/member" method="get" pjax-container class="row  justify-content-end">
            <div class="col-md-3 col-xs-6">
                <input type="text" class="form-control" name="keywords" placeholder="输入手机号搜索" id="">
            </div>
            <div class="col-md-3 col-xs-4">
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
                  <th scope="col">昵称</th>
                  <th scope="col">手机号</th>
                  <th scope="col">消费金额</th>
                  <th scope="col">注册时间</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                  <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php echo e($member->nickname, false); ?>

                        </td>
                        <td><?php echo e(str_replace(substr($member->mobile,3,4),'****',$member->mobile), false); ?></td>
                        <td>
                            <?php echo e($member->cash_money, false); ?>

                        </td>
                        <td>
                            <?php echo e($member->created_at, false); ?>

                        </td>
                        <td>
                            <a href="/stores/card-detail/<?php echo e($member->id, false); ?>" class="btn btn-sm btn-primary">查看消费明细</a>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/stores/member.blade.php ENDPATH**/ ?>