<?php
//引入类文件
require './wechat.inc.php';
//实例化类，new对象
$wechat = new Wechat();
//调用类的方法
$wechat->showMenu();