<?php 
require_once('ReviewForm.php');
require_once('Assay.php');

// ヨーグルト評価
$sweet = new Assay('甘味','sweetness');
$acid = new Assay('酸味','acidity');
$mellow = new Assay('なめらかさ','mellow');
$rich = new Assay('コク','richness');
$fresh = new Assay('爽やかさ・後味の軽さ','freshness');
$peculiar = new Assay('味のクセ','peculiar');
$texture = new Assay('選択された商品の食感','texture');
$type = new Assay('ヨーグルトの種類','type');
$category = new Assay('その他該当するカテゴリー','category');

$sweet1 = new ReviewForm('1','無糖・甘さない');
$sweet2 = new ReviewForm('2','甘さが少し足りない');
$sweet3 = new ReviewForm('3','ちょうど良い');
$sweet4 = new ReviewForm('4','少し甘すぎる');
$sweet5 = new ReviewForm('5','甘すぎる');

$acid1 = new ReviewForm('1','酸味はない');
$acid2 = new ReviewForm('2','ほとんど酸味はない');
$acid3 = new ReviewForm('3','ほどよい酸味');
$acid4 = new ReviewForm('4','少し強い');
$acid5 = new ReviewForm('5','強い');

$mellow1 = new ReviewForm('1','なめらかではない');
$mellow2 = new ReviewForm('2','あまりなめらかではない');
$mellow3 = new ReviewForm('3','どちらでもない');
$mellow4 = new ReviewForm('4','少しなめらか');
$mellow5 = new ReviewForm('5','とてもなめらか');

$rich1 = new ReviewForm('1','コクはない');
$rich2 = new ReviewForm('2','あまりコクはない');
$rich3 = new ReviewForm('3','どちらでもない');
$rich4 = new ReviewForm('4','少しコクがある');
$rich5 = new ReviewForm('5','とてもコクがある');

$fresh1 = new ReviewForm('1','爽やかさはない');
$fresh2 = new ReviewForm('2','あまり爽やかさはない');
$fresh3 = new ReviewForm('3','どちらでもない');
$fresh4 = new ReviewForm('4','少し爽やか');
$fresh5 = new ReviewForm('5','とても爽やかさっぱり');

$peculiar1 = new ReviewForm('1','クセがすごい');
$peculiar2 = new ReviewForm('2','クセが強い');
$peculiar3 = new ReviewForm('3','どちらでもない');
$peculiar4 = new ReviewForm('4','クセが少ない');
$peculiar5 = new ReviewForm('5','クセがない');


// ヨーグルト希望
$preferSweet = new Assay('甘味','preferSweet');
$preferAcid = new Assay('酸味','preferAcid');
$preferMellow = new Assay('なめらかさ','preferMellow');
$preferRich = new Assay('コク','preferRich');
$preferFresh = new Assay('爽やかさ・後味の軽さ','preferFresh');
$preferTexture = new Assay('食感','preferTexture');
$preferType = new Assay('タイプ','preferType');

$preferSweet1 = new ReviewForm('-2','より甘さを抑える');
$preferSweet2 = new ReviewForm('-1','甘さを抑える');
$preferSweet3 = new ReviewForm('0','そのまま');
$preferSweet4 = new ReviewForm('1','少し甘く');
$preferSweet5 = new ReviewForm('2','より甘く');

$preferAcid1 = new ReviewForm('-2','より酸味を抑える');
$preferAcid2 = new ReviewForm('-1','酸味を抑える');
$preferAcid3 = new ReviewForm('0','そのまま');
$preferAcid4 = new ReviewForm('1','少し酸味を強める');
$preferAcid5 = new ReviewForm('2','より酸味を強める');

$preferMellow1 = new ReviewForm('-2','より弱める');
$preferMellow2 = new ReviewForm('-1','少し弱める');
$preferMellow3 = new ReviewForm('0','そのまま');
$preferMellow4 = new ReviewForm('1','少し強める');
$preferMellow5 = new ReviewForm('2','より強める');

$preferRich1 = new ReviewForm('-2','より弱める');
$preferRich2 = new ReviewForm('-1','少し弱める');
$preferRich3 = new ReviewForm('0','そのまま');
$preferRich4 = new ReviewForm('1','少し強める');
$preferRich5 = new ReviewForm('2','より強める');

