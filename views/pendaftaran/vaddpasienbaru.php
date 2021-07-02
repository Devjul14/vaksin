<?php
    if ($row) {
        $no_pasien = $row->no_pasien;
        $nama_pasien = $row->nama_pasien;
        $alamat = $row->alamat;
        $id_kecamatan = $row->id_kecamatan;
        $id_desa = $row->id_desa;
        $id_kota = $row->id_kota;
        $id_propinsi = $row->id_propinsi;
        $tgl_lahir = $row->tgl_lahir;
        $nik = $row->nik;
        $jk = $row->jk;
        $gol_pas = $row->id_gol;
        $agama = $row->agama;
        $hidden = "";
        $action = "edit";
    } else {
        $nama_pasien =
        $alamat =
        $id_kecamatan =
        $id_desa =
        $id_kota =
        $id_propinsi =
        $tgl_lahir =
        $nik =
        $jk =
        $agama =
        $gol_pas = "";
        $hidden = "hidden";
        $action = "simpan";
    }
?>
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
    function getAge(dateString) {
	    var today = new Date();
	    var DOB = new Date(dateString);
	    var totalMonths = (today.getFullYear() - DOB.getFullYear()) * 12 + today.getMonth() - DOB.getMonth();
	    totalMonths += today.getDay() < DOB.getDay() ? -1 : 0;
	    var years = today.getFullYear() - DOB.getFullYear();
	    if (DOB.getMonth() > today.getMonth())
	        years = years - 1;
	    else if (DOB.getMonth() === today.getMonth())
	        if (DOB.getDate() > today.getDate())
	            years = years - 1;


	    var days;
	    var months;

	    if (DOB.getDate() > today.getDate()) {
	        months = (totalMonths % 12);
	        if (months == 0)
	            months = 11;
	        var x = today.getMonth();
	        switch (x) {
	            case 1:
	            case 3:
	            case 5:
	            case 7:
	            case 8:
	            case 10:
	            case 12: {
	                var a = DOB.getDate() - today.getDate();
	                days = 31 - a;
	                break;
	            }
	            default: {
	                var a = DOB.getDate() - today.getDate();
	                days = 30 - a;
	                break;
	            }
	        }
	    }
	    else {
	        days = today.getDate() - DOB.getDate();
	        if (DOB.getMonth() === today.getMonth())
	            months = (totalMonths % 12);
	        else
	            months = (totalMonths % 12);
	    }
	    var age = years + ' Tahun ' + months + ' Bulan ' + days + ' Hari';
	    return age;
	}
    $(document).ready(function(){
        getpropinsi();
        $(".lanjut").click(function(){
            prosessinkron();
            return false;
        });
        $("[name='id_propinsi']").change(function(){
          var propinsi = $(this).val();
          getkota(propinsi);
        })
        $("[name='id_kota']").change(function(){
          var kota = $(this).val();
          getkecamatan(kota);
        })
        $("[name='id_kecamatan']").change(function(){
          var kecamatan = $(this).val();
          getdesa(kecamatan);
        })
        $("[name='id_propinsi'],[name='id_kota'],[name='id_kecamatan'],[name='id_desa']").select2();
        $("[name='foto_kakikiri']").change(function(event){
            if (event.target.files[0].size<=250000){
                $('.gambar_kakikiri').attr("src",URL.createObjectURL(event.target.files[0]));
                upload();
            } else {
                alert("Ukuran foto tidak boleh lebih dari 250 Kb");
            }
        });
        $('.btn_kakikiri :file').on('fileselect', function(event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
            if( input.strlen ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        });
        $("[name='foto_kakikanan']").change(function(event){
            if (event.target.files[0].size<=250000){
                $('.gambar_kakikanan').attr("src",URL.createObjectURL(event.target.files[0]));
                upload();
            } else {
                alert("Ukuran foto tidak boleh lebih dari 250 Kb");
            }
        });
        $('.btn_kakikanan :file').on('fileselect', function(event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
            if( input.strlen ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        });
        $("[name='foto_ibujari_kiri']").change(function(event){
            if (event.target.files[0].size<=250000){
                $('.gambar_ibujari_kiri').attr("src",URL.createObjectURL(event.target.files[0]));
                upload();
            } else {
                alert("Ukuran foto tidak boleh lebih dari 250 Kb");
            }
        });
        $('.btn_ibujari_kiri :file').on('fileselect', function(event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
            if( input.strlen ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        });
        $("[name='foto_ibujari_kanan']").change(function(event){
            if (event.target.files[0].size<=250000){
                $('.gambar_ibujari_kanan').attr("src",URL.createObjectURL(event.target.files[0]));
                upload();
            } else {
                alert("Ukuran foto tidak boleh lebih dari 250 Kb");
            }
        });
        $('.btn_ibujari_kanan :file').on('fileselect', function(event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
            if( input.strlen ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        });
    	var tgl_lahir = $("input[name='tgl_lahir']").val();
    	$("input[name='umur_view']").val(getAge(tgl_lahir));
    	$("input[name='umur']").val(getAge(tgl_lahir));

		$("input[name='tgl_lahir']").change(function(){
        	$("input[name='umur_view']").val(getAge($(this).val()));
    		$("input[name='umur']").val(getAge($(this).val()));
        });
        $("table#form td:even").css("text-align", "right");
        $("table#form td:odd").css("background-color", "white");


        $(".cari_no").click(function(){
            $(".modal_cari_no").modal("show");
            $("[name='cari_no']").focus();
            return false;
        });
        $(".tmb_cari_no").click(function(){
            pencarian();
            return false;
        });
        $('.cancel').click(function(){
            var cari_no = $("[name='no_pasien']").val();
            $.ajax({
                type  : "POST",
                data  : {cari_no:cari_no},
                url   : "<?php echo site_url('pendaftaran/getcaripasien');?>",
                success : function(result){
                    window.location = "<?php echo site_url('pendaftaran');?>";
                },
                error: function(result){
                    alert(result);
                }
            });
        });

        $(".cetak").click(function(){
        	var no_pasien = $("input[name='no_pasien']").val();
            var url = "<?php echo site_url('pendaftaran/cetak_rekmed');?>/"+no_pasien;
            openCenteredWindow(url)
        })
    });
    function getpropinsi(){
      var val = "<?php echo $id_propinsi;?>";
      $.ajax({
          url: "<?php echo site_url('pendaftaran/getpropinsi')?>",
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='id_propinsi']").html('').select2({data:row,placeholder:"Pilih Propinsi"}).select2("val",val);
          }
      });
    }
    function getkota(id){
      var val = "<?php echo $id_kota;?>";
      $.ajax({
          url: "<?php echo site_url('pendaftaran/getkota')?>",
          data: {propinsi:id},
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='id_kota']").html('').select2({data:row,placeholder:"Pilih Kota/ Kabupaten"}).select2("val",val);
          }
      });
    }
    function getkecamatan(id){
      var val = "<?php echo $id_kecamatan?>";
      $.ajax({
          url: "<?php echo site_url('pendaftaran/getkecamatan')?>",
          data: {kota:id},
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='id_kecamatan']").html('').select2({data:row,placeholder:"Pilih Kecamatan"}).select2("val",val);
          }
      });
    }
    function getdesa(id){
      var val = "<?php echo $id_desa;?>";
      $.ajax({
          url: "<?php echo site_url('pendaftaran/getdesa')?>",
          data: {kecamatan:id},
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='id_desa']").html('').select2({data:row,placeholder:"Pilih Desa"}).select2("val",val);
          }
      });
    }
    function getimage(){
        var no_pasien = $("input[name='no_pasien']").val();
        $.ajax({
            url: "<?php echo base_url();?>/pendaftaran/getfoto",
            type: 'POST',
            data:{no_pasien:no_pasien},
            success: function(result){
                var imgdata = JSON.parse(result);
                console.log(result);
                if (no_pasien==""){
                    image1 = "<?php echo base_url();?>img/default-image_450.png";
                    $(".gambar_kakikiri").attr('src', image1);
                    image2 ="<?php echo base_url();?>img/default-image_450.png";
                    $(".gambar_kakikanan").attr('src', image2);
                    image3 = "<?php echo base_url();?>img/default-image_450.png";
                    $(".gambar_ibujari_kiri").attr('src', image3);
                    image4 = "<?php echo base_url();?>img/default-image_450.png";
                    $(".gambar_ibujari_kanan").attr('src', image4);
                } else {
                    image1 = (imgdata["kakikiri"]==null || imgdata["kakikiri"]=="") ? "<?php echo base_url();?>img/default-image_450.png" : 'data:image/gif;base64,'+imgdata["kakikiri"];
                    $(".gambar_kakikiri").attr('src', image1);
                    image2 = (imgdata["kakikanan"]==null || imgdata["kakikanan"]=="") ? "<?php echo base_url();?>img/default-image_450.png" : 'data:image/gif;base64,'+imgdata["kakikanan"];
                    $(".gambar_kakikanan").attr('src', image2);
                    image3 = (imgdata["ibujari_kiri"]==null || imgdata["ibujari_kiri"]=="") ? "<?php echo base_url();?>img/default-image_450.png" : 'data:image/gif;base64,'+imgdata["ibujari_kiri"];
                    $(".gambar_ibujari_kiri").attr('src', image3);
                    image4 = (imgdata["ibujari_kanan"]==null || imgdata["ibujari_kanan"]=="") ? "<?php echo base_url();?>img/default-image_450.png" : 'data:image/gif;base64,'+imgdata["ibujari_kanan"];
                    $(".gambar_ibujari_kanan").attr('src', image4);
                }
            }
        });
    }
    function pencarian(){
        var cari_no = $("[name='cari_no']").val();
        var html = "";
        $(".ljt").addClass("hide");
        $.ajax({
            type  : "POST",
            data  : {cari_no:cari_no},
            url   : "<?php echo site_url('pendaftaran/sinkronpasien');?>",
            success : function(result){
                var value = JSON.parse(result);
                html += "<div class='clearfix'>&nbsp;</div>";
                html += "<table class='table table-stripped'>";
                html += "<tr class='bg-navy'><th>Nama</th><th>Tgl Lahir</th><th>Alamat</th></tr>";
                html += "<tr><td width='180px'>"+value.nama_pasien+"</td><td width='100px'>"+value.tgl_lahir+"</td><td>"+value.alamat+"</td></tr>";
                html += "</table>";
                if (value.nama_pasien!=null){
                    $(".ljt").removeClass("hide");
                    $("[name='hasil_no_rm']").val(value.no_pasien);
                    $("[name='hasil_nama_pasien']").val(value.nama_pasien);
                    $("[name='hasil_id_gol']").val(value.id_gol);
                }
                $(".hasil_cari").html(html);
            },
            error: function(result){
                console.log(result);
            }
        });
    }
