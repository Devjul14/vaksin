<?php
class Pendaftaran extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->Model('Mpendaftaran');
        if (($this->session->userdata('username') == NULL) || ($this->session->userdata('password') == NULL)) {
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        }
    }
    function migrasi($no_pasien)
    {
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data['menu'] = "user";
        $data["title"]        = "Pendaftaran || RS CIREMAI";
        $data["title_header"] = "Migrasi No RM";
        $data["content"] = "pendaftaran/vmigrasi";
        $data["breadcrumb"]   = "<li class='active'><strong>Migrasi No RM</strong></li>";
        $data["q1"] = $this->Mpendaftaran->getnoreg_autocomplete();
        $data["no_pasien_lama"]              = $no_pasien;
        // $data["row"]			  = $this->Mpendaftaran->getrjalandetail($id);
        $this->load->view('template', $data);
    }
    function simpanmigrasi()
    {
        $message = $this->Mpendaftaran->simpanmigrasi();
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata("no_reg", $this->input->post("no_reg"));
        redirect("pendaftaran/rawat_jalan");
    }
    function daftar()
    {
        $data["title"]        = "Pendaftaran || RS CIREMAI";
        $data['judul'] = "Pendaftaran Pasien&nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vpendaftaran";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "pendaftaran";
        $data["title_header"] = "Pendaftaran Pasien";
        $data["breadcrumb"] = "<li class='active'><strong>Pendaftaran Pasien</strong></li>";
        $data["q1"] = $this->Madmindkk->getpuskesmas();
        $data["q2"] = $this->Madmindkk->getlayanan();
        $data["q3"] = $this->Mpendaftaran->getstatuspembayaran();
        $data["q4"] = $this->Mpendaftaran->asal_pasien();
        $data["k"] = $this->Mpendaftaran->getkesatuan();
        $q4 = array();
        $q5 = array();
        $q = $this->Mpendaftaran->getpasien_autocomplete();
        foreach ($q->result() as $row) {
            $q5[] = array(
                "id" => $row->id_pasien,
                "label" => $row->nama_pasien
            );
        }
        $data["q5"] = $q5;
        $data["coba"] = $this->input->post('kode');
        $this->load->view('template', $data);
    }
    function home()
    {
        $data["title"] = "Hospital Management System < Home";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "home";
        $data['vmenu'] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "vhome";
        $data["title_header"] = "Home";
        $data["breadcrumb"] = "<li class='active'><strong>Home</strong></li>";
        $this->load->view('template', $data);
    }
    function caripasien($id_puskesmas)
    {
        $data["q1"] = $this->Mpendaftaran->getjenis_kelamin();
        $data["q2"] = $this->Mpendaftaran->getstatuspembayaran();
        $q = $this->Mpendaftaran->getjumlahpasien($id_puskesmas);

        $iskk = $this->input->post("iskk");
        if ($iskk == "Y") $data["chk"] = "checked";
        else $data["chk"] = "";

        $status_pembayaran = $this->input->post("status_pembayaran");
        $data["status_pembayaran"] = $status_pembayaran;

        $jenis_kelamin = $this->input->post("jenis_kelamin");
        $data["jenis_kelamin"] = $jenis_kelamin;

        $nama_pasien = $this->input->post("nama_pasien");
        $data["nama_pasien"] = $nama_pasien;

        $tgl1 = $this->input->post("tgl1");
        $data["tgl1"] = $tgl1;

        $tgl2 = $this->input->post("tgl2");
        $data["tgl2"] = $tgl2;

        $baris = $this->input->post("baris");
        if ($baris == "") $baris = 50;

        $hal = $this->input->post("hal");
        if ($hal == "") $hal = 1;

        $row = $q->row();
        $jmlrec = $row->jumlah;
        $n = $jmlrec / $baris;
        if ($n == floor($jmlrec / $baris)) $npage = $n;
        else $npage = floor($jmlrec / $baris) + 1;
        if ($npage == 0) $npage = 1;
        $posisi = ($hal - 1) * $baris;
        $data["q3"] = $this->Mpendaftaran->getpasien($id_puskesmas, $posisi, $baris);
        $data["npage"] = $npage;
        $data["hal"] = $hal;
        $data["baris"] = $baris;
        $data["posisi"] = $posisi;
        $data["id_puskesmas"] = $id_puskesmas;
        $data["jmlrec"] = $jmlrec;
        $this->load->view('pendaftaran/vcaripasien', $data);
    }
    function getnamakk($id_puskesmas, $no_kk)
    {
        $q = $this->Mpendaftaran->getlistpasien($id_puskesmas, $no_kk, "Y");
        $row = $q->row();
        echo "<input type='text' class='form-control' value='" . $row->nama_pasien . "' disabled>";
    }
    function datapasien()
    {
        $q = $this->Mpendaftaran->datapasien($this->input->post('kode'), $this->input->post('cari'));
        switch ($this->input->post('kode')) {
            case 'id_card':
                if ($q) {
                    $data = $q->id_pasien . "-" . $q->nama_pasien;
                    echo $data;
                } else echo "";
                break;
            case 'id_pasien':
                if ($q) {
                    $data = $q->id_card;
                    echo $data;
                } else echo "";
                break;
        }
    }
    function getdetailpasien($id_pasien)
    {
        $row = $this->Mpendaftaran->getdetailpasien($id_pasien)->row();
        $q3 = $this->Mpendaftaran->getstatuspembayaran();
        $html = "<script>
				 $(document).ready(function(){

					var id_layanan = $(\"select[name='id_layanan']\").val();
					var status_pembayaran = $(\"select[name='status_pembayaran']\").val();
					var url = \"" . site_url('pendaftaran/karcis') . "/\"+id_layanan+\"/\"+status_pembayaran;
					$('#karcis').load(url);
					var id_puskesmas = $(\"select[name='id_puskesmas']\").val();
					var url = \"" . site_url('pendaftaran/getnamakk') . "/\"+id_puskesmas+\"/\"+\"" . $row->no_kk . "\";
					$('#nama_kk').load(url);
				});
				</script>";
        $html .= '
				<div class="form-group"><label class="col-sm-2 control-label">No. Pasien</label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control span1" name="no_pasien" value="' . $row->id_pasien . '" disabled>
                    	<input type="hidden" name="id_pasien" value="' . $row->id_pasien . '">
                    </div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Pembayaran</label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" name="status_pembayaran" value="' . $row->status_pembayaran . '" disabled>
                    </div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Alamat</label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" name="alamat" value="' . $row->alamat . '" disabled>
                    </div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Kecamatan</label>
                    <div class="col-sm-10"><input type="text" class="form-control" name="nama_kecamatan" value="' . $row->nama_kecamatan . '" disabled></div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Kelurahan</label>
                    <div class="col-sm-10"><input type="text" class="form-control"  name="nama_kelurahan" value="' . $row->nama_kelurahan . '" disabled></div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">RW</label>
                    <div class="col-sm-10"><input type="text" class="form-control"  name="nama_rw" value="' . $row->nama_rw . '" disabled></div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Status Pembayaran</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="status_pembayaran" class="form-control">';
        foreach ($q3->result() as $r) {
            $html .= "<option value='" . $r->status_pembayaran . "' " . ($r->status_pembayaran == $row->status_pembayaran ? "selected" : "") . ">" . $r->status_pembayaran . "</option>";
        }
        $html .= '   </select>
                            </div>
                            <div class="col-md-6"><span id="karcis"></span></div>
                        </div>
                    </div>
                </div>
		';

        // $html.="<div class='control-group'>
        // 			<label class='control-label'>No. Pasien</label>
        // 				<div class='controls'>
        // 				<input type='hidden' name='id_pasien' value='".$row->id_pasien."'>
        // 				<input type='text' name='no_pasien' value='".$row->no_pasien."' disabled class='span1'>
        // 				</div>
        // 			</div>
        // 			<div class='control-group'>
        // 			<label class='control-label'>Pembayaran</label>
        // 				<div class='controls'>
        // 				<input type='text' name='status_pembayaran' value='".$row->status_pembayaran."' disabled>
        // 				</div>
        // 			</div>
        // 			<div class='control-group'>
        // 			<label class='control-label'>Alamat</label>
        // 				<div class='controls'><input type='text' name='alamat' value='".$row->alamat."' disabled></div>
        // 			</div>
        // 			<div class='control-group'>
        // 			<label class='control-label'>Kecamatan</label>
        // 				<div class='controls'><input type='text' name='nama_kecamatan' value='".$row->nama_kecamatan."' disabled></div>
        // 			</div>
        // 			<div class='control-group'>
        // 			<label class='control-label'>Kelurahan</label>
        // 				<div class='controls'><input type='text' name='nama_kelurahan' value='".$row->nama_kelurahan."' disabled></div>
        // 			</div>
        // 			<div class='control-group'>
        // 			<label class='control-label'>RW</label>
        // 				<div class='controls'><input type='text' name='nama_rw' value='".$row->nama_rw."' disabled></div>
        // 			</div>
        // 			<div class='control-group'>
        // 				<label class='control-label'>Status Pembayaran</label>
        // 					<div class='controls'>
        // 						<select name='status_pembayaran'>";
        // 							foreach($q3->result() as $r){
        // 								$html .="<option value='".$r->status_pembayaran."' ".($r->status_pembayaran==$row->status_pembayaran ? "selected" : "").">".$r->status_pembayaran."</option>";
        // 							}
        // 						$html .= "
        // 						</select>&nbsp;<span id='karcis'></span>
        // 					</div>
        // 			</div>
        // 			";
        echo $html;
    }
    function karcis($id_layanan, $status_pembayaran)
    {
        $row = $this->Madmindkk->getlayanandetail($id_layanan)->row();
        switch ($status_pembayaran) {
            case 'BPJS':
                $karcis = 0;
                break;
            case 'ASURANSI':
                $karcis = 0;
                break;
            case 'PRIBADI':
                $karcis = $row->karcis;
                break;
        }
        echo "
				<div class='input-group m-b'><span class='input-group-addon'>Rp</span> <input type='text' class='form-control span2 text-right' name='karcis' id='appendedPrependedInput' type='text' value='" . number_format($karcis, 0, ',', '.') . "'> <span class='input-group-addon'>.00</span></div>

			";
    }
    function simpanpendaftaran()
    {
        $message = $this->Mpendaftaran->simpanpendaftaran();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran");
    }
    function cetakresume($no_rm)
    {
        $data["title"]    = "Cetak Resume";
        $data["no_rm"]    = $no_rm;
        $data["q"]        = $this->Mpendaftaran->cetakresume($no_rm);
        $data["q1"]        = $this->Mpendaftaran->getpasien_detail($no_rm);
        $this->load->view('pendaftaran/vcetakresume', $data);
    }
    function index($current = 0, $from = 0)
    {
        $data["title"]        = "Data Pasien || RS CIREMAI";
        $data['judul'] = "Identitas Pasien&nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vpasienbaru";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "user";
        $data["current"] = $current;
        $data["title_header"] = "Identitas Pasien";
        $data["breadcrumb"] = "<li class='active'><strong>Identitas Pasien</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'pendaftaran/index/' . $current;
        $config['total_rows'] = $this->Mpendaftaran->getjumlahpasien();
        $config['per_page'] = 20;
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
        $config['num_links'] = 1;
        $config['uri_segment'] = 4;
        $from = $this->uri->segment(4);
        $data["from"] = $from;
        $this->pagination->initialize($config);
        $data["q3"] = $this->Mpendaftaran->getpasien($config['per_page'], $from);
        $this->load->view('template', $data);
    }
    function getcaripasien()
    {
        $this->session->set_userdata("no_pasien", $this->input->post("cari_no"));
        $this->session->set_userdata("nama", $this->input->post("cari_nama"));
    }
    function getcaripasien_ralan()
    {
        $this->session->set_userdata("no_pasien", $this->input->post("cari_no"));
        $this->session->set_userdata("status_vaksin", $this->input->post("status_vaksin"));
    }
    function getcaripasien_inap()
    {
        $this->session->set_flashdata("no_pasien", $this->input->post("cari_no"));
        $this->session->set_flashdata("nama", $this->input->post("cari_nama"));
        $this->session->set_flashdata("no_reg", $this->input->post("cari_noreg"));
    }
    function addpasienbaru($id_pasien = NULL)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pendaftaran Pasien Baru&nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vaddpasienbaru";
        $data['menu'] = "user";
        $data["username"] = $this->session->userdata('nama_user');
        $data["title_header"] = "Identitas Pasien";
        $data["breadcrumb"] = "<li class='active'><strong>Identitas Pasien</strong></li>";
        $data["idlama"] = $id_pasien;
        $data["row"] = $this->Mpendaftaran->getdetailpasien($id_pasien);
        $data["q2"] = $this->Mpendaftaran->getjenis_kelamin();
        $data["k1"] = $this->Mpendaftaran->getgolpasien();
        $this->load->view('template', $data);
    }
    function ambil_province(){
      $q = $this->db->get("propinsi");
      echo json_encode($q->result());
    }
    function addpasienbaru_inap($iskk, $baru, $no_kk = NULL, $id_pasien = NULL, $no_reg = NULL)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pendaftaran Pasien Baru&nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vaddpasienbaru_inap";
        $data['menu'] = "user";
        $data["username"] = $this->session->userdata('nama_user');
        $data["title_header"] = "Identitas Pasien Rawat Inap";
        $data["breadcrumb"] = "<li class='active'><strong>Identitas Pasien Rawat Inap</strong></li>";
        $data["iskk"] = $iskk;
        $data["baru"] = $baru;
        $data["idlama"] = $id_pasien;
        $data["no_reg"] = $no_reg;
        $data["row"] = $this->Mpendaftaran->getdetailpasien($id_pasien);
        $data["q1"] = $this->Mpendaftaran->getstatus_keluarga();
        $data["q2"] = $this->Mpendaftaran->getjenis_kelamin();
        $data["q4"] = $this->Mpendaftaran->getpendidikan();
        $data["q5"] = $this->Mpendaftaran->getpekerjaan();
        $data["q6"] = $this->Mpendaftaran->getgolongan();
        $data["q7"] = $this->Mpendaftaran->getkepala_keluarga($no_kk);
        $data["q8"] = $this->Mpendaftaran->getstatuspembayaran();
        $data["k"] = $this->Mpendaftaran->getkesatuan();
        $data["k1"] = $this->Mpendaftaran->getgolpasien();
        $data["k3"] = $this->Mpendaftaran->getcabang();
        $data['provinsi'] = json_decode($this->ambil_province())->rajaongkir->results;
        $data["kw"] = $this->Mpendaftaran->getkawin();
        $data["h"] = $this->Mpendaftaran->gethubungankeluarga();
        $data["s"] = $this->Mpendaftaran->getsuku();
        $this->load->view('template', $data);
    }
    function viewrjalan($id, $igd = false)
    {
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data['menu'] = "user";
        $data["igd"] = $igd;
        $data["title"]        = "Rawat Jalan || RS CIREMAI";
        $data["title_header"] = "Pasien Rawat Jalan";
        $data["content"] = "pendaftaran/vformrawatjalan";
        $data["breadcrumb"]   = "<li class='active'><strong>Pasien Rawat Jalan</strong></li>";
        $data["row"]              = $this->Mpendaftaran->getrjalandetail($id);
        $data["no_reg"]              = $this->Mpendaftaran->getnoreg();
        $data["tarif"]              = $this->Mpendaftaran->gettarif($igd, "tdk");
        $data["d"]              = $this->Mpendaftaran->getdokter();
        $this->load->view('template', $data);
    }
    function getnoreg()
    {
        $tanggal = $this->input->post("tanggal");
        $q = $this->Mpendaftaran->getnoreg($tanggal);
        echo $q;
    }
    function simpanrawatjalan()
    {
        $message = $this->Mpendaftaran->simpanrjalan();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_jalan");
    }
    function viewinap($id)
    {
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data['menu'] = "user";
        $data["title"]        = "Rawat Inap || RS CIREMAI";
        $data["title_header"] = "Pasien Rawat Inap";
        $data["content"] = "pendaftaran/vformrawatinap";
        $data["breadcrumb"]   = "<li class='active'><strong>Pasien Rawat Inap</strong></li>";
        $data["row"]              = $this->Mpendaftaran->getinapdetail($id);
        $data["no_reg"]              = $this->Mpendaftaran->getnoreginap();
        $data["tarif"]              = $this->Mpendaftaran->gettarif(true, "tdk");
        $this->load->view('template', $data);
    }
    function getnoreginap()
    {
        $tanggal = $this->input->post("tanggal");
        $jam_masuk = $this->input->post("jam");
        $q = $this->Mpendaftaran->getnoreginap($tanggal, $jam_masuk);
        echo $q;
    }
    function editinap($id, $no_reg)
    {
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data['menu'] = "user";
        $data["title"]        = "Rawat Inap || RS CIREMAI";
        $data["title_header"] = "Pasien Rawat Inap";
        $data["content"] = "pendaftaran/vformeditinap";
        $data["no_reg"] = $no_reg;
        $data["id"] = $id;
        $data["breadcrumb"]   = "<li class='active'><strong>Pasien Rawat Inap</strong></li>";
        $data["q"]                 = $this->Mpendaftaran->getdokter();
        $data["p"]                 = $this->Mperawat->getperawat();
        $data["row"]              = $this->Mpendaftaran->getinap_edit($id, $no_reg);
        $data["tarif"]              = $this->Mpendaftaran->gettarif(true, "tdk");
        $this->load->view('template', $data);
    }
    function pindahkamar($id, $no_reg)
    {
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data['menu'] = "user";
        $data["title"]        = "Rawat Inap || RS CIREMAI";
        $data["title_header"] = "Pasien Rawat Inap";
        $data["content"] = "pendaftaran/vformpindahkamar";
        $data["no_reg"] = $no_reg;
        $data["id"] = $id;
        $data["breadcrumb"]   = "<li class='active'><strong>Pasien Rawat Inap</strong></li>";
        $data["q"]                 = $this->Mpendaftaran->getdokter();
        $data["row"]              = $this->Mpendaftaran->getinap_edit($id, $no_reg);
        $data["tarif"]              = $this->Mpendaftaran->gettarif(true, "tdk");
        $this->load->view('template', $data);
    }
    function pindahstatus($id, $no_reg)
    {
        $data["vmenu"]             = $this->session->userdata("controller") . "/vmenu";
        $data['menu']            = "inap";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "Pindah Status Pasien Rawat Inap";
        $data["content"]         = "pendaftaran/vformpindahstatus";
        $data["no_reg"]         = $no_reg;
        $data["id"]             = $id;
        $data["breadcrumb"]       = "<li class='active'><strong>Pindah Status Pasien Rawat Inap</strong></li>";
        $data["q"]                 = $this->Mpendaftaran->getdokter();
        $data["row"]            = $this->Mpendaftaran->getinap_edit($id, $no_reg);
        $data["tarif"]            = $this->Mpendaftaran->gettarif(true, "tdk");
        $data["g"]                = $this->Mpendaftaran->getgolpasien();
        $data["p"]                = $this->Mpendaftaran->getperusahaan();
        $this->load->view('template', $data);
    }
    function pindahstatus_ralan($id, $no_reg)
    {
        $data["vmenu"]             = $this->session->userdata("controller") . "/vmenu";
        $data['menu']            = "jalan";
        $data["title"]            = "Rawat Jalan || RS CIREMAI";
        $data["title_header"]     = "Pindah Status Pasien Rawat Jalan";
        $data["content"]         = "pendaftaran/vformpindahstatus_ralan";
        $data["no_reg"]         = $no_reg;
        $data["id"]             = $id;
        $data["breadcrumb"]       = "<li class='active'><strong>Pindah Status Pasien Rawat Jalan</strong></li>";
        $data["q"]                 = $this->Mpendaftaran->getdokter();
        $data["row"]            = $this->Mpendaftaran->getralan_edit($id, $no_reg);
        $data["tarif"]            = $this->Mpendaftaran->gettarif(true, "tdk");
        $data["g"]                = $this->Mpendaftaran->getgolpasien();
        $data["p"]                = $this->Mpendaftaran->getperusahaan();
        $this->load->view('template', $data);
    }
    function inos($id="", $no_reg="", $kode_inos="")
    {
        $data["vmenu"]           = $this->session->userdata("controller") . "/vmenu";
        $data['menu']            = "inap";
        $data["title"]           = "Rawat Inap || RS CIREMAI";
        $data["title_header"]    = "INOS Pasien Rawat Inap";
        $data["content"]         = "pendaftaran/vforminos";
        $data["no_reg"]          = $no_reg;
        $data["id"]              = $id;
        $data["kode_inos"]       = $kode_inos;
        $data["breadcrumb"]      = "<li class='active'><strong>INOS Pasien Rawat Inap</strong></li>";
        $data["q"]               = $this->Mpendaftaran->getjenisinos();
        $data["p"]               = $this->Mpendaftaran->getinap_edit($id, $no_reg);
        $data["q1"]              = $this->Mpendaftaran->getinos($id, $no_reg);
        $data["q2"]              = $this->Mpendaftaran->getinos_detail($id, $no_reg, $kode_inos);
        $data["s"]               = $this->Mpendaftaran->getspesialisasi();
        $this->load->view('template', $data);
    }
    function inos_harian()
    {
        $data["title"]    = "Inos Harian Rawat Inap";
        $data["q"]        = $this->Mpendaftaran->getjenisinos();
        $data["row"]    = $this->Mpendaftaran->getpasien_inos();
        $this->load->view('pendaftaran/vinos_harian', $data);
    }
    function simpanrawatinap()
    {
        $message = $this->Mpendaftaran->simpaninap();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inap");
    }
    function editrawatinap()
    {
        $message = $this->Mpendaftaran->editrawatinap();
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata("no_reg", $this->input->post("no_reg"));
        $this->session->set_flashdata("no_pasien", $this->input->post("no_pasien"));
        redirect("pendaftaran/rawat_inap");
    }
    function simpanpindahkamar()
    {
        $message = $this->Mpendaftaran->pindahkamar();
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata("no_reg", $this->input->post("no_reg"));
        $this->session->set_flashdata("no_pasien", $this->input->post("no_pasien"));
        redirect("pendaftaran/rawat_inap");
    }
    function simpanpindahstatus()
    {
        $message = $this->Mpendaftaran->simpanpindahstatus();
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata("no_reg", $this->input->post("no_reg"));
        $this->session->set_flashdata("no_pasien", $this->input->post("no_pasien"));
        redirect("pendaftaran/rawat_inap");
    }
    function simpanpindahstatus_ralan()
    {
        $message = $this->Mpendaftaran->simpanpindahstatus_ralan();
        $this->session->set_flashdata("message", $message);
        $this->session->set_flashdata("no_reg", $this->input->post("no_reg"));
        $this->session->set_flashdata("no_pasien", $this->input->post("no_pasien"));
        redirect("pendaftaran/rawat_jalan");
    }
    function simpaninos($aksi)
    {
        $message = $this->Mpendaftaran->simpaninos($aksi);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/inos/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
    }
    function getgol_pekerjaan()
    {
        $q = $this->Mpendaftaran->getgolongan();
        $html = "<select name='gol_pekerjaan'>";
        foreach ($q->result() as $row) {
            $html .= "<option value='" . $row->gol . "'>" . $row->gol . "</option>";
        }
        $html .= "</select>";
        echo $html;
    }
    function simpanpasienbaru($action)
    {
        $no_reg = $this->input->post("no_reg");
        $message = $this->Mpendaftaran->simpanpasienbaru($action);
        $this->session->set_flashdata("message", $message);
        $m = explode("-", $message);
        $this->session->set_flashdata('no_pasien', $m[2]);
        if ($no_reg == "") {
            redirect("pendaftaran");
        } else {
            redirect("pendaftaran/rawat_jalan");
        }
    }
    function simpanpasienbaru_inap($action)
    {
        $no_reg = $this->input->post("no_reg");
        $message = $this->Mpendaftaran->simpanpasienbaru_inap($action);
        $this->session->set_flashdata("message", $message);
        $m = explode("-", $message);
        $this->session->set_flashdata('no_pasien', $m[2]);
        if ($no_reg == "") {
            redirect("pendaftaran");
        } else {
            redirect("pendaftaran/rawat_inap");
        }
    }
    function hapuspasien_inap($id)
    {
        $message = $this->Mpendaftaran->hapuspasien_inap($id);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inap");
    }
    function rekapdaftar()
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Rekap Data Pendaftaran Berobat&nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vrekapdaftar";
        $data['menu'] = "rekap";
        $data["username"] = $this->session->userdata('nama_user');
        $data["title_header"] = "Rekap Pendaftaran Pasien";
        $data["breadcrumb"] = "<li class='active'><strong>Rekap Pendaftaran Pasien</strong></li>";
        $this->load->view('template', $data);
    }
    function listrekap($tgl1 = NULL, $tgl2 = NULL, $jenis = NULL, $umur = NULL)
    {
        if ($tgl1 == "") $tgl1 = date('Y-m-d');
        if ($tgl2 == "") $tgl2 = date('Y-m-d');
        $data["q1"] = $this->Madmindkk->getlayanan();
        $data["q2"] = $this->Mpendaftaran->getstatuspembayaran();
        $data["jumlah"] = $this->Mpendaftaran->rekaplayanan($tgl1, $tgl2, $jenis, $umur);
        $data["jml"] = $this->Mpendaftaran->rekap_status_pembayaran($tgl1, $tgl2, $jenis, $umur);
        echo $this->load->view('pendaftaran/vlistrekap', $data, true);
    }
    function listrekap_pasien($id_pasien = NULL)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Rekap Data Pasien&nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vrekappasien";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "rekap";
        $data["title_header"] = "Rekap Pasien";
        $data["breadcrumb"] = "<li class='active'><strong>Rekap Data Pasien</strong></li>";
        $data["q1"] = $this->Madmindkk->getlayanan();
        $data["q2"] = $this->Mpendaftaran->getstatuspembayaran();
        $data["jumlah"] = $this->Mpendaftaran->rekaplayanan_pasien($id_pasien);
        $data["jml"] = $this->Mpendaftaran->rekap_status_pembayaran_pasien($id_pasien);
        $this->load->view('template', $data);
    }
    function listkunjungan()
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Rekap Data Pasien&nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vkunjungan";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "rekap";
        $data["title_header"] = "Rekap Pasien";
        $data["breadcrumb"] = "<li class='active'><strong>Rekap Data Pasien</strong></li>";

        // $data["title"] = $this->session->userdata('status_user');
        // $data['judul'] = "Daftar Kunjungan&nbsp;&nbsp;&nbsp;";
        // $data["menu"] = $this->session->userdata("controller")."/vmenu";
        //$data["content"] = "pendaftaran/vkunjungan";
        $data["q2"] = $this->Madmindkk->getlayanan();
        $baris = $this->input->post("baris");
        $tgl = $this->input->post("tgl");
        if ($tgl == "") $tgl = date('d-m-Y');
        $data["tgl"] = $tgl;
        if ($baris == "") $baris = 50;
        $id_layanan = $this->input->post("id_layanan");
        if ($id_layanan == "") $id_layanan = 3;
        $data["id_layanan"] = $id_layanan;
        $q = $this->Mpendaftaran->jumlah_listkunjungan($id_layanan);
        $hal = $this->input->post("hal");
        if ($hal == "") $hal = 1;
        $jmlrec = $q->num_rows();
        $n = $jmlrec / $baris;
        if ($n == floor($jmlrec / $baris)) $npage = $n;
        else $npage = floor($jmlrec / $baris) + 1;
        if ($npage == 0) $npage = 1;
        $posisi = ($hal - 1) * $baris;
        $data["q"] = $this->Mpendaftaran->listkunjungan($id_layanan, $posisi, $baris);
        $data["npage"] = $npage;
        $data["hal"] = $hal;
        $data["baris"] = $baris;
        $data["posisi"] = $posisi;
        $data["jmlrec"] = $jmlrec;
        $this->load->view('template', $data);
    }
    function addtindakan($id = NULL)
    {
        $data["breadcrumb"] = "<li class='active'><strong>Tindakan</strong></li>";
        $data["title_header"] = "Tindakan";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Tindakan&nbsp;&nbsp;&nbsp;";
        $data["menu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vaddtindakan";
        $data["row"] = $this->Mpendaftaran->gettindakandetail($id)->row();
        $this->load->view('template', $data);
    }
    function simpantindakan($action)
    {
        $message = $this->Mpendaftaran->simpantindakan($action);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/tindakan");
    }
    function hapustindakan($id)
    {
        $message = $this->Mpendaftaran->hapustindakan($id);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/tindakan");
    }
    function ambil_kota($prov = "")
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=" . $prov . "&id=",
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
    function ambil_kecamatan($city = "")
    {
        $q = $this->db->get("kotakabupaten");
        echo json_encode($q->result());
    }

    function ambildata_pangkat()
    {
        $id = $this->input->post('id');
        $data = $this->Mpendaftaran->getpangkat($id);
        echo json_encode($data);
    }
    function ambildata_ketcabang()
    {
        $id = $this->input->post('id');
        $data = $this->Mpendaftaran->getketcabang($id);
        echo json_encode($data);
    }
    function pilihwilayah($jenis)
    {
        $nama = $this->input->post("nama");
        $data["nama"] = $nama;
        $data["jenis"] = $jenis;
        $data["q"]                 = $this->Mpendaftaran->getwilayah($nama);
        $data["title"]            = $jenis . " || RS CIREMAI";
        $data["title_header"]     = $jenis;
        $this->load->view('pendaftaran/vpilih_wilayah', $data);
    }
    function pilihperusahaan()
    {
        $data["q"]                 = $this->Mpendaftaran->getperusahaan();
        $data["title"]            = "Perusahaan || RS CIREMAI";
        $data["title_header"]     = "Perusahaan";
        $this->load->view('pendaftaran/vpilih_perusahaan', $data);
    }
    function pilihpangkat($id_golongan)
    {
        $data["q"]                 = $this->Mpendaftaran->pilihpangkat($id_golongan);
        $data["title"]            = "Perusahaan || RS CIREMAI";
        $data["title_header"]     = "Perusahaan";
        $data["id_golongan"]    = $id_golongan;
        $this->load->view('pendaftaran/vpilih_pangkat', $data);
    }
    function pilihpoli()
    {
        $data["q"]                 = $this->Mpendaftaran->getpoli();
        $data["title"]            = "Poli || RS CIREMAI";
        $data["title_header"]     = "Poli";
        $this->load->view('pendaftaran/vpilih_poli', $data);
    }
    function pilihpolid()
    {
        $data["q"]                 = $this->Mpendaftaran->getpoli();
        $data["title"]            = "Poli || RS CIREMAI";
        $data["title_header"]     = "Poli";
        $this->load->view('pendaftaran/vpilih_polid', $data);
    }
    function pilihnoreg()
    {
        $no_reg = $this->input->post("no_reg");
        $data["no_reg"] = $no_reg;
        $data["q"]                 = $this->Mpendaftaran->getpilihnoreg($no_reg);
        $data["title"]            = "No REG || RS CIREMAI";
        $data["title_header"]     = "No REG";
        $this->load->view('pendaftaran/vpilih_noreg', $data);
    }
    function pilihruangan()
    {
        $data["q"]                 = $this->Mpendaftaran->getruangan();
        $data["title"]            = "Ruangan || RS CIREMAI";
        $data["title_header"]     = "Ruangan";
        $this->load->view('pendaftaran/vpilih_ruangan', $data);
    }
    function pilihruangan1()
    {
        $data["q"]                 = $this->Mpendaftaran->getruangan1();
        $data["title"]            = "Ruangan || RS CIREMAI";
        $data["title_header"]     = "Ruangan";
        $this->load->view('pendaftaran/vpilih_ruangana', $data);
    }
    function pilihkelas()
    {
        $data["q"]                 = $this->Mpendaftaran->getkelas();
        $data["title"]            = "Kelas || RS CIREMAI";
        $data["title_header"]     = "Kelas";
        $this->load->view('pendaftaran/vpilih_kelas', $data);
    }
    function pilihdokter()
    {
        $data["q"]                 = $this->Mpendaftaran->getdokter();
        $data["title"]            = "Dokter || RS CIREMAI";
        $data["title_header"]     = "Dokter";
        $this->load->view('pendaftaran/vpilih_dokter', $data);
    }
    function pilihdokterpoli($kode_poli = "")
    {
        $data["q"]                 = $this->Mpendaftaran->getdokterpoli($kode_poli);
        $data["title"]            = "Dokter || RS CIREMAI";
        $data["title_header"]     = "Dokter";
        $this->load->view('pendaftaran/vpilih_dokterpoli', $data);
    }
    function pilihdiagnosa($kode = "")
    {
        $data["q"]                 = $this->Mpendaftaran->pilihdiagnosa($kode);
        $data["title"]            = "Diagnosa || RS CIREMAI";
        $data["title_header"]     = "Diagnosa";
        $this->load->view('pendaftaran/vpilih_diagnosa', $data);
    }
    function cetakpasien($no_pasien)
    {
        $data["no_pasien"]    = $no_pasien;
        $data["q"]            = $this->Mpendaftaran->getcetakpasien($no_pasien);
        $this->load->view('pendaftaran/vcetakrekmed', $data);
    }
    function cetakinap($no_rm, $no_reg)
    {
        $data["title"]    = "Cetak Inap";
        $data["no_rm"]    = $no_rm;
        $data["no_reg"]    = $no_reg;
        $data["q"]        = $this->Mpendaftaran->getcetakinap($no_rm, $no_reg);
        $this->load->view('pendaftaran/vcetakinap', $data);
    }
    function cetak_rekmed($id)
    {
        $data["id"]    = $id;
        $data["q"]            = $this->Mpendaftaran->getcetakrekmed($id);
        $this->load->view('pendaftaran/vcetakrekmed', $data);
    }
    function rawat_jalan($current = 0, $from = 0)
    {
        // 	$minimum_price = $this->input->post('tgl1');
        // $maximum_price = $this->input->post('tgl2');
        // $brand = $this->input->post('kode_poli');
        // $brand = $this->input->post('kode_doker');
        $this->session->unset_userdata("temptindakan");
        if ($this->session->userdata("status_vaksin") == "")
            $this->session->set_flashdata("status_vaksin", "ALL");
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pasien Rawat Jalan &nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vlistrawatjalan";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "ralan";
        $data["current"] = $current;
        $data["title_header"] = "Pasien Rawat Jalan ";
        $data["p"] = $this->Mpendaftaran->getpoli();
        $data["splg"] = $this->Mpendaftaran->getstatus_pulang();
        $data["kplg"] = $this->Mpendaftaran->getkeadaan_pulang();
        $data["jlayan"] = $this->Mpendaftaran->gettotalpasien("LAYAN");
        $data["jbatal"] = $this->Mpendaftaran->gettotalpasien("BATAL");
        $data["breadcrumb"] = "<li class='active'><strong>Pasien Rawat Jalan</strong></li>";
        $this->load->library('pagination');
        $data["total_rows"] = $this->Mpendaftaran->getpasien_rawatjalan();
        $config['base_url'] = base_url() . 'pendaftaran/rawat_jalan/' . $current;
        $config['total_rows'] = $data["total_rows"];
        $config['per_page'] = 10;
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
        $config['num_links'] = 3;
        $config['uri_segment'] = 4;
        $from = $this->uri->segment(4);
        $data["from"] = $from;
        $this->pagination->initialize($config);
        $data["q3"] = $this->Mpendaftaran->getpasien_ralan($config['per_page'], $from);
        $data["artikel"] = $this->Mpendaftaran->getartikel();
        $data["tm"] = $this->Mpendaftaran->gettindakan_medis();
        $data["dok"] = $this->Mpendaftaran->getdokter();
        $data["dp"] = $this->Mpendaftaran->getdokterperawat();
        $data["poli"] = $this->Mpendaftaran->getpoli_array();
        $data["tempat_vaksin"] = $this->Mpendaftaran->tempat_vaksin();
        $this->load->view('template', $data);
    }
    function rawat_jalandokter($current = 0, $from = 0)
    {
        // 	$minimum_price = $this->input->post('tgl1');
        // $maximum_price = $this->input->post('tgl2');
        // $brand = $this->input->post('kode_poli');
        // $brand = $this->input->post('kode_doker');
        if ($this->session->userdata("status_pasien") == "")
            $this->session->set_flashdata("status_pasien", "BARU");
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pasien Rawat Jalan &nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vlistrawatjalandokter";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "dokter";
        $data["current"] = $current;
        $data["title_header"] = "Pasien Rawat Jalan ";
        $data["p"] = $this->Mpendaftaran->getpoli();
        $data["splg"] = $this->Mpendaftaran->getstatus_pulang();
        $data["kplg"] = $this->Mpendaftaran->getkeadaan_pulang();
        $data["total_rows"] = $this->Mpendaftaran->getpasien_rawatjalan();
        $data["jlayan"] = $this->Mpendaftaran->gettotalpasien("LAYAN");
        $data["jbatal"] = $this->Mpendaftaran->gettotalpasien("BATAL");
        $data["breadcrumb"] = "<li class='active'><strong>Pasien Rawat Jalan</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'pendaftaran/rawat_jalan/' . $current;
        $config['total_rows'] = $data["total_rows"];
        $config['per_page'] = 10;
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
        $this->pagination->initialize($config);
        $data["q3"] = $this->Mpendaftaran->getpasien_ralan($config['per_page'], $from);
        $this->load->view('template', $data);
    }
    function search_ralan()
    {
        $this->session->set_userdata('poli_kode', $this->input->post("poli_kode"));
        $this->session->set_userdata('poliklinik', $this->input->post("poliklinik"));
        $this->session->set_userdata('kode_dokter', $this->input->post("kode_dokter"));
        $this->session->set_userdata('dokter', $this->input->post("dokter"));
        $this->session->set_userdata('tgl1', $this->input->post("tgl1"));
        $this->session->set_userdata('tgl2', $this->input->post("tgl2"));
        $this->session->set_userdata('status_vaksin', $this->input->post("status_vaksin"));
        $this->session->set_userdata('tempat_vaksin', $this->input->post("tempat_vaksin"));
    }
    function search_inap()
    {
        $this->session->set_userdata('kode_kelas', $this->input->post("kode_kelas"));
        $this->session->set_userdata('kelas', $this->input->post("kelas"));
        $this->session->set_userdata('kode_ruangan', $this->input->post("kode_ruangan"));
        $this->session->set_userdata('ruangan', $this->input->post("ruangan"));
        $this->session->set_userdata('tgl1', $this->input->post("tgl1"));
        $this->session->set_userdata('tgl2', $this->input->post("tgl2"));
    }
    function reset()
    {
        $this->session->unset_userdata('no_pasien');
        $this->session->unset_userdata('poli_kode');
        $this->session->unset_userdata('poliklinik');
        $this->session->unset_userdata('kode_dokter');
        $this->session->unset_userdata('dokter');
        $this->session->set_flashdata('status_vaksin', "ALL");
        $this->session->unset_userdata('tgl1');
        $this->session->unset_userdata('tgl2');
        redirect("pendaftaran");
    }
    function reset_ralan()
    {
        $this->session->unset_userdata('no_pasien');
        $this->session->unset_userdata('poli_kode');
        $this->session->unset_userdata('poliklinik');
        $this->session->unset_userdata('kode_dokter');
        $this->session->unset_userdata('dokter');
        $this->session->set_flashdata('status_vaksin', "ALL");
        $this->session->unset_userdata('tgl1');
        $this->session->unset_userdata('tgl2');
        redirect("pendaftaran/rawat_jalan");
    }
    function reset_inap()
    {
        $this->session->unset_userdata('kode_kelas');
        $this->session->unset_userdata('kelas');
        $this->session->unset_userdata('kode_ruangan');
        $this->session->unset_userdata('ruangan');
        $this->session->unset_userdata('tgl1');
        $this->session->unset_userdata('tgl2');
        $this->session->unset_userdata('no_pasien');
        redirect("pendaftaran/rawat_inap");
    }
    function reset_inapdokter()
    {
        $this->session->unset_userdata('kode_kelas');
        $this->session->unset_userdata('kelas');
        $this->session->unset_userdata('kode_ruangan');
        $this->session->unset_userdata('ruangan');
        $this->session->unset_userdata('tgl1');
        $this->session->unset_userdata('tgl2');
        $this->session->unset_userdata('no_pasien');
        redirect("pendaftaran/rawat_inapdokter");
    }

    function rawat_inap($current = 0, $from = 0)
    {
        // $this->load->library("encrypt");
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pasien Rawat Inap &nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vlistrawatinap";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "inap";
        $data["current"] = $current;
        $data["title_header"] = "Pasien Rawat Inap ";
        $data["breadcrumb"] = "<li class='active'><strong>Pasien Rawat Inap</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'pendaftaran/rawat_inap/' . $current;
        $config['total_rows'] = $this->Mpendaftaran->getpasien_rawatinap();
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
        $data["q3"] = $this->Mpendaftaran->getpasien_inap($config['per_page'], $from);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $data["dok"] = $this->Mpendaftaran->getdokter();
        $data["artikel"] = $this->Mpendaftaran->getartikel();
        $data["tm"] = $this->Mpendaftaran->gettindakan_medis();
        $data["dok"] = $this->Mpendaftaran->getdokter();
        $data["dp"] = $this->Mpendaftaran->getdokterperawat();
        $this->load->view('template', $data);
    }
    function rawat_inapdokter($current = 0, $from = 0)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pasien Rawat Inap &nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vlistrawatinapdokter";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "dokter";
        $data["current"] = $current;
        $data["title_header"] = "Pasien Rawat Inap ";
        $data["breadcrumb"] = "<li class='active'><strong>Pasien Rawat Inap</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'pendaftaran/rawat_inapdokter/' . $current;
        $config['total_rows'] = $this->Mpendaftaran->getpasien_rawatinap();
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
        $data["q3"] = $this->Mpendaftaran->getpasien_inap($config['per_page'], $from);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $this->load->view('template', $data);
    }

    function history($current = 0, $from = 0)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "History Pendaftaran &nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vhistory";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "ralan";
        $data["current"] = $current;
        $data["title_header"] = "History Pendaftaran ";
        $data["breadcrumb"] = "<li class='active'><strong>History Pendaftaran</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'pendaftaran/rawat_inap/' . $current;
        $config['total_rows'] = $this->Mpendaftaran->gethistorypasien();
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
        $this->pagination->initialize($config);
        $data["q3"] = $this->Mpendaftaran->gethistory($config['per_page'], $from);
        $this->load->view('template', $data);
    }
    function batal($no_pasien, $no_reg, $asal)
    {
        $message = $this->Mpendaftaran->batal($no_pasien, $no_reg, $asal);
        $this->session->set_flashdata("message", $message);
        // redirect("pendaftaran/rawat_jalan");
    }
    function getbarcode($no_pasien, $no_reg)
    {
        $q = $this->Mpendaftaran->getrawat_jalan($no_reg);
        echo json_encode($q);
    }
    function cetakbarcode($no_pasien, $no_reg, $status = "")
    {
        $data["no_pasien"] = $no_pasien;
        $data["no_reg"] = $no_reg;
        $data["status"] = $status;
        $this->Mpendaftaran->updatetanggal($no_pasien, $no_reg, "tgl_cetakbarcode");
        $data["q"] = $this->Mpendaftaran->getrawat_jalan($no_reg);
        $this->load->view('pendaftaran/vcetakbarcode', $data);
    }
    function cetakbarcode_inap($no_pasien, $no_reg, $status = "")
    {
        $data["no_pasien"] = $no_pasien;
        $data["no_reg"] = $no_reg;
        $data["status"] = $status;
        $data["q"] = $this->Mpendaftaran->getinap_edit($no_pasien, $no_reg);
        $this->load->view('pendaftaran/vcetakbarcode_inap', $data);
    }
    function updatetanggal($no_pasien, $no_reg)
    {
        $message = $this->Mpendaftaran->updatetanggal($no_pasien, $no_reg, "tgl_scanbarcode");
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_jalan");
    }
    function ambildatadokter()
    {
        $kode = $this->input->post("kode");
        $q = $this->Mpendaftaran->getjadwaldokter($kode);
        foreach ($q->result() as $val) {
            $option = "<option value='" . $val->id_dokter . "'>" . $val->nama_dokter . "</option>";
            echo $option;
        }
    }
    function konsul($id, $reg_sebelumnya, $reg_baru = "")
    {
        $data["id"]                    = $id;
        $data["reg_sebelumnya"]        = $reg_sebelumnya;
        $data["reg_baru"]            = $reg_baru;
        $data["vmenu"]                 = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                = "ralan";
        $data["title"]                = "Rawat Jalan || RS CIREMAI";
        $data["title_header"]         = "Konsul";
        $data["content"]             = "pendaftaran/vkonsul";
        $data["breadcrumb"]           = "<li class='active'><strong>Konsul</strong></li>";
        $data["row"]                = $this->Mpendaftaran->ceknoreg($reg_baru);
        $data["pasien"]                = $this->Mpendaftaran->getpasien_detail($id);
        $data["no_reg"]                  = $this->Mpendaftaran->getnoreg();
        $data["q"]                    = $this->Mpendaftaran->getnoreg_sebelumnya($reg_sebelumnya);
        $data["d"]                      = $this->Mpendaftaran->getdokter();
        $data["k"]                  = $this->Mlab->getkasir($reg_baru);
        $data["k1"]                  = $this->Mradiologi->getkasir($reg_baru);
        $data["k2"]                  = $this->Mpendaftaran->getkasir($reg_baru);
        $data["q1"]                    = $this->Mkasir->getkasir_detail($reg_baru);
        $data["t"]                   = $this->Mlab->gettarif_lab();
        $data["t1"]                    = $this->Mradiologi->gettarif_radiologi();
        $data["t2"]                    = $this->Mpendaftaran->gettarif_fisioterapi();
        $data["dokter"]             = $this->Mlab->getdokter_array();
        $data["dok_all"]             = $this->Mlab->getdokter_array("all");
        $data["analys"]             = $this->Mlab->getanalys_array();
        $data["dp"]                    = $this->Mlab->getdokterpengirim_array();
        $this->load->view('template', $data);
    }
    function simpankonsul($action)
    {
        $no_reg     = $this->input->post("no_reg");
        $no_pasien     = $this->input->post("no_pasien");
        $sebelumnya = $this->input->post("no_reg_sebelumnya");
        $q = $this->Mpendaftaran->ceknoreg($no_reg);
        switch ($action) {
            case 'simpan':
                if ($no_reg) {
                    $message = $this->Mpendaftaran->simpankonsul();
                    $this->session->set_flashdata("message", $message);
                    redirect("pendaftaran/konsul/" . $no_pasien . "/" . $sebelumnya . "/" . $no_reg);
                } else {
                    $message = "danger-Noreg sudah ada sebelumnya";
                    $this->session->set_flashdata("message", $message);
                    redirect("pendaftaran/konsul/" . $no_pasien . "/" . $sebelumnya);
                }
                break;
            case 'edit':

                break;
        }
    }
    function tambahtindakan()
    {
        $no_reg     = $this->input->post("no_reg");
        $no_pasien     = $this->input->post("no_pasien");
        $sebelumnya = $this->input->post("reg_sebelumnya");
        $this->Mlab->addtindakankonsul();
        $this->session->set_flashdata("message", "success-Tarif berhasil ditambahkan");
        redirect("pendaftaran/konsul/" . $no_pasien . "/" . $sebelumnya . "/" . $no_reg);
    }
    function hapustindakanlab()
    {
        $this->Mlab->hapusralan();
    }
    function batalkonsul($no_reg)
    {
        $message = $this->Mpendaftaran->batalkonsul($no_reg);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_jalan");
    }
    function tindakan($no_pasien, $no_reg)
    {
        $data["vmenu"]             = $this->session->userdata("controller") . "/vmenu";
        $data['menu']            = "ralan";
        $data["no_pasien"]         = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]            = "Tindakan Rawat Jalan || RS CIREMAI";
        $data["title_header"]     = "Tindakan Rawat Jalan";
        $data["content"]         = "pendaftaran/vtindakan";
        $data["breadcrumb"]       = "<li class='active'><strong>Tindakan Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mkasir->getralan_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mkasir->getkasir($no_reg);
        $data["k1"]             = $this->Mkasir->getkasir_radiologi($no_reg);
        $data["k2"]             = $this->Mkasir->getkasir_radiologi2($no_reg);
        $data["pa1"]            = $this->Mkasir->getkasir_pa($no_reg);
        $data["p1"]             = $this->Mkasir->getkasir_penunjang_ralan($no_reg);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]              = $this->Mkasir->gettindakan($no_reg);
        $data["a"]              = $this->Mkasir->getambulance();
        $data["l1"]             = $this->Mkasir->getkasirralan_lab($no_reg);
        $data["l2"]             = $this->Mkasir->getkasirralan_lab2($no_reg);
        $data["a1"]             = $this->Mkasir->getkasir_ambulance_ralan($no_reg);
        $data["t1"]              = $this->Mkasir->gettindakan_radiologi();
        $data["p"]                 = $this->Mkasir->getpenunjang_medis();
        $this->load->view('template', $data);
    }
    function formsep($no_pasien, $no_reg, $nobpjs)
    {
        $data["vmenu"]             = $this->session->userdata("controller") . "/vmenu";
        $data['menu']            = "ralan";
        $data["no_pasien"]         = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["nobpjs"]         = $nobpjs;
        $data["title"]            = "SEP || RS CIREMAI";
        $data["title_header"]     = "SEP";
        $data["content"]         = "pendaftaran/vsep_ralan";
        $data["breadcrumb"]       = "<li class='active'><strong>SEP</strong></li>";
        $data["row"]            = $this->Mpendaftaran->getralan_detail($no_pasien, $no_reg);
        $data["rujukan"]        = $this->rujukan($nobpjs)->rujukan;
        $this->load->view('template', $data);
    }
    function diag_awal($kode)
    {
        $result = json_decode($this->api_vclaim("referensi_diagnosa_" . $kode));
        echo json_encode($result->response);
    }
    function poli($kode)
    {
        $result = json_decode($this->api_vclaim("referensi_poli_" . $kode));
        echo json_encode($result->response);
    }
    function rujukan($nobpjs)
    {
        $result = json_decode($this->api_vclaim("Rujukan_Peserta_" . $nobpjs));
        return $result->response;
    }
    function vclaim($nokartu)
    {
        // $nokartu = $this->input->post("nokartu");
        $tglsep = date("Y-m-d", strtotime($this->input->post("tglsep")));
        $ppkpelayanan = $this->input->post("ppkpelayanan");
        $data = "20337";
        $secretKey = "4tW3926623";
        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);
        $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/Peserta/" . $nokartu;
        $curl = curl_init();
        $header = array(
            "X-cons-id" => $data,
            "X-signature" => $encodedSignature,
            "X-timestamp" => $tStamp
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "X-cons-id: " . $data . " ",
                "X-signature: " . $encodedSignature . " ",
                "X-timestamp: " . $tStamp,
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        var_dump($response);
    }
    function api_vclaim($url)
    {
        $data = "20337";
        $secretKey = "4tW3926623";
        date_default_timezone_set('UTC');
        $url = str_replace("_", "/", $url);
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);
        $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/" . $url;
        $curl = curl_init();
        $header = array(
            "X-cons-id" => $data,
            "X-signature" => $encodedSignature,
            "X-timestamp" => $tStamp
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "X-cons-id: " . $data . " ",
                "X-signature: " . $encodedSignature . " ",
                "X-timestamp: " . $tStamp,
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    function getsep()
    {
        $no_rm = $this->input->post("no_rm");
        $no_reg = $this->input->post("no_reg");
        $nokartu = $this->input->post("no_bpjs");
        $provperujuk = $this->input->post("provperujuk");
        $provperujuk = $this->input->post("provperujuk");

        $jenis = $this->input->post("jenis");
        $cob = $this->input->post("cob");
        $katarak = $this->input->post("katarak");
        $lakalantas = $this->input->post("lakalantas");
        $penjamin = $this->input->post("penjamin");
        $tglkejadian = date("Y-m-d", strtotime($this->input->post("tglkejadian")));
        $keterangan = $this->input->post("keterangan");
        $suplesi = $this->input->post("suplesi");
        $nosepsuplesi = $this->input->post("nosepsuplesi");
        $kodepropinsi = $this->input->post("kodepropinsi");
        $kodekabupaten = $this->input->post("kodekabupaten");
        $kodekecamatan = $this->input->post("kodekecamatan");
        $nosurat = $this->input->post("nosurat");
        $notelpon = $this->input->post("telpon");
        $dpjp = $this->input->post("dpjp");
        $q = $this->Mpendaftaran->getrawat_jalan($no_reg)->row();
        $data["t_sep"] = array(
            "noKartu" => $nokartu,
            "tglSep" => date("Y-m-d"),
            "ppkPelayanan" => $provperujuk /*(provPetunjuk dari rujukan)*/,
            "jnsPelayanan" => "2",/*1. rawat inap, 2. rawat jalan*/
            "klsRawat" => $hakkelas /*(hak_kelas dari rujukan)*/,
            "noMR" => $no_rm,
            "rujukan" => array(
                "asalRujukan" => $asalrujukan,
                "tglRujukan" => $tglkunjungan /*(tglKunjungan dari rujukan)*/,
                "noRujukan" => $norujukan /*(noKunjungan dari rujukan)*/,
                "ppkRujukan" => $provperujuk /*(provperujuk-code dari rujukan)*/,
            ),
            "catatan" => "-",
            "diagAwal" => $diagnosa /*(diagnosa-code dari rujukan)*/,
            "poli" => array(
                "tujuan" =>  $polirujukan/*(polirujukan dari rujukan)*/,
                "eksekutif" => $jenis
            ),
            "cob" => array(
                "cob" => $cob
            ),
            "katarak" => array(
                "katarak" => $katarak
            ),
            "jaminan" => array(
                "lakaLantas" => $lakaLantas,
                "penjamin" => array(
                    "penjamin" => $penjamin,
                    "tglKejadian" => $tglkejadian,
                    "keterangan" => $keterangan,
                    "suplesi" => array(
                        "suplesi" => $suplesi,
                        "noSepSuplesi" => $nosepsuplesi,
                        "lokasiLaka" => array(
                            "kdPropinsi" => $kodepropinsi,
                            "kdKabupaten" => $kodekabupaten,
                            "kdKecamatan" => $kodekecamatan
                        )
                    )
                )
            ),
            "skdp" => array(
                "noSurat" => $nosurat,/*$nosurat /* (ambil dari tabel pasien_inap)*/
                "kodeDPJP" => $dpjp/*$dpjp /* (ambil dari tabel pasien_inap)*/,
            ),
            "noTelp" => $notelpon /* (ambil dari tabel pasien)*/,
            "user" => "Coba Ws"
        );
    }
    function viewpembayaran_inap($no_pasien, $no_reg)
    {
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data['menu'] = "kasir";
        $data["no_pasien"] = $no_pasien;
        $data["no_reg"] = $no_reg;
        $data["title"]        = "Pembayaran Rawat Inap || RS CIREMAI";
        $data["title_header"] = "Pembayaran Rawat Inap";
        $data["content"] = "pendaftaran/vviewpembayaran_inap";
        $data["breadcrumb"]   = "<li class='active'><strong>Pembayaran Rawat Inap</strong></li>";
        $data["row"]              = $this->Mkasir->getinap_detail($no_pasien, $no_reg);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t1"] = $this->Mkasir->getkasir_inap($no_reg);
        $data["t2"] = $this->Mkasir->getkasir_igd($no_reg);
        $data["a1"] = $this->Mkasir->getkasir_ambulance($no_reg);
        $data["p1"] = $this->Mkasir->getkasir_penunjang($no_reg);
        $data["o1"] = $this->Mkasir->getkasir_operasi($no_reg);
        $data["o2"] = $this->Mkasir->getkasir_opr($no_reg);
        $data["l1"] = $this->Mkasir->getkasir_lab($no_reg);
        $data["r1"] = $this->Mkasir->getkasir_inap_radiologi($no_reg);
        $data["pa1"] = $this->Mkasir->getkasir_inap_pa($no_reg);
        $data["t"]  = $this->Mkasir->gettindakan_inap();
        $data["a"]  = $this->Mkasir->getambulance();
        $data["o"]  = $this->Mkasir->getoperasi();
        $data["dokter"]  = $this->Mkasir->getdokter_array();
        $data["kamar"]  = $this->Mkasir->getkamar_array();
        $data["p"]  = $this->Mkasir->getpenunjang_medis();
        $this->load->view('template', $data);
    }
    function addtindakan_inap($jenis)
    {
        $this->Mkasir->addtindakan_inap($jenis);
        $this->session->set_flashdata("message", "success-Tarif berhasil ditambahkan");
    }

    function hapusinap()
    {
        $this->Mkasir->hapusinap();
        $this->session->set_flashdata("message", "danger-Tarif berhasil dihapus");
    }
    function viewpembayaran_ralan($no_pasien, $no_reg)
    {
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data['menu'] = "kasir";
        $data["no_pasien"] = $no_pasien;
        $data["no_reg"] = $no_reg;
        $data["title"]        = "Pembayaran Rawat Jalan || RS CIREMAI";
        $data["title_header"] = "Pembayaran Rawat Jalan";
        $data["content"] = "pendaftaran/vviewpembayaran_ralan";
        $data["breadcrumb"]   = "<li class='active'><strong>Pembayaran Rawat Jalan</strong></li>";
        $data["row"]              = $this->Mkasir->getralan_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mkasir->getkasir($no_reg);
        $data["k1"]              = $this->Mkasir->getkasir_radiologi($no_reg);
        $data["k2"]              = $this->Mkasir->getkasir_radiologi2($no_reg);
        $data["pa1"]              = $this->Mkasir->getkasir_pa($no_reg);
        $data["p1"]              = $this->Mkasir->getkasir_penunjang_ralan($no_reg);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]  = $this->Mkasir->gettindakan($no_reg);
        $data["a"]  = $this->Mkasir->getambulance();
        $data["l1"] = $this->Mkasir->getkasirralan_lab($no_reg);
        $data["l2"] = $this->Mkasir->getkasirralan_lab2($no_reg);
        $data["a1"] = $this->Mkasir->getkasir_ambulance_ralan($no_reg);
        $data["t1"]  = $this->Mkasir->gettindakan_radiologi();
        $data["p"]  = $this->Mkasir->getpenunjang_medis();
        $this->load->view('template', $data);
    }
    function ekspertisiradiologi_inap($no_pasien, $no_reg, $id_tindakan = "", $tgl = "", $pemeriksaan="")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "inap";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["id_tindakan"]    = $id_tindakan;
        $data["pemeriksaan"]    = $pemeriksaan;
        $data["title"]          = "Ekspertisi Radiologi || RS CIREMAI";
        $data["title_header"]   = "Ekspertisi Radiologi";
        $data["tgl"]            = $tgl;
        $data["content"]        = "pendaftaran/vformekspertisiradiologi_inap";
        $data["breadcrumb"]     = "<li class='active'><strong>Ekspertisi Radiologi</strong></li>";
        $data["row"]            = $this->Mradiologi->getinap_detail($no_pasien, $no_reg);
        $data["q"]              = $this->Mradiologi->getekspertisiinap_detail($no_pasien, $no_reg, $id_tindakan, $tgl,$pemeriksaan);
        $data["d"]              = $this->Mradiologi->getdokter_radiologi();
        $data["d1"]             = $this->Mradiologi->getdokter();
        $data["r"]              = $this->Mradiologi->getradiografer();
        $data["k"]              = $this->Mradiologi->getkasir_inap($no_reg, "");
        $this->load->view('template', $data);
    }
    function ekspertisilab_inap($no_pasien, $no_reg, $tanggal = "", $pemeriksaan = "")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "inap";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["tgl"]            = $tanggal;
        $data["pemeriksaan"]    = $pemeriksaan;
        $data["title"]          = "Ekspertisi Lab || RS CIREMAI";
        $data["title_header"]   = "Ekspertisi Lab";
        $data["content"]        = "pendaftaran/vformekspertisilab_inap";
        $data["breadcrumb"]     = "<li class='active'><strong>Ekspertisi Lab</strong></li>";
        $data["row"]            = $this->Mlab->getinap_detail1($no_pasien, $no_reg, $tanggal, $pemeriksaan);
        $data["q"]              = $this->Mlab->getekspertisiinap_detail($no_reg);
        $data["d"]              = $this->Mlab->getdokter_lab();
        $data["r"]              = $this->Mlab->getanalys();
        $data["k"]              = $this->Mlab->getlabinap_normal($no_reg, $tanggal, $pemeriksaan);
        $data["hasil"]          = $this->Mlab->getekspertisilabinap_detail_array($no_reg, $tanggal, $pemeriksaan);
        $data["x"]              = $this->Mlab->getekspertisilabinap_detail($no_reg, $tanggal, $pemeriksaan);
        $data["ks"]             = $this->Mlab->getkasir_inap_ekspertisi($no_reg);
        $this->load->view('template', $data);
    }
    function ekspertisipa_inap($no_pasien, $no_reg, $id_tindakan = "", $tgl = "", $pemeriksaan = "")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "inap";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["kode_tindakan"]  = $id_tindakan;
        $data["pemeriksaan"]    = $pemeriksaan;
        $data["title"]          = "Ekspertisi PA || RS CIREMAI";
        $data["title_header"]   = "Ekspertisi PA";
        $data["tgl"]            = $tgl;
        $data["content"]        = "pendaftaran/vformekspertisipa_inap";
        $data["breadcrumb"]     = "<li class='active'><strong>Ekspertisi PA</strong></li>";
        $data["row"]            = $this->Mpa->getinap_detail($no_pasien, $no_reg);
        $data["q"]              = $this->Mpa->getekspertisiinap_detail($no_pasien, $no_reg, $id_tindakan, $tgl, $pemeriksaan);
        $data["d"]              = $this->Mpa->getdokter_pa();
        $data["d1"]             = $this->Mpa->getdokter();
        $data["r"]              = $this->Mpa->getpetugaspa();
        $data["k"]              = $this->Mpa->getkasir_inap($no_reg, "");
        $this->load->view('template', $data);
    }
    function ekspertisigizi_inap($no_pasien, $no_reg, $id_tindakan = "", $tgl = "", $pemeriksaan = "")
    {
        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "inap";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["kode_tindakan"]              = $id_tindakan;
        $data["pemeriksaan"]                = $pemeriksaan;
        $data["title"]                      = "Ekspertisi Gizi || RS CIREMAI";
        $data["title_header"]               = "Ekspertisi Gizi";
        $data["tgl"]                        = $tgl;
        $data["content"]                    = "pendaftaran/vformekspertisigizi_inap";
        $data["breadcrumb"]                 = "<li class			='active'><strong>Ekspertisi Gizi</strong></li>";
        $data["row"]                        = $this->Mgizi->getinap_detail($no_pasien, $no_reg);
        $data["q"]                          = $this->Mgizi->getekspertisiinap_detail($no_pasien, $no_reg, $id_tindakan, $tgl, $pemeriksaan);
        $data["d"]                          = $this->Mgizi->getdokter_gizi();
        $data["d1"]                         = $this->Mgizi->getdokter();
        $data["r"]                          = $this->Mgizi->getpetugasgizi();
        $data["k"]                          = $this->Mgizi->getkasir_inap($no_reg, "");
        $data["hasil_pemeriksaan"]          = $this->Mgizi->getekspertisigiziinap_detail_array($no_reg, $tgl, $pemeriksaan);
        $data["as"]                         = $this->Mgizi->gethasuhan($no_reg, $tgl, $pemeriksaan);
        $data["a"]                          = $this->Mgizi->getasuhan($no_reg, $tgl, $pemeriksaan);
        $this->load->view('template', $data);
    }
    function detaillab_inap($no_pasien, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "dokter";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]          = "Detail Lab Rawat Inap || RS CIREMAI";
        $data["title_header"]   = "Detail Lab Rawat Inap";
        $data["content"]        = "pendaftaran/vformlab_inap";
        $data["breadcrumb"]     = "<li class='active'><strong>Detail Lab Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mlab->getinap_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mlab->getkasir_inap($no_reg);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]              = $this->Mlab->gettarif_lab();
        $data["d"]              = $this->Mlab->getdokter_lab();
        $data["dk"]             = $this->Mlab->getdokterall();
        $data["r"]              = $this->Mlab->getanalys();
        $data["dokter"]         = $this->Mlab->getdokter_array();
        $data["analys"]         = $this->Mlab->getanalys_array();
        $data["dokter_pengirim"] = $this->Mlab->getdokterpengirim_array();
        $this->load->view('template', $data);
    }
    function detailradiologi_inap($no_pasien, $no_reg, $tanggal = "")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "radiologi";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["tanggal"]        = $tanggal;
        $data["title"]          = "Detail Radiologi Rawat Inap || RS CIREMAI";
        $data["title_header"]   = "Detail Radiologi Rawat Inap";
        $data["content"]        = "pendaftaran/vformradiologi_inap";
        $data["breadcrumb"]     = "<li class='active'><strong>Detail Radiologi Rawat Inap</strong></li>";
        $data["row"]            = $this->Mradiologi->getinap_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mradiologi->getkasir_inap($no_reg, $tanggal);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]              = $this->Mradiologi->gettarif_radiologi();
        $data["d"]              = $this->Mradiologi->getdokter_radiologi();
        $data["d1"]             = $this->Mradiologi->getdokter();
        $data["r"]              = $this->Mradiologi->getradiografer();
        $data["dokter"]         = $this->Mradiologi->getdokter_array();
        $data["dokter_pengirim"]         = $this->Mradiologi->getdokterpengirim_array();
        $data["radiografer"]    = $this->Mradiologi->getradiografer_array();
        $this->load->view('template', $data);
    }
    function detailpa_inap($no_pasien, $no_reg, $tanggal = "")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "pa";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["tanggal"]        = $tanggal;
        $data["title"]          = "Detail Panatologi Anatomi Rawat Inap || RS CIREMAI";
        $data["title_header"]   = "Detail Panatologi Anatomi Rawat Inap";
        $data["content"]        = "pendaftaran/vformpa_inap";
        $data["breadcrumb"]     = "<li class='active'><strong>Detail Panatologi Anatomi Rawat Inap</strong></li>";
        $data["row"]            = $this->Mpa->getinap_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mpa->getkasir_inap($no_reg, $tanggal);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]              = $this->Mpa->gettarif_pa();
        $data["d"]              = $this->Mpa->getdokter_pa();
        $data["d1"]             = $this->Mpa->getdokter();
        $data["r"]              = $this->Mpa->getpetugaspa();
        $data["dokter"]         = $this->Mpa->getdokter_array();
        $data["dokter_pengirim"]         = $this->Mpa->getdokterpengirim_array();
        $data["petugas_pa"]    = $this->Mpa->getpetugas_array();
        $this->load->view('template', $data);
    }
    function detailgizi_inap($no_pasien, $no_reg, $tanggal = "")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "gizi";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["tanggal"]        = $tanggal;
        $data["title"]          = "Detail Gizi Rawat Inap || RS CIREMAI";
        $data["title_header"]   = "Detail Gizi Rawat Inap";
        $data["content"]        = "pendaftaran/vformgizi_inap";
        $data["breadcrumb"]     = "<li class='active'><strong>Detail Gizi Rawat Inap</strong></li>";
        $data["row"]            = $this->Mgizi->getinap_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mgizi->getkasir_inap($no_reg, $tanggal);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]              = $this->Mgizi->gettarif_gizi();
        $data["d"]              = $this->Mgizi->getdokter_gizi();
        $data["d1"]             = $this->Mgizi->getdokter();
        $data["r"]              = $this->Mgizi->getpetugasgizi();
        $data["dokter"]         = $this->Mgizi->getdokter_array();
        $data["dokter_pengirim"]         = $this->Mgizi->getdokterpengirim_array();
        $data["petugas_gizi"]    = $this->Mgizi->getpetugas_array();
        $this->load->view('template', $data);
    }
    function indeks($no_pasien, $no_reg)
    {
        $q                                     = $this->Mpendaftaran->cektglpulang($no_pasien, $no_reg);
        if ($q->tanggal_pulang == "0000-00-00 00:00:00" || $q->tanggal_pulang == NULL) {
            $data["content"]                 = "pendaftaran/vindeks_notfound";
        } else {
            $data["content"]                 = "pendaftaran/vindeks";
        }

        $data["vmenu"]                         = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                        = "ralan";
        $data["no_pasien"]                     = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                         = "Indeks Rawat Jalan || RS CIREMAI";
        $data["title_header"]                 = "Indeks Rawat Jalan";
        $data["breadcrumb"]                   = "<li class='active'><strong>Indeks Rawat Jalan</strong></li>";
        $data["row"]                         = $this->Mgrouper->getralan_detail($no_pasien, $no_reg);
        $data["g1"]                         = $this->Mgrouper->getgrouper(6, 0);
        $data["g2"]                         = $this->Mgrouper->getgrouper(6, 6);
        $data["g3"]                         = $this->Mgrouper->getgrouper(6, 12);
        $data["hasil"]                         = $this->Mgrouper->getgrouper_ralan($no_reg);
        $data["i10"]                         = $this->Mgrouper->getindeksicd10_ralan($no_reg);
        $data["i9"]                         = $this->Mgrouper->getindeksicd9_ralan($no_reg);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $this->load->view('template', $data);
    }
    function indeks_inap($no_pasien, $no_reg)
    {
        $data["vmenu"]                         = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                        = "inap";
        $data["no_pasien"]                     = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                         = "Indeks Rawat Inap || RS CIREMAI";
        $data["title_header"]                 = "Indeks Rawat Inap";
        $data["content"]                     = "pendaftaran/vindeks_inap";
        $data["breadcrumb"]                   = "<li class='active'><strong>Indeks Rawat Inap</strong></li>";
        $data["row"]                         = $this->Mgrouper->getinap_detail($no_pasien, $no_reg);
        $data["g1"]                         = $this->Mgrouper->getgrouper(6, 0);
        $data["g2"]                         = $this->Mgrouper->getgrouper(6, 6);
        $data["g3"]                         = $this->Mgrouper->getgrouper(6, 12);
        $data["hasil"]                         = $this->Mgrouper->getgrouper_inap($no_reg);
        $data["i10"]                         = $this->Mgrouper->getindeksicd10_inap($no_reg);
        $data["i9"]                         = $this->Mgrouper->getindeksicd9_inap($no_reg);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $this->load->view('template', $data);
    }
    function geticd10()
    {
        echo json_encode($this->Mgrouper->geticd10());
    }
    function geticd10detail()
    {
        echo json_encode($this->Mgrouper->geticd10detail());
    }
    function simpan_indeksicd10()
    {
        $this->Mgrouper->simpan_indeksicd10();
    }
    function edit_indeksicd10()
    {
        $this->Mgrouper->edit_indeksicd10();
    }
    function hapus_indeksicd10()
    {
        $this->Mgrouper->hapus_indeksicd10();
    }
    function simpan_indeksicd10_inap()
    {
        $this->Mgrouper->simpan_indeksicd10_inap();
    }
    function edit_indeksicd10_inap()
    {
        $this->Mgrouper->edit_indeksicd10_inap();
    }
    function hapus_indeksicd10_inap()
    {
        $this->Mgrouper->hapus_indeksicd10_inap();
    }
    function geticd9()
    {
        echo json_encode($this->Mgrouper->geticd9());
    }
    function geticd9detail()
    {
        echo json_encode($this->Mgrouper->geticd9detail());
    }
    function simpan_indeksicd9()
    {
        $this->Mgrouper->simpan_indeksicd9();
    }
    function edit_indeksicd9()
    {
        $this->Mgrouper->edit_indeksicd9();
    }
    function hapus_indeksicd9()
    {
        $this->Mgrouper->hapus_indeksicd9();
    }
    function simpan_indeksicd9_inap()
    {
        $this->Mgrouper->simpan_indeksicd9_inap();
    }
    function edit_indeksicd9_inap()
    {
        $this->Mgrouper->edit_indeksicd9_inap();
    }
    function hapus_indeksicd9_inap()
    {
        $this->Mgrouper->hapus_indeksicd9_inap();
    }
    function terima($no_rm, $no_reg)
    {
        $message = $this->Mpendaftaran->terima($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        $this->session->set_userdata("no_reg", $no_reg);
        redirect("pendaftaran/rawat_jalan");
    }
    function terima_pasien($no_rm, $no_reg)
    {
        $message = $this->Mpendaftaran->terima_pasien($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        $this->session->set_userdata("no_reg", $no_reg);
        redirect("perawat/assesmenralan/nonassesmen/" . $no_rm . "/" . $no_reg);
        // redirect("pendaftaran/rawat_jalan");
    }
    function pulang($no_rm, $no_reg, $keadaan_pulang, $status_pulang)
    {
        $message = $this->Mpendaftaran->pulang($no_rm, $no_reg, $keadaan_pulang, $status_pulang);
        $this->session->set_flashdata("message", $message);
        $this->session->set_userdata("no_reg", $no_reg);
        redirect("pendaftaran/rawat_jalan");
    }
    function gudang($no_rm, $no_reg)
    {
        $message = $this->Mpendaftaran->gudang($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        $this->session->set_userdata("no_reg", $no_reg);
        redirect("pendaftaran/rawat_jalan");
    }
    function layani($no_rm, $no_reg)
    {
        $message = $this->Mpendaftaran->layani($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        // $this->session->set_userdata("no_pasien",$no_reg);
        redirect("dokter/igdralan/noassesmen/" . $no_rm . "/" . $no_reg);
        // redirect("pendaftaran/rawat_jalan");
    }
    function layani_inap($no_rm, $no_reg)
    {
        $message = $this->Mpendaftaran->layani_inap($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        // $this->session->set_userdata("no_pasien",$no_reg);
        redirect("pendaftaran/rawat_inap");
    }
    function send_inap($no_rm, $no_reg, $back)
    {
        $message = $this->Mpendaftaran->send_inap($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        // $this->session->set_userdata("no_pasien",$no_reg);
        redirect("perawat/listpindahkamar/" . $no_rm . "/" . $no_reg . "/" . $back);
    }
    function terima_ruangan($no_rm, $no_reg)
    {
        $message = $this->Mpendaftaran->terima_ruangan($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        // $this->session->set_userdata("no_pasien",$no_reg);
        redirect("pendaftaran/rawat_inap");
    }
    function rtpelayanan($no_rm, $no_reg)
    {
        $data["q"]          = $this->Mpendaftaran->rtpelayanan($no_rm, $no_reg);
        $this->load->view('pendaftaran/vrtpelayanan', $data);
    }
    function rtpelayanan_inap($no_rm, $no_reg)
    {
        $data["q"]          = $this->Mpendaftaran->rtpelayanan_inap($no_rm, $no_reg);
        $this->load->view('pendaftaran/vrtpelayanan_inap', $data);
    }
    function rtrm($no_rm, $no_reg)
    {
        $data["q"]          = $this->Mpendaftaran->rtpelayanan($no_rm, $no_reg);
        $this->load->view('pendaftaran/vrtrm', $data);
    }
    function rt_poliklinik($no_rm, $no_reg)
    {
        $data["q"]          = $this->Mpendaftaran->rtpelayanan($no_rm, $no_reg);
        $this->load->view('pendaftaran/vrt_poliklinik', $data);
    }
    function rttunggu($no_rm, $no_reg)
    {
        $data["q"]          = $this->Mpendaftaran->rtpelayanan($no_rm, $no_reg);
        $this->load->view('pendaftaran/vrttunggu', $data);
    }
    function ekspertisiradiologi_ralan($no_pasien, $no_reg, $id_tindakan = "")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "ralan";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["id_tindakan"]    = $id_tindakan;
        $data["title"]          = "Ekspertisi Radiologi Rawat Jalan || RS CIREMAI";
        $data["title_header"]   = "Ekspertisi Radiologi Rawat Jalan";
        $data["content"]        = "pendaftaran/vformekspertisiradiologi_ralan";
        $data["breadcrumb"]     = "<li class='active'><strong>Ekspertisi Radiologi Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mradiologi->getralan_detail($no_pasien, $no_reg);
        $data["q"]              = $this->Mradiologi->getekspertisi_detail($no_pasien, $no_reg, $id_tindakan);
        $data["d"]              = $this->Mradiologi->getdokter_radiologi();
        $data["d1"]             = $this->Mradiologi->getdokter();
        $data["r"]              = $this->Mradiologi->getradiografer();
        $data["k2"]             = $this->Mradiologi->getkasir_detail($no_reg, $id_tindakan);
        $data["k"]              = $this->Mradiologi->getkasir($no_reg);
        $this->load->view('template', $data);
    }
    function ekspertisilab_ralan($no_pasien, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "ralan";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]          = "Ekspertisi Lab Rawat Jalan || RS CIREMAI";
        $data["title_header"]   = "Ekspertisi Lab Rawat Jalan";
        $data["content"]        = "pendaftaran/vformekspertisilab_ralan";
        $data["breadcrumb"]     = "<li class='active'><strong>Ekspertisi Lab Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mlab->getralan_detail1($no_pasien, $no_reg);
        $data["q"]              = $this->Mlab->getekspertisi_detail($no_reg);
        $data["d"]              = $this->Mlab->getdokter_lab();
        $data["r"]              = $this->Mlab->getanalys();
        $data["k"]              = $this->Mlab->getlab_normal($no_reg);
        $data["hasil"]          = $this->Mlab->getekspertisilab_detail_array($no_reg);
        $data["x"]              = $this->Mlab->getekspertisilab_detail($no_reg);
        $this->load->view('template', $data);
    }
    function ekspertisipa_ralan($no_pasien, $no_reg, $kode_tindakan = "")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "ralan";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["kode_tindakan"]  = $kode_tindakan;
        $data["title"]          = "Ekspertisi PA Rawat Jalan || RS CIREMAI";
        $data["title_header"]   = "Ekspertisi PA Rawat Jalan";
        $data["content"]        = "pendaftaran/vformekspertisipa_ralan";
        $data["breadcrumb"]     = "<li class='active'><strong>Ekspertisi PA Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mpa->getralan_detail($no_pasien, $no_reg);
        $data["q"]              = $this->Mpa->getekspertisi_detail($no_pasien, $no_reg, $kode_tindakan);
        $data["d"]              = $this->Mpa->getdokter_pa();
        $data["d1"]             = $this->Mpa->getdokter();
        $data["r"]              = $this->Mpa->getpetugaspa();
        $data["k2"]             = $this->Mpa->getkasir_detail($no_reg, $kode_tindakan);
        $data["k"]              = $this->Mpa->getkasir($no_reg);
        $this->load->view('template', $data);
    }
    function ekspertisigizi_ralan($no_pasien, $no_reg, $kode_tindakan = "")
    {
        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "ralan";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["kode_tindakan"]              = $kode_tindakan;
        $data["title"]                      = "Ekspertisi Gizi || RS CIREMAI";
        $data["title_header"]               = "Ekspertisi Gizi";
        $data["content"]                    = "pendaftaran/vformekspertisigizi_ralan";
        $data["breadcrumb"]                 = "<li class			='active'><strong>Ekspertisi Gizi</strong></li>";
        $data["row"]                        = $this->Mgizi->getralan_detail($no_pasien, $no_reg);
        $data["q"]                          = $this->Mgizi->getekspertisi_detail($no_pasien, $no_reg, $kode_tindakan);
        $data["d"]                          = $this->Mgizi->getdokter_gizi();
        $data["d1"]                         = $this->Mgizi->getdokter();
        $data["r"]                          = $this->Mgizi->getpetugasgizi();
        $data["k2"]                         = $this->Mgizi->getkasir_detail($no_reg, $kode_tindakan);
        $data["k"]                          = $this->Mgizi->getkasir($no_reg);
        $data["hasil_pemeriksaan"]          = $this->Mgizi->getekspertisigizi_detail_array($no_reg, $kode_tindakan);
        $data["a"]                          = $this->Mgizi->getasuhan_ralan($no_reg, $kode_tindakan);
        $this->load->view('template', $data);
    }
    function apotek_ralan($no_pasien, $no_reg)
    {
        $data["vmenu"]                         = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                        = "ralan";
        $data["no_pasien"]                     = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                        = "Apotek Rawat Jalan || RS CIREMAI";
        $data["title_header"]                 = "Apotek Rawat Jalan";
        $data["content"]                     = "pendaftaran/vviewapotek_ralan";
        $data["breadcrumb"]                   = "<li class='active'><strong>Apotek Rawat Jalan</strong></li>";
        $data["row"]                          = $this->Mapotek->getralan_detail($no_pasien, $no_reg);
        $data["k"]                          = $this->Mapotek->getapotek($no_reg);
        $data["q"]                          = $this->Mapotek->getapotek_detail($no_reg);
        $data["t"]                          = $this->Mapotek->getobat();
        $this->load->view('template', $data);
    }
    function apotek_inap($no_pasien, $no_reg)
    {
        $data["vmenu"]                         = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                        = "ralan";
        $data["no_pasien"]                     = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                        = "Apotek Rawat Inap || RS CIREMAI";
        $data["title_header"]                 = "Apotek Rawat Inap";
        $data["content"]                     = "pendaftaran/vviewapotek_inap";
        $data["breadcrumb"]                   = "<li class='active'><strong>Apotek Rawat Inap</strong></li>";
        $data["row"]                          = $this->Mapotek->getinap_detail($no_pasien, $no_reg);
        $data["k"]                          = $this->Mapotek->getapotek_inap($no_reg);
        $data["q"]                          = $this->Mapotek->getapotek_detail($no_reg);
        $data["t"]                          = $this->Mapotek->getobat();
        $this->load->view('template', $data);
    }
    function hapusinos($no_pasien, $no_reg, $kode_inos)
    {
        $message = $this->Mpendaftaran->hapusinos($no_pasien, $no_reg, $kode_inos);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/inos/" . $no_pasien . "/" . $no_reg);
    }
    function formuploadpdf_ralan($no_pasien, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "ralan";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]          = "Upload PDF Rawat Jalan || RS CIREMAI";
        $data["title_header"]   = "Upload PDF Rawat Jalan";
        $data["content"]        = "pendaftaran/vformuploadpdf_ralan";
        $data["q"]              = $this->Mgrouper->getfilepdf_ralan($no_reg);
        $data["q1"]              = $this->Mgrouper->getfilepdf_noregsebelumnya_ralan($no_reg);
        $data["breadcrumb"]     = "<li class='active'><strong>Upload PDF Rawat Jalan</strong></li>";
        $data["j"]              = $this->Mlab->getjenisfile();
        $this->load->view('template', $data);
    }
    function uploadpdf_ralan()
    {
        for ($i = 0; $i <= 100; $i++) {
            $n = $i;

            $this->db->select("count(*) as total");
            $this->db->where("no_reg", $this->input->post("no_reg"));

            $q = $this->db->get("pdf_ralan")->row();
            if ($q->total == $n) {
                $a = $n;
            } else {
                $a = 1;
            }
        }
        $config['upload_path']          = './file_pdf/ralan/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['file_name']            = "Ralan-" . $this->input->post("no_reg") . "-" . $this->input->post("jenisfile") . "-" . $a;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('pdf_ralan')) {
            $message = "danger-Gagal diupload";
            $this->session->set_flashdata("message", $message);
            redirect("pendaftaran/formuploadpdf_ralan/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
        } else {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $message = $this->Mgrouper->uploadpdf_ralan($nama_file);
            $this->session->set_flashdata("message", $message);
            redirect("pendaftaran/formuploadpdf_ralan/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
        }
    }
    function formuploadpdf_inap($no_pasien, $no_reg, $asal = "assesmen")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "grouper";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["asal"]            = $asal;
        $data["title"]          = "Upload PDF Rawat Inap || RS CIREMAI";
        $data["title_header"]   = "Upload PDF Rawat Inap";
        $data["content"]        = "grouper/vformuploadpdf_inap";
        $data["q"]              = $this->Mgrouper->getfilepdf_inap($no_reg);
        $data["breadcrumb"]     = "<li class='active'><strong>Upload PDF Rawat Inap</strong></li>";
        $data["j"]              = $this->Mlab->getjenisfile();
        $this->load->view('template', $data);
    }
    function uploadpdf_inap()
    {
        for ($i = 0; $i <= 100; $i++) {
            $n = $i;

            $this->db->select("count(*) as total");
            $this->db->where("no_reg", $this->input->post("no_reg"));

            $q = $this->db->get("pdf_inap")->row();
            if ($q->total == $n) {
                $a = $n;
            } else {
                $a = 1;
            }
        }
        $config['upload_path']          = './file_pdf/inap/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['file_name']            = "Inap-" . $this->input->post("no_reg") . "-" . $this->input->post("jenisfile") . "-" . $a;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('pdf_inap')) {
            $message = "danger-Gagal diupload";
            $this->session->set_flashdata("message", $message);
            redirect("pendaftaran/formuploadpdf_inap/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
        } else {
            $data = array(
                'upload_data' => $this->upload->data('file_name'),
            );
            $nama_file =  $data['upload_data'];
            $message = $this->Mgrouper->uploadpdf_inap($nama_file);
            $this->session->set_flashdata("message", $message);
            redirect("pendaftaran/formuploadpdf_inap/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
        }
    }
    function cetak_mata($kode = "")
    {
        $data["kode"]               = $kode;
        $data["q"]                  = $this->Moka->getlaporan_mataoka($kode);
        $this->load->view('oka/vcetak_mata', $data);
    }
    function cetak_pterygium($kode = "")
    {
        $data["kode"]               = $kode;
        $data["q"]                  = $this->Moka->getlaporan_pterygiumoka($kode);
        $this->load->view('oka/vcetak_pterygium', $data);
    }
    function cetak_operasi($kode = "")
    {
        $data["q"] = $this->Moka->getcetakoka($kode);
        $this->load->view("oka/vcetak_oka", $data);
    }
    function laporan_tindakan($no_pasien = "", $no_reg = "", $poli = "")
    {
        $data["no_reg"]              = $no_reg;
        $data["no_pasien"]          = $no_pasien;
        $data["vmenu"]              = $this->session->userdata("controller") . "/vmenu";
        $data['menu']              = "user";
        $data["title"]            = "Rawat Jalan || RS CIREMAI";
        $data["title_header"]      = "Laporan Tindakan";
        $data["content"]           = "pendaftaran/vlaporan_tindakan";
        $data["breadcrumb"]         = "<li class='active'><strong>Laporan Tindakan</strong></li>";
        $data["q"]                   = $this->Mpendaftaran->getlaporan_tindakan($no_pasien, $no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter_op($poli);
        $data["diagnosa1"]           = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]           = $this->Mpendaftaran->getanastesi();
        $data["asisten"]           = $this->Mpendaftaran->getasisten();
        $data["tindakan"]           = $this->Mpendaftaran->gettindakan_op($poli);
        // $data["row"]			  = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }
    function mcu($no_pasien, $no_reg, $poli)
    {
        $data["no_reg"]              = $no_reg;
        $data["no_pasien"]          = $no_pasien;
        $data["vmenu"]              = $this->session->userdata("controller") . "/vmenu";
        $data['menu']              = "user";
        $data["title"]            = "Rawat Jalan || RS CIREMAI";
        $data["title_header"]      = "MCU";
        $data["content"]           = "pendaftaran/vmcu";
        $data["no_pasien"]          = $no_pasien;
        $data["no_reg"]                = $no_reg;
        $data["poli"]              = $poli;
        $data["breadcrumb"]         = "<li class='active'><strong>MCU</strong></li>";
        $data["q"]                   = $this->Mpendaftaran->getlaporan_tindakan($no_pasien, $no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter_op($poli);
        $data["diagnosa1"]           = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]           = $this->Mpendaftaran->getanastesi();
        $data["asisten"]           = $this->Mpendaftaran->getasisten();
        $data["tindakan"]           = $this->Mpendaftaran->gettindakan_op($poli);
        $data["row"]              = $this->Mpendaftaran->getmcu_detail($no_reg);
        $data["q1"]               = $this->Mpendaftaran->getassesmen_perawat($no_reg);
        $this->load->view('template', $data);
    }
    function simpanmcu($aksi)
    {
        $message = $this->Mpendaftaran->simpanmcu($aksi);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/mcu/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg") . "/" . $this->input->post("tujuan_poli"));
    }
    function cetak_laporantindakan($no_reg = "")
    {
        $data["q"] = $this->Mpendaftaran->getcetak_laporantindakan($no_reg);
        $this->load->view("pendaftaran/vcetak_laporantindakan", $data);
    }
    function cetak_mcu($no_reg = "")
    {
        $data["q"] = $this->Mpendaftaran->getcetak_laporantindakan($no_reg);
        $data["q1"] = $this->Mpendaftaran->getmcu_detail($no_reg);
        $data["q2"]    = $this->Mpendaftaran->getbmi($no_reg);
        $this->load->view("pendaftaran/vcetakmcu", $data);
    }
    function cetakmcu_resume($no_reg = "")
    {
        $data["q"]  = $this->Mpendaftaran->getcetak_laporantindakan($no_reg);
        $data["q1"] = $this->Mpendaftaran->getmcu_detail($no_reg);
        $data["q2"] = $this->Mpendaftaran->getfoto_thorax($no_reg);
        $data["k1"]    = $this->Mpendaftaran->getekspertisilab_detail($no_reg);
        $data["dk"] = $this->Mpendaftaran->getpasien_ralan_mcu($no_reg);
        $this->load->view("pendaftaran/vcetakmcu_resume", $data);
    }
    function cetakmcu_identitas($no_reg = "")
    {
        $data["q"]  = $this->Mpendaftaran->getcetak_laporantindakan($no_reg);
        $data["q1"] = $this->Mpendaftaran->getmcu_detail($no_reg);
        $data["q2"] = $this->Mpendaftaran->getfoto_thorax($no_reg);
        $data["k1"]    = $this->Mpendaftaran->getekspertisilab_detail($no_reg);
        $data["dk"] = $this->Mpendaftaran->getpasien_ralan_mcu($no_reg);
        $data["rw"] = $this->Mpendaftaran->getidentitas_pasien($no_reg);
        $this->load->view("pendaftaran/vcetakmcu_identitas", $data);
    }
    function simpanlaporan_tindakan()
    {
        $message = $this->Mpendaftaran->simpanlaporan_tindakan();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/laporan_tindakan/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg") . "/" . $this->input->post("tujuan_poli"));
    }
    function laporan_tindakaninap($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]              = $no_reg;
        $data["no_pasien"]          = $no_pasien;
        $data["vmenu"]              = $this->session->userdata("controller") . "/vmenu";
        $data['menu']              = "user";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]      = "Laporan Tindakan";
        $data["content"]           = "pendaftaran/vlaporan_tindakaninap";
        $data["breadcrumb"]         = "<li class='active'><strong>Laporan Tindakan</strong></li>";
        $data["q"]                   = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["diagnosa1"]           = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]           = $this->Mpendaftaran->getanastesi();
        $data["asisten"]           = $this->Mpendaftaran->getasisten();
        $data["tindakan"]           = $this->Mpendaftaran->gettindakan_opi();
        $data["tdk"]           = $this->Mpendaftaran->gettindakan_opi_array();
        // $data["row"]			  = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }
    function cetak_laporantindakaninap($no_reg = "")
    {
        $data["q"] = $this->Mpendaftaran->getcetak_laporantindakaninap($no_reg);
        $this->load->view("pendaftaran/vcetak_laporantindakaninap", $data);
    }
    function simpanlaporan_tindakaninap()
    {
        $message = $this->Mpendaftaran->simpanlaporan_tindakaninap();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/laporan_tindakaninap/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
    }

    function simpanvisitinap()
    {
        $message = $this->Mpendaftaran->simpanvisitinap();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inapdokter");
    }
    function simpankonsulinap()
    {
        $message = $this->Mpendaftaran->simpankonsulinap();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inapdokter");
    }

    function simpanjawabankonsulinap()
    {
        $message = $this->Mpendaftaran->simpanjawabankonsulinap();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inapdokter");
    }
    function getdiagnosa1()
    {
        echo json_encode($this->Mpendaftaran->getdiagnosa1());
    }
    function namadiagnosa()
    {
        echo $this->Mpendaftaran->namadiagnosa();
    }
    function formoka($kode = "")
    {
        $data["title"]              = $this->session->userdata('status_user');
        $data["username"]           = $this->session->userdata('username');
        $data['judul']              = "Kamar Operasi &nbsp;&nbsp;&nbsp;";
        $data["vmenu"]              = $this->session->userdata("controller") . "/vmenu";
        $data["menu"]               = "doter";
        $data["title_header"]       = "Kamar Operasi";
        $data["kode"]                = $kode;
        $data["q"]                    = $this->Mpendaftaran->getoka_detail($kode);
        $data["diagnosa"]           = $this->Moka->getdiagnosa();
        $data["dokter"]             = $this->Moka->getdokter();
        $data["dokter_anastesi1"]    = $this->Moka->getdokter_anastesi();
        $data["tarif_operasi"]      = $this->Moka->gettarif_operasi();
        // $data["kamar"]              = $this->Moka->getkamar();
        $data["kamar_operasi"]      = $this->Moka->getkamar_operasi();
        $data["jenis_anatesi"]      = $this->Moka->getjenis_anatesi();
        $data["klasifikasi"]        = $this->Moka->getklasifikasi();
        $data["asisten_op"]         = $this->Moka->getasisten_op();
        $data["asisten_an"]         = $this->Moka->getasisten_an();
        $data["breadcrumb"]         = "<li class='active'><strong>Kamar Operasi</strong></li>";
        $data["content"]            = "oka/vformoka";
        $this->load->view('template', $data);
    }
    function triage_inap($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]              = $no_reg;
        $data["no_pasien"]          = $no_pasien;
        $data["vmenu"]              = $this->session->userdata("controller") . "/vmenu";
        $data['menu']              = "perawat";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]      = "Triage";
        $data["content"]           = "pendaftaran/vtriage";
        $data["breadcrumb"]         = "<li class='active'><strong>Triage</strong></li>";
        $data["q"]                   = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["q1"]              = $this->Mpendaftaran->getpasien_triage($no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["keputusan"]           = $this->Mpendaftaran->getkeputusan();
        $data["dokterigd"]           = $this->Mpendaftaran->getdokterigd();
        $data["petugas_igd"]       = $this->Mpendaftaran->getpetugas_igd();
        $data["diagnosa1"]           = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]           = $this->Mpendaftaran->getanastesi();
        $data["asisten"]           = $this->Mpendaftaran->getasisten();
        $data["tindakan"]           = $this->Mpendaftaran->gettindakan_opi();
        // $data["row"]			  = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }
    function ambiltriage()
    {
        $data["q"]              = $this->Mpendaftaran->ambiltriage();
        $data["title"]          = "Triage || RS CIREMAI";
        $data["title_header"]   = "Triage";
        $this->load->view('pendaftaran/vpilih_triage', $data);
    }
    function simpantriage_inap($no_reg)
    {
        $message = $this->Mpendaftaran->simpantriage($no_reg);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inapdokter");
    }
    function simpanigd_inap($no_reg)
    {
        $message = $this->Mpendaftaran->simpanigd($no_reg);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inapdokter");
    }
    function cetaktriage_inap($no_reg)
    {
        $data["title"]    = "Cetak Inap";
        $data["no_reg"]    = $no_reg;
        $data["q"]        = $this->Mpendaftaran->getcetaktriage_inap($no_reg);
        $this->load->view('pendaftaran/vcetaktriage_inap', $data);
    }
    function cetakigd_inap($no_reg)
    {
        $data["title"]    = "Cetak Inap";
        $data["no_reg"]    = $no_reg;
        $data["q"]        = $this->Mdokter->getcetakigd_inap($no_reg);
        $this->load->view('pendaftaran/vcetak_igd', $data);
    }
    function igd_inap($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]              = $no_reg;
        $data["no_pasien"]          = $no_pasien;
        $data["vmenu"]              = $this->session->userdata("controller") . "/vmenu";
        $data['menu']              = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]      = "Assesment Medic IGD";
        $data["content"]           = "pendaftaran/vigd";
        $data["breadcrumb"]         = "<li class='active'><strong>Assesment Medic IGD</strong></li>";
        $data["q"]                   = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["q1"]              = $this->Mpendaftaran->getpasien_igd($no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["radiologi"]           = $this->Mpendaftaran->gettarif_radiologi();
        $data["lab"]           = $this->Mpendaftaran->gettarif_lab();
        $data["obat"]           = $this->Mpendaftaran->getobat();
        $data["tarif_penunjang_medis"]           = $this->Mpendaftaran->gettarif_penunjang_medis();
        $data["keputusan"]           = $this->Mpendaftaran->getkeputusan();
        $data["dokterigd"]           = $this->Mpendaftaran->getdokterigd();
        $data["petugas_igd"]       = $this->Mpendaftaran->getpetugas_igd();
        $data["diagnosa1"]           = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]           = $this->Mpendaftaran->getanastesi();
        $data["asisten"]           = $this->Mpendaftaran->getasisten();
        $data["tindakan"]           = $this->Mpendaftaran->gettindakan_opi();
        // $data["row"]			  = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }
    function addtindakan_inapradiologi()
    {
        $this->Mpendaftaran->addtindakan_inapradiologi();
        $this->session->set_flashdata("message", "success-Tarif berhasil ditambahkan");
    }
    function addtindakan_inaplab()
    {
        $this->Mpendaftaran->addtindakan_inaplab();
        $this->session->set_flashdata("message", "success-Tarif berhasil ditambahkan");
    }
    function apotek_inapigd($no_pasien, $no_reg)
    {
        $data["vmenu"]                         = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                        = "dokter";
        $data["no_pasien"]                     = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                        = "Apotek Rawat Inap || RS CIREMAI";
        $data["title_header"]                 = "Apotek Rawat Inap";
        $data["content"]                     = "pendaftaran/vviewapotek_inapdokter";
        $data["breadcrumb"]                   = "<li class='active'><strong>Apotek Rawat Inap</strong></li>";
        $data["row"]                          = $this->Mapotek->getinap_detail($no_pasien, $no_reg);
        $data["k"]                          = $this->Mapotek->getapotek_inap($no_reg);
        $data["q"]                          = $this->Mapotek->getapotek_detail($no_reg);
        $data["t"]                          = $this->Mapotek->getobat();
        $data["aturan"]         = $this->Mapotek->getaturan_pakai();
        $data["waktu"]         = $this->Mapotek->getwaktu_pakai();
        $this->load->view('template', $data);
    }
    function tambahtriage_inap($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]              = $no_reg;
        $data["no_pasien"]          = $no_pasien;
        $data["vmenu"]              = $this->session->userdata("controller") . "/vmenu";
        $data['menu']              = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]      = "Form Triage";
        $data["content"]           = "pendaftaran/vformtriage";
        $data["breadcrumb"]         = "<li class='active'><strong>Form Triage</strong></li>";
        $data["q"]                   = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["q1"]                  = $this->Mpendaftaran->getpasien_triage($no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["keputusan"]           = $this->Mpendaftaran->getkeputusan();
        $data["dokterigd"]           = $this->Mpendaftaran->getdokterigd();
        $data["petugas_igd"]       = $this->Mpendaftaran->getpetugas_igd();
        $data["diagnosa1"]           = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]           = $this->Mpendaftaran->getanastesi();
        $data["asisten"]           = $this->Mpendaftaran->getasisten();
        $data["tindakan"]           = $this->Mpendaftaran->gettindakan_opi();
        // $data["row"]			  = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }

    function simpanpasientriage_inap($no_reg)
    {
        $message = $this->Mpendaftaran->simpanpasientriage($no_reg);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inapdokter");
    }
    function getfoto()
    {
        $this->db->select("kakikiri,kakikanan,ibujari_kiri,ibujari_kanan");
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $d = $this->db->get("pasien");
        echo json_encode($d->row());
    }
    function sinkronpasien()
    {
        $this->db->select("no_pasien,nama_pasien,tgl_lahir,alamat,id_gol");
        $this->db->group_start();
        $no_pasien = $this->input->post("cari_no");
        $this->db->or_like("nip", $no_pasien);
        $this->db->or_like("no_pasien", $no_pasien);
        $this->db->or_like("ktp", $no_pasien);
        $this->db->or_like("no_bpjs", $no_pasien);
        $this->db->group_end();
        $d = $this->db->get("pasien");
        echo json_encode($d->row());
    }
    function prosessinkron($jenis)
    {
        if ($jenis == "ranap") {
            $status = $this->db->get_where("gol_pasien", ["id_gol" => $this->input->post("id_gol")])->row()->status;
            $this->db->where("no_reg", $this->input->post('no_reg'));
            $this->db->update("pasien_inap", ["no_rm" => $this->input->post('no_rm'), "nama_pasien" => $this->input->post('nama_pasien'), "id_gol" => $this->input->post("id_gol"), "status_bayar" => $status]);
            $this->db->where("no_reg", $this->input->post('no_reg'));
            $this->db->update("pasien_igdinap", ["no_rm" => $this->input->post('no_rm'), "nama_pasien" => $this->input->post('nama_pasien')]);
            $this->db->where("no_reg", $this->input->post('no_reg'));
            $this->db->update("pasien_triage", ["no_rm" => $this->input->post('no_rm'), "nama_pasien" => $this->input->post('nama_pasien')]);
            $this->db->where("no_pasien", $this->input->post('no_reg'));
            $this->db->delete("pasien");
        } else {
            $status = $this->db->get_where("gol_pasien", ["id_gol" => $this->input->post("id_gol")])->row()->status;
            $this->db->where("no_reg", $this->input->post('no_reg'));
            $this->db->update("pasien_ralan", ["no_pasien" => $this->input->post('no_rm'), "nama_pasien" => $this->input->post('nama_pasien'), "status_pasien" => "LAMA", "gol_pasien" => $this->input->post("id_gol"), "status_bayar" => $status]);
            $this->db->where("no_reg_sebelumnya", $this->input->post('no_reg'));
            $this->db->update("pasien_ralan", ["no_pasien" => $this->input->post('no_rm'), "nama_pasien" => $this->input->post('nama_pasien'), "status_pasien" => "LAMA", "gol_pasien" => $this->input->post("id_gol"), "status_bayar" => $status]);
            $this->db->where("no_reg", $this->input->post('no_reg'));
            $this->db->update("pasien_igd", ["no_rm" => $this->input->post('no_rm'), "nama_pasien" => $this->input->post('nama_pasien')]);
            $this->db->where("no_reg", $this->input->post('no_reg'));
            $this->db->update("pasien_triage", ["no_rm" => $this->input->post('no_rm'), "nama_pasien" => $this->input->post('nama_pasien')]);
            $this->db->where("no_pasien", $this->input->post('no_reg'));
            $this->db->delete("pasien");
        }
    }
    function resume()
    {
        echo json_encode($this->Mpendaftaran->getresume());
    }
    function persenpelayanan($tgl = "", $kota = "3274")
    {
        $data["vmenu"]                         = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                        = "ralan";
        $tgl                                = $tgl == "" ? date("d-m-Y") : date("d-m-Y", strtotime($tgl));
        $bulan                              = date("m", strtotime($tgl));
        $tahun                              = date("Y", strtotime($tgl));
        $data["tgl"]                        = $tgl;
        $data["bulan"]                         = $bulan;
        $data["tahun"]                         = $tahun;
        $data["kota"]                       = $kota;
        $data["title"]                        = "Persentase Rawat Jalan|| RS CIREMAI";
        $data["title_header"]                 = "Persentase Rawat Jalan";
        $data["content"]                     = "pendaftaran/vpersenpelayanan";
        $data["breadcrumb"]                   = "<li class='active'><strong>Persentase Rawat Jalan</strong></li>";
        $data["row"]                          = $this->Mpendaftaran->persenpelayanan($bulan, $tahun);
        $data["p"]                          = json_encode($this->Mpendaftaran->persenperwilayah($kota));
        $this->load->view('template', $data);
    }
    function cppt_ralan($no_pasien = "", $no_reg = "", $tgl1 = "", $tgl2 = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $tgl1                     = $tgl1 == "" ? date("Y-m-d") : $tgl1;
        $tgl2                     = $tgl2 == "" ? date("Y-m-d") : $tgl2;
        $data["tanggal1"]         = date("d-m-Y", strtotime($tgl1));
        $data["tanggal2"]         = date("d-m-Y", strtotime($tgl2));
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakan($no_pasien, $no_reg);
        $data["c"]           = $this->Mcppt->viewcppt_ralan($no_pasien, $tgl1, $tgl2);
        $this->load->view('pendaftaran/vcppt_ralan', $data);
    }
    function cppt_ranap($no_pasien = "", $no_reg = "", $tgl1 = "", $tgl2 = "", $status = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "CPPT";
        $tgl1                     = $tgl1 == "" ? date("Y-m-d") : $tgl1;
        $tgl2                     = $tgl2 == "" ? date("Y-m-d") : $tgl2;
        $data["tanggal1"]         = date("d-m-Y", strtotime($tgl1));
        $data["tanggal2"]         = date("d-m-Y", strtotime($tgl2));
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["c"]           = $this->Mcppt->viewcppt($no_reg, $tgl1, $tgl2, $status);
        echo $this->load->view('pendaftaran/vcppt_ranap', $data);
    }
    function simpanttd()
    {
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $this->db->update("pasien", ["ttd" => $this->input->post("ttd")]);
    }
    function getttd()
    {
        $this->db->select("ttd");
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("pasien_ttd")->row();
        echo $q->ttd;
    }
    function ujifungsi($no_pasien, $no_reg)
    {
        $data["vmenu"]             = $this->session->userdata("controller") . "/vmenu";
        $data['menu']            = "ralan";
        $data["no_pasien"]         = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]            = "Uji Fungsi Rawat Jalan || RS CIREMAI";
        $data["title_header"]     = "Uji Fungsi Rawat Jalan";
        $data["content"]         = "pendaftaran/vujifungsi";
        $data["breadcrumb"]       = "<li class='active'><strong>Uji Fungsi Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mpendaftaran->getdetailpasien($no_pasien);
        $data["q"]            = $this->Mpendaftaran->getpasienujifungsi($no_reg);
        $data["q1"]            = $this->Mpendaftaran->getpasienujifungsi_sebelumnya($no_reg);
        $data["q2"]    = $this->Mpendaftaran->getpasien_ralan_detail($no_reg);
        $data["u"] = $this->Madmindkk->getujifungsi();
        $data["icd"] = $this->Madmindkk->icd10();
        $data["icd9"] = $this->Madmindkk->icd9();
        $data["tarif"] = $this->Mpendaftaran->gettarif_ujifungsi();
        $data["no_pasien"] = $no_pasien;
        $data["no_reg"] = $no_reg;
        $this->load->view('template', $data);
    }
    function simpanujifungsi($action)
    {
        $message = $this->Mpendaftaran->simpanujifungsi($action);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/ujifungsi/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
    }
    function getujifungsidetail($id)
    {
        echo json_encode($this->Madmindkk->getujifungsidetail($id)->result());
    }
    function cetakujifungsi($no_pasien, $no_reg)
    {
        $data["row"] = $this->Mpendaftaran->getdetailpasien($no_pasien);
        $data["q"] = $this->Mpendaftaran->getpasienujifungsi($no_reg)->row();
        $data["p"] = $this->Mpendaftaran->getrawat_jalan($no_reg)->row();
        $data["icd9"] = $this->Madmindkk->icd9();
        $data["icd"] = $this->Madmindkk->icd10();
        $this->load->view("pendaftaran/vcetakuji", $data);
    }
    function cetakrehab($no_pasien, $no_reg)
    {
        $data["row"] = $this->Mpendaftaran->getdetailpasien($no_pasien);
        $data["q"] = $this->Mpendaftaran->getpasienujifungsi($no_reg)->row();
        $data["p"] = $this->Mpendaftaran->getrawat_jalan($no_reg)->row();
        $data["a"] = $this->Mpendaftaran->getpasien_igdralan($no_reg);
        $data["icd9"] = $this->Madmindkk->icd9();
        $data["icd"] = $this->Madmindkk->icd10();
        $this->load->view("pendaftaran/vcetakrehab", $data);
    }
    function cetakujifungsi2($no_pasien, $no_reg)
    {
        $data["row"] = $this->Mpendaftaran->getdetailpasien($no_pasien);
        $data["parent"] = $this->Mpendaftaran->getpasienujifungsi2($no_pasien, 1);
        $data["child"] = $this->Mpendaftaran->getpasienujifungsi2($no_pasien, 0);
        $data["x"] = $this->Mperawat->cetakassesmen_perawat_array($no_pasien);
        $data["p"] = $this->Mpendaftaran->getrawat_jalan($no_reg)->row();
        $data["tarif"] = $this->Mpendaftaran->gettarif_ujifungsi();
        $this->load->view("pendaftaran/vcetakpermintaanterapi", $data);
    }
    function simpan_berita_perawatan()
    {
        $this->Mpendaftaran->simpan_berita_perawatan();
    }
    function getpasien_masuk_perawatan()
    {
        $q = $this->db->get_where("surat_masuk_perawatan", ["no_reg" => $this->input->post("no_reg")]);
        echo json_encode($q->result());
    }
    function getpasien_tindakan()
    {
        $q = $this->db->get_where("pasien_tindakan_medis", ["no_reg" => $this->input->post("no_reg")]);
        echo json_encode($q->result());
    }

    function simpan_berita_lepas_perawatan()
    {
        $this->Mpendaftaran->simpan_berita_lepas_perawatan();
    }
    function getpasien_lepas_perawatan()
    {
        $q = $this->db->get_where("surat_lepas_perawatan", ["no_reg" => $this->input->post("no_reg")]);
        echo json_encode($q->result());
    }
    function simpan_surat_istirahat_sakit()
    {
        $this->Mpendaftaran->simpan_surat_istirahat_sakit();
    }
    function simpan_surat_keterangan_dokter()
    {
        $this->Mpendaftaran->simpan_surat_keterangan_dokter();
    }
    function simpan_ket_narkoba()
    {
        $this->Mpendaftaran->simpan_ket_narkoba();
    }
    function simpan_keterangan_jiwa()
    {
        $this->Mpendaftaran->simpan_keterangan_jiwa();
    }
    function simpan_tindakan_medis()
    {
        $this->Mpendaftaran->simpan_tindakan_medis();
    }
    function getpasien_istirahat_sakit()
    {
        $q = $this->db->get_where("surat_istirahat_sakit", ["no_reg" => $this->input->post("no_reg")]);
        echo json_encode($q->result());
    }
    function getpasien_keterangan_dokter()
    {
        $q = $this->db->get_where("surat_keterangan_dokter", ["no_reg" => $this->input->post("no_reg"), "jenis" => "ralan"]);
        echo json_encode($q->result());
    }
    function getpasien_ket_narkoba()
    {
        $q = $this->db->get_where("surat_narkoba", ["no_reg" => $this->input->post("no_reg"), "jenis" => "ralan"]);
        echo json_encode($q->result());
    }
    function getpasien_jiwa()
    {
        $q = $this->db->get_where("surat_jiwa", ["no_reg" => $this->input->post("no_reg"), "jenis" => "ralan"]);
        echo json_encode($q->result());
    }
    function getralan_detail()
    {
        $no_pasien = $this->input->post("no_pasien");
        $no_reg = $this->input->post("no_reg");
        $q = $this->Mpendaftaran->getralan_detail($no_pasien, $no_reg);
        echo json_encode($q);
    }
    function pulang_ralan()
    {
        $this->Mpendaftaran->pulang_ralan();
    }
    function cetakringkasan($no_rm, $no_reg)
    {
        $data["title"]    = "Cetak Ringkasan Masuk dan Keluar";
        $data["no_rm"]    = $no_rm;
        $data["no_reg"]    = $no_reg;
        $data["p"]              = $this->Mpendaftaran->getinapdetail($no_rm);
        $data["pi"]        = $this->Mpendaftaran->getinap_ringkasan($no_rm, $no_reg);
        $data["lp"]        = $this->Mpendaftaran->getlistpindahkamar($no_rm, $no_reg);
        $data["rp"]        = $this->Mdokter->getresume_pulang($no_rm, $no_reg);
        $data["inos"]        = $this->Mpendaftaran->getinos($no_rm, $no_reg);
        $data["d_ahli"]        = $this->Mpendaftaran->getdokter_ahli($no_reg);
        $data["ad"]        = $this->Mpendaftaran->getassesmen_dokter($no_reg);
        $data["icd10"]        = $this->Mpendaftaran->getindeksinap_icd10_ringkasan($no_reg);
        $data["icd9"]        = $this->Mpendaftaran->getindeksinap_icd9_ringkasan($no_reg);
        $data["dokter"]             = $this->Mlab->getdokter_array("all");
        $this->load->view('pendaftaran/vcetakringkasan', $data);
    }
    function kematian($no_reg, $no_rm, $jenis)
    {
        $data["title"]    = "Cetak Surat Kematian";
        $data["no_rm"]    = $no_rm;
        $data["q3"]        = $this->Msurat->getsetup_rs();
        $data["q"]        = $this->Mpendaftaran->getpasien_detail($no_rm);
        if ($jenis == "ranap") {
            $data["q1"]        = $this->Msurat->getpasieninap_detail($no_reg);
        } else {
            $data["q1"]        = $this->Msurat->getpasienralan_detail($no_reg);
        }
        $data["q2"]        = $this->Msurat->getkematian_detail($no_reg);
        $this->load->view('kematian/vcetakkematian', $data);
    }
    function pemulsaran_covid($no_reg, $no_rm, $jenis)
    {
        $data["title"]    = "Surat Keterangan Pemulsaran Covid";
        $data["no_rm"]    = $no_rm;
        $data["q3"]        = $this->Msurat->getsetup_rs();
        $data["q"]        = $this->Mpendaftaran->getpasien_detail($no_rm);
        if ($jenis == "ranap") {
            $data["q1"]        = $this->Msurat->getpasieninap_detail($no_reg);
        } else {
            $data["q1"]        = $this->Msurat->getpasienralan_detail($no_reg);
        }
        $data["q2"]        = $this->Msurat->getkematian_detail($no_reg);
        $this->load->view('kematian/vcetakpemulsaran_covid', $data);
    }
    function cetakkelahiran($no_reg, $no_pasien, $jenis)
    {
        //$data["status"]    = "copied";
        $data["no_reg"]    = $no_reg;
        $data["no_pasien"] = $no_pasien;
        $data["q"]         = $this->Msurat->getpasien_detail($no_pasien);
        if ($jenis == "ranap") {
            $data["q1"]        = $this->Msurat->getpasieninap_detail($no_reg);
        } else {
            $data["q1"]        = $this->Msurat->getpasienralan_detail($no_reg);
        }
        $data["q2"]        = $this->Msurat->getkelahiran_detail($no_reg);
        $data["q3"]        = $this->Msurat->getsetup_rs();
        $this->load->view("kelahiran/vcetakkelahiran", $data);
    }
    function cetakkelahiran2($no_reg, $no_pasien, $jenis)
    {
        $data["status"]    = "copied";
        $data["no_reg"]    = $no_reg;
        $data["no_pasien"] = $no_pasien;
        $data["q"]         = $this->Msurat->getpasien_detail($no_pasien);
        if ($jenis == "ranap") {
            $data["q1"]        = $this->Msurat->getpasieninap_detail($no_reg);
        } else {
            $data["q1"]        = $this->Msurat->getpasienralan_detail($no_reg);
        }
        $data["q2"]        = $this->Msurat->getkelahiran_detail($no_reg);
        $data["q3"]        = $this->Msurat->getsetup_rs();
        $this->load->view("kelahiran/vcetakkelahiran", $data);
    }
    function cetaknarkoba($no_reg, $no_pasien, $jenis)
    {
        $data["title"]    = "Cetak Surat Keterangan Narkoba";
        $data["no_reg"]    = $no_reg;
        $data["no_pasien"] = $no_pasien;
        $data["q"]         = $this->Msurat->getpasien_detail($no_pasien);
        if ($jenis == "ranap") {
            $data["q1"]        = $this->Msurat->getpasieninap_detail($no_reg);
        } else {
            $data["q1"]        = $this->Msurat->getpasienralan_detail($no_reg);
        }
        $data["q2"]        = $this->Msurat->getnarkoba_detail($no_reg);
        $data["q3"]        = $this->Msurat->getsetup_rs();
        $data["narkoba"]        = $this->Msuket->getnarkoba_test($jenis, $no_reg);
        $this->load->view("suket/vcetaknarkoba", $data);
    }
    function cetakjiwa($no_reg, $no_pasien, $jenis)
    {
        $data["title"]    = "Cetak Surat Keterangan Narkoba";
        $data["no_reg"]    = $no_reg;
        $data["no_pasien"] = $no_pasien;
        $data["q"]         = $this->Msurat->getpasien_detail($no_pasien);
        if ($jenis == "ranap") {
            $data["q1"]        = $this->Msurat->getpasieninap_detail($no_reg);
        } else {
            $data["q1"]        = $this->Msurat->getpasienralan_detail($no_reg);
        }
        $data["q2"]        = $this->Msurat->getjiwa_detail($no_reg);
        $data["q3"]        = $this->Msurat->getsetup_rs();
        $this->load->view("suket/vcetakjiwa", $data);
    }
    function getcek_gk(){
        $row = $this->input->post("row");
        $data = array();
        foreach ($row as $key => $value) {
            $val = explode("_",$value);
            $this->db->where("no_pasien",$val[0]);
            $this->db->where("no_reg",$val[1]);
            $query = $this->db->get("persetujuan");
            $data[$value] = $query->num_rows();
        }
        echo json_encode($data);
    }
    function ekspertisi_inap($no_pasien,$no_reg,$tanggal="",$pemeriksaan=""){
        $data["vmenu"]          = $this->session->userdata("controller")."/vmenu";
        $data['menu']           = "lab";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["tgl"]            = $tanggal;
        $data["pemeriksaan"]    = $pemeriksaan;
        $data["title"]          = "Ekspertisi Inap || RS CIREMAI";
        $data["title_header"]   = "Ekspertisi Inap";
        $data["content"]        = "pendaftaran/vcetak_eks";
        $data["breadcrumb"]     = "<li class='active'><strong>Ekspertisi Inap</strong></li>";
        $data["row"]            = $this->Mlab->getinap_detail1($no_pasien,$no_reg,$tanggal,$pemeriksaan);
        $data["q"]              = $this->Mlab->getekspertisiinap_detail($no_reg);
        // $data["d"]              = $this->Mlab->getdokter_lab();
        // $data["r"]              = $this->Mlab->getanalys();
        $data["k"]              = $this->Mlab->getlabinap_normal($no_reg,$tanggal,$pemeriksaan);
        $data["hasil"]          = $this->Mlab->getekspertisilabinap_detail_array($no_reg,$tanggal,$pemeriksaan);
        // $data["x"]              = $this->Mlab->getekspertisilabinap_detail($no_reg,$tanggal,$pemeriksaan);
        $data["ks"]             = $this->Mlab->getkasir_inap_ekspertisi_covid($no_reg);
        $this->load->view('template',$data);
    }
    function tmbtindakan(){
      $data = array();
      $temp = $this->session->userdata("temptindakan");
      foreach ($temp as $key => $value) {
        $data[] = array(
            "tindakan" => $value["tindakan"],
            "pemeriksaanke" => $value["pemeriksaanke"],
            "tanggal" => $value["tanggal"],
            "jam_masuk" => $value["jam_masuk"],
            "jam_keluar" => $value["jam_keluar"],
            "ulangan" => $value["ulangan"]
        );
      }
      $data[] = array(
          "tindakan" => $this->input->post("tindakan"),
          "pemeriksaanke" => $this->input->post("pemeriksaanke"),
          "tanggal" => $this->input->post("tanggal"),
          "jam_masuk" => $this->input->post("jam_masuk"),
          "jam_keluar" => $this->input->post("jam_keluar"),
          "ulangan" => $this->input->post("ulangan")
      );
      $this->session->set_userdata("temptindakan",$data);
    }
    function hpstindakan(){
      $data = array();
      $temp = $this->session->userdata("temptindakan");
      foreach ($temp as $key => $value) {
        if ($this->input->post("kode")!=$value["tindakan"]){
          $data[] = array(
              "tindakan" => $value["tindakan"],
              "pemeriksaanke" => $value["pemeriksaanke"],
              "tanggal" => $value["tanggal"],
              "jam_masuk" => $value["jam_masuk"],
              "jam_keluar" => $value["jam_keluar"],
              "ulangan" => $value["ulangan"]
          );
        }
      }
      $this->session->set_userdata("temptindakan",$data);
    }
    function cetakpengantarterapi($no_pasien, $no_reg)
    {
        $data["row"]            = $this->Mpendaftaran->getdetailpasien($no_pasien);
        $data["q"]            = $this->Mdokter->getpengantar_terapi($no_reg)->row();
        $q = $this->db->get_where("pasien_ralan",["no_reg"=>$no_reg]);
        $row = $q->row();
        if ($row->no_reg_sebelumnya!=""){
          $no_reg = $row->no_reg_sebelumnya;
        }
        $data["p"] = $this->Mpendaftaran->getrawat_jalan($no_reg)->row();
        $this->load->view("pendaftaran/vcetakpengantarterapi", $data);
    }
    function getpropinsi(){
      $q = $this->db->get_where("propinsi",["id"=>32]);
      $data = array();
      $data[] = array("id"=>'',"text"=>'');
      foreach ($q->result() as $key) {
        $data[] = array("id"=>$key->id,"text"=>$key->name);
      }
      echo json_encode($data);
    }
    function getkota(){
      $q = $this->db->get_where("kotakabupaten",["province_id"=>$this->input->post("propinsi")]);
      $data = array();
      $data[] = array("id"=>'',"text"=>'');
      foreach ($q->result() as $key) {
        $data[] = array("id"=>$key->id,"text"=>$key->name);
      }
      echo json_encode($data);
    }
    function getkecamatan(){
      $q = $this->db->get_where("kecamatan",["regency_id"=>$this->input->post("kota")]);
      $data = array();
      $data[] = array("id"=>'',"text"=>'');
      foreach ($q->result() as $key) {
        $data[] = array("id"=>$key->id,"text"=>$key->name);
      }
      echo json_encode($data);
    }
    function getdesa(){
      $q = $this->db->get_where("desa",["district_id"=>$this->input->post("kecamatan")]);
      $data = array();
      $data[] = array("id"=>'',"text"=>'');
      foreach ($q->result() as $key) {
        $data[] = array("id"=>$key->id,"text"=>$key->name);
      }
      echo json_encode($data);
    }
    function simpan_vaksin(){
      $q = $this->db->get_where("pasien_ralan_vaksin",["no_pasien"=>$this->input->post("no_pasien"),"tujuan_poli"=>"VAKSIN1"]);
      if ($q->num_rows()>0){
        echo "error";
      } else {
        $no = $this->getnoantrian($this->input->post("tempat_vaksin"));
        $data = array(
          "no_reg" => date("YmdHis"),
          "no_pasien" => $this->input->post("no_pasien"),
          "nama_pasien" => $this->input->post("nama_pasien"),
          "no_antrian" => $no["nourut"],
          "tanggal" => date("Y-m-d H:i:s"),
          "tgl_vaksin" => date("Y-m-d",strtotime($no["tgl"])),
          "tujuan_poli" => "VAKSIN1",
          "tempat_vaksin" => $this->input->post("tempat_vaksin")
        );
        $this->db->insert("pasien_ralan_vaksin",$data);
        $this->db->select("p.*,pr.tgl_vaksin as tgl,a.tempat,a.alamat,a.maps");
        $this->db->join("pasien_ralan_vaksin pr","pr.no_pasien=p.no_pasien","inner");
        $this->db->join("tempat_vaksin a","a.id=pr.tempat_vaksin","inner");
        $q = $this->db->get_where("pasien_vaksin p",["p.no_pasien"=>$no_pasien]);
        $row = array();
        $row["list"] = $q->row();
        $q = $this->db->get_where("jadwal_vaksin",["dari<="=>$no_pasien,"sampai>="=>$no_pasien]);
        $row["jam"] = $q->row()->jam;
        echo json_encode($row);
      }
    }
    function getnoantrian($tempat_vaksin)
    {
        $no = array();
        $tgl = date("Y-m-d",strtotime("+1 days"));
        $q = $this->db->get_where("tempat_vaksin",["id"=>$tempat_vaksin])->row();
        $maks = $q->maks;
        for ($i = 1; $i <= 300000; $i++) {
          if ($i>$maks){
            $tgl = date("Y-m-d",strtotime($tgl." +1 days"));
            $i = 1;
          }
          $n = substr("000000" . $i, -6, 6);
          $q = $this->db->get_where("pasien_ralan_vaksin", array("no_antrian" => $n,"tempat_vaksin"=>$tempat_vaksin,"tgl_vaksin"=>date("Y-m-d",strtotime($tgl))));
          if ($q->num_rows() <= 0) {
              $no["nourut"] = $n;
              $no["tgl"] = $tgl;
              return $no;
              break;
          }
        }
    }
    function formskrining_vaksin($no_pasien = "", $no_reg = ""){
		$data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
		$data['menu']           = "pendaftaran";
    $data["no_reg"]         = $no_reg;
    $data["no_pasien"]      = $no_pasien;
		$data["title"]          = "Skrining Vakasin || RS CIREMAI";
		$data["title_header"]   = "Skrining Vakasin";
		$data["breadcrumb"]     = "<li class='active'><strong>Skrining Vakasin</strong></li>";
    $data["q1"]             = $this->Mpendaftaran->getpasien_skrining($no_reg);
    $data["q"]              = $this->Mpendaftaran->getskrining_detail($no_reg);
		$this->load->view('pendaftaran/vformskrining',$data);
		}
    function simpanskrining_vaksin($aksi)
    {
        $message         = $this->Mpendaftaran->simpanskrining_vaksin($aksi);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/formskrining_vaksin/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
    }
}
?>
