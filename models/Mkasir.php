<?php
class Mkasir extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getpasien_ralan($page, $offset)
	{
		$poli_kode = $this->session->userdata("poli_kode");
		$kode_dokter = $this->session->userdata("kode_dokter");
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$no_pasien = $this->session->userdata("no_pasien");
		$no_reg = $this->session->userdata("no_reg");
		$nama = $this->session->userdata("nama");
		$this->db->select("pr.*,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, g.keterangan as gol_pasien");
		// if ($no_pasien!="") {
		// 	$no_pasien = "000000".$no_pasien;
		// 	$this->db->where("p.no_pasien",substr($no_pasien,-6));
		// }
		$this->db->where("pr.layan!=", 2);
		// if ($no_reg!="") {
		// 	$this->db->where("no_reg",$no_reg);
		// }
		// if ($nama!="") {
		// 	$this->db->like("p.nama_pasien",$nama);
		// }
		$this->db->group_start();
		$this->db->like("p.no_pasien", $no_pasien);
		$this->db->or_like("no_reg", $no_pasien);
		$this->db->or_like("no_bpjs", $no_pasien);
		$this->db->or_like("no_sjp", $no_pasien);
		$this->db->or_like("p.nama_pasien", $no_pasien);
		$this->db->group_end();
		if ($poli_kode != "") {
			$this->db->where("pr.tujuan_poli", $poli_kode);
		}
		if ($kode_dokter != "") {
			$this->db->where("pr.dokter_poli", $kode_dokter);
		}
		if ($tgl1 != "" || $tgl2 != "") {
			$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
		}
		$this->db->order_by("no_reg", "desc");
		$this->db->join("pasien p", "p.no_pasien=pr.no_pasien");
		$this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
		$this->db->join("poliklinik pol", "pol.kode=pr.dari_poli", "left");
		$this->db->join("poliklinik pol2", "pol2.kode=pr.tujuan_poli", "left");
		$query = $this->db->get("pasien_ralan pr", $page, $offset);
		return $query;
	}
	function getjumlahpasien_ralan()
	{
		$poli_kode = $this->session->userdata("poli_kode");
		$kode_dokter = $this->session->userdata("kode_dokter");
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$no_pasien = $this->session->userdata("no_pasien");
		$no_reg = $this->session->userdata("no_reg");
		$nama = $this->session->userdata("nama");
		$this->db->select("pr.no_pasien");
		// if ($no_pasien!="") {
		// 	$no_pasien = "000000".$no_pasien;
		// 	$this->db->where("p.no_pasien",substr($no_pasien,-6));
		// }
		// if ($no_reg!="") {
		// 	$this->db->where("no_reg",$no_reg);
		// }
		$this->db->where("pr.layan!=", 2);
		// if ($nama!="") {
		// 	$this->db->like("p.nama_pasien",$nama);
		// }
		$this->db->group_start();
		$this->db->like("p.no_pasien", $no_pasien);
		$this->db->or_like("no_reg", $no_pasien);
		$this->db->or_like("no_bpjs", $no_pasien);
		$this->db->or_like("no_sjp", $no_pasien);
		$this->db->or_like("p.nama_pasien", $no_pasien);
		$this->db->group_end();
		if ($poli_kode != "") {
			$this->db->where("pr.tujuan_poli", $poli_kode);
		}
		if ($kode_dokter != "") {
			$this->db->where("pr.dokter_poli", $kode_dokter);
		}
		if ($tgl1 != "" || $tgl2 != "") {
			$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
		}
		$this->db->join("pasien p", "p.no_pasien=pr.no_pasien");
		$this->db->join("poliklinik pol", "pol.kode=pr.dari_poli", "left");
		$this->db->join("poliklinik pol2", "pol2.kode=pr.tujuan_poli", "left");
		$query = $this->db->get("pasien_ralan pr");
		return $query->num_rows();
	}
	function getpasien_inap($page, $offset)
	{
		$kode_kelas = $this->session->userdata("kode_kelas");
		$kode_ruangan = $this->session->userdata("kode_ruangan");
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$no_rm = $this->session->flashdata("no_rm");
		$no_reg = $this->session->flashdata("no_reg");
		$nama = $this->session->flashdata("nama");
		$this->db->select("i.*,r.nama_ruangan,k.nama_kelas, g.keterangan as gol_pasien,p.nama_pasien");
		// if ($no_rm!="") {
		// 	$no_rm = "000000".$no_rm;
		// 	$this->db->where("i.no_rm",substr($no_rm,-6));
		// }
		// if ($nama!="") {
		// 	$this->db->like("p.nama_pasien",$nama);
		// }
		// if ($no_reg!="") {
		// 	$this->db->where("no_reg",$no_reg);
		// }
		$this->db->group_start();
		$this->db->like("i.no_rm", $no_rm);
		$this->db->or_like("no_reg", $no_rm);
		$this->db->or_like("no_bpjs", $no_rm);
		$this->db->or_like("no_sjp", $no_rm);
		$this->db->or_like("p.nama_pasien", $no_rm);
		$this->db->group_end();
		if ($kode_kelas != "") {
			$this->db->where("i.kode_kelas", $kode_kelas);
		}
		if ($kode_ruangan != "") {
			$this->db->where("i.kode_ruangan", $kode_ruangan);
		}
		if ($tgl1 != "" || $tgl2 != "") {
			$this->db->where("i.tgl_masuk>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("i.tgl_masuk<=", date("Y-m-d", strtotime($tgl2)));
		}
		$this->db->order_by("no_reg", "desc");
		$this->db->join("pasien p", "p.no_pasien=i.no_rm");
		$this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
		$this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
		$this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
		$this->db->order_by("no_reg,no_rm");
		$query = $this->db->get("pasien_inap i", $page, $offset);
		return $query;
	}
	function getjumlahpasien_inap()
	{
		$kode_kelas = $this->session->userdata("kode_kelas");
		$kode_ruangan = $this->session->userdata("kode_ruangan");
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$no_rm = $this->session->userdata("no_rm");
		$no_reg = $this->session->userdata("no_reg");
		$nama = $this->session->userdata("nama");
		$this->db->select("i.no_rm");
		// if ($no_pasien!="") {
		// 	$this->db->where("p.no_pasien",$no_pasien);
		// }
		// if ($nama!="") {
		// 	$this->db->like("p.nama_pasien",$nama);
		// }
		// if ($no_reg!="") {
		// 	$this->db->where("no_reg",$no_reg);
		// }
		$this->db->group_start();
		$this->db->like("i.no_rm", $no_rm);
		$this->db->or_like("no_reg", $no_rm);
		$this->db->or_like("no_bpjs", $no_rm);
		$this->db->or_like("no_sjp", $no_rm);
		$this->db->or_like("p.nama_pasien", $no_rm);
		$this->db->group_end();
		if ($kode_kelas != "") {
			$this->db->where("i.kode_kelas", $kode_kelas);
		}
		if ($kode_ruangan != "") {
			$this->db->where("i.kode_ruangan", $kode_ruangan);
		}
		if ($tgl1 != "" || $tgl2 != "") {
			$this->db->where("i.tgl_masuk>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("i.tgl_masuk<=", date("Y-m-d", strtotime($tgl2)));
		}
		$this->db->join("pasien p", "p.no_pasien=i.no_rm");
		$this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
		$this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
		$this->db->order_by("no_reg,no_rm");
		$query = $this->db->get("pasien_inap i");
		return $query->num_rows();
	}
	function getralan_detail($no_pasien, $no_reg)
	{
		$this->db->select("pr.*,p.alamat,p.nama_pasien,pl.keterangan as poli,g.keterangan as ket_gol_pasien,g1.keterangan as ket_gol_pasien1");
		$this->db->join("pasien p", "pr.no_pasien=p.no_pasien");
		$this->db->join("poliklinik pl", "pl.kode=pr.tujuan_poli");
		$this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "left");
		$this->db->join("gol_pasien g1", "g1.id_gol=p.id_gol", "left");
		$this->db->where("pr.no_pasien", $no_pasien);
		$this->db->where("pr.no_reg", $no_reg);
		$q = $this->db->get("pasien_ralan pr");
		return $q->row();
	}
	function getinap_detail($no_pasien, $no_reg)
	{
		$this->db->select("pr.*,k.nama_kelas,r.nama_ruangan,p.alamat,p.nama_pasien,g.keterangan as ket_gol_pasien");
		$this->db->join("pasien p", "pr.no_rm=p.no_pasien");
		$this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "left");
		$this->db->join("ruangan r", "r.kode_ruangan=pr.kode_ruangan", "left");
		$this->db->join("kelas k", "k.kode_kelas=pr.kode_kelas", "left");
		$this->db->where("pr.no_rm", $no_pasien);
		$this->db->where("pr.no_reg", $no_reg);
		$q = $this->db->get("pasien_inap pr");
		return $q->row();
	}
	function getkasir($no_reg)
	{
		$this->db->join("tarif_ralan t", "t.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_inap($no_reg)
	{
		$this->db->join("tarif_inap i", "i.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_inap_radiologi($no_reg)
	{
		$this->db->join("tarif_radiologi i", "i.id_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_inap_pa($no_reg)
	{
		$this->db->join("tarif_pa i", "i.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_igd($no_reg)
	{
		$this->db->join("tarif_ralan i", "i.kode_tindakan=k.kode_tarif and i.kode_tindakan!='FRM'", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasirralan_lab($no_reg)
	{
		$this->db->join("tarif_lab i", "i.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_lab($no_reg)
	{
		$this->db->join("tarif_lab i", "i.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_gizi($no_reg)
	{
		$this->db->join("tarif_gizi i", "i.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_ambulance($no_reg)
	{
		$this->db->join("tarif_ambulance i", "i.kode=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_ambulance_ralan($no_reg)
	{
		$this->db->join("tarif_ambulance i", "i.kode=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_penunjang($no_reg)
	{
		$this->db->join("tarif_penunjang_medis p", "p.kode=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_operasi($no_reg)
	{
		$this->db->join("tarif_operasi p", "p.kode=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_opr($no_reg)
	{
		$this->db->join("tarif_opr p", "p.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_radiologi($no_reg)
	{
		$this->db->join("tarif_radiologi t", "t.id_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_pa($no_reg)
	{
		$this->db->join("tarif_pa t", "t.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_penunjang_ralan($no_reg)
	{
		$this->db->join("tarif_penunjang_medis t", "t.kode=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		return $q;
	}
	function getkasir_radiologi2($no_reg)
	{
		$this->db->where("no_reg_sebelumnya", $no_reg);
		$this->db->where("tujuan_poli", "0102025");
		$q = $this->db->get("pasien_ralan")->row();

		$noreg = (isset($q->no_reg) ? $q->no_reg : "");
		$this->db->join("tarif_radiologi t", "t.id_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $noreg]);
		return $q;
	}
	function getkasirralan_lab2($no_reg)
	{
		$this->db->where("no_reg_sebelumnya", $no_reg);
		$this->db->where("tujuan_poli", "0102024");
		$q = $this->db->get("pasien_ralan")->row();

		$noreg = (isset($q->no_reg) ? $q->no_reg : "");
		$this->db->join("tarif_lab t", "t.kode_tindakan=k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $noreg]);
		return $q;
	}
	function gettindakan($no_reg)
	{
		$this->db->select("tujuan_poli");
		$q = $this->db->get_where("pasien_ralan", ["no_reg" => $no_reg]);
		if ($q->num_rows() > 0) $poli = $q->row()->tujuan_poli;
		else $poli = "";
		return $this->db->get_where("tarif_ralan", ["kategori" => "tdk", "kode_poli" => $poli]);
	}
	function gettindakan_inap()
	{
		return $this->db->get_where("tarif_inap");
	}
	function getambulance()
	{
		return $this->db->get_where("tarif_ambulance");
	}
	function getoperasi()
	{
		return $this->db->get_where("tarif_operasi");
	}
	function getpenunjang_medis()
	{
		return $this->db->get_where("tarif_penunjang_medis");
	}
	function gettindakan_radiologi()
	{
		return $this->db->get("tarif_radiologi");
	}
	function gettindakan_lab()
	{
		return $this->db->get("tarif_lab");
	}
	function addtindakan()
	{
		$t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $this->input->post("tindakan")]);
		if ($t->num_rows() > 0) {
			$data = $t->row();
			if ($this->input->post('jenis') == "E") $tarif = $data->executive;
			else $tarif = $data->reguler;
			$data = array(
				"id" => date("dmyHis"),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => $this->input->post("tindakan"),
				"jumlah" => $tarif
			);
			$this->db->insert("kasir", $data);
		}
	}
	function addtindakan_penunjang()
	{
		$t = $this->db->get_where("tarif_penunjang_medis", ["kode" => $this->input->post("tindakan")]);
		if ($t->num_rows() > 0) {
			$data = $t->row();
			$tarif = $data->tarif;
			$data = array(
				"id" => date("dmyHis"),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => $this->input->post("tindakan"),
				"jumlah" => $tarif
			);
			$this->db->insert("kasir", $data);
		}
	}
	function addtindakan_ambulance()
	{
		$t = $this->db->get_where("tarif_ambulance", ["kode" => $this->input->post("tindakan")]);
		if ($t->num_rows() > 0) {
			$data = $t->row();
			$tarif = $data->tarif;
			$data = array(
				"id" => date("dmyHis"),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => $this->input->post("tindakan"),
				"jumlah" => $tarif
			);
			$this->db->insert("kasir", $data);
		}
	}
	function addtindakan_lab()
	{
		$t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $this->input->post("tindakan")]);
		if ($t->num_rows() > 0) {
			$data = $t->row();
			if ($this->input->post('jenis') == "E") $tarif = $data->executive;
			else $tarif = $data->reguler;
			$data = array(
				"id" => date("dmyHis"),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => $this->input->post("tindakan"),
				"jumlah" => $tarif
			);
			$this->db->insert("kasir", $data);
		}
	}
	function addtindakan_inap($jenis)
	{
		$tarif = 0;
		switch ($jenis) {
			case 'inap':
				$t = $this->db->get_where("tarif_inap", ["kode_tindakan" => $this->input->post("tindakan")]);
				if ($t->num_rows() > 0) {
					$t = $t->row();
					switch ($this->input->post('kode_kelas')) {
						case '01':
							$tarif = $t->vip_deluxe;
							break;
						case '02':
							$tarif = $t->vip_premium;
							break;
						case '03':
							$tarif = $t->vip_executive;
							break;
						case '04':
							$tarif = $t->vip_deluxe;
							break;
						case '05':
							$tarif = $t->vip;
							break;
						case '051':
							$tarif = $t->vip1;
							break;
						case '052':
							$tarif = $t->vip2;
							break;
						case '053':
							$tarif = $t->vip3;
							break;
						case '06':
							$tarif = $t->kelas1;
							break;
						case '07':
							$tarif = $t->kelas2;
							break;
						case '08':
							$tarif = $t->kelas3;
							break;
						case '09':
							$tarif = $t->icu;
							break;
						case '10':
							$tarif = $t->nicu;
							break;
						case '11':
							$tarif = $t->nicu;
							break;
						case '12':
							$tarif = $t->bayi;
							break;
						case '13':
							$tarif = $t->bayi;
							break;
					}
				}
				break;
			case 'ambulance':
				$t = $this->db->get_where("tarif_ambulance", ["kode" => $this->input->post("tindakan")]);
				if ($t->num_rows() > 0) {
					$t = $t->row();
					$tarif = $t->tarif;
				}
				break;
			case 'penunjang':
				$t = $this->db->get_where("tarif_penunjang_medis", ["kode" => $this->input->post("tindakan")]);
				if ($t->num_rows() > 0) {
					$t = $t->row();
					$tarif = $t->tarif;
				}
				break;
			case 'operasi':
				$t = $this->db->get_where("tarif_operasi", ["kode" => $this->input->post("tindakan")]);
				if ($t->num_rows() > 0) {
					$t = $t->row_array();
					switch ($this->input->post('kode_kelas')) {
						case '01':
							$tarif = $t["vip_deluxe"];
							break;
						case '02':
							$tarif = $t["vip_premium"];
							break;
						case '03':
							$tarif = $t["vip_executive"];
							break;
						case '04':
							$tarif = $t["vip_deluxe"];
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
					$id_operasi = $t["id_operasi"];
				}
				break;
		}
		$id = date("dmyHis");
		if ($this->input->post("tindakan") == "kmr") {
			$data = array(
				"id" => $id,
				"no_reg" => $this->input->post("no_reg"),
				"tanggal" => date("Y-m-d", strtotime($this->input->post("tanggal"))),
				"kode_tarif" => $this->input->post("tindakan"),
				"qty" => $this->input->post("qty"),
				"kode_petugas" => $this->input->post("kode_ruangan") . "-" . $this->input->post("kode_kelas"),
				"jumlah" => $tarif * $this->input->post("qty")
			);
		} else {
			$data = array(
				"id" => $id,
				"no_reg" => $this->input->post("no_reg"),
				"tanggal" => date("Y-m-d", strtotime($this->input->post("tanggal"))),
				"kode_tarif" => $this->input->post("tindakan"),
				"qty" => $this->input->post("qty"),
				"jumlah" => $tarif * $this->input->post("qty")
			);
		}
		$this->db->insert("kasir_inap", $data);
		if ($jenis == "operasi") {
			$id++;
			$this->db->like("jenis", $id_operasi);
			$s = $this->db->get("tarif_opr");
			foreach ($s->result() as $row) {
				$trf = (($t[$row->kode_tindakan] * $tarif) / 100) * $this->input->post("qty");
				$data = array(
					"id" => $id++,
					"no_reg" => $this->input->post("no_reg"),
					"tanggal" => date("Y-m-d", strtotime($this->input->post("tanggal"))),
					"kode_tarif" => $row->kode_tindakan,
					"qty" => $this->input->post("qty"),
					"jumlah" => $trf
				);
				$this->db->insert("kasir_inap", $data);
			}
		}
	}
	function addtindakan_radiologi()
	{
		$t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $this->input->post("tindakan")]);
		if ($t->num_rows() > 0) {
			$data = $t->row();
			if ($this->input->post('jenis') == "E") $tarif = $data->executive;
			else $tarif = $data->reguler;
			$data = array(
				"id" => date("dmyHis"),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => $this->input->post("tindakan"),
				"jumlah" => $tarif
			);
			$this->db->insert("kasir", $data);
		}
	}
	function hapustindakan()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("kasir");
	}
	function hapusinap()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("kasir_inap");
	}
	function changedata()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->update("kasir", ["jumlah" => $this->input->post("value")]);
	}
	function changedata_inap()
	{
		switch ($this->input->post("jenis")) {
			case 'qty':
				$data = array("qty" => $this->input->post("value"));
				break;
			case 'lama':
				$data = array("lama" => $this->input->post("value"));
				break;
			case 'petugas':
				$data = array("kode_petugas" => $this->input->post("value"));
				break;
			case 'tagihan':
				$data = array("jumlah" => str_replace(".", "", $this->input->post("value")));
				break;
		}
		$this->db->where("id", $this->input->post("id"));
		$this->db->update("kasir_inap", $data);
	}
	function simpantransaksi()
	{
		$dat = array();
		$username_kasir = $this->input->post("username_kasir");
		$disc_nominal = $this->input->post("disc_nominal");
		$dp_nominal = $this->input->post("dp_nominal");
		$sharing = $this->input->post("sharing");
		$bayar = $this->input->post("total");
		$id = date("dmyHis");
		$dat = array(
			"id" => $id,
			"tanggal" => date("Y-m-d"),
			"no_reg" => $this->input->post("no_reg"),
			"jumlah_disc" => $disc_nominal,
			"jumlah_dp" => $dp_nominal,
			"jumlah_sharing" => $sharing,
			"jumlah_bayar" => $bayar,
			"petugas_kasir" => $username_kasir
		);
		$q = $this->db->get_where("transaksi", ["no_reg" => $this->input->post("no_reg")]);
		if ($q->num_rows() > 0) {
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update('transaksi', $dat);
		} else {
			$this->db->insert('transaksi', $dat);
		}
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->set("bayar", "jumlah", FALSE);
		$this->db->set("id_transaksi", $id);
		$q = $this->db->update("kasir");
		if ($this->input->post("status_bayar") != "TAGIH") {
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->set("status_bayar", "LUNAS");
			$this->db->update("pasien_ralan");
		}
	}
	function simpantransaksi_inap()
	{
		$dat = array();
		$username_kasir = $this->input->post("username_kasir");
		$disc_nominal = $this->input->post("disc_nominal");
		$dp_nominal = $this->input->post("dp_nominal");
		$sharing = $this->input->post("sharing");
		$bayar = $this->input->post("total");
		$id = date("dmyHis");
		$dat = array(
			"id" => $id,
			"tanggal" => date("Y-m-d"),
			"no_reg" => $this->input->post("no_reg"),
			"jumlah_disc" => $disc_nominal,
			"jumlah_dp" => $dp_nominal,
			"jumlah_sharing" => $sharing,
			"jumlah_bayar" => $bayar,
			"petugas_kasir" => $username_kasir
		);
		$q = $this->db->get_where("transaksi", ["no_reg" => $this->input->post("no_reg")]);
		if ($q->num_rows() > 0) {
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update('transaksi', $dat);
		} else {
			$this->db->insert('transaksi', $dat);
		}
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->set("id_transaksi", $id);
		$q = $this->db->update("kasir_inap");
	}
	function getkasir_detail($no_reg)
	{
		$this->db->select("t.*,p.nama");
		$this->db->join("petugas_kasir p", "p.nip=t.petugas_kasir", "left");
		$q = $this->db->get_where("transaksi t", ["no_reg" => $no_reg]);
		return $q;
	}
	function getdokter()
	{
		$q = $this->db->get("dokter");
		return $q->result();
	}
	function getkamar()
	{
		$this->db->select("k.kode_kamar,k.kode_ruangan,r.nama_ruangan,k.kode_kelas,kl.nama_kelas");
		$this->db->join("kelas kl", "kl.kode_kelas=k.kode_kelas", "inner");
		$this->db->join("ruangan r", "r.kode_ruangan=k.kode_ruangan", "inner");
		$q = $this->db->get("kamar k");
		return $q->result();
	}
	function getdokter_array()
	{
		$this->db->select("id_dokter,nama_dokter");
		$q = $this->db->get("dokter");
		$data = array();
		foreach ($q->result() as $key) {
			$data[$key->id_dokter] = $key->nama_dokter;
		}
		return $data;
	}
	function getkamar_array()
	{
		$this->db->select("k.kode_kamar,k.kode_ruangan,r.nama_ruangan,k.kode_kelas,kl.nama_kelas,k.klasifikasi");
		$this->db->join("kelas kl", "kl.kode_kelas=k.kode_kelas", "inner");
		$this->db->join("ruangan r", "r.kode_ruangan=k.kode_ruangan", "inner");
		$q = $this->db->get("kamar k");
		$data = array();
		foreach ($q->result() as $key) {
			$data[$key->kode_ruangan . "-" . $key->kode_kelas] = $key->nama_ruangan . " - " . $key->nama_kelas . " - " . $key->klasifikasi;
		}
		return $data;
	}
	function simpan_pulang()
	{
		// $sp = explode("/", $this->input->post("status_pulang"));
		$this->db->select("tgl_keluar");
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$q = $this->db->get("pasien_inap")->row();
		$tgl_keluar = $q->tgl_keluar;
		$data = array(
			"keadaan_pulang" => $this->input->post("keadaan_pulang"),
			"status_pulang" => $this->input->post("status_pulang"),
			"no_surat_pulang" => $this->input->post("no_surat_pulang"),
			"tgl_keluar" => date("Y-m-d", strtotime($this->input->post("tgl_keluar"))),
			"tgl_kontrol" => date("Y-m-d", strtotime($this->input->post("tgl_kontrol"))),
			"jam_keluar" => date("H:i", strtotime($this->input->post("jam_keluar"))),
			"jam_kontrol" => date("H:i", strtotime($this->input->post("jam_kontrol"))),
			"transport_pulang" => $this->input->post("transport_pulang"),
			"no_sjp" => $this->input->post("no_sep"),
			"dpjp" => $this->input->post("dpjp")
		);
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("pasien_inap", $data);
		if ($tgl_keluar == "" || $tgl_keluar == null) {
			$this->db->where("kode_kelas", $this->input->post("kode_kelas"));
			$this->db->where("kode_ruangan", $this->input->post("kode_ruangan"));
			$this->db->where("kode_kamar", $this->input->post("kode_kamar"));
			$this->db->where("no_bed", $this->input->post("no_bed"));
			$this->db->update("kamar", ["status_kamar" => "KOSONG"]);
		}
	}
	// function batal_pulang(){
	// 	$data = array(
	// 				"tgl_keluar" => NULL
	// 			);
	// 	$this->db->where("no_reg",$this->input->post("no_reg"));
	// 	$this->db->update("pasien_inap",$data);
	// }
	function getkeadaan_pulang()
	{
		$q = $this->db->get("keadaan_pulang");
		return $q;
	}
	function getstatus_pulang()
	{
		$q = $this->db->get("status_pulang");
		return $q;
	}
	function laporan_ralan($tgl1, $tgl2, $frm, $poli)
	{
		if ($frm == "all") {
			$this->db->select("k.*,sum(k.jumlah) as jumlah,t.jumlah_disc,pr.layan,pr.tanggal,pr.gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien,pr.tujuan_poli,pk.keterangan as poliklinik");
			$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("pr.layan!=", 2);
			$this->db->join("pasien_ralan pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("poliklinik pk", "pk.kode=pr.tujuan_poli", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
			$this->db->join("transaksi t", "t.no_reg=k.no_reg", "left");
			$this->db->order_by("k.no_reg,k.id");
			$this->db->group_by("k.no_reg");
		} else
		if ($frm == 0) {
			$this->db->select("k.*,sum(k.jumlah) as jumlah,t.jumlah_disc,pr.layan,pr.tanggal,pr.gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien,pr.tujuan_poli,pk.keterangan as poliklinik");
			$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("pr.layan!=", 2);
			$this->db->where("k.kode_tarif!=", "FRM");
			$this->db->join("pasien_ralan pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("poliklinik pk", "pk.kode=pr.tujuan_poli", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
			$this->db->join("transaksi t", "t.no_reg=k.no_reg", "left");
			$this->db->order_by("k.no_reg,k.id");
			$this->db->group_by("k.no_reg");
		} else
		if ($frm == 1) {
			$this->db->select("k.*,sum(k.jumlah) as jumlah,t.jumlah_disc,pr.layan,pr.tanggal,pr.gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien,pr.tujuan_poli,pk.keterangan as poliklinik");
			$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("pr.layan!=", 2);
			$this->db->where("k.kode_tarif", "FRM");
			$this->db->join("pasien_ralan pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("poliklinik pk", "pk.kode=pr.tujuan_poli", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "inner");
			$this->db->join("transaksi t", "t.no_reg=k.no_reg", "left");
			$this->db->order_by("k.no_reg,k.id");
			$this->db->group_by("k.no_reg");
		}
		if ($poli != "all") {
			$this->db->where("pr.tujuan_poli", $poli);
		}
		$q = $this->db->get("kasir k");
		return $q;
	}
	function laporan_apotek($tgl1, $tgl2, $frm, $poli, $pelayanan = "ralan")
	{
		if ($frm == "all" || $frm == 1) {
			$this->db->select("p.no_penjualan as no_reg,p.pembeli as nama_pasien,p.no_rm,sum(i.total_harga) as jumlah,p.tanggal");
			$this->db->where("p.tanggal>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("p.tanggal<=", date("Y-m-d", strtotime($tgl2)));
			if ($pelayanan == "ralan")
				$this->db->where("p.depo!=", "D-INAP");
			else
				$this->db->where("p.depo", "D-INAP");
			if ($poli != "all") {
				$this->db->where("p.poli_ruangan", $poli);
			}
			$this->db->join("item_penjualan i", "i.no_penjualan=p.no_penjualan", "inner");
			$this->db->order_by("p.tanggal,p.no_penjualan");
			$this->db->group_by("p.no_penjualan");
			$q = $this->db->get("penjualan_apotek p");
			return $q;
		}
	}
	function laporan_parsial_ralan($tgl1, $tgl2, $frm, $poli)
	{
		if ($frm == "all" || $frm == 2) {
			$this->db->select("k.*,sum(k.jumlah) as jumlah,pr.layan,pr.tanggal,pr.gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien,pr.tujuan_poli,pk.keterangan as poliklinik");
			$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("pr.layan!=", 2);
			$this->db->join("pasien_ralan pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("poliklinik pk", "pk.kode=pr.tujuan_poli", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
			$this->db->order_by("k.no_reg,k.id");
			$this->db->group_by("k.no_reg");
			if ($poli != "all") {
				$this->db->where("pr.tujuan_poli", $poli);
			}
			$q = $this->db->get("parsial k");
			return $q;
		}
	}
	function getpoliklinik()
	{
		return $this->db->get("poliklinik");
	}
	function laporan_inap($tgl1, $tgl2, $frm)
	{
		if ($frm == "all") {
			$this->db->select("k.*,sum(k.qty*k.jumlah) as jumlah,pr.layan,pr.tgl_keluar,pr.id_gol as gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien,pr.blu");
			$this->db->where("pr.tgl_keluar>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("pr.tgl_keluar<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->join("pasien_inap pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "inner");
			$this->db->order_by("pr.tgl_keluar,k.no_reg");
			$this->db->group_by("k.no_reg");
		} else
		if ($frm == 0) {
			$this->db->select("k.*,sum(k.qty*k.jumlah) as jumlah,pr.layan,pr.tgl_keluar,pr.id_gol as gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien");
			$this->db->where("pr.tgl_keluar>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("pr.tgl_keluar<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("k.kode_tarif!=", "FRM");
			$this->db->join("pasien_inap pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "inner");
			$this->db->order_by("pr.tgl_keluar,k.no_reg");
			$this->db->group_by("k.no_reg");
		} else
		if ($frm == 1) {
			$this->db->select("k.*,sum(k.qty*k.jumlah) as jumlah,pr.layan,pr.tgl_keluar,pr.id_gol as gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien");
			$this->db->where("pr.tgl_keluar>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("pr.tgl_keluar<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("k.kode_tarif", "FRM");
			$this->db->join("pasien_inap pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "inner");
			$this->db->order_by("pr.tgl_keluar,k.no_reg");
			$this->db->group_by("k.no_reg");
		} else
		if ($frm == 2) {
			$this->db->select("k.*,pr.blu as jumlah,pr.layan,pr.tgl_keluar,pr.id_gol as gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien");
			$this->db->where("pr.tgl_keluar>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("pr.tgl_keluar<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("pr.naik_kelas", "naik");
			$this->db->join("pasien_inap pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "inner");
			$this->db->order_by("pr.tgl_keluar,k.no_reg");
			$this->db->group_by("k.no_reg");
		}
		$q = $this->db->get("kasir_inap k");
		return $q;
	}
	function laporan_parsial_inap($tgl1, $tgl2, $frm)
	{
		if ($frm == "all" || $frm == 3) {
			$this->db->select("k.*,sum(k.qty*k.jumlah) as jumlah,pr.layan,k.tanggal as tgl_keluar,pr.id_gol as gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien,pr.blu");
			$this->db->where("k.tanggal>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("k.tanggal<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->join("pasien_inap pr", "pr.no_reg=k.no_reg", "inner");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "inner");
			$this->db->order_by("k.tanggal,k.no_reg");
			$this->db->group_by("k.no_reg");
			$q = $this->db->get("parsial_inap k");
			return $q;
		}
	}
	function keuangan($tgl1, $tgl2)
	{
		$data = array();
		$this->db->select("prs.nama as nama_perusahaan,pr.*,pr.layan,pr.tgl_keluar as tanggal,pr.id_gol as gol_pasien,g.keterangan as ket_golpas,p.no_pasien,p.nama_pasien");
		$this->db->where("pr.tgl_keluar>=", date("Y-m-d", strtotime($tgl1)));
		$this->db->where("pr.tgl_keluar<=", date("Y-m-d", strtotime($tgl2)));
		$this->db->where("pr.naik_kelas", "naik");
		$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
		$this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "inner");
		$this->db->join("perusahaan prs", "prs.kode=pr.kode_perusahaan", "left");
		$this->db->order_by("pr.tgl_keluar,pr.no_reg");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_inap pr");
		return $q;
	}
	function getperusahaan()
	{
		return $this->db->get("perusahaan");
	}
	function countprint($no_reg)
	{
		$this->db->where("no_reg", $no_reg);
		$this->db->set("print", "print+1", FALSE);
		$this->db->update("transaksi");
	}
}
