<script>
    var mywindow;

    function openCenteredWindow(url) {
        var width = 1200;
        var height = 500;
        var left = parseInt((screen.availWidth / 2) - (width / 2));
        var top = parseInt((screen.availHeight / 2) - (height / 2));
        var windowFeatures = "width=" + width + ",height=" + height +
            ",status,resizable,left=" + left + ",top=" + top +
            ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow = window.open(url, "subWind", windowFeatures);
    }
    $(document).ajaxStart(function() {
        $('.loading').show();
    }).ajaxStop(function() {
        $('.loading').hide();
    });
    $(document).ready(function() {
        $("input[name='gcs']").on('input', function() {
            var gcs = $("[name='kesadaran']").val();
            var min = 0;
            var max = 100;
            if (gcs == "Compos Metis") {
                min = 14;
                max = 15
            } else
            if (gcs == "Apatis") {
                min = 12;
                max = 13
            } else
            if (gcs == "Somnolen") {
                min = 10;
                max = 11
            } else
            if (gcs == "Delirium") {
                min = 7;
                max = 9
            } else
            if (gcs == "Sopor") {
                min = 4;
                max = 6
            } else
            if (gcs == "Coma") {
                min = 3;
                max = 3
            }
            var value = $(this).val();
            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, max), min));
            }
        });
        $("[name='jenis_nyeri']").hide();
        $("[name='lokasi']").hide();
        $("[name='frekuensi']").hide();
        $("[name='durasi']").hide();
        $("[name='diantar']").hide();
        $("[name='rujuk_ke']").hide();
        $("[name='alasan_rujuk']").hide();
        $("[name='skrining_gizi2']").hide();
        $("[name='pemeriksaan_fisik1']").change(function() {
            if ($(this).val() == "1") {
                $("[name='kelainan1']").hide();
            } else {
                $("[name='kelainan1']").show();
            }
        });
        $(".upload").click(function() {
            var no_rm = $("[name='no_rm']").val();
            var id = $("[name='no_reg']").val();
            var url = "<?php echo site_url('pendaftaran/formuploadpdf_ralan'); ?>/" + no_rm + "/" + id;
            window.location = url;
            return false;
        });
        if ($("[name='nyeri']").val() == "YA") {
            $("[name='jenis_nyeri']").show();
            $("[name='lokasi']").show();
            $("[name='frekuensi']").show();
            $("[name='durasi']").show();
        } else {
            $("[name='jenis_nyeri']").hide();
            $("[name='lokasi']").hide();
            $("[name='frekuensi']").hide();
            $("[name='durasi']").hide();
        }
        if ($("[name='tindak_lanjut']").val() == "Rujuk") {
            $("[name='rujuk_ke']").show();
            $("[name='alasan_rujuk']").show();
        } else {
            $("[name='rujuk_ke']").hide();
            $("[name='alasan_rujuk']").hide();
        }
        if ($("[name='skrining_gizi']").val() == "> 2") {
            $("[name='skrining_gizi2']").show();
        } else {
            $("[name='skrining_gizi2']").hide();
        }
        $("[name='nyeri']").change(function() {
            if ($("[name='nyeri']").val() == "YA") {
                $("[name='jenis_nyeri']").show();
                $("[name='lokasi']").show();
                $("[name='frekuensi']").show();
                $("[name='durasi']").show();
            } else if ($("[name='nyeri']").val() != "YA") {
                $("[name='jenis_nyeri']").hide();
                $("[name='lokasi']").hide();
                $("[name='frekuensi']").hide();
                $("[name='durasi']").hide();
            }
        });
        $("[name='tindak_lanjut']").change(function() {
            if ($("[name='tindak_lanjut']").val() == "Rujuk") {
                $("[name='rujuk_ke']").show();
                $("[name='alasan_rujuk']").show();
            } else if ($("[name='tindak_lanjut']").val() != "Rujuk") {
                $("[name='rujuk_ke']").hide();
                $("[name='alasan_rujuk']").hide();
            }
        });
        $("[name='skrining_gizi']").change(function() {
            if ($("[name='skrining_gizi']").val() == "> 2") {
                $("[name='skrining_gizi2']").show();
            } else if ($("[name='skrining_gizi']").val() != " > 2") {
                $("[name='skrining_gizi2']").hide();
            }
        });
        var formattgl = "dd-mm-yy";
        $("input[name='tanggal_masuk'],[name='jadwal_vaksin2']").datepicker({
            dateFormat: formattgl,
        });
        $('.back').click(function() {
            var asal = "<?php echo $asal; ?>";
            if (asal == "assesmen") {
                window.location = "<?php echo site_url('dokter/rawat_jalandokter'); ?>";
            } else {
                window.location = "<?php echo site_url('pendaftaran/rawat_jalan'); ?>";
            }

        });
        $('.cetak').click(function() {
            var no_reg = $("[name='no_reg']").val();
            var no_rm = $("[name='no_rm']").val();
            var url = "<?php echo site_url('whatsapp/cetakskrining_vaksin'); ?>/" + no_rm+"/"+no_reg;
            openCenteredWindow(url);
        });
        $('.terapi').click(function() {
            var no_reg = $("[name='no_reg']").val();
            var no_rm = $("[name='no_rm']").val();
            window.location = "<?php echo site_url('dokter/apotek_igdralan'); ?>/" + no_rm + "/" + no_reg;
            return false;
        });
        $('.anatomi').click(function() {
            var no_rm = $("[name='no_rm']").val();
            var no_reg = $("[name='no_reg']").val();
            window.location = "<?php echo site_url('assesmen/getanatomi_ralan'); ?>/" + no_reg;
            return false;
        });
        $('.odontogram').click(function() {
            var no_rm = $("[name='no_rm']").val();
            var no_reg = $("[name='no_reg']").val();
            window.location = "<?php echo site_url('assesmen/getdental_ralan'); ?>/" + no_reg;
            return false;
        });
        $('.radioterapi').click(function() {
            var no_reg = $("[name='no_reg']").val();
            var no_rm = $("[name='no_rm']").val();
            window.location = "<?php echo site_url('dokter/radioterapi'); ?>/" + no_rm + "/" + no_reg;
            return false;
        });
        $('.lunas').click(function() {
            $(".modalnotif").modal("show");
            var total = $("[name='total']").val();
            $(".total").html("Rp. " + total);
        });
        $('.resume').click(function() {
            var no_rm = $("[name='no_rm']").val();
            $(".modalresume").modal("show");
            var html = "";
            $(".listresume").html(html);
            $.ajax({
                url: "<?php echo base_url(); ?>pendaftaran/resume",
                method: "POST",
                data: {
                    no_rm: no_rm
                },
                success: function(data) {
                    console.log(data);
                    data = JSON.parse(data);
                    $.each(data["ralan"], function(key, val) {
                        var no = key + 1;
                        html += "<tr>";
                        html += "<td>" + (no) + "</td>";
                        html += "<td>" + val.tanggal + "</td>";
                        html += "<td>" + val.nama_poli + "</td>";
                        html += "<td>" + (val.a == undefined ? "-" : val.a) + "</td>";
                        html += "<td>";
                        html += "<ul style='margin-left:-20px'>";
                        if (data["terapi"][val.no_reg] != undefined) {
                            $.each(data["terapi"][val.no_reg], function(key1, val1) {
                                html += "<li>" + val1.nama_obat + " " + val1.qty + " " + val1.satuan + " | " + (val1.aturan_pakai == null ? "-" : val1.aturan_pakai) + "</li>";
                            });
                        } else {
                            html += "-";
                        }
                        html += "</ul>";
                        html += "</td>";
                        html += "<td>" + (val.riwayat_alergi == undefined ? "-" : val.riwayat_alergi) + "</td>";
                        html += "<td>";
                        html += "<ul style='margin-left:-20px'>";
                        if (data["kasir"][val.no_reg] != undefined) {
                            var koma = "";
                            var nama_tindakan = "";
                            $.each(data["kasir"][val.no_reg], function(key1, val1) {
                                if (val1.nama_tindakan1 != null) {
                                    nama_tindakan = val1.nama_tindakan1;
                                } else
                                if (val1.nama_tindakan2 != null) {
                                    nama_tindakan = val1.nama_tindakan2;
                                } else {
                                    nama_tindakan = val1.nama_tindakan3;
                                }
                                if (nama_tindakan != '' && nama_tindakan != 'PEMERIKSAAN DOKTER') {
                                    html += "<li>" + nama_tindakan + "</li>";
                                }
                            });
                        } else {
                            html += "-";
                        }
                        html += "</ul>";
                        html += "</td>";
                        html += "<td>" + val.nama_dokter + "</td>";
                        html += "<td>";
                        if (data["grouper_icd9"][val.no_reg] != undefined) {
                            var koma = "";
                            $.each(data["grouper_icd9"][val.no_reg], function(key1, val1) {
                                html += koma + val1.kode;
                                koma = ", ";
                            });
                        }
                        if (data["grouper_icd10"][val.no_reg] != undefined) {
                            var koma = "";
                            $.each(data["grouper_icd10"][val.no_reg], function(key1, val1) {
                                html += koma + val1.kode;
                                koma = ", ";
                            });
                        }
                        if (data["grouper_icd9"][val.no_reg] == undefined && data["grouper_icd10"][val.no_reg] == undefined) {
                            html += "-";
                        }
                        html += "</td>";
                        html += "</tr>";
                    });
                    $(".listresume").html(html);
                }
            });

        });
        $('.hapus').click(function() {
            var id = $(this).attr("id");
            $.ajax({
                url: "<?php echo base_url(); ?>kasir/hapustindakan",
                method: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    location.reload();
                }
            });
        });
        $(".ambil").click(function() {
            var url = "<?php echo site_url('dokter/ambiltriage'); ?>/";
            openCenteredWindow(url);
            return false;
        });
        $("select[name='dokter']").change(function() {
            var rad = $(this).find(':selected').attr('data-id');
            $("input[name='radiologi']").val(rad);
        });
        // $(".Books_Illustrations").select2("val", ["a", "c"]);
        $("[name='nama_vaksin'],[name='petugas_vaksin'],[name='dosis_vaksin']").select2();
        $(".tindakan_radiologi").select2();
        $(".tindakan_lab").select2();
        $(".penunjang").select2();
        $("select[name='kedatangan']").select2();
        $("select[name='terapi1']").select2();
        $("select[name='keputusan']").select2();
    });
