<script type="text/javascript" src="<?php echo base_url()?>plugins/input-mask/jquery.inputmask.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/plugins/input-mask/jquery.inputmask.extensions.js"></script>
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
    $(document).ready(function(){
        if ($("[name='bak']").val()=="KATETER"){
            $("#kateter").removeClass("hide");
        }
        else {
            $("#kateter").addClass("hide");
        }
        $("#cc").addClass("hide");
        $("#dietkhusus").addClass("hide");
        $("#puasa").addClass("hide");
        $.each($("#diet"),function(key,value){
            $.each(value,function(key1,value1){
                if (value1=="BATASAN CAIRAN") {
                    $("#cc").removeClass("hide");
                }
                if (value1=="DIET KHUSUS") {
                    $("#dietkhusus").removeClass("hide");
                }
                if (value1=="PUASA") {
                    $("#puasa").removeClass("hide");
                    $("input[name='jam_puasa1']").inputmask("hh:mm:ss", {"placeholder": "00:00:0000 "});
                    $("input[name='jam_puasa2']").inputmask("hh:mm:ss", {"placeholder": "00:00:0000 "});
                }
            })
        })
        $("input[name='obs_jam']").inputmask("hh:mm:ss", {"placeholder": "00:00:0000 "});
    	$(".cetak").click(function(){
            var no_pasien 		= $("[name='no_pasien']").val();
            var no_reg 			= $("[name='no_reg']").val();
            var id_pindahkamar 	= $("[name='id_pindahkamar']").val();
            var url = "<?php echo site_url('perawat/cetakpemindahan_pasien');?>/"+no_pasien+"/"+no_reg+"/"+id_pindahkamar;
            openCenteredWindow(url);
        });
        $("[name='bak']").change(function(){
            if ($("[name='bak']").val()=="KATETER"){
                $("#kateter").removeClass("hide");
            }
            else {
                $("#kateter").addClass("hide");
            }
            return false;
        });
        $("[name='mobilitas']").change(function(){
            if ($("[name='mobilitas']").val()=="Lain-lain"){
                $("#mobilitas_lain").removeClass("hide");
            }
            else {
                $("#mobilitas_lain").addClass("hide");
            }
            return false;
        });
        $(".diet").change(function(){
            $("#cc").addClass("hide");
            $("#dietkhusus").addClass("hide");
            $("#puasa").addClass("hide");
            $.each($(".diet"),function(key,value){
                $.each($(this).val(),function(key1,value1){
                    if (value1=="BATASAN CAIRAN") {
                        $("#cc").removeClass("hide");
                    }
                    if (value1=="DIET KHUSUS") {
                        $("#dietkhusus").removeClass("hide");
                    }
                    if (value1=="PUASA") {
                        $("#puasa").removeClass("hide");
                        $("input[name='jam_puasa1']").inputmask("hh:mm:ss", {"placeholder": "00:00:0000 "});
                        $("input[name='jam_puasa2']").inputmask("hh:mm:ss", {"placeholder": "00:00:0000 "});
                    }
                })
            })
        });
        $(".tindakan_radiologi").select2();
        $(".tindakan_lab").select2();
        $(".prosedur_pembedahan").select2();
        $(".precaution").select2();
        $(".diet").select2();
        $(".bab").select2();
        $(".tindakan_khusus").select2();
        $(".note").select2();
        $(".lokasi").select2();
        $(".back").click(function(){
            var jenis = "<?php echo $jenis;?>";
            if (jenis=="igd"){
                window.location ="<?php echo site_url('perawat/pasienigd');?>/";
            } else 
            if (jenis=="ralan"){
                window.location ="<?php echo site_url('perawat/pasienralan');?>/";
            } else 
            if (jenis=="ranap"){
                window.location ="<?php echo site_url('perawat/pasieninap');?>/";
            }
            return false;
        });
        $(".backpi").click(function(){
            window.location ="<?php echo site_url('pendaftaran/rawat_inap');?>/";
            return false;
        });
        $(".edit").click(function(){
            var no_pasien = $("input[name='no_pasien']").val();
            var no_reg = $("input[name='no_reg']").val();
            var id_pindahkamar = $("input[name='id_pindahkamar']").val();
            var id = $(this).attr("href");
            var back = "<?php echo $back;?>";
            window.location ="<?php echo site_url('perawat/inap');?>/"+no_pasien+"/"+no_reg+"/"+id_pindahkamar+"/"+back+"/"+id;
            return false;
        });
        var formattgl = "yy-mm-dd";
        $("input[name='tglpasang_kateter']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='tanggal']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='tanggal_pemasangan']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='obs_tgl']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='tgl_pemesanan_pemasangan']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='tgl_prosedur']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='pukul']").inputmask("hh:mm:ss", {"placeholder": "00:00:0000 "});
        $("input[name='reservasi_terakhir']").inputmask("hh:mm:ss", {"placeholder": "00:00:0000 "});
        $(".terapi").wysihtml5({
            toolbar: {
                "fa": false,
                "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": false, //Italics, bold, etc. Default true
                "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": false, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false, //Blockquote  
                "lists": false, //Blockquote  
            }
        });
        $(".intervensi_medis").wysihtml5({
            toolbar: {
                "fa": false,
                "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": false, //Italics, bold, etc. Default true
                "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": false, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false, //Blockquote  
                "lists": false, //Blockquote  
            }
        });
        $(".masalah_keperawatan").wysihtml5({
            toolbar: {
                "fa": false,
                "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": false, //Italics, bold, etc. Default true
                "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": false, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false, //Blockquote  
                "blockquote": false, //Blockquote  
                "lists": false, //Blockquote  
            }
        });
    });
