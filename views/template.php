<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/defaultTheme.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>js/select2/select2.css">
    <link rel="stylesheet" href="<?php echo base_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <script src="<?php echo base_url();?>js/jquery.js"></script>
    <script src="<?php echo base_url();?>js/jquery.fixedheadertable.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/library.js"></script>
    <script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>plugins/bootstrap-typeahead/bootstrap-typeahead.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/select2/select2.js"></script>
    <script src="<?php echo base_url();?>js/jquery-barcode.js"></script>
    <script src="<?php echo base_url();?>js/jquery-qrcode.js"></script>
    <script src="<?php echo base_url();?>js/html2pdf.bundle.js"></script>
    <script src="<?php echo base_url();?>js/html2canvas.js"></script>
    <script src="<?php echo base_url();?>js/jquery.mask.min.js"></script>
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <link rel="icon" href="<?php echo base_url();?>img/computer.png" type="image/x-icon" />
  </head>
<body class="hold-transition sidebar-mini skin-blue sidebar-fixed sidebar-collapse">
<div class="wrapper">
    <header class="main-header">
        <a href="#" class="logo">
            <!-- <span class="logo-mini"><img size="20px" src="<?php echo base_url();?>img/computer32.png" alt="User Image"></span> -->
            <span class="logo-mini"> <img size="20px" height="30px" src="<?php echo base_url();?>img/hesti.png" alt="User Image"></span>
            <span class="logo-lg">
                <img size="20px" height="30px" src="<?php echo base_url();?>img/hesti.png" alt="User Image">
            <b>Vaksinasi</b>
            </span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"><span class="sr-only">Toggle navigation</span></a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="user user-menu">
                        <?php echo anchor("login/logout/","<i class='fa fa-sign-out'></i> Logout"); ?>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar" >
        <section class="sidebar ">
            <div class="user-panel">
                <div class="pull-left image">
                    <?php
                        echo "<img src='".base_url()."img/avatar2.png' class='img-circle' alt='User Image'/>";
                    ?>
                </div>
                <div class="pull-left info">
                    <p>Administrator</p>
                    <a href="<?php echo site_url('profil');?>"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <?php $this->load->view($vmenu);?>
            </ul>
        </section>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1><?php echo $title_header;?></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url('home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <?php echo $breadcrumb;?>
            </ol>
        </section>
        <section class="content"><div class="row"><?php $this->load->view($content);?></div></section><!-- /.content -->
    </div>
    <footer class="main-footer" id="footers">
        <div class="pull-right hidden-xs"></div>
        <strong>Copyright &copy; 2019 <a href="http://trustme.co.id/">TRUSTME</a></strong> | we respect, trust and care
    </footer>
</div>
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="<?php echo base_url();?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url();?>plugins/fastclick/fastclick.min.js"></script>
<script src="<?php echo base_url();?>js/app.min.js"></script>
<script src="<?php echo base_url();?>js/demo.js"></script>
</body>
</html>
