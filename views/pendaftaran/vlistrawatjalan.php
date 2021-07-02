<link rel="stylesheet" href="<?php echo base_url(); ?>css/print.css">
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
<style>
    .dropbtn {
        color: white;
        padding: 14px, 8px, 14px, 8px;
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
        min-width: 260px;
        bottom: 31px;
        z-index: 1;
    }

    .dropup-content a {
        color: black;
        padding: 5px 16px;
        text-decoration: none;
        display: block;
    }

    .dropup-content a:hover {
        background-color: #ccc
    }

    .sidenav a:hover {
        background-color: #ccc
    }

    .sidenav:hover {
        background-color: #ccc
    }

    .dropup:hover .dropup-content,
    .sidenav:hover .dropup-content-sidenav {
        display: block;
    }

    .dropup-content-sidenav {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 260px;
        left: 260px;
        margin-top: -32px;
        z-index: 999999;
    }

    /*.dropup:hover .dropbtn {
      background-color: #2980B9;
      }*/
  </style>
  <script>
    var mywindow;

    function openCenteredWindow(url) {
        var width = 1000;
        var height = 650;
        var left = parseInt((screen.availWidth / 2) - (width / 2));
        var top = parseInt((screen.availHeight / 2) - (height / 2));
        var windowFeatures = "width=" + width + ",height=" + height +
        ",status,resizable,left=" + left + ",top=" + top +
        ",screenX=" + left + ",screenY=" + top + ",scrollbars";
        mywindow = window.open(url, "subWind", windowFeatures);
    }

    function pencarian() {
        var cari_no = $("[name='cari_no']").val();
        var status_vaksin = $("[name='status_vaksin']").val();
        $.ajax({
            type: "POST",
            data: {
                cari_no: cari_no,
                status_vaksin: status_vaksin
            },
            url: "<?php echo site_url('pendaftaran/getcaripasien_ralan'); ?>",
            success: function(result) {
                location.reload();
                // window.location = "<?php echo site_url('pendaftaran/rawat_jalan'); ?>";
            },
            error: function(result) {
                alert(result);
            }
        });
    }
    $(document).ready(function(e) {
        $(".upload").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/formuploadpdf_ralan'); ?>/" + id;
            window.location = url;
            return false;
        });
        gettempatvaksin();
        $(".cetakresume").click(function() {
            var id = $(".bg-gray").attr("href");
            id = id.split("/");
            var url = "<?php echo site_url('pendaftaran/cetakresume'); ?>/" + id[0];
            openCenteredWindow(url)
        });
        $(".cppt").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cppt_ralan'); ?>/" + id;
            openCenteredWindow(url);
        });
        $(".search").click(function() {
            var poli_kode = $("[name='poli_kode']").val();
            var poliklinik = $("[name='poliklinik']").val();
            var kode_dokter = $("[name='kode_dokter']").val();
            var dokter = $("[name='dokter']").val();
            var tgl1 = $("[name='tgl1']").val();
            var tgl2 = $("[name='tgl2']").val();
            var status_vaksin = $("select[name='status_vaksin']").val();
            var tempat_vaksin = $("select[name='tempat_vaksin']").val();
            var arrayData = {
                poli_kode: poli_kode,
                poliklinik: poliklinik,
                kode_dokter: kode_dokter,
                dokter: dokter,
                tgl1: tgl1,
                tgl2: tgl2,
                status_vaksin: status_vaksin,
                tempat_vaksin : tempat_vaksin
            };
            $.ajax({
                url: "<?php echo site_url('pendaftaran/search_ralan'); ?>",
                type: 'POST',
                data: arrayData,
                success: function() {
                    location.reload();
                }
            });
        });
        var formattgl = "dd-mm-yy";
        $("input[name='tgl1']").datepicker({
            dateFormat: formattgl,
        });
        $("input[name='tgl2'],[name='batastgl_surat_keterangan_dokter']").datepicker({
            dateFormat: formattgl,
        });
        $("input[name='tgl3'],[name='batastgl_ket_narkoba']").datepicker({
            dateFormat: formattgl,
        });
        $("input[name='tgl4'],[name='batastgl_keterangan_jiwa']").datepicker({
            dateFormat: formattgl,
        });
        $("[name='mulai_surat_istirahat_sakit'],[name='sampai_surat_istirahat_sakit']").datepicker({
            dateFormat: formattgl,
            onSelect: function() {
                if (($("[name='mulai_surat_istirahat_sakit']").val() != "") && ($("[name='sampai_surat_istirahat_sakit']").val() != "")) {
                    var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                    var firstDate = new Date(tgl_barat($("[name='mulai_surat_istirahat_sakit']").val()));
                    var secondDate = new Date(tgl_barat($("[name='sampai_surat_istirahat_sakit']").val()));
                    var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay))) + 1;
                    $("[name='selama_surat_istirahat_sakit']").val(diffDays);
                }
            }
        });
        $(".reset").click(function() {
            var url = "<?php echo site_url('pendaftaran/reset_ralan'); ?>";
            window.location = url;
            return false;
        });
        $(".triage").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('dokter/cetaktriage'); ?>/" + no_reg;
            openCenteredWindow(url);
            return false;
        });
        $(".assesment").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('dokter/cetakigd'); ?>/" + no_reg;
            openCenteredWindow(url);
            return false;
        });
        $(".laporan_mata").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('pendaftaran/cetak_mata') ?>/" + no_reg;
            openCenteredWindow(url);
            return false;
        });
        $(".cetakujifungsi").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetakujifungsi') ?>/" + id;
            openCenteredWindow(url);
            return false;
        });
        $(".erm_pengantarterapi").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetakpengantarterapi') ?>/" + id;
            openCenteredWindow(url);
            return false;
        });
        $(".cetakujifungsi2").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetakujifungsi2') ?>/" + id;
            openCenteredWindow(url);
            return false;
        });
        $(".cetakrehab").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetakrehab') ?>/" + id;
            openCenteredWindow(url);
            return false;
        });
        $(".laporan_pterygium").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('pendaftaran/cetak_pterygium') ?>/" + no_reg;
            openCenteredWindow(url);
            return false;
        });
        $(".erm_rujukan").click(function() {
            var no_rm = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('surat/rujukan_pasien'); ?>/" + no_reg + "/" + no_rm + "/ralan";
            openCenteredWindow(url);
            return false;
        });
        $(".laporan_operasi").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('pendaftaran/cetak_operasi') ?>/" + no_reg;
            openCenteredWindow(url);
            return false;
        });
        $('.pdf').click(function() {
            var no_sep = $(".bg-gray").attr("no_sep");
            if (no_sep == "") {
                alert("Pasien belum memiliki SEP");
            } else {
                var url = "<?php echo site_url('grouper/claimprint_ralan'); ?>/" + no_sep;
                openCenteredWindow(url);
            }
        });
        $(".cetak_barcode").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetakbarcode'); ?>/" + id;
            openCenteredWindow(url);
            return false;
        });
        $(".cari_data").click(function() {
            var url = "<?php echo site_url('pendaftaran'); ?>";
            window.location = url;
            return false;
        });
        $(".cetaksep").click(function() {
            var no_rm = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            var no_bpjs = $(".bg-gray").attr("no_bpjs");
            var no_sep = $(".bg-gray").attr("no_sep");
            var url = "<?php echo site_url('sep/cetaksep'); ?>/" + no_reg + "/" + no_rm + "/" + no_bpjs + "/" + no_sep;
            openCenteredWindow(url);
            return false;
        });
        $('#myTable').fixedHeaderTable({
            height: '450',
            altClass: 'odd',
            footer: true
        });
        $("tr#data:first").addClass("bg-gray");
        var poli = $(".bg-gray").attr("poli");
        var dokter = $(".bg-gray").attr("dokter");
        $("<audio id='suara" + poli + "'></audio>").attr({
            'src': '<?php echo base_url() . 'rekaman/'; ?>' + poli + '.mp3',
            'volume': 0.4,
        }).appendTo("body");
        $("<audio id='suara" + dokter + "'></audio>").attr({
            'src': '<?php echo base_url() . 'rekaman/'; ?>' + dokter + '.mp3',
            'volume': 0.4,
        }).appendTo("body");
        $("table tr#data ").click(function() {
            $("table tr#data ").removeClass("bg-gray");
            $(this).addClass("bg-gray");
            var poli = $(this).attr("poli");
            var dokter = $(this).attr("dokter");
            $("<audio id='suara" + poli + "'></audio>").attr({
                'src': '<?php echo base_url() . 'rekaman/'; ?>' + poli + '.mp3',
                'volume': 0.4,
            }).appendTo("body");
            $("<audio id='suara" + dokter + "'></audio>").attr({
                'src': '<?php echo base_url() . 'rekaman/'; ?>' + dokter + '.mp3',
                'volume': 0.4,
            }).appendTo("body");
        });
        $(".add").click(function() {
            window.location = "<?php echo site_url('pendaftaran/addpasienbaru/y/y') ?>";
            return false;
        });
        $(".edit").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/addpasienbaru/n/n/n') ?>/" + id;
            return false;
        });
        $(".cetak_rekmed").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/cetak_rekmed') ?>/" + id;
            openCenteredWindow(url);
        });
        $(".hapus").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/hapuspasien') ?>/" + id;
            return false;
        });
        $(".cari_no").click(function() {
            $(".modal_cari_no").modal("show");
            $("[name='cari_no']").focus();
            return false;
        });
        $(".artikel").click(function() {
            $(".modal_artikel").modal("show");
            $("[name='artikel']").focus();
            return false;
        });
        $(".share").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            $(".modal_share").modal("show");
            $("[name='no_reg_share']").focus();
            $("input[name='no_pasien_share']").val(no_pasien);
            return false;
        });
        $(".cari_nama").click(function() {
            $(".modal_cari_nama").modal("show");
            $("[name='cari_nama']").focus();
            return false;
        });
        $(".cari_noreg").click(function() {
            $(".modal_cari_noreg").modal("show");
            $("[name='cari_noreg']").focus();
            return false;
        });
        $("[name='cari_nama'], [name='cari_no'], [name='cari_noreg']").keyup(function(e) {
            if (e.keyCode == 13) pencarian();
        });
        $("[name='status_pasien']").change(function() {
            pencarian();
        });
        $(".tmb_cari_nama, .tmb_cari_no, .tmb_cari_noreg").click(function() {
            pencarian();
            return false;
        });
        $(".poli").click(function() {
            var url = "<?php echo site_url('pendaftaran/pilihpoli'); ?>";
            openCenteredWindow(url);
            return false;
        });
        $('.selectall_tindakankedokteran').click(function() {
            var cek = $('.tindakan_kedokteran').find(":selected").text();
            if (cek == "") {
                $('select.tindakan_kedokteran > option').prop('selected', 'selected');
                $(".tindakan_kedokteran").trigger("change");
            } else {
                $('select.tindakan_kedokteran > option').prop('selected', '');
                $(".tindakan_kedokteran").trigger("change");
            }
        });
        $('.selectall_tindakananestesi').click(function() {
            var cek = $('.tindakan_anestesi').find(":selected").text();
            if (cek == "") {
                $('select.tindakan_anestesi > option').prop('selected', 'selected');
                $(".tindakan_anestesi").trigger("change");
            } else {
                $('select.tindakan_anestesi > option').prop('selected', '');
                $(".tindakan_anestesi").trigger("change");
            }
        });
        $('.selectall_tindakantransfusi').click(function() {
            var cek = $('.tindakan_transfusi').find(":selected").text();
            if (cek == "") {
                $('select.tindakan_transfusi > option').prop('selected', 'selected');
                $(".tindakan_transfusi").trigger("change");
            } else {
                $('select.tindakan_transfusi > option').prop('selected', '');
                $(".tindakan_transfusi").trigger("change");
            }
        });
        $(".dokter").click(function() {
            var kode_poli = $("input[name='poli_kode']").val()
            var url = "<?php echo site_url('pendaftaran/pilihdokterpoli'); ?>/" + kode_poli;
            openCenteredWindow(url);
            return false;
        });
        $(".konsul").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/konsul') ?>/" + id;
            return false;
        });
        $(".sep").click(function() {
            var id = $(".bg-gray").attr("href");
            var no_bpjs = $(".bg-gray").attr("no_bpjs");
            window.location = "<?php echo site_url('sep/formsep') ?>/" + id + "/" + no_bpjs;
            return false;
        });
        $(".tindakan").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/tindakan') ?>/" + id;
            return false;
        });
        $(".skip").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/layani') ?>/" + id;
            return false;
        });
        $(".indeks").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/indeks') ?>/" + id;
            return false;
        });
        $(".persenpelayanan").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/persenpelayanan') ?>";
            return false;
        });
        $(".cari_noreg_share").click(function() {
            var no_pasien = "-";
            var no_reg = $("input[name='no_reg_share']").val();
            window.location = "<?php echo site_url('pendaftaran/updatetanggal') ?>/" + no_pasien + "/" + no_reg;
            return false;
        });
        $(".terima").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/terima') ?>/" + id;
            return false;
        });
        $(".general_concent").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var no_rm = $(".bg-gray").attr("no_pasien");
            var url = "<?php echo site_url('persetujuan/cetakpersetujuan_all'); ?>/" + no_reg + "/" + no_rm + "/ralan";
            openCenteredWindow(url);
            return false;
        });
        $(".terima_pasien").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/terima_pasien') ?>/" + id;
            return false;
        });
        $(".pulang").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            $("input[name='no_pasien_pulang']").val(no_pasien);
            $("input[name='no_reg_pulang']").val(no_reg);
            $.ajax({
                type: "POST",
                data: {
                    no_pasien: no_pasien,
                    no_reg: no_reg
                },
                url: "<?php echo site_url('pendaftaran/getralan_detail'); ?>",
                success: function(result) {
                    var value = JSON.parse(result);
                    console.log(value);
                    $(".modal-pulang").modal("show");
                    $("[name='no_sep']").val(value.no_sjp);
                    $("[name='jam_pulang']").val("<?php echo date("H:i"); ?>");
                    $(".jam_meninggal").addClass("hide");
                    $("[name='jam_meninggal']").val("");
                    if (value.tanggal_pulang != null) {
                        $("[name='no_surat_pulang']").val((value.no_surat_pulang == null || value.no_surat_pulang == "" ? no_reg : value.no_surat_pulang));
                        $("[name='tanggal_pulang']").val(tgl_indo(value.tanggal_pulang));
                        if (value.jam_keluar != null) {
                            $("[name='jam_pulang']").val(value.jam_keluar);
                        }
                        if (value.status_pulang == "4") {
                            $(".jam_meninggal").removeClass("hide");
                        } else {
                            $(".jam_meninggal").addClass("hide");
                        }
                        $("[name='jam_meninggal']").val(value.jam_meninggal);
                        $(".status_pasien").html("<span class='label label-danger'>Pasien sudah pulang</span>");
                        $('[name=keadaan_pulang] option[value=' + value.keadaan_pulang + ']').prop("selected", true);
                        $('[name=status_pulang] option[value=' + value.status_pulang + ']').prop("selected", true);
                    } else {
                        $("[name='no_surat_pulang']").val(no_reg);
                        $("[name='tanggal_pulang']").val('');
                        $("[name='tanggal_kontrol']").val('');
                        $(".status_pasien").html("");
                        $('[name=keadaan_pulang] option[value=1]').prop("selected", true);
                        $('[name=status_pulang] option[value=1]').prop("selected", true);
                    }
                },
                error: function(result) {
                    alert(result);
                }
            });
            return false;
        });
        $(".tindakan_kedokteran").change(function() {
            var keterangan_kedokteran = "";
            var no_reg = $(".bg-gray").attr("no_reg");
            $.ajax({
                type: "POST",
                data: {
                    no_reg: no_reg
                },
                url: "<?php echo site_url('pendaftaran/getpasien_tindakan'); ?>",
                success: function(result) {
                    var val = JSON.parse(result);
                    var result = val[0];
                    var keterangan_kedokteran_array = {};
                    if (result != undefined) {
                        $.each(result.keterangan_tindakan_kedokteran.split("|"), function(i, e) {
                            keterangan_kedokteran_array[i] = e;
                        });
                    }
                    $.each($(".tindakan_kedokteran option:selected"), function(i, e) {
                        keterangan_kedokteran += '<div class="form-group">';
                        keterangan_kedokteran += '    <label class="col-md-4 control-label">&nbsp;&nbsp;' + (i + 1) + ". " + e.text + '</label>';
                        keterangan_kedokteran += '    <div class="col-md-8">';
                        keterangan_kedokteran += '        <input type="text" name="keterangan_kedokteran" class="form-control ket_kedokteran" value="' + (keterangan_kedokteran_array[i] == undefined ? "" : keterangan_kedokteran_array[i]) + '">';
                        keterangan_kedokteran += '    </div>';
                        keterangan_kedokteran += '</div>';
                    });
                    $(".keterangan_kedokteran").html(keterangan_kedokteran);
                }
            });
        })
        $(".tindakan_anestesi").change(function() {
            var keterangan_anestesi = "";
            var no_reg = $(".bg-gray").attr("no_reg");
            $.ajax({
                type: "POST",
                data: {
                    no_reg: no_reg
                },
                url: "<?php echo site_url('pendaftaran/getpasien_tindakan'); ?>",
                success: function(result) {
                    var val = JSON.parse(result);
                    var result = val[0];
                    var keterangan_anestesi_array = {};
                    if (result != undefined) {
                        $.each(result.keterangan_tindakan_anestesi.split("|"), function(i, e) {
                            keterangan_anestesi_array[i] = e;
                        });
                    }
                    $.each($(".tindakan_anestesi option:selected"), function(i, e) {
                        keterangan_anestesi += '<div class="form-group">';
                        keterangan_anestesi += '    <label class="col-md-4 control-label">&nbsp;&nbsp;' + (i + 1) + ". " + e.text + '</label>';
                        keterangan_anestesi += '    <div class="col-md-8">';
                        keterangan_anestesi += '        <input type="text" name="keterangan_anestesi" class="form-control ket_anestesi" value="' + (keterangan_anestesi_array[i] == undefined ? "" : keterangan_anestesi_array[i]) + '">';
                        keterangan_anestesi += '    </div>';
                        keterangan_anestesi += '</div>';
                    });
                    $(".keterangan_anestesi").html(keterangan_anestesi);
                }
            });
        })
        $(".tindakan_transfusi").change(function() {
            var keterangan_transfusi = "";
            var no_reg = $(".bg-gray").attr("no_reg");
            $.ajax({
                type: "POST",
                data: {
                    no_reg: no_reg
                },
                url: "<?php echo site_url('pendaftaran/getpasien_tindakan'); ?>",
                success: function(result) {
                    var val = JSON.parse(result);
                    var result = val[0];
                    var keterangan_transfusi_array = {};
                    if (result != undefined) {
                        $.each(result.keterangan_tindakan_transfusi.split("|"), function(i, e) {
                            keterangan_transfusi_array[i] = e;
                        });
                    }
                    $.each($(".tindakan_transfusi option:selected"), function(i, e) {
                        keterangan_transfusi += '<div class="form-group">';
                        keterangan_transfusi += '    <label class="col-md-4 control-label">&nbsp;&nbsp;' + (i + 1) + ". " + e.text + '</label>';
                        keterangan_transfusi += '    <div class="col-md-8">';
                        keterangan_transfusi += '        <input type="text" name="keterangan_transfusi" class="form-control ket_transfusi" value="' + (keterangan_transfusi_array[i] == undefined ? "" : keterangan_transfusi_array[i]) + '">';
                        keterangan_transfusi += '    </div>';
                        keterangan_transfusi += '</div>';
                    });
                    $(".keterangan_transfusi").html(keterangan_transfusi);
                }
            })
        })
        $(".tindakan_medis").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            $(".modal-tindakan-medis").modal("show");
            $("input[name='no_reg_tindakan']").val(no_reg);
            $("[name='pelaksana_tindakan']").val("");
            $("[name='pemberi_informasi']").val("");
            $("[name='saksirs']").val("");
            $("[name='nama']").val("");
            $("[name='umur']").val("");
            $("[name='alamat']").val("");
            $("[name='tindakan_kedokteran']").val("");
            $("[name='tindakan_anestesi']").val("");
            $("[name='tindakan_transfusi']").val("");
            $(".keterangan_kedokteran").html("");
            $(".keterangan_anestesi").html("");
            $(".keterangan_transfusi").html("");
            $("[name='pelaksana_tindakan'],[name='pemberi_informasi'],[name='saksirs'],[name='tindakan_kedokteran'],[name='tindakan_anestesi'],[name='tindakan_transfusi']").select2();
            $.ajax({
                type: "POST",
                data: {
                    no_reg: no_reg
                },
                url: "<?php echo site_url('pendaftaran/getpasien_tindakan'); ?>",
                success: function(result) {
                    var val = JSON.parse(result);
                    var result = val[0];
                    $("[name='pelaksana_tindakan'] option[value='" + result.pelaksana_tindakan + "']").prop("selected", true);
                    $("[name='pemberi_informasi'] option[value='" + result.kategori_pemberi_informasi + "/" + result.pemberi_informasi + "']").prop("selected", true);
                    $("[name='saksirs'] option[value='" + result.kategori_saksirs + "/" + result.saksirs + "']").prop("selected", true);
                    $("[name='nama']").val(result.nama);
                    $("[name='umur']").val(result.umur);
                    $("[name='alamat']").val(result.alamat);
                    var keterangan_kedokteran_array = {};
                    $.each(result.keterangan_tindakan_kedokteran.split("|"), function(i, e) {
                        keterangan_kedokteran_array[i] = e;
                    });
                    var keterangan_anestesi_array = {};
                    $.each(result.keterangan_tindakan_anestesi.split("|"), function(i, e) {
                        keterangan_anestesi_array[i] = e;
                    });
                    var keterangan_transfusi_array = {};
                    $.each(result.keterangan_tindakan_transfusi.split("|"), function(i, e) {
                        keterangan_transfusi_array[i] = e;
                    });
                    var keterangan_kedokteran_array = {};
                    $.each(result.keterangan_tindakan_kedokteran.split("|"), function(i, e) {
                        keterangan_kedokteran_array[i] = e;
                    });
                    $.each(result.tindakan_kedokteran.split(","), function(i, e) {
                        $(".tindakan_kedokteran option[value='" + e + "']").prop("selected", true);
                    });
                    var keterangan_kedokteran = "";
                    $.each($(".tindakan_kedokteran option:selected"), function(i, e) {
                        keterangan_kedokteran += '<div class="form-group">';
                        keterangan_kedokteran += '    <label class="col-md-4 control-label">&nbsp;&nbsp;' + (i + 1) + ". " + e.text + '</label>';
                        keterangan_kedokteran += '    <div class="col-md-8">';
                        keterangan_kedokteran += '        <input type="text" name="keterangan" class="form-control ket_kedokteran" value="' + keterangan_kedokteran_array[i] + '">';
                        keterangan_kedokteran += '    </div>';
                        keterangan_kedokteran += '</div>';
                    });
                    $(".keterangan_kedokteran").html(keterangan_kedokteran);
                    $.each(result.tindakan_anestesi.split(","), function(i, e) {
                        $(".tindakan_anestesi option[value='" + e + "']").prop("selected", true);
                    });
                    var keterangan_anestesi = "";
                    $.each($(".tindakan_anestesi option:selected"), function(i, e) {
                        keterangan_anestesi += '<div class="form-group">';
                        keterangan_anestesi += '    <label class="col-md-4 control-label">&nbsp;&nbsp;' + (i + 1) + ". " + e.text + '</label>';
                        keterangan_anestesi += '    <div class="col-md-8">';
                        keterangan_anestesi += '        <input type="text" name="keterangan" class="form-control ket_anestesi" value="' + keterangan_anestesi_array[i] + '">';
                        keterangan_anestesi += '    </div>';
                        keterangan_anestesi += '</div>';
                    });
                    $(".keterangan_anestesi").html(keterangan_anestesi);
                    $.each(result.tindakan_transfusi.split(","), function(i, e) {
                        $(".tindakan_transfusi option[value='" + e + "']").prop("selected", true);
                    });
                    var keterangan_transfusi = "";
                    $.each($(".tindakan_transfusi option:selected"), function(i, e) {
                        keterangan_transfusi += '<div class="form-group">';
                        keterangan_transfusi += '    <label class="col-md-4 control-label">&nbsp;&nbsp;' + (i + 1) + ". " + e.text + '</label>';
                        keterangan_transfusi += '    <div class="col-md-8">';
                        keterangan_transfusi += '        <input type="text" name="keterangan" class="form-control ket_transfusi" value="' + keterangan_transfusi_array[i] + '">';
                        keterangan_transfusi += '    </div>';
                        keterangan_transfusi += '</div>';
                    });
                    $(".keterangan_transfusi").html(keterangan_transfusi);
                    $("[name='status_tindakan_anestesi'] option[value='" + result.status_tindakan_anestesi + "']").prop("selected", true);
                    $("[name='status_tindakan_kedokteran'] option[value='" + result.status_tindakan_kedokteran + "']").prop("selected", true);
                    $("[name='pelaksana_tindakan'],[name='pemberi_informasi'],[name='saksirs'],[name='tindakan_kedokteran'],[name='tindakan_anestesi'],[name='tindakan_transfusi']").select2();
                },
                error: function(result) {
                    console.log(result);
                    $("[name='pelaksana_tindakan'],[name='pemberi_informasi'],[name='saksirs'],[name='tindakan_kedokteran'],[name='tindakan_anestesi'],[name='tindakan_transfusi']").select2();
                }
            });
