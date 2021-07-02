
<script>
    var mywindow;

    function openCenteredWindow(url) {
        var width = 800;
        var height = 500;
        var left = parseInt((screen.availWidth / 2) - (width / 2));
        var top = parseInt((screen.availHeight / 2) - (height / 2));
        var windowFeatures = "width=" + width + ",height=" + height +
        ",status,resizable,left=" + left + ",top=" + top +
        ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow = window.open(url, "subWind", windowFeatures);
    }
    $(document).ready(function() {
        $(".cetak").click(function() {
            var no_reg = $("[name='no_reg']").val();
            var no_rm = $("[name='no_rm']").val();
            var url = "<?php echo site_url('perawat/cetakform_ab'); ?>/" + no_reg + "/" + no_rm;
            openCenteredWindow(url);
        });
        $(".hapus").click(function(){
           $(".modal").show();
       });
       $(".tidak").click(function(){
           $(".modal").hide();
       });
       $(".ya").click(function(){
            // var id= $(".bg-gray").attr("href");
            window.location="<?php echo site_url('perawat/pasieninap');?>";
           return false;
       });


        getttd();
    });
</script>
<?php
$t1 = new DateTime('today');
$t2 = new DateTime($q->tgl_lahir);
$y  = $t1->diff($t2)->y;
$m  = $t1->diff($t2)->m;
$d  = $t1->diff($t2)->d;
$umur = $y." tahun ".$m." bulan ".$d." hari";


if ($q2 > 0) {
    $assesment1             = $q2->assesment1;
    $assesment2             = $q2->assesment2;
    $assesment3             = $q2->assesment3;
    $assesment4             = $q2->assesment4;
    $assesment5             = $q2->assesment5;
    $assesment6             = $q2->assesment6;
    $assesment7             = $q2->assesment7;
    $assesment8             = $q2->assesment8;
    $assesment9             = $q2->assesment9;
    $assesment10            = $q2->assesment10;
    $assesment11            = $q2->assesment11;
    $assesment12            = $q2->assesment12;
    $identifikasi           = $q2->identifikasi;
    $perencanaan            = $q2->perencanaan;
    $no_reg                 = $q->no_reg;
    $no_pasien              = $q->no_pasien;
    $skrining               = explode(",",$q2->skrining);
    if ($q2->status) $ubah = "readonly";
    else $ubah = "";
    $aksi = "edit";
} else {
    $tanggal        = date("d-m-Y");
    $assesment1     =
    $assesment2     =
    $assesment3     =
    $assesment4     =
    $assesment5     =
    $assesment6     =
    $assesment7     =
    $assesment8     =
    $assesment9     =
    $assesment10    =
    $assesment11    =
    $assesment12    =
    $identifikasi   =
    $perencanaan    =
    $ubah		    =
    $skrining       = "";
    $aksi = "simpan";
}

