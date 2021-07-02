
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
            var no_pasien = $("input[name='no_pasien']").val();
            var kode_tindakan = $("[name='tindakan']").val();
            var url = "<?php echo site_url('gizi/cetak');?>/"+no_reg+"/"+no_pasien+"/"+kode_tindakan;
            openCenteredWindow(url);
        });
        $("select[name='tindakan']").change(function(){
            var no_reg = $("input[name='no_reg']").val();
            var no_pasien = $("input[name='no_pasien']").val();
            var tindakan = $(this).val();
            window.location = "<?php echo site_url('pendaftaran/ekspertisigizi_ralan');?>/"+no_pasien+"/"+no_reg+"/"+tindakan;
        });
    });
</script>
<?php
    if ($q) {
        $no_foto                = $q->no_foto;
        $action                 = "edit";
    } else {
        $no_foto            =
        $action = "simpan";
    }
    if ($k2){
        $nofoto = $k2->nofoto;
        $ukuranfoto = $k2->ukuranfoto;
        $dokter = $k2->kode_petugas;
        $petugas_gizi = $k2->analys;
        $dokter_pengirim = $k2->dokter_pengirim;
    } else {
        $nofoto = $ukuranfoto = $dokter = $petugas_gizi = $dokter_pengirim = "";
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
            <input type="hidden" name="jenis" value="<?php echo $row->jenis;?>">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">No. Reg</label>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control" name='no_reg'  readonly value="<?php echo $no_reg;?>"/>
                    </div>
                    <label class="col-md-1 control-label">No. RM</label>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control" name='no_pasien' readonly value="<?php echo $no_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Nama Pasien</label>
                    <div class="col-md-3">
                        <input type="text" readonly class="form-control" name='nama_pasien'  readonly value="<?php echo $row->nama_pasien;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Dokter</label>
                    <div class="col-md-2">
                        <select class="form-control" name="dokter" disabled="">
                            <?php
                                foreach ($d->result() as $dk) {
                                    echo "
                                        <option value='".$dk->id_dokter."' ".($dk->id_dokter==$dokter ? "selected" : "")." data-id='".$dk->gizi."'>".$dk->nama_dokter."</option>
                                    ";
                                }
                            ?>
                        </select>
                        <input type="hidden" readonly class="form-control" name='gizi'/>
                    </div>
                    <label class="col-md-1 control-label">Petugas Gizi</label>
                    <div class="col-md-2">
                        <select class="form-control" name="petugas_gizi" disabled>
                            <?php
                                foreach ($r->result() as $rg) {
                                    echo "
                                        <option value='".$rg->nip."' ".($rg->nip==$petugas_gizi ? "selected" : "").">".$rg->nama."</option>
                                    ";
                                }
                            ?>
                        </select>
                    </div>
                    <label class="col-md-2 control-label">Dokter Pengirim</label>
                    <div class="col-md-3">
                        <select class="form-control" name="dokter_pengirim" disabled>
                            <?php
                                foreach ($d1->result() as $dk1) {
                                    echo "
                                        <option value='".$dk1->id_dokter."' ".($dk1->id_dokter==$dokter_pengirim ? "selected" : "").">".$dk1->nama_dokter."</option>
                                    ";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Ukuran</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" disabled name='ukuran_foto' value="<?php echo $ukuranfoto ?>" />
                    </div>
                    <label class="col-md-1 control-label">Diagnosa Medis</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" disabled name='no_foto' value="<?php echo $nofoto ?>" />
                    </div>
                    <label class="col-md-2 control-label">Tindakan</label>
                    <div class="col-md-3">
                        <select class="form-control" name="tindakan">
                            <option value="">-----</option>
                            <?php
                                foreach ($k->result() as $kas) {
                                    echo "
                                        <option value='".$kas->kode_tindakan."' ".($kas->kode_tindakan==$kode_tindakan ? "selected" : "").">".$kas->nama_tindakan."</option>
                                    ";
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <table class="table table-bordered table-hover " id="myTable" >
                <thead>
                    <tr class="bg-navy">
                        <th width="10" class='text-center'>No</th>
                        <th width="100px" class="text-center">Asuhan</th>
                        <th class="text-center">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0;
                        $kode_judul = $kode_tindakan = "";

                        $tgl1_print = $tgl2_print = "";
                        foreach($a->result() as $data){
                            $i++;
                            echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td>".$data->asuhan."</td>";
                            echo "<td>
                                    <textarea class='form-control' name='hasil_pemeriksaan[".$data->kode."]'>".(isset($hasil_pemeriksaan[$data->kode]) ? $hasil_pemeriksaan[$data->kode]->hasil_pemeriksaan : "")."</textarea></td>";
                            echo "</tr>";
                        }
                        $tgl1_print = $tgl1_print=="" ? date("d-m-Y") : $tgl1_print;
                        $tgl2_print = $tgl2_print=="" ? date("d-m-Y") : $tgl2_print;
                    ?>
                </tbody>
            </table>
        <div class="box-header with-border">
            <div class="pull-right">
                <div class="btn-group">
                    <button class="back btn btn-warning" type="button"><i class="fa fa-arrow-left"></i> Back</button>
                    <button class="cetak btn btn-primary" type="button"><i class="fa fa-print"></i> Cetak</button>
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
    #hp{
    
    }
</style>