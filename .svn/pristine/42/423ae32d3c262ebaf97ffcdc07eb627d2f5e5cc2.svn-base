<?php 
//引入配置文件和相关函数
require_once ('../includes.php');

//判断管理员是否已经登录
if(admin_login()){
	go_url('index.php');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_KEYWORDS;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/login.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
	//如果被包含在框架内，则自动跳转
	if (window.top != window) {
		window.top.location.href = document.location.href;
	}
	//清空帐号内容
	function clearName(ob){
		if(ob.val()=="请输入管理员帐号"){
			ob.css({'color':'#333'});
			ob.val("");
		}
	}
	//重置帐号内容
	function resetName(ob){
		if(ob.val()==""){
			ob.css({'color':'#aaa'});
			ob.val("请输入管理员帐号");
		}
	}
	//清空密码内容
	function clearPwd(ob){
		if(ob.val()=="请输入管理员密码"){
			var pwd_ob = ob.siblings(":password");
			ob.hide();
			pwd_ob.css({'color':'#333'});
			pwd_ob.val("").show().focus();
		}
	}
	//重置密码内容
	function resetPwd(ob){
		if(ob.val()==""){
			var text_ob = ob.siblings(":text");
			ob.hide();
			text_ob.css({'color':'#aaa'});
			text_ob.val("请输入管理员密码").show();
		}
	}

	//点击文字改变复选框状态，提高用户体验
	$(function(){
		$("#keep_login").click(function(){
			$(this).siblings("[name='login_cookie']").click();
		});
	});

	//表单验证
	function form_check(){
		var admin_name = $("[name='admin_name']");
		if(admin_name.val()==""||admin_name.val()=="请输入管理员帐号"){
			$(".error_tip").text("请输入管理员帐号！").show();
			return false;
		}
		var admin_pwd = $("[name='admin_pwd']:password");
		if(admin_pwd.val()==""||admin_pwd.val()=="请输入管理员密码"){
			$(".error_tip").text("请输入管理员密码！").show();
			$("[name='admin_pwd']:text").hide();
			admin_pwd.show().focus();
			return false;
		}
		$.ajax({
			url:"ajax_admin_login.php",
			type:"POST",
			data:"admin_name="+admin_name.val()+"&admin_pwd="+admin_pwd.val(),
			dataType:"json",
			timeout:3000,
			cache:false,
			async:false,
			success:function(obj){
				if(obj['status']==0){
					$(".error_tip").text(obj['message']).show();
				}else{
					$(".error_tip").text('').hide();
					location.href = "index.php";
				}
			},
			error:function(){
				$(".error_tip").text("未知错误，登录失败！").show();
			}
		})
		return false;
	}
</script>
</head>
<body>
<div class="login_main">
	<form action="admin_login_act.php" method="post" onsubmit="return form_check();">
		<h1 class="login_title">后台管理系统</h1>
		<div class="login_area">
			<div class="submit_area">
				<div class="login_button">
					<input class="submit_button" type="submit" value=""/>
				</div>
			</div>
			<div class="input_area">
				<div class="form_text_box admin_name">
					<input class="form_text" type="text" name="admin_name" maxlength="20" value="请输入管理员帐号" onfocus="clearName($(this))" onblur="resetName($(this))"/>
				</div>
				<div class="form_text_box admin_pwd">
					<input class="form_text" type="text" name="admin_pwd" maxlength="20" value="请输入管理员密码" onfocus="clearPwd($(this))"/>
					<input class="form_text" style="display:none;" type="password" name="admin_pwd" maxlength="20" onblur="resetPwd($(this))"/>
				</div>
			</div>
		</div>
		<div class="error_tip"></div>
	</form>
</div>
</body>
</html>