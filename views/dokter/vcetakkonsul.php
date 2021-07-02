<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.css">
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
<script type="text/javascript">
	$(document).ready(function(){
	    getttd_dari();
	    getttd_data();
	    window.print();
	});
	function getttd_dari(){
	    var ttd = "<?php echo site_url('ttddokter/getttddokterlab/'.$d["dari"]->id_dokter);?>";
	    $('.ttd_dari_qrcode').qrcode({width: 100,height: 100, text:ttd});
	}
	function getttd_data(){
	    var ttd = "<?php echo site_url('ttddokter/getttddokterlab/'.$d["data"]->id_dokter);?>";
	    $('.ttd_data_qrcode').qrcode({width: 100,height: 100, text:ttd});
	}
</script>
<?php
	list($year,$month,$day) = explode("-",$d["data"]->tgl_lahir);
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
<section class="margin">
<table class="table" width="100%" cellspacing="0" cellpadding="1">
	<tr>
		<td>
			DETASEMEN KESEHATAN WILAYAH 03.04.03<BR>
			RUMAH SAKIT TINGKAT III 03.06.01 CIREMAI
		</td>
	</tr>
</table>
<table border="1" width="100%" cellspacing="0" cellpadding="1">
    <tr>
    	<td rowspan="3" style="padding:5px"><b>LEMBAR KONSULTASI</b></td>
    	<td style="padding:5px">Nama</td>
    	<td style="padding:5px">: <?php echo $d["data"]->nama_pasien;?></td>
    	<td style="padding:5px">No. RM <?php echo $d["data"]->no_rm;?></td>
    </tr>
    <tr>
    	<td style="padding:5px">Umur</td>
    	<td style="padding:5px">: <?php echo $umur;?></td>
    	<td style="padding:5px">&nbsp;</td>
    </tr>
    <tr>
    	<td style="padding:5px">Dokter</td>
    	<td style="padding:5px">: <?php echo $d["dari"]->nama_dokter;?></td>
    	<td style="padding:5px">No. Reg <?php echo $d["data"]->no_reg;?></td>
    </tr>
</table>
<table width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;border-top:none;border-bottom:none">
    <tr valign="top">
    	<td style="padding:5px">
    		Kepada Yth TS :<br>
    		<?php echo $d["data"]->nama_dokter;?><br>
    		Di Tempat
    	</td>
    	<td style="padding:5px" width="30%">
    		Tgl. Konsul :  <?php echo date("d-m-Y",strtotime($d["dari"]->tanggal));?><br>
    		Jam Konsul :  <?php echo date("H:i:s",strtotime($d["dari"]->jam));?>
    	</td>
</table>
<table width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;border-top:none;border-bottom:none">
    <tr>
        <td style="padding:5px" align="left"><?php echo ($d["dari"]->td=="" ? "" : "TD 1 : ".$d["dari"]->td." mmHg"); ?></td>
        <td style="padding:5px" align="left"><?php echo ($d["dari"]->td2=="" ? "" : "TD 2 : ".$d["dari"]->td2." mmHg"); ?></td>
        <td style="padding:5px" align="left"><?php echo ($d["dari"]->nadi=="" ? "" : "Nadi : ".$d["dari"]->nadi." x/ mnt"); ?></td>
        <td style="padding:5px" align="left"><?php echo ($d["dari"]->respirasi=="" ? "" : "Respirasi : ".$d["dari"]->respirasi." x/ mnt");?></td>
        <td style="padding:5px" align="left"><?php echo ($d["dari"]->suhu=="" ? "" : "Suhu : ".$d["dari"]->suhu." °C");?></td>
        <td style="padding:5px" align="left"><?php echo ($q->spo2=="" ? "" : "SpO2 : ".$d["dari"]->spo2." %");?></td>
    </tr> 
    <tr>
        <td style="padding:5px" align="left"><?php echo ($d["dari"]->bb=="" ? "" : "BB : ".$d["dari"]->bb." kg");?></td>
        <td style="padding:5px" align="left"><?php echo ($d["dari"]->tb=="" ? "" : "TB : ".$d["dari"]->tb." cm");?></td>
    	<td colspan="4">&nbsp;</td>
    </tr>
