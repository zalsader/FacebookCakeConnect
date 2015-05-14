
<?php echo $this->Facebook->html(); ?>
    <head>
        <title><?php echo "hello" ?></title>
    </head>
    <body>
      <a href="<?php echo $loginURL; ?>">Login</a>
      <?php echo $this->Form->create(['action'=>'post', 'type' => 'file']); ?>
      <?php echo $this->Form->file('image'); ?>
      <?php echo $this->Form->input('caption') ?>
      <?php echo $this->Form->submit('Post to Wall'); ?>
      <?php echo $this->Form->submit('Post to Pages', ['formAction'=>'face/page']); ?>
      <?php echo $this->Form->end(); ?>


      <a href="/FacebookCakeConnect/face/post">Post to Wall</a>
      <a href="/FacebookCakeConnect/face/page">Post to Pages</a>
    </body>
    <?php echo $this->Facebook->init(); ?>
</html>
