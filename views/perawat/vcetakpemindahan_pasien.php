<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
    <script src="<?php echo base_url();?>js/jquery.fixedheadertable.js"></script>
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
        getttd_perawat();
        getttd_perawat2();
        getttd_perawatigd();
		window.print();
    });

    function getttd(){
        var ttd = "<?php echo site_url('ttddokter/getttdpasien/'.$no_pasien);?>";
        $('.pasien_qrcode').qrcode({width: 80,height: 80, text:ttd});
    }
    function getttd_perawat(){
        var ttd = "<?php echo site_url('ttddokter/getttdperawat/'.$ap->pemberi);?>";
        $('.ttd_pemberi').qrcode({width: 80,height: 80, text:ttd});
    }
    function getttd_perawat2(){
        var ttd = "<?php echo site_url('ttddokter/getttdperawat/'.$ap->penerima);?>";
        $('.ttd_penerima').qrcode({width: 80,height: 80, text:ttd});
    }
    function getttd_perawatigd(){
        var ttd = "<?php echo site_url('ttddokter/getttdperawat/'.$tg->petugas_igd);?>";
        $('.ttd_perawatigd').qrcode({width: 80,height: 80, text:ttd});
    }
</script>
    <?php        
        $t1 = new DateTime('today');
        $t2 = new DateTime($q->tgl_lahir);
        $y  = $t1->diff($t2)->y;
        $m  = $t1->diff($t2)->m;
        $d  = $t1->diff($t2)->d;

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
    <table class="laporan" width="100%">
        <tr>
            <td rowspan="5" align="center">
                <img src="<?php echo base_url("img/Logo.png")?>"><br><b>RS CIREMAI</b>
            </td>
            <td rowspan="5" align="center" style="vertical-align: middle;">
                <h4>PEMINDAHAN PASIEN INAP</h4>
            </td>
            <td width="20%">No. RM</td><td>:</td><td><?php echo $no_pasien;?></td>
        </tr>
        <tr>
            <td>No. REG </td><td>:</td><td><?php echo $no_reg;?></td>
        </tr>
        <tr>
            <td>Nama </td><td>:</td><td><?php echo $q->nama_pasien;?></td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td><td>:</td><td><?php echo ($q->tgl_lahir!="" ? date("d-m-Y",strtotime($q->tgl_lahir)) : "")."/ ".$umur;?></td>
        </tr>
        <tr>
            <td>Alamat </td><td>:</td><td><?php echo $q->alamat;?></td>
        </tr>
    </table>
    <table width="100%">
    	<tr>
    		<th>SITUATION</th>
    	</tr>
    	<tr>
    		<td>Tiba Diruangan</td>
    		<td><?php echo $q->tiba_diruangan ?></td>
    		<td>Dari Diruangan</td>
    		<td colspan="4"><?php echo $q->dari_ruangan ?></td>
    	</tr>
    	<tr>
    		<td>Tanggal</td>
    		<td><?php echo $q->tanggal ?></td>
    		<td>Pukul</td>
    		<td><?php echo $q->pukul ?></td>
    		<td>Diagnosa</td>
    		<td><?php echo $q->diagnosa ?></td>
    	</tr>
    	<tr>
    		<td>Dokter yang merawat</td>
    		<td>1. 
    			<?php echo $q->dokter_1 ?>
    		</td>
    		<td>Dokter yang merawat</td>
    		<td>2. 
    			<?php echo $q->dokter_2 ?>
    		</td>
    		<td>Dokter yang merawat</td>
    		<td>3. 
    			<?php echo $q->dokter_3 ?>
    		</td>
    	</tr>
    	<tr>
    		<td>Pasien sudah dijelaskan mengenai diagnosa</td>
    		<td colspan="6"><?php echo $q->penjelasan_diagnosa ?></td>
    	</tr>
    	<tr>
    		<td>Masalah Keperawatan yang utama saat ini</td>
    		<td colspan="6"><?php echo $q->masalah_keperawatan ?></td>
    	</tr>
    	<tr>
    		<td>Prosedur Pembedahan</td>
    		<td><?php echo $q->prosedur_pembedahan ?></td>
    		<td>Tanggal</td>
    		<td colspan="4"><?php echo $q->tgl_prosedur ?></td>
    	</tr>
    	<tr>
    		<td>&nbsp;</td>
    	</tr>
    	<tr>
    		<td>&nbsp;</td>
    	</tr>
    	<tr>
    		<th>BACKGROUND</th>
    	</tr>
    	<tr>
    		<td>Riwayat alergi / reaksi obat</td>
    		<td><?php echo $q->riwayat_alergi ?></td>
    		<td>Nama Obat</td>
    		<td colspan="4"><?php echo $q->nama_obat ?></td>
    	</tr>
    	<tr>
    		<td>Riwayat reaksi</td>
    		<td colspan="6"><?php echo $q->riwayat_reaksi ?></td>
    	</tr>
    	<tr>
    		<td>Intervensi medis / keperawatan</td>
    		<td colspan="6"><?php echo $q->intervensi_medis ?></td>
    	</tr>
    	<tr>
    		<td>Hasil investigasi abnormal</td>
    		<td colspan="6"><?php echo $q->hasil_abnormal ?></td>
    	</tr>
    	<tr>
    		<td>Kewaspadaan / Precaution</td>
    		<td colspan="6"><?php echo $q->precaution ?></td>
    	</tr>
    	<tr>
    		<td><td>&nbsp;</td></td>
    	</tr>
    	<tr>
    		<td><td>&nbsp;</td></td>
    	</tr>
    	<tr>
    		<th>ASSESSMENT</th>
    	</tr>
    	<tr>
    		<td>Reservasi terakhir pukul</td>
    		<td colspan="6"><?php echo $q->reservasi_terakhir ?></td>
    	</tr>
    	<tr>
    		<td>GCS</td>
    		<td><?php echo $q->gcs ?></td>
    		<td>E : <?php echo $q->e ?></td>
    		<td>V : <?php echo $q->v ?></td>
    		<td colspan="1">M : <?php echo $q->m ?></td>
    	</tr>
    	<tr>
    		<td>Pupil & Reaksi cahaya Kanan</td>
    		<td><?php echo $q->pupil ?></td>
    		<td>Kiri</td>
    		<td colspan="4"><?php echo $q->kiri ?></td>
    	</tr>
    	<tr>
    		<td>TD Kanan</td>
    		<td><?php echo $q->td_kanan ?></td>
    		<td>TD Kiri</td>
    		<td><?php echo $q->td_kiri ?></td>
    		<td>Nadi</td>
    		<td><?php echo $q->nadi ?></td>
    	</tr>
    	<tr>
    		<td>Respirasi</td>
    		<td><?php echo $q->respirasi ?></td>
    		<td>Suhu</td>
    		<td><?php echo $q->suhu ?></td>
    		<td>SpO2</td>
    		<td><?php echo $q->spo2 ?></td>
    	</tr>
    	<tr>
    		<td>Diet / Nutrisi</td>
    		<td colspan="6"><?php echo $q->diet_nutrisi ?></td>
    	</tr>
    	<?php if ($q->batasancairan!=0): ?>
    		<tr>
	    		<td>Batasan Cairan</td>
	    		<td colspan="6"><?php echo $q->batasancairan ?> cc</td>
	    	</tr>
    	<?php endif ?>
    	<?php if ($q->dietkhusus!=""): ?>
    		<tr>
	    		<td>Diet Khusus</td>
	    		<td colspan="6"><?php echo $q->dietkhusus ?></td>
	    	</tr>
    	<?php endif ?>
    	<?php if ($q->puasa!=""): ?>
    		<tr>
	    		<td>Puasa</td>
	    		<td colspan="6"><?php echo $q->puasa ?></td>
	    	</tr>
    	<?php endif ?>
    	<tr>
    		<td>BAB</td>
    		<td colspan="6"><?php echo $q->bab ?></td>
    	</tr>
    	<tr>
    		<td>BAK</td>
    		<td colspan="6"><?php echo $q->bak ?></td>
    	</tr>
    	<?php if ($q->bak=="KATETER"): ?>
    		<tr>
	    		<td>Jenis Kateter</td>
	    		<td><?php echo $q->jenis_kateter ?></td>
	    		<td>Nomor Kateter</td>
	    		<td><?php echo $q->nomor_kateter ?></td>
	    		<td>Tgl Pemasangan</td>
	    		<td><?php echo $q->tglpasang_kateter ?></td>
	    	</tr>
    	<?php endif ?>
    	<tr>
    		<td>Transfer</td>
    		<td colspan="6"><?php echo $q->transfer ?></td>
    	</tr>
    	<tr>
    		<td>Mobilitas</td>
    		<td colspan="6"><?php echo $q->mobilitas ?></td>
    	</tr>
    	<tr>
    		<td>Gangguan Indra</td>
    		<td colspan="6"><?php echo $q->ganguan_indra ?></td>
    	</tr>
    	<tr>
    		<td>Alat Bantu yang digunakan</td>
    		<td colspan="6"><?php echo $q->alat_bantu ?></td>
    	</tr>
    	<tr>
    		<td>Infus</td>
    		<td><?php echo $q->infus ?></td>
    		<td>Lokasi</td>
    		<td><?php echo $q->lokasi ?></td>
    		<td>Tanggal Pemasangan</td>
    		<td><?php echo $q->tgl_pemasangan ?></td>
    	</tr>
    	<tr>
    		<td>Hal-hal istimewa yang berhubungan dengan kondisi pasien</td>
    		<td colspan="6"><?php echo $q->hal_istimewa ?></td>
    	</tr>
    	<tr>
    		<td>Tindakan/kebutuhan khusus</td>
    		<td colspan="6"><?php echo $q->tindakan_khusus ?></td>
    	</tr>
    	<tr>
    		<td>Peralatan khusus yang diperlukan</td>
    		<td colspan="6"><?php echo $q->peralatan_khusus ?></td>
    	</tr>
    	<tr>
    		<td><td>&nbsp;</td></td>
    	</tr>
    	<tr>
    		<td><td>&nbsp;</td></td>
    	</tr>
    	<tr>
    		<th>RECOMMENDATIONS</th>
    	</tr>
    	<tr>
    		<td>Konsultasi</td>
    		<td colspan="6"><?php echo $q->dokter_konsultasi ?></td>
    	</tr>
    	<tr>
    		<td>Terapi</td>
    		<td colspan="6"><?php echo $q->terapi ?></td>
    	</tr>
    	<tr>
    		<td>Rencana pemeriksaan lab / radiologi</td>
    		<td colspan="2"><?php echo $q->tindakan_radiologi ?></td>
    		<td colspan="3"><?php echo $q->tindakan_lab ?></td>
    	</tr>
    	<tr>
    		<td>Rencana tindakan lebih lanjut</td>
    		<td colspan="6"><?php echo $q->renacana_tindakan ?></td>
    	</tr>
    	<tr>
    		<td>Note : obat, barang, dokumen yang disertakan</td>
    		<td colspan="6"><?php echo $q->note ?></td>
    	</tr>
    </table>
    <br>
    <br>
    <table width="100%">
    	<tr>
			<th>OBSERVASI</th>
		</tr>
    </table>
    <table width="100%" class="laporan">
    	<thead>
            <tr>
                <th class="text-center" rowspan="2">TGL</th>
                <th class="text-center" rowspan="2">JAM</th>
                <th class="text-center" rowspan="2">TENSI</th>
                <th class="text-center" rowspan="2">NADI</th>
                <th class="text-center" rowspan="2">SUHU</th>
                <th class="text-center" rowspan="2">RESPIRASI</th>
                <th class="text-center" rowspan="2">KES</th>
                <th class="text-center" rowspan="2">SPO2</th>
                <th class="text-center" colspan="3">INTAKE</th>
                <th class="text-center" colspan="3">OUTPUT</th>
                <th class="text-center" rowspan="2">CATATAN</th>
            </tr>
            <tr>
                <th class="text-center">ORAL</th>
                <th class="text-center">INFUS</th>
                <th class="text-center">DARAH</th>
                <th class="text-center">URINE</th>
                <th class="text-center">DRAINE</th>
                <th class="text-center">NGT</th>
            </tr>
        </thead>
        <tbody>
            <?php
            	foreach ($q1->result() as $value) {
            		echo "
            			<tr>
            				<td>".$value->tgl."</td>
                            <td>".$value->jam."</td>
            				<td>".$value->tensi."</td>
            				<td>".$value->nadi."</td>
            				<td>".$value->suhu."</td>
            				<td>".$value->respirasi."</td>
            				<td>".$value->kes."</td>
            				<td>".$value->spo2."</td>
            				<td>".$value->oral."</td>
            				<td>".$value->infus."</td>
            				<td>".$value->darah."</td>
            				<td>".$value->urine."</td>
            				<td>".$value->draine."</td>
            				<td>".$value->ngt."</td>
            				<td>".$value->catatan."</td>
            			</tr>
            		";
            	}
            ?>
        </tbody>
    </table>
    <table class="laporan" width="100%">
        <tr>
            <td align="center" width="30%" rowspan="2">
                Disetujui
            </td>
            <td align="center" width="40%">
                Diserahkan
            </td>
            <td align="center" width="30%">
                Diterima
            </td>
        </tr>
        <tr>
            <td>Tanggal : Jam :</td >
            <td>Tanggal : Jam :</td >
        </tr>
        <tr>
            <td align="center">
                <div class="pasien_qrcode"> </div>
                <br>
                <?php echo $q->nama_pasien ?>
                <br>
                No Pasien <?php echo $no_pasien ?>
            </td>
            <td align="center">
                <?php if ($q->dari_ruangan=="IGD"): ?>
                    <div class="ttd_perawatigd"> </div>
                    <br>
                    <?php echo $tg->nama_perawat ?>
                    <br>
                    Nip.<?php echo $tg->petugas_igd ?>
                <?php else: ?>
                    <div class="ttd_pemberi"> </div>
                    <br>
                    <?php echo $ap->perawat_pengirim ?>
                    <br>
                    Nip.<?php echo $ap->pemberi ?>
                <?php endif ?>
            </td>
            <td align="center">
                <div class="ttd_penerima"> </div>
                <br>
                <?php echo $ap->perawat_penerima ?>
                <br>
                Nip.<?php echo $ap->penerima ?>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td align="center">Pasien / Penanggung Jawab</td>
            <td align="center">Perawat Pengirim</td>
            <td align="center">Perawat Penerima</td>
        </tr>
    </table>
