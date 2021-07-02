<?php

class Mperawat extends CI_Model{
  function __construct()
  {
    parent::__construct();
  }
  function getperawat($jenis="",$no_reg=""){
    if ($jenis=="igd"){
      $this->db->where("bagian","igd");
    } else
    if ($jenis=="poliklinik"){
      $this->db->select("tujuan_poli");
      $p = $this->db->get_where("pasien_ralan",["no_reg"=>$no_reg])->row();
      $this->db->where("bagian",$p->tujuan_poli);
    } else
    if ($jenis=="ranap"){
      $kode_bagian = "";
      $this->db->select("kode_bagian");
      $this->db->join("ruangan r","r.kode_ruangan=p.kode_ruangan","inner");
      $n = $this->db->get_where("pasien_inap p",["no_reg"=>$no_reg]);
      if ($n->num_rows()>0) {
        $n = $n->row();
        $kode_bagian = $n->kode_bagian;
      }
      $this->db->where("bagian",$kode_bagian);
    }
    $q = $this->db->get("perawat");
    return $q;
  }
  function getperawat_detail($id_perawat){
    $this->db->where("id_perawat", $id_perawat);
    $q = $this->db->get("perawat");
    return $q->row();
  }
  function getpuskesmas(){
    $this->db->order_by("id_kecamatan,id_puskesmas","asc");
    $query = $this->db->get("puskesmas");
    return $query;
  }
  function getuser($id){
    $q = $this->db->get_where("user",array("nip"=>$id));
    return $q;
  }
  function simpanperawat($aksi){
    $nama_file = str_replace('data:image/jpg;base64,', '', $this->input->post("source_foto"));
    $nama_photo = str_replace('data:image/jpg;base64,', '', $this->input->post("source_photo"));
    switch ($aksi) {
      case 'simpan' :
      $data = array(
        "id_perawat" => $this->input->post('id_perawat'),
        "nama_perawat" => $this->input->post('nama_perawat'),
        "no_telepon" => $this->input->post('no_telepon'),
        "alamat" => $this->input->post('alamat'),
        "bagian" => $this->input->post('bagian'),
        "ttd" => $nama_file,
        "photo" => $nama_photo
      );
      $q = $this->getperawat_detail($this->input->post('id_perawat'));
      if ($q) {
        $msg  = "danger-Data Perawat sudah ada sebelumnya";
      } else {
        $this->db->insert("perawat",$data);
        $msg  = "success-Data Perawat berhasil di simpan";
      }
      break;
      case 'edit' :
      $data = array(
        "nama_perawat" => $this->input->post('nama_perawat'),
        "no_telepon" => $this->input->post('no_telepon'),
        "alamat" => $this->input->post('alamat'),
        "bagian" => $this->input->post('bagian'),
        "ttd" => $nama_file,
        "photo" => $nama_photo
      );
      $this->db->where("id_perawat",$this->input->post('id_perawat'));
      $this->db->update("perawat",$data);
      $msg  = "success-Data Perawat berhasil di ubah";
      break;
    }
    if ($this->input->post('password')!=""){
      $data = array("password"=>md5($this->input->post('password')));
      $this->db->where("id_perawat",$this->input->post('id_perawat'));
      $this->db->update("perawat",$data);
    }
    return $msg;
  }
  function hapusperawat($id_perawat){
    $this->db->where("id_perawat",$id_perawat);
    $this->db->delete("perawat");
    return "danger-Data Perawat berhasil di hapus";
  }
  function getbagian(){
    return $this->db->get("bagian");
  }
  function getpasienralan($page,$offset){
    $kode_kelas = $this->session->userdata("kode_kelas");
    $kode_ruangan = $this->session->userdata("kode_ruangan");
    $tgl1 = $this->session->userdata("tgl1");
    $tgl2 = $this->session->userdata("tgl2");
    $no_pasien = $this->session->userdata("no_pasien");
    $no_reg = $this->session->userdata("no_reg");
    $nama = $this->session->userdata("nama");
    $this->db->select("i.*,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan, p.nama_pasien as nama_pasien, g.keterangan as gol_pasien");
    $this->db->group_start();
    $this->db->like("i.no_pasien",$no_pasien);
    $this->db->or_like("i.no_reg",$no_pasien);
    $this->db->or_like("p.nama_pasien",$no_pasien);
    $this->db->group_end();
    if ($tgl1!="" OR $tgl2!="") {
      $this->db->where("date(i.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
      $this->db->where("date(i.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
    }
    $this->db->where("tujuan_poli!=", "0102030");
    $this->db->join("pasien p","p.no_pasien=i.no_pasien","left");
    $this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
    $this->db->join("poliklinik pol","pol.kode=i.dari_poli","left");
    $this->db->join("poliklinik pol2","pol2.kode=i.tujuan_poli","left");
    $this->db->order_by("no_reg","desc");
    $this->db->order_by("no_reg,no_pasien");
    $query = $this->db->get("pasien_ralan i",$page,$offset);
    return $query;
  }
  function totalpasienralan(){
    $kode_kelas = $this->session->userdata("kode_kelas");
    $kode_ruangan = $this->session->userdata("kode_ruangan");
    $tgl1 = $this->session->userdata("tgl1");
    $tgl2 = $this->session->userdata("tgl2");
    $no_pasien = $this->session->userdata("no_pasien");
    $no_reg = $this->session->userdata("no_reg");
    $nama = $this->session->userdata("nama");
    $this->db->select("i.*");
    $this->db->group_start();
    $this->db->like("i.no_pasien",$no_pasien);
    $this->db->or_like("no_reg",$no_pasien);
    $this->db->or_like("nama_pasien",$no_pasien);
    $this->db->group_end();
    if ($tgl1!="" OR $tgl2!="") {
      $this->db->where("date(i.tanggal)>=",date("Y-m-d",strtotime($tgl1)));
      $this->db->where("date(i.tanggal)<=",date("Y-m-d",strtotime($tgl2)));
    }
    $this->db->where("tujuan_poli!=", "0102030");
    $this->db->order_by("no_reg","desc");
    $this->db->order_by("no_reg,no_pasien");
    $query = $this->db->get("pasien_ralan i");
    return $query->num_rows();
  }
  function getpasieninap($page,$offset){
    $kode_kelas = $this->session->userdata("kode_kelas");
    $kode_ruangan = $this->session->userdata("kode_ruangan");
    $tgl1 = $this->session->userdata("tgl1");
    $tgl2 = $this->session->userdata("tgl2");
    $no_pasien = $this->session->userdata("no_pasien");
    $no_reg = $this->session->userdata("no_reg");
    $nama = $this->session->userdata("nama");
    $indeks = $this->session->userdata("indeks");
    $this->db->select("i.*,p.nama_pasien,o.kode_oka, r.nama_ruangan,k.nama_kelas,p.alamat,p.no_bpjs, g.keterangan as gol_pasien");
    $this->db->group_start();
    $this->db->like("i.no_rm",$no_pasien);
    $this->db->or_like("i.no_reg",$no_pasien);
    $this->db->or_like("no_bpjs",$no_pasien);
    $this->db->or_like("no_sjp",$no_pasien);
    $this->db->or_like("p.nama_pasien",$no_pasien);
    $this->db->or_like("p.nip",$this->session->userdata("no_pasien"));
    $this->db->or_like("p.ktp",$this->session->userdata("no_pasien"));
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
    if ($indeks!="") {
      $this->db->where("i.no_reg IS NULL");
    }
    $this->db->join("pasien p","p.no_pasien=i.no_rm");
    $this->db->join("oka o","o.no_reg=i.no_reg","left");
    $this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
    $this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
    $this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
    $this->db->order_by("i.no_reg,i.no_rm","desc");
    $query = $this->db->get("pasien_inap i",$page,$offset);
    return $query;
  }
  function totalpasieninap(){
    $kode_kelas = $this->session->userdata("kode_kelas");
    $kode_ruangan = $this->session->userdata("kode_ruangan");
    $tgl1 = $this->session->userdata("tgl1");
    $tgl2 = $this->session->userdata("tgl2");
    $no_pasien = $this->session->userdata("no_pasien");
    $no_reg = $this->session->userdata("no_reg");
    $nama = $this->session->userdata("nama");
    $this->db->select("i.*,p.nama_pasien,r.nama_ruangan,k.nama_kelas, g.keterangan as gol_pasien");
    $this->db->group_start();
    $this->db->like("i.no_rm",$no_pasien);
    $this->db->or_like("no_reg",$no_pasien);
    $this->db->or_like("no_bpjs",$no_pasien);
    $this->db->or_like("no_sjp",$no_pasien);
    $this->db->or_like("p.nama_pasien",$no_pasien);
    $this->db->or_like("p.nip",$this->session->userdata("no_pasien"));
    $this->db->or_like("p.ktp",$this->session->userdata("no_pasien"));
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
    $this->db->order_by("no_reg","desc");
    $this->db->join("pasien p","p.no_pasien=i.no_rm");
    $this->db->join("gol_pasien g","g.id_gol=p.id_gol","left");
    $this->db->join("ruangan r","r.kode_ruangan=i.kode_ruangan","left");
    $this->db->join("kelas k","k.kode_kelas=i.kode_kelas","left");
    $this->db->order_by("no_reg,no_rm");
    $query = $this->db->get("pasien_inap i");
    return $query->num_rows();
  }
  function getpasienralan_detail($no_reg){
    $this->db->select("pi.*,pr.dokter_poli,p.no_pasien,p.alergi, p.nama_pasien as nama_pasien1,p.tgl_lahir");
    $this->db->join("pasien p","p.no_pasien = pr.no_pasien","left");
    $this->db->join("pasien_igd pi","p.no_pasien = pi.no_rm","left");
    $this->db->where("pr.no_reg",$no_reg);
    $this->db->where("pr.tujuan_poli!=","0102030");
    $q = $this->db->get("pasien_ralan pr");
    return $q->row();
  }
  function soap_perawat(){
    return $this->db->get("soap_perawat");
  }
  function getassesmen_perawat($jenis,$no_reg=""){
    $this->db->order_by("tanggal,jam","desc");
    $q = $this->db->get_where("assesmen_perawat",["no_reg"=>$no_reg,"jenis"=>$jenis]);
    $t = $koma = "";
    foreach($q->result() as $row){
      $this->session->set_userdata("s".$row->id,$row->s);
      $this->session->set_userdata("o".$row->id,$row->o);
      $this->session->set_userdata("a".$row->id,$row->a);
      $this->session->set_userdata("p".$row->id,$row->p);
      $this->session->set_userdata("tujuan".$row->id,$row->tujuan);
      $this->session->set_userdata("tgl".$row->id,$row->tanggal);
      $this->session->set_userdata("jam".$row->id,$row->jam);
      $this->session->set_userdata("td".$row->id,$row->td);
      $this->session->set_userdata("td2".$row->id,$row->td2);
      $this->session->set_userdata("nadi".$row->id,$row->nadi);
      $this->session->set_userdata("respirasi".$row->id,$row->respirasi);
      $this->session->set_userdata("suhu".$row->id,$row->suhu);
      $this->session->set_userdata("spo2".$row->id,$row->spo2);
      $this->session->set_userdata("bb".$row->id,$row->bb);
      $this->session->set_userdata("shift".$row->id,$row->shift);
      $this->session->set_userdata("tb".$row->id,$row->tb);
      $this->session->set_userdata("shift".$row->id,$row->shift);
      $this->session->set_userdata("situasional".$row->id,$row->situasional);
      $this->session->set_userdata("medis".$row->id,$row->medis);
      $this->session->set_userdata("dpjp".$row->id,$row->dpjp);
      $this->session->set_userdata("rekomendasi".$row->id,$row->rekomendasi);
      $this->session->set_userdata("id_pindahkamar".$row->id,$row->id_pindahkamar);
      $this->session->set_userdata("pemberi".$row->id,$row->pemberi);
      $this->session->set_userdata("penerima".$row->id,$row->penerima);
      $this->session->set_userdata("gejala".$row->id,$row->gejala);
      $this->session->set_userdata("resiko".$row->id,$row->resiko);
      $this->session->set_userdata("prov".$row->id,$row->prov);
      $this->session->set_userdata("kota".$row->id,$row->kota);
      $this->session->set_userdata("status".$row->id,$row->status);
      $this->session->set_userdata("tingkat_status".$row->id,$row->tingkat_status);
      $this->session->set_userdata("status_assesmen".$row->id,$row->status_assesmen);
      $this->session->set_userdata("tglgejala".$row->id,$row->tglgejala);
      $this->session->set_userdata("tglresiko".$row->id,$row->tglresiko);
      $t .= $koma.$row->id;
      $koma = ",";
    }
    if ($t!="") $this->session->set_userdata("temp",$t); else $this->session->unset_userdata("temp");
  }
  function cetakassesmen_perawat($no_reg){
    $this->db->select("a.*,d.nama_perawat");
    $this->db->join("perawat d","d.id_perawat=a.pemberi","left");
    $q = $this->db->get_where("assesmen_perawat_vaksin a",["no_reg"=>$no_reg]);
    return $q;
  }
  function cetakassesmen_perawat_array($no_pasien){
    $this->db->select("a.*,d.nama_perawat");
    $this->db->join("perawat d","d.id_perawat=a.pemberi","left");
    $this->db->join("pasien_ralan pr","pr.no_reg=a.no_reg","inner");
    $q = $this->db->get_where("assesmen_perawat a",["pr.no_pasien"=>$no_pasien]);
    $data = array();
    foreach($q->result() as $row){
      $data[$row->no_reg] = $row;
    }
    return $data;
  }
  function cetakhandover($no_reg){
    $this->db->select("a.*,d.nama_dokter as nama_dpjp");
    $this->db->group_by("a.shift");
    $this->db->join("dokter d","d.id_dokter=a.dpjp","left");
    $q = $this->db->get_where("assesmen_perawat a",["a.no_reg"=>$no_reg]);
    $data = array();
    foreach ($q->result() as $key) {
      $data[$key->shift] = $key;
    }
    return $data;
  }
  function pasientriage($no_reg){
    $this->db->select("t.*,p.nama_perawat");
    $this->db->join("perawat p","p.id_perawat=t.petugas_igd","left");
    $q = $this->db->get_where("pasien_triage t",["no_reg"=>$no_reg]);
    return $q->row();
  }
  function sparray(){
    $q = $this->db->get("soap_perawat");
    $data = array();
    foreach ($q->result() as $key) {
      $data[$key->id] = $key;
    }
    return $data;
  }
  function perawatarray(){
    $q = $this->db->get("perawat");
    $data = array();
    foreach ($q->result() as $key) {
      $data[$key->id_perawat] = $key->nama_perawat;
    }
    return $data;
  }
  function simpantemp(){
    $no_reg = $this->input->post("no_reg");
    $jenis = $this->input->post("jenis");
    $temp = $this->session->userdata("temp");
    $row = explode(",", $temp);
    if (is_array($row)){
      foreach ($row as $key => $value) {
        if ($value!=""){
          $q = $this->db->get_where("assesmen_perawat",["no_reg"=>$no_reg,"jenis"=>$jenis,"id"=>$value]);
          if ($q->num_rows()>0){
            $this->db->where("no_reg",$no_reg);
            $this->db->delete("assesmen_perawat");
          }
          $data = array(
            "no_reg" => $no_reg,
            "id" => $value,
            "tanggal" => date("Y-m-d",strtotime($this->session->userdata("tgl".$value))),
            "jam" => date("H:i:s",strtotime($this->session->userdata("jam".$value))),
            "jenis" => $jenis,
            "s" => $this->session->userdata("s".$value),
            "o" => $this->session->userdata("o".$value),
            "a" => $this->session->userdata("a".$value),
            "p" => $this->session->userdata("p".$value),
            "tujuan" => $this->session->userdata("tujuan".$value),
            "td" => $this->session->userdata("td".$value),
            "td2" => $this->session->userdata("td2".$value),
            "nadi" => $this->session->userdata("nadi".$value),
            "respirasi" => $this->session->userdata("respirasi".$value),
            "suhu" => $this->session->userdata("suhu".$value),
            "spo2" => $this->session->userdata("spo2".$value),
            "bb" => $this->session->userdata("bb".$value),
            "tb" => $this->session->userdata("tb".$value),
            "shift" => $this->session->userdata("shift".$value),
            "situasional" => $this->session->userdata("situasional".$value),
            "medis" => $this->session->userdata("medis".$value),
            "dpjp" => $this->session->userdata("dpjp".$value),
            "rekomendasi" => $this->session->userdata("rekomendasi".$value),
            "id_pindahkamar" => $this->session->userdata("id_pindahkamar".$value),
            "pemberi" => $this->session->userdata("pemberi".$value),
            "penerima" => $this->session->userdata("penerima".$value),
            "gejala" => $this->session->userdata("gejala".$value)=="null" ? "" : $this->session->userdata("gejala".$value),
            "tglgejala" => $this->session->userdata("tglgejala".$value)=="null" ? "" : $this->session->userdata("tglgejala".$value),
            "resiko" => $this->session->userdata("resiko".$value)=="null" ? "" : $this->session->userdata("resiko".$value),
            "tglresiko" => $this->session->userdata("tglresiko".$value)=="null" ? "" : $this->session->userdata("tglresiko".$value),
            "prov" => $this->session->userdata("prov".$value),
            "kota" => $this->session->userdata("kota".$value),
            "status" => $this->session->userdata("status".$value),
            "tingkat_status" => $this->session->userdata("tingkat_status".$value),
            "status_assesmen" => $this->session->userdata("status_assesmen".$value),
          );
          $this->db->insert("assesmen_perawat",$data);
        }
      }
    }
    $this->session->unset_userdata("temp");
  }
  function simpantujuan(){
    $data = array(
      "id"=>date("dmYHis"),
      "id_ap"=>$this->input->post("id_ap"),
      "no_reg"=>$this->input->post("no_reg"),
      "tanggal"=>date("Y-m-d H:i:s"),
      "tujuan"=>$this->input->post("tujuan"),
    );
    $this->db->insert("assesmen_perawat_tujuan",$data);
  }
  function simpanimplementasi(){
    // $this->db->where("no_reg",$this->input->post("no_reg"));
    // $this->db->where("id_ap",$this->input->post("id_ap"));
    // $this->db->delete("assesmen_perawat_implementasi");
    $data = array(
      "id"=>date("dmYHis"),
      "id_ap"=>$this->input->post("id_ap"),
      "no_reg"=>$this->input->post("no_reg"),
      "tanggal"=>date("Y-m-d H:i:s"),
      "implementasi"=>$this->input->post("implementasi"),
      "perawat"=>$this->input->post("perawat_implementasi"),
    );
    $this->db->insert("assesmen_perawat_implementasi",$data);
  }
  function simpanevaluasi(){
    $this->db->where("no_reg",$this->input->post("no_reg"));
    $this->db->where("id_ap",$this->input->post("id_ap"));
    $this->db->delete("assesmen_perawat_evaluasi");
    $data = array(
      "id"=>date("dmYHis"),
      "id_ap"=>$this->input->post("id_ap"),
      "no_reg"=>$this->input->post("no_reg"),
      "tanggal"=>date("Y-m-d H:i:s"),
      "s"=>$this->input->post("s_implementasi"),
      "o"=>$this->input->post("o_implementasi"),
      "a"=>$this->input->post("a_implementasi"),
      "p"=>$this->input->post("p_implementasi"),
    );
    $this->db->insert("assesmen_perawat_evaluasi",$data);
  }
  function cetakimplementasi($no_reg){
    $this->db->select("a.*,ap.shift");
    $this->db->order_by("a.tanggal,ap.shift");
    // $this->db->order_by("ap.shift,a.id_ap,a.tanggal");
    $this->db->join("assesmen_perawat ap","ap.no_reg=a.no_reg and ap.id=a.id_ap","inner");
    $q = $this->db->get_where("assesmen_perawat_implementasi a",["a.no_reg"=>$no_reg]);
    return $q;
  }
  function cetakevaluasi($no_reg){
    $q = $this->db->get_where("assesmen_perawat_evaluasi",["no_reg"=>$no_reg]);
    $data = array();
    foreach($q->result() as $row){
      $data[$row->id_ap][] = $row;
    }
    return $data;
  }
  function getpasien_inap($no_reg){
    $this->db->select("pi.no_reg,pi.tgl_masuk,pi.tgl_keluar,r.nama_ruangan,k.nama_kelas,pi.kode_kamar,pi.no_bed,p.jenis_kelamin as jk,p.nama_pasien,p.tgl_lahir");
    $this->db->join("pasien p", "p.no_pasien = pi.no_rm", "left");
    $this->db->join("ruangan r","r.kode_ruangan=pi.kode_ruangan","left");
    $this->db->join("kelas k","k.kode_kelas=pi.kode_kelas","left");
    $this->db->where("pi.no_reg",$no_reg);
    $q = $this->db->get("pasien_inap pi");
    return $q->row();
  }
  function getpasien_ralan($no_reg){
    $this->db->select("pi.no_reg,pi.tanggal,k.keterangan as nama_ruangan");
    $this->db->where("pi.no_reg",$no_reg);
    $this->db->join("poliklinik k","k.kode=pi.tujuan_poli","left");
    $q = $this->db->get("pasien_ralan pi");
    return $q->row();
  }
  function getimplementasi(){
    return $this->db->get("implementasi");
  }
  function getassesmen_perawat_cetak($no_reg){
    $this->db->order_by("tanggal,jam");
    $q = $this->db->get_where("assesmen_perawat",["no_reg"=>$no_reg]);
    $data = array();
    foreach($q->result() as $row){
      $data[$row->id] = $row;
    }
    return $data;
  }
  function getassesmen_covid($no_reg,$jenis){
    if ($jenis=="igd"){
      $this->db->order_by("a.tanggal,a.jam");
      $this->db->select("a.*,p.id_kota,p.id_provinsi,pt.petugas_igd,pw.nama_perawat");
      $this->db->join("pasien_ralan pi","pi.no_reg=a.no_reg","inner");
      $this->db->join("pasien_triage pt","pt.no_reg=a.no_reg","inner");
      $this->db->join("pasien p","p.no_pasien=pi.no_pasien","inner");
      $this->db->join("perawat pw","pw.id_perawat=pt.petugas_igd","left");
    } else {
      $this->db->order_by("a.tanggal,a.jam");
      $this->db->select("a.*,p.id_kota,p.id_provinsi,pt.petugas_igd,pw.nama_perawat");
      $this->db->join("pasien_inap pi","pi.no_reg=a.no_reg","inner");
      $this->db->join("pasien_triage pt","pt.no_reg=a.no_reg","inner");
      $this->db->join("pasien p","p.no_pasien=pi.no_rm","inner");
      $this->db->join("perawat pw","pw.id_perawat=pt.petugas_igd","left");
    }
    $q = $this->db->get_where("assesmen_perawat a",["a.no_reg"=>$no_reg,"a.shift"=>"igd"]);
    return $q;
  }
  function getprovince(){
    return $this->db->get("provinces");
  }
  function getlistpindah_kamar($no_reg){
    $this->db->select("pk.*,pi.no_rm,p.no_pasien,p.alamat,gp.keterangan,p.nama_pasien,r1.nama_ruangan as ruanglama,r2.nama_ruangan as ruangbaru,k1.nama_kelas as kelaslama,k2.nama_kelas as kelasbaru");
    $this->db->join("pasien_inap pi","pi.no_reg=pk.no_reg");
    $this->db->join("pasien p","p.no_pasien=pi.no_rm");
    $this->db->join("gol_pasien gp","gp.id_gol=pi.id_gol","LEFT");
    $this->db->join("ruangan r1","r1.kode_ruangan=pk.kode_ruangan_lama","LEFT");
    $this->db->join("ruangan r2","r2.kode_ruangan=pk.kode_ruangan","left");
    $this->db->join("kelas k1","k1.kode_kelas=pk.kode_kelas_lama","left");
    $this->db->join("kelas k2","k2.kode_kelas=pk.kode_kelas","left");
    $this->db->where("pk.no_reg",$no_reg);
    $this->db->order_by("pk.tanggal,pk.jam","ASC");
    $q = $this->db->get("pindahkamar pk");
    return $q;
  }
  function getpindahkamar_detail($no_reg,$id_pindah){
    $this->db->select("pk.*,pi.no_rm,p.no_pasien,p.alamat,gp.keterangan,p.nama_pasien,r1.nama_ruangan as ruanglama,r2.nama_ruangan as ruangbaru,k1.nama_kelas as kelaslama,k2.nama_kelas as kelasbaru");
    $this->db->join("pasien_inap pi","pi.no_reg=pk.no_reg");
    $this->db->join("pasien p","p.no_pasien=pi.no_rm");
    $this->db->join("gol_pasien gp","gp.id_gol=pi.id_gol","LEFT");
    $this->db->join("ruangan r1","r1.kode_ruangan=pk.kode_ruangan_lama","LEFT");
    $this->db->join("ruangan r2","r2.kode_ruangan=pk.kode_ruangan","left");
    $this->db->join("kelas k1","k1.kode_kelas=pk.kode_kelas_lama","left");
    $this->db->join("kelas k2","k2.kode_kelas=pk.kode_kelas","left");
    $this->db->where("pk.no_reg",$no_reg);
    $this->db->where("pk.id",$id_pindah);
    $q = $this->db->get("pindahkamar pk");
    return $q->row();
  }
  function getpemindahan_pasien($no_reg,$id_pindah){
    $this->db->where("no_reg",$no_reg);
    $this->db->where("id_pindahkamar",$id_pindah);
    $q = $this->db->get("pemindahan_pasieninap");
    return $q->row();
  }
  function simpanpemindahan($action){
    $ppb = $this->input->post("prosedur_pembedahan");
    $koma = $prosedur_pembedahan = "";
    if (is_array($ppb)){
      foreach ($ppb as $key => $value) {
        $prosedur_pembedahan .= $koma.($value!="" ? $value : 0);
        $kodetarif .= $koma.($value!="" ? "'".$value."'" : "");
        $koma = ",";
      }
    }

    $pre = $this->input->post("precaution");
    $koma = $precaution = "";
    if (is_array($pre)){
      foreach ($pre as $key => $value) {
        $precaution .= $koma.($value!="" ? $value : 0);
        $kodetarif .= $koma.($value!="" ? "'".$value."'" : "");
        $koma = ",";
      }
    }

    $dn = $this->input->post("diet");
    $koma = $diet = "";
    if (is_array($dn)){
      foreach ($dn as $key => $value) {
        $diet .= $koma.($value!="" ? $value : 0);
        $kodetarif .= $koma.($value!="" ? "'".$value."'" : "");
        $koma = ",";
      }
    }

    $bb1 = $this->input->post("bab");
    $km = $bab = "";
    if (is_array($bb1)){
      foreach ($bb1 as $key => $val) {
        $bab .= $km.($val!="" ? $val : 0);
        $kodetarif .= $km.($val!="" ? "'".$val."'" : "");
        $km = ",";
      }
    }
    $tk     = $this->input->post("tindakan_khusus");
    $koma   = $tindakan_khusus = "";
    if (is_array($tk)){
      foreach ($tk as $key => $value) {
        $tindakan_khusus .= $koma.($value!="" ? $value : 0);
        $kodetarif .= $koma.($value!="" ? "'".$value."'" : "");
        $koma = ",";
      }
    }

    $nt     = $this->input->post("note");
    $koma   = $note = "";
    if (is_array($nt)){
      foreach ($nt as $key => $value) {
        $note .= $koma.($value!="" ? $value : 0);
        $kodetarif .= $koma.($value!="" ? "'".$value."'" : "");
        $koma = ",";
      }
    }

    $lokinfus = $this->input->post("lokasi");
    $koma = $lokasi = "";
    if (is_array($lokinfus)){
      foreach ($lokinfus as $key => $value) {
        $lokasi .= $koma.($value!="" ? $value : 0);
        $kodetarif .= $koma.($value!="" ? "'".$value."'" : "");
        $koma = ",";
      }
    }
    switch ($action) {
      case 'simpan':
      $data = array(
        'no_reg'                 => $this->input->post('no_reg'),
        'no_pasien'                 => $this->input->post('no_pasien'),
        'prosedur_pembedahan'    => $prosedur_pembedahan,
        'id_pindahkamar'         => $this->input->post('id_pindahkamar'),
        'tiba_diruangan'         => $this->input->post('tiba_diruangan'),
        'dari_ruangan'           => $this->input->post('dari_ruangan'),
        'tanggal'                => $this->input->post('tanggal'),
        'pukul'                  => $this->input->post('pukul'),
        'diagnosa'               => $this->input->post('diagnosa'),
        'dokter1'                => $this->input->post('dokter1'),
        'dokter2'                => $this->input->post('dokter2'),
        'dokter3'                => $this->input->post('dokter3'),
        'penjelasan_diagnosa'    => $this->input->post('penjelasan_diagnosa'),
        'masalah_keperawatan'    => $this->input->post('masalah_keperawatan'),
        'tgl_prosedur'           => $this->input->post('tgl_prosedur'),
        'riwayat_alergi'         => $this->input->post('riwayat_alergi'),
        'nama_obat'              => $this->input->post('nama_obat'),
        'riwayat_reaksi'         => $this->input->post('riwayat_reaksi'),
        'intervensi_medis'       => $this->input->post('intervensi_medis'),
        'hasil_abnormal'         => $this->input->post('hasil_abnormal'),
        'precaution'             => $precaution,
        'konsultasi'             => $this->input->post('konsultasi'),
        'terapi'                 => $this->input->post('terapi'),
        'pemeriksaan_lab'        => $this->input->post('pemeriksaan_lab'),
        'rencana_tindakan'       => $this->input->post('rencana_tindakan'),
        'note'                   => $note,
        'reservasi_terakhir'     => $this->input->post('reservasi_terakhir'),
        'gcs'                    => $this->input->post('gcs'),
        'e'                      => $this->input->post('e'),
        'v'                      => $this->input->post('v'),
        'm'                      => $this->input->post('m'),
        'pupil'                  => $this->input->post('pupil'),
        'kiri'                   => $this->input->post('kiri'),
        'td_kanan'               => $this->input->post('td_kanan'),
        'td_kiri'                => $this->input->post('td_kiri'),
        'nadi'                   => $this->input->post('nadi'),
        'respirasi'              => $this->input->post('respirasi'),
        'suhu'                   => $this->input->post('suhu'),
        'spo2'                   => $this->input->post('spo2'),
        'diet'                   => $diet,
        'batasancairan'          => $this->input->post('batasancairan'),
        'dietkhusus'             => $this->input->post('dietkhusus'),
        'puasa'                  => $this->input->post('puasa'),
        'bab'                    => $bab,
        'bak'                    => $this->input->post('bak'),
        'transfer'               => $this->input->post('transfer'),
        'mobilitas'              => $this->input->post('mobilitas'),
        'gangguan_indra'         => $this->input->post('gangguan_indra'),
        'alat_bantu'             => $this->input->post('alat_bantu'),
        'infus'                  => $this->input->post('infus'),
        'lokasi'                 => $lokasi,
        'tanggal_pemasangan'     => $this->input->post('tanggal_pemasangan'),
        'hal_istimewa'           => $this->input->post('hal_istimewa'),
        'tindakan_khusus'        => $tindakan_khusus,
        'peralatan_khusus'       => $this->input->post('peralatan_khusus'),
        'jenis_kateter'          => $this->input->post('jenis_kateter'),
        'nomor_kateter'          => $this->input->post('nomor_kateter'),
        'tglpasang_kateter'      => $this->input->post('tglpasang_kateter'),
        'jam_puasa1'             => $this->input->post('jam_puasa1'),
        'jam_puasa2'             => $this->input->post('jam_puasa2'),
      );
$this->db->insert("pemindahan_pasieninap",$data);
return "success-Data berhasil disimpan";
break;
case 'edit':
$data = array(
  'tiba_diruangan'         => $this->input->post('tiba_diruangan'),
  'dari_ruangan'           => $this->input->post('dari_ruangan'),
  'tanggal'                => $this->input->post('tanggal'),
  'pukul'                  => $this->input->post('pukul'),
  'diagnosa'               => $this->input->post('diagnosa'),
  'dokter1'                => $this->input->post('dokter1'),
  'dokter2'                => $this->input->post('dokter2'),
  'dokter3'                => $this->input->post('dokter3'),
  'penjelasan_diagnosa'    => $this->input->post('penjelasan_diagnosa'),
  'masalah_keperawatan'    => $this->input->post('masalah_keperawatan'),
  'prosedur_pembedahan'    => $prosedur_pembedahan,
  'tgl_prosedur'           => $this->input->post('tgl_prosedur'),
  'riwayat_alergi'         => $this->input->post('riwayat_alergi'),
  'nama_obat'              => $this->input->post('nama_obat'),
  'riwayat_reaksi'         => $this->input->post('riwayat_reaksi'),
  'intervensi_medis'       => $this->input->post('intervensi_medis'),
  'hasil_abnormal'         => $this->input->post('hasil_abnormal'),
  'precaution'             => $precaution,
  'konsultasi'             => $this->input->post('konsultasi'),
  'terapi'                 => $this->input->post('terapi'),
  'pemeriksaan_lab'        => $this->input->post('pemeriksaan_lab'),
  'rencana_tindakan'       => $this->input->post('rencana_tindakan'),
  'note'                   => $note,
  'reservasi_terakhir'     => $this->input->post('reservasi_terakhir'),
  'gcs'                    => $this->input->post('gcs'),
  'e'                      => $this->input->post('e'),
  'v'                      => $this->input->post('v'),
  'm'                      => $this->input->post('m'),
  'pupil'                  => $this->input->post('pupil'),
  'kiri'                   => $this->input->post('kiri'),
  'td_kanan'               => $this->input->post('td_kanan'),
  'td_kiri'                => $this->input->post('td_kiri'),
  'nadi'                   => $this->input->post('nadi'),
  'respirasi'              => $this->input->post('respirasi'),
  'suhu'                   => $this->input->post('suhu'),
  'spo2'                   => $this->input->post('spo2'),
  'diet'                   => $diet,
  'batasancairan'          => $this->input->post('batasancairan'),
  'dietkhusus'             => $this->input->post('dietkhusus'),
  'puasa'                  => $this->input->post('puasa'),
  'bab'                    => $bab,
  'bak'                    => $this->input->post('bak'),
  'transfer'               => $this->input->post('transfer'),
  'mobilitas'              => $this->input->post('mobilitas'),
  'mobilitas_lain'         => $this->input->post('mobilitas_lain'),
  'gangguan_indra'         => $this->input->post('gangguan_indra'),
  'alat_bantu'             => $this->input->post('alat_bantu'),
  'infus'                  => $this->input->post('infus'),
  'lokasi'                 => $lokasi,
  'tanggal_pemasangan'     => $this->input->post('tanggal_pemasangan'),
  'hal_istimewa'           => $this->input->post('hal_istimewa'),
  'tindakan_khusus'        => $tindakan_khusus,
  'peralatan_khusus'       => $this->input->post('peralatan_khusus'),
  'jenis_kateter'          => $this->input->post('jenis_kateter'),
  'nomor_kateter'          => $this->input->post('nomor_kateter'),
  'tglpasang_kateter'      => $this->input->post('tglpasang_kateter'),
  'no_pasien'              => $this->input->post('no_pasien'),
  'jam_puasa1'             => $this->input->post('jam_puasa1'),
  'jam_puasa2'             => $this->input->post('jam_puasa2'),
);
$this->db->where("no_reg",$this->input->post("no_reg"));
$this->db->where("id_pindahkamar",$this->input->post("id_pindahkamar"));
$this->db->update("pemindahan_pasieninap",$data);
return "info-Data berhasil diubah";
break;
}
}
function getap($no_reg){
  $this->db->where("no_reg",$no_reg);
  $q = $this->db->get("assesmen_perawat");
  return $q->row();
}
function gettarif_ralan($no_reg){
  $this->db->select("kode_tindakan,nama_tindakan");
  $this->db->join("tarif_ralan tr","tr.kode_tindakan=ki.kode_tarif");
  $this->db->where("no_reg",$no_reg);
  $this->db->where("pindah_pasien","1");
  $q = $this->db->get("kasir_inap ki");
  return $q;
}
function getprecaution(){
  return $this->db->get("precaution");
}
function getdiet_nutrisi(){
  return $this->db->get("diet_nutrisi");
}
function getbab(){
  return $this->db->get("bab");
}
function getbak(){
  return $this->db->get("bak");
}
function gettindakan_khusus(){
  return $this->db->get("tindakan_khusus");
}
function getnote(){
  return $this->db->get("note");
}
function getapotek_inap($no_reg){
  $this->db->select("apotek_inap.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
  $this->db->join("waktu w","w.kode = apotek_inap.waktu","left");
  $this->db->join("waktu_lainnya wl","wl.kode = apotek_inap.waktu_lainnya","left");
  $this->db->join("aturan_pakai a","a.kode = apotek_inap.aturan_pakai","left");
  $this->db->join("farmasi_data_obat f","f.kode=apotek_inap.kode_obat");
  $this->db->order_by("tanggal,nama_obat");
  $q = $this->db->get_where("apotek_inap",["no_reg" => $no_reg,"kelkd<>" => "ALS"]);
  return $q;
}
function simpanobservasi(){
  $this->db->where("id",$this->input->post("obs_id"));
  $q = $this->db->get("observasi");
  $row = $q->row();
  if ($row) {
    $data = array(
      'tgl'                    => $this->input->post('obs_tgl'),
      'jam'                    => $this->input->post('obs_jam'),
      'tensi'                  => $this->input->post('obs_tensi'),
      'nadi'                   => $this->input->post('obs_nadi'),
      'suhu'                   => $this->input->post('obs_suhu'),
      'respirasi'              => $this->input->post('obs_respirasi'),
      'kes'                    => $this->input->post('obs_kes'),
      'spo2'                   => $this->input->post('obs_spo2'),
      'oral'                   => $this->input->post('obs_oral'),
      'infus'                  => $this->input->post('obs_infus'),
      'darah'                  => $this->input->post('obs_darah'),
      'urine'                  => $this->input->post('obs_urine'),
      'draine'                 => $this->input->post('obs_draine'),
      'ngt'                    => $this->input->post('obs_ngt'),
      'catatan'                => $this->input->post('obs_catatan'),
    );
    $this->db->where("id",$this->input->post("obs_id"));
    $this->db->update("observasi",$data);
    return "info-Data berhasil diubah";
  } else {
    $data = array(
      'no_reg'                 => $this->input->post('obs_no_reg'),
      'id_pindahkamar'         => $this->input->post('obs_id_pindahkamar'),
      'tgl'                    => $this->input->post('obs_tgl'),
      'jam'                    => $this->input->post('obs_jam'),
      'tensi'                  => $this->input->post('obs_tensi'),
      'nadi'                   => $this->input->post('obs_nadi'),
      'suhu'                   => $this->input->post('obs_suhu'),
      'respirasi'              => $this->input->post('obs_respirasi'),
      'kes'                    => $this->input->post('obs_kes'),
      'spo2'                   => $this->input->post('obs_spo2'),
      'oral'                   => $this->input->post('obs_oral'),
      'infus'                  => $this->input->post('obs_infus'),
      'darah'                  => $this->input->post('obs_darah'),
      'urine'                  => $this->input->post('obs_urine'),
      'draine'                 => $this->input->post('obs_draine'),
      'ngt'                    => $this->input->post('obs_ngt'),
      'catatan'                => $this->input->post('obs_catatan'),
    );
    $this->db->insert("observasi",$data);
    return "success-Data berhasil disimpan";
  }

}
function getobservasi($no_reg,$id_pindahkamar){
  $this->db->where("no_reg",$no_reg);
  $this->db->where("id_pindahkamar",$id_pindahkamar);
  $q = $this->db->get("observasi");
  return $q;
}
function getobservasi_detail($no_reg,$id_pindahkamar,$id){
  $this->db->where("id",$id);
  $this->db->where("no_reg",$no_reg);
  $this->db->where("id_pindahkamar",$id_pindahkamar);
  $q = $this->db->get("observasi");
  return $q->row();
}
function cetakpemindahan_pasien($no_reg,$id_pindahkamar){
  $this->db->select("pp.*,d1.nama_dokter as dokter_1,d2.nama_dokter as dokter_2,d3.nama_dokter as dokter_3,dk.nama_dokter as dokter_konsultasi,p.nama_pasien,p.alamat,p.tgl_lahir");
  $this->db->join("dokter d1","d1.id_dokter=pp.dokter1","left");
  $this->db->join("dokter d2","d2.id_dokter=pp.dokter2","left");
  $this->db->join("dokter d3","d3.id_dokter=pp.dokter3","left");
  $this->db->join("dokter dk","dk.id_dokter=pp.konsultasi","left");
  $this->db->join("pasien p","p.no_pasien=pp.no_pasien");
  $this->db->where("no_reg",$no_reg);
  $this->db->where("id_pindahkamar",$id_pindahkamar);
  $q = $this->db->get("pemindahan_pasieninap pp");
  return $q->row();
}
function getperawat_assesmen($no_reg,$id_pindahkamar){
  $this->db->select("p1.nama_perawat as perawat_pengirim, p2.nama_perawat as perawat_penerima,pemberi,penerima");
  $this->db->join("perawat p1","p1.id_perawat=ap.pemberi","left");
  $this->db->join("perawat p2","p2.id_perawat=ap.penerima","left");
  $this->db->where("ap.no_reg",$no_reg);
  $this->db->where("ap.id_pindahkamar",$id_pindahkamar);
  $q = $this->db->get("assesmen_perawat ap");
  return $q->row();
}
function getlokasi_infus(){
  return $this->db->get("lokasi_infus");
}
function getperawat_triage($no_reg){
  $this->db->select("p.nama_perawat,pt.*");
  $this->db->join("perawat p","p.id_perawat=pt.petugas_igd","left");
  $this->db->where("pt.no_reg",$no_reg);
  $q = $this->db->get("pasien_triage pt");
  return $q->row();
}
function simpantemp_pews(){
  $no_reg = $this->input->post("no_reg");
  $value = date("Y-m-d H:i:s",strtotime($this->input->post("tanggal")));
  if ($this->input->post("tanggal")!=""){
    $q = $this->db->get_where("pews",["no_reg"=>$no_reg,"tanggal"=>$value]);
    if ($q->num_rows()>0){
      $this->db->where("tanggal",$value);
      $this->db->where("no_reg",$no_reg);
      $this->db->delete("pews");
    }
    $v = explode(" ", $value);
    $id = date("YmdHis",strtotime($value));
    $intake_array = $this->session->userdata("intake".$id);
    $intake = $koma = "";
    foreach ($intake_array as $key1 => $vl) {
      $intake .= $koma.$vl;
      $koma = ",";
    }
      // $output_array = $this->session->userdata("output".$id);
    $output_array = $this->session->userdata("output".$id);
    $output = $koma = "";
    foreach ($output_array as $key1 => $vl) {
      $output .= $koma.$vl;
      $koma = ",";
    }
    $data[] = array(
      "no_reg" => $no_reg,
      "tanggal" => date("Y-m-d H:i:s",strtotime($value)),
      "rr" => $this->session->userdata("rr".$id),
      "spo2" => $this->session->userdata("spo2".$id),
      "metode_pemberian_o2" => $this->session->userdata("metode_pemberian_o2".$id),
      "pemakaian_o2" => $this->session->userdata("pemakaian_o2".$id),
      "keterangan_pemakaian_o2" => ($this->session->userdata("metode_pemberian_o2".$id)=="T" ? "" : $this->session->userdata("keterangan_pemakaian_o2".$id)),
      "upaya_nafas" => $this->session->userdata("upaya_nafas".$id),
      "nadi" => $this->session->userdata("nadi".$id),
      "crt" => $this->session->userdata("crt".$id),
      "nilai_kardiovaskuler" => $this->session->userdata("nilai_kardiovaskuler".$id),
      "prilaku" => $this->session->userdata("prilaku".$id),
      "nebulizer" => $this->session->userdata("nebulizer".$id),
      "muntah_post_op" => $this->session->userdata("muntah_post_op".$id),
      "nilai_prilaku" => $this->session->userdata("nilai_prilaku".$id),
      "nilai_pews_tambahan" => $this->session->userdata("nilai_pews_tambahan".$id),
      "nilai_pews_total" => $this->session->userdata("nilai_pews_total".$id),
      "suhu" => $this->session->userdata("suhu".$id),
      "tekanan_darah" => $this->session->userdata("tekanan_darah".$id),
      "gula_darah" => $this->session->userdata("gula_darah".$id),
      "luka" => $this->session->userdata("luka".$id),
      "warna_luka" => $this->session->userdata("luka".$id)=="T" ? "" : $this->session->userdata("warna_luka".$id),
      "mobilisasi" => $this->session->userdata("mobilisasi".$id),
      "tinggi_badan" => $this->session->userdata("tinggi_badan".$id),
      "berat_badan" => $this->session->userdata("berat_badan".$id),
      "luka_skala_norton" => $this->session->userdata("luka_skala_norton".$id),
      "total" => $this->session->userdata("total".$id),
      "intake" => $intake,
      "asi" => strpos($intake, "Oral") !== false ? $this->session->userdata("asi".$id) : "",
      "pasi" => strpos($intake, "Oral") !== false ? $this->session->userdata("pasi".$id) : "",
      "intravena" => strpos($intake, "Intravena") !== false ? $this->session->userdata("intravena".$id) : "",
      "darah" => strpos($intake, "Darah") !== false ? $this->session->userdata("darah".$id) : "",
      "output" => $output,
      "urine" => strpos($output, "Urine") !== false ? $this->session->userdata("urine".$id) : "",
      "muntah" => strpos($output, "Muntah") !== false ? $this->session->userdata("muntah".$id) : "",
      "feaces" => strpos($output, "Feaces") !== false ? $this->session->userdata("feaces".$id) : "",
      "drain" => strpos($output, "Drain") !== false ? $this->session->userdata("drain".$id) : "",
      "iwl" => strpos($output, "IWL") !== false ? $this->session->userdata("iwl".$id) : "",
    );
    $this->db->insert_batch("pews",$data);
  }
  $this->session->unset_userdata("temp_pews");
  return json_encode($value);
}
function getpews($no_reg=""){
  $this->db->order_by("tanggal","desc");
  $q = $this->db->get_where("pews",["no_reg"=>$no_reg]);
  $t = $koma = "";
  foreach($q->result() as $row){
    $id = date("YmdHis",strtotime($row->tanggal));
    $this->session->set_userdata("tgl".$id,date("Y-m-d",strtotime($row->tanggal)));
    $this->session->set_userdata("jam".$id,date("H:i:s",strtotime($row->tanggal)));
    $this->session->set_userdata("rr".$id,$row->rr);
    $this->session->set_userdata("spo2".$id,$row->spo2);
    $this->session->set_userdata("metode_pemberian_o2".$id,$row->metode_pemberian_o2);
    $this->session->set_userdata("pemakaian_o2".$id,$row->pemakaian_o2);
    $this->session->set_userdata("keterangan_pemakaian_o2".$id,$row->keterangan_pemakaian_o2);
    $this->session->set_userdata("upaya_nafas".$id,$row->upaya_nafas);
    $this->session->set_userdata("nadi".$id,$row->nadi);
    $this->session->set_userdata("crt".$id,$row->crt);
    $this->session->set_userdata("nilai_kardiovaskuler".$id,$row->nilai_kardiovaskuler);
    $this->session->set_userdata("prilaku".$id,$row->prilaku);
    $this->session->set_userdata("nebulizer".$id,$row->nebulizer);
    $this->session->set_userdata("muntah_post_op".$id,$row->muntah_post_op);
    $this->session->set_userdata("nilai_prilaku".$id,$row->nilai_prilaku);
    $this->session->set_userdata("nilai_pews_tambahan".$id,$row->nilai_pews_tambahan);
    $this->session->set_userdata("nilai_pews_total".$id,$row->nilai_pews_total);
    $this->session->set_userdata("suhu".$id,$row->suhu);
    $this->session->set_userdata("tekanan_darah".$id,$row->tekanan_darah);
    $this->session->set_userdata("gula_darah".$id,$row->gula_darah);
    $this->session->set_userdata("luka".$id,$row->luka);
    $this->session->set_userdata("warna_luka".$id,$row->warna_luka);
    $this->session->set_userdata("mobilisasi".$id,$row->mobilisasi);
    $this->session->set_userdata("tinggi_badan".$id,$row->tinggi_badan);
    $this->session->set_userdata("berat_badan".$id,$row->berat_badan);
    $this->session->set_userdata("luka_skala_norton".$id,$row->luka_skala_norton);
    $this->session->set_userdata("total".$id,$row->total);
    $this->session->set_userdata("intake".$id,$row->intake);
    $this->session->set_userdata("asi".$id,$row->asi);
    $this->session->set_userdata("pasi".$id,$row->pasi);
    $this->session->set_userdata("intravena".$id,$row->intravena);
    $this->session->set_userdata("darah".$id,$row->darah);
    $this->session->set_userdata("output".$id,$row->output);
    $this->session->set_userdata("urine".$id,$row->urine);
    $this->session->set_userdata("muntah".$id,$row->muntah);
    $this->session->set_userdata("feaces".$id,$row->feaces);
    $this->session->set_userdata("drain".$id,$row->drain);
    $this->session->set_userdata("iwl".$id,$row->iwl);
    $t .= $koma.$row->tanggal;
    $koma = ",";
  }
  if ($t!="") $this->session->set_userdata("temp_pews",$t); else $this->session->unset_userdata("temp_pews");
}
function cetakpews($no_reg,$tgl1,$tgl2){
  $this->db->order_by("tanggal");
  $q = $this->db->get_where("pews",["no_reg"=>$no_reg,"date(tanggal)>="=>date("Y-m-d",strtotime($tgl1)),"date(tanggal)<="=>date("Y-m-d",strtotime($tgl2))]);
  $data = array();
  foreach($q->result() as $row){
    $tgl = date("d-m-Y",strtotime($row->tanggal));
    $jam = date("H:i:s",strtotime($row->tanggal));
    $data[$tgl][$jam] = $row;
  }
  return $data;
}
function cetaknews($no_reg,$tgl1,$tgl2){
  $this->db->order_by("tanggal");
  $q = $this->db->get_where("news",["no_reg"=>$no_reg,"date(tanggal)>="=>date("Y-m-d",strtotime($tgl1)),"date(tanggal)<="=>date("Y-m-d",strtotime($tgl2))]);
  $data = array();
  foreach($q->result() as $row){
    $tgl = date("d-m-Y",strtotime($row->tanggal));
    $jam = date("H:i:s",strtotime($row->tanggal));
    $data[$tgl][$jam] = $row;
  }
  return $data;
}
function cetakmeows($no_reg,$tgl1,$tgl2){
  $this->db->order_by("tanggal");
  $q = $this->db->get_where("meows",["no_reg"=>$no_reg,"date(tanggal)>="=>date("Y-m-d",strtotime($tgl1)),"date(tanggal)<="=>date("Y-m-d",strtotime($tgl2))]);
  $data = array();
  foreach($q->result() as $row){
    $tgl = date("d-m-Y",strtotime($row->tanggal));
    $jam = date("H:i:s",strtotime($row->tanggal));
    $data[$tgl][$jam] = $row;
  }
  return $data;
}
function getnews($no_reg=""){
  $this->db->order_by("tanggal","desc");
  $q = $this->db->get_where("news",["no_reg"=>$no_reg]);
  $t = $koma = "";
  foreach($q->result() as $row){
    $id = date("YmdHis",strtotime($row->tanggal));
    $this->session->set_userdata("tgl_news".$id,date("Y-m-d",strtotime($row->tanggal)));
    $this->session->set_userdata("jam_news".$id,date("H:i:s",strtotime($row->tanggal)));
    $this->session->set_userdata("rr".$id,$row->rr);
    $this->session->set_userdata("spo2".$id,$row->spo2);
    $this->session->set_userdata("pemakaian_o2".$id,$row->pemakaian_o2);
    $this->session->set_userdata("keterangan_pemakaian_o2".$id,$row->keterangan_pemakaian_o2);
    $this->session->set_userdata("suhu".$id,$row->suhu);
    $this->session->set_userdata("tensi".$id,$row->tensi);
    $this->session->set_userdata("nadi".$id,$row->nadi);
    $this->session->set_userdata("tingkat_kesadaran".$id,$row->tingkat_kesadaran);
    $this->session->set_userdata("score_ews".$id,$row->score_ews);
    $this->session->set_userdata("gula_darah".$id,$row->gula_darah);
    $this->session->set_userdata("cvp".$id,$row->cvp);
    $this->session->set_userdata("lingkar_perut".$id,$row->lingkar_perut);
    $this->session->set_userdata("berat_badan".$id,$row->berat_badan);
    $this->session->set_userdata("tinggi_badan".$id,$row->tinggi_badan);
    $this->session->set_userdata("luka_skala_norton".$id,$row->luka_skala_norton);
    $this->session->set_userdata("oral".$id,$row->oral);
    $this->session->set_userdata("intravena".$id,$row->intravena);
    $this->session->set_userdata("darah".$id,$row->darah);
    $this->session->set_userdata("urine".$id,$row->urine);
    $this->session->set_userdata("muntah".$id,$row->muntah);
    $this->session->set_userdata("faeces".$id,$row->faeces);
    $this->session->set_userdata("drain".$id,$row->drain);
    $this->session->set_userdata("iwl".$id,$row->iwl);
    $this->session->set_userdata("konjungtiva".$id,$row->konjungtiva);
    $this->session->set_userdata("buah_dada".$id,$row->buah_dada);
    $this->session->set_userdata("kontraksi".$id,$row->kontraksi);
    $this->session->set_userdata("flatus".$id,$row->flatus);
    $this->session->set_userdata("fundur_uteri".$id,$row->fundur_uteri);
    $this->session->set_userdata("luka_pembedahan".$id,$row->luka_pembedahan);
    $this->session->set_userdata("perineum".$id,$row->perineum);
    $this->session->set_userdata("defekasi".$id,$row->defekasi);
    $this->session->set_userdata("bak".$id,$row->bak);
    $this->session->set_userdata("diastasis_retchi".$id,$row->diastasis_retchi);
    $t .= $koma.$row->tanggal;
    $koma = ",";
  }
  if ($t!="") $this->session->set_userdata("temp_news",$t); else $this->session->unset_userdata("temp_news");
}
function simpantemp_news(){
  $no_reg = $this->input->post("no_reg");
  $temp = $this->session->userdata("temp_news");
  $row = explode(",", $temp);
  if (is_array($row)){
    foreach ($row as $key => $value) {
      if ($value!=""){
        $v = explode(" ", $value);
        $id = date("YmdHis",strtotime($value));
        $q = $this->db->get_where("news",["no_reg"=>$no_reg,"tanggal"=>$value]);
        if ($q->num_rows()>0){
          $this->db->where("no_reg",$no_reg);
          $this->db->delete("news");
        }
        $data = array(
          "no_reg" => $no_reg,
          "tanggal" => date("Y-m-d H:i:s",strtotime($value)),
          "rr" => $this->session->userdata("rr".$id),
          "spo2" => $this->session->userdata("spo2".$id),
          "pemakaian_o2" => $this->session->userdata("pemakaian_o2".$id),
          "keterangan_pemakaian_o2" => ($this->session->userdata("pemakaian_o2".$id)=="T" ? "" : $this->session->userdata("keterangan_pemakaian_o2".$id)),
          "suhu" => $this->session->userdata("suhu".$id),
          "tensi" => $this->session->userdata("tensi".$id),
          "nadi" => $this->session->userdata("nadi".$id),
          "tingkat_kesadaran" => $this->session->userdata("tingkat_kesadaran".$id),
          "score_ews" => $this->session->userdata("score_ews".$id),
          "gula_darah" => $this->session->userdata("gula_darah".$id),
          "cvp" => $this->session->userdata("cvp".$id),
          "lingkar_perut" => $this->session->userdata("lingkar_perut".$id),
          "berat_badan" => $this->session->userdata("berat_badan".$id),
          "tinggi_badan" => $this->session->userdata("tinggi_badan".$id),
          "luka_skala_norton" => $this->session->userdata("luka_skala_norton".$id),
          "oral" => $this->session->userdata("oral".$id),
          "intravena" => $this->session->userdata("intravena".$id),
          "darah" => $this->session->userdata("darah".$id),
          "urine" => $this->session->userdata("urine".$id),
          "muntah" => $this->session->userdata("muntah".$id),
          "faeces" => $this->session->userdata("faeces".$id),
          "drain" => $this->session->userdata("drain".$id),
          "iwl" => $this->session->userdata("iwl".$id),
          "konjungtiva" => $this->session->userdata("konjungtiva".$id),
          "buah_dada" => $this->session->userdata("buah_dada".$id),
          "kontraksi" => $this->session->userdata("kontraksi".$id),
          "flatus" => $this->session->userdata("flatus".$id),
          "fundur_uteri" => $this->session->userdata("fundur_uteri".$id),
          "luka_pembedahan" => $this->session->userdata("luka_pembedahan".$id),
          "perineum" => $this->session->userdata("perineum".$id),
          "defekasi" => $this->session->userdata("defekasi".$id),
          "bak" => $this->session->userdata("bak".$id),
          "diastasis_retchi" => $this->session->userdata("diastasis_retchi".$id),
        );
        $this->db->insert("news",$data);
      }
    }
  }
  $this->session->unset_userdata("temp_news");
}
function getmeows($no_reg=""){
  $this->db->order_by("tanggal","desc");
  $q = $this->db->get_where("meows",["no_reg"=>$no_reg]);
  $t = $koma = "";
  foreach($q->result() as $row){
    $id = date("YmdHis",strtotime($row->tanggal));
    $this->session->set_userdata("tgl_meows".$id,date("Y-m-d",strtotime($row->tanggal)));
    $this->session->set_userdata("jam_meows".$id,date("H:i:s",strtotime($row->tanggal)));
    $this->session->set_userdata("rr".$id,$row->rr);
    $this->session->set_userdata("spo2".$id,$row->spo2);
    $this->session->set_userdata("pemakaian_o2".$id,$row->pemakaian_o2);
    $this->session->set_userdata("keterangan_pemakaian_o2".$id,$row->keterangan_pemakaian_o2);
    $this->session->set_userdata("suhu".$id,$row->suhu);
    $this->session->set_userdata("tensi".$id,$row->tensi);
    $this->session->set_userdata("tekanan_darah".$id,$row->tekanan_darah);
    $this->session->set_userdata("nadi".$id,$row->nadi);
    $this->session->set_userdata("tingkat_kesadaran".$id,$row->tingkat_kesadaran);
    $this->session->set_userdata("nyeri".$id,$row->nyeri);
    $this->session->set_userdata("lochea".$id,$row->lochea);
    $this->session->set_userdata("protein_urin".$id,$row->protein_urin);
    $this->session->set_userdata("score_ews".$id,$row->score_ews);
    $this->session->set_userdata("gula_darah".$id,$row->gula_darah);
    $this->session->set_userdata("konjungtiva".$id,$row->konjungtiva);
    $this->session->set_userdata("buah_dada".$id,$row->buah_dada);
    $this->session->set_userdata("kontraksi".$id,$row->kontraksi);
    $this->session->set_userdata("flatus".$id,$row->flatus);
    $this->session->set_userdata("fundur_uteri".$id,$row->fundur_uteri);
    $this->session->set_userdata("luka_pembedahan".$id,$row->luka_pembedahan);
    $this->session->set_userdata("perineum".$id,$row->perineum);
    $this->session->set_userdata("defekasi".$id,$row->defekasi);
    $this->session->set_userdata("bak".$id,$row->bak);
    $this->session->set_userdata("diastasis_retchi".$id,$row->diastasis_retchi);
    $this->session->set_userdata("jenis_persalinan".$id,$row->jenis_persalinan);
    $t .= $koma.$row->tanggal;
    $koma = ",";
  }
  if ($t!="") $this->session->set_userdata("temp_meows",$t); else $this->session->unset_userdata("temp_meows");
}
function simpantemp_meows(){
  $no_reg = $this->input->post("no_reg");
  $temp = $this->session->userdata("temp_meows");
  $row = explode(",", $temp);
  if (is_array($row)){
    foreach ($row as $key => $value) {
      if ($value!=""){
        $v = explode(" ", $value);
        $id = date("YmdHis",strtotime($value));
        $q = $this->db->get_where("meows",["no_reg"=>$no_reg,"tanggal"=>$value]);
        if ($q->num_rows()>0){
          $this->db->where("no_reg",$no_reg);
          $this->db->delete("meows");
        }
        $data = array(
          "no_reg" => $no_reg,
          "tanggal" => date("Y-m-d H:i:s",strtotime($value)),
          "rr" => $this->session->userdata("rr".$id),
          "spo2" => $this->session->userdata("spo2".$id),
          "pemakaian_o2" => $this->session->userdata("pemakaian_o2".$id),
          "keterangan_pemakaian_o2" => ($this->session->userdata("pemakaian_o2".$id)=="T" ? "" : $this->session->userdata("keterangan_pemakaian_o2".$id)),
          "suhu" => $this->session->userdata("suhu".$id),
          "tensi" => $this->session->userdata("tensi".$id),
          "tekanan_darah" => $this->session->userdata("tekanan_darah".$id),
          "nadi" => $this->session->userdata("nadi".$id),
          "tingkat_kesadaran" => $this->session->userdata("tingkat_kesadaran".$id),
          "nyeri" => $this->session->userdata("nyeri".$id),
          "lochea" => $this->session->userdata("lochea".$id),
          "protein_urin" => $this->session->userdata("protein_urin".$id),
          "score_ews" => $this->session->userdata("score_ews".$id),
          "gula_darah" => $this->session->userdata("gula_darah".$id),
          "konjungtiva" => $this->session->userdata("konjungtiva".$id),
          "buah_dada" => $this->session->userdata("buah_dada".$id),
          "kontraksi" => $this->session->userdata("kontraksi".$id),
          "flatus" => $this->session->userdata("flatus".$id),
          "fundur_uteri" => $this->session->userdata("fundur_uteri".$id),
          "luka_pembedahan" => $this->session->userdata("luka_pembedahan".$id),
          "perineum" => $this->session->userdata("perineum".$id),
          "defekasi" => $this->session->userdata("defekasi".$id),
          "bak" => $this->session->userdata("bak".$id),
          "diastasis_retchi" => $this->session->userdata("diastasis_retchi".$id),
          "jenis_persalinan" => $this->session->userdata("jenis_persalinan".$id),
        );
        $this->db->insert("meows",$data);
      }
    }
  }
    // else{
  $this->session->unset_userdata("temp_meows");
    // }
}
function getcase_manager($no_rm, $no_reg)
{
  $q = $this->db->get_where("case_manager", ["no_reg" => $no_reg], ["no_rm" => $no_rm]);
  return $q->row() ;
}
function simpanform_a($aksi){
  switch ($aksi) {
    case 'simpan':
    $data = array(
      'tanggal'                => date("Y-m-d"),
      'assesment1'             => $this->input->post("assesment1"),
      'assesment2'             => $this->input->post("assesment2"),
      'assesment3'             => $this->input->post("assesment3"),
      'assesment4'             => $this->input->post("assesment4"),
      'assesment5'             => $this->input->post("assesment5"),
      'assesment6'             => $this->input->post("assesment6"),
      'assesment7'             => $this->input->post("assesment7"),
      'assesment8'             => $this->input->post("assesment8"),
      'assesment9'             => $this->input->post("assesment9"),
      'assesment10'            => $this->input->post("assesment10"),
      'assesment11'            => $this->input->post("assesment11"),
      'assesment12'            => $this->input->post("assesment12"),
      'identifikasi'           => $this->input->post("identifikasi"),
      'perencanaan'            => $this->input->post("perencanaan"),
      'skrining'      => $this->input->post("skrining1") . "," . $this->input->post("skrining2") . "," . $this->input->post("skrining3") . "," . $this->input->post("skrining4"). "," . $this->input->post("skrining5") . "," . $this->input->post("skrining6") . "," . $this->input->post("skrining7"). "," . $this->input->post("skrining8") . "," . $this->input->post("skrining9") . "," . $this->input->post("skrining10"). "," . $this->input->post("skrining11") . "," . $this->input->post("skrining12"),
      'no_reg'        => $this->input->post("no_reg"),
      'no_rm'         => $this->input->post("no_rm")
    );
    $this->db->insert("case_manager",$data);
    return "success-Data berhasil disimpan";
    break;
    case 'edit':
    $data = array(
      'tanggal'                => date("Y-m-d"),
      'assesment1'             => $this->input->post("assesment1"),
      'assesment2'             => $this->input->post("assesment2"),
      'assesment3'             => $this->input->post("assesment3"),
      'assesment4'             => $this->input->post("assesment4"),
      'assesment5'             => $this->input->post("assesment5"),
      'assesment6'             => $this->input->post("assesment6"),
      'assesment7'             => $this->input->post("assesment7"),
      'assesment8'             => $this->input->post("assesment8"),
      'assesment9'             => $this->input->post("assesment9"),
      'assesment10'            => $this->input->post("assesment10"),
      'assesment11'            => $this->input->post("assesment11"),
      'assesment12'            => $this->input->post("assesment12"),
      'identifikasi'           => $this->input->post("identifikasi"),
      'perencanaan'            => $this->input->post("perencanaan"),
      'skrining'      => $this->input->post("skrining1") . "," . $this->input->post("skrining2") . "," . $this->input->post("skrining3") . "," . $this->input->post("skrining4"). "," . $this->input->post("skrining5") . "," . $this->input->post("skrining6") . "," . $this->input->post("skrining7"). "," . $this->input->post("skrining8") . "," . $this->input->post("skrining9") . "," . $this->input->post("skrining10"). "," . $this->input->post("skrining11") . "," . $this->input->post("skrining12")
    );
    $this->db->where("no_reg",$this->input->post("no_reg"), "no_rm",$this->input->post("no_rm"));
    $this->db->update("case_manager",$data);
    return "info-Data berhasil diubah";
    break;

  }
}
function getitemformb(){
  return $this->db->get("item_formb");
}
function getcaseformb($no_rm,$no_reg){
  $this->db->select("c.*,p.nama_perawat as nama");
  $this->db->join("perawat p","p.id_perawat=c.id_petugas","left");
  return $this->db->get_where("case_formb c",["no_rm"=>$no_rm,"no_reg"=>$no_reg,]);
}
function getcaseformb_detail($id){
  $this->db->select("c.*,p.nama_perawat as nama");
  $this->db->join("perawat p","p.id_perawat=c.id_petugas","left");
  return $this->db->get_where("case_formb c",["id"=>$id]);
}
function simpanform_b($action){
  $q = $this->getitemformb();
  $item = array();
  foreach ($q->result() as $row){
    $item[$row->kode] =  $this->input->post($row->kode);
  }
  $j = explode("/",$this->input->post("id_petugas"));
  switch ($action) {
    case 'simpan':
      $data = array(
        "id" => date("YmdHis"),
        "no_rm" => $this->input->post("no_rm"),
        "no_reg" => $this->input->post("no_reg"),
        "tanggal" => date("Y-m-d",strtotime($this->input->post("tgl")))." ".date("H:i",strtotime($this->input->post("jam"))),
        "item" => json_encode($item),
        "jenis" => $j[0],
        "id_petugas" => $j[1]
      );
      $this->db->insert("case_formb",$data);
    break;
    case 'edit':
    $data = array(
      "id" => date("YmdHis"),
      "no_rm" => $this->input->post("no_rm"),
      "no_reg" => $this->input->post("no_reg"),
      "tanggal" => date("Y-m-d",strtotime($this->input->post("tgl")))." ".date("H:i",strtotime($this->input->post("jam"))),
      "item" => json_encode($item),
      "jenis" => $j[0],
      "id_petugas" => $j[1]
    );
    $this->db->where("id",$this->input->post("id"));
    $this->db->update("case_formb",$data);
    break;
  }
  return "success-Data berhasil disimpan";
}
function getriwayatperawat($id_perawat)
{
  $this->db->select("p.*,b.nama as bagian");
  $this->db->join("bagian b", "b.kode=p.bagian", "left");
  $this->db->where("id_perawat", $id_perawat);
  $q = $this->db->get("perawat p");
  return $q->row();
}
function getsimpeg_keluarga($id_perawat)
{
  $this->db->where("id_perawat", $id_perawat);
  $q = $this->db->get("simpeg_keluarga");
  return $q;
}
function hapusform_b($id){
  $this->db->where("id",$id);
  $this->db->delete("case_formb");
  return "danger-Data berhasil dihapus";
}
function getsimpeg_detail($nik){
  $q = $this->db->get_where("simpeg_keluarga",["nik"=>$nik]);
  return $q->row();
}
function getpendidikan()
{
  $data = array();
  $q = $this->db->get("pendidikan");
  foreach ($q->result() as $row) {
    $data["pendidikan"][$row->idx] = $row->pendidikan;
  }
  return $data;
}
function getpendidikan2(){
  $query = $this->db->get("pendidikan");
      return $query->result();
}
// function getno_keluarga()
//     {
//         for ($i = 1; $i <= 300000; $i++) {
//             $n = substr("000000" . $i, -6, 6);
//             $q = $this->db->get_where("simpeg_keluarga", array("nip" => $n));

//             if ($q->num_rows() <= 0) {
//                 return $n;
//                 break;
//             }
//         }
//     }
  function simpansimpeg($aksi, $nama_file = "")
    {
        switch ($aksi) {
            case 'simpan':
                $data1 = array(
                  'filepdf'           => $nama_file,
                  "nik"               => $this->input->post('nik'),
                  "id_perawat"        => $this->input->post('id_perawat'),
                  "nama"              => $this->input->post('nama'),
                  "kawin"             => $this->input->post('kawin'),
                  "jenis_kelamin"     => $this->input->post('jenis_kelamin'),
                  "tempat_lahir"      => $this->input->post('tempat_lahir'),
                  "id_pendidikan"     => $this->input->post('id_pendidikan'),
                  "bpjs"              => $this->input->post('bpjs'),
                  "hubungan"          => $this->input->post('hubungan'),
                  "tunjangan"         => $this->input->post('tunjangan'),
                  "pegawai_kemenhan"  => $this->input->post('pegawai_kemenhan'),
                  "tgl_lahir"         => date('Y-m-d', strtotime($this->input->post('tgl_lahir')))
                );
                $this->db->insert("simpeg_keluarga", $data1);
                break;
            case 'edit':
                $nik = $this->input->post('nik');
                $data = array(

                  "nama"              => $this->input->post('nama'),
                  "kawin"             => $this->input->post('kawin'),
                  "jenis_kelamin"     => $this->input->post('jenis_kelamin'),
                  "tempat_lahir"      => $this->input->post('tempat_lahir'),
                  "id_pendidikan"     => $this->input->post('id_pendidikan'),
                  "bpjs"              => $this->input->post('bpjs'),
                  "hubungan"          => $this->input->post('hubungan'),
                  "tunjangan"         => $this->input->post('tunjangan'),
                  "pegawai_kemenhan"  => $this->input->post('pegawai_kemenhan'),
                  "tgl_lahir"         => date('Y-m-d', strtotime($this->input->post('tgl_lahir')))
                );
                $this->db->where("nik", $nik);
                $this->db->update("simpeg_keluarga", $data);
                break;
        }
        if ($nama_file != "") {
            $data = array(
                'filepdf' => $nama_file
            );
            $this->db->where("nik", $nik);
            $this->db->update("simpeg_keluarga", $data);
        }
        return "success-Data berhasil di input-" . $nik;
    }
  function hapussimpeg($id){
      $this->db->where("nik",$id);
      $this->db->delete("simpeg_keluarga");
      return "danger-Data Keluarga berhasil di hapus";
  }
  function getpend($id_perawat){
  $this->db->select("r.*,p.pendidikan");
  $this->db->join("pendidikan p","p.idx=r.id_pendidikan","left");
  $this->db->where("r.id_perawat", $id_perawat);
  $q = $this->db->get("riwayat_pendidikan r");
  return $q;
  }
  function getpendidikan_detail($id){
    $this->db->select("r.*,pr.nama_perawat as perawat");
    $this->db->join("perawat pr","pr.id_perawat=r.id_perawat","left");
    $q = $this->db->get_where("riwayat_pendidikan r",["id"=>$id]);
    return $q->row();
  }
  function simpanpendidikan($aksi, $nama_file = "")
    {
        switch ($aksi) {
            case 'simpan':
                $data1 = array(
                  'filepdf'           => $nama_file,
                  "id"                => date("YmdHis"),
                  "id_perawat"        => $this->input->post('id_perawat'),
                  "id_pendidikan"     => $this->input->post('id_pendidikan'),
                  "nama_sekolah"      => $this->input->post('nama_sekolah'),
                  "negara"            => $this->input->post('negara'),
                  "no_ijasah"         => $this->input->post('no_ijasah'),
                  "tahun"             => $this->input->post('tahun'),
                  "tgl_lulus"         => date('Y-m-d', strtotime($this->input->post('tgl_lulus'))),
                  "gelar_depan"       => $this->input->post('gelar_depan'),
                  "gelar_belakang"    => $this->input->post('gelar_belakang')
                );
                $this->db->insert("riwayat_pendidikan", $data1);
                break;
            case 'edit':
                $id = $this->input->post('id');
                $id_perawat = $this->input->post('id_perawat');
                $data = array(
                  "id_pendidikan"     => $this->input->post('id_pendidikan'),
                  "nama_sekolah"      => $this->input->post('nama_sekolah'),
                  "negara"            => $this->input->post('negara'),
                  "no_ijasah"         => $this->input->post('no_ijasah'),
                  "tahun"             => $this->input->post('tahun'),
                  "tgl_lulus"         => date('Y-m-d', strtotime($this->input->post('tgl_lulus'))),
                  "gelar_depan"       => $this->input->post('gelar_depan'),
                  "gelar_belakang"    => $this->input->post('gelar_belakang')
                );
                $this->db->where("id", $id);
                $this->db->where("id_perawat", $id_perawat);
                $this->db->update("riwayat_pendidikan", $data);
                break;
        }
        if ($nama_file != "") {
            $data = array(
                'filepdf' => $nama_file
            );
            $this->db->where("id", $id);
            $this->db->update("riwayat_pendidikan", $data);
        }
        return "success-Data berhasil di input-" . $id;
    }
  function hapuspendidikan($id){
      $this->db->where("id",$id);
      $this->db->delete("riwayat_pendidikan");
      return "danger-Data Pendidikan berhasil di hapus";
  }
  function getriwayat_pangkat($id_perawat){
    $this->db->select("r.*,p.keterangan,k.keterangan as kenaikan");
    $this->db->group_by("p.kode_pangkat");
    $this->db->join("pangkat p","p.kode_pangkat=r.id_pangkat","left");
    $this->db->join("kenaikan_pangkat k","k.id_kenaikan=r.id_kenaikan","left");
    $this->db->where("r.id_perawat", $id_perawat);
    $q = $this->db->get("riwayat_pangkat r");
    return $q;
  }
  function getriwayatpangkat_detail($id){
    $q = $this->db->get_where("riwayat_pangkat",["id"=>$id]);
    return $q->row();
  }
  function getkenaikan(){
    $query = $this->db->get("kenaikan_pangkat");
    return $query->result();
  }
  function getpangkat(){
    $this->db->group_by("kode_pangkat");
    $query = $this->db->get("pangkat");
    return $query->result();
  }
  function simpanpangkat($aksi, $nama_file = "")
    {
        switch ($aksi) {
            case 'simpan':
                $data1 = array(
                  'filepdf'       => $nama_file,
                  "id"                => date("YmdHis"),
                  "id_perawat"        => $this->input->post('id_perawat'),
                  "id_kenaikan"       => $this->input->post('id_kenaikan'),
                  "id_pangkat"        => $this->input->post('id_pangkat'),
                  "tmt"               => date('Y-m-d', strtotime($this->input->post('tmt'))),
                  "sk_tgl"            => date('Y-m-d', strtotime($this->input->post('sk_tgl'))),
                  "bkn_tgl"           => date('Y-m-d', strtotime($this->input->post('bkn_tgl'))),
                  "sk_no"             => $this->input->post('sk_no'),
                  "bkn_no"            => $this->input->post('bkn_no'),
                  "kredit_utama"      => $this->input->post('kredit_utama'),
                  "kredit_tambahan"   => $this->input->post('kredit_tambahan'),
                  "status_pangkat"    => $this->input->post("awal") . "," . $this->input->post("saatini")
                );
                $this->db->insert("riwayat_pangkat", $data1);
                break;
            case 'edit':
                $id = $this->input->post('id');
                $id_perawat = $this->input->post('id_perawat');
                $data = array(
                  "id_kenaikan"       => $this->input->post('id_kenaikan'),
                  "id_pangkat"        => $this->input->post('id_pangkat'),
                  "tmt"               => date('Y-m-d', strtotime($this->input->post('tmt'))),
                  "sk_tgl"            => date('Y-m-d', strtotime($this->input->post('sk_tgl'))),
                  "bkn_tgl"           => date('Y-m-d', strtotime($this->input->post('bkn_tgl'))),
                  "sk_no"             => $this->input->post('sk_no'),
                  "bkn_no"            => $this->input->post('bkn_no'),
                  "kredit_utama"      => $this->input->post('kredit_utama'),
                  "kredit_tambahan"   => $this->input->post('kredit_tambahan'),
                  "status_pangkat"    => $this->input->post("awal") . "," . $this->input->post("saatini")
                );
                $this->db->where("id", $id);
                $this->db->where("id_perawat", $id_perawat);
                $this->db->update("riwayat_pangkat", $data);
                break;
        }
        if ($nama_file != "") {
            $data = array(
                'filepdf' => $nama_file
            );
            $this->db->where("id", $id);
            $this->db->update("riwayat_pangkat", $data);
        }
        return "success-Data berhasil di input-" . $id;
    }
  function hapuspangkat($id){
      $this->db->where("id",$id);
      $this->db->delete("riwayat_pangkat");
      return "danger-Data Pangkat berhasil di hapus";
  }
  function getriwayatdiklat($id_perawat){
  $this->db->select("r.*,d.keterangan as diklat");
  $this->db->join("diklat d","d.id_diklat=r.id_diklat","left");
  $this->db->where("r.id_perawat", $id_perawat);
  $q = $this->db->get("riwayat_diklat r");
  return $q;
  }
  function getdiklat_detail($id){
    $q = $this->db->get_where("riwayat_diklat",["id"=>$id]);
    return $q->row();
  }
  function getdiklat(){
    $query = $this->db->get("diklat");
    return $query->result();
  }
  function simpandiklat($aksi, $nama_file = "")
    {
        switch ($aksi) {
            case 'simpan':
                $data1 = array(
                  'filepdf'       => $nama_file,
                  "id"            => date("YmdHis"),
                  "id_perawat"    => $this->input->post('id_perawat'),
                  "id_diklat"     => $this->input->post('id_diklat'),
                  "nomor"         => $this->input->post('nomor'),
                  "tahun"         => $this->input->post('tahun'),
                  "tanggal"       => date('Y-m-d', strtotime($this->input->post('tanggal')))
                );
                $this->db->insert("riwayat_diklat", $data1);
                break;
            case 'edit':
                $id = $this->input->post('id');
                $id_perawat = $this->input->post('id_perawat');
                $data = array(
                  "id_diklat"     => $this->input->post('id_diklat'),
                  "nomor"         => $this->input->post('nomor'),
                  "tahun"         => $this->input->post('tahun'),
                  "tanggal"       => date('Y-m-d', strtotime($this->input->post('tanggal')))
                );
                $this->db->where("id", $id);
                $this->db->where("id_perawat", $id_perawat);
                $this->db->update("riwayat_diklat", $data);
                break;
        }
        if ($nama_file != "") {
            $data = array(
                'filepdf' => $nama_file
            );
            $this->db->where("id", $id);
            $this->db->update("riwayat_diklat", $data);
        }
        return "success-Data berhasil di input-" . $id;
    }
  function hapusdiklat($id){
      $this->db->where("id",$id);
      $this->db->delete("riwayat_diklat");
      return "danger-Data Diklat berhasil di hapus";
  }
  function getriwayatmiliter($id_perawat){
  $this->db->select("r.*,m.keterangan as militer");
  $this->db->join("militer m","m.id_militer=r.id_militer","left");
  $this->db->where("r.id_perawat", $id_perawat);
  $q = $this->db->get("riwayat_militer r");
  return $q;
  }
  function getmiliter_detail($id){
    $q = $this->db->get_where("riwayat_militer",["id"=>$id]);
    return $q->row();
  }
  function getmiliter(){
    $query = $this->db->get("militer");
    return $query->result();
  }
  function simpanmiliter($aksi, $nama_file = "")
    {
        switch ($aksi) {
            case 'simpan':
                $data1 = array(
                  'filepdf'       => $nama_file,
                  "id"            => date("YmdHis"),
                  "id_perawat"    => $this->input->post('id_perawat'),
                  "id_militer"    => $this->input->post('id_militer'),
                  "nomor"         => $this->input->post('nomor'),
                  "tahun"         => $this->input->post('tahun'),
                  "tanggal"       => date('Y-m-d', strtotime($this->input->post('tanggal')))
                );
                $this->db->insert("riwayat_militer", $data1);
                break;
            case 'edit':
                $id = $this->input->post('id');
                $id_perawat = $this->input->post('id_perawat');
                $data = array(
                  "id_militer"    => $this->input->post('id_militer'),
                  "nomor"         => $this->input->post('nomor'),
                  "tahun"         => $this->input->post('tahun'),
                  "tanggal"       => date('Y-m-d', strtotime($this->input->post('tanggal')))
                );
                $this->db->where("id", $id);
                $this->db->where("id_perawat", $id_perawat);
                $this->db->update("riwayat_militer", $data);
                break;
        }
        if ($nama_file != "") {
            $data = array(
                'filepdf' => $nama_file
            );
            $this->db->where("id", $id);
            $this->db->update("riwayat_militer", $data);
        }
        return "success-Data berhasil di input-" . $id;
    }
  function hapusmiliter($id){
      $this->db->where("id",$id);
      $this->db->delete("riwayat_militer");
      return "danger-Data Militer berhasil di hapus";
  }
  function getpenugasan($id_perawat){
  $this->db->select("g.*,p.name as provinsi,r.name as kota");
  $this->db->join("regencies r","r.id=g.id_kota","left");
  $this->db->join("provinces p","p.id=g.id_provinsi","left");
  $this->db->where("g.id_perawat", $id_perawat);
  $q = $this->db->get("penugasan g");
  return $q;
  }
  function getpenugasan_detail($id){
    $q = $this->db->get_where("penugasan",["id"=>$id]);
    return $q->row();
  }
  function getprovinsi(){
    $query = $this->db->get("provinces");
    return $query->result();
  }
  function getkota(){
    $query = $this->db->get("regencies");
    return $query->result();
  }
  function simpanpenugasan($aksi){
    switch ($aksi) {
      case 'simpan' :
        $data = array(
          "id"            => date("YmdHis"),
          "id_perawat"    => $this->input->post('id_perawat'),
          "id_provinsi"   => $this->input->post('id_provinsi'),
          "id_kota"       => $this->input->post('id_kota'),
          "uraian"        => $this->input->post('uraian'),
          "tahun"         => $this->input->post('tahun')
        );
        $q = $this->getpenugasan_detail($this->input->post('id'));
        if ($q) {
          $msg  = "danger- Penugasan sudah ada sebelumnya";
          return $msg;
        } else {
          $this->db->insert("penugasan",$data);
          $msg  = "success-Data Penugasan berhasil di simpan";
          return $msg;
        }

              break;
      case 'edit' :
        $data = array(
          "id_provinsi"   => $this->input->post('id_provinsi'),
          "id_kota"       => $this->input->post('id_kota'),
          "uraian"        => $this->input->post('uraian'),
          "tahun"         => $this->input->post('tahun')
        );
        $this->db->where("id",$this->input->post('id'));
        $this->db->where("id_perawat",$this->input->post('id_perawat'));
        $this->db->update("penugasan",$data);
        $msg  = "success-Data Penugasan berhasil di ubah";
        return $msg;
        break;
    }
  }
  function hapuspenugasan($id){
      $this->db->where("id",$id);
      $this->db->delete("penugasan");
      return "danger-Data Penugasan berhasil di hapus";
  }
  function getkursus($id_perawat){
    $this->db->select("k.*,p.*,p.id_perawat,p.nama_perawat as perawat");
    $this->db->join("perawat p","p.id_perawat=k.id_perawat","left");
    $this->db->where("k.id_perawat", $id_perawat);
    $q = $this->db->get("kursus k");
    return $q;
  }
  function getkursus_detail($id){
    $q = $this->db->get_where("kursus",["id"=>$id]);
    return $q->row();
  }
  function simpankursus($aksi, $nama_file = "")
    {
        switch ($aksi) {
            case 'simpan':
                $data1 = array(
                  'filepdf'       => $nama_file,
                  "id"            => $this->input->post('id'),
                  "id_perawat"    => $this->input->post('id_perawat'),
                  "jenis"         => $this->input->post('jenis'),
                  "nama"          => $this->input->post('nama'),
                  "tahun"         => $this->input->post('tahun'),
                  "tanggal"       => date('Y-m-d', strtotime($this->input->post('tanggal'))),
                  "jam"           => $this->input->post('jam'),
                  "penyelenggara" => $this->input->post('penyelenggara'),
                  "sk_no"         => $this->input->post('sk_no')
                );
                $this->db->insert("kursus", $data1);
                break;
            case 'edit':
                $id = $this->input->post('id');
                $id_perawat = $this->input->post('id_perawat');
                $data = array(
                  "jenis"         => $this->input->post('jenis'),
                  "nama"          => $this->input->post('nama'),
                  "tahun"         => $this->input->post('tahun'),
                  "tanggal"       => date('Y-m-d', strtotime($this->input->post('tanggal'))),
                  "jam"           => $this->input->post('jam'),
                  "penyelenggara" => $this->input->post('penyelenggara'),
                  "sk_no"         => $this->input->post('sk_no')
                );
                $this->db->where("id", $id);
                $this->db->where("id_perawat", $id_perawat);
                $this->db->update("kursus", $data);
                break;
        }
        if ($nama_file != "") {
            $data = array(
                'filepdf' => $nama_file
            );
            $this->db->where("id", $id);
            $this->db->update("kursus", $data);
        }
        return "success-Data berhasil di input-" . $id;
    }
  function hapuskursus($id){
      $this->db->where("id",$id);
      $this->db->delete("kursus");
      return "danger-Data Kursus berhasil di hapus";
  }
  function getskp($id_perawat){
  $this->db->where("id_perawat", $id_perawat);
  $q = $this->db->get("skp");
  return $q;
  }
  function getskp_detail($id){
    $q = $this->db->get_where("skp",["id"=>$id]);
    return $q->row();
  }
  function simpanskp($aksi, $nama_file = "")
    {
        switch ($aksi) {
            case 'simpan':
                $data1 = array(
                  'filepdf'       => $nama_file,
                  "id"            => date("YmdHis"),
                  "id_perawat"    => $this->input->post('id_perawat'),
                  "tahun"         => $this->input->post('tahun'),
                  "penilai"       => $this->input->post('penilai'),
                  "atasan"        => $this->input->post('atasan'),
                  "nilai"         => $this->input->post('nilai'),
                  "orientasi"     => $this->input->post('orientasi'),
                  "integritas"    => $this->input->post('integritas'),
                  "komitmen"      => $this->input->post('komitmen'),
                  "disiplin"      => $this->input->post('disiplin'),
                  "kerjasama"     => $this->input->post('kerjasama'),
                  "kepemimpinan"  => $this->input->post('kepemimpinan')
                );
                $this->db->insert("skp", $data1);
                break;
            case 'edit':
                $id = $this->input->post('id');
                $id_perawat = $this->input->post('id_perawat');
                $data = array(
                  "tahun"         => $this->input->post('tahun'),
                  "penilai"       => $this->input->post('penilai'),
                  "atasan"        => $this->input->post('atasan'),
                  "nilai"         => $this->input->post('nilai'),
                  "orientasi"     => $this->input->post('orientasi'),
                  "integritas"    => $this->input->post('integritas'),
                  "komitmen"      => $this->input->post('komitmen'),
                  "disiplin"      => $this->input->post('disiplin'),
                  "kerjasama"     => $this->input->post('kerjasama'),
                  "kepemimpinan"  => $this->input->post('kepemimpinan')
                );
                $this->db->where("id", $id);
                $this->db->where("id_perawat", $id_perawat);
                $this->db->update("skp", $data);
                break;
        }
        if ($nama_file != "") {
            $data = array(
                'filepdf' => $nama_file
            );
            $this->db->where("id", $id);
            $this->db->update("skp", $data);
        }
        return "success-Data berhasil di input-" . $id;
    }
  function hapusskp($id){
      $this->db->where("id",$id);
      $this->db->delete("skp");
      return "danger-Data SKP berhasil di hapus";
  }
  function getjabatan_perawat($id_perawat){
  $this->db->select("j.*,n.keterangan as jabatan,b.keterangan as jenis");
  $this->db->order_by("tgl_skep", "desc");
  $this->db->join("jabatan n","n.kode_jabatan=j.jabatan","left");
  $this->db->join("jenis_jabatan b","b.kode_jabatan=n.jenis_jabatan");
  $this->db->where("j.id_perawat", $id_perawat);
  $q = $this->db->get("jabatan_perawat j");
  return $q;
  }
  function getjabatan(){
  $this->db->select("j.*,b.keterangan");
  // $this->db->group_by("b.kode_jabatan,j.jenis_jabatan");
  $this->db->join("jenis_jabatan b","b.kode_jabatan=j.jenis_jabatan");
  $query = $this->db->get("jabatan j");
      return $query->result();
  }
  // function getjabatanpusat(){
  // $query = $this->db->get("jabatanpusat");
  //     return $query->result();
  // }
  function getjabatanperawat_detail($id){
    $q = $this->db->get_where("jabatan_perawat",["id"=>$id]);
    return $q->row();
  }
  function simpanjabatan($aksi, $nama_file = "", $file="")
    {
        switch ($aksi) {
            case 'simpan':
                $data1 = array(
                  'filepdf'     => $nama_file,
                  "id"          => date("YmdHis"),
                  "id_perawat"  => $this->input->post('id_perawat'),
                  "jabatan"     => $this->input->post('jabatan'),
                  "tmt"         => date('Y-m-d', strtotime($this->input->post('tmt'))),
                  "no_kep"      => $this->input->post('no_kep'),
                  "tgl_skep"    => date('Y-m-d', strtotime($this->input->post('tgl_skep')))
                );
                $this->db->insert("jabatan_perawat", $data1);
                break;
            case 'edit':
                $id = $this->input->post('id');
                $id_perawat = $this->input->post('id_perawat');
                $data = array(
                  "jabatan"     => $this->input->post('jabatan'),
                  "tmt"         => date('Y-m-d', strtotime($this->input->post('tmt'))),
                  "no_kep"      => $this->input->post('no_kep'),
                  "tgl_skep"    => date('Y-m-d', strtotime($this->input->post('tgl_skep')))
                );
                $this->db->where("id", $id);
                $this->db->where("id_perawat", $id_perawat);
                $this->db->update("jabatan_perawat", $data);
                break;
        }
        if ($nama_file != "") {
            $data = array(
                'filepdf' => $nama_file
            );
            $this->db->where("id", $id);
            $this->db->update("jabatan_perawat", $data);
        }
        return "success-Data berhasil di input-" . $id;
    }
  function hapusjabatan($id){
      $this->db->where("id",$id);
      $this->db->delete("jabatan_perawat");
      return "danger-Data Jabatan berhasil di hapus";
  }
  function getcpns(){
    $q = $this->db->get_where("riwayat_pangkat n",["id_perawat"=>$this->session->userdata("username_nurse"),"id_kenaikan"=>12]);
    return $q;
  }
}

?>
