<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="<?php echo base_url();?>css/print.css"> 
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
    <script type="text/javascript" src="<?php echo base_url()?>js/library.js"></script>
</head>
<script>
    $(document).ready(function(){
            getttd1();
            getttd();
            // window.print();
        });
        function getttd(){
            var ttd = "<?php echo site_url('ttddokter/getttddokter/'.$t->dokter_igd);?>";
            $('.ttd_qrcode_dokter').qrcode({width: 100,height: 100, text:ttd});
        }

        function getttd1(){
            var ttd = "<?php echo site_url('ttddokter/getttdperawat/'.$t->petugas_igd);?>";
            $('.ttd_qrcode_perawat').qrcode({width: 100,height: 100, text:ttd});
        }
</script> 
<section class="margin">
    <table width="100%" cellspacing="0" cellpadding="0" border=0>
        <tr>
            <td align="right">
                RM 05. 1/RI/RSC REV 1
            </td>
        </tr>
    </table>
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
        $umur = $year_diff." tahun ".$month_diff." bulan ".$day_diff." hari ";
    ?>
    <table border="1" width="100%" cellspacing="0" cellpadding="1" style="border:1px solid #000000;">
        <tr>
            <td rowspan="5" align="center"><img src="<?php echo base_url("img/Logo.png")?>"><br><b>RS CIREMAI</b></td>
        </tr>
        <tr>
            <td rowspan="5" align="center">
                <h4 style="margin-top:0px; margin-bottom: 0px;">LEMBAR OBSERVASI HARIAN<br>PASIEN DEWASA</h4>
            </td>
        </tr>
        <tr>
            <td style="padding:5px">Nama</td>
            <td style="padding:5px"><?php echo $q->nama_pasien;?></td>
        </tr>
        <tr>
            <td style="padding:5px">No. RM </td>
            <td style="padding:5px"><?php echo $q->no_pasien;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Tanggal Lahir</td>
            <td style="padding:5px"><?php echo ($q->tgl_lahir!="" ? date("d-m-Y",strtotime($q->tgl_lahir)) : "");?></td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%" cellspacing="0" cellpadding="1">
        <tr>
            <td style="padding:5px;text-align: center" colspan="3">Tanggal</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                        echo "<td class='text-center'>".date("d-m-Y",strtotime($tgl))."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center" colspan="3">Waktu</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$key."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td width=80px rowspan="8"><div class="aside" style="height: 200px;"><h5 style="bottom:-25px;">NATIONAL EARLY <br>WARNING SCORE (NEWS)</h5></td></td>
            <td colspan="2" style="padding:5px;text-align: center">RR (x/mnt)</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->rr."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">SPO2</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->spo2."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Pemakaian O2</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->pemakaian_o2." ".$value->keterangan_pemakaian_o2."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Tensi (Nilai Systolic) (mmHg)</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->tensi."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Nadi (x/mnt)</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->nadi."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Tingkat Kesadaran</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->tingkat_kesadaran."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Total Score EWS</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->score_ews."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Gula Darah</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->gula_darah."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td width=80px rowspan="16"><div class="aside" style="height: 200px;"><h5>PENGAWASAN LAINNYA</h5></td></td>
            <td colspan="2" style="padding:5px;text-align: center">CVP</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->cvp."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Lingkar Perut / Lingkar Kepala</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->lingkar_perut."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Berat Badan</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->berat_badan."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Tinggi / Panjang Badan</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->tinggi_badan."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Luka Skala Norton</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->luka_skala_norton."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center" rowspan="4">INTAKE</td>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center">Oral</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->oral."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center">Intravena</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->intravena."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center">darah</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->darah."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan='2' style="padding:5px;text-align: center">Total</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>&nbsp;</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center" rowspan="6">OUTPUT</td>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center">Urine</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->urine."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center">Muntah</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->muntah."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center">Faeces</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->feaces."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center">Drain</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->drain."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td style="padding:5px;text-align: center">IWL</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->iwl."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td width=80px rowspan="16"><div class="aside" style="height: 200px;"><h5>PENGAWASAN OBSTETRI GINEKOLOGI</h5></td></td>
            <td colspan="2" style="padding:5px;text-align: center">Konjungtiva</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->konjungtiva."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Buah Dada</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->buah_dada."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Kontraksi</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->kontraksi."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Flatus</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->flatus."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Fundus Uteri</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->fundur_uteri."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Luka Pembedahan</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->luka_pembedahan."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Perineum</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->perineum."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Defekasi</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->defekasi."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">BAK</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->bak."</td>";
                } 
            ?>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;text-align: center">Diastasis Rechti</td>
            <?php
                foreach ($r as $tgl => $val) {
                    foreach ($val as $key => $value)
                    echo "<td class='text-center'>".$value->diastasis_retchi."</td>";
                } 
            ?>
        </tr>
    </table>
</section>
<style>
    .aside {
        display: block;
        position: relative;
        border:none;
    }
    .aside h5 {
        font-weight: bold;
        position: absolute;
        transform-origin: 0 0;
        transform: rotate(-90deg);
        bottom:-60px;
        left: 20px;
        width: 800px;
        line-height:15px;
    }
</style>
</html>