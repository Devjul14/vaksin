<script>
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
        $(".pilih").click(function(){
            var id = $(this).attr("href");
            var back = $("[name='back").val();
            window.location = "<?php echo site_url('perawat/inap')?>/"+id+"/"+back;
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
            var no_reg = $(".bg-gray").attr("no_reg");
            window.location = "<?php echo site_url('perawat/listpindahkamar')?>/"+no_reg;
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
                <input type="hidden" name="back" value="<?php echo $back ?>">
                <thead>
                    <tr class="bg-navy">
                        <th class='text-center' rowspan="2">#</th>
                        <th width="10%" rowspan='2' class='text-center'>Nomor RM</th>
                        <th rowspan='2' class='text-center'>Nomor REG</th>
                        <th rowspan='2' class="text-center">Nama</th>
                        <th rowspan='2' class='text-center'>Alamat</th>
                        <th class='text-center' colspan="2">Ruangan</th>
                        <th class='text-center' colspan="2">Kelas</th>
                        <th class='text-center' colspan="2">Kamar</th>
                        <th class='text-center'colspan="2">No. Bed</th>
                        <th class='text-center' rowspan="2">Golongan Pasien</th>
                    </tr>
                    <tr class="bg-navy">
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Dari</th>
                        <th>Ke</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i=0;
                    if ($q->num_rows()>0) {
                        foreach ($q->result() as $row){
                            $i++;
                            echo "<tr>";
                            echo "<td><button class='pilih btn btn-primary' href='".$row->no_rm."/".$row->no_reg."/".$row->id."'>Pilih</button></td>";
                            echo "<td class='text-center'>".$row->no_rm."</td>";
                            echo "<td class='text-center'>".$row->no_reg."</td>";
                            echo "<td>".$row->nama_pasien."</td>";
                            echo "<td>".substr($row->alamat, 0,45)."</td>";
                            echo "<td>".$row->ruanglama."</td>";
                            echo "<td>".$row->ruangbaru."</td>";
                            echo "<td>".$row->kelaslama."</td>";
                            echo "<td>".$row->kelasbaru."</td>";
                            echo "<td>".$row->kode_kamar_lama."</td>";
                            echo "<td>".$row->kode_kamar."</td>";
                            echo "<td>".$row->no_bed_lama."</td>";
                            echo "<td>".$row->no_bed."</td>";
                            echo "<td>".$row->keterangan."</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "BELUM PERNAH PINDAH KAMAR";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>