</table>
<?php 
	$pem = explode(",",$d["dari"]->pemeriksaan_fisik);
	$kelainan = explode("|",$d["dari"]->kelainan);
    $ada = 0;
    for ($i=0;$i<=10;$i++){
        if (!$pem[$i]){
            $ada = 1;
        }
    }
    if ($ada==1) :
?>
<table width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;border-top:none;border-bottom:none">
    <tr>
        <th style="padding:5px">Pemeriksaan</th>
        <th style="padding:5px">Kelainan</th>
    </tr>
    <?php if ($pem[0]!="1") : ?>
    <tr>
        <td style="padding:5px" width=200px>Kepala</td>
        <td style="padding:5px"><?php echo (isset($kelainan[0]) ? ($pem[0] == "1" ? "" : $kelainan[0]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[1]!="1") : ?>
    <tr>
        <td style="padding:5px">Mata</td>
        <td style="padding:5px"><?php echo (isset($kelainan[1]) ? ($pem[1] == "1" ? "" : $kelainan[1]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[2]!="1") : ?>
    <tr>
        <td style="padding:5px">THT</td>
        <td style="padding:5px"><?php echo (isset($kelainan[2]) ? ($pem[2] == "1" ? "" : $kelainan[2]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[3]!="1") : ?>
    <tr>
        <td style="padding:5px">Gigi Mulut</td>
        <td style="padding:5px"><?php echo (isset($kelainan[3]) ? ($pem[3] == "1" ? "" : $kelainan[3]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[4]!="1") : ?>
    <tr>
        <td style="padding:5px">Leher</td>
        <td style="padding:5px"><?php echo (isset($kelainan[4]) ? ($pem[4] == "1" ? "" : $kelainan[4]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[5]!="1") : ?>
    <tr>
        <td style="padding:5px">Thoraks</td>
        <td style="padding:5px"><?php echo (isset($kelainan[5]) ? ($pem[5] == "1" ? "" : $kelainan[5]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[6]!="1") : ?>
    <tr>
        <td style="padding:5px">Abdomen</td>
        <td style="padding:5px"><?php echo (isset($kelainan[6]) ? ($pem[6] == "1" ? "" : $kelainan[6]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[7]!="1") : ?>
    <tr>
        <td style="padding:5px">Ekstremitas Atas</td>
        <td style="padding:5px"><?php echo (isset($kelainan[7]) ? ($pem[7] == "1" ? "" : $kelainan[7]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[8]!="1") : ?>
    <tr>
        <td style="padding:5px">Ekstremitas Bawah</td>
        <td style="padding:5px"><?php echo (isset($kelainan[8]) ? ($pem[8] == "1" ? "" : $kelainan[8]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[9]!="1") : ?>
    <tr>
        <td style="padding:5px">Genitalia</td>
        <td style="padding:5px"><?php echo (isset($kelainan[9]) ? ($pem[9] == "1" ? "" : $kelainan[9]) : ''); ?></td>
    </tr>
    <?php endif ?>
    <?php if ($pem[10]!="1") : ?>
    <tr>
        <td style="padding:5px">Anus</td>
        <td style="padding:5px"><?php echo (isset($kelainan[10]) ? ($pem[10] == "1" ? "" : $kelainan[10]) : ''); ?></td>
    </tr>
    <?php endif ?>
</table>
<?php endif ?>
<table width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;border-top:none;">
    <tr>
        <td style="padding:5px">&nbsp;</td>
        <td width="30%"><br><br><br><br>Salam Sejawat<br><br><div class="ttd_dari_qrcode"> </div><?php echo $d["dari"]->nama_dokter;?></td>
    </tr>
</table>
<table border="1" width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;border-top:none;">
    <tr>
    	<td style="padding:5px;text-align:center"><b>JAWABAN KONSUL</b></td>
    </tr>
</table>
<table width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;border-top:none;border-bottom:none">
    <tr valign="top">
    	<td style="padding:5px">
    		Kepada Yth TS :<br>
    		<?php echo $d["dari"]->nama_dokter;?><br>
    		Di Tempat
    	</td>
    	<td style="padding:5px" width="30%">
    		Tgl. Jawab Konsul :  <?php echo date("d-m-Y",strtotime($d["data"]->tgl_jawab));?><br>
    		Jam Jawab Konsul :  <?php echo date("H:i:s",strtotime($d["data"]->jam_jawab));?>
    	</td>
</table>
<table width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;border-top:none;;border-bottom:none;">
    <tr>
        <td style="padding:5px">A : </strong><?php echo $d["data"]->a; ?></td>
    </tr>
    <tr>
        <td style="padding:5px">P : </strong><?php echo $d["data"]->p ?></td>
    </tr>
    <?php if ($d["data"]->tindakan_radiologi=="" && $d["data"]->tindakan_lab=="" && $d["data"]->tindakan_penunjang=="") : ?>
    <tr>
    	<td style="padding:5px"><strong>Pemeriksaan Penunjang</strong></td>
    </tr>
    <tr>
    	<td style="padding:5px">
			<table width="100%" cellspacing="0" cellpadding="1">
			    <tr>
			        <td class="text-left" style="padding:5px"><b>Radiologi</b></td>
			        <td class="text-left" style="padding:5px"><b>Lab</b></td>
			        <td class="text-left" style="padding:5px"><b>Lain</b></td>
			    </tr>
			    <?php
			        $n = 1;
			        $rad = explode(",", $d["data"]->tindakan_radiologi);
			        echo "<tr id='data'>";
			        echo "<td valign='top' style='padding:5px'><ol style='padding-left:30px'>";
			        if (is_array($rad)){
			            foreach ($rad as $key => $value) {
			            	if ($value!="")
			                echo "<li>".$value."</li>";
			            }
			        } else {
			        	if ($d["data"]->tindakan_radiologi!="")
			        	echo "<li>".$d["data"]->tindakan_radiologi."</li>";
			        }
			        $lab = explode(",", $d["data"]->tindakan_lab);
			        echo "</ol></td>";
			        echo "<td valign='top' style='padding:5px'><ol style='padding-left:30px'>";
			        if (is_array($lab)){
			            foreach ($lab as $key => $value) {
			            	if ($value!="")
			                echo "<li>".$value."</li>";
			            }
			        } else {
			        	if ($d["data"]->tindakan_lab!="")
			        	echo "<li>".$d["data"]->tindakan_lab."</li>";
			        }      
			        echo "</ol></td>";
			        $penunjang = explode(",", $d["data"]->tindakan_penunjang);
			        echo "<td valign='top' style='padding:5px'><ol style='padding-left:30px'>";
			        if (is_array($penunjang)){
			            foreach ($penunjang as $key => $value) {
			            	if ($value!="")
			                echo "<li>".$value."</li>";
			            }
			        } else {
			        	if ($d["data"]->tindakan_penunjang!="")
			        	echo "<li>".$d["data"]->tindakan_penunjang."</li>";
			        }  
			        echo "</ol></td>";
			        echo "</tr>";
			    ?>
			</table>
		</td>
	</tr>
    <?php endif?>
</table>
<table border="1" width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;border-top:none;border-bottom:none;">
    <tr>
    	<td style="padding:5px"><b>Terapi</b></td>
    </tr>
</table>
<table width="100%" cellspacing="2" cellpadding="1" style="border:1px solid;border-top:none;border-bottom:none;">
    <tr>
        <th style='padding:5px' width="50" class='text-center'>No</th>
        <th style='padding:5px'>Nama Obat</th>
        <th style='padding:5px' width="150">Aturan Pakai</th>
        <th style='padding:5px'>Waktu</th>
        <th style='padding:5px'>Cara</th>
        <th style='padding:5px' class="text-center">Qty</th>
    </tr>
    <?php
        $n = 1;
        foreach ($d["terapi"] as $data) {
            echo "<tr id='data'>";
            echo "<td class='text-center'>".($n++)."</td>";
            echo "<td>".$data->nama_obat."</td>";
            echo "<td>".$data->aturan."</td>";
            echo "<td>".$data->nwaktu."</td>";
            echo "<td>".$data->pagi."-".$data->siang."-".$data->sore."-".$data->malem."-".$data->ket_waktulainnya."</td>";
            echo "<td class='text-center'>".$data->qty." ".$data->satuan."</td>";
            echo "</tr>";
        }
    ?>
</table>
<table width="100%" cellspacing="2" cellpadding="1" style="border:1px solid;border-top:none;">
	<tr>
        <td style="padding:5px">&nbsp;</td>
        <td width="30%"><br><br><br><br>Salam Sejawat<br>
        	<div class="ttd_data_qrcode"> </div>
            <?php echo $d["data"]->nama_dokter;?>
            <br>
        </td>
    </tr>
</table>
</section>