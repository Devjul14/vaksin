<script type="text/javascript" src="<?php echo base_url() ?>js/library.js"></script>
<script>
    var mywindow;

    function openCenteredWindow(url) {
        var width = 1000;
        var height = 700;
        var left = parseInt((screen.availWidth / 2) - (width / 2));
        var top = parseInt((screen.availHeight / 2) - (height / 2));
        var windowFeatures = "width=" + width + ",height=" + height +
            ",status,resizable,left=" + left + ",top=" + top +
            ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow = window.open(url, "subWind", windowFeatures);
    }
    $(document).ready(function() {

        $(".cetak").click(function() {
            var no_reg = $("[name='no_reg']").val();
            var no_rm = $("[name='no_rm']").val();
            var url = "<?php echo site_url('dokter/cetaksebabkematian'); ?>/" + no_reg + "/" + no_rm;
            openCenteredWindow(url);
        });
        $('.cancel').click(function() {
            window.location = "<?php echo site_url('dokter/rawat_inapdokter_ranap'); ?>";
        });
    });
</script>
<?php
list($year, $month, $day) = explode("-", $pi->tgl_lahir);
$year_diff  = date("Y") - $year;
$month_diff = date("m") - $month;
$day_diff   = date("d") - $day;
if ($month_diff < 0) {
    $year_diff--;
    $month_diff *= (-1);
} elseif (($month_diff == 0) && ($day_diff < 0)) $year_diff--;
if ($day_diff < 0) {
    $day_diff *= (-1);
}
$umur = $year_diff . " tahun ";
if ($p > 0) {
    $a = $p->a;
    $b = $p->b;
    $c = $p->c;
    $lamanya1 = $p->lamanya1;
    $lamanya2 = $p->lamanya2;
    $lamanya3 = $p->lamanya3;
    $lamanya4 = $p->lamanya4;
    $ii       = $p->ii;
    $rudapaksa_a = $p->rudapaksa_a;
    $rudapaksa_b = $p->rudapaksa_b;
    $rudapaksa_c = $p->rudapaksa_c;
    $kelahiran_a = $p->kelahiran_a;
    $kelahiran_b = $p->kelahiran_b;
    $persalinan_a = $p->persalinan_a;
    $persalinan_b = $p->persalinan_b;
    $oprasi_a = $p->oprasi_a;
    $oprasi_b = $p->oprasi_b;
    $catatan = $p->catatan;
    $tanggal = $p->tanggal;
    $no_reg         = $pi->no_reg;
    $no_rm          = $pi->no_rm;
    if ($p->status) $ubah = "readonly";
    else $ubah = "";
    $aksi = "edit";
} else {
    $a =
        $b           =
        $c           =
        $lamanya1    =
        $lamanya2    =
        $lamanya3    =
        $lamanya4    =
        $ii          =
        $rudapaksa_a =
        $rudapaksa_b =
        $rudapaksa_c =
        $kelahiran_a =
        $kelahiran_b =
        $persalinan_a =
        $persalinan_b =
        $oprasi_a    =
        $oprasi_b    =
        $catatan     =
        $tanggal   =
        $ubah = "";
    $aksi = "simpan";
}
?>
<style>
    .dropbtn {
        color: white;
        padding: 14px, 8px, 14px, 8px;
        font-size: 14px;
        border: none;
    }

    .dropup {
        position: relative;
        display: inline-block;
    }

    .dropup-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        bottom: 31px;
        z-index: 1;
    }

    .dropup-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropup-content a:hover {
        background-color: #ccc
    }

    .dropup:hover .dropup-content {
        display: block;
    }
