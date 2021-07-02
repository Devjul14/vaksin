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
            getttd();
            window.print();
        });
        function getttd(){
            $.each($(".perawat"), function(key, value){
                var id = $(this).attr("idperawat");
                if (id!=""){
                    var ttd = "<?php echo site_url('ttddokter/getttdperawat');?>/"+id;
                    $(this).qrcode({width: 100,height: 100, text:ttd});
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
                <h4 style="margin-top:0px; margin-bottom: 0px;">ASUHAN KEBIDANAN</h4>
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
            <td style="padding:5px;text-align: center">No.</td>
            <td style="padding:5px;text-align: center;width:20%">S</td>
            <td style="padding:5px;text-align: center;width:30%">O</td>
            <td style="padding:5px;text-align: center;width:15%">A</td>
            <td style="padding:5px;text-align: center;width:20%">P</td>
            <td style="padding:5px;text-align: center;width:15%">Nama & TTD</td>
        </tr>
        <?php
            $i = 1;
            foreach ($x->result() as $row){
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
                echo "<td style='padding:5px;vertical-align:top;text-align:center'><div class='perawat' idperawat='".$row->pemberi."'></div>".$row->nama_perawat."</td>";
                echo "</tr>";
            }
        ?>
    </table>
</section>