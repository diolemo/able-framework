<!doctype html>
   <head>
      <title><?= Content::esc($title) ?></title>
      <link rel="stylesheet" href="/static/css/base.css" />
      <script src="/static/js/base.js"></script>
      <?= $head ?>
   </head>
   <body>
      <?= $content ?>
   </body>
</html>
