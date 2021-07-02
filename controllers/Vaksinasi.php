<?php
class Vaksinasi extends CI_Controller{
    function __construct(){
        parent::__construct();
    }
    function index(){
        $this->load->view('vvaksinasi');
    }
    function vaksin(){
        $this->load->view('vvaksin');
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
    function simpan_pasien(){
      $this->db->select("p.*,pr.no_antrian,pr.tgl_vaksin as tgl,a.tempat,a.alamat,a.maps");
      $this->db->join("pasien_ralan_vaksin pr","pr.no_pasien=p.no_pasien","inner");
      $this->db->join("tempat_vaksin a","a.id=pr.tempat_vaksin","inner");
      $q = $this->db->get_where("pasien_vaksin p",["p.nik"=>$this->input->post("nik"),"p.nohp"=>$this->input->post("nohp")]);
      if ($q->num_rows()>0) {
        $row["ada"] = "ada";
        $data = $q->row();
        $row["list"] = $data;
        $q = $this->db->get_where("jadwal_vaksin",["dari<="=>(int)($data->no_antrian),"sampai>="=>(int)($data->no_antrian)]);
        $row["jam"] = $q->row()->jam;
        echo json_encode($row);
      } else {
        $tgl_lahir = date("Y-m-d",strtotime($this->input->post("tgl_lahir")));
        $lahir = new DateTime($tgl_lahir);
        $hari_ini = new DateTime();
        $diff = $hari_ini->diff($lahir);
        $umur = $diff->y;
        if ($umur>=18){
          $no_pasien = $this->getno_pasien_baru();
          $data = array(
                  "no_pasien" => $no_pasien,
                  "nama_pasien" => strtoupper($this->input->post("nama_pasien")),
                  "tgl_lahir" => date("Y-m-d",strtotime($this->input->post("tgl_lahir"))),
                  "nohp" => $this->input->post("nohp"),
                  "nik" => $this->input->post("nik"),
                  "jk" => $this->input->post("jk"),
                  "id_desa" => $this->input->post("desa"),
                  "id_kecamatan" => $this->input->post("kecamatan"),
                  "id_kota" => $this->input->post("kotakabupaten"),
                  "id_propinsi" => $this->input->post("propinsi"),
                  "alamat" => $this->input->post("alamat")
                );
          $this->db->insert("pasien_vaksin",$data);
          $no = $this->getnoantrian($this->input->post("tempat_vaksin"));
          $data = array(
            "no_reg" => date("YmdHis"),
            "no_pasien" => $no_pasien,
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
          $q = $this->db->get_where("jadwal_vaksin",["dari<="=>(int)($no["nourut"]),"sampai>="=>(int)($no["nourut"])]);
          $row["jam"] = $q->row()->jam;
          echo json_encode($row);
        } else echo "false";
      }
    }
    function getno_pasien_baru()
    {
        for ($i = 1; $i <= 300000; $i++) {
            $n = substr("000000" . $i, -6, 6);
            $q = $this->db->get_where("pasien_vaksin", array("no_pasien" => $n));

            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function getnoantrian($tempat_vaksin)
    {
        $no = array();
        $tgl = date("Y-m-d");
        $q = $this->db->get_where("tempat_vaksin",["id"=>$tempat_vaksin])->row();
        $maks = $q->maks;
        for ($i = 1; $i <= $maks; $i++) {
          $tgl = date("Y-m-d",strtotime($tgl." +1 days"));
          $libur = (int)(date("w",strtotime($tgl)));
          $b = $this->db->get_where("pasien_ralan_vaksin", array("tempat_vaksin"=>$tempat_vaksin,"tgl_vaksin"=>date("Y-m-d",strtotime($tgl))));
          if ($b->num_rows()<$maks && $libur != 6 && $libur !=0){
            break;
          }
        }
        for ($i = 1; $i <= $maks; $i++) {
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
    function gettempatvaksin(){
      $q = $this->db->get_where("tempat_vaksin",["status"=>1]);
      $data = array();
      $data[] = array("id"=>'',"text"=>'');
      foreach ($q->result() as $key) {
        $data[] = array("id"=>$key->id,"text"=>$key->tempat);
      }
      echo json_encode($data);
    }
    function cetakvaksin($no_pasien){
      $this->db->select("p.*,pr.no_antrian,pr.tgl_vaksin as tgl,a.tempat,a.alamat,a.maps");
      $this->db->join("pasien_ralan_vaksin pr","pr.no_pasien=p.no_pasien","inner");
      $this->db->join("tempat_vaksin a","a.id=pr.tempat_vaksin","inner");
      $q = $this->db->get_where("pasien_vaksin p",["p.no_pasien"=>$no_pasien]);
      $row = array();
      $n = $q->row();
      $row["list"] = $n;
      $q = $this->db->get_where("jadwal_vaksin",["dari<="=>$n->no_antrian,"sampai>="=>$n->no_antrian]);
      $row["jam"] = $q->row()->jam;
      $data["val"] = $row;
      $this->load->view('vcetakvaksin',$data);
    }
}
?>
