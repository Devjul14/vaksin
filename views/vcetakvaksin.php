<?php
$value = $val["list"];
$jam = $val["jam"];
$hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
$content  = '<table cellspacing="0" cellpadding="0" width="100%" style="font-size:12px">';
$content .= '<tr><td width="100px">Nama</td><td>:</td><td>'.$value->nama_pasien.'</td></tr>';
$content .= '<tr><td>Tgl Lahir</td><td>:</td><td>'.date("d-m-Y",strtotime($value->tgl_lahir)).'</td></tr>';
$content .= '<tr><td>NIK</td><td>:</td><td>'.$value->nik.'</td></tr>';
$content .= '<tr><td colspan="3" style="font-weight:bold;font-size:14px;"><br><br>Undangan<br>Diharapkan hadir untuk vaksinasi 1 Covid-19</td></tr>';
$content .= '</table><br>';
$content .= '<table cellspacing="0" cellpadding="0" width="100%" style="font-size:12px">';
$content .= '<tr><td style="vertical-align:top" width="100px">Hari/ Tanggal</td><td width="10px" style="vertical-align:top">:</td><td style="vertical-align:top">'.$hari[date("w",strtotime($value->tgl))].", ".date("d-m-Y",strtotime($value->tgl)).'</td></tr>';
$content .= '<tr><td style="vertical-align:top">Waktu</td><td style="vertical-align:top">:</td><td style="vertical-align:top">'.$jam.'</td></tr>';
$content .= '<tr><td style="vertical-align:top">Tempat</td><td style="vertical-align:top">:</td><td style="vertical-align:top">'.$value->tempat.'<br>'.$value->alamat.'</td></tr>';
$content .= '<tr><td style="vertical-align:top;font-weight:bold" colspan="3"><br>Catatan<br>
                <ul style="padding-left:30px">
                  <li>Membawa KTP/ Kartu Identitas</li>
                  <li>Memakai Masker</li>
                  <li>Tunjukan Undangan ini ke vaksinator (Screenshoot/ Download)</li>
                </ul></td></tr>';
$content .= '</table>';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cetak Undangan Vaksinasi</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/AdminLTE.css">
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-barcode.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-qrcode.js"></script>
    <link rel="icon" href="<?php echo base_url(); ?>img/computer.png" type="image/x-icon" />
    <script type="text/javascript" src="<?php echo base_url() ?>js/library.js"></script>
    <script>
      $(document).ready(function(){
        var maps = "<?php echo $value->maps;?>";
        $('.qrcodemap').qrcode({width: 100,height: 100, text:maps});
        window.print();
      });
    </script>
</head>
<body class="page">
  <table class="table no-border laporan" align="center">
      <tr>
          <td colspan="2" style="vertical-align:middle">
              <h5 align="center"><b><u>UNDANGAN VAKSINASI COVID-19</u></b></h5>
          </td>
      </tr>
  </table>
  <?php echo $content;?>
  <table cellspacing="0" cellpadding="0" width="100%" style="font-size:12px">
    <tr><td align="center"><br><span class="qrcodemap" id="barcode"></span></td></tr>
  </table>
</body>
<style>
    * {
        padding-left: 5px;
        padding-right: 5px;
    }

    table,
    td,
    th {
        font-family: sans-serif;
        /*padding: 0px; margin:0px;*/
        /*font-size: 13px;*/
    }

    .laporan {
        border-collapse: collapse !important;
        background-color: transparent;
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 12px;
    }

    .laporan>thead>tr>th,
    .laporan>tbody>tr>th,
    .laporan>tfoot>tr>th,
    .laporan>thead>tr>td,
    .laporan>tbody>tr>td,
    .laporan>tfoot>tr>td {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .laporan>thead>tr>th {
        vertical-align: bottom;
        border-bottom: 2px solid #ddd;
    }

    .laporan>caption+thead>tr:first-child>th,
    .laporan>colgroup+thead>tr:first-child>th,
    .laporan>thead:first-child>tr:first-child>th,
    .laporan>caption+thead>tr:first-child>td,
    .laporan>colgroup+thead>tr:first-child>td,
    .laporan>thead:first-child>tr:first-child>td {
        border-top: 0;
    }

    .laporan>tbody+tbody {
        border-top: 2px solid #ddd;
    }

    .laporan td,
    .laporan th {
        background-color: #fff !important;
    }



    .laporan2 {
        border-collapse: collapse !important;
        background-color: transparent;
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 12px;
    }

    .laporan2 {
        border-collapse: collapse !important;
        background-color: transparent;
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 12px;
    }

    .laporan2>thead>tr>th,
    .laporan2>tbody>tr>th,
    .laporan2>tfoot>tr>th,
    .laporan2>thead>tr>td,
    .laporan2>tbody>tr>td,
    .laporan2>tfoot>tr>td {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .laporan2>thead>tr>th {
        vertical-align: bottom;
        border-bottom: 1px solid #ddd;
    }

    .laporan2>caption+thead>tr:first-child>th,
    .laporan2>colgroup+thead>tr:first-child>th,
    .laporan2>thead:first-child>tr:first-child>th,
    .laporan2>caption+thead>tr:first-child>td,
    .laporan2>colgroup+thead>tr:first-child>td,
    .laporan2>thead:first-child>tr:first-child>td {
        border-top: 1;
    }

    .laporan2>tbody+tbody {
        border-top: 1px solid #ddd;
    }

    .laporan2 td,
    .laporan2 th {
        background-color: #fff !important;
        border: 1px solid #000 !important;
    }

    body {
        margin: 0;
        padding: 0;
        background-color: #FFFFFF;
        font: 12pt "Tahoma";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        width: 148mm;
        min-height: 210mm;
        padding: 0.5cm;
        margin: 1cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .subpage {
        padding: 1cm;
        border: 5px red solid;
        height: 256mm;
        outline: 2cm #FFEAEA solid;
    }

    @page {
        size: A5;
        margin: 0;
    }

    h5 {
        font-size: 14px;
    }

    @media print {
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
</style>