</script>
<?php
    list($year,$month,$day) = explode("-",$q->tgl_lahir);
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
    $umur = $year_diff." tahun ";

    $doktersp = array();
    foreach ($sp->result() as $value) {
        $doktersp[] = $value;
    }

    if ($q1) {
        $tiba_diruangan         = $q1->tiba_diruangan;
        $dari_ruangan           = $q1->dari_ruangan;
        $tanggal                = $q1->tanggal;
        $pukul                  = $q1->pukul;
        $diagnosa               = $q1->diagnosa;
        $dokter1                = $q1->dokter1;
        $dokter2                = $q1->dokter2;
        $dokter3                = $q1->dokter3;
        $penjelasan_diagnosa    = $q1->penjelasan_diagnosa;
        $masalah_keperawatan    = $q1->masalah_keperawatan;
        $prosedur_pembedahan    = $q1->prosedur_pembedahan;
        $tgl_prosedur           = $q1->tgl_prosedur;
        $riwayat_alergi         = $q1->riwayat_alergi;
        $nama_obat              = $q1->nama_obat;
        $riwayat_reaksi         = $q1->riwayat_reaksi;
        $intervensi_medis       = $q1->intervensi_medis;
        $hasil_abnormal         = $q1->hasil_abnormal;
        $precaution             = $q1->precaution;
        $konsultasi             = $q1->konsultasi;
        $pemeriksaan_lab        = $q1->pemeriksaan_lab;
        $rencana_tindakan       = $q1->rencana_tindakan;
        $note                   = $q1->note;
        $reservasi_terakhir     = $q1->reservasi_terakhir;
        $gcs                    = $q1->gcs;
        $e                      = $q1->e;
        $v                      = $q1->v;
        $m                      = $q1->m;
        $pupil                  = $q1->pupil;
        $kiri                   = $q1->kiri;
        $td_kanan               = $q1->td_kanan;
        $td_kiri                = $q1->td_kiri;
        $nadi                   = $q1->nadi;
        $respirasi              = $q1->respirasi;
        $suhu                   = $q1->suhu;
        $spo2                   = $q1->spo2;
        $diet                   = $q1->diet;
        $batasancairan          = $q1->batasancairan;
        $dietkhusus             = $q1->dietkhusus;
        $puasa                  = $q1->puasa;
        $jam_puasa1             = $q1->jam_puasa1;
        $jam_puasa2             = $q1->jam_puasa2;
        $bab                    = $q1->bab;
        $bak                    = $q1->bak;
        $transfer               = $q1->transfer;
        $mobilitas              = $q1->mobilitas;
        $mobilitas_lain         = $q1->mobilitas_lain;
        $gangguan_indra         = $q1->gangguan_indra;
        $alat_bantu             = $q1->alat_bantu;
        $infus                  = $q1->infus;
        $lokasi                 = $q1->lokasi;
        $tanggal_pemasangan     = $q1->tanggal_pemasangan;
        $hal_istimewa           = $q1->hal_istimewa;
        $tindakan_khusus        = $q1->tindakan_khusus;
        $peralatan_khusus       = $q1->peralatan_khusus;
        $jenis_kateter          = $q1->jenis_kateter;
        $nomor_kateter          = $q1->nomor_kateter;
        $tglpasang_kateter      = $q1->tglpasang_kateter;
        $terapi                 = $q1->terapi;
        $action                 = "edit";

    } else {
        $tiba_diruangan         = $q2->ruangbaru;
        $dari_ruangan           = ($q2->ruanglama==NULL ? $q->prosedur_masuk : $q2->ruangbaru);
        $tanggal                = ($q2->tanggal==NULL ? $q->tgl_masuk : $q2->tanggal);
        $pukul                  = ($q2->jam==NULL ? $q->jam_masuk : $q2->jam);
        $diagnosa               = $q->a;
        $dokter1                = (isset($doktersp[0]) ? $doktersp[0]->dokter_konsul : "");
        $dokter2                = (isset($doktersp[1]) ? $doktersp[1]->dokter_konsul : "");
        $dokter3                = (isset($doktersp[2]) ? $doktersp[2]->dokter_konsul : "");
        $masalah_keperawatan    = $ap->a;
        $riwayat_alergi         = ($q->riwayat_alergi==NULL ? "TIDAK" : "IYA");
        $nama_obat              = $q->riwayat_alergi;
        $intervensi_medis       = $ap->p;
        $td_kanan               = $q->td;
        $td_kiri                = $q->td2;
        $nadi                   = $q->nadi;
        $respirasi              = $q->respirasi;
        $suhu                   = $q->suhu;
        $spo2                   = $q->spo2;
        $gcs                    = $q->gcs;
        $e                      = $q->e;
        $v                      = $q->v;
        $m                      = $q->m;
        $reservasi_terakhir     = $q2->jam;
        $precaution             = $q->resiko_jatuh;
        $rencana_tindakan       = $q->p;
        $riwayat_reaksi         = $q->riwayat_alergi;
        $penjelasan_diagnosa    = 
        $prosedur_pembedahan    = 
        $tgl_prosedur           = 
        $note                   = 
        $diet                   = 
        $batasancairan          =
        $dietkhusus             = 
        $puasa                  =
        $jam_puasa1             =
        $jam_puasa2             =
        $bab                    = 
        $bak                    = 
        $transfer               = 
        $mobilitas              = 
        $mobilitas_lain         =
        $gangguan_indra         = 
        $alat_bantu             = 
        $infus                  = 
        $lokasi                 = 
        $tanggal_pemasangan     = 
        $hal_istimewa           = 
        $tindakan_khusus        = 
        $jenis_kateter          = 
        $nomor_kateter          =
        $tglpasang_kateter      =
        $peralatan_khusus       = "";
        $action                 = "simpan";
        $kel                    = explode("|", $q->kelainan);
        $hasil_abnormal         = "Kepala : ".$kel[0]." Mata : ".$kel[1]." THT : ".$kel[2]." Gigi Mulut : ".$kel[3]." Leher : ".$kel[4]." Thoraks : ".$kel[5]." Abdomen ".$kel[6]." Ekstrimitas Atas : ".$kel[7]." Ekstrimitas Bawah : ".$kel[8]." Gentalia : ".$kel[9]." Anus : ". $kel[9];
        $kiri                   = 
        $pupil                  = $kel[1];
        $konsultasi             = (isset($doktersp[0]) ? $doktersp[0]->dokter_konsul : "");
        $tindakan_radiologi     = $q->tindakan_radiologi;
        $tindakan_lab           = $q->tindakan_lab;
        $pemeriksaan_lab        = $tindakan_radiologi.",".$tindakan_lab;

    }
        $read1                   = ($q->riwayat_alergi==NULL ? "" : "READONLY");
    
