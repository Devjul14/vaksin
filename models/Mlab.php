<?php
	class Mlab extends CI_Model{
	   function __construct()
	    {
	        parent::__construct();
	    }
	    function getpasien_ralan_lab($page,$offset){
			// $poli_kode = $this->session->userdata("poli_kode");
			$kode_dokter = $this->session->userdata("kode_dokter");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$status_pasien = $this->session->userdata("status_pasien");
			$nama = $this->session->userdata("nama");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$this->db->select("pr.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, g.keterangan as gol_pasien");
			// if ($no_pasien!="") {
			// 	$no_pasien = "000000".$no_pasien;
			// 	$this->db->where("p.no_pasien",substr($no_pasien,-6));
			// }
			// if ($no_reg!="") {
			// 	$this->db->where("no_reg",$no_reg);
			// }
			// if ($nama!="") {
			// 	$this->db->like("p.nama_pasien",$nama);
			// }
			$this->db->group_start();
        	$this->db->like("p.no_pasien",$no_pasien);
        	$this->db->or_like("no_reg",$no_pasien);
        	$this->db->or_like("no_bpjs",$no_pasien);
        	$this->db->or_like("no_sjp",$no_pasien);
        	$this->db->or_like("p.nama_pasien",$no_pasien);
        	$this->db->group_end();
			if ($kode_dokter!="") {
				$this->db->where("pr.dokter_poli",$kode_dokter);
			}
			if ($tgl1!="" OR $tgl2!="") {
				$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			}
			$this->db->order_by("no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
			$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
			$this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli");
			$this->db->where("pr.tujuan_poli","0102024");
			$query = $this->db->get("pasien_ralan pr",$page,$offset);
			return $query;
		}
		function getlab_ralan(){
			$kode_dokter = $this->session->userdata("kode_dokter");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$status_pasien = $this->session->userdata("status_pasien");
			$nama = $this->session->userdata("nama");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$this->db->select("pr.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien");
			if ($tgl1!="" OR $tgl2!="") {
				$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			}
			// if ($no_pasien!="") {
			// 	$no_pasien = "000000".$no_pasien;
			// 	$this->db->where("p.no_pasien",substr($no_pasien,-6));
			// }
			// if ($no_reg!="") {
			// 	$this->db->where("no_reg",$no_reg);
			// }
			// if ($nama!="") {
			// 	$this->db->like("p.nama_pasien",$nama);
			// }
			$this->db->group_start();
        	$this->db->like("pr.no_pasien",$no_pasien);
        	$this->db->or_like("no_reg",$no_pasien);
        	$this->db->or_like("no_bpjs",$no_pasien);
        	$this->db->or_like("no_sjp",$no_pasien);
        	$this->db->or_like("p.nama_pasien",$no_pasien);
        	$this->db->group_end();
			if ($kode_dokter!="") {
				$this->db->where("pr.dokter_poli",$kode_dokter);
			}
			$this->db->order_by("no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
			$this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli");
			$this->db->where("pr.tujuan_poli","0102024");
			$query = $this->db->get("pasien_ralan pr");
			return $query->num_rows();
		}
		function getrespond($no_rm, $no_reg){
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			if ($tgl1!="" OR $tgl2!="") {
				$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			}
			if ($tgl1=="" OR $tgl2=="") {
				$this->db->where("pr.no_reg",$no_reg);
			}
			$this->db->select("pr.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, kas.terima_lab, kas.periksa_lab, kas.jam_lab");
			$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
			$this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli");
			$this->db->join("kasir kas", "kas.no_reg = pr.no_reg","left");
			$this->db->like("kas.kode_tarif","L");
			$this->db->group_by("no_reg");
			$this->db->where("pr.tujuan_poli","0102024");
			$query = $this->db->get("pasien_ralan pr");
			return $query;
		}
		function getlab_inap($page,$offset){
			$kode_kelas = $this->session->userdata("kode_kelas");
			$kode_ruangan = $this->session->userdata("kode_ruangan");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$nama = $this->session->userdata("nama");
			$this->db->select("i.*,r.nama_ruangan,k.nama_kelas, g.keterangan as gol_pasien");
			// if ($no_pasien!="") {
			// 	$no_pasien = "000000".$no_pasien;
			// 	$this->db->where("i.no_rm",substr($no_pasien,-6));
			// }
			// if ($nama!="") {
			// 	$this->db->like("p.nama_pasien",$nama);
			// }
			// if ($no_reg!="") {
			// 	$this->db->where("no_reg",$no_reg);
			// }
			$this->db->group_start();
        	$this->db->like("i.no_rm",$no_pasien);
        	$this->db->or_like("no_reg",$no_pasien);
        	$this->db->or_like("no_bpjs",$no_pasien);
        	$this->db->or_like("no_sjp",$no_pasien);
        	$this->db->or_like("p.nama_pasien",$no_pasien);
        	$this->db->group_end();
			if ($kode_kelas!="") {
				$this->db->where("i.kode_kelas",$kode_kelas);
			}
			if ($kode_ruangan!="") {
				$this->db->where("i.kode_ruangan",$kode_ruangan);
			}
			if ($tgl1!="" OR $tgl2!="") {
				$this->db->where("i.tgl_masuk>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("i.tgl_masuk<=",date("Y-m-d",strtotime($tgl2)));
			}
			// $this->db->order_by("no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=i.no_rm");
			$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
			$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
			$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
			$this->db->order_by("no_reg,no_rm","desc");
			$query = $this->db->get("pasien_inap i",$page,$offset);
			return $query;
		}
		function getlab_rawatinap(){
			$kode_kelas = $this->session->userdata("kode_kelas");
			$kode_ruangan = $this->session->userdata("kode_ruangan");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$nama = $this->session->userdata("nama");
			$this->db->select("i.*,r.nama_ruangan,k.nama_kelas");
			// if ($no_pasien!="") {
			// 	$no_pasien = "000000".$no_pasien;
			// 	$this->db->where("i.no_rm",substr($no_pasien,-6));
			// }
			// if ($nama!="") {
			// 	$this->db->like("p.nama_pasien",$nama);
			// }
			// if ($no_reg!="") {
			// 	$this->db->where("no_reg",$no_reg);
			// }
			$this->db->group_start();
        	$this->db->like("i.no_rm",$no_pasien);
        	$this->db->or_like("no_reg",$no_pasien);
        	$this->db->or_like("no_bpjs",$no_pasien);
        	$this->db->or_like("no_sjp",$no_pasien);
        	$this->db->or_like("p.nama_pasien",$no_pasien);
        	$this->db->group_end();
			if ($kode_kelas!="") {
				$this->db->where("i.kode_kelas",$kode_kelas);
			}
			if ($kode_ruangan!="") {
				$this->db->where("i.kode_ruangan",$kode_ruangan);
			}
			if ($tgl1!="" OR $tgl2!="") {
				$this->db->where("i.tgl_masuk>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("i.tgl_masuk<=",date("Y-m-d",strtotime($tgl2)));
			}
			$this->db->order_by("no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=i.no_rm");
			$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
			$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
			$this->db->order_by("no_reg,no_rm");
			$query = $this->db->get("pasien_inap i");
			return $query->num_rows();
		}
		function getrespond_inap($no_rm, $no_reg){
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			if ($tgl1!="" OR $tgl2!="") {
				$this->db->where("date(i.tgl_masuk)>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("date(i.tgl_masuk)<=",date("Y-m-d",strtotime($tgl2)));
			}
			if ($tgl1=="" OR $tgl2=="") {
				$this->db->where("i.no_reg",$no_reg);
			}
			$this->db->select("i.*,r.nama_ruangan,k.nama_kelas, kas.jam_lab, kas.terima_lab, kas.periksa_lab");
			$this->db->join("pasien p","p.no_pasien=i.no_rm");
			$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
			$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
			$this->db->join("kasir_inap kas","kas.no_reg=i.no_reg","left");
			$this->db->like("kas.kode_tarif","L");
			$this->db->order_by("no_reg,no_rm");
			$this->db->group_by("no_reg");
			$query = $this->db->get("pasien_inap i");
			return $query;
		}
		function pilihdokterlab(){
	    	$this->db->select("dokter.*, k.nama_kelompok");
	    	$this->db->join("kelompok_dokter k","k.id_kelompok = dokter.kelompok_dokter","left");
	    	$this->db->where("poli","0102024");
	    	return $this->db->get("dokter");
	    }
	function terima($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("kasir")->row();
        if ($q->terima_lab === "0000-00-00 00:00:00") {
            $data = array('terima_lab' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("kasir",$data);
            return "success-Berkas diterima";
        } else {
            return "danger-Berkas sudah pernah diterima";
        }
    }
    function periksa($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("kasir")->row();
        if ($q->periksa_lab === "0000-00-00 00:00:00") {
            $data = array('periksa_lab' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("kasir",$data);
            return "success-Berkas diperiksa";
        } else {
            return "danger-Berkas sudah pernah diperiksa";
        }
    }
    function periksa_inap($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("kasir_inap")->row();
        if ($q->periksa_lab === "0000-00-00 00:00:00") {
            $data = array('periksa_lab' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("kasir_inap",$data);
            return "success-Berkas diperiksa";
        } else {
            return "danger-Berkas sudah pernah diperiksa";
        }
    }
	    function getralan_detail($no_pasien,$no_reg){
			$this->db->select("pr.*,p.alamat,p.nama_pasien,pl.keterangan as poli,jk.keterangan jenis_kelamin,d.nama_dokter,d.lab  ");
			$this->db->join("pasien p","pr.no_pasien=p.no_pasien");
			$this->db->join("poliklinik pl","pl.kode=pr.tujuan_poli");
			// $this->db->join("kasir kr", "kr.no_reg = pr.no_reg");
			// $this->db->join("analys an", "an.nip = pr.analys");
			$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
			$this->db->join("dokter d","d.id_dokter=pr.dokter_poli","left");
			$this->db->where("pr.no_pasien",$no_pasien);
			$this->db->where("pr.no_reg",$no_reg);
			$q = $this->db->get("pasien_ralan pr");
			return $q->row();
		}
		function getralan_detail1($no_pasien,$no_reg){
			$this->db->select("pr.*,p.alamat,p.nama_pasien,pl.keterangan as poli,jk.keterangan jenis_kelamin,d.nama_dokter,d.lab, an.nama_perawat as namaanalys ");
			$this->db->join("pasien p","pr.no_pasien=p.no_pasien");
			$this->db->join("poliklinik pl","pl.kode=pr.tujuan_poli");
			$this->db->join("kasir kr", "kr.no_reg = pr.no_reg");
			$this->db->join("perawat an", "an.id_perawat = kr.analys","left");
			$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin","left");
			$this->db->join("dokter d","d.id_dokter=kr.kode_petugas","left");
			$this->db->where("pr.no_pasien",$no_pasien);
			$this->db->where("pr.no_reg",$no_reg);
			$q = $this->db->get("pasien_ralan pr");
			return $q->row();
		}
		function getinap_detail($no_pasien,$no_reg){
			$this->db->select("pi.*,p.nama_pasien,r.nama_ruangan,k.nama_kelas,nama_kamar,ei.id_ekspertisi");
			$this->db->join("pasien p","p.no_pasien=pi.no_rm");
			$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
			$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
			$this->db->join("kelas k","k.kode_kelas=pi.kode_kelas","left");
			$this->db->join("kamar kmr","kmr.kode_kamar=pi.kode_kamar","left");
			$this->db->join("ekspertisi_labinap ei","ei.no_reg=pi.no_reg","left");
			$this->db->join("dokter d","d.id_dokter=pi.dokter","left");
			$this->db->where("pi.no_rm",$no_pasien);
			$this->db->where("pi.no_reg",$no_reg);
			$q = $this->db->get("pasien_inap pi");
			return $q->row();
		}
		// function getinap_detail1($no_pasien,$no_reg){
		// 	$this->db->select("pi.*,p.nama_pasien,r.nama_ruangan,k.nama_kelas,nama_kamar,ei.id_ekspertisi, an.nama as namaanalys, d.nama_dokter");
		// 	$this->db->join("pasien p","pi.no_rm=p.no_pasien");
		// 	$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
		// 	$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
		// 	$this->db->join("kelas k","k.kode_kelas=pi.kode_kelas","left");
		// 	$this->db->join("kamar kmr","kmr.kode_kamar=pi.kode_kamar","left");
		// 	$this->db->join("ekspertisi_labinap ei","ei.no_reg=pi.no_reg","left");
		// 	$this->db->join("kasir_inap ki","ki.no_reg=pi.no_reg and ki.kode_tarif = ei.kode_tindakan and ki.tanggal=ei.tanggal and ki.pemeriksaan = ei.pemeriksaan","left");
		// 	$this->db->join("analys an", "an.nip = ki.analys","left");
		// 	$this->db->join("dokter d","d.id_dokter=ki.kode_petugas","left");
		// 	$this->db->where("pi.no_rm",$no_pasien);
		// 	$this->db->where("pi.no_reg",$no_reg);
		// 	$q = $this->db->get("pasien_inap pi");
		// 	return $q->row();
		// }
		function getinap_detail1($no_pasien,$no_reg,$tanggal,$pemeriksaan=""){
			$this->db->select("p.nama_pasien,jk.jenis_kelamin");
			$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
			$this->db->where("p.no_pasien",$no_pasien);
			$p = $this->db->get("pasien p");
			// $e = $this->db->get_where("ekspertisi_labinap ei",["ei.no_reg"=>$no_reg,"ei.pemeriksaan"=>$pemeriksaan]);
			// $kode_tindakan = $e->row()->kode_tindakan;
			// $pemeriksaan = $e->row()->pemeriksaan;
			// $tanggal = $e->row()->tanggal;
			$this->db->select("pi.*, an.nama_perawat as namaanalys, d.nama_dokter");
			$this->db->join("kasir_inap ki","ki.no_reg=pi.no_reg","left");
			$this->db->join("perawat an", "an.id_perawat = ki.analys","left");
			$this->db->join("dokter d","d.id_dokter=ki.kode_petugas","left");
			// $this->db->where("ki.kode_tarif", $kode_tindakan);
			$this->db->where("ki.tanggal",$tanggal);
			$this->db->where("ki.pemeriksaan",$pemeriksaan);
			$this->db->where("pi.no_rm",$no_pasien);
			$this->db->where("pi.no_reg",$no_reg);
			$this->db->like("ki.kode_tarif","L","after");
			$q = $this->db->get("pasien_inap pi");
			$kode_ruangan = $q->row()->kode_ruangan;
			$r = $this->db->get_where("ruangan r",["r.kode_ruangan"=>$kode_ruangan]);
			$kode_kelas = $q->row()->kode_kelas;
			$k = $this->db->get_where("kelas k",["k.kode_kelas"=>$kode_kelas]);
			$kode_kamar = $q->row()->kode_kamar;
			$km = $this->db->get_where("kamar",["kode_kamar"=>$kode_kamar]);
			$data = array(
						"pasieninap" => $q->row(),
						"pasien" => $p->row(),
						"ruangan" => $r->row(),
						"kelas" => $k->row(),
						"kamar" => $km->row(),
					);
			return $data;
		}
		function gettarif_lab(){
			return $this->db->get("tarif_lab");
		}
		function getswab_lab(){
			return $this->db->get("metode_swab");
		}
		function addtindakan(){
			$tindakan = $this->input->post("tindakan");
			$id = date("dmyHis");
			foreach ($tindakan as $key => $value) {
				$t = $this->db->get_where("tarif_lab",["kode_tindakan" => $value]);
				if ($t->num_rows()>0){
					$data = $t->row();
					$this->db->where("kode_tarif",$value);
					$this->db->where("no_reg",$this->input->post("no_reg"));
					$q = $this->db->get("kasir");
					if ($q->num_rows()<=0){
						if ($this->input->post('jenis')=="E") $tarif = $data->executive; else $tarif = $data->reguler;
						$data = array(
									"id" => $id,
									"no_reg" => $this->input->post("no_reg"),
									"kode_tarif" => $value,
									"kode_petugas" => $this->input->post("dokter"),
									"dokter_pengirim" => $this->input->post("dokter_pengirim"),
									"analys" => $this->input->post("analys"),
									"diagnosa" => $this->input->post("diagnosa"),
									"pemeriksaan" => $this->input->post("pemeriksaan"),
									"terima_lab" =>  date("Y-m-d H:i:s"),
                                    "jumlah" => $tarif,
                                    "metode_swab" => $this->input->post("metode_swab"),
								);
						$this->db->insert("kasir",$data);
						$l = $this->db->get_where("lab_normal",["kode_tindakan"=>$value]);
						if ($l->num_rows()>0) {
							$lrow = $l->row();
						}
						$item = array(
											'kode_labnormal' => $lrow->kode,
											'kode_tindakan' => $value,
											'no_reg' => $this->input->post("no_reg"),
											'kode_judul' => $lrow->kode_judul
										);
						$this->db->where("no_reg", $this->input->post("no_reg"));
						$this->db->insert("ekspertisi_lab",$item);
					}
				}
				$id++;
			}
			$q = $this->db->get_where("pasien_ralan",["no_reg"=>$this->input->post("no_reg"),"keadaan_pulang"=>""]);
			if ($q->num_rows()>0){
				$this->db->where("no_reg",$this->input->post("no_reg"));
				$this->db->update("pasien_ralan",["keadaan_pulang"=>"2"]);
			}
			// $q = $this->getlab_normal($this->input->post("no_reg"));
			// foreach ($q->result() as $row) {
			// 	$item = array(
			// 					'kode_labnormal' => $row->kode,
			// 					'kode_tindakan' => $row->kode_tindakan,
			// 					'no_reg' => $this->input->post("no_reg"),
			// 					'hasil' => $row->hasil,
			// 					'kode_judul' => $row->kode_judul
			// 				);
			// }
			// $this->db->where("no_reg", $this->input->post("no_reg"));
			// $this->db->insert("ekspertisi_lab",$item);
		}
		function addtindakankonsul(){
			$tindakan = $this->input->post("tindakan");
			$id = date("dmyHis");
			foreach ($tindakan as $key => $value) {
				$t = $this->db->get_where("tarif_lab",["kode_tindakan" => $value]);
				if ($t->num_rows()>0){
					$data = $t->row();
					$this->db->where("kode_tarif",$value);
					$this->db->where("no_reg",$this->input->post("no_reg"));
					$q = $this->db->get("kasir");
					if ($q->num_rows()<=0){
						if ($this->input->post('jenis')=="R") $tarif = $data->reguler; else $tarif = $data->executive;
						$data = array(
									"id" => $id,
									"no_reg" => $this->input->post("no_reg"),
									"kode_tarif" => $value,
									"kode_petugas" => $this->input->post("dokter"),
									"dokter_pengirim" => $this->input->post("dokter_pengirim"),
									"analys" => $this->input->post("analys"),
									"diagnosa" => $this->input->post("diagnosa"),
									"jumlah" => $tarif
								);
						$this->db->insert("kasir",$data);
						$l = $this->db->get_where("lab_normal",["kode_tindakan"=>$value]);
						if ($l->num_rows()>0) {
							$lrow = $l->row();
						}
						$item = array(
											'kode_labnormal' => $lrow->kode,
											'kode_tindakan' => $value,
											'no_reg' => $this->input->post("no_reg"),
											'kode_judul' => $lrow->kode_judul
										);
						$this->db->where("no_reg", $this->input->post("no_reg"));
						$this->db->insert("ekspertisi_lab",$item);
					}
				}
				$id++;
			}
			$q = $this->db->get_where("pasien_ralan",["no_reg"=>$this->input->post("no_reg"),"keadaan_pulang"=>""]);
			if ($q->num_rows()>0){
				$this->db->where("no_reg",$this->input->post("no_reg"));
				$this->db->update("pasien_ralan",["keadaan_pulang"=>"2"]);
			}
			// $q = $this->getlab_normal($this->input->post("no_reg"));
			// foreach ($q->result() as $row) {
			// 	$item = array(
			// 					'kode_labnormal' => $row->kode,
			// 					'kode_tindakan' => $row->kode_tindakan,
			// 					'no_reg' => $this->input->post("no_reg"),
			// 					'hasil' => $row->hasil,
			// 					'kode_judul' => $row->kode_judul
			// 				);
			// }
			// $this->db->where("no_reg", $this->input->post("no_reg"));
			// $this->db->insert("ekspertisi_lab",$item);
		}
		function addtindakan_inap(){
			$tindakan = $this->input->post("tindakan");
			$id = date("dmyHis");
			$data = array();
			foreach ($tindakan as $key => $value) {
				$t = $this->db->get_where("tarif_lab",["kode_tindakan" => $value]);
				if ($t->num_rows()>0){
					$data = $t->row();
					$tarif = $data->reguler;
					$this->db->where("kode_tarif",$value);
					$this->db->where("no_reg",$this->input->post("no_reg"));
					$this->db->where("tanggal",date("Y-m-d",strtotime($this->input->post("tanggal"))));
					$this->db->where("pemeriksaan",$this->input->post("pemeriksaan"));
					$q = $this->db->get("kasir_inap");
					if ($q->num_rows()<=0){
						$data = array(
									"id" => $id,
									"no_reg" => $this->input->post("no_reg"),
									"qty" => 1,
									"tanggal" => date("Y-m-d",strtotime($this->input->post("tanggal"))),
									"kode_tarif" => $value,
									"kode_petugas" => $this->input->post("dokter"),
									"analys" => $this->input->post("analys"),
									"dokter_pengirim" => $this->input->post("dokterpengirim"),
									"jumlah" => $tarif,
									"terima_lab" =>  date("Y-m-d H:i:s"),
									"pemeriksaan" => $this->input->post("pemeriksaan"),
									"metode_swab" => $this->input->post("metode_swab"),
								);
						$this->db->insert("kasir_inap",$data);
						$l = $this->db->get_where("lab_normal",["kode_tindakan"=>$value]);
						if ($l->num_rows()>0) {
							$lrow = $l->row();
						}
						$item = array(
										'kode_labnormal' => $lrow->kode,
										'kode_tindakan' => $value,
										'no_reg' => $this->input->post("no_reg"),
										'tanggal' => date("Y-m-d",strtotime($this->input->post("tanggal"))),
										"pemeriksaan" => $this->input->post("pemeriksaan"),
										'kode_judul' => $lrow->kode_judul
									);
						$this->db->insert("ekspertisi_labinap",$item);
					}
				}
				$id++;
			}
			// $q = $this->getlabinap_normal($this->input->post("no_reg"));
			// foreach ($q->result() as $row) {
			// 	$item = array(
			// 					'kode_labnormal' => $row->kode,
			// 					'kode_tindakan' => $row->kode_tindakan,
			// 					'no_reg' => $this->input->post("no_reg"),
			// 					'hasil' => $row->hasil,
			// 					'tanggal' => date("Y-m-d",strtotime($row->tanggal)),
			// 					"pemeriksaan" => $row->pemeriksaan,
			// 					'kode_judul' => $row->kode_judul
			// 				);
			// }
			// $this->db->where("no_reg", $this->input->post("no_reg"));
			// $this->db->insert("ekspertisi_labinap",$item);

	}
		function getkasir($no_reg){
			$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif","inner");
			$q = $this->db->get_where("kasir k",["k.no_reg" => $no_reg]);
			return $q;
		}
		function getkasir_inap($no_reg){
			$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif","inner");
			$q = $this->db->get_where("kasir_inap k",["k.no_reg" => $no_reg]);
			return $q;
		}
		function getkasir_inap_ekspertisi_array($no_reg){
			$this->db->where("no_reg",$no_reg);
			// $this->db->where("hasil",NULL);
			// $this->db->or_where("hasil","");
			$q = $this->db->get("ekspertisi_labinap k");
			$data = array();
			foreach ($q->result() as $row) {
				$data[$row->kode_tindakan][$row->tanggal][$row->pemeriksaan] = $row->hasil;
			}
			return $data;
		}
		function getkasir_inap_ekspertisi($no_reg){
			$this->db->select("k.tanggal,k.pemeriksaan");
			$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif","inner");
			$this->db->group_by("k.tanggal,k.pemeriksaan");
			$this->db->order_by("k.tanggal","DESC");
			$q = $this->db->get_where("kasir_inap k",["k.no_reg" => $no_reg]);
			return $q;
		}
		function ambildatanormal($tindakan){
			$this->db->where("kode_tindakan",$tindakan);
			$this->db->order_by("kode");
			return $this->db->get("lab_normal");
		}
		function simpanekspertisi($action){
			// $dt = array(
			// 		'dokter_poli' 	=> $this->input->post("dokter"),
			// 		'analys' 		=> $this->input->post("analys")

			// 	);
			$hasil = $this->input->post("hasil");
			$n1 = $this->input->post("n1");
			$n2 = $this->input->post("n2");
			$rp = $this->input->post("rp");
			foreach ($hasil as $key => $value) {
				$q = $this->db->get_where("ekspertisi_lab",["no_reg"=>$this->input->post("no_reg"),"kode_labnormal"=>$key]);
				if ($q->num_rows()>0){
					$q = $q->row();
					$item = array(
                        'hasil' => $value,
                        'n1' => (isset($n1[$key]) ? $n1[$key] : ""),
                        'n2' => (isset($n2[$key]) ? $n2[$key] : ""),
                        'rp' => (isset($rp[$key]) ? $rp[$key] : ""),
					);
					$this->db->where("id_ekspertisi",$q->id_ekspertisi);
					$this->db->update("ekspertisi_lab",$item);
				} else {
					if ($value!="" || $value!=null){
						$q = $this->db->get_where("lab_normal",["kode"=>$key])->row();
						$item = array(
									"no_reg" => $this->input->post("no_reg"),
									"kode_tindakan" => $q->kode_tindakan,
									"kode_labnormal" =>$q->kode,
									"kode_judul" => $q->kode_judul,
	                                "hasil" => $value,
	                                'n1' => (isset($n1[$key]) ? $n1[$key] : ""),
	                                'n2' => (isset($n2[$key]) ? $n2[$key] : ""),
	                                'rp' => (isset($rp[$key]) ? $rp[$key] : ""),
								);
						$this->db->insert("ekspertisi_lab",$item);
					}
				}
				if ($value!="" || $value!=null){
					$item1 = array("jam_lab" => date("Y-m-d H:i:s"));
					$this->db->where("no_reg", $this->input->post("no_reg"));
					$this->db->where("jam_lab", "0000-00-00 00:00:00");
					$this->db->update("kasir",$item1);
				}
			}
			// $this->db->where("no_pasien",$this->input->post("no_pasien"));
			// $this->db->where("no_reg",$this->input->post("no_reg"));
			// $this->db->update("pasien_ralan",$dt);
			return "info-Data berhasil diubah";
		}
		function simpanekspertisi_inap($action){
			// $dt = array(
			// 	'kode_petugas' 	=> $this->input->post("dokter"),
			// 	'analys' 		=> $this->input->post("analys")
			// );
            $hasil = $this->input->post("hasil");
            $n1 = $this->input->post("n1");
			$n2 = $this->input->post("n2");
			$rp = $this->input->post("rp");
			foreach ($hasil as $key => $value) {
				$pemeriksaan = $this->input->post("tanggal_pemeriksaan");
				$pem = explode("/", $pemeriksaan);
				$q = $this->db->get_where("ekspertisi_labinap",["no_reg"=>$this->input->post("no_reg"),"kode_labnormal"=>$key, "tanggal"=>$pem[0], "pemeriksaan" => $pem[1]]);
				if ($q->num_rows()>0){
					$q = $q->row();
					$item = array(
						"tanggal" => $pem[0],
						"pemeriksaan" => $pem[1],
						"kode_judul" => $q->kode_judul,
                        'hasil' => $value,
                        'n1' => (isset($n1[$key]) ? $n1[$key] : ""),
                        'n2' => (isset($n2[$key]) ? $n2[$key] : ""),
                        'rp' => (isset($rp[$key]) ? $rp[$key] : ""),
					);
					$this->db->where("id_ekspertisi",$q->id_ekspertisi);
					$this->db->update("ekspertisi_labinap",$item);
				} else {
					if ($value!="" || $value!=null){
						$q = $this->db->get_where("lab_normal",["kode"=>$key])->row();
						$pemeriksaan = $this->input->post("tanggal_pemeriksaan");
						$pem = explode("/", $pemeriksaan);
						$item = array(
									"no_reg" => $this->input->post("no_reg"),
									"kode_tindakan" => $q->kode_tindakan,
									"kode_labnormal" =>$q->kode,
									"kode_judul" => $q->kode_judul,
									"tanggal" => $pem[0],
									"pemeriksaan" => $pem[1],
	                                "hasil" => $value,
	                                'n1' => (isset($n1[$key]) ? $n1[$key] : ""),
	                                'n2' => (isset($n2[$key]) ? $n2[$key] : ""),
	                                'rp' => (isset($rp[$key]) ? $rp[$key] : ""),
								);
						$this->db->insert("ekspertisi_labinap",$item);
						$item1 = array("jam_lab" => date("Y-m-d H:i:s"));
						$this->db->where("no_reg", $this->input->post("no_reg"));
						$this->db->update("kasir_inap",$item1);
					}
				}
			}
			// $this->db->where("no_rm",$this->input->post("no_pasien"));
			// $this->db->where("no_reg",$this->input->post("no_reg"));
			// $this->db->update("kasir_inap",$dt);
			return "info-Data berhasil diubah";
		}
		function getekspertisi_detail($no_reg){
			$this->db->where("no_reg",$no_reg);
			$q = $this->db->get("ekspertisi_lab");
			return $q->row();
		}
		function getekspertisiinap_detail($no_reg){
			$this->db->where("no_reg",$no_reg);
			$q = $this->db->get("ekspertisi_labinap");
			return $q->row();
		}
		function simpanlab($jenis_pasien=""){
        	if ($jenis_pasien=="inap"){
        		$data = array(
							'dokter_lab' 	=> $this->input->post("dokter"),
							'analys' 		=> $this->input->post("analys")

						);
        		$this->db->where("no_rm",$this->input->post("no_pasien"));
				$this->db->where("no_reg",$this->input->post("no_reg"));
				$this->db->update("pasien_inap",$data);
        	} else {
				$data = array(
							'dokter_poli' 	=> $this->input->post("dokter"),
							'analys' 		=> $this->input->post("analys")

						);
				$this->db->where("no_pasien",$this->input->post("no_pasien"));
				$this->db->where("no_reg",$this->input->post("no_reg"));
				$this->db->update("pasien_ralan",$data);
        	}
			return "success-Data berhasil disimpan";
		}
		function getdokter_lab(){
			$this->db->select("d.*");
			$this->db->join("jadwal_dokter j","j.id_dokter=d.id_dokter","inner");
			$this->db->where("id_poli","0102024");
			return $this->db->get("dokter d");
		}
		function getcetak($no_reg){
	    	$this->db->select("p.*,jk.keterangan as jk,k.nama as status_kawin,pen.pendidikan as pendidikan,g.keterangan as golpas, pr.no_pasien as no_rekmed,pr.no_antrian, pr.tanggal as trk, per.nama as nama_perusahaan, pr.alergi, pan.keterangan as pangkat, ek.no_foto, ek.hasil_pemeriksaan, d.nama_dokter as dokter, ek.kesan,  pr.dari_poli, ru.nama_ruangan,d.nip as ndokter,po.keterangan as polik, d.id_dokter ");
	    	$this->db->join("pasien_ralan pr","pr.no_pasien=p.no_pasien","left");
	    	$this->db->join("pasien_inap pi","pi.no_rm=p.no_pasien","left");
	    	$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin","left");
	    	$this->db->join("kawin k","k.kode=p.status_kawin","left");
			$this->db->join("kamar ka","ka.kode_kamar=pi.kode_kamar","left");
			$this->db->join("ruangan ru","ru.kode_ruangan=ka.kode_ruangan","left");
	    	$this->db->join("pendidikan pen","pen.idx=p.pendidikan","left");
	    	$this->db->join("poliklinik po","po.kode=pr.dari_poli","left");
	    	$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
	    	$this->db->join("ekspertisi ek","ek.no_pasien=pr.no_pasien","left");
	    	$this->db->join("perusahaan per","per.kode = p.perusahaan","left");
	    	$this->db->join("pangkat pan","pan.id_pangkat = p.id_pangkat","left");
	    	$this->db->join("dokter d","d.id_dokter=pr.dokter_poli","left");
	    	$this->db->where("pr.no_reg",$no_reg);
	    	$q = $this->db->get("pasien p");
	    	return $q->row();
	    }
	    function getcetakinap($no_reg){
	    	$this->db->select("p.nama_pasien,p.jenis_kelamin,p.alamat,p.tgl_lahir,p.telpon,pi.no_rm, g.keterangan as golpas, ru.nama_ruangan");
	    	$this->db->join("pasien_inap pi","pi.no_rm=p.no_pasien","left");
	    	// $this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin","left");
	    	$this->db->join("kawin k","k.kode=p.status_kawin","left");
			// $this->db->join("kamar ka","ka.kode_kamar=pi.kode_kamar","left");
			$this->db->join("ruangan ru","ru.kode_ruangan=pi.kode_ruangan","left");
	    	$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
	  //   	$this->db->join("ekspertisi ek","ek.no_pasien=pi.no_rm","left");
	  //   	$this->db->join("ekspertisi_labinap eks","eks.no_reg=pi.no_reg","left");
	  //   	$this->db->join("perusahaan per","per.kode = p.perusahaan","left");
	  //   	$this->db->join("pangkat pan","pan.id_pangkat = p.id_pangkat","left");
	  //   	$this->db->join("dokter d","d.id_dokter=pi.dokter","left");
	    	// $this->db->join("kasir_inap ki","ki.no_reg=pi.no_reg","left");

	    	$this->db->where("pi.no_reg",$no_reg);
	    	$q = $this->db->get("pasien p");
	    	return $q->row();
        }
        function getcetakinap_multi(){
            $data = $this->session->userdata("nr");
            foreach ($data as $key => $no_reg) {
                $this->db->select("p.nama_pasien");
                $this->db->join("pasien_inap pi","pi.no_rm=p.no_pasien","inner");
                $this->db->where("pi.no_reg",$no_reg);
                $q = $this->db->get("pasien p");
            }
	    }
	    function getanalys(){
	    	// return	$this->db->get("analys");
				return $this->db->get_where("perawat",["bagian"=>"0102024"]);
	    }
	    function gettanggal($no_reg,$t='',$pemeriksaan=''){
	    	$this->db->select("tanggal as tglp");
	    	$this->db->where("no_reg",$no_reg);
				if ($t!='')
	    	$this->db->where("tanggal",$t);
				if ($pemeriksaan!='')
	    	$this->db->where("pemeriksaan",$pemeriksaan);
	    	$q = $this->db->get("kasir_inap");
	    	return $q->row();
	    }
	    function getlab_normal($no_reg){
	    	$this->db->select("k.*,l.*,p.jenis_kelamin,t.nama_tindakan,j.judul");
	    	$this->db->join("lab_normal l","l.kode_tindakan=k.kode_tarif");
	    	$this->db->join("lab_judul j","j.kode_judul=l.kode_judul");
	    	$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif");
	    	$this->db->join("pasien_ralan pr","pr.no_reg=k.no_reg");
	    	$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
	    	$this->db->where("k.no_reg",$no_reg);
	    	$this->db->order_by("l.no_urut,k.kode_tarif,l.kode");
	    	$q = $this->db->get("kasir k");
	    	return $q;
	    }
	    function getlabinap_normal($no_reg,$tanggal="",$pemeriksaan=""){
	    	$this->db->select("k.*,l.*,p.jenis_kelamin,t.nama_tindakan, t.kode_tindakan, j.judul");
	    	$this->db->join("lab_normal l","l.kode_tindakan=k.kode_tarif");
	    	$this->db->join("lab_judul j","j.kode_judul=l.kode_judul");
	    	$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif");
	    	$this->db->join("pasien_inap pi","pi.no_reg=k.no_reg");
	    	$this->db->join("pasien p","p.no_pasien=pi.no_rm");
	    	$this->db->where("k.no_reg",$no_reg);
	    	if ($tanggal!="")
	    	$this->db->where("k.tanggal",$tanggal);
	    	if ($pemeriksaan!="")
	    	$this->db->where("k.pemeriksaan",$pemeriksaan);
	    	$this->db->order_by("k.tanggal,l.no_urut,k.kode_tarif,l.kode");
	    	$q = $this->db->get("kasir_inap k");
	    	return $q;
	    }
	    function getekspertisilab_detail($no_reg){
	    	$this->db->select("l.*,e.*,t.nama_tindakan,p.jenis_kelamin, j.judul,kr.analys");
	    	$this->db->join("lab_normal l","l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
	    	$this->db->join("tarif_lab t","t.kode_tindakan=e.kode_tindakan and t.kode_tindakan=l.kode_tindakan");
	    	$this->db->join("lab_judul j","j.kode_judul=e.kode_judul and j.kode_judul=l.kode_judul");
	    	$this->db->join("pasien_ralan pr","pr.no_reg=e.no_reg");
	    	$this->db->join("kasir kr","kr.no_reg=e.no_reg and kr.kode_tarif = e.kode_tindakan");
	    	$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
	    	$this->db->where("e.no_reg",$no_reg);
	    	$this->db->order_by("l.no_urut");
	    	$q = $this->db->get("ekspertisi_lab e");
	    	return $q;
	    }
	    function getekspertisilab_detail_covid($no_reg){
	    	$this->db->select("e.*,kr.pemeriksaan,a.nama_perawat as namaanalys,d1.nama_dokter as dokter_pengirim,d.nama_dokter,kr.kode,n1,n2,rp,kr.terima_lab,kr.periksa_lab,l.pria,l.wanita,m.nama_swab");
	    	$this->db->join("lab_normal l","l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
	    	// $this->db->join("tarif_lab t","t.kode_tindakan=e.kode_tindakan and t.kode_tindakan=l.kode_tindakan");
	    	// $this->db->join("lab_judul j","j.kode_judul=e.kode_judul and j.kode_judul=l.kode_judul");
			// $this->db->join("pasien_ralan pr","pr.no_reg=e.no_reg");
	    	$this->db->join("kasir kr","kr.no_reg=e.no_reg and kr.kode_tarif = e.kode_tindakan");
			$this->db->join("metode_swab m","m.kode_swab=kr.metode_swab","left");
            $this->db->join("perawat a","a.id_perawat=kr.analys","left");
            $this->db->join("dokter d1","d1.id_dokter=kr.dokter_pengirim","left");
            $this->db->join("dokter d","d.id_dokter=kr.kode_petugas","left");
	    	// $this->db->join("pasien p","p.no_pasien=pr.no_pasien");
				$this->db->group_start();
				$this->db->where("e.kode_tindakan","L158");
				$this->db->or_where("e.kode_tindakan","L121");
				$this->db->or_where("e.kode_tindakan","L151");
				$this->db->group_end();
	    	$this->db->where("e.no_reg",$no_reg);
	    	// $this->db->order_by("l.no_urut");
	    	$q = $this->db->get("ekspertisi_lab e");
	    	return $q;
	    }
	    function getekspertisilab_detail_array($no_reg){
	    	$this->db->where("e.no_reg",$no_reg);
	    	$q = $this->db->get("ekspertisi_lab e");
	    	$data = array();
	    	foreach ($q->result() as $row){
	    		$data[$row->kode_labnormal] = $row;
	    	}
	    	return $data;
	    }
	    function getkasir_ekspertisi_array($no_reg){
	    	$this->db->where("e.no_reg",$no_reg);
	    	$q = $this->db->get("ekspertisi_lab e");
	    	$data = array();
	    	foreach ($q->result() as $row){
	    		$data[$row->kode_tindakan] = $row->hasil;
	    	}
	    	return $data;
	    }
	    function getekspertisilabinap_detail_covid($no_reg,$tanggal="",$pemeriksaan=""){
	    	$this->db->select("e.*, ki.tanggal,d.nip,d1.nama_dokter as dokter_pengirim, d.nama_dokter,a.nama_perawat as namaanalys,n1,n2,rp,ki.kode,ki.terima_lab,ki.periksa_lab,l.pria,l.wanita,m.nama_swab");
	    	$this->db->join("kasir_inap ki","ki.no_reg=e.no_reg and ki.kode_tarif = e.kode_tindakan and ki.tanggal=e.tanggal and ki.pemeriksaan = e.pemeriksaan");
	    	$this->db->join("lab_normal l","l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
			$this->db->join("metode_swab m","m.kode_swab=ki.metode_swab","left");
			$this->db->join("perawat a","a.id_perawat=ki.analys","left");
			$this->db->join("dokter d1","d1.id_dokter=ki.dokter_pengirim","left");
	    	$this->db->join("dokter d","d.id_dokter=ki.kode_petugas","left");
            $this->db->where("e.no_reg",$no_reg);
						if ($tanggal!="")
            	$this->db->where("e.tanggal",$tanggal);
						if ($pemeriksaan!="")
            	$this->db->where("e.pemeriksaan",$pemeriksaan);
							$this->db->group_start();
							$this->db->where("e.kode_tindakan","L158");
							$this->db->or_where("e.kode_tindakan","L121");
							$this->db->or_where("e.kode_tindakan","L151");
							$this->db->group_end();
	    	$this->db->order_by("e.kode_judul");
            $q = $this->db->get("ekspertisi_labinap e");
            return $q;
            // $this->db->select("l.*,e.*,t.nama_tindakan,p.jenis_kelamin, ki.tanggal,ki.analys, j.judul, ki.kode_petugas");
	    	// $this->db->join("lab_normal l","l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
	    	// $this->db->join("tarif_lab t","t.kode_tindakan=e.kode_tindakan and t.kode_tindakan=l.kode_tindakan");
	    	// $this->db->join("lab_judul j","j.kode_judul=e.kode_judul and j.kode_judul=l.kode_judul");
	    	// $this->db->join("pasien_inap pi","pi.no_reg=e.no_reg","left");
	    	// $this->db->join("pasien p","p.no_pasien=pi.no_rm","left");
	    	// $this->db->join("kasir_inap ki","ki.no_reg=pi.no_reg and ki.kode_tarif = e.kode_tindakan and ki.tanggal = e.tanggal and ki.pemeriksaan = e.pemeriksaan");
	    	// $this->db->where("e.no_reg",$no_reg);
	    	// $this->db->where("e.tanggal",$t);
	    	// $this->db->where("e.pemeriksaan",$pemeriksaan);
	    	// $this->db->order_by("e.kode_judul,l.no_urut");
	    	// $q = $this->db->get("ekspertisi_labinap e");
	    	// return $q;
        }
        function getekspertisilabinap_detail_covid_multi(){
        	$no_reg = $this->session->userdata("nr");
            $dt = array();
        	foreach ($no_reg as $key => $value) {
        		$this->db->select("e.*,n1,n2,rp,ki.kode,p.nama_pasien,p.jenis_kelamin as jk,p.alamat,p.tgl_lahir");
        		$this->db->join("pasien_inap pi","pi.no_reg=e.no_reg");
        		$this->db->join("pasien p","p.no_pasien=pi.no_rm");
		    	$this->db->join("kasir_inap ki","ki.no_reg=pi.no_reg and ki.kode_tarif = e.kode_tindakan and ki.tanggal=e.tanggal and ki.pemeriksaan = e.pemeriksaan");
		    	$this->db->join("perawat a","a.id_perawat=ki.analys","left");
		    	$this->db->join("dokter d","d.id_dokter=ki.kode_petugas","left");
        		$this->db->where("e.no_reg",$value);
						$this->db->group_start();
						$this->db->where("e.kode_tindakan","L158");
						$this->db->or_where("e.kode_tindakan","L121");
						$this->db->or_where("e.kode_tindakan","L151");
						$this->db->group_end();
            	$this->db->group_by("e.no_reg,e.tanggal,e.pemeriksaan");
	            $q = $this->db->get("ekspertisi_labinap e");
	            $row = $q->row();
	            $dt[$value] = $row;
        	}
            // $this->db->order_by("e.no_reg,e.tanggal,e.pemeriksaan");
            // foreach ($q->result() as $row) {
            // 	$dt[$row->no_reg] = $row->hasil;
            // }
	    	return $dt;
	    }
	    function getekspertisilabinap_detail_array($no_reg,$tanggal="",$pemeriksaan=""){
	    	$this->db->select("kode_labnormal,pemeriksaan,hasil,e.tanggal,e.n1,e.n2,e.rp");
	    	$this->db->where("e.no_reg",$no_reg);
	    	if ($tanggal!="")
	    	$this->db->where("e.tanggal",$tanggal);
	    	if ($pemeriksaan!="")
	    	$this->db->where("e.pemeriksaan",$pemeriksaan);
	    	$q = $this->db->get("ekspertisi_labinap e");
	    	$data = array();
	    	foreach ($q->result() as $row){
	    		$data[$row->kode_labnormal][$row->pemeriksaan][$row->tanggal] = $row;
	    	}
	    	return $data;
	    }
	    function getekspertisilabinap_detail($no_reg,$t='',$pemeriksaan=''){
	    	$this->db->select("l.*,e.*,t.nama_tindakan,p.jenis_kelamin, ki.tanggal,ki.analys, j.judul, ki.kode_petugas");
	    	$this->db->join("lab_normal l","l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
	    	$this->db->join("tarif_lab t","t.kode_tindakan=e.kode_tindakan and t.kode_tindakan=l.kode_tindakan");
	    	$this->db->join("lab_judul j","j.kode_judul=e.kode_judul and j.kode_judul=l.kode_judul");
	    	$this->db->join("pasien_inap pi","pi.no_reg=e.no_reg","left");
	    	$this->db->join("pasien p","p.no_pasien=pi.no_rm","left");
	    	$this->db->join("kasir_inap ki","ki.no_reg=pi.no_reg and ki.kode_tarif = e.kode_tindakan and ki.tanggal = e.tanggal and ki.pemeriksaan = e.pemeriksaan");
	    	$this->db->where("e.no_reg",$no_reg);
				if ($t!="")
	    	$this->db->where("e.tanggal",$t);
				if ($pemeriksaan!="")
	    	$this->db->where("e.pemeriksaan",$pemeriksaan);
	    	$this->db->order_by("e.kode_judul,l.no_urut");
	    	$q = $this->db->get("ekspertisi_labinap e");
	    	return $q;
	    }
	    function getekspertisilabinap_detail1($no_reg,$t,$pemeriksaan){
	    	$this->db->select("l.*,e.*,t.nama_tindakan,p.jenis_kelamin, ki.tanggal, ki.analys, j.judul, ki.kode_petugas");
	    	$this->db->join("lab_normal l","l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
	    	$this->db->join("tarif_lab t","t.kode_tindakan=e.kode_tindakan and t.kode_tindakan=l.kode_tindakan");
	    	$this->db->join("lab_judul j","j.kode_judul=e.kode_judul and j.kode_judul=l.kode_judul");
	    	$this->db->join("pasien_inap pi","pi.no_reg=e.no_reg","left");
	    	$this->db->join("pasien p","p.no_pasien=pi.no_rm","left");
	    	$this->db->join("kasir_inap ki","ki.no_reg=pi.no_reg and ki.kode_tarif = e.kode_tindakan and ki.tanggal = e.tanggal and ki.pemeriksaan = e.pemeriksaan");
	    	$this->db->where("e.no_reg",$no_reg);
	    	$this->db->where("e.tanggal",$t);
	    	$this->db->where("e.pemeriksaan",$pemeriksaan);
	    	$this->db->order_by("e.kode_judul,l.no_urut");
	    	$q = $this->db->get("ekspertisi_labinap e");
	    	return $q;
	    }
	    function getekspertisilab_detailcetak($no_reg){
	    	$this->db->select("l.*,e.*,t.nama_tindakan,p.jenis_kelamin");
	    	$this->db->join("lab_normal l","l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
	    	$this->db->join("tarif_lab t","t.kode_tindakan=e.kode_tindakan and t.kode_tindakan=l.kode_tindakan and e.kode_tindakan = l.kode_tindakan");
	    	$this->db->join("pasien_ralan pr","pr.no_reg=e.no_reg");
	    	$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
	    	$this->db->where("e.no_reg",$no_reg);
	    	$q = $this->db->get("ekspertisi_lab e");
	    	return $q;
	    }
	    function getekspertisilabinap_detailcetak($no_reg,$t,$pemeriksaan){
	    	$this->db->select("l.*,e.*,t.nama_tindakan,p.jenis_kelamin");
	    	$this->db->join("lab_normal l","l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
	    	$this->db->join("tarif_lab t","t.kode_tindakan=e.kode_tindakan and t.kode_tindakan=l.kode_tindakan and e.kode_tindakan = l.kode_tindakan");
	    	$this->db->join("pasien_inap pi","pi.no_reg=e.no_reg");
	    	$this->db->join("pasien p","p.no_pasien=pi.no_rm");
	    	$this->db->where("e.no_reg",$no_reg);
	    	if ($t!=""){
	    		$this->db->where("e.tanggal",$t);
	    	}
	    	if ($pemeriksaan!="") {
	    		$this->db->where("e.pemeriksaan",$pemeriksaan);
	    	}
	    	$this->db->order_by("e.kode_judul,l.no_urut");
	    	$q = $this->db->get("ekspertisi_labinap e");
	    	return $q;
	    }

	   	function getdokter(){
	   		$this->db->where("id_dokter","058");
	   		return $this->db->get("dokter");
	   	}
	   	function getmetode(){
	   		return $this->db->get("metode_swab");
	   	}
	   	function getdokterall(){
	   		return $this->db->get("dokter");
	   	}
	   	function hapusinap(){
			$this->db->where("id",$this->input->post("id"));
			$q = $this->db->get("kasir_inap");
			$row = $q->row();
			$this->db->where("id",$this->input->post("id"));
			$this->db->delete("kasir_inap");
			$this->db->where("no_reg",$row->no_reg);
			$this->db->where("tanggal",$row->tanggal);
			$this->db->where("pemeriksaan", $row->pemeriksaan);
			$this->db->where("kode_tindakan", $row->kode_tarif);
			$this->db->delete("ekspertisi_labinap");
			$data = array(
						"no_reg" => $row->no_reg,
						"tanggal" => date("Y-m-d H:i:s"),
						"username" => $this->input->post("username"),
						"keterangan" => "Berhasil menghapus tindakan Labotarium No Reg : ".$row->no_reg
					);
			$this->db->insert("log_delete",$data);
		}
		function hapusralan(){
			$this->db->where("id",$this->input->post("id"));
			$q = $this->db->get("kasir");
			$row = $q->row();
			$this->db->where("id",$this->input->post("id"));
			$this->db->delete("kasir");
			$this->db->where("no_reg",$row->no_reg);
			$this->db->where("kode_tindakan", $row->kode_tarif);
			$this->db->delete("ekspertisi_lab");
		}
		function getdokter_array($status=""){
			$this->db->select("id_dokter,nama_dokter");
			if ($status!="all")
			$this->db->where("id_dokter","058");
			$q = $this->db->get("dokter");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->id_dokter] = $key->nama_dokter;
			}
			return $data;
		}
		function getmetode_swab_array($status=""){
			if ($status!="all")
			$q = $this->db->get("metode_swab");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->kode_swab] = $key->nama_swab;
			}
			return $data;
		}
		function getanalys_array(){
			$this->db->select("id_perawat as nip,nama_perawat as nama");
			$q = $this->db->get("perawat");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->nip] = $key->nama;
			}
			return $data;
		}
		function changedata_ralan(){
			$no_reg = $this->db->get_where("kasir",["id"=>$this->input->post("id")])->row()->no_reg;
			switch ($this->input->post("jenis")) {
				case 'petugas':
					$data = array("kode_petugas"=>$this->input->post("value"),"terima_lab"=>date("Y-m-d H:i:s"));
					$data2 = array("dokter_poli"=>$this->input->post("value"));
					break;
				case 'analys':
					$data = array("analys"=>$this->input->post("value"));
					$data2 = array("analys"=>$this->input->post("value"));
					break;
				case 'dokter_pengirim':
					$data = array("dokter_pengirim"=>$this->input->post("value"));
					$data2 = array("dokter_pengirim"=>$this->input->post("value"));
					break;
				case 'kode':
					$data = array("kode"=>$this->input->post("value"));
					break;
				case 'pemeriksaan':
					$data = array("pemeriksaan"=>$this->input->post("value"));
					break;
				case 'metode_swab':
					$data = array("metode_swab"=>$this->input->post("value"));
					break;
			}
			$this->db->where("id",$this->input->post("id"));
			$this->db->update("kasir",$data);
			$this->db->where("no_reg",$no_reg);
			$this->db->update("pasien_ralan",$data2);
			$multiid = $this->input->post("multiid");
			if (is_array($multiid)){
				foreach ($multiid as $key => $value) {
					$no_reg = $this->db->get_where("kasir",["id"=>$key])->row()->no_reg;
					switch ($this->input->post("jenis")) {
						case 'petugas':
							$data = array("kode_petugas"=>$this->input->post("value"),"terima_lab"=>date("Y-m-d H:i:s"));
							$data2 = array("dokter_poli"=>$this->input->post("value"));
							break;
						case 'analys':
							$data = array("analys"=>$this->input->post("value"));
							$data2 = array("analys"=>$this->input->post("value"));
							break;
						case 'dokter_pengirim':
							$data = array("dokter_pengirim"=>$this->input->post("value"));
							$data2 = array("dokter_pengirim"=>$this->input->post("value"));
							break;
						case 'kode':
							$data = array("kode"=>$this->input->post("value"));
							break;
					}
					$this->db->where("id",$key);
					$this->db->update("kasir",$data);
					$this->db->where("no_reg",$no_reg);
					$this->db->update("pasien_ralan",$data2);
				}
			}
		}
		function changedata_inap(){
			switch ($this->input->post("jenis")) {
				case 'petugas':
					$data = array("kode_petugas"=>$this->input->post("value"),"terima_lab"=>date("Y-m-d H:i:s"));
					break;
				case 'analys':
					$data = array("analys"=>$this->input->post("value"));
					break;
				case 'dokter_pengirim':
					$data = array("dokter_pengirim"=>$this->input->post("value"));
					break;
				case 'kode':
					$data = array("kode"=>$this->input->post("value"));
					break;
				case 'metode_swab':
					$data = array("metode_swab"=>$this->input->post("value"));
					break;
			}
			$id = explode("/",$this->input->post("id"));
			if ($this->input->post("jenis")=="kode") {
				$this->db->where("id",$id[0]);
				$this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
				$this->db->where("pemeriksaan",$id[2]);
				$this->db->where("no_reg",$this->input->post("no_reg"));
				$this->db->like("kode_tarif","L","after");
				$this->db->update("kasir_inap",$data);
			} else {
				$this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
				$this->db->where("pemeriksaan",$id[2]);
				$this->db->where("no_reg",$this->input->post("no_reg"));
				$this->db->like("kode_tarif","L","after");
				$this->db->update("kasir_inap",$data);
			}
		}
		function getdokterpengirim_array(){
			$this->db->select("id_dokter,nama_dokter");
			$q = $this->db->get("dokter");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->id_dokter] = $key->nama_dokter;
			}
			return $data;
		}
		function gettotalpasien($jenis){
			$kode_dokter = $this->session->userdata("kode_dokter");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$status_pasien = $this->session->userdata("status_pasien");
			$nama = $this->session->userdata("nama");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$this->db->select("pr.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien");
			if ($tgl1!="" OR $tgl2!="") {
				$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			}
			if ($no_pasien!="") {
				$no_pasien = "000000".$no_pasien;
				$this->db->where("p.no_pasien",substr($no_pasien,-6));
			}
			if ($no_reg!="") {
				$this->db->where("no_reg",$no_reg);
			}
			if ($nama!="") {
				$this->db->like("p.nama_pasien",$nama);
			}
			if ($kode_dokter!="") {
				$this->db->where("pr.dokter_poli",$kode_dokter);
			}
			if ($jenis=="LAYAN") {
	            $this->db->where("layan",0);
	        } else {
	            $this->db->where("layan",2);
	        }
			$this->db->order_by("no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
			$this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli");
			$this->db->where("pr.tujuan_poli","0102024");
			$query = $this->db->get("pasien_ralan pr");
			return $query->num_rows();
		}
		function gettindakan(){
			$q = $this->db->get("tarif_lab");
			return $q;
		}
		function gettindakan_cetak($tindakan){
			if ($tindakan != "all") {
				$this->db->where("kode_tindakan", $tindakan);
			}
			$q = $this->db->get("tarif_lab");
			return $q;
		}

		function gettindakan_cetak2($tindakan){
			if ($tindakan != "all") {
				$this->db->where("kode_tindakan", $tindakan);
			}
			$q = $this->db->get("tarif_lab");
			return $q->row();
		}
		function labrekap_ralan($tindakan,$tgl1="",$tgl2=""){
    	    $data = array();
    	    $tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
    	    $tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
			$this->db->select("k.kode_tarif,p.status_pasien,p.jenis,p.gol_pasien");
			$this->db->where("layan!=",2);
			if ($tindakan!="all") {
				$this->db->where("k.kode_tarif",$tindakan);
			} else {
				$this->db->like("k.kode_tarif","L",'after');
			}
			$this->db->where("date(p.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(p.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->join("kasir k","k.no_reg=p.no_reg","inner");
			// $this->db->order_by("jumlah","desc");
			// $this->db->group_by("kode_tarif");
			$sql = $this->db->get("pasien_ralan p");
			foreach ($sql->result() as $key) {
				if (isset($data["tindakan"][$key->kode_tarif]))
				    $data["tindakan"][$key->kode_tarif] += 1;
				else
				    $data["tindakan"][$key->kode_tarif] = 1;
			    if ($key->jenis=="R"){
			        if (isset($data["REGULER"][$key->kode_tarif]))
			            $data["REGULER"][$key->kode_tarif] += 1;
			        else
			            $data["REGULER"][$key->kode_tarif] = 1;
			    } else
			    if ($key->jenis=="E"){
			        if (isset($data["EKSEKUTIF"][$key->kode_tarif]))
			            $data["EKSEKUTIF"][$key->kode_tarif] += 1;
			        else
			            $data["EKSEKUTIF"][$key->kode_tarif] = 1;
			    }
			    if ($key->status_pasien=="BARU"){
			        if (isset($data["BARU"][$key->kode_tarif]))
			            $data["BARU"][$key->kode_tarif] += 1;
			        else
			            $data["BARU"][$key->kode_tarif] = 1;
			    } else
			    if ($key->status_pasien=="LAMA"){
			        if (isset($data["LAMA"][$key->kode_tarif]))
			            $data["LAMA"][$key->kode_tarif] += 1;
			        else
			            $data["LAMA"][$key->kode_tarif] = 1;
			    }
			    if (($key->gol_pasien>=404 && $key->gol_pasien<=410) || ($key->gol_pasien>=415 && $key->gol_pasien<=417) || ($key->gol_pasien==3133)){
			        if (isset($data["DINAS"][$key->kode_tarif]))
			            $data["DINAS"][$key->kode_tarif] += 1;
			        else
			            $data["DINAS"][$key->kode_tarif] = 1;
			    } else
			    if ($key->gol_pasien==11){
			        if (isset($data["UMUM"][$key->kode_tarif]))
			            $data["UMUM"][$key->kode_tarif] += 1;
			        else
			            $data["UMUM"][$key->kode_tarif] = 1;
			    } else
			    if (($key->gol_pasien>=400 && $key->gol_pasien<=403) || ($key->gol_pasien>=411 && $key->gol_pasien<=414) || ($key->gol_pasien>=418 && $key->gol_pasien<=420)){
			        if (isset($data["BPJS"][$key->kode_tarif]))
			            $data["BPJS"][$key->kode_tarif] += 1;
			        else
			            $data["BPJS"][$key->kode_tarif] = 1;
			    } else
			    if (($key->gol_pasien==12) || ($key->gol_pasien==13) || ($key->gol_pasien>=16 && $key->gol_pasien<=18)){
			        if (isset($data["PRSH"][$key->kode_tarif]))
			            $data["PRSH"][$key->kode_tarif] += 1;
			        else
			            $data["PRSH"][$key->kode_tarif] = 1;
			    }
			}
    	    return $data;
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
    	function labrekap_inap($tindakan,$tgl1="",$tgl2=""){
    	    $data = array();
    	    $tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
    	    $tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
			$this->db->select("k.kode_tarif,pa.id_gol");
			if ($tindakan!="all") {
				$this->db->where("k.kode_tarif",$tindakan);
			} else {
				$this->db->like("k.kode_tarif","L",'after');
			}
			$this->db->where("date(k.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(k.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->join("kasir_inap k","k.no_reg=p.no_reg","inner");
			$this->db->join("pasien pa","pa.no_pasien = p.no_rm","left");
			// $this->db->order_by("jumlah","desc");
			// $this->db->group_by("kode_tarif");
			$sql = $this->db->get("pasien_inap p");
			foreach ($sql->result() as $key) {
				if (isset($data["tindakan"][$key->kode_tarif]))
				    $data["tindakan"][$key->kode_tarif] += 1;
				else
				    $data["tindakan"][$key->kode_tarif] = 1;

			    if (($key->id_gol>=404 && $key->id_gol<=410) || ($key->id_gol>=415 && $key->id_gol<=417) || ($key->id_gol==3133)){
			        if (isset($data["DINAS"][$key->kode_tarif]))
			            $data["DINAS"][$key->kode_tarif] += 1;
			        else
			            $data["DINAS"][$key->kode_tarif] = 1;
			    } else
			    if ($key->id_gol==11){
			        if (isset($data["UMUM"][$key->kode_tarif]))
			            $data["UMUM"][$key->kode_tarif] += 1;
			        else
			            $data["UMUM"][$key->kode_tarif] = 1;
			    } else
			    if (($key->id_gol>=400 && $key->id_gol<=403) || ($key->id_gol>=411 && $key->id_gol<=414) || ($key->id_gol>=418 && $key->id_gol<=420)){
			        if (isset($data["BPJS"][$key->kode_tarif]))
			            $data["BPJS"][$key->kode_tarif] += 1;
			        else
			            $data["BPJS"][$key->kode_tarif] = 1;
			    } else
			    if (($key->id_gol==12) || ($key->id_gol==13) || ($key->id_gol>=16 && $key->id_gol<=18)){
			        if (isset($data["PRSH"][$key->kode_tarif]))
			            $data["PRSH"][$key->kode_tarif] += 1;
			        else
			            $data["PRSH"][$key->kode_tarif] = 1;
			    }
			}
    	    return $data;
    	}
    	function getpasien_rekap_inap($tindakan,$tgl1,$tgl2){
    		$this->db->select("pi.*,p.nama_pasien as nama_pasien,s.keterangan, k.pemeriksaan, r.nama_ruangan, d.nama_dokter, kls.nama_kelas, kmr.nama_kamar, k.tanggal");
    		$this->db->order_by("pi.no_reg");
			$this->db->join("pasien p","p.no_pasien=pi.no_rm");
			$this->db->join("status_pulang s","s.id=pi.status_pulang","left");
			$this->db->join("kasir_inap k","k.no_reg=pi.no_reg","inner");
			$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
			$this->db->join("kelas kls","kls.kode_kelas=pi.kode_kelas","left");
			$this->db->join("kamar kmr","kmr.kode_kamar=pi.kode_kamar and kls.kode_kelas = kmr.kode_kelas and r.kode_ruangan = kmr.kode_ruangan","left");
			$this->db->join("dokter d","d.id_dokter = pi.dokter","left");
			// $this->db->where("pi.tujuan_poli","0102024");
			$this->db->where("k.kode_tarif",$tindakan);
			$this->db->where("date(k.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(k.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->group_by("k.id,pi.no_reg");
			$query = $this->db->get("pasien_inap pi");
			return $query->result();
    	}
    	function getttddokter($id_dokter){
			$this->db->select("ttd");
			$this->db->where("id_dokter",$id_dokter);
			return $this->db->get("dokter_ttd");
		}
		function getjenisfile(){
			return $this->db->get("jenisfile");
		}
		function cekkasir_detail(){
			$this->db->where("k.no_reg",$this->input->post("no_reg"));
			$this->db->group_start();
			$this->db->where("k.kode_petugas","");
			$this->db->or_where("k.kode_petugas IS NULL",NULL,FALSE);
			$this->db->or_where("k.analys","");
			$this->db->or_where("k.analys IS NULL",NULL,FALSE);
			$this->db->or_where("k.dokter_pengirim","");
			$this->db->or_where("k.dokter_pengirim IS NULL",NULL,FALSE);
			$this->db->group_end();
			$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif","inner");
			$q = $this->db->get("kasir k");
			return $q;
		}
		function cekkasirinap_detail(){
			$this->db->where("k.no_reg",$this->input->post("no_reg"));
			$this->db->group_start();
			$this->db->where("k.kode_petugas","");
			$this->db->or_where("k.kode_petugas IS NULL",NULL,FALSE);
			$this->db->or_where("k.analys","");
			$this->db->or_where("k.analys IS NULL",NULL,FALSE);
			$this->db->or_where("k.dokter_pengirim","");
			$this->db->or_where("k.dokter_pengirim IS NULL",NULL,FALSE);
			$this->db->group_end();
			$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif","inner");
			$q = $this->db->get("kasir_inap k");
			return $q;
		}
		function getswabke($no_reg){
			$no_rm = $this->db->get_where("pasien_ralan",["no_reg"=>$no_reg])->row()->no_pasien;
			$this->db->join("pasien_ralan p","p.no_reg=k.no_reg","inner");
			$this->db->where("p.no_pasien",$no_rm);
			$this->db->where("k.kode_tarif","L158");
			$this->db->where("k.no_reg<=",$no_reg);
			$q = $this->db->get("kasir k");
			return $q->num_rows();
		}
		function getswabinapke($no_reg){
			$no_rm = $this->db->get_where("pasien_inap",["no_reg"=>$no_reg])->row()->no_rm;
			$this->db->join("pasien_inap p","p.no_reg=k.no_reg","inner");
			$this->db->where("p.no_rm",$no_rm);
			$this->db->where("k.kode_tarif","L158");
			$this->db->where("k.no_reg<=",$no_reg);
			$q = $this->db->get("kasir_inap k");
			return $q->num_rows();
		}
		function rekap_ralan_full($tindakan,$tgl1="",$tgl2=""){
			$data = array();
			$tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
			$tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
			$this->db->select("k.metode_swab,k.no_reg,k.kode_tarif,k.asal,p.status_pasien,p.jenis,p.gol_pasien,k.jam_lab");
			$this->db->where("layan!=",2);
			if ($tindakan!="all") {
				$this->db->where("k.kode_tarif",$tindakan);
			} else {
				$this->db->like("k.kode_tarif","L",'after');
			}
			$this->db->where("date(p.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(p.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->join("kasir k","k.no_reg=p.no_reg","inner");
			// $this->db->order_by("jumlah","desc");
			// $this->db->group_by("kode_tarif");
			$sql = $this->db->get("pasien_ralan p");
			foreach ($sql->result() as $key) {
				if ($key->kode_tarif=="L158" || $key->kode_tarif=="L160" || $key->kode_tarif=="L047"){
						$s = $this->db->get_where("ekspertisi_lab",["no_reg"=>$key->no_reg,"kode_tindakan"=>$key->kode_tarif]);
						if ($s->num_rows()>0){
							$srow = $s->row();
							$hasil = $srow->hasil;
							if (strtolower($hasil)=="reaktif") $hasil = "positif";
							if (strtolower($hasil)=="non reaktif") $hasil = "negatif";
							if (isset($data[strtolower($hasil)][$key->kode_tarif]))
							$data[strtolower($hasil)][$key->kode_tarif] += 1;
							else
							$data[strtolower($hasil)][$key->kode_tarif] = 1;
						}
				}
				if (isset($data["tindakan"][$key->kode_tarif]))
				$data["tindakan"][$key->kode_tarif] += 1;
				else
				$data["tindakan"][$key->kode_tarif] = 1;
				if ($key->jenis=="R"){
					if (isset($data["REGULER"][$key->kode_tarif]))
					$data["REGULER"][$key->kode_tarif] += 1;
					else
					$data["REGULER"][$key->kode_tarif] = 1;
				} else
				if ($key->jenis=="E"){
					if (isset($data["EKSEKUTIF"][$key->kode_tarif]))
					$data["EKSEKUTIF"][$key->kode_tarif] += 1;
					else
					$data["EKSEKUTIF"][$key->kode_tarif] = 1;
				}
				if ($key->status_pasien=="BARU"){
					if (isset($data["BARU"][$key->kode_tarif]))
					$data["BARU"][$key->kode_tarif] += 1;
					else
					$data["BARU"][$key->kode_tarif] = 1;
				} else
				if ($key->status_pasien=="LAMA"){
					if (isset($data["LAMA"][$key->kode_tarif]))
					$data["LAMA"][$key->kode_tarif] += 1;
					else
					$data["LAMA"][$key->kode_tarif] = 1;
				}
				if ($key->jam_lab!="0000-00-00 00:00:00"){
					if (isset($data["EKS"][$key->kode_tarif]))
					$data["EKS"][$key->kode_tarif] += 1;
					else
					$data["EKS"][$key->kode_tarif] = 1;
				} else {
					if ($key->kode_tarif=="L158" || $key->kode_tarif=="160"){
						if ($key->metode_swab!=""){
							if (isset($data["EKS"][$key->kode_tarif]))
							$data["EKS"][$key->kode_tarif] += 1;
							else
							$data["EKS"][$key->kode_tarif] = 1;
						}
					}
				}
				if ($key->asal=="DR"){
					if (isset($data["DR"][$key->kode_tarif]))
					$data["DR"][$key->kode_tarif] += 1;
					else
					$data["DR"][$key->kode_tarif] = 1;
				} else
				if ($key->asal=="MANUAL"){
					if (isset($data["MANUAL"][$key->kode_tarif]))
					$data["MANUAL"][$key->kode_tarif] += 1;
					else
					$data["MANUAL"][$key->kode_tarif] = 1;
				}
				if (($key->gol_pasien>=404 && $key->gol_pasien<=410) || ($key->gol_pasien>=415 && $key->gol_pasien<=417) || ($key->gol_pasien==3133)){
					if (isset($data["DINAS"][$key->kode_tarif]))
					$data["DINAS"][$key->kode_tarif] += 1;
					else
					$data["DINAS"][$key->kode_tarif] = 1;
				} else
				if ($key->gol_pasien==11){
					if (isset($data["UMUM"][$key->kode_tarif]))
					$data["UMUM"][$key->kode_tarif] += 1;
					else
					$data["UMUM"][$key->kode_tarif] = 1;
				} else
				if (($key->gol_pasien>=400 && $key->gol_pasien<=403) || ($key->gol_pasien>=411 && $key->gol_pasien<=414) || ($key->gol_pasien>=418 && $key->gol_pasien<=420)){
					if (isset($data["BPJS"][$key->kode_tarif]))
					$data["BPJS"][$key->kode_tarif] += 1;
					else
					$data["BPJS"][$key->kode_tarif] = 1;
				} else
				if (($key->gol_pasien==12) || ($key->gol_pasien==13) || ($key->gol_pasien>=16 && $key->gol_pasien<=18)){
					if (isset($data["PRSH"][$key->kode_tarif]))
					$data["PRSH"][$key->kode_tarif] += 1;
					else
					$data["PRSH"][$key->kode_tarif] = 1;
				}
			}
			return $data;
		}
		function rekap_inap_full($tindakan,$tgl1="",$tgl2=""){
			$data = array();
			$tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
			$tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
			$this->db->select("k.metode_swab,k.no_reg,k.kode_tarif,k.asal,pa.id_gol,k.jam_lab");
			if ($tindakan!="all") {
				$this->db->where("k.kode_tarif",$tindakan);
			} else {
				$this->db->like("k.kode_tarif","L",'after');
			}
			$this->db->where("date(k.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(k.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->join("kasir_inap k","k.no_reg=p.no_reg","inner");
			$this->db->join("pasien pa","pa.no_pasien = p.no_rm","left");
			// $this->db->order_by("jumlah","desc");
			// $this->db->group_by("kode_tarif");
			$sql = $this->db->get("pasien_inap p");
			foreach ($sql->result() as $key) {
				if ($key->kode_tarif=="L158" || $key->kode_tarif=="L160" || $key->kode_tarif=="L047"){
						$s = $this->db->get_where("ekspertisi_labinap",["no_reg"=>$key->no_reg,"kode_tindakan"=>$key->kode_tarif]);
						if ($s->num_rows()>0){
							$srow = $s->row();
							$hasil = $srow->hasil;
							if (strtolower($hasil)=="reaktif" || strpos(strtolower($hasil),"positif")!==false ) $hasil = "positif";
							if (strtolower($hasil)=="non reaktif" || strpos(strtolower($hasil),"negatif")!==false) $hasil = "negatif";
							if (strtolower($hasil)=="") $hasil = "kosong";
							if (isset($data[strtolower($hasil)][$key->kode_tarif]))
							$data[strtolower($hasil)][$key->kode_tarif] += 1;
							else
							$data[strtolower($hasil)][$key->kode_tarif] = 1;
						}
				}
				if (isset($data["tindakan"][$key->kode_tarif]))
				$data["tindakan"][$key->kode_tarif] += 1;
				else
				$data["tindakan"][$key->kode_tarif] = 1;

				if (($key->id_gol>=404 && $key->id_gol<=410) || ($key->id_gol>=415 && $key->id_gol<=417) || ($key->id_gol==3133)){
					if (isset($data["DINAS"][$key->kode_tarif]))
					$data["DINAS"][$key->kode_tarif] += 1;
					else
					$data["DINAS"][$key->kode_tarif] = 1;
				} else
				if ($key->id_gol==11){
					if (isset($data["UMUM"][$key->kode_tarif]))
					$data["UMUM"][$key->kode_tarif] += 1;
					else
					$data["UMUM"][$key->kode_tarif] = 1;
				} else
				if (($key->id_gol>=400 && $key->id_gol<=403) || ($key->id_gol>=411 && $key->id_gol<=414) || ($key->id_gol>=418 && $key->id_gol<=420)){
					if (isset($data["BPJS"][$key->kode_tarif]))
					$data["BPJS"][$key->kode_tarif] += 1;
					else
					$data["BPJS"][$key->kode_tarif] = 1;
				} else
				if (($key->id_gol==12) || ($key->id_gol==13) || ($key->id_gol>=16 && $key->id_gol<=18)){
					if (isset($data["PRSH"][$key->kode_tarif]))
					$data["PRSH"][$key->kode_tarif] += 1;
					else
					$data["PRSH"][$key->kode_tarif] = 1;
				}
				if ($key->asal=="DR"){
					if (isset($data["DR"][$key->kode_tarif]))
					$data["DR"][$key->kode_tarif] += 1;
					else
					$data["DR"][$key->kode_tarif] = 1;
				} else
				if ($key->asal=="MANUAL"){
					if (isset($data["MANUAL"][$key->kode_tarif]))
					$data["MANUAL"][$key->kode_tarif] += 1;
					else
					$data["MANUAL"][$key->kode_tarif] = 1;
				}
				if ($key->kode_tarif=="L158" || $key->kode_tarif=="160"){
					if ($key->jam_lab!="0000-00-00 00:00:00" && $key->metode_swab!=""){
						if (isset($data["PEMERIKSAAN"][$key->kode_tarif]))
						$data["PEMERIKSAAN"][$key->kode_tarif] += 1;
						else
						$data["PEMERIKSAAN"][$key->kode_tarif] = 1;
					}
				} else {
					if ($key->jam_lab!="0000-00-00 00:00:00"){
						if (isset($data["PEMERIKSAAN"][$key->kode_tarif]))
						$data["PEMERIKSAAN"][$key->kode_tarif] += 1;
						else
						$data["PEMERIKSAAN"][$key->kode_tarif] = 1;
					}
				}
			}
			return $data;
		}
		function getpasien_rekap_full($tindakan,$tgl1,$tgl2){
			$data = array();
			//ralan
			$this->db->select("k.pemeriksaan,pr.tanggal,pr.no_pasien,pr.no_reg,pr.tujuan_poli,pr.dari_poli,pr.dokter_pengirim,pr.layan");
			$this->db->where("pr.tujuan_poli","0102024");
			$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->where("k.kode_tarif",$tindakan);
			$this->db->join("kasir k","k.no_reg=pr.no_reg","inner");
			$this->db->order_by("pr.no_reg");
			$query = $this->db->get("pasien_ralan pr");
			foreach ($query->result() as $row) {

				// $this->db->where("k.no_reg",$row->no_reg);
				// $q = $this->db->get("kasir k");
				// if ($q->num_rows()>0){
				$data["list"][$row->no_reg] = $row;
				// $data["kasir"][$row->no_reg] = $q->row();
				$q = $this->db->get_where("pasien",["no_pasien"=>$row->no_pasien]);
				$data["master"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
				$q = $this->db->get_where("poliklinik",["kode"=>$row->tujuan_poli]);
				$data["pol2"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
				$q = $this->db->get_where("poliklinik",["kode"=>$row->dari_poli]);
				$data["pol"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
				$q = $this->db->get_where("dokter",["id_dokter"=>$row->dokter_pengirim]);
				$data["dokter"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
				$this->db->order_by('hasil','desc');
      	$q = $this->db->get_where("ekspertisi_lab",["no_reg"=>$row->no_reg , "kode_tindakan"=>"$tindakan" ]);
                //$q = $this->db->get_where("ekspertisi_lab",["no_reg"=>$row->no_reg]);
				$data["ekspertisi_lab"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
				// }
			}
			//ranap
			$this->db->select("k.no_reg,k.dokter_pengirim,pi.kode_ruangan,pi.kode_kelas,pi.kode_kamar,pi.status_pulang,pi.no_rm as no_pasien, k.pemeriksaan, k.tanggal");
			$this->db->where("k.kode_tarif",$tindakan);
			$this->db->where("date(k.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(k.tanggal)<=",date("Y-m-d",strtotime($tgl2)));;
			//$this->db->order_by("k.no_reg");
			$this->db->join("kasir_inap k","k.no_reg=pi.no_reg","inner");
			$query = $this->db->get("pasien_inap pi");
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
				$q = $this->db->get_where("dokter",["id_dokter"=>$row->dokter_pengirim]);
				$data["dokter"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
				$this->db->order_by('hasil','desc');
                $q = $this->db->get_where("ekspertisi_labinap",["no_reg"=>$row->no_reg , "kode_tindakan"=>"$tindakan" ]);
				$data["ekspertisi_lab"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
			}
			return $data;
		}

        function getkasir_inap_ekspertisi_covid($no_reg){
			$this->db->select("k.tanggal,k.pemeriksaan");
			$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif","inner");
			$this->db->group_by("k.tanggal,k.pemeriksaan");
			$this->db->order_by("k.tanggal","DESC");
			$q = $this->db->get_where("kasir_inap k",["k.no_reg" => $no_reg, "k.kode_tarif"=> "L158"]);
			return $q;
		}
		function getheader(){
			$d = $this->db->get_where("lab_normal",["header"=>1]);
			$data = array();
			foreach($d->result() as $row){
				$data[$row->kode_tindakan] = $row->nama;
			}
			return $data;
		}
	}
?>
