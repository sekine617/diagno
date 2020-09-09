<?php
session_start();
ini_set('display_errors', 1);
require_once('../dbconnect.php');
require_once('Assay.php');
require_once('reviewData.php');
ob_start();

if (empty($_SESSION['question'])) {
    header('Location: ../diagnosisPages/question.php');
    exit();
}

$postId = $_SESSION['question']['itemId'];
$adjustSweet = $_SESSION['question']['preferSweet'];
$adjustAcid = $_SESSION['question']['preferAcid'];
$adjustMellow = $_SESSION['question']['preferMellow'];
$adjustRich = $_SESSION['question']['preferRich'];
$adjustFresh = $_SESSION['question']['preferFresh'];
$adjustTexture = $_SESSION['question']['preferTexture'];
$adjustType = $_SESSION['question']['preferType'];

$adjusts = array($adjustSweet, $adjustAcid, $adjustMellow, $adjustRich, $adjustFresh);

// $texture = 2;
// $type = 3;
$categoriesNum = array($adjustTexture, $adjustType);


for ($j = 0; $j < count($categoriesNum); $j++) {
    $categoryNum = $categoriesNum[$j];
    $selectedItemIds = array();
    if ($j < 1) {
        $sqlCtgry1 = 'SELECT itemID FROM itemCategoryID WHERE categoryID=' . $categoryNum;
        $resultsId = $db->query($sqlCtgry1);
        while ($resultId = $resultsId->fetch()) {
            array_push($selectedItemIds, $resultId['itemID']);
        }
        // var_dump($selectedItemIds);
        $resultsIdText = implode(",", $selectedItemIds);
        // var_dump($resultsIdText);
    } else {
        $sqlCtgry2 = 'SELECT itemID FROM 
                (SELECT * FROM itemCategoryID WHERE itemID IN (' . $resultsIdText . ')) AS selected 
                 WHERE categoryID=' . $categoryNum;
        $resultsId = $db->query($sqlCtgry2);
        while ($resultId = $resultsId->fetch()) {
            array_push($selectedItemIds, $resultId['itemID']);
        }
        $resultsIdText = implode(",", $selectedItemIds);
    }
}



$sql1 = 'SELECT itemID, AVG(sweetness) AS AVG_sweetness , AVG(acidity) AS AVG_acidity ,
                       AVG(mellow) AS AVG_mellow ,AVG(richness) AS AVG_richness ,
                       AVG(freshness) AS AVG_freshness  
                       FROM memberReview WHERE itemID = ' . $postId;


$formerDatas = $db->query($sql1);
$formerData = $formerDatas->fetchAll();
$formerData = $formerData[0];
$increaseNum = 0;
do {
    $sqlAvgPart = array();
    $sqlWherePart = array();
    for ($i = 0; $i < count($adjusts); $i++) {
        $adjust = $adjusts[$i];
        $formerAvg = $formerData[$i + 1];
        if ($adjust == 0) {
            $maximum = $formerAvg + 0.5 + 0.1 * $increaseNum;
            $minimum = $formerAvg - 0.5 - 0.1 * $increaseNum;
        } else {
            if ($adjust > 0) {
                $maximum = $formerAvg + $adjust + 0.1 * $increaseNum;
                $minimum = $formerAvg - 0.1 * $increaseNum;
            } elseif ($adjust < 0) {
                $minimum = $formerAvg + $adjust - 0.1 * $increaseNum;
                $maximum = $formerAvg + $adjust + 0.1 * $increaseNum;
            }
        }
        $getEvalName = $questionAssays[$i]->getEvalName();
        $AvgArray = array_push($sqlAvgPart, ', AVG(' . $getEvalName . ') AS AVG_' . $getEvalName . ' ');
        $WhereArray = array_push($sqlWherePart, '(AVG_' . $getEvalName . ' BETWEEN ' . $minimum . ' AND ' . $maximum  . ') ');
        if ($i >= 0 & $i < count($adjusts) - 1) {
            $AvgArray = array_push($sqlWherePart, 'AND');
        }
    }

    $WhereStr = implode($sqlWherePart);
    $AvgStr = implode($sqlAvgPart);
    $sql2 = 'SELECT*FROM (SELECT itemID' . $AvgStr .
        'FROM memberReview WHERE itemID IN(' . $resultsIdText . ') GROUP BY itemID) AS average , yogurtData WHERE' . $WhereStr . 'AND average.itemID=yogurtData.id';

    $sqlCount = 'SELECT COUNT(*) FROM (SELECT itemID' . $AvgStr .
        'FROM memberReview WHERE itemID IN(' . $resultsIdText . ') GROUP BY itemID) AS average WHERE' . $WhereStr;

    $results = $db->query($sql2);
    $resultNum = $db->query($sqlCount);
    $resultNum = $resultNum->fetchColumn();
    if ($resultNum >= 2) {
        break;
    } else {
        $increaseNum++;
    }
} while (TRUE);

$recommendRate = 5 - $increaseNum;
// echo '再検索数' . $increaseNum . '回<br>';
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
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/diagnosis_copy2/menuHeader.php'); ?>
    <div class="outcomes">
        <div class="outcomes_content">
            
            <?php $star_num = 5 ?>
            <h2>あなたにおすすめの商品はこちらです！</h2>
            <?php while ($result = $results->fetch()) : ?>
                <div class="outcome">
                    <p　class="item_name"><?php echo $result['itemName'] ?></p>
                    <img src="../images/yogurt_img/<?php echo $result['img']; ?>" class="result_img">
                    <div class="star_rate">
                        おすすめ度<br>
                        <?php if ($star_num == 5) : ?>
                            <?php $tweetItemName = $result['itemName'] ?>
                            <img src="../images/star_rank.png" class="star">
                            <img src="../images/star_rank.png" class="star">
                            <img src="../images/star_rank.png" class="star">
                            <img src="../images/star_rank.png" class="star">
                            <img src="../images/star_rank.png" class="star">
                        <?php else: ?>
                            <img src="../images/star_rank.png" class="star">
                            <img src="../images/star_rank.png" class="star">
                            <img src="../images/star_rank.png" class="star">
                            <img src="../images/star_rank.png" class="star">
                        <?php endif ?>
                    </div>
                </div>
                <?php $star_num-- ?>
            <?php endwhile ?>
            <p class="share">結果をシェアしよう！</p>
            <a target="_blank" rel="noopener noreferrer" 　id=tweetText href="https://twitter.com/intent/tweet?text=ヨーグルト診断%0A%0Aあなたにおすすめの商品はこちらです！%0A%0A<?php echo $tweetItemName; ?>%0A%0A%23ヨーグルト診断%20%23おすすめのヨーグルト%0Ahttp://duo197.php.xdomain.jp/diagnosis_copy2/diagnosisPages/result.php%0Ahttp://duo197.php.xdomain.jp/diagnosis_copy2">
                <div class="share_btn_block" >
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