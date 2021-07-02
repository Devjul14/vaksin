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
    <script src="<?php echo base_url();?>js/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>plugins/bootstrap-typeahead/bootstrap-typeahead.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/select2/select2.js"></script>
    <script src="<?php echo base_url();?>js/jquery-barcode.js"></script>
    <script src="<?php echo base_url();?>js/jquery-qrcode.js"></script>
    <script src="<?php echo base_url();?>js/html2pdf.bundle.js"></script>
    <script src="<?php echo base_url();?>js/html2canvas.js"></script>
    <script src="<?php echo base_url();?>js/jquery.mask.min.js"></script>
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/library.js"></script>
    <script src="<?php echo base_url();?>js/jquery.signature.js"></script>
    <script src="<?php echo base_url();?>js/jquery.ui.touch-punch.min.js"></script>
    <link rel="icon" href="<?php echo base_url();?>img/computer.png" type="image/x-icon" />
  </head>
  <?php
      $t1 = new DateTime('today');
      $t2 = new DateTime($q1->tgl_lahir);
      $y  = $t1->diff($t2)->y;
      $m  = $t1->diff($t2)->m;
      $d  = $t1->diff($t2)->d;

      list($year,$month,$day) = explode("-",$q1->tgl_lahir);
      $year_diff  = date("Y") - $year;
      $month_diff = date("m") - $month;
      $day_diff   = date("d") - $day;
      if ($month_diff < 0) {
          $year_diff--;
          $month_diff *= (-1);
      }
      elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
      if ($day_diff < 0) {
          $day_diff *= (-1);
      }
      $umur_pasien = $year_diff." tahun ".$month_diff." bulan ".$day_diff." hari ";

      if ($q) {
          // $nama_pasien = $q1->nama_pasien1;
          // $tgl_lahir   = $q->tgl_lahir;
          // $nik         = $q1->nik1;
          $tgl_periksa = $q->tgl_periksa;
          $jam         = $q->jam;
          // $umur        = $q->umur;
          // $no_hp       = $q1->nohp;
          // $alamat      = $q->alamat;
          $suhu        = $q->suhu;
          $tekanan_darah        = $q->tekanan_darah;
          $pertanyaan1_1        = $q->pertanyaan1_1;
          $pertanyaan1_2        = $q->pertanyaan1_2;
          $pertanyaan2        = $q->pertanyaan2;
          $pertanyaan3        = $q->pertanyaan3;
          $pertanyaan4        = $q->pertanyaan4;
          $pertanyaan5        = $q->pertanyaan5;
          $pertanyaan6        = $q->pertanyaan6;
          $pertanyaan7_1        = $q->pertanyaan7_1;
          $pertanyaan7_2        = $q->pertanyaan7_2;
          $pertanyaan7_3        = $q->pertanyaan7_3;
          $pertanyaan7_4        = $q->pertanyaan7_4;
          $pertanyaan7_5        = $q->pertanyaan7_5;
          $anamnesa        = $q->anamnesa;
          $bersedia        = $q->bersedia;
          $ttd = $q->ttd;
          $tandatangan = $q->ttd=="" ? 1 : 0;
          if ($q2->status) $ubah = "readonly"; else $ubah = "";
          $aksi = "edit";
      } else {
          // $tgl_lahir =
          // $nik =
          $tgl_periksa =
          $jam =
          // $umur =
          // $alamat =
          // $no_hp =
          $suhu =
          $tekanan_darah =
          $pertanyaan1_1 =
          $pertanyaan1_2 =
          $pertanyaan2 =
          $pertanyaan3 =
          $pertanyaan4 =
          $pertanyaan5 =
          $pertanyaan6 =
          $pertanyaan7_1 =
          $pertanyaan7_2 =
          $pertanyaan7_3 =
          $pertanyaan7_4 =
          $pertanyaan7_5 =
          $anamnesa =
          $bersedia =
          $tandatangan =
          // $no_reg         = $no_reg;
          // $no_pasien      = $no_pasien;
          $ubah = "";
          $aksi = "simpan";
          $ttd = "";
      }
      // var_dump($q1->nik);
  ?>