return false;
});
        // $(".simpan_pulang").click(function(){
        //     var no_pasien = $("input[name='no_pasien_pulang']").val();
        //     var no_reg = $("input[name='no_reg_pulang']").val();
        //     var status_pulang = $("select[name='status_pulang']").val();
        //     var keadaan_pulang = $("select[name='keadaan_pulang']").val();
        //     window.location = "<?php echo site_url('pendaftaran/pulang') ?>/"+no_pasien+"/"+no_reg+"/"+keadaan_pulang+"/"+status_pulang;
        //     return false;
        // });
        $("[name='status_pulang']").change(function() {
            var status_pulang = $(this).val();
            if (status_pulang == "4") {
                $(".jam_meninggal").removeClass("hide");
                $("[name='jam_meninggal']").val("<?php echo date("H:i"); ?>");
            } else {
                $(".jam_meninggal").addClass("hide");
                $("[name='jam_meninggal']").val("");
            }
        })
        $(".simpan_pulang").click(function() {
            var no_pasien = $("input[name='no_pasien_pulang']").val();
            var no_reg = $("input[name='no_reg_pulang']").val();
            var jam_keluar = $("[name='jam_pulang']").val();
            var jam_meninggal = $("[name='jam_meninggal']").val();
            var status_pulang = $("[name='status_pulang']").val();
            var no_surat_pulang = $("[name='no_surat_pulang']").val();
            var keadaan_pulang = $("[name='keadaan_pulang']").val();
            var no_sep = $("[name='no_sep']").val();
            if (status_pulang != "" && no_surat_pulang != "" && keadaan_pulang != "" && no_sep != "") {
                $.ajax({
                    type: "POST",
                    data: {
                        no_pasien: no_pasien,
                        no_reg: no_reg,
                        status_pulang: status_pulang,
                        no_surat_pulang: no_surat_pulang,
                        keadaan_pulang: keadaan_pulang,
                        no_sep: no_sep,
                        jam_keluar: jam_keluar,
                        jam_meninggal: jam_meninggal
                    },
                    url: "<?php echo site_url('pendaftaran/pulang_ralan'); ?>",
                    success: function(result) {
                        location.reload();
                    },
                    error: function(result) {
                        alert(result);
                    }
                });
            } else {
                alert("Lengkapi data dengan benar !!!");
            }
        });
        $(".erm_suratistirahatsakit").click(function() {
            var no_rm = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('surat/suratistirahatsakit'); ?>/" + no_reg + "/" + no_rm + "/ralan";
            openCenteredWindow(url);
            return false;
        });
        $(".erm_suratketerangandokter").click(function() {
            var no_rm = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('surat/suratketerangandokter'); ?>/" + no_reg + "/" + no_rm + "/ralan";
            openCenteredWindow(url);
            return false;
        });
        $(".erm_narkoba").click(function() {
            var no_rm = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('pendaftaran/cetaknarkoba'); ?>/" + no_reg + "/" + no_rm + "/ralan";
            openCenteredWindow(url);
            return false;
        });
        $(".erm_jiwa").click(function() {
            var no_rm = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('pendaftaran/cetakjiwa'); ?>/" + no_reg + "/" + no_rm + "/ralan";
            openCenteredWindow(url);
            return false;
        });
        $(".gudang").click(function() {
            var id = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/gudang') ?>/" + id;
            return false;
        });
        $(".layani").click(function() {
            // var id = $(".bg-gray").attr("href");
            // window.location = "<?php echo site_url('pendaftaran/layani') ?>/"+id;
            var no_antrian = $(".bg-gray").attr("no_antrian");
            simpanpanggil();
            mulai(no_antrian, "layani");
            return false;
        });
        $(".rtpelayanan").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/rtpelayanan'); ?>/" + id;
            openCenteredWindow(url);
        });
        $(".rttunggu").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/rttunggu'); ?>/" + id;
            openCenteredWindow(url);
        });
        $(".pindahstatus").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/pindahstatus_ralan'); ?>/" + id;
            window.location = url ;
            return false;
        });
        $(".rt_poliklinik").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/rt_poliklinik'); ?>/" + id;
            openCenteredWindow(url);
        });
        $(".rtrm").click(function() {
            var id = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('pendaftaran/rtrm'); ?>/" + id;
            openCenteredWindow(url);
        });
        $(".laporan_tindakan").click(function() {
            var id = $(".bg-gray").attr("href");
            var poli = $(".bg-gray").attr("poli");
            window.location = "<?php echo site_url('pendaftaran/laporan_tindakan'); ?>/" + id + "/" + poli;
            return false;
        });
        $(".mcu").click(function() {
            var id = $(".bg-gray").attr("href");
            var poli = $(".bg-gray").attr("poli");
            window.location = "<?php echo site_url('pendaftaran/mcu'); ?>/" + id + "/" + poli;
            return false;
        });
        $(".panggil").click(function() {
            var no_antrian = $(".bg-gray").attr("no_antrian");
            simpanpanggil();
            mulai(no_antrian, "panggil");
            return false;
        });
        $(".cetakcovid1").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var url = "<?php echo site_url('lab/cetak_covid'); ?>/" + no_reg;
            openCenteredWindow(url);
            return false;
        });
        $(".cetakcovid2").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var jenis_kelamin = $(".bg-gray").attr("jenis_kelamin");
            var url = "<?php echo site_url('lab/cetak_covid2');?>/" + no_reg + "/" + jenis_kelamin;
            openCenteredWindow(url);
            return false;
        });
        $(".ekspertisiradiologi").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/ekspertisiradiologi_ralan'); ?>/" + no_rm;
            return false;
        });
        $(".ekspertisilab").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/ekspertisilab_ralan'); ?>/" + no_rm;
            return false;
        });
        $(".ekspertisipa").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/ekspertisipa_ralan'); ?>/" + no_rm;
            return false;
        });
        $(".ekspertisigizi").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/ekspertisigizi_ralan'); ?>/" + no_rm;
            return false;
        });
        $(".obat").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/apotek_ralan'); ?>/" + no_rm;
            return false;
        });
        $(".ujifungsi").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/ujifungsi'); ?>/" + no_rm;
            return false;
        });
        $(".view_pembayaran").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            window.location = "<?php echo site_url('pendaftaran/viewpembayaran_ralan') ?>/" + no_rm;
            return false;
        });

        $(".batal").click(function() {
            $(".rejected").show();
        });
        // $(".reject").click(function(){
        //     $(".rejected").show();
        // });
        $(".tidak_approved").click(function() {
            $(".approved").hide();
        });
        $(".tidak_rejected").click(function() {
            $(".rejected").hide();
        });
        $(".ya_rejected").click(function() {
            var alasan = $("[name='alasan']").val();
            var id = $(".bg-gray").attr("href");
            var poli = $(".bg-gray").attr("poli");
            var password = $("[name='password_batal']").val();
            $.ajax({
                type: "POST",
                data: {
                    alasan: alasan,
                    password: password,
                    poli: poli
                },
                url: "<?php echo site_url('pendaftaran/batal'); ?>/" + id + "/ralan",
                success: function(result) {
                    location.reload();
                    // console.log(result);
                },
                error: function(result) {
                    console.log(result);
                }
            });
            return false;
        });
        $(".simpan_tindakan").click(function() {
            var no_reg = $("[name='no_reg_tindakan']").val();
            var pelaksana_tindakan = $("[name='pelaksana_tindakan']").val();
            var pemberi_informasi = $("[name='pemberi_informasi']").val();
            var saksirs = $("[name='saksirs']").val();
            var tindakan_kedokteran = $("[name='tindakan_kedokteran']").val();
            var status_tindakan_kedokteran = $("[name='status_tindakan_kedokteran']").val();
            var status_tindakan_anestesi = $("[name='status_tindakan_anestesi']").val();
            var tindakan_anestesi = $("[name='tindakan_anestesi']").val();
            var tindakan_transfusi = $("[name='tindakan_transfusi']").val();
            var nama = $("[name='nama']").val();
            var umur = $("[name='umur']").val();
            var alamat = $("[name='alamat']").val();
            var keterangan_kedokteran = "";
            var space = "";
            $.each($(".keterangan_kedokteran .ket_kedokteran"), function(i, e) {
                keterangan_kedokteran += space + $(this).val();
                space = "|";
            });
            var keterangan_anestesi = "";
            var space = "";
            $.each($(".keterangan_anestesi .ket_anestesi"), function(i, e) {
                keterangan_anestesi += space + $(this).val();
                space = "|";
            });
            var keterangan_transfusi = "";
            var space = "";
            $.each($(".keterangan_transfusi .ket_transfusi"), function(i, e) {
                keterangan_transfusi += space + $(this).val();
                space = "|";
            });
            $.ajax({
                type: "POST",
                data: {
                    jenis: "ralan",
                    no_reg: no_reg,
                    pelaksana_tindakan: pelaksana_tindakan,
                    pemberi_informasi: pemberi_informasi,
                    saksirs: saksirs,
                    nama: nama,
                    umur: umur,
                    alamat: alamat,
                    tindakan_kedokteran: tindakan_kedokteran,
                    tindakan_anestesi: tindakan_anestesi,
                    status_tindakan_kedokteran: status_tindakan_kedokteran,
                    status_tindakan_anestesi: status_tindakan_anestesi,
                    tindakan_transfusi: tindakan_transfusi,
                    keterangan_kedokteran: keterangan_kedokteran,
                    keterangan_anestesi: keterangan_anestesi,
                    keterangan_transfusi: keterangan_transfusi
                },
                url: "<?php echo site_url('pendaftaran/simpan_tindakan_medis'); ?>",
                success: function(result) {
                    alert("Data berhasil disimpan");
                    // location.reload();
                },
                error: function(result) {
                    console.log(result);
                }
            });
            return false;
        });
        $(".perawat").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            var id_dokter = $(".bg-gray").attr("id_dokter");
            var url = "<?php echo site_url('perawat/cetakassesmen'); ?>/" + no_rm + "/" + no_reg;
            openCenteredWindow(url);
            return false;
        });
        $(".covid").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            var id_dokter = $(".bg-gray").attr("id_dokter");
            var url = "<?php echo site_url('perawat/cetakcovid'); ?>/" + no_rm + "/igd";
            openCenteredWindow(url);
            return false;
        });
        $(".sksi").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            var url = "<?php echo site_url('suket/suketisolasi'); ?>/" + no_rm + "/ralan";
            openCenteredWindow(url);
            return false;
        });
        $(".erm_kematian").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            var ket_pulang = $(".bg-gray").attr("ket_pulang");
            if (ket_pulang == "Meninggal") {
                var url = "<?php echo site_url('pendaftaran/kematian'); ?>/" + no_reg + "/" + no_rm + "/ralan";
                openCenteredWindow(url);
            } else {
                alert("Pasien Tidak Meninggal");
            }
            return false;
        });
        $(".erm_pc").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            var ket_pulang = $(".bg-gray").attr("ket_pulang");
            if (ket_pulang == "Meninggal") {
                var url = "<?php echo site_url('pendaftaran/pemulsaran_covid'); ?>/" + no_reg + "/" + no_rm + "/ralan";
                openCenteredWindow(url);
            } else {
                alert("Pasien Tidak Meninggal");
            }
            return false;
        });
        $(".sebabkematian").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            var status_pulang = $(".bg-gray").attr("status_pulang");
            if (status_pulang == "4") {
            var url = "<?php echo site_url('dokter/cetaksebabkematian_ralan'); ?>/" + no_reg + "/" + no_rm + "/ralan";
            openCenteredWindow(url);
            } else {
                alert("Pasien Tidak Meninggal");
            }
            return false;
        });
        $(".erm_kelahiran").click(function() {
            var no_rm = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            var berat_badan = $(".bg-gray").attr("berat_badan");
            if (berat_badan != "") {
                var url = "<?php echo site_url('pendaftaran/cetakkelahiran'); ?>/" + no_reg + "/" + no_rm + "/ralan";
                openCenteredWindow(url);
            } else {
                alert("Pasien bukan bayi");
            }
            return false;
        });
        $(".surat_tindakan_kedokteran").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var no_rm = $(".bg-gray").attr("no_pasien");
            var url = "<?php echo site_url('surat/cetaktindakanmedis'); ?>/" + no_reg + "/" + no_rm + "/ralan/kedokteran";
            openCenteredWindow(url);
            return false;
        });
        $(".surat_tindakan_anestesi").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var no_rm = $(".bg-gray").attr("no_pasien");
            var url = "<?php echo site_url('surat/cetaktindakanmedis'); ?>/" + no_reg + "/" + no_rm + "/ralan/anestesi";
            openCenteredWindow(url);
            return false;
        });
        $(".surat_tindakan_transfusi").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var no_rm = $(".bg-gray").attr("no_pasien");
            var url = "<?php echo site_url('surat/cetaktindakanmedis'); ?>/" + no_reg + "/" + no_rm + "/ralan/transfusi";
            openCenteredWindow(url);
            return false;
        });
        $(".kronologi").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("href");
                var text = "Selamat datang di Rumah Sakit Ciremai%0A%0A";
                text += "Kami vaksinator RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk *Download* persetujuan perawatan dan tindakan klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk persetujuan perawatan dan tindakan klik link dibawah ini%0A%0A";
                }
                text += "http://rsciremai.ddns.net/rsciremai/persetujuan/formkronologis_ralan/" + jenis + "/" + no_reg + "/" + no_pasien;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".cetakkronologi").click(function() {
            var no_pasien = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            $(".modal_kronologi").modal("show");
            $("[name='password_kronologis']").focus();
            $("[name='no_pasien_p']").val(no_pasien);
            $("[name='no_reg_p']").val(no_reg);
            return false;
        });
        $(".tmb_kronologi").click(function() {
            // var no_pasien = $("[name='no_pasien_p']").val();
            // var no_reg = $("[name='no_reg_p']").val();
            var no_pasien = $(".bg-gray").attr("href");
            var no_reg = $(".bg-gray").attr("no_reg");
            var password_kronologis = $("[name='password_kronologis']").val();
            $.ajax({
                type: "POST",
                data: {
                    password_petugas: password_kronologis
                },
                url: "<?php echo site_url('persetujuan/cekpetugas_kronologis'); ?>/" + no_reg + "/" + no_pasien,
                success: function(ada) {
                    if (ada == "true") {
                        var site = "<?php echo site_url('persetujuan/cetakkronologis_ralan'); ?>/" + no_reg + "/" + no_pasien;
                        openCenteredWindow(site);
                        $("[name='password_kronologis").val("");
                        $(".modal_kronologi").modal("hide");
                    } else {
                        alert("Password yang Anda masukan salah");
                    }
                },
                error: function(result) {
                    console.log(result);
                }
            });
            return false;
        });
        $(".pulang_paksa").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg_encrypt");
                var no_pasien = $(".bg-gray").attr("href");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk *Download* surat pulang paksa klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk surat pulang paksa klik link dibawah ini%0A%0A";
                }
                text += "http://rsciremai.ddns.net/rsciremai/surat/pulang_paksa/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".send_surat_istirahat_sakit").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                text += "Klik link dibawah ini untuk Form Persetujuan%0A";
                text += "http://rsciremai.ddns.net/rsciremai/surat/suratistirahatsakit/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
            }
        });
        $(".send_surat_keterangan_dokter").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                text += "Klik link dibawah ini untuk Form Persetujuan%0A";
                text += "http://rsciremai.ddns.net/rsciremai/surat/suratketerangandokter/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
            }
        });
        $(".send_ket_narkoba").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                text += "Klik link dibawah ini untuk Form Persetujuan%0A";
                text += "http://rsciremai.ddns.net/rsciremai/pendaftaran/cetaknarkoba/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
            }
        });
        $(".send_ket_jiwa").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                text += "Klik link dibawah ini untuk Form Persetujuan%0A";
                text += "http://rsciremai.ddns.net/rsciremai/pendaftaran/cetakjiwa/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
            }
        });
        $(".pemulasaran").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk *Download* surat pemulasaran klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk surat pemulasaran klik link dibawah ini%0A%0A";
                }
                text += "http://rsciremai.ddns.net/rsciremai/surat/pemulasaran/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".send_artikel").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var slug = $("[name='artikel']").val();
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai.%0A";
                text += "Untuk mendapatkan informasi dari kami klik link dibawah ini%0A%0A";
                text += "http://rsciremai.ddns.net/rsciremai/surat/artikel/" + slug;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".surat_kematian").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var ket_pulang = $(".bg-gray").attr("ket_pulang");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                if (ket_pulang == "Meninggal") {
                    var no_reg = $(".bg-gray").attr("no_reg");
                    var no_pasien = $(".bg-gray").attr("no_pasien");
                    var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                    if (petugas_rm != "") {
                        text += "Untuk *Download* surat pemulasaran klik link dibawah ini%0A%0A";
                    } else {
                        text += "Untuk surat pemulasaran klik link dibawah ini%0A%0A";
                    }
                    text += "http://rsciremai.ddns.net/rsciremai/surat/kematian/" + no_reg + "/" + no_pasien + "/" + jenis;
                    var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                    openCenteredWindow(url);
                    return false;
                } else {
                    alert("Pasien Tidak Meninggal");
                }
            }
        });
        $(".pc").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var ket_pulang = $(".bg-gray").attr("ket_pulang");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                if (ket_pulang == "Meninggal") {
                    var no_reg = $(".bg-gray").attr("no_reg");
                    var no_pasien = $(".bg-gray").attr("no_pasien");
                    var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                    if (petugas_rm != "") {
                        text += "Untuk *Download* surat pemulasaran klik link dibawah ini%0A%0A";
                    } else {
                        text += "Untuk surat pemulasaran klik link dibawah ini%0A%0A";
                    }
                    text += "http://rsciremai.ddns.net/rsciremai/pendaftaran/pemulsaran_covid/" + no_reg + "/" + no_pasien + "/" + jenis;
                    var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                    openCenteredWindow(url);
                    return false;
                } else {
                    alert("Pasien Tidak Meninggal");
                }
            }
        });
        $(".surat_kelahiran").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk *Download* surat keterangan kelahiran klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk surat keterangan kelahiran klik link dibawah ini%0A%0A";
                }
                text += "http://rsciremai.ddns.net/rsciremai/surat/kelahiran/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".whatsapp").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Vaksinasi Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk *Skrining Vaksinasi* klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk persetujuan perawatan dan tindakan klik link dibawah ini%0A%0A";
                }
                text += "http://rsciremai.ddns.net/rsciremai/persetujuan/formpersetujuan/" + jenis + "/" + no_reg + "/" + no_pasien;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".skrining_vaksin").click(function() {
            var phone = $(".bg-gray").attr("nohp");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var id = $(".bg-gray").attr("href");
                var text = "Selamat datang di Vaksinasi Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk *Skrining Vaksinasi* klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk persetujuan perawatan dan tindakan klik link dibawah ini%0A%0A";
                }
                text += "";
                text += "http://vaksinasi.rsciremai.com/whatsapp/formskrining_vaksin/" + id;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".lengkapidata_wa").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                text += "Untuk *Lengkapi Data* klik link dibawah ini%0A%0A";
                text += "http://rsciremai.ddns.net/rsciremai/surat/addpasienbaru/" + no_pasien + "/" + no_reg;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".surat_isolasi").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk *Download* surat keterangan selesai isolasi  klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk surat keterangan selesai isolasi  klik link dibawah ini%0A%0A";
                }
                text += "http://rsciremai.ddns.net/rsciremai/surat/suketisolasi/" + no_pasien + "/" + no_reg + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".surat_bebascovid").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk *Download* surat keterangan bebas covid klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk surat keterangan bebas covid klik link dibawah ini%0A%0A";
                }
                text += "http://rsciremai.ddns.net/rsciremai/surat/suketbebascovid/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".send_tindakan").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                text += "Klik link dibawah ini untuk Form Persetujuan%0A";
                text += "http://rsciremai.ddns.net/rsciremai/surat/tindakanmedis/" + no_reg + "/" + no_pasien + "/" + jenis;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".surat_istirahat_sakit").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            $("input[name='no_reg_surat_istirahat_sakit']").val(no_reg);
            $(".modal-surat-istirahat-sakit").modal("show");
            $("[name='kepada_surat_istirahat_sakit']").val("");
            $("[name='selama_surat_istirahat_sakit']").val("");
            $("[name='mulai_surat_istirahat_sakit']").val("");
            $("[name='sampai_surat_istirahat_sakit']").val("");
            $.ajax({
                type: "POST",
                data: {
                    no_reg: no_reg,
                    no_rm: no_pasien
                },
                url: "<?php echo site_url('pendaftaran/getpasien_istirahat_sakit'); ?>",
                success: function(result) {
                    var val = JSON.parse(result);
                    var result = val[0];
                    $("[name='kepada_surat_istirahat_sakit']").val(result.kepada);
                    $("[name='selama_surat_istirahat_sakit']").val(result.selama);
                    $("[name='mulai_surat_istirahat_sakit']").val(tgl_indo(result.mulai));
                    $("[name='sampai_surat_istirahat_sakit']").val(tgl_indo(result.sampai));
                },
            });
        });
        $(".surat_keterangan_dokter").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            $("[name='no_reg_surat_keterangan_dokter']").val(no_reg);
            $(".modal-surat-keterangan-dokter").modal("show");
            $("[name='hasil_surat_keterangan_dokter1']").val("");
            $("[name='hasil_surat_keterangan_dokter2']").val("");
            $("[name='hasil_surat_keterangan_dokter3']").val("");
            $("[name='untuk_surat_keterangan_dokter']").val("");
            $("[name='batastgl_surat_keterangan_dokter']").val("");
            $.ajax({
                type: "POST",
                data: {
                    no_reg: no_reg,
                    no_rm: no_pasien
                },
                url: "<?php echo site_url('pendaftaran/getpasien_keterangan_dokter'); ?>",
                success: function(result) {
                    var val = JSON.parse(result);
                    var result = val[0];
                    var hasil = result.hasil.split(",");
                    $("[name='hasil_surat_keterangan_dokter1']").val(hasil[0]);
                    $("[name='hasil_surat_keterangan_dokter2']").val(hasil[1]);
                    $("[name='hasil_surat_keterangan_dokter3']").val(hasil[2]);
                    $("[name='untuk_surat_keterangan_dokter']").val(result.untuk);
                    $("[name='batastgl_surat_keterangan_dokter']").val(tgl_indo(result.batastgl));
                },
            });
        });
        $(".keterangan_narkoba").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            $("[name='no_reg_ket_narkoba']").val(no_reg);
            $("[name='no_rm_ket_narkoba']").val(no_pasien);
            $(".modalket-narkoba").modal("show");
            $("[name='anamnesis']").val("");
            $("[name='fisik']").val("");
            $("[name='untuk_ket_narkoba']").val("");
            $("[name='batastgl_ket_narkoba']").val("");
            $.ajax({
                type: "POST",
                data: {
                    no_reg: no_reg,
                    no_rm: no_pasien
                },
                url: "<?php echo site_url('pendaftaran/getpasien_ket_narkoba'); ?>",
                success: function(result) {
                    var val = JSON.parse(result);
                    var result = val[0];
                    var hasil = result.hasil.split(",");
                    $("[name='anamnesis']").val(hasil[0]);
                    $("[name='fisik']").val(hasil[1]);
                    $("[name='untuk_ket_narkoba']").val(result.untuk);
                    $("[name='batastgl_ket_narkoba']").val(tgl_indo(result.batastgl));
                },
            });
        });
        $(".keterangan_jiwa").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $(".bg-gray").attr("no_reg");
            $("[name='no_reg_keterangan_jiwa']").val(no_reg);
            //$("[name='no_rm_keterangan_jiwa']").val(no_pasien);
            $(".modal-keterangan-jiwa").modal("show");
            $("[name='hasil_keterangan_jiwa1']").val("");
            $("[name='hasil_keterangan_jiwa2']").val("");
            $("[name='untuk_keterangan_jiwa']").val("");
            $("[name='batastgl_keterangan_jiwa']").val("");
            $.ajax({
                type: "POST",
                data: {
                    no_reg: no_reg,
                    no_rm: no_pasien
                },
                url: "<?php echo site_url('pendaftaran/getpasien_jiwa'); ?>",
                success: function(result) {
                    var val = JSON.parse(result);
                    var result = val[0];
                    var hasil = result.hasil.split(",");
                    $("[name='hasil_keterangan_jiwa1']").val(hasil[0]);
                    $("[name='hasil_keterangan_jiwa2']").val(hasil[1]);
                    $("[name='untuk_keterangan_jiwa']").val(result.untuk);
                    $("[name='batastgl_keterangan_jiwa']").val(tgl_indo(result.batastgl));
                },
            });
        });
        $(".kirim_resume").click(function() {
            var phone = $(".bg-gray").attr("telpon");
            var petugas_rm = $(".bg-gray").attr("petugas_rm");
            var no_bpjs = $(".bg-gray").attr("no_bpjs");
            var no_sep = $(".bg-gray").attr("no_sep");
            var jenis = "ralan";
            if (phone == "") {
                alert("No. HP belum terisi");
            }
            if (no_sep == "") {
                alert("No. SEP belum ada");
            } else {
                var no_reg = $(".bg-gray").attr("no_reg");
                var no_pasien = $(".bg-gray").attr("no_pasien");
                var text = "Selamat datang di Rumah Sakit Ciremai kami petugas pendaftaran RS Ciremai.%0A";
                if (petugas_rm != "") {
                    text += "Untuk resume klik link dibawah ini%0A%0A";
                } else {
                    text += "Untuk *Download* resume klik link dibawah ini%0A%0A";
                }
                text += "http://rsciremai.ddns.net/rsciremai/surat/cetakresumeralan/" + no_pasien + "/" + no_reg + "/" + no_bpjs + "/" + no_sep;
                var url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + text;
                openCenteredWindow(url);
                return false;
            }
        });
        $(".simpan_surat_istirahat_sakit").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $("[name='no_reg_surat_istirahat_sakit']").val();
            var kepada = $("[name='kepada_surat_istirahat_sakit']").val();
            var selama = $("[name='selama_surat_istirahat_sakit']").val();
            var mulai = $("[name='mulai_surat_istirahat_sakit']").val();
            var sampai = $("[name='sampai_surat_istirahat_sakit']").val();
            $.ajax({
                type: "POST",
                data: {
                    jenis: "ralan",
                    no_reg: no_reg,
                    no_pasien: no_pasien,
                    kepada: kepada,
                    selama: selama,
                    mulai: mulai,
                    sampai: sampai
                },
                url: "<?php echo site_url('pendaftaran/simpan_surat_istirahat_sakit'); ?>",
                success: function(result) {
                    alert("Data berhasil disimpan");
                },
                error: function(result) {
                    console.log(result);
                }
            });
            return false;
        });
        $(".simpan_surat_keterangan_dokter").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $("[name='no_reg_surat_keterangan_dokter']").val();
            var hasil1 = $("[name='hasil_surat_keterangan_dokter1']").val();
            var hasil2 = $("[name='hasil_surat_keterangan_dokter2']").val();
            var hasil3 = $("[name='hasil_surat_keterangan_dokter3']").val();
            var untuk = $("[name='untuk_surat_keterangan_dokter']").val();
            var batastgl = $("[name='batastgl_surat_keterangan_dokter']").val();
            $.ajax({
                type: "POST",
                data: {
                    jenis: "ralan",
                    no_reg: no_reg,
                    no_pasien: no_pasien,
                    hasil1: hasil1,
                    hasil2: hasil2,
                    hasil3: hasil3,
                    untuk: untuk,
                    batastgl: batastgl
                },
                url: "<?php echo site_url('pendaftaran/simpan_surat_keterangan_dokter'); ?>",
                success: function(result) {
                    alert("Data berhasil disimpan");
                },
                error: function(result) {
                    console.log(result);
                }
            });
            return false;
        });
        $(".simpan_ket_narkoba").click(function() {
            var no_pasien = $("[name='no_rm_ket_narkoba']").val();
            var no_reg = $("[name='no_reg_ket_narkoba']").val();
            var anamnesis = $("[name='anamnesis']").val();
            var fisik = $("[name='fisik']").val();
            var untuk = $("[name='untuk_ket_narkoba']").val();
            var batastgl = $("[name='batastgl_ket_narkoba']").val();
            $.ajax({
                type: "POST",
                data: {
                    jenis: "ralan",
                    no_reg: no_reg,
                    no_pasien: no_pasien,
                    anamnesis: anamnesis,
                    fisik: fisik,
                    untuk: untuk,
                    batastgl: batastgl
                },
                url: "<?php echo site_url('pendaftaran/simpan_ket_narkoba'); ?>",
                success: function(result) {

                    alert("Data berhasil disimpan !");
                },
                error: function(result) {
                    console.log(result);
                }
            });
            return false;
        });
        $(".simpan_keterangan_jiwa").click(function() {
            var no_pasien = $(".bg-gray").attr("no_pasien");
            var no_reg = $("[name='no_reg_keterangan_jiwa']").val();
            var hasil1 = $("[name='hasil_keterangan_jiwa1']").val();
            var hasil2 = $("[name='hasil_keterangan_jiwa2']").val();
            var untuk = $("[name='untuk_keterangan_jiwa']").val();
            var batastgl = $("[name='batastgl_keterangan_jiwa']").val();
            $.ajax({
                type: "POST",
                data: {
                    jenis: "ralan",
                    no_reg: no_reg,
                    no_pasien: no_pasien,
                    hasil1: hasil1,
                    hasil2: hasil2,
                    untuk: untuk,
                    batastgl: batastgl
                },
                url: "<?php echo site_url('pendaftaran/simpan_keterangan_jiwa'); ?>",
                success: function(result) {
                    alert("Data berhasil disimpan");
                },
                error: function(result) {
                    console.log(result);
                }
            });
            return false;
        });
        $(".skbc").click(function() {
            var no_reg = $(".bg-gray").attr("no_reg");
            var no_pasien = $(".bg-gray").attr("no_pasien");
            $.ajax({
                type: "POST",
                data: {
                    jenis: "ralan",
                    no_reg: no_reg,
                    no_pasien: no_pasien
                },
                url: "<?php echo site_url('suket/getswab'); ?>",
                success: function(result) {
                    console.log(result)
                    if (result.toLowerCase()=="non reaktif" || result.toLowerCase()=="negatif"){
                      var no_rm = $(".bg-gray").attr("href");
                      var url = "<?php echo site_url('suket/suketbebascovid'); ?>/" + no_rm + "/ralan";
                      openCenteredWindow(url);
                      return false;
                  } else {
                      alert("Anda tidak dapat membuat surat keterangan bebas covid");
                  }
              },
              error: function(result) {
                console.log(result);
            }
        });
        });
        var poli = $(".bg-gray").attr("poli");
        $("<audio id='suarabel'></audio>").attr({
            'src': '<?php echo base_url() . 'rekaman/suarabel_'; ?>' + poli + '.mp3',
            'volume': 0.4,
        }).appendTo("body");
        getcek_gk();
    });

