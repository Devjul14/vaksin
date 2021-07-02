<link rel="stylesheet" href="<?php echo base_url();?>plugins/select2/select2.css">
<script src="<?php echo base_url(); ?>plugins/select2/select2.js"></script>
<script>
var mywindow;
    function openCenteredWindow(url) {
        var width = 800;
        var height = 500;
        var left = parseInt((screen.availWidth/2) - (width/2));
        var top = parseInt((screen.availHeight/2) - (height/2));
        var windowFeatures = "width=" + width + ",height=" + height +
                             ",status,resizable,left=" + left + ",top=" + top +
                             ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow = window.open(url, "subWind", windowFeatures);
    }
 var mywindow1;
    function openCenteredWindow1(url) {
        var width = 800;
        var height = 500;
        var left = parseInt((screen.availWidth/2) - (width/2));
        var top = parseInt((screen.availHeight/2) - (height/2));
        var windowFeatures = "width=" + width + ",height=" + height +
                             ",status,resizable,left=" + left + ",top=" + top +
                             ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow1 = window.open(url, "subWind", windowFeatures);
    }
    $(document).ready(function(){
        $("[name='doktersp']").select2();
        var formattgl = "dd-mm-yy";
        $("input[name='tanggal']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='ulangan']").datepicker({
            dateFormat : formattgl,
        });
        $("[name='doktersp']").change(function(){
            var no_rm = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var iddokter = $(this).val();
            var url = "<?php echo base_url();?>dokter/jawabankonsul_inap/"+no_rm+"/"+no_reg+"/"+iddokter;
            window.location = url;
        })
        $('.back').click(function(){
            var cari_noreg = $("[name='no_reg']").val();
            $.ajax({
                type  : "POST",
                data  : {cari_no:cari_noreg},
                url   : "<?php echo site_url('dokter/getcaripasien');?>",
                success : function(result){
                    window.location = "<?php echo site_url('dokter/rawat_inapdokter_ranap');?>";
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $("[name='tindakan']").select2();
        $("table#form td:even").css("text-align", "right");
        $("table#form td:odd").css("background-color", "white");

        
    });
    function namadiagnosa(kode,element){
        var data = $.ajax({
                        url : "<?php echo base_url();?>pendaftaran/namadiagnosa",
                        method : "POST",
                        async: false,
                        data : {kode: kode}
                    }).responseText;
        $("[name='"+element+"']").val(data);
    }
</script>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="form-horizontal">
            <div class="box-body">
            	<div class="form-group">
                    <label class="col-md-2 control-label">No. Reg</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='no_reg' readonly value="<?php echo $no_reg;?>"/>
                    </div>
                    <label class="col-md-2 control-label">No. RM</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='no_pasien' readonly value="<?php echo $no_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Nama Pasien</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_pasien;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Tgl Lahir / Umur</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->tgl_lahir.' / '.$y;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Ruangan</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='ruangan' readonly value="<?php echo $q->nama_ruangan;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Kelas</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_kelas?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Kamar</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='kamar' readonly value="<?php echo $q->nama_kamar?>"/>
                    </div>
                    <label class="col-md-2 control-label">Dokter Visit</label>
                    <div class="col-md-2">
                        <input name="dokter" class="form-control" type="text" readonly value="<?php echo $dv->row()->nama_dokter;?>">
                    </div>
                    <label class="col-md-2 control-label">Dokter Konsul</label>
                    <div class="col-md-2">
                        <select name="doktersp" class="form-control">
                            <option value="">---</option>
                            <?php 
                                foreach ($dokter->result() as $key){
                                    echo "<option value = '".$key->dokter_konsul."/".$key->id."' ".($key->dokter_konsul==$iddokter ? "selected" : "").">".$key->nama_dokter."</option>";
                                }    
                            ?>
                        </select>
                    </div>
                </div>
                <?php
                    json_encode($d);
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
                    <table border="1" width="100%" cellspacing="0" cellpadding="1" style="border:1px solid;">
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
                    <table width="100%" cellspacing="2" cellpadding="1" style="border:1px solid;border-top:none;">
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
                </section>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button class="back btn btn-warning" type="button"> Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modalnotif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-navy">Yakin akan membayar sejumlah</div>
                <div class="modal-body">
                    <h2 class="total"></h2>
                </div>
                <div class="modal-footer">
                    <button class="okbayar btn btn-success" type="button">OK</button>
                </div>
            </div>
        </div>
    </div>
<style type="text/css">
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        margin-top: -15px;
    }
    .select2-container--default .select2-selection--single{
        padding: 16px 0px;
        border-color: #d2d6de;
    }
</style>