</script>
<div class="col-xs-12">
    <div class="box box-primary">
        <?php if (strlen($idlama)>6):?>
        <div class="box-header">
            <button class="cari_no btn btn-sm btn-primary" type="button"><i class="fa fa-search"></i> Cari</button>
        </div>
        <?php endif ?>
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-horizontal">
                    <?php
                        echo form_open("pendaftaran/simpanpasienbaru/".$action,array("id"=>"formsave","class"=>"form-horizontal"));
                        echo "<input type=hidden name='idlama' value='".$idlama."'>";
                    ?>
                    <div class="form-group">
                        <label class="col-md-3 control-label">No. RM</label>
                        <div class="col-md-9">
                            <input type="text" readonly class="form-control" name='no_pasien' value="<?php echo $no_pasien;?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" required name="nama_pasien" value="<?php echo $nama_pasien;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Agama</label>
                        <div class="col-md-9">
                            <select name ="agama" class="form-control">
                                <option <?php echo ($agama=="ISLAM" ? "selected" : ""); ?> value = "ISLAM">ISLAM</option>
                                <option <?php echo ($agama=="PROTESTAN" ? "selected" : ""); ?> value = "PROTESTAN">PROTESTAN</option>
                                <option <?php echo ($agama=="KATOLIK" ? "selected" : ""); ?> value = "KATOLIK">KATOLIK</option>
                                <option <?php echo ($agama=="HINDU" ? "selected" : ""); ?> value = "HINDU">HINDU</option>
                                <option <?php echo ($agama=="BUDHA" ? "selected" : ""); ?> value = "BUDHA">BUDHA</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Gol.Pas</label>
                        <div class="col-md-9">
                            <select name="gol_pas" class="form-control">
                                <option value="">--Pilih--</option>
                                <?php
                                    foreach ($k1->result() as $val1) {
                                        echo "
                                            <option value='".$val1->id_gol."' ".($gol_pas==$val1->id_gol ? "selected" : "").">".$val1->keterangan."</option>
                                        ";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">NIK</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nik" value="<?php echo $nik;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tgl. Lahir</label>
                        <div class="col-md-5">
                            <input type="hidden" name="umur" class="form-control" readonly>
                            <input type="text" id="age" name="umur_view" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="tgl_lahir" value='<?php echo $tgl_lahir?>' autocomplete='off'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Jenis Kelamin</label>
                        <div class="col-md-9">
                            <select name="jenis_kelamin" class="form-control">
                                <option value="">--Pilih--</option>
                                <?php
                                    foreach($q2->result() as $row){
                                        echo "<option value='".$row->jenis_kelamin."' ".($row->jenis_kelamin==$jk ? "selected" : "").">".$row->keterangan."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="col-md-6">
                <div class="form-horizontal">
                    <div class="form-group">
                          <label class="col-md-3 control-label">Propinsi</label>
                          <div class="col-md-9">
                              <select type="text" class="form-control" required name="id_propinsi" style="width:100%"></select>
                          </div>
                      </div>
                      <div class="form-group">
                            <label class="col-md-3 control-label">Kota/ Kabupaten</label>
                            <div class="col-md-9">
                                <select type="text" class="form-control" required name="id_kota" style="width:100%"></select>
                            </div>
                        </div>
                        <div class="form-group">
                              <label class="col-md-3 control-label">Kecamatan</label>
                              <div class="col-md-9">
                                  <select type="text" class="form-control" required name="id_kecamatan" style="width:100%"></select>
                              </div>
                          </div>
                          <div class="form-group">
                                <label class="col-md-3 control-label">Desa</label>
                                <div class="col-md-9">
                                    <select type="text" class="form-control" required name="id_desa" style="width:100%"></select>
                                </div>
                            </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Alamat Lengkap</label>
                        <div class="col-md-9">
                            <textarea name="alamat" class="form-control"><?php echo $alamat;?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="pull-right">
            <div class="btn-group">
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
                <button class="cancel btn btn-danger" type="button"><i class="fa fa-times"></i> Cancel</button>
            </div>
        </div>
        <?php echo form_close();?>
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
                                <input class="form-control" type="text" name="cari_no" placeholder="No. KTP/ No. BPJS/ No. SEP"/>
                                <span class="input-group-btn">
                                    <button class="tmb_cari_no btn btn-success">Cari</button>
                                </span>
                            </div>
                            <div class="hasil_cari"></div>
                            <div class="ljt hide">
                            	<div class="pull-right">
                                	<input type="hidden" name="hasil_no_rm"/>
                                    <input type="hidden" name="hasil_nama_pasien"/>
                                    <input type="hidden" name="hasil_id_gol"/>
                                	<button class="lanjut btn btn-success">Lanjutkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
