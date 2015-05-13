
<?php echo $this->Facebook->html(); ?>
    <head>
        <title><?php echo "hello" ?></title>
    </head>
    <body>
      <a href="<?php echo $loginURL; ?>">Login</a>
      <a href="/FacebookCakeConnect/face/post">Post to Wall</a>
      <a href="/FacebookCakeConnect/face/page">Post to Pages</a>
    </body>
    <?php echo $this->Facebook->init(); ?>
</html>
