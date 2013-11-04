<?php
// セッション開始
session_start();

// CSRF対策トークン生成関数
function settoken() {
	# 現在時刻を元にした乱数からsha1ハッシュを生成
	$token = sha1(uniqid(mt_rand(),true));
	$_SESSION[token] = $token;
}

// トークンチェック関数
function checktoken() {
	# トークンが空かもしくはPOSTされたトークンと値が違う
	if(empty($_SESSION[token]) || ($_SESSION[token] != $_POST[token]))
	{
	print('不正な投稿が行われました。');
	exit;
	}
}

// POSTされたときの処理
if($_SERVER[REQUEST_METHOD] != 'POST')
	{
	// トークンをセット
	settoken();
	}
	else{
	// トークンをチェック
	checktoken();

	// ポストされてきた文字列を変数へ格納
	$string = $_POST[string];

	// エラーチェック用の連想配列
	$err = array();

		// ハッシュ化したい文字列が空？
		if($string == ''){
		$err[string] = 'ハッシュ化したい文字列が入力されていません。';
		}

	//問題がなければハッシュ化
	if(empty($err)){
		$hash = sha1($string);
		$hashed = "ハッシュ化した文字列 : $hash";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<!- 文字化け対策の文字コード ->
<meta charset="UTF-8">
<title>sha1 hash maker</title>
</head>

<body>
<!- タイトル ->
<h1>sha1 hash maker</h1>

<!- 入力フォーム ->
<form action="" method="POST">
	<!- CSRF対策トークン ->
	<input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION[token],ENT_QUOTES,""); ?>">

	<!- 文字列入力部分 ->
	<p>ハッシュ化したい文字列 <input type="text" name="string" value="<?php echo $string; ?>"> <?php echo $err[string]; ?></p>

	<!- ハッシュ値出力部分 ->
	<p><?php echo htmlspecialchars($hashed,ENT_QUOTES,""); ?></p>

	<- submitボタン ->
	<input type="submit" value="ハッシュ化">
</form>
</body>
</html>
