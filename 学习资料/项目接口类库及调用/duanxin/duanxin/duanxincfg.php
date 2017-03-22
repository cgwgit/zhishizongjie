<?php
$accountSid= '8aaf070855c4a7270155ca34b0be09e6';
//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
$accountToken= 'd85d9b1323a04280aa8921e2b5c1a2c8';
//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
$appId='8aaf070855c4a7270155ca34b11b09ec';
//请求地址
//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
//生产环境（用户应用上线使用）：app.cloopen.com
$serverIP='sandboxapp.cloopen.com';
//请求端口，生产环境和沙盒环境一致
$serverPort='8883';
//REST版本号，在官网文档REST介绍中获得。
$softVersion='2013-12-26';

/**
  * 发送模板短信
  * @param to 手机号码集合,用英文逗号分开
  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
  * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
  */