</script>
<?php
if ($q1) {
    $dokter_poli = $q1->dokter_poli;
    $tanggal_masuk      = $t->tanggal;
    $jam_masuk      = $t->jam;
    $jam_periksa      = $t->tanggal;
    $jam_keluar_igd      = $q1->jam_keluar_igd;
    $nyeri      = $q1->nyeri;
    $jenis_nyeri      = $q1->jenis_nyeri;
    $resiko_jatuh      = $q1->resiko_jatuh;
    $skrining_gizi      = $q1->skrining_gizi;
    $keluhan_utama      = $q1->keluhan_utama;
    $kronologis_kejadian      = $q1->kronologis_kejadian;
    $anamnesa      = $q1->anamnesa;
    $riwayat_penyakit      = $q1->riwayat_penyakit;
    $obat_dikonsumsi      = $q1->obat_dikonsumsi;
    $pemeriksaan_penunjang      = $q1->penunjang;
    $diagnosis_kerja     = $q1->diagnosis_kerja;
    $dd     = $q1->dd;
    $terapi     = $q1->terapi;
    $observasi     = $q1->observasi;
    $waktu     = $q1->waktu;
    $assesment     = $q1->assesment;
    $a     = $q1->a;
    $p     = $q1->p;
    $tindak_lanjut     = $q1->tindak_lanjut;
    $ruang     = $q1->ruang;
    $rujuk_ke     = $q1->rujuk_ke;
    $alasan_rujuk     = $q1->alasan_rujuk;
    $lokasi     = $q1->lokasi;
    $pengirim     = $q1->pengirim;
    $frekuensi     = $q1->frekuensi;
    $durasi     = $q1->durasi;
    $kedatangan     = $q1->kedatangan;
    $diantar     = $q1->diantar;
    $riwayat_alergi     = $q1->riwayat_alergi;
    $tindakan_radiologi     = $q1->tindakan_radiologi;
    $tindakan_lab     = $q1->tindakan_lab;
    $nama_pasien = $q1->nama_pasien1;
    $gcs = $q1->gcs;
    $e = $q1->e;
    $v = $q1->v;
    $m = $q1->m;
    $kesadaran = $q1->kesadaran;
    $kelainan = explode("|", $q1->kelainan);
    if ($q1->pemeriksaan_fisik != "")
        $pemeriksaan_fisik = explode(",", $q1->pemeriksaan_fisik);
    $td      = $q1->td;
    $td2      = $q1->td2;
    $nadi      = $q1->nadi;
    $respirasi      = $q1->respirasi;
    $suhu      = $q1->suhu;
    $no_batch      = $q1->no_batch;
    $spo2      = $q1->spo2;
    $bb      = $q1->bb;
    $tb      = $q1->tb;
    $skrining_gizi2 = $q1->skrining_gizi2;
    $jam_meninggal      = $q1->jam_meninggal;
    $nama_vaksin     = $q1->nama_vaksin;
    $petugas_vaksin     = $q1->petugas_vaksin;
    $dosis_vaksin     = $q1->dosis_vaksin;
    $jadwal_vaksin2     = $q1->jadwal_vaksin2=="" ? "" : date("d-m-Y", strtotime($q1->jadwal_vaksin2));
    $nama_kelas =
        $nama_ruangan =
        $kode_kelas =
        $kode_kamar = "";
} else {
    $dokter_poli = "";
    $tanggal_masuk  = "";
    $jam_masuk      = date("H:i:s");
        $jam_periksa      = date("Y-m-d");
        $jam_keluar_igd      =
        $nyeri      =
        $no_batch      =
        $jenis_nyeri =
        $resiko_jatuh      =
        $skrining_gizi      =
        $keluhan_utama      =
        $kronologis_kejadian      =
        $anamnesa   =
        $riwayat_penyakit      =
        $obat_dikonsumsi      =
        $pemeriksaan_penunjang      =
        $diagnosis_kerja     =
        $dd     =
        $gcs =
        $e = $v = $m =
        $kesadaran =
        $terapi     =
        $observasi     =
        $waktu     =
        $assesment     =
        $pengirim =
        $a     =
        $p     =
        $td      =
        $td2      =
        $nadi      =
        $respirasi      =
        $suhu      =
        $spo2      =
        $bb      =
        $tb      =
        $jam_meninggal =
        $tindak_lanjut     =
        $ruang     =
        $rujuk_ke     =
        $alasan_rujuk     =
        $lokasi     =
        $frekuensi     =
        $durasi     =
        $kedatangan =
        $riwayat_alergi     =
        $diantar =
        $tindakan_radiologi =
        $tindakan_lab =
        $nama_kelas =
        $nama_pasien =
        $nama_ruangan =
        $kode_kelas =
        $skrining_gizi2 =
        $nama_vaksin =
        $petugas_vaksin =
        $dosis_vaksin =
        $jadwal_vaksin2 =
        $kode_kamar = "";
}
list($year, $month, $day) = explode("-", $q1->tgl_lahir);
$year_diff  = date("Y") - $year;
$month_diff = date("m") - $month;
$day_diff   = date("d") - $day;
if ($month_diff < 0) {
    $year_diff--;
    $month_diff *= (-1);
} elseif (($month_diff == 0) && ($day_diff < 0)) $year_diff--;
if ($day_diff < 0) {
    $day_diff *= (-1);
}
$umur = $year_diff . " Tahun";
?>
<div class="col-md-12">
    <?php echo form_open("dokter/simpanigdralan/" . $no_reg, array("id" => "formsave", "class" => "form-horizontal"));
    if ($this->session->flashdata('message')) {
        $pesan = explode('-', $this->session->flashdata('message'));
        echo "<div class='alert alert-" . $pesan[0] . "' alert-dismissable>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <b>" . $pesan[1] . "</b>
            </div>";
    }
    ?>
    <div class="box box-primary">
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-1 control-label">No. Reg</label>
                    <div class="col-md-2">
                        <input type="hidden"  class="form-control" name='dokter_igd'  value="<?php echo $dokter_poli; ?>" />
                        <input type="hidden"  class="form-control" name='tanggal_masuk'  value="<?php echo $tanggal_masuk; ?>" />
                        <input type="hidden"  class="form-control" name='jam_masuk'  value="<?php echo $jam_masuk; ?>" />
                        <input type="hidden"  class="form-control" name='jam_periksa'  value="<?php echo $jam_periksa; ?>" />
                        <input type="text"  class="form-control" name='no_reg'  value="<?php echo $no_reg; ?>" />
                    </div>
                    <label class="col-md-1 control-label">No. RM</label>
                    <div class="col-md-2">
                        <input type="text"  class="form-control" readonly name='no_rm'  value="<?php echo $no_pasien; ?>" />
                    </div>
                    <label class="col-md-1 control-label">Nama Pasien</label>
                    <div class="col-md-2">
                        <input type="text"  class="form-control" readonly name='nama_pasien' value="<?php echo $nama_pasien; ?>" />
                    </div>
                    <label class="col-md-1 control-label">Umur</label>
                    <div class="col-md-2">
                        <input type="text"  class="form-control" readonly name='umur' value="<?php echo $umur; ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <div class="form-group">
                <label class="col-md-2 control-label">Kedatangan</label>
                <div class="col-md-4">
                    <select name="kedatangan" class="form-control">
                        <option value="Datang Sendiri" <?php if ($q1->kedatangan == "Datang Sendiri") : ?> selected <?php endif ?>>Datang Sendiri</option>
                        <option value="Rujukan RS" <?php if ($q1->kedatangan == "Rujukan RS") : ?> selected <?php endif ?>>Rujukan RS</option>
                        <option value="Rujukan Dokter" <?php if ($q1->kedatangan == "Rujukan Dokter") : ?> selected <?php endif ?>>Rujukan Dokter</option>
                        <option value="Rujukan Paramedis" <?php if ($q1->kedatangan == "Rujukan Paramedis") : ?> selected <?php endif ?>>Rujukan Paramedis</option>
                        <option value="Rujukan Puskesmas" <?php if ($q1->kedatangan == "Rujukan Puskesmas") : ?> selected <?php endif ?>>Rujukan Puskesmas</option>
                        <option value="Rujukan Kepolisian" <?php if ($q1->kedatangan == "Rujukan Kepolisian") : ?> selected <?php endif ?>>Rujukan Kepolisian</option>
                        <option value="Rujukan Lain" <?php if ($q1->kedatangan == "Rujukan Lain") : ?> selected <?php endif ?>>Rujukan Lain</option>
                    </select>
                </div>
                <label class="col-md-2 control-label">Pengirim</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name='pengirim' value="<?php echo $pengirim; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">TD Kanan</label>
                <div class="col-md-2">
                    <input type="text" class="form-control"  name='td' value="<?php echo $td; ?>" />
                </div>
                <label class="col-md-1 control-label">TD Kiri</label>
                <div class="col-md-2">
                    <input type="text" class="form-control"  name='td2' value="<?php echo $td2; ?>" />
                </div>
                <label class="col-md-2 control-label">Nadi</label>
                <div class="col-md-3">
                    <input type="text" class="form-control"  name='nadi' value="<?php echo $nadi; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Respirasi</label>
                <div class="col-md-2">
                    <input type="text" class="form-control"  name='respirasi' value="<?php echo $respirasi; ?>" />
                </div>
                <label class="col-md-1 control-label">Suhu</label>
                <div class="col-md-2">
                    <input type="text" class="form-control"  name='suhu' value="<?php echo $suhu; ?>" />
                </div>
                <label class="col-md-2 control-label">SpO2</label>
                <div class="col-md-3">
                    <input type="text" class="form-control"  name='spo2' value="<?php echo $spo2; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">BB</label>
                <div class="col-md-2">
                    <input type="text" class="form-control"   name='bb' value="<?php echo $bb; ?>" />
                </div>
                <label class="col-md-1 control-label">TB</label>
                <div class="col-md-2">
                    <input type="text" class="form-control"  name='tb' value="<?php echo $tb; ?>" />
                </div>
                <label class="col-md-2 control-label">Tgl Vaksin 2</label>
                <div class="col-md-2">
                    <input type="text" class="form-control" autocomplete="off"  name='jadwal_vaksin2' value="<?php echo $jadwal_vaksin2; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Kesadaran</label>
                <div class="col-md-6">
                    <select name="kesadaran" id="" class="form-control">
                        <option value="">---</option>
                        <option value="Compos Metis" <?php echo ($kesadaran == "Compos Metis" ? "selected" : ""); ?>>Compos Metis</option>
                        <option value="Apatis" <?php echo ($kesadaran == "Apatis" ? "selected" : ""); ?>>Apatis</option>
                        <option value="Somnolen" <?php echo ($kesadaran == "Somnolen" ? "selected" : ""); ?>>Somnolen</option>
                        <option value="Delirium" <?php echo ($kesadaran == "Delirium" ? "selected" : ""); ?>>Delirium</option>
                        <option value="Sopor" <?php echo ($kesadaran == "Sopor" ? "selected" : ""); ?>>Sopor</option>
                        <option value="Coma" <?php echo ($kesadaran == "Coma" ? "selected" : ""); ?>>Coma</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <input type="number" class="form-control" placeholder="GCS" name='gcs' value="<?php echo $gcs; ?>" />
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control" placeholder="E" name='e' value="<?php echo $e; ?>" />
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control" placeholder="V" name='v' value="<?php echo $v; ?>" />
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control" placeholder="M" name='m' value="<?php echo $m; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nyeri</label>
                <div class="col-md-2">
                    <select name="nyeri" id="" class="form-control">
                        <option value="">---</option>
                        <option value="YA" <?php echo ($nyeri == "YA" ? "selected" : ""); ?>>YA</option>
                        <option value="TIDAK" <?php echo ($nyeri == "TIDAK" ? "selected" : ""); ?>>Tidak</option>
                    </select>
                </div>
                <label class="col-md-2 control-label">No Batch</label>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="no batch"  name='no_batch' value="<?php echo $no_batch; ?>" />
                </div>
                <div class="col-md-2">
                    <select name="jenis_nyeri" id="" class="form-control">
                        <option value="Nyeri Akut" <?php echo ($jenis_nyeri == "Nyeri Akut" ? "selected" : ""); ?>>Nyeri Akut</option>
                        <option value="Nyeri Kronis" <?php echo ($jenis_nyeri == "Nyeri Kronis" ? "selected" : ""); ?>>Nyeri Kronis</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="Lokasi" name='lokasi' value="<?php echo $lokasi; ?>" />
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="Frekuensi" name='frekuensi' value="<?php echo $frekuensi; ?>" />
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="Durasi" name='durasi' value="<?php echo $durasi; ?>" />
                </div>
            </div>
            <div class="form-group">
              <label class="col-md-2 control-label">Nama Vaksin</label>
              <div class="col-md-2">
                  <select name="nama_vaksin" class="form-control">
                      <option value="">---</option>
                      <?php
                        foreach ($nv->result() as $vrow) {
                          echo "<option value='".$vrow->id."' ".($nama_vaksin==$vrow->id ? "selected" : "").">".$vrow->nama_vaksin."</option>";
                        }
                      ?>
                  </select>
              </div>
              <label class="col-md-2 control-label">Dosis Vaksin</label>
              <div class="col-md-2">
                  <select name="dosis_vaksin" id="" class="form-control">
                      <option value="">---</option>
                      <?php
                        foreach ($dosis->result() as $drow) {
                          echo "<option value='".$drow->dosis."' ".($nama_dosis==$drow->dosis ? "selected" : "").">".$drow->dosis."</option>";
                        }
                      ?>
                  </select>
              </div>
              <label class="col-md-2 control-label">Petugas Vaksin</label>
              <div class="col-md-2">
                  <select name="petugas_vaksin" id="" class="form-control">
                      <option value="">---</option>
                      <?php
                        foreach ($pwt->result() as $prow) {
                          echo "<option value='".$prow->id_perawat."'  ".($petugas_vaksin==$prow->id_perawat ? "selected" : "").">".$prow->nama_perawat."</option>";
                        }
                      ?>
                  </select>
              </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Resiko Jatuh</label>
                <div class="col-md-4">
                    <select name="resiko_jatuh" id="" class="form-control">
                        <option value="">---</option>
                        <option value="Resiko Rendah Morse (0-24)" <?php echo ($resiko_jatuh == "Resiko Rendah Morse (0-24)" ? "selected" : ""); ?>>Resiko Rendah Morse (0-24)</option>
                        <option value="Resiko Sedang Morse (25-50)" <?php echo ($resiko_jatuh == "Resiko Sedang Morse (25-50)" ? "selected" : ""); ?>>Resiko Sedang Morse (25-50)</option>
                        <option value="Resiko Tinggi Morse (>= 51)" <?php echo ($resiko_jatuh == "Resiko Tinggi Morse (>= 51)" ? "selected" : ""); ?>>Resiko Tinggi Morse (>= 51)</option>
                        <option value="Resiko Rendah Humpty Dumpty (7-11)" <?php echo ($resiko_jatuh == "Resiko Rendah Humpty Dumpty (7-11)" ? "selected" : ""); ?>>Resiko Rendah Humpty Dumpty (7-11)</option>
                        <option value="Resiko Tinggi Humpty Dumpty (>= 12)" <?php echo ($resiko_jatuh == "Resiko Tinggi Humpty Dumpty (>= 12)" ? "selected" : ""); ?>>Resiko Tinggi Humpty Dumpty (>= 12)</option>
                    </select>
                </div>
                <label class="col-md-2 control-label">Riwayat Alergi</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name='riwayat_alergi' value="<?php echo $riwayat_alergi; ?>" />
                </div>
            </div>
            <div class="form-group">

                <label class="col-md-2 control-label">Skrining Gizi Awal</label>
                <div class="col-md-4">
                    <select name="skrining_gizi" id="" class="form-control">
                        <option value="">---</option>
                        <option value="< 2" <?php echo ($skrining_gizi == "< 2" ? "selected" : ""); ?>> > 2</option>
                        <option value="> 2" <?php echo ($skrining_gizi == "> 2" ? "selected" : ""); ?>>
                            < 2 / Diagnosis khusus sudah dilaporkan ke tim terapi Gizi</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="skrining_gizi2" id="" class="form-control">
                        <option value="">---</option>
                        <option value="Ya" <?php echo ($skrining_gizi2 == "Ya" ? "selected" : ""); ?>>Ya</option>
                        <option value="Tidak" <?php echo ($skrining_gizi2 == "Tidak" ? "selected" : ""); ?>>Tidak</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Kronologis Kejadian</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="kronologis_kejadian" style="max-width: 100%;height:100px;"><?php echo $kronologis_kejadian ?></textarea>
                </div>
                <label class="col-md-2 control-label">Riwayat Penyakit</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="riwayat_penyakit" style="max-width: 100%;height:100px;"><?php echo $riwayat_penyakit ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Obat - obatan yang dikonsumsi</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="obat_dikonsumsi" style="max-width: 100%;height:100px;"><?php echo $obat_dikonsumsi ?></textarea>
                </div>
                <label class="col-md-2 control-label">Observasi</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="observasi" style="max-width: 100%;height:100px;"><?php echo $observasi ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Waktu</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="waktu" style="max-width: 100%;height:50px;"><?php echo $waktu ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">S</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="s"  style="max-width: 100%;height:160px;"><?php echo $s ?></textarea>
                </div>
                <label class="col-md-2 control-label">O</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="o"  style="max-width: 100%;height:160px;"><?php echo $o ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">A</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="a" style="max-width: 100%;height:160px;"><?php echo $a ?></textarea>
                </div>
                <label class="col-md-2 control-label">P</label>
                <div class="col-md-4">
                    <textarea class="form-control" name="p" style="max-width: 100%;height:160px;"><?php echo $p ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="pull-right">
            <div class="btn-group">
                <button class="back btn btn-warning" type="button"><i class="fa fa-arrow-left"></i> Back</button>
                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button>
                <button class="cetak btn btn-primary" type="button"><i class="fa fa-print"></i> Cetak</button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
