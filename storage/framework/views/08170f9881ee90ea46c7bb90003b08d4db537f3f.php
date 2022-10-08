<?php $__env->startSection('content'); ?>
<style>
    .data-item .data{color:#FD6B31;font-size:24px;}
    .total{margin-top: 30px}
    .total >p{margin-bottom: 5px;font-weight: 600;}
    .total .data{color:#FD6B31;}


</style>
<div class="page-header">首页</div>
<div class="row justify-content-center">
    <div class="col-md-12">        
        <div>
            <ul class="page-tab nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">今日数据</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">昨日数据</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">本月数据</a>
                </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active row" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="d-flex col-md-6">
                        <div class="data-item col">
                            <div>分销佣金</div>
                            <div class="data"><?php echo e($today['commision'], false); ?>元</div>
                        </div>
                        <div class="data-item col">
                            <div>订单</div>
                            <div class="data"><?php echo e($today['order'], false); ?></div>
                        </div>
                        <div class="data-item col">
                            <div>新增用户</div>
                            <div class="data"><?php echo e($today['member'], false); ?></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade row" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="d-flex col-md-6">
                        <div class="data-item col">
                            <div>分销佣金</div>
                            <div class="data"><?php echo e($yestday['commision'], false); ?>元</div>
                        </div>
                        <div class="data-item col">
                            <div>订单</div>
                            <div class="data"><?php echo e($yestday['order'], false); ?></div>
                        </div>
                        <div class="data-item col">
                            <div>新增用户</div>
                            <div class="data"><?php echo e($yestday['member'], false); ?></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade row" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="d-flex col-md-6">
                        <div class="data-item col">
                            <div>分销佣金</div>
                            <div class="data"><?php echo e($month['commision'], false); ?>元</div>
                        </div>
                        <div class="data-item col">
                            <div>订单</div>
                            <div class="data"><?php echo e($month['order'], false); ?></div>
                        </div>
                        <div class="data-item col">
                            <div>新增用户</div>
                            <div class="data"><?php echo e($month['member'], false); ?></div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
        
        <div class="total">
            <p>分销总佣金：<span class="data"><?php echo e($store->total_money, false); ?></span>元</p>
            <p>已结金额：<span class="data"><?php echo e($store->settle_money, false); ?></span>元</p>
            <p>账户余额：<span class="data"><?php echo e($store->balance, false); ?></span>元</p>
        </div>
        <div class="page-header">公众号推广小程序  <span>示例</span></div>
        <div>
            <p>第一步：公众号关联小程序</p>
            <div class="tips-item">①进入公众号 → 广告与服务 → 小程序管理 → 关联小程序 (小程序APPID:<span class="copy-text"><?php echo e(config('wechat.mini_program.default1.app_id'), false); ?></span>)  <button type="button"  class="copy-btn btn btn-sm btn-outline-info">复制</button></div>
            <p>第二步: 配置公众号自定义菜单</p>
            <div class="tips-item">①配置菜单内容为“跳转到小程序”。</div>
            <div class="tips-item">②跳转到小程序首页路径：<span class="copy-text">pages/index/index?com_id=<?php echo e($store->id, false); ?></span>  <button type="button"   class="copy-btn btn btn-sm btn-outline-info">复制</button></div>
        </div>
    </div>
</div>


<script>
    
    var clipboard = new Clipboard('.copy-btn',{
        text:function(trigger){
            _text = $(trigger).prev('.copy-text').text()
            toast('toast-success','内容已复制')
            return _text;
        }        
    });
    clipboard.on('success', function(e) {
        e.clearSelection();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\wwwroot\moviestickets\webroot\resources\views/stores/home.blade.php ENDPATH**/ ?>