function simpanpanggil() {
    var poli = $(".bg-gray").attr("poli");
    var dokter = $(".bg-gray").attr("dokter");
    var no_reg = $(".bg-gray").attr("no_reg");
    var no_antrian = $(".bg-gray").attr("no_antrian");
    $("tr#data").removeClass("bg-maroon");
    $.ajax({
        type: "POST",
        data: {
            poli: poli,
            dokter: dokter,
            no_antrian: no_antrian,
            no_reg: no_reg
        },
        url: "<?php echo site_url('displayantrian/simpanpanggil'); ?>",
        success: function(result) {

        },
        error: function(result) {
            console.log(result);
        }
    });
}

function mulai(antrian, status) {
    $('.loading').show();
    $(".no_urut").html(antrian);
        //MAINKAN SUARA BEL PADA SAAT AWAL
        var poli = $(".bg-gray").attr("poli");
        $("<audio id='suarabel'></audio>").attr({
            'src': '<?php echo base_url() . 'rekaman/suarabel_'; ?>' + poli + '.mp3',
            'volume': 0.4,
        }).appendTo("body");
        document.getElementById('suarabel').pause();
        document.getElementById('suarabel').currentTime = 0;
        document.getElementById('suarabel').play();
        //SET DELAY UNTUK MEMAINKAN REKAMAN NOMOR URUT
        totalwaktu = document.getElementById('suarabel').duration * 1000;
        //MAINKAN SUARA NOMOR URUT
        setTimeout(function() {
            document.getElementById('suarapanggilan').pause();
            document.getElementById('suarapanggilan').currentTime = 0;
            document.getElementById('suarapanggilan').play();
        }, totalwaktu);
        totalwaktu = totalwaktu + document.getElementById('suarapanggilan').duration * 1000 + 1000;
        setTimeout(function() {
            document.getElementById('suaraklinik').pause();
            document.getElementById('suaraklinik').currentTime = 0;
            document.getElementById('suaraklinik').play();
        }, totalwaktu);
        totalwaktu = totalwaktu + document.getElementById('suaraklinik').duration * 1000 + 500;
        setTimeout(function() {
            document.getElementById('suara' + poli).pause();
            document.getElementById('suara' + poli).currentTime = 0;
            document.getElementById('suara' + poli).play();
        }, totalwaktu);
        totalwaktu = totalwaktu + document.getElementById('suara' + poli).duration * 1000 + 1000;
        setTimeout(function() {
            document.getElementById('suaradokter').pause();
            document.getElementById('suaradokter').currentTime = 0;
            document.getElementById('suaradokter').play();
        }, totalwaktu);
        var dokter = $(".bg-gray").attr("dokter");
        totalwaktu = totalwaktu + document.getElementById('suaradokter').duration * 1000 + 1000;
        setTimeout(function() {
            document.getElementById('suara' + dokter).pause();
            document.getElementById('suara' + dokter).currentTime = 0;
            document.getElementById('suara' + dokter).play();
        }, totalwaktu);
        totalwaktu = totalwaktu + (parseInt(document.getElementById('suara' + dokter).duration) * 1000) + 1000;
        //MAINKAN SUARA NOMOR URUT
        setTimeout(function() {
            document.getElementById('suarabelnomorurut').pause();
            document.getElementById('suarabelnomorurut').currentTime = 0;
            document.getElementById('suarabelnomorurut').play();
        }, totalwaktu);
        totalwaktu = totalwaktu + (parseInt(document.getElementById('suarabelnomorurut').duration) * 1000) + 1000;
        var antrian = parseInt(antrian);
        if (antrian < 10) {
            setTimeout(function() {
                document.getElementById('suarabel' + antrian).pause();
                document.getElementById('suarabel' + antrian).currentTime = 0;
                document.getElementById('suarabel' + antrian).play();
            }, totalwaktu);

            totalwaktu = totalwaktu + 2000;
        } else
        if (antrian == 10) {
            //JIKA 10 MAKA MAIKAN SUARA SEPULUH
            setTimeout(function() {
                document.getElementById('sepuluh').pause();
                document.getElementById('sepuluh').currentTime = 0;
                document.getElementById('sepuluh').play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
        } else
        if (antrian == 11) {
            setTimeout(function() {
                document.getElementById('sebelas').pause();
                document.getElementById('sebelas').currentTime = 0;
                document.getElementById('sebelas').play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
        } else
        if (antrian < 20) {
            var an = antrian - 10;
            setTimeout(function() {
                document.getElementById('suarabel' + an).pause();
                document.getElementById('suarabel' + an).currentTime = 0;
                document.getElementById('suarabel' + an).play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                document.getElementById('belas').pause();
                document.getElementById('belas').currentTime = 0;
                document.getElementById('belas').play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
        } else
        if (antrian < 100) {
            var n = antrian.toString().slice(0, 1);
            setTimeout(function() {
                document.getElementById('suarabel' + n).pause();
                document.getElementById('suarabel' + n).currentTime = 0;
                document.getElementById('suarabel' + n).play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                document.getElementById('puluh').pause();
                document.getElementById('puluh').currentTime = 0;
                document.getElementById('puluh').play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            var m = antrian.toString().slice(1);
            setTimeout(function() {
                document.getElementById('suarabel' + m).pause();
                document.getElementById('suarabel' + m).currentTime = 0;
                document.getElementById('suarabel' + m).play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
        }
        if (status == "layani") {
            setTimeout(function() {
                var id = $(".bg-gray").attr("href");
                window.location = "<?php echo site_url('pendaftaran/layani') ?>/" + id;
            }, totalwaktu);
        } else {
            setTimeout(function() {
                location.reload();
            }, totalwaktu);
        }
    }

    function tgl_indo(tgl, tipe = 1) {
        var date = tgl.substring(tgl.length, tgl.length - 2);
        if (tipe == 1)
            var bln = tgl.substring(5, 7);
        else
            var bln = tgl.substring(4, 6);
        var thn = tgl.substring(0, 4);
        return date + "-" + bln + "-" + thn;
    }

    function tgl_barat(tgl, tipe = 1) {
        var date = tgl.substring(0, 2);
        if (tipe == 1)
            var bln = tgl.substring(3, 5);
        else
            var bln = tgl.substring(4, 6);
        var thn = tgl.substring(tgl.length, tgl.length - 4);
        return thn + "-" + bln + "-" + date;
    }
    function getcek_gk(){
        var row = {}
        $.each($("tr#data"), function( key, value ) {
            row[key] = $(this).attr("gk");
        });
        $.ajax({
            type  : "POST",
            data  : {row:row},
            url   : "<?php echo site_url('pendaftaran/getcek_gk');?>",
            success : function(result){
                var dat = JSON.parse(result);
                console.log(result);
                $.each(dat, function( key, value ) {
                    var text = value!="0" ? "<i class='fa fa-check label-success'></i>" : "";
                    $(".gk_"+key).html(text);
                });
            },
            error: function(result){
                console.log(result);
            }
        });
        return false;
    };
    function gettempatvaksin(){
      var val = "<?php echo $this->session->userdata("tempat_vaksin");?>";
      $.ajax({
          url: "<?php echo site_url('vaksinasi/gettempatvaksin')?>",
          type: 'POST',
          success: function(result){
            console.log(result);
            var row = JSON.parse(result);
            $("[name='tempat_vaksin']").html('').select2({data:row,placeholder:"Pilih Tempat Vaksin"}).select2("val",val);
          }
      });
    }
</script>
<!-- <audio id="suarabel" src="<?php echo base_url(); ?>rekaman/kenari.mp3"></audio> -->
<audio id="suarapanggilan" src="<?php echo base_url(); ?>rekaman/panggilan.mp3"></audio>
<audio id="suaraklinik" src="<?php echo base_url(); ?>rekaman/klinik.mp3"></audio>
<audio id="suarabelnomorurut" src="<?php echo base_url(); ?>rekaman/nomor-urut.mp3"></audio>
<audio id="suarabelsuarabelloket" src="<?php echo base_url(); ?>rekaman/loket.wav"></audio>
<audio id="belas" src="<?php echo base_url(); ?>rekaman/belas.mp3"></audio>
<audio id="sebelas" src="<?php echo base_url(); ?>rekaman/sebelas.mp3"></audio>
<audio id="puluh" src="<?php echo base_url(); ?>rekaman/puluh.mp3"></audio>
<audio id="sepuluh" src="<?php echo base_url(); ?>rekaman/sepuluh.mp3"></audio>
<audio id="ratus" src="<?php echo base_url(); ?>rekaman/ratus.mp3"></audio>
<audio id="seratus" src="<?php echo base_url(); ?>rekaman/seratus.mp3"></audio>
<audio id="suaradokter" src="<?php echo base_url(); ?>rekaman/dokter.mp3"></audio>
<?php
for ($i = 1; $i < 10; $i++) {
    echo '<audio id="suarabel' . $i . '" src="' . base_url() . 'rekaman/' . $i . '.mp3" ></audio>';
}
?>
<div class='modal rejected'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-red">
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;NOTIFICATION</h4>
            </div>
            <div class='modal-body'>
                <p>Yakin akan Batal ?</p>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alasan Batal</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="alasan" /></textarea>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password_batal">
                        </div>
                    </div> -->
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
    if ($this->session->flashdata('message')) {
        $pesan = explode('-', $this->session->flashdata('message'));
        echo "<div class='alert alert-" . $pesan[0] . "' alert-dismissable>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
        <b>" . $pesan[1] . "</b>
        </div>";
    }

    ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2">
                        Tanggal
                    </label>
                    <div class="col-md-2">
                        <input type="text" name="tgl1" class="form-control input-sm" value="<?php echo $this->session->userdata("tgl1") ?>" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="tgl2" class="form-control input-sm" value="<?php echo $this->session->userdata("tgl2") ?>" autocomplete="off">
                    </div>
                    <div class="col-md-1">
                        <button class="search btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col-md-5">
                          <label class="col-md-5 control-label">Tempat Vaksin</label>
                          <div class="col-md-7">
                              <select type="text" class="form-control" required name="tempat_vaksin" style="width:100%"></select>
                          </div>
                    </div>
                    <!-- <label class="col-md-1">
                        Poliklinik
                    </label>
                    <div class="col-md-2">
                        <input type="text" name="poliklinik" class="form-control" readonly value="<?php echo $this->session->userdata("poliklinik") ?>">
                    </div> -->
                    <!-- <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" name="poli_kode" class="form-control input-sm" readonly value="<?php echo $this->session->userdata("poli_kode") ?>">
                            <span class="input-group-btn"><button class="poli btn btn-sm btn-primary">...</button></span>
                        </div>
                    </div> -->
                </div>
                <div class="form-group">
                    <label class="col-md-2">
                        Status Vaksin
                    </label>
                    <div class="col-md-2">
                        <select name="status_vaksin" class="form-control input-sm">
                            <option value="ALL" <?php echo ($this->session->userdata("status_vaksin") == "ALL" ? "selected" : ""); ?>>ALL</option>
                            <option value="VAKSIN1" <?php echo ($this->session->userdata("status_vaksin") == "VAKSIN1" ? "selected" : ""); ?>>VAKSIN 1</option>
                            <option value="VAKSIN2" <?php echo ($this->session->userdata("status_vaksin") == "VAKSIN2" ? "selected" : ""); ?>>VAKSIN 2</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-group">
                            <button class="cari_no btn-sm btn btn-info" type="button"> Cari</button>
                            <button class="reset btn btn-sm btn-warning"> Reset</button>
                        </div>
                    </div>
                    <!-- <label class="col-md-1">
                        Dokter
                    </label>
                    <div class="col-md-2">
                        <input type="text" name="dokter" class="form-control input-sm" readonly value="<?php echo $this->session->userdata("dokter") ?>">
                    </div> -->
                    <!-- <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" name="kode_dokter" class="form-control input-sm" readonly value="<?php echo $this->session->userdata("kode_dokter") ?>">
                            <span class="input-group-btn"><button class="dokter btn btn-sm btn-primary">...</button></span>
                        </div>
                    </div> -->
                </div>
                <div class="form-group">
                    <div class="col-md-7"></div>
                    <div class="col-md-5 pull-right">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover table-responsive" id="myTable">
                <thead>
                    <tr class="bg-navy">
                        <th width="100px" class='text-center'>Nomor RM</th>
                        <!-- <th width="100px" class='text-center'>Nomor REG</th> -->
                        <th width="100px" class='text-center'>NIK</th>
                        <th width="200px" class='text-center' >Nama</th>
                        <th width="120px" class='text-center'>Tgl Lahir</th>
                        <th width="150px" class='text-center' >No. HP</th>
                        <!-- <th width="150px"  class='text-center'>Tempat</th> -->
                        <th class='text-center'>Alamat</th>
                        <th class='text-center'>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $no_kk = '';
                    foreach ($q3->result() as $row) {
                        if ($row->layan == "0") {
                            $layan = "-";
                        } else if ($row->layan == "1") {
                            $layan = "<label class='label label-success'>Layan</label>";
                        } else {
                            $layan = "<label class='label label-danger'>Batal</label>";
                        }
                        $telpon = preg_replace('/0/', '62', $row->nohp, 1);
                        echo "<tr id=data href='" . $row->no_pasien . "/" . $row->no_reg . "' gk='".$row->no_pasien . "_" . $row->no_reg."' no_antrian='" . $row->no_antrian . "' no_pasien='" . $row->no_pasien . "' no_reg='" . $row->no_reg . "' poli='" . $row->tujuan_poli . "' nohp='" . $telpon . "'>";
                        echo "<td class='text-center'>" . $row->no_pasien . "</td>";
                        // echo "<td class='text-center'>" . $row->no_reg . "</td>";
                        echo "<td>" . $row->nik . "</td>";
                        echo "<td>" . $row->nama_pasien . "</td>";
                        echo "<td class='text-center'>" . date("d-m-Y",strtotime($row->tgl_lahir)) . "</td>";
                        echo "<td>" . $row->nohp . "</td>";
                        // echo "<td>" . (isset($tempat_vaksin[$row->tempat_vaksin]) ? $tempat_vaksin[$row->tempat_vaksin] : "") . "</td>";
                        echo "<td>" . $row->alamat . "</td>";
                        echo "<td>" . $layan . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="bg-navy">
                        <th colspan="4">Jumlah Pasien : <?php echo $total_rows; ?></th>
                        <th colspan="3">
                        <span class="pull-left">Layan : <?php echo $jlayan; ?></span>
                        <span class="pull-right">Batal : <?php echo $jbatal; ?></span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="box-footer">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <button class="skip btn btn-sm btn-warning" type="button"> Observasi dan Pencatatan</button>
                        <button class="batal btn btn-sm btn-danger" type="button"> Batal</button>
                        <!-- <div class="dropup">
                            <button class="dropbtn btn btn-sm btn-success">Respon Time</button>
                            <div class="dropup-content">
                                <a class="rt_poliklinik">Klinik</a>
                                <a class="rtpelayanan">Pelayanan</a>
                                <a class="rtrm">RM</a>
                                <a class="rttunggu">Tunggu</a>
                            </div>
                        </div> -->
                        <!-- <div class="dropup">
                            <button class="dropbtn btn btn-sm btn-danger">Klinik</button>
                            <div class="dropup-content">
                                <a class="terima">Terima RM</a>
                                <a class="terima_pasien">Terima Pasien</a>
                                <a class="layani">Layani</a>
                                <a class="konsul">Konsul</a>
                                <a class="tindakan">Tindakan</a>
                                <a class="pulang">Pulang</a>
                                <a class="batal">Batal</a>
                                <a class="ujifungsi"> Uji Fungsi</a>
                            </div>
                        </div> -->
                        <!-- <div class="dropup">
                            <button class="dropbtn btn btn-sm btn-primary">Rekam Medis</button>
                            <div class="dropup-content">
                                <a class="cetak_rekmed">Cetak</a>
                                <a class="cari_data">Cari Data</a>
                                <a class="edit">Lengkapi Data</a>
                                <a class="sep">Buat SEP</a>
                                <a class="cetak_barcode">Barcode</a>
                                <a class="pindahstatus">Pindah Status</a>
                                <a class="share">Share</a>
                                <a class="indeks">Indeks</a>
                                <a class="persenpelayanan">Persentase Indeks</a>
                                <a class="gudang">Gudang</a>
                            </div>
                        </div> -->
                        <!-- <div class="dropup">
                            <button class="dropbtn btn btn-sm btn-warning">Ekspertisi</button>
                            <div class="dropup-content">
                                <a class="obat"> Obat</a>
                                <a class="view_pembayaran"> Billing</a>
                                <a class="ekspertisigizi">Gizi</a>
                                <a class="ekspertisipa">PA</a>
                                <a class="ekspertisilab">Lab</a>
                                <a class="ekspertisiradiologi">Radiologi</a>
                                <a class="cetakcovid1">Cetak Covid</a>
                                <a class="cetakcovid2">Cetak Covid2</a>
                            </div>
                        </div> -->
                        <!-- <div class="dropup">
                            <button class="dropbtn btn btn-sm bg-maroon">ERM</button>
                            <div class="dropup-content">
                                <a class="triage"> Triage</a>
                                <div class="sidenav">
                                    <a class="cetak"> Assesment<i class='fa fa-angle-right pull-right'></i></a>
                                    <div class="dropup-content-sidenav">
                                        <a class="assesment"> Assesment Medis IGD</a>
                                        <a class="perawat"> Assesment Keperawatan</a>
                                    </div>
                                </div>
                                <a class="covid"> Covid</a>
                                <a class="cetaksep"> SEP</a>
                                <a class="cetakresume"> Resume</a>
                                <a class="sebabkematian"> Sebab Kematian</a>
                                <a class="cppt"> CPPT</a>
                                <div class="sidenav">
                                    <a class="cetak"> Laporan<i class='fa fa-angle-right pull-right'></i></a>
                                    <div class="dropup-content-sidenav">
                                        <a class="laporan_tindakan"> Laporan Tindakan</a>
                                        <a class="laporan_operasi"> Laporan Operasi</a>
                                        <a class="laporan_mata"> Laporan Ops Mata (Katarak)</a>
                                        <a class="laporan_pterygium"> Laporan Ops Mata (Pterygium)</a>
                                    </div>
                                </div>
                                <a class="pdf"> LIP</a>
                                <a class="cetakujifungsi"> Uji Fungsi</a>
                                <a class="cetakujifungsi2"> Permintaan Terapis</a>
                                <a class="cetakrehab"> Rehabilitas Rajal</a>
                                <div class="sidenav">
                                    <a class="cetak"> Surat<i class='fa fa-angle-right pull-right'></i></a>
                                    <div class="dropup-content-sidenav">
                                        <a class="sksi"> Surat Keterangan Selesai ISOLASI</a>
                                        <a class="skbc"> Surat Keterangan Bebas Covid</a>
                                        <a class="erm_pc"> Suket Pemulasaran Covid</a>
                                        <a class="erm_kematian"> Surat Kematian</a>
                                        <a class="erm_kelahiran"> Surat Kelahiran</a>
                                        <a class="erm_suratistirahatsakit"> Surat Istirahat Sakit</a>
                                        <a class="erm_suratketerangandokter"> Surat Keterangan Dokter</a>
                                        <a class="erm_narkoba"> Surat Keterangan Narkoba</a>
                                        <a class="erm_jiwa"> Surat Keterangan Sehat Jiwa</a>
                                        <a class="erm_rujukan"> Rujukan Pasien</a>
                                        <a class="erm_pengantarterapi"> Pengantar Terapi/ Tindakan</a>
                                    </div>
                                </div>
                                <div class="sidenav">
                                    <a class="cetak"> IC<i class='fa fa-angle-right pull-right'></i></a>
                                    <div class="dropup-content-sidenav">
                                        <a class="general_concent"> General Concent</a>
                                        <a class="surat_tindakan_kedokteran"> Surat Persetujuan Tindakan Kedokteran</a>
                                        <a class="surat_tindakan_anestesi"> Surat Persetujuan Tindakan Anestesi</a>
                                        <a class="surat_tindakan_transfusi"> Surat Persetujuan Tindakan Transfusi</a>
                                        <a class="cetakkronologi">Kronologis</a>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- <button class="upload btn btn-sm btn-primary" type="button"> PDF</button>
                        <button class="mcu btn btn-sm btn-warning" type="button"> MCU</button>
                        <button class="panggil btn btn-sm btn-primary" type="button"><i class="fa fa-bullhorn"></i>&nbsp;Panggil</button> -->
                        <div class="dropup">
                            <button class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i> Send Whatsapp</button>
                            <div class="dropup-content">
                                <a class="skrining_vaksin"> Skrining Vaksinasi </a>
                                <!-- <a class="lengkapidata_wa"> Lengkapi Data</a> -->
                                <!-- <a class="whatsapp"> General Concent</a> -->
                                <!-- <a class="kronologi"> Kronologi</a>
                                <a class="artikel"> Info/ Promosi</a>
                                <a class="surat_isolasi"> Surat Keterangan Selesai ISOLASI</a>
                                <a class="surat_bebascovid">Surat Keterangan Bebas COVID</a>
                                <a class="pc">Suket Pemulasaran Covid</a>
                                <a class="kirim_resume"> Resume</a>
                                <a class="pulang_paksa"> Pulang Paksa</a>
                                <a class="pemulasaran"> Pemulasaran</a>
                                <a class="surat_kematian"> Surat Kematian</a>
                                <a class="tindakan_medis"> Tindakan Medis</a>
                                <a class="surat_istirahat_sakit"> Surat Istirahat Sakit</a>
                                <a class="surat_keterangan_dokter"> Surat Keterangan Dokter</a>
                                <a class="keterangan_narkoba"> Surat Keterangan Narkoba</a>
                                <a class="keterangan_jiwa"> Surat Keterangan Sehat Jiwa</a> -->
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="pull-right">
                            <button class="laporan_tindakan btn btn-sm btn-primary" type="button"> Laporan Tindakan</button>
                            <button class="laporan_operasi btn btn-sm btn-warning" type="button">Laporan Operai</button>
                            <button class="laporan_mata btn btn-sm btn-primary" type="button">Laporan Ops Mata</button>
                            <button class="pdf btn btn-sm btn-success" type="button"> LIP</button>
                            <button class="reset btn btn-sm btn-warning"> Reset</button>
                            <button class="cari_no btn-sm btn btn-info" type="button"> Cari</button>
                        </div>
                    </div> -->
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
                                    <input class="form-control" type="text" name="no_reg_share" placeholder="No Reg" />
                                    <input class="form-control" type="hidden" name="no_pasien_share" />
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
                    <input type="hidden" name="no_pasien_pulang">
                    <input type="hidden" name="no_reg_pulang">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Keadaan Pulang</label>
                        <div class="col-md-8">
                            <select name="keadaan_pulang" class="form-control">
                                <?php
                                foreach ($kplg->result() as $key) {
                                    echo "<option value=" . $key->id . ">" . $key->keterangan . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Status Pulang</label>
                        <div class="col-md-8">
                            <select name="status_pulang" class="form-control">
                                <?php
                                foreach ($splg->result() as $key) {
                                    echo "<option value='" . $key->id . "'>" . $key->keterangan . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Jam Pulang</label>
                        <div class="col-md-3"><input type="text" name="jam_pulang" class="form-control" autocomplete="off" placeholder="00:00"></div>
                    </div>
                    <div class="jam_meninggal">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Jam Meninggal</label>
                            <div class="col-md-3"><input type="text" name="jam_meninggal" class="form-control" autocomplete="off" placeholder="00:00"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">No. Surat Pulang</label>
                        <div class="col-md-8">
                            <input type="text" name="no_surat_pulang" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">No. SEP</label>
                        <div class="col-md-8">
                            <input type="text" name="no_sep" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type='button'class="next btn btn-success">Next</button> -->
                <button class="simpan_pulang btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class='modal modal-tindakan-medis no-print' role="dialog">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Tindakan Medis</h4>
            </div>
            <div class='modal-body'>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Pelaksana Tindakan</label>
                        <div class="col-md-8">
                            <input type="hidden" name="no_reg_tindakan">
                            <select name="pelaksana_tindakan" class="form-control" style="width: 100%">
                                <?php
                                foreach ($dok->result() as $key) {
                                    echo "<option value=" . $key->id_dokter . ">" . $key->nama_dokter . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Pemberi Informasi</label>
                        <div class="col-md-8">
                            <select name="pemberi_informasi" class="form-control" style="width: 100%">
                                <?php
                                foreach ($dp["dokter"] as $key => $value) {
                                    echo "<option value='dokter/" . $key . "'>" . $value . "</option>";
                                }
                                foreach ($dp["perawat"] as $key => $value) {
                                    echo "<option value='perawat/" . $key . "'>" . $value . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Perawat/ Bidan</label>
                        <div class="col-md-8">
                            <select name="saksirs" class="form-control" style="width: 100%">
                                <?php
                                foreach ($dp["dokter"] as $key => $value) {
                                    echo "<option value='dokter/" . $key . "'>" . $value . "</option>";
                                }
                                foreach ($dp["perawat"] as $key => $value) {
                                    echo "<option value='perawat/" . $key . "'>" . $value . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Tindakan Kedokteran</label>
                        <div class="col-md-1"><button class="selectall_tindakankedokteran btn btn-success btn-sm"><i class="fa fa-check"></i></button></div>
                        <div class="col-md-7">
                            <select name="tindakan_kedokteran" class="form-control tindakan_kedokteran" multiple="multiple" style="width: 100%">
                                <?php
                                foreach ($tm->result() as $key) {
                                    echo "<option value=" . $key->id . ">" . $key->keterangan . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <span class='keterangan_kedokteran'></span>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Tindakan Anestesi</label>
                        <div class="col-md-1"><button class="selectall_tindakananestesi btn btn-success btn-sm"><i class="fa fa-check"></i></button></div>
                        <div class="col-md-7">
                            <select name="tindakan_anestesi" class="form-control tindakan_anestesi" multiple="multiple" style="width: 100%">
                                <?php
                                foreach ($tm->result() as $key) {
                                    echo "<option value=" . $key->id . ">" . $key->keterangan . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <span class='keterangan_anestesi'></span>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Tindakan Transfusi</label>
                        <div class="col-md-1"><button class="selectall_tindakantransfusi btn btn-success btn-sm"><i class="fa fa-check"></i></button></div>
                        <div class="col-md-7">
                            <select name="tindakan_transfusi" class="form-control tindakan_transfusi" multiple="multiple" style="width: 100%">
                                <?php
                                foreach ($tm->result() as $key) {
                                    echo "<option value=" . $key->id . ">" . $key->keterangan . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <span class='keterangan_transfusi'></span>
                </div>
            </div>
            <div class='modal-footer'>
                <div class="pull-right">
                    <button class="simpan_tindakan btn btn-success">Simpan</button>
                    <button class="send_tindakan btn btn-success">Send</button>
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
                                <input class="form-control" type="text" name="cari_no" placeholder="Nama/ No. RM/ No. Reg/ No. BPJS/ No. SEP/ NIK/ NRP" />
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
                                <input class="form-control" type="text" name="cari_nama" />
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
                                <input class="form-control" type="text" name="cari_noreg" />
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
<div class='modal modal_artikel no-print' role="dialog">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Artikel</h4>
            </div>
            <div class='modal-body'>
                <div class="row">
                    <div class="col-sm-12">
                        <select class="form-control" name='artikel'>
                            <?php
                            foreach ($artikel->result() as $row) {
                                echo "<option value='" . $row->slug . "'>" . $row->title . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <div class="pull-right">
                    <button class="send_artikel btn btn-success">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='modal modal_kronologi no-print' role="dialog">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Petugas RM</h4>
            </div>
            <div class='modal-body'>
                <!-- <?php
                        echo form_open("persetujuan/cekpetugas_rm/persetujuan", array("id" => "formsave"));
                        ?> -->
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input class="form-control" type="hidden" name="no_reg_p" />
                                        <input class="form-control" type="hidden" name="no_pasien_p" />
                                        <input class="form-control" type="password" name="password_kronologis" placeholder="Masukan password petugas rm" />
                                        <span class="input-group-btn">
                                            <button class="tmb_kronologi btn btn-success" type="button">Ok</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <?php echo form_close(); ?> -->
                    </div>
                </div>
            </div>
        </div>
        <div class='modal modal-surat-istirahat-sakit no-print' role="dialog">
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class="modal-header bg-orange">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Surat Istirahat Sakit</h4>
                    </div>
                    <div class='modal-body'>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Alamat yang dituju</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="no_reg_surat_istirahat_sakit">
                                    <input type="text" class="form-control" name="kepada_surat_istirahat_sakit">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Selama</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="selama_surat_istirahat_sakit" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mulai Tanggal</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="mulai_surat_istirahat_sakit">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Sampai Tanggal</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="sampai_surat_istirahat_sakit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="pull-right">
                            <button class="simpan_surat_istirahat_sakit btn btn-success">Simpan</button>
                            <button class="send_surat_istirahat_sakit btn btn-success">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='modal modal-surat-keterangan-dokter no-print' role="dialog">
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class="modal-header bg-orange">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Surat Keterangan Dokter</h4>
                    </div>
                    <div class='modal-body'>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Dengan hasil</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="no_reg_surat_keterangan_dokter">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">1.</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="hasil_surat_keterangan_dokter1">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">2.</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="hasil_surat_keterangan_dokter2">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">3.</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="hasil_surat_keterangan_dokter3">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Untuk Keperluan</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="untuk_surat_keterangan_dokter">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Batas Waktu</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="batastgl_surat_keterangan_dokter">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="pull-right">
                            <button class="simpan_surat_keterangan_dokter btn btn-success">Simpan</button>
                            <button class="send_surat_keterangan_dokter btn btn-success">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='modal modalket-narkoba no-print' role="dialog">
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class="modal-header bg-orange">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Surat Keterangan Narkoba</h4>
                    </div>
                    <div class='modal-body'>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Dengan hasil</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="no_reg_ket_narkoba">
                                    <input type="hidden" name="no_rm_ket_narkoba">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Anamnesis</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="anamnesis">
                                        <option value="Pilih"></option>
                                        <option>Didapatkan riwayat pengguna Narkoba</option>
                                        <option>Tidak didapatkan riwayat pengguna Narkoba</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Pemeriksaan Fisik</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="fisik">
                                        <option value="Pilih"></option>
                                        <option>Ditemukan tanda-tanda pengguna Narkoba</option>
                                        <option>Tidak ditemukan tanda-tanda pengguna Narkoba</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Untuk Keperluan</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="untuk_ket_narkoba">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Sampai Tanggal</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="batastgl_ket_narkoba">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="pull-right">
                            <button class="simpan_ket_narkoba btn btn-success">Simpan</button>
                            <button class="send_ket_narkoba btn btn-success">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='modal modal-keterangan-jiwa no-print' role="dialog">
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class="modal-header bg-orange">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class='modal-title'><i class="icon fa fa-warning"></i>&nbsp;&nbsp;Surat Keterangan Sehat Jiwa</h4>
                    </div>
                    <div class='modal-body'>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Dengan hasil</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="no_reg_keterangan_jiwa">
                                    <!-- <input type="hidden" name="no_rm_keterangan_jiwa"> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">1.</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="hasil_keterangan_jiwa1">
                                        <option value="Pilih"></option>
                                        <option>Ada gangguan jiwa berat (psikotik)</option>
                                        <option>Tidak terdapat gangguan jiwa berat (psikotik)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">2.</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="hasil_keterangan_jiwa2">
                                        <option value="Pilih"></option>
                                        <option>Ada gangguan neurosa yang berat</option>
                                        <option>Tidak terdapat gangguan neurosa yang berat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Untuk Keperluan</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="untuk_keterangan_jiwa">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Batas Waktu</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="batastgl_keterangan_jiwa">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="pull-right">
                            <button class="simpan_keterangan_jiwa btn btn-success">Simpan</button>
                            <button class="send_ket_jiwa btn btn-success">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='loading modal'>
            <div class='text-center align-middle' style="margin-top: 200px">
                <div class="col-xs-3 col-sm-3 col-lg-5"></div>
                <div class="alert col-xs-6 col-sm-6 col-lg-2" style="background-color: white;border-radius: 10px;">
                    <div style="font-size:54px;font-weight:bold;margin-top:-30px;margin-bottom:20px;padding:30px 50px"><i class="fa fa-bullhorn"></i></div>
                    <!-- <div class="overlay" style="font-size:50px;color:#696969"><img src="<?php echo base_url(); ?>/img/load.gif" width="150px"></div> -->
                    <div style="font-size:14px;font-weight:bold;color:#696969;margin-top:-30px;margin-bottom:20px">Sedang Melakukan Panggilan Pasien<br><span class="no_urut"></span></div>
                    <a href="#" class="skip" style="color:#000000">Skip</a>
                </div>
                <div class="col-xs-3 col-sm-3 col-lg-5"></div>
            </div>
        </div>
        <style type="text/css">
            .konten_print td {
                font-family: antoniobold;
                text-transform: uppercase;
            }

            .dropup-content {
                z-index: 999999;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: -15px;
            }

            .select2-container--default .select2-selection--single {
                padding: 16px 0px;
                border-color: #d2d6de;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background-color: #3c8dbc;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                color: #f4f4f4;
            }
        </style>
