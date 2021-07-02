<?php
$t1 = new DateTime('today');
$t2 = new DateTime($q->tgl_lahir);
$y  = $t1->diff($t2)->y;
$m  = $t1->diff($t2)->m;
$d  = $t1->diff($t2)->d;

list($year, $month, $day) = explode("-", $q->tgl_lahir);
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
$umur_pasien = $year_diff . " tahun " . $month_diff . " bulan " . $day_diff . " hari ";

if ($q2) {
    $nama_pasien           = $q->nama_pasien;
    $pengantar           = $q2->pengantar;
    $telepon             = $q2->telepon;
    $dikirim             = $q2->dikirim;
    $penerima            = $q2->penerima;
    $alasan              = explode(",",$q2->alasan);
    $aksi = "edit";
} else {
    $nama_pasien      = $q->nama_pasien;
    $pengantar          =
        $telepon        =
        $dikirim        =
        $penerima       =
        $alasan         =
        $alasan         = "";
    $no_reg         = $no_reg;
    $no_pasien      = $no_pasien;
    $aksi = "simpan";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/defaultTheme.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>js/select2/select2.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.fixedheadertable.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/library.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>plugins/bootstrap-typeahead/bootstrap-typeahead.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/select2/select2.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-barcode.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-qrcode.js"></script>
    <script src="<?php echo base_url(); ?>js/html2pdf.bundle.js"></script>
    <script src="<?php echo base_url(); ?>js/html2canvas.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.mask.min.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/library.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.signature.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.ui.touch-punch.min.js"></script>
    <link rel="icon" href="<?php echo base_url(); ?>img/computer.png" type="image/x-icon" />
</head>
<script>
    var mywindow;

    function openCenteredWindow(url) {
        var width = 800;
        var height = 500;
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
            var no_pasien = $("[name='no_pasien']").val();
            var jenis = "<?php echo $jenis;?>";
            var url = "<?php echo site_url('dokter/cetakrujukanpasien'); ?>/" + no_reg + "/" + no_pasien + "/" + jenis;
            openCenteredWindow(url);
        });

        getttd();
    });
</script>
<?php
if ($this->session->flashdata('message')) {
    $pesan = explode('-', $this->session->flashdata('message'));
    echo "<div class='alert alert-" . $pesan[0] . "' alert-dismissable>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
        <b>" . $pesan[1] . "</b>
        </div>";
}
?>
<div class="col-xs-12 margin">
    <div class="box box-primary">
        <div class="box-header">
            <h3>RUJUKAN PASIEN</h3>
        </div>
        <div class="box-body">
            <?php echo form_open("dokter/simpanrujukan_pasien/" . $aksi, array("class" => "form-horizontal")); ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Nama Pasien</label>
                <div class="col-sm-2">
                    <input type="hidden" name="jenis" value="<?php echo $jenis ?>">
                    <input type="text" name="nama_pasien" class="form-control" readonly value="<?php echo $q->nama_pasien ?>">
                </div>
                <label class="col-sm-2 control-label">No Reg</label>
                <div class="col-sm-2">
                    <input type="text" name="no_reg" class="form-control" readonly value="<?php echo $no_reg ?>">
                </div>
                <label class="col-sm-2 control-label">No RM</label>
                <div class="col-sm-2">
                    <input type="text" name="no_pasien" class="form-control" readonly value="<?php echo $no_pasien ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Umur Pasien</label>
                <div class="col-sm-2">
                    <input type="text" name="umur_pasien" class="form-control" readonly value="<?php echo $umur_pasien ?>">
                </div>
                <label class="col-sm-2 control-label">Ruang</label>
                <div class="col-sm-2">
                    <input type="text" name="ruang" class="form-control" readonly value="<?php echo $q1->nama_ruangan ?>">
                </div>
                <label class="col-sm-2 control-label">Kelas</label>
                <div class="col-sm-2">
                    <input type="text" name="kelas" class="form-control" readonly value="<?php echo $q1->nama_kelas ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Nama Pengantar</label>
                <div class="col-sm-4">
                    <input type="text" required name="pengantar" class="form-control" value="<?php echo $pengantar ?>" <?php echo $ubah; ?>>
                </div>
                <label class="col-sm-2 control-label">No. Telepon / HP</label>
                <div class="col-sm-4">
                    <input type="text" required name="telepon" class="form-control" value="<?php echo $telepon ?>" <?php echo $ubah; ?>>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2">
                    Kepada Yth
                </label>
                <div class="col-sm-10">
                    <textarea required class="form-control" name="dikirim" <?php echo $ubah; ?>><?php echo $dikirim ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2">Penerima yang sudah dihububngi</label>
                <div class="col-sm-10">
                    <input type="text" required name="penerima" class="form-control" value="<?php echo $penerima ?>" <?php echo $ubah; ?>>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2" value="<?php echo $alasan ?>" <?php echo $ubah; ?>>Alasan</label>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input type="checkbox" name="alasan1" value="Tempat Tidur Penuh" <?php echo (isset($alasan[0]) && $alasan[0] != "" ? "checked" : "");?>>
                        <label class="form-check-label">Tempat Tidur Penuh</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="alasan2" value="Sesuai Permintaan Pasien / Keluarga" <?php echo (isset($alasan[1]) && $alasan[1] != "" ? "checked" : "");?>>
                        <label class="form-check-label">Sesuai Permintaan Pasien / Keluarga</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="alasan3" value="Fasilitas Tidak Tersedia" <?php echo (isset($alasan[2]) && $alasan[2] != "" ? "checked" : "");?>>
                        <label class="form-check-label">Fasilitas Tidak Tersedia</label>
                    </div>
                    <div class="form-check">
                        <div class="col-sm-8">
                            <input type="text" name="alasan4" placeholder="Lain-lain" class="form-control" <?php echo (isset($alasan[3]) && $alasan[3] != "" ? "value='".$alasan[3]."'" : "");?>>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn btn-success" type="submit"></i> Simpan</button>
                        <button class="cetak btn btn-primary" type="button"><i class="fa fa-print"></i> Cetak</button>

                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <style type="text/css">
        #signature {
            width: 100%;
            height: 300px;
            border: 1px solid black;
        }

        #signature2 {
            width: 100%;
            height: 300px;
            border: 1px solid black;
        }
    </style>
