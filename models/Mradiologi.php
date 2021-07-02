<?php
class Mradiologi extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}
	function getpasien_ralan_radiologi2(){
		$row = $this->input->post("row");
		$data = array();
		foreach ($row as $key => $value) {
			$this->db->like("kode_tarif","R","after");
			$this->db->where("no_reg",$value);
			$query = $this->db->get("kasir");
			$data["detail"][$value] = $query->num_rows();
			$this->db->where("no_reg",$value);
			$query = $this->db->get("ekspertisi");
			$data["ekspertisi"][$value] = $query->num_rows();
		}
		return $data;
	}
	function getpasien_inap_radiologi1(){
		$row = $this->input->post("row");
		$data = array();
		foreach ($row as $key => $value) {
			$this->db->like("kode_tarif","R","after");
			$this->db->where("no_reg",$value);
			$query = $this->db->get("kasir_inap");
			$data["detail"][$value] = $query->num_rows();
			$this->db->where("no_reg",$value);
			$query = $this->db->get("ekspertisi_radinap");
			$data["ekspertisi"][$value] = $query->num_rows();
		}
		return $data;
	}
	function getpasien_ralan_radiologi($page,$offset){
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
		$this->db->where("pr.tujuan_poli","0102025");
		$query = $this->db->get("pasien_ralan pr",$page,$offset);
		return $query;
	}
	function getradiologi_ralan(){
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
		$this->db->where("pr.tujuan_poli","0102025");
		$query = $this->db->get("pasien_ralan pr");
		return $query->num_rows();
	}
	function getrespond($no_pasien,$no_reg){
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		if ($tgl1!="" OR $tgl2!="") {
			$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
		}
		if ($tgl1=="" OR $tgl2=="") {
			$this->db->where("pr.no_reg",$no_reg);
		}
		$this->db->select("pr.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, kas.terima_radiologi, kas.periksa_radiologi, kas.jam_radiologi");
		$this->db->group_by("no_reg");
		$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
		$this->db->join("kasir kas", "kas.no_reg = pr.no_reg","left");
		$this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli");
		$this->db->like("kas.kode_tarif","R");
		$this->db->where("pr.tujuan_poli","0102025");
		$query = $this->db->get("pasien_ralan pr");
		return $query;
	}
	function pilihdokterradiologi(){
		$this->db->select("dokter.*, k.nama_kelompok");
		$this->db->join("kelompok_dokter k","k.id_kelompok = dokter.kelompok_dokter","left");
		$this->db->where("poli","0102025");
		return $this->db->get("dokter");
	}
	function terima($no_rm,$no_reg){
		// $this->db->where("no_pasien",$no_rm);
		$this->db->where("no_reg",$no_reg);
		$q = $this->db->get("kasir")->row();
		if ($q->terima_radiologi === "0000-00-00 00:00:00") {
			$data = array('terima_radiologi' => date("Y-m-d H:i:s"));
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
		if ($q->periksa_radiologi === "0000-00-00 00:00:00") {
			$data = array('periksa_radiologi' => date("Y-m-d H:i:s"));
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
		if ($q->periksa_radiologi === "0000-00-00 00:00:00") {
			$data = array('periksa_radiologi' => date("Y-m-d H:i:s"));
			// $this->db->where("no_pasien",$no_rm);
			$this->db->where("no_reg",$no_reg);
			$this->db->update("kasir_inap",$data);
			return "success-Berkas diperiksa";
		} else {
			return "danger-Berkas sudah pernah diperiksa";
		}
	}
	function getralan_detail($no_pasien,$no_reg){
		$this->db->select("pr.*,p.tgl_lahir,p.alamat,p.nama_pasien,pl.keterangan as poli,jk.keterangan jenis_kelamin,d.nama_dokter,d.radiologi");
		$this->db->join("pasien p","pr.no_pasien=p.no_pasien");
		$this->db->join("poliklinik pl","pl.kode=pr.tujuan_poli");
		$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin","left");
		$this->db->join("dokter d","d.id_dokter=pr.dokter_poli","left");
		$this->db->where("pr.no_pasien",$no_pasien);
		$this->db->where("pr.no_reg",$no_reg);
		$q = $this->db->get("pasien_ralan pr");
		return $q->row();
	}
	function gettarif_radiologi(){
		return $this->db->get("tarif_radiologi");
	}
	function addtindakan(){
		$t = $this->db->get_where("tarif_radiologi",["id_tindakan" => $this->input->post("tindakan")]);
		if ($t->num_rows()>0){
			$data = $t->row();
			if ($this->input->post('jenis')=="E") $tarif = $data->executive; else $tarif = $data->reguler;
			$data = array(
				"id" => date("dmyHis"),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => $this->input->post("tindakan"),
				"nofoto" => $this->input->post("nofoto"),
				"ukuranfoto" => $this->input->post("ukuranfoto"),
				"kode_petugas" => $this->input->post("dokter_radiologi"),
				"analys" => $this->input->post("radiografer"),
				"dokter_pengirim" => $this->input->post("dokter_pengirim"),
				"terima_radiologi" =>  date("Y-m-d H:i:s"),
				"diagnosa" => $this->input->post("diagnosa"),
				"jumlah" => $tarif
			);
			$this->db->insert("kasir",$data);
			$item = array("");
		}
		$q = $this->db->get_where("pasien_ralan",["no_reg"=>$this->input->post("no_reg"),"keadaan_pulang"=>""]);
		if ($q->num_rows()>0){
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->update("pasien_ralan",["keadaan_pulang"=>"2"]);
		}
	}
	function addtindakan_inap(){
		$kelas = $this->input->post("kode_kelas");
		$t = $this->db->get_where("tarif_radiologi",["id_tindakan" => $this->input->post("tindakan")]);

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
				case '10':
				$tarif = $row->nicu;
				break;
				case '11':
				$tarif = $row->isolasi;
				break;
				case '12':
				$tarif = $row->bayi;
				break;
				case '13':
				$tarif = $row->odc;
				break;
			}
			$data = array(
				"id" => date("dmyHis"),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => $this->input->post("tindakan"),
				"pemeriksaan" => $this->input->post("pemeriksaan"),
				"kode_petugas" => $this->input->post("dokter_radiologi"),
				"analys" => $this->input->post("radiografer"),
				"dokter_pengirim" => $this->input->post("dokter_pengirim"),
				"asal" => $this->input->post("asal"),
				"terima_radiologi" =>  date("Y-m-d H:i:s"),
				"qty" => 1,
				"tanggal" => $tanggal,
				"jumlah" => $tarif,
				"diagnosa" => $this->input->post("diagnosa")
			);
			$this->db->insert("kasir_inap",$data);

		}
	}
	function getkasir($no_reg){
		$this->db->join("tarif_radiologi t","t.id_tindakan=k.kode_tarif","inner");
		$q = $this->db->get_where("kasir k",["k.no_reg" => $no_reg]);
		return $q;
	}
	function cekkasir_detail(){
		$this->db->where("k.no_reg",$this->input->post("no_reg"));
		$this->db->group_start();
		$this->db->where("k.kode_petugas","");
		$this->db->or_where("k.kode_petugas IS NULL",NULL,FALSE);
		$this->db->or_where("k.nofoto","");
		$this->db->or_where("k.nofoto IS NULL",NULL,FALSE);
		$this->db->or_where("k.ukuranfoto","");
		$this->db->or_where("k.ukuranfoto IS NULL",NULL,FALSE);
		$this->db->or_where("k.diagnosa","");
		$this->db->or_where("k.diagnosa IS NULL",NULL,FALSE);
		$this->db->group_end();
		$this->db->join("tarif_radiologi t","t.id_tindakan=k.kode_tarif","inner");
		$q = $this->db->get("kasir k");
		return $q;
	}
	function cekkasirinap_detail(){
		$this->db->where("k.no_reg",$this->input->post("no_reg"));
		$this->db->group_start();
		$this->db->where("k.kode_petugas","");
		$this->db->or_where("k.kode_petugas IS NULL",NULL,FALSE);
		$this->db->or_where("k.nofoto","");
		$this->db->or_where("k.nofoto IS NULL",NULL,FALSE);
		$this->db->or_where("k.ukuranfoto","");
		$this->db->or_where("k.ukuranfoto IS NULL",NULL,FALSE);
		$this->db->or_where("k.diagnosa","");
		$this->db->or_where("k.diagnosa IS NULL",NULL,FALSE);
		$this->db->group_end();
		$this->db->join("tarif_radiologi t","t.id_tindakan=k.kode_tarif","inner");
		$q = $this->db->get("kasir_inap k");
		return $q;
	}
	function getkasir_detail($no_reg,$kode_tindakan){
		$q = $this->db->get_where("kasir",["no_reg" => $no_reg,"kode_tarif"=> $kode_tindakan]);
		return $q->row();
	}
	function getkasir_inap($no_reg,$tgl){
		$this->db->join("tarif_radiologi t","t.id_tindakan=k.kode_tarif","inner");
		if ($tgl!="") {
			$this->db->where("tanggal",date("Y-m-d",strtotime($tgl)));
		}
		$this->db->where("k.no_reg",$no_reg);
		$this->db->order_by("k.tanggal","desc");
		$this->db->join("dokter d","d.id_dokter=k.kode_petugas","left");
		$q = $this->db->get("kasir_inap k");
		return $q;
	}
	function getkasir_inap_detail($no_reg,$id_tindakan,$tgl,$pemeriksaan){
		$this->db->select("k.*,d.nama_dokter,r.nama_perawat as nama,d.radiologi,dp.nama_dokter as dokter_pengirim");
		$this->db->where("tanggal",date("Y-m-d",strtotime($tgl)));
		$this->db->where("k.no_reg",$no_reg);
		$this->db->where("k.kode_tarif",$id_tindakan);
		$this->db->where("k.pemeriksaan",$pemeriksaan);
		$this->db->join("dokter d","d.id_dokter=k.kode_petugas","left");
		$this->db->join("dokter dp","dp.id_dokter=k.dokter_pengirim","left");
		$this->db->join("perawat r","r.id_perawat=k.analys","left");
		$q = $this->db->get("kasir_inap k");
		return $q->result();
	}
	function ambildatanormal($tabel){
		$this->db->order_by("kode");
		return $this->db->get($tabel);
	}
	function getekspertisiradinap_detail_array($no_reg,$tanggal=""){
		$this->db->select("id_tindakan,pemeriksaan,hasil_pemeriksaan,tanggal");
		$this->db->where("e.no_reg",$no_reg);
		if ($tanggal!="")
		$this->db->where("e.tanggal",$tanggal);
		$q = $this->db->get("ekspertisi_radinap e");
		$data = array();
		foreach ($q->result() as $row){
			$data[$row->id_tindakan][$row->pemeriksaan][$row->tanggal] = $row->hasil_pemeriksaan;
		}
		return $data;
	}
	function simpanekspertisi($action){
		$data1 = array(
			'dokter_pengirim' => $this->input->post("dokter_pengirim"),
			'no_foto' => $this->input->post("no_foto"),
			'ukuran_foto' => $this->input->post("ukuran_foto"),
			'dokter_poli' => $this->input->post("dokter"),
			'petugas_radiologi' => $this->input->post("petugas_radiologi"),
		);
		switch ($action) {
			case 'simpan':
			$data = array(
				'no_pasien' => $this->input->post("no_pasien"),
				'no_reg' => $this->input->post("no_reg"),
				'hasil_pemeriksaan' => $this->input->post("hasil_pemeriksaan"),
				'id_tindakan' => $this->input->post("tindakan"),
			);
			$this->db->insert("ekspertisi",$data);
			$item1 = array("jam_radiologi" => date("Y-m-d H:i:s"));
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update("kasir",$item1);

			// $this->db->where("no_pasien",$this->input->post("no_pasien"));
			// $this->db->where("no_reg",$this->input->post("no_reg"));
			// $this->db->update("pasien_ralan",$data1);
			break;
			case 'edit':
			$data = array(
				'hasil_pemeriksaan' => $this->input->post("hasil_pemeriksaan"),
			);
			$this->db->where("no_pasien",$this->input->post("no_pasien"));
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->where("id_tindakan",$this->input->post("tindakan"));
			$this->db->update("ekspertisi",$data);

			// $this->db->where("no_pasien",$this->input->post("no_pasien"));
			// $this->db->where("no_reg",$this->input->post("no_reg"));
			// $this->db->update("pasien_ralan",$data1);
			break;
		}
		return "success-Data berhasil disimpan";
	}
	function getekspertisi_detail($no_pasien,$no_reg,$id_tindakan){
		$this->db->where("no_pasien",$no_pasien);
		$this->db->where("no_reg",$no_reg);
		$this->db->where("id_tindakan",$id_tindakan);
		$q = $this->db->get("ekspertisi");
		return $q->row();
	}
	function simpanradiologi(){
		$data = array(
			'no_foto'				=> $this->input->post("no_foto"),
			'ukuran_foto'			=> $this->input->post("ukuran_foto"),
			'dokter_poli' 			=> $this->input->post("dokter"),
			'dokter_pengirim'		=> $this->input->post("dokter_pengirim"),
			'petugas_radiologi' 	=> $this->input->post("petugas_radiologi")
		);
		$this->db->where("no_pasien",$this->input->post("no_pasien"));
		$this->db->where("no_reg",$this->input->post("no_reg"));
		$this->db->update("pasien_ralan",$data);
		return "success-Data berhasil disimpan";
	}
	function getdokter_radiologi(){
		$this->db->select("d.*");
		$this->db->join("jadwal_dokter j","j.id_dokter=d.id_dokter","inner");
		$this->db->where("id_poli","0102025");
		return $this->db->get("dokter d");
	}
	function getdokter(){
		return $this->db->get("dokter");
	}
	function getukuranfoto(){
		// $this->db->where("asal",$asal);
		return $this->db->get("asal_foto");
	}
	function getcetak($no_reg,$no_pasien){
		$this->db->select("p.*,jk.keterangan as jenis_kelamin,k.nama as status_kawin,pen.pendidikan as pendidikan,g.keterangan as golpas, pr.no_pasien as no_rekmed, pr.tanggal as trk, per.nama as nama_perusahaan, pr.alergi, pan.keterangan as pangkat, pr.no_foto, ek.hasil_pemeriksaan, d.nama_dokter as dokter, ek.kesan, dok.nama_dokter as dokter_pengirim, pr.dari_poli,pr.diagnosa, po.keterangan as polik, ru.nama_ruangan, tin.nama_tindakan, pr.tanggal as tglp, d.id_dokter");
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
		$this->db->join("ekspertisi ek","ek.no_reg = pr.no_reg","left");
		$this->db->join("tarif_radiologi tin","tin.id_tindakan=ek.id_tindakan","left");
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
		$this->db->join("dokter d","d.id_dokter=pi.dokter_radiologi","left");
		$this->db->join("dokter dk","dk.id_dokter=pi.pengirim","left");
		$this->db->join("ekspertisi_radinap ek","ek.no_reg = pi.no_reg","left");
		$this->db->join("tarif_radiologi tin","tin.id_tindakan=ek.id_tindakan","left");
		$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
		$this->db->where("pi.no_rm",$no_pasien);
		$this->db->where("pi.no_reg",$no_reg);
		$this->db->where("ek.id_tindakan",$id_tindakan);
		$this->db->where("ek.tanggal",$tgl);
		$q = $this->db->get("pasien_inap pi");
		return $q->row();
	}
	function getradiografer(){
		$this->db->select("id_perawat as nip,nama_perawat as nama");
		return	$this->db->get_where("perawat",["bagian"=>"0102025"]);
	}
	function gettanggal($no_reg,$no_pasien,$id_tindakan,$tgl,$pemeriksaan){
		$this->db->select("tanggal as tglp");
		$this->db->where("no_reg",$no_reg);
		$this->db->where("tanggal",$tgl);
		$this->db->where("pemeriksaan",$pemeriksaan);
		$q = $this->db->get("kasir_inap");
		return $q->row();
	}
	function getradiologi_inap(){
		$kode_kelas = $this->session->userdata("kode_kelas");
		$kode_ruangan = $this->session->userdata("kode_ruangan");
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$no_pasien = $this->session->userdata("no_pasien");
		$no_reg = $this->session->userdata("no_reg");
		$nama = $this->session->userdata("nama");
		$detail1 = $this->session->userdata("detail1");
		$ekspertisi1 = $this->session->userdata("ekspertisi1");
		$this->db->select("i.*,r.nama_ruangan,k.nama_kelas, g.keterangan as gol_pasien");
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
		$this->db->or_like("i.no_reg",$no_pasien);
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
		if ($detail1!="") {
			$this->db->where("ka.terima_radiologi !=", "0000-00-00 00:00:00");
			$this->db->join("kasir_inap ka","ka.no_reg= i.no_reg","left");
			$this->db->group_by("ka.no_reg");
		}
		if ($ekspertisi1!="") {
			$this->db->where("i.ekspertisi_radinap", NULL);
		}
		$this->db->order_by("i.no_reg","desc");
		$this->db->join("pasien p","p.no_pasien=i.no_rm");
		$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
		$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
		$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
		$this->db->order_by("i.no_reg,no_rm");
		$query = $this->db->get("pasien_inap i");
		return $query->num_rows();
	}
	function getpasien_inap_radiologi($page,$offset){
		$kode_kelas = $this->session->userdata("kode_kelas");
		$kode_ruangan = $this->session->userdata("kode_ruangan");
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$no_pasien = $this->session->userdata("no_pasien");
		$no_reg = $this->session->userdata("no_reg");
		$nama = $this->session->userdata("nama");
		$detail1 = $this->session->userdata("detail1");
		$ekspertisi1 = $this->session->userdata("ekspertisi1");
		$this->db->select("i.*,p.nama_pasien,r.nama_ruangan,k.nama_kelas, g.keterangan as gol_pasien");
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
		$this->db->or_like("i.no_reg",$no_pasien);
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
		if ($tgl1!="" || $tgl2!="") {
			$this->db->where("i.tgl_masuk>=",date("Y-m-d",strtotime($tgl1)));
			$this->db->where("i.tgl_masuk<=",date("Y-m-d",strtotime($tgl2)));
		}
		if ($detail1!="") {
			$this->db->where("ka.terima_radiologi !=", "0000-00-00 00:00:00");
			$this->db->join("kasir_inap ka","ka.no_reg= i.no_reg","left");
			$this->db->group_by("ka.no_reg");
			// $this->db->where($this->Mradiologi->getpasien_inap_radiologi3(""));
		}
		if ($ekspertisi1!="") {
			$this->db->where("i.ekspertisi_radinap", NULL);
		}
		if ($this->session->userdata("dokter")!="") {
			$this->db->where("i.dokter", $this->session->userdata("dokter"));
		}
		$this->db->join("pasien p","p.no_pasien=i.no_rm");
		$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
		$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
		$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
		// $this->db->join("kasir_inap ka","ka.no_reg= i.no_reg","left");
		$this->db->order_by("i.no_reg","desc");
		// $this->db->group_by("ka.no_reg");
		$query = $this->db->get("pasien_inap i",$page,$offset);
		return $query;
	}
	function getpasien_inap_radiologi2($no_reg){
		$this->db->where("no_reg",$no_reg);
		$this->db->like("kode_tarif","R","after");
		// $this->db->or_like("kode_tarif","R1");
		// $this->db->or_like("kode_tarif","R2");
		// $this->db->where("kode_tarif !=","FRM");
		$this->db->group_by("no_reg");
		$query = $this->db->get("kasir_inap");
		return $query->num_rows();
	}
	function getpasien_inap_radiologi3($no_reg){
		$this->db->where("no_reg",$no_reg);
		$this->db->group_by("no_reg");
		$query = $this->db->get("ekspertisi_radinap");
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
		$this->db->select("i.*,r.nama_ruangan,k.nama_kelas, kas.jam_radiologi, kas.terima_radiologi, kas.periksa_radiologi");
		$this->db->join("pasien p","p.no_pasien=i.no_rm");
		$this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
		$this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
		$this->db->join("kasir_inap kas","kas.no_reg=i.no_reg","left");
		$this->db->like("kas.kode_tarif","R");
		$this->db->group_by("no_reg");
		$query = $this->db->get("pasien_inap i");
		return $query;
	}
	function getinap_detail($no_pasien,$no_reg){
		$this->db->select("pi.*,p.tgl_lahir,p.nama_pasien,r.nama_ruangan,k.nama_kelas, d.nama_dokter,d.radiologi, rad.nama_perawat as radio, dok.nama_dokter as peng");
		$this->db->join("pasien p","pi.no_rm=p.no_pasien");
		$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
		$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
		$this->db->join("kelas k","k.kode_kelas=pi.kode_kelas","left");
		$this->db->join("perawat rad","rad.id_perawat=pi.radiografer","left");
		$this->db->join("dokter d","d.id_dokter=pi.dokter_radiologi","left");
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
		$data1 = array(
			'no_foto' => $this->input->post("no_foto"),
			'ukuran_foto' => $this->input->post("ukuran_foto"),
			'dokter_radiologi' => $this->input->post("dokter_radiologi"),
			'radiografer' => $this->input->post("radiografer"),
			'pengirim' => $this->input->post("pengirim"),
		);
		switch ($action) {
			case 'simpan':
			$data = array(
				'no_pasien' => $this->input->post("no_rm"),
				'no_reg' => $this->input->post("no_reg"),
				'hasil_pemeriksaan' => $this->input->post("hasil_pemeriksaan"),
				'id_tindakan' => $tindakan[0],
				'tanggal' => $tindakan[3],
				'pemeriksaan' => $tindakan[4],
			);
			$this->db->insert("ekspertisi_radinap",$data);
			$item1 = array("jam_radiologi" => date("Y-m-d H:i:s"));
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update("kasir_inap",$item1);
			// $this->db->where("no_rm",$this->input->post("no_rm"));
			// $this->db->where("no_reg",$this->input->post("no_reg"));
			// $this->db->update("pasien_inap",$data1);
			$data2 = array(
				"ekspertisi_radinap" => "1"
			);
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update("pasien_inap", $data2);
			break;
			case 'edit':
			$data = array(
				'hasil_pemeriksaan' => $this->input->post("hasil_pemeriksaan"),
			);
			$this->db->where("no_pasien",$this->input->post("no_rm"));
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->where("id_tindakan",$tindakan[0]);
			$this->db->where("tanggal",$tindakan[3]);
			$this->db->update("ekspertisi_radinap",$data);
			// $this->db->where("no_rm",$this->input->post("no_rm"));
			// $this->db->where("no_reg",$this->input->post("no_reg"));
			// $this->db->update("pasien_inap",$data1);
			break;
		}
		return "success-Data berhasil disimpan";
	}
	function getekspertisiinap_detail($no_pasien,$no_reg,$id_tindakan,$tanggal,$pemeriksaan=""){
		$this->db->select("e.*");
		$this->db->where("e.no_pasien",$no_pasien);
		$this->db->where("e.no_reg",$no_reg);
		$this->db->where("e.id_tindakan",$id_tindakan);
		$this->db->where("e.tanggal",$tanggal);
		$this->db->where("e.pemeriksaan",$pemeriksaan);
		$q = $this->db->get("ekspertisi_radinap e");
		return $q->row();
	}
	function changedata_ralan($jenis){
		$no_reg = $this->db->get_where("kasir",["id"=>$this->input->post("id")])->row()->no_reg;
		if ($jenis=="petugas"){
			$data = array("kode_petugas"=>$this->input->post("value"),"terima_radiologi"=>date("Y-m-d H:i:s"));
			$this->db->where("id",$this->input->post("id"));
			$this->db->update("kasir",$data);
			$data = array("dokter_poli"=>$this->input->post("value"));
			$this->db->where("no_reg",$no_reg);
			$this->db->update("pasien_ralan",$data);
		} else
		if ($jenis=="dokter_pengirim"){
			$data = array("dokter_pengirim"=>$this->input->post("value"));
			$this->db->where("id",$this->input->post("id"));
			$this->db->update("kasir",$data);
			$data = array("dokter_pengirim"=>$this->input->post("value"));
			$this->db->where("no_reg",$no_reg);
			$this->db->update("pasien_ralan",$data);
		} else
		if ($jenis=="radiografer"){
			$data = array("analys"=>$this->input->post("value"));
			$this->db->where("id",$this->input->post("id"));
			$this->db->update("kasir",$data);
			$data = array("petugas_radiologi"=>$this->input->post("value"));
			$this->db->where("no_reg",$no_reg);
			$this->db->update("pasien_ralan",$data);
		} else {
			$this->db->where("id",$this->input->post("id"));
			$this->db->update("kasir",[$jenis=>$this->input->post("value")]);
		}
	}
	function changedata($jenis){
		if ($jenis=="petugas"){
			$data = array("kode_petugas"=>$this->input->post("value"),"terima_radiologi"=>date("Y-m-d H:i:s"));
			$id = explode("/",$this->input->post("id"));
			$this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
			$this->db->where("pemeriksaan",$id[2]);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->like("kode_tarif","R","after");
			$this->db->update("kasir_inap",$data);
		} else
		if ($jenis=="dokter_pengirim"){
			$data = array("dokter_pengirim"=>$this->input->post("value"));
			$id = explode("/",$this->input->post("id"));
			$this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
			$this->db->where("pemeriksaan",$id[2]);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->like("kode_tarif","R","after");
			$this->db->update("kasir_inap",$data);
		} else
		if ($jenis=="radiografer"){
			$data = array("analys"=>$this->input->post("value"));
			$id = explode("/",$this->input->post("id"));
			$this->db->where("tanggal",date("Y-m-d",strtotime($id[1])));
			$this->db->where("pemeriksaan",$id[2]);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->like("kode_tarif","R","after");
			$this->db->update("kasir_inap",$data);
		} else {
			$this->db->where("id",$this->input->post("id"));
			$this->db->update("kasir_inap",[$jenis=>$this->input->post("value")]);
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
		$this->db->where("id_tindakan", $row->kode_tarif);
		$this->db->delete("ekspertisi_radinap");
		$data = array(
			"no_reg" => $row->no_reg,
			"tanggal" => date("Y-m-d H:i:s"),
			"username" => $this->input->post("username"),
			"keterangan" => "Berhasil menghapus tindakan Radiologi No Reg : ".$row->no_reg
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
		$this->db->where("id_tindakan", $row->kode_tarif);
		$this->db->delete("ekspertisi");
	}
	function getdokter_array(){
		$this->db->select("id_dokter,nama_dokter");
		$this->db->where("poli","0102025");
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
	function getradiografer_array(){
		$this->db->select("id_perawat as nip,nama_perawat as nama");
		$q = $this->db->get_where("perawat",["bagian"=>"0102025"]);
		$data = array();
		foreach ($q->result() as $key) {
			$data[$key->nip] = $key->nama;
		}
		return $data;
	}
	function getcetak_kasir($no_reg,$kode_tindakan){
		$this->db->select("d.nama_dokter,k.nofoto,d1.nama_dokter as dokter_pengirim,tin.nama_tindakan");
		$this->db->join("dokter d","d.id_dokter=k.kode_petugas","left");
		$this->db->join("dokter d1","d1.id_dokter=k.dokter_pengirim","left");
		$this->db->join("tarif_radiologi tin","tin.id_tindakan=k.kode_tarif","left");
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
		$this->db->where("pr.tujuan_poli","0102025");
		$query = $this->db->get("pasien_ralan pr");
		return $query->num_rows();
	}
	function gettindakan(){
		$q = $this->db->get("tarif_radiologi");
		return $q;
	}
	function gettindakan_cetak($tindakan){
		if ($tindakan != "all") {
			$this->db->where("id_tindakan", $tindakan);
		}
		$q = $this->db->get("tarif_radiologi");
		return $q;
	}

	function gettindakan_cetak2($tindakan){
		if ($tindakan != "all") {
			$this->db->where("id_tindakan", $tindakan);
		}
		$q = $this->db->get("tarif_radiologi");
		return $q->row();
	}
	function rekap_ralan($tindakan,$tgl1="",$tgl2=""){
		$data = array();
		$tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
		$tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
		$this->db->select("k.kode_tarif,k.asal,p.status_pasien,p.jenis,p.gol_pasien");
		$this->db->where("layan!=",2);
		if ($tindakan!="all") {
			$this->db->where("k.kode_tarif",$tindakan);
		} else {
			$this->db->like("k.kode_tarif","R",'after');
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
	function rekap_ralan_full($tindakan,$tgl1="",$tgl2=""){
		$data = array();
		$tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
		$tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
		$this->db->select("k.kode_tarif,k.asal,p.status_pasien,p.jenis,p.gol_pasien,k.jam_radiologi");
		$this->db->where("layan!=",2);
		if ($tindakan!="all") {
			$this->db->where("k.kode_tarif",$tindakan);
		} else {
			$this->db->like("k.kode_tarif","R",'after');
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
			if ($key->jam_radiologi!="0000-00-00 00:00:00"){
				if (isset($data["EKS"][$key->kode_tarif]))
				$data["EKS"][$key->kode_tarif] += 1;
				else
				$data["EKS"][$key->kode_tarif] = 1;
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
		$this->db->select("k.kode_tarif,k.asal,pa.id_gol,k.jam_radiologi");
		if ($tindakan!="all") {
			$this->db->where("k.kode_tarif",$tindakan);
		} else {
			$this->db->like("k.kode_tarif","R",'after');
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
			if ($key->jam_radiologi!="0000-00-00 00:00:00"){
				if (isset($data["PEMERIKSAAN"][$key->kode_tarif]))
				$data["PEMERIKSAAN"][$key->kode_tarif] += 1;
				else
				$data["PEMERIKSAAN"][$key->kode_tarif] = 1;
			}
		}
		return $data;
	}
	function getpasien_rekap_full($tindakan,$tgl1,$tgl2){
		$data = array();
		//ralan
		$this->db->select("k.no_reg,pr.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, k.pemeriksaan, d.nama_dokter, pol.keterangan as nama_poli");
		$this->db->group_start();
		$this->db->where("pr.tujuan_poli","0102025");
		$this->db->where("k.kode_tarif",$tindakan);
		$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
		$this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
		$this->db->group_end();
		$this->db->join("pasien_ralan pr","pr.no_reg=k.no_reg","inner");
		$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
		$this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli");
		//$this->db->join("kasir k","k.no_reg=pr.no_reg","inner");
		$this->db->join("poliklinik pol","pol.kode = pr.dari_poli","left");
		$this->db->join("dokter d","d.id_dokter = pr.dokter_pengirim", "left");
		$this->db->order_by("pr.no_reg");
		$query = $this->db->get("kasir k");
		foreach ($query->result() as $row) {
			$data[$row->no_reg] = $row;
		}
		//ranap
		$this->db->select("k.no_reg,pi.*,p.nama_pasien as nama_pasien,s.keterangan, k.pemeriksaan, k.tanggal, r.nama_ruangan, kls.nama_kelas, d.nama_dokter, kmr.nama_kamar");
		$this->db->group_start();
		$this->db->where("k.kode_tarif",$tindakan);
		$this->db->where("date(k.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
		$this->db->where("date(k.tanggal)<=",date("Y-m-d",strtotime($tgl2)));;
		$this->db->group_end();
		$this->db->order_by("k.no_reg");
		$this->db->join("pasien_inap pi","pi.no_reg=k.no_reg","inner");
		$this->db->join("pasien p","p.no_pasien=pi.no_rm");
		$this->db->join("status_pulang s","s.id=pi.status_pulang","left");
		//$this->db->join("kasir_inap k","k.no_reg=pi.no_reg","inner");
		// $this->db->join("kamar ka","ka.kode_kamar=pi.kode_kamar","left");
		$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
		$this->db->join("kelas kls","kls.kode_kelas=pi.kode_kelas","left");
		$this->db->join("kamar kmr","kmr.kode_kamar=pi.kode_kamar and kls.kode_kelas = kmr.kode_kelas and r.kode_ruangan = kmr.kode_ruangan","left");
		$this->db->join("dokter d","d.id_dokter = pi.dokter","left");
		$query = $this->db->get("kasir_inap k");
		foreach ($query->result() as $row) {
			$data[$row->no_reg] = $row;
		}
		return $data;
	}
	function getpasien_rekap($tindakan,$tgl1,$tgl2){
		$this->db->select("pr.*,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, k.pemeriksaan, d.nama_dokter, pol.keterangan as nama_poli");
		$this->db->order_by("k.no_reg");
		$this->db->join("pasien_ralan pr","pr.no_reg=k.no_reg","inner");
		$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
		$this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli");
		$this->db->join("poliklinik pol","pol.kode = pr.dari_poli","left");
		$this->db->join("dokter d","d.id_dokter = pr.dokter_pengirim", "left");
		$this->db->where("pr.tujuan_poli","0102025");
		$this->db->where("k.kode_tarif",$tindakan);
		$this->db->where("date(pr.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
		$this->db->where("date(pr.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
		$query = $this->db->get("kasir k");

		return $query->result();
	}
	function rekap_inap($tindakan,$tgl1="",$tgl2=""){
		$data = array();
		$tgl1 = $tgl1=="" ? date("Y-m-d") : $tgl1;
		$tgl2 = $tgl2=="" ? date("Y-m-d") : $tgl2;
		$this->db->select("k.kode_tarif,k.asal,pa.id_gol");
		if ($tindakan!="all") {
			$this->db->where("k.kode_tarif",$tindakan);
		} else {
			$this->db->like("k.kode_tarif","R",'after');
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
		}
		return $data;
	}
	function getpasien_rekap_inap($tindakan,$tgl1,$tgl2){
		$this->db->select("pi.*,p.nama_pasien as nama_pasien,s.keterangan, k.pemeriksaan, k.tanggal, r.nama_ruangan, kls.nama_kelas, d.nama_dokter, kmr.nama_kamar");
		$this->db->order_by("pi.no_reg");
		$this->db->join("pasien p","p.no_pasien=pi.no_rm");
		$this->db->join("status_pulang s","s.id=pi.status_pulang","left");
		$this->db->join("kasir_inap k","k.no_reg=pi.no_reg","inner");
		// $this->db->join("kamar ka","ka.kode_kamar=pi.kode_kamar","left");
		$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
		$this->db->join("kelas kls","kls.kode_kelas=pi.kode_kelas","left");
		$this->db->join("kamar kmr","kmr.kode_kamar=pi.kode_kamar and kls.kode_kelas = kmr.kode_kelas and r.kode_ruangan = kmr.kode_ruangan","left");
		$this->db->join("dokter d","d.id_dokter = pi.dokter","left");
		// $this->db->where("pi.tujuan_poli","0102024");
		$this->db->where("k.kode_tarif",$tindakan);
		$this->db->where("date(k.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
		$this->db->where("date(k.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
		$this->db->group_by("pi.no_reg");
		$query = $this->db->get("pasien_inap pi");
		return $query->result();
	}
	function getdiagnosa($no_reg){
		$this->db->select("a as diagnosa");
		$q = $this->db->get_where("riwayat_pasien_inap",["no_reg"=>$no_reg]);
		return $q->row()->diagnosa;
	}
}
