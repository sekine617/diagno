<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../style.css">
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-177315620-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-177315620-1');
</script>

  <title>Document</title>
</head>

<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/diagno/menuHeader.php'); ?>

  <div class="bg_loop">
    <div class="bg_loop_top"></div>
    <div class="title">

      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
        <defs>
          <text dy="0.4em" id="outTextx">ヨーグルト診断</text>
        </defs>
        <use x="50%" y="50%" xlink:href="#outTextx"></use>
        <use x="50%" y="50%" xlink:href="#outTextx"></use>
      </svg>


      <!-- （注）IE対策のため「text(use)」を2回繰り返しています -->
    </div>
    <div class="bg_loop_bottom"></div>
  </div>

  <div class="content">
    <p>40種類以上の商品から<br>
      あなたの好みをもとに<br>
      <span>おすすめのヨーグルト</span>を紹介します！</p>
      <div class="btn">
      <a href="question.php" class="btn_block">診断を始める！ </a>
      </div>
  </div>
</body>

</html>