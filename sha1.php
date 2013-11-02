<?php
session_start();

// CSRF対策トークン生成関数
function settoken() {
	$token = sha1(uniqid(mt_rand(),true));
	$_SESSION[token] = $token;
}

// トークンチェック関数
function checktoken() {
	if(empty($_SESSION[token]) || ($_SESSION[token] != $_POST[token]))
	{
	print('不正な投稿が行われました。');
	exit;
	}
}

if($_SERVER[REQUEST_METHOD] != 'POST')
	{
	// CSRF対策フロー
	settoken();
	}
	else{
	checktoken();

	$string = $_POST[string];

	// エラーチェック
	$err = array();

	// ハッシュ化したい文字列が空？
	if($string == ''){
	$err[string] = 'ハッシュ化したい文字列が入力されていません。';
	}

	if(empty($err)){
		//ハッシュ化
		$hash = sha1($string);
		$hashed = "ハッシュ化した文字列 : $hash";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>sha1 hash maker</title>
</head>

<body>
<h1>sha1 hash maker</h1>
<form action="" method="POST">
<input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION[token],ENT_QUOTES,""); ?>">
<p>ハッシュ化したい文字列 <input type="text" name="string" value="<?php echo $string; ?>"> <?php echo $err[string]; ?></p>
<p><?php echo htmlspecialchars($hashed,ENT_QUOTES,""); ?></p>
<input type="submit" value="ハッシュ化">
</form>
</body>
</html>
