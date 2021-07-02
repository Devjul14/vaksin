<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SCRINNING VAKSIN</title>
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
<script type="text/javascript">
    var mywindow;
    function openCenteredWindow(url) {
        var width = 800;
        var height = 500;
        var left = parseInt((screen.availWidth/2) - (width/2));
        var top = parseInt((screen.availHeight/2) - (height/2));
        var windowFeatures = "width=" + width + ",height=" + height +
                             ",status,resizable,left=" + left + ",top=" + top +
                             ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow = window.open(url, "subWind", windowFeatures);
    }
    $(document).on('keyup', "input[type=text]", function () {
        $(this).val(function (_, val) {
            return val.toUpperCase();
        });
    });
    $(document).ready(function(){
        $("[name='propinsi'],[name='kotakabupaten'],[name='kecamatan'],[name='desa']").select2();
        $("[name='jk']").select2({placeholder:"Jenis Kelamin"});
        $('#myTable').fixedHeaderTable({ height: '450', altClass: 'odd', footer: true});
        startTime();
        gettanggal();
        gettempatvaksin();
        $("[name='tgl_lahir']").mask('00-00-0000',{placeholder:"00-00-000"});
        localStorage.removeItem('status');
        $(".daftar").click(function(){
            $(".home").addClass("hide");
            $(".formpendaftaran").removeClass("hide");
            getpropinsi();
        });
        $(".simpan").click(function(){
            simpan();
        });
        $(".cetak").click(function(){
            var no_pasien = $(this).attr("no_pasien");
            cetak(no_pasien);
        });
        $(".back").click(function(){
            $(".poli").addClass("hide");
            $(".dokter").addClass("hide");
            $(".home").removeClass("hide");
            $(".daftar").addClass("hide");
            localStorage.removeItem('kode');
            localStorage.removeItem('dokter');
            localStorage.removeItem('status');
        });
        $("[name='propinsi']").change(function(){
          var propinsi = $(this).val();
          getkota(propinsi);
        })
        $("[name='kotakabupaten']").change(function(){
          var kota = $(this).val();
          getkecamatan(kota);
        })
        $("[name='kecamatan']").change(function(){
          var kecamatan = $(this).val();
          getdesa(kecamatan);
        })
    });
    // function simpan(){
    //     $('.invoice').removeClass("hide");
    //     var nama_pasien = $("[name='nama_pasien']").val();
    //     var nohp = $("[name='nohp']").val();
    //     var nik = $("[name='nik']").val();
    //     var tgl_lahir = $("[name='tgl_lahir']").val();
    //     var propinsi = $("[name='propinsi']").val();
    //     var jk = $("[name='jk']").val();
    //     var kotakabupaten = $("[name='kotakabupaten']").val();
    //     var kecamatan = $("[name='kecamatan']").val();
    //     var desa = $("[name='desa']").val();
    //     var alamat = $("[name='alamat']").val();
    //     if (nama_pasien=="" || nohp=="" || nik=="" || tgl_lahir=="" || desa=="" || alamat==""){
    //       alert("Lengkapi data dengan benar");
    //     } else {
    //     $.ajax({
    //           url: "<?php echo site_url('vaksinasi/simpan_pasien')?>",
    //           type: 'POST',
    //           data: {nama_pasien:nama_pasien,nohp:nohp,nik:nik,tgl_lahir:tgl_lahir,propinsi:propinsi,kotakabupaten:kotakabupaten,kecamatan:kecamatan,desa:desa,alamat:alamat,jk:jk},
    //           success: function(result){
    //               var val = JSON.parse(result);
    //               var value = val.list;
    //               var jam = val.jam;
    //               var content = "";
    //               // $(".barcode").barcode(value.no_pasien,"code39",{showHRI: false,barHeight:25});
    //               content += '<tr><td width="100px">No. RM</td><td>: '+value.no_pasien+'</td></tr>';
    //               content += '<tr><td>Nama</td><td>: '+value.nama_pasien+'</td></tr>';
    //               content += '<tr><td>NIK</td><td>: '+value.nik+'</td></tr>';
    //               content += '<tr><td colspan="2">Jadwal Vaksin Tanggal '+value.tgl+' Jam '+jam+' bertempat di '+value.tempat+'</td></tr>';
    //               $(".konten_print").html(content);
    //               var divToPrint=document.getElementById("invoice");
    //               newWin= window.open("");
    //               newWin.document.write(divToPrint.outerHTML);
    //               newWin.print();
    //               newWin.close();
    //               var opt = {
    //                   margin: 5,
    //                   filename: 'myfile.pdf'
    //               };
    //               var string = $(".invoice").html();
    //           },
    //           error: function(result){
    //               console.log(result);
    //           }
    //       });
    //     }
    // }
    function cetak(no_pasien){
      // $('#tampilancetak').printThis();
      var url = "<?php echo site_url('vaksinasi/cetakvaksin');?>/"+no_pasien;
      openCenteredWindow(url);
    }
    function simpan(){
        var nama_pasien = $("[name='nama_pasien']").val();
        var nohp = $("[name='nohp']").val();
        var nik = $("[name='nik']").val();
        var tgl_lahir = $("[name='tgl_lahir']").val();
        var propinsi = $("[name='propinsi']").val();
        var jk = $("[name='jk']").val();
        var kotakabupaten = $("[name='kotakabupaten']").val();
        var kecamatan = $("[name='kecamatan']").val();
        var desa = $("[name='desa']").val();
        var alamat = $("[name='alamat']").val();
        var tempat_vaksin = $("[name='tempat_vaksin']").val();
        if (tgl_lahir=="00-00-0000" || nama_pasien=="" || nohp=="" || nik=="" || tgl_lahir=="" || desa=="" || alamat=="" || tempat_vaksin==""){
          alert("Lengkapi data dengan benar");
        } else {
        $.ajax({
              url: "<?php echo site_url('vaksinasi/simpan_pasien')?>",
              type: 'POST',
              data: {nama_pasien:nama_pasien,nohp:nohp,nik:nik,tgl_lahir:tgl_lahir,propinsi:propinsi,kotakabupaten:kotakabupaten,kecamatan:kecamatan,desa:desa,alamat:alamat,jk:jk,tempat_vaksin:tempat_vaksin},
              success: function(result){
                if (result=="false"){
                  $('.formcetak').addClass("hide");
                  $('.formpendaftaran').removeClass("hide");
                  alert("Umur masih dibawah 18 tahun");
                } else {
                    $('.formcetak').removeClass("hide");
                    $('.formpendaftaran').addClass("hide");
                    var val = JSON.parse(result);
                    var value = val.list;
                    var jam = val.jam;
                    var content = "";
                    // $(".barcode").barcode(value.no_pasien,"code39",{showHRI: false,barHeight:25});
                    $('.qrcodemap').qrcode({width: 100,height: 100, text:value.maps, render: 'image'});
                    $(".linkmap").attr("href",value.maps);
                    if (val.ada=="ada") content += '<div class="alert alert-danger">NIK dan No HP anda telah terdaftar dengan data sebagai berikut</div><br>';
                    content += '<table cellspacing="0" cellpadding="0" width="100%" style="font-size:12px">';
                    content += '<tr><td>Nama</td><td>:</td><td>'+value.nama_pasien+'</td></tr>';
                    content += '<tr><td>Tgl Lahir</td><td>:</td><td>'+tgl_indo(value.tgl_lahir)+'</td></tr>';
                    content += '<tr><td>NIK</td><td>:</td><td>'+value.nik+'</td></tr>';
                    content += '</table><br>';
                    content += '<b>Undangan<br>Diharapkan hadir untuk vaksinasi 1 Covid-19</b>';
                    content += '<table cellspacing="0" cellpadding="0" width="100%" style="font-size:12px">';
                    content += '<tr><td style="vertical-align:top" width="100px">Hari/ Tanggal</td><td width="10px" style="vertical-align:top">:</td><td style="vertical-align:top">'+tgl_indo(value.tgl,1,1)+'</td></tr>';
                    content += '<tr><td style="vertical-align:top">Waktu</td><td style="vertical-align:top">:</td><td style="vertical-align:top">'+jam+'</td></tr>';
                    content += '<tr><td style="vertical-align:top">Tempat</td><td style="vertical-align:top">:</td><td style="vertical-align:top">'+value.tempat+'<br>'+value.alamat+'</td></tr>';
                    content += '<tr><td style="vertical-align:top" colspan="3"><br><b>Catatan<br>Membawa KTP/ Kartu Identitas<br>Memakai Masker<br>Tunjukan Undangan ini ke vaksinator (Screenshoot/ Download)</b></td></tr>';
                    content += '</table>';
                    $(".cetak").attr("no_pasien",value.no_pasien);
                    $(".tampilancetak").html(content);
                }
              },
              error: function(result){
                  console.log(result);
              }
          });
        }
    }
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        $(".clock").html(h + ":" + m + ":" + s);
        var t = setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }
    function gettanggal(){
        var d = new Date();
        var weekday = new Array(7);
        weekday[0] = "Minggu";
        weekday[1] = "Senin";
        weekday[2] = "Selasa";
        weekday[3] = "Rabu";
        weekday[4] = "Kamis";
        weekday[5] = "Jumat";
        weekday[6] = "Sabtu";
        var month = new Array();
        month[0] = "Jan";
        month[1] = "Feb";
        month[2] = "Mar";
        month[3] = "Apr";
        month[4] = "Mei";
        month[5] = "Jun";
        month[6] = "Jul";
        month[7] = "Agust";
        month[8] = "Sept";
        month[9] = "Okt";
        month[10] = "Nov";
        month[11] = "Des";
        $(".tanggal").html(weekday[d.getDay()]+", "+d.getDate()+" "+month[d.getMonth()]+" "+d.getFullYear());
    }
    function tgl_indo(tgl,tipe=1,hari=0){
        var date = tgl.substring(tgl.length,tgl.length-2);
        if (tipe==1)
            var bln = tgl.substring(5,7);
        else
            var bln = tgl.substring(4,6);
        var thn = tgl.substring(0,4);
        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        var dateString = bln+"/"+date+"-"+thn;
        var d = new Date(dateString);
        var dayName = days[d.getDay()];
        if (hari==1){
          return dayName+", "+date+"-"+bln+"-"+thn;
        } else {
          return date+"-"+bln+"-"+thn;
        }
    }
    function getpropinsi(){
      $.ajax({
          url: "<?php echo site_url('vaksinasi/getpropinsi')?>",
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='propinsi']").html('').select2({data:row,placeholder:"Pilih Propinsi"});
          }
      });
    }
    function gettempatvaksin(){
      $.ajax({
          url: "<?php echo site_url('vaksinasi/gettempatvaksin')?>",
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='tempat_vaksin']").html('').select2({data:row,placeholder:"Pilih Tempat Vaksin"});
          }
      });
    }
    function getkota(id){
      $.ajax({
          url: "<?php echo site_url('vaksinasi/getkota')?>",
          data: {propinsi:id},
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='kotakabupaten']").html('').select2({data:row,placeholder:"Pilih Kota/ Kabupaten"});
          }
      });
    }
    function getkecamatan(id){
      $.ajax({
          url: "<?php echo site_url('vaksinasi/getkecamatan')?>",
          data: {kota:id},
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='kecamatan']").html('').select2({data:row,placeholder:"Pilih Kecamatan"});
          }
      });
    }
    function getdesa(id){
      $.ajax({
          url: "<?php echo site_url('vaksinasi/getdesa')?>",
          data: {kecamatan:id},
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='desa']").html('').select2({data:row,placeholder:"Pilih Desa"});
          }
      });
    }
