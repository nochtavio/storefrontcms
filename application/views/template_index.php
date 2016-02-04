<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
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
                StorefrontCMS
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
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span>Admin <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                <li class="dropdown-header text-center">Account</li>
                                <li>
                                    <a href="#"><i class="fa fa-ban fa-fw pull-right"></i> Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                        <li><a href="index.html"><i class="fa fa-gavel"></i> <span>Products</span></a></li>
                    </ul>
                </section>
            </aside>

            <aside class="right-side">
                <!-- Main content -->
                <section class="content">
                    <div class="row" style="margin-bottom:5px;">
                        <div class="col-md-3">
                            <div class="sm-st clearfix">
                                <span class="sm-st-icon st-red"><i class="fa fa-check-square-o"></i></span>
                                <div class="sm-st-info">
                                    <span>3200</span>
                                    Total Tasks
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="sm-st clearfix">
                                <span class="sm-st-icon st-violet"><i class="fa fa-envelope-o"></i></span>
                                <div class="sm-st-info">
                                    <span>2200</span>
                                    Total Messages
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="sm-st clearfix">
                                <span class="sm-st-icon st-blue"><i class="fa fa-dollar"></i></span>
                                <div class="sm-st-info">
                                    <span>100,320</span>
                                    Total Profit
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="sm-st clearfix">
                                <span class="sm-st-icon st-green"><i class="fa fa-paperclip"></i></span>
                                <div class="sm-st-info">
                                    <span>4567</span>
                                    Total Documents
                                </div>
                            </div>
                        </div>
                    </div>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->

        </div><!-- ./wrapper -->


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/daterangepicker.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/chart.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/icheck.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/app.js" type="text/javascript"></script>

    </body>
</html>
