
<?php
//外部引用

//二维码生成
require_once "jssdk.php";
$jssdk = new JSSDK("wx1640e8d62ab97a4f", "96340a19b9b00523acbca2205254d752");
$signPackage = $jssdk->GetSignPackage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<title>一起来数钱</title>
	<!-- <link rel="stylesheet" type="text/css" href="css/main.css"/> -->
	<link rel="stylesheet" type="text/css" href="css/animate.min.css"/>
</head>
<style type="text/css">
	*{
	margin: 0;
	padding: 0;
}
html,body{
	height: 100%;
	width: 100%;
}
@-webkit-keyframes loading_rotate{
	
	0%{
		-webkit-transform: translate3d(-50%,-50%,0) rotateY(0deg);
		transform: translate3d(-50%,-50%,0) rotateY(0deg);
	}
	100%{
		-webkit-transform: translate3d(-50%,-50%,0) rotateY(360deg);
		transform: translate3d(-50%,-50%,0) rotateY(360deg);
	}
}
@keyframes loading_rotate{
	
	0%{
		-webkit-transform: translate3d(-50%,-50%,0) rotateY(0deg);
		transform: translate3d(-50%,-50%,0) rotateY(0deg);
	}
	100%{
		-webkit-transform: translate3d(-50%,-50%,0) rotateY(360deg);
		transform: translate3d(-50%,-50%,0) rotateY(360deg);
	}
}


#loading_wrap{
	height: 100%;
	width: 100%;
	position: absolute;
	left: 0;
	top: 0;
	background-color: rgba(0,0,0,1);
	perspective:150px;
	/*暂时的*/
}
#loading{
	width: 100px;
	height: 100px;
	border-radius: 50px;
	background-color: #f56471;
	position: absolute;
	left: 50%;
	top: 50%;
	-webkit-transform-style: preserve-3d;
	transform-style: preserve-3d;
	font-size: 50px;
	color: white;
	text-align: center;
	line-height: 100px;
	-webkit-animation: "loading_rotate" 3s infinite;
	animation: "loading_rotate" 3s infinite;
}
.wrap{
	height: 100%;
	width: 100%;
	/*position: absolute;*/
}
#p1{
	height: 100%;
	width: 100%;
	background: url(img/bg.jpg);
	-webkit-background-size: 100% 100%;
	background-size: 100% 100%;
	display: none;
}
.tiaozhan{
	position: absolute;
	left: 20.9375%;
	top: 12.5%;
	width: 70.46875%;
	animation-delay: .8s;
}
.yinqu{
	position: absolute;
	left: 2%;
	top: 25.70422%;
	width: 96%;
}
.p1_btns_wrap{
	position: absolute;
	left: 4.6875%;
	bottom: 2.8415%;
	width: 90%;
}
.ranking{
	position: absolute;
	left: 7.18%;
	bottom: 4.866%;
	width: 20%;
}
.activity_rule{
	position: absolute;
	left: 28.59%;
	bottom: 4.778%;
	width: 20.3125%;
}
.prize{
	position: absolute;
	left: 50.46875%;
	bottom: 4.778%;
	width: 20.3125%;
}
.shiyong{
	position: absolute;
	left: 72.5%;
	bottom: 4.778%;
	width: 20.625%;
}
.p1_mask{
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background: rgba(0,0,0,0.7);
	z-index: 2;
}
.close{
	position: absolute;
	right: 13.59375%;
	top: 13.845%;
	width: 6.6875%;
}
.ranking_wrap{
	position: absolute;
	top: 19.63%;
	left: 13.28125%;
	width: 57.99375%;
	height: 40.387%;
	background: url(img/ranking_bg.png);
	background-size: 100% 100%;
	padding: 14.8% 7.8% 8.688%;
}
#user_data{
	display: none;
}
.userData_wrap{
	position: absolute;
	top: 19.63%;
	left: 13%;
	width: 74%;
	height: 44%;
	background: url(img/p1_from.png);
	background-size: 100% 100%;
}

