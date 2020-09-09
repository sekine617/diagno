<?php
session_start();
ini_set('display_errors', 1);
require_once('../dbconnect.php');
require_once('Assay.php');
require_once('reviewData.php');

if(isset($_SESSION['id']) && $_SESSION['time'] +3600 > time()){
    echo 'hello';
    if($_SESSION['review']['itemId']===''){
        // header('Location: writeReview.php');
        // exit();
        var_dump($_SESSION);
    }
    $_SESSION['time'] = time();
    
}else{
    // header('Location: ../members/login.php');
    // exit();
}
$itemId = $_SESSION['review']['itemId'];

// $memberId = $_SESSION['memberId'];
$memberId = $_SESSION['id'];
$sweetness = $_SESSION['review']['sweetness'];
$acidity = $_SESSION['review']['acidity'];
$mellow = $_SESSION['review']['mellow'];
$richness = $_SESSION['review']['richness'];
$freshness = $_SESSION['review']['freshness'];
$peculiar = $_SESSION['review']['peculiar'];
$reviewComment = $_SESSION['review']['reviewText'];

$categoryArray = $_SESSION['review']['category'];
array_push($categoryArray, $_SESSION['review']['type']);
array_push($categoryArray, $_SESSION['review']['texture']);

var_dump($_SESSION);
// $evaluationPoints = array($sweetness,$acidity,$mellow,$richness,$freshness,$peculiar);
// $evalNameArry = array();
echo '<br>';
var_dump($categoryArray);



// if ($memberId == '') {
//     //     for ($i = 0; $i < count($evaluationPoints); $i++) {
//     // $assay = $assays[$i];
//     // $evaluationPoint = $evaluationPoints[$i];
//     // $getEvalName = $assay->getEvalName();
//     // array_push($evalNameArry,$getEvalName);

//     // $stmt = $db->prepare($sql);
//     // $stmt->bindValue(':itemID',$postId,PDO::PARAM_STR);

//     // }
//     // $sql = "INSERT INTO nonMemberReview ('itemID', 'sweetness','acidity','mellow',
//     // 'richness','freshness','peculiar') VALUES (:itemID, :sweetness,:acidity,:mellow,
//     // :richness,:freshness,:peculiar)";

//     $sql = "INSERT INTO nonMemberReview ('itemID', 'sweetness','acidity','mellow',
// 'richness','freshness','peculiar') VALUES (:itemID, :sweetness,:acidity,:mellow,
// :richness,:freshness,:peculiar)";
//     $stmt = $db->prepare($sql);
//     $stmt->bindValue(':itemID', $postId, PDO::PARAM_STR);
//     $stmt->bindValue(':sweetness', $postId, PDO::PARAM_STR);
//     $stmt->bindValue(':mellow', $postId, PDO::PARAM_STR);
//     $stmt->bindValue(':richness', $postId, PDO::PARAM_STR);
//     $stmt->bindValue(':freshness', $postId, PDO::PARAM_STR);
//     $stmt->bindValue(':peculiar', $postId, PDO::PARAM_STR);
//     $stmt->execute();
// }




// $sql = "INSERT INTO nonMemberReview ('itemID', 'sweetness','acidity','mellow',
// 'richness','freshness','peculiar') VALUES (:itemID, :sweetness,:acidity,:mellow,
// :richness,:freshness,:peculiar)";
// $stmt = $db->prepare($sql);
// $stmt->bindValue(':itemID',$postId,PDO::PARAM_STR);
// $stmt->bindValue(':sweetness',$postId,PDO::PARAM_STR);
// $stmt->bindValue(':mellow',$postId,PDO::PARAM_STR);
// $stmt->bindValue(':richness',$postId,PDO::PARAM_STR);
// $stmt->bindValue(':freshness',$postId,PDO::PARAM_STR);
// $stmt->bindValue(':peculiar',$postId,PDO::PARAM_STR);
// $stmt->execute();



    

if ($_SESSION['id'] !== '' && $_SESSION['review']['itemId'] !=='') {
    try{
    $db->beginTransaction();
    $sql = "INSERT INTO memberReview (reviewerID,itemID, sweetness,acidity,mellow,
richness,freshness,peculiar,reviewComment) VALUES (:reviewerID,:itemID, :sweetness,:acidity,:mellow,
:richness,:freshness,:peculiar,:reviewComment)";
    $insertpoints = $db->prepare($sql);
    $insertpoints->bindValue(':reviewerID', $memberId, PDO::PARAM_STR);
    $insertpoints->bindValue(':itemID', $itemId, PDO::PARAM_STR);
    $insertpoints->bindValue(':sweetness', $sweetness, PDO::PARAM_STR);
    $insertpoints->bindValue(':acidity', $acidity, PDO::PARAM_STR);
    $insertpoints->bindValue(':mellow', $mellow, PDO::PARAM_STR);
    $insertpoints->bindValue(':richness', $richness, PDO::PARAM_STR);
    $insertpoints->bindValue(':freshness', $freshness, PDO::PARAM_STR);
    $insertpoints->bindValue(':peculiar', $peculiar, PDO::PARAM_STR);
    $insertpoints->bindValue(':reviewComment', $peculiar, PDO::PARAM_STR);
    $insertpoints->execute();
    
    foreach ($categoryArray as $category) {
        $sql2 = "INSERT INTO itemCategoryID (itemID, categoryID) VALUES (:itemID, :categoryID)";
        $insertCategories = $db->prepare($sql2);
        $insertCategories->bindValue(':itemID', $itemId, PDO::PARAM_STR);
        $insertCategories->bindValue(':categoryID', $category, PDO::PARAM_STR);
        $insertCategories->execute();
    }
    $db->commit();
    unset($_SESSION);
header('Location: complete.php');
exit();
}catch(Exception $e){
    $db->rollBack();
    echo '投稿エラー: ' . $e->getMessage();
}
}

?>
