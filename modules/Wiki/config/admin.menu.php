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
        <li class="tobe-highlight" data-highlight-url="/admin/wiki.*">
            <a href="/admin/wiki">
                <i class="icon-double-angle-right"></i>
                Wiki List
            </a>
            <a href="/admin/wiki/create">
                <i class="icon-double-angle-right"></i>
                Create New Wiki
            </a>
        </li>
    </ul>
</li>
