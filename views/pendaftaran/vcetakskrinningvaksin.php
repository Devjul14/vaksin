<?php
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
    $tgl_periksa =
    $jam =
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
    $ubah = "";
    $aksi = "simpan";
    $ttd = "";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/AdminLTE.css">
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-barcode.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-qrcode.js"></script>
    <!-- <script src="<?php echo base_url(); ?>js/html2pdf.bundle.js"></script> -->
    <!-- <script src="<?php echo base_url(); ?>js/html2canvas.js"></script> -->
    <!-- <script src="<?php echo base_url(); ?>js/jquery.mask.min.js"></script> -->
    <!-- <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script> -->
    <link rel="icon" href="<?php echo base_url(); ?>img/computer.png" type="image/x-icon" />

    <script type="text/javascript" src="<?php echo base_url() ?>js/library.js"></script>
</head>
<script>
    $(document).ready(function() {
        getttd_pasien();
        window.print();
    });

    function getttd_pasien() {
        var ttd = "<?php echo site_url('ttddokter/getttdpasien/' . $no_reg); ?>";
        $('.ttd_pasien').qrcode({
            width: 80,
            height: 80,
            text: ttd
        });
        var ttd = "<?php echo site_url('ttddokter/getttdpetugas/' . $q1->petugas_vaksin); ?>";
        $('.ttd_petugas').qrcode({
            width: 80,
            height: 80,
            text: ttd
        });
    }
</script>
<?php
function getRomawi($bulan)
{
    switch ($bulan) {
        case 1:
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }
}
$t1 = new DateTime('today');
$t2 = new DateTime($q1->tgl_lahir);
$y  = $t1->diff($t2)->y;
$m  = $t1->diff($t2)->m;
$d  = $t1->diff($t2)->d;

$lahir = new DateTime($q1->tgl_lahir);
$hari_ini = new DateTime();

$diff = $hari_ini->diff($lahir);
$umur = $diff->y;

if ($q->jenis_kelamin == "L") {
    $jenis_kelamin = "Laki-Laki";
} else {
    $jenis_kelamin = "Perempuan";
}
function tgl($tgl, $tipe)
{
    $month = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember");
    $xmonth = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nop", "Des");
    $hari = substr($tgl, 0, 10);
    $jam = substr($tgl, 11, 5);
    $m = (int)(substr($tgl, 5, 2));
    $tmp = substr($tgl, 8, 2) . " " . $month[$m] . " " . substr($tgl, 0, 4);
    if ($tipe == 1) {
        $tmp = $tmp . " - " . $jam;
    } elseif ($tipe == 2) {
        $tmp = $tmp;
    }
    if (substr($tgl, 0, 4) == '0000') {
        return "";
    } else {
        return $tmp;
    }
}

?>

