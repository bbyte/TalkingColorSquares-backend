<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <style>
      tr:hover {
        background-color: #DDDAE5;
        color: #000000;
      }
    </style>
  </head>
  <body>

    <?php echo link_to("Simple statistics", "statistics/index") ?>&nbsp;&nbsp;
    <?php echo link_to("Devices list", "statistics/devices") ?>
    <br/><hr/><br/>
    <?php echo $sf_content ?>
  </body>
</html>