.userData_name{
	position: absolute;
	left: 12.711%;
	top: 33.5%;
	width: 72%;
	border: 2px solid #e33145;
	height: 14%;
	border-radius: 10px;
	padding-left: 2%;
	font-size: 16px;
	color: black;
	background: #f2bb00;
	outline: none;
}
.tel{
	position: absolute;
	left: 12.711%;
	top: 50.5%;
	width: 72%;
	border: 2px solid #e33145;
	height: 14%;
	border-radius: 10px;
	padding-left: 2%;
	font-size: 16px;
	color: black;
	background: #f2bb00;
	outline: none;
}
.sub{
	position: absolute;
	top: 69%;
	width: 54.85%;
	height: 17.2%;
	border: 0;
	outline: none;
	background: url(img/p1_sub.png);
	background-size: 100% 100%;
	left: 23.5%;
}
.ranking_wrap ul{
	list-style: none;
	overflow: scroll;
	height: 100%;
}
.ranking_wrap li{
	border-bottom: 1px dashed #999;
	padding-bottom: 8px;
	padding-top: 16px;
	font-size: 14px;
	width: 100%;
	box-sizing: content-box;
}
.ranking_wrap li span{
	vertical-align: top;
	line-height: 44px;
	display: inline-block;
}
.rank{
	width: 21px;
	height: 44px;
	text-align: center;
	font-size: 14px;
}
.ranking_wrap li:nth-child(1) .rank{
	background: url(img/p1_first.png) center center no-repeat;
	-webkit-background-size: 80%;
	background-size: 80%;
	
}
.ranking_wrap li:nth-child(2) .rank{
	background: url(img/p1_second.png) center center no-repeat;
	-webkit-background-size: 80%;
	background-size: 80%;
}
.ranking_wrap li:nth-child(3) .rank{
	background: url(img/p1_third.png) center center no-repeat;
	-webkit-background-size: 80%;
	background-size: 80%;
}
.point{
	float: right;
}
.title_img{
	width: 44px;
	height: 44px;
	border-radius: 22px;
}
#ranking{
	display: none;
}
#activity_rule{
	display: none;
}
#prize{
	display: none;
}
#shiyong{
	display: none;
}
.float_wrap{
	position: absolute;
	left: 13.59375%;
	top: 19.71%;
	width: 65.4375%;
	border: 2px solid #e33145;
	border-radius: 10px;
	background: #ffead1;
	padding: 15px;
	max-height: 60%;
	overflow: scroll;
	
}
.float_wrap h2{
	text-align: center;
	padding: 10px 0;
}
.float_wrap p{
	font-size: 14px;
	margin: 20px 0;
}
#p2{
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	display: none;
	background: url(img/bg2.png);
	background-size: 100% 100%;
	overflow: hidden;
}
#p3{
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	display: none;
	background: url(img/p3_bg.jpeg);
	background-size: 100% 100%;
	overflow: hidden;
}
.start_btn{
	position: absolute;
	left: 30.46875%;
	bottom: 21.56690%;
	width: 34.84375%;
	animation-delay: 1s;
	
	animation-iteration-count: infinite;
}
.shou{
	position: absolute;
	left: 58.28125%;
	bottom: 24.207%;
	width: 13.75%;
	animation-iteration-count: infinite;
	animation-direction: alternate;
}
.kuang{
	position: absolute;
	top: 12.676%;
	left: 8%;
	width: 84%;
}
.p2_txt{
	position: absolute;
	left: 15%;
	top: 15.052%;
	width: 70%;
}
@-webkit-keyframes qian_move{
	0%{
		opacity: 1;
		transform: translate3d(0,0,0) scale(1);
	}
	100%{
		opacity: 0;
		transform: translate3d(0,-500px,0) scale(0.2);
	}
}
#game{
	width: 100%;
	height: 100%;
	position: absolute;
	left: 0;
	top: 0;
}
.qian_wrap{
	position: absolute;
	left: 20.625%;
	top: 54.419%;
	width: 59.375%;
}
.p2_qian{
	width: 100%;
}
.p2_qian_new{
	width: 100%;
	position: absolute;
	left: 0;
	top: 0;
	animation: qian_move 1s 1;
}
.p2_zhuan{
	position: absolute;
	width: 100%;
	bottom: 0;
	left: 0;
}
.p2_shou{
	position: absolute;
	right: 2%;
	bottom: 20%;
	width: 35.2%;
	animation-iteration-count: infinite;
}
#timeDown{
	position: absolute;
	top: 32.83%;
	width: 100%;
	text-align: center;
}
#timeDown .time_num{
	display: inline-block;
	width: 10.9375%;
	font-size: 20px;
	color:white;
	background: url(img/p2_scoring.png);
	background-size: 100% 100%;
	padding: 20px 0;
}
#timeDown .clock{
	display: inline-block;
	font-size: 22px;
	color: #bf986b;
	width: 60px;
	height: 60px;
	line-height: 60px;
	margin-left: 5px;
	background: url(img/shizhong.png);
	background-size: 100% 100%;
}
.p3_acquire{
	position: absolute;
	top: 13.38%;
	left: 16.1%;
	width: 69.68%;
}
#result_num{
	text-align: center;
	color: white;
	font-size: 50px;
	position: absolute;
	width: 100%;
	top: 23%;
}
#result_txt{
	position: absolute;
	left: 0;
	width: 100%;
	text-align: center;
	top: 35%;
	font-size: 14px;
	color: white;
}
.result_rank{
	position: absolute;
	left: 10%;
	width: 80%;
	padding: 3% 0;
	background: white;
	border-radius: 10px;
	text-align: center;
	top: 40%;
	font-size: 14px;
	color: #d21026;
}
.p3_again{
	position: absolute;
	left: 15.9%;
	bottom: 27.11%;
	width: 27.8%;
}
.p3_share_btn{
	position: absolute;
	right: 15.9%;
	bottom: 27.11%;
	width: 27.8%;
}
#share{
	display: none;
}
.p3_share{
	position: absolute;
	right: 5%;
	top: 5%;
	width: 50%;
}
</style>
<body>
	<!--<audio autoplay loop src="audio/bgmusic.mp3"></audio>-->
	<div id="loading_wrap">
		<div id="loading">0%</div>
	</div>
	<div class="wrap">
		<div id="p1">
			<div id="index">
				<img class="tiaozhan animated" src="img/tiaozhan.png" alt="" />
				<img class="yinqu animated" src="img/yinqu.png" alt="" />
				<img id="start_btn" class="start_btn animated" src="img/start_game.png" alt="" />
				<img class="shou fadeOut animated" src="img/shou.png"/>
				<img class="p1_btns_wrap" src="img/p1_btns_wrap.png"/>
				<img src="img/ranking.png" class="ranking"/>
				<img src="img/activity_rule.png" class="activity_rule"/>
				<img src="img/prize.png" class="prize"/>
				<img src="img/shiyong.png" class="shiyong"/>
			</div>
			
			<div class="p1_mask" id="user_data">
				<!--提交-->
				<img class="close" src="img/close.png" alt="" />
				<form class="userData_wrap">
					<input class="userData_name" type="text" placeholder="姓名" />
					<input class="tel" type="text" placeholder="电话" />
					<input class="sub" type="button" value="&nbsp;" />
				</form>
			</div>
			
		</div>
		<div class="p1_mask" id="activity_rule">
			<img class="close" src="img/close.png" alt="" />
			<div class="float_wrap">
				<h2>活动规则</h2>
				<p>1、每人有多次游戏机会，但成绩只能提交一次，且提交之后不能更改！<br />
