<li class="tab-options">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?php echo e($trans['oprations'], false); ?><i class="fa fa-caret-down" style="padding-left: 3px;"></i>
    </a>
    <ul class="dropdown-menu">
        <li><a class="tabReload" href="javascript:;" onclick="refreshTab();"><?php echo e($trans['refresh_current'], false); ?></a></li>
        <li><a class="tabCloseCurrent" href="javascript:;" onclick="closeCurrentTab();"><?php echo e($trans['close_current'], false); ?></a></li>
        <li><a class="tabCloseAll" href="javascript:;" onclick="closeOtherTabs(true);"><?php echo e($trans['close_all'], false); ?></a></li>
        <li><a class="tabCloseOther" href="javascript:;" onclick="closeOtherTabs();"><?php echo e($trans['close_other'], false); ?></a></li>
        <li><a class="tabscrollLeft" href="javascript:;" onclick="scrollTabLeft();"><?php echo e($trans['scroll_left'], false); ?></a></li>
        <li><a class="tabscrollRight" href="javascript:;" onclick="scrollTabRight();"><?php echo e($trans['scroll_right'], false); ?></a></li>
        <li><a class="tabscrollRight" href="javascript:;" onclick="scrollTabCurrent();"><?php echo e($trans['scroll_current'], false); ?></a></li>
    </ul>
</li><?php /**PATH F:\wwwroot\moviestickets\webroot\vendor\ichynul\iframe-tabs\src/../resources/views/ext/options.blade.php ENDPATH**/ ?>