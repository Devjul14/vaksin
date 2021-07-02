<div class="col-md-12">
    <div class="box box-primary">
        <div class="form-horizontal">
            <div class="box-body">
            	<div class="form-group">
                    <label class="col-md-2 control-label">No. RM</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='no_pasien' readonly value="<?php echo $q->no_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Nama Pasien</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_pasien1;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Tgl Lahir / Umur</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='tgl_lahir' readonly value="<?php echo date("d-m-Y",strtotime($q->tgl_lahir)).' / '.$umur;?>"/>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="col-md-12 control-label">Kerusakan</label>
                    <div class="col-md-12">
                        <textarea class="form-control" name="kerusakan" style="max-width: 100%;height:300px;"><?php echo $kerusakan; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12 control-label">Lain-lain</label>
                    <div class="col-md-12">
                        <textarea class="form-control" name="lain" style="max-width: 100%;height:300px;"><?php echo $lain; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
