 <?php
 //手机号
 preg_match("/^1[3578][0-9]{9}$/", $_POST['mobile']) ? $tel = trim($_POST['mobile']) : $err = '请填写正确的手机号';
 //身份证号
 preg_match("/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/", $_POST['idCard']) ? $sfz = $_POST['idCard'] : $err = '请填写正确的身份证号';
 //验证字符串是否只含数字与英文，字符串长度并在4~16个字符之间
 preg_match("^[a-zA-Z0-9]{4,16}$", $str);