$preferFresh1 = new ReviewForm('-2','より弱める');
$preferFresh2 = new ReviewForm('-1','少し弱める');
$preferFresh3 = new ReviewForm('0','そのまま');
$preferFresh4 = new ReviewForm('1','少し強める');
$preferFresh5 = new ReviewForm('2','より強める');

// カテゴリー
$texture1 = new ReviewForm('1','しっかり・硬め');
$texture2 = new ReviewForm('2','とろとろ・柔らかめ');

$type1 = new ReviewForm('3','プレーン(加糖も含む)');
$type2 = new ReviewForm('4','フルーツ系');
$type3 = new ReviewForm('5','スイーツ系(レアチーズなど)');

$category1 = new ReviewForm('6','低糖質');
$category2 = new ReviewForm('7','高タンパク');
$category3 = new ReviewForm('8','脂肪ゼロ');
$category4 = new ReviewForm('9','腹持ちいい');
$category5 = new ReviewForm('10','大容量');
$category6 = new ReviewForm('11','小分けタイプ');
$category7 = new ReviewForm('12','トクホ');
$category8 = new ReviewForm('13','機能性表示食品');


// 希望カテゴリー
$preferTexture1 = new ReviewForm('1','ハード');
$preferTexture2 = new ReviewForm('2','ソフト');

$preferType1 = new ReviewForm('3','プレーン');
$preferType2 = new ReviewForm('4','フルーツ系');
// $preferType3 = new ReviewForm('5','スイーツ系(レアチーズなど)');

// question.phpのフォーム内容
$questionAssays = array($sweet,$acid,$mellow,$rich,$fresh,$peculiar,$preferSweet,$preferAcid,
$preferMellow,$preferRich,$preferFresh,$preferTexture,$preferType);

$sweetRadios = array($sweet1,$sweet2,$sweet3,$sweet4,$sweet5);
$acidRadios = array($acid1,$acid2,$acid3,$acid4,$acid5);
$mellowRadios = array($mellow1,$mellow2,$mellow3,$mellow4,$mellow5);
$richRadios = array($rich1,$rich2,$rich3,$rich4,$rich5);
$freshRadios = array($fresh1,$fresh2,$fresh3,$fresh4,$fresh5);
$peculiarRadios = array($peculiar1,$peculiar2,$peculiar3,$peculiar4,$peculiar5);

$preferSweetRadios = array($preferSweet1,$preferSweet2,$preferSweet3,$preferSweet4,$preferSweet5);
$preferAcidRadios = array($preferAcid1,$preferAcid2,$preferAcid3,$preferAcid4,$preferAcid5);
$preferMellowRadios = array($preferMellow1,$preferMellow2,$preferMellow3,$preferMellow4,$preferMellow5);
$preferRichRadios = array($preferRich1,$preferRich2,$preferRich3,$preferRich4,$preferRich5);
$preferFreshRadios = array($preferFresh1,$preferFresh2,$preferFresh3,$preferFresh4,$preferFresh5);
$preferTextureRadios = array($preferTexture1,$preferTexture2);
$preferTypeRadios = array($preferType1,$preferType2);

$questionAssayContents = array($sweetRadios,$acidRadios,$mellowRadios,$richRadios,
$freshRadios,$peculiarRadios,$preferSweetRadios,$preferAcidRadios,
$preferMellowRadios,$preferRichRadios,$preferFreshRadios,$preferTextureRadios,$preferTypeRadios);


// writeReview.phpのフォーム内容
$reviewAssays = array($sweet,$acid,$mellow,$rich,$fresh,$peculiar,$texture,$type,$category);

$textureRadios = array($texture1,$texture2);
$typeRadios = array($type1,$type2,$type3);
$categoryRadios = array($category1,$category2,$category3,$category4,$category5,$category6,$category7,$category8);

$reviewAssayContents = array($sweetRadios,$acidRadios,$mellowRadios,$richRadios,$freshRadios,$peculiarRadios,$textureRadios,$typeRadios,$categoryRadios);
?>