</script>
<?php
$tgl = date("Y-m-d");
do {
  $tgl = date("Y-m-d",strtotime($tgl." +1 days"));
  $libur = (int)(date("w",strtotime($tgl)));
} while ($libur!=4);
?>
<body class="skin-blue layout-top-nav fixed">
    <input type="hidden" name="id">
    <div class="wrapper">
        <div class="main-header">
            <div class="atas">
                <div class="col-lg-9 col-xs-8 col-sm-8">
                    <div class="judul pull-right">
                        PENDAFTARAN VAKSINASI COVID-19
                        <span class="tanggal"></span>
                        <span class="clock"></span>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-4 col-sm-4">
                    <div class="logo_atas"><img src="<?php echo base_url();?>img/Logo.png"></div>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
             <section class="content">
               <div class="home" style="margin-top:100px">
               <div class="col-lg-9 col-xs-8 col-sm-9">
                    <div class="alert alert-success"><h3>Mohon maaf link pendaftaran vaksin akan dibuka kembali hari Minggu, 04 Juli 2021</h3></div>
                </div>
              </div>
                <!-- <div class="bawah row">
                  <div class="home">
                    <div class="row">
                      <div class="col-lg-4 col-md-4">&nbsp;</div>
                      <div class="col-xs-8 col-lg-4 col-md-4">
                        <div class="alert2" style="border-radius: 90px">
                          <a class="menu daftar" href="#">
                            <p class="text-center">
                              <img src="<?php echo base_url();?>img/vaccination.png" style="width:120px">
                            </p>
                            <div class="menutitle">VAKSINASI 1</div>
                          </a>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-4">&nbsp;</div>
                    </div>
                  </div>
                  <div class="formpendaftaran hide">
                    <div class="col-xs-12">
                        <div class="box box-solid">
                          <div class="box-header with-border"><h3 class="box-title">Form Pendaftaran</h3></div>
                          <div class="box-body">
                              <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Nama</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" autocomplete="off" required name="nama_pasien">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">No. HP</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" autocomplete="off" required name="nohp">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">NIK</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" autocomplete="off" required name="nik">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Jenis Kelamin</label>
                                    <div class="col-md-9">
                                        <select type="text" class="form-control" required name="jk" style="width:100%">
                                          <option value=""></option>
                                          <option value="L">Laki-laki</option>
                                          <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Tgl Lahir</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" required name="tgl_lahir" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Propinsi</label>
                                    <div class="col-md-9">
                                        <select type="text" class="form-control" required name="propinsi"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Kota/ Kabupaten</label>
                                    <div class="col-md-9">
                                        <select type="text" class="form-control" required name="kotakabupaten" style="width:100%"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Kecamatan</label>
                                    <div class="col-md-9">
                                        <select type="text" class="form-control" required name="kecamatan" style="width:100%"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Desa</label>
                                    <div class="col-md-9">
                                        <select type="text" class="form-control" required name="desa" style="width:100%"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Alamat</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" autocomplete="off" required name="alamat">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Tempat Vaksin</label>
                                    <div class="col-md-9">
                                        <select type="text" class="form-control" required name="tempat_vaksin" style="width:100%"></select>
                                    </div>
                                </div>
                              </div>
                          </div>
                          <div class="box-footer">
                            <button class="simpan btn btn-success btn-block btn-flat">VAKSINASI 1</button>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="formcetak hide">
                    <div class="col-xs-12">
                        <div class="box box-primary">
                          <div class="box-body">
                            <div class="col-xs-12" id="tampilancetak">
                              <div class="tampilancetak"></div>
                              <div class="col-xs-12">
                                <table cellspacing="0" cellpadding="0" width="100%" style="font-size:12px">
                                  <tr><td align="center"><a class='linkmap'>link maps</a><br><div class="qrcodemap"></div></td></tr>
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="box-footer">
                            <button class="cetak btn btn-primary btn-block btn-flat"><i class="fa fa-download"></i>&nbsp;&nbsp;DOWNLOAD</button>
                          </div>
                        </div>
                    </div>
                  </div>
                </div> -->
            </section>
        </div>
        <footer class="main-footer" id="footers">
            <div class="pull-right hidden-xs"></div>
            <strong>Copyright &copy; 2021 <a href="#">RS Ciremai Cirebon</a></strong>
        </footer>
    </div>
    <div class='loading modal'>
        <div class='text-center align-middle' style="margin-top: 200px">
            <div class="col-xs-3 col-sm-3 col-lg-5"></div>
            <div class="alert col-xs-6 col-sm-6 col-lg-2" style="background-color: white;border-radius: 10px;">
                <div class="overlay" style="font-size:50px;color:#696969"><img src="<?php echo base_url();?>img/load.gif" width="150px"></div>
                <div style="font-size:12px;font-weight:bold;color:#696969;margin-top:-30px;margin-bottom:20px">Harap menunggu, data sedang diproses</div>
            </div>
            <div class="col-xs-3 col-sm-3 col-lg-5"></div>
        </div>
    </div>
    <section class="invoice no-border hide" id="invoice">
        <table cellspacing="0" cellpadding="0" width="100%" style="font-size:12px">
            <tbody class="konten_print"></tbody>
            <tfoot>
                <tr><td colspan="2"><br><span class="barcode" id="barcode"></span></td></tr>
            </tfoot>
        </table>
    </section>
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>js/app.js"></script>
    <script src="<?php echo base_url();?>js/demo.js"></script>
    <style type="text/css">
        .navbar-nav > .notifications-menu > .dropdown-menu > li .menu, .navbar-nav > .messages-menu > .dropdown-menu > li .menu, .navbar-nav > .tasks-menu > .dropdown-menu > li .menu {
            max-height: 420px;
        }
        .invoice{
            width:4cm;
        }
        .home {
            width: 560px;
            margin: 7% auto;
        }
        .invoice td{
            font-size: 10px;
        }
        a.menu{
            font-size:100px;
        }
        .alert a{
          color: #313233;
          text-decoration: none;
          margin: 0px auto;
          text-align: center;
        }
        .content-wrapper{
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            background: url("img/bg-bawah.jpg") repeat center center;
        }
        .menutitle{
            text-align: center;
            font-size: 20px;
            min-height: 40px;
            padding-top:20px;
            font-weight: bold;
        }
        .alert{
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #f4f4f4;
        }
        .alert p{
            margin-top: 20px;
        }
        .judul{
            text-align: right;
            margin: 50px 0px;
            font-size: 40px;
            font-weight: bold;
            display: block;
        }
        .tanggal{
            text-align: right;
            font-size: 15px;
            font-weight: bold;
            display: block;
        }
        .clock{
            text-align: right;
            font-size: 15px;
            font-weight: bold;
            display: block;
        }
        .atas{
            background: url("img/bg-vaksinasi.jpg") no-repeat 30% center fixed;
            padding: 40px 0px;
            height:200px;
        }
        .bg::before {
            content: "";
            background: url("img/background_bawah.jpg") no-repeat center right fixed;
            position: absolute;
            opacity: 0.3;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
        }
        .bawah{
            padding: 120px 0px 40px;
        }
        .login-box,
        .register-box {
          width: 700px;
          margin: 0 auto;
        }
        .alert{
            background-color: transparent;
            border:0px;
        }
        .main-footer {
            position: fixed;
            z-index: 1030;
            bottom: 0px;
            right: 0px;
            left: 0px;
        }
        .logo_atas img{
            width: 120px;
        }
        img.tombol{
            width: 450px;
        }
        .img-thumbnail{
            border:0px;
        }
        tr.pol td{
            font-size: 25px;
            font-weight: bold;
        }
        .modal{
            padding-top: 10%;
        }
        .modal-footer, .modal-content {
            background-color: transparent;
            border:0px;
        }
        .btn{
            border-radius: 30px;
        }
        @media (min-width: 260px) and (max-width: 395px) {
          .alert{
              margin: 0px 100px 0px 0px;
          }
        }
        @media (min-width: 395px) and (max-width: 500px)  {
          .alert{
              margin: 0px 80px 0px 0px;
          }
        }
        @media (max-width: 799px) {
            .judul{
                text-align: right;
                margin: 0px;
                font-size: 17px;
                font-weight: bold;
                display: block;
            }
            .atas{
                padding: 20px 0px;
                height:130px;
            }
            .bawah{
                padding: 30px 0px;
            }
            .logo_atas img{
                width: 80px;
            }
            img.tombol{
                width: 200px;
            }
            .login-box,
            .register-box {
              width: 80%;
              margin: 7% auto;
            }
            tr.pol td{
                font-size: 15px;
                font-weight: bold;
            }
            .tanggal, .clock{
                font-size:10px;
            }
            .alert{
                margin: 0px auto;
            }
            .alert a{
          		color: #313233;
          		text-decoration: none;
          		margin: 0px auto;
          		text-align: center;
          	}
        }
        @media (min-width: 800px) and (max-width: 1300px) {
            .judul{
                text-align: center;
                margin: 20px 0px;
                font-size: 30px;
                font-weight: bold;
                display: block;
            }
            .atas{
                background-color: black;
                padding: 20px 0px;
                height:150px;
            }
            img.tombol{
                width: 250px;
            }
        }
        #signature{
            height: 300px;
            border: 1px solid black;
        }
    </style>
</body>
