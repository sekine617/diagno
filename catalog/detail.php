<?php
require_once('../dbconnect.php');
// ini_set('display_errors', 1);
$itemId = $_GET['id'];

$sql = 'SELECT * FROM (SELECT itemID, AVG(totalRate) AS AVG_totalRate FROM memberReview GROUP BY itemID) AS average, yogurtData WHERE yogurtData.id = average.itemID AND id=' . $itemId . ' ORDER BY AVG_totalRate DESC';
$details = $db->query($sql);
$details = $details->fetch();
$rate = $details['AVG_totalRate'];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/diagno/menuHeader.php'); ?>
    <div class="detail_title">
        <h1>商品詳細</h1>
    </div>
    <div class="item_box">
        <div class="item_contents">
            <div class="item_img">
                <img src="../images/yogurt_img/<?php echo $details['img'] ?>">
            </div>
            <div class="item_text">
                <div class="item_name"><?php echo $details['itemName']; ?></div>
                <div class="rate_content">
                    <div class="rate"><span class="rate_star" style="--rate: <?php echo $rate * 20 ?>%;"></span><span class="rate_text"> 評価 <?php echo round($details['AVG_totalRate'], 3); ?><span></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="share">
            <p class="share_title">Twitterでシェア</p>
            <a target="_blank" rel="noopener noreferrer" 　id=tweetText href="https://twitter.com/intent/tweet?text=<?php echo $details['itemName']; ?>%0A%0A%23ヨーグルト診断%20%23おすすめのヨーグルト%0Ahttp://localhost/diagnosis/diagnosisPages/result.php%0Ahttp://localhost/diagnosis/diagnosisPages/index.php">
                <div class="share_btn_block" for="tweetText">
                    <span class="share_twitter">
                        <i class="fab fa-twitter"></i>
                        ツイート
                    </span>
                </div>
            </a>
        </div>
    </div>

</body>

</html>