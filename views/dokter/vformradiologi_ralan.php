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
        gettotal();
        $("[name='bayarsharing'], [name='disc_nominal'], [name='sharing']").mask('000.000.000', {reverse: true});
        $("table#form td:even").css("text-align", "right");
        $("table#form td:odd").css("background-color", "white");
        $("[name='tindakan']").change(function(){
            var no_reg= $("[name='no_reg']").val();
            var jenis= $("[name='jenis']").val();
            var tindakan= $(this).val();
            var dokter_radiologi= $("[name='dokter']").val();
            var radiografer= $("[name='petugas_radiologi']").val();
            var ukuran_foto= $("[name='ukuran_foto']").val();
            var no_foto= $("[name='no_foto']").val();
            var dokter_pengirim= $("[name='pengirim']").val();
            $.ajax({
                url : "<?php echo base_url();?>radiologi/addtindakan",
                method : "POST",
                data : {no_reg: no_reg, jenis: jenis, tindakan: tindakan,dokter_radiologi:dokter_radiologi,radiografer:radiografer,nofoto:no_foto,ukuranfoto:ukuran_foto,dokter_pengirim:dokter_pengirim},
                success: function(data){
                     location.reload();
                }
            });
        });
        $('.back').click(function(){
            window.location = "<?php echo site_url('dokter/pasienigd');?>";
        });
        $('.print').click(function(){
            var no_rm= $("[name='no_rm']").val();
            var no_reg= $("[name='no_reg']").val();
            var url = "<?php echo site_url('kasir/cetakkwitansi');?>/"+no_rm+"/"+no_reg;
            openCenteredWindow(url);
        });
        $('.lunas').click(function(){
            $(".modalnotif").modal("show");
            var total = $("[name='total']").val();
            $(".total").html("Rp. "+total);
        });
        $('.hapus').click(function(){
            var id= $(this).attr("id");
            $.ajax({
                url : "<?php echo base_url();?>radiologi/hapusralan",
                method : "POST",
                data : {id: id},
                success: function(data){
                     location.reload();
                }
            });
        });
        $('.okbayar').click(function(){
            var no_reg= $("[name='no_reg']").val();
            var subtotal= $("[name='subtotal']").val().replace(/\D/g,'');
            var disc_nominal= $("[name='disc_nominal']").val().replace(/\D/g,'');
            var sharing= $("[name='sharing']").val().replace(/\D/g,'');
            var total= $("[name='total']").val().replace(/\D/g,'');
            $.ajax({
                url : "<?php echo base_url();?>kasir/simpantransaksi",
                method : "POST",
                data : {no_reg: no_reg,disc_nominal: disc_nominal, sharing: sharing, total: total},
                success: function(data){
                     location.reload();
                },
                error: function(data){
                    console.log(data);
                }
            });
        });
        $("[name='disc_persen']").keyup(function(evt){
            var subtotal = parseInt($("[name='subtotal']").val().replace(/\D/g,''));
            var disc_persen = parseFloat($(this).val());
            disc_nominal = number_format(disc_persen*subtotal/100,0,',','.');
            $("[name='disc_nominal']").val(disc_nominal);
            gettotal();
            return false;
        });
        $("[name='disc_nominal']").keyup(function(evt){
            if ($(this).val()=="") $("[name='disc_persen']").val("0");
            else {
                var subtotal = parseInt($("[name='subtotal']").val().replace(/\D/g,''));
                var disc_nominal = parseInt($(this).val().replace(/\D/g,''));
                disc_persen = (disc_nominal/subtotal)*100;
                $("[name='disc_persen']").val(disc_persen.toFixed(2));
            }
            gettotal();
            return false;
        });
        $("[name='sharing']").keyup(function(evt){
            gettotal();
            return false;
        });
        $("[name='tindakan']").select2();
        $("select[name='dokter']").select2();
        $("select[name='dokter_pengirim'],select[name='pengirim']").select2();
        $('.dataChange').click(function(evt) {
            evt.preventDefault();
            var dataText = $(this);
            var kode = dataText.attr('id');
            var jenis;
            if (dataText.hasClass("jumlah")){
                var jenis = "jumlah";
            } else 
            if (dataText.hasClass("nofoto")){
                var jenis = "nofoto";
            } else 
            if (dataText.hasClass("ukuranfoto")){
                var jenis = "ukuranfoto";
            }else 
            if (dataText.hasClass("dokter_pengirim")){
                jenis = "dokter_pengirim";
            } else 
            if (dataText.hasClass("petugas")){
                jenis = "petugas";
            } else 
            if (dataText.hasClass("radiografer")){
                jenis = "radiografer";
            }
            if (jenis=='petugas'){
                var id_dokter = dataText.attr('id_dokter');
                var result = getdokter(id_dokter);
                var dataInputField = $(result);
            }
            else
            if (jenis=='dokter_pengirim'){
                var id_dokter = dataText.attr('id_dokter');
                var result = getdokter_pengirim(id_dokter);
                var dataInputField = $(result);
            }
            else
            if (jenis=='radiografer'){
                var radiografer = dataText.attr('radiografer');
                var result = getradiografer(radiografer);
                var dataInputField = $(result);
            }
            else
                var dataContent = dataText.text().trim();
            dataText.before(dataInputField).hide();
            if (jenis=='petugas' || jenis=='radiografer' || jenis=='dokter_pengirim'){
                dataInputField.select2();
                dataInputField.focus().select().change(function(){
                    var inputval = dataInputField.val()
                    changeData(inputval,kode,jenis);
                    $(this).remove();
                    dataText.show();
                }).keyup(function(evt) {
                    if (evt.keyCode == 13) {
                        var inputval = dataInputField.val()
                        changeData(inputval,kode,jenis);
                        $(this).remove();
                        dataText.show();
                    }
                });
            } else {
                var dataInputField = $('<input type="text" value="' + dataContent + '" class="form-control" />');
                dataText.before(dataInputField).hide();
                dataInputField.focus().blur(function(){
                    if (jenis=="nofoto" || jenis=="ukuranfoto")
                        var inputval = dataInputField.val();
                    else
                        var inputval = dataInputField.val().replace(/\D/g,'');
                    changeData(inputval,kode,jenis);
                    $(this).remove();
                    dateText.show();
                }).keyup(function(evt) {
                    if (evt.keyCode == 13) {
                        if (jenis=="nofoto" || jenis=="ukuranfoto")
                            var inputval = dataInputField.val();
                        else
                            var inputval = dataInputField.val().replace(/\D/g,'');
                        changeData(inputval,kode,jenis);
                        $(this).remove();
                        dateText.show();
                    }
                });
            }
        });
    });
    function gettotal(){
        var subtotal = $("[name='subtotal']").val().replace(/\D/g,'');
        var disc_nominal = $("[name='disc_nominal']").val().replace(/\D/g,'');
        var sharing = $("[name='sharing']").val().replace(/\D/g,'');
        var total = subtotal-disc_nominal-sharing;
        $("[name='total']").val(number_format(total,0,',','.'));
    }
    var changeData = function(value,id,jenis){
        $.ajax({
            url: "<?php echo site_url('radiologi/changedata_ralan');?>/"+jenis, 
            type: 'POST', 
            data: {id: id,value: value}, 
            success: function(){
                location.reload();
            }
        });
    };
    function getdokter(val){
        var result = false;
        $.ajax({
            url: "<?php echo site_url('radiologi/getdokter_radiologi');?>", 
            type: 'POST',
            async: false, 
            success: function(data){
                var html = "<select name='petugas' class='selectpetugas form-control'>";
                html += "<option value=''>---Pilih Petugas/Dokter---</option>";
                $.each(JSON.parse(data), function(key, value){
                    html += "<option value='"+value.id_dokter+"' "+(val==value.id_dokter ? "selected" : "")+">"+value.nama_dokter+"</option>";
                })
                html += "</select>";
                result = html;
            }
        });
        return result;
    };
    function getdokter_pengirim(val){
        var result = false;
        $.ajax({
            url: "<?php echo site_url('radiologi/getdokter');?>", 
            type: 'POST',
            async: false, 
            success: function(data){
                var html = "<select name='petugas' class='selectpengirim form-control'>";
                html += "<option value=''>---Pilih Petugas/Dokter---</option>";
                $.each(JSON.parse(data), function(key, value){
                    html += "<option value='"+value.id_dokter+"' "+(val==value.id_dokter ? "selected" : "")+">"+value.nama_dokter+"</option>";
                })
                html += "</select>";
                result = html;
            }
        });
        return result;
    };
    function getradiografer(val){
        var result = false;
        $.ajax({
            url: "<?php echo site_url('radiologi/getradiografer');?>", 
            type: 'POST',
            async: false, 
            success: function(data){
                var html = "<select name='radiografer' class='selectpetugas form-control'>";
                html += "<option value=''>---Pilih Radiografer---</option>";
                $.each(JSON.parse(data), function(key, value){
                    html += "<option value='"+value.nip+"' "+(val==value.nip ? "selected" : "")+">"+value.nama+"</option>";
                })
                html += "</select>";
                result = html;
            }
        });
        return result;
    };
    function number_format (number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
<?php
    if($q->num_rows()>0){
        $data = $q->row();
        $disc_nominal = $data->jumlah_disc;
        $sharing = $data->jumlah_sharing;
        $total = $data->jumlah_bayar;
        $disc_persen = round($disc_nominal/($disc_nominal+$sharing+$total),2)*100;
        // $disabled = "disabled";
        $disabled = "";
        $disabled_print = "";
        $tgl_pembayaran = "Tanggal pembayaran -> ".date("d-m-Y",strtotime($data->tanggal));
    } else {
        $disc_nominal = $sharing = $total = $disc_persen = 0;
        $disabled = $tgl_pembayaran = "";
        $disabled_print = "disabled";
    }
?>
<div class="col-md-12">
    <div class="box box-primary">
        <?php
            // echo form_open("radiologi/simpanradiologi",array("id"=>"formsave","class"=>"form-horizontal"));
        ?>
        <div class="form-horizontal">
            <div class="box-body">
            	<div class="form-group">
                    <label class="col-md-2 control-label">No. Reg</label>
                    <div class="col-md-2">
                        <input type="hidden" name="jenis" value="<?php echo $row->jenis;?>">
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
                <!-- <div class="form-group">
                    <label class="col-md-2 control-label">Poliklinik</label>
                    <div class="col-md-10">
                        <input type="hidden" class="form-control" name='kode_poli' readonly value="<?php echo $row->tujuan_poli;?>"/>
                        <input type="text" class="form-control" name='poliklinik' readonly value="<?php echo $row->poli;?>"/>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-md-2 control-label">Dokter</label>
                    <div class="col-md-2">
                        <!-- <input type="text" class="form-control"  name='dokter' readonly value="<?php echo $row->nama_dokter;?>"/> -->
                        <select class="form-control" name="dokter">
                            <?php
                                foreach ($d->result() as $dk) {
                                    echo "
                                        <option value='".$dk->id_dokter."' ".($dk->id_dokter==$row->dokter_poli ? "selected" : "").">".$dk->nama_dokter."</option>
                                    ";
                                }
                            ?>
                        </select>
                        <input type="hidden" class="form-control" name='radiologi' readonly value="<?php echo $row->radiologi;?>"/>
                    </div>
                    <label class="col-md-1 control-label">Radiografer</label>
                    <div class="col-md-2">
                        <!-- <input type="text" class="form-control"  name='petugas_radiologi' value="<?php echo $row->petugas_radiologi;?>"/> -->
                        <select class="form-control" name="petugas_radiologi">
                            <?php
                                foreach ($r->result() as $rg) {
                                    echo "
                                        <option value='".$rg->nip."' ".($rg->nip==$row->petugas_radiologi ? "selected" : "").">".$rg->nama."</option>
                                    ";
                                }
                            ?>
                        </select>
                    </div>
                    <label class="col-md-2 control-label">Dokter Pengirim</label>
                    <div class="col-md-3">
                        <!-- <input type="text" readonly class="form-control"  name='dokter' readonly value="<?php echo $row->nama_dokter;?>"/> -->
                        <select class="form-control" name="pengirim">
                            <option>---</option>
                            <?php
                                foreach ($d1->result() as $dk1) {
                                    echo "
                                        <option value='".$dk1->id_dokter."' ".($dk1->id_dokter==$row->dokter_pengirim ? "selected" : "").">".$dk1->nama_dokter."</option>
                                    ";
                                }
                            ?>
                        </select>
                        <input type="hidden" readonly class="form-control" name='radiologi' readonly value="<?php echo $row->radiologi;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Ukuran Foto</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control"  name='ukuran_foto'/>
                    </div>
                    <label class="col-md-1 control-label">No Foto</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control"  name='no_foto'/>
                    </div>
                    <label class="col-md-2 control-label">Diagnosa</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control"  name='diagnosa' value="<?php echo $row->diagnosa;?>"/>
                    </div>
                </div>
            </div>
            <!-- <div class="box-footer">
                <div class="pull-right">
                    <button class="btn btn-primary" type="submit"> Simpan</button>
                </div>
            </div> -->
        </div>
        <?php //echo form_close(); ?>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <table class="table table-bordered table-hover " id="myTable" >
                <thead>
                    <tr class="bg-navy">
                        <th width="10" class='text-center'>No</th>
                        <th class="text-center">Tarif</th>
                        <th class="text-center">Dokter</th>
                        <th class="text-center">Radiografer</th>
                        <th class="text-center">No Foto</th>
                        <th class="text-center">Uk Foto</th>
                        <th class="text-center">Dok. Pengirim</th>
                        <th class='text-center'>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        $subtotal = 0;
                        foreach($k->result() as $data){
                            $subtotal += $data->jumlah;
                            echo "<tr>";
                            echo "<td>".$i++."</td>";
                            echo "<td>".$data->nama_tindakan." <div class='pull-right'><button id='".$data->id."' class='hapus btn btn-sm btn-danger'><i class='fa fa-minus'></i></div></td>";
                            echo "<td class='text-left'><a href='#' class='petugas dataChange' id='".$data->id."' id_dokter='".$data->kode_petugas."'>".($data->kode_petugas=="" ? "---Pilih Petugas/Dokter---" : (isset($dokter[$data->kode_petugas]) ? $dokter[$data->kode_petugas] : "---Pilih Petugas/Dokter---") )."</a></td>";
                            echo "<td class='text-left'><a href='#' class='radiografer dataChange' id='".$data->id."' radiografer='".$data->analys."'>".($data->analys=="" ? "---Pilih Radiografer---" : (isset($radiografer[$data->analys]) ? $radiografer[$data->analys] : "---Pilih Radiografer---") )."</a></td>";
                            echo "<td class='text-right'><a href='#' class='dataChange nofoto' id='".$data->id."'>".($data->nofoto=="" ? "-" : $data->nofoto)."</a></td>";
                            echo "<td class='text-right'><a href='#' class='dataChange ukuranfoto' id='".$data->id."'>".($data->ukuranfoto=="" ? "-" : $data->ukuranfoto)."</a></td>";
                            echo "<td class='text-left'><a href='#' class='dokter_pengirim dataChange' id='".$data->id."' id_dokter='".$data->dokter_pengirim."'>".($data->dokter_pengirim=="" ? "---Pilih Petugas/Dokter---" : (isset($dokter_pengirim[$data->dokter_pengirim]) ? $dokter_pengirim[$data->dokter_pengirim] : "---Pilih Petugas/Dokter---") )."</a></td>";
                            echo "<td class='text-right'><a href='#' class='dataChange' id='".$data->id."'>".number_format($data->jumlah,0,'.','.')."</a></td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" style="vertical-align: middle" ><span class="pull-right">Subtotal</span></th>
                        <th style="vertical-align: middle" ><input type="text" readonly name="subtotal" class="form-control text-right" value="<?php echo number_format($subtotal,0,',','.');?>"></th>
                    </tr>
                    <tr>
                        <th colspan="7" style="vertical-align: middle" ><span class="pull-right">Disc</span></th>
                        <th width="250px" style="vertical-align: middle" >
                            <div class="row">
                                <div class="col-sm-5">
                                    <input type="text" name="disc_persen" class="form-control text-right" value="<?php echo $disc_persen;?>">
                                </div>
                                <div class="col-sm-7">  
                                    <input type="text" name="disc_nominal" class="form-control text-right" value="<?php echo number_format($disc_nominal,0,',','.');?>">
                                </div>
                            </div>
                        </th>
                    </tr>
                    <?php if ($row->status_bayar=="TAGIH"): ?>
                        <tr>
                            <th colspan="7" style="vertical-align: middle" ><span class="pull-right">Sharing</span></th>
                            <th style="vertical-align: middle" ><input type="text" readonly name="sharing" class="form-control text-right" value="<?php echo number_format($sharing,0,',','.');?>"></th>
                        </tr>
                    <?php else: ?>
                        <input type="hidden" name="sharing">
                    <?php endif ?>
                    <tr>
                        <th colspan="7" style="vertical-align: middle" ><?php echo $tgl_pembayaran;?><span class="pull-right">Total</span></th>
                        <th style="vertical-align: middle" >
                            <input type="text" readonly name="total" class="form-control text-right" value="<?php echo number_format($total,0,',','.');?>">
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="box-footer">
            <div class="col-sm-5">
                <select class="form-control" name="tindakan">
                    <option value="">---Pilih Tindakan---</option>
                    <?php 
                        foreach ($t->result() as $key) {
                            echo '<option value="'.$key->id_tindakan.'">'.$key->nama_tindakan.'</option>';
                        }
                    ?>
                </select>    
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <button class="back btn btn-warning" type="button"><i class="fa fa-arrow-left"></i> Back</button>

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
</style>