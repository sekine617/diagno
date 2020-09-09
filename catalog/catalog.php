<?php
require_once('../dbconnect.php');
if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
} else {
    $page = 1;
}
$start = 16 * ($page - 1);

$catalogs = $db->prepare('SELECT * FROM (SELECT itemID, AVG(totalRate) AS AVG_totalRate FROM memberReview GROUP BY itemID) AS average, yogurtData WHERE yogurtData.id = average.itemID ORDER BY AVG_totalRate DESC LIMIT ?, 16');
$catalogs->bindParam(1, $start, PDO::PARAM_INT);
$catalogs->execute();


$counts = $db->query('SELECT COUNT(*) as cnt FROM yogurtData');
$count = $counts->fetch();
$max_page = ceil($count['cnt'] / 16);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Document</title>
</head>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/diagno/menuHeader.php'); ?>
    <div class="catalog_title">
            <h1>評価一覧</h1>
        </div>
    <div class="catalog_box">
        
        <div class="catalog_contents">
            <?php while ($catalog = $catalogs->fetch()) : ?>
                <?php $rate = $catalog['AVG_totalRate'];?>
                <a href="detail.php?id=<?php echo $catalog['id']?>">
                    <div class="catalog_content">
                        <img class="catalog_img" src="../images/yogurt_img/<?php print($catalog['img']); ?>" />
                        <div class="catalog_text">
                            <div><?php echo $catalog['itemName']; ?></div>
                            <div class="rate_content">
                                <div class="rate" ><span class="rate_star" style="--rate: <?php echo $rate*20?>%;"></span><span class="rate_text">評価 <?php echo round($catalog['AVG_totalRate'],3); ?><span></div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endwhile ?>
        </div>

        <div class="pg_btns">
            <?php if ($page - 1 > 0) : ?>
                <div class="pg_btn">
                    <a href="catalog.php?page=<?php print($page - 1); ?>">
                        <div class="pgNum"><?php print($page - 1); ?></div>
                    </a>
                </div>
            <?php endif ?>


            <div class="pg_btn">
                <a href="catalog.php?page=<?php print($page); ?>">
                    <div class="pgNum" id="selected"><?php print($page); ?></div>
                </a>
            </div>

            <?php if ($page < $max_page) :?>
                <div class="pg_btn">
                    <a href="catalog.php?page=<?php print($page + 1); ?>">
                        <div class="pgNum"><?php print($page + 1); ?></div>
                    </a>
                </div>
                <?php if ($page - 1 == 0) : ?>
                    <div class="pg_btn">
                        <a href="catalog.php?page=<?php print($page + 2); ?>">
                            <div class="pgNum"><?php print($page + 2); ?></div>
                        </a>
                    </div>
                <?php endif ?>
                <div class="pg_btn">
                    <a href="catalog.php?page=<?php print($page + 1); ?>">
                        <div class="pgNum">></div>
                    </a>
                </div>
            <?php endif ?>
        </div>

    </div>
</body>

</html>