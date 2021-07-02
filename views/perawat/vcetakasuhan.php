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
            getttd_perawat();
            window.print();
        });
        function getttd(id1,id2,d){
            var ttd1 = "<?php echo site_url('ttddokter/getttdperawat');?>/"+id1;
            var ttd2 = "<?php echo site_url('ttddokter/getttdperawat');?>/"+id2;
            $('.ttd_qrcode_pemberi'+d).qrcode({width: 100,height: 100, text:ttd1});
            $('.ttd_qrcode_penerima'+d).qrcode({width: 100,height: 100, text:ttd2});
        }
        function getttd_perawat(){
            $.each($(".ttd_qrcode_perawat"), function(key, value){
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
                <h4 style="margin-top:0px; margin-bottom: 0px;">ASUHAN KEPERAWATAN</h4>
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
            <th style="padding:5px;text-align:center">Tgl</th>
            <th style="padding:5px;text-align:center">Jam</th>
            <th style="padding:5px;text-align:center;width:20%">Data</th>
            <th style="padding:5px;text-align:center;width:15%">Diagnosa</th>
            <th style="padding:5px;text-align:center;width:15%">Tujuan</th>
            <th style="padding:5px;text-align:center;width:15%">Rencana Keperawatan</th>
            <th style="padding:5px;text-align:center;width:10%">Implementasi</th>
            <th style="padding:5px;text-align:center;width:15%">Evaluasi Perkembangan</th>
            <th style="padding:5px;text-align:center;width:10%">TTD & Nama</th>
        </tr>
        <?php
            $n = 1;
            $tgl_string = $tanggal = $shift = $garis = "";
            foreach($i->result() as $row){
                $jam = date("H:i",strtotime($row->tanggal));
                $tgl = date("d-m-Y",strtotime($row->tanggal));
                if ($shift!=$row->shift){
                    $tgl_string = $tgl;
                    $garis = "";
                    $tanggal = "";
                } else {
                    if ($tgl!=$tanggal){
                        $tgl_string = $tgl;
                        $garis = "";
                    } else {
                        $tgl_string = "";
                        $garis = "border-top:1px solid #fff";
                    }
                }
                if ($row->shift!="igd"){
                    echo "<tr valign='top'>";
                    echo "<td style='padding:5px;".$garis."' width='90px'>".$tgl_string."</td>";
                    echo "<td style='padding:5px;".$garis."'>".$jam."</td>";
                    if ($shift!=$row->shift){
                        echo "<td style='padding:5px;".$garis."'>";
                        echo "S : <br>".$q[$row->id_ap]->s."<br>O : <br>".$q[$row->id_ap]->o."<br>";
                        echo "<ul>";
                        echo "<li>TTV";
                        echo "<div class='row'>";
                        echo ($q[$row->id_ap]->td=="" ? "" : "<div class='col-xs-6'>TD ka : ".$q[$row->id_ap]->td." mmHg</div>");
                        echo ($q[$row->id_ap]->td2=="" ? "" : "<div class='col-xs-6'>TD ki : ".$q[$row->id_ap]->td2." mmHg</div>");
                        echo ($q[$row->id_ap]->nadi=="" ? "" : "<div class='col-xs-6'>N : ".$q[$row->id_ap]->nadi." x/ mnt</div>");
                        echo ($q[$row->id_ap]->respirasi=="" ? "" : "<div class='col-xs-6'>R : ".$q[$row->id_ap]->respirasi." x/ mnt</div>");
                        echo ($q[$row->id_ap]->suhu=="" ? "" : "<div class='col-xs-6'>S : ".$q[$row->id_ap]->suhu." °C</div>");
                        echo ($q[$row->id_ap]->spo2=="" ? "" : "<div class='col-xs-6'>SPO2 : ".$q[$row->id_ap]->spo2." %</div>");
                        echo ($q[$row->id_ap]->bb=="" ? "" : "<div class='col-xs-6'>BB : ".$q[$row->id_ap]->bb." Kg</div>");
                        echo ($q[$row->id_ap]->tb=="" ? "" : "<div class='col-xs-6'>TB : ".$q[$row->id_ap]->tb." cm</div>");
                        echo "</div>";
                        echo "</li>";
                        echo "</ul>";
                        echo "</td>";
                        echo "<td style='padding:5px;".$garis."'>";
                        echo $q[$row->id_ap]->a;
                        echo "</td>";
                        echo "<td style='padding:5px;".$garis."'>";
                        echo $q[$row->id_ap]->tujuan;
                        echo "</td>";
                        echo "<td style='padding:5px;".$garis."'>";
                        echo $q[$row->id_ap]->p;
                        echo "</td>";
                    } else {
                        echo "<td style='padding:5px;".$garis."'></td>";
                        echo "<td style='padding:5px;".$garis."'></td>";
                        echo "<td style='padding:5px;".$garis."'></td>";
                        echo "<td style='padding:5px;".$garis."'></td>";
                    }
                    echo "<td style='padding:5px;".$garis."'>";
                    echo $row->implementasi."<br>";
                    echo "</td>";
                    echo "<td style='padding:5px;".$garis."'>";
                    if ($shift!=$row->shift){
                        $koma = "";
                        foreach ($e[$row->id_ap] as $key => $value) {
                            echo $koma."S : <br>".$value->s."<br>O : <br>".$value->o."<br>A : <br>".$value->a."<br>P : <br>".$value->p;
                            $koma = "<br>";
                        }
                        echo "</td>";
                    }
                    echo "<td style='text-align:center;padding:5px;".$garis."'>";
                    echo "<div class='ttd_qrcode_perawat' idperawat='".$row->perawat."'></div>";
                    echo $sp[$row->perawat];
                    echo "</td>";
                    echo "</tr>";
                    $n++;
                }
                $tanggal = $tgl;
                $shift = $row->shift;
            } 
        ?>
    </table>
</section>