<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/library.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-qrcode.js"></script>
    <script src="<?php echo base_url(); ?>js/html2pdf.bundle.js"></script>
    <script src="<?php echo base_url(); ?>js/html2canvas.js"></script>
    <link rel="icon" href="<?php echo base_url(); ?>img/computer.png" type="image/x-icon" />
</head>
<script>
    $(document).ready(function() {
        getttd();
        getttd_dpjp();
        window.print();
    });

    function getttd_dpjp() {
        var ttd = "<?php echo site_url('ttddokter/getttddokterlab/' . $q->dpjp); ?>";
        $('.dokter_qrcode').qrcode({
            width: 80,
            height: 80,
            text: ttd
        });
    }

    function getttd() {
        var ttd = "<?php echo site_url('ttddokter/getttdpasien/' . $no_pasien); ?>";
        $('.pasien_qrcode').qrcode({
            width: 80,
            height: 80,
            text: ttd
        });
    }

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

if ($ad) {
    $pem = explode(",", $ad->pemeriksaan_fisik);
    $kelainan = explode("|", $ad->kelainan);
} else {
    $pem = array();
    $kelainan = array();
}
if ($ok->num_rows() > 0) {
    $row = $ok->row();
    $nama_operasi = $row->nama_operasi;
} else {
    $nama_operasi = "-";
}
?>
<p align="right" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <small>RM13/RI/RSC/REV1</small>
</p>
<table class="laporan" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td rowspan="3" align="center" style="vertical-align:middle">
            <img src="<?php echo base_url("img/Logo.png") ?>"><br><b>RS CIREMAI</b>
        </td>
        <td rowspan="3" align="center" style="vertical-align: middle;">
            <h4>RUJUKAN PASIEN</h4>
        </td>
        <td>
            <table class="no-border" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        Nama Pasien
                    </td>
                    <td>
                        <?php echo $q->nama_pasien; ?>
                    </td>
                    <td>Ruangan/ Kelas</td>
                    <td>
                        <?php echo $q->nama_ruangan . "/ " . $q->nama_kelas; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Golongan Pasien
                    </td>
                    <td>
                        <?php echo $q->ket_gol_pasien; ?>
                    </td>
                    <td>No. RM</td>
                    <td>
                        <?php echo $no_pasien; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Tgl Lahir / Umur
                    </td>
                    <td>
                        <?php echo date("d-m-Y", strtotime($q->tgl_lahir)) . ' / ' . $umur; ?>
                    </td>
                    <td>No. Reg</td>
                    <td>
                        <?php echo $no_reg; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Tgl Masuk
                    </td>
                    <td>
                        <?php echo date("d-m-Y", strtotime($q->tgl_masuk)); ?>
                    </td>
                    <td>Tgl Keluar</td>
                    <td>
                        <?php echo ($q->tgl_keluar == "" ? "-" : date("d-m-Y", strtotime($q->tgl_keluar))); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Jam Masuk
                    </td>
                    <td>
                        <?php echo date("H:i:s", strtotime($q->jam_masuk)); ?>
                    </td>
                    <td>Jam Keluar</td>
                    <td>
                        <?php echo ($q->jam_keluar == "" ? "-" : date("H:i:s", strtotime($q->jam_keluar))); ?>
                    </td>
                </tr>
                <tr>
                    <td>Nomor Surat</td>
                    <?php $bulan = array("", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"); ?>
                    <td><?php echo $p->nomor_surat ?>/ RP/ <?php echo $bulan[(int)(date("m", strtotime($p->tgl_insert)))] . "/ " . date("Y", strtotime($p->tgl_insert)); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="no-border" width="100%">
    <tr>
        <td width=200px>
            Nama Pengantar / Keluarga terdekat
        </td>
        <td><?php echo $p->pengantar; ?></td>
    </tr>
    <tr>
        <td>
            No. Telepon / HP
        </td>
        <td><?php echo $p->telepon; ?></td>
    </tr>
    <tr>
        <td>Dikirim </td>
        <td><?php echo $p->dikirim; ?></td>
    </tr>
    <tr>
        <td>Penerima yang sudah dihububngi</td>
        <td><?php echo $p->penerima; ?></td>
    </tr>
    <tr>
        <td colspan="2">Kami mengirim pasien tersebut diatas untuk perawatan selanjutnya dengan alasan<br>
            <?php
            $alasan = explode(",", $p->alasan);
            echo "<ul>";
            foreach ($alasan as $key => $value) {
                if ($value != "")
                    echo "<li>" . $value . "</li>";
            }
            echo "</ul>";
            ?>
        </td>
    </tr>
</table>
<table class="no-border" width="100%">
    <tr>
        <td width=200px>
            Keluhan Utama
        </td>
        <td colspan=3>
            <?php echo $p->keluhan_utama; ?>
        </td>
    </tr>
    <tr>
        <td>
            Pemeriksaan Fisik
        </td>
        <td colspan=3>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=4>
            Hasil Pemeriksaan Penunjang Medis
        </td>
    </tr>
    <tr>
        <td colspan=4>
            <table class="laporan1" width="100%">
                <tr>
                    <th width=50px>1.</th>
                    <th align="left">Labotarium</th>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <table class="laporan1" width="100%">
                            <tr>
                                <th width="10" class='text-center'>No</th>
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
                            foreach ($k->result() as $data) {
                                $tgl1_print = $tgl1_print == "" ? date("d-m-Y", strtotime($data->tanggal)) : $tgl1_print;
                                $tgl2_print = date("d-m-Y", strtotime($data->tanggal));
                                if ($kode_judul != $data->kode_judul) {
                                    echo "<tr>";
                                    echo "<td colspan='6'>" . $data->judul . "</td>";
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
                                echo "<td>" . $nama_tindakan . "</td>";
                                echo "<td>" . $data->nama . "</td>";
                                echo "<td>" . (isset($hasil[$data->kode]) ? $hasil[$data->kode]->hasil : "") . "</td>";
                                echo "<td>" . $data->satuan . "</td>";
                                echo "<td>" . $rujukan . "</td>";
                                echo "</tr>";
                            }
                            $tgl1_print = $tgl1_print == "" ? date("d-m-Y") : $tgl1_print;
                            $tgl2_print = $tgl2_print == "" ? date("d-m-Y") : $tgl2_print;
                            ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>2.</th>
                    <th align="left">Radiologi</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <?php
                        $i = 1;
                        $subtotal = 0;
                        echo "<ul>";
                        foreach ($rad->result() as $data) {
                            echo "<li>";
                            echo (isset($data->tanggal) ? date("d-m-Y", strtotime($data->tanggal)) : "-") . " " . $data->nama_tindakan . "<br>";
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
                <tr>
                    <th>3.</th>
                    <th align="left">Patologi Anatomi</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <?php
                        $i = 1;
                        $subtotal = 0;
                        echo "<ul>";
                        foreach ($pa->result() as $data) {
                            echo "<li>";
                            echo date("d-m-Y", strtotime($data->tanggal)) . " " . $data->nama_tindakan . "<br>";
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
                <tr>
                    <th>4.</th>
                    <th align="left">EKG</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><?php echo $ekg; ?></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            Diagnosa
        </td>
        <td colspan=3>
            <?php echo $p->diagnosa; ?>
        </td>
    </tr>
    <tr>
        <td>Tindakan</td>
        <td colspan=3><?php echo $p->tindakan; ?></td>
    </tr>
    <tr>
        <td>Terapi</td>
        <td colspan=3>
            <?php
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
        </td>
    </tr>
</table>
<table class="no-border" width="100%">
    <tr>
        <td width="70%">&nbsp;</td>
        <td align="center">
            Cirebon, Tgl <?php echo date("d-m-Y", strtotime($q->tgl_keluar)); ?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align="center">
            Dokter yang merawat (dpjp)
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align="right">
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
        background-color: #fff !important;
        border: 1px solid #000 !important;
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

    .no-border>thead>tr>th,
    .no-border>tbody>tr>th,
    .no-border>tfoot>tr>th,
    .no-border>thead>tr>td,
    .no-border>tbody>tr>td,
    .no-border>tfoot>tr>td {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 2px solid #ddd;
    }

    .no-border>thead>tr>th {
        vertical-align: bottom;
        border-bottom: 2px solid #ddd;
    }

    .no-border>caption+thead>tr:first-child>th,
    .no-border>colgroup+thead>tr:first-child>th,
    .no-border>thead:first-child>tr:first-child>th,
    .no-border>caption+thead>tr:first-child>td,
    .no-border>colgroup+thead>tr:first-child>td,
    .no-border>thead:first-child>tr:first-child>td {
        border-top: 0;
    }

    .no-border>tbody+tbody {
        border-top: 2px solid #ddd;
    }

    .no-border td,
    .no-border th {
        background-color: #fff !important;
        border: 0px solid #000 !important;
    }
</style>
