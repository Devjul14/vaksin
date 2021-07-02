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
        var temp = "<?php echo $this->session->userdata("temp");?>";
        row = temp.split(",");
        $.each(row, function(key, value){
            $.ajax({
                type  : "POST",
                url   : "<?php echo site_url('perawat/getprov');?>/"+value,
                success : function(result){
                    provinsi(value,result.trim());
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $("[name='diagnosa'], [name='imp']").select2();
        $("[name='diagnosa_implementasi']").select2();
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
            var url = "<?php echo site_url('perawat/cetakassesmen');?>/"+no_pasien+"/"+no_reg;
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
        $(".tglgejala,.tglresiko").datepicker({
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
        $(".simpanevaluasi").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
              type: "POST",
              url: url,
              data: form.serialize(), // serializes the form's elements.
              success: function(data){
                alert("Data berhasil disimpan"); // show response from the php script.
              }
            });
        });
        $("[name='diagnosa']").change(function(){
            var diagnosa = $(this).val();
            var shift = $("[name='shift']").val();
            $.ajax({
                type  : "POST",
                data  : {diagnosa:diagnosa,shift:shift},
                url   : "<?php echo site_url('perawat/addtemp');?>",
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
        $(".save").click(function(){
            var id = $(this).attr("id");
            var s = $("[name='s"+id+"']").val();
            var o = $("[name='o"+id+"']").val();
            var a = $("[name='a"+id+"']").val();
            var p = $("[name='p"+id+"']").val();
            var tujuan = $("[name='tujuan"+id+"']").val();
            var tgl = $("[name='tgl"+id+"']").val();
            var jam = $("[name='jam"+id+"']").val();
            var td = $("[name='td"+id+"']").val();
            var td2 = $("[name='td2"+id+"']").val();
            var nadi = $("[name='nadi"+id+"']").val();
            var respirasi = $("[name='respirasi"+id+"']").val();
            var suhu = $("[name='suhu"+id+"']").val();
            var spo2 = $("[name='spo2"+id+"']").val();
            var bb = $("[name='bb"+id+"']").val();
            var tb = $("[name='tb"+id+"']").val();
            var status = $("[name='status"+id+"']").val();
            var shift = $("[name='shift"+id+"']").val();
            var situasional = $("[name='situasional"+id+"']").val();
            var medis = $("[name='medis"+id+"']").val();
            var dpjp = $("[name='dpjp"+id+"']").val();
            var rekomendasi = $("[name='rekomendasi"+id+"']").val();
            var id_pindahkamar = $("[name='id_pindahkamar"+id+"']").val();
            var pemberi = $("[name='pemberi"+id+"']").val();
            var penerima = $("[name='penerima"+id+"']").val();
            var prov = $("[name='prov"+id+"']").val();
            var kota = $("[name='kota"+id+"']").val();
            var tingkat_status = $("[name='tingkat_status"+id+"']").val();
            var status_assesmen = $("[name='status_assesmen"+id+"']").val();
            var gejala = "diare,demam,panas,batuk,pilek,sesak,sakittenggorokan";
            var resiko = "resiko1,sd,resiko2";
            var tglgejala = "";
            var tglresiko = "";
            var koma = "";
            $.each($(".tglgejala"+id), function(key, value){
                if ($(this).val()==""){
                    tglgejala += koma+"-";
                } else {
                    tglgejala += koma+$(this).val();
                }
                koma = ",";
            });
            koma = "";
            $.each($(".tglresiko"+id), function(key, value){
                tglresiko += koma+$(this).val();
                koma = ",";
            });
            $.ajax({
                type  : "POST",
                data  : {id:id,s:s,o:o,a:a,p:p,tgl:tgl,jam:jam,td:td,td2:td2,nadi:nadi,respirasi:respirasi,suhu:suhu,spo2:spo2,bb:bb,tb:tb,shift:shift,situasional:situasional,medis:medis,dpjp:dpjp,rekomendasi:rekomendasi,pemberi:pemberi,penerima:penerima,tujuan:tujuan,gejala:gejala,tglgejala:tglgejala,resiko:resiko,tglresiko:tglresiko,prov:prov,kota:kota,status:status,tingkat_status:tingkat_status,status_assesmen:status_assesmen,id_pindahkamar:id_pindahkamar},
                url   : "<?php echo site_url('perawat/changesoap');?>",
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
            var shift = $(this).attr("shift");
            var no_reg = "<?php echo $no_reg;?>";
            var jenis = "<?php echo $jenis;?>";
            $.ajax({
                type  : "POST",
                data  : {id:id,no_reg:no_reg,jenis:jenis,shift:shift},
                url   : "<?php echo site_url('perawat/hapustemp');?>",
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
    function simpan(){
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
                console.log(prov);
                if (prov=="_"){
                    pr0 = "0";
                    pr1 = "0";
                } else {
                    var p = prov.split("_");
                    pr0 = p[0];
                    pr1 = p[1];
                }
                $("[name='prov"+id+"']").append($('<option>', {
                        value: "0",
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
                // alert(pr0);
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
        <div class="box-header"><h3 class="box-title">Rencana Keperawatan <?php echo $judul;?></h3></div>
        <div class="box-body">
            <div class="form-horizontal">
                <div class='form-group'>
                    <label class='col-md-4 control-label'>Shift</label>
                    <div class='col-md-8'>
                        <select name='shift' class='form-control'>
                          <option value='igd' >IGD</option>
                          <option value='terimapasien' >Terima Pasien</option>
                          <option value='pagi'>Pagi (14:00-Ruangan)</option>
                          <option value='sore'>Sore (21:00-Ruangan)</option>
                          <option value='malam'>Malam (7:00-Ruangan)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Diagnosa Keperawatan / Masalah Kolaborasi</label>
                    <div class="col-md-8">
                        <select class="form-control" name="diagnosa"/>
                            <option value="">---</option>
                            <?php
                                foreach($s->result() as $row){
                                    echo "<option value='".$row->id."'>".$row->a."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
                <?php
                    $temp = $this->session->userdata("temp");
                    $row = explode(",", $temp);
                    $t = $koma = "";
                    $no = 1;
                    if (is_array($row)){
                        $no = count($row);
                        foreach ($row as $key => $value) {
                            if ($value!=""){
                                echo "<input type='hidden' name='tgl".$value."' value='".$this->session->userdata("tgl".$value)."'>";
                                echo "<input type='hidden' name='tgl".$value."' value='".$this->session->userdata("jam".$value)."'>";
                                echo "<div class='box box-primary ".($no==count($row) ? "" : "collapsed-box")."'>";
                                echo "<div class='box-header'>";
                                echo "	<h3 class='box-title'>DIAGNOSA KE-".$no."</h3>";
                                echo "	<div class='pull-right box-tools'>";
                                echo "		<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa ".($no==count($row) ? "fa-minus" : "fa-plus")."'></i></button>";
                                echo "		<button type='button' class='hapus btn btn-box-tool' id='".$value."' shift='".$this->session->userdata("shift".$value)."'><i class='fa fa-times'></i></button>";
                                echo "	</div>";
                                echo "</div>";
                                echo "<div class='box-body'>";
            					echo "<table class='table table-striped table-bordered'>";
            					echo "    <tbody>";
                                echo "<tr><td>";
                                echo "<div class='form-horizontal'>";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>Shift</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <select name='shift".$value."' class='form-control'>";
                                echo "          <option value='igd' ".($this->session->userdata("shift".$value)=="igd" ? "selected" : "").">IGD</option>";
                                echo "          <option value='terimapasien' ".($this->session->userdata("shift".$value)=="terimapasien" ? "selected" : "").">Terima Pasien</option>";
                                echo "          <option value='pagi' ".($this->session->userdata("shift".$value)=="pagi" ? "selected" : "").">Pagi (14:00-Ruangan)</option>";
                                echo "          <option value='sore' ".($this->session->userdata("shift".$value)=="sore" ? "selected" : "").">Sore (21:00-Ruangan)</option>";
                                echo "          <option value='malam' ".($this->session->userdata("shift".$value)=="malam" ? "selected" : "").">Malam (7:00-Ruangan)</option>";
                                echo "        </select>";
                                echo "    </div>";
                                echo "</div>";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>TD Kanan</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control' name='td".$value."' value='".$this->session->userdata("td".$value)."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-1 control-label'>TD Kiri</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control' name='td2".$value."' value='".$this->session->userdata("td2".$value)."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-2 control-label'>Nadi</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <input type='text' class='form-control' name='nadi".$value."' value='".$this->session->userdata("nadi".$value)."'/>";
                                echo "    </div>";
                                echo "</div>";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>Respirasi</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control' name='respirasi".$value."' value='".$this->session->userdata("respirasi".$value)."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-1 control-label'>Suhu</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control' name='suhu".$value."' value='".$this->session->userdata("suhu".$value)."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-2 control-label'>SpO2</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <input type='text' class='form-control' name='spo2".$value."' value='".$this->session->userdata("spo2".$value)."'/>";
                                echo "    </div>";
                                echo "</div>";
                                $tglgejala = explode(",", $this->session->userdata("tglgejala".$value));
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>BB</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control' name='bb".$value."' value='".$this->session->userdata("bb".$value)."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-1 control-label'>TB</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control' name='tb".$value."' value='".$this->session->userdata("tb".$value)."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-2 control-label'>Diare</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <input type='text' class='form-control tglgejala tglgejala".$value."' name='tglgejala".$value."' value='".$tglgejala[0]."'/>";
                                echo "    </div>";
                                echo "</div> ";
                                echo "</div> ";
                                echo "</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td>";
                                echo "<div class='form-horizontal'>";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>Demam</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control tglgejala tglgejala".$value."' name='tglgejala".$value."' value='".$tglgejala[1]."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-1 control-label'>Panas</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control tglgejala tglgejala".$value."' name='tglgejala".$value."' value='".$tglgejala[2]."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-2 control-label'>Batuk</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <input type='text' class='form-control tglgejala tglgejala".$value."' name='tglgejala".$value."' value='".$tglgejala[3]."'/>";
                                echo "    </div>";
                                echo "</div> ";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>Pilek</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control tglgejala tglgejala".$value."' name='tglgejala".$value."' value='".$tglgejala[4]."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-1 control-label'>Sesak</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control tglgejala tglgejala".$value."' name='tglgejala".$value."' value='".$tglgejala[5]."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-2 control-label'>Sakit Tenggorokan</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <input type='text' class='form-control tglgejala tglgejala".$value."' name='tglgejala".$value."' value='".$tglgejala[6]."'/>";
                                echo "    </div>";
                                echo "</div> ";
                                echo "</div> ";
                                echo "</td>";
                                echo "</tr>";
                                echo "<tr>";
                                $tglresiko = explode(",", $this->session->userdata("tglresiko".$value));
                                echo "<td>";
                                echo "<div class='form-horizontal'>";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-3 control-label'>Riwayat perjalanan / Singgah</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control tglresiko tglresiko".$value."' name='tglresiko".$value."' value='".$tglresiko[0]."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-1 control-label'>s.d</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <input type='text' class='form-control tglresiko tglresiko".$value."' name='tglresiko".$value."' value='".$tglresiko[1]."'/>";
                                echo "    </div>";
                                echo "</div>";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-3 control-label'>Propinsi</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <select name='prov".$value."' id='".$value."' class='form-control prov'></select>";
                                echo "    </div>";
                                echo "    <label class='col-md-3 control-label'>Kota</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <select name='kota".$value."' class='form-control kota'></select>";
                                echo "    </div>";
                                echo "</div> ";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-3 control-label'>Riwayat kontak COVID-19</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <input type='text' class='form-control tglresiko tglresiko".$value."' name='tglresiko".$value."' value='".$tglresiko[2]."'/>";
                                echo "    </div>";
                                echo "    <label class='col-md-3 control-label'>Status</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <select name='status".$value."' class='form-control status'>";
                                echo "              <option ".($this->session->userdata("status".$value)=="" ? "selected" : "")." value=''>---</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="ODP" ? "selected" : "")." value='ODP'>ODP</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="PDP" ? "selected" : "")." value='PDP'>PDP</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="OTG" ? "selected" : "")." value='OTG'>OTG</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="CONFIRM" ? "selected" : "")." value='CONFIRM'>CONFIRM</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="POSITIF" ? "selected" : "")." value='POSITIF'>POSITIF</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="SUSPECT" ? "selected" : "")." value='SUSPECT'>SUSPECT</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="PROBLABLE" ? "selected" : "")." value='PROBLABLE'>PROBLABLE</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="DISCARDED" ? "selected" : "")." value='DISCARDED'>DISCARDED</option>";
                                echo "              <option ".($this->session->userdata("status".$value)=="MENINGGAL" ? "selected" : "")." value='MENINGGAL'>MENINGGAL</option>";
                                echo "        </select>";
                                echo "    </div>";
                                echo "</div> ";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-3 control-label'>Tingkat Status</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <select name='tingkat_status".$value."' class='form-control tingkat_status'>";
                                echo "              <option value=''>---</option>";
                                echo "              <option ".($this->session->userdata("tingkat_status".$value)=="RINGAN" ? "selected" : "")." value='RINGAN'>RINGAN</option>";
                                echo "              <option ".($this->session->userdata("tingkat_status".$value)=="SEDANG" ? "selected" : "")." value='SEDANG'>SEDANG</option>";
                                echo "              <option ".($this->session->userdata("tingkat_status".$value)=="BERAT" ? "selected" : "")." value='BERAT'>BERAT</option>";
                                echo "        </select>";
                                echo "    </div>";
                                echo "    <label class='col-md-3 control-label'>Klasifikasi Rawat</label>";
                                echo "    <div class='col-md-3'>";
                                echo "        <select name='status_assesmen".$value."' class='form-control status_assesmen'>";
                                echo "              <option value=''>---</option>";
                                echo "              <option ".($this->session->userdata("status_assesmen".$value)=="HIJAU" ? "selected" : "")." value='HIJAU'>HIJAU</option>";
                                echo "              <option ".($this->session->userdata("status_assesmen".$value)=="KUNING" ? "selected" : "")." value='KUNING'>KUNING</option>";
                                echo "              <option ".($this->session->userdata("status_assesmen".$value)=="MERAH" ? "selected" : "")." value='MERAH'>MERAH</option>";
                                echo "        </select>";
                                echo "    </div>";
                                echo "</div> ";
                                echo "</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td>";
                                echo "<div class='form-horizontal'>";
                                if ($jenis=="ranap") {
                                    echo "<div class='form-group'>";
                                    echo "    <label class='col-md-2 control-label'>History Pindah Kamar</label>";
                                    echo "    <div class='col-md-10'>";
                                    echo "        <select name='id_pindahkamar".$value."' class='form-control'>";
                                    echo "          <option value=''>---</option>";
                                    foreach ($pk->result() as $pk1){
                                        echo "          <option value='".$pk1->id."' ".($this->session->userdata("id_pindahkamar".$value)==$pk1->id ? "selected" : "").">".$pk1->ruanglama." ".$pk1->kode_kamar_lama." ".$pk1->no_bed_lama." Ke ".$pk1->ruangbaru." ".$pk1->kode_kamar." ".$pk1->no_bed."</option>";
                                    }
                                    echo "        </select>";
                                    echo "    </div>";
                                    echo "</div>";
                                }
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>Pemberi Operan</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <select name='pemberi".$value."' class='form-control'>";
                                echo "          <option value=''>---</option>";
                                foreach ($p->result() as $r){
                                echo "          <option value='".$r->id_perawat."' ".($this->session->userdata("pemberi".$value)==$r->id_perawat ? "selected" : "").">".$r->nama_perawat."</option>";
                                }
                                echo "        </select>";
                                echo "    </div>";
                                echo "    <label class='col-md-2 control-label'>Penerima Operan</label>";
                                echo "    <div class='col-md-2'>";
                                echo "        <select name='penerima".$value."' class='form-control'>";
                                echo "          <option value=''>---</option>";
                                foreach ($p->result() as $r){
                                echo "          <option value='".$r->id_perawat."' ".($this->session->userdata("penerima".$value)==$r->id_perawat ? "selected" : "").">".$r->nama_perawat."</option>";
                                }
                                echo "        </select>";
                                echo "    </div>";
                                echo "</div>";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>DPJP</label>";
                                echo "    <div class='col-md-4'>";
                                echo "        <select name='dpjp".$value."' class='form-control'>";
                                echo "          <option value=''>---</option>";
                                foreach ($d->result() as $r){
                                echo "          <option value='".$r->id_dokter."' ".($this->session->userdata("dpjp".$value)==$r->id_dokter ? "selected" : "").">".$r->nama_dokter."</option>";
                                }
                                echo "        </select>";
                                echo "    </div>";
                                echo "    <label class='col-md-2 control-label'>Dx/ Medis</label>";
                                echo "    <div class='col-md-4'>";
                                echo "        <input type='text' class='form-control' name='medis".$value."' value='".$this->session->userdata("medis".$value)."'/>";
                                echo "    </div>";
                                echo "</div>";
                                echo "<div class='form-group'>";
                                echo "    <label class='col-md-2 control-label'>Situasional</label>";
                                echo "    <div class='col-md-4'>";
                                echo "        <textarea class='form-control' name='situasional".$value."'>".$this->session->userdata("situasional".$value)."</textarea>";
                                echo "    </div>";
                                echo "    <label class='col-md-2 control-label'>Rekomendasi</label>";
                                echo "    <div class='col-md-4'>";
                                echo "        <textarea class='form-control' name='rekomendasi".$value."'>".$this->session->userdata("rekomendasi".$value)."</textarea>";
                                echo "    </div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                                echo '<tr class="bg-navy"><th>S</th></tr>';
                                echo "<tr><td><textarea name='s".$value."' class='textarea form-control'>".$this->session->userdata("s".$value)."</textarea></td></tr>";
                                echo '<tr class="bg-navy"><th>O</th></tr>';
                                echo "<tr><td><textarea name='o".$value."' class='textarea form-control'>".$this->session->userdata("o".$value)."</textarea></td></tr>";
                                echo '<tr class="bg-navy"><th>A</th></tr>';
                                echo "<tr><td><textarea name='a".$value."' class='textarea form-control'>".$this->session->userdata("a".$value)."</textarea></td></tr>";
                                echo '<tr class="bg-navy"><th>P</th></tr>';
                                echo "<tr><td><textarea name='p".$value."' class='textarea form-control'>".$this->session->userdata("p".$value)."</textarea></td></tr>";
                                echo '<tr class="bg-navy"><th>Tujuan</th></tr>';
                                echo "<tr><td><textarea name='tujuan".$value."' class='textarea form-control'>".$this->session->userdata("tujuan".$value)."</textarea></td>";
                                echo "<tr>";
                                echo "<td class='bg-gray'>";
                                echo "<div class='btn-group'>";
                                echo "<button type='button' id='".$value."' class='implementasi btn btn-xs btn-primary'><i class='fa fa-check'></i>&nbsp;IMPLEMENTASI</button>";
                                echo "<button type='button' id='".$value."' class='evaluasi btn btn-xs btn-warning'><i class='fa fa-check'></i>&nbsp;EVALUASI</button>";
                                echo "</div>";
                                echo "<div class='pull-right'><button type='button' id='".$value."' class='save btn btn-xs btn-success'><i class='fa fa-check'></i>&nbsp;SAVE</button></div>";
                                echo "</td>";
                                echo "</tr>";
                                $no--;
                                echo "</tbody>";
            					echo "</table>";
            					echo "</div>";
            					echo "</div>";
                            }
                        }
                    }
                ?>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <div class="btn-group">
                    <button class="back btn btn-sm btn-warning"><i class="fa fa-arrow-left"></i>&nbsp;Back</button>
                    <button class="handover btn btn-sm bg-maroon"><i class="fa fa-print"></i>&nbsp;Handover</button>
                    <button class="asuhan btn btn-sm bg-navy"><i class="fa fa-print"></i>&nbsp;Asuhan Keperawatan</button>
                    <button class="kebidanan btn btn-sm btn-success"><i class="fa fa-save"></i>&nbsp;Asuhan Kebidanan</button>
                    <button class="cetak btn btn-sm btn-primary"><i class="fa fa-print"></i>&nbsp;Askep IGD</button>
                    <?php if ($jenis=="ralan") : ?>
                    <button class="resume btn btn-sm btn-success" type="button"> Resume</button>
                    <?php endif ?>
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
                <?php echo form_open("perawat/simpanevaluasi",array("class"=>"simpanevaluasi form-horizontal"));?>
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
                <button class="btn btn-danger"  class="close"  data-dismiss="modal" aria-hidden="true" type="button">Keluar</button>
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
