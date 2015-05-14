
<?php echo $this->Facebook->html(); ?>
    <head>
        <title><?php echo "hello" ?></title>
    </head>
    <body>
      <?php echo json_encode($resp); ?>
    </body>
    <?php echo $this->Facebook->init(); ?>
</html>
