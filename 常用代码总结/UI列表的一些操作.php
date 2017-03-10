<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>

<body>
	<!-- 针对公司的ui框架添加的操作 -->
    <form class="form form-horizontal" id="form-article-add" method="post" action="__CONTROLLER__/addAction" enctype="multipart/form-data" onsubmit="return false">
    <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i>保存</button>
    </form>
    <!-- 列表的操作 -->
    <form>
         <!-- 最上边的标题头 -->
    	 <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" id="btndel" class="btn btn-danger radius" onclick="activity_dels(this)">
				<i class="Hui-iconfont" id="btndel">&#xe6e2;</i> 批量删除</a>
				<a class="btn btn-primary radius" onclick="article_add('添加活动','__CONTROLLER__/addAction/','800','500')" href="javascript:;">
					<i class="Hui-iconfont">&#xe600;</i> 添加活动</a></span> <span class="r">共有数据：<strong><?php echo $allmoney[0][count] ?></strong> 条</span>
		 </div>
		 <!-- 下边的列表 -->
		 		<tbody>
		 		<!-- 循环获取的数据 -->
					<foreach name="action" item="v">
						<tr class="text-c">
						    <!-- 最前边的复选框 -->
							<td><input type="checkbox" id="aid" name="huodong" value="<?php echo $v['id'] ?>" /></td>
							<td><?php echo $v['id'] ?></td>
							<td><?php echo date('Y-m-d H:i:s', $v['stime']) ?></td>
							<!-- 点击查看详情 -->
							<td class="text-l"><u style="cursor:pointer" class="text-primary" onClick="article_list('查看','__CONTROLLER__/showList/aid/<?php echo $v['id'] ?>','800','500')" title="查看"><?php echo $v['title'] ?></u></td>
							<!-- 看状态 -->
							<td class="td-status" id="kai"><if condition="$v['status'] == 1"><span class="label label-success radius">已开启</span><else /><span class="label label-default radius">已关闭</span></if></td>
							<td class="td-manage">
							   <!-- 后面的开启关闭按钮小图标显示 -->
								<if condition="$v['status'] == 0">
									<a style="text-decoration:none" onClick="activity_start(this,'<?php echo $v['id'] ?>')" href="javascript:;" title="启用">
										<i class="Hui-iconfont">&#xe615;</i>
									</a>
								<else />
	                              <a style="text-decoration:none" onClick="activity_stop(this,'<?php echo $v['id'] ?>')" href="javascript:;" title="关闭">
										<i class="Hui-iconfont">&#xe615;</i>
									</a>
								</if>
								    <!-- 编辑单条信息 -->
									<a title="编辑" href="__CONTROLLER__/editAction/aid/<?php echo $v['id'] ?>" class="ml-10" style="text-decoration:none">
										<i class="Hui-iconfont">&#xe6df;</i>
									</a>
									<!-- 删除单条信息 -->
									<a title="删除" href="javascript:;" onclick="activity_del(this,'<?php echo $v['id'] ?>')" class="ml-10" style="text-decoration:none">
										<i class="Hui-iconfont">&#xe6e2;</i>
									</a>
									<!-- 显示详情点击出现弹窗 -->
									<a href="javascript:;" class="ml-10" onclick="activity_detail('参与详情','__MODULE__/Cinfo/showList/aid/<?php echo $v['id'] ?>','800','500')">参与详情</a>
							</td>
						</tr>
					</foreach>
					</tbody>
    </form>
