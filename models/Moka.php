<?php
    class Moka extends CI_Model{
        function __construct(){
            parent::__construct();
        }

        function getoka($page,$offset){
            $tgl1 = $this->session->userdata("tgl1");
            $tgl2 = $this->session->userdata("tgl2");
            $pelayanan = $this->session->userdata("pelayanan");
            $cari_no = $this->session->userdata("cari_no");
            if ($cari_no==""){
                if ($tgl1 != "" || $tgl2 !=""){
                    $this->db->where("date(o.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
                    $this->db->where("date(o.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
                }
            }
            if ($pelayanan != "") {
                $this->db->where("o.pelayanan",$pelayanan);
            }
            $this->db->group_start();
            $this->db->like("o.kode_oka",$cari_no);
            $this->db->or_like("o.nama",$cari_no);
            $this->db->or_like("o.no_rm",$cari_no);
            $this->db->or_like("o.no_reg",$cari_no);
            $this->db->group_end();
            $this->db->select("o.*, p.alamat, g.keterangan as gol_pasien");
            $this->db->join("pasien p", "p.no_pasien = o.no_rm","left");
            $this->db->join("gol_pasien g","g.id_gol = p.id_gol","left");
            // $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            // $this->db->join("kelas kl", "kl.kode_kelas = o.kelas","left");
            // $this->db->join("kamar k", "k.kode_kamar = o.kamar and k.no_bed=o.no_bed and k.kode_ruangan=o.ruangan and k.kode_kelas=o.kelas","left");
            // $this->db->group_by("k.kode_kamar");
            $q = $this->db->get("oka o",$page,$offset);
            return $q;
        }
        function getoka_jumlah($layan=""){
            $tgl1 = $this->session->userdata("tgl1");
            $tgl2 = $this->session->userdata("tgl2");
            $pelayanan = $this->session->userdata("pelayanan");
            $cari_no = $this->session->userdata("cari_no");
            if ($cari_no==""){
                if ($tgl1 != "" || $tgl2 !=""){
                    $this->db->where("date(o.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
                    $this->db->where("date(o.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
                }
            }
            if ($pelayanan != "") {
                $this->db->where("o.pelayanan",$pelayanan);
                // $this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
            }
            if ($layan != "") {
                $this->db->where("o.layan",$layan);
            }
            $this->db->group_start();
            $this->db->like("o.kode_oka",$cari_no);
            $this->db->or_like("o.nama",$cari_no);
            $this->db->or_like("o.no_rm",$cari_no);
            $this->db->or_like("o.no_reg",$cari_no);
            $this->db->group_end();

            // $this->db->select("o.*, p.alamat,r.nama_ruangan as nama_ruangan, kl.nama_kelas as nama_kelas, g.keterangan as gol_pasien");
            // $this->db->join("pasien p", "p.no_pasien = o.no_rm","left");
            // $this->db->join("gol_pasien g","g.id_gol = p.id_gol","left");
            // $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            // $this->db->join("kelas kl", "kl.kode_kelas = o.kelas","left");
            // $this->db->join("kamar k", "k.kode_kamar = o.kamar and k.no_bed=o.no_bed and k.kode_ruangan=o.ruangan and k.kode_kelas=o.kelas","left");
            // $this->db->group_by("k.kode_kamar");
            $q = $this->db->get("oka o");
            return $q->num_rows();
        }
        function getoka_detail($kode){
            $this->db->select("o.*,r.nama_ruangan,kl.nama_kelas,o.klinik as kode_poli,p.keterangan as klinik");
            $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            $this->db->join("kelas kl", "kl.kode_kelas = o.kelas","left");
            $this->db->join("poliklinik p", "p.kode = o.klinik","left");
            $this->db->where("kode_oka",$kode);
            $q = $this->db->get("oka o");
            return $q->row();
        }
        function getoka_detail1($kode){
            $this->db->select("o.*,r.nama_ruangan,kl.nama_kelas");
            $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            $this->db->join("kelas kl", "kl.kode_kelas = o.kelas","left");
            $this->db->where("no_reg",$no_reg);
            $q = $this->db->get("oka o");
            return $q->row();
        }
        function getlaporan_mata($kode){
            $this->db->select("o.*,r.nama_ruangan,kl.nama_kelas,p.tgl_lahir, asan.nama as nama_anastesi, op.nama_dokter, pre.nama as diagnosa, asop.nama as nama_operasi, an.nama_dokter as nama_danastesi");
            $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            $this->db->join("kelas kl", "kl.kode_kelas = o.kelas","left");
            $this->db->join("pasien p","p.no_pasien = o.no_rm","left");
            $this->db->join("dokter op", "op.id_dokter = o.dokter_operasi","left");
            $this->db->join("dokter op2", "op2.id_dokter = o.dokter_operasi2","left");
            $this->db->join("dokter an", "an.id_dokter = o.dokter_anastesi","left");
            $this->db->join("asisten_anastesi asan", "asan.kode = o.asisten_anastesi","left");
            $this->db->join("asisten_anastesi asan2", "asan2.kode = o.asisten_anastesi2","left");
            $this->db->join("asisten_operasi asop", "asop.kode = o.asisten_operasi","left");
            $this->db->join("asisten_operasi asop2", "asop2.kode = o.asisten_operasi2","left");
            $this->db->join("master_icd pre","pre.kode = o.diagnosa","left");
            $this->db->join("master_icd post","post.kode = o.diagnosa_post","left");
            $this->db->join("master_icd post2","post2.kode = o.diagnosa_post2","left");
            $this->db->join("tarif_operasi to","to.kode = o.operasi","left");
            $this->db->join("tarif_operasi to2","to2.kode = o.operasi2","left");
            $this->db->join("jenis_anatesi ja","ja.kode = o.jenis_anastesi","left");
            $this->db->join("klasifikasi kla","kla.kode = o.klasifikasi","left");
            $this->db->where("kode_oka",$kode);
            $this->db->group_by("o.no_reg");
            $q = $this->db->get("oka o");
            return $q->row();
        }
        function getlaporan_pterygium($kode){
            $this->db->select("o.*,r.nama_ruangan,kl.nama_kelas,p.tgl_lahir, asan.nama as nama_anastesi, op.nama_dokter, pre.nama as diagnosa, asop.nama as nama_operasi, an.nama_dokter as nama_danastesi, to.nama_tindakan as tindakan_operasi, ja.nama as jenis_anastesi");
            $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            $this->db->join("kelas kl", "kl.kode_kelas = o.kelas","left");
            $this->db->join("pasien p","p.no_pasien = o.no_rm","left");
            $this->db->join("dokter op", "op.id_dokter = o.dokter_operasi","left");
            $this->db->join("dokter op2", "op2.id_dokter = o.dokter_operasi2","left");
            $this->db->join("dokter an", "an.id_dokter = o.dokter_anastesi","left");
            $this->db->join("asisten_anastesi asan", "asan.kode = o.asisten_anastesi","left");
            $this->db->join("asisten_anastesi asan2", "asan2.kode = o.asisten_anastesi2","left");
            $this->db->join("asisten_operasi asop", "asop.kode = o.asisten_operasi","left");
            $this->db->join("asisten_operasi asop2", "asop2.kode = o.asisten_operasi2","left");
            $this->db->join("master_icd pre","pre.kode = o.diagnosa","left");
            $this->db->join("master_icd post","post.kode = o.diagnosa_post","left");
            $this->db->join("master_icd post2","post2.kode = o.diagnosa_post2","left");
            $this->db->join("tarif_operasi to","to.kode = o.operasi","left");
            $this->db->join("tarif_operasi to2","to2.kode = o.operasi2","left");
            $this->db->join("jenis_anatesi ja","ja.kode = o.jenis_anastesi","left");
            $this->db->join("klasifikasi kla","kla.kode = o.klasifikasi","left");
            $this->db->where("kode_oka",$kode);
            $this->db->group_by("o.no_reg");
            $q = $this->db->get("oka o");
            return $q->row();
        }
        function getlaporan_mataoka($kode){
            $this->db->select("o.*,r.nama_ruangan,kl.nama_kelas,p.tgl_lahir, asan.nama as nama_anastesi, op.nama_dokter, pre.nama as diagnosa, asop.nama as nama_operasi, an.nama_dokter as nama_danastesi");
            $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            $this->db->join("kelas kl", "kl.kode_kelas = o.kelas","left");
            $this->db->join("pasien p","p.no_pasien = o.no_rm","left");
            $this->db->join("dokter op", "op.id_dokter = o.dokter_operasi","left");
            $this->db->join("dokter op2", "op2.id_dokter = o.dokter_operasi2","left");
            $this->db->join("dokter an", "an.id_dokter = o.dokter_anastesi","left");
            $this->db->join("asisten_anastesi asan", "asan.kode = o.asisten_anastesi","left");
            $this->db->join("asisten_anastesi asan2", "asan2.kode = o.asisten_anastesi2","left");
            $this->db->join("asisten_operasi asop", "asop.kode = o.asisten_operasi","left");
            $this->db->join("asisten_operasi asop2", "asop2.kode = o.asisten_operasi2","left");
            $this->db->join("master_icd pre","pre.kode = o.diagnosa","left");
            $this->db->join("master_icd post","post.kode = o.diagnosa_post","left");
            $this->db->join("master_icd post2","post2.kode = o.diagnosa_post2","left");
            $this->db->join("tarif_operasi to","to.kode = o.operasi","left");
            $this->db->join("tarif_operasi to2","to2.kode = o.operasi2","left");
            $this->db->join("jenis_anatesi ja","ja.kode = o.jenis_anastesi","left");
            $this->db->join("klasifikasi kla","kla.kode = o.klasifikasi","left");
            $this->db->where("no_reg",$kode);
            $this->db->group_by("o.no_reg");
            $q = $this->db->get("oka o");
            return $q->row();
        }
        function getlaporan_pterygiumoka($kode){
            $this->db->select("o.*,r.nama_ruangan,kl.nama_kelas,p.tgl_lahir, asan.nama as nama_anastesi, op.nama_dokter, pre.nama as diagnosa, asop.nama as nama_operasi, an.nama_dokter as nama_danastesi, to.nama_tindakan as tindakan_operasi, ja.nama as jenis_anastesi");
            $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            $this->db->join("kelas kl", "kl.kode_kelas = o.kelas","left");
            $this->db->join("pasien p","p.no_pasien = o.no_rm","left");
            $this->db->join("dokter op", "op.id_dokter = o.dokter_operasi","left");
            $this->db->join("dokter op2", "op2.id_dokter = o.dokter_operasi2","left");
            $this->db->join("dokter an", "an.id_dokter = o.dokter_anastesi","left");
            $this->db->join("asisten_anastesi asan", "asan.kode = o.asisten_anastesi","left");
            $this->db->join("asisten_anastesi asan2", "asan2.kode = o.asisten_anastesi2","left");
            $this->db->join("asisten_operasi asop", "asop.kode = o.asisten_operasi","left");
            $this->db->join("asisten_operasi asop2", "asop2.kode = o.asisten_operasi2","left");
            $this->db->join("master_icd pre","pre.kode = o.diagnosa","left");
            $this->db->join("master_icd post","post.kode = o.diagnosa_post","left");
            $this->db->join("master_icd post2","post2.kode = o.diagnosa_post2","left");
            $this->db->join("tarif_operasi to","to.kode = o.operasi","left");
            $this->db->join("tarif_operasi to2","to2.kode = o.operasi2","left");
            $this->db->join("jenis_anatesi ja","ja.kode = o.jenis_anastesi","left");
            $this->db->join("klasifikasi kla","kla.kode = o.klasifikasi","left");
            $this->db->where("no_reg",$kode);
            $this->db->group_by("o.no_reg");
            $q = $this->db->get("oka o");
            return $q->row();
        }
        function simpanoka($action){
            if ($this->input->post("pemeriksaan") == "Ya PA"){
                $pa = "Ya";
                $cairan = "Tidak";
            }
            else if($this->input->post("pemeriksaan") == "Ya Cairan"){
                $pa = "Tidak";
                $cairan = "Ya";
            }
            else{
                $pa = "Tidak";
                $cairan = "Tidak";
            }
            $mata = "";
            $lain = "";
            $koma = "";
            for($i=1;$i<=90;$i++){
                $mata .= $koma."0";
                $koma = ",";
            }
            $koma = "";
            for($i=1;$i<=15;$i++){
                $lain .= $koma." ";
                $koma = ",";
            }
            $pet = "";
            $petlain = "";
            $koma = "";
            for($i=1;$i<=90;$i++){
                $pet .= $koma."0";
                $koma = ",";
            }
            $koma = "";
            for($i=1;$i<=15;$i++){
                $petlain .= $koma." ";
                $koma = ",";
            }
            switch ($action) {
                case 'simpan':
                    $kode_oka = date("dmYHis");
                    $data = array(
                                'kode_oka'          => $kode_oka,
                                'pelayanan'         => $this->input->post("pelayanan"),
                                'no_reg'            => $this->input->post("no_reg"),
                                'no_rm'             => $this->input->post("no_rm"),
                                'nama'              => $this->input->post("nama"),
                                'jk'                => $this->input->post("jk"),
                                'ruangan'           => $this->input->post("kode_ruangan"),
                                'klinik'            => $this->input->post("kode_poli"),
                                'kelas'             => $this->input->post("kode_kelas"),
                                'kamar'             => $this->input->post("kamar"),
                                'no_bed'            => $this->input->post("no_bed"),
                                'diagnosa'          => $this->input->post("diagnosa"),
                                'diagnosa_post'     => $this->input->post("post_diagnosa"),
                                'jam_masuk'         => $this->input->post("jam_masuk"),
                                'jam_keluar'        => $this->input->post("jam_keluar"),
                                'operasi'           => $this->input->post("operasi"),
                                'jenis_operasi'     => $this->input->post("jenis_operasi"),
                                'dokter_operasi'    => $this->input->post("dokter_operasi"),
                                'asisten_operasi'   => $this->input->post("asisten_operasi"),
                                'asisten_operasi2'  => $this->input->post("asisten_operasi2"),
                                'kamar_operasi'     => $this->input->post("kamar_operasi"),
                                'dokter_anastesi'   => $this->input->post("dokter_anastesi"),
                                'asisten_anastesi'  => $this->input->post("asisten_anastesi"),
                                'asisten_anastesi2' => $this->input->post("asisten_anastesi2"),
                                'jenis_anastesi'    => $this->input->post("jenis_anastesi"),
                                'klasifikasi'       => $this->input->post("klasifikasi"),
                                'tanggal'           => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                                'diagnosa_post2'    => $this->input->post("post_diagnosa2"),
                                'dokter_operasi2'   => $this->input->post("dokter_operasi2"),
                                'operasi2'          => $this->input->post("operasi2"),
                                'jam_anastesi'      => $this->input->post("jam_anastesi"),
                                'jenis_jaringan'    => $this->input->post("jenis_jaringan"),
                                'pemeriksaan_pa'    => $pa,
                                'pemeriksaan_cairan'=> $cairan,
                                'jenis_pemeriksaan' => $this->input->post("jenis_pemeriksaan"),
                                'mata' => $mata,
                                'lain' => $lain,
                                'pterygium' => $pet,
                                'petlain' => $petlain,
                            );
                    $this->db->insert("oka",$data);
                    // $this->session->set_userdata("kode_ok",date("dmYHis"));
                    break;
                case 'edit':
                    $kode_oka = $this->input->post("kode_oka");
                    $data = array(
                                'diagnosa'          => $this->input->post("diagnosa"),
                                'diagnosa_post'     => $this->input->post("post_diagnosa"),
                                'jam_masuk'         => $this->input->post("jam_masuk"),
                                'jam_keluar'        => $this->input->post("jam_keluar"),
                                'operasi'           => $this->input->post("operasi"),
                                'jenis_operasi'     => $this->input->post("jenis_operasi"),
                                'dokter_operasi'    => $this->input->post("dokter_operasi"),
                                'asisten_operasi'   => $this->input->post("asisten_operasi"),
                                'asisten_operasi2'  => $this->input->post("asisten_operasi2"),
                                'kamar_operasi'     => $this->input->post("kamar_operasi"),
                                'dokter_anastesi'   => $this->input->post("dokter_anastesi"),
                                'asisten_anastesi'  => $this->input->post("asisten_anastesi"),
                                'asisten_anastesi2' => $this->input->post("asisten_anastesi2"),
                                'jenis_anastesi'    => $this->input->post("jenis_anastesi"),
                                'klasifikasi'       => $this->input->post("klasifikasi"),
                                'tanggal'           => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                                'diagnosa_post2'    => $this->input->post("post_diagnosa2"),
                                'dokter_operasi2'   => $this->input->post("dokter_operasi2"),
                                'operasi2'          => $this->input->post("operasi2"),
                                'jam_anastesi'      => $this->input->post("jam_anastesi"),
                                'jenis_jaringan'    => $this->input->post("jenis_jaringan"),
                                'pemeriksaan_pa'    => $pa,
                                'pemeriksaan_cairan'=> $cairan,
                                'jenis_pemeriksaan' => $this->input->post("jenis_pemeriksaan"),
                            );
                    $this->db->where("kode_oka",$this->input->post("kode_oka"));
                    $this->db->update("oka",$data);
                    break;
            }
            if ($this->input->post("pelayanan")=="RALAN"){
                $this->db->delete("kasir",["no_reg"=>$this->input->post("no_reg"),"kode_tarif"=>$this->input->post("operasi")]);
                $t = $this->db->get_where("tarif_ralan",["kode_tindakan" => $this->input->post("operasi")]);
                if ($t->num_rows()>0){
                    $data = $t->row();
                    $id = date("dmyHis");
                    if ($this->input->post('jenis')=="R") $tarif = $data->reguler; else $tarif = $data->executive;
                    $d = array(
                            "id" => $id,
                            "no_reg" => $this->input->post("no_reg"),
                            "kode_tarif" => $this->input->post("operasi"),
                            "kode_petugas" => $this->input->post("dokter_operasi"),
                            "analys" => $this->input->post("dokter_anastesi"),
                            "jumlah" => $tarif,
                            "bayar" => 0
                         );
                    $this->db->insert("kasir",$d);
                }
            } else {
                $this->db->delete("kasir_inap",["no_reg"=>$this->input->post("no_reg"),"kode_tarif"=>$this->input->post("operasi")]);
                $t = $this->db->get_where("tarif_operasi",["kode" => $this->input->post("operasi")]);
                if ($t->num_rows()>0){
                    $id = date("dmyHis");
                    $t = $t->row_array();
                    switch ($this->input->post('kode_kelas')) {
                        case '01':
                            $tarif = $t["supervip_deluxe"];
                            break;
                        case '02':
                            $tarif = $t["supervip_premium"];
                            break;
                        case '03':
                            $tarif = $t["supervip_executive"];
                            break;
                        case '04':
                            $tarif = $t["supervip"];
                            break;
                        case '05':
                            $tarif = $t["vip"];
                            break;
                        case '051':
                            $tarif = $t["vip1"];
                            break;
                        case '052':
                            $tarif = $t["vip2"];
                            break;
                        case '053':
                            $tarif = $t["vip3"];
                            break;
                        case '06':
                            $tarif = $t["kelas1"];
                            break;
                        case '07':
                            $tarif = $t["kelas2"];
                            break;
                        case '08':
                            $tarif = $t["kelas3"];
                            break;
                        case '09':
                            $tarif = $t["icu"];
                            break;
                        case '10':
                            $tarif = $t["nicu"];
                            break;
                        case '11':
                            $tarif = $t["nicu"];
                            break;
                        case '12':
                            $tarif = $t["bayi"];
                            break;
                        case '13':
                            $tarif = $t["bayi"];
                            break;
                    }
                    $d = array(
                            "id" => $id,
                            "no_reg" => $this->input->post("no_reg"),
                            "kode_tarif" => $this->input->post("operasi"),
                            "jumlah" => $tarif,
                            "qty" => 1,
                            "tanggal" => date("Y-m-d",strtotime($this->input->post('tanggal')))
                         );
                    $this->db->insert("kasir_inap",$d);
                    $this->db->like("jenis",$t->id_operasi);
                    $s = $this->db->get("tarif_opr");
                    foreach($s->result() as $row){
                        $kode_petugas = "";
                        switch ($row->kode_tindakan) {
                            case 'dr_ahli_anaestesi':
                                $kode_petugas = $this->input->post("dokter_anastesi");
                                break;
                            case 'asisten_operasi':
                                $kode_petugas = $this->input->post("asisten_operasi");
                                break;
                            case 'asisten_anaestesi':
                                $kode_petugas = $this->input->post("asisten_anastesi");
                                break;
                        }
                        $trf = (($t[$row->kode_tindakan]*$tarif)/100)*1;
                        $data = array(
                            "id" => $id++,
                            "no_reg" => $this->input->post("no_reg"),
                            "tanggal" => date("Y-m-d",strtotime($this->input->post("tanggal"))),
                            "kode_tarif" => $row->kode_tindakan,
                            "qty" => 1,
                            "kode_petugas" => $kode_petugas,
                            "jumlah" => $trf
                        );
                        $this->db->insert("kasir_inap",$data);
                    }
                }
            }
            return $kode_oka;
        }
        function getpasien(){
            if ($this->input->post("pelayanan")=="RALAN"){
                $this->db->select("pr.no_reg,pr.no_pasien, p.nama_pasien, p.jenis_kelamin as jk, pr.tujuan_poli as kode_poli, k.keterangan as poliklinik,pr.jenis");
                $this->db->like("pr.no_pasien",$this->input->post("no_rm"));
                $this->db->or_like("pr.no_reg",$this->input->post("no_rm"));
                $this->db->or_like("p.nama_pasien",$this->input->post("no_rm"));
                $this->db->join("pasien p","p.no_pasien=pr.no_pasien","inner");
                $this->db->join("poliklinik k","k.kode=pr.tujuan_poli","left");
                $q = $this->db->get("pasien_ralan pr");
                $data = array();
                foreach ($q->result() as $key => $value) {
                    $data[] = array('no_reg' => $value->no_reg,'no_rm' => $value->no_pasien, 'nama_pasien' => $value->nama_pasien , 'jk' => $value->jk , 'poliklinik' => $value->poliklinik, 'jenis' => $value->jenis);
                }
            } else {
                $this->db->select("pr.kode_ruangan,pr.kode_kelas,pr.no_bed,pr.kode_kamar,pr.no_reg,pr.no_rm as no_pasien, p.nama_pasien, p.jenis_kelamin as jk, r.nama_ruangan, kel.nama_kelas, kam.nama_kamar");
                $this->db->like("pr.no_rm",$this->input->post("no_rm"));
                $this->db->or_like("pr.no_reg",$this->input->post("no_rm"));
                $this->db->or_like("p.nama_pasien",$this->input->post("no_rm"));
                $this->db->join("pasien p","p.no_pasien=pr.no_rm","inner");
                $this->db->join("ruangan r","r.kode_ruangan=pr.kode_ruangan","left");
                $this->db->join("kelas kel","kel.kode_kelas=pr.kode_kelas","left");
                $this->db->join("kamar kam","kam.kode_kelas=pr.kode_kelas AND kam.kode_kamar = pr.kode_kamar AND kam.kode_ruangan = r.kode_ruangan","left");
                $this->db->group_by("pr.no_reg");
                $q = $this->db->get("pasien_inap pr");
                foreach ($q->result() as $key => $value) {
                    $data[] = array('no_reg' => $value->no_reg,'no_rm' => $value->no_pasien, 'nama_pasien' => $value->nama_pasien, 'jk' => $value->jk, 'kode_ruangan' => $value->kode_ruangan, 'nama_ruangan' => $value->nama_ruangan, 'kode_kelas' => $value->kode_kelas, 'nama_kelas' => $value->nama_kelas, 'kode_kamar' => $value->kode_kamar, 'nama_kamar' =>$value->nama_kamar, 'no_bed' => $value->no_bed);
                }
            }
            return $data;
        }
        function getdiagnosa(){
            return $this->db->get("master_icd");
        }
        function getdokter(){
            $this->db->where("p.kode", "0102005");
            $this->db->or_where("p.kode", "0102007");
            $this->db->or_where("p.kode", "0102010");
            $this->db->or_where("p.kode", "0102016");
            $this->db->or_where("p.kode", "0102020");
            $this->db->or_where("p.kode", "0102003");
            $this->db->or_where("p.kode", "0102009");
            $this->db->or_where("p.kode", "0102021");
            $this->db->or_where("p.kode", "0102004");
            $this->db->join("jadwal_dokter j","j.id_dokter = d.id_dokter","left");
            $this->db->join("poliklinik p","p.kode = j.id_poli","left");
            return $this->db->get("dokter d");
        }
        function getdokter_anastesi(){
            $this->db->where("p.kode", "0102032");
            $this->db->join("jadwal_dokter j","j.id_dokter = d.id_dokter","left");
            $this->db->join("poliklinik p","p.kode = j.id_poli","left");
            return $this->db->get("dokter d");
        }
        function getjenis_anatesi(){
            return $this->db->get("jenis_anatesi");
        }
        function getklasifikasi(){
            return $this->db->get("klasifikasi");
        }
        function getasisten_op(){
            return $this->db->get("asisten_operasi");
        }
        function getasisten_an(){
            return $this->db->get("asisten_anastesi");
        }
        function getkamar_operasi(){
            return $this->db->get("kamar_operasi");
        }
        function getoperasi(){
            if ($this->input->post("pelayanan")=="RALAN"){
                $this->db->like("kode_tindakan",$this->input->post("kode"));
                $this->db->or_like("nama_tindakan",$this->input->post("kode"));
                $q = $this->db->get("tarif_ralan");
                $data = array();
                foreach ($q->result() as $key => $value) {
                    $data[] = array('kode' => $value->kode_tindakan,'nama_tindakan' => $value->nama_tindakan, 'id_cabang_operasi' => 'K');
                }
            } else {
                $this->db->like("kode",$this->input->post("kode"));
                $this->db->or_like("nama_tindakan",$this->input->post("kode"));
                $q = $this->db->get("tarif_operasi");
                $data = array();
                foreach ($q->result() as $key => $value) {
                    $data[] = array('kode' => $value->kode,'nama_tindakan' => $value->nama_tindakan, 'id_cabang_operasi' => $value->id_cabang_operasi);
                }
            }
            return $data;
        }
        function getoperasi_array(){
            $q = $this->db->get("tarif_ralan");
            $data = array();
            foreach ($q->result() as $key => $value) {
                $data[$value->kode_tindakan] = array('kode' => $value->kode_tindakan,'nama_tindakan' => $value->nama_tindakan, 'id_cabang_operasi' => 'K');
            }
            $q = $this->db->get("tarif_operasi");
            foreach ($q->result() as $key => $value) {
                $data[$value->kode] = array('kode' => $value->kode,'nama_tindakan' => $value->nama_tindakan, 'id_cabang_operasi' => $value->id_cabang_operasi);
            }
            return $data;
        }
        function getdiagnosa_operasi(){
            $this->db->like("kode",$this->input->post("kode"));
            $this->db->or_like("nama",$this->input->post("kode"));
            $q = $this->db->get("master_icd");
            $data = array();
            foreach ($q->result() as $key => $value) {
                $data[] = array('kode' => $value->kode,'nama' => $value->nama);
            }
            return $data;
        }
        function gettarif_operasi(){
            return $this->db->get("tarif_operasi");
        }
        function getkamar(){
            $this->db->where("k.kode_kamar >=", "01");
            $this->db->where("k.kode_kamar <=", "05");
            $this->db->join("ruangan r","r.kode_ruangan = k.kode_ruangan","left");
            return $this->db->get("kamar k");
        }
        function hapus($kode){
            $this->db->where("kode_oka", $kode);
            $this->db->delete("oka");
            return "danger-Data berhasil di Hapus";
        }
        function simpanlaporan($kode){
            $data = array(
                'laporan' => $this->session->userdata("laporan"),
                'layan' => "1"
            );
            $this->db->where("kode_oka", $kode);
            $this->db->update("oka", $data);
        }
        function simpankomplikasi($kode){
            $data = array(
                    // 'komplikasi' => str_replace("%20", " ", $komplikasi)
                    'komplikasi' => $this->session->userdata("komplikasi")
            );
            $this->db->where("kode_oka", $kode);
            $this->db->update("oka", $data);
        }
        function batal($kode){
            $data = array(
                    'layan' => "2"
            );
            $this->db->where("kode_oka", $kode);
            $this->db->update("oka", $data);
        }
        function simpanintruksi($kode){
            $data = array(
                    // 'intruksi' => str_replace("%20", " ", $intruksi)
                    'intruksi' => $this->session->userdata("intruksi")
            );
            $this->db->where("kode_oka", $kode);
            $this->db->update("oka", $data);
        }
        function getcetak($kode){
            $this->db->select("o.*, p.alamat, k.no_bed,
                p.tgl_lahir, op.nama_dokter as dokter_op,
                op2.nama_dokter as dokter_op2, an.nama_dokter as dokter_an,
                asan.nama as asisten_an, asan2.nama as asisten_an2,
                asop.nama as asisten_op, asop2.nama as asisten_op2,
                pre.nama as pre_diagnosa, post.nama as post_diagnosa, post2.nama as post_diagnosa2,
                to.nama_tindakan as nama_operasi, to2.nama_tindakan as nama_operasi2, ja.nama as j_anastesi, kla.nama as nama_klasifikasi"
            );
            $this->db->join("pasien p", "p.no_pasien = o.no_rm","left");
            $this->db->join("kamar k", "k.kode_kamar = o.kamar","left");
            $this->db->join("dokter op", "op.id_dokter = o.dokter_operasi","left");
            $this->db->join("dokter op2", "op2.id_dokter = o.dokter_operasi2","left");
            $this->db->join("dokter an", "an.id_dokter = o.dokter_anastesi","left");
            $this->db->join("asisten_anastesi asan", "asan.kode = o.asisten_anastesi","left");
            $this->db->join("asisten_anastesi asan2", "asan2.kode = o.asisten_anastesi2","left");
            $this->db->join("asisten_operasi asop", "asop.kode = o.asisten_operasi","left");
            $this->db->join("asisten_operasi asop2", "asop2.kode = o.asisten_operasi2","left");
            $this->db->join("master_icd pre","pre.kode = o.diagnosa","left");
            $this->db->join("master_icd post","post.kode = o.diagnosa_post","left");
            $this->db->join("master_icd post2","post2.kode = o.diagnosa_post2","left");
            $this->db->join("tarif_operasi to","to.kode = o.operasi","left");
            $this->db->join("tarif_operasi to2","to2.kode = o.operasi2","left");
            $this->db->join("jenis_anatesi ja","ja.kode = o.jenis_anastesi","left");
            $this->db->join("klasifikasi kla","kla.kode = o.klasifikasi","left");
            // $this->db->join("tarif_operasi toj","toj.kode = o.operasi AND toj.id_cabang_operasi = o.jenis","left");
            $this->db->where("kode_oka", $kode);
            // $this->db->group_by("p.no_pasien");
            $q = $this->db->get("oka o");
            return $q->row();
        }
        function getcetakoka($kode){
            $this->db->select("o.*, p.alamat, k.no_bed,
                p.tgl_lahir, op.nama_dokter as dokter_op,
                op2.nama_dokter as dokter_op2, an.nama_dokter as dokter_an,
                asan.nama as asisten_an, asan2.nama as asisten_an2,
                asop.nama as asisten_op, asop2.nama as asisten_op2,
                pre.nama as pre_diagnosa, post.nama as post_diagnosa, post2.nama as post_diagnosa2,
                to.nama_tindakan as nama_operasi, to2.nama_tindakan as nama_operasi2, ja.nama as j_anastesi, kla.nama as nama_klasifikasi"
            );
            $this->db->join("pasien p", "p.no_pasien = o.no_rm","left");
            $this->db->join("kamar k", "k.kode_kamar = o.kamar","left");
            $this->db->join("dokter op", "op.id_dokter = o.dokter_operasi","left");
            $this->db->join("dokter op2", "op2.id_dokter = o.dokter_operasi2","left");
            $this->db->join("dokter an", "an.id_dokter = o.dokter_anastesi","left");
            $this->db->join("asisten_anastesi asan", "asan.kode = o.asisten_anastesi","left");
            $this->db->join("asisten_anastesi asan2", "asan2.kode = o.asisten_anastesi2","left");
            $this->db->join("asisten_operasi asop", "asop.kode = o.asisten_operasi","left");
            $this->db->join("asisten_operasi asop2", "asop2.kode = o.asisten_operasi2","left");
            $this->db->join("master_icd pre","pre.kode = o.diagnosa","left");
            $this->db->join("master_icd post","post.kode = o.diagnosa_post","left");
            $this->db->join("master_icd post2","post2.kode = o.diagnosa_post2","left");
            $this->db->join("tarif_operasi to","to.kode = o.operasi","left");
            $this->db->join("tarif_operasi to2","to2.kode = o.operasi2","left");
            $this->db->join("jenis_anatesi ja","ja.kode = o.jenis_anastesi","left");
            $this->db->join("klasifikasi kla","kla.kode = o.klasifikasi","left");
            // $this->db->join("tarif_operasi toj","toj.kode = o.operasi AND toj.id_cabang_operasi = o.jenis","left");
            $this->db->where("no_reg", $kode);
            // $this->db->group_by("p.no_pasien");
            $q = $this->db->get("oka o");
            return $q->row();
        }
        function getjadwal(){
            $tgl1 = $this->session->userdata("tgl1");
            $tgl2 = $this->session->userdata("tgl2");
            $pelayanan = $this->session->userdata("pelayanan");
            $cari_no = $this->session->userdata("cari_no");
            $this->db->where("o.laporan", NULL);
            if ($cari_no==""){
                if ($tgl1 != "" || $tgl2 !=""){
                    $this->db->where("date(o.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
                    $this->db->where("date(o.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
                }
            }
            if ($pelayanan != "") {
                $this->db->where("o.pelayanan",$pelayanan);
            }
            $this->db->group_start();
            $this->db->like("o.kode_oka",$cari_no);
            $this->db->or_like("o.nama",$cari_no);
            $this->db->or_like("o.no_rm",$cari_no);
            $this->db->or_like("o.no_reg",$cari_no);
            $this->db->group_end();
            // $this->db->select("o.*, p.alamat, p.tgl_lahir, k.no_bed, g.keterangan as gol_pasien, r.nama_ruangan, k.nama_kamar, jn.nama as j_anastesi, da.nama_dokter as anastesi, do.nama_dokter as operator, ao.nama as a_operasi");
            $this->db->select("o.*, p.alamat, p.tgl_lahir,g.keterangan as gol_pasien");
            $this->db->join("pasien p", "p.no_pasien = o.no_rm","inner");
            $this->db->join("gol_pasien g","g.id_gol = p.id_gol");
            // $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","inner");
            // $this->db->join("kamar k", "k.kode_kamar = o.kamar","inner");
            // $this->db->join("jenis_anatesi jn", "jn.kode = o.jenis_anastesi","left");
            // $this->db->join("dokter da", "da.id_dokter = o.dokter_anastesi","left");
            // $this->db->join("dokter do", "do.id_dokter = o.dokter_operasi","left");
            // $this->db->join("master_icd post","post.kode = o.diagnosa_post","left");
            // $this->db->join("asisten_operasi ao", "ao.kode = o.asisten_operasi","left");
            $this->db->group_by("no_rm");
            // $this->db->group_by("p.no_pasien");
            $q = $this->db->get("oka o");
            return $q;
        }
        function getjadwal_dashboard(){
            // $this->db->where("o.laporan", NULL);
            $this->db->where("date(o.tanggal)>=",date("Y-m-d"));
            $this->db->select("o.*, p.alamat, p.tgl_lahir,g.keterangan as gol_pasien");
            $this->db->join("pasien p", "p.no_pasien = o.no_rm","inner");
            $this->db->join("gol_pasien g","g.id_gol = p.id_gol");
            $this->db->group_by("no_rm");
            $this->db->order_by("o.jam_masuk","desc");
            $q = $this->db->get("oka o");
            return $q;
        }
        function getjadwal_fromklinik(){
            $this->db->where("o.pelayanan", "RALAN");
            $this->db->where("date(o.tanggal)>=",date("Y-m-d"));
            $this->db->select("o.no_reg,p.tujuan_poli");
            $this->db->join("pasien_ralan p", "p.no_pasien = o.no_rm and p.no_reg=o.no_reg","inner");
            $this->db->group_by("no_rm");
            $this->db->order_by("o.jam_masuk","desc");
            $q = $this->db->get("oka o");
            $data = array();
            foreach($q->result() as $row){
                $data[$row->no_reg] = $row->tujuan_poli;
            }
            return $data;
        }
        function getcetak_pasien(){
            $tgl1 = $this->session->userdata("tgl1");
            $tgl2 = $this->session->userdata("tgl2");
            $pelayanan = $this->session->userdata("pelayanan");
            $cari_no = $this->session->userdata("cari_no");
            // $this->db->or_where("o.mata", NULL);
            if ($cari_no==""){
                if ($tgl1 != "" || $tgl2 !=""){
                    $this->db->where("date(o.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
                    $this->db->where("date(o.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
                }
            }
            if ($pelayanan != "") {
                $this->db->where("o.pelayanan",$pelayanan);
            }
            $this->db->group_start();
            $this->db->like("o.kode_oka",$cari_no);
            $this->db->or_like("o.nama",$cari_no);
            $this->db->or_like("o.no_rm",$cari_no);
            $this->db->or_like("o.no_reg",$cari_no);
            $this->db->group_end();
            $this->db->select("o.*, p.alamat, p.tgl_lahir, g.keterangan as gol_pasien, to.nama_tindakan as operasi_1");
            $this->db->join("pasien p", "p.no_pasien = o.no_rm","left");
            $this->db->join("gol_pasien g","g.id_gol = p.id_gol");
            // $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan","left");
            // $this->db->join("kamar k", "k.kode_kamar = o.kamar","left");
            // $this->db->join("jenis_anatesi jn", "jn.kode = o.jenis_anastesi","left");
            // $this->db->join("dokter da", "da.id_dokter = o.dokter_anastesi","left");
            // $this->db->join("dokter do", "do.id_dokter = o.dokter_operasi","left");
            // $this->db->join("master_icd post","post.kode = o.diagnosa_post","left");
            $this->db->join("tarif_operasi to","to.kode = o.operasi","left");
            // $this->db->join("asisten_operasi ao", "ao.kode = o.asisten_operasi","left");
            $this->db->group_by("no_rm");
            // $this->db->group_by("p.no_pasien");
            $q = $this->db->get("oka o");
            return $q;
        }
        function gettindakan(){
            $q = $this->db->get("tarif_operasi");
            return $q;
        }
        function gettindakan2($tindakan){
            if ($tindakan!="all") {
                $this->db->where("kode",$tindakan);
            }
            $q = $this->db->get("tarif_operasi");
            return $q->row();
        }
        function getrekap($tindakan,$tgl1="",$tgl2="",$pelayanan,$dokter_operasi,$dokter_anastesi){
                $data = array();
                $tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
                $tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
                $this->db->select("p.operasi,pa.id_gol");
                if ($tindakan!="all") {
                    $this->db->where("p.operasi",$tindakan);
                }
                if ($dokter_operasi!="all") {
                    $this->db->where("p.dokter_operasi",$dokter_operasi);
                }
                if ($dokter_anastesi!="all") {
                    $this->db->where("p.dokter_anastesi",$dokter_anastesi);
                }
                $this->db->where("date(p.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
                $this->db->where("date(p.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
                if ($pelayanan!="all") {
                    $this->db->where("p.pelayanan", $pelayanan);
                }
                // $this->db->join("kasir_inap k","k.no_reg=p.no_reg","inner");
                $this->db->join("pasien pa","pa.no_pasien = p.no_rm","left");
                // $this->db->order_by("jumlah","desc");
                // $this->db->group_by("kode_tarif");
                $sql = $this->db->get("oka p");
                foreach ($sql->result() as $key) {
                    if (isset($data["tindakan"][$key->operasi]))
                        $data["tindakan"][$key->operasi] += 1;
                    else
                        $data["tindakan"][$key->operasi] = 1;

                    if (($key->id_gol>=404 && $key->id_gol<=410) || ($key->id_gol>=415 && $key->id_gol<=417) || ($key->id_gol==3133)){
                        if (isset($data["DINAS"][$key->operasi]))
                            $data["DINAS"][$key->operasi] += 1;
                        else
                            $data["DINAS"][$key->operasi] = 1;
                    } else
                    if ($key->id_gol==11){
                        if (isset($data["UMUM"][$key->operasi]))
                            $data["UMUM"][$key->operasi] += 1;
                        else
                            $data["UMUM"][$key->operasi] = 1;
                    } else
                    if (($key->id_gol>=400 && $key->id_gol<=403) || ($key->id_gol>=411 && $key->id_gol<=414) || ($key->id_gol>=418 && $key->id_gol<=420)){
                        if (isset($data["BPJS"][$key->operasi]))
                            $data["BPJS"][$key->operasi] += 1;
                        else
                            $data["BPJS"][$key->operasi] = 1;
                    } else
                    if (($key->id_gol==12) || ($key->id_gol==13) || ($key->id_gol>=16 && $key->id_gol<=18)){
                        if (isset($data["PRSH"][$key->operasi]))
                            $data["PRSH"][$key->operasi] += 1;
                        else
                            $data["PRSH"][$key->operasi] = 1;
                    }
                }
                return $data;
        }
        function getpasien_rekap_inap($tindakan,$tgl1,$tgl2,$pelayanan){
            // var_dump($pelayanan);
            if ($pelayanan == "RANAP"){
                $this->db->select("pi.*,p.nama_pasien as nama_pasien,s.keterangan, r.nama_ruangan, d.nama_dokter, kls.nama_kelas, kmr.nama_kamar");
                $this->db->join("pasien_inap o","o.no_reg=pi.no_reg");
                $this->db->join("status_pulang s","s.id=o.status_pulang","left");
                $this->db->join("ruangan r","r.kode_ruangan=pi.ruangan","left");
                $this->db->join("kelas kls","kls.kode_kelas=pi.kelas","left");
                $this->db->join("kamar kmr","kmr.kode_kamar=pi.kamar and kls.kode_kelas = kmr.kode_kelas and r.kode_ruangan = kmr.kode_ruangan","left");
            }else if($pelayanan =="RALAN"){
                $this->db->select("pi.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, d.nama_dokter, pol.keterangan as nama_poli");
                $this->db->join("pasien_ralan o","k.no_reg=pi.no_reg","inner");
                $this->db->join("poliklinik pol2","pol2.kode=o.tujuan_poli");
                $this->db->join("poliklinik pol","pol.kode = o.dari_poli","left");
                $this->db->join("dokter d","d.id_dokter = pi.dokter_operasi","left");
            }
            $this->db->join("pasien p","p.no_pasien=pi.no_rm");
            $this->db->join("dokter d","d.id_dokter = pi.dokter_operasi","left");
            $this->db->join("tarif_operasi to", "to.kode = pi.operasi");
            // $this->db->where("pi.tujuan_poli","0102024");
            $this->db->where("to.kode",$tindakan);
            $this->db->where("date(pi.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
            $this->db->where("date(pi.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
            $this->db->order_by("pi.no_reg");
            $this->db->group_by("pi.kode_oka,pi.no_reg");
            $query = $this->db->get("oka pi");
            return $query->result();
        }
        function getpasien_rekap($tindakan,$tgl1,$tgl2){
            $this->db->select("pr.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, k.pemeriksaan, d.nama_dokter, pol.keterangan as nama_poli");
            $this->db->order_by("pr.no_reg");
            $this->db->join("pasien p","p.no_pasien=pr.no_pasien");
            $this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli");
            $this->db->join("kasir k","k.no_reg=pr.no_reg","inner");
            $this->db->join("poliklinik pol","pol.kode = pr.dari_poli","left");
            $this->db->join("dokter d","d.id_dokter = pr.dokter_pengirim","left");
            $this->db->where("pr.tujuan_poli","0102024");
            $this->db->where("k.kode_tarif",$tindakan);
            $this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
            $this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
            $query = $this->db->get("pasien_ralan pr");
            return $query->result();
        }
        function namadiagnosa(){
            $q = $this->db->get_where("master_icd",["kode"=>$this->input->post("kode")]);
            if ($q->num_rows()>0) return $q->row()->nama; else return "-";
        }
        function namaoperasi(){
            if ($this->input->post("pelayanan")=="RALAN"){
                $q = $this->db->get_where("tarif_ralan",["kode_tindakan"=>$this->input->post("kode")]);
            } else {
                $q = $this->db->get_where("tarif_operasi",["kode"=>$this->input->post("kode")]);
            }
            if ($q->num_rows()>0) return $q->row()->nama_tindakan; else return "-";
        }
        function simpan_mataak($action){
            $mata ="";
            $koma = "";
            for($i=0;$i<=90;$i++){
                $mata .= $koma."1";
                $koma = ",";
            }
            $data = array(
                            'mata' => $akses_level
                        );
            $this->db->where("kode_oka", $this->input->post("kode_oka"));
            $this->db->insert("update",$data);
            return "success-Data berhasil disimpan ....";
        }
        function simpan_mata(){
        	$lain = "";
            $mata = "";
            $koma = "";
            for($i=1;$i<=90;$i++){
                $mata .= $koma.($this->input->post("mata".$i)!="" ? $this->input->post("mata".$i) : 0);
                $koma = ",";
            }
            $koma = "";
            for($i=1;$i<=15;$i++){
                $lain .= $koma.($this->input->post("lain".$i)!="" ? $this->input->post("lain".$i) : 0);
                $koma = ",";
            }
            $data = array(
                            'mata' => $mata,
                            'lain' => $lain,
                            'layan' => "1",
                            'laporan' => $this->input->post("laporan")
                        );

            $this->db->where("kode_oka",$this->input->post('kode_oka'));
            $this->db->update("oka",$data);
            return "success-Data berhasil disimpan ....";
        }
        function simpan_pterygium(){
            $petlain = "";
            $pterygium = "";
            $koma = "";
            for($i=1;$i<=90;$i++){
                $pterygium .= $koma.($this->input->post("mata".$i)!="" ? $this->input->post("mata".$i) : 0);
                $koma = ",";
            }
            $koma = "";
            for($i=1;$i<=15;$i++){
                $petlain .= $koma.($this->input->post("lain".$i)!="" ? $this->input->post("lain".$i) : 0);
                $koma = ",";
            }
            $data = array(
                            'pterygium' => $pterygium,
                            'petlain' => $petlain,
                            'layan' => "1",
                            'keterangan_tambahan' =>  $this->input->post("keterangan_tambahan"),
                            'laporan' => $this->input->post("laporan")
                        );

            $this->db->where("kode_oka",$this->input->post('kode_oka'));
            $this->db->update("oka",$data);
            return "success-Data berhasil disimpan ....";
        }
        function gettabel($tabel){
            $fields = $this->db->field_data($tabel);
            $namafield = array();
            foreach ($fields as $field){
               $namafield[] = $field->name;
            }
            $q = $this->db->get($tabel);
            $data = array();
            foreach($q->result_array() as $row){
                $data[$row[$namafield[0]]] = $row[$namafield[1]];
            }
            return $data;
        }
        function getmaster_icd($kode){
            $q = $this->db->get_where("master_icd",["kode"=>$kode]);
            if ($q->num_rows()>0){
                $q = $q->row()->nama;
            } else $q = "";
            return $q;
        }
        function gettindakan_full_rekap(){
            $data = array();
            //ralan
            //$ralan = array('T291','T271','T188','T059','T055');
            $this->db->select("t.operasi ");
            $this->db->like("t.operasi","T",'after');
            $ralan = $this->db->get("oka t");
            foreach ($ralan->result() as $cari) {
                $cari_ralan = $cari->operasi;
                $this->db->select("tp.kode_tindakan as kode ,tp.nama_tindakan");
                $this->db->where_in('kode_tindakan', $cari_ralan);
                $query = $this->db->get("tarif_ralan tp");
                foreach ($query->result() as $row) {
                    $data["kode"][$row->kode] = $row;
                }
            }
            // $this->db->select("tp.kode_tindakan as kode ,tp.nama_tindakan");
            // $this->db->where_in('kode_tindakan', $ralan);
            // $query = $this->db->get("tarif_ralan tp");
            // foreach ($query->result() as $row) {
            //         $data["kode"][$row->kode] = $row;
            // }
            //ranap
            $this->db->select("tp.kode ,tp.nama_tindakan");
            $query = $this->db->get("tarif_operasi tp");
            foreach ($query->result() as $row) {
                $data["kode"][$row->kode] = $row;
            }
            return $data;
        }
        function gettindakan_full_rekap_cetak($tindakan){
            if ($tindakan!="all") {
                if (stripos($tindakan, "T") !== FALSE) {
                $this->db->where("kode_tindakan", $tindakan);
                $q = $this->db->get("tarif_ralan");
                }else{
                $this->db->where("kode", $tindakan);
                $q = $this->db->get("tarif_operasi");
                }
            }else{
                $q = $this->db->get("tarif_operasi");
            }
            return $q->row();
        }
        function rekap_ralan_full($tindakan,$tgl1="",$tgl2=""){
            $data = array();
            $tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
            $tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
            // $this->db->where("tanggal",date("Y-m-d",strtotime($tgl1)));
            // $q = $this->db->get("rekap_kamarbedah");
            // if($q->num_rows()<=0){
              $this->db->select("p.operasi,pa.gol_pasien,g.pensiunan");
              // $this->db->where("layan!=",2);
              if ($tindakan!="all") {
                  $this->db->where("p.operasi",$tindakan);
              }//else {
              //     $this->db->like("p.operasi","T",'after');
              // }
              $this->db->where("p.pelayanan","RALAN");
              $this->db->where("date(p.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
              $this->db->where("date(p.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
              $this->db->where("(p.laporan IS NOT NULL OR p.mata IS NOT NULL OR p.lain IS NOT NULL OR p.pterygium IS NOT NULL)");
              $this->db->join("pasien_ralan pa","pa.no_reg = p.no_reg","inner");
              $this->db->join("gol_pasien g","g.id_gol = pa.gol_pasien","inner");
              // $this->db->order_by("jumlah","desc");
              // $this->db->group_by("kode_tarif");
              $sql = $this->db->get("oka p");
              foreach ($sql->result() as $key) {
                  if (isset($data["tindakan"][$key->operasi]))
                  $data["tindakan"][$key->operasi] += 1;
                  else
                  $data["tindakan"][$key->operasi] = 1;
                  if ($key->jenis=="R"){
                      if (isset($data["REGULER"][$key->operasi]))
                      $data["REGULER"][$key->operasi] += 1;
                      else
                      $data["REGULER"][$key->operasi] = 1;
                  } else
                  if ($key->jenis=="E"){
                      if (isset($data["EKSEKUTIF"][$key->operasi]))
                      $data["EKSEKUTIF"][$key->operasi] += 1;
                      else
                      $data["EKSEKUTIF"][$key->operasi] = 1;
                  }
                  if ($key->status_pasien=="BARU"){
                      if (isset($data["BARU"][$key->operasi]))
                      $data["BARU"][$key->operasi] += 1;
                      else
                      $data["BARU"][$key->operasi] = 1;
                  } else
                  if ($key->status_pasien=="LAMA"){
                      if (isset($data["LAMA"][$key->operasi]))
                      $data["LAMA"][$key->operasi] += 1;
                      else
                      $data["LAMA"][$key->operasi] = 1;
                  }
                  if (($key->gol_pasien>=404 && $key->gol_pasien<=410) || ($key->gol_pasien>=415 && $key->gol_pasien<=417) || ($key->gol_pasien==3133)){
                      if (isset($data["DINAS"][$key->operasi]))
                      $data["DINAS"][$key->operasi] += 1;
                      else
                      $data["DINAS"][$key->operasi] = 1;
                      if ($key->pensiunan){
            						if (isset($data["DINAS_PUR"][$key->operasi]))
            						$data["DINAS_PUR"][$key->operasi] += 1;
            						else
            						$data["DINAS_PUR"][$key->operasi] = 1;
            					} else {
            						if (isset($data["DINAS_A"][$key->operasi]))
            						$data["DINAS_A"][$key->operasi] += 1;
            						else
            						$data["DINAS_A"][$key->operasi] = 1;
            					}
                  } else
                  if ($key->gol_pasien==11){
                      if (isset($data["UMUM"][$key->operasi]))
                      $data["UMUM"][$key->operasi] += 1;
                      else
                      $data["UMUM"][$key->operasi] = 1;
                  } else
                  if (($key->gol_pasien>=400 && $key->gol_pasien<=403) || ($key->gol_pasien>=411 && $key->gol_pasien<=414) || ($key->gol_pasien>=418 && $key->gol_pasien<=420)){
                      if (isset($data["BPJS"][$key->operasi]))
                      $data["BPJS"][$key->operasi] += 1;
                      else
                      $data["BPJS"][$key->operasi] = 1;
                  } else
                  if (($key->gol_pasien==12) || ($key->gol_pasien==13) || ($key->gol_pasien>=16 && $key->gol_pasien<=18)){
                      if (isset($data["PRSH"][$key->operasi]))
                      $data["PRSH"][$key->operasi] += 1;
                      else
                      $data["PRSH"][$key->operasi] = 1;
                  }
              }
            // } else {
            //   $this->db->select("tp.kode ,tp.nama_tindakan");
            //   $query = $this->db->get("tarif_operasi tp")->row();
            //   foreach ($q->result() as $key) {
            //     $data["BARU"][$query->kode] = $key->baru_ralan;
            //     $data["LAMA"][$query->kode] = $key->lama_ralan;
            //     $data["REGULER"][$query->kode] = $key->reguler_ralan;
            //     $data["EKSEKUTIF"][$query->kode] = $key->eksekutif_ralan;
            //     $data["DINAS_A"][$query->kode] = $key->dinas_a_ralan;
            //     $data["DINAS_PUR"][$query->kode] = $key->dinas_pur_ralan;
            //     $data["UMUM"][$query->kode] = $key->umum_ralan;
            //     $data["BPJS"][$query->kode] = $key->bpjs_ralan;
            //     $data["PRSH"][$query->kode] = $key->prsh_ralan;
            //   }
            // }
            return $data;
        }
        function rekap_inap_full($tindakan,$tgl1="",$tgl2=""){
            $data = array();
            $tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
            $tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
            // $this->db->where("tanggal",date("Y-m-d",strtotime($tgl1)));
            // $q = $this->db->get("rekap_kamarbedah");
            // if($q->num_rows()<=0){
              $this->db->select("p.operasi,pa.id_gol as gol_pasien,g.pensiunan");
              // $this->db->where("layan!=",2);
              if ($tindakan!="all") {
                  $this->db->where("p.operasi",$tindakan);
              }
              $this->db->where("p.pelayanan","RANAP");
              $this->db->where("date(p.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
              $this->db->where("date(p.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
              $this->db->where("(p.laporan IS NOT NULL OR p.mata IS NOT NULL OR p.lain IS NOT NULL OR p.pterygium IS NOT NULL)");
              // $this->db->where('p.laporan !=', null);
              // $this->db->or_where('p.mata !=', null);
              // $this->db->or_where('p.lain !=', null);
              // $this->db->or_where('p.pterygium !=', null);
              $this->db->join("pasien_inap pa","pa.no_reg = p.no_reg","inner");
              $this->db->join("gol_pasien g","g.id_gol = pa.id_gol","inner");
              // $this->db->order_by("jumlah","desc");
              // $this->db->group_by("kode_tarif");
              $sql = $this->db->get("oka p");
              foreach ($sql->result() as $key) {
                  if (isset($data["tindakan"][$key->operasi]))
                  $data["tindakan"][$key->operasi] += 1;
                  else
                  $data["tindakan"][$key->operasi] = 1;
                  // if ($key->jenis=="R"){
                  //     if (isset($data["REGULER"][$key->operasi]))
                  //     $data["REGULER"][$key->operasi] += 1;
                  //     else
                  //     $data["REGULER"][$key->operasi] = 1;
                  // } else
                  // if ($key->jenis=="E"){
                  //     if (isset($data["EKSEKUTIF"][$key->operasi]))
                  //     $data["EKSEKUTIF"][$key->operasi] += 1;
                  //     else
                  //     $data["EKSEKUTIF"][$key->operasi] = 1;
                  // }
                  // if ($key->status_pasien=="BARU"){
                  //     if (isset($data["BARU"][$key->operasi]))
                  //     $data["BARU"][$key->operasi] += 1;
                  //     else
                  //     $data["BARU"][$key->operasi] = 1;
                  // } else
                  // if ($key->status_pasien=="LAMA"){
                  //     if (isset($data["LAMA"][$key->operasi]))
                  //     $data["LAMA"][$key->operasi] += 1;
                  //     else
                  //     $data["LAMA"][$key->operasi] = 1;
                  // }
                  if (($key->gol_pasien>=404 && $key->gol_pasien<=410) || ($key->gol_pasien>=415 && $key->gol_pasien<=417) || ($key->gol_pasien==3133)){
                      if (isset($data["DINAS"][$key->operasi]))
                      $data["DINAS"][$key->operasi] += 1;
                      else
                      $data["DINAS"][$key->operasi] = 1;
                      if ($key->pensiunan){
            						if (isset($data["DINAS_PUR"][$key->operasi]))
            						$data["DINAS_PUR"][$key->operasi] += 1;
            						else
            						$data["DINAS_PUR"][$key->operasi] = 1;
            					} else {
            						if (isset($data["DINAS_A"][$key->operasi]))
            						$data["DINAS_A"][$key->operasi] += 1;
            						else
            						$data["DINAS_A"][$key->operasi] = 1;
            					}
                  } else
                  if ($key->gol_pasien==11){
                      if (isset($data["UMUM"][$key->operasi]))
                      $data["UMUM"][$key->operasi] += 1;
                      else
                      $data["UMUM"][$key->operasi] = 1;
                  } else
                  if (($key->gol_pasien>=400 && $key->gol_pasien<=403) || ($key->gol_pasien>=411 && $key->gol_pasien<=414) || ($key->gol_pasien>=418 && $key->gol_pasien<=420)){
                      if (isset($data["BPJS"][$key->operasi]))
                      $data["BPJS"][$key->operasi] += 1;
                      else
                      $data["BPJS"][$key->operasi] = 1;
                  } else
                  if (($key->gol_pasien==12) || ($key->gol_pasien==13) || ($key->gol_pasien>=16 && $key->gol_pasien<=18)){
                      if (isset($data["PRSH"][$key->operasi]))
                      $data["PRSH"][$key->operasi] += 1;
                      else
                      $data["PRSH"][$key->operasi] = 1;
                  }
              }
            // } else {
            //   $this->db->select("tp.kode ,tp.nama_tindakan");
            //   $query = $this->db->get("tarif_operasi tp")->row();
            //   foreach($q->result() as $key){
            //     $data["BARU"][$query->kode] = $key->baru_inap;
            //     $data["LAMA"][$query->kode] = $key->lama_inap;
            //     $data["REGULER"][$query->kode] = $key->reguler_inap;
            //     $data["EKSEKUTIF"][$query->kode] = $key->eksekutif_inap;
            //     $data["DINAS_A"][$query->kode] = $key->dinas_a_inap;
            //     $data["DINAS_PUR"][$query->kode] = $key->dinas_pur_inap;
            //     $data["UMUM"][$query->kode] = $key->umum_inap;
            //     $data["BPJS"][$query->kode] = $key->bpjs_inap;
            //     $data["PRSH"][$query->kode] = $key->prsh_inap;
            //   }
            // }
            return $data;
        }
        function getpasien_rekap_full($tindakan,$tgl1,$tgl2){
            $data = array();
            //ralan
            $this->db->select("ok.tanggal,pr.no_pasien,pr.no_reg,pr.tujuan_poli,pr.dari_poli,ok.dokter_operasi,pr.layan");
            $this->db->where("date(ok.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
            $this->db->where("date(ok.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
            $this->db->where("ok.operasi",$tindakan);
            $this->db->join("pasien_ralan pr","ok.no_reg=pr.no_reg","inner");
            $query = $this->db->get("oka ok");
            foreach ($query->result() as $row) {
                $data["list"][$row->no_reg] = $row;
                $q = $this->db->get_where("pasien",["no_pasien"=>$row->no_pasien]);
                $data["master"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
                $q = $this->db->get_where("poliklinik",["kode"=>$row->tujuan_poli]);
                $data["pol2"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");

                //nama_poli
                $this->db->select("po.keterangan as keterangan");
                $this->db->join("poliklinik po","po.kode=dk.poli","inner");
                $q = $this->db->get_where("dokter dk",["id_dokter"=>$row->dokter_operasi]);
                $data["pol"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");

                $q = $this->db->get_where("dokter",["id_dokter"=>$row->dokter_operasi]);
                $data["dokter"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
    //             $q = $this->db->order_by('hasil','desc')->get_where("ekspertisi_lab",["no_reg"=>$row->no_reg , "kode_tindakan"=>"$tindakan" ]);
    //             //$q = $this->db->get_where("ekspertisi_lab",["no_reg"=>$row->no_reg]);
                // $data["ekspertisi_lab"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
                // }
            }
            //ranap
            $this->db->select("ok.no_reg,ok.dokter_operasi,pi.kode_ruangan,pi.kode_kelas,pi.kode_kamar,pi.status_pulang,pi.no_rm as no_pasien, ok.tanggal");
            $this->db->where("ok.operasi",$tindakan);
            $this->db->where("date(ok.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
            $this->db->where("date(ok.tanggal)<=",date("Y-m-d",strtotime($tgl2)));;
            $this->db->join("pasien_inap pi","ok.no_reg=pi.no_reg","inner");
            $query = $this->db->get("oka ok");
            foreach ($query->result() as $row) {
                $data["list"][$row->no_reg] = $row;
                $q = $this->db->get_where("pasien",["no_pasien"=>$row->no_pasien]);
                $data["master"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
                $q = $this->db->get_where("status_pulang s",["s.id"=>$row->status_pulang]);
                $data["status_pulang"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
                $q = $this->db->get_where("ruangan r",["r.kode_ruangan"=> $row->kode_ruangan]);
                $data["ruangan"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
                $q = $this->db->get_where("kelas kls",["kls.kode_kelas"=>$row->kode_kelas]);
                $data["kelas"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
                $q = $this->db->get_where("kamar kmr",["kmr.kode_kamar"=>$row->kode_kamar,"kmr.kode_kelas"=>$row->kode_kelas, "kmr.kode_ruangan"=>$row->kode_ruangan]);
                $data["kamar"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
                $q = $this->db->get_where("dokter",["id_dokter"=>$row->dokter_operasi]);
                $data["dokter"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
    //             $q = $this->db->order_by('hasil','desc')->get_where("ekspertisi_labinap",["no_reg"=>$row->no_reg , "kode_tindakan"=>"$tindakan" ]);
                // $data["ekspertisi_lab"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
            }
            return $data;
        }
    }
?>
