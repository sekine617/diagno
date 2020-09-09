<?php
session_start();
ini_set('display_errors', 1);
 
define("Consumer_Key", "dd9bdxBvODpL6wDFeLvBAic8D");
define("Consumer_Secret", "BNL2Bfi9IuJi5ab1Qng8G3nuIpLNEXQ4vyJ719fZOhUIWi8I1N");

//Callback URL
define('Callback', 'http://duo197.php.xdomain.jp/diagnosis_copy2/twitter/callback.php');
 
//ライブラリを読み込む
require("twitteroauth-main/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;
 
//TwitterOAuthのインスタンスを生成し、Twitterからリクエストトークンを取得する
$connection = new TwitterOAuth(Consumer_Key, Consumer_Secret);
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => Callback));
 
//リクエストトークンはcallback.phpでも利用するのでセッションに保存する
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
// Twitterの認証画面へリダイレクト
$url = $connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
header('Location: ' . $url);
?>