<?php
if(!empty($_SESSION['backUrl'])){
    $backUrl = $_SESSION['backUrl'];
	$location = 'Location: ' . $backUrl;
	header($location);
	exit();
}
header('Location: diagnosisPages/index.php');
?>