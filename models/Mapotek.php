<?php
class Mapotek extends CI_Model{
   function __construct()
    {
        parent::__construct();
    }
    function getpasien_ralan($igd=false,$page,$offset){
		$poli_kode = $this->session->userdata("poli_kode");
		$kode_dokter = $this->session->userdata("kode_dokter");
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$no_pasien = $this->session->userdata("no_pasien");
		$no_reg = $this->session->userdata("no_reg");
		$nama = $this->session->userdata("nama");
		$this->db->select("pr.*,p.telpon,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, g.keterangan as gol_pasien");
		// if ($no_pasien!="") {
		// 	$no_pasien = "000000".$no_pasien;
		// 	$this->db->where("p.no_pasien",substr($no_pasien,-6));
		// }
		$this->db->where("pr.layan!=","2");
		if ($igd){
			$this->db->where("pr.tujuan_poli","0102030");
		} else {
			$this->db->where("pr.tujuan_poli!=","0102030");
		}
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
		if ($poli_kode!="") {
			$this->db->where("pr.tujuan_poli",$poli_kode);
		}
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
		$this->db->join("poliklinik pol","pol.kode=pr.dari_poli","left");
		$this->db->join("poliklinik pol2","pol2.kode=pr.tujuan_poli","left");
		$query = $this->db->get("pasien_ralan pr",$page,$offset);
		return $query;
	}

