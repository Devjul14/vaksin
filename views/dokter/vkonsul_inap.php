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
        var diagnosa = $("[name='diagnosa']").val();
        namadiagnosa(diagnosa,"nama_diagnosa");
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
        $('.cetakkonsul').click(function(){
            var id= "<?php echo $id_terkait;?>";
            var url = "<?php echo site_url('dokter/cetak_konsul');?>/"+id;
            openCenteredWindow(url);
        });
        $("[name='doktersp']").change(function(){
            var no_rm = $("[name='no_pasien']").val();
            var no_reg = $("[name='no_reg']").val();
            var iddokter = $(this).val();
            var url = "<?php echo base_url();?>dokter/konsul_inap/"+no_rm+"/"+no_reg+"/"+iddokter;
            window.location = url;
        })
        $("[name='doktersp']").select2();
        var formattgl = "dd-mm-yy";
        $("input[name='tanggal']").datepicker({
            dateFormat : formattgl,
        });
        $("input[name='ulangan']").datepicker({
            dateFormat : formattgl,
        });
        $("[name='pemeriksaan_fisik1_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan1_tambah']").hide();
            }
            else {
                $("[name='kelainan1_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik2_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan2_tambah']").hide();
            }
            else {
                $("[name='kelainan2_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik3_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan3_tambah']").hide();
            }
            else {
                $("[name='kelainan3_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik4_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan4_tambah']").hide();
            }
            else {
                $("[name='kelainan4_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik5_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan5_tambah']").hide();
            }
            else {
                $("[name='kelainan5_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik6_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan6_tambah']").hide();
            }
            else {
                $("[name='kelainan6_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik7_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan7_tambah']").hide();
            }
            else {
                $("[name='kelainan7_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik8_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan8_tambah']").hide();
            }
            else {
                $("[name='kelainan8_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik9_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan9_tambah']").hide();
            }
            else {
                $("[name='kelainan9_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik10_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan10_tambah']").hide();
            }
            else {
                $("[name='kelainan10_tambah']").show();
            }
        });
        $("[name='pemeriksaan_fisik11_tambah']").change(function(){
            if ($(this).val()=="1"){
                $("[name='kelainan11_tambah']").hide();
            }
            else {
                $("[name='kelainan11_tambah']").show();
            }
        });
        $(".tambahkonsul").click(function(){
            $(".modaltambahankonsul").modal("show");
            $("[name='id_lama']").val('');
            $("[name='id_dokter_tambah']").select2();
            if ($("[name='pemeriksaan_fisik1_tambah']").val()=="1"){
                $("[name='kelainan1_tambah']").hide();
            }
            else {
                $("[name='kelainan1_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik2_tambah']").val()=="1"){
                $("[name='kelainan2_tambah']").hide();
            }
            else {
                $("[name='kelainan2_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik3_tambah']").val()=="1"){
                $("[name='kelainan3_tambah']").hide();
            }
            else {
                $("[name='kelainan3_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik4_tambah']").val()=="1"){
                $("[name='kelainan4_tambah']").hide();
            }
            else {
                $("[name='kelainan4_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik5_tambah']").val()=="1"){
                $("[name='kelainan5_tambah']").hide();
            }
            else {
                $("[name='kelainan5']").show();
            }
            if ($("[name='pemeriksaan_fisik6_tambah']").val()=="1"){
                $("[name='kelainan6_tambah']").hide();
            }
            else {
                $("[name='kelainan6_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik7_tambah']").val()=="1"){
                $("[name='kelainan7_tambah']").hide();
            }
            else {
                $("[name='kelainan7_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik8_tambah']").val()=="1"){
                $("[name='kelainan8_tambah']").hide();
            }
            else {
                $("[name='kelainan8_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik9_tambah']").val()=="1"){
                $("[name='kelainan9_tambah']").hide();
            }
            else {
                $("[name='kelainan9_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik10_tambah']").val()=="1"){
                $("[name='kelainan10_tambah']").hide();
            }
            else {
                $("[name='kelainan10_tambah']").show();
            }
            if ($("[name='pemeriksaan_fisik11_tambah']").val()=="1"){
                $("[name='kelainan11_tambah']").hide();
            }
            else {
                $("[name='kelainan11_tambah']").show();
            }
            return false;
        });
        $(".cetak").click(function(){
            var no_reg = $("[name='no_reg']").val();
            var url = "<?php echo site_url('pendaftaran/cetak_laporantindakan');?>/"+no_reg;
            openCenteredWindow(url);
        });
        $('.back').click(function(){
            var cari_noreg = $("[name='no_reg']").val();
            $.ajax({
                type  : "POST",
                data  : {cari_noreg:cari_noreg},
                url   : "<?php echo site_url('pendaftaran/getcaripasien_ralan');?>",
                success : function(result){
                    window.location = "<?php echo site_url('dokter/rawat_inapdokter_ranap');?>";
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
        if ($("[name='pemeriksaan_fisik1']").val()=="1"){
            $("[name='kelainan1']").hide();
        }
        else {
            $("[name='kelainan1']").show();
        }
        if ($("[name='pemeriksaan_fisik2']").val()=="1"){
            $("[name='kelainan2']").hide();
        }
        else {
            $("[name='kelainan2']").show();
        }
        if ($("[name='pemeriksaan_fisik3']").val()=="1"){
            $("[name='kelainan3']").hide();
        }
        else {
            $("[name='kelainan3']").show();
        }
        if ($("[name='pemeriksaan_fisik4']").val()=="1"){
            $("[name='kelainan4']").hide();
        }
        else {
            $("[name='kelainan4']").show();
        }
        if ($("[name='pemeriksaan_fisik5']").val()=="1"){
            $("[name='kelainan5']").hide();
        }
        else {
            $("[name='kelainan5']").show();
        }
        if ($("[name='pemeriksaan_fisik6']").val()=="1"){
            $("[name='kelainan6']").hide();
        }
        else {
            $("[name='kelainan6']").show();
        }
        if ($("[name='pemeriksaan_fisik7']").val()=="1"){
            $("[name='kelainan7']").hide();
        }
        else {
            $("[name='kelainan7']").show();
        }
        if ($("[name='pemeriksaan_fisik8']").val()=="1"){
            $("[name='kelainan8']").hide();
        }
        else {
            $("[name='kelainan8']").show();
        }
        if ($("[name='pemeriksaan_fisik9']").val()=="1"){
            $("[name='kelainan9']").hide();
        }
        else {
            $("[name='kelainan9']").show();
        }
        if ($("[name='pemeriksaan_fisik10']").val()=="1"){
            $("[name='kelainan10']").hide();
        }
        else {
            $("[name='kelainan10']").show();
        }
        if ($("[name='pemeriksaan_fisik11']").val()=="1"){
            $("[name='kelainan11']").hide();
        }
        else {
            $("[name='kelainan11']").show();
        }
        $('tr#data').click(function(){
            var file_pdf = $(this).attr("file_pdf");
            var jenis = "ranap";
            var html = '';
            $.ajax({
                url   : "<?php echo site_url('dokter/getpdf');?>",
                type : "POST",
                data: {file_pdf:file_pdf,jenis:jenis},
                success: function(result){
                    html += '<div class="row">';
                    html += '<iframe src="'+result+'" style="border:0" class="col-lg-12 col-md-12 col-sm-12" height="800px">';
                    html += '</iframe>';
                    html += '</div>';
                    $(".modalpdf").modal("show");
                    $(".view_pdf").html(html);
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $("[name='obat']").typeahead({
            source: function(query, process) {
                objects = [];
                map = {};
                if (query.length>=3){
                    var data = $.ajax({
                        url : "<?php echo base_url();?>dokter/getfarmasiobat",
                        method : "POST",
                        async: false,
                        data : {kode: query}
                    }).responseText;
                    $.each(JSON.parse(data), function(i, object) {
                        map[object.id] = object;
                        objects.push(object.id+" | "+object.label);
                    });
                    process(objects);
                }
            },
            delay: 0,
            updater: function(item) {
                console.log(item);
                var n = item.split(" | ");
                var no_reg = $("[name='no_reg']").val();
                var tanggal = $("[name='tanggal_terapi']").val();
                var obat = n[0];
                $("[name='obat']").val(n[0]);
                addobat(no_reg,obat,tanggal);
                return n[0];
            }
        });
        $('.terapi').click(function(){
            $(".modalterapikonsul").modal("show");
            getterapi();
        });
        $('.hapus').click(function(){
            var id = $(this).attr("iddata");
            $.ajax({
                url   : "<?php echo site_url('dokter/hapuskonsul');?>",
                type : "POST",
                data: {id:id},
                success: function(result){
                    location.reload();
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $('.list').on("click",".hapusobat",function(){
            var id = $(this).attr("id");
            $.ajax({
                url   : "<?php echo site_url('dokter/hapusobat');?>",
                type : "POST",
                data: {id:id},
                success: function(result){
                    getterapi();
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $('.list').on("click",".editobat",function(){
            var id = $(this).attr("id");
            var waktu = $("[name='waktu_"+id+"']").val();
            var aturan_pakai = $("[name='aturan_pakai_"+id+"']").val();
            var pagi = $("[name='pagi_"+id+"']").val();
            var siang = $("[name='siang_"+id+"']").val();
            var sore = $("[name='sore_"+id+"']").val();
            var malem = $("[name='malem_"+id+"']").val();
            var qty = $("[name='qty_"+id+"']").val();
            var jumlah = $("[name='jumlah_"+id+"']").val();
            var waktu_lainnya = $("[name='waktu_lainnya_"+id+"']").val();
            $.ajax({
                url   : "<?php echo site_url('dokter/editobat');?>",
                type : "POST",
                data: {id:id,waktu:waktu,aturan_pakai:aturan_pakai,pagi:pagi,siang:siang,sore:sore,malem:malem,waktu_lainnya:waktu_lainnya,qty:qty,jumlah:jumlah},
                success: function(result){
                    getterapi();
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $('a.konsultmb').click(function(){
            var id = $(this).attr("iddata");
            $(".modaltambahankonsul").modal("show");
            $.ajax({
                url   : "<?php echo site_url('dokter/editkonsul');?>",
                type : "POST",
                data: {id:id},
                success: function(result){
                    var value = JSON.parse(result);
                    console.log(value);
                    $("[name='id_lama']").val(id);
                    $("[name='tanggal_tambah']").val(tgl_indo(value.tanggal));
                    $("[name=id_dokter_tambah] option[value="+value.dokter_konsul+"]").prop("selected", true);
                    $("[name='s_tambah']").val(value.s);
                    $("[name='td_tambah']").val(value.td);
                    $("[name='td2_tambah']").val(value.td2);
                    $("[name='nadi_tambah']").val(value.nadi);
                    $("[name='respirasi_tambah']").val(value.respirasi);
                    $("[name='suhu_tambah']").val(value.suhu);
                    $("[name='spo2_tambah']").val(value.spo2);
                    $("[name='bb_tambah']").val(value.bb);
                    $("[name='tb_tambah']").val(value.tb);
                    $("[name='p_tambah']").val(value.p);
                    $("[name='a_tambah']").val(value.a);
                    var pf = value.pemeriksaan_fisik.split(",");
                    $.each(pf, function(key, val){
                        $("[name=pemeriksaan_fisik"+(key+1)+"_tambah] option[value="+val+"]").prop("selected", true);
                        if (val=="1"){
                            $("[name='kelainan"+(key+1)+"_tambah']").hide();
                        } else {
                            $("[name='kelainan"+(key+1)+"_tambah']").show();
                        }
                    });
                    var kelainan = value.kelainan.split("|");
                    $("[name='kelainan1_tambah']").val(kelainan[0]);
                    $("[name='kelainan2_tambah']").val(kelainan[1]);
                    $("[name='kelainan3_tambah']").val(kelainan[2]);
                    $("[name='kelainan4_tambah']").val(kelainan[3]);
                    $("[name='kelainan5_tambah']").val(kelainan[4]);
                    $("[name='kelainan6_tambah']").val(kelainan[5]);
                    $("[name='kelainan7_tambah']").val(kelainan[6]);
                    $("[name='kelainan8_tambah']").val(kelainan[7]);
                    $("[name='kelainan9_tambah']").val(kelainan[8]);
                    $("[name='kelainan10_tambah']").val(kelainan[9]);
                    $("[name='kelainan11_tambah']").val(kelainan[10]);
                    $("[name='id_dokter_tambah']").select2();
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
        $('.jawabkonsul').click(function(){
            var id = "<?php echo $id_terkait;?>";
            var no_reg = $("[name='no_reg']").val();
            $(".modaljawabkonsul").modal("show");
            $.ajax({
                url   : "<?php echo site_url('dokter/editkonsul');?>",
                type : "POST",
                data: {id:id,no_reg:no_reg},
                success: function(result){
                    var value = JSON.parse(result);
                    console.log(value);
                    $("[name=id_dokter_tambah] option[value="+value.dokter_konsul+"]").prop("selected", true);
                    $("[name='a_jawab']").val(value.a);
                    $("[name='p_jawab']").val(value.p);
                    // alert(value.tindakan_radiologi);
                    if (value.tindakan_radiologi!=null && value.tindakan_radiologi!="0" && value.tindakan_radiologi!=""){
                        var tr = value.tindakan_radiologi.split(",");
                        $.each(tr, function(key, val){
                            $(".tindakan_radiologi option[value='"+val+"']").prop("selected", true);
                        });
                    }
                    if (value.tindakan_lab!=null && value.tindakan_lab!="0" && value.tindakan_lab!=""){
                        var tl = value.tindakan_lab.split(",");
                        $.each(tl, function(key, val){
                            $(".tindakan_lab option[value='"+val+"']").prop("selected", true);
                        });
                    }
                    if (value.tindakan_penunjang!=null && value.tindakan_penunjang!="0" && value.tindakan_penunjang!=""){
                        var tr = value.tindakan_penunjang.split(",");
                        $.each(tr, function(key, val){
                            $(".penunjang option[value='"+val+"']").prop("selected", true);
                        });
                    }
                    $(".tindakan_radiologi").select2();
                    $(".tindakan_lab").select2();
                    $(".penunjang").select2();
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
    });
    function addobat(no_reg,obat,tanggal){
        var iddokter = "<?php echo $iddokter;?>";
        $.ajax({
            url   : "<?php echo site_url('dokter/addobat_terapi_inap');?>",
            type : "POST",
            data: {no_reg:no_reg,obat:obat,tanggal:tanggal,iddokter:iddokter},
            success: function(result){
                getterapi();
                $("[name='obat']").val('');
            },
            error: function(result){
                console.log(result);
            }
        });
    }
    function namadiagnosa(kode,element){
        var data = $.ajax({
                        url : "<?php echo base_url();?>pendaftaran/namadiagnosa",
                        method : "POST",
                        async: false,
                        data : {kode: kode}
                    }).responseText;
        $("[name='"+element+"']").val(data);
    }
    function tgl_indo(tgl,tipe=1){
        var date = tgl.substring(tgl.length,tgl.length-2);
        if (tipe==1)
            var bln = tgl.substring(5,7);
        else
            var bln = tgl.substring(4,6);
        var thn = tgl.substring(0,4);
        return date+"-"+bln+"-"+thn;
    }
    function getterapi(){
        var no_reg = $("[name='no_reg']").val();
        var iddokter = "<?php echo $iddokter;?>";
        var tanggal = $("[name='tanggal_terapi']").val();
        var html = '';
        $.ajax({
            url   : "<?php echo site_url('dokter/terapi_inap');?>",
            type : "POST",
            data: {no_reg:no_reg,iddokter:iddokter,tanggal:tanggal},
            success: function(result){
                var i = 1; 
                var n = 1;
                var subtotal = 0;
                var tgl1_print = "";
                var tgl2_print = "";
                var hasil = JSON.parse(result);
                $.each(hasil, function(key, data){
                    hari = data.id.substring(0,2);
                    bulan = data.id.substring(2,4);
                    tahun = data.id.substring(4,6);
                    tgl = "20"+tahun+"-"+bulan+"-"+hari;
                    tgl1_print = data.tgl1_print=="" ? (hari+"-"+bulan+"-20"+tahun) : data.tgl1_print;
                    tgl2_print = hari+"-"+bulan+"-20"+tahun;
                    subtotal += data.jumlah;
                    html +=  "<input type=hidden name='jumlah_"+data.id+"' value='"+(data.jumlah/data.qty)+"'>";
                    html +=  "<tr id='data' title='"+(n++)+"'>";
                    html +=  "<td>"+(i++)+"</td>";
                    html +=  "<td>"+(hari+"-"+bulan+"-20"+tahun)+"</td>";
                    html +=  "<td>"+data.nama_obat+"<div class='pull-right'><button id='"+data.id+"' class='hapusobat btn btn-xs btn-danger'><i class='fa fa-minus'></i></div></td>";
                    html +=  "<td><select class='form-control' name='aturan_pakai_"+data.id+"'>";
                    html +=  "<option value = ''>---</option>";
                    $.ajax({
                        url   : "<?php echo site_url('dokter/aturan_pakai');?>",
                        async : false, 
                        success: function(result){
                            var hsl = JSON.parse(result);
                            console.log(hsl);
                            $.each(hsl, function(key1, val){
                                html +=  "<option value = '"+val.kode+"' "+(data.aturan_pakai==val.kode ? "selected" : "")+" >"+val.nama+"</option>";
                            });
                        },
                        error: function(result){
                            console.log(result);
                        }
                    });  
                    html +=  "</select></td>";
                    html +=  "<td><select class='form-control' name='waktu_"+data.id+"'>";
                    html +=  "<option value = ''>---</option>";
                    $.ajax({
                        url   : "<?php echo site_url('dokter/waktu_pakai');?>",
                        async : false, 
                        success: function(result){
                            var hsl = JSON.parse(result);
                            console.log(hsl);
                            $.each(hsl, function(key1, val){
                                html +=  "<option value = '"+val.kode+"' "+(data.waktu==val.kode ? "selected" : "")+" >"+val.nama+"</option>";
                            });
                        },
                        error: function(result){
                            console.log(result);
                        }
                    });   
                    html +=  "</select></td>";
                    // html +=  "<td class='text-right'><input class='form-control' type = 'text 'name='waktu' value='".$data->waktu."'></td>";
                    html +=  "<td class='text-right'><input class='form-control' class='form-control' type = 'text 'name='pagi_"+data.id+"' autocomplete='off' value='"+(data.pagi==null ? "" : data.pagi)+"'></td>";
                    html +=  "<td class='text-right'><input class='form-control' type = 'text 'name='siang_"+data.id+"' autocomplete='off' value='"+(data.siang==null ? "" : data.siang)+"'></td>";
                    html +=  "<td class='text-right'><input class='form-control' type = 'text 'name='sore_"+data.id+"' autocomplete='off' value='"+(data.sore==null ? "" : data.sore)+"'></td>";
                    html +=  "<td class='text-right'><input class='form-control' type = 'text 'name='malem_"+data.id+"' autocomplete='off' value='"+(data.malem==null ? "" : data.malem)+"'></td>";
                    html +=  "<td><select class='form-control' name='waktu_lainnya_"+data.id+"'>";
                    html +=  "<option value = ''>---</option>";
                    $.ajax({
                        url   : "<?php echo site_url('dokter/waktu_lain');?>",
                        async : false, 
                        success: function(result){
                            var hsl = JSON.parse(result);
                            console.log(hsl);
                            $.each(hsl, function(key1, val){
                                html +=  "<option value = '"+val.kode+"' "+(data.waktu_lainnya==val.kode ? "selected" : "")+" >"+val.nama+"</option>";
                            });
                        },
                        error: function(result){
                            console.log(result);
                        }
                    });  
                    html +=  "</select></td>";
                    html +=  "<td class='text-right' width='100px'><input class='form-control' type = 'text 'name='qty_"+data.id+"' autocomplete='off' value='"+(data.qty==null ? "" : data.qty)+"'></td>";
                    html +=  "<td class='text-center'>"+data.satuan+"</td>";
                    html +=  "<td><button type='button' id='"+data.id+"' class='editobat btn btn-xs btn-success'><i class='fa fa-check'></i></button></td>";
                    html +=  "</tr>";
                });
                $(".list").html(html);
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
    for ($i=0;$i<=11;$i++){
        $pemeriksaan_fisik[$i] = 1;
    }
    for ($i=0;$i<=11;$i++){
        $pemeriksaan_fisik_tambah[$i] = 1;
    }
    $tanggal_tambah = date("d-m-Y");
    if ($dv->num_rows()>0) {
        $q1 = $dv->row();
        $id = $q1->id;
        $s     = $q1->s;
        $o     = $q1->o;
        $a     = $q1->a;
        $p     = $q1->p;
        $kelainan = explode("|", $q1->kelainan);
        if ($q1->pemeriksaan_fisik!="")
        $pemeriksaan_fisik = explode(",", $q1->pemeriksaan_fisik);
        $td      = $q1->td;
        $td2      = $q1->td2;
        $nadi      = $q1->nadi;
        $respirasi      = $q1->respirasi;
        $suhu      = $q1->suhu;
        $spo2      = $q1->spo2;
        $bb      = $q1->bb;
        $tb      = $q1->tb;
        $tanggal = date("d-m-Y",strtotime($q1->tanggal));
    } else {
        $id = 
        $dokter_visit = 
        $s     =
        $o     =
        $a     =
        $p     =
        $td      =
        $td2      =
        $nadi      =
        $respirasi      =
        $suhu      =
        $spo2      =
        $bb      =
        $tb      = "";
        $tanggal = date("d-m-Y");
    }
?>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="form-horizontal">
            <div class="box-body">
            	<div class="form-group">
                    <label class="col-md-2 control-label">No. Reg</label>
                    <div class="col-md-2">
                        <input type="hidden" name='id' value="<?php echo $id;?>"/>
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

                <!-- <div class="form-group">
                    <label class="col-md-2 control-label">Poliklinik</label>
                    <div class="col-md-20">
                        <input type="hidden" class="form-control" name='kode_poli' readonly value="<?php echo $row->tujuan_poli;?>"/>
                        <input type="text" class="form-control" name='poliklinik' readonly value="<?php echo $row->poli;?>"/>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-md-2 control-label">Tgl Lahir / Umur</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo date("d-m-Y",strtotime($q->tgl_lahir)).' / '.$umur;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Ruangan</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='ruangan' readonly value="<?php echo $q->nama_ruangan;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Kelas</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_kelas?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Kamar</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='kamar' readonly value="<?php echo $q->nama_kamar?>"/>
                    </div>

                    <label class="col-md-2 control-label">Dokter Visit</label>
                    <div class="col-md-2">
                        <input name="dokter" class="form-control" type="text" readonly value="<?php echo $dv->row()->nama_dokter;?>">
                    </div>
                    <label class="col-md-2 control-label">Dokter Konsul</label>
                    <div class="col-md-2">
                        <select name="doktersp" class="form-control">
                            <option value="">---</option>
                            <?php 
                                foreach ($dokter->result() as $key){
                                    echo "<option value = '".$key->dokter_konsul."/".$key->id."' ".($key->dokter_konsul==$iddokter ? "selected" : "").">".$key->nama_dokter."</option>";
                                }    
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">S</label>
                    <div class="col-md-6">
                        <textarea class="form-control" readonly style="max-width: 100%;height:120px;"/><?php echo $s;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">TD Kanan</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" readonly name='td' value="<?php echo $td;?>"/>
                    </div>
                    <label class="col-md-2 control-label">TD Kiri</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" readonly name='td2' value="<?php echo $td2;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Nadi</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" readonly name='nadi' value="<?php echo $nadi;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Respirasi</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" readonly name='respirasi' value="<?php echo $respirasi;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Suhu</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" readonly name='suhu' value="<?php echo $suhu;?>"/>
                    </div>
                    <label class="col-md-2 control-label">SpO2</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" readonly name='spo2' value="<?php echo $spo2;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">BB</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" readonly name='bb' value="<?php echo $bb;?>"/>
                    </div>
                    <label class="col-md-2 control-label">TB</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" readonly name='tb' value="<?php echo $tb;?>"/>
                    </div>
                    <label class="col-md-2 control-label">Tanggal</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name='tanggal' readonly value="<?php echo $tanggal;?>"/>
                    </div>
                </div> 
                <?php
                    $ada = 0;
                    for ($n=0;$n<=10;$n++) {
                        if (!$pemeriksaan_fisik[$n]){
                            $ada = 1;
                        }
                    }
                    if ($ada) :
                ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Pemeriksaan Fisik</label>
                    <label class="col-md-8 control-label">Kelainan/ Keluhan</label>
                    <label class="col-md-2 control-label"></label>
                </div>
                <?php if ($pemeriksaan_fisik[0]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Kepala</label>
                    <div class="col-md-2">
                        <select class="form-control" readonly name="pemeriksaan_fisik1">
                            <option value="0" <?php echo ($pemeriksaan_fisik[0]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[0]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan1' value="<?php echo (isset($kelainan[0]) ? $kelainan[0] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[1]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Mata</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik2">
                            <option value="0" <?php echo ($pemeriksaan_fisik[1]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[1]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan2' value="<?php echo (isset($kelainan[1]) ? $kelainan[1] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[2]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">THT</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik3">
                            <option value="0" <?php echo ($pemeriksaan_fisik[2]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[2]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan3' value="<?php echo (isset($kelainan[2]) ? $kelainan[2] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[3]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Gigi Mulut</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik4">
                            <option value="0" <?php echo ($pemeriksaan_fisik[3]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[3]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan4' value="<?php echo (isset($kelainan[3]) ? $kelainan[3] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[4]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Leher</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik5">
                            <option value="0" <?php echo ($pemeriksaan_fisik[4]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[4]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan5' value="<?php echo (isset($kelainan[4]) ? $kelainan[4] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[5]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Thoraks</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik6">
                            <option value="0" <?php echo ($pemeriksaan_fisik[5]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[5]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan6' value="<?php echo (isset($kelainan[5]) ? $kelainan[5] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[6]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Abdomen</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik7">
                            <option value="0" <?php echo ($pemeriksaan_fisik[6]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[6]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan7' value="<?php echo (isset($kelainan[6]) ? $kelainan[6] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[7]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Ekstremitas Atas</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik8">
                            <option value="0" <?php echo ($pemeriksaan_fisik[7]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[7]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan8' value="<?php echo (isset($kelainan[7]) ? $kelainan[7] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[8]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Ekstremitas Bawah</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik9">
                            <option value="0" <?php echo ($pemeriksaan_fisik[8]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[8]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly name='kelainan9' value="<?php echo (isset($kelainan[8]) ? $kelainan[8] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[9]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Genitalia</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik10">
                            <option value="0" <?php echo ($pemeriksaan_fisik[9]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[9]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name='kelainan10' readonly value="<?php echo (isset($kelainan[9]) ? $kelainan[9] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($pemeriksaan_fisik[10]==0) : ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Anus</label>
                    <div class="col-md-2">
                         <select class="form-control" readonly name="pemeriksaan_fisik11">
                            <option value="0" <?php echo ($pemeriksaan_fisik[10]==0 ? "selected" : "");?>>Tidak Normal</option>
                            <option value="1" <?php echo ($pemeriksaan_fisik[10]==1 || $pemeriksaan_fisik[0]=="" ? "selected" : "");?>>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name='kelainan11' readonly value="<?php echo (isset($kelainan[10]) ? $kelainan[10] : '');?>"/>
                    </div>
                </div>
                <?php endif ?>
                <?php endif ?>
                <div class="row">
                    <div class="col-md-6">
                        <h3>File PDF</h3>
                        <table class="table table-bordered table-hover " id="myTable" >
                            <thead>
                                <tr class="bg-navy">
                                    <th width="10" class='text-center'>No</th>
                                    <th class="text-center">File</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i=0;
                                    foreach($pdf->result() as $val){
                                        $i++;
                                        echo "<tr id='data' title='' file_pdf='".$val->file_pdf."'>";
                                        echo "<td>".$i."</td>";
                                        echo "<td>".$val->file_pdf."</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h3>Konsul Tambahan</h3>
                        <table class="table table-bordered table-hover " id="myTable" >
                            <thead>
                                <tr class="bg-navy">
                                    <th width="10" class='text-center'>No</th>
                                    <th class="text-center">Nama Dokter</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i=0;
                                    foreach($dsp->result() as $val){
                                        $i++;
                                        echo "<tr>";
                                        echo "<td>".$i."</td>";
                                        echo "<td><a href='#' class='konsultmb' iddata='".$val->id."'>".$val->nama_dokter."-".$val->nama_dokter_visit."</a><div class='pull-right'><button class='hapus btn btn-xs btn-danger' iddata='".$val->id."'><i class='fa fa-remove'></i></button></div></td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button class="cetakkonsul btn btn-primary" type="button"><i class="fa fa-print"></i> Cetak</button>
                    <button class="jawabkonsul btn btn-success" type="button"> Jawab Konsul</button>
                    <button class="tambahkonsul btn btn-primary"> Tambah Konsul</button>
                    <button class="back btn btn-warning" type="button"> Back</button>
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
<div class="modal fade modalpdf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">View PDF</div>
            <div class="modal-body">
                <div class="view_pdf" style="height:800px"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modaltambahankonsul" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">Tambahan Konsul</div>
            <div class="modal-body">
                <?php echo form_open("dokter/simpantambahkonsul_inap",array("class"=>"form-horizontal"));?>
                    <input type="hidden" name="id_lama">
                    <input type="hidden" name="id_terkait" value="<?php echo $id_terkait;?>">
                    <input type="hidden" name="no_reg_tambah" value="<?php echo $no_reg;?>">
                    <input type="hidden" name="doktersp_tambah" value="<?php echo $iddokter;?>">
                    <input type="hidden" name="no_rm_tambah" value="<?php echo $no_pasien;?>">
                    <div class="form-group">   
                        <label class="col-md-3 control-label">Tanggal</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name='tanggal_tambah' value="<?php echo $tanggal_tambah;?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Dokter Konsul</label>
                        <div class="col-md-9">
                            <select name="id_dokter_tambah" class="form-control" style="width:100%">
                                <option value="">---</option>
                                <?php 
                                    foreach ($dk->result() as $key){
                                        echo "<option value = '".$key->id_dokter."'>".$key->nama_dokter."</option>";
                                    }    
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">S</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="s_tambah" style="max-width: 100%;height:160px;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">O</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">TD Kanan</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='td_tambah'/>
                        </div>
                        <label class="col-md-3 control-label">TD Kiri</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='td2_tambah'/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nadi</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='nadi_tambah'/>
                        </div>
                        <label class="col-md-3 control-label">Respirasi</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='respirasi_tambah'/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Suhu</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='suhu_tambah'/>
                        </div>
                        <label class="col-md-3 control-label">SpO2</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='spo2_tambah'/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">BB</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='bb_tambah'/>
                        </div>
                        <label class="col-md-3 control-label">TB</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name='tb_tambah'/>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-6 control-label">Pemeriksaan Fisik</label>
                        <label class="col-md-6 control-label">Kelainan/ Keluhan</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Kepala</label>
                        <div class="col-md-3">
                            <select class="form-control" name="pemeriksaan_fisik1_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[0]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[0]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan1_tambah' value="<?php echo (isset($kelainan_tambah[0]) ? $kelainan_tambah[0] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Mata</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik2_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[1]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[1]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan2_tambah' value="<?php echo (isset($kelainan_tambah[1]) ? $kelainan_tambah[1] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">THT</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik3_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[2]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[2]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan3_tambah' value="<?php echo (isset($kelainan_tambah[2]) ? $kelainan_tambah[2] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Gigi Mulut</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik4_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[3]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[3]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan4_tambah' value="<?php echo (isset($kelainan_tambah[3]) ? $kelainan_tambah[3] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Leher</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik5_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[4]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[4]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan5_tambah' value="<?php echo (isset($kelainan_tambah[4]) ? $kelainan_tambah[4] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Thoraks</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik6_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[5]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[5]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan6_tambah' value="<?php echo (isset($kelainan_tambah[5]) ? $kelainan_tambah[5] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Abdomen</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik7_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[6]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[6]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan7_tambah' value="<?php echo (isset($kelainan_tambah[6]) ? $kelainan_tambah[6] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Ekstremitas Atas</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik8_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[7]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[7]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan8_tambah' value="<?php echo (isset($kelainan_tambah[7]) ? $kelainan_tambah[7] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Ekstremitas Bawah</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik9_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[8]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[8]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan9_tambah' value="<?php echo (isset($kelainan_tambah[8]) ? $kelainan_tambah[8] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Genitalia</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik10_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[9]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[9]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan10_tambah' value="<?php echo (isset($kelainan_tambah[9]) ? $kelainan_tambah[9] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Anus</label>
                        <div class="col-md-3">
                             <select class="form-control" name="pemeriksaan_fisik11_tambah">
                                <option value="0" <?php echo ($pemeriksaan_fisik_tambah[10]==0 ? "selected" : "");?>>Tidak Normal</option>
                                <option value="1" <?php echo ($pemeriksaan_fisik_tambah[10]==1 || $pemeriksaan_fisik_tambah[0]=="" ? "selected" : "");?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name='kelainan11_tambah' value="<?php echo (isset($kelainan_tambah[10]) ? $kelainan_tambah[10] : '');?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">A</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="a_tambah" style="max-width: 100%;height:160px;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">P</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="p_tambah" style="max-width: 100%;height:160px;"></textarea>
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
<div class="modal fade modaljawabkonsul" style="overflow:hidden;" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">Jawab Konsul</div>
            <div class="modal-body">
                <?php echo form_open("dokter/simpanjawabkonsul_inap",array("class"=>"form-horizontal"));?>
                <input type="hidden" name="id_terkait" value="<?php echo $id_terkait;?>">
                <input type="hidden" name="no_reg_tambah" value="<?php echo $no_reg;?>">
                <input type="hidden" name="doktersp_tambah" value="<?php echo $iddokter;?>">
                <input type="hidden" name="no_rm_tambah" value="<?php echo $no_pasien;?>">
                <div class="form-group">
                    <label class="col-md-4 control-label">A</label>
                    <div class="col-md-8">
                        <textarea class="form-control" name="a_jawab" style="max-width: 100%;height:80px;"><?php echo $a_jawab ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">P</label>
                    <div class="col-md-8">
                        <textarea class="form-control" name="p_jawab" style="max-width: 100%;height:80px;"><?php echo $p_jawab ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Tindakan Radiologi</label>
                    <div class="col-md-8">
                        <select class="form-control tindakan_radiologi"  name="tindakan_radiologi[]" multiple="multiple" style="width:100%">
                            <option value="">-----</option>
                            <?php
                                foreach ($radiologi->result() as $key) {
                                    $t = explode(",", $tindakan_radiologi);
                                    if (count($t)>0){
                                        foreach ($t as $k => $value) {
                                            echo "<option value='".$key->id_tindakan."' ".($key->id_tindakan==$value ? "selected" : "").">".$key->nama_tindakan."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$key->id_tindakan."' ".($key->id_tindakan==$tindakan_radiologi ? "selected" : "").">".$key->nama_tindakan."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Tindakan Lab</label>
                    <div class="col-md-8">
                        <select class="form-control tindakan_lab" name="tindakan_lab[]" multiple="multiple" style="width:100%">
                            <option value="">-----</option>
                            <?php
                                foreach ($lab->result() as $key) {
                                    $t = explode(",", $tindakan_lab);
                                    if (count($t)>0){
                                        foreach ($t as $k => $value) {
                                            echo "<option value='".$key->kode_tindakan."' ".($key->kode_tindakan==$value ? "selected" : "").">".$key->nama_tindakan."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$key->kode_tindakan."' ".($key->kode_tindakan==$tindakan_lab ? "selected" : "").">".$key->nama_tindakan."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Tindakan Lain Lain</label>
                    <div class="col-md-8">
                        <select class="form-control penunjang"  name="penunjang[]" multiple="multiple" style="width:100%">
                            <option value="">-----</option>
                            <?php
                                foreach ($tarif_penunjang_medis->result() as $key) {
                                    $t = explode(",", $pemeriksaan_penunjang);
                                    if (count($t)>0){
                                        foreach ($t as $k => $value) {
                                            echo "<option value='".$key->kode."' ".($key->kode==$value ? "selected" : "").">".$key->ket."</option>";
                                        }
                                    } else {
                                        echo "<option value='".$key->kode."' ".($key->kode==$pemeriksaan_penunjang ? "selected" : "").">".$key->ket."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                    <button class="terapi btn bg-maroon" type="button">Terapi</button>
                </div>
                <button class="btn btn-success" type="submit">Simpan</button>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modalterapikonsul" style="overflow:hidden;" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:1200px">
        <div class="modal-content">
            <div class="modal-header bg-navy">Terapi Konsul</div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <input type="hidden" name='no_reg' readonly value="<?php echo $no_reg;?>"/>
                        <input type="hidden" name='no_pasien' readonly value="<?php echo $no_pasien;?>"/>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Tanggal</label>
                                <div class="col-md-8">
                                    <input type="text"  class="form-control" name='tanggal_terapi'  value="<?php echo date("d-m-Y");?>" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Obat</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <input type="text" name="obat" class="form-control" autocomplete="off">
                                        <span class="input-group-btn"><button type="button" class="btn btn-success"><i class="fa fa-check"></i></button></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover " id="myTable" >
                            <thead>
                                <tr class="bg-navy">
                                    <th width="10" rowspan="2" class='text-center'>No</th>
                                    <th width=200px rowspan="2" class="text-center">Tanggal</th>
                                    <th width=500px rowspan="2" class="text-center">Nama Obat</th>
                                    <th rowspan="2" class="text-center" width="100">Aturan Pakai</th>
                                    <th rowspan="2" width="100" class="text-center">Waktu</th>
                                    <th colspan="5" class="text-center">Cara</th>
                                    <th rowspan="2" class="text-center">Qty</th>
                                    <th rowspan="2" class="text-center">Satuan</th>
                                    <th rowspan="2" class="text-center">&nbsp;</th>
                                </tr>
                                <tr class="bg-navy">
                                    <th width=100px>Pagi</th>
                                    <th width=100px>Siang</th>
                                    <th width=100px>Sore</th>
                                    <th width=100px>Malem</th>
                                    <th width=100px>Lainnya</th>
                                </tr>
                            </thead>
                            <tbody class="list"></tbody>
                        </table>
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
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
</style>