</div>
<div class="modal fade modalnotif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">Yakin akan membayar sejumlah</div>
            <div class="modal-body">
                <h2 class="total"></h2>
            </div>
            <div class="modal-footer">
                <button class="okbayar btn btn-success" type="button">OK</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modalresume" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                Resume Pasien Rawat Jalan Berkelanjutan
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-navy">
                                <th style="vertical-align: middle">No</th>
                                <th style="vertical-align: middle">Tgl, Jam Kunjungan</th>
                                <th style="vertical-align: middle">Poliklinik yang dituju</th>
                                <th style="vertical-align: middle">Diagnosa</th>
                                <th style="vertical-align: middle">Pengobatan Saat ini</th>
                                <th style="vertical-align: middle">Alergi</th>
                                <th style="vertical-align: middle">Tindakan/Operasi dan Rawat Inap dimasa lalu</th>
                                <th style="vertical-align: middle">Paraf DPJP</th>
                                <th style="vertical-align: middle">ICD X/IX</th>
                            </tr>
                        </thead>
                        <tbody class="listresume">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='loading modal'>
    <div class='text-center align-middle' style="margin-top: 200px">
        <div class="col-xs-3 col-sm-3 col-lg-5"></div>
        <div class="alert col-xs-6 col-sm-6 col-lg-2" style="background-color: white;border-radius: 10px;">
            <div class="overlay" style="font-size:50px;color:#696969"><img src="<?php echo base_url(); ?>/img/load.gif" width="150px"></div>
            <div style="font-size:20px;font-weight:bold;color:#696969;margin-top:-30px;margin-bottom:20px">Loading</div>
        </div>
        <div class="col-xs-3 col-sm-3 col-lg-5"></div>
    </div>
</div>
<style type="text/css">
    ul.wysihtml5-toolbar {
        display: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -15px;
    }

    .select2-container--default .select2-selection--single {
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
