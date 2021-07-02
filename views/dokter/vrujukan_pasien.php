<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/select2/select2.css">
<script src="<?php echo base_url(); ?>plugins/select2/select2.js"></script>
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
            var no_pasien = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var url = "<?php echo site_url('dokter/cetakresumeinap'); ?>/" + no_pasien + "/" + no_reg;
            openCenteredWindow(url);
        });
        $(".textarea").wysihtml5({
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
            }
        });
        var text = $('.hasilpemeriksaan').val(),
            matches = text.match(/\n/g),
            breaks = matches ? matches.length : 2;
        $('.hasilpemeriksaan').attr('rows', breaks + 2);
        $('.back').click(function() {
            var cari_noreg = $("[name='no_reg']").val();
            $.ajax({
                type: "POST",
                data: {
                    cari_noreg: cari_noreg
                },
                url: "<?php echo site_url('pendaftaran/getcaripasien_ralan'); ?>",
                success: function(result) {
                    window.location = "<?php echo site_url('dokter/rawat_inapdokter_ranap'); ?>";
                },
                error: function(result) {
                    alert(result);
                }
            });
        });
    });

    function tgl_indo(tgl, tipe = 1) {
        var date = tgl.substring(tgl.length, tgl.length - 2);
        if (tipe == 1)
            var bln = tgl.substring(5, 7);
        else
            var bln = tgl.substring(4, 6);
        var thn = tgl.substring(0, 4);
        return date + "-" + bln + "-" + thn;
    }
</script>
<?php
list($year, $month, $day) = explode("-", $q->tgl_lahir);
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
$umur = $year_diff . " tahun ";
if ($p->num_rows() > 0) {
    $row = $p->row();
    $keluhan_utama = $row->keluhan_utama;
    $diagnosa = $row->diagnosa;
    $tindakan = $row->tindakan;
    $pengantar = $row->pengantar;
    $telepon = $row->telepon;
    $dikirim = $row->dikirim;
    $penerima = $row->penerima;
    $alasan = $row->alasan;
    $pemeriksaan_fisik = $row->pemeriksaan_fisik;
    $action = "edit";
} else {
    $keluhan_utama =
        $diagnosa =
        $tindakan =
        $pengantar =
        $telepon =
        $dikirim =
        $penerima =
        $pemeriksaan_fisik =
        $alasan = "";
    $action = "simpan";
}
if ($ad) {
    $pem = explode(",", $ad->pemeriksaan_fisik);
    $kelainan = explode("|", $ad->kelainan);
} else {
    $pem = array();
    $kelainan = array();
}

