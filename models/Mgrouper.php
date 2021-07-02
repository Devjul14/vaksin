<?php
class Mgrouper extends CI_Model
{
	function __construct()
	{
		parent::__construct();
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
	function getpasien_ralan($page, $offset)
	{
		$poli_kode = $this->session->userdata("poli_kode");
		$kode_dokter = $this->session->userdata("kode_dokter");
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$no_pasien = $this->session->userdata("no_pasien");
		$no_reg = $this->session->userdata("no_reg");
		$nama = $this->session->userdata("nama");
		$this->db->select("pr.*,p.no_bpjs,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien");
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
		$this->db->join("poliklinik pol", "pol.kode=pr.dari_poli", "left");
		$this->db->join("poliklinik pol2", "pol2.kode=pr.tujuan_poli", "left");
		$query = $this->db->get("pasien_ralan pr", $page, $offset);
		return $query;
	}
	function getralan_detail($no_pasien, $no_reg)
	{
		$this->db->select("pr.*,d.nama_dokter,ps.kode_bpjs as kode_perusahaan,p.jenis_kelamin,p.tgl_lahir,p.no_bpjs,p.alamat,p.nama_pasien,pl.keterangan as poli,g.keterangan as ket_gol_pasien");
		$this->db->join("pasien p", "pr.no_pasien=p.no_pasien");
		$this->db->join("poliklinik pl", "pl.kode=pr.tujuan_poli");
		$this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "left");
		$this->db->join("perusahaan ps", "ps.kode=p.perusahaan", "left");
		$this->db->join("dokter d", "d.id_dokter=pr.dokter_poli", "left");
		$this->db->where("pr.no_pasien", $no_pasien);
		$this->db->where("pr.no_reg", $no_reg);
		$q = $this->db->get("pasien_ralan pr");
		return $q->row();
	}
	function getgrouper($page, $offset)
	{
		$this->db->order_by("kode");
		$q = $this->db->get("grouper", $page, $offset);
		return $q;
	}
	function geticd9_ralan($no_reg, $icd)
	{
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		$data = array();
		foreach ($q->result() as $key) {
			$t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd[$tkey->icd9];
			}
			$t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd[$tkey->icd9];
			}
			$t = $this->db->get_where("tarif_ambulance", ["kode" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd[$tkey->icd9];
			}
			$t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd[$tkey->icd9];
			}
		}
		foreach ($data as $key => $value) {
			$q = $this->db->get_where("grouper_ralan_icd9", ["kode" => $key, "no_reg" => $no_reg]);
			if ($q->num_rows() <= 0) {
				$dat = array(
					"id" => date("dmYHis"),
					"kode" => $key,
					"no_reg" => $no_reg
				);
				$this->db->insert("grouper_ralan_icd9", $dat);
			}
		}
		$this->db->where("g.no_reg", $no_reg);
		$q = $this->db->get("grouper_ralan_icd9 g");
		return $q;
	}
	function geticd9_ralan1($no_reg, $icd)
	{
		// $icd = $this->geticd9_array();
		$this->db->select("k.*,t.icd9 as kode");
		$this->db->join("tarif_ralan t", "t.kode_tindakan = k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		$data = array();
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd[$key->kode];
		}
		$this->db->select("k.*,t.icd9 as kode");
		$this->db->join("tarif_radiologi t", "t.id_tindakan = k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd[$key->kode];
		}
		$this->db->select("k.*,t.icd9 as kode");
		$this->db->join("tarif_ambulance t", "t.kode = k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd[$key->kode];
		}
		$this->db->select("k.*,t.icd9 as kode");
		$this->db->join("tarif_lab t", "t.kode_tindakan = k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd[$key->kode];
		}
		foreach ($data as $key => $value) {
			$q = $this->db->get_where("grouper_ralan_icd9", ["kode" => $key, "no_reg" => $no_reg]);
			if ($q->num_rows() <= 0) {
				$dat = array(
					"id" => date("dmYHis"),
					"kode" => $key,
					"no_reg" => $no_reg
				);
				$this->db->insert("grouper_ralan_icd9", $dat);
			}
		}
		$this->db->where("g.no_reg", $no_reg);
		$q = $this->db->get("grouper_ralan_icd9 g");
		return $q;
	}
	function geticd9_inap($no_reg, $icd9)
	{
		$data = array();
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $key->kode_tarif, "kode_tindakan!=" => "FRM", "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd9[$tkey->icd9];
			}
			$t = $this->db->get_where("tarif_inap", ["kode_tindakan" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd9[$tkey->icd9];
			}
			$t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd9[$tkey->icd9];
			}
			$t = $this->db->get_where("tarif_ambulance", ["kode" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd9[$tkey->icd9];
			}
			$t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd9[$tkey->icd9];
			}
			$t = $this->db->get_where("tarif_penunjang_medis", ["kode" => $key->kode_tarif, "icd9!=" => ""]);
			foreach ($t->result() as $tkey) {
				$data[$tkey->icd9] = $icd9[$tkey->icd9];
			}
		}
		$id = date("dmYHis");
		foreach ($data as $key => $value) {
			$q = $this->db->get_where("grouper_inap_icd9", ["kode" => $key, "no_reg" => $no_reg]);
			if ($q->num_rows() <= 0) {
				$dat = array(
					"id" => $id++,
					"kode" => $key,
					"no_reg" => $no_reg
				);
				$this->db->insert("grouper_inap_icd9", $dat);
			}
		}
		$this->db->where("g.no_reg", $no_reg);
		$q = $this->db->get("grouper_inap_icd9 g");
		return $q;
	}
	function geticd9_inap1($no_reg, $icd9)
	{
		// $icd9 = $this->geticd9_array();
		$data = array();
		$this->db->select("k.*, t.icd9 as kode");
		$this->db->join("tarif_ralan t", "t.kode_tindakan = k.kode_tarif and t.kode_tindakan !='FRM'", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd9[$key->kode];
		}
		$this->db->select("k.*, t.icd9 as kode");
		$this->db->join("tarif_inap t", "t.kode_tindakan = k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		$data = array();
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd9[$key->kode];
		}
		$this->db->select("k.*, t.icd9 as kode");
		$this->db->join("tarif_radiologi t", "t.id_tindakan = k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd9[$key->kode];
		}
		$this->db->select("k.*, t.icd9 as kode");
		$this->db->join("tarif_ambulance t", "t.kode = k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd9[$key->kode];
		}
		$this->db->select("k.*, t.icd9 as kode");
		$this->db->join("tarif_lab t", "t.kode_tindakan = k.kode_tarif", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd9[$key->kode];
		}
		$this->db->select("k.*, t.icd9 as kode");
		$this->db->join("tarif_penunjang_medis t", "t.kode = k.kode_tarif", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $icd9[$key->kode];
		}

		$id = date("dmYHis");
		foreach ($data as $key => $value) {
			$q = $this->db->get_where("grouper_inap_icd9", ["kode" => $key, "no_reg" => $no_reg]);
			if ($q->num_rows() <= 0) {
				$dat = array(
					"id" => $id++,
					"kode" => $key,
					"no_reg" => $no_reg
				);
				$this->db->insert("grouper_inap_icd9", $dat);
			}
		}
		$this->db->where("g.no_reg", $no_reg);
		$q = $this->db->get("grouper_inap_icd9 g");
		return $q;
	}
	function geticd_inap($no_reg)
	{
		$this->db->select("k.*, m.kode, m.keterangan");
		$this->db->join("tarif_ralan t", "t.kode_tindakan = k.kode_tarif and t.kode_tindakan !='FRM'", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		$data = array();
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		$this->db->select("k.*, m.kode, m.keterangan");
		$this->db->join("tarif_radiologi t", "t.id_tindakan = k.kode_tarif", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		$this->db->select("k.*, m.kode, m.keterangan");
		$this->db->join("tarif_ambulance t", "t.kode = k.kode_tarif", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		$this->db->select("k.*, m.kode, m.keterangan");
		$this->db->join("tarif_lab t", "t.kode_tindakan = k.kode_tarif", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		$this->db->select("k.*, m.kode, m.keterangan");
		$this->db->join("tarif_penunjang_medis t", "t.kode = k.kode_tarif", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		$this->db->select("k.*, m.kode, m.keterangan");
		$this->db->join("tarif_operasi t", "t.kode = k.kode_tarif", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		$this->db->select("k.*, m.kode, m.keterangan");
		$this->db->join("tarif_inap t", "t.kode_tindakan = k.kode_tarif", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		$this->db->select("k.*, m.kode, m.keterangan");
		$this->db->join("tarif_opr t", "t.kode_tindakan = k.kode_tarif", "inner");
		$this->db->join("master_icd9 m", "m.kode = t.icd9", "inner");
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		return $data;
	}
	function getgrouper_ralan($no_reg)
	{
		$total = 0;
		$data = array();
		$q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->jumlah;
				} else {
					$data[$val->grouper] = $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_ambulance", ["kode" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->jumlah;
				} else {
					$data[$val->grouper] = $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->jumlah;
				} else {
					$data[$val->grouper] = $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_opr", ["kode_tindakan" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->jumlah;
				} else {
					$data[$val->grouper] = $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_operasi", ["kode" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->jumlah;
				} else {
					$data[$val->grouper] = $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_penunjang_medis", ["kode" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->jumlah;
				} else {
					$data[$val->grouper] = $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->jumlah;
				} else {
					$data[$val->grouper] = $key->jumlah;
				}
				$total += $key->jumlah;
			}
		}
		$this->db->where("no_reg_sebelumnya", $no_reg);
		$this->db->where("tujuan_poli", "0102024");
		$q = $this->db->get("pasien_ralan")->row();
		if (isset($q->no_reg)) {
			$noreg = $q->no_reg;
			$this->db->select("k.no_reg,t.grouper,sum(k.jumlah) as jumlah");
			$this->db->join("tarif_lab t", "t.kode_tindakan=k.kode_tarif", "inner");
			$this->db->group_by("t.grouper");
			$q = $this->db->get_where("kasir k", ["k.no_reg" => $noreg]);
			foreach ($q->result() as $key) {
				if (isset($data[$key->grouper])) {
					$data[$key->grouper] += $key->jumlah;
				} else {
					$data[$key->grouper] = $key->jumlah;
				}
			}
			$total += $key->jumlah;
		}
		$this->db->where("no_reg_sebelumnya", $no_reg);
		$this->db->where("tujuan_poli", "0102025");
		$q = $this->db->get("pasien_ralan")->row();
		if (isset($q->no_reg)) {
			$noreg = $q->no_reg;
			$this->db->select("k.no_reg,t.grouper,sum(k.jumlah) as jumlah");
			$this->db->join("tarif_radiologi t", "t.id_tindakan=k.kode_tarif", "inner");
			$this->db->group_by("t.grouper");
			$q = $this->db->get_where("kasir k", ["k.no_reg" => $noreg]);
			foreach ($q->result() as $key) {
				if (isset($data[$key->grouper])) {
					$data[$key->grouper] += $key->jumlah;
				} else {
					$data[$key->grouper] = $key->jumlah;
				}
				$total += $key->jumlah;
			}
		}
		$this->db->select("a.no_reg,sum(harga_obat_kronis) as jumlah");
		$q = $this->db->get_where("apotek a", ["a.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			if (isset($data["tarif11"])) {
				$data["tarif11"] += $key->jumlah;
			} else {
				$data["tarif11"] = $key->jumlah;
			}
			$total += $key->jumlah;
		}
		$data["total"] = $total;
		return $data;
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
		$this->db->select("i.*,p.no_bpjs,r.nama_ruangan,k.nama_kelas");
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
		$no_pasien = $this->session->userdata("no_pasien");
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
		$this->db->like("i.no_rm", $no_pasien);
		$this->db->or_like("no_reg", $no_pasien);
		$this->db->or_like("no_bpjs", $no_pasien);
		$this->db->or_like("no_sjp", $no_pasien);
		$this->db->or_like("p.nama_pasien", $no_pasien);
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
	function getinap_detail($no_pasien, $no_reg)
	{
		$this->db->select("pr.*,sp.id_bpjs,sp.keterangan as ket_status_pulang,d.nama_dokter,k.nama_kelas,r.nama_ruangan,k.kode_kelas_bpjs,ps.kode_bpjs as kode_perusahaan,p.jenis_kelamin,p.tgl_lahir,p.no_bpjs,p.alamat,p.nama_pasien,g.keterangan as ket_gol_pasien");
		$this->db->join("pasien p", "pr.no_rm=p.no_pasien", "inner");
		$this->db->join("kelas k", "k.kode_kelas=pr.kode_kelas", "left");
		$this->db->join("ruangan r", "r.kode_ruangan=pr.kode_ruangan", "left");
		$this->db->join("perusahaan ps", "ps.kode=p.perusahaan", "left");
		$this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "left");
		$this->db->join("dokter d", "d.id_dokter=pr.dpjp", "left");
		$this->db->join("status_pulang sp", "sp.id=pr.status_pulang", "left");
		$this->db->where("pr.no_rm", $no_pasien);
		$this->db->where("pr.no_reg", $no_reg);
		$q = $this->db->get("pasien_inap pr");
		return $q->row();
	}
	function getgrouper_inap($no_reg)
	{
		$data = array();
		$q = $this->db->get_where("kasir_inap k", ["k.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			$t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $key->kode_tarif, "kode_tindakan!=" => "FRM"]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->qty * $key->jumlah;
				} else {
					$data[$val->grouper] = $key->qty * $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_inap", ["kode_tindakan" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->qty * $key->jumlah;
				} else {
					$data[$val->grouper] = $key->qty * $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_ambulance", ["kode" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->qty * $key->jumlah;
				} else {
					$data[$val->grouper] = $key->qty * $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->qty * $key->jumlah;
				} else {
					$data[$val->grouper] = $key->qty * $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_opr", ["kode_tindakan" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->qty * $key->jumlah;
				} else {
					$data[$val->grouper] = $key->qty * $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_operasi", ["kode" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->qty * $key->jumlah;
				} else {
					$data[$val->grouper] = $key->qty * $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_penunjang_medis", ["kode" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->qty * $key->jumlah;
				} else {
					$data[$val->grouper] = $key->qty * $key->jumlah;
				}
				$total += $key->jumlah;
			}
			$t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $key->kode_tarif]);
			foreach ($t->result() as $val) {
				if (isset($data[$val->grouper])) {
					$data[$val->grouper] += $key->qty * $key->jumlah;
				} else {
					$data[$val->grouper] = $key->qty * $key->jumlah;
				}
				$total += $key->jumlah;
			}
		}
		$this->db->select("a.no_reg,sum(harga_obat_kronis) as jumlah");
		$q = $this->db->get_where("apotek_inap a", ["a.no_reg" => $no_reg]);
		foreach ($q->result() as $key) {
			if (isset($data["tarif11"])) {
				$data["tarif11"] += $key->jumlah;
			} else {
				$data["tarif11"] = $key->jumlah;
			}
		}
		return $data;
	}
	function geticd10()
	{
		$this->db->like("kode", $this->input->post("kode"));
		$this->db->or_like("nama", $this->input->post("kode"));
		$q = $this->db->get("master_icd");
		$data = [];
		foreach ($q->result() as $key) {
			$data[] = array('id' => $key->kode, 'label' => $key->nama);
		}
		return $data;
	}
	function geticd10_array()
	{
		$q = $this->db->get("master_icd");
		$data = [];
		foreach ($q->result() as $key) {
			$data[$key->kode] =  $key->nama;
		}
		return $data;
	}
	function geticd10detail()
	{
		$this->db->where("kode", $this->input->post("kode"));
		$q = $this->db->get("master_icd");
		$data = [];
		foreach ($q->result() as $key) {
			$data[] = array('id' => $key->kode, 'label' => $key->nama);
		}
		return $data;
	}
	function geticd9detail()
	{
		$this->db->where("kode", $this->input->post("kode"));
		$q = $this->db->get("master_icd9");
		$data = [];
		foreach ($q->result() as $key) {
			$data[] = array('id' => $key->kode, 'label' => $key->keterangan);
		}
		return $data;
	}
	function geticd9()
	{
		$this->db->like("kode", $this->input->post("kode"));
		$this->db->or_like("keterangan", $this->input->post("kode"));
		$q = $this->db->get("master_icd9");
		$data = [];
		foreach ($q->result() as $key) {
			$data[] = array('id' => $key->kode, 'label' => $key->keterangan);
		}
		return $data;
	}
	function geticd9_array()
	{
		$q = $this->db->get("master_icd9");
		$data = [];
		foreach ($q->result() as $key) {
			$data[$key->kode] = $key->keterangan;
		}
		return $data;
	}
	function geticd10_ralan($no_reg)
	{
		// $this->db->join("master_icd m","m.kode=g.kode","inner");
		$this->db->where("g.no_reg", $no_reg);
		$this->db->order_by("g.urut");
		$q = $this->db->get("grouper_ralan_icd10 g");
		return $q;
	}
	function simpan_icd10()
	{
		$q = $this->db->get_where("grouper_ralan_icd10", ["no_reg" => $this->input->post("no_reg")]);
		if ($q->num_rows() <= 0) $urut = 1;
		else $urut = 2;
		$data = array(
			"id" => date("dmYHis"),
			"kode" => $this->input->post("kode"),
			"no_reg" => $this->input->post("no_reg"),
			"urut" => $urut
		);
		$this->db->insert("grouper_ralan_icd10", $data);
	}
	function edit_icd10()
	{
		$data = array("kode" => $this->input->post("kode"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("grouper_ralan_icd10", $data);
	}
	function hapus_icd10()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("grouper_ralan_icd10");
	}
	function simpan_inap_icd10()
	{
		$q = $this->db->get_where("grouper_inap_icd10", ["no_reg" => $this->input->post("no_reg")]);
		if ($q->num_rows() <= 0) $urut = 1;
		else $urut = 2;
		$data = array(
			"id" => date("dmYHis"),
			"kode" => $this->input->post("kode"),
			"no_reg" => $this->input->post("no_reg"),
			"urut" => $urut
		);
		$this->db->insert("grouper_inap_icd10", $data);
	}
	function edit_inap_icd10()
	{
		$data = array("kode" => $this->input->post("kode"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("grouper_inap_icd10", $data);
	}
	function hapus_inap_icd10()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("grouper_inap_icd10");
	}
	function simpan_icd9()
	{
		$data = array(
			"id" => date("dmYHis"),
			"kode" => $this->input->post("kode"),
			"no_reg" => $this->input->post("no_reg")
		);
		$this->db->insert("grouper_ralan_icd9", $data);
	}
	function edit_icd9()
	{
		$data = array("kode" => $this->input->post("kode"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("grouper_ralan_icd9", $data);
	}
	function hapus_icd9()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("grouper_ralan_icd9");
	}
	function simpan_inap_icd9()
	{
		$data = array(
			"id" => date("dmYHis"),
			"kode" => $this->input->post("kode"),
			"no_reg" => $this->input->post("no_reg")
		);
		$this->db->insert("grouper_inap_icd9", $data);
	}
	function edit_inap_icd9()
	{
		$data = array("kode" => $this->input->post("kode"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("grouper_inap_icd9", $data);
	}
	function hapus_inap_icd9()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("grouper_inap_icd9");
	}
	function geticd10_inap($no_reg)
	{
		// $this->db->join("master_icd m","m.kode=g.kode","inner");
		$this->db->where("g.no_reg", $no_reg);
		$this->db->order_by("g.urut");
		$q = $this->db->get("grouper_inap_icd10 g");
		return $q;
	}
	function get_eklaim()
	{
		return $this->db->get("server_eklaim")->row();
	}
	function updatesep_ralan($no_reg, $no_sep)
	{
		$data = array("no_sjp" => $no_sep);
		$this->db->where("no_reg", $no_reg);
		$this->db->update("pasien_ralan", $data);
	}
	function updatepasien_ralan($no_reg, $kode_eclaim, $tarif_bpjs, $tarif_rumahsakit, $tarif_obat_kronis)
	{
		$data = array("kode_eclaim" => $kode_eclaim, "tarif_bpjs" => $tarif_bpjs, "tarif_rumahsakit" => $tarif_rumahsakit, "obat_kronis" => $tarif_obat_kronis);
		$this->db->where("no_reg", $no_reg);
		$this->db->update("pasien_ralan", $data);
	}
	function updatepasien_inap($no_reg, $kode_eclaim, $tarif_bpjs, $tarif_rumahsakit, $tarif_obat_kronis)
	{
		$data = array("kode_eclaim" => $kode_eclaim, "tarif_bpjs" => $tarif_bpjs, "tarif_rumahsakit" => $tarif_rumahsakit, "obat_kronis" => $tarif_obat_kronis);
		$this->db->where("no_reg", $no_reg);
		$this->db->update("pasien_inap", $data);
	}
	function updatepasienpct_inap($no_reg, $pct)
	{
		$data = array("add_payment_pct" => $pct);
		$this->db->where("no_reg", $no_reg);
		$this->db->update("pasien_inap", $data);
	}
	function updatehakkelas_inap($no_reg, $hak_kelas)
	{
		$data = array("hak_kelas" => $hak_kelas);
		$this->db->where("no_reg", $no_reg);
		$this->db->update("pasien_inap", $data);
	}
	function updatesep_inap($no_reg, $no_sep)
	{
		$data = array("no_sjp" => $no_sep);
		$this->db->where("no_reg", $no_reg);
		$this->db->update("pasien_inap", $data);
	}
	function updatebayi_inap($no_reg, $birth_weight)
	{
		$data = array("birth_weight" => $birth_weight);
		$this->db->where("no_reg", $no_reg);
		$this->db->update("pasien_inap", $data);
	}
	function updatedpjp_inap($no_reg, $dpjp)
	{
		$data = array("dpjp" => $dpjp);
		$this->db->where("no_reg", $no_reg);
		$this->db->update("pasien_inap", $data);
	}
	function updatetgllahir_ralan($no_rm, $tgl)
	{
		$data = array("tgl_lahir" => date("Y-m-d", strtotime($tgl)));
		$this->db->where("no_pasien", $no_rm);
		$this->db->update("pasien", $data);
	}
	function updatetgllahir_inap($no_rm, $tgl)
	{
		$data = array("tgl_lahir" => date("Y-m-d", strtotime($tgl)));
		$this->db->where("no_pasien", $no_rm);
		$this->db->update("pasien", $data);
	}
	function updatepdf_ralan($no_sep, $pdf)
	{
		$data = array("eklaim_pdf" => $pdf);
		$this->db->where("no_sjp", $no_sep);
		$this->db->update("pasien_ralan", $data);
	}
	function updatepdf_inap($no_sep, $pdf)
	{
		$data = array("eklaim_pdf" => $pdf);
		$this->db->where("no_sjp", $no_sep);
		$this->db->update("pasien_inap", $data);
	}
	function edit_urut()
	{
		if ($this->input->post("urut") == 1) {
			$data = array("urut" => 2);
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update("grouper_ralan_icd10", $data);
		}
		$data = array("urut" => $this->input->post("urut"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->update("grouper_ralan_icd10", $data);
	}
	function edit_indeks_urut()
	{
		if ($this->input->post("urut") == 1) {
			$data = array("urut" => 2);
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update("indeks_ralan_icd10", $data);
		}
		$data = array("urut" => $this->input->post("urut"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->update("indeks_ralan_icd10", $data);
	}
	function edit_urut_inap()
	{
		if ($this->input->post("urut") == 1) {
			$data = array("urut" => 2);
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update("grouper_inap_icd10", $data);
		}
		$data = array("urut" => $this->input->post("urut"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->update("grouper_inap_icd10", $data);
	}
	function edit_indeks_urut_inap()
	{
		if ($this->input->post("urut") == 1) {
			$data = array("urut" => 2);
			$this->db->where("no_reg", $this->input->post("no_reg"));
			$this->db->update("indeks_inap_icd10", $data);
		}
		$data = array("urut" => $this->input->post("urut"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->update("indeks_inap_icd10", $data);
	}
	function getdokter()
	{
		$q = $this->db->get("dokter");
		return $q;
	}
	function simpan_indeksicd10()
	{
		$q = $this->db->get_where("indeks_ralan_icd10", ["no_reg" => $this->input->post("no_reg")]);
		if ($q->num_rows() <= 0) $urut = 1;
		else $urut = 2;
		$data = array(
			"id" => date("dmYHis"),
			"kode" => $this->input->post("kode"),
			"no_reg" => $this->input->post("no_reg"),
			"urut" => $urut
		);
		$this->db->insert("indeks_ralan_icd10", $data);
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("pasien_ralan", ["tanggal_indeks" => date("Y-m-d H:i:s")]);
	}
	function simpan_indeksicd10_inap()
	{
		$q = $this->db->get_where("indeks_inap_icd10", ["no_reg" => $this->input->post("no_reg")]);
		if ($q->num_rows() <= 0) $urut = 1;
		else $urut = 2;
		$data = array(
			"id" => date("dmYHis"),
			"kode" => $this->input->post("kode"),
			"no_reg" => $this->input->post("no_reg"),
			"urut" => $urut
		);
		$this->db->insert("indeks_inap_icd10", $data);
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("pasien_inap", ["tanggal_indeks" => date("Y-m-d H:i:s")]);
	}
	function edit_indeksicd10()
	{
		$data = array("kode" => $this->input->post("kode"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("indeks_ralan_icd10", $data);
	}
	function edit_indeksicd10_inap()
	{
		$data = array("kode" => $this->input->post("kode"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("indeks_inap_icd10", $data);
	}
	function simpan_indeksicd9()
	{
		$data = array(
			"id" => date("dmYHis"),
			"kode" => $this->input->post("kode"),
			"no_reg" => $this->input->post("no_reg")
		);
		$this->db->insert("indeks_ralan_icd9", $data);
	}
	function simpan_indeksicd9_inap()
	{
		$data = array(
			"id" => date("dmYHis"),
			"kode" => $this->input->post("kode"),
			"no_reg" => $this->input->post("no_reg")
		);
		$this->db->insert("indeks_inap_icd9", $data);
	}
	function edit_indeksicd9()
	{
		$data = array("kode" => $this->input->post("kode"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("indeks_ralan_icd9", $data);
	}
	function edit_indeksicd9_inap()
	{
		$data = array("kode" => $this->input->post("kode"));
		$this->db->where("id", $this->input->post("id"));
		$this->db->where("no_reg", $this->input->post("no_reg"));
		$this->db->update("indeks_inap_icd9", $data);
	}
	function hapus_indeksicd10()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("indeks_ralan_icd10");
	}
	function hapus_indeksicd10_inap()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("indeks_inap_icd10");
	}
	function hapus_indeksicd9()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("indeks_ralan_icd9");
	}
	function hapus_indeksicd9_inap()
	{
		$this->db->where("id", $this->input->post("id"));
		$this->db->delete("indeks_inap_icd9");
	}
	function getindeksicd10_ralan($no_reg)
	{
		$this->db->where("no_reg", $no_reg);
		$q = $this->db->get("indeks_ralan_icd10");
		if ($q->num_rows() <= 0) {
			$this->db->where("no_reg", $no_reg);
			$n = $this->db->get("grouper_ralan_icd10");
			$id = date("dmYHis");
			foreach ($n->result() as $key) {
				$data = array(
					"id" => $id++,
					"kode" => $key->kode,
					"no_reg" => $no_reg,
					"urut" => $key->urut
				);
				$this->db->insert("indeks_ralan_icd10", $data);
			}
		}
		$this->db->join("master_icd m", "m.kode=g.kode", "inner");
		$this->db->where("g.no_reg", $no_reg);
		$this->db->order_by("g.urut");
		$query = $this->db->get("indeks_ralan_icd10 g");
		return $query;
	}
	function getindeksicd10_inap($no_reg)
	{
		$this->db->where("no_reg", $no_reg);
		$q = $this->db->get("indeks_inap_icd10");
		if ($q->num_rows() <= 0) {
			$this->db->where("no_reg", $no_reg);
			$n = $this->db->get("grouper_inap_icd10");
			$id = date("dmYHis");
			foreach ($n->result() as $key) {
				$data = array(
					"id" => $id++,
					"kode" => $key->kode,
					"no_reg" => $no_reg,
					"urut" => $key->urut
				);
				$this->db->insert("indeks_inap_icd10", $data);
			}
		}
		$this->db->join("master_icd m", "m.kode=g.kode", "inner");
		$this->db->where("g.no_reg", $no_reg);
		$this->db->order_by("g.urut");
		$query = $this->db->get("indeks_inap_icd10 g");
		return $query;
	}
	function getindeksicd9_ralan($no_reg)
	{
		$q = $this->db->get_where("indeks_ralan_icd9", ["no_reg" => $no_reg]);
		if ($q->num_rows() <= 0) {
			$n = $this->db->get_where("grouper_ralan_icd9", ["no_reg" => $no_reg]);
			$id = date("dmYHis");
			foreach ($n->result() as $row) {
				$data = array(
					"id" => $id++,
					"kode" => $row->kode,
					"no_reg" => $no_reg
				);
				$this->db->insert("indeks_ralan_icd9", $data);
			}
		}
		$this->db->join("master_icd9 m", "m.kode=g.kode", "inner");
		$this->db->where("g.no_reg", $no_reg);
		$q = $this->db->get("indeks_ralan_icd9 g");
		return $q;
	}
	// function getindeksicd10_inap($no_reg){
	//    	$this->db->join("master_icd m","m.kode=g.kode","inner");
	//    	$this->db->where("g.no_reg",$no_reg);
	//    	$this->db->order_by("g.urut");
	//    	$q = $this->db->get("indeks_inap_icd10 g");
	//    	return $q;
	//    }
	function getindeksicd9_inap($no_reg)
	{
		$q = $this->db->get_where("indeks_inap_icd9", ["no_reg" => $no_reg]);
		if ($q->num_rows() <= 0) {
			$n = $this->db->get_where("grouper_inap_icd9", ["no_reg" => $no_reg]);
			$id = date("dmYHis");
			foreach ($n->result() as $row) {
				$data = array(
					"id" => $id++,
					"kode" => $row->kode,
					"no_reg" => $no_reg
				);
				$this->db->insert("indeks_inap_icd9", $data);
			}
		}
		$this->db->join("master_icd9 m", "m.kode=g.kode", "inner");
		$this->db->where("g.no_reg", $no_reg);
		$q = $this->db->get("indeks_inap_icd9 g");
		return $q;
	}
	function getfilepdf_ralan($no_reg)
	{
		$this->db->where("no_reg", $no_reg);
		$q = $this->db->get("pdf_ralan");
		return $q;
	}
	function getfilepdf_noregsebelumnya_ralan($no_reg)
	{
		$this->db->select("p.*");
		$this->db->where("pr.no_reg_sebelumnya", $no_reg);
		$this->db->join("pasien_ralan pr", "pr.no_reg=p.no_reg", "inner");
		$q = $this->db->get("pdf_ralan p");
		return $q;
	}
	function uploadpdf_ralan($nama_file)
	{
		$data = array(
			'no_reg' => $this->input->post("no_reg"),
			'id_file' => date("dmYHis"),
			'file_pdf' => $nama_file,
			'tgl_upload' => date("Y-m-d H:i:s"),
		);
		$this->db->insert("pdf_ralan", $data);
		return "success-File berhasil diupload";
	}
	function getfilepdf_inap($no_reg)
	{
		$this->db->where("no_reg", $no_reg);
		$q = $this->db->get("pdf_inap");
		return $q;
	}
	function uploadpdf_inap($nama_file)
	{
		$data = array(
			'no_reg' => $this->input->post("no_reg"),
			'id_file' => date("dmYHis"),
			'file_pdf' => $nama_file,
			'tgl_upload' => date("Y-m-d H:i:s"),
		);
		$this->db->insert("pdf_inap", $data);
		return "success-File berhasil diupload";
	}
	function rekap_ralan()
	{
		$this->db->select("pr.*,p.*,pi.file_pdf");
		$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($this->input->post("tgl1_rekap"))));
		$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($this->input->post("tgl2_rekap"))));
		$this->db->where("pr.no_sjp!=", "");
		$this->db->where("pr.status_bayar", "TAGIH");
		$this->db->where("pr.layan!=", "2");
		$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
		$this->db->join("pdf_ralan pi", "pi.no_reg=pr.no_reg", "left");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_ralan pr");
		return $q->result();
	}
	function rekap_inap()
	{
		if ($this->input->post("resume")) {
			$this->db->select("pr.*,p.no_pasien,p.nama_pasien,p.no_bpjs,pi.file_pdf");
			$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($this->input->post("tgl1_rekap"))));
			$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($this->input->post("tgl2_rekap"))));
			$this->db->where("pr.no_sjp!=", "");
			$this->db->where("pr.status_bayar", "TAGIH");
			$this->db->like("pi.file_pdf", "resume");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
			$this->db->group_by("pr.no_reg");
			$q = $this->db->get("pasien_inap pr");
		} else {
			$this->db->select("pr.no_reg");
			$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($this->input->post("tgl1_rekap"))));
			$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($this->input->post("tgl2_rekap"))));
			$this->db->where("pr.no_sjp!=", "");
			$this->db->where("pr.status_bayar", "TAGIH");
			$this->db->like("pi.file_pdf", "resume");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
			$this->db->group_by("pr.no_reg");
			$q = $this->db->get("pasien_inap pr");
			$koma = $no_reg = "";
			foreach ($q->result() as $row) {
				$no_reg .= $koma . $row->no_reg;
				$koma = ",";
			}
			if ($no_reg != "") {
				$this->db->select("pr.*,p.no_pasien,p.nama_pasien,p.no_bpjs,'-' as file_pdf");
				$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($this->input->post("tgl1_rekap"))));
				$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($this->input->post("tgl2_rekap"))));
				$this->db->where("pr.no_sjp!=", "");
				$this->db->where("pr.status_bayar", "TAGIH");
				$this->db->where("pr.no_reg NOT IN (" . $no_reg . ")", NULL, FALSE);
				$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
				$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
				$this->db->group_by("pr.no_reg");
				$q = $this->db->get("pasien_inap pr");
			} else {
				$this->db->select("pr.*,p.no_pasien,p.nama_pasien,p.no_bpjs,'-' as file_pdf");
				$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($this->input->post("tgl1_rekap"))));
				$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($this->input->post("tgl2_rekap"))));
				$this->db->where("pr.no_sjp!=", "");
				$this->db->where("pr.status_bayar", "TAGIH");
				$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
				$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
				$this->db->group_by("pr.no_reg");
				$q = $this->db->get("pasien_inap pr");
			}
		}
		return $q->result();
	}
	function cetak_rekap_ralan($tgl1, $tgl2)
	{
		$this->db->select("pr.*,p.*,pi.file_pdf");
		$this->db->where("date(pr.tanggal_pulang)>=", date("Y-m-d", strtotime($tgl1)));
		$this->db->where("date(pr.tanggal_pulang)<=", date("Y-m-d", strtotime($tgl2)));
		$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
		$this->db->join("pdf_ralan pi", "pi.no_reg=pr.no_reg", "left");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_ralan pr");
		return $q;
	}
	function cetak_rekap_inap($tgl1, $tgl2, $resume)
	{
		if ($resume) {
			$this->db->select("pr.*,p.*,pi.file_pdf");
			$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("pr.no_sjp!=", "");
			$this->db->where("pr.status_bayar", "TAGIH");
			$this->db->like("pi.file_pdf", "resume");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
			$this->db->group_by("pr.no_reg");
			$q = $this->db->get("pasien_inap pr");
		} else {
			$this->db->select("pr.no_reg");
			$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($tgl1)));
			$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($tgl2)));
			$this->db->where("pr.no_sjp!=", "");
			$this->db->where("pr.status_bayar", "TAGIH");
			$this->db->like("pi.file_pdf", "resume");
			$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
			$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
			$this->db->group_by("pr.no_reg");
			$q = $this->db->get("pasien_inap pr");
			$koma = $no_reg = "";
			foreach ($q->result() as $row) {
				$no_reg .= $koma . $row->no_reg;
				$koma = ",";
			}
			if ($no_reg != "") {
				$this->db->select("pr.*,p.no_pasien,p.nama_pasien,p.no_bpjs,'-' as file_pdf");
				$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($tgl1)));
				$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($tgl2)));
				$this->db->where("pr.no_sjp!=", "");
				$this->db->where("pr.status_bayar", "TAGIH");
				$this->db->where("pr.no_reg NOT IN (" . $no_reg . ")", NULL, FALSE);
				$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
				$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
				$this->db->group_by("pr.no_reg");
				$q = $this->db->get("pasien_inap pr");
			} else {
				$this->db->select("pr.*,p.no_pasien,p.nama_pasien,p.no_bpjs,'-' as file_pdf");
				$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($tgl1)));
				$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($tgl2)));
				$this->db->where("pr.no_sjp!=", "");
				$this->db->where("pr.status_bayar", "TAGIH");
				$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
				$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
				$this->db->group_by("pr.no_reg");
				$q = $this->db->get("pasien_inap pr");
			}
		}
		return $q;
	}
	function getrekaplupis()
	{
		$this->db->select("pr.*,p.*,pi.file_pdf");
		$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($this->input->post("tgl2_rekap"))));
		$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($this->input->post("tgl1_rekap"))));
		$this->db->where("pr.no_sjp!=", "");
		$this->db->where("pr.status_bayar", "TAGIH");
		$this->db->where("pr.layan!=", "2");
		$this->db->like("pi.file_pdf", "lupis");
		$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
		$this->db->join("pdf_ralan pi", "pi.no_reg=pr.no_reg", "left");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_ralan pr");
		$data = array();
		$data["ralan"] = $q->result();
		$this->db->select("pr.*,p.*,pi.file_pdf");
		$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($this->input->post("tgl1_rekap"))));
		$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($this->input->post("tgl2_rekap"))));
		$this->db->where("pr.no_sjp!=", "");
		$this->db->where("pr.status_bayar", "TAGIH");
		// $this->db->where("pr.layan!=","2");
		$this->db->like("pi.file_pdf", "lupis");
		$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
		$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_inap pr");
		$data["ranap"] = $q->result();
		return $data;
	}
	function cetak_rekaplupis($tgl1, $tgl2)
	{
		$this->db->select("pr.*,p.*,pi.file_pdf");
		$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
		$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
		$this->db->where("pr.no_sjp!=", "");
		$this->db->where("pr.status_bayar", "TAGIH");
		$this->db->where("pr.layan!=", "2");
		$this->db->like("pi.file_pdf", "lupis");
		$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
		$this->db->join("pdf_ralan pi", "pi.no_reg=pr.no_reg", "left");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_ralan pr");
		$data = array();
		$data["ralan"] = $q->result();
		$this->db->select("pr.*,p.*,pi.file_pdf");
		$this->db->where("date(pr.tgl_keluar)>=", date("Y-m-d", strtotime($tgl1)));
		$this->db->where("date(pr.tgl_keluar)<=", date("Y-m-d", strtotime($tgl2)));
		$this->db->where("pr.no_sjp!=", "");
		$this->db->where("pr.status_bayar", "TAGIH");
		// $this->db->where("pr.layan!=","2");
		$this->db->like("pi.file_pdf", "lupis");
		$this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
		$this->db->join("pdf_inap pi", "pi.no_reg=pr.no_reg", "left");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_inap pr");
		$data["ranap"] = $q->result();
		return $data;
	}
	function getrekapobatkronis()
	{
		$this->db->select("pr.*,p.nama_pasien,p.no_bpjs,pi.file_pdf");
		$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($this->input->post("tgl1_rekap"))));
		$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($this->input->post("tgl2_rekap"))));
		$this->db->where("pr.no_sjp!=", "");
		$this->db->where("pr.status_bayar", "TAGIH");
		$this->db->where("pr.layan!=", "2");
		// $this->db->like("pi.file_pdf", "resep");
		$this->db->where("pr.obat_kronis>", 0);
		$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
		$this->db->join("pdf_ralan pi", "pi.no_reg=pr.no_reg", "left");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_ralan pr");
		$data = array();
		$data = $q->result();
		return $data;
	}
	function cetak_rekapobatkronis($tgl1, $tgl2)
	{
		$this->db->select("pr.*,p.nama_pasien,p.no_bpjs");
		$this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime("$tgl1")));
		$this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime("$tgl2")));
		$this->db->where("pr.no_sjp!=", "");
		$this->db->where("pr.status_bayar", "TAGIH");
		$this->db->where("pr.layan!=", "2");
		$this->db->where("pr.obat_kronis>", 0);
		$this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
		$this->db->group_by("pr.no_reg");
		$q = $this->db->get("pasien_ralan pr");
		$data = array();
		$data = $q->result();
		return $data;
	}
	function rekap_klaim()
	{
		$this->db->select("KAMAR_AKOMODASI as kamar,month(ADMISSION_DATE) as bulan,STATUS as status,TARIF_INACBG as tarif_bpjs,TARIF_RS as tarif_rs");
		$q = $this->db->get("rekap_klaim");
		$data = array();
		foreach ($q->result() as $row) {
			if ($row->kamar == 0) {
				if (isset($data["ralan"][$row->bulan]["total"])) {
					$data["ralan"][$row->bulan]["total"] += 1;
				} else {
					$data["ralan"][$row->bulan]["total"] = 1;
				}
				if (isset($data["ralan"][$row->bulan][$row->status])) {
					$data["ralan"][$row->bulan][$row->status] += 1;
				} else {
					$data["ralan"][$row->bulan][$row->status] = 1;
				}
				if (isset($data["ralan"][$row->bulan]["tarif_bpjs"])) {
					$data["ralan"][$row->bulan]["tarif_bpjs"] += $row->tarif_bpjs;
				} else {
					$data["ralan"][$row->bulan]["tarif_bpjs"] = $row->tarif_bpjs;
				}
				if (isset($data["ralan"][$row->bulan]["tarif_rs"])) {
					$data["ralan"][$row->bulan]["tarif_rs"] += $row->tarif_rs;
				} else {
					$data["ralan"][$row->bulan]["tarif_rs"] = $row->tarif_rs;
				}
			} else {
				if (isset($data["ranap"][$row->bulan]["total"])) {
					$data["ranap"][$row->bulan]["total"] += 1;
				} else {
					$data["ranap"][$row->bulan]["total"] = 1;
				}
				if (isset($data["ranap"][$row->bulan][$row->status])) {
					$data["ranap"][$row->bulan][$row->status] += 1;
				} else {
					$data["ranap"][$row->bulan][$row->status] = 1;
				}
				if (isset($data["ranap"][$row->bulan]["tarif_bpjs"])) {
					$data["ranap"][$row->bulan]["tarif_bpjs"] += $row->tarif_bpjs;
				} else {
					$data["ranap"][$row->bulan]["tarif_bpjs"] = $row->tarif_bpjs;
				}
				if (isset($data["ranap"][$row->bulan]["tarif_rs"])) {
					$data["ranap"][$row->bulan]["tarif_rs"] += $row->tarif_rs;
				} else {
					$data["ranap"][$row->bulan]["tarif_rs"] = $row->tarif_rs;
				}
			}
		}
		return $data;
	}
}