?>
<?php
if ($this->session->flashdata('message')) {
    $pesan = explode('-', $this->session->flashdata('message'));
    echo "<div class='alert alert-" . $pesan[0] . "' alert-dismissable>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
        <b>" . $pesan[1] . "</b>
        </div>";
}
?>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="form-horizontal">
            <div class="box-body">
               <?php echo form_open("perawat/simpanform_a/" . $aksi, array("class" => "form-horizontal")); ?>
               <div class="form-group">
                <label class="col-md-2 control-label">No. RM</label>
                <div class="col-md-4">
                    <input type="hidden" name="no_reg" value="<?php echo $no_reg ?>">
                    <input type="hidden" name="tanggal" value="<?php echo date("d-m-Y", strtotime($tanggal)) ?>">
                    <input type="text" class="form-control" name='no_rm' readonly value="<?php echo $q->no_pasien;?>"/>
                </div>
                <label class="col-md-2 control-label">Nama Pasien</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_pasien1;?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Tgl Lahir / Umur</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name='tgl_lahir' readonly value="<?php echo date("d-m-Y",strtotime($q->tgl_lahir)).' / '.$umur;?>"/>
                </div>
                <label class="col-md-2 control-label">Jenis Kelamin</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name='jk' readonly value="<?php echo $q->jenis_kelamin;?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header" align="center"><b><h2 class="box-title">Skrining</h2></b></div>
    <div class="box-body">
        <div class="form-group">
            <div class="col-sm-6"><br>
                <div class="form-check">
                    <input type="checkbox" name="skrining1" value="Usia > 65 tahun" <?php echo (isset($skrining[0]) && $skrining[0] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Usia > 65 tahun</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining2" value="Pasien dengan fungsi kognitif rendah" <?php echo (isset($skrining[1]) && $skrining[1] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Pasien dengan fungsi kognitif rendah</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining3" value="Pasien resiko tinggi" <?php echo (isset($skrining[2]) && $skrining[2] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Pasien resiko tinggi</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining4" value="Potensi komplain tinggi" <?php echo (isset($skrining[3]) && $skrining[3] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Potensi komplain tinggi</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining5" value="Kasus penyakit kronis,katastropik, terminal" <?php echo (isset($skrining[4]) && $skrining[4] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Kasus penyakit kronis,katastropik, terminal</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining6" value="Status fungsional rendah, kebutuhan bantuan ADL" <?php echo (isset($skrining[5]) && $skrining[5] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Status fungsional rendah, kebutuhan bantuan ADL</label>
                </div>                    
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <div class="form-check">
                    <input type="checkbox" name="skrining7" value="Riwayat gangguan mental" <?php echo (isset($skrining[6]) && $skrining[6] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Riwayat gangguan mental</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining8" value="Riwayat penggunaan peralatan medis" <?php echo (isset($skrining[7]) && $skrining[7] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Riwayat penggunaan peralatan medis</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining9" value="Sering masuk IGD, readmisi RS" <?php echo (isset($skrining[8]) && $skrining[8] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Sering masuk IGD, readmisi RS</label>
                </div> 
                <div class="form-check">
                    <input type="checkbox" name="skrining10" value="Perkiraan asuhan dengan biaya tinggi" <?php echo (isset($skrining[9]) && $skrining[9] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Perkiraan asuhan dengan biaya tinggi</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining11" value="Masalah finansial" <?php echo (isset($skrining[10]) && $skrining[10] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Masalah finansial</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="skrining12" value="Hari rawat yang panjang" <?php echo (isset($skrining[11]) && $skrining[11] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Hari rawat yang panjang</label>
                </div> 
                <div class="form-check">
                    <input type="checkbox" name="skrining13" value="Membutuhkan kontinuitas pelayanan" <?php echo (isset($skrining[12]) && $skrining[12] != "" ? "checked" : "");?>>
                    <label class="form-check-label">Membutuhkan kontinuitas pelayanan</label>
                </div>                    
            </div>
        </div>
    </div>
</div>    
<div class="box box-primary">
    <div class="box-header" align="center"><b><h3 class="box-title">Assesment</h3></b></div>
    <div class="box-body">
        <div class="form-horizontal">
            <div class='form-group'>
                <label class="col-sm-2 control-label">1. Fisik, Fungsional, Kognitif, kekuatan-kemampuan,kemandirian.</label>
                <div class="col-sm-3">
                    <input type="text" required name="assesment1" class="form-control" value="<?php echo $assesment1 ?>" <?php echo $ubah; ?>>
                </div>
                <label class="col-sm-2 control-label">2. Riwayat Kesehatan</label>
                <div class="col-sm-3">
                    <input type="text" required name="assesment2" class="form-control" value="<?php echo $assesment2 ?>" <?php echo $ubah; ?>>
                </div>
            </div> 
        </div>
        <div class="form-horizontal">
            <div class="form-horizontal">
                <div class='form-group'>
                 <label class="col-sm-2 control-label">3. Perilaku Psiko-Sosio-Kultural</label>
                 <div class="col-sm-3">
                    <input type="text" required name="assesment3" class="form-control" value="<?php echo $assesment3 ?>" <?php echo $ubah; ?>>
                </div>
                <label class="col-sm-2 control-label">4. Kesehatan Mental</label>
                <div class="col-sm-3">
                    <input type="text" required name="assesment4" class="form-control" value="<?php echo $assesment4 ?>" <?php echo $ubah; ?>>
                </div>
            </div> 
        </div>
        <div class="form-horizontal">
            <div class="form-horizontal">
                <div class='form-group'>
                 <label class="col-sm-2 control-label">5. Tersedianya dukungan kelurga, kemampuan merawat dari pemberi asuhan</label>
                 <div class="col-sm-3">
                    <input type="text" required name="assesment5" class="form-control" value="<?php echo $assesment5 ?>" <?php echo $ubah; ?>>
                </div>
                <label class="col-sm-2 control-label">6. Finansial</label>
                <div class="col-sm-3">
                    <input type="text" required name="assesment6" class="form-control" value="<?php echo $assesment6 ?>" <?php echo $ubah; ?>>
                </div>
            </div>
        </div>
        <div class="form-horizontal">
            <div class="form-horizontal">
                <div class='form-group'>
                 <label class="col-sm-2 control-label">7. Status Asuransi</label>
                 <div class="col-sm-3">
                    <input type="text" required name="assesment7" class="form-control" value="<?php echo $assesment7 ?>" <?php echo $ubah; ?>>
                </div>
                <label class="col-sm-2 control-label">8. Riwayat penggunaan obat, Alternatif</label>
                <div class="col-sm-3">
                    <input type="text" required name="assesment8" class="form-control" value="<?php echo $assesment8 ?>" <?php echo $ubah; ?>>
                </div>
            </div>
        </div>
        <div class="form-horizontal">
            <div class="form-horizontal">
                <div class='form-group'>
                 <label class="col-sm-2 control-label">9. Riwayat taruma, Kekerasan</label>
                 <div class="col-sm-3">
                    <input type="text" required name="assesment9" class="form-control" value="<?php echo $assesment9 ?>" <?php echo $ubah; ?>>
                </div>
                <label class="col-sm-2 control-label">10. Pemahaman tentang kesehatan</label>
                <div class="col-sm-3">
                    <input type="text" required name="assesment10" class="form-control" value="<?php echo $assesment10 ?>" <?php echo $ubah; ?>>
                </div>
            </div>
        </div>
        <div class="form-horizontal">
            <div class="form-horizontal">
                <div class='form-group'>
                 <label class="col-sm-2 control-label">11. Harapan hasil asuhan, kemampuan menerima perubahan</label>
                 <div class="col-sm-3">
                    <input type="text" required name="assesment11" class="form-control" value="<?php echo $assesment11 ?>" <?php echo $ubah; ?>>
                </div>
                <label class="col-sm-2 control-label">12. Aspek Legal</label>
                <div class="col-sm-3">
                    <input type="text" required name="assesment12" class="form-control" value="<?php echo $assesment12 ?>" <?php echo $ubah; ?>>
                </div>
            </div> 
        </div>
        <div class="form-group">
                <label class="col-md-12 control-label" style="font-size: larger;text-align:center;"><u>Identifikasi Masalah-Risiko-Kesempatan</u></label>
                <div class="col-md-12">
                    <textarea class="form-control" name="identifikasi" style="max-width: 100%;height:300px;"><?php echo $identifikasi ?><?php echo $ubah; ?></textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
                <label class="col-md-12 control-label"style="font-size: larger;text-align:center;"><u>Perencanaan Manager Pelayanan Pasien</u></label>
                <div class="col-md-12">
                    <textarea class="form-control" name="perencanaan" style="max-width: 100%;height:300px;"><?php echo $perencanaan ?><?php echo $ubah; ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>             
<div class="box-footer">
    <div class="row">
        <div class="col-xs-12">
            <div class="pull-right">
                <div class="btn-group">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
                        <button class="hapus btn btn-danger" type="button"><i class="fa fa-trash"></i>Hapus</button>
                    </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<div class='modal'>
   <div class='modal-dialog'>
       <div class='modal-content'>
           <div class="modal-header bg-orange"><h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;NOTIFICATION</h4></div>
           <div class='modal-body'>Yakin akan menghapus data ?</div>
           <div class='modal-footer'>
               <button class="ya btn btn-sm btn-danger">Ya</button>
                <button class="tidak btn btn-sm btn-success">Tidak</button>
           </div>
       </div>
   </div>
</div>
</div>
<style type="text/css">
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        margin-top: -15px;
    }
    .select2-container--default .select2-selection--single{
        padding: 16px 0px;
        border-color: #d2d6de;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
</style>