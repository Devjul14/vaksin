<?php
class Perawat extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->Model('Mperawat');
		$this->load->Model('Mdokter');
		$this->load->Model('Mkasir');
		$this->load->Model('Mpendaftaran');
		if (($this->session->userdata('username') == NULL)||($this->session->userdata('password') == NULL))
		{
			redirect("login/logout","refresh");
		}
	}

	function view(){
		$data["title"]			= $this->session->userdata('status_user');
		$data["username"] 		= $this->session->userdata('username');
		$data["q"] 				= $this->Mperawat->getperawat();
		$data['menu']			= "perawat";
		$data['vmenu']			= "admindkk/vmenu";
		$data["content"]		= "admindkk/perawat/vperawat";
		$data["title_header"] 	= "Perawat";
		$data["breadcrumb"] 	= "<li class='active'><strong>Perawat</strong></li>";
		$this->load->view('template',$data);
	}
	function formperawat($id_perawat=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getperawat_detail($id_perawat);
		$data["bagian"]         = $this->Mperawat->getbagian();
		$data["id_perawat"]     = $id_perawat;
		$data['menu']           = "perawat";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "admindkk/perawat/vform_perawat";
		$data["title_header"]   = "Form Perawat";
		$data["breadcrumb"]     = "<li class='active'><strong>Perawat</strong></li>";
		$this->load->view('template',$data);
	}
	function simpanperawat($aksi){
		$message = $this->Mperawat->simpanperawat($aksi);
		$this->session->set_flashdata("message",$message);
		redirect("perawat/view");
	}
	function hapusperawat($id){
		$message = $this->Mperawat->hapusperawat($id);
		$this->session->set_flashdata("message",$message);
		redirect("perawat/view");
	}
	function getttd(){
		$this->db->select("ttd");
		$d = $this->db->get_where("perawat",["id_perawat"=>$this->input->post("id_perawat")]);
		echo $d->row()->ttd;
	}
	function getphoto(){
		$this->db->select("photo");
		$d = $this->db->get_where("perawat",["id_perawat"=>$this->input->post("id_perawat")]);
		echo $d->row()->photo;
	}
	function pasienigd($current=0,$from=0){
		$data["title"] = $this->session->userdata('status_user');
		$data['judul'] = "Pasien IGD &nbsp;&nbsp;&nbsp;";
		$data["vmenu"] = $this->session->userdata("controller")."/vmenu";
		$data["content"] = "perawat/vlistpasienigd";
		$data["username"] = $this->session->userdata('nama_user');
		$data['menu']="perawat";
		$data["current"] = $current;
		$data["title_header"] = "Pasien IGD ";
		$data["breadcrumb"] = "<li class='active'><strong>Pasien IGD</strong></li>";
		$this->load->library('pagination');
		$config['base_url'] = base_url().'perawat/pasienigd/'.$current;
		$config['total_rows'] = $this->Mdokter->getpasien_rawatinapigd();
		$config['per_page'] = 50;
		$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';
		$config['cur_tag_open'] = '<li class=active><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['num_links'] = 4;
		$config['uri_segment'] = 4;
		$from = $this->uri->segment(4);
		$data["from"] = $from;
		$data['total_rows'] = $config['total_rows'];
		$this->pagination->initialize($config);
		$data["q3"] = $this->Mdokter->getpasien_inapigd($config['per_page'],$from);
		$this->session->unset_userdata("temp");
		$this->load->view('template',$data);
	}
	function pasienralan($current=0,$from=0){
		$this->session->unset_userdata("temp");
		$data["title"] = $this->session->userdata('status_user');
		$data['judul'] = "Pasien Rawat Jalan &nbsp;&nbsp;&nbsp;";
		$data["vmenu"] = $this->session->userdata("controller")."/vmenu";
		$data["content"] = "perawat/vlistpasienralan";
		$data["username"] = $this->session->userdata('nama_user');
		$data['menu']="perawat";
		$data["current"] = $current;
		$data["title_header"] = "Pasien Rawat Jalan ";
		$data["breadcrumb"] = "<li class='active'><strong>Pasien Rawat Jalan</strong></li>";
		$this->load->library('pagination');
		$config['base_url'] = base_url().'perawat/pasienralan/'.$current;
		$config['total_rows'] = $this->Mperawat->totalpasienralan();
		$config['per_page'] = 50;
		$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';
		$config['cur_tag_open'] = '<li class=active><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['num_links'] = 4;
		$config['uri_segment'] = 4;
		$from = $this->uri->segment(4);
		$data["from"] = $from;
		$data['total_rows'] = $config['total_rows'];
		$this->pagination->initialize($config);
		$data["q3"] = $this->Mperawat->getpasienralan($config['per_page'],$from);
		$s = $this->session->userdata();
		foreach($s AS $sessKey => $sessValue){
			if (strpos($sessKey, 'status') !== false) {
				$this->session->unset_userdata($sessKey);
			}
			if (strpos($sessKey, 'prov') !== false) {
				$this->session->unset_userdata($sessKey);
			}
			if (strpos($sessKey, 'kota') !== false) {
				$this->session->unset_userdata($sessKey);
			}
			if (strpos($sessKey, 'tglresiko') !== false) {
				$this->session->unset_userdata($sessKey);
			}
			if (strpos($sessKey, 'tglgejala') !== false) {
				$this->session->unset_userdata($sessKey);
			}
		}
		$this->load->view('template',$data);
	}
	function pasieninap($current=0,$from=0){
		$this->hapustemp2();
		$data['judul'] = "Pasien Rawat Inap &nbsp;&nbsp;&nbsp;";
		$data["vmenu"] = $this->session->userdata("controller")."/vmenu";
		$data["content"] = "perawat/vlistpasieninap";
		$data["username"] = $this->session->userdata('nama_user');
		$data['menu']="perawat";
		$data["current"] = $current;
		$data["title"] = "Pasien Rawat Inap ";
		$data["title_header"] = "Pasien Rawat Inap ";
		$data["breadcrumb"] = "<li class='active'><strong>Pasien Rawat Inap</strong></li>";
		$this->load->library('pagination');
		$config['base_url'] = base_url().'perawat/pasieninap/'.$current;
		$config['total_rows'] = $this->Mperawat->totalpasieninap();
		$config['per_page'] = 50;
		$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';
		$config['cur_tag_open'] = '<li class=active><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['num_links'] = 4;
		$config['uri_segment'] = 4;
		$from = $this->uri->segment(4);
		$data["from"] = $from;
		$data['total_rows'] = $config['total_rows'];
		$this->pagination->initialize($config);
		$data["q3"] = $this->Mperawat->getpasieninap($config['per_page'],$from);
		$this->session->unset_userdata("temp");
		$s = $this->session->userdata();
		foreach($s AS $sessKey => $sessValue){
			if (strpos($sessKey, 'status') !== false) {
				$this->session->unset_userdata($sessKey);
			}
			if (strpos($sessKey, 'prov') !== false) {
				$this->session->unset_userdata($sessKey);
			}
			if (strpos($sessKey, 'kota') !== false) {
				$this->session->unset_userdata($sessKey);
			}
			if (strpos($sessKey, 'tglresiko') !== false) {
				$this->session->unset_userdata($sessKey);
			}
			if (strpos($sessKey, 'tglgejala') !== false) {
				$this->session->unset_userdata($sessKey);
			}
		}
		$this->load->view('template',$data);
	}
	function igd($no_pasien="", $no_reg=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "Pemindahan Pasien IGD || RS CIREMAI";
		$data["title_header"]     = "Pemindahan Pasien IGD";
		$data["content"]          = "perawat/vformpemindahan";
		$data["breadcrumb"]       = "<li class='active'><strong>Pemindahan Pasien IGD</strong></li>";
		$data["q"]                = $this->Mpendaftaran->getpasien_igd($no_reg);
		$data["jenis"]            = "igd";
		$this->session->unset_userdata("temp");
		$this->load->view('template',$data);
	}
	function listpindahkamar($no_pasien="", $no_reg="",$back=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["back"]             = $back;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "Pemindahan Pasien Rawat Jalan || RS CIREMAI";
		$data["title_header"]     = "Pemindahan Pasien Rawat Jalan";
		$data["content"]          = "perawat/vlistpindah_kamar";
		$data["breadcrumb"]       = "<li class='active'><strong>Pemindahan Pasien Rawat Jalan</strong></li>";
		$data["q"]                = $this->Mperawat->getlistpindah_kamar($no_reg);
		$this->session->unset_userdata("temp");
		$this->load->view('template',$data);
	}
	function inap($no_pasien,$no_reg,$id_pindahkamar,$back="",$id=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "Pemindahan Pasien Rawat Inap || RS CIREMAI";
		$data["title_header"]     = "Pemindahan Pasien Rawat Inap";
		$data["content"]          = "perawat/vformpemindahan";
		$data["breadcrumb"]       = "<li class='active'><strong>Pemindahan Pasien Rawat Inap</strong></li>";
		$data["id_pindahkamar"]   = $id_pindahkamar;
		$data["no_reg"]			  = $no_reg;
		$data["back"]             = $back;
		$data["id"]               = $id;
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["q1"]               = $this->Mperawat->getpemindahan_pasien($no_reg,$id_pindahkamar);
		$data["q2"]               = $this->Mperawat->getpindahkamar_detail($no_reg,$id_pindahkamar);
		$data["jenis"]            = "ranap";
		$data["sp"]               = $this->Mdokter->getdokterkonsul_inap($no_reg);
		$data["dokter"]           = $this->Mpendaftaran->getdokter();
		$data["ap"]               = $this->Mperawat->getap($no_reg);
		$data["radiologi"]        = $this->Mpendaftaran->gettarif_radiologi();
		$data["lab"]              = $this->Mpendaftaran->gettarif_lab();
		$data["tr"]               = $this->Mperawat->gettarif_ralan($no_reg);
		$data["pre"]              = $this->Mperawat->getprecaution();
		$data["dn"]               = $this->Mperawat->getdiet_nutrisi();
		$data["bb"]               = $this->Mperawat->getbab();
		$data["bk"]               = $this->Mperawat->getbak();
		$data["tk"]               = $this->Mperawat->gettindakan_khusus();
		$data["nt"]               = $this->Mperawat->getnote();
		$data["aptk"]             = $this->Mperawat->getapotek_inap($no_reg);
		$data["obs"]              = $this->Mperawat->getobservasi($no_reg,$id_pindahkamar);
		$data["li"]               = $this->Mperawat->getlokasi_infus();
		$data["obd"]              = $this->Mperawat->getobservasi_detail($no_reg,$id_pindahkamar,$id);
		$this->session->unset_userdata("temp");
		$this->load->view('template',$data);
	}
	function assesmenigd($no_pasien="", $no_reg=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "Assesment IGD || RS CIREMAI";
		$data["title_header"]     = "Assesment IGD";
		$data["content"]          = "perawat/vformassesmen";
		$data["breadcrumb"]       = "<li class='active'><strong>Assesment IGD</strong></li>";
		$data["q"]                = $this->Mpendaftaran->getpasien_igd($no_reg);
		$data["s"]				  = $this->Mperawat->soap_perawat();
		$data["sp"]            	  = $this->Mperawat->sparray();
		$data["d"]               = $this->Mdokter->getdokter();
		$data["p"]               = $this->Mperawat->getperawat("igd",$no_reg);
		$data["im"]               = $this->Mperawat->getimplementasi();
		if ($this->session->userdata("temp")=="") $this->Mperawat->getassesmen_perawat("igd",$no_reg);
		$data["jenis"]            = "igd";
		$data["asal"]             = "assesmen";
		$this->load->view('template',$data);
	}
	function assesmenralan($asal,$no_pasien="", $no_reg=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["asal"]             = $asal;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "Assesment Rawat Jalan || RS CIREMAI";
		$data["title_header"]     = "Assesment Rawat Jalan";
		$data["content"]          = "perawat/vformassesmen";
		$data["breadcrumb"]       = "<li class='active'><strong>Assesment Rawat Jalan</strong></li>";
		$data["q"]                = $this->Mpendaftaran->getpasien_igdralan($no_reg);
		$data["s"]				  = $this->Mperawat->soap_perawat();
		$data["sp"]            	  = $this->Mperawat->sparray();
		$data["d"]               = $this->Mdokter->getdokter();
		$data["p"]               = $this->Mperawat->getperawat("poliklinik",$no_reg);
		$data["im"]               = $this->Mperawat->getimplementasi();
		if ($this->session->userdata("temp")=="") $this->Mperawat->getassesmen_perawat("ralan",$no_reg);
		$data["jenis"]            = "ralan";
		$this->load->view('template',$data);
	}
	function assesmeninap($no_pasien="", $no_reg=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "Assesment Rawat Inap || RS CIREMAI";
		$data["title_header"]     = "Assesment Rawat Inap";
		$data["content"]          = "perawat/vformassesmen";
		$data["breadcrumb"]       = "<li class='active'><strong>Assesment Rawat Inap</strong></li>";
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["s"]				  = $this->Mperawat->soap_perawat();
		$data["sp"]            	  = $this->Mperawat->sparray();
		$data["d"]               = $this->Mdokter->getdokter();
		$data["p"]               = $this->Mperawat->getperawat("ranap",$no_reg);
		$data["im"]               = $this->Mperawat->getimplementasi();
		$data["prov"]               = $this->Mperawat->getprovince();
		if ($this->session->userdata("temp")=="") $this->Mperawat->getassesmen_perawat("ranap",$no_reg);
		$data["jenis"]            = "ranap";
		$data["asal"]             = "assesmen";
		$data["pk"]                = $this->Mperawat->getlistpindah_kamar($no_reg);
		$this->load->view('template',$data);
	}
	function cetakassesmen($no_pasien,$no_reg){
		$data["no_pasien"]  = $no_pasien;
		$data["r"]          = $this->Mperawat->cetakassesmen_perawat($no_reg);
		$data["t"]          = $this->Mperawat->pasientriage($no_reg);
		$data["q"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$this->load->view('perawat/vcetak_assesmen',$data);
	}
	function cetakhandover($no_pasien,$no_reg){
		$data["no_pasien"]  = $no_pasien;
		$data["no_reg"]  = $no_reg;
		$data["r"]          = $this->Mperawat->cetakhandover($no_reg);
		$data["t"]          = $this->Mperawat->pasientriage($no_reg);
		$data["p"]          = $this->Mperawat->getpasien_inap($no_reg);
		$data["q"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$data["sp"]         = $this->Mperawat->perawatarray();
		$this->load->view('perawat/vcetakhandover',$data);
	}
	function cetakkebidanan($no_pasien,$no_reg){
		$data["no_pasien"]  = $no_pasien;
		$data["no_reg"]  = $no_reg;
		$data["x"]          = $this->Mperawat->cetakassesmen_perawat($no_reg);
		$data["t"]          = $this->Mperawat->pasientriage($no_reg);
		$data["p"]          = $this->Mperawat->getpasien_inap($no_reg);
		$data["q"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$data["sp"]         = $this->Mperawat->perawatarray();
		$this->load->view('perawat/vcetakkebidanan',$data);
	}
	function addtemp(){
		$temp = $this->session->userdata("temp");
		$id = $this->input->post("diagnosa");
		$shift = $this->input->post("shift");
		$row = explode(",", $temp);
		$ada = 0;
		$t = $koma = "";
		if (is_array($row)){
			foreach ($row as $key => $val) {
				$v = explode("-", $val);
				if ($val!=""){
					if ($id==$v[0] && $v[1]==$shift) {
						$ada = 1;
					}
					$t .= $koma.$val;
					$koma = ",";
				}
			}
		} else {
			if ($temp!=""){
				$val = explode("-", $temp);
				if ($id==$val[0] && $val[1]==$shift) {
					$ada = 1;
				}
				$t .= $koma.$temp;
				$koma = ",";
			}
		}
		if (!$ada){
			$t .= $koma.$id."-".$shift;
		}
		$q = $this->db->get_where("soap_perawat",["id"=>$id])->row();
		$this->session->set_userdata("s".$id."-".$shift,$q->s);
		$this->session->set_userdata("o".$id."-".$shift,$q->o);
		$this->session->set_userdata("a".$id."-".$shift,$q->a);
		$this->session->set_userdata("p".$id."-".$shift,$q->p);
		$this->session->set_userdata("gejala".$id."-".$shift,$this->input->post("gejala"));
		$this->session->set_userdata("tglgejala".$id."-".$shift,$this->input->post("tglgejala"));
		$this->session->set_userdata("resiko".$id."-".$shift,$this->input->post("resiko"));
		$this->session->set_userdata("tujuan".$id."-".$shift,$q->tujuan);
		$this->session->set_userdata("tgl".$id."-".$shift,date("Y-m-d"));
		$this->session->set_userdata("jam".$id."-".$shift,date("H:i:s"));
		$this->session->set_userdata("shift".$id."-".$shift,$shift);
		$this->session->set_userdata("prov".$id."-".$shift,$this->input->post("prov"));
		$this->session->set_userdata("kota".$id."-".$shift,$this->input->post("kota"));
		$this->session->set_userdata("status".$id."-".$shift,$this->input->post("status"));
		$this->session->set_userdata("tingkat_status".$id."-".$shift,$this->input->post("tingkat_status"));
		$this->session->set_userdata("status_assesmen".$id."-".$shift,$this->input->post("status_assesmen"));
		$this->session->set_userdata("temp",$t);
	}
	function addimplementasi(){
		$id = $this->input->post("diagnosa");
		$q = $this->db->get_where("soap_perawat",["id"=>$id])->row();
		echo json_encode($q);
	}
	function changesoap(){
		$this->session->set_userdata("s".$this->input->post("id"),$this->input->post("s"));
		$this->session->set_userdata("o".$this->input->post("id"),$this->input->post("o"));
		$this->session->set_userdata("a".$this->input->post("id"),$this->input->post("a"));
		$this->session->set_userdata("p".$this->input->post("id"),$this->input->post("p"));
		$this->session->set_userdata("tujuan".$this->input->post("id"),$this->input->post("tujuan"));
		$this->session->set_userdata("td".$this->input->post("id"),$this->input->post("td"));
		$this->session->set_userdata("td2".$this->input->post("id"),$this->input->post("td2"));
		$this->session->set_userdata("nadi".$this->input->post("id"),$this->input->post("nadi"));
		$this->session->set_userdata("respirasi".$this->input->post("id"),$this->input->post("respirasi"));
		$this->session->set_userdata("suhu".$this->input->post("id"),$this->input->post("suhu"));
		$this->session->set_userdata("spo2".$this->input->post("id"),$this->input->post("spo2"));
		$this->session->set_userdata("bb".$this->input->post("id"),$this->input->post("bb"));
		$this->session->set_userdata("tb".$this->input->post("id"),$this->input->post("tb"));
		$this->session->set_userdata("shift".$this->input->post("id"),$this->input->post("shift"));
		$this->session->set_userdata("situasional".$this->input->post("id"),$this->input->post("situasional"));
		$this->session->set_userdata("medis".$this->input->post("id"),$this->input->post("medis"));
		$this->session->set_userdata("dpjp".$this->input->post("id"),$this->input->post("dpjp"));
		$this->session->set_userdata("rekomendasi".$this->input->post("id"),$this->input->post("rekomendasi"));
		$this->session->set_userdata("pemberi".$this->input->post("id"),$this->input->post("pemberi"));
		$this->session->set_userdata("penerima".$this->input->post("id"),$this->input->post("penerima"));
		$this->session->set_userdata("gejala".$this->input->post("id"),$this->input->post("gejala"));
		$this->session->set_userdata("tglgejala".$this->input->post("id"),$this->input->post("tglgejala"));
		$this->session->set_userdata("resiko".$this->input->post("id"),$this->input->post("resiko"));
		$this->session->set_userdata("tglresiko".$this->input->post("id"),$this->input->post("tglresiko"));
		$this->session->set_userdata("prov".$this->input->post("id"),$this->input->post("prov"));
		$this->session->set_userdata("kota".$this->input->post("id"),$this->input->post("kota"));
		$this->session->set_userdata("status".$this->input->post("id"),$this->input->post("status"));
		$this->session->set_userdata("tingkat_status".$this->input->post("id"),$this->input->post("tingkat_status"));
		$this->session->set_userdata("status_assesmen".$this->input->post("id"),$this->input->post("status_assesmen"));
	}
	function reset_inap(){
		$this->session->unset_userdata('kode_kelas');
		$this->session->unset_userdata('kelas');
		$this->session->unset_userdata('kode_ruangan');
		$this->session->unset_userdata('ruangan');
		$this->session->unset_userdata('tgl1');
		$this->session->unset_userdata('tgl2');
		$this->session->unset_userdata('no_pasien');
	}
	function hapustemp(){
		$temp = $this->session->userdata("temp");
		$id = $this->input->post("id");
		$shift = $this->input->post("shift");
		$row = explode(",", $temp);
		$t = $koma = "";
		if (is_array($row)){
			foreach ($row as $key => $val) {
				$v = explode("-", $val);
				$value = $v[0];
				if ($value!=""){
					if ($id!=$value && $shift!=$v[1]){
						$t .= $koma.$value."-".$v[1];
						$koma = ",";
					}
				}
			}
		} else {
			if ($temp!=""){
				$val = explode("-", $temp);
				if ($id!=$val[0] && $shift!=$val[1]){
					$t .= $koma.$val[0]."-".$val[1];
					$koma = ",";
				}
			}
		}
		if ($t==""){
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->where("jenis",$this->input->post("jenis"));
			$this->db->delete("assesmen_perawat");
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->delete("assesmen_perawat_evaluasi");
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->delete("assesmen_perawat_implementasi");
		}
		$this->session->set_userdata("temp",$t);
	}
	function simpantemp(){
		$message = $this->Mperawat->simpantemp();
		$this->session->set_flashdata("message",$message);
	}
	function simpantujuan(){
		$message = $this->Mperawat->simpantujuan();
		$this->session->set_flashdata("message",$message);
		redirect("perawat/assesmeninap/".$this->input->post("no_pasien")."/".$this->input->post("no_reg"));
	}
	function simpanimplementasi(){
		$message = $this->Mperawat->simpanimplementasi();
		$this->session->set_flashdata("message",$message);
		if ($this->input->post("jenis")=="igd")
			redirect("perawat/assesmenigd/".$this->input->post("no_pasien")."/".$this->input->post("no_reg"));
		else
			redirect("perawat/assesmeninap/".$this->input->post("no_pasien")."/".$this->input->post("no_reg"));
	}
	function simpanevaluasi(){
		$message = $this->Mperawat->simpanevaluasi();
		$this->session->set_flashdata("message",$message);
		if ($this->input->post("jenis")=="igd")
			redirect("perawat/assesmenigd/".$this->input->post("no_pasien")."/".$this->input->post("no_reg"));
		else
			redirect("perawat/assesmeninap/".$this->input->post("no_pasien")."/".$this->input->post("no_reg"));
	}
	function cetakasuhan($no_pasien,$no_reg,$jenis){
		$data["no_pasien"]  = $no_pasien;
		$data["no_reg"] = $no_reg;
		if ($jenis=="igd")
			$data["p"]      = $this->Mperawat->getpasien_ralan($no_reg);
		else
			$data["p"]      = $this->Mperawat->getpasien_inap($no_reg);
		$data["n"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$data["i"]          = $this->Mperawat->cetakimplementasi($no_reg);
		$data["q"]          = $this->Mperawat->getassesmen_perawat_cetak($no_reg);
		$data["e"]          = $this->Mperawat->cetakevaluasi($no_reg);
		$data["t"]          = $this->Mperawat->pasientriage($no_reg);
		$data["q1"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$data["sp"]         = $this->Mperawat->perawatarray();
		$this->load->view('perawat/vcetakasuhan',$data);
	}
	function cetakcovid($no_pasien,$no_reg,$jenis){
		$data["no_pasien"]  = $no_pasien;
		$data["no_reg"] = $no_reg;
		$data["jenis"] = $jenis;
		if ($jenis=="igd")
			$data["p"]      = $this->Mperawat->getpasien_ralan($no_reg);
		else
			$data["p"]      = $this->Mperawat->getpasien_inap($no_reg);
		$data["n"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$data["i"]          = $this->Mperawat->cetakimplementasi($no_reg);
		$data["q"]          = $this->Mperawat->getassesmen_covid($no_reg,$jenis);
		$data["e"]          = $this->Mperawat->cetakevaluasi($no_reg);
		$data["t"]          = $this->Mperawat->pasientriage($no_reg);
		$data["q1"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$data["sp"]         = $this->Mperawat->perawatarray();
		$this->load->view('perawat/vcetakcovid',$data);
	}
	function ambil_province(){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/province?",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: c54c2237da96b5b342eded2febe37665"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
	}
	function ambil_kota(){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=".$this->input->post("prov")."&id=",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/x-www-form-urlencoded",
				"key: c54c2237da96b5b342eded2febe37665"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
	}
	function ambil_namakota(){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=".$this->input->post("prov")."&id=".$this->input->post("kota"),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/x-www-form-urlencoded",
				"key: c54c2237da96b5b342eded2febe37665"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
	}
	function getprov($value){
		echo $this->session->userdata("prov".$value)."_".$this->session->userdata("kota".$value);
	}
	function getpropinsi($value){
		echo $this->db->get_where("provinces",["id"=>$value])->row()->name;
	}
	function getdomisili($value){
		echo $this->db->get_where("regencies",["id"=>$value])->row()->name;
	}
	function getprovince(){
		echo json_encode($this->Mperawat->getprovince()->result());
	}
	function getkota(){
		$prov = $this->input->post("prov");
		$q = $this->db->get_where("regencies",["province_id"=>$prov]);
		echo json_encode($q->result());
	}
	function simpanpemindahan($action){
		$message = $this->Mperawat->simpanpemindahan($action);
		$this->session->set_flashdata("message",$message);
		redirect("perawat/inap/".$this->input->post("no_pasien")."/".$this->input->post("no_reg")."/".$this->input->post("id_pindahkamar")."/".$this->input->post("back"));
	}
	function simpanobservasi(){
		$message = $this->Mperawat->simpanobservasi();
		$this->session->set_flashdata("message",$message);
		redirect("perawat/inap/".$this->input->post("obs_no_pasien")."/".$this->input->post("obs_no_reg")."/".$this->input->post("obs_id_pindahkamar")."/".$this->input->post("back"));
	}
	function cetakpemindahan_pasien($no_pasien,$no_reg,$id_pindahkamar){
		$data["no_pasien"]  = $no_pasien;
		$data["no_reg"]     = $no_reg;
		$data["id_pindahkamar"] = $id_pindahkamar;
		$data["q"]  = $this->Mperawat->cetakpemindahan_pasien($no_reg,$id_pindahkamar);
		$data["q1"] = $this->Mperawat->getobservasi($no_reg,$id_pindahkamar);
		$data["ap"]               = $this->Mperawat->getperawat_assesmen($no_reg,$id_pindahkamar);
		$data["tg"]               = $this->Mperawat->getperawat_triage($no_reg);
		$data["dokter"]           = $this->Mpendaftaran->getdokter();
		$this->load->view("perawat/vcetakpemindahan_pasien",$data);
	}
	function pewsinap($no_pasien="", $no_reg=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "PEWS Rawat Inap || RS CIREMAI";
		$data["title_header"]     = "PEWS Rawat Inap";
		$data["content"]          = "perawat/vformpews";
		$data["breadcrumb"]       = "<li class='active'><strong>PEWS Rawat Inap</strong></li>";
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["s"]				  = $this->Mperawat->soap_perawat();
		$data["sp"]            	  = $this->Mperawat->sparray();
		$data["d"]               = $this->Mdokter->getdokter();
		$data["p"]               = $this->Mperawat->getperawat("ranap",$no_reg);
		$data["im"]               = $this->Mperawat->getimplementasi();
		$data["prov"]               = $this->Mperawat->getprovince();
		$this->Mperawat->getpews($no_reg);
		$data["jenis"]            = "ranap";
		$data["asal"]             = "assesmen";
		$data["pk"]                = $this->Mperawat->getlistpindah_kamar($no_reg);
		$this->load->view('template',$data);
	}
	function addtemp_pews(){
		$temp = $this->session->userdata("temp_pews");
		$tgl = date("Y-m-d", strtotime($this->input->post("tgl")));
		$jam = $this->input->post("jam");
		$row = explode(",", $temp);
		$ada = 0;
		$t = $koma = "";
		if (is_array($row)){
			foreach ($row as $key => $val) {
				$v = explode(" ", $val);
				if ($val!=""){
					if ($tgl==$v[0] && $v[1]==$jam) {
						$ada = 1;
					}
					$t .= $koma.$val;
					$koma = ",";
				}
			}
		} else {
			if ($temp!=""){
				$val = explode(" ", $temp);
				if ($tgl==$val[0] && $val[1]==$jam) {
					$ada = 1;
				}
				$t .= $koma.$temp;
				$koma = ",";
			}
		}
		if (!$ada){
			$t .= $koma.$tgl." ".$jam;
		}
		$id = date("Ymd",strtotime($this->input->post("tgl"))).date("His",strtotime($this->input->post("jam")));
		$this->session->set_userdata("tgl".$id,$this->input->post("tgl"));
		$this->session->set_userdata("jam".$id,$this->input->post("jam"));
		$this->session->set_userdata("temp_pews",$t);
	}
	function hapustemp_pews(){
		$temp = $this->session->userdata("temp_pews");
		$this->db->where("no_reg",$this->input->post("no_reg"));
		$this->db->where("tanggal",$this->input->post("tanggal"));
		$this->db->delete("pews");
	}
	function simpantemp_pews(){
		$message = $this->Mperawat->simpantemp_pews();
		echo $message;
		$this->session->set_flashdata("message",$message);
	}
	function changesoap_pews(){
		$this->session->set_userdata("id".$this->input->post("id"),$this->input->post("id"));
		$this->session->set_userdata("rr".$this->input->post("id"),$this->input->post("rr"));
		$this->session->set_userdata("spo2".$this->input->post("id"),$this->input->post("spo2"));
		$this->session->set_userdata("metode_pemberian_o2".$this->input->post("id"),$this->input->post("metode_pemberian_o2"));
		$this->session->set_userdata("pemakaian_o2".$this->input->post("id"),$this->input->post("pemakaian_o2"));
		$this->session->set_userdata("keterangan_pemakaian_o2".$this->input->post("id"),$this->input->post("keterangan_pemakaian_o2"));
		$this->session->set_userdata("upaya_nafas".$this->input->post("id"),$this->input->post("upaya_nafas"));
		$this->session->set_userdata("nadi".$this->input->post("id"),$this->input->post("nadi"));
		$this->session->set_userdata("crt".$this->input->post("id"),$this->input->post("crt"));
		$this->session->set_userdata("nilai_kardiovaskuler".$this->input->post("id"),$this->input->post("nilai_kardiovaskuler"));
		$this->session->set_userdata("prilaku".$this->input->post("id"),$this->input->post("prilaku"));
		$this->session->set_userdata("nebulizer".$this->input->post("id"),$this->input->post("nebulizer"));
		$this->session->set_userdata("muntah_post_op".$this->input->post("id"),$this->input->post("muntah_post_op"));
		$this->session->set_userdata("nilai_prilaku".$this->input->post("id"),$this->input->post("nilai_prilaku"));
		$this->session->set_userdata("nilai_pews_tambahan".$this->input->post("id"),$this->input->post("nilai_pews_tambahan"));
		$this->session->set_userdata("nilai_pews_total".$this->input->post("id"),$this->input->post("nilai_pews_total"));
		$this->session->set_userdata("suhu".$this->input->post("id"),$this->input->post("suhu"));
		$this->session->set_userdata("tekanan_darah".$this->input->post("id"),$this->input->post("tekanan_darah"));
		$this->session->set_userdata("gula_darah".$this->input->post("id"),$this->input->post("gula_darah"));
		$this->session->set_userdata("luka".$this->input->post("id"),$this->input->post("luka"));
		$this->session->set_userdata("warna_luka".$this->input->post("id"),$this->input->post("warna_luka"));
		$this->session->set_userdata("mobilisasi".$this->input->post("id"),$this->input->post("mobilisasi"));
		$this->session->set_userdata("tinggi_badan".$this->input->post("id"),$this->input->post("tinggi_badan"));
		$this->session->set_userdata("berat_badan".$this->input->post("id"),$this->input->post("berat_badan"));
		$this->session->set_userdata("luka_skala_norton".$this->input->post("id"),$this->input->post("luka_skala_norton"));
		$this->session->set_userdata("total".$this->input->post("id"),$this->input->post("total"));
		$this->session->set_userdata("intake".$this->input->post("id"),$this->input->post("intake"));
		$this->session->set_userdata("asi".$this->input->post("id"),$this->input->post("asi"));
		$this->session->set_userdata("pasi".$this->input->post("id"),$this->input->post("pasi"));
		$this->session->set_userdata("intravena".$this->input->post("id"),$this->input->post("intravena"));
		$this->session->set_userdata("darah".$this->input->post("id"),$this->input->post("darah"));
		$this->session->set_userdata("output".$this->input->post("id"),$this->input->post("output"));
		$this->session->set_userdata("urine".$this->input->post("id"),$this->input->post("urine"));
		$this->session->set_userdata("muntah".$this->input->post("id"),$this->input->post("muntah"));
		$this->session->set_userdata("feaces".$this->input->post("id"),$this->input->post("feaces"));
		$this->session->set_userdata("drain".$this->input->post("id"),$this->input->post("drain"));
		$this->session->set_userdata("iwl".$this->input->post("id"),$this->input->post("iwl"));
	}
	function cetakpews($no_pasien,$no_reg,$tgl1,$tgl2){
		$data["no_pasien"]  = $no_pasien;
		$data["r"]          = $this->Mperawat->cetakpews($no_reg,$tgl1,$tgl2);
		$data["tgl1"]        = $tgl1;
		$data["tgl2"]        = $tgl2;
		$data["q"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$this->load->view('perawat/vcetakpews',$data);
	}
	function newsinap($no_pasien="", $no_reg=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "NEWS Rawat Inap || RS CIREMAI";
		$data["title_header"]     = "NEWS Rawat Inap";
		$data["content"]          = "perawat/vformnews";
		$data["breadcrumb"]       = "<li class='active'><strong>NEWS Rawat Inap</strong></li>";
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["s"]				  = $this->Mperawat->soap_perawat();
		$data["sp"]            	  = $this->Mperawat->sparray();
		$data["d"]                = $this->Mdokter->getdokter();
		$data["p"]                = $this->Mperawat->getperawat("ranap",$no_reg);
		$data["im"]               = $this->Mperawat->getimplementasi();
		$data["prov"]             = $this->Mperawat->getprovince();
		if ($this->session->userdata("temp_news")=="") $this->Mperawat->getnews($no_reg);
		$data["jenis"]            = "ranap";
		$data["asal"]             = "assesmen";
		$data["pk"]               = $this->Mperawat->getlistpindah_kamar($no_reg);
		$this->load->view('template',$data);
	}
	function addtemp_news(){
		$temp = $this->session->userdata("temp_news");
		$tgl = date("Y-m-d", strtotime($this->input->post("tgl")));
		$jam = $this->input->post("jam");
		$row = explode(",", $temp);
		$ada = 0;
		$t = $koma = "";
		if (is_array($row)){
			foreach ($row as $key => $val) {
				$v = explode(" ", $val);
				if ($val!=""){
					if ($tgl==$v[0] && $v[1]==$jam) {
						$ada = 1;
					}
					$t .= $koma.$val;
					$koma = ",";
				}
			}
		} else {
			if ($temp!=""){
				$val = explode(" ", $temp);
				if ($tgl==$val[0] && $val[1]==$jam) {
					$ada = 1;
				}
				$t .= $koma.$temp;
				$koma = ",";
			}
		}
		if (!$ada){
			$t .= $koma.$tgl." ".$jam;
		}
		$id = date("Ymd",strtotime($this->input->post("tgl"))).date("His",strtotime($this->input->post("jam")));
		$this->session->set_userdata("tgl_news".$id,$this->input->post("tgl"));
		$this->session->set_userdata("jam_news".$id,$this->input->post("jam"));
		$this->session->set_userdata("temp_news",$t);
	}
	function hapustemp_news(){
		$temp = $this->session->userdata("temp_news");
		$id = $this->input->post("id");
		$row = explode(",", $temp);
		$t = $koma = "";
		if (is_array($row)){
			foreach ($row as $key => $val) {
				$value = date("YmdHis",strtotime($val));
				if ($value!=""){
					if ($id!=$value){
						$t .= $koma.$val;
						$koma = ",";
					}
				}
			}
		} else {
			if ($temp!=""){
				$value = date("YmdHis",strtotime($temp));
				if ($id!=$value){
					$t .= $koma.$temp;
					$koma = ",";
				}
			}
		}
		if ($t==""){
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->delete("news");
		}
		$this->session->set_userdata("temp_news",$t);
	}
	function simpantemp_news(){
		$message = $this->Mperawat->simpantemp_news();
		$this->session->set_flashdata("message",$message);
	}
	function changesoap_news(){
		$this->session->set_userdata("id".$this->input->post("id"),$this->input->post("id"));
		$this->session->set_userdata("rr".$this->input->post("id"),$this->input->post("rr"));
		$this->session->set_userdata("spo2".$this->input->post("id"),$this->input->post("spo2"));
		$this->session->set_userdata("pemakaian_o2".$this->input->post("id"),$this->input->post("pemakaian_o2"));
		$this->session->set_userdata("keterangan_pemakaian_o2".$this->input->post("id"),$this->input->post("keterangan_pemakaian_o2"));
		$this->session->set_userdata("suhu".$this->input->post("id"),$this->input->post("suhu"));
		$this->session->set_userdata("tensi".$this->input->post("id"),$this->input->post("tensi"));
		$this->session->set_userdata("nadi".$this->input->post("id"),$this->input->post("nadi"));
		$this->session->set_userdata("tingkat_kesadaran".$this->input->post("id"),$this->input->post("tingkat_kesadaran"));
		$this->session->set_userdata("score_ews".$this->input->post("id"),$this->input->post("score_ews"));
		$this->session->set_userdata("gula_darah".$this->input->post("id"),$this->input->post("gula_darah"));
		$this->session->set_userdata("cvp".$this->input->post("id"),$this->input->post("cvp"));
		$this->session->set_userdata("lingkar_perut".$this->input->post("id"),$this->input->post("lingkar_perut"));
		$this->session->set_userdata("berat_badan".$this->input->post("id"),$this->input->post("berat_badan"));
		$this->session->set_userdata("tinggi_badan".$this->input->post("id"),$this->input->post("tinggi_badan"));
		$this->session->set_userdata("luka_skala_norton".$this->input->post("id"),$this->input->post("luka_skala_norton"));
		$this->session->set_userdata("oral".$this->input->post("id"),$this->input->post("oral"));
		$this->session->set_userdata("intravena".$this->input->post("id"),$this->input->post("intravena"));
		$this->session->set_userdata("darah".$this->input->post("id"),$this->input->post("darah"));
		$this->session->set_userdata("urine".$this->input->post("id"),$this->input->post("urine"));
		$this->session->set_userdata("muntah".$this->input->post("id"),$this->input->post("muntah"));
		$this->session->set_userdata("faeces".$this->input->post("id"),$this->input->post("faeces"));
		$this->session->set_userdata("drain".$this->input->post("id"),$this->input->post("drain"));
		$this->session->set_userdata("iwl".$this->input->post("id"),$this->input->post("iwl"));
		$this->session->set_userdata("konjungtiva".$this->input->post("id"),$this->input->post("konjungtiva"));
		$this->session->set_userdata("buah_dada".$this->input->post("id"),$this->input->post("buah_dada"));
		$this->session->set_userdata("kontraksi".$this->input->post("id"),$this->input->post("kontraksi"));
		$this->session->set_userdata("flatus".$this->input->post("id"),$this->input->post("flatus"));
		$this->session->set_userdata("fundur_uteri".$this->input->post("id"),$this->input->post("fundur_uteri"));
		$this->session->set_userdata("luka_pembedahan".$this->input->post("id"),$this->input->post("luka_pembedahan"));
		$this->session->set_userdata("perineum".$this->input->post("id"),$this->input->post("perineum"));
		$this->session->set_userdata("defekasi".$this->input->post("id"),$this->input->post("defekasi"));
		$this->session->set_userdata("bak".$this->input->post("id"),$this->input->post("bak"));
		$this->session->set_userdata("diastasis_retchi".$this->input->post("id"),$this->input->post("diastasis_retchi"));
	}
	function cetaknews($no_pasien,$no_reg,$tgl1,$tgl2){
		$data["no_pasien"]  = $no_pasien;
		$data["r"]          = $this->Mperawat->cetaknews($no_reg,$tgl1,$tgl2);
		$data["tgl1"]        = $tgl1;
		$data["tgl2"]        = $tgl2;
		$data["q"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$this->load->view('perawat/vcetaknews',$data);
	}
	function meowsinap($no_pasien="", $no_reg=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "MEOWS Rawat Inap || RS CIREMAI";
		$data["title_header"]     = "MEOWS Rawat Inap";
		$data["content"]          = "perawat/vformmeows";
		$data["breadcrumb"]       = "<li class='active'><strong>MEOWS Rawat Inap</strong></li>";
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["s"]				  = $this->Mperawat->soap_perawat();
		$data["sp"]            	  = $this->Mperawat->sparray();
		$data["d"]                = $this->Mdokter->getdokter();
		$data["p"]                = $this->Mperawat->getperawat("ranap",$no_reg);
		$data["im"]               = $this->Mperawat->getimplementasi();
		$data["prov"]             = $this->Mperawat->getprovince();
		if ($this->session->userdata("temp_meows")=="") $this->Mperawat->getmeows($no_reg);
		$data["jenis"]            = "ranap";
		$data["asal"]             = "assesmen";
		$data["pk"]               = $this->Mperawat->getlistpindah_kamar($no_reg);
		$this->load->view('template',$data);
	}
	function addtemp_meows(){
		$temp = $this->session->userdata("temp_meows");
		$tgl = date("Y-m-d", strtotime($this->input->post("tgl")));
		$jam = $this->input->post("jam");
		$row = explode(",", $temp);
		$ada = 0;
		$t = $koma = "";
		if (is_array($row)){
			foreach ($row as $key => $val) {
				$v = explode(" ", $val);
				if ($val!=""){
					if ($tgl==$v[0] && $v[1]==$jam) {
						$ada = 1;
					}
					$t .= $koma.$val;
					$koma = ",";
				}
			}
		} else {
			if ($temp!=""){
				$val = explode(" ", $temp);
				if ($tgl==$val[0] && $val[1]==$jam) {
					$ada = 1;
				}
				$t .= $koma.$temp;
				$koma = ",";
			}
		}
		if (!$ada){
			$t .= $koma.$tgl." ".$jam;
		}
		$id = date("Ymd",strtotime($this->input->post("tgl"))).date("His",strtotime($this->input->post("jam")));
		$this->session->set_userdata("tgl_meows".$id,$this->input->post("tgl"));
		$this->session->set_userdata("jam_meows".$id,$this->input->post("jam"));
		$this->session->set_userdata("temp_meows",$t);
	}
	function hapustemp_meows(){
		$temp = $this->session->userdata("temp_meows");
		$id = $this->input->post("id");
		$row = explode(",", $temp);
		$t = $koma = "";
		if (is_array($row)){
			foreach ($row as $key => $val) {
				$value = date("YmdHis",strtotime($val));
				if ($value!=""){
					if ($id!=$value){
						$t .= $koma.$val;
						$koma = ",";
					}
				}
			}
		} else {
			if ($temp!=""){
				$value = date("YmdHis",strtotime($temp));
				if ($id!=$value){
					$t .= $koma.$temp;
					$koma = ",";
				}
			}
		}
		if ($t==""){
			$this->db->where("no_reg",$this->input->post("no_reg"));
			$this->db->delete("meows");
		}
		$this->session->set_userdata("temp_meows",$t);
	}
	function simpantemp_meows(){
		$message = $this->Mperawat->simpantemp_meows();
		$this->session->set_flashdata("message",$message);
	}
	function changesoap_meows(){
		$this->session->set_userdata("id".$this->input->post("id"),$this->input->post("id"));
		$this->session->set_userdata("rr".$this->input->post("id"),$this->input->post("rr"));
		$this->session->set_userdata("spo2".$this->input->post("id"),$this->input->post("spo2"));
		$this->session->set_userdata("pemakaian_o2".$this->input->post("id"),$this->input->post("pemakaian_o2"));
		$this->session->set_userdata("keterangan_pemakaian_o2".$this->input->post("id"),$this->input->post("keterangan_pemakaian_o2"));
		$this->session->set_userdata("suhu".$this->input->post("id"),$this->input->post("suhu"));
		$this->session->set_userdata("tensi".$this->input->post("id"),$this->input->post("tensi"));
		$this->session->set_userdata("tekanan_darah".$this->input->post("id"),$this->input->post("tekanan_darah"));
		$this->session->set_userdata("nadi".$this->input->post("id"),$this->input->post("nadi"));
		$this->session->set_userdata("tingkat_kesadaran".$this->input->post("id"),$this->input->post("tingkat_kesadaran"));
		$this->session->set_userdata("nyeri".$this->input->post("id"),$this->input->post("nyeri"));
		$this->session->set_userdata("lochea".$this->input->post("id"),$this->input->post("lochea"));
		$this->session->set_userdata("protein_urin".$this->input->post("id"),$this->input->post("protein_urin"));
		$this->session->set_userdata("score_ews".$this->input->post("id"),$this->input->post("score_ews"));
		$this->session->set_userdata("gula_darah".$this->input->post("id"),$this->input->post("gula_darah"));
		$this->session->set_userdata("konjungtiva".$this->input->post("id"),$this->input->post("konjungtiva"));
		$this->session->set_userdata("buah_dada".$this->input->post("id"),$this->input->post("buah_dada"));
		$this->session->set_userdata("kontraksi".$this->input->post("id"),$this->input->post("kontraksi"));
		$this->session->set_userdata("flatus".$this->input->post("id"),$this->input->post("flatus"));
		$this->session->set_userdata("fundur_uteri".$this->input->post("id"),$this->input->post("fundur_uteri"));
		$this->session->set_userdata("luka_pembedahan".$this->input->post("id"),$this->input->post("luka_pembedahan"));
		$this->session->set_userdata("perineum".$this->input->post("id"),$this->input->post("perineum"));
		$this->session->set_userdata("defekasi".$this->input->post("id"),$this->input->post("defekasi"));
		$this->session->set_userdata("bak".$this->input->post("id"),$this->input->post("bak"));
		$this->session->set_userdata("diastasis_retchi".$this->input->post("id"),$this->input->post("diastasis_retchi"));
		$this->session->set_userdata("jenis_persalinan".$this->input->post("id"),$this->input->post("jenis_persalinan"));
	}
	function cetakmeows($no_pasien,$no_reg,$tgl1,$tgl2){
		$data["no_pasien"]  = $no_pasien;
		$data["r"]          = $this->Mperawat->cetakmeows($no_reg,$tgl1,$tgl2);
		$data["tgl1"]        = $tgl1;
		$data["tgl2"]        = $tgl2;
		$data["q"]          = $this->Mpendaftaran->getpasien_detail($no_pasien);
		$this->load->view('perawat/vcetakmeows',$data);
	}
	function hapustemp2(){
		$this->session->unset_userdata("temp_pews");
		$this->session->unset_userdata("temp_news");
		$this->session->unset_userdata("temp_meows");
		$s = $this->session->userdata();
		foreach($s as $key => $value){
			if (strpos($key, "id") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "rr") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "spo2") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "metode_pemberian_o2") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "pemakaian_o2") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "keterangan_pemakaian_o2") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "upaya_nafas") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nadi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "crt") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nilai_kardiovaskuler") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "prilaku") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nebulizer") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "muntah_post_op") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nilai_prilaku") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nilai_pews_tambahan") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nilai_pews_total") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "suhu") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "tekanan_darah") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "gula_darah") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "luka") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "warna_luka") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "mobilisasi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "tinggi_badan") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "berat_badan") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "luka_skala_norton") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "total") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "intake") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "asi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "pasi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "intravena") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "darah") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "output") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "urine") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "muntah") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "feaces") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "drain") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "iwl") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "id") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "rr") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "spo2") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "pemakaian_o2") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "keterangan_pemakaian_o2") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "suhu") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "tensi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "tekanan_darah") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nadi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "tingkat_kesadaran") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nyeri") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "lochea") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "protein_urin") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "score_ews") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "gula_darah") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "konjungtiva") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "buah_dada") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "kontraksi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "flatus") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "fundur_uteri") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "luka_pembedahan") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "perineum") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "defekasi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "bak") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "diastasis_retchi") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "jenis_persalinan") !== false){
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "id") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "rr") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "spo2") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "pemakaian_o2") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "keterangan_pemakaian_o2") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "suhu") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "tensi") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "nadi") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "tingkat_kesadaran") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "score_ews") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "gula_darah") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "cvp") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "lingkar_perut") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "berat_badan") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "tinggi_badan") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "luka_skala_norton") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "oral") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "intravena") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "darah") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "urine") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "muntah") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "faeces") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "drain") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "iwl") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "konjungtiva") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "buah_dada") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "kontraksi") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "flatus") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "fundur_uteri") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "luka_pembedahan") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "perineum") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "defekasi") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "bak") !== false) {
				$this->session->unset_userdata($key);
			}
			if (strpos($key, "diastasis_retchi") !== false) {
				$this->session->unset_userdata($key);
			}
		}
	}
	function form_a($no_rm="", $no_reg=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_rm;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "FORM-A | CASE MANAGER || RS CIREMAI";
		$data["title_header"]     = "FORM-A | CASE MANAGER";
		$data["content"]          = "perawat/vform_a";
		$data["breadcrumb"]       = "<li class='active'><strong>FORM-A | CASE MANAGER</strong></li>";
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["q2"]                = $this->Mperawat->getcase_manager($no_rm, $no_reg);
		$this->load->view('template',$data);
	}
	function simpanform_a($aksi){
		$no_reg          = $this->input->post('no_reg');
		$no_rm           = $this->input->post('no_rm');
		$message = $this->Mperawat->simpanform_a($aksi);
		$this->session->set_flashdata("message", $message);
		redirect('perawat/form_a/'.$no_rm."/".$no_reg);
	}
	function form_b($no_rm="", $no_reg="",$id=""){
		$data["no_reg"]           = $no_reg;
		$data["no_pasien"]        = $no_pasien;
		$data["vmenu"]            = $this->session->userdata("controller")."/vmenu";
		$data['menu']             = "perawat";
		$data["title"]            = "FORM-B | CASE MANAGER || RS CIREMAI";
		$data["title_header"]     = "FORM-B | CASE MANAGER";
		$data["content"]          = "perawat/vform_b";
		$data["breadcrumb"]       = "<li class='active'><strong>FORM-B | CASE MANAGER</strong></li>";
		$data["dt"]				  = $this->Mperawat->getcaseformb_detail($id);
		$data["id"]				  = $id;
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["item"]             = $this->Mperawat->getitemformb();
		$data["dp"]               = $this->Mpendaftaran->getdokterperawat();
		$data["c"]                = $this->Mperawat->getcaseformb($no_rm,$no_reg);
		$this->load->view('template',$data);
	}
	function getpetugas(){
		$dp = $this->Mpendaftaran->getdokterperawat();
		echo json_encode($dp);
	}
	function simpanform_b($action){
		$no_reg          = $this->input->post('no_reg');
		$no_rm           = $this->input->post('no_rm');
		$message = $this->Mperawat->simpanform_b($action);
		$this->session->set_flashdata("message", $message);
		redirect('perawat/form_b/'.$no_rm."/".$no_reg);
	}
	function hapusform_b($no_rm,$no_reg,$id){
		$message = $this->Mperawat->hapusform_b($id);
		$this->session->set_flashdata("message", $message);
		redirect('perawat/form_b/'.$no_rm."/".$no_reg);
	}
	function cetakform_ab($no_reg,$no_pasien){
		$data["no_reg"]    = $no_reg;
		$data["no_pasien"] = $no_pasien;
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["q2"]                = $this->Mperawat->getcase_manager($no_pasien, $no_reg);
		$data["item"]             = $this->Mperawat->getitemformb();
		$data["dp"]               = $this->Mpendaftaran->getdokterperawat();
		$data["c"]                = $this->Mperawat->getcaseformb($no_pasien,$no_reg);
		$this->load->view("perawat/vcetakform_ab",$data);
	}
	function cetakform_b($no_reg,$no_pasien){
		$data["no_reg"]    = $no_reg;
		$data["no_pasien"] = $no_pasien;
		$data["q"]                = $this->Mdokter->getpasien_igdinap($no_reg);
		$data["q2"]                = $this->Mperawat->getcase_manager($no_rm, $no_reg);
		$data["item"]             = $this->Mperawat->getitemformb();
		$data["dp"]               = $this->Mpendaftaran->getdokterperawat();
		$data["c"]                = $this->Mperawat->getcaseformb($no_rm,$no_reg);
		$this->load->view("perawat/vcetakform_ab",$data);
	}
	function simpeg($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["q"]                = $this->Mperawat->getsimpeg_keluarga($id_perawat);
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["id_perawat"]       = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vsimpeg_keluarga";
		$data["title_header"]     = "Riwayat Keluarga";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat Keluarga</strong></li>";
		$this->load->view('template', $data);
	}
	function formsimpeg($id_perawat, $nik=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getsimpeg_detail($nik);
		$data["pend"]           = $this->Mperawat->getpendidikan2();
		$data["nik"]            = $nik;
		$data["id_perawat"]     = $id_perawat;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformsimpegkeluarga";
		$data["title_header"]   = "Form Keluarga";
		$data["breadcrumb"]     = "<li class='active'><strong>Keluarga</strong></li>";
		$this->load->view('template',$data);
	}
	function simpansimpeg($aksi)
    {
        $config['upload_path']          = './file_pdf/suket/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "keluarga-" . $this->input->post("nik");
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('filepdf')) {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $this->session->set_flashdata("message", $message);
        } else {
            $nama_file = "";
        }
        $id_perawat = $this->input->post("id_perawat");
        $message = $this->Mperawat->simpansimpeg($aksi, $nama_file);
        $this->db->where("id_perawat", $id_perawat);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('nik');
        redirect("perawat/simpeg/".$id_perawat);
    }
	function getpendidikan(){
		$pend = $this->Mperawat->getpendidikan();
		echo json_encode($pend);
	}
	function hapussimpeg($id, $id_perawat){
		$message = $this->Mperawat->hapussimpeg($id);
		$this->db->where("nik", $nik);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/simpeg/".$id_perawat);
    }
    function changedata(){
    	switch ($this->input->post("jenis")) {
    		case 'petugas':
    		$data = array("id_petugas" => $this->input->post("value"));
    		break;
    		case 'petugas':
    		$data = array("id_petugas" => $this->input->post("value"));
    		break;
    	}
    	$this->db->where("id",$this->input->post("id"));
    	$this->db->update("case_formb",$data);
    }
	function pendidikan($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["q"]                = $this->Mperawat->getpend($id_perawat);
		$data["row"]              = $this->Mperawat->getpendidikan_detail($id);
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["id_perawat"]       = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vpendidikan";
		$data["title_header"]     = "Riwayat Pendidikan";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat Pendidikan</strong></li>";
		$this->load->view('template', $data);
	}
	function formpendidikan($id_perawat, $id=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getpendidikan_detail($id);
		$data["pend"]           = $this->Mperawat->getpendidikan2();
		$data["id"]             = $id;
		$data["id_perawat"]     = $id_perawat;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformpendidikan";
		$data["title_header"]   = "Form Pendidikan";
		$data["breadcrumb"]     = "<li class='active'><strong>Pendidikan</strong></li>";
		$this->load->view('template',$data);
	}
	function simpanpendidikan($aksi)
    {
        $config['upload_path']          = './file_pdf/suket/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "pendidikan-" . $this->input->post("no_ijasah");
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('filepdf')) {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $this->session->set_flashdata("message", $message);
        } else {
            $nama_file = "";
        }
        $id_perawat = $this->input->post("id_perawat");
        $id = $this->input->post("id");
        $message = $this->Mperawat->simpanpendidikan($aksi, $nama_file);
        $this->db->where("id_perawat", $id_perawat);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('id');
        redirect("perawat/pendidikan/".$id_perawat);
    }
	function hapuspendidikan($id, $id_perawat){
		$message = $this->Mperawat->hapuspendidikan($id);
		$this->db->where("id", $id);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/pendidikan/".$id_perawat);
    }
    function riwayat_pangkat($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["q"]                = $this->Mperawat->getriwayat_pangkat($id_perawat);
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["id_perawat"]		  = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vriwayat_pangkat";
		$data["title_header"]     = "Riwayat Pangkat";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat Pangkat</strong></li>";
		$this->load->view('template', $data);
	}
	function formriwayat_pangkat($id_perawat, $id=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getriwayatpangkat_detail($id);
		$data["k"]              = $this->Mperawat->getkenaikan();
		$data["p"]              = $this->Mperawat->getpangkat();
		$data["c"]              = $this->Mperawat->getcpns();
		$data["id_perawat"]     = $id_perawat;
		$data["id"]             = $id;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformriwayat_pangkat";
		$data["title_header"]   = "Form Pangkat";
		$data["breadcrumb"]     = "<li class='active'><strong>Pangkat</strong></li>";
		$this->load->view('template',$data);
	}
	function simpanpangkat($aksi)
    {
        $config['upload_path']          = './file_pdf/suket/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "pangkat-" . $this->input->post("sk_no");
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('filepdf')) {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $this->session->set_flashdata("message", $message);
        } else {
            $nama_file = "";
        }
        $id = $this->input->post("id");
        $id_perawat = $this->input->post("id_perawat");
        $message = $this->Mperawat->simpanpangkat($aksi, $nama_file);
        $this->db->where("id", $id);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('id');
        redirect("perawat/riwayat_pangkat/".$id_perawat);
    }
	function hapuspangkat($id, $id_perawat){
		$message = $this->Mperawat->hapuspangkat($id);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/riwayat_pangkat/".$id_perawat);
    }
    function diklat($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["q"]                = $this->Mperawat->getriwayatdiklat($id_perawat);
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["id_perawat"]       = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vdiklat";
		$data["title_header"]     = "Riwayat Diklat";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat Diklat</strong></li>";
		$this->load->view('template', $data);
	}
	function formdiklat($id_perawat, $id=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getdiklat_detail($id);
		$data["d"]           	= $this->Mperawat->getdiklat();
		$data["id_perawat"]     = $id_perawat;
		$data["id"]             = $id;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformdiklat";
		$data["title_header"]   = "Form Diklat";
		$data["breadcrumb"]     = "<li class='active'><strong>Diklat</strong></li>";
		$this->load->view('template',$data);
	}
	function simpandiklat($aksi)
    {
        $config['upload_path']          = './file_pdf/suket/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "diklat-" . $this->input->post("nomor");
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('filepdf')) {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $this->session->set_flashdata("message", $message);
        } else {
            $nama_file = "";
        }
        $id_perawat = $this->input->post("id_perawat");
        $message = $this->Mperawat->simpandiklat($aksi, $nama_file);
        $this->db->where("id", $id);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('id');
        redirect("perawat/diklat/".$id_perawat);
    }
	function hapusdiklat($id, $id_perawat){
		$message = $this->Mperawat->hapusdiklat($id);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/diklat/".$id_perawat);
    }
    function militer($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["q"]                = $this->Mperawat->getriwayatmiliter($id_perawat);
		$data["id_perawat"]       = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vmiliter";
		$data["title_header"]     = "Riwayat Militer";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat Militer</strong></li>";
		$this->load->view('template', $data);
	}
	function formmiliter($id_perawat, $id=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getmiliter_detail($id);
		$data["m"]           	= $this->Mperawat->getmiliter();
		$data["id"]             = $id;
		$data["id_perawat"]     = $id_perawat;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformmiliter";
		$data["title_header"]   = "Form Militer";
		$data["breadcrumb"]     = "<li class='active'><strong>Militer</strong></li>";
		$this->load->view('template',$data);
	}
	function simpanmiliter($aksi)
    {
        $config['upload_path']          = './file_pdf/suket/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "militer-" . $this->input->post("nomor");
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('filepdf')) {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $this->session->set_flashdata("message", $message);
        } else {
            $nama_file = "";
        }
        // $id = $this->input->post("id");
        $id_perawat = $this->input->post("id_perawat");
        $message = $this->Mperawat->simpanmiliter($aksi, $nama_file);
        $this->db->where("id_perawat", $id_perawat);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('id');
        redirect("perawat/militer/".$id_perawat);
    }
	function hapusmiliter($id, $id_perawat){
		$message = $this->Mperawat->hapusmiliter($id);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/militer/".$id_perawat);
    }
    function penugasan($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["q"]                = $this->Mperawat->getpenugasan($id_perawat);
		$data["id_perawat"]       = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vpenugasan";
		$data["title_header"]     = "Riwayat Penugasan";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat Penugasan</strong></li>";
		$this->load->view('template', $data);
	}
	function formpenugasan($id_perawat, $id=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getpenugasan_detail($id);
		$data["p"]           	= $this->Mperawat->getprovinsi();
		$data["k"]           	= $this->Mperawat->getkota();
		$data["id"]             = $id;
		$data["id_perawat"]     = $id_perawat;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformpenugasan";
		$data["title_header"]   = "Form Penugasan";
		$data["breadcrumb"]     = "<li class='active'><strong>Penugasan</strong></li>";
		$this->load->view('template',$data);
	}
	function simpanpenugasan($aksi){
		$id = $this->input->post("id");
		$id_perawat = $this->input->post("id_perawat");
        $message = $this->Mperawat->simpanpenugasan($aksi);
        $this->db->where("id_perawat", $id_perawat);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('id');
        redirect("perawat/penugasan/".$id_perawat);
		// $message = $this->Mperawat->simpanpenugasan($aksi);
  //       $this->session->set_flashdata("message",$message);
  //       redirect("perawat/penugasan");
	}
	function hapuspenugasan($id, $id_perawat){
		$message = $this->Mperawat->hapuspenugasan($id);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/penugasan/".$id_perawat);
    }
    function kursus($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["q"]                = $this->Mperawat->getkursus($id_perawat);
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["id_perawat"]       = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vkursus";
		$data["title_header"]     = "Riwayat Kursus";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat Kursus</strong></li>";
		$this->load->view('template', $data);
	}
	function formkursus($id_perawat="",$id=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getkursus_detail($id);
		$data["id"]      		= $id;
		$data["id_perawat"]     = $id_perawat;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformkursus";
		$data["title_header"]   = "Form Kursus";
		$data["breadcrumb"]     = "<li class='active'><strong>Kursus</strong></li>";
		$this->load->view('template',$data);
	}
	function simpankursus($aksi)
    {
        $config['upload_path']          = './file_pdf/suket/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "kursus-" . $this->input->post("sk_no");
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('filepdf')) {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $this->session->set_flashdata("message", $message);
        } else {
            $nama_file = "";
        }
        $id_perawat = $this->input->post("id_perawat");
        $message = $this->Mperawat->simpankursus($aksi, $nama_file);
        $this->db->where("id_perawat", $id_perawat);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('id');
        redirect("perawat/kursus/".$id_perawat);
    }
	function hapuskursus($id, $id_perawat){
		$message = $this->Mperawat->hapuskursus($id);
		$this->db->where("id", $id);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/kursus/".$id_perawat);
    }
    function skp($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["q"]                = $this->Mperawat->getskp($id_perawat);
		$data["id_perawat"]       = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vskp";
		$data["title_header"]     = "Riwayat SKP";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat SKP</strong></li>";
		$this->load->view('template', $data);
	}
	function formskp($id_perawat, $id=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getskp_detail($id);
		$data["id"]             = $id;
		$data["id_perawat"]     = $id_perawat;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformskp";
		$data["title_header"]   = "Form SKP";
		$data["breadcrumb"]     = "<li class='active'><strong>SKP</strong></li>";
		$this->load->view('template',$data);
	}
	function simpanskp($aksi)
    {
        $config['upload_path']          = './file_pdf/suket/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "skp-" . $this->input->post("nilai");
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('filepdf')) {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $this->session->set_flashdata("message", $message);
        } else {
            $nama_file = "";
        }
        $id_perawat = $this->input->post("id_perawat");
        $message = $this->Mperawat->simpanskp($aksi, $nama_file);
        $this->db->where("id_perawat", $id_perawat);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('id');
        redirect("perawat/skp/".$id_perawat);
    }
	function hapusskp($id, $id_perawat){
		$message = $this->Mperawat->hapusskp($id);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/skp/".$id_perawat);
    }
    function jabatan($id_perawat)
	{
		$data["title"]            = $this->session->userdata('status_user');
		$data["username"]         = $this->session->userdata('username');
		$data["p"]                = $this->Mperawat->getriwayatperawat($id_perawat);
		$data["q"]                = $this->Mperawat->getjabatan_perawat($id_perawat);
		$data["id_perawat"]       = $id_perawat;
		$data['menu']             = "personalia";
		$data['vmenu']            = "admindkk/vmenu";
		$data["content"]          = "personalia/vjabatan";
		$data["title_header"]     = "Riwayat Jabatan";
		$data["breadcrumb"]       = "<li class='active'><strong>Riwayat Jabatan</strong></li>";
		$this->load->view('template', $data);
	}
	function formjabatan($id_perawat, $id=""){
		$data["title"]          = $this->session->userdata('status_user');
		$data["username"]       = $this->session->userdata('username');
		$data["q"]              = $this->Mperawat->getjabatanperawat_detail($id);
		$data["jab"]            = $this->Mperawat->getjabatan();
		$data["id"]             = $id;
		$data["id_perawat"]     = $id_perawat;
		$data['menu']           = "personalia";
		$data['vmenu']          = "admindkk/vmenu";
		$data["content"]        = "personalia/vformjabatan";
		$data["title_header"]   = "Form Jabatan";
		$data["breadcrumb"]     = "<li class='active'><strong>Jabatan</strong></li>";
		$this->load->view('template',$data);
	}
	function simpanjabatan($aksi)
    {
        $config['upload_path']          = './file_pdf/suket/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "jabatan-" . $this->input->post("no_kep");
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('filepdf')) {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $this->session->set_flashdata("message", $message);
        } else {
            $nama_file = "";
        }
        $id_perawat = $this->input->post("id_perawat");
        $message = $this->Mperawat->simpanjabatan($aksi, $nama_file, $nama_file2);
        $this->db->where("id_perawat", $id_perawat);
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata('id');
        redirect("perawat/jabatan/".$id_perawat);
    }
	function hapusjabatan($id, $id_perawat){
		$message = $this->Mperawat->hapusjabatan($id);
        $this->session->set_flashdata("message",$message);
        redirect("perawat/jabatan/".$id_perawat);
    }
}
