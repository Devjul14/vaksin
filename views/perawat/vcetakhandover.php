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
            var pemberi1 = "<?php if (isset($r['pagi'])) echo $r['pagi']->pemberi;?>";
            var penerima1 = "<?php if (isset($r['pagi'])) echo $r['pagi']->penerima;?>";
            var pemberi2 = "<?php if (isset($r['sore'])) echo $r['sore']->pemberi;?>";
            var penerima2 = "<?php if (isset($r['sore'])) echo $r['sore']->penerima;?>";
            var pemberi3 = "<?php if (isset($r['malam'])) echo $r['malam']->pemberi;?>";
            var penerima3 = "<?php if (isset($r['malam'])) echo $r['malam']->penerima;?>";
            getttd(pemberi1,penerima1,1);
            getttd(pemberi2,penerima2,2);
            getttd(pemberi3,penerima3,3);
            window.print();
        });
        function getttd(){
            var ttd = "<?php echo site_url('ttddokter/getttddokter/'.$t->dokter_igd);?>";
            $('.ttd_qrcode_dokter').qrcode({width: 100,height: 100, text:ttd});
        }
        function getttd(id1,id2,d){
            var ttd1 = "<?php echo site_url('ttddokter/getttdperawat');?>/"+id1;
            var ttd2 = "<?php echo site_url('ttddokter/getttdperawat');?>/"+id2;
            $('.ttd_qrcode_pemberi'+d).qrcode({width: 100,height: 100, text:ttd1});
            $('.ttd_qrcode_penerima'+d).qrcode({width: 100,height: 100, text:ttd2});
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

        list($year,$month,$day) = explode("-",$p->tgl_masuk);
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
        $ho = ($day_diff+1)." hari ";
    ?>
    <table border="1" width="100%" cellspacing="0" cellpadding="1" style="border:1px solid #000000;">
        <tr>
            <td rowspan="7" align="center">
                <img src="<?php echo base_url("img/Logo.png")?>"><br><b>RS CIREMAI</b>
            </td>
        </tr>
        <tr>
            <td rowspan="6" align="center">
                <h4 style="margin-top:0px; margin-bottom: 0px;">HAND OVER <br>(SERAH TERIMA ANTAR SHIF)</h4>
            </td>
        </tr>
        <tr>
            <td style="padding:5px">No. RM </td>
            <td style="padding:5px"><?php echo $q->no_pasien;?></td>
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
            <td style="padding:5px"><?php echo $q->nama_pasien;?></td>
            <td style="padding:5px">Kamar/ Bed</td>
            <td style="padding:5px"><?php echo $p->kode_kamar."/".$p->no_bed;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Tanggal Lahir</td>
            <td style="padding:5px"><?php echo ($q->tgl_lahir!="" ? date("d-m-Y",strtotime($q->tgl_lahir)) : "")."/ ".$umur;?></td>
            <td style="padding:5px">Hari Perawatan</td>
            <td style="padding:5px"><?php echo $ho;?></td>
        </tr>
        <tr>
            <td style="padding:5px">Tanggal Masuk</td>
            <td style="padding:5px"><?php echo date("d-m-Y", strtotime($p->tgl_masuk));?></td>
            <td style="padding:5px">Tanggal HO</td>
            <td style="padding:5px"><?php echo date("d-m-Y");?></td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%" cellspacing="0" cellpadding="1">
        <tr>
            <th width="30%" style="padding:5px;text-align:center">PAGI (Jam 14.00)</th>
            <th width="30%" style="padding:5px;text-align:center">SORE (Jam 21.00)</th>
            <th width="30%" style='padding:5px;text-align:center'>MALAM (Jam 07.00)</th>
        </tr>
        <?php
                echo "<tr style='vertical-align:top'>";
                echo "<td style='padding:5px'>";
                echo "Situasional :<br>";
                if (isset($r["pagi"])){
                    $data = $r["pagi"];
                    echo $data->situasional."<br>";
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                echo "Situasional :<br>";
                if (isset($r["sore"])){
                    $data = $r["sore"];
                    echo $data->situasional."<br>";
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                echo "Situasional :<br>";
                if (isset($r["malam"])){
                    $data = $r["malam"];
                    echo $data->situasional."<br>";
                }
                echo "</td>";
                echo "</tr>";
                echo "<tr style='vertical-align:top'>";
                echo "<td style='padding:5px'>";
                echo "Background :<br>";
                if (isset($r["pagi"])){
                    $data = $r["pagi"];
                    echo "Dx/Medis : ".$data->medis."<br>";
                    echo "DPJP : ".$data->nama_dpjp; 
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                echo "Background :<br>";
                if (isset($r["sore"])){
                    $data = $r["sore"];
                    echo "Dx/Medis : ".$data->medis."<br>";
                    echo "DPJP : ".$data->nama_dpjp;
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                echo "Background :<br>";
                if (isset($r["malam"])){
                    $data = $r["malam"];
                    echo "Dx/Medis : ".$data->medis."<br>";
                    echo "DPJP : ".$data->nama_dpjp;
                }
                echo "</td>";
                echo "</tr>";
                echo "<tr style='vertical-align:top'>";
                echo "<td style='padding:5px'>";
                echo "Assesmen :<br>";
                if (isset($r["pagi"])){
                    $data = $r["pagi"];
                    echo "S : <br>".$data->s."<br>";
                    echo "O : <br>".$data->o;
                    echo "<div class='row'>";
                    echo "<div class='col-md-6'>T : ".($data->td=="" ? $data->td2 : $data->td)."</div>";
                    echo "<div class='col-md-6'>R : ".$data->respirasi."</div>";
                    echo "<div class='col-md-6'>N : ".$data->nadi."</div>";
                    echo "<div class='col-md-6'>S : ".$data->suhu."</div>"; 
                    echo "<div class='col-md-12'>O2 Saturasi : ".$data->spo2."</div>"; 
                    echo "</div><br>";
                    echo "A : <br>".$data->a."<br>";
                    echo "P : <br>".$data->p;
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                echo "Assesmen :<br>";
                if (isset($r["sore"])){
                    $data = $r["sore"];
                    echo "S : <br>".$data->s."<br>";
                    echo "O : <br>".$data->o;
                    echo "<div class='row'>";
                    echo "<div class='col-md-6'>T : ".($data->td=="" ? $data->td2 : $data->td)."</div>";
                    echo "<div class='col-md-6'>R : ".$data->respirasi."</div>";
                    echo "<div class='col-md-6'>N : ".$data->nadi."</div>";
                    echo "<div class='col-md-6'>S : ".$data->suhu."</div>";
                    echo "<div class='col-md-12'>O2 Saturasi : ".$data->spo2."</div>"; 
                    echo "</div><br>";
                    echo "A : <br>".$data->a."<br>";
                    echo "P : <br>".$data->p;
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                echo "Assesmen :<br>";
                if (isset($r["malam"])){
                    $data = $r["malam"];
                    echo "S : <br>".$data->s."<br>";
                    echo "O : <br>".$data->o;
                    echo "<div class='row'>";
                    echo "<div class='col-md-6'>T : ".($data->td=="" ? $data->td2 : $data->td)."</div>";
                    echo "<div class='col-md-6'>R : ".$data->respirasi."</div>";
                    echo "<div class='col-md-6'>N : ".$data->nadi."</div>";
                    echo "<div class='col-md-6'>S : ".$data->suhu."</div>"; 
                    echo "<div class='col-md-12'>O2 Saturasi : ".$data->spo2."</div>"; 
                    echo "</div><br>";
                    echo "A : <br>".$data->a."<br>";
                    echo "P : <br>".$data->p;
                }
                echo "</td>";
                echo "</tr>";
                echo "<tr style='vertical-align:top'>";
                echo "<td style='padding:5px'>";
                echo "Rekomendasi :<br>";
                if (isset($r["pagi"])){
                    $data = $r["pagi"];
                    echo $data->rekomendasi;
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                echo "Rekomendasi :<br>";
                if (isset($r["sore"])){
                    $data = $r["sore"];
                    echo $data->rekomendasi;
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                echo "Rekomendasi :<br>";
                if (isset($r["malam"])){
                    $data = $r["malam"];
                    echo $data->rekomendasi;
                }
                echo "</td>";
                echo "</tr>";
                echo "<tr style='vertical-align:top'>";
                echo "<td style='padding:5px'>";
                if (isset($r["pagi"])){
                    $data = $r["pagi"];
                    echo "<div class='row'>";
                    echo "<div class='col-xs-6 text-center'>Pemberi Operan";
                    echo "<div class='ttd_qrcode_pemberi1'></div>".$sp[$data->pemberi];
                    echo "</div>";
                    echo "<div class='col-xs-6 text-center'>Penerima Operan";
                    echo "<div class='ttd_qrcode_penerima1'></div>".$sp[$data->penerima];
                    echo "</div>";
                    echo "</div>";
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                if (isset($r["sore"])){
                    $data = $r["sore"];
                    echo "<div class='row'>";
                    echo "<div class='col-xs-6 text-center'>Pemberi Operan";
                    echo "<div class='ttd_qrcode_pemberi2'></div>".$sp[$data->pemberi];
                    echo "</div>";
                    echo "<div class='col-xs-6 text-center'>Penerima Operan";
                    echo "<div class='ttd_qrcode_penerima2'></div>".$sp[$data->penerima];
                    echo "</div>";
                    echo "</div>";
                }
                echo "</td>";
                echo "<td style='padding:5px'>";
                if (isset($r["malam"])){
                    $data = $r["malam"];
                    echo "<div class='row'>";
                    echo "<div class='col-xs-6 text-center'>Pemberi Operan";
                    echo "<div class='ttd_qrcode_pemberi3'></div>".$sp[$data->pemberi];
                    echo "</div>";
                    echo "<div class='col-xs-6 text-center'>Penerima Operan";
                    echo "<div class='ttd_qrcode_penerima3'></div>".$sp[$data->penerima];
                }
                echo "</td>";
                echo "</tr>";
        ?>
    </table>
</section>