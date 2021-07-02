<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="<?php echo base_url();?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/library.js"></script>
    <script src="<?php echo base_url();?>js/jquery-qrcode.js"></script>
    <script src="<?php echo base_url();?>js/html2pdf.bundle.js"></script>
    <script src="<?php echo base_url();?>js/html2canvas.js"></script>
    <link rel="icon" href="<?php echo base_url();?>img/computer.png" type="image/x-icon" />
</head>
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
    $umur = $year_diff;
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
<script>
    $(document).ready(function(){
        getttd();
        getttd_dpjp();
        window.print();
    });
    function getttd_dpjp(){
        var ttd = "<?php echo site_url('ttddokter/getttddokterlab/'.$q->dpjp);?>";
        $('.dokter_qrcode').qrcode({width: 80,height: 80, text:ttd});
    }
    function getttd(){
        var umur = "<?php echo $umur;?>";
        var status_pulang = "<?php echo $status_pulang;?>";
        if (umur<10 || status_pulang==4){
          var ttd = "<?php echo site_url('ttddokter/getttdkeluarga/'.$no_reg);?>";
        } else{
          var ttd = "<?php echo site_url('ttddokter/getttdpasien/'.$no_pasien);?>";
        }
        $('.pasien_qrcode').qrcode({width: 80,height: 80, text:ttd});
    }
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
<p align="right" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <small>RM13/RI/RSC/REV1</small>
</p>
<table class="laporan" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td rowspan="3" align="center" style="vertical-align:middle">
            <img src="<?php echo base_url("img/Logo.png")?>"><br><b>RS CIREMAI</b>
        </td>
        <td rowspan="3" align="center" style="vertical-align: middle;">
            <h4>RINGKASAN KELUAR<br>(RESUME PULANG)</h4>
        </td>
        <td>
            <table class="no-border" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        Nama Pasien
                    </td>
                    <td>
                        <?php echo $q->nama_pasien;?>
                    </td>
                    <td>Ruangan/ Kelas</td>
                    <td>
                        <?php echo $q->nama_ruangan."/ ".$q->nama_kelas;?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Golongan Pasien
                    </td>
                    <td>
                        <?php echo $q->ket_gol_pasien;?>
                    </td>
                    <td>No. RM</td>
                    <td>
                        <?php echo $no_pasien;?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Tgl Lahir / Umur
                    </td>
                    <td>
                        <?php echo date("d-m-Y",strtotime($q->tgl_lahir)).' / '.$umur." tahun";?>
                    </td>
                    <td>No. Reg</td>
                    <td>
                        <?php echo $no_reg;?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Tgl Masuk
                    </td>
                    <td>
                        <?php echo date("d-m-Y",strtotime($q->tgl_masuk));?>
                    </td>
                    <td>Tgl Keluar</td>
                    <td>
                        <?php echo ($q->tgl_keluar=="" ? "-" : date("d-m-Y",strtotime($q->tgl_keluar)));?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Jam Masuk
                    </td>
                    <td>
                        <?php echo date("H:i:s",strtotime($q->jam_masuk));?>
                    </td>
                    <td>Jam Keluar</td>
                    <td>
                        <?php echo ($q->jam_keluar=="" ? "-" : date("H:i:s",strtotime($q->jam_keluar)));?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="no-border" width="100%">
    <tr>
        <td width=200px>
            Alasan Masuk rumah sakit
        </td>
        <td colspan=3>
            <?php echo $alasan_masuk_rs;?>
        </td>
    </tr>
    <tr>
        <td>Diagnosa Masuk</td>
        <td colspan=3><?php echo $diagnosa_masuk;?></td>
    </tr>
    <tr>
        <td>
            Diagnosa Akhir
        </td>
        <td colspan=3>
            <?php echo $diagnosa_akhir;?>
        </td>
    </tr>
    <tr>
        <td>Diagnosa Tambahan</td>
        <td colspan=3>
            <?php echo $diagnosa_tambahan;?>
        </td>
    </tr>
    <tr>
        <td>
            Komplikasi
        </td>
        <td colspan=3>
            <?php echo $komplikasi;?>
        </td>
    </tr>
    <tr>
        <td>Operasi</td>
        <td colspan=3>
            <?php echo $nama_operasi;?>
        </td>
    </tr>
    <tr>
        <td>
            Riwayat Penyakit
        </td>
        <td colspan=3>
            <?php echo $riwayat_penyakit;?>
        </td>
    </tr>
    <tr>
        <td>
            Pemeriksaan Fisik
        </td>
        <td colspan=3>
        <?php
            $ada = 0;
            for ($i=0;$i<=10;$i++){
                if (!$pem[$i] && $pem[$i]!=""){
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
            echo $o;
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
        </td>
    </tr>
    <tr>
        <td colspan=4>
            Hasil Pemeriksaan Penunjang Medis
        </td>
    </tr>
    <tr>
        <td colspan=4>
            <table class="laporan1" width="100%">
                <tr><th width=50px>1.</th><th align="left">Labotarium</th>
                <tr><td>&nbsp;</td>
                    <td>
                        <table class="laporan1" width="100%">
                                <tr>
                                    <th width="10" class='text-center'>No</th>
                                    <th width="100px" class="text-center">Tanggal</th>
                                    <th width="100px" class="text-center">Tindakan</th>
                                    <th width="300" class='text-center'>Jenis Pemeriksaan</th>
                                    <th width="100" class='text-center'>Hasil</th>
                                    <th width="100" class='text-center'>Satuan</th>
                                    <th width="200" class='text-center'>Nilai Rujukan</th>
                                </tr>

                                <?php
                                    $i = 0;
                                    $kode_judul = $kode_tindakan = "";
                                    $tgl1_print = $tgl2_print = "";
                                    foreach($k->result() as $data){
                                        $tgl1_print = $tgl1_print=="" ? date("d-m-Y",strtotime($data->tanggal)) : $tgl1_print;
                                        $tgl2_print = date("d-m-Y",strtotime($data->tanggal));
                                        if ($kode_judul!=$data->kode_judul) {
                                            echo "<tr>";
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
                                        echo "<td>".(isset($hasil[$data->kode][$data->pemeriksaan][$data->tanggal]) ? $hasil[$data->kode][$data->pemeriksaan][$data->tanggal]->hasil : "")."</td>";
                                        echo "<td>".$data->satuan."</td>";
                                        echo "<td>".$rujukan."</td>";
                                        echo "</tr>";
                                    }
                                    $tgl1_print = $tgl1_print=="" ? date("d-m-Y") : $tgl1_print;
                                    $tgl2_print = $tgl2_print=="" ? date("d-m-Y") : $tgl2_print;
                                ?>
                        </table>
                    </td>
                </tr>
                <tr><th>2.</th><th align="left">Radiologi</th></tr>
                <tr><td>&nbsp;</td>
                    <td>
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
                                echo $data->hasil_pemeriksaan;
                                echo "</div>";
                                echo "</li>";
                            }
                            echo "</ul>";
                        ?>
                    </td>
                <tr>
                <tr><th>3.</th><th align="left">Patologi Anatomi</th></tr>
                <tr><td>&nbsp;</td>
                    <td>
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
                                echo $data->hasil_pemeriksaan;
                                echo "</div>";
                                echo "</li>";
                            }
                            echo "</ul>";
                            ?>
                    </td>
                </tr>
                <tr><th>4.</th><th align="left">EKG</th></tr>
                <tr><td>&nbsp;</td><td><?php echo $ekg;?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            Perkembangan selama perawatan/ dengan komplikasi (jika ada)
        </td>
        <td colspan=3>
            <?php echo $perkembangan_perawatan;?>
        </td>
    </tr>
    <tr>
        <td>Obat selama perawatan di RS</td>
        <td colspan=3>
            <?php
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
        </td>
    </tr>
    <tr>
        <td>Keadaan saat keluar Rumah Sakit</td>
        <td colspan=3><?php echo $kp[$q->status_pulang];?></td>
    </tr>
    <tr>
        <td>Transportasi saat pulang</td>
        <td colspan=3><?php echo $q->transport_pulang;?></td>
    </tr>
    <tr>
        <td colspan=4>INTRUKSI<br>
            <ul>
                <li>Tanggal Kontrol : <?php echo ($q->status_pulang==4 || $q->status_pulang==3 ? "-" : ($q->tgl_kontrol=="" || $q->tgl_kontrol==null ? "-" : date("d-m-Y",strtotime($q->tgl_kontrol))));?></li>
                <li>Klinik tujuan : <?php echo ($q->status_pulang==4 || $q->status_pulang==3 ? "-" : $dpjp[$q->dpjp]);?></li>
                <li>Terapi setelah perawatan :
                    <?php
                        if ($q->status_pulang==4){
                          echo "-";
                        } else {
                          echo "<br>";
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
                        }
                    ?>
                </li>
                <li>Apabila ada tanda-tanda kegawatdaruratan (pendarahan, gatal-gatal/ alergi, sesak nafas, mual muntah, sakit kepala) segera dibawa ke Instalasi Gawat Darurat (24 jam) atau rumah sakit terdekat.</li>
                <li style="list-style-type: none;">
                    <div class="row">
                        Perlu Pelayanan Puskesmas : <?php echo ($q->status_pulang==4 ? "Tidak" : ($pelayanan_puskesmas=="Y" ? "Ya" : "Tidak"));?>
                    </div>
                </li>
            </ul>
        </td>
    </tr>
</table>
<table class="no-border" width="100%">
    <tr>
        <td align="center" width="50%">
            Nama dan Tanda Tangan <?php echo ($umur<10 ? "Keluarga" : ($q->status_pulang!=4 ? "Pasien" : "Keluarga"));?>
        </td>
        <td align="center">
            Dokter yang merawat (dpjp)
        </td>
    </tr>
    <tr>
        <td align="center">
            <?php echo ($umur<10 ? ($s->nama=="" ? "" : '<div class="pasien_qrcode" align="center"> </div>') : ($q->status_pulang!=4 ? '<div class="pasien_qrcode" align="center"> </div>' : ($s->saksi=="" ? '' :'<div class="pasien_qrcode" align="center"> </div>')));?>
            <br>
            <?php echo ($umur<10 ? $s->nama : ($q->status_pulang!=4 ? $q->nama_pasien : $s->nama));?>
        </td>
        <td align="center">
            <div class="dokter_qrcode" align="center"> </div>
            <br>
            <?php echo $q->dokter_dpjp ?>
        </td>
    </tr>
</table>
<style type="text/css">
    .laporan {
        border-collapse: collapse !important;
        background-color: transparent;
        border-spacing: 0px;
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 12px;
    }
    .laporan > thead > tr > th,
    .laporan > tbody > tr > th,
    .laporan > tfoot > tr > th,
    .laporan > thead > tr > td,
    .laporan > tbody > tr > td,
    .laporan > tfoot > tr > td {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
        background-color: #fff !important;
        border: 1px solid #000 !important;
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
        padding: 0px;
        background-color: #fff !important;
        border: 1px solid #000 !important;
    }
    .no-border {
        border-collapse: collapse !important;
        background-color: transparent;
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 12px;
    }
    .no-border {
        border-collapse: collapse !important;
        background-color: transparent;
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 12px;
    }
    .no-border > thead > tr > th,
    .no-border > tbody > tr > th,
    .no-border > tfoot > tr > th,
    .no-border > thead > tr > td,
    .no-border > tbody > tr > td,
    .no-border > tfoot > tr > td {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 2px solid #ddd;
    }
    .no-border > thead > tr > th {
        vertical-align: bottom;
        border-bottom: 2px solid #ddd;
    }
    .no-border > caption + thead > tr:first-child > th,
    .no-border > colgroup + thead > tr:first-child > th,
    .no-border > thead:first-child > tr:first-child > th,
    .no-border > caption + thead > tr:first-child > td,
    .no-border > colgroup + thead > tr:first-child > td,
    .no-border > thead:first-child > tr:first-child > td {
        border-top: 0;
    }
    .no-border > tbody + tbody {
        border-top: 2px solid #ddd;
    }
    .no-border td,
    .no-border th {
        background-color: #fff !important;
        border: 0px solid #000 !important;
    }

</style>