2、提交成绩时要提供姓名及手机号码作为兑奖凭证，因用户本人未在规定时间内提供正确的手机号码造成的奖品损失，由用户个人承担。<br />
3、活动时间为2016年5月11日-5月19日24:00，活动结束后将在“雾灵山庄”微信公布中奖名单。<br />
4、获奖规则：系统将根据大家提交的成绩，按照由多到少的规则进行排行，排名第1的网友将获得一等奖，排名第2-第21位的网友将分获二等奖，以此类推。<br />
5、奖品的发放：活动结束后，将由工作人员与您取得联系，并将相应的卡券编号发送到您提供的手机号码上。</p>
			</div>
		</div>
		<div class="p1_mask" id="prize">
			<img class="close" src="img/close.png" alt="" />
			<div class="float_wrap">
				<h2>活动奖品</h2>
				<p>一等奖1人：价值1488元7号楼1晚豪华标间免费房券1张，并可享康体项目3折优惠；</p>
				<p>二等奖20人：100元订房代金券每人1张，并可享康体项目4折优惠；</p>
				<p>三等奖50人：50元订房代金券每人1张，并可享康体项目5折优惠。</p>
				<p>奖品的有效期：2016年5月20日至6月15日（周五、周六及法定节假日不可用）</p>
			</div>
		</div>
		<div class="p1_mask" id="ranking">
			<img class="close" src="img/close.png" alt="" />
			<div class="ranking_wrap">
				<!--
					前三个的.rank中不用插入名次文本，留空就行
				-->
				<ul>
					<li>
						<span class="rank"></span>
						<img class="title_img" src="img/shizhong.png" alt="" />
						<span class="user_name">聪明的小明</span>
						<span class="point">800分</span>
					</li>
					<li>
						<span class="rank"></span>
						<img class="title_img" src="img/shizhong.png" alt="" />
						<span class="user_name">聪明的小明</span>
						<span class="point">800分</span>
					</li>
					<li>
						<span class="rank"></span>
						<img class="title_img" src="img/shizhong.png" alt="" />
						<span class="user_name">聪明的小明</span>
						<span class="point">800分</span>
					</li>
					<li>
						<span class="rank">111</span>
						<img class="title_img" src="img/shizhong.png" alt="" />
						<span class="user_name">聪明的小明</span>
						<span class="point">800分</span>
					</li>
					<li>
						<span class="rank">1</span>
						<img class="title_img" src="img/shizhong.png" alt="" />
						<span class="user_name">聪明的小明</span>
						<span class="point">800分</span>
					</li>
					<li>
						<span class="rank">1</span>
						<img class="title_img" src="img/shizhong.png" alt="" />
						<span class="user_name">聪明的小明</span>
						<span class="point">800分</span>
					</li>
				</ul>
			</div>
		</div>
		<div class="p1_mask" id="shiyong">
			<img class="close" src="img/close.png" alt="" />
			<div class="float_wrap">
				<h2>奖券使用说明</h2>
				<p>1、奖品的使用：请务必至少提前一周致电010-81027788或81027799进行预约，并于入住时向前台服务人员出示您手机上收到的卡券编号即可使用（需同时验证获奖人姓名与手机号码）。<br />
