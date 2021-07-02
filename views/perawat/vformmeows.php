<script type="text/javascript" src="<?php echo base_url()?>plugins/input-mask/jquery.inputmask.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script type="text/javascript">
    var mywindow;
    function openCenteredWindow(url) {
        var width = 1200;
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
    $(document).ready(function(){
        $("[name='diagnosa'], [name='imp']").select2();
        $("[name='diagnosa_implementasi']").select2();
        $("[name='tgl_pemeriksaan1']").val(today());
        $("[name='tgl_pemeriksaan2']").val(today());
        var formattgl = "dd-mm-yy";
        $("input[name='tgl_pemeriksaan1']").datepicker({
            dateFormat : formattgl,
        });
            $("input[name='tgl_pemeriksaan2']").datepicker({
            dateFormat : formattgl,
        });
        $(".back").click(function(){
            var jenis = "<?php echo $jenis;?>";
            var asal = "<?php echo $asal;?>";
            if (asal=="assesmen"){
                if (jenis=="igd"){
                    window.location ="<?php echo site_url('perawat/pasienigd');?>/";
                } else 
                if (jenis=="ralan"){
                    window.location ="<?php echo site_url('perawat/pasienralan');?>/";
                } else 
                if (jenis=="ranap"){
                    window.location ="<?php echo site_url('perawat/pasieninap');?>/";
                }
            } else {
                window.location ="<?php echo site_url('pendaftaran/rawat_jalan');?>";
            }
            return false;
        });
        $(".pemakaian_o2").change(function(){
            var id = $(this).attr("id");
            var pemakaian_o2 = $(this).val();
            if (pemakaian_o2=="Y") {
                $("[name='keterangan_pemakaian_o2"+id+"']").removeClass("hide");
            } else {
                $("[name='keterangan_pemakaian_o2"+id+"']").addClass("hide");
            }
        })
        $('.resume').click(function(){
            var no_rm = $("[name='no_pasien']").val();
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
                                html += "<li>"+val1.nama_obat+" "+val1.qty+" "+val1.satuan+"</li>";
                            });
                        } else {
                            html += "-";
                        }
                        html += "</ul>";
                        html += "</td>";
                        html += "<td>"+(val.riwayat_alergi==undefined ? "-" : val.riwayat_alergi)+"</td>";
                        html += "<td>";
                        html += "<ul style='margin-left:-20px'>";
                        if (data["kasir"][val.no_reg]!=undefined){
                            var koma = "";
                            var nama_tindakan = "";
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
        $('.cetak').click(function(){
            var no_pasien =  $("[name='no_pasien']").val();
            var no_reg =  "<?php echo $no_reg;?>";
            var tgl1 =  $("[name='tgl_pemeriksaan1']").val();
            var tgl2 =  $("[name='tgl_pemeriksaan2']").val();
            var url = "<?php echo site_url('perawat/cetakmeows');?>/"+no_pasien+"/"+no_reg+"/"+tgl1+"/"+tgl2;
            openCenteredWindow(url);
        });
        $('.kebidanan').click(function(){
            var no_pasien =  $("[name='no_pasien']").val();
            var no_reg =  "<?php echo $no_reg;?>";
            var url = "<?php echo site_url('perawat/cetakkebidanan');?>/"+no_pasien+"/"+no_reg;
            openCenteredWindow(url);
        });
        $('.handover').click(function(){
            var no_pasien =  $("[name='no_pasien']").val();
            var no_reg =  "<?php echo $no_reg;?>";
            var url = "<?php echo site_url('perawat/cetakhandover');?>/"+no_pasien+"/"+no_reg;
            openCenteredWindow(url);
        });
        $('.asuhan').click(function(){
            var no_pasien =  $("[name='no_pasien']").val();
            var no_reg =  "<?php echo $no_reg;?>";
            var jenis =  "<?php echo $jenis;?>";
            var url = "<?php echo site_url('perawat/cetakasuhan');?>/"+no_pasien+"/"+no_reg+"/"+jenis;
            openCenteredWindow(url);
        });
        var formattgl = "dd-mm-yy";
        $("[name='tgl']").datepicker({
            dateFormat : formattgl
        })
        $('.tujuan').click(function(){
            var id_ap =  $(this).attr("id");
            $("[name='id_ap']").val(id_ap);
            $(".modaltujuan").modal("show");
        });
        $('.implementasi').click(function(){
            var id_ap =  $(this).attr("id");
            $("[name='id_ap']").val(id_ap);
            $(".modalimplementasi").modal("show");
        });
        $('.evaluasi').click(function(){
            var id_ap =  $(this).attr("id");
            $("[name='id_ap']").val(id_ap);
            $(".modalevaluasi").modal("show");
        });
        $(".textarea, .text_implementasi").wysihtml5({
            toolbar: {
                "fa": false,
                "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": false, //Italics, bold, etc. Default true
                "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": false, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false, //Blockquote  
            }
        });
        $("[name='imp']").change(function(){
            var imp = $(this).val();
            $("[name='implementasi']").val(imp);
        });
        $("input[name='jam']").inputmask("hh:mm", {"placeholder": "00:00 "});
        $(".add").click(function(){
            var tgl = $("[name='tgl']").val();
            var jam = $("[name='jam']").val();
            $.ajax({
                type  : "POST",
                data  : {tgl:tgl,jam:jam},
                url   : "<?php echo site_url('perawat/addtemp_meows');?>",
                success : function(result){
                    simpan();
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $(".prov").change(function(){
            var prov = $(this).val();
            var id = $(this).attr("id");
            $("[name='kota"+id+"']").html("");
            $.ajax({
                type  : "POST",
                data  : {prov:prov},
                url   : "<?php echo site_url('perawat/getkota');?>",
                success : function(result){
                    var v = JSON.parse(result);
                    $("[name='kota"+id+"']").append($('<option>', {
                        value: "",
                        text: "---"
                    }));
                    $.each(v, function(key, value){
                        var name = value.name=="" ? "" : value.name;
                        $("[name='kota"+id+"']").append($('<option>', {
                            value: value.id,
                            text: name
                        }));
                    });
                    $("[name='kota"+id+"']").select2();
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $("[name='diagnosa_implementasi']").change(function(){
            var diagnosa = $(this).val();
            $.ajax({
                type  : "POST",
                data  : {diagnosa:diagnosa},
                url   : "<?php echo site_url('perawat/addimplementasi');?>",
                success : function(result){
                    console.log(result);
                    var value = JSON.parse(result);
                    var html = "<tr>";
                    html += "<td><textarea class='text_implementasi form-control' name='s_implementasi'>"+value.s+"</textarea></td>";
                    html += "<td><textarea class='text_implementasi form-control' name='o_implementasi'>"+value.o+"</textarea></td>";
                    html += "<td><textarea class='text_implementasi form-control' name='a_implementasi'>"+value.a+"</textarea></td>";
                    html += "<td><textarea class='text_implementasi form-control' name='p_implementasi'>"+value.p+"</textarea></td>";
                    html += "</tr>";
                    $(".im_content").html(html);
                    $(".text_implementasi").wysihtml5({
                        toolbar: {
                            "fa": false,
                            "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                            "emphasis": false, //Italics, bold, etc. Default true
                            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                            "html": false, //Button which allows you to edit the generated HTML. Default false
                            "link": false, //Button to insert a link. Default true
                            "image": false, //Button to insert an image. Default true,
                            "color": false, //Button to change color of font  
                            "blockquote": false, //Blockquote  
                        }
                    });
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $(".prilaku").change(function(){
            var id = $(this).attr("id");
            var prilaku = $(this).val();
            var nilai_prilaku = "";
            switch (prilaku) {
                case "Tidak sadar" : nilai_prilaku = 3; break;
                case "Iritable" : nilai_prilaku = 2; break;
                case "Tidur" : nilai_prilaku = 1; break;
                case "Bermain" : nilai_prilaku = 0; break;
            }
            $("[name='nilai_prilaku"+id+"']").val(nilai_prilaku);
            var nilai_meows_tambahan = $("[name='nilai_meows_tambahan"+id+"']").val();
            var nilai_meows_total = parseFloat(nilai_prilaku)+parseFloat(nilai_meows_tambahan);
            $("[name='nilai_meows_total"+id+"']").val(nilai_meows_total);
        })
        $(".nebulizer, .muntah_post_op").change(function(){
            var id = $(this).attr("id");
            var nebulizer = $("[name='nebulizer"+id+"']").val();
            var nilai_meows_tambahan = 0;
            switch (nebulizer) {
                case "Y" : nilai_meows_tambahan += 2; break;
            }
            var muntah_post_op = $("[name='muntah_post_op"+id+"']").val();
            switch (muntah_post_op) {
                case "Y" : nilai_meows_tambahan += 2; break;
            }
            $("[name='nilai_meows_tambahan"+id+"']").val(nilai_meows_tambahan);
            var nilai_prilaku = $("[name='nilai_prilaku"+id+"']").val();
            var nilai_meows_total = parseFloat(nilai_prilaku)+parseFloat(nilai_meows_tambahan);
            $("[name='nilai_meows_total"+id+"']").val(nilai_meows_total);
        })
        $(".save").click(function(){
            // var rr = $("[name='rr"+id+"']").val();
            // var spo2 = $("[name='spo2"+id+"']").val();
            // var nadi = $("[name='nadi"+id+"']").val();
            // var suhu = $("[name='suhu"+id+"']").val();
            // var gula_darah = $("[name='gula_darah"+id+"']").val();
            var id = $(this).attr("id");
            var rr = $("[name='rr"+id+"']").val();
            var spo2 = $("[name='spo2"+id+"']").val();
            var pemakaian_o2 = $("[name='pemakaian_o2"+id+"']").val();
            var keterangan_pemakaian_o2 = $("[name='keterangan_pemakaian_o2"+id+"']").val();
            var suhu = $("[name='suhu"+id+"']").val();
            var tensi = $("[name='tensi"+id+"']").val();
            var tekanan_darah = $("[name='tekanan_darah"+id+"']").val();
            var nadi = $("[name='nadi"+id+"']").val();
            var tingkat_kesadaran = $("[name='tingkat_kesadaran"+id+"']").val();
            var nyeri = $("[name='nyeri"+id+"']").val();
            var lochea = $("[name='lochea"+id+"']").val();
            var protein_urin = $("[name='protein_urin"+id+"']").val();
            var score_ews = $("[name='score_ews"+id+"']").val();
            var gula_darah = $("[name='gula_darah"+id+"']").val();
            var konjungtiva = $("[name='konjungtiva"+id+"']").val();
            var buah_dada = $("[name='buah_dada"+id+"']").val();
            var kontraksi = $("[name='kontraksi"+id+"']").val();
            var flatus = $("[name='flatus"+id+"']").val();
            var fundur_uteri = $("[name='fundur_uteri"+id+"']").val();
            var luka_pembedahan = $("[name='luka_pembedahan"+id+"']").val();
            var perineum = $("[name='perineum"+id+"']").val();
            var defekasi = $("[name='defekasi"+id+"']").val();
            var bak = $("[name='bak"+id+"']").val();
            var diastasis_retchi = $("[name='diastasis_retchi"+id+"']").val();
            var jenis_persalinan = $("[name='jenis_persalinan"+id+"']").val();
            var arrayData = {
                id : id,
                rr : rr,
                spo2 : spo2,
                pemakaian_o2 : pemakaian_o2,
                keterangan_pemakaian_o2 : keterangan_pemakaian_o2,
                suhu : suhu,
                tensi : tensi,
                tekanan_darah : tekanan_darah,
                nadi : nadi,
                tingkat_kesadaran : tingkat_kesadaran,
                nyeri : nyeri,
                lochea : lochea,
                protein_urin : protein_urin,
                score_ews : score_ews,
                gula_darah : gula_darah,
                konjungtiva : konjungtiva,
                buah_dada : buah_dada,
                kontraksi : kontraksi,
                flatus : flatus,
                fundur_uteri : fundur_uteri,
                luka_pembedahan : luka_pembedahan,
                perineum : perineum,
                defekasi : defekasi,
                bak : bak,
                diastasis_retchi : diastasis_retchi,
                jenis_persalinan : jenis_persalinan,
            }
            $.ajax({
                type  : "POST",
                data  : arrayData,
                url   : "<?php echo site_url('perawat/changesoap_meows');?>",
                success : function(result){
                    // location.reload();
                    simpan();

                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $(".hapus").click(function(){
            var id = $(this).attr("id");
            var no_reg = "<?php echo $no_reg;?>";
            $.ajax({
                type  : "POST",
                data  : {id:id,no_reg:no_reg},
                url   : "<?php echo site_url('perawat/hapustemp_meows');?>",
                success : function(result){
                    simpan();
                },
                error: function(result){
                    alert(result);
                }
            });
        });
        $(".simpan").click(function(){
            var no_reg = "<?php echo $no_reg;?>";
            var jenis = "<?php echo $jenis;?>";
            $.ajax({
                type  : "POST",
                data  : {no_reg:no_reg,jenis:jenis},
                url   : "<?php echo site_url('perawat/simpantemp');?>",
                success : function(result){
                    location.reload();
                },
                error: function(result){
                    alert(result);
                }
            });
        });
    });
    function today() {
        var d = new Date();
        var hari = ("0"+d.getDate()).slice(-2)+ "-" + ("0"+(d.getMonth() +1)).slice(-2) + "-" + d.getFullYear();
        return hari;
    }
    function simpan(){
        var no_reg = "<?php echo $no_reg;?>";
        $.ajax({
            type  : "POST",
            data  : {no_reg:no_reg},
            url   : "<?php echo site_url('perawat/simpantemp_meows');?>",
            success : function(result){
                location.reload();
            },
            error: function(result){
                console.log(result);
            }
        });
    }
    function provinsi(id="",prov=""){
        $("[name='kota"+id+"']").html("");
        $.ajax({
            type  : "POST",
            url   : "<?php echo site_url('perawat/getprovince');?>",
            success : function(result){
                console.log(result);
                var v = JSON.parse(result);
                var pr0 = "0";
                var pr1 = "0";
                if (prov!="_"){
                    var p = prov.split("_");
                    pr0 = p[0];
                    pr1 = p[1];
                }
                $("[name='prov"+id+"']").append($('<option>', {
                        value: "",
                        text: "---"
                    }));
                $.each(v, function(key, value){
                    var selected = value.id==pr0 ? "selected" : "";
                    $("[name='prov"+id+"']").append($('<option>', {
                        value: value.id,
                        text: value.name
                    }));
                });
                $("[name='prov"+id+"'] option[value="+pr0+"]").prop("selected", true);
                $("[name='prov"+id+"']").select2();
                kota(id,pr0,pr1);
            },
            error: function(result){
                console.log(result);
            }
        });
    }
    function kota(id="",prov="",kota=""){
        $("[name='kota"+id+"']").html("");
        $.ajax({
            type  : "POST",
            data : {prov:prov},
            url   : "<?php echo site_url('perawat/getkota');?>",
            success : function(result){
                console.log(result);
                var v = JSON.parse(result);
                $("[name='kota"+id+"']").append($('<option>', {
                        value: "",
                        text: "---"
                    }));
                $.each(v, function(key, value){
                    $("[name='kota"+id+"']").append($('<option>', {
                        value: value.id,
                        text: value.name
                    }));
                });
                if (kota!="")
                    $("[name='kota"+id+"'] option[value="+kota+"]").prop("selected", true);
                $("[name='kota"+id+"']").select2();
            },
            error: function(result){
                console.log(result);
            }
        });
    }
</script>
<?php
    list($year,$month,$day) = explode("-",$q->tgl_lahir);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($month_diff < 0) { 
        $year_diff--;
        $month_diff *= (-1);
    }
    elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
    if ($day_diff < 0) { 
        $day_diff *= (-1);
    }
    $umur = $year_diff." tahun ";
    if ($jenis=="igd"){
        $judul = "IGD";
    } else 
    if ($jenis=="ralan"){
        $judul = "Rawat Jalan";
    } else
    if ($jenis=="ranap"){
        $judul = "Rawat Inap";
    } 
?>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="form-horizontal">
            <div class="box-body">
            	<div class="form-group">
                    <label class="col-md-2 control-label">No. RM</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='no_pasien' readonly value="<?php echo $q->no_pasien;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Nama Pasien</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_pasien1;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Tgl Lahir / Umur</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='tgl_lahir' readonly value="<?php echo date("d-m-Y",strtotime($q->tgl_lahir)).' / '.$umur;?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header"><h3 class="box-title">Observasi Harian Pasien Dewasa</h3></div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class='form-group'>
                    <label class='col-md-1 control-label'>Tanggal</label>
                    <div class='col-md-2'>
                        <input type="text" name='tgl' class='form-control' value="<?php echo date("d-m-Y");?>">
                    </div>
                    <label class='col-md-1 control-label'>Jam</label>
                    <div class='col-md-2'>
                        <input type="text" name='jam' class='form-control'>
                    </div>
                    <label class='col-md-1 control-label'></label>
                    <div class='col-md-3'>
                        <button type="button" class="add btn btn-success"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
                </div>
            </div>
                <?php
                    $temp = $this->session->userdata("temp_meows");
                    $row = explode(",", $temp);
                    $t = $koma = "";
                    $no = 1;
                    if (is_array($row)){
                        $no = count($row);
                        foreach ($row as $key => $value) {
                            if ($value!=""){
                                $v = explode(" ", $value);
                                $id = date("YmdHis",strtotime($value));
                                $tgl_pemeriksaan[$this->session->userdata("tgl".$id)] = $this->session->userdata("tgl".$id);
                                echo "<div class='box box-primary ".($no==count($row) ? "" : "collapsed-box")."'>";
                                echo "  <div class='box-header'>";
                                echo "  	<h3 class='box-title'>EWS ".$no."</h3>";
                                echo "  	<div class='pull-right box-tools'>";
                                echo "  		<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa ".($no==count($row) ? "fa-minus" : "fa-plus")."'></i></button>";
                                echo "  		<button type='button' class='hapus btn btn-box-tool' id='".$id."'><i class='fa fa-times'></i></button>";
                                echo "  	</div>";
                                echo "  </div>";
                                echo "  <div class='box-body'>";
                                echo "    <div class='form-horizontal'>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>Tanggal</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='tgl".$id."' class='form-control' readonly value='".date("d-m-Y",strtotime($this->session->userdata("tgl_meows".$id)))."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Jam</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='jam".$id."' class='form-control' readonly value='".$this->session->userdata("jam_meows".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>RR (x/mnt)</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                  <input type='text' name='rr".$id."' class='form-control' value='".$this->session->userdata("rr".$id)."'>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>SpO2</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='spo2".$id."' class='form-control' value='".$this->session->userdata("spo2".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Pemakaian O2</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <select name='pemakaian_o2".$id."' class='form-control pemakaian_o2' id='".$id."'>";
                                echo "                    <option value='Y' ".($this->session->userdata("pemakaian_o2".$id)=="Y" ? "selected" : "").">YA</option>";
                                echo "                    <option value='T' ".($this->session->userdata("pemakaian_o2".$id)=="T" ? "selected" : "").">Tidak</option>";
                                echo "                </select>";
                                echo "            </div>";
                                echo "            <div class='col-md-4'>";
                                echo "                  <input type='text' name='keterangan_pemakaian_o2".$id."' class='form-control ".($this->session->userdata("pemakaian_o2".$id)=="T" ? "hide" : "")."' value='".$this->session->userdata("keterangan_pemakaian_o2".$id)."'>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>Suhu</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <select name='suhu".$id."' class='form-control'>";
                                echo "                    <option value='<35' ".($this->session->userdata("suhu".$id)=="<35" ? "selected" : "")."><35</option>";
                                echo "                    <option value='35.1-36.0' ".($this->session->userdata("suhu".$id)=="35.1-36.0" ? "selected" : "").">35.1-36.0</option>";
                                echo "                    <option value='36.1-38.0' ".($this->session->userdata("suhu".$id)=="36.1-38.0" ? "selected" : "").">36.1-38.0</option>";
                                echo "                    <option value='38.1-39.0' ".($this->session->userdata("suhu".$id)=="38.1-39.0" ? "selected" : "").">38.1-39.0</option>";
                                echo "                    <option value='>39.1' ".($this->session->userdata("suhu".$id)==">39.1" ? "selected" : "").">>39.1</option>";
                                echo "                </select>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>
                                                        TENSI (NILAI SYSTOLIC) (mmHg)
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <select name='tensi".$id."' class='form-control'>";
                                echo "                    <option value='<90' ".($this->session->userdata("tensi".$id)=="<90" ? "selected" : "")."><90</option>";
                                echo "                    <option value='91-100' ".($this->session->userdata("tensi".$id)=="91-100" ? "selected" : "").">91-100</option>";
                                echo "                    <option value='101-110' ".($this->session->userdata("tensi".$id)=="101-110" ? "selected" : "").">101-110</option>";
                                echo "                    <option value='111-219' ".($this->session->userdata("tensi".$id)=="111-219" ? "selected" : "").">111-219</option>";
                                echo "                    <option value='>>220' ".($this->session->userdata("tensi".$id)==">>220" ? "selected" : "").">>>220</option>";
                                echo "                </select>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Tekanan Darah Diastolik</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <select name='tekanan_darah".$id."' class='form-control'>";
                                echo "                    <option value='<40' ".($this->session->userdata("tekanan_darah".$id)=="<40" ? "selected" : "")."><40</option>";
                                echo "                    <option value='41-50' ".($this->session->userdata("tekanan_darah".$id)=="41-50" ? "selected" : "").">41-50</option>";
                                echo "                    <option value='51-90' ".($this->session->userdata("tekanan_darah".$id)=="51-90" ? "selected" : "").">51-90</option>";
                                echo "                    <option value='91-110' ".($this->session->userdata("tekanan_darah".$id)=="91-110" ? "selected" : "").">91-110</option>";
                                echo "                    <option value='>111-130' ".($this->session->userdata("tekanan_darah".$id)==">111-130" ? "selected" : "").">>111-130</option>";
                                echo "                    <option value='>>131' ".($this->session->userdata("tekanan_darah".$id)==">>131" ? "selected" : "").">>>131</option>";
                                echo "                </select>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>NADI</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <select name='nadi".$id."' class='form-control'>";
                                echo "                    <option value='<40' ".($this->session->userdata("nadi".$id)=="<40" ? "selected" : "")."><40</option>";
                                echo "                    <option value='41-50' ".($this->session->userdata("nadi".$id)=="41-50" ? "selected" : "").">41-50</option>";
                                echo "                    <option value='51-90' ".($this->session->userdata("nadi".$id)=="51-90" ? "selected" : "").">51-90</option>";
                                echo "                    <option value='91-110' ".($this->session->userdata("nadi".$id)=="91-110" ? "selected" : "").">91-110</option>";
                                echo "                    <option value='>111-130' ".($this->session->userdata("nadi".$id)==">111-130" ? "selected" : "").">>111-130</option>";
                                echo "                    <option value='>>131' ".($this->session->userdata("nadi".$id)==">>131" ? "selected" : "").">>>131</option>";
                                echo "                </select>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Tingkat Kesadaran</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <select name='tingkat_kesadaran".$id."' class='form-control'>";
                                echo "                    <option value='A' ".($this->session->userdata("tingkat_kesadaran".$id)=="A" ? "selected" : "").">A</option>";
                                echo "                    <option value='v' ".($this->session->userdata("tingkat_kesadaran".$id)=="v" ? "selected" : "").">v</option>";
                                echo "                    <option value='P' ".($this->session->userdata("tingkat_kesadaran".$id)=="P" ? "selected" : "").">P</option>";
                                echo "                    <option value='U' ".($this->session->userdata("tingkat_kesadaran".$id)=="U" ? "selected" : "").">U</option>";
                                echo "                </select>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>
                                                        Nyeri
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='nyeri".$id."' class='form-control' value='".$this->session->userdata("nyeri".$id)."'>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>
                                                        Pengeluaran / Lochea
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='lochea".$id."' class='form-control' value='".$this->session->userdata("lochea".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Protein Urin</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='protein_urin".$id."' class='form-control' value='".$this->session->userdata("protein_urin".$id)."'>";
                                echo "            </div>";
                                echo "         </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>
                                                        Total Score EWS
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='score_ews".$id."' class='form-control' value='".$this->session->userdata("score_ews".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Gula Darah</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='gula_darah".$id."' class='form-control' value='".$this->session->userdata("gula_darah".$id)."'>";
                                echo "            </div>";
                                echo "         </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>
                                                        Konjungtiva
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='konjungtiva".$id."' class='form-control' value='".$this->session->userdata("konjungtiva".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Buah Dada</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='buah_dada".$id."' class='form-control' value='".$this->session->userdata("buah_dada".$id)."'>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>Kontraksi</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='kontraksi".$id."' class='form-control' value='".$this->session->userdata("kontraksi".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>
                                                        Flatus
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='flatus".$id."' class='form-control' value='".$this->session->userdata("flatus".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Fundur Uteri</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='fundur_uteri".$id."' class='form-control' value='".$this->session->userdata("fundur_uteri".$id)."'>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>Luka Pembedahan</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='luka_pembedahan".$id."' class='form-control' value='".$this->session->userdata("luka_pembedahan".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>
                                                        Perineum
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='perineum".$id."' class='form-control' value='".$this->session->userdata("perineum".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>Defekasi</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='defekasi".$id."' class='form-control' value='".$this->session->userdata("defekasi".$id)."'>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "        <div class='form-group'>";
                                echo "            <label class='col-md-2 control-label'>BAK</label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='bak".$id."' class='form-control' value='".$this->session->userdata("bak".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>
                                                        Diastasis Retchi
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='diastasis_retchi".$id."' class='form-control' value='".$this->session->userdata("diastasis_retchi".$id)."'>";
                                echo "            </div>";
                                echo "            <label class='col-md-2 control-label'>
                                                        Jenis Persalinan
                                                  </label>";
                                echo "            <div class='col-md-2'>";
                                echo "                <input name='jenis_persalinan".$id."' class='form-control' value='".$this->session->userdata("jenis_persalinan".$id)."'>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "  </div>";
                                echo "  <div class='box-footer'>";
                                echo "      <div class='pull-right'><button type='button' id='".$id."' class='save btn btn-xs btn-success'><i class='fa fa-check'></i>&nbsp;SAVE</button></div>";
                                echo "  </div>";
                                echo "</div>";
                            }
                            $no--;
                        }
                    }
                ?>
        </div>
        <div class="box-footer">
            <div class="row">
                <div class="col-xs-8">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class='col-md-2 control-label'>Tgl Periksa</label>
                            <div class='col-md-3'>
                                <input name="tgl_pemeriksaan1" class="form-control" readonly>
                            </div>
                            <div class='col-md-3'>
                                <input name="tgl_pemeriksaan2" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="pull-right">
                        <div class="btn-group">
                            <button class="back btn btn-sm btn-warning"><i class="fa fa-arrow-left"></i>&nbsp;Back</button>
                            <button class="cetak btn btn-sm bg-maroon"><i class="fa fa-print"></i>&nbsp;Cetak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modaltujuan" style="overflow:hidden;" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">Tujuan</div>
            <div class="modal-body">
                <?php echo form_open("perawat/simpantujuan",array("class"=>"form-horizontal"));?>
                <input type="hidden" name="id_ap">
                <input type="hidden" name="no_reg" value="<?php echo $no_reg;?>">
                <input type="hidden" name="no_pasien" value="<?php echo $no_pasien;?>">
                <div class="form-group">
                    <label class="col-md-12 control-label">Tujuan</label>
                    <div class="col-md-12">
                        <textarea class="form-control" name="tujuan" style="max-width: 100%;height:80px;"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Simpan</button>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modalevaluasi" style="overflow:hidden;" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:70%;">
        <div class="modal-content">
            <div class="modal-header bg-navy">Evaluasi</div>
            <div class="modal-body">
                <?php echo form_open("perawat/simpanevaluasi",array("class"=>"form-horizontal"));?>
                <input type="hidden" name="id_ap">
                <input type="hidden" name="no_reg" value="<?php echo $no_reg;?>">
                <input type="hidden" name="no_pasien" value="<?php echo $no_pasien;?>">
                <input type="hidden" name="jenis" value="<?php echo $jenis;?>">
                <div class="form-group">
                    <label class="col-md-4 control-label">Diagnosa Keperawatan</label>
                    <div class="col-md-8">
                        <select class="form-control" name="diagnosa_implementasi" style="width:100%"/>
                            <option value="">---</option>
                            <?php
                                foreach($s->result() as $row){
                                    echo "<option value='".$row->id."'>".$row->a."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr class="bg-navy">
                            <th>S</th>
                            <th>O</th>
                            <th>A</th>
                            <th>P</th>
                        </tr>
                    </thead>
                    <tbody class="im_content">
                        <tr>
                            <td><textarea name='s_implementasi' id='s_implementasi' class='text_implementasi form-control'></textarea></td>
                            <td><textarea name='o_implementasi' id='o_implementasi' class='text_implementasi form-control'></textarea></td>
                            <td><textarea name='a_implementasi' id='a_implementasi' class='text_implementasi form-control'></textarea></td>
                            <td><textarea name='p_implementasi' id='p_implementasi' class='text_implementasi form-control'></textarea></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Simpan</button>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modalimplementasi" style="overflow:hidden;" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">Implementasi</div>
            <div class="modal-body">
                <?php echo form_open("perawat/simpanimplementasi",array("class"=>"form-horizontal"));?>
                <input type="hidden" name="id_ap">
                <input type="hidden" name="no_reg" value="<?php echo $no_reg;?>">
                <input type="hidden" name="no_pasien" value="<?php echo $no_pasien;?>">
                <input type="hidden" name="jenis" value="<?php echo $jenis;?>">
                <div class="form-group">
                    <label class="col-md-4 control-label">Implementasi</label>
                    <div class="col-md-8">
                        <select class="form-control" name="imp" style="width:100%"/>
                            <option value="">---</option>
                            <?php
                                foreach($im->result() as $row){
                                    echo "<option value='".$row->keterangan."'>".$row->keterangan."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="form-control" name="implementasi" style="max-width: 100%;height:80px;"></textarea>
                    </div>
                </div>
                <div class='form-group'>
                    <label class="col-md-12 control-label">Perawat</label>
                    <div class="col-md-12">
                        <select name='perawat_implementasi' class='form-control'>";
                            <option value=''>---</option>
                            <?php
                                foreach ($p->result() as $r){
                                    echo "          <option value='".$r->id_perawat."' ".($this->session->userdata("pemberi".$value)==$r->id_perawat ? "selected" : "").">".$r->nama_perawat."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Simpan</button>
                <?php echo form_close();?>
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
                <div class="table-responsive">
                    <table class="table table-bordered">
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
            <div class="modal-footer">
                <button class="okbayar btn btn-success" type="button">OK</button>
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
<style type="text/css">
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        margin-top: -15px;
    }
    .select2-container--default .select2-selection--single{
        padding: 16px 0px;
        border-color: #d2d6de;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
</style>