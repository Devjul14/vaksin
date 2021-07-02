<script>
    $(document).ready(function(){
        $('.back').click(function(){
            var cari_noreg = $("[name='no_reg']").val();
            $.ajax({
                type  : "POST",
                data  : {cari_no:cari_noreg},
                url   : "<?php echo site_url('pendaftaran/getcaripasien');?>",
                success : function(result){
                    window.location = "<?php echo site_url('dokter/rawat_jalandokter');?>";
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $("[name='suspek_kerja']").change(function(){
            getketerangansuspek();
        })
        var formattgl = "dd-mm-yy";
        $("input[name='tgl_pemeriksaan']").datepicker({
            dateFormat : formattgl,
        });
    });
</script>
<?php
    $t1 = new DateTime('today');
    $t2 = new DateTime($row->tgl_lahir);
    $y  = $t1->diff($t2)->y;
    $m  = $t1->diff($t2)->m;
    $d  = $t1->diff($t2)->d;
    if ($q->num_rows()>0){
        $dat = $q->row();
        $diagnosa_kerja = $dat->diagnosa_kerja;
        $rencana_terapi = $dat->rencana_terapi;
        $jadwal_terapi = $dat->jadwal_terapi;
        $persiapan = $dat->persiapan;
        $rencana_pemeriksaan = $dat->rencana_pemeriksaan;
        $catatan = $dat->catatan;
        $action = "edit";
    } else {
        $diagnosa_kerja =
        $rencana_terapi =
        $jadwal_terapi =
        $persiapan =
        $rencana_pemeriksaan =
        $catatan = "";
        $action = "simpan";
    }
?>
<div class="col-md-12">
    <div class="box box-primary">
        <?php echo form_open("dokter/simpanpengantarterapi/".$action);?>
        <div class="box-body">
        	<div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">No. RM</label>
                    <div class="col-md-4">
                        <input type="text" readonly class="form-control" name='no_rm' readonly value="<?php echo $no_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">No. Reg</label>
                    <div class="col-md-4">
                        <input type="text" readonly class="form-control" name='no_reg' readonly value="<?php echo $no_reg;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Nama Pasien</label>
                    <div class="col-md-4">
                        <input type="text" readonly class="form-control" name='nama_pasien' readonly value="<?php echo $row->nama_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">L/ P</label>
                    <div class="col-md-4">
                        <input type="text" readonly class="form-control" name='jenis_kelamin' readonly value="<?php echo $row->jenis_kelamin;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Tgl Lahir/ Usia</label>
                    <div class="col-md-4">
                        <input type="text" readonly class="form-control" name='tgl_lahir' readonly value="<?php echo date("d-m-Y",strtotime($row->tgl_lahir))."/ ".$y." tahun";?>"/>
                    </div>
                    <label class="col-md-2 control-label">Alamat/ Telpon</label>
                    <div class="col-md-4">
                        <textarea type="text" style="height:100px" class="form-control" name='alamat' readonly><?php echo $row->alamat." - ".$row->telpon;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">Diagnosis Kerja</label>
                  <div class="col-md-10">
                      <input type="text" class="form-control" name='diagnosa_kerja' autocomplete="off" value="<?php echo $diagnosa_kerja;?>"/>
                  </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Rencana Terapi/ Tindakan</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name='rencana_terapi' value="<?php echo $rencana_terapi;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Jadwal Terapi/ Tindakan</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name='jadwal_terapi' value="<?php echo $jadwal_terapi;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Persiapan</label>
                    <div class="col-md-10">
                        <textarea type="text" class="form-control" name='persiapan'><?php echo $persiapan;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Rencana Pemeriksaan Penunjang</label>
                    <div class="col-md-10">
                        <textarea type="text" class="form-control" name='rencana_pemeriksaan'><?php echo $rencana_pemeriksaan;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Catatan</label>
                    <div class="col-md-10">
                        <textarea type="text" class="form-control" name='catatan'><?php echo $catatan;?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <div class="btn-group">
                    <button class="back btn btn-warning" type="button"><i class="fa fa-arrow-left"></i> Back</button>
                    <button class="simpan btn btn-success" type="submit"> Simpan</button>
                </div>
            </div>
        </div>
        <?php echo form_close();?>
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
        color: #f4f4f4;
    }
</style>