2、代金券仅适用于线下门市价入住客房消费使用，不适用于通过携程或微信等其他线上渠道预定使用。<br />
3、免费房安排的房间将视当时酒店排房情况而定，如您所预约的时段预订已满，将与您协商调整入住时间。<br />
4、免费房券及代金券不得用于除订房外其他产品消费，不得与酒店其他优惠折扣或礼券同时使用，且不予退换、兑换现金或找赎。<br />
5、对于恶意刷奖者和作弊者，主办方有权取消其兑奖资格。</p>
			</div>
		</div>
		<div id="p2">
			<div id="game">
				<img class="kuang" src="img/p2_kuang.png"/>
				<img class="p2_txt" src="img/p2_txt1.png" alt="" />
				<div class="qian_wrap">
					<img id="qian_swiper" class="p2_qian" src="img/p2_qian.jpeg"/>
				</div>
				<img class="p2_zhuan" src="img/p2_zhuan.png" alt="" />
				<img class="p2_shou fadeOutUp animated" src="img/p2_shou.png"/>
				<div id="timeDown">
					<span class="time_num">0</span>
					<span class="time_num">0</span>
					<span class="time_num">0</span>
					<span class="clock">60</span>
				</div>
			</div>
		</div>
		<div id="p3">
			<div id="result">
				<img class="p3_acquire" src="img/p3_acquire.png" alt="" />
				<div id="result_num">￥888</div>
				<div id="result_txt">没办法！你已经强到没有对手了</div>
				<div class="result_rank">我的辉煌战绩:￥<span id="highScore">999</span>  当前排名：<span id="result_rank">62</span>位</div>
				<img class="p3_again" src="img/p3_again.png" alt="" />
				<img class="p3_share_btn" src="img/p3_share_btn.png" alt="" />
				<img class="p1_btns_wrap" src="img/p1_btns_wrap.png"/>
				<img src="img/ranking.png" class="ranking"/>
				<img src="img/activity_rule.png" class="activity_rule"/>
				<img src="img/prize.png" class="prize"/>
				<img src="img/shiyong.png" class="shiyong"/>
			</div>
			<div class="p1_mask" id="share">
				<img class="p3_share" src="img/p3_share.png"/>
			</div>
		</div>
	</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="js/touch.min.js"></script>
<script src="js/main.js" type="text/javascript"></script>
<script type="text/javascript">
	wx.config({
	    debug: true,//开启调试模式
	    appId: '<?php echo $signPackage["appId"];?>',
	    timestamp: <?php echo $signPackage["timestamp"];?>,
	    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
	    signature: '<?php echo $signPackage["signature"];?>',
	    jsApiList: [
	      // 所有要调用的 API 都要加到这个列表中
	      'onMenuShareTimeline',
	    ]
	  });
	var sum = 100;
	wx.ready(function () {
    // 在这里调用 API
    wx.onMenuShareAppMessage({
      title: '数钱拿大奖', // 分享标题
      desc: '我数了100张，谁来挑战我', //分享描述
      link: 'http://2.weixinbuild.applinzi.com/sq/html.php', // 分享链接
      imgUrl: 'http://www.dev666.com/dev666/static/images/dev666logo.png', // 分享图标
      type: '', // 分享类型,music、video或link，不填默认为link
      dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
      success: function () { 
          // 用户确认分享后执行的回调函数
      },
      cancel: function () { 
          // 用户取消分享后执行的回调函数
      }
    });
  });
</script>
</html>