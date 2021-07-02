<?php
class Whatsapp extends CI_Controller {
  function __construct()
  {
      parent::__construct();
      $this->load->Model('Mpendaftaran');
  }
  function formskrining_vaksin($no_pasien = "", $no_reg = ""){
    $data["vmenu"]          = $this->session->userdata("controller") . "/vmenu";
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
      redirect("whatsapp/formskrining_vaksin/" . $this->input->post("no_pasien") . "/" . $this->input->post("no_reg"));
  }
  function cetakskrining_vaksin($no_pasien = "", $no_reg = ""){
    $data["no_reg"]         = $no_reg;
    $data["no_pasien"]      = $no_pasien;
    $data["q1"]             = $this->Mpendaftaran->getpasien_skrining($no_reg);
    $data["q"]              = $this->Mpendaftaran->getskrining_detail($no_reg);
    $data["p"]              = $this->Mpendaftaran->getdokterperawat();
    $this->load->view('pendaftaran/vcetakskrinningvaksin',$data);
  }
}