</style>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-body">
            <?php
            echo form_open("dokter/simpansebabkematian/" . $aksi, array("id" => "formsave", "class" => "form-horizontal"));
            ?>

            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">Nama</label>
                    <div class="col-md-2">
                        <input type="hidden" name="no_reg" value="<?php echo $no_reg ?>">
                        <input type="hidden" name="no_rm" value="<?php echo $no_rm ?>">
                        <input type="hidden" name="jenis" value="<?php echo $jenis ?>">
                        <input type="hidden" name="tanggal" value="<?php echo date("d-m-Y", strtotime($tanggal)) ?>">
                        <input type="text" name="nama_pasien" readonly autocomplete="off" class="form-control" value="<?php echo $pi->nama_pasien ?>">
                    </div>
                    <label class="col-md-2 control-label">Umur</label>
                    <div class="col-md-2">
                        <input type="text" name="umur" readonly class="form-control" value="<?php echo $umur ?>">
                    </div>
                    <label class="col-md-2 control-label">NO REG</label>
                    <div class="col-md-2 control-label">
                        <input type="text" name="no_reg" readonly class="form-control" value="<?php echo $pi->no_reg ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Ruangan</label>
                    <div class="col-md-2 control-label">
                        <input type="hidden" name="kode_ruangan" value="<?php echo $pi->kode_ruangan; ?>">
                        <input type="text" name="nama_ruangan" readonly class="form-control" value="<?php echo $pi->nama_ruangan ?>">
                    </div>
                    <label class="col-md-2 control-label">Kelas</label>
                    <div class="col-md-2 control-label">
                        <input type="hidden" name="kode_kelas" value="<?php echo $pi->kode_kelas; ?>">
                        <input type="text" name="nama_kelas" readonly class="form-control" value="<?php echo $pi->nama_kelas ?>">
                    </div>
                    <label class="col-md-2 control-label">NO RM</label>
                    <div class="col-md-2 control-label">
                        <input type="text" name="no_rm" readonly class="form-control" value="<?php echo $pi->no_rm ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">I</h3>
        </div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-4 control-label">
                        <input type="text" name="a" placeholder="a." value="<?php echo $a ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                        <label>Penyakit tersebut dalam ruang a disebabkan oleh (atau akibat dari)</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4 control-label">
                        <input type="text" name="b" placeholder="b." value="<?php echo $b ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                        <label>Penyakit tersebut dalam ruang b disebabkan oleh (atau akibat dari)</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4 control-label">
                        <input type="text" name="c" placeholder="c." value="<?php echo $c ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4 control-label">
                        <label>Lamanya (kira-kira) mulai sakit hingga meninggal dunia</label>
                        <textarea type="text" cols="4" rows="4" name="lamanya1" value="<?php echo $ubah ?>" autocomplete="off" class="form-control"><?php echo $lamanya1; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">II</h3>
        </div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-4 control-label">Penyakit penyakit lain yang berani dan mempengaruhi pula kematian itu tetapi tidak ada hubungannya dengan penyakit penyakit tersebut dalam I.a b c</label>
                    <div class="col-md-4 control-label">
                        <br>
                        <textarea type="text" cols="6" rows="4" name="ii" value="<?php echo $ubah ?>" autocomplete="off" class="form-control"><?php echo $ii; ?></textarea>
                    </div>
                    <div class="col-md-4 control-label">
                        <label>Lamanya (kira-kira) mulai sakit hingga meninggal dunia</label>
                        <textarea type="text" cols="6" rows="4" name="lamanya4" value="<?php echo $ubah ?>" autocomplete="off" class="form-control"><?php echo $lamanya4; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Keterangan khusus untuk :</h3><br>
            <h3 class="box-title">1. MATI KARENA RUDAPAKSA (Volent Death)</h3>
        </div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-4 control-label">a.Macam rudapaksa</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="rudapaksa_a" value="<?php echo $rudapaksa_a ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">b.Cara kerja rudapaksa</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="rudapaksa_b" value="<?php echo $rudapaksa_b ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">c.Sifat Jelas (kerusakan tubuh)</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="rudapaksa_c" value="<?php echo $rudapaksa_c ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="box-header with-border">
                    <h3 class="box-title">2. KELAHIRAN MATI (Stillbirth)</h3>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">a.Apakah ini janin lahir mati</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="kelahiran_a" value="<?php echo $kelahiran_a ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">b.Sebab kelahiran mati</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="kelahiran_b" value="<?php echo $kelahiran_b ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="box-header with-border">
                    <h3 class="box-title">3. PERSALINAN KEHAMILAN</h3>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">a.Apakah ini peristiwa persalinan</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="persalinan_a" value="<?php echo $persalinan_a ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">b. Apakah ini peristiwa kehamilan</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="persalinan_b" value="<?php echo $persalinan_b ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="box-header with-border">
                    <h3 class="box-title">4. OPERASI</h3>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">a. Apakah disini dilakukan operasi</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="oprasi_a" value="<?php echo $oprasi_a ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">b.Jenis operasi</label>
                    <div class="col-md-6 control-label">
                        <input type="text" name="oprasi_b" value="<?php echo $oprasi_b ?>" <?php echo $ubah; ?> autocomplete="off" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Catatan</h3>
        </div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12 control-label">
                        <textarea type="text" cols="6" rows="4" name="catatan" value="<?php echo $ubah ?>" autocomplete="off" class="form-control"><?php echo $catatan; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-box primary">
        <div class="box-body">
            <div class="pull-right">
                <!-- <?php if ($aksi == "edit") : ?>
                    <button class="cetak btn btn-success" type="button"><i class="fa fa-print"></i> Cetak</button>
                <?php endif ?> -->
                <div class="btn-group">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
                    <button class="cancel btn btn-danger" type="button"><i class="fa fa-times"></i> Cancel</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

    <div class='modal laporan_m'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="modal-header bg-orange">
                    <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;NOTIFICATION !</h4>
                </div>
                <div class='modal-body'>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-12 control-label">Laporan Operasi</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="laporan" style="max-width: 100%;height:300px;"><?php echo $laporan ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button class="simpan_laporan btn btn-sm btn-success">Simpan</button>
                    <button class="tidak_laporan btn btn-sm btn-warning">Keluar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal komplikasi_m'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="modal-header bg-orange">
                    <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;NOTIFICATION !</h4>
                </div>
                <div class='modal-body'>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-12 control-label">Komplikasi</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="komplikasi" style="max-width: 100%;height:300px;"><?php echo $komplikasi ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button class="simpan_komplikasi btn btn-sm btn-success">Simpan</button>
                    <button class="tidak_komplikasi btn btn-sm btn-warning">Keluar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal intruksi_m'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="modal-header bg-orange">
                    <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;NOTIFICATION !</h4>
                </div>
                <div class='modal-body'>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-12 control-label">Intruksi Pasca Operasi</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="intruksi" style="max-width: 100%;height:300px;"><?php echo $intruksi ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button class="simpan_intruksi btn btn-sm btn-success">Simpan</button>
                    <button class="tidak_intruksi btn btn-sm btn-warning">Keluar</button>
                </div>
            </div>
        </div>
    </div>
