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
    $(document).ready(function(){
        $('.back').click(function(){
            window.location = "<?php echo site_url('dokter/rawat_inapdokterigd');?>";
        });
        $('.cetak').click(function(){
            var no_reg= $("[name='no_reg']").val();
            var url = "<?php echo site_url('lab/cetak');?>/"+no_reg;
            openCenteredWindow(url);
        });
    });
</script>
<?php
    if ($q) {
        $action     = "edit";
    } else {
        $action = "simpan";
    }
?>
<div class="col-md-12">
    <?php
        if($this->session->flashdata('message')){

           $pesan=explode('-', $this->session->flashdata('message'));
            echo "<div class='alert alert-".$pesan[0]."' alert-dismissable>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <b>".$pesan[1]."</b>
            </div>";
        }

    ?>
    <div class="box box-primary">
        <div class="box-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">No. Reg</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='no_reg' readonly value="<?php echo $no_reg;?>"/>
                    </div>
                    <label class="col-md-1 control-label">No. RM</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='no_pasien' readonly value="<?php echo $no_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Nama Pasien</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $row->nama_pasien;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Dokter</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name='dokter' readonly value="<?php echo $row->nama_dokter;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Analys</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name='analys' readonly value="<?php echo $row->namaanalys;?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <table class="table table-bordered table-hover " id="myTable" >
                <thead>
                    <tr class="bg-navy">
                        <th width="10" class='text-center'>No</th>
                        <th class="text-center">Nama Tindakan</th>
                        <th width="300" class='text-center'>Jenis Pemeriksaan</th>
                        <th width="100" class='text-center'>Hasil</th>
                        <th width="100" class='text-center'>Satuan</th>
                        <th width="200" class='text-center'>Nilai Rujukan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0;
                        $kode_judul = $kode_tindakan = "";
                        foreach($k->result() as $data){
                            if ($kode_judul!=$data->kode_judul) {
                                echo "<tr class='bg-orange'>";
                                echo "<td colspan='6'>".$data->judul."</td>";
                                $kode_judul = $data->kode_judul;
                                $i = 0;
                            }
                            if ($data->jenis_kelamin=="L") {
                                $rujukan = $data->pria;
                            } else {
                                $rujukan = $data->wanita;
                            }
                            
                            $i++;
                            if ($kode_tindakan!=$data->kode_tindakan){
                                $nama_tindakan = $data->nama_tindakan;
                                $kode_tindakan = $data->kode_tindakan;
                            } else {
                                $nama_tindakan = "";
                            }
                            // if ($data->no_urut == "59") {
                            //     $nama_tindakan = "Sediment";   
                            // }
                            echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td>".$nama_tindakan."</td>";
                            echo "<td>".$data->nama."</td>";
                            echo "<td>".(isset($hasil[$data->kode]) ? $hasil[$data->kode]->hasil : "")."
                                    ";
                            echo "<td>".$data->satuan."</td>";
                            echo "<td>".$rujukan."</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <div class="btn-group">
                    <button class="back btn btn-warning" type="button"><i class="fa fa-arrow-left"></i> Back</button>
                    <button class="cetak btn btn-primary" type="button"><i class="fa fa-print"></i> Cetak</button>
                </div>
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