<script>
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
    $(document).ready(function() {
        // $("[name='tgl_lahir']").mask("dd-mm-yyyy", {placeholder: 'dd-mm-yyyy'});
        $(".back").click(function(){
            var no_reg  = $("[name='no_reg']").val();
            var no_pasien   = $("[name='no_pasien']").val();
            var url     = "<?php echo site_url('pendaftaran/formskrining_vaksin');?>/"+no_pasien+"/"+no_reg;
            window.location = url;
            return false;
        });
        // var formattgl = "dd-mm-yy";
        // $("[name='tgl_lahir'],[name='tgl_periksa']").datepicker({
        //     dateFormat: formattgl,
        // });
        $(".cetak").click(function(){
            var no_reg          = $("[name='no_reg']").val();
            var no_pasien       = $("[name='no_pasien']").val();
            var url = "<?php echo site_url('whatsapp/cetakskrining_vaksin');?>/"+no_pasien+"/"+no_reg;
            openCenteredWindow(url);
        });
        getttd();
        $('.hapusttd').click(function() {
          $('#signature').signature('clear');
          $("[name='hapusttd']").val(1);
        return false;
        });
        function getttd() {
        var ttd = "<?php echo "data:image/png;base64,".$ttd;?>";
        $("#signature").signature({syncField: '#signatureJSON'});
        $('#signature').signature('option', 'syncFormat', "PNG");
        $('#signature').draggable();
        $('#signature').signature('draw', ttd);
    }
    });
</script>
<?php
    if($this->session->flashdata('message')){
        $pesan=explode('-', $this->session->flashdata('message'));
        echo "<div class='alert alert-".$pesan[0]."' alert-dismissable>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
        <b>".$pesan[1]."</b>
        </div>";
    }
?>

