<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Storefront CMS</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/datepicker3.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/all.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/summernote.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-black">
    <!-- header logo: style can be found in header.less -->
    <header class="header">
      <a href="#" class="logo">
        Storefront CMS
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
          <ul class="nav navbar-nav">
              <?php echo $header; ?>
          </ul>
        </div>
      </nav>
    </header>
    <div class="wrapper row-offcanvas row-offcanvas-left">
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="left-side sidebar-offcanvas">
        <section class="sidebar">
            <?php echo $sidebar; ?>
        </section>
      </aside>

      <aside class="right-side">
        <!-- Main content -->
        <section class="content">
          <?php echo $content; ?>
        </section><!-- /.content -->
      </aside><!-- /.right-side -->

    </div><!-- ./wrapper -->


    <!-- jQuery 2.0.2 -->
<!--    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>-->
    <script src="<?php echo base_url() ?>assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/daterangepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/chart.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/icheck.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/jquery.easy-overlay.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/app.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/summernote.js" type="text/javascript"></script>
    <script type="text/javascript">
      base_url = "<?php echo base_url() ?>";
    </script>
    <script src="<?php echo base_url() ?>assets/js/global.js" type="text/javascript"></script>
    <?php
    if (!empty($js)) {
      if (count($js) > 0) {
        foreach ($js as $j) {
          ?>
          <script type="text/javascript" src="<?php echo base_url() . "assets/js/page/" . $j ?>"></script>
          <?php
        }
      }
    }
    ?>
  </body>
</html>
