<?php
require "common.php";
require "twitteroauth-main/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

 
$twitter = new TwitterOAuth(Consumer_Key, Consumer_Secret, Access_Token, Access_Token_Secret);
$result = $twitter->post(
    "statuses/update",
    array("status" => "hello world")
    );
 
if($twitter->getLastHttpCode() == 200) {
    // ツイート成功
    print "tweeted\n";
} else {
    // ツイート失敗
    print "tweet failed\n";
}

echo "<a href='index.php'>はじめのページへ</a>";