<div class="col-xs-12 margin">
    <div class="box box-primary">
        <div class="box-header">
            <h3 align="center">FORM VAKSINASI COVID-19</h3>
        </div>
        <div class="box-body">
            <?php echo form_open("pendaftaran/simpanskrining_vaksin/".$aksi,array("class"=>"form-horizontal"));?>
            <div class="form-group">
                <label class="col-sm-1 control-label">Nama Pasien</label>
                <div class="col-sm-3">
                    <input type="text" name="nama_pasien" class="form-control" readonly value="<?php echo $q1->nama_pasien1 ?>">
                </div>
                <label class="col-sm-1 control-label">No REG</label>
                <div class="col-sm-3">
                    <input type="text" name="no_reg" class="form-control" readonly value="<?php echo $no_reg ?>">
                </div>
                <label class="col-sm-1 control-label">No RM</label>
                <div class="col-sm-3">
                    <input type="text" name="no_pasien" class="form-control" readonly value="<?php echo $no_pasien ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">Tgl Lahir</label>
                <div class="col-sm-3">
                    <input type="text" name="tgl_lahir" class="form-control" readonly value="<?php echo date("d-m-Y", strtotime($q1->tgl_lahir))  ?>">
                </div>
                <label class="col-sm-1 control-label">Umur</label>
                <div class="col-sm-3">
                    <input type="text" name="umur" class="form-control" readonly value="<?php echo $umur_pasien ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">NRP/NIP/NIK</label>
                <div class="col-sm-3">
                    <input type="text" name="nik" class="form-control" readonly value="<?php echo $q1->nik; ?>">
                </div>
                <label class="col-sm-1 control-label">No Hp</label>
                <div class="col-sm-3">
                    <input type="text" name="no_hp" class="form-control" readonly value="<?php echo $q1->nohp ?>">
                </div>
                <label class="col-sm-1 control-label">Jam Periksa</label>
                <div class="col-sm-3">
                    <input type="text" name="jam" class="form-control" readonly value="<?php echo date("H:i:s", strtotime($q1->tanggal))  ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">Tgl Periksa</label>
                <div class="col-sm-3">
                    <input type="text" name="tgl_periksa" class="form-control" readonly value="<?php echo date("d-m-Y", strtotime($q1->tanggal))  ?>">
                </div>
                <label class="col-sm-1 control-label">Suhu</label>
                <div class="col-sm-3">
                    <input type="text" name="suhu" class="form-control" readonly value="<?php echo $q1->suhu ?>">
                </div>
                <label class="col-sm-1 control-label">Tekanan Darah</label>
                <div class="col-sm-3">
                    <input type="text" name="tekanan_darah" class="form-control" readonly value="<?php echo $q1->td ?>">
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-1 control-label">Alamat</label>
              <div class="col-sm-11">
                  <textarea name="alamat" class="form-control" readonly rows="3" > <?php echo $q1->alamat; ?> </textarea>
              </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <h3>Pertanyaan :</h3>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">1. Pertanyaan untuk vaksin ke-1 <br> Apakah Anda memiliki riwayat alergi berat seperti sesak napas, bengkak dan urtikaria seluruh badan atau reaksi berat lainnya karena vaksin ?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan1_1">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan1_1=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan1_1=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Pertanyaan untuk vaksin ke-2 <br> Apakah Anda memiliki riwayat alergi berat setelah divaksinasi COVID-19 sebelumnya ?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan1_2">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan1_2=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan1_2=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">2. Apakah Anda sedang hamil?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan2">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan2=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan2=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">3. Apakah Anda mengidap penyakit autoimun seperti asma, lupus?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan3">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan3=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan3=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">4. Apakah Anda sedang mendapat pengobatan untuk gangguan pembekuan darah, kelainan darah, defisiensi imun dan penerima produk darah/tranfusi?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan4">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan4=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan4=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">5. Apakah Anda sedang mendapat pengobatan immunosuppresant seperti kortikosteroid dan kemoterapi?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan5">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan5=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan5=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">6. Apakah Anda memiliki penyakit jantung berat dalam keadaan sesak?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan6">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan6=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan6=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <?php if ($year_diff>60) : ?>
            <div class="form-group">
                <label class="col-sm-6 control-label">7. Pertanyaan tambahan bagi sasaran lansia (>60 tahun) : <br>&nbsp;&nbsp;&nbsp;&nbsp;1. Apakah Anda mengalami kesulitan untuk menaiki 10 anak tangga?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan7_1">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan7_1=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan7_1=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">&nbsp;&nbsp;&nbsp;&nbsp;2. Apakah Anda sering merasa kelelahan?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan7_2">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan7_2=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan7_2=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">&nbsp;&nbsp;&nbsp;&nbsp;3. Apakah Anda memiliki paling sedikit 5 dari 11 penyakit (Hipertensi, diabetes, kanker, penyakit paru kronis, serangan jantung kongestif, nyeri dada, asma, nyeri sendi, stroke dan penyakit ginjal)?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan7_3">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan7_3=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan7_3=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">&nbsp;&nbsp;&nbsp;&nbsp;4. Apakah Anda mengalami kesulitan berjalan kira-kira 100 sd 200 meter?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan7_4">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan7_4=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan7_4=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">&nbsp;&nbsp;&nbsp;&nbsp;5. Apakah Anda mengalami penurunan berat badan yang bermakna dalam setahun ini?</label>
                <div class="col-md-2">
                      <select class="form-control" name="pertanyaan7_5">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($pertanyaan7_5=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($pertanyaan7_5=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <?php endif ?>
            <div class="form-group">
                <label class="col-sm-6 control-label">Apakah Anda Bersedia melaksanakan vaksin, diobservasi selama 30 menit dan kembali untuk vaksin sesuai jadwal yang telah ditetapkan?</label>
                <div class="col-md-2">
                      <select class="form-control" name="bersedia">
                          <option value=""></option>
                          <option value="Ya" <?php echo ($bersedia=="Ya" ? "selected" : "");?>>Ya</option>
                          <option value="Tidak" <?php echo ($bersedia=="Tidak" ? "selected" : "");?>>Tidak</option>
                      </select>
                </div>
            </div>
            <!-- <div class="form-group">
                <label class="col-sm-3 control-label">Anamnesa dan riwayat terapi jika ada:</label>
                <div class="col-sm-5">
                    <textarea name="anamnesa" class="form-control" rows="3" > <?php echo $anamnesa; ?> </textarea>
                </div>
            </div> -->
            <div class="form-group">
                <label class="col-sm-3 control-label">Tanda Tangan</label><br>
                <div class="col-xs-5">
                    <input type="hidden" name="ttd" id="signatureJSON">
                    <input type="hidden" name="hapusttd" value="<?php echo $tandatangan;?>">
                    <div id="signature"></div>
                </div>
            </div>
            <div class="form-group">
            <div class="col-xs-2"><button type="button" class="btn btn-danger hapusttd">Clear</div>
            </div>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <div class="btn-group">
                    <!-- <button class="back btn btn-danger" type="button"><i class="fa fa-arrow-left"></i> Back</button> -->
                    <?php if ($aksi!="edit"): ?>
                      <button class="btn btn-primary" type="submit"><i class="fa fa-print"></i> Simpan</button>
                    <?php endif ?>
                    <?php if ($aksi=="edit"): ?>
                        <button class="cetak btn btn-success" type="button"><i class="fa fa-print"></i> Cetak</button>
                        <!-- <button class="cetak2 btn btn-success" type="button"><i class="fa fa-print"></i> Cetak Hak & Kewajiban Pasien</button> -->
                    <?php endif ?>
                </div>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
</html>
<style type="text/css">
#signature{
    width: 100%;
    height: 100px;
    border: 1px solid black;
}

</style>
