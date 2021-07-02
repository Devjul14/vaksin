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
            window.print();
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
            <td rowspan="8" align="center"><img src="<?php echo base_url("img/Logo.png")?>"><br><b>RS CIREMAI</b></td>
        </tr>
        <tr>
            <td rowspan="8" align="center">
                <h4 style="margin-top:0px; margin-bottom: 0px;">ASUHAN KEPERAWATAN <br>GAWAT DARURAT/ KEBIDANAN PONEK</h4>
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
        <?php
            $ats = $t->triage;
            if($ats == "ATS 1 (Merah)"){
                $warna = '<span class="text-red text-bold">ATS 1 (Merah)</span>';
            }else
            if($ats == "ATS 2 (Orange)"){
                $warna = '<span class="text-orange text-bold">ATS 2 (Orange)</span>';
            }else
            if($ats == "ATS 3 (Kuning)"){
                $warna = '<span class="text-yellow text-bold">ATS 3 (Kuning)</span>';
            }else
            if($ats == "ATS 4 (Hijau)"){
                $warna = '<span class="text-green text-bold">ATS 4 (Hijau)</span>';
            }else
            if($ats == "D.O.A (Hitam)"){
                $warna = '<span class="text-black text-bold">D.O.A (Hitam)</span>';
            }
            else{
                $warna = '';
            }
        ?>
        <tr>
            <td style="padding:5px">Tanggal Lahir</td>
            <td style="padding:5px"><?php echo ($q->tgl_lahir!="" ? date("d-m-Y",strtotime($q->tgl_lahir)) : "");?></td>
        </tr>
        <tr>
            <td style="padding:5px">Kategori Triage</td>
            <td style="padding:5px"><?php echo $warna;?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td style="padding:5px">Tanggal</td>
            <td style="padding:5px"><?php echo date("d-m-Y");?></td>
        </tr>
        <tr>
            <td style="padding:5px">Jam</td>
            <td style="padding:5px"><?php echo $t->waktu_keputusan;?></td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%" cellspacing="0" cellpadding="1">
        <tr>
            <td style="padding:5px;text-align: center">No.</td>
            <td style="padding:5px;text-align: center;width:24%">S</td>
            <td style="padding:5px;text-align: center;width:35%">O</td>
            <td style="padding:5px;text-align: center;width:15%">A</td>
            <td style="padding:5px;text-align: center;width:24%">P</td>
        </tr>
        <?php
            $i = 1;
            foreach ($r->result() as $row){
                if ($row->shift=="igd"){
                    echo "<tr>";
                    echo "<td style='padding:5px;vertical-align:top'>".($i++)."</td>";
                    echo "<td style='padding:5px;vertical-align:top'>".$row->s."</td>";
                    echo "<td style='padding:5px;vertical-align:top'>".$row->o."<br>";
                    echo "<ul>";
                    echo "<li>TTV";
                    echo "<div class='row'>";
                    echo ($row->td=="" ? "" : "<div class='col-xs-6'>TD 1 : ".$row->td." mmHg</div>");
                    echo ($row->td2=="" ? "" : "<div class='col-xs-6'>TD 2 : ".$row->td2." mmHg</div>");
                    echo ($row->nadi=="" ? "" : "<div class='col-xs-6'>N : ".$row->nadi." x/ mnt</div>");
                    echo ($row->respirasi=="" ? "" : "<div class='col-xs-6'>R : ".$row->respirasi." x/ mnt</div>");
                    echo ($row->suhu=="" ? "" : "<div class='col-xs-6'>S : ".$row->suhu." °C</div>");
                    echo ($row->spo2=="" ? "" : "<div class='col-xs-6'>SPO2 : ".$row->spo2." %</div>");
                    echo ($row->bb=="" ? "" : "<div class='col-xs-6'>BB : ".$row->bb." Kg</div>");
                    echo ($row->tb=="" ? "" : "<div class='col-xs-6'>TB : ".$row->tb." cm</div>");
                    echo "</div>";
                    echo "</li>";
                    echo "</ul>";
                    echo "</td>";
                    echo "<td style='padding:5px;vertical-align:top'>".$row->a."</td>";
                    echo "<td style='padding:5px;vertical-align:top'><br>".$row->p."</td>";
                    echo "</tr>";
                }
            }
        ?>
    </table>
    <table border="1" width="100%" cellspacing="0" cellpadding="1">
        <tr> 
            <td align="right" style='padding:5px;'>Perawat/ Bidan 
                <br>
                <br>
                <div class="ttd_qrcode_perawat"> </div>
                <br>
                <?php echo $t->nama_perawat?>
            </td>
        </tr>
    </table>
</section>