</body>
<script type="text/javascript">
// 添加的操作
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	
	$("#form-article-add").validate({
		rules:{
			articletitle:{
				required:true,
			},
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		//ajaxform表单提交
		submitHandler:function(form){
			$(form).ajaxSubmit({
				type: 'post',
				url: "__CONTROLLER__/addAction/id/<?php echo $id ?>",
				//获取表单的要提交的值
				data:$("form").serialize(),
				dataType: "json",
				success: function(data){
					if(data.code == 1){
						//如果添加成功,回调一个函数,data.msg是提示信息,icon是图标类型，time是1秒后关闭，function()为回调函数
						layer.msg(data.msg,{icon:1,time:1000},function(){
							if(data.id == 1){
								window.location.href="__CONTROLLER__/showList";
							}else{
								//刷新父页面
								window.parent.location.reload();
								var index = parent.layer.getFrameIndex(window.name);
								parent.$('.btn-refresh').click();
								//关闭窗口
								parent.layer.close(index);	
							}
						});
					}else if(data.code == 0){
						//如果失败没有回调
						layer.msg(data.msg,{icon:2,time:1000});
					}
				}
			});	
			return false;
		}
	});
});
</script>
<script type="text/javascript">
	/*活动-添加*/
			// 内容讲解：title为弹窗后的标题,url为弹窗的地址链接，w为弹窗的宽，h为弹窗的高
			function article_add(title, url, w, h) {
				layer_show(title, url, w, h);
			}
			/*活动-查看*/
			function article_list(title, url, w, h) {
				layer_show(title, url, w, h);
			}
			/*活动-查看详情*/
			function activity_detail(title, url, w, h) {
				layer_show(title, url, w, h);
			}

			/*活动-编辑*/
			function activity_edit(title, url, w, h) {
				layer_show(title, url, w, h);
			}
			/*活动-关闭*/
			function activity_stop(obj, id) {
				//confirm弹出之后点击确认，可以回调一个函数发送ajax请求
				layer.confirm('确认要关闭吗？', function(index) {
					           $.ajax({
						        type: "get",
						        dataType: "json",
						        url: '__CONTROLLER__/endAction',
						        data: {'aid':id},
						        //成功之后的回调
						        success: function (data) {
						            if(data.code == 1) {
						                //此处请求后台程序，下方是成功后的前台处理……
											$(obj).parents("tr").find(".td-manage").prepend('<a onClick="activity_start(this,<?php echo $v['id'] ?>)" href="javascript:;" title="开启" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
											$(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已关闭</span>');
											$(obj).remove();
											layer.msg('已关闭', {
												icon: 5,
												time: 1000
											});
						            }
						        }
				});
				});
			}

			/*活动-开启*/
			function activity_start(obj, id) {
				layer.confirm('确认要开启吗？', function(index) {
					//此处请求后台程序，下方是成功后的前台处理……
                           $.ajax({
						        type: "get",
						        dataType: "json",
						        url: '__CONTROLLER__/startAction',
						        data: {'aid':id},
						        success: function (data) {
						            if(data.code == 0) {
						                layer.msg('请关闭正在进行的活动', {
												icon: 6,
												time: 1000
											});
						            }else if(data.code == 1){
						            		$(obj).parents("tr").find(".td-manage").prepend('<a onClick="activity_stop(this,<?php echo $v['id'] ?>)" href="javascript:;" title="关闭" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
											$(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已开启</span>');
											$(obj).remove();
											layer.msg('已开启!', {
												icon: 6,
												time: 1000
											});
						            }

						        }
						   
				
				});
                 	});
			}
			/*活动-删除*/
			function activity_del(obj, id) {
				layer.confirm('确认要删除吗？', function(index) {
					$.ajax({
						type: 'get',
						url: '__CONTROLLER__/delAction',
						data:{'aid':id},
						dataType: 'json',
						success: function(data) {
							if(data.code == 1){
							$(obj).parents("tr").remove();
							layer.msg('已删除!', {
								icon: 1,
								time: 1000
							});
						}
					},
						error: function(data) {
							console.log(data.msg);
						},
					});
				});
			}
    //给删除按钮添加点击事件(批量删除)
    	function activity_dels(obj) {
        	layer.confirm('确认要删除吗？', function(index) {
        		        //获取复选框的id值
	        var id = $(':checkbox:checked');    //jQuery对象,类数组的对象
	        var ids = '';   //要求ids的形式是 1,2,3,4,5
	        for(var i = 0;i < id.length;i++){
	            ids = ids + id[i].value + ',';
	        }
	        //剔除右边的逗号
	        ids = ids.substring(0,ids.length-1);
					$.ajax({
						type: 'get',
						url: '__CONTROLLER__/delAction',
						data:{'aid':ids},
						dataType: 'json',
						success: function(data) {
							if(data.code == 1){
								$(obj).parents("tr").remove();
							layer.msg('已删除!', {
								icon: 1,
								time: 1000,
							},function(){
								window.location.href=window.location.href;
							});
						}
					}
					});
				});
        // window.location.href = '__CONTROLLER__/delAction/aid/' + ids; //Tp框架中的写法
    };
$(function(){
    //给编辑按钮添加点击事件
    $('#btnedit').on('click',function(){
        //获取复选框的id值
        var id = $(':checkbox:checked').val();
        //跳转
        window.location.href = '__CONTROLLER__/edit/id/' + id;
    });
});
</script>
</html>