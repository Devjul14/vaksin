<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>Denkesyah 03.04.03 Cirebon | Log in</title>

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <meta http-equiv="cache-control" content="max-age=0" />

    <meta http-equiv="Cache-Control" content="no-store" />

    <meta http-equiv="expires" content="0" />

    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />

    <meta http-equiv="pragma" content="no-cache" />

    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url();?>css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url();?>css/AdminLTE.css" rel="stylesheet" type="text/css" />

    <link rel="icon" href="<?php echo base_url();?>img/computer.ico" type="image/x-icon" />

</head>

<body class="hold-transition login-page">

    <div class="login-box">

        <div class="login-logo">

          <!-- <a href="<?php echo site_url('login'); ?>">

            <img src="<?php echo base_url();?>img/computer128.png" alt="User Image">

          </a> -->

        </div>

        <div class="login-box-body box box-solid">

            <div class="box-header"><h3 class="text-center" style="color:#fff"><b>VAKSINASI</b></h3></div>

            <div class="box-body callout-info">

                <?php echo form_open('login/login_process',array('id'=>'loginform'));?>

                <div class="form-group has-feedback">

                    <input type="text" class="form-control" id="user_login" name="username" placeholder="Username">

                    <span class="glyphicon glyphicon-user form-control-feedback"></span>

                </div>

                <div class="form-group has-feedback">

                    <input type="password" class="form-control" id="user_pass" name="password" placeholder="Password">

                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                </div>

                <div class="row">

                    <div class="col-xs-6 pull-right"><button type="submit" class="btn btn-danger btn-block">Sign In</button></div>

                </div>

                <?php echo form_close()?>

            </div>

        </div>

        <div class="login-box-msg"><span class="label bg-blue">&copy; 2021 | Denkesyah 03.04.03 Cirebon</span></div><br>

        <?php

            if($this->session->flashdata('message')){

              $pesan=explode('-', $this->session->flashdata('message'));

              echo "<div class='alert alert-".$pesan[0]."' alert-dismissable><b>".$pesan[1]."</b></div>";

            }

        ?>

    </div>

</body>

</html>
<style>
  .login-page, .register-page {
      background-image: url(../img/bg-bawah.jpg);
  }
</style>
