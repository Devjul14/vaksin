<link rel="stylesheet" href="<?php echo base_url();?>plugins/select2/select2.css">
<script src="<?php echo base_url(); ?>plugins/select2/select2.js"></script>
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
 var mywindow1;
    function openCenteredWindow1(url) {
        var width = 800;
        var height = 500;
        var left = parseInt((screen.availWidth/2) - (width/2));
        var top = parseInt((screen.availHeight/2) - (height/2));
        var windowFeatures = "width=" + width + ",height=" + height +
                             ",status,resizable,left=" + left + ",top=" + top +
                             ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow1 = window.open(url, "subWind", windowFeatures);
    }
    $(document).ready(function(){
        getttd();
        var diagnosa = $("[name='diagnosa']").val();
        namadiagnosa(diagnosa,"nama_diagnosa");
        $(".dropdown-toggle").click(function(){
            var parent = $(this).parent();
            console.log(parent.hasClass("open"));
            if (parent.hasClass("open")){
                parent.removeClass("open");
            } else {
                parent.addClass("open");
            }
        })
        $("[name='nama_diagnosa']").typeahead({
            source: function(query, process) {
                objects = [];
                map = {};
                $("[name='diagnosa']").val('');
                if (query.length>=3){
                    var data = $.ajax({
                        url : "<?php echo base_url();?>pendaftaran/getdiagnosa1",
                        method : "POST",
                        async: false,
                        data : {kode: query}
                    }).responseText;
                    console.log(JSON.parse(data));
                    $.each(JSON.parse(data), function(i, object) {
                        map[object.kode] = object;
                        objects.push(object.kode+" | "+object.nama);
                    });
                    process(objects);
                }
            },
            delay: 0,
            updater: function(item) {
                console.log(item);
                var n = item.split(" | ");
                $("[name='diagnosa']").val(n[0]);
                return n[1];
            }
        });
        $("[name='dokter']").select2();
        var formattgl = "dd-mm-yy";
        $("input[name='tanggal_soap'],[name='tanggal1'],[name='tanggal2']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='ulangan']").datepicker({
            dateFormat : formattgl,
        });
        $("[name='tanggal1'],[name='tanggal2']").change(function(){
            var no_pasien = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var tanggal1 = $("[name='tanggal1']").val();
            var tanggal2 = $("[name='tanggal2']").val();
            window.location = "<?php echo site_url('dokter/cppt_ralan');?>/"+no_pasien+"/"+no_reg+"/"+tanggal1+"/"+tanggal2;
        })
       
        $(".cetak").click(function(){
            var no_reg = $("[name='no_reg']").val();
            var url = "<?php echo site_url('pendaftaran/cetak_laporantindakan');?>/"+no_reg;
            openCenteredWindow(url);
        });
        $('.full').click(function(){
            var full = $(this).attr("full");
            $.ajax({
                type  : "POST",
                data  : {full:full},
                url   : "<?php echo site_url('dokter/sessionfull');?>",
                success : function(result){
                    location.reload();
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $('.back').click(function(){
            var cari_noreg = $("[name='no_reg']").val();
            $.ajax({
                type  : "POST",
                data  : {cari_noreg:cari_noreg},
                url   : "<?php echo site_url('pendaftaran/getcaripasien_ralan');?>",
                success : function(result){
                    window.location = "<?php echo site_url('dokter/rawat_jalandokter');?>";
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        // $("[name='diagnosa']").select2();
        $("[name='tindakan']").select2();
        $("table#form td:even").css("text-align", "right");
        $("table#form td:odd").css("background-color", "white");

        
    });
    function namadiagnosa(kode,element){
        var data = $.ajax({
                        url : "<?php echo base_url();?>pendaftaran/namadiagnosa",
                        method : "POST",
                        async: false,
                        data : {kode: kode}
                    }).responseText;
        $("[name='"+element+"']").val(data);
    }
    function getttd(){
        $.each($(".ttd"), function(key, value) {
            var id_dokter = $(this).attr("id_dokter");
            var ttd = "<?php echo site_url('ttddokter/getttddokterlab/');?>/"+id_dokter;
            $(this).qrcode({width: 100,height: 100, text:ttd});
        });
        $.each($(".ttd_dpjp"), function(key, value) {
            var id_dokter = $(this).attr("id_dokter");
            var ttd = "<?php echo site_url('ttddokter/getttddokterlab/');?>/"+id_dokter;
            $(this).qrcode({width: 100,height: 100, text:ttd});
        });

    }
</script>
<?php
    $t1 = new DateTime('today');
    $t2 = new DateTime($q->tgl_lahir);
    $y  = $t1->diff($t2)->y;
    $m  = $t1->diff($t2)->m;
    $d  = $t1->diff($t2)->d;
    if($q){
        // $nama = $q->nama;
    } else {
        
    }
?>
<div class="col-md-12">
    <div class="box box-primary">
        <?php
            echo form_open("pendaftaran/simpanvisitinap",array("id"=>"formsave","class"=>"form-horizontal"));
        ?>
        <div class="form-horizontal">
            <div class="box-body">
            	<div class="form-group">
                    <label class="col-md-2 control-label">No. Reg</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='no_reg' readonly value="<?php echo $no_reg;?>"/>
                    </div>
                    <label class="col-md-2 control-label">No. RM</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='no_pasien' readonly value="<?php echo $no_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Nama Pasien</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_pasien;?>"/>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class='pull-right'>
                    <div class="col-xs-12 col-lg-2">
                        <div class="row">
                            <div class="col-xs-12 col-lg-11">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="tanggal1" type="text" value='<?php echo $tanggal1;?>' class="form-control" placeHolder="Tanggal ke-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-2">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="tanggal2" type="text" value='<?php echo $tanggal2;?>' class="form-control" placeHolder="Tanggal ke-2">
                                <span class="input-group-btn">
                                    <button  title="Cari" type="button" class="cari btn btn-md btn-primary"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-xs-12 col-lg-2 pull-right">
                        <div class='row'>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary"><?php echo (($this->session->userdata("full")==1 || $this->session->userdata("full")=="") ? "Full" : "Not Full");?></button>
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" class="full" full="1">Full</a></li>
                                    <li><a href="#" class="full" full="0">Not Full</a></li>
                                </ul>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                        </div>
                    </div> -->
                </div>
                <table width="100%" border="1" class="table">
                    <tr class="bg-navy">
                        <td align="center"><strong>Tanggal / Jam </strong></td>
                        <td align="center"><strong>Profesi / Bagian </strong></td>
                        <td align="center"><strong> <center>HASIL PEMERIKSAAN, ANALISIS DAN TINDAK LANJUT / IMPLEMENTASI <br>
                            (Dituliskan dengan format SOAP, untuk nutrisionis dengan format ADIME, <br> 
                            untuk konsul via telp menggunakan metode SBAR)</center>
                         </strong></td>
                        <td align="center" width="10%"><strong>Paraf dan Nama Jelas</strong></td>
                        <td align="center" width ="10%"><strong>Verifikasi DPJP (DPJP Harus membaca seluruh rencana asuhan)</strong></td>
                    </tr>
                    <?php
                        foreach ($c->result() as $key) {
                            echo "<tr><th colspan='5' class='text-center bg-gray'>".strtoupper($key->jenis)."</th></tr>";
                            echo "<tr>";
                            echo "<td>".date("d-m-Y H:i:s",strtotime($key->tanggal))."</td>";
                            echo "<td>".$key->petugas."</td>";
                            echo "<td>".$key->soap."</td>";
                            echo "<td class='text-center'><div class='ttd ttd_".$key->id_dokter."' id_dokter='".$key->id_dokter."'></div><br>".$key->nama_dokter."</td>"; 
                            echo "<td class='text-center'><div class='ttd_dpjp ttd_".$key->dpjp."' id_dokter='".$key->dpjp."'></div><br>".$key->nama_dpjp."</td>";    
                        }
                    ?>
                </table>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <!-- <button class="cetak btn btn-success" type="button"> Cetak</button> -->
                    <button class="btn btn-primary" type="submit"> Simpan</button>
                    <button class="back btn btn-warning" type="button"> Back</button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
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
</style>