<?php
// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-10 16:42
// +----------------------------------------------------------------------
// + admin.menu.php 管理后台 wiki 相关链接
// +----------------------------------------------------------------------
?>
<li class="">
    <a href="#" class="dropdown-toggle">
        <i class="icon-book"></i>
        <span class="menu-text"> Wiki </span>
        <b class="arrow icon-angle-down"></b>
    </a>

    <ul class="submenu">
        <li class="tobe-highlight" data-highlight-url="/admin/wiki$">
            <a href="/admin/wiki">
                <i class="icon-double-angle-right"></i>
                Entry List
            </a>
        </li>
        <li  class="tobe-highlight" data-highlight-url="/admin/wiki/create">
            <a href="/admin/wiki/create">
                <i class="icon-double-angle-right"></i>
                Create New Entry
            </a>
        </li>
        <li class="tobe-highlight" data-highlight-url="/admin/wiki/category">
            <a href="/admin/wiki/category">
                <i class="icon-double-angle-right"></i>
                Entry Category List
            </a>
        </li>
    </ul>
</li>
