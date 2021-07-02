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
        var kota = "<?php echo $q->row()->kota;?>";
        var prov = "<?php echo $q->row()->prov;?>";
        var d_kota = "<?php echo $q->row()->id_kota;?>";
        var d_prov = "<?php echo $q->row()->id_provinsi;?>";
        namakota(kota,prov);
        domisili(d_kota);
        getttd();
    });
    function getttd(){
        var ttd = "<?php echo site_url('ttddokter/getttdperawat/'.$q->row()->petugas_igd);?>";
        $('.ttd_qrcode_perawat').qrcode({width: 100,height: 100, text:ttd});
    }
    function namakota(kota="",prov=""){
        $.ajax({
            type  : "POST",
            url   : "<?php echo site_url('perawat/getpropinsi');?>/"+prov,
            success : function(kota){
                if (kota!=""){
                    $(".kota").html(kota);
                }
            },
            error: function(result){
                console.log(result);
            }
        });
        $.ajax({
            type  : "POST",
            url   : "<?php echo site_url('perawat/getdomisili');?>/"+kota,
            success : function(kota){
                if (kota!=""){
                    $(".kota").append(" "+kota);
                }
            },
            error: function(result){
                console.log(result);
            }
        });
    }
    function domisili(kota=""){
        $.ajax({
            type  : "POST",
            url   : "<?php echo site_url('perawat/getdomisili');?>/"+kota,
            success : function(kota){
                if (kota!=""){
                    $(".domisili").html(kota);
                }
            },
            error: function(result){
                console.log(result);
            }
        });
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
        $umur = $year_diff." tahun ".$month_diff." bulan ".$day_diff." hari ";

        // list($year,$month,$day) = explode("-",$p->tgl_masuk);
        // $year_diff  = date("Y",strtotime($p->tgl_keluar)) - $year;
        // $month_diff = date("m",strtotime($p->tgl_keluar)) - $month;
        // $day_diff   = date("d",strtotime($p->tgl_keluar)) - $day;
        // if ($month_diff < 0) {
        //     $year_diff--;
        //     $month_diff *= (-1);
        // }
        // elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
        // if ($day_diff < 0) {
        //     $day_diff *= (-1);
        // }
        $tgl1 = new DateTime($p->tgl_masuk);
        $tgl2 = new DateTime($p->tgl_keluar);
        $d = $tgl2->diff($tgl1)->d + 1;
        $ho = $d." hari";
        ?>
    <table border="1" width="100%" cellspacing="0" cellpadding="1" style="border:1px solid #000000;">
        <?php if($jenis=="ranap") :?>
        <tr>
            <td rowspan="7" align="center">
                <img src="<?php echo base_url("img/Logo.png")?>"><br><b>RS CIREMAI</b>
            </td>
        </tr>
        <tr>
            <td rowspan="6" align="center">
                <h4 style="margin-top:0px; margin-bottom: 0px;">DETEKSI DINI COVID-19</h4>
            </td>
        </tr>
        <tr>
            <td style="padding:5px">No. RM </td>
            <td style="padding:5px"><?php echo $q1->no_pasien;?></td>
            <td style="padding:5px">Ruangan</td>
            <td style="padding:5px"><?php echo $p->nama_ruangan;?></td>
        </tr>
        <tr>
            <td style="padding:5px">No. Reg </td>
            <td style="padding:5px"><?php echo $no_reg;?></td>
            <td style="padding:5px">Kelas</td>
            <td style="padding:5px"><?php echo $p->nama_kelas;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Nama</td>
            <td style="padding:5px"><?php echo $q1->nama_pasien;?></td>
            <td style="padding:5px">Kamar/ Bed</td>
            <td style="padding:5px"><?php echo $p->kode_kamar."/".$p->no_bed;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Tanggal Lahir</td>
            <td style="padding:5px"><?php echo ($q1->tgl_lahir!="" ? date("d-m-Y",strtotime($q1->tgl_lahir)) : "")."/ ".$umur;?></td>
            <td style="padding:5px">Suhu</td>
            <td style="padding:5px"><?php echo $q->row()->suhu;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Tanggal Masuk</td>
            <td style="padding:5px"><?php echo date("d-m-Y", strtotime($p->tgl_masuk));?></td>
            <td style="padding:5px">Hari Perawatan</td>
            <td style="padding:5px"><?php echo $ho;?></td>
        </tr>
        <?php else :?>
        <tr>
            <td rowspan="7" align="center">
                <img src="<?php echo base_url("img/Logo.png")?>"><br><b>RS CIREMAI</b>
            </td>
        </tr>
        <tr>
            <td rowspan="6" align="center">
                <h4 style="margin-top:0px; margin-bottom: 0px;">DETEKSI DINI COVID-19</h4>
            </td>
        </tr>
        <tr>
            <td style="padding:5px">No. RM </td>
            <td style="padding:5px"><?php echo $q1->no_pasien;?></td>
            <td style="padding:5px">Ruangan</td>
            <td style="padding:5px"><?php echo $p->nama_ruangan;?></td>
        </tr>
        <tr>
            <td style="padding:5px">No. Reg </td>
            <td style="padding:5px"><?php echo $no_reg;?></td>
            <td style="padding:5px">Kelas</td>
            <td style="padding:5px"><?php echo $p->nama_kelas;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Nama</td>
            <td style="padding:5px"><?php echo $q1->nama_pasien;?></td>
            <td style="padding:5px">Kamar/ Bed</td>
            <td style="padding:5px"><?php echo $p->kode_kamar."/".$p->no_bed;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Tanggal Lahir</td>
            <td style="padding:5px"><?php echo ($q1->tgl_lahir!="" ? date("d-m-Y",strtotime($q1->tgl_lahir)) : "")."/ ".$umur;?></td>
            <td style="padding:5px">Suhu</td>
            <td style="padding:5px"><?php echo $q->row()->suhu;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Tanggal Masuk</td>
            <td style="padding:5px"><?php echo date("d-m-Y", strtotime($p->tanggal));?></td>
            <td style="padding:5px">Hari Perawatan, Tanggal HO</td>
            <td style="padding:5px"><?php echo $ho.", ".date("d-m-Y");?></td>
        </tr>
        <?php endif ?>
    </table>
    <br>
    <table border="1" width="100%" cellspacing="0" cellpadding="1" style="border:1px solid #000000;">
        <tr class="bg-orange">
            <th style="padding:5px" width="20px">No.</th>
            <th style="padding:5px" class="text-center">GEJALA</th>
            <th style="padding:5px" class="text-center">YA/TDK</th>
        </tr>
        <?php
            $n = 1;
            $jml = 1;
            foreach ($q->result() as $row) {
                if ($jml<=1){
                    $gejala = explode(",", $row->gejala);
                    $tglgejala = explode(",", $row->tglgejala);
                    foreach ($gejala as $key => $value) {
                        echo "<tr>";
                        echo "<td style='padding:5px'>".($n++)."</td>";
                        echo "<td style='padding:5px'>".(strtoupper($value)=="SAKITTENGGOROKAN" ? "SAKIT TENGGOROKAN" : strtoupper($value))."</td>";
                        echo "<td style='padding:5px'>".($tglgejala[$key]=="-" ? "TIDAK" : "YA, ".$tglgejala[$key])."</td>";
                        echo "</tr>";
                    }
                    echo "<tr><td style='padding:5px'>".($n++)."</td><td style='padding:5px'>";
                    echo "<div class='form-horizontal'>";
                    echo "<div class='form-group'>";
                    echo "    <label class='col-md-2 control-label'>TD Kanan</label>";
                    echo "    <div class='col-md-2'>";
                    echo $row->td." mmHg";
                    echo "    </div>";
                    echo "    <label class='col-md-1 control-label'>TD Kiri</label>";
                    echo "    <div class='col-md-2'>";
                    echo $row->td2." mmHg";
                    echo "    </div>";
                    echo "    <label class='col-md-2 control-label'>Nadi</label>";
                    echo "    <div class='col-md-3'>";
                    echo $row->nadi." x/menit";
                    echo "    </div>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "    <label class='col-md-2 control-label'>Respirasi</label>";
                    echo "    <div class='col-md-2'>";
                    echo $row->respirasi." x/menit";
                    echo "    </div>";
                    echo "    <label class='col-md-1 control-label'>Suhu</label>";
                    echo "    <div class='col-md-2'>";
                    echo $row->suhu." °C";
                    echo "    </div>";
                    echo "    <label class='col-md-2 control-label'>SpO2</label>";
                    echo "    <div class='col-md-3'>";
                    echo $row->spo2." %";
                    echo "    </div>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "    <label class='col-md-2 control-label'>BB</label>";
                    echo "    <div class='col-md-2'>";
                    echo $row->bb;
                    echo "    </div>";
                    echo "    <label class='col-md-1 control-label'>TB</label>";
                    echo "    <div class='col-md-2'>";
                    echo $row->tb;
                    echo "    </div>";
                    echo "</div> ";
                    echo "</div> ";
                    echo "</td><td style='padding:5px'>";
                    if ($row->respirasi>30 || $row->spo2<90){
                        echo "YA";
                    } else {
                        echo "TIDAK";
                    }
                    echo "</td></tr>";
                    echo '<tr class="bg-orange">
                        <th style="padding:5px" width="20px">No.</th>
                        <th style="padding:5px" class="text-center">RESIKO</th>
                        <th style="padding:5px" class="text-center">YA/TDK</th>
                    </tr>';
                    $resiko = explode(",", $row->resiko);
                    $tglresiko = explode(",", $row->tglresiko);
                    $n = 1;
                    foreach ($resiko as $key => $value) {
                        if ($value!="sd"){
                            if ($value=="resiko1"){
                                echo "<tr>";
                                echo "<td style='padding:5px'>".($n++)."</td>";
                                echo "<td style='padding:5px'>Riwayat perjalanan/ tinggal diluar negeri ATAU kota-kota terjangkit di Indonesia dalam 14 Hari sebelum timbul gejala. <span class='kota text-bold'></span></td>";
                                echo "<td style='padding:5px'>".($tglresiko[0]=="" ? "TIDAK" : "YA, ".$tglresiko[0]." sd ".$tglresiko[1])."</td>";
                                echo "</tr>";
                            } else {
                                echo "<tr>";
                                echo "<td style='padding:5px'>".($n++)."</td>";
                                echo "<td style='padding:5px'>Riwayat kontak erat dengan kasus konfirmasi COVID-19</td>";
                                echo "<td style='padding:5px'>".($tglresiko[2]=="" ? "TIDAK" : "YA, ".$tglresiko[2])."</td>";
                                echo "</tr>";
                            }
                        }
                    }
                    echo "<tr>";
                    echo "<td style='padding:5px'>".($n++)."</td>";
                    echo "<td style='padding:5px'>Berada di Kota Terrjangkit di Indonesia</td>";
                    echo "<td style='padding:5px'><span class='domisili'></span></td>";
                    echo "</tr>";
                    echo '<tr>
                        <th style="padding:5px" colspan="3">STATUS '.($row->status=="" ? "NEGATIF" : "<span style='color:red'>".$row->status."</span>").'</th>
                    </tr>';
                }
                $jml++;
            }
        ?>
    </table>
    <table width="100%" cellspacing="0" cellpadding="1" style="border:0px solid #000000;">
        <tr>
            <td class="text-right"><br>Cirebon, <?php echo date("d-m-Y",strtotime($no_reg));?><br>Perawat<br>
                <span class="ttd_qrcode_perawat"></span><br>
                <?php echo $q->row()->nama_perawat;?></td>
        </tr>
    </table>
</section>
