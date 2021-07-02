<link rel="stylesheet" href="<?php echo base_url();?>css/print.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<style>
    .dropbtn {
      
      color: white;
      padding: 14px,8px,14px,8px;
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

    .dropup-content a:hover {background-color: #ccc}

    .dropup:hover .dropup-content {
      display: block;
    }

    /*.dropup:hover .dropbtn {
      background-color: #2980B9;
    }*/
</style>
<script>
var mywindow;
    function openCenteredWindow(url) {
        var width = 1000;
        var height = 500;
        var left = parseInt((screen.availWidth/2) - (width/2));
        var top = parseInt((screen.availHeight/2) - (height/2));
        var windowFeatures = "width=" + width + ",height=" + height +
                             ",status,resizable,left=" + left + ",top=" + top +
                             ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow = window.open(url, "subWind", windowFeatures);
    }
    function pencarian(){
        var cari_no = $("[name='cari_no']").val();
        var status_pasien = $("[name='status_pasien']").val();
        $.ajax({
            type  : "POST",
            data  : {cari_no:cari_no,status_pasien: status_pasien},
            url   : "<?php echo site_url('pendaftaran/getcaripasien_ralan');?>",
            success : function(result){
                location.reload();
                // window.location = "<?php echo site_url('pendaftaran/rawat_jalan');?>";
            },
            error: function(result){
                alert(result);
            }
        });
    }
    $(document).ready(function(e){
        $(".upload").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/formuploadpdf_ralan');?>/"+id;
            window.location = url;
            return false; 
        });
        $(".search").click(function(){
            var poli_kode = $("[name='poli_kode']").val();
            var poliklinik = $("[name='poliklinik']").val();
            var kode_dokter = $("[name='kode_dokter']").val();
            var dokter = $("[name='dokter']").val();
            var tgl1 = $("[name='tgl1']").val();
            var tgl2 = $("[name='tgl2']").val();
            var status_pasien = $("select[name='status_pasien']").val();
            var arrayData = {poli_kode: poli_kode, poliklinik: poliklinik,kode_dokter: kode_dokter,dokter: dokter,tgl1: tgl1,tgl2: tgl2,status_pasien: status_pasien};
            $.ajax({
                url: "<?php echo site_url('pendaftaran/search_ralan');?>", 
                type: 'POST', 
                data: arrayData, 
                success: function(){
                    location.reload();
                }
            });
        });
        var formattgl = "dd-mm-yy";
        $("input[name='tgl1']").datepicker({
            dateFormat : formattgl,
        });
            $("input[name='tgl2']").datepicker({
            dateFormat : formattgl,
        });
        $(".reset").click(function(){
            var url = "<?php echo site_url('pendaftaran/reset_ralan');?>";
            window.location = url;
            return false;
        });

        $(".laporan_mata").click(function(){
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('pendaftaran/cetak_mata')?>/"+no_reg;
            openCenteredWindow(url);
            return false;
        });
        $(".laporan_operasi").click(function(){
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('pendaftaran/cetak_operasi')?>/"+no_reg;
            openCenteredWindow(url);
            return false;
        });
        $('.pdf').click(function(){
            var no_sep = $(".bg-gray").attr("no_sep");
            if (no_sep==""){
                alert("Pasien belum memiliki SEP");
            } else {
                var url = "<?php echo site_url('grouper/claimprint_ralan');?>/"+no_sep;
                openCenteredWindow(url);
            }
        });
        $(".cetak_barcode").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetakbarcode');?>/"+id;
            openCenteredWindow(url);
            return false;
        });
        $(".cari_data").click(function(){
            var url = "<?php echo site_url('pendaftaran');?>";
            window.location = url;
            return false; 
        });
        $('#myTable').fixedHeaderTable({ height: '450', altClass: 'odd', footer: true});
        $("tr#data:first").addClass("bg-gray");
        $("table tr#data ").click(function(){
            $("table tr#data ").removeClass("bg-gray");
            $(this).addClass("bg-gray");
        });
        $(".add").click(function(){
            window.location = "<?php echo site_url('pendaftaran/addpasienbaru/y/y')?>";
            return false;
        });
        $(".edit").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/addpasienbaru/n/n/n')?>/"+id;
            return false;
        });
        $(".cetak_rekmed").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetak_rekmed')?>/"+id;
            openCenteredWindow(url);
        });
        $(".hapus").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/hapuspasien')?>/"+id;
            return false;
        });
        $(".cari_no").click(function(){
            $(".modal_cari_no").modal("show");
            $("[name='cari_no']").focus();
            return false;
        });
        $(".share").click(function(){
            var no_pasien = $(".bg-gray").attr("no_pasien");
            $(".modal_share").modal("show");
            $("[name='no_reg_share']").focus();
            $("input[name='no_pasien_share']").val(no_pasien);
            return false;
        });
        $(".cari_nama").click(function(){
            $(".modal_cari_nama").modal("show");
            $("[name='cari_nama']").focus();
            return false;
        });
        $(".cari_noreg").click(function(){
            $(".modal_cari_noreg").modal("show");
            $("[name='cari_noreg']").focus();
            return false;
        });
        $("[name='cari_nama'], [name='cari_no'], [name='cari_noreg']").keyup(function(e){
            if (e.keyCode==13) pencarian();
        });
        $("[name='status_pasien']").change(function(){
            pencarian();
        });
        $(".tmb_cari_nama, .tmb_cari_no, .tmb_cari_noreg").click(function(){
            pencarian();
            return false;
        });
        $(".poli").click(function(){
            var url = "<?php echo site_url('pendaftaran/pilihpoli');?>";
            openCenteredWindow(url);
            return false;
        });
        $(".dokter").click(function(){
            var kode_poli = $("input[name='poli_kode']").val()
            var url = "<?php echo site_url('pendaftaran/pilihdokterpoli');?>/"+kode_poli;
            openCenteredWindow(url);
            return false;
        });
        $(".konsul").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/konsul')?>/"+id;
            return false;
        });
        $(".sep").click(function(){
            var id = $(".bg-gray").attr("href");
            var no_bpjs = $(".bg-gray").attr("no_bpjs");
            window.location = "<?php echo site_url('sep/formsep')?>/"+id+"/"+no_bpjs;
            return false;
        });
        $(".tindakan").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/tindakan')?>/"+id;
            return false;
        });
        $(".indeks").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/indeks')?>/"+id;
            return false;
        });
        $(".cari_noreg_share").click(function(){
            var no_pasien = "-";
            var no_reg = $("input[name='no_reg_share']").val();
            window.location = "<?php echo site_url('pendaftaran/updatetanggal')?>/"+no_pasien+"/"+no_reg;
            return false;
        });
        $(".terima").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/terima')?>/"+id;
            return false;
        });
        $(".terima_pasien").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/terima_pasien')?>/"+id;
            return false;
        });
        $(".pulang").click(function(){
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            $(".modal-pulang").modal("show");
            $("input[name='no_pasien_pulang']").val(no_pasien);
            $("input[name='no_reg_pulang']").val(no_reg);
            return false;
        });
        $(".simpan_pulang").click(function(){
            var no_pasien = $("input[name='no_pasien_pulang']").val();
            var no_reg = $("input[name='no_reg_pulang']").val();
            var status_pulang = $("select[name='status_pulang']").val();
            var keadaan_pulang = $("select[name='keadaan_pulang']").val();
            window.location = "<?php echo site_url('pendaftaran/pulang')?>/"+no_pasien+"/"+no_reg+"/"+keadaan_pulang+"/"+status_pulang;
            return false;
        });
        $(".next").click(function(){
            $("select[name='status_pulang']").addClass("hide");
            $("select[name='keadaan_pulang']").removeClass("hide");
            $(this).addClass("hide");
            $(".simpan_pulang").removeClass("hide");
        });
        $(".gudang").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/gudang')?>/"+id;
            return false;
        });
        $(".layani").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/layani')?>/"+id;
            return false;
        });
        $(".rtpelayanan").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/rtpelayanan');?>/"+id;
            openCenteredWindow(url);
        });
        $(".rttunggu").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/rttunggu');?>/"+id;
            openCenteredWindow(url);
        });
        $(".rt_poliklinik").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/rt_poliklinik');?>/"+id;
            openCenteredWindow(url);
        });
        $(".rtrm").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/rtrm');?>/"+id;
            openCenteredWindow(url);
        });
        $(".laporan_tindakan").click(function(){
            var id = $(".bg-gray").attr("href");
            var poli = $(".bg-gray").attr("poli");
            window.location = "<?php echo site_url('pendaftaran/laporan_tindakan');?>/"+id+"/"+poli;
            return false;
        });
        $(".ekspertisiradiologi").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            window.location ="<?php echo site_url('pendaftaran/ekspertisiradiologi_ralan');?>/"+no_rm;
            return false;
        });
        $(".ekspertisilab").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            window.location ="<?php echo site_url('pendaftaran/ekspertisilab_ralan');?>/"+no_rm;
            return false;
        });
        $(".ekspertisipa").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            window.location ="<?php echo site_url('pendaftaran/ekspertisipa_ralan');?>/"+no_rm;
            return false;
        });
        $(".ekspertisigizi").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            window.location ="<?php echo site_url('pendaftaran/ekspertisigizi_ralan');?>/"+no_rm;
            return false;
        });
        $(".obat").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            window.location ="<?php echo site_url('pendaftaran/apotek_ralan');?>/"+no_rm;
            return false;
        });
        $(".view_pembayaran").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/viewpembayaran_ralan')?>/"+no_rm;
            return false;
        });

        $(".batal").click(function(){
            $(".rejected").show();
        });
        // $(".reject").click(function(){
        //     $(".rejected").show();
        // });
        $(".tidak_approved").click(function(){
            $(".approved").hide();
        });
        $(".tidak_rejected").click(function(){
            $(".rejected").hide();
        });
        $(".ya_rejected").click(function(){
            var alasan = $("[name='alasan']").val();
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/batal');?>/"+id+"/"+alasan;
            window.location = url;
            return false; 
        });
    });
