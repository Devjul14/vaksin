<link rel="stylesheet" href="<?php echo base_url();?>plugins/select2/select2.css">
<script src="<?php echo base_url(); ?>plugins/select2/select2.js"></script>
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
    $(document).ready(function(){
        $(".cetak").click(function(){
            var no_pasien = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var url = "<?php echo site_url('dokter/cetakresumeinap');?>/"+no_pasien+"/"+no_reg;
            openCenteredWindow(url);
        });
        var text = $('.hasilpemeriksaan').val(),
        matches = text.match(/\n/g),
        breaks = matches ? matches.length : 2;
        $('.hasilpemeriksaan').attr('rows',breaks + 2);
        $('.back').click(function(){
            var cari_noreg = $("[name='no_reg']").val();
            $.ajax({
                type  : "POST",
                data  : {cari_noreg:cari_noreg},
                url   : "<?php echo site_url('pendaftaran/getcaripasien_ralan');?>",
                success : function(result){
                    window.location = "<?php echo site_url('dokter/rawat_inapdokter_ranap');?>";
                },
                error: function(result){
                    alert(result);
                }
            });
        });
    });
    function tgl_indo(tgl,tipe=1){
        var date = tgl.substring(tgl.length,tgl.length-2);
        if (tipe==1)
            var bln = tgl.substring(5,7);
        else
            var bln = tgl.substring(4,6);
        var thn = tgl.substring(0,4);
        return date+"-"+bln+"-"+thn;
    }
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
    if ($r){
        $alasan_masuk_rs = $r->s;
        $riwayat_penyakit = $r->s;
        $diagnosa_masuk = $r->a;
        $o = $r->o;
    } else {
        $alasan_masuk_rs = 
        $riwayat_penyakit = 
        $diagnosa_masuk = $o = "";
    }
    if($p->num_rows()>0){
        $row = $p->row();
        $diagnosa_akhir = $row->diagnosa_akhir;
        $diagnosa_tambahan = $row->diagnosa_tambahan;
        $komplikasi = $row->komplikasi;
        $pelayanan_puskesmas = $row->pelayanan_puskesmas;
        $riwayat_penyakit = $row->riwayat_penyakit;
        $perkembangan_perawatan = $row->perkembangan_perawatan;
        $ekg = $row->ekg;
        $o = $row->pemeriksaan_fisik;
        $alasan_masuk_rs = ($row->alasan_masuk_rs!="" ? $row->alasan_masuk_rs : $alasan_masuk_rs);
        $riwayat_penyakit = ($row->riwayat_penyakit!="" ? $row->riwayat_penyakit : $riwayat_penyakit);
        $diagnosa_masuk = ($row->diagnosa_masuk!="" ? $row->diagnosa_masuk : $diagnosa_masuk);
        $action = "edit";
    } else {
        $diagnosa_akhir = 
        $diagnosa_tambahan = 
        $komplikasi = 
        $pelayanan_puskesmas = 
        $perkembangan_perawatan =
        $ekg = "";
        $action = "simpan";
    }
    if ($ad){
        $pem = explode(",", $ad->pemeriksaan_fisik);
        $kelainan = explode("|", $ad->kelainan);
    } else {
        $pem = array();
        $kelainan = array();
    }
    if ($ok->num_rows()>0){
        $row = $ok->row();
        $nama_operasi = $row->nama_operasi;
    } else {
        $nama_operasi = "-";
    }
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
    <div class="box box-primary">
        <div class="form-horizontal">
        <?php echo form_open("dokter/simpanresume_inap/".$action);?>
            <input type="hidden" name="no_pasien" value='<?php echo $no_pasien;?>'>
            <input type="hidden" name="no_reg" value='<?php echo $no_reg;?>'>
            <div class="box-body">
            	<div class="form-group">
                    <label class="col-md-3 control-label">Nama Pasien</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_pasien;?>"/>
                    </div>
                    <label class="col-md-3 control-label">Ruangan/ Kelas</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='ruangan' readonly value="<?php echo $q->nama_ruangan."/ ".$q->nama_kelas;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Golongan pasien</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" readonly value="<?php echo $q->ket_gol_pasien;?>"/>
                    </div>
                    <label class="col-md-3 control-label">No. RM</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='no_pasien' readonly value="<?php echo $no_pasien;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Tgl Lahir / Umur</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" readonly value="<?php echo date("d-m-Y",strtotime($q->tgl_lahir)).' / '.$umur;?>"/>
                    </div>
                    <label class="col-md-3 control-label">No. Reg</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" readonly value="<?php echo $no_reg;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Tgl Masuk</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='tgl_masuk' readonly value="<?php echo date("d-m-Y",strtotime($q->tgl_masuk));?>"/>
                    </div>
                    <label class="col-md-3 control-label">Tgl Keluar</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='tgl_keluar' readonly value="<?php echo ($q->tgl_keluar=="" ? "-" : date("d-m-Y",strtotime($q->tgl_keluar)));?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Jam Masuk</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='jam_masuk' readonly value="<?php echo date("H:i:s",strtotime($q->jam_masuk));?>"/>
                    </div>
                    <label class="col-md-3 control-label">Jam Keluar</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='jam_keluar' readonly value="<?php echo ($q->jam_keluar=="" ? "-" : date("H:i:s",strtotime($q->jam_keluar)));?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Alasan Masuk rumah sakit</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name='alasan_masuk_rs'><?php echo $alasan_masuk_rs;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Diagnosa Masuk</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name='diagnosa_masuk' value="<?php echo $diagnosa_masuk;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Diagnosa Akhir</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name='diagnosa_akhir' required value="<?php echo $diagnosa_akhir;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Diagnosa Tambahan</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name='diagnosa_tambahan' required value="<?php echo $diagnosa_tambahan;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Komplikasi</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name='komplikasi' required value="<?php echo $komplikasi;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Operasi</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name='operasi' readonly value="<?php echo $nama_operasi;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Riwayat Penyakit</label>
                    <div class="col-md-9"><textarea class="form-control" name="riwayat_penyakit"><?php echo $riwayat_penyakit;?></textarea></div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Pemeriksaan Fisik</label>
                    <div class="col-md-9">
                        <?php 
                            $ada = 0;
                            for ($i=0;$i<=10;$i++){
                                if (!$pem[$i]){
                                    $ada = 1;
                                }
                            }
                            echo ($q1->td=="" ? "" : "TD ka : ".$q1->td." mmHg, "); 
                            echo ($q1->td2=="" ? "" : "TD ki : ".$q1->td2." mmHg, "); 
                            echo ($q1->nadi=="" ? "" : "Nadi : ".$q1->nadi." x/ mnt, "); 
                            echo ($q1->respirasi=="" ? "" : "Respirasi : ".$q1->respirasi." x/ mnt, "); 
                            echo ($q1->suhu=="" ? "" : "Suhu : ".$q1->suhu." °C, "); 
                            echo ($q1->spo2=="" ? "" : "SpO2 : ".$q1->spo2." %, "); 
                            echo ($q1->bb=="" ? "" : "BB : ".$q1->bb." kg, "); 
                            echo ($q1->tb=="" ? "" : "TB : ".$q1->tb." cm, "); 
                            echo "<br>";
                            echo "<textarea class='form-control hasilpemeriksaan' name='pemeriksaan_fisik'>".$o."</textarea>";
                        ?>
                        <?php if ($ada==1) :?>
                            <table width="100%" cellspacing="0" cellpadding="1">
                                <tr>
                                    <th>Pemeriksaan</th>
                                    <th>Kelainan</th>
                                </tr>
                                <?php if ($pem[0]!="1") : ?>
                                <tr>
                                    <td width=200px>Kepala</td>
                                    <td><?php echo (isset($kelainan[0]) ? ($pem[0] == "1" ? "" : $kelainan[0]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[1]!="1") : ?>
                                <tr>
                                    <td>Mata</td>
                                    <td><?php echo (isset($kelainan[1]) ? ($pem[1] == "1" ? "" : $kelainan[1]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[2]!="1") : ?>
                                <tr>
                                    <td>THT</td>
                                    <td><?php echo (isset($kelainan[2]) ? ($pem[2] == "1" ? "" : $kelainan[2]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[3]!="1") : ?>
                                <tr>
                                    <td>Gigi Mulut</td>
                                    <td><?php echo (isset($kelainan[3]) ? ($pem[3] == "1" ? "" : $kelainan[3]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[4]!="1") : ?>
                                <tr>
                                    <td>Leher</td>
                                    <td><?php echo (isset($kelainan[4]) ? ($pem[4] == "1" ? "" : $kelainan[4]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[5]!="1") : ?>
                                <tr>
                                    <td>Thoraks</td>
                                    <td><?php echo (isset($kelainan[5]) ? ($pem[5] == "1" ? "" : $kelainan[5]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[6]!="1") : ?>
                                <tr>
                                    <td>Abdomen</td>
                                    <td><?php echo (isset($kelainan[6]) ? ($pem[6] == "1" ? "" : $kelainan[6]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[7]!="1") : ?>
                                <tr>
                                    <td>Ekstremitas Atas</td>
                                    <td><?php echo (isset($kelainan[7]) ? ($pem[7] == "1" ? "" : $kelainan[7]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[8]!="1") : ?>
                                <tr>
                                    <td>Ekstremitas Bawah</td>
                                    <td><?php echo (isset($kelainan[8]) ? ($pem[8] == "1" ? "" : $kelainan[8]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[9]!="1") : ?>
                                <tr>
                                    <td>Genitalia</td>
                                    <td><?php echo (isset($kelainan[9]) ? ($pem[9] == "1" ? "" : $kelainan[9]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                                <?php if ($pem[10]!="1") : ?>
                                <tr>
                                    <td>Anus</td>
                                    <td><?php echo (isset($kelainan[10]) ? ($pem[10] == "1" ? "" : $kelainan[10]) : ''); ?></td>
                                </tr>
                                <?php endif ?>
                            </table>
                        <?php endif ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12 control-label">Hasil Pemeriksaan Penunjang Medis</label>
                    <div class="col-md-12">
                        <p class="text-bold">1. Labotarium</p>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="bg-navy">
                                    <th width="10" class='text-center'>No</th>
                                    <th width="100px" class="text-center">Tanggal</th>
                                    <th class="text-center">Nama Tindakan</th>
                                    <th width="300" class='text-center'>Jenis Pemeriksaan</th>
                                    <th width="100" class='text-center'>Hasil</th>
                                    <th width="100" class='text-center'>Satuan</th>
                                    <th width="200" class='text-center'>Nilai Rujukan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 0;
                                    $kode_judul = $kode_tindakan = "";
                                    $tgl1_print = $tgl2_print = "";
                                    foreach($k->result() as $data){
                                        $tgl1_print = $tgl1_print=="" ? date("d-m-Y",strtotime($data->tanggal)) : $tgl1_print;
                                        $tgl2_print = date("d-m-Y",strtotime($data->tanggal));
                                        if ($kode_judul!=$data->kode_judul) {
                                            echo "<tr class='bg-orange'>";
                                            echo "<td colspan='7'>".$data->judul."</td>";
                                            $kode_judul = $data->kode_judul;
                                            $i = 0;
                                        }
                                        if ($data->jenis_kelamin=="L") {
                                            $rujukan = $data->pria;
                                        } else {
                                            $rujukan = $data->wanita;
                                        }
                                        $i++;
                                        if ($kode_tindakan!=$data->kode_tindakan){
                                            $nama_tindakan = $data->nama_tindakan;
                                            $kode_tindakan = $data->kode_tindakan;
                                        } else {
                                            $nama_tindakan = "";
                                        }
                                        echo "<tr>";
                                        echo "<td>".$i."</td>";
                                        echo "<td>".date("d-m-Y",strtotime($data->tanggal))."</td>";
                                        echo "<td>".$nama_tindakan."</td>";
                                        echo "<td>".$data->nama."</td>";
                                        echo "<td><input type='text' class='form-control' readonly name='hasil[".$data->kode."]' value='".(isset($hasil[$data->kode][$data->pemeriksaan][$data->tanggal]) ? $hasil[$data->kode][$data->pemeriksaan][$data->tanggal]->hasil : "")."'></td>";
                                        echo "<td>".$data->satuan."</td>";
                                        echo "<td>".$rujukan."</td>";
                                        echo "</tr>";
                                    }
                                    $tgl1_print = $tgl1_print=="" ? date("d-m-Y") : $tgl1_print;
                                    $tgl2_print = $tgl2_print=="" ? date("d-m-Y") : $tgl2_print;
                                ?>
                            </tbody>
                        </table>
                        <p class="text-bold">2. Radiologi</p>
                        <?php
                            $i = 1;
                            $subtotal = 0;
                            echo "<ul>";
                            foreach($rad->result() as $data){
                                echo "<li>";
                                echo date("d-m-Y",strtotime($data->tanggal))." ".$data->nama_tindakan."<br>";
                                echo '<div class="form-group">';
                                echo '<label class="col-md-12 control-label">Hasil Pemeriksaan</label>';
                                echo '<div class="col-md-6">';
                                echo "<textarea class='form-control hasilpemeriksaan' readonly>".$data->hasil_pemeriksaan."</textarea>";
                                echo "</div>";
                                echo "</li>";
                            }
                            echo "</ul>";
                        ?>
                        <p class="text-bold">3. Patologi Anatomi</p>
                        <?php
                            $i = 1;
                            $subtotal = 0;
                            echo "<ul>";
                            foreach($pa->result() as $data){
                                echo "<li>";
                                echo date("d-m-Y",strtotime($data->tanggal))." ".$data->nama_tindakan."<br>";
                                echo '<div class="form-group">';
                                echo '<label class="col-md-12 control-label">Hasil Pemeriksaan</label>';
                                echo '<div class="col-md-6">';
                                echo "<textarea class='form-control hasilpemeriksaan' readonly>".$data->hasil_pemeriksaan."</textarea>";
                                echo "</div>";
                                echo "</li>";
                            }
                            echo "</ul>";
                        ?>
                        <p class="text-bold">4. EKG</p>
                        <div class="col-md-12">
                            <textarea class="form-control" name='ekg' required><?php echo $ekg;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12 control-label">Perkembangan selama perawatan/ dengan komplikasi (jika ada)</label>
                    <div class="col-md-12">
                        <textarea class="form-control" name='perkembangan_perawatan' required><?php echo $perkembangan_perawatan;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12 control-label">Obat selama perawatan di RS</label>
                    <div class="col-md-12">
                        <?php
                            $i = 1;
                            $koma = "";
                            foreach ($ob->result() as $data) {
                                if ($nama_obat!=$data->nama_obat){
                                    if ($q->tgl_keluar!=$data->tanggal){
                                        echo $koma.$data->nama_obat;
                                        $koma = ", ";
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3">Keadaan saat keluar Rumah Sakit</label>
                    <div class="col-md-9"><?php echo $kp[$q->status_pulang];?></div>
                </div>
                <div class="form-group">
                    <label class="col-md-3">Transportasi saat pulang</label>
                    <div class="col-md-9"><?php echo $q->transport_pulang;?></div>
                </div>
                <div class="form-group">
                    <label class="col-md-12 control-label">INTRUKSI</label>
                    <div class="col-md-12">
                        <ul>
                            <li>Tanggal Kontrol : <?php echo ($q->status_pulang==4 ? "-" : ($q->tgl_kontrol=="" || $q->tgl_kontrol==null ? "-" : date("d-m-Y",strtotime($q->tgl_kontrol))));?></li>
                            <li>Klinik tujuan : <?php echo ($q->status_pulang==4 ? "-" : $dpjp[$q->dpjp]);?></li>
                            <li>Terapi setelah perawatan :<br>
                                <?php
                                    $i = 0;
                                    $koma = "";
                                    $nama_obat = "";
                                    foreach ($ob->result() as $data) {
                                        if ($nama_obat!=$data->nama_obat){
                                            if ($q->tgl_keluar==$data->tanggal){
                                                echo $koma.$data->nama_obat." (".$data->aturan_pakai.")";
                                                $koma = ", ";
                                            }
                                        }
                                    }
                                ?>
                            </li>
                            <li>Apabila ada tanda-tanda kegawatdaruratan (pendarahan, gatal-gatal/ alergi, sesak nafas, mual muntah, sakit kepala) segera dibawa ke Instalasi Gawat Darurat (24 jam) atau rumah sakit terdekat.</li>
                            <li style="list-style-type: none;">
                                <div class="row">
                                    <div class="col-md-3">Perlu Pelayanan Puskesmas</div>
                                    <div class="col-md-3">
                                        <select name="pelayanan_puskesmas" class="form-control">
                                            <option value="Y" <?php echo ($pelayanan_puskesmas=="Y" ? "selected" : "");?>>Ya</option>
                                            <option value="T" <?php echo ($pelayanan_puskesmas=="T" ? "selected" : "");?>>Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button class="cetak btn btn-primary" type="button"><i class="fa fa-print"></i> Cetak</button>
                    <button class="simpan btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
        <?php echo form_close();?>
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