<style>
    *{
        padding-left : 5px;
        padding-right: 5px;
    }
    table, td,th{
        font-family: sans-serif;
        /*padding: 0px; margin:0px;*/
        /*font-size: 13px;*/
    }
    /*input.text{
        height:5px;
    }*/
</style>
<style type="text/css">
    .laporan {
        border-collapse: collapse !important;
        background-color: transparent;
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 11px;
    }
    .laporan {
        border-collapse: collapse !important;
        background-color: transparent;
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 11px;
    }
    .laporan > thead > tr > th,
    .laporan > tbody > tr > th,
    .laporan > tfoot > tr > th,
    .laporan > thead > tr > td,
    .laporan > tbody > tr > td,
    .laporan > tfoot > tr > td {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }
    .laporan > thead > tr > th {
        vertical-align: bottom;
        border-bottom: 2px solid #ddd;
    }
    .laporan > caption + thead > tr:first-child > th,
    .laporan > colgroup + thead > tr:first-child > th,
    .laporan > thead:first-child > tr:first-child > th,
    .laporan > caption + thead > tr:first-child > td,
    .laporan > colgroup + thead > tr:first-child > td,
    .laporan > thead:first-child > tr:first-child > td {
        border-top: 0;
    }
    .laporan > tbody + tbody {
        border-top: 2px solid #ddd;
    }
    .laporan td,
    .laporan th {
        background-color: #fff !important;
        border: 1px solid #000 !important;
    }
</style>