if ($p->num_rows() > 0) {
    $row = $p->row();
    $ekg = $row->ekg;
} else {
    $ekg = "-";
}
?>
<div class="col-md-12">
    <?php
    if ($this->session->flashdata('message')) {
        $pesan = explode('-', $this->session->flashdata('message'));
        echo "<div class='alert alert-" . $pesan[0] . "' alert-dismissable>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <b>" . $pesan[1] . "</b>
            </div>";
    }

    ?>
    <div class="box box-primary">
        <div class="form-horizontal">
            <?php echo form_open("dokter/simpanrujukan_pasien2/" . $action); ?>
            <input type="hidden" name="no_pasien" value='<?php echo $no_pasien; ?>'>
            <input type="hidden" name="no_reg" value='<?php echo $no_reg; ?>'>
            <div class="box-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Nama Pasien</label>
                    <div class="col-md-3">
                        <input type="hidden" class="form-control" name='pengantar' readonly value="<?php echo $pengantar; ?>" />
                        <input type="hidden" class="form-control" name='telepon' readonly value="<?php echo $telepon; ?>" />
                        <input type="hidden" class="form-control" name='dikirim' readonly value="<?php echo $dikirim; ?>" />
                        <input type="hidden" class="form-control" name='penerima' readonly value="<?php echo $penerima; ?>" />
                        <input type="hidden" class="form-control" name='alasan' readonly value="<?php echo $alasan; ?>" />
                        <input type="hidden" class="form-control" name='jenis' readonly value="<?php echo $jenis; ?>" />
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_pasien; ?>" />
                    </div>
                    <label class="col-md-3 control-label">Ruangan/ Kelas</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='ruangan' readonly value="<?php echo $q->nama_ruangan . "/ " . $q->nama_kelas; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Golongan pasien</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" readonly value="<?php echo $q->ket_gol_pasien; ?>" />
                    </div>
                    <label class="col-md-3 control-label">No. RM</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='no_pasien' readonly value="<?php echo $no_pasien; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Tgl Lahir / Umur</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" readonly value="<?php echo date("d-m-Y", strtotime($q->tgl_lahir)) . ' / ' . $umur; ?>" />
                    </div>
                    <label class="col-md-3 control-label">No. Reg</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" readonly value="<?php echo $no_reg; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Tgl Masuk</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='tgl_masuk' readonly value="<?php echo date("d-m-Y", strtotime($q->tgl_masuk)); ?>" />
                    </div>
                    <label class="col-md-3 control-label">Tgl Keluar</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='tgl_keluar' readonly value="<?php echo ($q->tgl_keluar == "" ? "-" : date("d-m-Y", strtotime($q->tgl_keluar))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Jam Masuk</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='jam_masuk' readonly value="<?php echo date("H:i:s", strtotime($q->jam_masuk)); ?>" />
                    </div>
                    <label class="col-md-3 control-label">Jam Keluar</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='jam_keluar' readonly value="<?php echo ($q->jam_keluar == "" ? "-" : date("H:i:s", strtotime($q->jam_keluar))); ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Keluhan Utama</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name='keluhan_utama'><?php echo $keluhan_utama; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Pemeriksaan Fisik</label>
                    <div class="col-md-9">
                        <?php
                        $ada = 0;
                        for ($i = 0; $i <= 10; $i++) {
                            if (!$pem[$i] && $pem[$i]!="") {
                                $ada = 1;
                                break;
                            }
                        }
                        echo ($q1->td == "" ? "" : "TD ka : " . $q1->td . " mmHg, ");
                        echo ($q1->td2 == "" ? "" : "TD ki : " . $q1->td2 . " mmHg, ");
                        echo ($q1->nadi == "" ? "" : "Nadi : " . $q1->nadi . " x/ mnt, ");
                        echo ($q1->respirasi == "" ? "" : "Respirasi : " . $q1->respirasi . " x/ mnt, ");
                        echo ($q1->suhu == "" ? "" : "Suhu : " . $q1->suhu . " °C, ");
                        echo ($q1->spo2 == "" ? "" : "SpO2 : " . $q1->spo2 . " %, ");
                        echo ($q1->bb == "" ? "" : "BB : " . $q1->bb . " kg, ");
                        echo ($q1->tb == "" ? "" : "TB : " . $q1->tb . " cm, ");
                        echo "<br>";
                        echo "<textarea class='form-control hasilpemeriksaan textarea' name='pemeriksaan_fisik'>" . ($pemeriksaan_fisik=="" ? $q1->o : $pemeriksaan_fisik) . "</textarea>";
                        ?>
                        <?php if ($ada) : ?>
                            <table width="100%" cellspacing="0" cellpadding="1">
                                <tr>
                                    <th>Pemeriksaan</th>
                                    <th>Kelainan</th>
                                </tr>
                                <?php if ($pem[0] != "1") : ?>
                                    <tr>
                                        <td width=200px>Kepala</td>
                                        <td><?php echo (isset($kelainan[0]) ? ($pem[0] == "1" ? "" : $kelainan[0]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[1] != "1") : ?>
                                    <tr>
                                        <td>Mata</td>
                                        <td><?php echo (isset($kelainan[1]) ? ($pem[1] == "1" ? "" : $kelainan[1]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[2] != "1") : ?>
                                    <tr>
                                        <td>THT</td>
                                        <td><?php echo (isset($kelainan[2]) ? ($pem[2] == "1" ? "" : $kelainan[2]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[3] != "1") : ?>
                                    <tr>
                                        <td>Gigi Mulut</td>
                                        <td><?php echo (isset($kelainan[3]) ? ($pem[3] == "1" ? "" : $kelainan[3]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[4] != "1") : ?>
                                    <tr>
                                        <td>Leher</td>
                                        <td><?php echo (isset($kelainan[4]) ? ($pem[4] == "1" ? "" : $kelainan[4]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[5] != "1") : ?>
                                    <tr>
                                        <td>Thoraks</td>
                                        <td><?php echo (isset($kelainan[5]) ? ($pem[5] == "1" ? "" : $kelainan[5]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[6] != "1") : ?>
                                    <tr>
                                        <td>Abdomen</td>
                                        <td><?php echo (isset($kelainan[6]) ? ($pem[6] == "1" ? "" : $kelainan[6]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[7] != "1") : ?>
                                    <tr>
                                        <td>Ekstremitas Atas</td>
                                        <td><?php echo (isset($kelainan[7]) ? ($pem[7] == "1" ? "" : $kelainan[7]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[8] != "1") : ?>
                                    <tr>
                                        <td>Ekstremitas Bawah</td>
                                        <td><?php echo (isset($kelainan[8]) ? ($pem[8] == "1" ? "" : $kelainan[8]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[9] != "1") : ?>
                                    <tr>
                                        <td>Genitalia</td>
                                        <td><?php echo (isset($kelainan[9]) ? ($pem[9] == "1" ? "" : $kelainan[9]) : ''); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($pem[10] != "1") : ?>
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
                                foreach ($k->result() as $data) {
                                    $tgl1_print = $tgl1_print == "" ? date("d-m-Y", strtotime($data->tanggal)) : $tgl1_print;
                                    $tgl2_print = date("d-m-Y", strtotime($data->tanggal));
                                    if ($kode_judul != $data->kode_judul) {
                                        echo "<tr class='bg-orange'>";
                                        echo "<td colspan='7'>" . $data->judul . "</td>";
                                        $kode_judul = $data->kode_judul;
                                        $i = 0;
                                    }
                                    if ($data->jenis_kelamin == "L") {
                                        $rujukan = $data->pria;
                                    } else {
                                        $rujukan = $data->wanita;
                                    }
                                    $i++;
                                    if ($kode_tindakan != $data->kode_tindakan) {
                                        $nama_tindakan = $data->nama_tindakan;
                                        $kode_tindakan = $data->kode_tindakan;
                                    } else {
                                        $nama_tindakan = "";
                                    }
                                    echo "<tr>";
                                    echo "<td>" . $i . "</td>";
                                    echo "<td>" . ($jenis=="ralan" ? date("d-m-Y", strtotime($q->tgl_masuk)) : date("d-m-Y", strtotime($data->tanggal))) . "</td>";
                                    echo "<td>" . $nama_tindakan . "</td>";
                                    echo "<td>" . $data->nama . "</td>";
                                    echo "<td><input type='text' class='form-control' readonly name='hasil[" . $data->kode . "]' value='" . (isset($hasil[$data->kode][$data->pemeriksaan][$data->tanggal]) ? $hasil[$data->kode][$data->pemeriksaan][$data->tanggal]->hasil : "") . "'></td>";
                                    echo "<td>" . $data->satuan . "</td>";
                                    echo "<td>" . $rujukan . "</td>";
                                    echo "</tr>";
                                }
                                $tgl1_print = $tgl1_print == "" ? date("d-m-Y") : $tgl1_print;
                                $tgl2_print = $tgl2_print == "" ? date("d-m-Y") : $tgl2_print;
                                ?>
                            </tbody>
                        </table>
                        <p class="text-bold">2. Radiologi</p>
                        <?php
                        $i = 1;
                        $subtotal = 0;
                        echo "<ul>";
                        foreach ($rad->result() as $data) {
                            echo "<li>";
                            echo ($jenis=="ralan" ? date("d-m-Y", strtotime($q->tgl_masuk)) : date("d-m-Y", strtotime($data->tanggal))) . " " . $data->nama_tindakan . "<br>";
                            echo '<div class="form-group">';
                            echo '<label class="col-md-12 control-label">Hasil Pemeriksaan</label>';
                            echo '<div class="col-md-6">';
                            echo "<textarea class='form-control hasilpemeriksaan' readonly>" . $data->hasil_pemeriksaan . "</textarea>";
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
                        foreach ($pa->result() as $data) {
                            echo "<li>";
                            echo ($jenis=="ralan" ? date("d-m-Y", strtotime($q->tgl_masuk)) : date("d-m-Y", strtotime($data->tanggal))) . " " . $data->nama_tindakan . "<br>";
                            echo '<div class="form-group">';
                            echo '<label class="col-md-12 control-label">Hasil Pemeriksaan</label>';
                            echo '<div class="col-md-6">';
                            echo "<textarea class='form-control hasilpemeriksaan' readonly>" . $data->hasil_pemeriksaan . "</textarea>";
                            echo "</div>";
                            echo "</li>";
                        }
                        echo "</ul>";
                        ?>
                        <p class="text-bold">4. EKG</p>
                        <div class="col-md-12">
                            <textarea class="form-control" name='ekg'><?php echo $ekg; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Diagnosa</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name='diagnosa' required value="<?php echo $diagnosa; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Tindakan</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name='tindakan' required value="<?php echo $tindakan; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12 control-label">Obat selama perawatan di RS</label>
                    <div class="col-md-12">
                        <?php
                        $i = 1;
                        $koma = "";
                        foreach ($ob->result() as $data) {
                            if ($nama_obat != $data->nama_obat) {
                                if ($q->tgl_keluar != $data->tanggal) {
                                    echo $koma . $data->nama_obat;
                                    $koma = ", ";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <!-- <button class="cetak btn btn-primary" type="button"><i class="fa fa-print"></i> Cetak</button> -->
                        <button class="simpan btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <style type="text/css">
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