<body class="page">
    <p>
    <h5>
        <b>
          KOMANDO DAERAH MILITER III/SILIWANGI KESEHATAN
        </b>
    </h5>
    </p>
    <br>
    <table class="table no-border laporan" align="center">
        <tr>
            <td colspan="2" style="vertical-align:middle">
                <h5 align="center"><b><u>FORM VAKSINASI COVID-19</u></b></h5>
            </td>
        </tr>
    </table>
    <table class="table no-border laporan">
        <tr>
            <td>Nama & Pangkat/ Gol<span style="float:right">:</span></td>
            <td><?php echo $q1->nama_pasien1; ?></td>
        </tr>
        <tr>
            <td>Tgl Lahir<span style="float:right">:</span></td>
            <td><?php echo date("d-m-Y",strtotime($q1->tgl_lahir)); ?> <span class="pull-right">Umur : <?php echo $umur; ?></span></td>
        </tr>
        <tr>
            <td>NRP/ NIP/ NIK<span style="float:right">:</span></td>
            <td><?php echo $q1->nik; ?> <span class="pull-right">No. HP : <?php echo $q1->nohp; ?></span></td>
        </tr>
        <tr>
            <td>Alamat/ Satuan<span style="float:right">:</span></td>
            <td><?php echo ($q1->alamat); ?></td>
        </tr>
        <tr>
            <td>Tgl Periksa<span style="float:right">:</span></td>
            <td><?php echo date("d-m-Y", strtotime($q1->tanggal)); ?> <span class="pull-right">Suhu : <?php echo $q1->suhu;?> &#176;C Tekanan Darah : <?php echo $q1->td;?></span></td>
        </tr>
    </table>
    <table class="table no-border laporan2">
      <tr>
        <th>No.</th>
        <th>Pertanyaan</th>
        <th>Ya</th>
        <th>Tidak</th>
      </tr>
      <tr>
        <td>1.</td>
        <td>Pertanyaan untuk  vaksinasi ke-1<br>
          Apakah Anda memiliki riwayat alergi berat seperti sesak  napas,  bengkak  dan urtikaria seluruh badan atau reaksi berat lainnya karena vaksin ?<br>
          Pertanyaan untuk vaksinasi ke-2 :<br>
          Apakah Anda memiliki riwayat alergi berat setelah divaksinasi COVID-19 sebelumnya ?</td>
          <td class='text-center'><?php echo ($pertanyaan1_1=="Ya" ? "&#10004;" : "");?></td>
          <td class='text-center'><?php echo ($pertanyaan1_1=="Tidak" ? "&#10004;" : "");?></td>
        </tr>
        <tr>
          <td>2.</td>
          <td>Apakah Anda sedang hamil ?
          </td>
          <td class='text-center'><?php echo ($pertanyaan2=="Ya" ? "&#10004;" : "");?></td>
          <td class='text-center'><?php echo ($pertanyaan2=="Tidak" ? "&#10004;" : "");?></td>
        </tr>
        <tr>
          <td>3.</td>
          <td>Apakah Anda mengidap penyakit autoimun seperti asma, lupus ?</td>
          <td class='text-center'><?php echo ($pertanyaan3=="Ya" ? "&#10004;" : "");?></td>
          <td class='text-center'><?php echo ($pertanyaan3=="Tidak" ? "&#10004;" : "");?></td>
        </tr>
        <tr>
          <td>4.</td>
          <td>Apakah Anda sedang mendapat pengobatan untuk gangguan pembekuan darah, kelainan darah, defisiensi imun dan penerima produk darah/transfusi ?</td>
          <td class='text-center'><?php echo ($pertanyaan4=="Ya" ? "&#10004;" : "");?></td>
          <td class='text-center'><?php echo ($pertanyaan4=="Tidak" ? "&#10004;" : "");?></td>
        </tr>
        <tr>
          <td>5.</td>
          <td>Apakah Anda sedang mendapat pengobatan immunosupressant seperti kortikosteroid dan kemoterapi ?</td>
          <td class='text-center'><?php echo ($pertanyaan5=="Ya" ? "&#10004;" : "");?></td>
          <td class='text-center'><?php echo ($pertanyaan5=="Tidak" ? "&#10004;" : "");?></td>
        </tr>
        <tr>
          <td>6.</td>
          <td>Apakah Anda memiliki penyakit jantung berat dalam keadaan sesak ?</td>
          <td class='text-center'><?php echo ($pertanyaan6=="Ya" ? "&#10004;" : "");?></td>
          <td class='text-center'><?php echo ($pertanyaan6=="Tidak" ? "&#10004;" : "");?></td>
        </tr>
        <tr>
          <td>7.</td>
          <td>Pertanyaan tambahan bagi sasaran lansia  (> 60 tahun):
              <ol style="padding-left:20px">
              <li>Apakah Anda mengalami kesulitan untuk naik 10 anak tangga?</li>
              <li>Apakah Anda sering merasa kelelahan?</li>
              <li>Apakah Anda memiliki paling sedikit 5 dari 11 penyakit (Hipertensi, diabetes, kanker,  penyakit   paru   kronis, serangan jantung, gagal jantung kongestif, nyeri dada,  asma,  nyeri sendi, stroke dan penyakit ginjal)?</li>
              <li>Apakah Anda mengalami kesulitan berjalan kira-kira 100 sd 200 meter?</li>
              <li>Apakah Anda mengalami penurunan berat badan yang bermakna dalam setahun terakhir ?</li>
            </ol>
          </td>
          <td class='text-center'>
            <br>
            <?php echo ($pertanyaan7_1=="Ya" ? "&#10004;" : "");?><br>
            <?php echo ($pertanyaan7_2=="Ya" ? "&#10004;" : "");?><br>
            <?php echo ($pertanyaan7_3=="Ya" ? "&#10004;" : "");?><br>
            <?php echo ($pertanyaan7_4=="Ya" ? "&#10004;" : "");?><br>
            <?php echo ($pertanyaan7_5=="Ya" ? "&#10004;" : "");?>
          </td>
          <td class='text-center'>
            <br>
            <?php echo ($pertanyaan7_1=="Tidak" ? "&#10004;" : "");?><br>
            <?php echo ($pertanyaan7_2=="Tidak" ? "&#10004;" : "");?><br>
            <?php echo ($pertanyaan7_3=="Tidak" ? "&#10004;" : "");?><br>
            <?php echo ($pertanyaan7_4=="Tidak" ? "&#10004;" : "");?><br>
            <?php echo ($pertanyaan7_5=="Tidak" ? "&#10004;" : "");?>
          </td>
        </tr>
        <tr>
          <td colspan="4">Anamnesa dan riwayat terapi jika ada :<br>
            S :<br><?php echo $q1->s;?><br>
            O :<br><?php echo $q1->o;?><br>
          </td>
        </tr>
      </table>
      <p class="text-center">PERSETUJUAN PELAYANAN (INFORMED CONSENT)</p>
      <p style="text-align:justify;font-size:12px">Pada tanggal <?php echo date("d-m-Y", strtotime($q1->tgl_layani))." Jam ".date("H:i", strtotime($q1->tgl_layani));?> WIB telah dilakukan penjelasan tentang tindakan medis yang akan dilaksanakan kepada calon penerima vaksin Covid-19. Calon penerima vaksin Covid-19 telah memahami mengenai vaksinasi Covid-19 serta Kejadian Ikutan Pasca Imunisasi (KIPI) dan pertanyaan yang ada telah terjawab. Calon penerima vaksin Covid-19 serta cukup waktu untuk berpikir sebelum menyatakan bersedia melaksanakan vaksin, diobservasi selama 30 menit dan kembali untuk vaksin sesuai jadwal yang telah ditetapkan.</p>
    <table class="table no-border laporan">
        <tr>
            <td align="center" width="50%">
              Petugas Informed Consent
            </td>
            <td align="center">
              Calon Penerima Vaksin
            </td>
        </tr>
        <tr>
          <td align="center">
              <span class="ttd_petugas"></span><br>
              <?php echo $p["perawat"][$q1->petugas_vaksin]; ?>
          </td>
            <td align="center">
                <span class="ttd_pasien"></span><br>
                <?php echo $q1->nama_pasien1; ?>
            </td>
        </tr>
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
