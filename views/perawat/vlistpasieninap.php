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
        var cari_noreg = $("[name='cari_noreg']").val();
        var cari_nama = $("[name='cari_nama']").val();
        $.ajax({
            type  : "POST",
            data  : {cari_no:cari_no,cari_nama:cari_nama,cari_noreg:cari_noreg},
            url   : "<?php echo site_url('pendaftaran/getcaripasien_inap');?>",
            success : function(result){
                location.reload();
            },
            error: function(result){
                alert(result);
            }
        });
    }

    $(document).ready(function(e){
        $('#myTable').fixedHeaderTable({ height: '450', altClass: 'odd', footer: true});
        $("tr#data:first").addClass("bg-gray");
        $("table tr#data ").click(function(){
            $("table tr#data ").removeClass("bg-gray");
            $(this).addClass("bg-gray");
        });
        $(".cppt").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            window.location = "<?php echo site_url('dokter/cppt')?>/"+no_rm+"/"+no_reg;
            return false;
        });
        $('.pdf').click(function(){
            var no_sep = $(".bg-gray").attr("no_sep");
            if (no_sep==""){
                alert("Pasien belum memiliki SEP");
            } else {
                var url = "<?php echo site_url('grouper/claimprint_inap');?>/"+no_sep;
                openCenteredWindow(url);
            }
        });
        $(".assesmen").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            window.location = "<?php echo site_url('perawat/assesmeninap')?>/"+no_rm+"/"+no_reg;
            return false;
        });
        $(".pews").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            window.location = "<?php echo site_url('perawat/pewsinap')?>/"+no_rm+"/"+no_reg;
            return false;
        });
        $(".news").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            window.location = "<?php echo site_url('perawat/newsinap')?>/"+no_rm+"/"+no_reg;
            return false;
        });
        $(".meows").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            window.location = "<?php echo site_url('perawat/meowsinap')?>/"+no_rm+"/"+no_reg;
            return false;
        });
        $(".form_a").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            window.location = "<?php echo site_url('perawat/form_a')?>/"+no_rm+"/"+no_reg;
            return false;
        });
        $(".form_b").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            window.location = "<?php echo site_url('perawat/form_b')?>/"+no_rm+"/"+no_reg;
            return false;
        });
        $(".search").click(function(){
            var kode_kelas = $("[name='kode_kelas']").val();
            var kode_ruangan = $("[name='kode_ruangan']").val();
            var kelas = $("[name='kelas']").val();
            var ruangan = $("[name='ruangan']").val();
            var tgl1 = $("[name='tgl1']").val();
            var tgl2 = $("[name='tgl2']").val();
            var arrayData = {kode_kelas: kode_kelas, kelas: kelas,kode_ruangan: kode_ruangan,ruangan: ruangan,tgl1: tgl1,tgl2: tgl2};
            $.ajax({
                url: "<?php echo site_url('pendaftaran/search_inap');?>",
                type: 'POST',
                data: arrayData,
                success: function(){
                    location.reload();
                }
            });
        });
        $(".pulang").click(function(){
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            $.ajax({
                type  : "POST",
                data  : {no_pasien:no_rm,no_reg:no_reg},
                url   : "<?php echo site_url('kasir/getinap_detail');?>",
                success : function(result){
                    var value = JSON.parse(result);
                    console.log(value);
                    $(".noreg").html(no_reg);
                    $(".formpulang").modal("show");
                    $("[name='no_sep']").val(value.no_sjp);
                    $("[name='jam_pulang']").val("<?php echo date("H:i");?>");
                    if (value.tgl_keluar!=null){
                        $("[name='no_surat_pulang']").val(value.no_surat_pulang);
                        $("[name='tanggal_pulang']").val(tgl_indo(value.tgl_keluar));
                        $(".status_pasien").html("<span class='label label-danger'>Pasien sudah pulang</span>");
                        $('[name=keadaan_pulang] option[value='+value.keadaan_pulang+']').prop("selected", true);
                        $('[name=status_pulang] option[value='+value.status_pulang+']').prop("selected", true);
                    } else {
                        $("[name='no_surat_pulang']").val(no_reg);
                        $("[name='tanggal_pulang']").val('');
                        $(".status_pasien").html("");
                        $('[name=keadaan_pulang] option[value=1]').prop("selected", true);
                        $('[name=status_pulang] option[value=1]').prop("selected", true);
                    }
                },
                error: function(result){
                    alert(result);
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
        var tgl_masuk = $(".bg-gray").attr("tgl_masuk");
        $("input[name='tanggal_pulang']").datepicker({
            dateFormat : formattgl,
            minDate: new Date(tgl_masuk),
        }).datepicker("setDate", new Date());
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
        $(".cari_noreg").click(function(){
            $(".modal_cari_noreg").modal("show");
            $("[name='cari_noreg']").focus();
            return false;
        });
        $("[name='cari_nama']").keyup(function(e){
            if (e.keyCode==13) pencarian();
        });
        $("[name='cari_no']").keyup(function(e){
            if (e.keyCode==13) pencarian();
        });
        $("[name='cari_noreg']").keyup(function(e){
            if (e.keyCode==13) pencarian();
        });
        $(".tmb_cari_nama, .tmb_cari_no, .tmb_cari_noreg").click(function(){
            pencarian();
            return false;
        });
        $(".reset").click(function(){
            $.ajax({
                type  : "POST",
                url   : "<?php echo site_url('perawat/reset_inap');?>/",
                success : function(result){
                    location.reload();
                }
            });
        });
        $(".edit").click(function(){
            var no_rm  = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            var back   = "pasieninap";
            window.location = "<?php echo site_url('perawat/listpindahkamar')?>/"+no_rm+"/"+no_reg+"/"+back;
            return false;
        });

    });
    $(document).keyup(function(e){
        if (e.keyCode==82 && e.altKey){
            $(".reset").click();
        }
    })
    function tgl_indo(tgl,tipe=1){
        var date = tgl.substring(tgl.length,tgl.length-2);
        if (tipe==1)
            var bln = tgl.substring(5,7);
        else
            var bln = tgl.substring(4,6);
        var thn = tgl.substring(0,4);
        return date+"-"+bln+"-"+thn;
    }
</script>
<div class='modal rejected'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-red"><h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;NOTIFICATION</h4></div>
            <div class='modal-body'>
                <p>Yakin akan Hapus ?</p>
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
        <div class="box-body">


            <table class="table table-bordered table-hover " id="myTable" >
                <thead>
                    <tr class="bg-navy">
                        <th width="10%" class='text-center'>Nomor RM</th>
                        <th class='text-center'>Nomor REG</th>
                        <th class="text-center">Nama</th>
                        <th class='text-center'>Alamat</th>
                        <th class='text-center'>Ruangan</th>
                        <th class='text-center'>Kelas</th>
                        <th class='text-center'>Kamar</th>
                        <th width="7%" class='text-center'>No. Bed</th>
                        <th class='text-center'>Golongan Pasien</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($q3->result() as $row){
                        echo "<tr id=data href='".$row->no_rm."' no_reg='".$row->no_reg."' no_bpjs='".$row->no_bpjs."' kode_kamar='".$row->kode_kamar."' kode_kelas='".$row->kode_kelas."' kode_ruangan='".$row->kode_ruangan."' no_bed='".$row->no_bed."' no_sep='".$row->no_sjp."' tgl_masuk='".date("Y/m/d",strtotime($row->tgl_masuk))."'>";
                        echo "<td class='text-center'>".$row->no_rm."</td>";
                        echo "<td class='text-center'>".$row->no_reg."</td>";
                        echo "<td>".$row->nama_pasien."</td>";
                        echo "<td>".substr($row->alamat, 0,45)."</td>";
                        echo "<td>".$row->nama_ruangan."</td>";
                        echo "<td>".$row->nama_kelas."</td>";
                        echo "<td>".$row->kode_kamar."</td>";
                        echo "<td class='text-center'>".$row->no_bed."</td>";
                        echo "<td>".$row->gol_pasien."</td>";
                        echo "</tr>";
                    }

                ?>
                </tbody>
                <tfoot>
                    <tr class="bg-navy">
                        <th colspan="7">Jumlah Pasien : <?php echo $total_rows; ?></th>
                    </tr>
                </tfoot>
            </table>

        </div>
        <div class="box-footer">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-1 control-label">Ruangan</label>
                    <div class="col-md-2">
                        <input type="text" name="ruangan" class="form-control" readonly value="<?php echo $this->session->userdata('ruangan');?>">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="kode_ruangan" class="form-control" readonly value="<?php echo $this->session->userdata('kode_ruangan');?>">
                    </div>
                    <div class="col-md-1">
                        <div class="pull-left">
                            <button class="ruangan btn btn-primary" type='button'>...</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class='pull-right'>
                            <?php echo $this->pagination->create_links();?>
                        </div>
                    </div>


                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label">Tanggal</label>
                    <div class="col-md-2">
                            <input type="text" class="form-control" name="tgl1" value="<?php echo $this->session->userdata("tgl1") ?>" autocomplete="off"/>
                    </div>
                    <div class="col-md-2">
                            <input type="text" class="form-control" name="tgl2" value="<?php echo $this->session->userdata("tgl2") ?>" autocomplete="off"/>
                    </div>
                    <div class="col-md-1">
                        <div class="pull-left">
                             <button class="search btn btn-primary" type="button"> <i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <label class="col-md-1 control-label">Kelas</label>
                    <div class="col-md-2">
                        <input type="text" name="kelas" class="form-control" readonly value="<?php echo $this->session->userdata('kelas');?>">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="kode_kelas" class="form-control" readonly value="<?php echo $this->session->userdata('kode_kelas');?>">
                    </div>
                    <div class="col-md-1">
                        <div class="pull-left">
                            <button class="kelas btn btn-primary" type='button'>...</button>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="pull-left">
                    </div>
                    <div class="pull-right">
                        <div class="dropup">
                            <button class="dropbtn btn btn-sm bg-purple">Case Manager</button>
                            <div class="dropup-content">
                                <a class="form_a"> Form A</a>
                                <a class="form_b"> Form B</a>
                            </div>
                        </div>
                        <button class="reset btn btn-sm btn-warning" type="button"> Reset</button>
                        <div class="dropup">
                            <button class="dropbtn btn btn-sm bg-blue">EWS</button>
                            <div class="dropup-content">
                                <a class="news"> NEWS</a>
                                <a class="pews"> PEWS</a>
                                <a class="meows"> MEOWS</a>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button class="assesmen btn btn-sm bg-maroon" type="button">Assesment</button>
                            <button class="edit btn btn-sm btn-success" type="button">Pemindahan Pasien</button>
                            <button class="cppt btn btn-sm bg-navy" type="button">CPPT</button>
                            <button class="cari_no btn btn-sm btn-primary" type="button"><i class="fa fa-search"></i> Cari</button>
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
                                <input class="form-control" type="text" name="cari_no" placeholder="Nama/ No. RM/ No. Reg/ No. BPJS/ No. SEP"/>
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
