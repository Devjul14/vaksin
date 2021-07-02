<?php
class Dokter extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->Model('Mdokter');
        $this->load->Model('Mperawat');
        $this->load->Model('Mpendaftaran');
        $this->load->Model('Mlab');
        $this->load->Model('Mradiologi');
        $this->load->Model('Mkasir');
        $this->load->Model('Mpa');
        $this->load->Model('Mgizi');
        $this->load->Model('Mgrouper');
        $this->load->Model('Mapotek');
        $this->load->Model('Moka');
        $this->load->Model('Mcppt');
        $this->load->Model('Msurat');
        if (($this->session->userdata('username') == NULL) || ($this->session->userdata('password') == NULL)) {
            redirect("login/logout", "refresh");
        }
    }
    function view()
    {
        $data["title"]            = $this->session->userdata('status_user');
        $data["username"]         = $this->session->userdata('username');
        $data["q"]                 = $this->Mdokter->getdokter();
        $data['menu']            = "dokter";
        $data['vmenu']            = "admindkk/vmenu";
        $data["content"]        = "admindkk/dokter/vdokter";
        $data["title_header"]     = "Dokter";
        $data["breadcrumb"]     = "<li class='active'><strong>Dokter</strong></li>";
        $this->load->view('template', $data);
    }
    function formdokter($id = null)
    {
        $data["title"] = $this->session->userdata('status_user');
        // $data["username"] = $this->session->userdata('username');
        $data['menu'] = "dokter";
        $data['vmenu'] = "admindkk/vmenu";
        $data["content"] = "admindkk/dokter/vform_dokter";
        $data["id"] = $id;
        $data["q1"] = $this->Mdokter->getdokter();
        $data["q"] = $this->Mdokter->getdokterdetail($id);
        $data["kel"] = $this->Mdokter->getkel();
        // $data["q1"] = $this->Mdokter->getpuskesmas();
        $data["title_header"] = "Form Dokter";
        $data["d"] = $this->Mdokter->getgelar_depan();
        $data["b"] = $this->Mdokter->getgelar_belakang();
        $data["breadcrumb"] = "<li class='active'><strong>Dokter</strong></li>";
        $this->load->view('template', $data);
    }

    function kelompok()
    {
        $data["title"]          = $this->session->userdata('status_user');
        $data["username"]       = $this->session->userdata('username');
        $data["q"]              = $this->Mdokter->getkelompok();
        $data['menu']           = "dokter";
        $data['vmenu']          = "admindkk/vmenu";
        $data["content"]        = "admindkk/dokter/vkelompokdokter";
        $data["title_header"]   = "Kelompok Dokter";
        $data["breadcrumb"]     = "<li class='active'><strong>Kelompok Kelompok Dokter</strong></li>";
        $this->load->view('template', $data);
    }
    function formkelompok($id = null)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data["username"] = $this->session->userdata('username');
        $data['menu'] = "dokter";
        $data['vmenu'] = "admindkk/vmenu";
        $data["content"] = "admindkk/dokter/vformkelompokdokter";
        $data["id"] = $id;
        $data["q1"] = $this->Mdokter->getkelompok();
        $data["q"] = $this->Mdokter->getkelompokdetail($id);
        $data["kel"] = $this->Mdokter->getkel();
        $data["title_header"]   = "Form Kelompok Dokter";
        $data["breadcrumb"]     = "<li class='active'><strong>Kelompok Kelompok Dokter</strong></li>";
        $this->load->view('template', $data);
    }
    function jadwal_dokter()
    {
        $data["title"]            = $this->session->userdata('status_user');
        $data["username"]         = $this->session->userdata('username');
        $data["q"]                 = $this->Mdokter->getjadwaldokter();
        $data['menu']            = "dokter";
        $data['vmenu']            = "admindkk/vmenu";
        $data["content"]        = "admindkk/dokter/vjadwal_dokter";
        $data["title_header"]     = "Jadwal Dokter";
        $data["breadcrumb"]     = "<li class='active'><strong>Jadwal Dokter</strong></li>";
        $this->load->view('template', $data);
    }
    function formjadwaldokter($id = null)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data["username"] = $this->session->userdata('username');
        $data['menu'] = "dokter";
        $data['vmenu'] = "admindkk/vmenu";
        $data["content"] = "admindkk/dokter/vformjadwaldokter";
        $data["id"] = $id;
        $data["q1"] = $this->Mdokter->getdokter();
        $data["q"] = $this->Mdokter->getjadwaldokterdetail($id);
        $data["q2"] = $this->Mdokter->getpoli();
        $data["title_header"]   = "Form Jadwal Dokter";
        $data["breadcrumb"]     = "<li class='active'><strong>Jadwal Dokter</strong></li>";
        $this->load->view('template', $data);
    }

    function simpandokter($aksi)
    {
        $nama_file = $this->input->post("id_dokter") . "_" . date("YmdHis");
        $message = $this->Mdokter->simpandokter($aksi, $nama_file);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/view");
    }
    function hapusdokter($id)
    {
        $message = $this->Mdokter->hapusdokter($id);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/view");
    }
    function simpanjadwaldokter($aksi)
    {
        $message = $this->Mdokter->simpanjadwaldokter($aksi);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/jadwal_dokter");
    }
    function hapusjadwaldokter($id)
    {
        $message = $this->Mdokter->hapusjadwaldokter($id);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/jadwal_dokter");
    }

    function simpankelompok($aksi)
    {
        $message = $this->Mdokter->simpankelompok($aksi);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/kelompok");
    }
    function hapuskelompok($id)
    {
        $message = $this->Mdokter->hapuskelompok($id);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/kelompok");
    }
    function getttd()
    {
        $this->db->select("ttd");
        $d = $this->db->get_where("dokter_ttd", ["id_dokter" => $this->input->post("id_dokter")]);
        echo $d->row()->ttd;
    }
    function getphoto()
    {
        $this->db->select("photo");
        $d = $this->db->get_where("dokter_ttd", ["id_dokter" => $this->input->post("id_dokter")]);
        echo $d->row()->photo;
    }
    function gelar_depan()
    {
        $data["title"]          = $this->session->userdata('status_user');
        $data["username"]       = $this->session->userdata('username');
        $data["q"]              = $this->Mdokter->getgelar_depan();
        $data['menu']           = "dokter";
        $data['vmenu']          = "admindkk/vmenu";
        $data["content"]        = "admindkk/dokter/vgelar_depan";
        $data["title_header"]   = "Gelar Depan";
        $data["breadcrumb"]     = "<li class='active'><strong>Gelar Depan</strong></li>";
        $this->load->view('template', $data);
    }
    function formgelar_depan($id = null)
    {
        $data["title"]             = $this->session->userdata('status_user');
        $data["username"]         = $this->session->userdata('username');
        $data['menu']            = "dokter";
        $data['vmenu']            = "admindkk/vmenu";
        $data["content"]        = "admindkk/dokter/vformgelar_depan";
        $data["id"]             = $id;
        $data["q"]                 = $this->Mdokter->getgelardepan_detail($id);
        $data["title_header"]   = "Form Gelar Depan";
        $data["breadcrumb"]     = "<li class='active'><strong>Gelar Depan</strong></li>";
        $this->load->view('template', $data);
    }
    function simpangelar_depan($aksi)
    {
        $message = $this->Mdokter->simpangelar_depan($aksi);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/gelar_depan");
    }
    function hapusgelar_depan($id)
    {
        $message = $this->Mdokter->hapusgelar_depan($id);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/gelar_depan");
    }
    function gelar_belakang()
    {
        $data["title"]          = $this->session->userdata('status_user');
        $data["username"]       = $this->session->userdata('username');
        $data["q"]              = $this->Mdokter->getgelar_belakang();
        $data['menu']           = "dokter";
        $data['vmenu']          = "admindkk/vmenu";
        $data["content"]        = "admindkk/dokter/vgelar_belakang";
        $data["title_header"]   = "Gelar Belakang";
        $data["breadcrumb"]     = "<li class='active'><strong>Gelar Belakang</strong></li>";
        $this->load->view('template', $data);
    }
    function formgelar_belakang($id = null)
    {
        $data["title"]             = $this->session->userdata('status_user');
        $data["username"]         = $this->session->userdata('username');
        $data['menu']            = "dokter";
        $data['vmenu']            = "admindkk/vmenu";
        $data["content"]        = "admindkk/dokter/vformgelar_belakang";
        $data["id"]             = $id;
        $data["q"]                 = $this->Mdokter->getgelarbelakang_detail($id);
        $data["title_header"]   = "Form Gelar Belakang";
        $data["breadcrumb"]     = "<li class='active'><strong>Gelar Belakang</strong></li>";
        $this->load->view('template', $data);
    }
    function simpangelar_belakang($aksi)
    {
        $message = $this->Mdokter->simpangelar_belakang($aksi);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/gelar_belakang");
    }
    function hapusgelar_belakang($id)
    {
        $message = $this->Mdokter->hapusgelar_belakang($id);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/gelar_belakang");
    }

    //---------------------------------------------------
    function migrasi($no_pasien)
    {
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data['menu'] = "user";
        $data["title"]        = "Pendaftaran || RS CIREMAI";
        $data["title_header"] = "Migrasi No RM";
        $data["content"] = "pendaftaran/vmigrasi";
        $data["breadcrumb"]   = "<li class='active'><strong>Migrasi No RM</strong></li>";
        $data["q1"] = $this->Mpendaftaran->getnoreg_autocomplete();
        $data["no_pasien_lama"]           = $no_pasien;
        // $data["row"]           = $this->Mpendaftaran->getrjalandetail($id);
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
        $data["title"] = $this->session->userdata('status_user');
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
        //          <label class='control-label'>No. Pasien</label>
        //              <div class='controls'>
        //              <input type='hidden' name='id_pasien' value='".$row->id_pasien."'>
        //              <input type='text' name='no_pasien' value='".$row->no_pasien."' disabled class='span1'>
        //              </div>
        //          </div>
        //          <div class='control-group'>
        //          <label class='control-label'>Pembayaran</label>
        //              <div class='controls'>
        //              <input type='text' name='status_pembayaran' value='".$row->status_pembayaran."' disabled>
        //              </div>
        //          </div>
        //          <div class='control-group'>
        //          <label class='control-label'>Alamat</label>
        //              <div class='controls'><input type='text' name='alamat' value='".$row->alamat."' disabled></div>
        //          </div>
        //          <div class='control-group'>
        //          <label class='control-label'>Kecamatan</label>
        //              <div class='controls'><input type='text' name='nama_kecamatan' value='".$row->nama_kecamatan."' disabled></div>
        //          </div>
        //          <div class='control-group'>
        //          <label class='control-label'>Kelurahan</label>
        //              <div class='controls'><input type='text' name='nama_kelurahan' value='".$row->nama_kelurahan."' disabled></div>
        //          </div>
        //          <div class='control-group'>
        //          <label class='control-label'>RW</label>
        //              <div class='controls'><input type='text' name='nama_rw' value='".$row->nama_rw."' disabled></div>
        //          </div>
        //          <div class='control-group'>
        //              <label class='control-label'>Status Pembayaran</label>
        //                  <div class='controls'>
        //                      <select name='status_pembayaran'>";
        //                          foreach($q3->result() as $r){
        //                              $html .="<option value='".$r->status_pembayaran."' ".($r->status_pembayaran==$row->status_pembayaran ? "selected" : "").">".$r->status_pembayaran."</option>";
        //                          }
        //                      $html .= "
        //                      </select>&nbsp;<span id='karcis'></span>
        //                  </div>
        //          </div>
        //          ";
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
    function index($current = 0, $from = 0)
    {
        $data["title"] = $this->session->userdata('status_user');
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
        $this->session->set_userdata("status_pasien", $this->input->post("status_pasien"));
    }
    function getcaripasien_inap()
    {
        $this->session->set_flashdata("no_pasien", $this->input->post("cari_no"));
        $this->session->set_flashdata("nama", $this->input->post("cari_nama"));
        $this->session->set_flashdata("no_reg", $this->input->post("cari_noreg"));
    }
    function addpasienbaru($iskk, $baru, $no_kk = NULL, $id_pasien = NULL, $no_reg = NULL)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pendaftaran Pasien Baru&nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vaddpasienbaru";
        $data['menu'] = "user";
        $data["username"] = $this->session->userdata('nama_user');
        $data["title_header"] = "Identitas Pasien";
        $data["breadcrumb"] = "<li class='active'><strong>Identitas Pasien</strong></li>";
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
        $data["no_reg"]           = $this->Mpendaftaran->getnoreg();
        $data["tarif"]            = $this->Mpendaftaran->gettarif($igd, "tdk");
        $data["d"]            = $this->Mpendaftaran->getdokter();
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
        $data["no_reg"]           = $this->Mpendaftaran->getnoreginap();
        $data["tarif"]            = $this->Mpendaftaran->gettarif(true, "tdk");
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
        $data["q"]              = $this->Mpendaftaran->getdokter();
        $data["row"]              = $this->Mpendaftaran->getinap_edit($id, $no_reg);
        $data["tarif"]            = $this->Mpendaftaran->gettarif(true, "tdk");
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
        $data["q"]              = $this->Mpendaftaran->getdokter();
        $data["row"]              = $this->Mpendaftaran->getinap_edit($id, $no_reg);
        $data["tarif"]            = $this->Mpendaftaran->gettarif(true, "tdk");
        $this->load->view('template', $data);
    }
    function pindahstatus($id, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "inap";
        $data["title"]          = "Rawat Inap || RS CIREMAI";
        $data["title_header"]   = "Pindah Status Pasien Rawat Inap";
        $data["content"]        = "pendaftaran/vformpindahstatus";
        $data["no_reg"]         = $no_reg;
        $data["id"]             = $id;
        $data["breadcrumb"]     = "<li class='active'><strong>Pindah Status Pasien Rawat Inap</strong></li>";
        $data["q"]              = $this->Mpendaftaran->getdokter();
        $data["row"]            = $this->Mpendaftaran->getinap_edit($id, $no_reg);
        $data["tarif"]          = $this->Mpendaftaran->gettarif(true, "tdk");
        $data["g"]              = $this->Mpendaftaran->getgolpasien();
        $data["p"]              = $this->Mpendaftaran->getperusahaan();
        $this->load->view('template', $data);
    }
    function inos($id, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "inap";
        $data["title"]          = "Rawat Inap || RS CIREMAI";
        $data["title_header"]   = "INOS Pasien Rawat Inap";
        $data["content"]        = "pendaftaran/vforminos";
        $data["no_reg"]         = $no_reg;
        $data["id"]             = $id;
        $data["breadcrumb"]     = "<li class='active'><strong>INOS Pasien Rawat Inap</strong></li>";
        $data["q"]              = $this->Mpendaftaran->getjenisinos();
        $data["row"]            = $this->Mpendaftaran->getinap_edit($id, $no_reg);
        $data["q1"]             = $this->Mpendaftaran->getinos($id, $no_reg);
        $data["s"]              = $this->Mpendaftaran->getspesialisasi();
        $this->load->view('template', $data);
    }
    function inos_harian()
    {
        $data["title"]  = "Inos Harian Rawat Inap";
        $data["q"]      = $this->Mpendaftaran->getjenisinos();
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
    function simpaninos()
    {
        $message = $this->Mpendaftaran->simpaninos();
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
    // function tindakan(){
    //  $data["title"] = $this->session->userdata('status_user');
    //  $data['judul'] = "Tindakan&nbsp;&nbsp;&nbsp;";
    //  $data["vmenu"] = $this->session->userdata("controller")."/vmenu";
    //  $data["content"] = "pendaftaran/vtindakan";
    //  $q =$this->Mpendaftaran->getjumlahtindakan();
    //  //$data["content"] = "pendaftaran/vaddpasienbaru";
    //  $data['menu']="rekap";
    //  $data["username"] = $this->session->userdata('nama_user');
    //     $data["title_header"] = "Tindakan";
    //     $data["breadcrumb"] = "<li class='active'><strong>Tindakan</strong></li>";

    //  $nama_tindakan = $this->input->post("nama_tindakan");
    //  $data["nama_tindakan"] = $nama_tindakan;

    //  $baris = $this->input->post("baris");
    //  if($baris=="") $baris = 50;

    //  $hal = $this->input->post("hal");
    //  if($hal=="") $hal = 1;

    //  $row = $q->row();
    //  $jmlrec=$row->jumlah;
    //  $n=$jmlrec/$baris;
    //  if ($n==floor($jmlrec/$baris)) $npage=$n; else $npage=floor($jmlrec/$baris)+1;
    //  if ($npage==0) $npage=1;
    //  $posisi=($hal-1)*$baris;
    //  $data["q3"] =$this->Mpendaftaran->gettindakan($posisi,$baris);
    //  $data["npage"] = $npage;
    //  $data["hal"] = $hal;
    //  $data["baris"] = $baris;
    //  $data["posisi"] = $posisi;
    //  $data["jmlrec"] = $jmlrec;
    //  $this->load->view('template',$data);
    //    }
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
    function ambil_province($id = "")
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province?id=" . $id,
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
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
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
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=" . $city . "&id=",
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
        $data["q"]              = $this->Mpendaftaran->getwilayah($nama);
        $data["title"]          = $jenis . " || RS CIREMAI";
        $data["title_header"]   = $jenis;
        $this->load->view('pendaftaran/vpilih_wilayah', $data);
    }
    function pilihperusahaan()
    {
        $data["q"]              = $this->Mpendaftaran->getperusahaan();
        $data["title"]          = "Perusahaan || RS CIREMAI";
        $data["title_header"]   = "Perusahaan";
        $this->load->view('pendaftaran/vpilih_perusahaan', $data);
    }
    function pilihpangkat($id_golongan)
    {
        $data["q"]              = $this->Mpendaftaran->pilihpangkat($id_golongan);
        $data["title"]          = "Perusahaan || RS CIREMAI";
        $data["title_header"]   = "Perusahaan";
        $data["id_golongan"]    = $id_golongan;
        $this->load->view('pendaftaran/vpilih_pangkat', $data);
    }
    function pilihpoli()
    {
        $data["q"]              = $this->Mpendaftaran->getpoli();
        $data["title"]          = "Poli || RS CIREMAI";
        $data["title_header"]   = "Poli";
        $this->load->view('pendaftaran/vpilih_poli', $data);
    }
    function pilihpolid()
    {
        $data["q"]              = $this->Mpendaftaran->getpoli();
        $data["title"]          = "Poli || RS CIREMAI";
        $data["title_header"]   = "Poli";
        $this->load->view('pendaftaran/vpilih_polid', $data);
    }
    function pilihnoreg()
    {
        $no_reg = $this->input->post("no_reg");
        $data["no_reg"] = $no_reg;
        $data["q"]              = $this->Mpendaftaran->getpilihnoreg($no_reg);
        $data["title"]          = "No REG || RS CIREMAI";
        $data["title_header"]   = "No REG";
        $this->load->view('pendaftaran/vpilih_noreg', $data);
    }
    function pilihruangan()
    {
        $data["q"]              = $this->Mpendaftaran->getruangan();
        $data["title"]          = "Ruangan || RS CIREMAI";
        $data["title_header"]   = "Ruangan";
        $this->load->view('pendaftaran/vpilih_ruangan', $data);
    }
    function pilihruangan1()
    {
        $data["q"]              = $this->Mpendaftaran->getruangan1();
        $data["title"]          = "Ruangan || RS CIREMAI";
        $data["title_header"]   = "Ruangan";
        $this->load->view('pendaftaran/vpilih_ruangana', $data);
    }
    function pilihkelas()
    {
        $data["q"]              = $this->Mpendaftaran->getkelas();
        $data["title"]          = "Kelas || RS CIREMAI";
        $data["title_header"]   = "Kelas";
        $this->load->view('pendaftaran/vpilih_kelas', $data);
    }
    function pilihdokter()
    {
        $data["q"]              = $this->Mpendaftaran->getdokter();
        $data["title"]          = "Dokter || RS CIREMAI";
        $data["title_header"]   = "Dokter";
        $this->load->view('pendaftaran/vpilih_dokter', $data);
    }
    function pilihdokterpoli($kode_poli = "")
    {
        $data["q"]              = $this->Mpendaftaran->getdokterpoli($kode_poli);
        $data["title"]          = "Dokter || RS CIREMAI";
        $data["title_header"]   = "Dokter";
        $this->load->view('pendaftaran/vpilih_dokterpoli', $data);
    }
    function pilihdiagnosa($kode = "")
    {
        $data["q"]              = $this->Mpendaftaran->pilihdiagnosa($kode);
        $data["title"]          = "Diagnosa || RS CIREMAI";
        $data["title_header"]   = "Diagnosa";
        $this->load->view('pendaftaran/vpilih_diagnosa', $data);
    }
    function cetakpasien($no_pasien)
    {
        $data["no_pasien"]  = $no_pasien;
        $data["q"]          = $this->Mpendaftaran->getcetakpasien($no_pasien);
        $this->load->view('pendaftaran/vcetakrekmed', $data);
    }
    function cetakinap($no_rm, $no_reg)
    {
        $data["title"]  = "Cetak Inap";
        $data["no_rm"]  = $no_rm;
        $data["no_reg"] = $no_reg;
        $data["q"]      = $this->Mpendaftaran->getcetakinap($no_rm, $no_reg);
        $this->load->view('pendaftaran/vcetakinap', $data);
    }
    function cetak_rekmed($id)
    {
        $data["id"] = $id;
        $data["q"]          = $this->Mpendaftaran->getcetakrekmed($id);
        $this->load->view('pendaftaran/vcetakrekmed', $data);
    }
    function rawat_jalan($current = 0, $from = 0)
    {
        //  $minimum_price = $this->input->post('tgl1');
        // $maximum_price = $this->input->post('tgl2');
        // $brand = $this->input->post('kode_poli');
        // $brand = $this->input->post('kode_doker');
        if ($this->session->userdata("status_pasien") == "")
            $this->session->set_flashdata("status_pasien", "BARU");
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
        $config['base_url'] = base_url() . 'pendaftaran/rawat_jalan/' . $current;
        $config['total_rows'] = $this->Mpendaftaran->getpasien_rawatjalan();
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
        $data["total_rows"] = $config['total_rows'];
        $from = $this->uri->segment(4);
        $data["from"] = $from;
        $this->pagination->initialize($config);
        $data["q3"] = $this->Mpendaftaran->getpasien_ralan($config['per_page'], $from);
        $this->load->view('template', $data);
    }
    function rawat_jalandokter($current = 0, $from = 0)
    {
        //  $minimum_price = $this->input->post('tgl1');
        // $maximum_price = $this->input->post('tgl2');
        // $brand = $this->input->post('kode_poli');
        // $brand = $this->input->post('kode_doker');
        $this->session->set_flashdata("status_pasien", "ALL");
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
        $data["breadcrumb"] = "<li class='active'><strong>Pasien Rawat Jalan</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'dokter/rawat_jalandokter/' . $current;
        $config['total_rows'] = $this->Mpendaftaran->getpasien_rawatjalan();
        // $data["jlayan"] = $this->Mpendaftaran->gettotalpasien("LAYAN");
        // $data["jbatal"] = $this->Mpendaftaran->gettotalpasien("BATAL");
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
        $data["total_rows"] = $config['total_rows'];
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
        $this->session->set_userdata('status_pasien', $this->input->post("status_pasien"));
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
        $this->session->set_flashdata('status_pasien', "ALL");
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
        $this->session->set_flashdata('status_pasien', "ALL");
        $this->session->unset_userdata('tgl1');
        $this->session->unset_userdata('tgl2');
        redirect("dokter/rawat_inapdokter");
    }
    function reset_igd()
    {
        $this->session->unset_userdata('no_pasien');
        $this->session->unset_userdata('poli_kode');
        $this->session->unset_userdata('poliklinik');
        $this->session->unset_userdata('kode_dokter');
        $this->session->unset_userdata('dokter');
        $this->session->set_flashdata('status_pasien', "ALL");
        $this->session->unset_userdata('tgl1');
        $this->session->unset_userdata('tgl2');
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
    function reset_ranapdokter()
    {
        $this->session->unset_userdata('kode_kelas');
        $this->session->unset_userdata('kelas');
        $this->session->unset_userdata('kode_ruangan');
        $this->session->unset_userdata('ruangan');
        $this->session->unset_userdata('tgl1');
        $this->session->unset_userdata('tgl2');
        $this->session->unset_userdata('no_pasien');
        redirect("dokter/rawat_inapdokter_ranap");
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
        redirect("dokter/rawat_inapdokterigd");
    }

    function rawat_inap($current = 0, $from = 0)
    {
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
        $this->load->view('template', $data);
    }
    function rawat_inapdokter($current = 0, $from = 0)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Triage &nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vlistrawatinapdokter";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "perawat";
        $data["current"] = $current;
        $data["title_header"] = "Triage";
        $data["breadcrumb"] = "<li class='active'><strong>Triage</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'dokter/rawat_inapdokter/' . $current;
        $config['total_rows'] = $this->Mdokter->getpasien_rawatinap();
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
        $data["q3"] = $this->Mdokter->getpasien_inap($config['per_page'], $from);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $this->load->view('template', $data);
    }
    function pasienigd($current = 0, $from = 0)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pasien IGD &nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vlistpasienigd";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "dokter";
        $data["current"] = $current;
        $data["title_header"] = "Pasien IGD ";
        $data["breadcrumb"] = "<li class='active'><strong>Pasien IGD</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'dokter/pasienigd/' . $current;
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
        $data["q3"] = $this->Mdokter->getpasien_inapigd($config['per_page'], $from);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $this->load->view('template', $data);
    }
    function rawat_inapdokter_ranap($current = 0, $from = 0)
    {
        $data["title"] = $this->session->userdata('status_user');
        $data['judul'] = "Pasien Rawat Inap &nbsp;&nbsp;&nbsp;";
        $data["vmenu"] = $this->session->userdata("controller") . "/vmenu";
        $data["content"] = "pendaftaran/vlistrawatinapdokter_ranap";
        $data["username"] = $this->session->userdata('nama_user');
        $data['menu'] = "dokter";
        $data["current"] = $current;
        $data["title_header"] = "Pasien Rawat Inap ";
        $data["breadcrumb"] = "<li class='active'><strong>Pasien Rawat Inap</strong></li>";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'dokter/rawat_inapdokter_ranap/' . $current;
        $config['total_rows'] = $this->Mdokter->getpasien_rawatinapdokter();
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
        $data["q3"] = $this->Mdokter->getpasien_inapdokter($config['per_page'], $from);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $this->session->unset_userdata("full");
        $this->load->view('template', $data);
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
        $data["id"]                 = $id;
        $data["reg_sebelumnya"]     = $reg_sebelumnya;
        $data["reg_baru"]           = $reg_baru;
        $data["vmenu"]              = $this->session->userdata("controller") . "/vmenu";
        $data['menu']               = "ralan";
        $data["title"]              = "Rawat Jalan || RS CIREMAI";
        $data["title_header"]       = "Konsul";
        $data["content"]            = "pendaftaran/vkonsul";
        $data["breadcrumb"]         = "<li class='active'><strong>Konsul</strong></li>";
        $data["row"]                = $this->Mpendaftaran->ceknoreg($reg_baru);
        $data["pasien"]             = $this->Mpendaftaran->getpasien_detail($id);
        $data["no_reg"]             = $this->Mpendaftaran->getnoreg();
        $data["q"]                  = $this->Mpendaftaran->getnoreg_sebelumnya($reg_sebelumnya);
        $data["d"]                  = $this->Mpendaftaran->getdokter();
        $data["k"]                  = $this->Mlab->getkasir($reg_baru);
        $data["k1"]                 = $this->Mradiologi->getkasir($reg_baru);
        $data["q1"]                 = $this->Mkasir->getkasir_detail($reg_baru);
        $data["t"]                  = $this->Mlab->gettarif_lab();
        $data["t1"]                 = $this->Mradiologi->gettarif_radiologi();
        $data["dokter"]             = $this->Mlab->getdokter_array();
        $data["dok_all"]            = $this->Mlab->getdokter_array("all");
        $data["analys"]             = $this->Mlab->getanalys_array();
        $data["dp"]                 = $this->Mlab->getdokterpengirim_array();
        $this->load->view('template', $data);
    }
    function simpankonsul($action)
    {
        $no_reg     = $this->input->post("no_reg");
        $no_pasien  = $this->input->post("no_pasien");
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
        $no_pasien  = $this->input->post("no_pasien");
        $sebelumnya = $this->input->post("reg_sebelumnya");
        $this->Mlab->addtindakan();
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
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "ralan";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]          = "Tindakan || RS CIREMAI";
        $data["title_header"]   = "Tindakan";
        $data["content"]        = "pendaftaran/vtindakan";
        $data["breadcrumb"]     = "<li class='active'><strong>Tindakan</strong></li>";
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
        $data["t1"]             = $this->Mkasir->gettindakan_radiologi();
        $data["p"]              = $this->Mkasir->getpenunjang_medis();
        $this->load->view('template', $data);
    }
    function formsep($no_pasien, $no_reg, $nobpjs)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "ralan";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["nobpjs"]         = $nobpjs;
        $data["title"]          = "SEP || RS CIREMAI";
        $data["title_header"]   = "SEP";
        $data["content"]        = "pendaftaran/vsep_ralan";
        $data["breadcrumb"]     = "<li class='active'><strong>SEP</strong></li>";
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
    function ekspertisiradiologi_inap($no_pasien, $no_reg, $id_tindakan = "", $tgl = "")
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "inap";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["id_tindakan"]    = $id_tindakan;
        $data["title"]          = "Ekspertisi Radiologi || RS CIREMAI";
        $data["title_header"]   = "Ekspertisi Radiologi";
        $data["tgl"]            = $tgl;
        $data["content"]        = "pendaftaran/vformekspertisiradiologi_inap";
        $data["breadcrumb"]     = "<li class='active'><strong>Ekspertisi Radiologi</strong></li>";
        $data["row"]            = $this->Mradiologi->getinap_detail($no_pasien, $no_reg);
        $data["q"]              = $this->Mradiologi->getekspertisiinap_detail($no_pasien, $no_reg, $id_tindakan, $tgl);
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
        $data["row"]            = $this->Mlab->getinap_detail1($no_pasien, $no_reg);
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
        $data["breadcrumb"]                 = "<li class            ='active'><strong>Ekspertisi Gizi</strong></li>";
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
        $data["content"]        = "dokter/vformlab_inap";
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
        $data["content"]        = "dokter/vformradiologi_inap";
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
        $data["content"]        = "dokter/vformpa_inap";
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
        $data["content"]        = "dokter/vformgizi_inap";
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
        $q                                  = $this->Mpendaftaran->cektglpulang($no_pasien, $no_reg);
        if ($q->tanggal_pulang == "0000-00-00 00:00:00" || $q->tanggal_pulang == NULL) {
            $data["content"]                = "pendaftaran/vindeks_notfound";
        } else {
            $data["content"]                = "pendaftaran/vindeks";
        }

        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "ralan";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                      = "Indeks Rawat Jalan || RS CIREMAI";
        $data["title_header"]               = "Indeks Rawat Jalan";
        $data["breadcrumb"]                 = "<li class='active'><strong>Indeks Rawat Jalan</strong></li>";
        $data["row"]                        = $this->Mgrouper->getralan_detail($no_pasien, $no_reg);
        $data["g1"]                         = $this->Mgrouper->getgrouper(6, 0);
        $data["g2"]                         = $this->Mgrouper->getgrouper(6, 6);
        $data["g3"]                         = $this->Mgrouper->getgrouper(6, 12);
        $data["hasil"]                      = $this->Mgrouper->getgrouper_ralan($no_reg);
        $data["i10"]                        = $this->Mgrouper->getindeksicd10_ralan($no_reg);
        $data["i9"]                         = $this->Mgrouper->getindeksicd9_ralan($no_reg);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $this->load->view('template', $data);
    }
    function indeks_inap($no_pasien, $no_reg)
    {
        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "inap";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                      = "Indeks Rawat Inap || RS CIREMAI";
        $data["title_header"]               = "Indeks Rawat Inap";
        $data["content"]                    = "pendaftaran/vindeks_inap";
        $data["breadcrumb"]                 = "<li class='active'><strong>Indeks Rawat Inap</strong></li>";
        $data["row"]                        = $this->Mgrouper->getinap_detail($no_pasien, $no_reg);
        $data["g1"]                         = $this->Mgrouper->getgrouper(6, 0);
        $data["g2"]                         = $this->Mgrouper->getgrouper(6, 6);
        $data["g3"]                         = $this->Mgrouper->getgrouper(6, 12);
        $data["hasil"]                      = $this->Mgrouper->getgrouper_inap($no_reg);
        $data["i10"]                        = $this->Mgrouper->getindeksicd10_inap($no_reg);
        $data["i9"]                         = $this->Mgrouper->getindeksicd9_inap($no_reg);
        $data["k"] = $this->Mkasir->getkeadaan_pulang();
        $data["sp"] = $this->Mkasir->getstatus_pulang();
        $this->load->view('template', $data);
    }
    function geticd10()
    {
        echo json_encode($this->Mgrouper->geticd10());
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
        redirect("pendaftaran/rawat_jalan");
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
        redirect("pendaftaran/rawat_jalan");
    }
    function layani_inap($no_rm, $no_reg)
    {
        $message = $this->Mpendaftaran->layani_inap($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        // $this->session->set_userdata("no_pasien",$no_reg);
        redirect("pendaftaran/rawat_inap");
    }
    function send_inap($no_rm, $no_reg)
    {
        $message = $this->Mpendaftaran->send_inap($no_rm, $no_reg);
        $this->session->set_flashdata("message", $message);
        // $this->session->set_userdata("no_pasien",$no_reg);
        redirect("pendaftaran/rawat_inap");
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
        $data["content"]        = "dokter/vformekspertisiradiologi_ralan";
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
        $data["content"]        = "dokter/vformekspertisilab_ralan";
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
        $data["content"]        = "dokter/vformekspertisipa_ralan";
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
        $data["content"]                    = "dokter/vformekspertisigizi_ralan";
        $data["breadcrumb"]                 = "<li class            ='active'><strong>Ekspertisi Gizi</strong></li>";
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
        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "ralan";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                      = "Apotek Rawat Jalan || RS CIREMAI";
        $data["title_header"]               = "Apotek Rawat Jalan";
        $data["content"]                    = "dokter/vviewapotek_ralan";
        $data["breadcrumb"]                 = "<li class='active'><strong>Apotek Rawat Jalan</strong></li>";
        $data["row"]                        = $this->Mapotek->getralan_detail($no_pasien, $no_reg);
        $data["k"]                          = $this->Mapotek->getapotek($no_reg);
        $data["q"]                          = $this->Mapotek->getapotek_detail($no_reg);
        $data["t"]                          = $this->Mapotek->getobat();
        $this->load->view('template', $data);
    }
    function apotek_inap($no_pasien, $no_reg)
    {
        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "ralan";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                      = "Apotek Rawat Inap || RS CIREMAI";
        $data["title_header"]               = "Apotek Rawat Inap";
        $data["content"]                    = "pendaftaran/vviewapotek_inap";
        $data["breadcrumb"]                 = "<li class='active'><strong>Apotek Rawat Inap</strong></li>";
        $data["row"]                        = $this->Mapotek->getinap_detail($no_pasien, $no_reg);
        $data["k"]                          = $this->Mapotek->getapotek_inap($no_reg);
        $data["q"]                          = $this->Mapotek->getapotek_detail($no_reg);
        $data["t"]                          = $this->Mapotek->getobat();
        $this->load->view('template', $data);
    }
    function hapusinos($no_pasien, $no_reg, $jenis_inos)
    {
        $message = $this->Mpendaftaran->hapusinos($no_pasien, $no_reg, $jenis_inos);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/inos/" . $no_pasien . "/" . $no_reg);
    }
    function cetak_mata($kode = "")
    {
        $data["kode"]               = $kode;
        $data["q"]                  = $this->Moka->getlaporan_mataoka($kode);
        $this->load->view('oka/vcetak_mata', $data);
    }
    function cetak_operasi($kode = "")
    {
        $data["q"] = $this->Moka->getcetakoka($kode);
        $this->load->view("oka/vcetak_oka", $data);
    }
    function laporan_tindakan($no_pasien = "", $no_reg = "", $poli = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "user";
        $data["title"]            = "Rawat Jalan || RS CIREMAI";
        $data["title_header"]     = "Laporan Tindakan";
        $data["content"]          = "pendaftaran/vlaporan_tindakan";
        $data["breadcrumb"]       = "<li class='active'><strong>Laporan Tindakan</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakan($no_pasien, $no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter_op($poli);
        $data["diagnosa1"]        = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]         = $this->Mpendaftaran->getanastesi();
        $data["asisten"]          = $this->Mpendaftaran->getasisten();
        $data["tindakan"]         = $this->Mpendaftaran->gettindakan_op($poli);
        // $data["row"]           = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }
    function cetak_laporantindakan($no_reg = "")
    {
        $data["q"] = $this->Mpendaftaran->getcetak_laporantindakan($no_reg);
        $this->load->view("pendaftaran/vcetak_laporantindakan", $data);
    }
    function simpanlaporan_tindakan()
    {
        $message = $this->Mpendaftaran->simpanlaporan_tindakan();
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/laporan_tindakan/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg") . "/" . $this->input->post("tujuan_poli"));
    }
    function laporan_tindakaninap($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "user";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "Laporan Tindakan";
        $data["content"]          = "pendaftaran/vlaporan_tindakaninap";
        $data["breadcrumb"]       = "<li class='active'><strong>Laporan Tindakan</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["diagnosa1"]        = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]         = $this->Mpendaftaran->getanastesi();
        $data["asisten"]          = $this->Mpendaftaran->getasisten();
        $data["tindakan"]         = $this->Mpendaftaran->gettindakan_opi();
        // $data["row"]           = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }
    function visit_inap($no_pasien = "", $no_reg = "", $iddokter = "", $id_terkait = "", $id = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "Visit";
        $iddokter                 = $iddokter == "" ? $this->Mdokter->getdokterkonsul_inap($no_reg)->row()->dokter_konsul : $iddokter;
        $id_terkait               = $id_terkait == "" ? $this->Mdokter->getdokterkonsul_inap($no_reg)->row()->id : $id_terkait;
        $data["iddokter"]         = $iddokter;
        $data["id_terkait"]       = $id_terkait;
        $data["id"]               = $id;
        $data["content"]          = "dokter/vvisit_inap";
        $data["breadcrumb"]       = "<li class='active'><strong>Visit</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["dokter"]           = $this->Mdokter->getdokterkonsul_inap($no_reg);
        $data["row"]              = $this->Mdokter->listvisit($no_reg, $iddokter);
        $data["v"]                = $this->Mdokter->listvisit_detail($id);
        $data["radiologi"]        = $this->Mpendaftaran->gettarif_radiologi();
        $data["lab"]              = $this->Mpendaftaran->gettarif_lab();
        $data["tarif_penunjang_medis"] = $this->Mpendaftaran->gettarif_penunjang_medis();
        $this->load->view('template', $data);
    }
    function konsul_inap($no_pasien = "", $no_reg = "", $iddokter = "", $id_terkait = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "Konsul";
        $data["content"]          = "dokter/vkonsul_inap";
        $iddokter                 = $iddokter == "" ? $this->Mdokter->getdokterkonsul_inap($no_reg)->row()->dokter_konsul : $iddokter;
        $id_terkait               = $id_terkait == "" ? $this->Mdokter->getdokterkonsul_inap($no_reg)->row()->id : $id_terkait;
        $data["iddokter"]         = $iddokter;
        $data["id_terkait"]       = $id_terkait;
        $data["breadcrumb"]       = "<li class='active'><strong>Konsul</strong></li>";
        $data["q"]                = $this->Mdokter->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["dokter"]           = $this->Mdokter->getdokterkonsul_inap($no_reg);
        $data["dv"]             = $this->Mdokter->getdoktervisit_inap($id_terkait);
        $data["dk"]               = $this->Mdokter->getdokter();
        $data["dsp"]           = $this->Mdokter->getdokterkonsultambahan_inap($no_reg, $id_terkait);
        $data["diagnosa1"]        = $this->Mdokter->getdiagnosa();
        $data["anastesi"]         = $this->Mdokter->getanastesi();
        $data["asisten"]          = $this->Mdokter->getasisten();
        $data["tindakan"]         = $this->Mdokter->gettindakan_opi();
        $data["k"]                = $this->Mdokter->getkonsul_inap($no_reg);
        $data["pdf"]              = $this->Mgrouper->getfilepdf_inap($no_reg);
        $data["radiologi"]        = $this->Mpendaftaran->gettarif_radiologi();
        $data["lab"]          = $this->Mpendaftaran->gettarif_lab();
        $data["tarif_penunjang_medis"]        = $this->Mpendaftaran->gettarif_penunjang_medis();
        $this->load->view('template', $data);
    }
    function resume_inap($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Resume Pulang || RS CIREMAI";
        $data["title_header"]     = "Resume Pulang";
        $data["content"]          = "dokter/vresume_pulang";
        $data["breadcrumb"]       = "<li class='active'><strong>Resume Pulang</strong></li>";
        $data["q"]                = $this->Mdokter->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["p"]                = $this->Mdokter->getresume_pulang($no_pasien, $no_reg);
        $data["r"]                = $this->Mdokter->getriwayat_pasien_inap($no_reg);
        $data["rad"]                = $this->Mdokter->getradinap($no_reg);
        $data["pa"]                = $this->Mdokter->getpainap($no_reg);
        $data["ad"]                = $this->Mdokter->getpasien_igdinap($no_reg);
        $data["q1"]     = $this->Mperawat->cetakassesmen_perawat($no_reg)->row();
        $data["ok"]                = $this->Mdokter->getokadetail($no_reg);
        $data["ob"]                = $this->Mdokter->getapotekinap_resume($no_reg);
        $data["kp"]             = $this->Mdokter->getstatuspulang();
        $data["dpjp"]             = $this->Mdokter->getdpjp_poli();
        $data["k"]              = $this->Mlab->getlabinap_normal($no_reg);
        $data["hasil"]          = $this->Mlab->getekspertisilabinap_detail_array($no_reg);
        $this->load->view('template', $data);
    }
    function rujukan_pasien_ralan($no_pasien = "", $no_reg = "", $jenis = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["jenis"]            = $jenis;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rujukan Pasien || RS CIREMAI";
        $data["title_header"]     = "Rujukan Pasien";
        $data["content"]          = "dokter/vrujukan_pasien";
        $data["breadcrumb"]       = "<li class='active'><strong>Rujukan Pasien</strong></li>";
        $data["q"]                = $this->Mdokter->getlaporan_tindakan($no_pasien, $no_reg);
        $data["p"]                = $this->Mdokter->getrujukan_pasien($no_pasien, $no_reg);
        // $data["r"]                = $this->Mdokter->getriwayat_pasien_inap($no_reg);
        // $data["rad"]                = $this->Mdokter->getradralan($no_reg);
        // $data["pa"]                = $this->Mdokter->getparalan($no_reg);
        $data["ad"]                = $this->Mdokter->getpasien_igdralan($no_reg);
        $data["q1"]                = $this->Mperawat->cetakassesmen_perawat($no_reg)->row();
        // $data["ob"]                = $this->Mdokter->getapotekralan_resume($no_reg);
        // $data["kp"]             = $this->Mdokter->getstatuspulang();
        // $data["dpjp"]             = $this->Mdokter->getdpjp_poli();
        // $data["k"]              = $this->Mlab->getlab_normal($no_reg);
        // $data["hasil"]          = $this->Mlab->getekspertisilab_detail_array($no_reg);
        $this->load->view('template', $data);
    }
    function rujukan_pasien($no_pasien = "", $no_reg = "", $jenis = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["jenis"]            = $jenis;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rujukan Pasien || RS CIREMAI";
        $data["title_header"]     = "Rujukan Pasien";
        $data["content"]          = "dokter/vrujukan_pasien";
        $data["breadcrumb"]       = "<li class='active'><strong>Rujukan Pasien</strong></li>";
        $data["q"]                = $this->Mdokter->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["p"]                = $this->Mdokter->getrujukan_pasien($no_pasien, $no_reg);
        $data["r"]                = $this->Mdokter->getriwayat_pasien_inap($no_reg);
        $data["rad"]                = $this->Mdokter->getradinap($no_reg);
        $data["pa"]                = $this->Mdokter->getpainap($no_reg);
        $data["ad"]                = $this->Mdokter->getpasien_igdinap($no_reg);
        $data["q1"]                = $this->Mperawat->cetakassesmen_perawat($no_reg)->row();
        $data["ok"]                = $this->Mdokter->getokadetail($no_reg);
        $data["ob"]                = $this->Mdokter->getapotekinap_resume($no_reg);
        $data["kp"]             = $this->Mdokter->getstatuspulang();
        $data["dpjp"]             = $this->Mdokter->getdpjp_poli();
        $data["k"]              = $this->Mlab->getlabinap_normal($no_reg);
        $data["hasil"]          = $this->Mlab->getekspertisilabinap_detail_array($no_reg);
        $this->load->view('template', $data);
    }
    function sebabkematian($no_rm = "", $no_reg = "", $jenis = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["jenis"]            = $jenis;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Sebab Kematian || RS CIREMAI";
        $data["title_header"]     = "Sebab Kematian";
        $data["content"]          = "dokter/vformsebabkematian";
        $data["breadcrumb"]       = "<li class='active'><strong>Sebab Kematian</strong></li>";
        $data["p"]                = $this->Mdokter->getsebab_kematian($no_rm, $no_reg);
        $data["pi"]                = $this->Mdokter->getpasieninap_detail($no_reg);
        $this->load->view('template', $data);
    }
    function sebabkematian_ralan($no_rm = "", $no_reg = "", $jenis = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["jenis"]            = $jenis;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = " Sebab Kematian Ralan || RS CIREMAI";
        $data["title_header"]     = " Sebab Kematian Ralan";
        $data["content"]          = "dokter/vformsebabkematian_ralan";
        $data["breadcrumb"]       = "<li class='active'><strong> Sebab Kematian Ralan</strong></li>";
        $data["p"]                = $this->Mdokter->getsebab_kematian($no_rm, $no_reg);
        $data["pi"]                = $this->Mdokter->getpasienralan_detail($no_reg);
        $this->load->view('template', $data);
    }

    function simpansebabkematian($aksi)
    {
        $jenis           = $this->input->post('jenis');
        $no_reg          = $this->input->post('no_reg');
        $no_rm           = $this->input->post('no_rm');
        $message = $this->Mdokter->simpansebabkematian($aksi);
        $this->session->set_flashdata("message", $message);
        redirect('dokter/sebabkematian/' . $no_rm . "/" . $no_reg . "/" . $jenis);
    }
    function simpansebabkematian_ralan($aksi)
    {
        $jenis           = $this->input->post('jenis');
        $no_reg          = $this->input->post('no_reg');
        $no_rm           = $this->input->post('no_rm');
        $message = $this->Mdokter->simpansebabkematian($aksi);
        $this->session->set_flashdata("message", $message);
        redirect('dokter/sebabkematian_ralan/' . $no_rm . "/" . $no_reg . "/" . $jenis);
    }
    function cetaksebabkematian($no_reg, $no_pasien)
    {
        $data["no_reg"]    = $no_reg;
        $data["no_pasien"] = $no_pasien;
        $data["p"]         = $this->Mdokter->getsebab_kematian($no_rm, $no_reg);
        $data["q2"]        = $this->Mdokter->getpasieninap_detail($no_reg);
        $this->load->view("dokter/vcetaksebabkematian", $data);
    }
    function cetaksebabkematian_ralan($no_reg, $no_pasien)
    {
        $data["no_reg"]    = $no_reg;
        $data["no_pasien"] = $no_pasien;
        $data["p"]         = $this->Mdokter->getsebab_kematian($no_rm, $no_reg);
        $data["q2"]        = $this->Mdokter->getpasienralan_detail($no_reg);
        $this->load->view("dokter/vcetaksebabkematian_ralan", $data);
    }
    function simpanresume_inap($action)
    {
        $message = $this->Mdokter->simpanresume_inap($action);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/resume_inap/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
    }
    function simpanrujukan_pasien($action)
    {
        $message = $this->Mdokter->simpanrujukan_pasien($action);
        $this->session->set_flashdata("message", $message);
        redirect("surat/rujukan_pasien/" . $this->input->post("no_reg") . "/" . $this->input->post("no_pasien") . "/" . $this->input->post("jenis"));
    }
    function simpanrujukan_pasien2($action)
    {
        $message = $this->Mdokter->simpanrujukan_pasien($action);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/rujukan_pasien/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg") . "/" . $this->input->post("jenis"));
    }
    function jawabankonsul_inap($no_pasien = "", $no_reg = "", $iddokter = "", $id_terkait = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "Jawaban Konsul";
        $data["content"]          = "dokter/vjawabankonsul_inap";
        $iddokter                 = $iddokter == "" ? $this->Mdokter->getdokterkonsul_inap($no_reg)->row()->dokter_konsul : $iddokter;
        $id_terkait               = $id_terkait == "" ? $this->Mdokter->getdokterkonsul_inap($no_reg)->row()->id : $id_terkait;
        $data["iddokter"]         = $iddokter;
        $data["id_terkait"]       = $id_terkait;
        $data["breadcrumb"]       = "<li class='active'><strong>Jawaban Konsul</strong></li>";
        $data["dv"]             = $this->Mdokter->getdoktervisit_inap($id_terkait);
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["dokter"]           = $this->Mdokter->getdokterkonsul_inap($no_reg);
        $data["d"] = $this->Mdokter->cetak_konsul($id_terkait);
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
        $message = $this->Mdokter->simpankonsulinap();
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
        $data["kode"]               = $kode;
        $data["q"]                  = $this->Mpendaftaran->getoka_detail($kode);
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
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "Triage";
        $data["content"]          = "pendaftaran/vtriage";
        $data["breadcrumb"]       = "<li class='active'><strong>Triage</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["q1"]           = $this->Mpendaftaran->getpasien_triage($no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["keputusan"]        = $this->Mpendaftaran->getkeputusan();
        $data["dokterigd"]        = $this->Mpendaftaran->getdokterigd();
        $data["petugas_igd"]      = $this->Mpendaftaran->getpetugas_igd();
        $data["diagnosa1"]        = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]         = $this->Mpendaftaran->getanastesi();
        $data["asisten"]          = $this->Mpendaftaran->getasisten();
        $data["tindakan"]         = $this->Mpendaftaran->gettindakan_opi();
        // $data["row"]           = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }
    function ambiltriage()
    {
        $data["q"]              = $this->Mpendaftaran->ambiltriage();
        $data["title"]          = "Triage || RS CIREMAI";
        $data["title_header"]   = "Triage";
        $this->load->view('pendaftaran/vpilih_triage', $data);
    }
    function simpantriage_inap($no_reg = "")
    {
        $message = $this->Mdokter->simpantriage($no_reg);
        $noreg = explode("-", $message);
        $this->session->set_flashdata("message", $message);
        if ($no_reg == "") {
            redirect("pendaftaran/triage_inap/" . $noreg[2] . "/" . $noreg[2]);
        } else {
            redirect("pendaftaran/triage_inap/" . $this->input->post("no_rm") . "/" . $this->input->post("no_reg"));
        }
    }
    function simpanigd($no_reg = "")
    {
        $message = $this->Mdokter->simpanigd($no_reg);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/igd/" . $this->input->post("no_rm") . "/" . $this->input->post("no_reg"));
    }
    function simpanigdralan($no_reg = "")
    {
        $message = $this->Mdokter->simpanigd($no_reg, "nonassesmen");
        $this->session->set_flashdata("message", $message);
        redirect("dokter/igdralan/nonassesmen/" . $this->input->post("no_rm") . "/" . $this->input->post("no_reg"));
    }
    function simpanigdinap($no_reg = "")
    {
        $message = $this->Mdokter->simpanigdinap($no_reg);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/igdinap/" . $this->input->post("no_rm") . "/" . $this->input->post("no_reg"));
    }
    function cetaktriage_inap($no_reg)
    {
        $data["title"]  = "Cetak Inap";
        $data["no_reg"] = $no_reg;
        $data["q"]      = $this->Mpendaftaran->getcetaktriage_inap($no_reg);
        $this->load->view('pendaftaran/vcetaktriage_inap', $data);
    }
    function igd($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "IGD || RS CIREMAI";
        $data["title_header"]     = "Assesment Medic IGD";
        $data["content"]          = "pendaftaran/vigd";
        $data["breadcrumb"]       = "<li class='active'><strong>Assesment Medic IGD</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["q1"]           = $this->Mpendaftaran->getpasien_igd($no_reg);
        $data["t"]            = $this->Mpendaftaran->getpasien_triage($no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["radiologi"]        = $this->Mpendaftaran->gettarif_radiologi();
        $data["lab"]          = $this->Mpendaftaran->gettarif_lab();
        $data["obat"]         = $this->Mpendaftaran->getobat();
        $data["tarif_penunjang_medis"]        = $this->Mpendaftaran->gettarif_penunjang_medis();
        $data["keputusan"]        = $this->Mpendaftaran->getkeputusan();
        $data["dokterigd"]        = $this->Mpendaftaran->getdokterigd();
        $data["petugas_igd"]      = $this->Mpendaftaran->getpetugas_igd();
        $data["diagnosa1"]        = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]         = $this->Mpendaftaran->getanastesi();
        $data["asisten"]          = $this->Mpendaftaran->getasisten();
        $data["tindakan"]         = $this->Mpendaftaran->gettindakan_opi();
        $data["p1"]           = $this->Mdokter->getassesmen_perawat($no_reg);
        // $data["row"]           = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }
    function igdralan($asal, $no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Jalan || RS CIREMAI";
        $data["title_header"]     = "Assesment Medic Rawat Jalan";
        $data["content"]          = "pendaftaran/vigdralan";
        $data["breadcrumb"]       = "<li class='active'><strong>Assesment Medic Rawat Jalan</strong></li>";
        // $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["q1"]           = $this->Mpendaftaran->getpasien_igdralan($no_reg);
        $data["t"]            = $this->Mpendaftaran->getpasien_ralan_detail($no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        // $data["radiologi"]        = $this->Mpendaftaran->gettarif_radiologi();
        $data["lab"]          = $this->Mpendaftaran->gettarif_lab();
        $data["obat"]         = $this->Mpendaftaran->getobat();
        // $data["tarif_penunjang_medis"]        = $this->Mpendaftaran->gettarif_penunjang_medis();
        $data["keputusan"]        = $this->Mpendaftaran->getkeputusan();
        $data["dokterigd"]        = $this->Mpendaftaran->getdokterigd();
        $data["petugas_igd"]      = $this->Mpendaftaran->getpetugas_igd();
        $data["diagnosa1"]        = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]         = $this->Mpendaftaran->getanastesi();
        $data["asisten"]          = $this->Mpendaftaran->getasisten();
        $data["tindakan"]         = $this->Mpendaftaran->gettindakan_opi();
        $data["pwt"]                = $this->Mdokter->getperawat();
        $data["dosis"]                = $this->Mdokter->getdosis();
        $data["nv"]                = $this->Mdokter->getnamavaksin();
        $data["p1"]           = $this->Mdokter->getassesmen_perawat($no_reg, "terimapasien");
        $data["asal"]         = $asal;
        $this->load->view('template', $data);
    }
    function igdinap($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "Assesment Medic IGD Inap";
        $data["content"]          = "pendaftaran/vigd_inap";
        $data["breadcrumb"]       = "<li class='active'><strong>Assesment Medic IGD Inap</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["q1"]           = $this->Mdokter->getpasien_igdinap($no_reg);
        $data["p1"]           = $this->Mdokter->getassesmen_perawat($no_reg);
        $data["t"]            = $this->Mpendaftaran->getpasien_triage($no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["radiologi"]        = $this->Mpendaftaran->gettarif_radiologi();
        $data["lab"]          = $this->Mpendaftaran->gettarif_lab();
        $data["obat"]         = $this->Mpendaftaran->getobat();
        $data["tarif_penunjang_medis"]        = $this->Mpendaftaran->gettarif_penunjang_medis();
        $data["keputusan"]        = $this->Mpendaftaran->getkeputusan();
        $data["dokterigd"]        = $this->Mpendaftaran->getdokterigd();
        $data["petugas_igd"]      = $this->Mpendaftaran->getpetugas_igd();
        $data["diagnosa1"]        = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]         = $this->Mpendaftaran->getanastesi();
        $data["asisten"]          = $this->Mpendaftaran->getasisten();
        $data["tindakan"]         = $this->Mpendaftaran->gettindakan_opi();
        $data["sp"]         = $this->Mdokter->getdokterkonsul_inap($no_reg);
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
    function apotek_igd($no_pasien, $no_reg)
    {
        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "dokter";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                      = "Apotek IGD || RS CIREMAI";
        $data["title_header"]               = "Apotek IGD";
        $data["content"]                    = "pendaftaran/vapotek_igd";
        $data["breadcrumb"]                 = "<li class='active'><strong>Apotek IGD</strong></li>";
        $data["row"]                        = $this->Mapotek->getralan_detail($no_pasien, $no_reg);
        $data["k"]                          = $this->Mapotek->getapotek($no_reg);
        $data["q"]                          = $this->Mapotek->getapotek_detail($no_reg);
        $data["t"]                          = $this->Mapotek->getobat();
        $data["aturan"]                     = $this->Mapotek->getaturan_pakai();
        $data["waktu"]                      = $this->Mapotek->getwaktu_pakai();
        $data["wl"]     = $this->Mapotek->getwaktulainnya();
        $this->load->view('template', $data);
    }
    function apotek_igdralan($no_pasien, $no_reg)
    {
        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "dokter";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["title"]                      = "Apotek Rawat Jalan || RS CIREMAI";
        $data["title_header"]               = "Apotek Rawat Jalan";
        $data["content"]                    = "pendaftaran/vapotek_igdralan";
        $data["breadcrumb"]                 = "<li class='active'><strong>Apotek Rawat Jalan</strong></li>";
        $data["row"]                        = $this->Mapotek->getralan_detail($no_pasien, $no_reg);
        $data["k"]                          = $this->Mapotek->getapotek($no_reg);
        $data["q"]                          = $this->Mapotek->getapotek_detail($no_reg);
        $data["t"]                          = $this->Mapotek->getobat();
        $data["aturan"]                     = $this->Mapotek->getaturan_pakai();
        $data["waktu"]                      = $this->Mapotek->getwaktu_pakai();
        $data["wl"]     = $this->Mapotek->getwaktulainnya();
        $this->load->view('template', $data);
    }
    function apotek_igdinap($no_pasien, $no_reg, $id_dokter, $asal = "assesmen")
    {
        $data["vmenu"]                      = $this->session->userdata("controller") . "/vmenu";
        $data['menu']                       = "dokter";
        $data["no_pasien"]                  = $no_pasien;
        $data["no_reg"]                     = $no_reg;
        $data["id_dokter"]                  = $id_dokter;
        $data["title"]                      = "Apotek IGD || RS CIREMAI";
        $data["title_header"]               = "Apotek IGD";
        $data["content"]                    = "pendaftaran/vapotek_igdinap";
        $data["breadcrumb"]                 = "<li class='active'><strong>Apotek IGD</strong></li>";
        $data["asal"]                       = $asal;
        $data["row"]                        = $this->Mapotek->getinap_detail($no_pasien, $no_reg);
        $data["k"]                          = $this->Mapotek->getapotek_inap($no_reg, $id_dokter);
        $data["q"]                          = $this->Mapotek->getapotek_detail($no_reg);
        $data["t"]                          = $this->Mapotek->getobat();
        $data["aturan"]                     = $this->Mapotek->getaturan_pakai();
        $data["waktu"]                      = $this->Mapotek->getwaktu_pakai();
        $data["wl"]     = $this->Mapotek->getwaktulainnya();
        $this->load->view('template', $data);
    }
    function tambahtriage_inap($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "Form Triage";
        $data["content"]          = "pendaftaran/vformtriage";
        $data["breadcrumb"]       = "<li class='active'><strong>Form Triage</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["q1"]               = $this->Mpendaftaran->getpasien_triage($no_reg);
        $data["dokter"]           = $this->Mpendaftaran->getdokter();
        $data["keputusan"]        = $this->Mpendaftaran->getkeputusan();
        $data["dokterigd"]        = $this->Mpendaftaran->getdokterigd();
        $data["petugas_igd"]      = $this->Mpendaftaran->getpetugas_igd();
        $data["diagnosa1"]        = $this->Mpendaftaran->getdiagnosa();
        $data["anastesi"]         = $this->Mpendaftaran->getanastesi();
        $data["asisten"]          = $this->Mpendaftaran->getasisten();
        $data["tindakan"]         = $this->Mpendaftaran->gettindakan_opi();
        // $data["row"]           = $this->Mpendaftaran->getinapdetail($no_pasien);
        $this->load->view('template', $data);
    }

    function simpanpasientriage_inap($no_reg)
    {
        $message = $this->Mpendaftaran->simpanpasientriage($no_reg);
        $this->session->set_flashdata("message", $message);
        redirect("pendaftaran/rawat_inapdokter");
    }
    function cetaktriage($no_reg = "")
    {
        $data["title"]  = "Cetak Inap";
        $data["no_reg"] = $no_reg;
        $data["q"]      = $this->Mdokter->getcetaktriage($no_reg);
        $this->load->view('pendaftaran/vcetak_triage', $data);
    }
    function cetakigd($no_reg = "")
    {
        $data["title"]  = "Cetak IGD";
        $data["no_reg"] = $no_reg;
        $data["status"] = "igd";
        $data["a"]      = $this->Mapotek->getapotek($no_reg);
        $data["p"]      = $this->Mdokter->getkasir($no_reg);
        $data["q"]      = $this->Mdokter->getcetakigd($no_reg);
        $data["p1"]     = $this->Mdokter->getassesmen_perawat($no_reg)->row();
        $data["k"]      = $this->Mdokter->getkonsul_inap2($no_reg);
        $data["dokter"]      = $this->Mdokter->getdokterarray();
        $this->load->view('pendaftaran/vcetak_igd', $data);
    }
    function cetakigdralan($no_reg = "")
    {
        $data["title"]  = "Cetak Rawan Jalan";
        $data["no_reg"] = $no_reg;
        $data["status"] = "ralan";
        $data["a"]      = $this->Mapotek->getapotek($no_reg);
        $data["p"]      = $this->Mdokter->getkasir($no_reg);
        $data["q"]      = $this->Mdokter->getcetakigd_ralan($no_reg);
        $data["p1"]     = $this->Mdokter->getassesmen_perawat($no_reg, "terimapasien")->row();
        $data["k"]      = $this->Mdokter->getkonsul_inap2($no_reg);
        $data["dokter"]      = $this->Mdokter->getdokterarray();
        $this->load->view('pendaftaran/vcetak_igd', $data);
    }
    function cetakigdinap($no_reg = "", $id_dokter = "")
    {
        $data["title"]  = "Cetak Inap";
        $data["no_reg"] = $no_reg;
        $data["id_dokter"] = $id_dokter;
        $data["status"] = "ranap";
        $data["r"]      = $this->Mdokter->getpasienruangan($no_reg);
        $data["a"]      = $this->Mapotek->getapotekigd_inap($no_reg, $id_dokter);
        $data["p"]      = $this->Mdokter->getkasir_inap($no_reg);
        $data["p1"]     = $this->Mdokter->getassesmen_perawat($no_reg)->row();
        $data["q"]      = $this->Mdokter->getcetakigd_inap($no_reg);
        $data["k"]      = $this->Mdokter->getkonsul_inap2($no_reg);
        $data["dokter"]      = $this->Mdokter->getdokterarray();
        $this->load->view('pendaftaran/vcetak_igd', $data);
    }
    function detaillab_ralan($no_pasien, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "dokter";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]          = "Detail Lab Rawat Jalan || RS CIREMAI";
        $data["title_header"]   = "Detail Lab Rawat Jalan";
        $data["content"]        = "dokter/vformlab_ralan";
        $data["breadcrumb"]     = "<li class='active'><strong>Detail Lab Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mlab->getralan_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mlab->getkasir($no_reg);
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
    function detailradiologi_ralan($no_pasien, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "dokter";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]          = "Detail Radiologi Rawat Jalan || RS CIREMAI";
        $data["title_header"]   = "Detail Radiologi Rawat Jalan";
        $data["content"]        = "dokter/vformradiologi_ralan";
        $data["breadcrumb"]     = "<li class='active'><strong>Detail Radiologi Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mradiologi->getralan_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mradiologi->getkasir($no_reg);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]              = $this->Mradiologi->gettarif_radiologi();
        $data["d"]              = $this->Mradiologi->getdokter_radiologi();
        $data["d1"]             = $this->Mradiologi->getdokter();
        $data["r"]              = $this->Mradiologi->getradiografer();
        $data["dokter"]         = $this->Mradiologi->getdokter_array();
        $data["radiografer"]    = $this->Mradiologi->getradiografer_array();
        $data["dokter_pengirim"] = $this->Mradiologi->getdokterpengirim_array();
        $this->load->view('template', $data);
    }
    function detailpa_ralan($no_pasien, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "dokter";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]          = "Detail Patologi Anatomi Rawat Jalan || RS CIREMAI";
        $data["title_header"]   = "Detail Patologi Anatomi Rawat Jalan";
        $data["content"]        = "dokter/vformpa_ralan";
        $data["breadcrumb"]     = "<li class='active'><strong>Detail Pa Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mpa->getralan_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mpa->getkasir($no_reg);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]              = $this->Mpa->gettarif_pa();
        $data["d"]              = $this->Mpa->getdokter_pa();
        $data["d1"]             = $this->Mpa->getdokter();
        $data["r"]              = $this->Mpa->getpetugaspa();
        $data["dokter"]         = $this->Mpa->getdokter_array();
        $data["petugas_pa"]    = $this->Mpa->getpetugas_array();
        $data["dokter_pengirim"] = $this->Mpa->getdokterpengirim_array();
        $this->load->view('template', $data);
    }
    function detailgizi_ralan($no_pasien, $no_reg)
    {
        $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
        $data['menu']           = "dokter";
        $data["no_pasien"]      = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]          = "Detail Gizi Rawat Jalan || RS CIREMAI";
        $data["title_header"]   = "Detail Gizi Rawat Jalan";
        $data["content"]        = "dokter/vformgizi_ralan";
        $data["breadcrumb"]     = "<li class='active'><strong>Detail Gizi Rawat Jalan</strong></li>";
        $data["row"]            = $this->Mgizi->getralan_detail($no_pasien, $no_reg);
        $data["k"]              = $this->Mgizi->getkasir($no_reg);
        $data["q"]              = $this->Mkasir->getkasir_detail($no_reg);
        $data["t"]              = $this->Mgizi->gettarif_gizi();
        $data["d"]              = $this->Mgizi->getdokter_gizi();
        $data["d1"]             = $this->Mgizi->getdokter();
        $data["r"]              = $this->Mgizi->getpetugasgizi();
        $data["dokter"]         = $this->Mgizi->getdokter_array();
        $data["petugas_gizi"]    = $this->Mgizi->getpetugas_array();
        $data["dokter_pengirim"] = $this->Mgizi->getdokterpengirim_array();
        $this->load->view('template', $data);
    }
    function cppt($no_pasien = "", $no_reg = "", $tgl1 = "", $tgl2 = "", $status = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "CPPT";
        $data["content"]          = "dokter/vcppt";
        $tgl1                     = $tgl1 == "" ? date("Y-m-d") : $tgl1;
        $tgl2                     = $tgl2 == "" ? date("Y-m-d") : $tgl2;
        $data["tanggal1"]         = date("d-m-Y", strtotime($tgl1));
        $data["tanggal2"]         = date("d-m-Y", strtotime($tgl2));
        $data["breadcrumb"]       = "<li class='active'><strong>CPPT</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["c"]           = $this->Mcppt->viewcppt($no_reg, $tgl1, $tgl2, $status);
        $this->load->view('template', $data);
    }
    function cppt_ralan($no_pasien = "", $no_reg = "", $tgl1 = "", $tgl2 = "", $status = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Rawat Inap || RS CIREMAI";
        $data["title_header"]     = "CPPT";
        $data["content"]          = "dokter/vcppt_ralan";
        $tgl1                     = $tgl1 == "" ? date("Y-m-d") : $tgl1;
        $tgl2                     = $tgl2 == "" ? date("Y-m-d") : $tgl2;
        $data["tanggal1"]         = date("d-m-Y", strtotime($tgl1));
        $data["tanggal2"]         = date("d-m-Y", strtotime($tgl2));
        $data["breadcrumb"]       = "<li class='active'><strong>CPPT</strong></li>";
        $data["q"]                = $this->Mpendaftaran->getlaporan_tindakan($no_pasien, $no_reg);
        $data["c"]           = $this->Mcppt->viewcppt_ralan($no_pasien, $tgl1, $tgl2);
        $this->load->view('template', $data);
    }
    function simpanwaktu()
    {
        $this->Mapotek->simpanwaktu_ralan();
        $this->session->set_flashdata("message", "success-Obat berhasil disimpan");
        redirect("dokter/apotek_igd/" . $this->input->post("no_rm") . "/" . $this->input->post("no_reg"));
    }
    function simpanwaktu_inap()
    {
        $this->Mapotek->simpanwaktu_inap();
        $this->session->set_flashdata("message", "success-Obat berhasil disimpan");
        redirect("dokter/apotek_igdinap/" . $this->input->post("no_rm") . "/" . $this->input->post("no_reg") . "/" . $this->input->post("iddokter"));
    }
    function getfarmasiobat()
    {
        echo json_encode($this->Mdokter->getfarmasiobat());
    }
    function addobat()
    {
        $this->Mdokter->addobat();
        $this->session->set_flashdata("message", "success-Obat berhasil ditambahkan");
        redirect("dokter/apotek_igd/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
    }
    function addobat_ralan()
    {
        $this->Mdokter->addobat();
        $this->session->set_flashdata("message", "success-Obat berhasil ditambahkan");
        redirect("dokter/apotek_igdralan/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
    }
    function addobat_inap()
    {
        $this->Mdokter->addobat_inap();
        $this->session->set_flashdata("message", "success-Obat berhasil ditambahkan");
        redirect("dokter/apotek_igdinap/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg") . "/" . $this->input->post("iddokter"));
    }
    function cekpasien()
    {
        $this->db->select("pr.no_pasien,pr.no_reg,p.nama_pasien");
        $this->db->where("pr.no_reg", $this->input->post("no_reg"));
        $this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
        $q = $this->db->get("pasien_ralan pr");
        echo json_encode($q->row());
    }
    function cekpasien_inap()
    {
        $this->db->select("pr.no_rm,pr.no_reg,p.nama_pasien,pr.tgl_keluar");
        $this->db->where("pr.no_reg", $this->input->post("no_reg"));
        $this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
        $q = $this->db->get("pasien_inap pr");
        echo json_encode($q->row());
    }
    function migrasi_ralan()
    {
        $this->Mdokter->migrasi();
        $this->session->set_flashdata("message", "success-No reg " . $this->input->post("no_reg") . " berhasil dimigrasi");
    }
    function migrasi_inap()
    {
        $this->Mdokter->migrasi_inap();
        $this->session->set_flashdata("message", "success-No reg " . $this->input->post("no_reg") . " berhasil dimigrasi");
    }
    function getpdf()
    {
        if ($this->input->post("jenis") == "ralan") {
            $file = base_url() . "file_pdf/ralan/" . $this->input->post("file_pdf");
        } else {
            $file = base_url() . "file_pdf/inap/" . $this->input->post("file_pdf");
        }
        echo $file;
    }
    function simpantambahkonsul_inap()
    {
        $this->Mdokter->simpantambahkonsul_inap();
        $this->session->set_flashdata("message", "success-Konsul No reg " . $this->input->post("no_reg") . " berhasil ditambahkan");
        redirect("dokter/konsul_inap/" . $this->input->post("no_rm_tambah") . "/" . $this->input->post("no_reg_tambah") . "/" . $this->input->post("doktersp_tambah") . "/" . $this->input->post("id_terkait"));
    }
    function simpantambahvisit_inap()
    {
        $this->Mdokter->simpantambahvisit_inap();
        $this->session->set_flashdata("message", "success-Konsul No reg " . $this->input->post("no_reg") . " berhasil ditambahkan");
        redirect("dokter/visit_inap/" . $this->input->post("no_rm_tambah") . "/" . $this->input->post("no_reg_tambah") . "/" . $this->input->post("dokter_visit"));
    }
    function editkonsul()
    {
        $q = $this->db->get_where("riwayat_pasien_inap", ["id" => $this->input->post("id")]);
        echo json_encode($q->row());
    }
    function simpanjawabkonsul_inap()
    {
        $this->Mdokter->simpanjawabkonsul_inap();
        $this->session->set_flashdata("message", "success-Konsul No reg " . $this->input->post("no_reg") . " berhasil ditambahkan");
        redirect("dokter/konsul_inap/" . $this->input->post("no_rm_tambah") . "/" . $this->input->post("no_reg_tambah") . "/" . $this->input->post("doktersp_tambah") . "/" . $this->input->post("id_terkait"));
    }
    function hapuskonsul()
    {
        $this->db->where("id", $this->input->post("id"));
        $q = $this->db->delete("riwayat_pasien_inap");
    }
    function terapi_inap()
    {
        $no_reg = $this->input->post("no_reg");
        $iddokter = $this->input->post("iddokter");
        $tgl = $this->input->post("tanggal");
        $q = $this->Mdokter->getapotek_inap($no_reg, $iddokter, $tgl);
        echo json_encode($q->result());
    }
    function addobat_terapi_inap()
    {
        $this->Mdokter->addobat_inap();
        $this->session->set_flashdata("message", "success-Obat berhasil ditambahkan");
    }
    function aturan_pakai()
    {
        $q = $this->Mapotek->getaturan_pakai();
        echo json_encode($q->result());
    }
    function waktu_pakai()
    {
        $q = $this->Mapotek->getwaktu_pakai();
        echo json_encode($q->result());
    }
    function waktu_lain()
    {
        $q = $this->Mapotek->getwaktulainnya();
        echo json_encode($q->result());
    }
    function sessionfull()
    {
        $this->session->set_userdata("full", $this->input->post("full"));
    }
    function hapusobat()
    {
        $this->db->where("id", $this->input->post("id"));
        $q = $this->db->delete("apotek_inap");
    }
    function editobat()
    {
        $this->db->where("id", $this->input->post("id"));
        $data = array(
            "aturan_pakai" => $this->input->post("aturan_pakai"),
            "waktu" => $this->input->post("waktu"),
            "pagi" => $this->input->post("pagi"),
            "siang" => $this->input->post("siang"),
            "sore" => $this->input->post("sore"),
            "malem" => $this->input->post("malem"),
            "waktu_lainnya" => $this->input->post("waktu_lainnya"),
            "qty" => $this->input->post("qty"),
            "jumlah" => ($this->input->post("qty") * $this->input->post("jumlah")),
        );
        $q = $this->db->update("apotek_inap", $data);
    }
    function cetak_konsul($id)
    {
        $data["d"] = $this->Mdokter->cetak_konsul($id);
        $this->load->view('dokter/vcetakkonsul', $data);
    }
    function hapusvisit()
    {
        $this->db->delete("riwayat_pasien_inap", ["id" => $this->input->post("id")]);
    }
    function cetakresumeinap($no_pasien, $no_reg)
    {
        $data["no_pasien"]  = $no_pasien;
        $data["no_reg"]     = $no_reg;
        $data["q"]                = $this->Mdokter->getlaporan_tindakaninap($no_pasien, $no_reg);
        $data["p"]                = $this->Mdokter->getresume_pulang($no_pasien, $no_reg);
        $data["r"]                = $this->Mdokter->getriwayat_pasien_inap($no_reg);
        $data["rad"]                = $this->Mdokter->getradinap($no_reg);
        $data["pa"]                = $this->Mdokter->getpainap($no_reg);
        $data["ad"]                = $this->Mdokter->getpasien_igdinap($no_reg);
        $data["q1"]     = $this->Mperawat->cetakassesmen_perawat($no_reg)->row();
        $data["ok"]                = $this->Mdokter->getokadetail($no_reg);
        $data["ob"]                = $this->Mdokter->getapotekinap_resume($no_reg);
        $data["kp"]             = $this->Mdokter->getstatuspulang();
        $data["dpjp"]             = $this->Mdokter->getdpjp_poli();
        $data["k"]              = $this->Mlab->getlabinap_normal($no_reg);
        $data["hasil"]          = $this->Mlab->getekspertisilabinap_detail_array($no_reg);
        $data["s"]  = $this->Mdokter->getpersetujuan($no_reg);
        $this->load->view('dokter/vcetakresumeinap', $data);
    }
    function cetakrujukanpasien($no_reg, $no_pasien, $jenis = "")
    {
        $data["no_pasien"]  = $no_pasien;
        $data["no_reg"]     = $no_reg;
        if ($jenis == "ralan") {
            $data["q"]                = $this->Mdokter->getlaporan_tindakan($no_pasien, $no_reg);
            $data["p"]                = $this->Mdokter->getrujukan_pasien($no_pasien, $no_reg);
            $data["r"]                = $this->Mdokter->getriwayat_pasien_inap($no_reg);
            $data["rad"]                = $this->Mdokter->getradralan($no_reg);
            $data["pa"]                = $this->Mdokter->getparalan($no_reg);
            $data["ad"]                = $this->Mdokter->getpasien_igdralan($no_reg);
            $data["q1"]                = $this->Mperawat->cetakassesmen_perawat($no_reg)->row();
            $data["ok"]                = $this->Mdokter->getokadetail($no_reg);
            $data["ob"]                = $this->Mdokter->getapotekralan_resume($no_reg);
            $data["kp"]             = $this->Mdokter->getstatuspulang();
            $data["dpjp"]             = $this->Mdokter->getdpjp_poli();
            $data["k"]              = $this->Mlab->getlab_normal($no_reg);
            $data["hasil"]          = $this->Mlab->getekspertisilab_detail_array($no_reg);
            $this->load->view('dokter/vcetakrujukanpasien_ralan', $data);
        } else {
            $data["q"]                = $this->Mdokter->getlaporan_tindakaninap($no_pasien, $no_reg);
            $data["p"]                = $this->Msurat->getrujukan_pasien($no_reg);
            $data["r"]                = $this->Mdokter->getriwayat_pasien_inap($no_reg);
            $data["rad"]               = $this->Mdokter->getradinap($no_reg);
            $data["pa"]                = $this->Mdokter->getpainap($no_reg);
            $data["ad"]                = $this->Mdokter->getpasien_igdinap($no_reg);
            $data["q1"]                = $this->Mperawat->cetakassesmen_perawat($no_reg)->row();
            $data["ok"]                = $this->Mdokter->getokadetail($no_reg);
            $data["ob"]                = $this->Mdokter->getapotekinap_resume($no_reg);
            $data["kp"]             = $this->Mdokter->getstatuspulang();
            $data["dpjp"]             = $this->Mdokter->getdpjp_poli();
            $data["k"]              = $this->Mlab->getlabinap_normal($no_reg);
            $data["hasil"]          = $this->Mlab->getekspertisilabinap_detail_array($no_reg);
            $this->load->view('dokter/vcetakrujukanpasien', $data);
        }
    }
    function pengantar_terapi($no_pasien, $no_reg)
    {
        $data["vmenu"]             = $this->session->userdata("controller") . "/vmenu";
        $data['menu']              = "dokter";
        $data["no_pasien"]         = $no_pasien;
        $data["no_reg"]         = $no_reg;
        $data["title"]            = "Surat Pengantar Terapi/ Tindakan || RS CIREMAI";
        $data["title_header"]     = "Surat Pengantar Terapi/ Tindakan";
        $data["content"]         = "dokter/vpengantarterapi";
        $data["breadcrumb"]       = "<li class='active'><strong>Surat Pengantar Terapi/ Tindakan</strong></li>";
        $data["row"]            = $this->Mpendaftaran->getdetailpasien($no_pasien);
        $data["q"]            = $this->Mdokter->getpengantar_terapi($no_reg);
        $this->load->view('template', $data);
    }
    function simpanpengantarterapi($aksi)
    {
        $message = $this->Mdokter->simpanpengantarterapi($aksi);
        $this->session->set_flashdata("message", $message);
        redirect("dokter/pengantar_terapi/" . $this->input->post("no_rm") . "/" . $this->input->post("no_reg"));
    }
    function radioterapi($no_pasien = "", $no_reg = "")
    {
        $data["no_reg"]           = $no_reg;
        $data["no_pasien"]        = $no_pasien;
        $data["vmenu"]            = $this->session->userdata("controller") . "/vmenu";
        $data['menu']             = "dokter";
        $data["title"]            = "Form Radioterapi || RS CIREMAI";
        $data["title_header"]     = "Form Radioterapi ";
        $data["content"]          = "dokter/vradioterapi";
        $data["breadcrumb"]       = "<li class='active'><strong>Form Radioterapi</strong></li>";
        $data["q"]                = $this->Mdokter->getradioterapi_detail($no_reg);
        $data["q1"]               = $this->Mdokter->getpasien_igdralan($no_reg);
        $data["jenis"]             = $this->Mdokter->getitemradioterapi();
        $this->load->view('template', $data);
    }
    function simpanradioterapi($aksi)
    {
        $no_reg          = $this->input->post('no_reg');
        $no_pasien       = $this->input->post('no_pasien');
        $message = $this->Mdokter->simpanradioterapi($aksi);
        $this->session->set_flashdata("message", $message);
        redirect('dokter/radioterapi/' . $no_pasien . "/" . $no_reg);
    }
}
