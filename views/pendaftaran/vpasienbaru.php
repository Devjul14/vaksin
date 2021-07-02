<script src="<?php echo base_url();?>js/jquery.signature.js"></script>
<!-- <script src="<?php echo base_url();?>js/jquery.ui.touch-punch.min.js"></script> -->
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
    $(document).ajaxStart(function () {
        $('.loading').show();
    }).ajaxStop(function () {
        $('.loading').hide();
    });
    $(document).ready(function(e){
        $('.resume').click(function(){
            var no_rm = $(".bg-gray").attr("href");
            $(".modalresume").modal("show");
            var html = "";
            $(".listresume").html(html);
            $.ajax({
                url : "<?php echo base_url();?>pendaftaran/resume",
                method : "POST",
                data : {no_rm: no_rm},
                success: function(data){
                    console.log(data);
                    data = JSON.parse(data);
                    $.each(data["ralan"],function(key,val){
                        var no = key+1;
                        html += "<tr>";
                        html += "<td>"+(no)+"</td>";
                        html += "<td>"+val.tanggal+"</td>";
                        html += "<td>"+val.nama_poli+"</td>";
                        html += "<td>"+(val.o==undefined ? "-" : val.o)+"</td>";
                        html += "<td>";
                        html += "<ul style='margin-left:-20px'>";
                        if (data["terapi"][val.no_reg]!=undefined){
                            $.each(data["terapi"][val.no_reg],function(key1,val1){
                                html += "<li>"+val1.nama_obat+" "+val1.qty+" "+val1.satuan+" | "+(val1.aturan_pakai==null ? "-" : val1.aturan_pakai)+"</li>";
                            });
                        } else {
                            html += "-";
                        }
                        html += "</ul>";
                        html += "</td>";
                        html += "<td>"+(val.riwayat_alergi==undefined ? "-" : val.riwayat_alergi)+"</td>";
                        html += "<td>";
                        html += "<ul>";
                        if (data["kasir"][val.no_reg]!=undefined){
                            var koma = "";
                            $.each(data["kasir"][val.no_reg],function(key1,val1){
                                if (val1.nama_tindakan1!=null){
                                    nama_tindakan = val1.nama_tindakan1;
                                } else
                                if (val1.nama_tindakan2!=null){
                                    nama_tindakan = val1.nama_tindakan2;
                                } else {
                                    nama_tindakan = val1.nama_tindakan3;
                                }
                                if (nama_tindakan!='' && nama_tindakan!='PEMERIKSAAN DOKTER'){
                                    html += "<li>"+nama_tindakan+"</li>";
                                }
                            });
                        } else {
                            html += "-";
                        }
                        html += "</ul>";
                        html += "</td>";
                        html += "<td>"+val.nama_dokter+"</td>";
                        html += "<td>";
                        if (data["grouper_icd9"][val.no_reg]!=undefined){
                            var koma = "";
                            $.each(data["grouper_icd9"][val.no_reg],function(key1,val1){
                                html += koma+val1.kode;
                                koma = ", ";
                            });
                        }
                        if (data["grouper_icd10"][val.no_reg]!=undefined){
                            var koma = "";
                            $.each(data["grouper_icd10"][val.no_reg],function(key1,val1){
                                html += koma+val1.kode;
                                koma = ", ";
                            });
                        }
                        if (data["grouper_icd9"][val.no_reg]==undefined && data["grouper_icd10"][val.no_reg]==undefined){
                            html += "-";
                        }
                        html += "</td>";
                        html += "</tr>";
                    });
                    $(".listresume").html(html);
                }
            });

        });
        $(".cetak").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetakpasien');?>/"+id;
            openCenteredWindow(url)
        });
        $(".cetakresume").click(function(){
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetakresume');?>/"+id;
            openCenteredWindow(url)
        });
        $('#myTable').fixedHeaderTable({ height: '450', altClass: 'odd', footer: true});
        $("tr#data:first").addClass("bg-gray");
        $("table tr#data ").click(function(){
            $("table tr#data ").removeClass("bg-gray");
            $(this).addClass("bg-gray");
        });
        var formattgl = "dd-mm-yy";
        var formattgl1 = "yy-mm-dd";
        $("input[name='tgl1']").datepicker({
            dateFormat : formattgl,
        });
            $("input[name='tgl2']").datepicker({
            dateFormat : formattgl,
        });
        // $("input[name='tanggal_pinjam']").datepicker({
        //     dateFormat : formattgl1,
        // }).datepicker("setDate", new Date());;
        $(".add").click(function(){
            window.location = "<?php echo site_url('pendaftaran/addpasienbaru')?>";
            return false;
        });
        $(".edit").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/addpasienbaru')?>/"+id;
            return false;
        });
        $(".ttd").click(function(){
            $(".modalttd").modal("show");
            var no_rm = $(".bg-gray").attr("href");
            // $('#signature').draggable();
            $("[name='no_rm']").val(no_rm);
            $("#signature").signature({syncField: '#signatureJSON'});
            $('#signature').signature('option', 'syncFormat', "PNG");
            getttd();
        });
        $(".retensi").click(function(){
            $(".modalretensi").show();
        });
        $(".tidak").click(function(){
            $(".modal").hide();
        });
        $(".ya").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('retensi/formretensi')?>/"+id;
            return false;
        });
        $(".rjalan").click(function(){
            var id = $(".bg-gray").attr("href");
            var status = $(".bg-gray").attr("status_pinjam");
            if(status == 1){
                alert("No RM Sedang Dipinjam")
            } else {
                window.location ="<?php echo site_url('pendaftaran/viewrjalan');?>/"+id;
            }

            // openCenteredWindow(url);
            return false;
        });
        $('.clear').click(function() {
            $('#signature').signature('clear');
        });
        $(".ugd").click(function(){
            var id = $(".bg-gray").attr("href");
            var status = $(".bg-gray").attr("status_pinjam");
            if(status == 1){
                alert("No RM Sedang Dipinjam")
            } else {
                window.location ="<?php echo site_url('pendaftaran/viewrjalan');?>/"+id+"/true";
            }

            // openCenteredWindow(url);
            return false;
        });
        $(".migrasi").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location ="<?php echo site_url('pendaftaran/migrasi');?>/"+id;
            // openCenteredWindow(url);
            return false;
        });
        $(".ralan_vaksin").click(function(){
            $(".modaltempatvaksin").modal("show");
            gettempatvaksin();
            return false;
        });
        $(".history").click(function(){
            var id = $(".bg-gray").attr("href");
            window.location ="<?php echo site_url('pendaftaran/viewhistory');?>/"+id;
            // openCenteredWindow(url);
            return false;
        });
        $(".cari_no").click(function(){
            $(".modal_cari_no").modal("show");
            $("[name='cari_no']").focus();
            return false;
        });
        $(".cari_nama").click(function(){
            $(".modal_cari_nama").modal("show");
            $("[name='cari_nama']").focus();
            return false;
        });
        $("[name='cari_nama']").keyup(function(e){
            if (e.keyCode==13) pencarian();
        });
        $("[name='cari_no']").keyup(function(e){
            if (e.keyCode==13) pencarian();
        });
        $(".tmb_cari_nama, .tmb_cari_no").click(function(){
            pencarian();
            return false;
        });
        $(".reset").click(function(){
            var url = "<?php echo site_url('pendaftaran/reset');?>";
            window.location = url;
        });

        $(".pinjam").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var nama = $(".bg-gray").attr("nama");
            $(".formpinjam").modal("show");
            $.ajax({
                type  : "POST",
                data  : {no_pasien:no_rm},
                url   : "<?php echo site_url('pinjam/getpasien_detail');?>",
                success : function(result){
                    var value = JSON.parse(result);
                    $(".formpinjam").modal("show");
                    if (value.status_pinjam!=null){
                        $("[name='no_pasien']").val(value.no_pasien);
                        $("[name='nama_pasien']").val(value.nama_pasien);
                        $("[name='nama_peminjam']").val(value.nama_peminjam);
                        $("[name='unit']").val(value.unit);
                        $("[name='alasan_pinjam']").val(value.alasan_pinjam);
                        $("input[name='status_pinjam']").val(value.status_pinjam);
                        $(".status_pinjam").html("<span class='label label-danger'>No Pasien sedang Pinjam</span>");
                        // $('[name=status_pinjam] option[value='+value.status_pinjam+']').prop("selected", true);
                        $("[name='tanggal_pinjam']").val(value.tanggal_pinjam);
                        // $(".simpan_pinjam").attr("disabled","disabled");
                        // $("[name='tanggal_pulang']").attr("disabled","disabled");
                        // $("[name='status_pulang']").attr("disabled","disabled");
                        // $("[name='no_surat_pulang']").attr("disabled","disabled");
                    } else {
                        // $("[name='no_surat_pulang']").val(no_reg);
                        $("[name='no_pasien']").val(no_rm);
                        $("[name='nama_pasien']").val(nama);
                    }
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $(".simpan_pinjam").click(function(){
            var no_pasien = $("input[name='no_pasien']").val();
            var tanggal_pinjam = $("[name='tanggal_pinjam']").val();
            var nama_peminjam = $("[name='nama_peminjam']").val();
            var alasan_pinjam = $("[name='alasan_pinjam']").val();
            var unit = $("[name='unit']").val();
            $.ajax({
                type  : "POST",
                data  : {no_pasien:no_pasien,tanggal_pinjam:tanggal_pinjam,nama_peminjam:nama_peminjam,alasan_pinjam:alasan_pinjam,unit:unit},
                url   : "<?php echo site_url('pinjam/simpan_pinjam');?>",
                success : function(result){
                    location.reload();
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $(".simpan_pasien").click(function(){
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var tempat_vaksin = $("[name='tempat_vaksin']").val();
            $.ajax({
                type  : "POST",
                data  : {no_pasien:no_pasien,tempat_vaksin: tempat_vaksin},
                url   : "<?php echo site_url('pendaftaran/simpan_vaksin');?>",
                success : function(result){
                    if (result=="error"){
                      alert("Pasien telah mendaftarkan diri untuk vaksinasi 1");
                    }
                    location.reload();
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $(".selesai_pinjam").click(function(){
            var no_pasien = $("input[name='no_pasien']").val();
            $.ajax({
                type  : "POST",
                data  : {no_pasien:no_pasien},
                url   : "<?php echo site_url('pinjam/selesai_pinjam');?>",
                success : function(result){
                    location.reload();
                },
                error: function(result){
                    alert(result);
                }
            });
        });
    });
    $(document).keyup(function(e){
        if (e.keyCode==82 && e.altKey){
            $(".reset").click();
        }
        // if (e.keyCode==78){
        //     $(".cari_nama").click();
        // }
        // if (e.keyCode==82 && !e.altKey){
        //     $(".cari_no").click();
        // }
    })
    function pencarian(){
        var cari_no = $("[name='cari_no']").val();
        var cari_nama = $("[name='cari_nama']").val();
        $.ajax({
            type  : "POST",
            data  : {cari_no:cari_no,cari_nama:cari_nama},
            url   : "<?php echo site_url('pendaftaran/getcaripasien');?>",
            success : function(result){
                window.location = "<?php echo site_url('pendaftaran');?>";
            },
            error: function(result){
                alert(result);
            }
        });
    }
    function getttd(){
        var no_rm = $(".bg-gray").attr("href");
        $.ajax({
            type  : "POST",
            data  : {no_pasien:no_rm},
            url   : "<?php echo site_url('pendaftaran/getttd');?>",
            success : function(result){
                if (result!=""){
                    $("[name='ttd']").val(result);
                    $('#signature').signature('enable').signature('draw', $("#signatureJSON").val());
                }
            },
            error: function(result){
                alert(result);
            }
        });
    }
    function gettempatvaksin(){
      $.ajax({
          url: "<?php echo site_url('vaksinasi/gettempatvaksin')?>",
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='tempat_vaksin']").html('').select2({data:row,placeholder:"Pilih Tempat Vaksin"});
          }
      });
    }
</script>
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
        <div class="box-body">
            <table class="table table-bordered table-hover " id="myTable" >
                <thead>
                    <tr class="bg-navy">
                        <th width="15%" class='text-center'>Nomor RM</th>
                        <th>Nama</th>
                        <th class='text-center'>Alamat</th>
                        <th class='text-center'>NIK</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $no_kk = '';
                    foreach ($q3->result() as $row){
                        echo "<tr id=data href='".$row->no_pasien."' nama='".$row->nama_pasien."'>";
                        echo "<td class='text-center'>".$row->no_pasien."</td>";
                        echo "<td>".$row->nama_pasien."</td>";
                        echo "<td>".$row->alamat."</td>";
                        echo "<td>".$row->nik."</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <div class="pull-left">
                <div class="btn-group">
                    <button class="add btn btn-primary" type="button" ><i class="fa fa-plus"></i> Tambah</button>
                    <button class="edit btn btn-warning" type="button"><i class="fa fa-edit"></i> Edit</button>
                </div>
                <div class="btn-group">
                    <button class="cari_no btn btn-primary" type="button"> Cari</button>
                    <button class="reset btn btn-success" type="button"> Reset</button>
                </div>
                <div class="btn-group">
                    <button class="ralan_vaksin btn btn-info" type="button"> Vaksinasi 1</button>
                </div>
            </div>
            <div class='pull-right'>
                <?php echo $this->pagination->create_links();?>
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
                                <input class="form-control" type="text" name="cari_no" placeholder="Nama/ No. RM/ No. Reg/ No. BPJS/ NIK/ NRP" />
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
<div class="formpinjam modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Pinjam No RM <b><span class="norm"></span></b></h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label">No Pasien</label>
                        <div class="col-md-8">
                            <input type="text" name="no_pasien" readonly class="form-control" autocomplete="off">
                        </div>
                    </div>
            <!--         <div class="form-group">
                        <label class="col-md-4 control-label">Status Pinjam</label>
                        <div class="col-md-8">
                            <input type="text" name="status_pinjam" readonly class="form-control" autocomplete="off">
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">Nama Pasien</label>
                        <div class="col-md-8">
                            <input type="text" name="nama_pasien" readonly class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Nama Peminjam</label>
                        <div class="col-md-8">
                            <input type="text" name="nama_peminjam" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Unit</label>
                        <div class="col-md-8">
                            <input type="text" name="unit" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-4 control-label">Tanggal pinjam</label>
                        <div class="col-md-8">
                            <input type="text" name="tanggal_pinjam" class="form-control" autocomplete="off">
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">Alasan pinjam</label>
                        <div class="col-md-8">
                            <input type="text" name="alasan_pinjam" class="form-control" autocomplete="off">
                            <p class="status_pinjam"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="selesai_pinjam btn btn-danger">Selesai Pinjam</button>
                <button class="simpan_pinjam btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class='modal modalretensi'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange"><h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;NOTIFICATION</h4></div>
            <div class='modal-body'>Yakin data pasien akan di retensi ?</div>
            <div class='modal-footer'>
                <button class="ya btn btn-sm btn-danger">Ya</button>
                <button class="tidak btn btn-sm btn-success">Tidak</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modaltempatvaksin">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <i class="icon fa fa-warning"></i>&nbsp;&nbsp;Tempat Vaksin
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class='modal-body'>
              <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-3 control-label">Tempat Vaksin</label>
                    <div class="col-md-9">
                        <select type="text" class="form-control" required name="tempat_vaksin" style="width:100%"></select>
                    </div>
                </div>
              </div>
            </div>
            <div class='modal-footer'>
                <button class="simpan_pasien btn btn-sm btn-danger">Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modalresume" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                Resume Pasien Rawat Jalan Berkelanjutan
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right">
                            <button type="button" class="cetakresume btn btn-sm btn-success"><i class="fa fa-print"></i>&nbsp;&nbsp;Cetak</button>
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-navy">
                                <th style="vertical-align: middle">No</th>
                                <th style="vertical-align: middle">Tgl, Jam Kunjungan</th>
                                <th style="vertical-align: middle">Poliklinik yang dituju</th>
                                <th style="vertical-align: middle">Diagnosa</th>
                                <th style="vertical-align: middle">Pengobatan Saat ini</th>
                                <th style="vertical-align: middle">Alergi</th>
                                <th style="vertical-align: middle">Tindakan/Operasi dan Rawat Inap dimasa lalu</th>
                                <th style="vertical-align: middle">Paraf DPJP</th>
                                <th style="vertical-align: middle">ICD X/IX</th>
                            </tr>
                        </thead>
                        <tbody class="listresume">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='loading modal'>
    <div class='text-center align-middle' style="margin-top: 200px">
        <div class="col-xs-3 col-sm-3 col-lg-5"></div>
        <div class="alert col-xs-6 col-sm-6 col-lg-2" style="background-color: white;border-radius: 10px;">
            <div class="overlay" style="font-size:50px;color:#696969"><img src="<?php echo base_url();?>/img/load.gif" width="150px"></div>
            <div style="font-size:20px;font-weight:bold;color:#696969;margin-top:-30px;margin-bottom:20px">Loading</div>
        </div>
        <div class="col-xs-3 col-sm-3 col-lg-5"></div>
    </div>
</div>
<style>
#signature{
    width: 100%;
    height: 300px;
    border: 1px solid black;
}
</style>