	function getralan_detail($no_pasien,$no_reg){
		$this->db->select("pr.*,p.alamat,p.nama_pasien,pl.keterangan as poli");
		$this->db->join("pasien p","pr.no_pasien=p.no_pasien");
		$this->db->join("poliklinik pl","pl.kode=pr.tujuan_poli");
		$this->db->where("pr.no_pasien",$no_pasien);
		$this->db->where("pr.no_reg",$no_reg);
		$q = $this->db->get("pasien_ralan pr");
		return $q->row();
	}
	function getinap_detail($no_pasien,$no_reg){
		$this->db->select("pi.*,p.nama_pasien,r.nama_ruangan,k.nama_kelas");
		$this->db->join("pasien p","pi.no_rm=p.no_pasien");
		$this->db->join("jenis_kelamin jk","jk.jenis_kelamin=p.jenis_kelamin");
		$this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
		$this->db->join("kelas k","k.kode_kelas=pi.kode_kelas","left");
		$this->db->join("dokter d","d.id_dokter=pi.dokter","left");
		$this->db->where("pi.no_rm",$no_pasien);
		$this->db->where("pi.no_reg",$no_reg);
		$q = $this->db->get("pasien_inap pi");
		return $q->row();
	}
	function getapotek($no_reg){
		$this->db->select("apotek.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
		$this->db->join("waktu w","w.kode = apotek.waktu","left");
		$this->db->join("waktu_lainnya wl","wl.kode = apotek.waktu_lainnya","left");
		$this->db->join("aturan_pakai a","a.kode = apotek.aturan_pakai","left");
		$this->db->order_by("id,nama_obat");
		$q = $this->db->get_where("apotek",["no_reg" => $no_reg]);
		return $q;
	}
  function getapotekigd_inap($no_reg,$id_dokter="",$tgl1="",$tgl2=""){
        $this->db->select("apotek_inap.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
		if ($tgl1!=""){
			$this->db->where("tanggal>=",date("Y-m-d",strtotime($tgl1)));
        	$this->db->where("tanggal<=",date("Y-m-d",strtotime($tgl2)));
        }
        if ($id_dokter!=""){
			$this->db->where("dokter",$id_dokter);
        }
    $this->db->where("depo","igd");
		$this->db->join("waktu w","w.kode = apotek_inap.waktu","left");
		$this->db->join("waktu_lainnya wl","wl.kode = apotek_inap.waktu_lainnya","left");
		$this->db->join("aturan_pakai a","a.kode = apotek_inap.aturan_pakai","left");
		$this->db->order_by("tanggal,nama_obat");
		$q = $this->db->get_where("apotek_inap",["no_reg" => $no_reg]);
		return $q;
	}
	function getapotek_inap($no_reg,$id_dokter="",$tgl1="",$tgl2=""){
        $this->db->select("apotek_inap.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
		if ($tgl1!=""){
			$this->db->where("tanggal>=",date("Y-m-d",strtotime($tgl1)));
        	$this->db->where("tanggal<=",date("Y-m-d",strtotime($tgl2)));
        }
        if ($id_dokter!=""){
			$this->db->where("dokter",$id_dokter);
        }
		$this->db->join("waktu w","w.kode = apotek_inap.waktu","left");
		$this->db->join("waktu_lainnya wl","wl.kode = apotek_inap.waktu_lainnya","left");
		$this->db->join("aturan_pakai a","a.kode = apotek_inap.aturan_pakai","left");
		$this->db->order_by("tanggal,nama_obat");
		$q = $this->db->get_where("apotek_inap",["no_reg" => $no_reg]);
		return $q;
	}
	function getterimaapotek_inap($no_reg,$id_dokter="",$tgl1="",$tgl2=""){
        $this->db->select("apotek_inap.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
		if ($tgl1!=""){
			$this->db->where("tanggal>=",date("Y-m-d",strtotime($tgl1)));
        	$this->db->where("tanggal<=",date("Y-m-d",strtotime($tgl2)));
        }
        if ($id_dokter!=""){
			$this->db->where("dokter",$id_dokter);
        }
		$this->db->join("waktu w","w.kode = apotek_inap.waktu","left");
		$this->db->join("waktu_lainnya wl","wl.kode = apotek_inap.waktu_lainnya","left");
		$this->db->join("aturan_pakai a","a.kode = apotek_inap.aturan_pakai","left");
        $this->db->where("apotek_inap.terima","0");
        $this->db->order_by("tanggal,nama_obat");
		$q = $this->db->get_where("apotek_inap",["no_reg" => $no_reg]);
		return $q;
	}
	function getapotek_inap_cetak($no_reg,$id_dokter,$tgl1="",$tgl2=""){
        $this->db->select("apotek_inap.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
		if ($tgl1!=""){
			$this->db->where("tanggal>=",date("Y-m-d",strtotime($tgl1)));
        	$this->db->where("tanggal<=",date("Y-m-d",strtotime($tgl2)));
        }
        if ($id_dokter!="all"){
			$this->db->where("dokter",$id_dokter);
        }
		$this->db->join("waktu w","w.kode = apotek_inap.waktu","left");
		$this->db->join("waktu_lainnya wl","wl.kode = apotek_inap.waktu_lainnya","left");
		$this->db->join("aturan_pakai a","a.kode = apotek_inap.aturan_pakai","left");
		$this->db->order_by("tanggal,nama_obat");
		$q = $this->db->get_where("apotek_inap",["no_reg" => $no_reg]);
		return $q;
	}
	function getapotek_detail($no_reg){
		$q = $this->db->get_where("transaksi_apotek",["no_reg"=>$no_reg]);
		return $q;
	}
	function getobat(){
		return $this->db->get("farmasi_data_obat");
	}
	function addobat(){
		$obat = $this->input->post("obat");
		$id = date("dmyHis");
		foreach ($obat as $key => $value) {
			$this->db->select("nama,pak2,hrg_jual");
			$t = $this->db->get_where("farmasi_data_obat",["kode" => $value]);
			if ($t->num_rows()>0){
				$q = $t->row();
				$data = array(
							"id" => $id,
							"no_reg" => $this->input->post("no_reg"),
							"kode_obat" => $value,
							"nama_obat" => $q->nama,
							"qty" => 1,
							"satuan" => $q->pak2,
							"jumlah" => $q->hrg_jual,
							"qty_obat_kronis" => 0,
							"harga_obat_kronis" => 0
						);
				$this->db->insert("apotek",$data);
				$id++;
			}
		}
	}
	function addobat_inap(){
		$obat = $this->input->post("obat");
		$id = date("dmyHis");
		foreach ($obat as $key => $value) {
			$this->db->select("nama,pak2,hrg_jual");
			$t = $this->db->get_where("farmasi_data_obat",["kode" => $value]);
			if ($t->num_rows()>0){
				$q = $t->row();
				$data = array(
							"id" => $id,
							"tanggal" => date("Y-m-d",strtotime($this->input->post("tanggal"))),
							"no_reg" => $this->input->post("no_reg"),
							"dokter" => $this->input->post("dokter"),
							"kode_obat" => $value,
							"nama_obat" => $q->nama,
							"qty" => 1,
							"satuan" => $q->pak2,
							"jumlah" => $q->hrg_jual
						);
				$this->db->insert("apotek_inap",$data);
				$id++;
			}
		}
	}
	function changedata($change){
		$this->db->select("hrg_jual");
		$q = $this->db->get_where("farmasi_data_obat",["kode"=>$this->input->post("obat")])->row();
		$this->db->where("id",$this->input->post("id"));
		if ($change=="qty"){
			$this->db->set("qty",$this->input->post("value"));
			$this->db->set("jumlah",$this->input->post("value")*$q->hrg_jual);
		} else {
			$this->db->set("qty_obat_kronis",$this->input->post("value"));
			$this->db->set("harga_obat_kronis",$this->input->post("value")*$q->hrg_obat_jual);
		}
		$this->db->update("apotek");
	}
	function changedata_inap(){
		$this->db->select("hrg_jual");
		$q = $this->db->get_where("farmasi_data_obat",["kode"=>$this->input->post("obat")])->row();
		$this->db->where("id",$this->input->post("id"));
		$this->db->set("qty",$this->input->post("value"));
		$this->db->set("jumlah",$this->input->post("value")*$q->hrg_jual);
		$this->db->update("apotek_inap");
	}
	function hapusobat(){
		$this->db->where("id",$this->input->post("id"));
		$this->db->delete("apotek");
	}
	function hapusobat_inap(){
		$this->db->where("id",$this->input->post("id"));
		$this->db->delete("apotek_inap");
	}
	function simpanobat(){
		$dat = array();
		$disc_nominal = $this->input->post("disc_nominal");
		$bayar = $this->input->post("total");
		$q = $this->db->get_where("transaksi_apotek",["no_reg"=>$this->input->post("no_reg")]);
		if ($q->num_rows()>0){
			$q = $q->row();
			$id = $q->id;
			$dat = array(
					"jumlah_disc" => $disc_nominal,
					"jumlah_bayar" => $bayar
			);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->update('transaksi_apotek', $dat);
		} else {
			$id = date("dmyHis");
			$dat = array(
					"id" => $id,
					"tanggal" => date("Y-m-d"),
					"no_reg" => $this->input->post("no_reg"),
					"jumlah_disc" => $disc_nominal,
					"jumlah_bayar" => $bayar
			   );
			$this->db->insert('transaksi_apotek', $dat);
		}
		$q = $this->db->get_where("kasir",["no_reg"=>$this->input->post("no_reg"),"kode_tarif"=>"FRM"]);
		if ($q->num_rows()>0){
			$dat = array(
				"jumlah" => $bayar
			);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->where("kode_tarif","FRM");
			$this->db->update('kasir', $dat);
		} else {
			$dat = array(
				"id" => date("dmyHis"),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => "FRM",
				"jumlah" => $bayar
			);
			$this->db->insert('kasir', $dat);
		}
	}
	function simpanobat_inap(){
		$dat = array();
		$disc_nominal = $this->input->post("disc_nominal");
		$bayar = $this->input->post("total");
		$q = $this->db->get_where("transaksi_apotek",["no_reg"=>$this->input->post("no_reg")]);
		if ($q->num_rows()>0){
			$q = $q->row();
			$id = $q->id;
			$dat = array(
					"jumlah_disc" => $disc_nominal,
					"jumlah_bayar" => $bayar
			);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->update('transaksi_apotek', $dat);
		} else {
			$id = date("dmyHis");
			$dat = array(
					"id" => $id,
					"tanggal" => date("Y-m-d"),
					"no_reg" => $this->input->post("no_reg"),
					"jumlah_disc" => $disc_nominal,
					"jumlah_bayar" => $bayar
			   );
			$this->db->insert('transaksi_apotek', $dat);
		}
		$q = $this->db->get_where("kasir_inap",["no_reg"=>$this->input->post("no_reg"),"kode_tarif"=>"FRM"]);
		if ($q->num_rows()>0){
			$dat = array(
				"jumlah" => $bayar
			);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->where("kode_tarif","FRM");
			$this->db->update('kasir_inap', $dat);
		} else {
			$dat = array(
				"id" => date("dmyHis"),
				"tanggal" => date("Y-m-d",strtotime($this->input->post("tanggal"))),
				"no_reg" => $this->input->post("no_reg"),
				"kode_tarif" => "FRM",
				"qty" => 1,
				"jumlah" => $bayar
			);
			$this->db->insert('kasir_inap', $dat);
		}
    }
    function simpanterimaobat_inap(){
        $dat = array(
            "terima" => 1,
            "tanggal_terimaapotek" => date("Y-m-d H:i:s"),
        );
        $this->db->where("no_reg",$this->input->post("no_reg"));
        $this->db->where("terima",0);
        $this->db->update('apotek_inap', $dat);
    }
	function getcetak($no_pasien,$no_reg){
		$this->db->select("p.*, d.nama_dokter as dokter, g.keterangan as golpas, pr.no_pasien as no_rekmed, pol.keterangan as poli, pr.no_reg as regis, ta.id as nota");
    	$this->db->join("pasien_ralan pr","pr.no_pasien=p.no_pasien","left");
    	$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
    	$this->db->join("transaksi_apotek ta","ta.no_reg=pr.no_reg","left");
    	$this->db->join("poliklinik pol","pol.kode=pr.tujuan_poli","left");
    	$this->db->join("pangkat pan","pan.id_pangkat = p.id_pangkat","left");
    	$this->db->join("dokter d","d.id_dokter=pr.dokter_poli","left");
		$this->db->where("pr.no_pasien",$no_pasien);
		$this->db->where("pr.no_reg",$no_reg);
    	$q = $this->db->get("pasien p");
    	return $q->row();
    }
    function getcetak_inap($no_pasien,$no_reg){
		$this->db->select("p.*, g.keterangan as golpas, pr.no_rm, pr.no_reg as regis, ta.id as nota");
    	$this->db->join("pasien_inap pr","pr.no_rm=p.no_pasien","left");
    	$this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
    	$this->db->join("transaksi_apotek ta","ta.no_reg=pr.no_reg","left");
    	$this->db->join("pangkat pan","pan.id_pangkat = p.id_pangkat","left");
		$this->db->where("pr.no_rm",$no_pasien);
		$this->db->where("pr.no_reg",$no_reg);
		$this->db->where("pr.no_reg",$no_reg);
    	$q = $this->db->get("pasien p");
    	return $q->row();
    }
    function getnota(){
    	$n=0;
		for ($i=1;$i<=300000;$i++){
			$q = $this->db->get_where("pasien_ralan",array("no_reg"=>$n));
			if ($q->num_rows()<=0){
				return $n;
				break;
			}
   		}

	}
	function terima($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tanggal_terimaigd === "0000-00-00 00:00:00") {
            $data = array('tanggal_terimaigd' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("pasien_ralan",$data);
            return "success-Berkas diterima";
        } else {
            return "danger-Berkas sudah pernah diterima";
        }
    }
	function obat($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tanggal_obatigd === "0000-00-00 00:00:00") {
            $data = array('tanggal_obatigd' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("pasien_ralan",$data);
            return "success-Berkas diterima";
        } else {
            return "danger-Berkas sudah pernah diterima";
        }
    }
    function getrespond($no_rm, $no_reg){
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$this->db->select("pr.*,p.nama_pasien as nama_pasien, a.tanggal_terima, a.tanggal_obat");
		$this->db->where("pr.layan!=","2");
		$this->db->where("pr.tujuan_poli","0102030");
		if ($tgl1!="" OR $tgl2!="") {
			$this->db->where("pr.tanggal>=",date("Y-m-d",strtotime($tgl1)));
            $this->db->where("pr.tanggal<=",date("Y-m-d",strtotime($tgl2)));
		}
		if ($tgl1=="" OR $tgl2=="") {
				$this->db->where("pr.no_reg",$no_reg);
			}
		$this->db->order_by("no_reg","desc");
		$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
		$this->db->join("apotek a", "a.no_reg = pr.no_reg","left");
		$this->db->group_by("a.no_reg");
		$query = $this->db->get("pasien_ralan pr");
		return $query;
	}
	function terima_ralan($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tanggal_terimaapotek === "0000-00-00 00:00:00") {
            $data = array('tanggal_terimaapotek' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("pasien_ralan",$data);
            return "success-Berkas diterima";
        } else{
            return "danger-Berkas sudah pernah diterima";
        }
    }
	function obat_ralan($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tanggal_obatapotek === "0000-00-00 00:00:00") {
            $data = array('tanggal_obatapotek' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("pasien_ralan",$data);
            return "success-Berkas diterima";
        } else {
            return "danger-Berkas sudah pernah diterima";
        }
    }
    function printobat_ralan($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tanggal_printobat === "0000-00-00 00:00:00") {
            $data = array('tanggal_printobat' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("pasien_ralan",$data);
            return "success-Berkas diterima";
        } else {
            return "danger-Berkas sudah pernah diterima";
        }
    }
    function printobat_inap($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        // $this->db->where("no_reg",$no_reg);
        // $q = $this->db->get("pasien_ralan")->row();
        // if ($q->tanggal_printobat === "0000-00-00 00:00:00") {
        //     $data = array('tanggal_printobat' => date("Y-m-d H:i:s"));
        //     // $this->db->where("no_pasien",$no_rm);
        //     $this->db->where("no_reg",$no_reg);
        //     $this->db->update("pasien_inap",$data);
        //     return "success-Berkas diterima";
        // } else {
        //     return "danger-Berkas sudah pernah diterima";
        // }
        $data = array('tanggal_printobat' => date("Y-m-d H:i:s"),"print"=>"1");
        $this->db->where("print","0");
        $this->db->where("no_reg",$no_reg);
        $this->db->update("apotek_inap",$data);
        return "success-Berkas diterima";
    }
    function getrespond_ralan($no_rm, $no_reg){
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$this->db->select("pr.*,p.nama_pasien as nama_pasien, a.tanggal_terimaralan, a.tanggal_obatralan");
		$this->db->where("pr.layan!=","2");
		$this->db->where("pr.tujuan_poli !=","0102030");
		if ($tgl1!="" OR $tgl2!="") {
			$this->db->where("pr.tanggal>=",date("Y-m-d",strtotime($tgl1)));
            $this->db->where("pr.tanggal<=",date("Y-m-d",strtotime($tgl2)));
		}
		if ($tgl1=="" OR $tgl2=="") {
				$this->db->where("pr.no_reg",$no_reg);
			}
		$this->db->order_by("no_reg","desc");
		$this->db->join("pasien p","p.no_pasien=pr.no_pasien");
		$this->db->join("apotek a", "a.no_reg = pr.no_reg","left");
		$this->db->group_by("a.no_reg");
		$query = $this->db->get("pasien_ralan pr");
		return $query;
	}
	function terima_inap($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        $this->db->where("no_reg",$no_reg);
        $q = $this->db->get("pasien_inap")->row();
        if ($q->tanggal_terimaapotek === "0000-00-00 00:00:00") {
            $data = array('tanggal_terimaapotek' => date("Y-m-d H:i:s"));
            // $this->db->where("no_pasien",$no_rm);
            $this->db->where("no_reg",$no_reg);
            $this->db->update("pasien_inap",$data);
            return "success-Berkas diterima";
        } else{
            return "danger-Berkas sudah pernah diterima";
        }
    }
	function obat_inap($no_rm,$no_reg){
        // $this->db->where("no_pasien",$no_rm);
        // $this->db->where("no_reg",$no_reg);
        // $q = $this->db->get("pasien_inap")->row();
        // if ($q->tanggal_obatapotek === "0000-00-00 00:00:00") {
        //     $data = array('tanggal_obatapotek' => date("Y-m-d H:i:s"));
        //     // $this->db->where("no_pasien",$no_rm);
        //     $this->db->where("no_reg",$no_reg);
        //     $this->db->update("pasien_inap",$data);
        //     return "success-Berkas diterima";
        // } else {
        //     return "danger-Berkas sudah pernah diterima";
        // }
        $data = array('tanggal_obatapotek' => date("Y-m-d H:i:s"));
        $this->db->where("tanggal_obatapotek","0000-00-00 00:00:00");
        $this->db->where("terima",1);
        $this->db->where("no_reg",$no_reg);
        $this->db->update("apotek_inap",$data);
        return "success-Berkas diterima";
    }
    function getrespond_inap($no_rm,$no_reg){
		$tgl1 = $this->session->userdata("tgl1");
		$tgl2 = $this->session->userdata("tgl2");
		$this->db->select("i.*,");
		if ($tgl1!="" OR $tgl2!="") {
			$this->db->where("i.tgl_masuk>=",date("Y-m-d",strtotime($tgl1)));
            $this->db->where("i.tgl_masuk<=",date("Y-m-d",strtotime($tgl2)));
		}
		if ($tgl1=="" OR $tgl2=="") {
				$this->db->where("i.no_reg",$no_reg);
			}
		$this->db->join("pasien p","p.no_pasien=i.no_rm");
		$this->db->join("apotek a", "a.no_reg = i.no_reg","left");
		$this->db->order_by("no_reg,no_rm","desc");
		$query = $this->db->get("pasien_inap i");
		return $query;
	}
	function getjenisfile(){
		return $this->db->get("jenisfile");
	}
	function getfilepdf($jenis='ralan',$no_reg){
		$this->db->where("no_reg",$no_reg);
		if ($jenis=="igd") $jenis = "ralan";
		$q = $this->db->get("pdf_".$jenis);
		return $q;
	}
	function uploadpdf($jenis,$nama_file){
		$data = array(
						'no_reg' => $this->input->post("no_reg"),
						'id_file' => date("dmYHis"),
						'file_pdf' => $nama_file,
						'tgl_upload' => date("Y-m-d H:i:s"),
					);
		if ($jenis=="igd") $jenis = "ralan";
		$this->db->insert("pdf_".$jenis,$data);
		return "success-File berhasil diupload";
	}

	function simpanwaktu_inap(){
		// $bayar = $this->input->post("total");
		$a = $this->getapotek_inap($this->input->post("no_reg"));
		foreach ($a->result() as $key) {
			$data = array(
							"aturan_pakai" => $this->input->post("aturan_pakai"),
							"waktu" => $this->input->post("waktu"),
							"pagi" => $this->input->post("pagi"),
							"sore" => $this->input->post("sore"),
							"malem" => $this->input->post("malem"),
							"siang" 		=> $this->input->post("siang"),
							"waktu_lainnya" 	=> $this->input->post("waktu_lainnya"),
							"takaran" 	=> $this->input->post("takaran"),
			);
			// $this->db->where("no_reg",$this->input->post("no_reg"));
			// $this->db->where("tanggal",$this->input->post("tanggal_obat"));
			// $this->db->where("kode_obat",$this->input->post("kode_obat"));
			// $this->db->where("dokter",$this->input->post("iddokter"));
			$this->db->where("id",$this->input->post("id"));
			$this->db->update("apotek_inap",$data);
		}
	}
	function simpanwaktu_ralan(){
		// $bayar = $this->input->post("total");
		$a = $this->getapotek($this->input->post("no_reg"),"","");
		foreach ($a->result() as $key) {
			$data = array(
							"aturan_pakai" 	=> $this->input->post("aturan_pakai"),
							"waktu" 		=> $this->input->post("waktu"),
							"pagi" 			=> $this->input->post("pagi"),
							"siang" 		=> $this->input->post("siang"),
							"sore" 			=> $this->input->post("sore"),
							"malem" 		=> $this->input->post("malem"),
							"waktu_lainnya" => $this->input->post("waktu_lainnya"),
							"takaran" 	=> $this->input->post("takaran"),
			);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->where("kode_obat",$this->input->post("kode_obat"));
			$this->db->update("apotek",$data);
		}
	}

	function simpanwaktu_igd(){
		// $bayar = $this->input->post("total");
		$a = $this->getapotek($this->input->post("no_reg"),"","");
		foreach ($a->result() as $key) {
			$data = array(
							"aturan_pakai" => $this->input->post("aturan_pakai"),
							"waktu" => $this->input->post("waktu"),
							"pagi" => $this->input->post("pagi"),
							"sore" => $this->input->post("sore"),
							"malem" => $this->input->post("malem"),
							"siang" 		=> $this->input->post("siang"),
							"waktu_lainnya" 	=> $this->input->post("waktu_lainnya"),
							"takaran" 	=> $this->input->post("takaran"),
			);
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->where("kode_obat",$this->input->post("kode_obat"));
			$this->db->update("apotek",$data);
		}
	}
	function getaturan_pakai(){
		return $this->db->get("aturan_pakai");
	}
	function getwaktu_pakai(){
		return $this->db->get("waktu");
	}
	function gettakaran(){
		return $this->db->get("takaran");
	}
	function getwaktulainnya(){
		return $this->db->get("waktu_lainnya");
	}
	function getdokter(){
    	$this->db->select("dokter.*, k.nama_kelompok");
    	$this->db->join("kelompok_dokter k","k.id_kelompok = dokter.kelompok_dokter","left");
    	return $this->db->get("dokter");
    }
}
?>
