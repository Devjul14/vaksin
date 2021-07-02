<?php
	class Mgizi extends CI_Model{
	   function __construct()
	    {
	        parent::__construct();
	    }
	    function getpasien_ralan_gizi($page,$offset){
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
        	$this->db->like("pr.no_pasien",$no_pasien);
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
			$this->db->where("pr.tujuan_poli","0102036");
			$query = $this->db->get("pasien_ralan pr",$page,$offset);
			return $query;
		}
		function getgizi_ralan(){
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
			$this->db->where("pr.tujuan_poli","0102036");
			$query = $this->db->get("pasien_ralan pr");
			return $query->num_rows();
		}
		function pilihdoktergizi(){
	    	$this->db->select("dokter.*, k.nama_kelompok");
	    	$this->db->join("kelompok_dokter k","k.id_kelompok = dokter.kelompok_dokter","left");
	    	$this->db->where("poli","0102036");
	    	return $this->db->get("dokter");
	    }
	    function getralan_detail($no_pasien,$no_reg){
			$this->db->select("pr.*,p.alamat,p.nama_pasien,pl.keterangan as poli,jk.keterangan jenis_kelamin,d.nama_dokter, d.gizi");
			$this->db->join("pasien p","pr.no_pasien=p.no_pasien");
			$this->db->join("poliklinik pl","pl.kode=pr.tujuan_poli");
			$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
			$this->db->join("dokter d","d.id_dokter=pr.dokter_poli","left");
			$this->db->where("pr.no_pasien",$no_pasien);
			$this->db->where("pr.no_reg",$no_reg);
			$q = $this->db->get("pasien_ralan pr");
			return $q->row();
		}
		function gettarif_gizi(){
			return $this->db->get("tarif_gizi");
		}
		function addtindakan(){
			$t = $this->db->get_where("tarif_gizi",["kode_tindakan" => $this->input->post("tindakan")]);
			if ($t->num_rows()>0){
				$data = $t->row();
				if ($this->input->post('jenis')=="R") $tarif = $data->reguler; else $tarif = $data->executive;
				$data = array(
							"id" => date("dmyHis"),
							"no_reg" => $this->input->post("no_reg"),
							"kode_tarif" => $this->input->post("tindakan"),
							"nofoto" => $this->input->post("nofoto"),
							"ukuranfoto" => $this->input->post("ukuranfoto"),
							"kode_petugas" => $this->input->post("dokter_gizi"),
							"analys" => $this->input->post("petugas_gizi"),
							"dokter_pengirim" => $this->input->post("dokter_pengirim"),
							"jumlah" => $tarif
						);
				$this->db->insert("kasir",$data);
			}
		}

		function addtindakan_inap(){
			$kelas = $this->input->post("kode_kelas");
			$t = $this->db->get_where("tarif_gizi",["kode_tindakan" => $this->input->post("tindakan")]);
			
			if ($this->input->post("tanggal")=="") {
				$tanggal = date("Y-m-d");
			} else {
				$tanggal = date("Y-m-d",strtotime($this->input->post("tanggal")));
			}
			
			if ($t->num_rows()>0){
				$row = $t->row();
				switch ($kelas) {
					case '01':
						$tarif = $row->supervip;
						break;
					case '02':
						$tarif = $row->supervip;
						break;
					case '03':
						$tarif = $row->supervip;
						break;
					case '04':
						$tarif = $row->supervip;
						break;
					case '05':
						$tarif = $row->vip;
						break;
					case '051':
						$tarif = $row->vip;
						break;
					case '052':
						$tarif = $row->vip;
						break;
					case '053':
						$tarif = $row->vip;
						break;
					case '06':
						$tarif = $row->kelas_1;
						break;
					case '07':
						$tarif = $row->kelas_2;
						break;
					case '08':
						$tarif = $row->kelas_3;
						break;
					case '09':
						$tarif = $row->icu;
						break;
				}
				$data = array(
							"id" => date("dmyHis"),
							"no_reg" => $this->input->post("no_reg"),
							"kode_tarif" => $this->input->post("tindakan"),
							"pemeriksaan" => $this->input->post("pemeriksaan"),
							"kode_petugas" => $this->input->post("dokter_gizi"),
							"analys" => $this->input->post("petugas_gizi"),
							"dokter_pengirim" => $this->input->post("dokter_pengirim"),
							"qty" => 1,
							"tanggal" => $tanggal,
							"jumlah" => $tarif
						);
				$this->db->insert("kasir_inap",$data);
			}
		}
		function addtindakan_makan(){
			$y = $this->getpasien_inap_makan('','');
			// $data = array();
			if ($this->input->post("tanggal")=="") {
				$tanggal = date("Y-m-d");
			} else {
				$tanggal = date("Y-m-d",strtotime($this->input->post("tanggal")));
			}
			if ($this->input->post("wak")=="") {
			foreach ($y->result() as $key) {
				$data = array(
							"no_reg" => $key->no_reg,
							"tanggal" => $tanggal,
							"waktu" => $this->input->post("waktu"),
							"diet" => "-",
							"menu" => "-"
						);
				$this->db->insert("makan_pasien",$data);
				}
			}else{
				foreach ($y->result() as $key) {
				$data = array(
							"tanggal" => $tanggal,
							"waktu" => $this->input->post("waktu"),
						);
					$this->db->where("no_reg",$key->no_reg);
					$this->db->update("makan_pasien",$data);
				}
			}
			// if ($this->input->post("waktu")!=""){
					
			// }

		}
		function getkasir($no_reg){
			$this->db->join("tarif_gizi t","t.kode_tindakan=k.kode_tarif","inner");
			$q = $this->db->get_where("kasir k",["k.no_reg" => $no_reg]);
			return $q;
		}
		function getkasir_detail($no_reg,$kode_tindakan){
			$q = $this->db->get_where("kasir",["no_reg" => $no_reg,"kode_tarif"=> $kode_tindakan]);
			return $q->row();
		}
		function getkasir_inap($no_reg,$tgl){
			$this->db->join("tarif_gizi t","t.kode_tindakan=k.kode_tarif","inner");
			if ($tgl!="") {
				$this->db->where("tanggal",date("Y-m-d",strtotime($tgl)));
			}
			$this->db->where("k.no_reg",$no_reg);
			$this->db->order_by("k.tanggal","desc");
			$q = $this->db->get("kasir_inap k");
			return $q;
		}
		function getkasir_inap_detail($no_reg,$id_tindakan,$tgl,$pemeriksaan){
			$this->db->select("k.*,d.nama_dokter,r.nama,d.gizi,dp.nama_dokter as dokter_pengirim");
			$this->db->where("tanggal",date("Y-m-d",strtotime($tgl)));
			$this->db->where("k.no_reg",$no_reg);
			$this->db->where("k.kode_tarif",$id_tindakan);
			$this->db->where("k.pemeriksaan",$pemeriksaan);
			$this->db->join("dokter d","d.id_dokter=k.kode_petugas","left");
			$this->db->join("dokter dp","dp.id_dokter=k.dokter_pengirim","left");
			$this->db->join("petugas_gizi r","r.nip=k.analys","left");
			$q = $this->db->get("kasir_inap k");
			return $q->result();
		}
		function ambildatanormal($tabel){
			$this->db->order_by("kode");
			return $this->db->get($tabel);
		}
		function simpanekspertisi($action){
			$hasil_pemeriksaan = $this->input->post("hasil_pemeriksaan");
			foreach ($hasil_pemeriksaan as $key => $value) {
				$q = $this->db->get_where("ekspertisi_gizi",["no_reg"=>$this->input->post("no_reg"),"kode_asuhan"=>$key]);
				if ($q->num_rows()>0){
					$q = $q->row();
					$item = array(
						'hasil_pemeriksaan' => $value
					);
					$this->db->where("id_ekspertisi",$q->id_ekspertisi);
					$this->db->update("ekspertisi_gizi",$item);
				} else {
					$q = $this->db->get_where("asuhan_gizi",["kode"=>$key])->row();
					$item = array(
								'no_pasien' => $this->input->post("no_pasien"), 
			 					'no_reg' => $this->input->post("no_reg"), 
			 					'kode_tindakan' => $this->input->post("tindakan"), 
			 					'kode_asuhan' => $q->kode,
			 					'tanggal' => date("Y-m-d H:i:s"),
			 					'hasil_pemeriksaan' => $value
							);
					$this->db->insert("ekspertisi_gizi",$item);
				}
			}
			// $data1 = array(
			// 				'dokter_pengirim' => $this->input->post("dokter_pengirim"), 
			// 				'no_foto' => $this->input->post("no_foto"), 
			// 				'ukuran_foto' => $this->input->post("ukuran_foto"), 
			// 				'dokter_poli' => $this->input->post("dokter"), 
			// 				'petugas_gizi' => $this->input->post("petugas_gizi"), 
			// 			);
			// switch ($action) {
			// 	case 'simpan':
			// 		$data = array(
			// 					'no_pasien' => $this->input->post("no_pasien"), 
			// 					'no_reg' => $this->input->post("no_reg"), 
			// 					'hasil_pemeriksaan' => $this->input->post("hasil_pemeriksaan"),
			// 					'kode_tindakan' => $this->input->post("tindakan"), 
			// 				);
			// 		$this->db->insert("ekspertisi_gizi",$data);

					// $this->db->where("no_pasien",$this->input->post("no_pasien"));
					// $this->db->where("no_reg",$this->input->post("no_reg"));
					// $this->db->update("pasien_ralan",$data1);
				// 	break;
				// case 'edit':
				// 	$data = array(
				// 				'hasil_pemeriksaan' => $this->input->post("hasil_pemeriksaan"),
				// 			);
				// 	$this->db->where("no_pasien",$this->input->post("no_pasien"));
				// 	$this->db->where("no_reg",$this->input->post("no_reg"));
				// 	$this->db->where("kode_tindakan",$this->input->post("tindakan"));
				// 	$this->db->update("ekspertisi_gizi",$data);

					// $this->db->where("no_pasien",$this->input->post("no_pasien"));
					// $this->db->where("no_reg",$this->input->post("no_reg"));
					// $this->db->update("pasien_ralan",$data1);
					// break;
			
			return "success-Data berhasil disimpan";
		}
		function getekspertisi_detail($no_pasien,$no_reg,$id_tindakan){
			$this->db->where("no_pasien",$no_pasien);
			$this->db->where("no_reg",$no_reg);
			$this->db->where("kode_tindakan",$id_tindakan);
			$q = $this->db->get("ekspertisi_gizi");
			return $q->row();
		}
		function simpangizi(){
			$data = array(
								'no_foto'				=> $this->input->post("no_foto"), 
								'ukuran_foto'			=> $this->input->post("ukuran_foto"), 
								'dokter_poli' 			=> $this->input->post("dokter"), 
								'dokter_pengirim'		=> $this->input->post("dokter_pengirim"),
								'petugas_gizi' 			=> $this->input->post("petugas_gizi")
							);
					$this->db->where("no_pasien",$this->input->post("no_pasien"));
					$this->db->where("no_reg",$this->input->post("no_reg"));
					$this->db->update("pasien_ralan",$data);
			return "success-Data berhasil disimpan";
		}
		function getdokter_gizi(){
			$this->db->where("poli","0102036");
			return $this->db->get("dokter");
		}
		function getdokter(){
			return $this->db->get("dokter");
		}
		function getcetak($no_reg,$no_pasien){
	    	$this->db->select("p.*,jk.keterangan as jenis_kelamin,k.nama as status_kawin,pen.pendidikan as pendidikan,g.keterangan as golpas, pr.no_pasien as no_rekmed, pr.tanggal as trk, per.nama as nama_perusahaan, pr.alergi, pan.keterangan as pangkat, pr.no_foto, ek.hasil_pemeriksaan, d.nama_dokter as dokter, dok.nama_dokter as dokter_pengirim, pr.dari_poli, po.keterangan as polik, ru.nama_ruangan, tin.nama_tindakan, pr.tanggal as tglp, d.id_dokter ");
	    	$this->db->join("pasien_ralan pr","pr.no_pasien=p.no_pasien","left");
	    	$this->db->join("pasien_inap pi","pi.no_rm=p.no_pasien","left");
	    	$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin","left");
	    	$this->db->join("kawin k","k.kode=p.status_kawin","left");
			$this->db->join("kamar ka","ka.kode_kamar=pi.kode_kamar","left");
			$this->db->join("ruangan ru","ru.kode_ruangan=ka.kode_ruangan","left");
	    	$this->db->join("pendidikan pen","pen.idx=p.pendidikan","left");
	    	$this->db->join("poliklinik po","po.kode=pr.dari_poli","left");
	    	$this->db->join("dokter dok","dok.id_dokter=pr.dokter_pengirim","left");
	    	$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
	    	$this->db->join("ekspertisi_gizi ek","ek.no_reg = pr.no_reg","left");
	    	$this->db->join("tarif_gizi tin","tin.kode_tindakan=ek.kode_tindakan","left");
	    	$this->db->join("perusahaan per","per.kode = p.perusahaan","left");
	    	$this->db->join("pangkat pan","pan.id_pangkat = p.id_pangkat","left");
	    	$this->db->join("dokter d","d.id_dokter=pr.dokter_poli","left");
	    	$this->db->where("pr.no_reg",$no_reg);
	    	$this->db->where("pr.no_pasien",$no_pasien);
	    	$q = $this->db->get("pasien p");
	    	return $q->row();
	    }
	    function getcetak_inap($no_reg,$no_pasien,$id_tindakan,$tgl){
	    	$this->db->select("pi.*,p.nama_pasien,r.nama_ruangan,k.nama_kelas, p.tgl_lahir, tin.nama_tindakan,p.jenis_kelamin, g.keterangan as golpas, ek.hasil_pemeriksaan, r.nama_ruangan, d.nama_dokter, dk.nama_dokter as dok_pengirim, d.id_dokter");
			$this->db->join("pasien p","pi.no_rm=p.no_pasien");
			$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
			$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
			$this->db->join("kelas k","k.kode_kelas=pi.kode_kelas","left");
			$this->db->join("dokter d","d.id_dokter=pi.dokter_pa","left");
			$this->db->join("dokter dk","dk.id_dokter=pi.pengirim","left");
			$this->db->join("ekspertisi_giziinap ek","ek.no_reg = pi.no_reg","left");
	    	$this->db->join("tarif_gizi tin","tin.kode_tindakan=ek.kode_tindakan","left");
	    	$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
			$this->db->where("pi.no_rm",$no_pasien);
			$this->db->where("pi.no_reg",$no_reg);
			$this->db->where("ek.kode_tindakan",$id_tindakan);
			$this->db->where("ek.tanggal",$tgl);
			$q = $this->db->get("pasien_inap pi");
			return $q->row();
	    }
	    function getpetugasgizi(){
	    	return	$this->db->get("petugas_gizi");
	    }
	    function getdiet(){
	    	return $this->db->get("diet_makan_pasien");
	    }
	    function getmenu(){
	    	return $this->db->get("menu_makan_pasien");
	    }
	    function gettanggal($no_reg,$no_pasien,$id_tindakan,$tgl,$pemeriksaan){
	    	$this->db->select("tanggal as tglp");
	    	$this->db->where("no_reg",$no_reg);
	    	$this->db->where("tanggal",$tgl);
	    	$this->db->where("pemeriksaan",$pemeriksaan);
	    	$q = $this->db->get("kasir_inap");
	    	return $q->row();
	    }
	    function getgizi_inap(){
			$kode_kelas = $this->session->userdata("kode_kelas");
			$kode_ruangan = $this->session->userdata("kode_ruangan");
			$isi = $this->session->userdata("isi");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$nama = $this->session->userdata("nama");
			$this->db->select("i.*,r.nama_ruangan,k.nama_kelas");
			// if ($no_pasien!="") {
			// 	// $no_pasien = "000000".$no_pasien;
			// 	// $this->db->where("p.no_pasien",substr($no_pasien,-6));
			// 	$this->db->where("p.no_pasien",$no_pasien);
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
			if ($isi!="") {
				$this->db->where("i.tgl_keluar",NULL);
				// $this->db->where("i.status_pulang !=","1");
				$this->db->where("km.status_kamar",$isi);
			}
			if ($tgl1!="" OR $tgl2!="") {
				$this->db->where("i.tgl_masuk>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("i.tgl_masuk<=",date("Y-m-d",strtotime($tgl2)));
			}
			$this->db->order_by("no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=i.no_rm");
			$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
			$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
			$this->db->join("kamar km","km.kode_kamar=i.kode_kamar and km.kode_ruangan = i.kode_ruangan and km.kode_kelas = i.kode_kelas and km.no_bed = i.no_bed ","left");
			$this->db->order_by("no_reg,no_rm");
			$query = $this->db->get("pasien_inap i");
			return $query->num_rows();	
		}
		function getpasien_inap_gizi($page,$offset){
			$kode_kelas = $this->session->userdata("kode_kelas");
			$kode_ruangan = $this->session->userdata("kode_ruangan");
			$isi = $this->session->userdata("isi");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$nama = $this->session->userdata("nama");
			$this->db->select("i.*,r.nama_ruangan,k.nama_kelas, g.keterangan as gol_pasien");
			// if ($no_pasien!="") {
			// 	$no_pasien = "000000".$no_pasien;
			// 	$this->db->where("p.no_pasien",substr($no_pasien,-6));
			// 	// $this->db->where("p.no_pasien",$no_pasien);
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
			if ($isi!="") {
				$this->db->where("i.tgl_keluar",NULL);
				$this->db->where("km.status_kamar",$isi);
			}
			if ($tgl1!="" || $tgl2!="") {
				$this->db->where("i.tgl_masuk>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("i.tgl_masuk<=",date("Y-m-d",strtotime($tgl2)));
			}
			// $this->db->order_by("no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=i.no_rm");
			$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
			$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
			$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
			$this->db->join("kamar km","km.kode_kamar=i.kode_kamar and km.kode_ruangan = i.kode_ruangan and km.kode_kelas = i.kode_kelas and km.no_bed = i.no_bed ","left");
			$this->db->order_by("no_reg,no_rm");
			$query = $this->db->get("pasien_inap i",$page,$offset);
			return $query;
		}
		function getpasien_inap_makan($page,$offset){
			$kode_kelas = $this->session->userdata("kode_kelas");
			$kode_ruangan = $this->session->userdata("kode_ruangan");
			$isi = $this->session->userdata("isi");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$nama = $this->session->userdata("nama");
			$this->db->select("i.*,r.nama_ruangan,k.nama_kelas, wm.makan as waktu, w.tanggal as tgw, w.diet, w.menu");
			if ($no_pasien!="") {
				$no_pasien = "000000".$no_pasien;
				$this->db->where("p.no_pasien",substr($no_pasien,-6));
				// $this->db->where("p.no_pasien",$no_pasien);
			}
			if ($nama!="") {
				$this->db->like("p.nama_pasien",$nama);
			}
			if ($no_reg!="") {
				$this->db->where("i.no_reg",$no_reg);
			}
			if ($kode_kelas!="") {
				$this->db->where("i.kode_kelas",$kode_kelas);
			}
			if ($kode_ruangan!="") {
				$this->db->where("i.kode_ruangan",$kode_ruangan);
			}
			if ($isi!="") {
				$this->db->where("i.tgl_keluar",NULL);
				$this->db->where("km.status_kamar",$isi);
			}
			if ($tgl1!="" || $tgl2!="") {
				$this->db->where("i.tgl_masuk>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("i.tgl_masuk<=",date("Y-m-d",strtotime($tgl2)));
			}
			$this->db->order_by("kode_kamar","asc");
			$this->db->order_by("i.no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=i.no_rm");
			$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
			$this->db->join("makan_pasien w","w.no_reg = i.no_reg","left");
			$this->db->join("waktu_makan wm","wm.kode = w.waktu","left");
			$this->db->join("diet_makan_pasien dm","dm.kd = w.diet","left");
			$this->db->join("menu_makan_pasien mm","mm.kd = w.menu","left");
			$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
			$this->db->join("kamar km","km.kode_kamar=i.kode_kamar and km.kode_ruangan = i.kode_ruangan and km.kode_kelas = i.kode_kelas and km.no_bed = i.no_bed ","left");
			$this->db->order_by("i.no_reg,no_rm");
			$query = $this->db->get("pasien_inap i",$page,$offset);
			return $query;
		}
		function getinap_detail($no_pasien,$no_reg){
			$this->db->select("pi.*,p.nama_pasien,r.nama_ruangan,k.nama_kelas, d.nama_dokter,d.gizi, rad.nama as radio, dok.nama_dokter as peng");
			$this->db->join("pasien p","pi.no_rm=p.no_pasien");
			$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
			$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
			$this->db->join("kelas k","k.kode_kelas=pi.kode_kelas","left");
			$this->db->join("petugas_gizi rad","rad.nip=pi.petugas_gizi","left");
			$this->db->join("dokter d","d.id_dokter=pi.dokter_gizi","left");
			$this->db->join("dokter dok","dok.id_dokter=pi.pengirim","left");
			$this->db->where("pi.no_rm",$no_pasien);
			$this->db->where("pi.no_reg",$no_reg);
			$q = $this->db->get("pasien_inap pi");
			return $q->row();
		}
		function hapustindakan_inap(){
			$this->db->where("id",$this->input->post("id"));
			$this->db->delete("kasir_inap");
		}
		function simpanekspertisi_inap($action){
			$tindakan = explode("/", $this->input->post("tindakan"));
			$hasil = $this->input->post("hasil_pemeriksaan");
			foreach ($hasil as $key => $value) {
				$q = $this->db->get_where("ekspertisi_giziinap",["no_reg"=>$this->input->post("no_reg"),"kode_asuhan"=>$key, "tanggal"=>$tindakan[3], "pemeriksaan" => $tindakan[4]]);
				if ($q->num_rows()>0){
					$q = $q->row();
					$item = array(
						'hasil_pemeriksaan' => $value
					);
					// $this->db->where("no_pasien",$this->input->post("no_rm"));
				 // 	$this->db->where("no_reg",$this->input->post("no_reg"));
				 // 	$this->db->where("kode_tindakan",$tindakan[0]);
				 // 	$this->db->where("tanggal",$tindakan[3]);
				 // 	$this->db->where("pemeriksaan",$tindakan[4]);
				 // 	$this->db->update("ekspertisi_giziinap",$item);
					$this->db->where("id_ekspertisi",$q->id_ekspertisi);
					$this->db->update("ekspertisi_giziinap",$item);
				} else {
					$q = $this->db->get_where("asuhan_gizi",["kode"=>$key])->row();
					$pemeriksaan = $this->input->post("tanggal_pemeriksaan");
					$pem = explode("/", $pemeriksaan);
					$item = array(
								'no_pasien' => $this->input->post("no_rm"), 
								"no_reg" => $this->input->post("no_reg"),
			 					'kode_tindakan' => $tindakan[0],
								"kode_asuhan" => $q->kode,
			 					'tanggal' => $tindakan[3],
			 					'jam' => date("H:i:s"), 
			 					'pemeriksaan' => $tindakan[4], 
								"hasil_pemeriksaan" => $value
							);
					$this->db->insert("ekspertisi_giziinap",$item);
				}
			// $tindakan = explode("/", $this->input->post("tindakan"));
			// $data1 = array(
			// 				'no_foto' => $this->input->post("no_foto"), 
			// 				'ukuran_foto' => $this->input->post("ukuran_foto"), 
			// 				'dokter_pa' => $this->input->post("dokter_pa"), 
			// 				'petugas_gizi' => $this->input->post("petugas_gizi"), 
			// 				'pengirim' => $this->input->post("pengirim"), 
			// 			);
			// switch ($action) {
			// 	case 'simpan':
			// 		$data = array(
			// 					'no_pasien' => $this->input->post("no_rm"), 
			// 					'no_reg' => $this->input->post("no_reg"), 
			// 					'hasil_pemeriksaan' => $this->input->post("hasil_pemeriksaan"),
			// 					'kode_tindakan' => $tindakan[0],
			// 					'tanggal' => $tindakan[3], 
			// 					'pemeriksaan' => $tindakan[4], 
			// 				);
			// 		$this->db->insert("ekspertisi_giziinap",$data);
					// $this->db->where("no_rm",$this->input->post("no_rm"));
					// $this->db->where("no_reg",$this->input->post("no_reg"));
					// $this->db->update("pasien_inap",$data1);
				// 	break;
				// case 'edit':
				// 	$data = array(
				// 				'hasil_pemeriksaan' => $this->input->post("hasil_pemeriksaan"),
				// 			);
				// 	$this->db->where("no_pasien",$this->input->post("no_rm"));
				// 	$this->db->where("no_reg",$this->input->post("no_reg"));
				// 	$this->db->where("kode_tindakan",$tindakan[0]);
				// 	$this->db->where("tanggal",$tindakan[3]);
				// 	$this->db->where("pemeriksaan",$tindakan[4]);
				// 	$this->db->update("ekspertisi_giziinap",$data);
					// $this->db->where("no_rm",$this->input->post("no_rm"));
					// $this->db->where("no_reg",$this->input->post("no_reg"));
					// $this->db->update("pasien_inap",$data1);
			// 		break;
			}
			return "success-Data berhasil disimpan";
		}
		function getekspertisigiziinap_detail_array($no_reg,$tanggal,$pemeriksaan){
	    	$this->db->where("e.no_reg",$no_reg);
	    	if ($tanggal!="")
	    	$this->db->where("e.tanggal",$tanggal);
	    	if ($pemeriksaan!="")
	    	$this->db->where("e.pemeriksaan",$pemeriksaan);
	    	$q = $this->db->get("ekspertisi_giziinap e");
	    	$data = array();
	    	foreach ($q->result() as $row){
	    		$data[$row->kode_asuhan][$row->pemeriksaan] = $row;
	    	}
	    	return $data;
	    }
	    function getekspertisigizi_detail_array($no_reg,$tindakan){
	    	$this->db->where("e.no_reg",$no_reg);
	    	if ($tindakan!="")
	    	$this->db->where("e.kode_tindakan",$tindakan);
	    	$q = $this->db->get("ekspertisi_gizi e");
	    	$data = array();
	    	foreach ($q->result() as $row){
	    		$data[$row->kode_asuhan] = $row;
	    	}
	    	return $data;
	    }
		function getekspertisiinap_detail($no_pasien,$no_reg,$id_tindakan,$tanggal,$pemeriksaan){
			$this->db->where("no_pasien",$no_pasien);
			$this->db->where("no_reg",$no_reg);
			$this->db->where("kode_tindakan",$id_tindakan);
			$this->db->where("tanggal",$tanggal);
			$this->db->where("pemeriksaan",$pemeriksaan);
			$q = $this->db->get("ekspertisi_giziinap");
			return $q->row();
		}
		function changedata_ralan($jenis){
			if ($jenis=="petugas"){
				$data = array("kode_petugas"=>$this->input->post("value"));
				$this->db->where("id",$this->input->post("id"));
				$this->db->update("kasir",$data);
			} else 
			if ($jenis=="dokter_pengirim"){
				$data = array("dokter_pengirim"=>$this->input->post("value"));
				$this->db->where("id",$this->input->post("id"));
				$this->db->update("kasir",$data);
			} else 
			if ($jenis=="petugas_gizi"){
				$data = array("analys"=>$this->input->post("value"));
				$this->db->where("id",$this->input->post("id"));
				$this->db->update("kasir",$data);
			} else {
				$this->db->where("id",$this->input->post("id"));
				$this->db->update("kasir",[$jenis=>$this->input->post("value")]);
			}
		}
		function changedata($jenis){
			if ($jenis=="petugas"){
				$data = array("kode_petugas"=>$this->input->post("value"));
				$id = explode("/",$this->input->post("id"));
				$this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
				$this->db->where("pemeriksaan",$id[2]);
				$this->db->update("kasir_inap",$data);
			} else 
			if ($jenis=="dokter_pengirim"){
				$data = array("dokter_pengirim"=>$this->input->post("value"));
				$id = explode("/",$this->input->post("id"));
				$this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
				$this->db->where("pemeriksaan",$id[2]);
				$this->db->update("kasir_inap",$data);
			} else 
			if ($jenis=="petugas_gizi"){
				$data = array("analys"=>$this->input->post("value"));
				$id = explode("/",$this->input->post("id"));
				$this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
				$this->db->where("pemeriksaan",$id[2]);
				$this->db->update("kasir_inap",$data);
			} else {
				$this->db->where("id",$this->input->post("id"));
				$this->db->update("kasir_inap",[$jenis=>$this->input->post("value")]);
			}
		}
		function changedata_makan($jenis){
			if ($jenis=="diet"){
				$data = array("diet"=>$this->input->post("value"));
				// $id = explode("/",$this->input->post("id"));
				// $this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
				$this->db->where("no_reg",$this->input->post("id"));
				$this->db->update("makan_pasien",$data);
			} else 
			if ($jenis=="menu"){
				$data = array("menu"=>$this->input->post("value"));
				// $id = explode("/",$this->input->post("id"));
				// $this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
				$this->db->where("no_reg",$this->input->post("id"));
				$this->db->update("makan_pasien",$data);
			} else {
				$this->db->where("id",$this->input->post("id"));
				$this->db->update("makan_pasien");
			}
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
			$this->db->delete("ekspertisi_giziinap");
		}
		function hapusralan(){
			$this->db->where("id",$this->input->post("id"));
			$q = $this->db->get("kasir");
			$row = $q->row();
			$this->db->where("id",$this->input->post("id"));
			$this->db->delete("kasir");
			$this->db->where("no_reg",$row->no_reg);
			$this->db->where("kode_tindakan", $row->kode_tarif);
			$this->db->delete("ekspertisi_gizi");
		}
		function getdokter_array(){
			$this->db->select("id_dokter,nama_dokter");
			$this->db->where("poli","0102036");
			$q = $this->db->get("dokter");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->id_dokter] = $key->nama_dokter;
			}
			return $data;
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
		function getpetugas_array(){
			$this->db->select("nip,nama");
			$q = $this->db->get("petugas_gizi");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->nip] = $key->nama;
			}
			return $data;
		}
		function getdiet_array(){
			$this->db->select("kd,ket");
			$q = $this->db->get("diet_makan_pasien");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->kd] = $key->kd;
			}
			return $data;
		}
		function getmenu_array(){
			$this->db->select("kd,ket");
			$q = $this->db->get("menu_makan_pasien");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->kd] = $key->kd;
			}
			return $data;
		}
		function getnoreg_array(){
			$this->db->select("kd,ket");
			$q = $this->db->get("menu_makan_pasien");
			$data = array();
			foreach ($q->result() as $key) {
				$data[$key->kd] = $key->ket;
			}
			return $data;
		}
		function getasuhan($no_reg,$tanggal="",$pemeriksaan=""){
	    	$this->db->select("k.*,l.*,t.nama_tindakan, l.nama as asuhan");
	    	$this->db->join("asuhan_gizi l","l.kode_tindakan=k.kode_tarif");
	    	$this->db->join("tarif_gizi t","t.kode_tindakan=k.kode_tarif");
	    	$this->db->join("pasien_inap pi","pi.no_reg=k.no_reg");
	    	// $this->db->join("ekspertisi_giziinap e","e.no_reg = k.no_reg and k.tanggal = e.tanggal and k.pemeriksaan = e.pemeriksaan");
	    	$this->db->where("k.no_reg",$no_reg);
	    	if ($tanggal!="")
	    	$this->db->where("k.tanggal",$tanggal);
	    	if ($pemeriksaan!="")
	    	$this->db->where("k.pemeriksaan",$pemeriksaan);
	    	$this->db->order_by("k.tanggal,k.kode_tarif,l.kode");
	    	$q = $this->db->get("kasir_inap k");
	    	return $q;
	    }
	    function getasuhan_ralan($no_reg,$tindakan=""){
	    	$this->db->select("k.*,l.*,t.nama_tindakan, l.nama as asuhan");
	    	$this->db->join("asuhan_gizi l","l.kode_tindakan=k.kode_tarif");
	    	$this->db->join("tarif_gizi t","t.kode_tindakan=k.kode_tarif");
	    	$this->db->join("pasien_ralan pi","pi.no_reg=k.no_reg");
	    	// $this->db->join("ekspertisi_giziinap e","e.no_reg = k.no_reg and k.tanggal = e.tanggal and k.pemeriksaan = e.pemeriksaan");
	    	$this->db->where("k.no_reg",$no_reg);
	    	if ($tindakan!="")
	    	$this->db->where("k.kode_tarif",$tindakan);
	    	$this->db->order_by("k.kode_tarif,l.kode");
	    	$q = $this->db->get("kasir k");
	    	return $q;
	    }
	    function gethasuhan($no_reg, $tanggal, $pemeriksaan){
	    	$this->db->select("e.hasil_pemeriksaan");
	    	$this->db->where("k.no_reg",$no_reg);
	    	if ($tanggal!="")
	    	$this->db->where("k.tanggal",$tanggal);
	    	if ($pemeriksaan!="")
	    	$this->db->where("k.pemeriksaan",$pemeriksaan);
	    	$this->db->join("kasir_inap k","e.no_reg = k.no_reg and k.tanggal = e.tanggal and k.pemeriksaan = e.pemeriksaan");
	    	$this->db->join("asuhan_gizi a","a.kode = e.kode_asuhan");
	    	// if("e.kode_asuhan" == "001")
	    	// $this->db->where("e.kode_asuhan", "001");
	    	// if("e.kode_asuhan" == "002")
	    	// $this->db->where("e.kode_asuhan", "002");
	    	// if("e.kode_asuhan" == "003")
	    	// $this->db->where("e.kode_asuhan", "003");
	    	// if("e.kode_asuhan" == "004")
	    	// $this->db->where("e.kode_asuhan", "004");
	    	// if("e.kode_asuhan" == "005")
	    	// $this->db->where("e.kode_asuhan", "005");
	    	// if("e.kode_asuhan" == "006")
	    	// $this->db->where("e.kode_asuhan", "006");
	    	// if("e.kode_asuhan" == "007")
	    	// $this->db->where("e.kode_asuhan", "007");
	    	// if("e.kode_asuhan" == "008")
	    	// $this->db->where("e.kode_asuhan", "008");
	    	$this->db->where("e.kode_asuhan", "008");
	    	$q =$this->db->get("ekspertisi_giziinap e");
	    	return $q->row();
	    }
		function getcetak_kasir($no_reg,$kode_tindakan){
			$this->db->select("d.nama_dokter,k.nofoto,d1.nama_dokter as dokter_pengirim");
			$this->db->join("dokter d","d.id_dokter=k.kode_petugas","left");
			$this->db->join("dokter d1","d1.id_dokter=k.dokter_pengirim","left");
			$q = $this->db->get_where("kasir k",["no_reg"=>$no_reg,"kode_tarif"=>$kode_tindakan]);
			return $q->row();
		}
		function getcetak_kasir_inap($no_reg,$id_tindakan,$tgl,$pemeriksaan){
			$this->db->select("d.nama_dokter,k.nofoto,d1.nama_dokter as dokter_pengirim");
			$this->db->join("dokter d","d.id_dokter=k.kode_petugas","left");
			$this->db->join("dokter d1","d1.id_dokter=k.dokter_pengirim","left");
			$q = $this->db->get_where("kasir_inap k",["no_reg"=>$no_reg,"kode_tarif"=>$id_tindakan,"tanggal"=>date("Y-m-d",strtotime($tgl)),"pemeriksaan"=>$pemeriksaan]);
			return $q->row();
		}
		function getmakan(){
			return $this->db->get("waktu_makan");
		}
		function getisi(){
			$this->db->select("status_kamar");
			// $this->db->where("status_kamar","ISI");
			return $this->db->get("kamar");
		}
		function getcetakmakan(){
			$kode_kelas = $this->session->userdata("kode_kelas");
			$kode_ruangan = $this->session->userdata("kode_ruangan");
			$isi = $this->session->userdata("isi");
			$tgl1 = $this->session->userdata("tgl1");
			$tgl2 = $this->session->userdata("tgl2");
			$no_pasien = $this->session->userdata("no_pasien");
			$no_reg = $this->session->userdata("no_reg");
			$nama = $this->session->userdata("nama");
			$this->db->select("i.*,r.nama_ruangan,k.nama_kelas, km.kode_kamar,p.tgl_lahir,p.jenis_kelamin, i.no_rm, km.no_bed, wm.makan as waktu, w.tanggal as tgw, w.diet, w.menu");
			if ($no_pasien!="") {
				$no_pasien = "000000".$no_pasien;
				$this->db->where("p.no_pasien",substr($no_pasien,-6));
				// $this->db->where("p.no_pasien",$no_pasien);
			}
			if ($nama!="") {
				$this->db->like("p.nama_pasien",$nama);
			}
			if ($no_reg!="") {
				$this->db->where("no_reg",$no_reg);
			}
			if ($kode_kelas!="") {
				$this->db->where("i.kode_kelas",$kode_kelas);
			}
			if ($kode_ruangan!="") {
				$this->db->where("i.kode_ruangan",$kode_ruangan);
			}
			if ($isi!="") {
				$this->db->where("i.tgl_keluar",NULL);
				$this->db->where("km.status_kamar",$isi);
			}
			if ($tgl1!="" || $tgl2!="") {
				$this->db->where("i.tgl_masuk>=",date("Y-m-d",strtotime($tgl1)));
	            $this->db->where("i.tgl_masuk<=",date("Y-m-d",strtotime($tgl2)));
			}
			$this->db->order_by("i.kode_kamar","asc");
			$this->db->order_by("no_reg","desc");
			$this->db->join("pasien p","p.no_pasien=i.no_rm");
			$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
			$this->db->join("makan_pasien w","w.no_reg = i.no_reg","left");
			$this->db->join("waktu_makan wm","wm.kode = w.waktu","left");
			$this->db->join("diet_makan_pasien dm","dm.kd = w.diet","left");
			$this->db->join("menu_makan_pasien mm","mm.kd = w.menu","left");
			$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
			$this->db->join("kamar km","km.kode_kamar=i.kode_kamar and km.kode_ruangan = i.kode_ruangan and km.kode_kelas = i.kode_kelas and km.no_bed = i.no_bed ","left");
			$this->db->order_by("no_reg,no_rm");
			$query = $this->db->get("pasien_inap i");
			return $query;
		}
		function hapusmakan(){
			$y = $this->getpasien_inap_makan('','');
			// $data = array();
			foreach ($y->result() as $key) {
					$this->db->where("no_reg",$key->no_reg);
					$this->db->delete("makan_pasien");
			}
		}
		function rekap_ralan_full($tindakan,$tgl1="",$tgl2=""){
			$data = array();
			$tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
			$tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
			$this->db->select("k.kode_tarif,k.asal,p.status_pasien,p.jenis,p.gol_pasien,p.tujuan_poli");
			$this->db->where("layan!=",2);
			if ($tindakan!="all") {
				$this->db->where("k.kode_tarif",$tindakan);
			} else {
				$this->db->like("k.kode_tarif","G",'after');
			}
			$this->db->where("date(p.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(p.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->where("p.tujuan_poli","010236");
			$this->db->join("kasir k","k.no_reg=p.no_reg","inner");
			// $this->db->order_by("jumlah","desc");
			// $this->db->group_by("kode_tarif");
			$sql = $this->db->get("pasien_ralan p");
			foreach ($sql->result() as $key) {
				if ($key->kode_tarif=="G001" ){
					$s = $this->db->get_where("ekspertisi_gizi",["no_reg"=>$key->no_reg,"kode_tindakan"=>$key->kode_tarif]);
					if ($s->num_rows()>0)
						$data["EKS"][$key->kode_tarif] += 1;
					else
						$data["EKS"][$key->kode_tarif] = 1;
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
				// if ($key->jam_radiologi!="0000-00-00 00:00:00"){
				// 	if (isset($data["EKS"][$key->kode_tarif]))
				// 	$data["EKS"][$key->kode_tarif] += 1;
				// 	else
				// 	$data["EKS"][$key->kode_tarif] = 1;
				// }
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
			$this->db->select("k.kode_tarif,k.asal,pa.id_gol");
			if ($tindakan!="all") {
				$this->db->where("k.kode_tarif",$tindakan);
			} else {
				$this->db->like("k.kode_tarif","G",'after');
			}
			$this->db->where("date(k.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(k.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->join("kasir_inap k","k.no_reg=p.no_reg","inner");
			$this->db->join("pasien pa","pa.no_pasien = p.no_rm","left");
			// $this->db->order_by("jumlah","desc");
			// $this->db->group_by("kode_tarif");
			$sql = $this->db->get("pasien_inap p");
			foreach ($sql->result() as $key) {
				if ($key->kode_tarif=="G001"){
					$s = $this->db->get_where("ekspertisi_giziinap",["no_reg"=>$key->no_reg,"kode_tindakan"=>$key->kode_tarif]);
					if ($s->num_rows()>0)
						$data["PEMERIKSAAN"][$key->kode_tarif] += 1;
					else
						$data["PEMERIKSAAN"][$key->kode_tarif] = 1;
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
				// if ($key->jam_radiologi!="0000-00-00 00:00:00"){
				// 	if (isset($data["PEMERIKSAAN"][$key->kode_tarif]))
				// 	$data["PEMERIKSAAN"][$key->kode_tarif] += 1;
				// 	else
				// 	$data["PEMERIKSAAN"][$key->kode_tarif] = 1;
				// }
			}
			return $data;
		}
		function getpasien_rekap_full($tindakan,$tgl1,$tgl2){
			$data = array();
			//ralan
			$this->db->select("k.pemeriksaan,pr.tanggal,pr.no_pasien,pr.no_reg,pr.tujuan_poli,pr.dari_poli,pr.dokter_pengirim,pr.layan");
			$this->db->where("pr.tujuan_poli","0102036");
			$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
			$this->db->where("k.kode_tarif",$tindakan);
			$this->db->join("kasir k","k.no_reg=pr.no_reg","inner");
			//$this->db->order_by("pr.no_reg");
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
    //             $q = $this->db->order_by('hasil','desc')->get_where("ekspertisi_lab",["no_reg"=>$row->no_reg , "kode_tindakan"=>"$tindakan" ]);
    //             //$q = $this->db->get_where("ekspertisi_lab",["no_reg"=>$row->no_reg]);
				// $data["ekspertisi_lab"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
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
    //             $q = $this->db->order_by('hasil','desc')->get_where("ekspertisi_labinap",["no_reg"=>$row->no_reg , "kode_tindakan"=>"$tindakan" ]);
				// $data["ekspertisi_lab"][$row->no_reg] = ($q->num_rows()>0 ? $q->row() : "");
			}
			return $data;
		}
		function gettindakan_cetak($tindakan){
			if ($tindakan != "all") {
				$this->db->where("kode_tindakan", $tindakan);
			}
			$q = $this->db->get("tarif_gizi");
			return $q;
		}
		function gettindakan_cetak2($tindakan){
			if ($tindakan != "all") {
				$this->db->where("kode_tindakan", $tindakan);
			}
			$q = $this->db->get("tarif_gizi");
			return $q->row();
		}
		// function getcetak_makan(){
		// $y = $this->getpasien_inap_makan('','');
		// 	// $data = array();
  //   	$this->db->select("p.*,pa.nama_pasien ,d.nama_dokter, pol.keterangan as nama_poli");
  //   	$this->db->join("dokter d","d.id_dokter=p.dokter_poli","inner");
  //       $this->db->join("pasien pa","pa.no_pasien=p.no_pasien","left");
  //       $this->db->join("poliklinik pol","pol.kode=p.tujuan_poli","left");
  //    //    foreach ($y->result() as $key) {
  //   		$q = $this->db->get_where("pasien_ralan p",["no_reg"=>$key->no_reg]);
  //   	// }
  //   	return $q;
    // }
		
	}
?>