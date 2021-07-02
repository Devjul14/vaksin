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

    <script type="text/javascript" src="<?php echo base_url()?>js/library.js"></script>
</head>
<script>
    $(document).ready(function(){
        getttd_dpjp();
    });

    function getttd_dpjp() {
        var ttd = "<?php echo site_url('ttddokter/getttddokterlab/' . $q2->dpjp); ?>";
        $('.dokter_qrcode').qrcode({
            width: 80,
            height: 80,
            text: ttd
        });
    }
</script>
<?php
$t1 = new DateTime('today');
$t2 = new DateTime($q->tgl_lahir);
$y  = $t1->diff($t2)->y;
$m  = $t1->diff($t2)->m;
$d  = $t1->diff($t2)->d;
$umur = $y." tahun ".$m." bulan ".$d." hari";
?>
<h4><b>RUMAH SAKIT TINGKAT III 03.06.01 CIREMAI CIREBON</b></h4>
<table class="laporan" width="100%">
    <tr>
        <td rowspan="4" align="center" style="vertical-align: middle;">
            <h4><b>FORM-B <br> MANAGER PELAYANAN PASIEN / CASE MANAGER</b></h4>
        </td>
        <td width="10%">Nama</td>
        <td width="30%">: <?php echo $q->nama_pasien; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="10%">No. RM</td>
        <td width="30%">: <?php echo $no_pasien;?></td>
    </tr>
    <tr>
        <td>Tgl. Lahir</td>
        <td>: <?php echo $q->tgl_lahir ?> / <?php echo $umur; ?></td>
        <td>No Reg</td>
        <td> : <?php echo $no_reg ?></td>
    </tr>
    <tr>
        <td colspan="4">Jenis Kelamin : <?php echo $q->jenis_kelamin ?> </td>
    </tr>
</table>   
<style>
    *{
        padding-left : 5px;
        padding-right: 5px;
    }
    table,
    td{
        margin-right: 10px;
        },
        th{
            font-family: sans-serif;
            /*padding: 0px; margin:0px;*/
            /*font-size: 13px;*/
        }
    /*input.text{
        height:5px;
        }*/
    </style>
    <style type="text/css">
        .laporan {
            border-collapse: collapse !important;
            background-color: transparent;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 11px;
        }
        .laporan {
            border-collapse: collapse !important;
            background-color: transparent;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 11px;
        }
        .laporan > thead > tr > th,
        .laporan > tbody > tr > th,
        .laporan > tfoot > tr > th,
        .laporan > thead > tr > td,
        .laporan > tbody > tr > td,
        .laporan > tfoot > tr > td {
            padding: 8px;
            height: 300%;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
        .laporan > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }
        .laporan > caption + thead > tr:first-child > th,
        .laporan > colgroup + thead > tr:first-child > th,
        .laporan > thead:first-child > tr:first-child > th,
        .laporan > caption + thead > tr:first-child > td,
        .laporan > colgroup + thead > tr:first-child > td,
        .laporan > thead:first-child > tr:first-child > td {
            border-top: 0;
        }
        .laporan > tbody + tbody {
            border-top: 2px solid #ddd;
        }
        .laporan td,
        .laporan th {
            background-color: #fff !important;
            border: 1px solid #000 !important;
        }



        .laporan2 {
            border-collapse: collapse !important;
            background-color: transparent;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
        }
        .laporan2 {
            border-collapse: collapse !important;
            background-color: transparent;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
        }
        .laporan2 > thead > tr > th,
        .laporan2 > tbody > tr > th,
        .laporan2 > tfoot > tr > th,
        .laporan2 > thead > tr > td,
        .laporan2 > tbody > tr > td,
        .laporan2 > tfoot > tr > td {
            padding: 4px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
        .laporan2 > thead > tr > th {
            margin-bottom: 20px;
            vertical-align: bottom;
            border-bottom: 0px solid #ddd;
        }
        .laporan2 > caption + thead > tr:first-child > th,
        .laporan2 > colgroup + thead > tr:first-child > th,
        .laporan2 > thead:first-child > tr:first-child > th,
        .laporan2 > caption + thead > tr:first-child > td,
        .laporan2 > colgroup + thead > tr:first-child > td,
        .laporan2 > thead:first-child > tr:first-child > td {
            border-top: 0;
        }
        .laporan2 > tbody + tbody {
            border-top: 0px solid #ddd;
        }
        .laporan2 td,
        .laporan2 th {
            background-color: #fff !important;
            border: 0px solid #000 !important;
        }
    </style>
