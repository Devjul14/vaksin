<script>
    $(document).ready(function() {

    });
</script>
<?php
if ($q->num_rows() > 0) {
    $row = $q->row();
    $keluhan_utama      = $row->keluhan_utama;
    $riwayat_pekerjaan  = $row->riwayat_pekerjaan;
    $merokok            = $row->merokok;
    $alkohol            = $row->alkohol;
    $anamnesa_khusus    = $row->anamnesa_khusus;
    $anamnesa_umum      = $row->anamnesa_umum;
    $jumlah_anak        = $row->jumlah_anak;
    $keadaan_anak       = $row->keadaan_anak;
    $keadaan_orangtua   = $row->keadaan_orangtua;
    $riwayat_penyakit   = $row->riwayat_penyakit;
    $items = json_decode($row->jenis);
    $dat = array();
    // foreach ($items as $keys => $values) {
    //     $dat[$keys] = $values;
    // }
    if ($q->status) $ubah = "readonly";
    else $ubah = "";
    $aksi = "edit";
} else {
    $keluhan_utama      =
        $riwayat_pekerjaan  =
        $merokok            =
        $alkohol            =
        $anamnesa_khusus    =
        $anamnesa_umum      =
        $jumlah_anak        =
        $keadaan_anak       =
        $keadaan_orangtua   =
        $items              =
        $ubah               =
        $riwayat_penyakit   = "";
    $dat = array();
    $aksi = "simpan";
}
?>
<div class="col-md-12">
    <?php
    if ($this->session->flashdata('message')) {
        $pesan = explode('-', $this->session->flashdata('message'));
        echo "<div class='alert alert-" . $pesan[0] . "' alert-dismissable>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <b>" . $pesan[1] . "</b>
            </div>";
    }

    ?>
    <div class="box box-primary">
        <div class="box-body">
            <div class="form-horizontal">
                <?php echo form_open_multipart("dokter/simpanradioterapi/" . $aksi, array("id" => "formsave", "class" => "form-horizontal")) ?>
                <div class="form-group">
                    <label class="col-md-1 control-label">No. Reg</label>
                    <div class="col-md-3">
                        <input type="text" readonly class="form-control" name='no_reg' readonly value="<?php echo $no_reg; ?>" />
                    </div>
                    <label class="col-md-1 control-label">No. RM</label>
                    <div class="col-md-3">
                        <input type="text" readonly class="form-control" name='no_pasien' readonly value="<?php echo $no_pasien; ?>" />
                    </div>
                    <label class="col-md-1 control-label">Nama Pasien</label>
                    <div class="col-md-3">
                        <input type="text" readonly class="form-control" name='nama_pasien' value="<?php echo $q1->nama_pasien1; ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-1 control-label">Keluhan Utama</label>
                    <div class="col-md-11">
                        <textarea type="text" class="form-control" name='keluhan_utama' value="<?php echo $keluhan_utama; ?>"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label">Riwayat Pekerjaan</label>
                    <div class="col-md-3">
                        <input type="text" required class="form-control" name='riwayat_pekerjaan' value="<?php echo $riwayat_pekerjaan; ?>" <?php echo $ubah; ?> />
                    </div>
                    <label class="col-md-1 control-label">Merokok</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='merokok' value="<?php echo $merokok; ?>" />
                    </div>
                    <label class="col-md-1 control-label">Alkohol</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='alkohol' value="<?php echo $alkohol; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label">Anamnesa Khusus</label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" name='anamnesa_khusus' value="<?php echo $anamnesa_khusus; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label">Anamnesa Umum</label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" name='anamnesa_umum' value="<?php echo $anamnesa_umum; ?>" />
                    </div>
                </div>
                <?php
                foreach ($jenis->result() as $row) {
                    echo "<div class='form-group'>";
                    echo "<div class='col-sm-6'>";
                    echo "<label class='col-sm-5 control-label'>" . $row->keterangan . "</label>";
                    echo "<input class='col-sm-1' type='checkbox' name='" . $row->keterangan . "' value='Ya' >";
                    echo "<label class='col-sm-2 form-check-label'>Ya</label>";
                    echo "<input class='col-sm-1' type='checkbox' name='" . $row->keterangan . "' value='Tidak'>";
                    echo "<label class='col-sm-2 form-check-label'>Tidak</label>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
                <div class="form-group">
                    <label class="col-md-1 control-label">Jumlah Anak</label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" name='jumlah_anak' value="<?php echo $jumlah_anak; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label">Keadaan Anak</label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" name='keadaan_anak' value="<?php echo $keadaan_anak; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label">Keadaan Orang tua / Saudara</label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" name='keadaan_orangtua' value="<?php echo $keadaan_orangtua; ?>" />
                    </div>
                </div>
                <h5 class="box-title"><b>RIWAYAT PENYAKIT DAHULU dan PENGOBATAN</b></h5>
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea type="text" style="height: 100px;" class="form-control" name='riwayat_penyakit' value="<?php echo $riwayat_penyakit; ?>"></textarea>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>