?>
<div class="col-md-12">
    <?php
        if($this->session->flashdata('message')){
            $pesan=explode('-', $this->session->flashdata('message'));
            echo "<div class='alert alert-".$pesan[0]."' alert-dismissable>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <b>".$pesan[1]."</b>
            </div>";
        }

    ?>
    <?php echo form_open("perawat/simpanpemindahan/".$action,array("class"=>"form-horizontal"));?>
    <input type="hidden" name="id_pindahkamar" value="<?php echo $id_pindahkamar ?>">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="no_reg" value="<?php echo $no_reg ?>">
    <input type="hidden" name="back" value="<?php echo $back ?>">
    <div class="box box-primary no-print">
        <div class="form-horizontal">
            <div class="box-body">
            	<div class="form-group">
                    <label class="col-md-2 control-label">No. RM</label>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control" name='no_pasien' value="<?php echo $q->no_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Nama Pasien</label>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control" name='nama_pasien' value="<?php echo $q->nama_pasien1;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Tgl Lahir / Umur</label>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control" name='nama_pasien' value="<?php echo date("d-m-Y",strtotime($q->tgl_lahir)).' / '.$umur;?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">SITUATION</h3>
            <div class='pull-right box-tools'>
            <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
        </div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">Tiba di ruangan</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="tiba_diruangan" value="<?php echo $tiba_diruangan ?>" readonly/>
                    </div>
                    <label class="col-md-2 control-label">Dari ruangan</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="dari_ruangan" value="<?php echo ($dari_ruangan=="UGD" ? "IGD" : $dari_ruangan) ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Tanggal</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="tanggal" value="<?php echo $tanggal ?>" readonly/>
                    </div>
                    <label class="col-md-1 control-label">Pukul</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="pukul" value="<?php echo $pukul ?>" readonly/>
                    </div>
                    <label class="col-md-1 control-label">Diagnosa</label>
                    <div class="col-md-4">
                        <textarea class="form-control" name="diagnosa" readonly><?php echo $diagnosa ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Dokter yang merawat <span class="pull-right">1.</span></label>
                    <div class="col-md-2">
                    	<input type="hidden" class="form-control" name="dokter1" value="<?php echo $dokter1 ?>"/>
                        <select class="form-control" disabled>
	                        <option value="">---</option>
	                        <?php 
	                            foreach ($dokter->result() as $key){
	                                echo "<option value = '".$key->id_dokter."' ".(isset($doktersp[0]) ? ($key->id_dokter==$doktersp[0]->dokter_konsul ? "selected" : "") : "").">".$key->nama_dokter."</option>";
	                            }    
	                        ?>
	                    </select>
                    </div>
                    <label class="col-md-1 control-label"><span class="pull-right">2.</span></label>
                    <div class="col-md-3">
                        <input type="hidden" class="form-control" name="dokter2" value="<?php echo $dokter2 ?>"/>
                        <select class="form-control" disabled>
	                        <option value="">---</option>
	                        <?php 
	                            foreach ($dokter->result() as $key){
	                                echo "<option value = '".$key->id_dokter."' ".(isset($doktersp[1]) ? ($key->id_dokter==$doktersp[1]->dokter_konsul ? "selected" : "") : "").">".$key->nama_dokter."</option>";
	                            }    
	                        ?>
	                    </select>
                    </div>
                    <label class="col-md-1 control-label"><span class="pull-right">3.</span></label>
                    <div class="col-md-3">
                        <input type="hidden" class="form-control" name="dokter3" value="<?php echo $dokter3 ?>"/>
                        <select class="form-control" disabled="">
	                        <option value="">---</option>
	                        <?php 
	                            foreach ($dokter->result() as $key){
	                                echo "<option value = '".$key->id_dokter."' ".(isset($doktersp[2]) ? ($key->id_dokter==$doktersp[2]->dokter_konsul ? "selected" : "") : "").">".$key->nama_dokter."</option>";
	                            }    
	                        ?>
	                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Pasien sudah dijelaskan mengenai diagnosa :</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name="penjelasan_diagnosa" value="<?php echo $penjelasan_diagnosa ?>"/> -->
                        <select class="form-control" name="penjelasan_diagnosa">
                            <option value="SUDAH" <?php if ($penjelasan_diagnosa=="SUDAH"): ?>
                                selected
                            <?php endif ?>>SUDAH</option>
                            <option value="BELUM" <?php if ($penjelasan_diagnosa=="BELUM"): ?>
                                selected
                            <?php endif ?>>BELUM</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Masalah keperawatan yang utama saat ini</label>
                    <div class="col-md-10">
                        <input type="hidden" class="form-control" name="masalah_keperawatan" value="<?php echo $masalah_keperawatan ?>"/>
                        <textarea class="form-control masalah_keperawatan" style="max-width: 100%;height:160px;" disabled><?php echo $masalah_keperawatan ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Prosedur pembedahan/ invasive yang akan/ sudah dilakukan</label>
                    <div class="col-md-4">
                        <!-- <input type="text" class="form-control" name="prosedur_pembedahan" value="<?php echo $prosedur_pembedahan ?>"/> -->
                        <select class="form-control prosedur_pembedahan"  name="prosedur_pembedahan[]" multiple="multiple">
                            <?php
                                foreach ($tr->result() as $val) {
                                    $pb = explode(",", $prosedur_pembedahan);
                                    if (count($pb)>0){
                                        foreach ($pb as $k => $value) {
                                            echo "<option value='".$val->kode_tindakan."' ".($val->kode_tindakan==$value ? "selected" : "").">".$val->nama_tindakan."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$val->kode_tindakan."' ".($val->kode_tindakan==$prosedur_pembedahan ? "selected" : "").">".$val->nama_tindakan."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <label class="col-md-2 control-label tgl">Tanggal</label>
                    <div class="col-md-4 tgl">
                        <input type="text" class="form-control" name="tgl_prosedur" value="<?php echo $tgl_prosedur ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header"><h3 class="box-title">BACKGROUND</h3></div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">Riwayat alergi/ reaksi obat</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="riwayat_alergi" value="<?php echo $riwayat_alergi ?>" readonly/>
                    </div>
                    <label class="col-md-2 control-label">Nama Obat / Alergi</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nama_obat" value="<?php echo $nama_obat ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Riwayat reaksi :</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="riwayat_reaksi" value="<?php echo $riwayat_reaksi ?>" <?php echo $read1 ?>/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Intervensi medis/ keperawatan :</label>
                    <div class="col-md-10">
                        <input type="hidden" class="form-control" name="intervensi_medis" value="<?php echo $intervensi_medis ?>"/>
                        <textarea class="form-control intervensi_medis" style="max-width: 100%;height:160px;" disabled ><?php echo $intervensi_medis ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Hasil investigasi abnormal</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name="hasil_abnormal" value="<?php echo $hasil_abnormal ?>"/> -->
                        <textarea class="form-control" name="hasil_abnormal" style="max-width: 100%;height:160px;" readonly><?php echo $hasil_abnormal ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Kewaspadaan/ Precaution</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name="precaution" value="<?php echo $precaution ?>" readonly/> -->
                        <select class="form-control precaution"  name="precaution[]" multiple="multiple">
                            <?php
                                foreach ($pre->result() as $val) {
                                    $pb = explode(",", $precaution);
                                    if (count($pb)>0){
                                        foreach ($pb as $k => $value) {
                                            echo "<option value='".$val->keterangan."' ".($val->keterangan==$value ? "selected" : "").">".$val->keterangan."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$val->keterangan."' ".($val->keterangan==$precaution ? "selected" : "").">".$val->keterangan."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header"><h3 class="box-title">ASSESSMENT</h3></div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">Reservasi terakhir pukul</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" value="<?php echo $reservasi_terakhir ?>" name="reservasi_terakhir" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">GCS :</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="<?php echo $gcs ?>" name="gcs" readonly/>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="col-md-2 control-label">E</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="<?php echo $e ?>" name="e" readonly/>
                            </div>
                            <label class="col-md-2 control-label">V</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="<?php echo $v ?>" name="v" readonly/>
                            </div>
                            <label class="col-md-2 control-label">M</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="<?php echo $m ?>" name="m" readonly/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Pupil & Reaksi cahaya Kanan</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="<?php echo $pupil ?>" name="pupil" readonly/>
                    </div>
                    <label class="col-md-1 control-label">Kiri</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" value="<?php echo $kiri ?>" name="kiri" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">TD Kanan</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='td_kanan' value="<?php echo $td_kanan;?>" readonly/>
                    </div>
                    <label class="col-md-2 control-label">TD Kiri</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='td_kiri' value="<?php echo $td_kiri;?>" readonly/>
                    </div>
                    <label class="col-md-2 control-label">Nadi</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nadi' value="<?php echo $nadi;?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Respirasi</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='respirasi' value="<?php echo $respirasi;?>" readonly/>
                    </div>
                    <label class="col-md-2 control-label">Suhu</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='suhu' value="<?php echo $suhu;?>" readonly/>
                    </div>
                    <label class="col-md-2 control-label">SpO2</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='spo2' value="<?php echo $spo2;?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Diet/ nutrisi</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='diet' value="<?php echo $diet;?>"/> -->
                        <select class="form-control diet"  name="diet[]" multiple="multiple" id="diet">
                            <?php
                                foreach ($dn->result() as $val) {
                                    $pb = explode(",", $diet);
                                    if (count($pb)>0){
                                        foreach ($pb as $k => $value) {
                                            echo "<option value='".$val->keterangan."' ".($val->keterangan==$value ? "selected" : "")." data-id='".$val->isi."'>".$val->keterangan."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$val->keterangan."' ".($val->keterangan==$diet ? "selected" : "")." data-id='".$val->isi."'>".$val->keterangan."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="cc">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Batasan Cairan</label>
                        <div class="col-md-10">
                            <input type="number" class="form-control" name='batasancairan' value="<?php echo $batasancairan;?>"/>
                        </div>
                    </div>
                </div>
                <div id="dietkhusus">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Diet Khusus</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name='dietkhusus' value="<?php echo $dietkhusus;?>"/>
                        </div>
                    </div>
                </div>
                <div id="puasa">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Puasa</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name='puasa' value="<?php echo $puasa;?>"/>
                        </div>
                        <label class="col-md-2 control-label">Jam</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='jam_puasa1' value="<?php echo $jam_puasa1;?>"/>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='jam_puasa2' value="<?php echo $jam_puasa2;?>"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">BAB</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='ab' value="<?php echo $ab;?>"/> -->
                        <select class="form-control bab"  name="bab[]" multiple="multiple">
                            <?php
                                foreach ($bb->result() as $val) {
                                    $pbab = explode(",", $bab);
                                    if (count($pbab)>0){
                                        foreach ($pbab as $k => $value) {
                                            echo "<option value='".$val->keterangan."' ".($val->keterangan==$value ? "selected" : "").">".$val->keterangan."</option>";
                                        }
                                    } 
                                    else {
                                        echo "<option value='".$val->keterangan."' ".($val->keterangan==$bab ? "selected" : "").">".$val->keterangan."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">BAK</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='bak' value="<?php echo $bak;?>"/> -->
                        <select class="form-control" name="bak">
                            <option>----</option>
                            <?php
                                foreach ($bk->result() as $value) {
                                    echo "
                                        <option value='".$value->keterangan."' ".($value->keterangan==$bak ? "selected" : "").">".$value->keterangan."</option>
                                    ";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group hide" id="kateter">
                    <label class="col-md-2">Jenis Kateter</label>
                    <div class="col-md-2">
                        <input type="text" name="jenis_kateter" class="form-control" value="<?php echo $jenis_kateter ?>">
                    </div>
                    <label class="col-md-2">Nomor Kateter</label>
                    <div class="col-md-2">
                        <input type="text" name="nomor_kateter" class="form-control" value="<?php echo $nomor_kateter ?>">
                    </div>
                    <label class="col-md-2">Tgl Pemasangan</label>
                    <div class="col-md-2">
                        <input type="text" name="tglpasang_kateter" class="form-control" value="<?php echo $tglpasang_kateter ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Transfer</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='transfer' value="<?php echo $transfer;?>"/> -->
                        <select class="form-control" name="transfer">
                            <option>----</option>
                            <option value="MANDIRI" <?php if ($transfer=="MANDIRI"): ?>
                                selected
                            <?php endif ?>>MANDIRI</option>
                            <option value="Dibantu Sebagian" <?php if ($transfer=="Dibantu Sebagian"): ?>
                                selected
                            <?php endif ?>>Dibantu Sebagian</option>
                            <option value="Dibantu Penuh" <?php if ($transfer=="Dibantu Penuh"): ?>
                                selected
                            <?php endif ?>>Dibantu Penuh</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Mobilitas</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='mobilitas' value="<?php echo $mobilitas;?>"/> -->
                        <select class="form-control" name="mobilitas">
                            <option>----</option>
                            <option value="Tirah Baring" <?php if ($mobilitas=="Tirah Baring"): ?>
                                selected
                            <?php endif ?>>Tirah Baring</option>
                            <option value="Duduk" <?php if ($mobilitas=="Duduk"): ?>
                                selected
                            <?php endif ?>>Duduk</option>
                            <option value="Lain-lain" <?php if ($mobilitas=="Lain-lain"): ?>
                                selected
                            <?php endif ?>>Lain-lain</option>
                        </select>
                    </div>
                </div>
                <div class="form-group hide" id="mobilitas_lain">
                    <label class="col-md-2 control-label">Lain-lain</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name='mobilitas_lain' value="<?php echo $mobilitas_lain;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Gangguan Indra</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='gangguan_indra' value="<?php echo $gangguan_indra;?>"/> -->
                        <select class="form-control" name="gangguan_indra">
                            <option>----</option>
                            <option value="Tidak Ada" <?php if ($gangguan_indra=="Tidak Ada"): ?>
                                selected
                            <?php endif ?>>Tidak Ada</option>
                            <option value="Bicara" <?php if ($gangguan_indra=="Bicara"): ?>
                                selected
                            <?php endif ?>>Bicara</option>
                            <option value="Pendengaran" <?php if ($gangguan_indra=="Pendengaran"): ?>
                                selected
                            <?php endif ?>>Pendengaran</option>
                            <option value="Penglihatan" <?php if ($gangguan_indra=="Penglihatan"): ?>
                                selected
                            <?php endif ?>>Penglihatan</option>
                            <option value="Penciuman Perabaan" <?php if ($gangguan_indra=="Penciuman Perabaan"): ?>
                                selected
                            <?php endif ?>>Penciuman Perabaan</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Alat bantu yang digunakan</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='alat_bantu' value="<?php echo $alat_bantu;?>"/> -->
                        <select class="form-control" name="alat_bantu">
                            <option>----</option>
                            <option value="Tanpa Alat Bantu" <?php if ($alat_bantu=="Tanpa Alat Bantu"): ?>
                                selected
                            <?php endif ?>>Tanpa Alat Bantu</option>
                            <option value="Gigi Palsu" <?php if ($alat_bantu=="Gigi Palsu"): ?>
                                selected
                            <?php endif ?>>Gigi Palsu</option>
                            <option value="Kacamata" <?php if ($alat_bantu=="Kacamata"): ?>
                                selected
                            <?php endif ?>>Kacamata</option>
                            <option value="Alat Bantu Dengar" <?php if ($alat_bantu=="Alat Bantu Dengar"): ?>
                                selected
                            <?php endif ?>>Alat Bantu Dengar</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Infus</label>
                    <div class="col-md-2">
                        <!-- <input type="text" class="form-control" name='infus' value="<?php echo $infus;?>"/> -->
                        <select class="form-control" name="infus">
                            <option value="Ya" <?php if ($infus=="Ya"): ?>
                                selected
                            <?php endif ?>>Ya</option>
                            <option value="Tidak" <?php if ($infus=="Tidak"): ?>
                                selected
                            <?php endif ?>>Tidak</option>
                        </select>
                    </div>
                    <label class="col-md-2 control-label">Lokasi</label>
                    <div class="col-md-2">
                        <select class="form-control lokasi" name="lokasi[]" multiple="multiple">
                            <?php
                                foreach ($li->result() as $val) {
                                    $lokinfus = explode(",", $lokasi);
                                    if (count($lokinfus)>0){
                                        foreach ($lokinfus as $k => $value) {
                                            echo "<option value='".$val->id_lokasi."' ".($val->id_lokasi==$value ? "selected" : "").">".$val->keterangan."</option>";
                                        }
                                    } 
                                    // else {
                                    //     echo "<option value='".$val->id_lokasi."' ".($val->id_lokasi==$lokasi ? "selected" : "").">".$val->keterangan."</option>";
                                    // }
                                }
                            ?>
                        </select>
                    </div>
                    <label class="col-md-2 control-label">Tanggal Pemasangan</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='tanggal_pemasangan' value="<?php echo $tanggal_pemasangan;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Hal-hal istimewa yang berhubungan dengan kondisi pasien :</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='hal_istimewa' value="<?php echo $hal_istimewa;?>"/> -->
                        <select name="hal_istimewa" class="form-control">
                            <option value="ADA" <?php if ($hal_istimewa=="ADA"): ?>
                                selected
                            <?php endif ?>>ADA</option>
                            <option value="TIDAK ADA" <?php if ($hal_istimewa=="TIDAK ADA"): ?>
                                selected
                            <?php endif ?>>TIDAK ADA</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Tindakan/ kebutuhan khusus :</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='tindakan_khusus' value="<?php echo $tindakan_khusus;?>"/> -->
                        <select class="form-control tindakan_khusus"  name="tindakan_khusus[]" multiple="multiple">
                            <?php
                                foreach ($tk->result() as $val) {
                                    $pb = explode(",", $tindakan_khusus);
                                    // if (count($pb)>0){
                                        foreach ($pb as $k => $value) {
                                            echo "<option value='".$val->keterangan."' ".($val->keterangan==$value ? "selected" : "").">".$val->keterangan."</option>";
                                        }
                                    // } else {
                                    //     echo "<option value='".$val->keterangan."' ".($val->keterangan==$tindakan_khusus ? "selected" : "").">".$val->keterangan."</option>";
                                    // }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Peralatan khusus yang diperlukan :</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='peralatan_khusus' value="<?php echo $peralatan_khusus;?>"/> -->
                        <select name="peralatan_khusus" class="form-control">
                            <option value="ADA" <?php if ($peralatan_khusus=="ADA"): ?>
                                selected
                            <?php endif ?>>ADA</option>
                            <option value="TIDAK ADA" <?php if ($peralatan_khusus=="TIDAK ADA"): ?>
                                selected
                            <?php endif ?>>TIDAK ADA</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header"><h3 class="box-title">RECOMMENDATIONS</h3></div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">Konsultasi :</label>
                    <div class="col-md-10">
                        <input type="hidden" class="form-control" name="konsultasi" value="<?php echo $konsultasi ?>"/>
                        <select class="form-control" disabled>
                            <?php 
                                foreach ($dokter->result() as $key){
                                    echo "<option value = '".$key->id_dokter."' ".($key->id_dokter==$konsultasi ? "selected" : "").">".$key->nama_dokter."</option>";
                                }    
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Terapi :</label>
                    <div class="col-md-10">
                        <?php if ($action=="simpan"): ?>
                            <?php 
                                $koma   = $terapi = "";
                                foreach ($aptk->result() as $value) {
                                    $terapi .= $koma.($value->nama_obat!="" ? $value->nama_obat." || &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$value->aturan : 0);
                                    $kodetarif .= $koma.($value->nama_obat!="" ? "'".$value->nama_obat."'" : "");
                                    $koma = "<br>";    
                                }
                            ?>
                            <input type="hidden" class="form-control" name="terapi" value="<?php echo $terapi ?>"/>
                            <textarea class="form-control terapi"  style="max-width: 100%;height:160px;" disabled><?php echo $terapi ?></textarea>
                        <?php else: ?>
                        	<input type="hidden" class="form-control" name="terapi" value="<?php echo $terapi ?>"/>
                            <textarea class="form-control terapi" style="max-width: 100%;height:160px;" disabled><?php echo $q1->terapi ?></textarea>

                        <?php endif ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Rencana pemeriksaan Lab/ Radiologi :</label>
                    <div class="col-md-5">
                        <input type="hidden" class="form-control" name='pemeriksaan_lab' value="<?php echo $pemeriksaan_lab;?>"/>
                        <select class="form-control tindakan_radiologi"  name="tindakan_radiologi[]" multiple="multiple" disabled>
                            <?php
                                foreach ($radiologi->result() as $key) {
                                    $t = explode(",", $tindakan_radiologi);
                                    if (count($t)>0){
                                        foreach ($t as $k => $value) {
                                            echo "<option value='".$key->id_tindakan."' ".($key->id_tindakan==$value ? "selected" : "").">".$key->nama_tindakan."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$key->id_tindakan."' ".($key->id_tindakan==$tindakan_radiologi ? "selected" : "").">".$key->nama_tindakan."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control tindakan_lab" name="tindakan_lab[]" multiple="multiple" disabled>
                            <?php
                                foreach ($lab->result() as $key) {
                                    $t = explode(",", $tindakan_lab);
                                    if (count($t)>0){
                                        foreach ($t as $k => $value) {
                                            echo "<option value='".$key->kode_tindakan."' ".($key->kode_tindakan==$value ? "selected" : "").">".$key->nama_tindakan."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$key->kode_tindakan."' ".($key->kode_tindakan==$tindakan_lab ? "selected" : "").">".$key->nama_tindakan."</option>";
                                    }
                                }
                            ?>
                        </select>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Rencana tindakan lebih lanjut :</label>
                    <div class="col-md-10">
                        <textarea class="form-control" name="rencana_tindakan" style="max-width: 100%;height:160px;" readonly><?php echo $rencana_tindakan ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Note : Obat, barang, dokumen yang disertakan</label>
                    <div class="col-md-10">
                        <!-- <input type="text" class="form-control" name='note' value="<?php echo $note;?>"/> -->
                        <select class="form-control note"  name="note[]" multiple="multiple">
                            <?php
                                foreach ($nt->result() as $val) {
                                    $pb = explode(",", $note);
                                    if (count($pb)>0){
                                        foreach ($pb as $k => $value) {
                                            echo "<option value='".$val->keterangan."' ".($val->keterangan==$value ? "selected" : "").">".$val->keterangan."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$val->keterangan."' ".($val->keterangan==$note ? "selected" : "").">".$val->keterangan."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer no-print">
            <div class="pull-right">
                <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                <?php if ($back=="pasieninap"): ?>
                    <button class="back btn btn-sm btn-warning" type="button"><i class="fa fa-arrow-left"></i>&nbsp;Back</button>
                <?php else: ?>
                    <button class="backpi btn btn-sm btn-danger" type="button"><i class="fa fa-arrow-left"></i>&nbsp;Back</button>
                <?php endif ?>
                <?php if ($action=="edit"): ?>
                	<button class="cetak btn btn-sm btn-success" type="button"><i class="fa fa-print"></i>&nbsp;Cetak</button>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
    <?php if ($action=="edit"): ?>
        <?php
            if ($obd) {
                $obs_tgl = $obd->tgl;
                $obs_jam = $obd->jam;
                $obs_tensi = $obd->tensi;
                $obs_nadi = $obd->nadi;
                $obs_suhu = $obd->suhu;
                $obs_respirasi = $obd->respirasi;
                $obs_kes = $obd->kes;
                $obs_spo2 = $obd->spo2;
                $obs_oral = $obd->oral;
                $obs_infus = $obd->infus;
                $obs_darah = $obd->darah;
                $obs_urine = $obd->urine;
                $obs_draine = $obd->draine;
                $obs_ngt = $obd->ngt;
                $obs_catatan = $obd->catatan;
                $read = "readonly";
            } else {
                $obs_tgl = 
                $obs_jam = 
                $obs_tensi = 
                $obs_nadi = 
                $obs_suhu = 
                $obs_respirasi = 
                $obs_kes = 
                $obs_spo2 = 
                $obs_oral = 
                $obs_infus = 
                $obs_darah = 
                $obs_urine = 
                $obs_draine = 
                $obs_ngt = 
                $obs_catatan =
                $read = "";
            }
            
        ?>
    	<?php echo form_open("perawat/simpanobservasi/",array("class"=>"form-horizontal"));?>
	    	<div class="box box-primary">
		        <div class="box-header"><h3 class="box-title">OBSERVASI</h3></div>
		        <div class="box-body">
                    <div class="form-group">
                        <label class="col-md-2">
                            Tanggal
                        </label>
                        <div class="col-md-5">
                            <input type="hidden" name="back" value="<?php echo $back ?>">
                            <input type="hidden" name="obs_no_reg" class="form-control" value="<?php echo $no_reg ?>">
                            <input type="hidden" name="obs_no_pasien" class="form-control" value="<?php echo $no_pasien ?>">
                            <input type="hidden" name="obs_id_pindahkamar" class="form-control" value="<?php echo $id_pindahkamar ?>">
                            <input type="hidden" name="obs_id" class="form-control" value="<?php echo $id ?>">
                            <input type="text" name="obs_tgl" class="form-control" value="<?php echo $obs_tgl ?>">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="obs_jam" class="form-control" value="<?php echo $obs_jam ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Tensi
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_tensi" class="form-control" value="<?php echo $obs_tensi ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Nadi
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_nadi" class="form-control" value="<?php echo $obs_nadi ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Suhu
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_suhu" class="form-control" value="<?php echo $obs_suhu ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Respirasi
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_respirasi" class="form-control" value="<?php echo $obs_respirasi ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            KES
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_kes" class="form-control" value="<?php echo $obs_kes ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            SPO2
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_spo2" class="form-control" value="<?php echo $obs_spo2 ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Oral
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_oral" class="form-control" value="<?php echo $obs_oral ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Infus
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_infus" class="form-control" value="<?php echo $obs_infus ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Darah
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_darah" class="form-control" value="<?php echo $obs_darah ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Urine
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_urine" class="form-control" value="<?php echo $obs_urine ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Draine
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_draine" class="form-control" value="<?php echo $obs_draine ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            NGT
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_ngt" class="form-control" value="<?php echo $obs_ngt ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">
                            Catatan
                        </label>
                        <div class="col-md-10">
                            <input type="text" name="obs_catatan" class="form-control" value="<?php echo $obs_catatan ?>">
                        </div>
                    </div>
		            <table  width="100%" class="table table-bordered table-hover">
		                <thead>
		                    <tr class="bg-navy">
		                        <th class="text-center" rowspan="2">TGL</th>
                                <th class="text-center" rowspan="2">JAM</th>
		                        <th class="text-center" rowspan="2">TENSI</th>
		                        <th class="text-center" rowspan="2">NADI</th>
		                        <th class="text-center" rowspan="2">SUHU</th>
		                        <th class="text-center" rowspan="2">RESPIRASI</th>
		                        <th class="text-center" rowspan="2">KES</th>
		                        <th class="text-center" rowspan="2">SPO2</th>
		                        <th class="text-center" colspan="3">INTAKE</th>
		                        <th class="text-center" colspan="3">OUTPUT</th>
		                        <th class="text-center" rowspan="2">CATATAN</th>
                                <th class="text-center" rowspan="2">#</th>
		                    </tr>
		                    <tr class="bg-navy">
		                        <th class="text-center">ORAL</th>
		                        <th class="text-center">INFUS</th>
		                        <th class="text-center">DARAH</th>
		                        <th class="text-center">URINE</th>
		                        <th class="text-center">DRAINE</th>
		                        <th class="text-center">NGT</th>
		                    </tr>
		                </thead>
		                <tbody>
	                        <?php
	                        	foreach ($obs->result() as $value) {
	                        		echo "
	                        			<tr>
	                        				<td>".$value->tgl."</td>
                                            <td>".$value->jam."</td>
	                        				<td>".$value->tensi."</td>
	                        				<td>".$value->nadi."</td>
	                        				<td>".$value->suhu."</td>
	                        				<td>".$value->respirasi."</td>
	                        				<td>".$value->kes."</td>
	                        				<td>".$value->spo2."</td>
	                        				<td>".$value->oral."</td>
	                        				<td>".$value->infus."</td>
	                        				<td>".$value->darah."</td>
	                        				<td>".$value->urine."</td>
	                        				<td>".$value->draine."</td>
	                        				<td>".$value->ngt."</td>
	                        				<td>".$value->catatan."</td>
                                            <td class='text-center'>
                                                <button href='".$value->id."' class='edit btn btn-warning' type='button'>Edit</button>
                                            </td>
	                        			</tr>
	                        		";
	                        	}
	                        ?>
	                    </tbody>
		            </table>
		        </div>
		        <div class="box-footer">
	            <div class="pull-right">
	                <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
	            </div>
	        </div>
	        </div>
        <?php echo form_close(); ?>
    </div>
    <?php endif ?>
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