</script>
<div class='modal rejected'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-red"><h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;NOTIFICATION</h4></div>
            <div class='modal-body'>
                <p>Yakin akan Batal ?</p>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alasan Batal</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="alasan"/></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button class="ya_rejected btn btn-sm btn-danger">Ya</button>
                <button class="tidak_rejected btn btn-sm btn-success">Tidak</button>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12">
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
        <div class="box-header">
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover " id="myTable" >
                <thead>
                    <tr class="bg-navy">
                        <th class='text-center'>No. Antrian</th>
                        <th class='text-center'>Nomor RM</th>
                        <th class='text-center'>Nomor REG</th>
                        <th>Nama</th>
                        <th class='text-center'>Poli Asal</th>
                        <th class='text-center'>Poli Tujuan</th>
                        <th class='text-center'>Status Pasien</th>
                        <th class='text-center'>Jenis Pasien</th>
                        <th class='text-center'>Golongan Pasien</th>
                        <th class='text-center'>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    
                    $no_kk = '';
                    foreach ($q3->result() as $row){
                        if ($row->layan=="0") {
                                $layan = "<label class='label label-primary'>Layan</label>";
                        }else if($row->layan=="1") {
                                $layan = "<label class='label label-success'>Layan</label>";
                        }else{
                                $layan = "<label class='label label-danger'>Batal</label>";
                        }
                        echo "<tr id=data href='".$row->no_pasien."/".$row->no_reg."' no_sep='".$row->no_sjp."' no_pasien='".$row->no_pasien."' no_reg='".$row->no_reg."' no_bpjs='".$row->no_bpjs."' poli='".$row->tujuan_poli."'>" ;
                        echo "<td class='text-center'>".$row->no_antrian."</td>";
                        echo "<td class='text-center'>".$row->no_pasien."</td>";
                        echo "<td class='text-center'>".$row->no_reg."</td>";
                        echo "<td>".$row->nama_pasien."</td>";
                        echo "<td>".$row->poli_asal."</td>";
                        echo "<td>".$row->poli_tujuan."</td>";
                        echo "<td>".$row->status_pasien."</td>";
                        echo "<td>".$row->jenis."</td>";
                        echo "<td>".$row->gol_pasien."</td>";
                        echo "<td>".$layan."</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr class="bg-navy">
                        <th colspan="7">Jumlah Pasien : <?php echo $total_rows;?></th>
                        <th>Layan : <?php echo $jlayan;?></th>
                        <th>Batal : <?php echo $jbatal;?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="box-footer">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-1">
                        Poliklinik
                    </label>
                    <div class="col-md-2">
                        <input type="text" name="poliklinik" class="form-control" readonly value="<?php echo $this->session->userdata("poliklinik") ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="poli_kode" class="form-control" readonly value="<?php echo $this->session->userdata("poli_kode") ?>">
                    </div>
                    <div class="col-md-1">
                        <button class="poli btn btn-primary">...</button>
                    </div>
                    <label class="col-md-1">
                        Status Pasien
                    </label>
                    <div class="col-md-2">
                        <select name="status_pasien" class="form-control input-sm">
                            <option value="ALL" <?php echo ($this->session->userdata("status_pasien")=="ALL" ? "selected" : "");?>>ALL</option>
                            <option value="BARU" <?php echo ($this->session->userdata("status_pasien")=="BARU" ? "selected" : "");?>>BARU</option>
                            <option value="LAMA" <?php echo ($this->session->userdata("status_pasien")=="LAMA" ? "selected" : "");?>>LAMA</option>
                        </select>
                    </div>
                    <div class="col-md-3 pull-right">
                        <?php echo $this->pagination->create_links();?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1">
                        Tanggal
                    </label>
                    <div class="col-md-2">
                        <input type="text" name="tgl1" class="form-control" value="<?php echo $this->session->userdata("tgl1") ?>" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="tgl2" class="form-control" value="<?php echo $this->session->userdata("tgl2") ?>" autocomplete="off">
                    </div>
                    <div class="col-md-1">
                        <button class="search btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                    <label class="col-md-1">
                        Dokter
                    </label>
                    <div class="col-md-2">
                        <input type="text" name="dokter" class="form-control" readonly value="<?php echo $this->session->userdata("dokter") ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="kode_dokter" class="form-control" readonly value="<?php echo $this->session->userdata("kode_dokter") ?>">
                    </div>
                    <div class="col-md-1">
                        <div class="pull-right">
                            <button class="dokter btn btn-primary">...</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <div class="dropup">
                            <button class="dropbtn btn btn-success">Respon Time</button>
                            <div class="dropup-content">
                                <a class="rt_poliklinik">Klinik</a>
                                <a class="rtpelayanan">Pelayanan</a>
                                <a class="rtrm">RM</a>
                                <a class="rttunggu">Tunggu</a>
                            </div>
                        </div>
                        <div class="dropup">
                            <button class="dropbtn btn btn-danger">Klinik</button>
                            <div class="dropup-content">
                                <a class="terima">Terima RM</a>
                                <a class="terima_pasien">Terima Pasien</a>
                                <a class="layani">Layani</a>
                                <a class="konsul">Konsul</a>
                                <a class="tindakan">Tindakan</a>
                                <a class="pulang">Pulang</a>
                                <a class="batal">Batal</a>
                            </div>
                        </div>
                        <div class="dropup">
                            <button class="dropbtn btn btn-primary">Rekam Medis</button>
                            <div class="dropup-content">
                                <a class="cetak_rekmed">Cetak</a>
                                <a class="cari_data">Cari Data</a>
                                <a class="edit">Lengkapi Data</a>
                                <a class="sep">Buat SEP</a>
                                <a class="cetak_barcode">Barcode</a>
                                <a class="share">Share</a>
                                <a class="indeks">Indeks</a>
                                <a class="gudang">Gudang</a>
                            </div>
                        </div>
                        <div class="dropup">
                            <button class="dropbtn btn btn-warning">Ekspertisi</button>
                            <div class="dropup-content">
                            <a class="obat" > Obat</a>
                            <a class="view_pembayaran"> Billing</a>
                            <a class="ekspertisigizi">Gizi</a>
                            <a class="ekspertisipa">PA</a>
                            <a class="ekspertisilab">Lab</a>
                            <a class="ekspertisiradiologi">Radiologi</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <button class="laporan_tindakan btn btn-sm btn-primary" type="button"> Laporan Tindakan</button>
                            <button class="laporan_operasi btn btn-sm btn-warning" type="button">Laporan Operai</button>
                            <button class="laporan_mata btn btn-sm btn-primary" type="button">Laporan Ops Mata</button>
                            <button class="upload btn btn-md btn-primary" type="button"> PDF</button>
                            <button class="pdf btn btn-md btn-success" type="button"> LIP</button>
                            <button class="reset btn btn-md btn-warning"> Reset</button>
                            <button class="cari_no btn-md btn btn-info" type="button"> Cari</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='modal modal_share no-print' role="dialog">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Scan Barcode</h4>
            </div>
            <div class='modal-body'>
                <form>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="no_reg_share" placeholder="No Reg"/>
                                    <input class="form-control" type="hidden" name="no_pasien_share"/>
                                    <span class="input-group-btn">
                                        <button class="cari_noreg_share btn btn-success" type="submit">Submit</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class='modal modal-pulang no-print' role="dialog">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Pulang</h4>
            </div>
            <div class='modal-body'>
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input type="hidden" name="no_pasien_pulang">
                                <input type="hidden" name="no_reg_pulang">
                                <select class="form-control" name="status_pulang">
                                    <?php
                                        foreach ($splg->result() as $val) {
                                            echo "
                                                <option value='".$val->id."'>".$val->keterangan."</option>
                                            ";
                                        }
                                    ?>
                                </select>
                                <select class="form-control hide" name="keadaan_pulang">
                                    <?php
                                        foreach ($kplg->result() as $val) {
                                            echo "
                                                <option value='".$val->id."'>".$val->keterangan."</option>
                                            ";
                                        }
                                    ?>
                                </select>
                                <span class="input-group-btn">
                                    <button type='button'class="next btn btn-success">Next</button>
                                    <button class="hide simpan_pulang btn btn-success">Simpan</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='modal modal_cari_no no-print' role="dialog">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Pencarian</h4>
            </div>
            <div class='modal-body'>
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input class="form-control" type="text" name="cari_no" placeholder="Nama/ No. RM/ No. Reg/ No. BPJS/ No. SEP/ NIK/ NRP"/>
                                <span class="input-group-btn">
                                    <button class="tmb_cari_no btn btn-success">Cari</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='modal modal_cari_nama no-print' role="dialog">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Pencarian</h4>
            </div>
            <div class='modal-body'>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input class="form-control" type="text" name="cari_nama"/>
                                <span class="input-group-btn">
                                    <button class="tmb_cari_nama btn btn-success">Cari</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='modal modal_cari_noreg no-print' role="dialog">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Pencarian</h4>
            </div>
            <div class='modal-body'>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">No Reg</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input class="form-control" type="text" name="cari_noreg"/>
                                <span class="input-group-btn">
                                    <button class="tmb_cari_noreg btn btn-success">Cari</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .konten_print td{
        font-family: antoniobold;
        text-transform: uppercase;
    }
</style>