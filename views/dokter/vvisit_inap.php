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
        $("[name='dokter_visit']").change(function(){
            var no_rm = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var iddokter = $(this).val();
            var url = "<?php echo base_url();?>dokter/visit_inap/"+no_rm+"/"+no_reg+"/"+iddokter;
            window.location = url;
        })
        $("[name='dokter'], .tindakan_radiologi, .tindakan_lab, .penunjang").select2();
        var formattgl = "dd-mm-yy";
        $("input[name='tanggal_tambah']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='ulangan']").datepicker({
            dateFormat : formattgl,
        });
        $('.anatomi').click(function(){
            var no_reg= $("[name='no_reg']").val();
            window.location ="<?php echo site_url('assesmen/getanatomi_inap');?>/"+no_reg+"/visit";
            return false;
        });
        $('.terapi').click(function(){
            var no_reg= $("[name='no_reg']").val();
            var no_rm= $("[name='no_pasien']").val();
            var iddokter= "<?php echo $iddokter;?>";
            window.location ="<?php echo site_url('dokter/apotek_igdinap');?>/"+no_rm+"/"+no_reg+"/"+iddokter+"/visit";
            return false;
        });
        $(".upload").click(function(){
            var no_rm = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var url = "<?php echo site_url('pendaftaran/formuploadpdf_inap');?>/"+no_rm+"/"+no_reg+"/visit";
            window.location = url;
            return false; 
        });
        $(".tambah").click(function(){
            var no_rm = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var id = $(this).attr("id");
            var iddokter = $("[name='doktersp_tambah']").val();
            window.location = "<?php echo site_url('dokter/visit_inap');?>/"+no_rm+"/"+no_reg+"/"+iddokter;
        });
        $(".hapus").click(function(){
            var id = $(this).attr("id");
            $.ajax({
                type  : "POST",
                data  : {id:id},
                url   : "<?php echo site_url('dokter/hapusvisit');?>",
                success : function(result){
                    $(".tambah").click();
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $("tr.data").dblclick(function(){
            var no_rm = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var id = $(this).attr("id");
            var id_terkait = $(this).attr("id_terkait");
            var iddokter = $(this).attr("iddokter");
            window.location = "<?php echo site_url('dokter/visit_inap');?>/"+no_rm+"/"+no_reg+"/"+iddokter+"/"+id_terkait+"/"+id;
        });
        $(".cetak").click(function(){
            var no_reg = $("[name='no_reg']").val();
            var url = "<?php echo site_url('pendaftaran/cetak_laporantindakan');?>/"+no_reg;
            openCenteredWindow(url);
        });
        $('.back').click(function(){
            var cari_noreg = $("[name='no_reg']").val();
            $.ajax({
                type  : "POST",
                data  : {cari_noreg:cari_noreg},
                url   : "<?php echo site_url('pendaftaran/getcaripasien_ralan');?>",
                success : function(result){
                    window.location = "<?php echo site_url('dokter/rawat_inapdokter_ranap');?>";
                },
                error: function(result){
                    alert(result);
                }
            });
        }); 
    });
</script>
<?php
    $t1 = new DateTime('today');
    $t2 = new DateTime($q->tgl_lahir);
    $y  = $t1->diff($t2)->y;
    $m  = $t1->diff($t2)->m;
    $d  = $t1->diff($t2)->d;
    if($v->num_rows()>0){
        $v = $v->row();
        $s_tambah = $v->s;
        $o_tambah = $v->o;
        $a_tambah = $v->a;
        $p_tambah = $v->p;
        $tanggal_tambah = date("d-m-Y",strtotime($v->tanggal));
        $td_tambah = $v->td;
        $td2_tambah = $v->td2;
        $nadi_tambah = $v->nadi;
        $respirasi_tambah = $v->respirasi;
        $suhu_tambah = $v->suhu;
        $spo2_tambah = $v->spo2;
        $bb_tambah = $v->bb;
        $tb_tambah = $v->tb;
        $pemeriksaan_fisik = $v->pemeriksaan_fisik;
        $pemeriksaan_fisik = explode(",", $pemeriksaan_fisik);
        foreach ($pemeriksaan_fisik as $key => $value) {
            $pemeriksaan_fisik_tambah[$key] = $value;
        }
        $kelainan = $v->kelainan;
        $kelainan = explode("|", $kelainan);
        foreach ($kelainan as $key => $value) {
            $kelainan_tambah[$key] = $value;
        }
    } else {
        $s_tambah = "";
        $o_tambah = "";
        $a_tambah = "";
        $p_tambah = "";
        $tanggal_tambah = date("d-m-Y");
        $td_tambah = "";
        $td2_tambah = "";
        $nadi_tambah = "";
        $respirasi_tambah = "";
        $suhu_tambah = "";
        $spo2_tambah = "";
        $bb_tambah = "";
        $tb_tambah = "";
        for ($i=0;$i<=11;$i++){
            $pemeriksaan_fisik_tambah[$i] = 1;
        }
    }
?>
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
                </div>
                <div class="row">
                    <!-- <div class="col-md-4">
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr class="bg-navy">
                                    <th width="80px">No</th>
                                    <th>Tanggal</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 1;
                                    foreach($row->result() as $data){
                                        echo "<tr id='".$data->id."' iddokter='".$data->dokter_visit."' id_terkait='".$data->id_terkait."' class='data'>";
                                        echo "<td>".($i++)."</td>";
                                        echo "<td>".date("d-m-Y",strtotime($data->tanggal))."</td>";
                                        echo "<td class='text-center'><button class='btn btn-xs btn-danger hapus' id='".$data->id."'><i class='fa fa-minus'></i></button></td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div> -->
                    <div class="col-md-12">
                        <!-- <div class="row">
                            <div class="col-md-12 ">
                                <div class="pull-right"> 
                                    <button class="tambah btn btn-primary btn-xs"><i class="fa fa-plus"></i>&nbsp;Tambah</button>
                                </div>
                            </div>
                        </div> -->
                        <div class="clearfix">&nbsp;</div>
                        <?php echo form_open("dokter/simpantambahvisit_inap",array("class"=>"form-horizontal"));?>
                        <input type="hidden" name="id_lama" value="<?php echo $id;?>">
                        <input type="hidden" name="no_rm_tambah" value="<?php echo $no_pasien;?>">
                        <input type="hidden" name="no_reg_tambah" value="<?php echo $no_reg;?>">
                        <input type="hidden" name="doktersp_tambah" value="<?php echo $iddokter;?>">
                        <input type="hidden" name="id_terkait" value="<?php echo $id_terkait;?>">
                        <div class="form-group">   
                            <label class="col-md-3 control-label">Tanggal</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name='tanggal_tambah' value="<?php echo $tanggal_tambah;?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Dokter</label>
                            <div class="col-md-9">
                                <select name="dokter_visit" class="form-control">
                                    <?php 
                                        foreach ($dokter->result() as $key){
                                            echo "<option value = '".$key->dokter_konsul."/".$key->ids."' ".($key->dokter_konsul==$iddokter ? "selected" : "").">".$key->nama_dokter."</option>";
                                        }    
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">S</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="s_tambah" style="max-width: 100%;height:160px;"><?php echo $s_tambah ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">O</label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">TD Kanan</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name='td_tambah' value="<?php echo $td_tambah;?>"/>
                            </div>
                            <label class="col-md-3 control-label">TD Kiri</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name='td2_tambah' value="<?php echo $td2_tambah;?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nadi</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name='nadi_tambah' value="<?php echo $nadi_tambah;?>"/>
                            </div>
                            <label class="col-md-3 control-label">Respirasi</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name='respirasi_tambah' value="<?php echo $respirasi_tambah;?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Suhu</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name='suhu_tambah' value="<?php echo $suhu_tambah;?>"/>
                            </div>
                            <label class="col-md-3 control-label">SpO2</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name='spo2_tambah' value="<?php echo $spo2_tambah;?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">BB</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name='bb_tambah' value="<?php echo $bb_tambah;?>"/>
                            </div>
                            <label class="col-md-3 control-label">TB</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name='tb_tambah' value="<?php echo $tb_tambah;?>"/>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-md-6 control-label">Pemeriksaan Fisik</label>
                            <label class="col-md-6 control-label">Kelainan/ Keluhan</label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kepala</label>
                            <div class="col-md-3">
                                <select class="form-control" name="pemeriksaan_fisik1_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[0]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[0]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan1_tambah' value="<?php echo (isset($kelainan_tambah[0]) ? $kelainan_tambah[0] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Mata</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik2_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[1]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[1]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan2_tambah' value="<?php echo (isset($kelainan_tambah[1]) ? $kelainan_tambah[1] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">THT</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik3_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[2]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[2]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan3_tambah' value="<?php echo (isset($kelainan_tambah[2]) ? $kelainan_tambah[2] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Gigi Mulut</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik4_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[3]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[3]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan4_tambah' value="<?php echo (isset($kelainan_tambah[3]) ? $kelainan_tambah[3] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Leher</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik5_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[4]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[4]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan5_tambah' value="<?php echo (isset($kelainan_tambah[4]) ? $kelainan_tambah[4] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Thoraks</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik6_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[5]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[5]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan6_tambah' value="<?php echo (isset($kelainan_tambah[5]) ? $kelainan_tambah[5] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Abdomen</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik7_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[6]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[6]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan7_tambah' value="<?php echo (isset($kelainan_tambah[6]) ? $kelainan_tambah[6] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Ekstremitas Atas</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik8_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[7]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[7]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan8_tambah' value="<?php echo (isset($kelainan_tambah[7]) ? $kelainan_tambah[7] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Ekstremitas Bawah</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik9_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[8]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[8]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan9_tambah' value="<?php echo (isset($kelainan_tambah[8]) ? $kelainan_tambah[8] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Genitalia</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik10_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[9]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[9]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan10_tambah' value="<?php echo (isset($kelainan_tambah[9]) ? $kelainan_tambah[9] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Anus</label>
                            <div class="col-md-3">
                                 <select class="form-control" name="pemeriksaan_fisik11_tambah">
                                    <option value="0" <?php echo ($pemeriksaan_fisik_tambah[10]==0 ? "selected" : "");?>>Tidak Normal</option>
                                    <option value="1" <?php echo ($pemeriksaan_fisik_tambah[10]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name='kelainan11_tambah' value="<?php echo (isset($kelainan_tambah[10]) ? $kelainan_tambah[10] : '');?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">A</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="a_tambah" style="max-width: 100%;height:160px;"><?php echo $a_tambah ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">P</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="p_tambah" style="max-width: 100%;height:160px;"><?php echo $p_tambah ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tindakan Radiologi</label>
                            <div class="col-md-9">
                                <select class="form-control tindakan_radiologi"  name="tindakan_radiologi[]" multiple="multiple" style="width:100%">
                                    <option value="">-----</option>
                                    <?php
                                        foreach ($radiologi->result() as $key) {
                                            $t = explode(",", $tindakan_radiologi);
                                            if (count($t)>0){
                                                foreach ($t as $k => $value) {
                                                    echo "<option value='".$key->id_tindakan."' ".($key->id_tindakan==$value ? "selected" : "").">".$key->nama_tindakan."</option>";
                                                }
                                            } else {
                                                echo "<option value='".$key->id_tindakan."' ".($key->id_tindakan==$tindakan_radiologi ? "selected" : "").">".$key->nama_tindakan."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tindakan Lab</label>
                            <div class="col-md-9">
                                <select class="form-control tindakan_lab" name="tindakan_lab[]" multiple="multiple" style="width:100%">
                                    <option value="">-----</option>
                                    <?php
                                        foreach ($lab->result() as $key) {
                                            $t = explode(",", $tindakan_lab);
                                            if (count($t)>0){
                                                foreach ($t as $k => $value) {
                                                    echo "<option value='".$key->kode_tindakan."' ".($key->kode_tindakan==$value ? "selected" : "").">".$key->nama_tindakan."</option>";
                                                }
                                            } else {
                                                echo "<option value='".$key->kode_tindakan."' ".($key->kode_tindakan==$tindakan_lab ? "selected" : "").">".$key->nama_tindakan."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tindakan Lain Lain</label>
                            <div class="col-md-9">
                                <select class="form-control penunjang"  name="penunjang[]" multiple="multiple" style="width:100%">
                                    <option value="">-----</option>
                                    <?php
                                        foreach ($tarif_penunjang_medis->result() as $key) {
                                            $t = explode(",", $pemeriksaan_penunjang);
                                            if (count($t)>0){
                                                foreach ($t as $k => $value) {
                                                    echo "<option value='".$key->kode."' ".($key->kode==$value ? "selected" : "").">".$key->ket."</option>";
                                                }
                                            } else {
                                                echo "<option value='".$key->kode."' ".($key->kode==$pemeriksaan_penunjang ? "selected" : "").">".$key->ket."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
            </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="pull-left">
                    <div class="btn-group">
                        <?php if ($dokter->num_rows()>0) :?>
                        <button class="anatomi btn bg-navy" type="button">Anatomi</button>
                        <button class="terapi btn bg-maroon" type="button">Terapi</button>
                        <button class="upload btn btn-md btn-primary" type="button"> PDF</button>
                        <?php endif ?>
                    </div>
                </div>
                <div class="pull-right">
                    <?php if ($dokter->num_rows()>0) :?>
                    <!-- <button class="cetak btn btn-success" type="button"> Cetak</button> -->
                    <button class="btn btn-primary" type="submit"> Simpan</button>
                    <button class="back btn btn-warning" type="button"> Back</button>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
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
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
</style>