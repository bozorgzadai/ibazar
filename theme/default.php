<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title><?= _page_title ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="<?=baseUrl()?>/asset/css/bootstrap.min.css">
<!--    <link rel="stylesheet" type="text/css" href="--><?//=baseUrl()?><!--/asset/css/theme.min.css">-->

    <!-- jQuery fitst, then Bootstrap JS. -->
    <script type="text/javascript" src="<?=baseUrl()?>/asset/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?=baseUrl()?>/asset/js/jquery.form.min.js"></script>
    <script type="text/javascript" src="<?=baseUrl()?>/asset/js/tether.min.js"></script>
    <script type="text/javascript" src="<?=baseUrl()?>/asset/js/bootstrap.min.js"></script>

  </head>
  <body>
    <? require_once('header.php'); ?>

    <div id="content"><?=$content?></div>

    <? require_once('footer.php'); ?>
  </body>
</html>