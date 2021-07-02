<?php
class Mpendaftaran extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function getpilihnoreg($no_reg)
    {
        $this->db->select("pr.*,ps.nama_pasien,p.keterangan as nama_poli");
        $this->db->join("poliklinik p", "pr.tujuan_poli=p.kode");
        $this->db->join("pasien ps", "ps.no_pasien=pr.no_pasien");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pr");
        return $q;
    }
    function getresume()
    {
        $this->db->select("pr.*,ps.nama_pasien,p.keterangan as nama_poli,d.nama_dokter,pi.a,pi.o,pi.riwayat_alergi");
        $this->db->join("poliklinik p", "pr.tujuan_poli=p.kode");
        $this->db->join("pasien ps", "ps.no_pasien=pr.no_pasien");
        $this->db->join("dokter d", "d.id_dokter=pr.dokter_poli", "left");
        $this->db->join("pasien_igd pi", "pi.no_rm=pr.no_pasien and pi.no_reg=pr.no_reg", "left");
        $this->db->where("pr.no_pasien", $this->input->post("no_rm"));
        $this->db->order_by("pr.tanggal", "desc");
        $q = $this->db->get("pasien_ralan pr");
        $data = array();
        $data["ralan"] = $q->result();
        $data["terapi"] = array();
        $data["kasir"] = array();
        $data["grouper_icd9"] = array();
        $data["grouper_icd10"] = array();
        $this->db->select("a.no_reg,a.nama_obat,a.qty,a.satuan,ap.nama as aturan_pakai");
        $this->db->join("pasien_ralan pr", "pr.no_reg=a.no_reg");
        $this->db->join("aturan_pakai ap", "ap.kode=a.aturan_pakai", "left");
        $this->db->where("pr.no_pasien", $this->input->post("no_rm"));
        $q = $this->db->get("apotek a");
        foreach ($q->result() as $key) {
            $data["terapi"][$key->no_reg][] = $key;
        }
        $this->db->select("k.no_reg,k.kode_tarif,tr.nama_tindakan as nama_tindakan1,tl.nama_tindakan as nama_tindakan2,tra.nama_tindakan as nama_tindakan3");
        $this->db->join("pasien_ralan pr", "pr.no_reg=k.no_reg");
        $this->db->join("tarif_ralan tr", "tr.kode_tindakan=k.kode_tarif", "left");
        $this->db->join("tarif_lab tl", "tl.kode_tindakan=k.kode_tarif", "left");
        $this->db->join("tarif_radiologi tra", "tra.id_tindakan=k.kode_tarif", "left");
        $this->db->where("pr.no_pasien", $this->input->post("no_rm"));
        $this->db->where("k.kode_tarif!=", "FRM");
        $q = $this->db->get("kasir k");
        foreach ($q->result() as $key) {
            $data["kasir"][$key->no_reg][] = $key;
        }
        $this->db->select("g.no_reg,g.kode");
        $this->db->join("pasien_ralan pr", "pr.no_reg=g.no_reg");
        $this->db->where("pr.no_pasien", $this->input->post("no_rm"));
        $q = $this->db->get("grouper_ralan_icd9 g");
        foreach ($q->result() as $key) {
            $data["grouper_icd9"][$key->no_reg][] = $key;
        }
        $this->db->select("g.no_reg,g.kode");
        $this->db->join("pasien_ralan pr", "pr.no_reg=g.no_reg");
        $this->db->where("pr.no_pasien", $this->input->post("no_rm"));
        $q = $this->db->get("grouper_ralan_icd10 g");
        foreach ($q->result() as $key) {
            $data["grouper_icd10"][$key->no_reg][] = $key;
        }
        return $data;
    }
    function cetakresume($no_rm)
    {
        $this->db->select("pr.*,ps.nama_pasien,p.keterangan as nama_poli,d.nama_dokter,pi.a,pi.riwayat_alergi");
        $this->db->join("poliklinik p", "pr.tujuan_poli=p.kode");
        $this->db->join("pasien ps", "ps.no_pasien=pr.no_pasien");
        $this->db->join("dokter d", "d.id_dokter=pr.dokter_poli", "left");
        $this->db->join("pasien_igd pi", "pi.no_rm=pr.no_pasien and pi.no_reg=pr.no_reg", "left");
        $this->db->where("pr.no_pasien", $no_rm);
        $this->db->order_by("pr.tanggal", "desc");
        $q = $this->db->get("pasien_ralan pr");
        $data = array();
        $data["ralan"] = $q->result();
        $data["terapi"] = array();
        $data["kasir"] = array();
        $data["grouper_icd9"] = array();
        $data["grouper_icd10"] = array();
        $this->db->select("a.no_reg,a.nama_obat,a.qty,a.satuan,ap.nama as aturan_pakai");
        $this->db->join("pasien_ralan pr", "pr.no_reg=a.no_reg");
        $this->db->join("aturan_pakai ap", "ap.kode=a.aturan_pakai", "left");
        $this->db->where("pr.no_pasien", $no_rm);
        $q = $this->db->get("apotek a");
        foreach ($q->result() as $key) {
            $data["terapi"][$key->no_reg][] = $key;
        }
        $this->db->select("k.no_reg,k.kode_tarif,tr.nama_tindakan as nama_tindakan1,tl.nama_tindakan as nama_tindakan2,tra.nama_tindakan as nama_tindakan3,e.hasil");
        $this->db->join("pasien_ralan pr", "pr.no_reg=k.no_reg");
        $this->db->join("tarif_ralan tr", "tr.kode_tindakan=k.kode_tarif", "left");
        $this->db->join("tarif_lab tl", "tl.kode_tindakan=k.kode_tarif", "left");
        $this->db->join("ekspertisi_lab e", "e.no_reg= k.no_reg and e.kode_tindakan=k.kode_tarif", "left");
        $this->db->join("tarif_radiologi tra", "tra.id_tindakan=k.kode_tarif", "left");
        $this->db->where("pr.no_pasien", $no_rm);
        $this->db->where("k.kode_tarif!=", "FRM");
        $q = $this->db->get("kasir k");
        foreach ($q->result() as $key) {
            $data["kasir"][$key->no_reg][] = $key;
        }
        $this->db->select("g.no_reg,g.kode");
        $this->db->join("pasien_ralan pr", "pr.no_reg=g.no_reg");
        $this->db->where("pr.no_pasien", $no_rm);
        $q = $this->db->get("grouper_ralan_icd9 g");
        foreach ($q->result() as $key) {
            $data["grouper_icd9"][$key->no_reg][] = $key;
        }
        $this->db->select("g.no_reg,g.kode");
        $this->db->join("pasien_ralan pr", "pr.no_reg=g.no_reg");
        $this->db->where("pr.no_pasien", $no_rm);
        $q = $this->db->get("grouper_ralan_icd10 g");
        foreach ($q->result() as $key) {
            $data["grouper_icd10"][$key->no_reg][] = $key;
        }
        return $data;
    }
    function getnoreg_autocomplete()
    {
        $this->db->select("pasien_ralan.*,pasien.nama_pasien");
        $this->db->join("pasien", "pasien_ralan.no_pasien=pasien.no_pasien");
        $query  = $this->db->get("pasien_ralan")->result();
        $data = array();
        foreach ($query as $key => $value) {
            $data[] = array('id' => $value->no_pasien, 'label' => $value->no_reg, 'nama_pasien' => $value->nama_pasien);
        }
        return $data;
    }
    function simpanmigrasi()
    {
        $data = array(
            'no_pasien' => $this->input->post("no_pasien_lama"),
        );
        $this->db->where("no_pasien", $this->input->post("no_pasien_baru"));
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->update("pasien_ralan", $data);


        return "success-No RM berhasil dimigrasi...";
    }
    function batal($no_pasien, $no_reg, $asal)
    {
        if ($asal == "radiologi") {
            $q = $this->db->get_where("radiografer", ["username" => $this->input->post("username"), "password" => md5($this->input->post("password"))]);
            if ($q->num_rows() > 0) {
                $row = $q->row();
                $data = array('layan' => '2', 'alasan_batal' => $this->input->post("alasan"), "idpetugas_batal" => $row->nip, "namapetugas_batal" => $row->nama);
                $this->db->where("no_pasien", $no_pasien);
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ralan", $data);
                return "success-Pasien Ralan Batal di Layani";
            } else {
                return "danger-Username dan Password tidak sesuai";
            }
        } else
        if ($asal == "labotarium") {
            $q = $this->db->get_where("analys", ["username" => $this->input->post("username"), "password" => md5($this->input->post("password"))]);
            if ($q->num_rows() > 0) {
                $row = $q->row();
                $data = array('layan' => '2', 'alasan_batal' => $this->input->post("alasan"), "idpetugas_batal" => $row->nip, "namapetugas_batal" => $row->nama);
                $this->db->where("no_pasien", $no_pasien);
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ralan", $data);
                return "success-Pasien Ralan Batal di Layani";
            } else {
                return "danger-Username dan Password tidak sesuai";
            }
        } else {
            $q = $this->db->get_where("perawat", ["bagian" => $this->input->post("poli"), "password" => md5($this->input->post("password"))]);
            if ($q->num_rows() > 0) {
                $row = $q->row();
                $data = array('layan' => '2', 'alasan_batal' => $this->input->post("alasan"), "idpetugas_batal" => $row->id_perawat, "namapetugas_batal" => $row->nama_perawat);
                $this->db->where("no_pasien", $no_pasien);
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ralan", $data);
                return "success-Pasien Ralan Batal di Layani";
            } else {
                return "danger-Username dan Password tidak sesuai";
            }
        }
    }

    function batal_inap($no_pasien, $no_reg, $alasan)
    {
        $data = array('layan' => '2', 'alasan_batal' => $alasan);
        $this->db->where("no_pasien", $no_pasien);
        $this->db->where("no_reg", $no_reg);
        $this->db->update("pasien_inap", $data);
        return "success-Pasien inap Batal di Layani";
    }
    function pilihpangkat($id_gol)
    {
        $this->db->select("p.*,k.keterangan as nama_kesatuan");
        $this->db->join("kesatuan k", "k.id_kesatuan=p.id_kesatuan");
        $this->db->where("id_gol", $id_gol);
        $q = $this->db->get("pangkat p");
        return $q;
    }
    function getrjalandetail($id)
    {

        $this->db->select("p.*, g.keterangan, pe.nama as perusahaan, pe.kode as kode_perusahaan");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
        $this->db->join("perusahaan pe", "pe.kode=p.perusahaan", "left");
        $this->db->where("p.no_pasien", $id);
        $q = $this->db->get("pasien p");
        return $q;
    }
    function getinapdetail($id)
    {
        $this->db->select("i.*, g.keterangan, r.nama_ruangan as ruangan, r.kode_ruangan, k.nama_kelas as kelas, k.kode_kelas, pi.jam_masuk,pi.hak_kelas, pi.naik_kelas");
        $this->db->join("pasien_inap pi", "pi.no_rm = i.no_pasien", "left");
        $this->db->join("gol_pasien g", "g.id_gol=i.id_gol", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
        $this->db->where("i.no_pasien", $id);
        $q = $this->db->get("pasien i");
        return $q->row();
    }
    function getinap_edit($no_pasien, $no_reg)
    {
        $this->db->select("i.*,p.nama_pasien,p.jenis_kelamin,p.tgl_lahir, g.keterangan, r.nama_ruangan as ruangan, r.kode_ruangan, k.nama_kelas as kelas, k.kode_kelas,mi.nama as nama_diagnosa,p.perusahaan,per.nama as nama_perusahaan");
        $this->db->join("pasien p", "p.no_pasien=i.no_rm");
        $this->db->join("perusahaan per", "per.kode=p.perusahaan", "left");
        $this->db->join("gol_pasien g", "g.id_gol=i.id_gol", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
        $this->db->join("master_icd mi", "mi.kode=i.diagnosa_masuk", "left");
        $this->db->where("i.no_reg", $no_reg);
        $this->db->where("i.no_rm", $no_pasien);
        $q = $this->db->get("pasien_inap i");
        return $q->row();
    }

    function getralan_edit($no_pasien, $no_reg)
    {
        $this->db->select("i.*, i.gol_pasien as gol_pasien,p.nama_pasien,p.jenis_kelamin,p.tgl_lahir, g.keterangan, i.perusahaan,per.nama as nama_perusahaan");
        $this->db->join("pasien p", "p.no_pasien=i.no_pasien");
        $this->db->join("perusahaan per", "per.kode=i.perusahaan", "left");
        $this->db->join("gol_pasien g", "g.id_gol=i.gol_pasien", "left");
        $this->db->where("i.no_reg", $no_reg);
        $this->db->where("i.no_pasien", $no_pasien);
        $q = $this->db->get("pasien_ralan i");
        return $q->row();
    }

    function getnoreg($tanggal = null)
    {
        for ($i = 1; $i <= 300000; $i++) {
            if ($tanggal != "") {
                $jam = date("His");
                $n = date("YmdHis", strtotime($tanggal . " " . $jam));
            } else
                $n = date("YmdHis");
            $q = $this->db->get_where("pasien_ralan", array("no_reg" => $n));
            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function getnoreginap($tanggal = null, $jam_masuk = null)
    {
        for ($i = 1; $i <= 300000; $i++) {
            if ($tanggal != "") {
                if ($jam_masuk != "")
                    $jam = date("His", strtotime($jam_masuk));
                else
                    $jam = date("His");
                $n = date("YmdHis", strtotime($tanggal . " " . $jam));
            } else {
                $n = date("YmdHis");
            }
            $q = $this->db->get_where("pasien_inap", array("no_reg" => $n));

            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function getperusahaan()
    {
        return $this->db->get("perusahaan");
    }
    function getpoli()
    {
        return $this->db->get("poliklinik");
    }
    function getdokter()
    {
        $this->db->select("dokter.*, k.nama_kelompok");
        $this->db->join("kelompok_dokter k", "k.id_kelompok = dokter.kelompok_dokter", "left");
        return $this->db->get("dokter");
    }
    function getdokterigd()
    {
        $this->db->where("k.id_poli", "0102030");
        $this->db->join("jadwal_dokter k", "k.id_dokter = dokter.id_dokter", "left");
        return $this->db->get("dokter");
    }
    function getdokterpoli($kode_poli)
    {
        $this->db->select("dokter.*, k.nama_kelompok");
        $this->db->join("kelompok_dokter k", "k.id_kelompok = dokter.kelompok_dokter", "left");
        $this->db->join("jadwal_dokter j", "j.id_dokter = dokter.id_dokter", "inner");
        $this->db->where("j.id_poli", $kode_poli);
        return $this->db->get("dokter");
    }
    function pilihdiagnosa($kode = "")
    {
        // $this->db->select("dokter.*, k.nama_kelompok");
        // $this->db->join("kelompok_dokter k","k.id_kelompok = dokter.kelompok_dokter");
        if ($kode != "") $this->db->like("kode", $kode);
        return $this->db->get("master_icd");
    }
    function getkelas()
    {
        return $this->db->get("kelas");
    }
    function getruangan()
    {
        $this->db->select("ka.*, r.nama_ruangan as ruangan, r.kode_ruangan as kruangan, k.kode_kelas as kkelas, k.nama_kelas as kelas");
        $this->db->join("ruangan r", "r.kode_ruangan = ka.kode_ruangan");
        $this->db->join("kelas k", "k.kode_kelas = ka.kode_kelas");
        $this->db->where("status_kamar", "KOSONG");
        return $this->db->get("kamar ka");
    }
    function getruangan1()
    {
        $this->db->select("r.nama_ruangan as ruangan, r.kode_ruangan as kruangan");
        return $this->db->get("ruangan r");
    }
    function simpanrjalan()
    {
        $no_reg = date("YmdHis");
        $jam = date("H:i:s");
        $q = $this->db->get_where("gol_pasien", ["id_gol" => $this->input->post('id_gol')])->row();
        $no_antrian = $this->getno_antrian();
        $status = $q->status;
        $data = array(
            "no_reg" => $no_reg,
            "tanggal" => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal') . " " . $jam)),
            "no_antrian" => $no_antrian,
            "no_pasien" => $this->input->post('no_pasien'),
            "gol_pasien" => $this->input->post('id_gol'),
            "askes" => $this->input->post('no_askes'),
            "no_sjp" => $this->input->post('no_sjp'),
            "perusahaan" => $this->input->post('perusahaan'),
            "dokter_poli" => $this->input->post('dokter_poli'),
            "dokter_pengirim" => $this->input->post('kode_dokter'),
            "dari_poli" => $this->input->post('kode_poli'),
            "no_reg_sebelumnya" => $this->input->post('no_reg_sebelumnya'),
            "status_pasien" => $this->input->post('status_pasien'),
            "jenis" => $this->input->post('jenis'),
            "status_bayar" => $status,
            "tujuan_poli" => $this->input->post('kode_tujuan')
        );
        $this->db->insert("pasien_ralan", $data);
        if ($this->input->post("igd") == "true") {
            $tindakan = $this->input->post("tindakan");
            $id = date("dmyHis");
            foreach ($tindakan as $key => $value) {
                $t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $value]);
                if ($t->num_rows() > 0) {
                    $data = $t->row();
                    if ($this->input->post('jenis') == "R") $tarif = $data->reguler;
                    else $tarif = $data->executive;
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "bayar" => 0
                    );
                    $this->db->insert("kasir", $d);
                    $id++;
                }
            }
        } else {
            $t = $this->db->get_where("tarif_ralan", ["kategori" => "pdf", "kode_poli" => $this->input->post("kode_tujuan")]);
            if ($t->num_rows() > 0) {
                $data = $t->row();
                if ($this->input->post('jenis') == "R") $tarif = $data->reguler;
                else $tarif = $data->executive;
                $d = array(
                    "id" => date("dmyHis"),
                    "no_reg" => $this->input->post('no_reg'),
                    "kode_tarif" => $data->kode_tindakan,
                    "jumlah" => $tarif,
                    "bayar" => 0
                );
                $this->db->insert("kasir", $d);
            }
        }
        return "success-Data berhasil di input";
    }
    function simpankonsul()
    {
        $no_reg     = $this->input->post("no_reg");
        $jam        = date("H:i:s");
        $q          = $this->db->get_where("gol_pasien", ["id_gol" => $this->input->post('id_gol')])->row();
        $no_antrian = $this->getno_antrian();
        $status     = $q->status;
        $data       = array(
            "no_reg" => $no_reg,
            "tanggal" => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal') . " " . $jam)),
            "no_antrian" => $no_antrian,
            "no_pasien" => $this->input->post('no_pasien'),
            "gol_pasien" => $this->input->post('id_gol'),
            "askes" => $this->input->post('no_askes'),
            "no_sjp" => $this->input->post('no_sjp'),
            "perusahaan" => $this->input->post('perusahaan'),
            "dokter_poli" => $this->input->post('dokter_poli'),
            "dokter_pengirim" => $this->input->post('kode_dokter'),
            "dari_poli" => $this->input->post('kode_poli'),
            "no_reg_sebelumnya" => $this->input->post('no_reg_sebelumnya'),
            "status_pasien" => $this->input->post('status_pasien'),
            "jenis" => $this->input->post('jenis'),
            "diagnosa" => $this->input->post('diagnosa'),
            "status_bayar" => $status,
            "tujuan_poli" => $this->input->post('kode_tujuan')
        );
        $this->db->insert("pasien_ralan", $data);
        if ($this->input->post("igd") == "true") {
            $tindakan = $this->input->post("tindakan");
            $id = date("dmyHis");
            foreach ($tindakan as $key => $value) {
                $t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $value]);
                if ($t->num_rows() > 0) {
                    $data = $t->row();
                    if ($this->input->post('jenis') == "R") $tarif = $data->reguler;
                    else $tarif = $data->executive;
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "bayar" => 0
                    );
                    $this->db->insert("kasir", $d);
                    $id++;
                }
            }
        } else {
            $t = $this->db->get_where("tarif_ralan", ["kategori" => "pdf", "kode_poli" => $this->input->post("kode_tujuan")]);
            if ($t->num_rows() > 0) {
                $data = $t->row();
                if ($this->input->post('jenis') == "R") $tarif = $data->reguler;
                else $tarif = $data->executive;
                $d = array(
                    "id" => date("dmyHis"),
                    "no_reg" => $this->input->post('no_reg'),
                    "kode_tarif" => $data->kode_tindakan,
                    "jumlah" => $tarif,
                    "bayar" => 0
                );
                $this->db->insert("kasir", $d);
            }
        }
        return "success-Data berhasil di input";
    }
    function simpaninap()
    {
        $no_reg = date("YmdHis");
        $this->db->where("kode_ruangan", $this->input->post("kode_ruangan"));
        $this->db->where("kode_kamar", $this->input->post("kode_kamar"));
        $this->db->where("kode_kelas", $this->input->post("kode_kelas"));
        $this->db->where("no_bed", $this->input->post("no_bed"));
        $this->db->where("status_kamar", "KOSONG");
        $q = $this->db->get("kamar");
        if ($q->num_rows() > 0) {
            $q = $this->db->get_where("gol_pasien", ["id_gol" => $this->input->post('id_gol')])->row();
            $status_bayar = $q->status;
            $sql = "insert into pasien_inap set
            						no_reg='" . $no_reg . "',
            						tgl_masuk='" . date("Y-m-d", strtotime($this->input->post('tgl_masuk'))) . "',
            						jam_masuk='" . date("H:i:s") . "',
            						no_rm='" . $this->input->post('no_pasien') . "',
            						nama_pasien='" . $this->input->post('nama_pasien') . "',
            						id_gol='" . $this->input->post('id_gol') . "',
            						kode_ruangan='" . $this->input->post('kode_ruangan') . "',
            						kode_kelas='" . $this->input->post('kode_kelas') . "',
            						kode_kamar='" . $this->input->post('kode_kamar') . "',
            						no_bed='" . $this->input->post('no_bed') . "',
            						prosedur_masuk='" . $this->input->post('prosedur_masuk') . "',
            						cara_masuk='" . $this->input->post('cara_masuk') . "',
            						pengirim='" . $this->input->post('pengirim') . "',
            						diagnosa_masuk='" . $this->input->post('kode_diagnosa') . "',
            						dokter='" . $this->input->post('kode_dokter') . "',
            						alergi='" . $this->input->post('alergi') . "',
            						catatan_pasien='" . $this->input->post('catatan_pasien') . "',
            						penanggung_jawab='" . $this->input->post('penanggung_jawab') . "',
                                    status_bayar='" . $status_bayar . "',
            						telepon_pj='" . $this->input->post('telepon_pj') . "'";
            $this->db->query($sql);
            $data = array('status_kamar' => 'ISI');
            $this->db->where("kode_ruangan", $this->input->post("kode_ruangan"));
            $this->db->where("kode_kamar", $this->input->post("kode_kamar"));
            $this->db->where("kode_kelas", $this->input->post("kode_kelas"));
            $this->db->where("no_bed", $this->input->post("no_bed"));
            $this->db->update("kamar", $data);
            // if ($this->input->post("prosedur_masuk")=="UGD"){
            $tindakan = $this->input->post("tindakan");
            $id = date("dmyHis");
            foreach ($tindakan as $key => $value) {
                $t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $value]);
                if ($t->num_rows() > 0) {
                    $data = $t->row();
                    if ($this->input->post('jenis') == "R") $tarif = $data->reguler;
                    else $tarif = $data->executive;
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "qty" => 1,
                        "tanggal" => date("Y-m-d", strtotime($this->input->post('tgl_masuk')))
                    );
                    $this->db->insert("kasir_inap", $d);
                    $id++;
                }
            }
            // }
            return "success-Data berhasil di input";
        } else {
            return "danger-Kamar sudah terisi";
        }
    }
    function editrawatinap()
    {
        $petugas_persalinan = explode("|", $this->input->post('petugas_persalinan'));
        $data = array(
            // "tgl_masuk" => date("Y-m-d",strtotime($this->input->post('tgl_masuk'))),
            // "jam_masuk" => date("H:i:s"),
            // "no_rm" => $this->input->post('no_pasien'),
            // "nama_pasien" => $this->input->post('nama_pasien'),
            // "id_gol" => $this->input->post('id_gol'),
            // "kode_ruangan" => $this->input->post('kode_ruangan'),
            // "kode_kelas" => $this->input->post('kode_kelas'),
            // "kode_kamar" => $this->input->post('kode_kamar'),
            // "no_bed" => $this->input->post('no_bed'),
            "hak_kelas" => $this->input->post('hak_kelas'),
            "naik_kelas" => $this->input->post('naik_kelas'),
            "prosedur_masuk" => $this->input->post('prosedur_masuk'),
            "cara_masuk" => $this->input->post('cara_masuk'),
            "pengirim" => $this->input->post('pengirim'),
            "dokter" => $this->input->post('kode_dokter'),
            "diagnosa_masuk" => $this->input->post('kode_diagnosa'),
            "alergi" => $this->input->post('alergi'),
            "catatan_pasien" => $this->input->post('catatan_pasien'),
            "penanggung_jawab" => $this->input->post('penanggung_jawab'),
            "telepon_pj" => $this->input->post('telepon_pj'),
            "petugas_persalinan" => $petugas_persalinan[1],
            "jenis_petugas_persalinan" => $petugas_persalinan[0],
            "petugas_telapakkaki" => $this->input->post('petugas_telapakkaki'),
        );
        $this->db->where("no_reg", $this->input->post('no_reg'));
        $this->db->where("no_rm", $this->input->post('no_pasien'));
        $this->db->update("pasien_inap", $data);
        $tindakan = $this->input->post("tindakan");
        $id = date("dmyHis");
        foreach ($tindakan as $key => $value) {
            $d = $this->db->get_where("kasir_inap", ["no_reg" => $this->input->post('no_reg'), "kode_tarif" => $value, "tanggal" => date("Y-m-d", strtotime($this->input->post('tgl_masuk')))]);
            if ($d->num_rows() <= 0) {
                $t = $this->db->get_where("tarif_ralan", ["kode_tindakan" => $value]);
                if ($t->num_rows() > 0) {
                    if ($value == "T073" || $value == "T074" || $value == "T075") {
                        $dokter = $this->input->post('kode_dokter');
                    } else $dokter = "";
                    $data = $t->row();
                    $tarif = $data->reguler;
                    $d = array(
                        "id" => $id,
                        "no_reg" => $this->input->post('no_reg'),
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "kode_petugas" => $dokter,
                        "qty" => 1,
                        "tanggal" => date("Y-m-d", strtotime($this->input->post('tgl_masuk')))
                    );
                    $this->db->insert("kasir_inap", $d);
                    $id++;
                }
            }
        }
        return "success-Data berhasil di input";
    }
    function pindahkamar()
    {
        $this->db->where("kode_ruangan", $this->input->post("kode_ruangan"));
        $this->db->where("kode_kamar", $this->input->post("kode_kamar"));
        $this->db->where("kode_kelas", $this->input->post("kode_kelas"));
        $this->db->where("no_bed", $this->input->post("no_bed"));
        $this->db->where("status_kamar", "KOSONG");
        $q = $this->db->get("kamar");
        if ($q->num_rows() > 0) {
            $data = array('status_kamar' => 'KOSONG');
            $this->db->where("kode_ruangan", $this->input->post("kode_ruangan_lama"));
            $this->db->where("kode_kamar", $this->input->post("kode_kamar_lama"));
            $this->db->where("kode_kelas", $this->input->post("kode_kelas_lama"));
            $this->db->where("no_bed", $this->input->post("no_bed_lama"));
            $this->db->update("kamar", $data);
            $data = array('status_kamar' => 'ISI');
            $this->db->where("kode_ruangan", $this->input->post("kode_ruangan"));
            $this->db->where("kode_kamar", $this->input->post("kode_kamar"));
            $this->db->where("kode_kelas", $this->input->post("kode_kelas"));
            $this->db->where("no_bed", $this->input->post("no_bed"));
            $this->db->update("kamar", $data);
            $t = $this->db->get_where("tarif_inap", ["kode_tindakan" => "kmr"]);
            if ($t->num_rows() > 0) {
                $t = $t->row();
                switch ($this->input->post('kode_kelas')) {
                    case '01':
                        $tarif = $t->supervip_deluxe;
                        break;
                    case '02':
                        $tarif = $t->supervip_premium;
                        break;
                    case '03':
                        $tarif = $t->supervip_executive;
                        break;
                    case '04':
                        $tarif = $t->supervip;
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
            $data = array(
                "kode_ruangan" => $this->input->post('kode_ruangan'),
                "kode_kelas" => $this->input->post('kode_kelas'),
                "kode_kamar" => $this->input->post('kode_kamar'),
                "no_bed" => $this->input->post('no_bed')
            );
            $this->db->where("no_reg", $this->input->post('no_reg'));
            $this->db->where("no_rm", $this->input->post('no_pasien'));
            $this->db->update("pasien_inap", $data);
            $id = date("dmyHis");
            $n = $this->db->get_where("kasir_inap", ["no_reg" => $this->input->post('no_reg'), "kode_tarif" => "kmr", "tanggal" => date("Y-m-d", strtotime($this->input->post('tgl_pindah')))]);
            if ($n->num_rows() <= 0) {
                $d = array(
                    "id" => $id,
                    "no_reg" => $this->input->post('no_reg'),
                    "kode_tarif" => "kmr",
                    "jumlah" => $tarif,
                    "qty" => 1,
                    "tanggal" => date("Y-m-d", strtotime($this->input->post('tgl_pindah')))
                );
                $this->db->insert("kasir_inap", $d);
            } else {
                $d = array(
                    "jumlah" => $tarif,
                );
                $this->db->where("no_reg", $this->input->post('no_reg'));
                $this->db->where("kode_tarif", "kmr");
                $this->db->where("tanggal", date("Y-m-d", strtotime($this->input->post('tgl_pindah'))));
                $this->db->update("kasir_inap", $d);
            }
            $d = array(
                "id" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "jam" => date("H:i:s", strtotime($this->input->post("jam_pindah"))),
                "tanggal" => date("Y-m-d", strtotime($this->input->post("tgl_pindah"))),
                "kode_ruangan_lama" => $this->input->post("kode_ruangan_lama"),
                "kode_kelas_lama" => $this->input->post("kode_kelas_lama"),
                "kode_kamar_lama" => $this->input->post("kode_kamar_lama"),
                "no_bed_lama" => $this->input->post("no_bed_lama"),
                "kode_ruangan" => $this->input->post("kode_ruangan"),
                "kode_kelas" => $this->input->post("kode_kelas"),
                "kode_kamar" => $this->input->post("kode_kamar"),
                "no_bed" => $this->input->post("no_bed")
            );
            $this->db->insert("pindahkamar", $d);
            return "success-Data berhasil di input";
        } else {
            return "danger-Kamar sudah terisi";
        }
    }
    function getcetakpasien($no_pasien)
    {
        $this->db->select("p.*,jk.keterangan as jenis_kelamin,k.nama as status_kawin,pen.pendidikan as pendidikan,g.keterangan as nama_golongan, p.no_pasien as no_rekmed, p.tanggal as trk, per.nama as nama_perusahaan, pan.keterangan as pangkat,pek.pekerjaan");
        $this->db->join("jenis_kelamin jk", "jk.jenis_kelamin=p.jenis_kelamin", "left");
        $this->db->join("kawin k", "k.kode=p.status_kawin", "left");
        $this->db->join("pendidikan pen", "pen.idx=p.pendidikan", "left");
        $this->db->join("pekerjaan pek", "pek.idx=p.pekerjaan", "left");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
        $this->db->join("perusahaan per", "per.kode = p.perusahaan", "left");
        $this->db->join("pangkat pan", "pan.id_pangkat = p.id_pangkat", "left");
        $this->db->where("p.no_pasien", $no_pasien);
        $q = $this->db->get("pasien p");
        return $q->row();
    }
    function getcetakinap($no_rm, $no_reg)
    {
        $this->db->select("p.*,pang.keterangan as nama_pangkat,jk.keterangan as jenis_kelamin,pi.no_reg, per.nama as nama_perusahaan,dok.nama_dokter, kls.nama_kelas, pi.pengirim,pi.prosedur_masuk,pi.cara_masuk, pi.diagnosa_masuk, pi.alergi, r.nama_ruangan, kmr.nama_kamar,kmr.no_bed, gp.keterangan as golpas, k.nama as status_kawin,pen.pendidikan as pendidikan,pi.penanggung_jawab,pi.telepon_pj,prov.name as nama_provinsi,reg.name as nama_kota,d.name as nama_kecamatan, v.name as nama_kelurahan,mcd.nama as mcd_nama,pi.catatan_pasien, pi.jam_masuk, pi.tgl_masuk");
        $this->db->join("pasien_inap pi", "pi.no_rm=p.no_pasien", "left");
        $this->db->join("jenis_kelamin jk", "jk.jenis_kelamin=p.jenis_kelamin", "left");
        $this->db->join("kawin k", "k.kode=p.status_kawin", "left");
        $this->db->join("pendidikan pen", "pen.idx=p.pendidikan", "left");
        $this->db->join("pekerjaan pek", "pek.idx=p.pekerjaan", "left");
        $this->db->join("gol_pasien gp", "gp.id_gol = p.id_gol");
        $this->db->join("kelas kls", "kls.kode_kelas = pi.kode_kelas", "left");
        $this->db->join("ruangan r", "r.kode_ruangan = pi.kode_ruangan", "left");
        $this->db->join("perusahaan per", "per.kode = p.perusahaan", "left");
        $this->db->join("dokter dok", "dok.id_dokter = pi.dokter", "left");
        $this->db->join("kamar kmr", "kmr.kode_kamar = pi.kode_kamar and kmr.no_bed=pi.no_bed", "left");
        $this->db->join("provinces prov", "prov.id=p.id_provinsi", "left");
        $this->db->join("regencies reg", "reg.id=p.id_kota", "left");
        $this->db->join("districts d", "d.id=p.id_kecamatan", "left");
        $this->db->join("villages v", "v.id=p.id_kelurahan", "left");
        $this->db->join("pangkat pang", "pang.id_pangkat=p.id_pangkat", "left");
        $this->db->join("master_icd mcd", "mcd.kode=pi.diagnosa_masuk", "left");
        $this->db->where("pi.no_rm", $no_rm);
        $this->db->where("pi.no_reg", $no_reg);
        $q = $this->db->get("pasien p");
        return $q->row();
    }
    function getcetakberitamasukperawatan($no_rm, $no_reg)
    {
        $this->db->select("p.*,pang.keterangan as nama_pangkat,jk.keterangan as jenis_kelamin,pi.no_reg, per.nama as nama_perusahaan,dok.nama_dokter, kls.nama_kelas, pi.pengirim,pi.prosedur_masuk,pi.cara_masuk, pi.diagnosa_masuk, pi.alergi, r.nama_ruangan, kmr.nama_kamar,kmr.no_bed, gp.keterangan as golpas, k.nama as status_kawin,pen.pendidikan as pendidikan,pi.penanggung_jawab,pi.telepon_pj,prov.name as nama_provinsi,reg.name as nama_kota,d.name as nama_kecamatan, v.name as nama_kelurahan,mcd.nama as mcd_nama,pi.catatan_pasien, pi.jam_masuk, pi.tgl_masuk");
        $this->db->join("pasien_inap pi", "pi.no_rm=p.no_pasien", "left");
        $this->db->join("jenis_kelamin jk", "jk.jenis_kelamin=p.jenis_kelamin", "left");
        $this->db->join("kawin k", "k.kode=p.status_kawin", "left");
        $this->db->join("pendidikan pen", "pen.idx=p.pendidikan", "left");
        $this->db->join("pekerjaan pek", "pek.idx=p.pekerjaan", "left");
        $this->db->join("gol_pasien gp", "gp.id_gol = p.id_gol");
        $this->db->join("kelas kls", "kls.kode_kelas = pi.kode_kelas", "left");
        $this->db->join("ruangan r", "r.kode_ruangan = pi.kode_ruangan", "left");
        $this->db->join("perusahaan per", "per.kode = p.perusahaan", "left");
        $this->db->join("dokter dok", "dok.id_dokter = pi.dokter", "left");
        $this->db->join("kamar kmr", "kmr.kode_kamar = pi.kode_kamar and kmr.no_bed=pi.no_bed", "left");
        $this->db->join("provinces prov", "prov.id=p.id_provinsi", "left");
        $this->db->join("regencies reg", "reg.id=p.id_kota", "left");
        $this->db->join("districts d", "d.id=p.id_kecamatan", "left");
        $this->db->join("villages v", "v.id=p.id_kelurahan", "left");
        $this->db->join("pangkat pang", "pang.id_pangkat=p.id_pangkat", "left");
        $this->db->join("master_icd mcd", "mcd.kode=pi.diagnosa_masuk", "left");
        $this->db->where("pi.no_rm", $no_rm);
        $this->db->where("pi.no_reg", $no_reg);
        $q = $this->db->get("pasien p");
        return $q->row();
    }
    function getcetakrekmed($id)
    {
        $this->db->select("p.*,jk.keterangan as jenis_kelamin,k.nama as status_kawin,pen.pendidikan as pendidikan,g.keterangan as nama_golongan, pr.no_pasien as no_rekmed, pr.tanggal as trk, per.nama as nama_perusahaan, pr.alergi, pan.keterangan as pangkat");
        $this->db->join("pasien_ralan pr", "pr.no_pasien=p.no_pasien", "left");
        $this->db->join("jenis_kelamin jk", "jk.jenis_kelamin=p.jenis_kelamin", "left");
        $this->db->join("kawin k", "k.kode=p.status_kawin", "left");
        $this->db->join("pendidikan pen", "pen.idx=p.pendidikan", "left");
        // $this->db->join("pekerjaan pek","pek.idx=p.pekerjaan","left");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
        $this->db->join("perusahaan per", "per.kode = p.perusahaan", "left");
        $this->db->join("pangkat pan", "pan.id_pangkat = p.id_pangkat", "left");
        $this->db->where("pr.no_pasien", $id);
        $q = $this->db->get("pasien p");
        return $q->row();
    }
    function getsuku()
    {
        return $this->db->get("suku");
    }
    function getwilayah($nama)
    {
        $this->db->select("p.id AS id_provinsi,p.name AS provinsi,r.id AS id_kota,r.name AS kota,d.id AS id_kecamatan,d.name AS kecamatan,v.id AS id_kelurahan,v.name AS kelurahan");
        $this->db->join("regencies r", "r.province_id=p.id");
        $this->db->join("districts d", "d.regency_id=r.id");
        $this->db->join("villages v", "v.district_id=d.id");
        if ($nama != "") {
            $this->db->group_start();
            $this->db->like("p.name", $nama);
            $this->db->or_like("r.name", $nama);
            $this->db->or_like("d.name", $nama);
            $this->db->or_like("v.name", $nama);
            $this->db->group_end();
        }
        $q = $this->db->get("provinces p");
        return $q;
    }
    function gethubungankeluarga()
    {
        return $this->db->get("hubungan_keluarga");
    }
    function getkawin()
    {
        return $this->db->get("kawin");
    }
    function getstatuspembayaran()
    {
        $sql = "select * from status_pembayaran order by status_pembayaran";
        $query = $this->db->query($sql);
        return $query;
    }
    function getjenis_kelamin()
    {
        $sql = "select * from jenis_kelamin order by jenis_kelamin";
        $query = $this->db->query($sql);
        return $query;
    }
    function getstatus_keluarga()
    {
        $sql = "select * from status_keluarga order by idx";
        $query = $this->db->query($sql);
        return $query;
    }
    function getpendidikan()
    {
        $sql = "select * from pendidikan order by idx";
        $query = $this->db->query($sql);
        return $query;
    }
    function getpekerjaan()
    {
        $sql = "select * from pekerjaan order by idx";
        $query = $this->db->query($sql);
        return $query;
    }
    function getgolongan()
    {
        $sql = "select * from gol_pekerjaan where pekerjaan='PNS' order by gol";
        $query = $this->db->query($sql);
        return $query;
    }
    function getkepala_keluarga($no_kk)
    {
        $sql = "select * from pasien where no_kk='" . $no_kk . "' and iskk='Y' and id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'";
        $query = $this->db->query($sql);
        return $query;
    }
    function asal_pasien()
    {
        $sql = "select * from asal_pasien";
        $query = $this->db->query($sql);
        return $query;
    }
    function getpasien($page, $offset)
    {
        $this->db->like("nama_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("no_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("nik", $this->session->userdata("no_pasien"));
        $this->db->order_by("no_pasien");
        $query = $this->db->get("pasien_vaksin", $page, $offset);
        return $query;
    }
    function getpasien_autocomplete()
    {
        $sql = "select * from pasien ";
        $query = $this->db->query($sql);
        return $query;
    }
    function getjumlahpasien()
    {
        $this->db->like("nama_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("no_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("nik", $this->session->userdata("no_pasien"));
        $query = $this->db->get("pasien_vaksin");
        return $query->num_rows();
    }
    function getpasien_rawatjalan()
    {
        $poli_kode = $this->session->userdata("poli_kode");
        $kode_dokter = $this->session->userdata("kode_dokter");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $status_pasien = $this->session->userdata("status_pasien");
        $nama = $this->session->userdata("nama");
        $this->db->select("pr.*,p.nama_pasien as nama_pasien,p.jk as jenis_kelamin");
        $this->db->group_start();
        $this->db->like("p.no_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("p.nik", $this->session->userdata("no_pasien"));
        $this->db->or_like("p.nama_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("pr.no_reg", $this->session->userdata("no_pasien"));
        $this->db->group_end();
        if ($this->session->userdata("status_vaksin") != "ALL") {
            $this->db->where("pr.tujuan_poli", $this->session->userdata("status_vaksin") );
        }
        if ($this->session->userdata("tempat_vaksin")!="")
          $this->db->where("pr.tempat_vaksin", $this->session->userdata("tempat_vaksin"));
        if ($tgl1 != "" || $tgl2 != "") {
            $this->db->where("date(pr.tgl_vaksin)>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("date(pr.tgl_vaksin)<=", date("Y-m-d", strtotime($tgl2)));
        }
        $this->db->join("pasien_vaksin p", "p.no_pasien=pr.no_pasien");
        $query = $this->db->get("pasien_ralan_vaksin pr");
        return $query->num_rows();
    }
    function getpoli_array()
    {
        $data = array();
        $q = $this->db->get("poliklinik");
        foreach ($q->result() as $key) {
            $data[$key->kode] = $key->keterangan;
        }
        return $data;
    }
    function getpasien_rawatinap()
    {
        $kode_kelas = $this->session->userdata("kode_kelas");
        $kode_ruangan = $this->session->userdata("kode_ruangan");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $nama = $this->session->userdata("nama");
        $this->db->select("i.*,r.nama_ruangan,k.nama_kelas, g.keterangan as gol_pasien");
        // if ($no_pasien!="") {
        //     $no_pasien = "000000".$no_pasien;
        //     $this->db->where("i.no_rm",substr($no_pasien,-6));
        // }
        // if ($nama!="") {
        //     $this->db->like("p.nama_pasien",$nama);
        // }
        // if ($no_reg!="") {
        //     $this->db->where("no_reg",$no_reg);
        // }
        $this->db->group_start();
        $this->db->like("i.no_rm", $no_pasien);
        $this->db->or_like("no_reg", $no_pasien);
        $this->db->or_like("no_bpjs", $no_pasien);
        $this->db->or_like("no_sjp", $no_pasien);
        $this->db->or_like("p.nama_pasien", $no_pasien);
        $this->db->or_like("p.nip", $this->session->userdata("no_pasien"));
        $this->db->or_like("p.ktp", $this->session->userdata("no_pasien"));
        $this->db->group_end();
        if ($kode_kelas != "") {
            $this->db->where("i.kode_kelas", $kode_kelas);
        }
        if ($kode_ruangan != "") {
            $this->db->where("i.kode_ruangan", $kode_ruangan);
        }
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("i.tgl_masuk>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("i.tgl_masuk<=", date("Y-m-d", strtotime($tgl2)));
        }
        $this->db->order_by("no_reg", "desc");
        $this->db->join("pasien p", "p.no_pasien=i.no_rm");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
        $this->db->order_by("no_reg,no_rm");
        $query = $this->db->get("pasien_inap i");
        return $query->num_rows();
    }
    function getpasien_ralan($page, $offset)
    {
        $poli_kode = $this->session->userdata("poli_kode");
        $kode_dokter = $this->session->userdata("kode_dokter");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        // $tgl1 = ($this->session->userdata("tgl1")=="" ? date("Y-m-d") : $this->session->userdata("tgl1"));
        // $tgl2 = ($this->session->userdata("tgl2")=="" ? date("Y-m-d") : $this->session->userdata("tgl2"));
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $status_pasien = $this->session->userdata("status_pasien");
        $nama = $this->session->userdata("nama");
        $this->db->select("pr.*,p.tgl_lahir,p.nik,p.nohp,p.nama_pasien as nama_pasien,p.alamat, g.keterangan as gol_pasien, p.jk as jenis_kelamin,p.nohp");
        $this->db->group_start();
        $this->db->like("p.no_pasien", $no_pasien);
        $this->db->or_like("p.nama_pasien", $no_pasien);
        $this->db->or_like("p.nik", $this->session->userdata("no_pasien"));
        $this->db->or_like("pr.no_reg", $this->session->userdata("no_pasien"));
        $this->db->group_end();
        if ( $this->session->userdata("status_vaksin")!= "ALL") {
            $this->db->where("pr.tujuan_poli", $this->session->userdata("status_vaksin"));
        }
        if ($this->session->userdata("tempat_vaksin")!="")
          $this->db->where("pr.tempat_vaksin", $this->session->userdata("tempat_vaksin"));
        if ($status_pasien != "ALL") {
            $this->db->where("pr.status_pasien", $status_pasien);
        }
        if ($tgl1 != "" || $tgl2 != "") {
            $this->db->where("date(pr.tgl_vaksin)>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("date(pr.tgl_vaksin)<=", date("Y-m-d", strtotime($tgl2)));
        }
        $this->db->order_by("pr.no_pasien", "desc");
        $this->db->join("pasien_vaksin p", "p.no_pasien=pr.no_pasien");
        $this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "left");
        $query = $this->db->get("pasien_ralan_vaksin pr", $page, $offset);
        return $query;
    }
    function getpasien_ralan_detail($no_reg)
    {
        $this->db->select("date(tanggal) as tanggal,time(tanggal) as jam");
        return $this->db->get_where("pasien_ralan_vaksin", ["no_reg" => $no_reg])->row();
    }
    function getpasien_inap($page, $offset)
    {
        $kode_kelas = $this->session->userdata("kode_kelas");
        $kode_ruangan = $this->session->userdata("kode_ruangan");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $nama = $this->session->userdata("nama");
        $indeks = $this->session->userdata("indeks");
        $this->db->select("i.*,o.kode_oka,p.berat_badan,p.nama_pasien,p.telpon, r.nama_ruangan,r.kode_bagian,k.nama_kelas,p.alamat,p.no_bpjs, g.keterangan as gol_pasien, sp.keterangan as ket_pulang, p.jenis_kelamin as jenis_kelamin");
        // if ($no_pasien!="") {
        // 	$no_pasien = "000000".$no_pasien;
        // 	$this->db->where("i.no_rm",substr($no_pasien,-6));
        // }
        // if ($nama!="") {
        // 	$this->db->like("p.nama_pasien",$nama);
        // }
        // if ($no_reg!="") {
        // 	$this->db->where("no_reg",$no_reg);
        // }
        $this->db->group_start();
        $this->db->like("i.no_rm", $no_pasien);
        $this->db->or_like("i.no_reg", $no_pasien);
        $this->db->or_like("no_bpjs", $no_pasien);
        $this->db->or_like("no_sjp", $no_pasien);
        $this->db->or_like("p.nama_pasien", $no_pasien);
        $this->db->or_like("p.nip", $this->session->userdata("no_pasien"));
        $this->db->or_like("p.ktp", $this->session->userdata("no_pasien"));
        $this->db->group_end();
        if ($kode_kelas != "") {
            $this->db->where("i.kode_kelas", $kode_kelas);
        }
        if ($kode_ruangan != "") {
            $this->db->where("i.kode_ruangan", $kode_ruangan);
        }
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("i.tgl_masuk>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("i.tgl_masuk<=", date("Y-m-d", strtotime($tgl2)));
        }
        if ($indeks != "") {
            $this->db->where("i.no_reg IS NULL");
        }
        // $this->db->order_by("no_reg","desc");
        $this->db->join("pasien p", "p.no_pasien=i.no_rm");
        $this->db->join("oka o", "o.no_reg=i.no_reg", "left");
        // $this->db->join("indeks_inap_icd10 in","in.no_reg=i.no_reg","left");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
        $this->db->join("status_pulang sp", "sp.id=i.status_pulang", "left");
        $this->db->order_by("i.no_reg,i.no_rm", "desc");
        $this->db->group_by("i.no_reg");
        $query = $this->db->get("pasien_inap i", $page, $offset);
        return $query;
    }
    function getpersetujuan($q)
    {
        $data = array();
        foreach ($q->result() as $key) {
            $this->db->select("petugas_rm");
            $n = $this->db->get_where("persetujuan", ["no_reg" => $key->no_reg, "isnull(ttd_saksi)" => 0]);
            if ($n->num_rows() > 0) {
                $r = $n->row();
                $data[$key->no_reg] = $r->petugas_rm;
            }
        }
        return $data;
    }
    function getlistpasien($id_puskesmas, $no_kk, $iskk = NULL)
    {
        $sql = "select * from pasien  where id_puskesmas='" . $id_puskesmas . "' and no_kk='" . $no_kk . "' ";
        if ($iskk <> "") {
            $sql .= " and iskk='" . $iskk . "' ";
        }

        $query = $this->db->query($sql);
        return $query;
    }
    function getdetailpasien($id_pasien)
    {
        $this->db->where("no_pasien", $id_pasien);
        $q = $this->db->get("pasien_vaksin p");
        return $q->row();
    }
    function datapasien($kode, $cari)
    {
        $sql = "select * from pasien where " . $kode . "='" . $cari . "'";
        $query = $this->db->query($sql);
        return $query->row();
    }
    function getdetailpendaftar($id_pendaftaran)
    {
        $sql = "select a.*,a.tgl_lahir,p.nama_puskesmas,b.nama_kecamatan,c.nama_kelurahan,d.nama_rw from pendaftaran e
				left join pasien a on(a.id_pasien=e.id_pasien)
				left join puskesmas p on(p.id_puskesmas=a.id_puskesmas)
		        left join kecamatan b on(b.id_kecamatan=a.id_kecamatan)
				left join kelurahan c on(c.id_kelurahan=a.id_kelurahan)
				left join rw d on(d.id_rw=a.id_rw)
				where e.id_pendaftaran='" . $id_pendaftaran . "'";
        $query = $this->db->query($sql);
        return $query;
    }
    function getawaldaftar($id_pasien, $id_layanan)
    {
        $sql = "select *,min(id_pendaftaran) from pendaftaran
				where id_pasien='" . $id_pasien . "' and id_layanan='" . $id_layanan . "'";
        $query = $this->db->query($sql);
        return $query;
    }
    function simpanpendaftaran()
    {
        $row = $this->db->query("select count(*) as jumlah from pendaftaran where tanggal=now()")->row();
        if ($row->jumlah == "") $no_urut = 1;
        else $no_urut = $row->jumlah + 1;
        $sql = "insert into pendaftaran set
			  tanggal='" . date('Y-m-d', strtotime($this->input->post('tanggal'))) . "',
			  id_layanan='" . $this->input->post('id_layanan') . "',
			  id_pasien='" . $this->input->post('id_pasien') . "',
			  id_puskesmas='" . $this->input->post('id_puskesmas') . "',
			  status_pembayaran='" . $this->input->post('status_pembayaran') . "',
			  no_urut='" . $no_urut . "',
			  asal_pasien='" . $this->input->post('asal_pasien') . "'";
        $this->db->query($sql);
        return "success-Data berhasil disimpan...";
    }
    function getno_kk_baru()
    {
        $sql = "select max(no_kk) as no_kk from pasien where id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'";
        $q = $this->db->query($sql);
        $row = $q->row();
        if ($q->num_rows() <= 0) $no_kk = '00001';
        else {
            $no_kk = $row->no_kk + 1;
            $no_kk = str_pad($no_kk, 5, "0", STR_PAD_LEFT);
        }
        return $no_kk;
    }
    function getno_pasien_baru()
    {
        for ($i = 1; $i <= 300000; $i++) {
            $n = substr("000000" . $i, -6, 6);
            $q = $this->db->get_where("pasien", array("no_pasien" => $n));

            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function getno_pasien_baru2()
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
    function simpanpasienbaru($action)
    {
        switch ($action) {
            case 'simpan':
                $no_pasien = $this->getno_pasien_baru2();
                $data1 = array(
                    'id_pasien' => date("dmyHis"),
                    'no_pasien' => $no_pasien,
                    'nama_pasien' => $this->input->post('nama_pasien'),
                    'agama' => $this->input->post('agama'),
                    'nik' => $this->input->post('nik'),
                    'alamat' => $this->input->post('alamat'),
                    'id_kelurahan' => $this->input->post('id_kelurahan'),
                    'id_kecamatan' => $this->input->post('id_kecamatan'),
                    'id_kota' => $this->input->post('id_kota'),
                    'id_propinsi' => $this->input->post('id_propinsi'),
                    'id_gol' => $this->input->post('gol_pas'),
                    'tgl_lahir' => date('Y-m-d', strtotime($this->input->post('tgl_lahir'))),
                );
                $this->db->insert("pasien_vaksin", $data1);
                break;
            case 'edit':
                $no_pasien = $this->input->post('idlama');
                $data = array(
                    'nama_pasien' => $this->input->post('nama_pasien'),
                    'agama' => $this->input->post('agama'),
                    'nik' => $this->input->post('nik'),
                    'alamat' => $this->input->post('alamat'),
                    'id_kota' => $this->input->post('id_kota'),
                    'id_kecamatan' => $this->input->post('id_kecamatan'),
                    'id_kota' => $this->input->post('id_kota'),
                    'id_propinsi' => $this->input->post('id_propinsi'),
                    'id_gol' => $this->input->post('gol_pas'),
                    'tgl_lahir' => date('Y-m-d', strtotime($this->input->post('tgl_lahir'))),
                );
                $this->db->where("no_pasien", $no_pasien);
                $this->db->update("pasien_vaksin", $data);
                break;
        }
        return "success-Data berhasil di input-" . $no_pasien;
    }
    function simpanpasienbaru_inap($action)
    {
        $file_kakikiri = str_replace('data:image/jpg;base64,', '', $this->input->post("sourcefoto_kakikiri"));
        $file_kakikanan = str_replace('data:image/jpg;base64,', '', $this->input->post("sourcefoto_kakikanan"));
        $file_ibujari_kiri = str_replace('data:image/jpg;base64,', '', $this->input->post("sourcefoto_ibujari_kiri"));
        $file_ibujari_kanan = str_replace('data:image/jpg;base64,', '', $this->input->post("sourcefoto_ibujari_kanan"));
        $this->db->where("no_reg=no_rm");
        $this->db->where("no_reg", $this->input->post('idlama'));
        $q = $this->db->get_where("pasien_inap");
        if ($q->num_rows() > 0) {
            $gp = $this->db->get_where("gol_pasien", ["id_gol" => $this->input->post('gol_pas')])->row()->status;
            $no_pasien = $this->getno_pasien_baru();
            $this->db->where("no_pasien", $this->input->post('idlama'));
            $this->db->update("pasien", ["no_pasien" => $no_pasien]);
            $this->db->where("no_rm", $this->input->post('idlama'));
            $this->db->update("pasien_inap", ["no_rm" => $no_pasien, "nama_pasien" => $this->input->post('nama_pasien'), "id_gol" => $this->input->post('gol_pas'), "status_bayar" => $gp, 'nama_pasien' => $this->input->post('nama_pasien')]);
            $this->db->where("no_rm", $this->input->post('idlama'));
            $this->db->update("pasien_triage", ["no_rm" => $no_pasien]);
            $this->db->where("no_rm", $this->input->post('idlama'));
            $this->db->update("pasien_igdinap", ["no_rm" => $no_pasien]);
        } else {
            $no_pasien = $this->input->post('idlama');
        }
        $data = array(
            "negara" => $this->input->post('negara'),
            "suku" => $this->input->post('suku'),
            "ktp" => $this->input->post('ktp'),
            "nama_pasien" => $this->input->post('nama_pasien'),
            "agama" => $this->input->post('agama'),
            "no_bpjs" => $this->input->post('no_bpjs'),
            "nip" => $this->input->post('nip'),
            "nama_pasangan" => $this->input->post('nama_pasangan'),
            "tgllahir_ayah" => date('Y-m-d', strtotime($this->input->post('tgllahir_ayah'))),
            "pekerjaan_ayah" => $this->input->post('pekerjaan_ayah'),
            "ibu" => $this->input->post('ibu'),
            "tgllahir_ibu" => date('Y-m-d', strtotime($this->input->post('tgllahir_ibu'))),
            "pekerjaan_ibu" => $this->input->post('pekerjaan_ibu'),
            "status_kawin" => $this->input->post('status_kawin'),
            "alamat" => $this->input->post('alamat'),
            "jenis_kelamin" => $this->input->post('jenis_kelamin'),
            "id_kelurahan" => $this->input->post('id_kelurahan'),
            "id_kecamatan" => $this->input->post('id_kecamatan'),
            "id_kota" => $this->input->post('id_kota'),
            "id_provinsi" => $this->input->post('id_provinsi'),
            "pendidikan" => $this->input->post('pendidikan'),
            "umur" => $this->input->post('umur'),
            "perusahaan" => $this->input->post('kode_perusahaan'),
            "hubungan_keluarga" => $this->input->post('hubungan_keluarga'),
            "status_pembayaran" => $this->input->post('status_pembayaran'),
            "pekerjaan" => $this->input->post('pekerjaan'),
            "tahun_lahir" => date('Y', strtotime($this->input->post('tgl_lahir'))),
            "tanggal" => date('Y-m-d', strtotime($this->input->post('tanggal'))),
            "tgl_lahir" => date('Y-m-d', strtotime($this->input->post('tgl_lahir'))),
            "id_ketcabang" => $this->input->post('ketcabang'),
            "telpon" => $this->input->post('telpon'),
            "id_gol" => $this->input->post('gol_pas'),
            "id_pangkat" => $this->input->post('pangkat'),
            "id_kesatuan" => $this->input->post('kesatuan'),
            "id_cabang" => $this->input->post('cabang'),
            "id_ketcabang" => $this->input->post('ketcabang'),
            "berat_badan" => $this->input->post('berat_badan'),
            "panjang_badan" => $this->input->post('panjang_badan'),
            "kelahiran_ke" => $this->input->post('kelahiran'),
            "tindakan_bayi" => $this->input->post('tindakan'),
            "kembar" => $this->input->post('kembar'),
            "kelainan_bawaan" => $this->input->post('kelainan_bawaan'),
            "lingkar_kepala" => $this->input->post('lingkar_kepala'),
            "lingkar_dada" => $this->input->post('lingkar_dada'),
            "lingkar_lengan" => $this->input->post('lingkar_lengan'),
            "lingkar_perut" => $this->input->post('lingkar_perut'),
            "kakikiri" => $file_kakikiri,
            "kakikanan" => $file_kakikanan,
            "ibujari_kiri" => $file_ibujari_kiri,
            "ibujari_kanan" => $file_ibujari_kanan,
            "gol" => $this->input->post('gol_pekerjaan'),
            'jamlahir' => $this->input->post('jamlahir'),
            'nikpasangan' => $this->input->post('nikpasangan'),
            'nikibu' => $this->input->post('nikibu'),
        );
        $this->db->where("no_pasien", $no_pasien);
        $this->db->update("pasien", $data);
        return "success-Data berhasil di input-" . $no_pasien;
    }
    function rekaplayanan($tgl1, $tgl2, $jenis, $umur)
    {
        $sql = "select a.id_layanan, count(*) as jumlah from pendaftaran a
				inner join pasien b on (b.id_pasien=a.id_pasien)
				where a.tanggal between '" . date('Y-m-d', strtotime($tgl1)) . "' and '" . date('Y-m-d', strtotime($tgl2)) . "'";
        switch ($jenis) {
            case 'lebihbesar':
                $sql .= " and year(sysdate())-tahun_lahir>" . $umur;
                break;
            case 'lebihkecil':
                $sql .= " and year(sysdate())-tahun_lahir<" . $umur;
                break;
            case 'samadengan':
                $sql .= " and year(sysdate())-tahun_lahir=" . $umur;
                break;
        }
        $sql .= " group by a.id_layanan";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $jml[$row->id_layanan] = $row->jumlah;
            }
        } else $jml = 0;
        return $jml;
    }
    function rekaplayanan_pasien($id_pasien)
    {
        $sql = "select a.id_layanan, count(*) as jumlah from pendaftaran a
				inner join pasien b on (b.id_pasien=a.id_pasien)
				where a.id_pasien='" . $id_pasien . "'";
        $sql .= " group by a.id_layanan";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $jml[$row->id_layanan] = $row->jumlah;
            }
        } else $jml = 0;
        return $jml;
    }
    function rekap_status_pembayaran($tgl1, $tgl2, $jenis, $umur)
    {
        $sql = "select a.status_pembayaran, count(*) as jumlah from pendaftaran a
				inner join pasien b on (b.id_pasien=a.id_pasien)
				where a.tanggal between '" . date('Y-m-d', strtotime($tgl1)) . "' and '" . date('Y-m-d', strtotime($tgl2)) . "'";
        switch ($jenis) {
            case 'lebihbesar':
                $sql .= " and year(sysdate())-tahun_lahir>" . $umur;
                break;
            case 'lebihkecil':
                $sql .= " and year(sysdate())-tahun_lahir<" . $umur;
                break;
            case 'samadengan':
                $sql .= " and year(sysdate())-tahun_lahir=" . $umur;
                break;
        }
        $sql .= " group by a.status_pembayaran";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $jml[$row->status_pembayaran] = $row->jumlah;
            }
        } else $jml[] = 0;
        return $jml;
    }
    function rekap_status_pembayaran_pasien($id_pasien)
    {
        $sql = "select a.status_pembayaran, count(*) as jumlah from pendaftaran a
				inner join pasien b on (b.id_pasien=a.id_pasien)
				where a.id_pasien='" . $id_pasien . "'";
        $sql .= " group by a.status_pembayaran";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $jml[$row->status_pembayaran] = $row->jumlah;
            }
        } else $jml[] = 0;
        return $jml;
    }
    function rujukan($def)
    {
        $q = $this->db->query("select * from rujukan order by rujukan");
        $html = "<option value=''>--Pilih--</option>";
        foreach ($q->result() as $row) {
            $html .= "<option value='" . $row->rujukan . "'" . ($def == $row->rujukan ? "selected" : "") . "> " . $row->rujukan . "</option>";
        }
        return $html;
    }
    function cekpasien($id_pendaftaran, $cek, $status)
    {
        $sql = "select * from pendaftaran
				where id_pendaftaran='" . $id_pendaftaran . "' and " . $cek . "='" . $status . "'";
        $q = $this->db->query($sql);
        return $q;
    }
    function listkunjungan($id_layanan, $posisi, $baris)
    {
        switch ($id_layanan) {
            case '1':
                $tgl = date('Y-m-d', strtotime($this->input->post("tgl")));
                $sql = "select p.*,b.tgl_kunjungan,p.nama_pasien,l.layanan from pendaftaran pd
						inner join pasien p on(p.id_pasien=pd.id_pasien)
						left join bpumum b on(b.id_pendaftaran=pd.id_pendaftaran)
						inner join layanan l on(l.id_layanan=pd.id_layanan)
						where b.tgl_kunjungan>pd.tanggal and p.id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'
						and b.tgl_kunjungan='" . $tgl . "'
						order by p.no_pasien limit " . $posisi . "," . $baris;
                break;
            case '3':
                $tgl = date('Y-m-d', strtotime($this->input->post("tgl")));
                $sql = "select p.*,b.tgl_kunjungan,p.nama_pasien,l.layanan from pendaftaran pd
						inner join pasien p on(p.id_pasien=pd.id_pasien)
						left join detail_antenatal_care b on(b.id_pendaftaran=pd.id_pendaftaran)
						inner join layanan l on(l.id_layanan=pd.id_layanan)
						where b.tgl_kunjungan>pd.tanggal and p.id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'
						and b.tgl_kunjungan='" . $tgl . "'
						order by p.no_pasien limit " . $posisi . "," . $baris;
                break;
            case '4':
                $tgl = date('Y-m-d', strtotime($this->input->post("tgl")));
                $sql = "select p.*,b.tgl_kunjungan,p.nama_pasien,l.layanan from pendaftaran pd
						inner join pasien p on(p.id_pasien=pd.id_pasien)
						left join detail_antenatal_care b on(b.id_pendaftaran=pd.id_pendaftaran)
						inner join layanan l on(l.id_layanan=pd.id_layanan)
						where b.tgl_kunjungan>pd.tanggal and p.id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'
						and b.tgl_kunjungan='" . $tgl . "'
						order by p.no_pasien limit " . $posisi . "," . $baris;
                break;
            case '5':
                $tgl = date('Y-m-d', strtotime($this->input->post("tgl")));
                $sql = "select p.*,b.tgl_kunjungan,p.nama_pasien,l.layanan from pendaftaran pd
						inner join pasien p on(p.id_pasien=pd.id_pasien)
						left join detail_antenatal_care b on(b.id_pendaftaran=pd.id_pendaftaran)
						inner join layanan l on(l.id_layanan=pd.id_layanan)
						where b.tgl_kunjungan>pd.tanggal and p.id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'
						and b.tgl_kunjungan='" . $tgl . "'
						order by p.no_pasien limit " . $posisi . "," . $baris;
                break;
        }
        $q = $this->db->query($sql);
        return $q;
    }
    function jumlah_listkunjungan($id_layanan)
    {
        switch ($id_layanan) {
            case '1':
                $tgl = date('Y-m-d', strtotime($this->input->post("tgl")));
                $sql = "select b.tgl_kunjungan,p.nama_pasien,l.layanan from pendaftaran pd
						inner join pasien p on(p.id_pasien=pd.id_pasien)
						left join bpumum b on(b.id_pendaftaran=pd.id_pendaftaran)
						inner join layanan l on(l.id_layanan=pd.id_layanan)
						where b.tgl_kunjungan>pd.tanggal and p.id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'
						and b.tgl_kunjungan='" . $tgl . "'";
                break;
            case '3':
                $tgl = date('Y-m-d', strtotime($this->input->post("tgl")));
                $sql = "select b.tgl_kunjungan,p.nama_pasien,l.layanan from pendaftaran pd
						inner join pasien p on(p.id_pasien=pd.id_pasien)
						left join detail_antenatal_care b on(b.id_pendaftaran=pd.id_pendaftaran)
						inner join layanan l on(l.id_layanan=pd.id_layanan)
						where b.tgl_kunjungan>pd.tanggal and p.id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'
						and b.tgl_kunjungan='" . $tgl . "'";
                break;
            case '4':
                $tgl = date('Y-m-d', strtotime($this->input->post("tgl")));
                $sql = "select b.tgl_kunjungan,p.nama_pasien,l.layanan from pendaftaran pd
						inner join pasien p on(p.id_pasien=pd.id_pasien)
						left join detail_antenatal_care b on(b.id_pendaftaran=pd.id_pendaftaran)
						inner join layanan l on(l.id_layanan=pd.id_layanan)
						where b.tgl_kunjungan>pd.tanggal and p.id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'
						and b.tgl_kunjungan='" . $tgl . "'";
                break;
            case '5':
                $tgl = date('Y-m-d', strtotime($this->input->post("tgl")));
                $sql = "select b.tgl_kunjungan,p.nama_pasien,l.layanan from pendaftaran pd
						inner join pasien p on(p.id_pasien=pd.id_pasien)
						left join detail_antenatal_care b on(b.id_pendaftaran=pd.id_pendaftaran)
						inner join layanan l on(l.id_layanan=pd.id_layanan)
						where b.tgl_kunjungan>pd.tanggal and p.id_puskesmas='" . $this->session->userdata('id_puskesmas') . "'
						and b.tgl_kunjungan='" . $tgl . "'";
                break;
        }
        $q = $this->db->query($sql);
        return $q;
    }
    function umur($tgl1, $tgl2)
    {
        $date1 = $tgl1;
        $date2 = $tgl2;

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        $umur = $years . "  tahun " . $months . " bulan " . $days . " hari";
        return $umur;
    }
    function batalperiksa($id_pendaftaran)
    {
        $sql = "delete from pendaftaran where id_pendaftaran='" . $id_pendaftaran . "'";
        $this->db->query($sql);
        return "danger-Data berhasil dihapus..";
    }
    function gettindakan($posisi, $baris)
    {
        $cond = "";
        $nama_tindakan = $this->input->post("nama_tindakan");
        if ($nama_tindakan != "") {
            $cond .= " where nama_tindakan like '%" . $nama_tindakan . "%'";
        }
        $sql = "select * from tindakan " . $cond . " order by nama_tindakan limit " . $posisi . "," . $baris;
        $query = $this->db->query($sql);
        return $query;
    }
    function getjumlahtindakan()
    {
        $cond = "";
        $nama_tindakan = $this->input->post("nama_tindakan");
        if ($nama_tindakan != "") {
            $cond .= " where nama_tindakan like '%" . $nama_tindakan . "%'";
        }
        $sql = "select count(*) as jumlah from tindakan " . $cond;
        $query = $this->db->query($sql);
        return $query;
    }
    function gettindakandetail($id)
    {
        $sql = "select * from tindakan where id_tindakan='" . $id . "'";
        $query = $this->db->query($sql);
        return $query;
    }
    function simpantindakan($action)
    {
        switch ($action) {
            case 'simpan':
                $sql = "insert into tindakan set
								nama_tindakan='" . strtoupper($this->input->post('nama_tindakan')) . "',
								karcis='" . $this->input->post('karcis') . "'";
                break;
            case 'edit':
                $sql = "update tindakan set
								nama_tindakan='" . strtoupper($this->input->post('nama_tindakan')) . "',
								karcis='" . $this->input->post('karcis') . "'
								where id_tindakan='" . $this->input->post('idlama') . "'";
                break;
        }
        $this->db->query($sql);
        $msg  = "success-Data berhasil di input";
        return $msg;
    }
    function hapustindakan($id)
    {
        $sql = "delete from tindakan where id_tindakan='" . $id . "'";
        $this->db->query($sql);
        $msg  = "danger-Data berhasil di hapus";
        return $msg;
    }
    // function getkecamatan(){
    // 	$this->db->order_by("nama_kecamatan","asc");
    //        $query = $this->db->get("kecamatan");
    // 	return $query;
    // }
    function getgolpasien()
    {
        // $this->db->order_by('keterangan','ASC');
        $q = $this->db->get("gol_pasien");
        return $q;
    }
    function getkesatuan()
    {
        $q = $this->db->get("kesatuan");
        return $q;
    }
    function getpangkat($id)
    {
        $this->db->where("id_gol", $id);
        $q = $this->db->get("pangkat");
        return $q->result();
    }
    function getcabang()
    {
        $q = $this->db->get("cabang");
        return $q;
    }
    function getketcabang($id)
    {
        $this->db->order_by("id_cabang,id_ketcabang");
        $this->db->where('id_cabang', $id);
        $q = $this->db->get("ket_cabang");
        return $q->result();
    }
    function getrawat_jalan($no_reg)
    {
        $this->db->select("p.*,pa.nama_pasien ,d.nama_dokter, pol.keterangan as nama_poli");
        $this->db->join("dokter d", "d.id_dokter=p.dokter_poli", "inner");
        $this->db->join("pasien pa", "pa.no_pasien=p.no_pasien", "left");
        $this->db->join("poliklinik pol", "pol.kode=p.tujuan_poli", "left");
        $q = $this->db->get_where("pasien_ralan p", ["no_reg" => $no_reg]);
        return $q;
    }
    //   	function pangkat($pangkatId){

    // 	$pangkat="<option value='0'>--pilih--</pilih>";

    // 	$this->db->order_by('keterangan','ASC');
    // 	$p= $this->db->get_where('pangkat',array('id_pangkat'=>$pangkatId));

    // 	foreach ($p->result_array() as $data ){
    // 	$pangkat.= "<option value='$data[id_pangkat]'>$data[keterangan]</option>";
    // 	}

    // 	return $pangkat;

    // }
    function gettarif($igd, $kategori = "")
    {
        if ($igd) $this->db->where("kode_poli", "0102030");
        if ($kategori != "") $this->db->where("kategori", $kategori);
        return $this->db->get("tarif_ralan");
    }
    function gettarif_ujifungsi()
    {
        $this->db->where("kode_poli", "0102034");
        $q = $this->db->get("tarif_ralan");
        $data = array();
        foreach ($q->result() as $key) {
            $data[$key->kode_tindakan] = $key->nama_tindakan;
        }
        return $data;
    }
    function hapuspasien_inap($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $this->db->delete("pasien_inap");
    }
    function getno_antrian()
    {
        for ($i = 1; $i <= 999; $i++) {
            $n = substr("000" . $i, -3);
            $where = array(
                "dokter_poli" => $this->input->post("dokter_poli"),
                "jenis" => $this->input->post("jenis"),
                "tujuan_poli" => $this->input->post("kode_tujuan"),
                "date(tanggal)" => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                "no_antrian" => $n
            );
            $q = $this->db->get_where("pasien_ralan", $where);
            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function getjadwaldokter($id_poli)
    {
        $this->db->join("dokter d", "d.id_dokter=j.id_dokter");
        $this->db->where("j.id_poli", $id_poli);
        $q = $this->db->get("jadwal_dokter j");
        return $q;
    }
    function getnoreg_sebelumnya($no_reg)
    {
        $this->db->select("pr.*,p.keterangan");
        $this->db->join("poliklinik p", "p.kode=tujuan_poli");
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function ceknoreg($no_reg)
    {
        $this->db->select("pr.*,p.keterangan,ps.id_gol,ps.nama_pasien,ps.no_bpjs,g.keterangan, pe.nama as perusahaan, pe.kode as kode_perusahaan,p.keterangan as nama_poli,d.nama_dokter");
        $this->db->join("poliklinik p", "p.kode=tujuan_poli");
        $this->db->join("pasien ps", "ps.no_pasien=pr.no_pasien");
        $this->db->join("gol_pasien g", "g.id_gol=ps.id_gol", "left");
        $this->db->join("perusahaan pe", "pe.kode=ps.perusahaan", "left");
        $this->db->join("dokter d", "d.id_dokter=pr.dokter_poli", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function getpasien_detail($no_pasien)
    {
        $this->db->select("p.*,g.keterangan,pe.nama as perusahaan,pe.kode as kode_perusahaan,pe.nama as nama_perusahaan,p.pekerjaan,k.nama as status_kawin,pg.keterangan as nama_pangkat,ks.keterangan as nama_kesatuan");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol");
        $this->db->join("perusahaan pe", "pe.kode=p.perusahaan", "left");
        $this->db->join("pekerjaan pk", "pk.idx=p.pekerjaan", "left");
        $this->db->join("kawin k", "k.kode=p.status_kawin", "left");
        $this->db->join("pangkat pg", "pg.id_pangkat=p.id_pangkat", "left");
        $this->db->join("kesatuan ks", "ks.id_kesatuan=p.id_kesatuan", "left");
        $this->db->where("p.no_pasien", $no_pasien);
        $q = $this->db->get("pasien p");
        return $q->row();
    }
    function batalkonsul($no_reg)
    {
        $data = array('layan' => '2');
        $this->db->where("no_reg", $no_reg);
        $this->db->update("pasien_ralan", $data);

        $this->db->where("no_reg", $no_reg);
        $this->db->delete("kasir");
        return "danger-Pasien Ralan Batal di Layani";
    }
    function gettotalpasien($jenis)
    {
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        // $tgl1 = ($this->session->userdata("tgl1")=="" ? date("Y-m-d") : $this->session->userdata("tgl1"));
        // $tgl2 = ($this->session->userdata("tgl2")=="" ? date("Y-m-d") : $this->session->userdata("tgl2"));
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $status_vaksin = $this->session->userdata("status_vaksin");
        $nama = $this->session->userdata("nama");
        $this->db->select("pr.*,p.nama_pasien as nama_pasien");
        $this->db->group_start();
        $this->db->like("p.no_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("p.nama_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("pr.no_reg", $this->session->userdata("no_pasien"));
        $this->db->or_like("p.nik", $this->session->userdata("no_pasien"));
        $this->db->group_end();
        if ($status_vaksin != "ALL") {
            $this->db->where("pr.tujuan_poli", $status_vaksin);
        }
        if ($this->session->userdata("tempat_vaksin")!="")
          $this->db->where("pr.tempat_vaksin", $this->session->userdata("tempat_vaksin"));
        if ($tgl1 != "" || $tgl2 != "") {
            $this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
        }
        if ($jenis == "LAYAN") {
            $this->db->where("pr.layan", 1);
        } else
        if ($jenis == "BATAL") {
            $this->db->where("pr.layan", 2);
        }
        $this->db->join("pasien_vaksin p", "p.no_pasien=pr.no_pasien");
        $query = $this->db->get("pasien_ralan_vaksin pr");
        return $query->num_rows();
    }
    function updatetanggal($no_pasien, $no_reg, $jenis)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->no_reg) {
            switch ($jenis) {
                case 'tgl_cetakbarcode':
                    $data = array(
                        'tgl_cetakbarcode' => date("Y-m-d H:i:s"),
                    );
                    if ($q->tgl_cetakbarcode == "0000-00-00 00:00:00" || $q->tgl_cetakbarcode === null) {
                        $this->db->where("no_pasien", $no_pasien);
                        $this->db->where("no_reg", $no_reg);
                        $this->db->update("pasien_ralan", $data);
                        return "success-Sukses";
                    } else {
                        return "success-Sukses";
                    }
                    break;
                case 'tgl_scanbarcode':
                    $data = array(
                        'tgl_scanbarcode' => date("Y-m-d H:i:s"),
                    );
                    if ($q->tgl_scanbarcode == "0000-00-00 00:00:00" || $q->tgl_scanbarcode === null) {
                        $this->db->where("no_reg", $no_reg);
                        $this->db->update("pasien_ralan", $data);
                        return "success-Update berhasil";
                    } else {
                        return "danger-Sudah pernah diupdate";
                    }
                    break;
            }
        } else {
            return "danger-No Reg tidak ditemukan";
        }
    }
    function terima($no_rm, $no_reg)
    {
        $this->db->where("no_pasien", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tgl_terima == "0000-00-00 00:00:00" || $q->tgl_terima === null) {
            if ($q->tgl_scanbarcode == "0000-00-00 00:00:00" || $q->tgl_scanbarcode === null) {
                return "warning-Berkas belum di share";
            } else {
                $data = array('tgl_terima' => date("Y-m-d H:i:s"));
                $this->db->where("no_pasien", $no_rm);
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ralan", $data);
                return "success-Berkas diterima";
            }
        } else {
        }
        return "danger-Berkas sudah pernah diterima";
    }
    function terima_pasien($no_rm, $no_reg)
    {
        $this->db->where("no_pasien", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tgl_terimapasien == "0000-00-00 00:00:00" || $q->tgl_terimapasien === null) {
            $data = array('tgl_terimapasien' => date("Y-m-d H:i:s"));
            $this->db->where("no_pasien", $no_rm);
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_ralan", $data);
            return "success-Pasien diterima";
        } else {
            return "danger-Pasien sudah pernah diterima";
        }
        // return "danger-Pasien sudah pernah diterima";
    }
    function pulang($no_rm, $no_reg, $keadaan_pulang, $status_pulang)
    {
        $this->db->where("no_pasien", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tanggal_pulang == "0000-00-00 00:00:00" || $q->tanggal_pulang === NULL) {
            if ($q->tgl_terimapasien == "0000-00-00 00:00:00" || $q->tgl_terimapasien === NULL) {
                return "warning-Pasien belum diterima";
            } else {
                $data = array(
                    'tanggal_pulang' => date("Y-m-d H:i:s"),
                    'status_pulang' => $status_pulang,
                    'keadaan_pulang' => $keadaan_pulang,
                );
                $this->db->where("no_pasien", $no_rm);
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ralan", $data);
                return "success-Pasien berhasil dipulangkan";
            }
        } else {
        }
        return "danger-Pasien sudah pulang sebelumnya";
    }
    function pulang_ralan()
    {
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tanggal_pulang == "0000-00-00 00:00:00" || $q->tanggal_pulang === NULL) {
            if ($q->tgl_terimapasien == "0000-00-00 00:00:00" || $q->tgl_terimapasien === NULL) {
                return "warning-Pasien belum diterima";
            } else {
                $data = array(
                    'tanggal_pulang' => date("Y-m-d H:i:s"),
                    'jam_keluar' => date("H:i:s", strtotime($this->input->post("jam_keluar"))),
                    'jam_meninggal' => ($this->input->post("jam_meninggal") == "" ? "" : date("H:i:s", strtotime($this->input->post("jam_meninggal")))),
                    'no_sjp' => $this->input->post("no_sep"),
                    'no_surat_pulang' => $this->input->post("no_surat_pulang"),
                    'status_pulang' => $this->input->post("status_pulang"),
                    'keadaan_pulang' => $this->input->post("keadaan_pulang"),
                );
                $this->db->where("no_pasien", $this->input->post("no_pasien"));
                $this->db->where("no_reg", $this->input->post("no_reg"));
                $this->db->update("pasien_ralan", $data);
                return "success-Pasien berhasil dipulangkan";
            }
        } else {
            $data = array(
                'no_sjp' => $this->input->post("no_sep"),
                'jam_keluar' => date("H:i:s", strtotime($this->input->post("jam_keluar"))),
                'no_surat_pulang' => $this->input->post("no_surat_pulang"),
                'status_pulang' => $this->input->post("status_pulang"),
                'keadaan_pulang' => $this->input->post("keadaan_pulang"),
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("pasien_ralan", $data);
            return "danger-Pasien sudah pulang sebelumnya";
        }
    }
    function getstatus_pulang()
    {
        return $this->db->get("status_pulang");
    }
    function getkeadaan_pulang()
    {
        return $this->db->get("keadaan_pulang");
    }
    function gudang($no_rm, $no_reg)
    {
        $this->db->where("no_pasien", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tgl_gudang == "0000-00-00 00:00:00" || $q->tgl_gudang === NULL) {
            if ($q->tanggal_pulang == "0000-00-00 00:00:00" || $q->tanggal_pulang === NULL) {
                return "warning-Pasien belum pulang";
            } else {
                $data = array('tgl_gudang' => date("Y-m-d H:i:s"));
                $this->db->where("no_pasien", $no_rm);
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ralan", $data);
                return "success-Berkas diterima digudang";
            }
        } else {
            return "danger-Berkas sudah ada digundang";
        }
    }
    function layani($no_rm, $no_reg)
    {
        $this->db->where("no_pasien", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan_vaksin")->row();
        if ($q->tgl_layani == "0000-00-00 00:00:00" || $q->tgl_layani === NULL) {
            $data = array('tgl_layani' => date("Y-m-d H:i:s"));
            $this->db->where("no_pasien", $no_rm);
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_ralan_vaksin", $data);
            return "success-Pasien berhasil dilayani";
            // }
        } else {
            return "danger-Pasien sudah dilayani sebelumnya";
        }
    }
    function layani_inap($no_rm, $no_reg)
    {
        $this->db->where("no_rm", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_inap")->row();
        if ($q->tgl_layani == "0000-00-00 00:00:00" || $q->tgl_layani === NULL) {
            $data = array('tgl_layani' => date("Y-m-d H:i:s"));
            $this->db->where("no_rm", $no_rm);
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_inap", $data);
            return "success-Pasien berhasil dilayani";
        } else {
            return "danger-Pasien sudah dilayani sebelumnya";
        }
    }
    function send_inap($no_rm, $no_reg)
    {
        $this->db->where("no_rm", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_inap")->row();
        if ($q->tgl_send == "0000-00-00 00:00:00" || $q->tgl_send === NULL) {
            $data = array('tgl_send' => date("Y-m-d H:i:s"));
            $this->db->where("no_rm", $no_rm);
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_inap", $data);
            return "success-Pasien berhasil masuk kamar";
        } else {
            return "danger-Pasien sudah masuk kamar sebelumnya";
        }
    }
    function terima_ruangan($no_rm, $no_reg)
    {
        $this->db->where("no_rm", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_inap")->row();
        if ($q->tgl_terimaruangan == "0000-00-00 00:00:00" || $q->tgl_terimaruangan === NULL) {
            $data = array('tgl_terimaruangan' => date("Y-m-d H:i:s"));
            $this->db->where("no_rm", $no_rm);
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_inap", $data);
            return "success-Pasien berhasil diterima";
        } else {
            return "danger-Pasien sudah diterima sebelumnya";
        }
    }
    function getralan_detail($no_pasien, $no_reg)
    {
        $this->db->select("pr.*,p.telpon,p.no_bpjs,p.tgl_lahir,p.alamat,p.nama_pasien,pl.keterangan as poli,g.keterangan as ket_gol_pasien,g1.keterangan as ket_gol_pasien1");
        $this->db->join("pasien p", "pr.no_pasien=p.no_pasien");
        $this->db->join("poliklinik pl", "pl.kode=pr.tujuan_poli");
        $this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "left");
        $this->db->join("gol_pasien g1", "g1.id_gol=p.id_gol", "left");
        $this->db->where("pr.no_pasien", $no_pasien);
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function getlaporan_tindakan($no_pasien, $no_reg)
    {
        $this->db->select("pr.*,p.telpon,p.no_bpjs,p.tgl_lahir,p.alamat,p.nama_pasien,pl.keterangan as poli,g.keterangan as ket_gol_pasien,g1.keterangan as ket_gol_pasien1, m.nama as diagnosa, an.nama as jenis_anastesi");
        $this->db->join("pasien p", "pr.no_pasien=p.no_pasien");
        $this->db->join("poliklinik pl", "pl.kode=pr.tujuan_poli");
        $this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "left");
        $this->db->join("gol_pasien g1", "g1.id_gol=p.id_gol", "left");
        $this->db->join("master_icd m", "m.kode=pr.diagnosa", "left");
        $this->db->join("jenis_anatesi an", "an.kode=pr.jenis_anastesi", "left");
        // $this->db->join("poliklinik pol","pol.kode=pr.tujuan_poli","left");
        $this->db->where("pr.no_pasien", $no_pasien);
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function getlaporan_tindakaninap($no_pasien, $no_reg)
    {
        $this->db->select("pr.*,p.telpon,p.no_bpjs,p.tgl_lahir,p.alamat,p.nama_pasien,g.keterangan as ket_gol_pasien,g1.keterangan as ket_gol_pasien1, m.nama as diagnosa, an.nama as jenis_anastesi,r.nama_ruangan, k.nama_kelas, kam.nama_kamar");
        $this->db->join("pasien p", "pr.no_rm=p.no_pasien");
        $this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "left");
        $this->db->join("gol_pasien g1", "g1.id_gol=p.id_gol", "left");
        $this->db->join("master_icd m", "m.kode=pr.diagnosa", "left");
        $this->db->join("jenis_anatesi an", "an.kode=pr.jenis_anastesi", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=pr.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=pr.kode_kelas", "left");
        $this->db->join("kamar kam", "kam.kode_kamar=pr.kode_kamar and kam.kode_ruangan = r.kode_ruangan and k.kode_kelas = kam.kode_kelas", "left");
        // $this->db->join("jenis_anatesi an","an.kode=pr.jenis_anastesi","left");
        // $this->db->join("poliklinik pol","pol.kode=pr.tujuan_poli","left");
        // $this->db->where("pr.no_rm",$no_pasien);
        $this->db->where("pr.no_reg", $no_reg);
        $this->db->group_by("pr.no_reg");
        $q = $this->db->get("pasien_inap pr");
        if ($this->session->userdata("temptindakan")==""){
          $row = $q->row();
          $data = array();
          $tdk = explode(",",$row->tindakan_operasi);
          $tanggal = explode(",",$row->tanggal_operasi);
          $pmr = explode(",",$row->pemeriksaanke);
          $mulai = explode(",",$row->jam_mulai);
          $selesai = explode(",",$row->jam_selesai);
          $ulangan = explode(",",$row->tanggal_ulangan);
          foreach ($tdk as $key => $value) {
            $data[] = array(
              "tindakan" => $value,
              "pemeriksaanke" => $pmr[$key],
              "tanggal" => $tanggal[$key],
              "jam_masuk" => $mulai[$key],
              "jam_keluar" => $selesai[$key],
              "ulangan" => $ulangan[$key]
            );
          }
          $this->session->set_userdata("temptindakan",$data);
        }
        return $q->row();
    }
    function getcetak_laporantindakan($no_reg)
    {
        $this->db->select("pr.*,p.telpon,p.no_bpjs,p.tgl_lahir,p.alamat,p.nama_pasien,pl.keterangan as poli,g.keterangan as ket_gol_pasien,g1.keterangan as ket_gol_pasien1, m.nama as diagnosa, an.nama as jenis_anastesi, tr.nama_tindakan, ao.nama as asisten, d.nama_dokter, d.id_dokter");
        $this->db->join("pasien p", "pr.no_pasien=p.no_pasien");
        $this->db->join("poliklinik pl", "pl.kode=pr.tujuan_poli");
        $this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "left");
        $this->db->join("gol_pasien g1", "g1.id_gol=p.id_gol", "left");
        $this->db->join("master_icd m", "m.kode=pr.diagnosa", "left");
        $this->db->join("jenis_anatesi an", "an.kode=pr.jenis_anastesi", "left");
        $this->db->join("tarif_ralan tr", "tr.kode_tindakan=pr.tindakan_operasi", "left");
        $this->db->join("asisten_operasi ao", "ao.kode=pr.asisten_operasi", "left");
        $this->db->join("dokter d", "d.id_dokter=pr.dokter_operasi", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function getcetak_laporantindakaninap($no_reg)
    {
        $this->db->select("pr.*,p.telpon,p.no_bpjs,p.tgl_lahir,p.alamat,p.nama_pasien,g.keterangan as ket_gol_pasien,g1.keterangan as ket_gol_pasien1, m.nama as diagnosa, an.nama as jenis_anastesi, tr.nama_tindakan, ao.nama as asisten, d.nama_dokter, d.id_dokter,r.nama_ruangan, k.nama_kelas");
        $this->db->join("pasien p", "pr.no_rm=p.no_pasien");
        $this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "left");
        $this->db->join("gol_pasien g1", "g1.id_gol=p.id_gol", "left");
        $this->db->join("master_icd m", "m.kode=pr.diagnosa", "left");
        $this->db->join("jenis_anatesi an", "an.kode=pr.jenis_anastesi", "left");
        $this->db->join("tarif_ralan tr", "tr.kode_tindakan=pr.tindakan_operasi", "left");
        $this->db->join("asisten_operasi ao", "ao.kode=pr.asisten_operasi", "left");
        $this->db->join("dokter d", "d.id_dokter=pr.dokter_operasi", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=pr.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=pr.kode_kelas", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_inap pr");
        return $q->row();
    }
    function rtpelayanan($p, $r)
    {
        $poli_kode = $this->session->userdata("poli_kode");
        $kode_dokter = $this->session->userdata("kode_dokter");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $status_pasien = $this->session->userdata("status_pasien");
        $nama = $this->session->userdata("nama");
        $this->db->select("p.no_bpjs,pr.*,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien");
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
        if ($status_pasien != "ALL") {
            $this->db->where("pr.status_pasien", $status_pasien);
        }
        if ($kode_dokter != "") {
            $this->db->where("pr.dokter_poli", $kode_dokter);
        }
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
        }
        $this->db->where("pr.layan<>", 2);
        $this->db->order_by("no_reg", "desc");
        $this->db->join("pasien p", "p.no_pasien=pr.no_pasien");
        $this->db->join("poliklinik pol", "pol.kode=pr.dari_poli", "left");
        $this->db->join("poliklinik pol2", "pol2.kode=pr.tujuan_poli", "left");
        $query = $this->db->get("pasien_ralan pr");
        return $query;
    }
    function rtpelayanan_inap($p, $r)
    {
        $kode_kelas = $this->session->userdata("kode_kelas");
        $kode_ruangan = $this->session->userdata("kode_ruangan");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $nama = $this->session->userdata("nama");
        $this->db->select("i.*,r.nama_ruangan,k.nama_kelas,p.alamat,p.no_bpjs");
        // if ($no_pasien!="") {
        //  $no_pasien = "000000".$no_pasien;
        //  $this->db->where("i.no_rm",substr($no_pasien,-6));
        // }
        // if ($nama!="") {
        //  $this->db->like("p.nama_pasien",$nama);
        // }
        // if ($no_reg!="") {
        //  $this->db->where("no_reg",$no_reg);
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
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("i.tgl_masuk>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("i.tgl_masuk<=", date("Y-m-d", strtotime($tgl2)));
        }
        // $this->db->order_by("no_reg","desc");
        // $this->db->where("i.layan<>",2);
        // $this->db->where("i.tgl_layani!=","0000-00-00 00:00:00");
        $this->db->join("pasien p", "p.no_pasien=i.no_rm");
        $this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
        $this->db->order_by("no_reg,no_rm", "desc");
        $query = $this->db->get("pasien_inap i");
        return $query;
    }

    function cektglpulang($no_rm, $no_reg)
    {
        $this->db->where("no_pasien", $no_rm);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan");
        return $q->row();
    }
    function simpanpindahstatus()
    {
        $status = $this->db->get_where("gol_pasien", array("id_gol" => $this->input->post("id_gol_baru")))->row()->status;
        $data = array(
            'id_gol' => $this->input->post("id_gol_baru"),
            'status_bayar' => $status
        );
        $data1 = array(
            'id_gol' => $this->input->post("id_gol_baru"),
            'perusahaan' => $this->input->post("id_perusahaan_baru"),
        );
        // $this->db->where("no_pasien",$this->input->post("no_pasien"));
        // $this->db->update("pasien",$data);

        $this->db->where("no_rm", $this->input->post("no_pasien"));
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->update("pasien_inap", $data);

        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $this->db->update("pasien", $data1);

        $id = date("dmyHis");
        $data1 = array(
            'id'                   => $id,
            'no_reg'               => $this->input->post("no_reg"),
            'tanggal'              => date("Y-m-d", strtotime($this->input->post("tanggal"))),
            'jam'                  => date("H:i:s", strtotime($this->input->post("jam"))),
            'id_gol_lama'          => $this->input->post("id_gol_lama"),
            'id_gol_baru'          => $this->input->post("id_gol_baru"),
            'id_perusahaan_lama'   => $this->input->post("id_perusahaan_lama"),
            'id_perusahaan_baru'   => $this->input->post("id_perusahaan_baru"),
        );
        $this->db->insert("pindahstatus", $data1);
        return "success-Perubahan status berhasil";
    }
    function simpanpindahstatus_ralan()
    {
        $status = $this->db->get_where("gol_pasien", array("id_gol" => $this->input->post("id_gol_baru")))->row()->status;
        if ($this->input->post("id_gol_baru")==11) $status_bayar = "LUNAS"; else $status_bayar = "TAGIH";
        $data = array(
            'gol_pasien' => $this->input->post("id_gol_baru"),
            'perusahaan' => $this->input->post("id_perusahaan_baru"),
            'status_bayar' => $status
        );
        // $this->db->where("no_pasien",$this->input->post("no_pasien"));
        // $this->db->update("pasien",$data);

        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->update("pasien_ralan", $data);

        $id = date("dmyHis");
        $data1 = array(
            'id'                   => $id,
            'no_reg'               => $this->input->post("no_reg"),
            'tanggal'              => date("Y-m-d", strtotime($this->input->post("tgl_pindah"))),
            'jam'                  => date("H:i:s", strtotime($this->input->post("jam_pindah"))),
            'id_gol_lama'          => $this->input->post("id_gol_lama"),
            'id_gol_baru'          => $this->input->post("id_gol_baru"),
            'id_perusahaan_lama'   => $this->input->post("id_perusahaan_lama"),
            'id_perusahaan_baru'   => $this->input->post("id_perusahaan_baru"),
        );
        $this->db->insert("pindahstatus", $data1);
        return "success-Perubahan status berhasil";
    }
    function getjenisinos()
    {
        return $this->db->get("jenis_inos");
    }
    function getinos($no_pasien, $no_reg)
    {
        $this->db->select("i.*,ji.keterangan");
        $this->db->join("jenis_inos ji", "ji.kode=i.jenis_inos", "inner");
        $this->db->where("i.no_pasien", $no_pasien);
        $this->db->where("i.no_reg", $no_reg);
        $this->db->order_by("i.kode_inos");
        $q = $this->db->get("inos i");
        return $q;
    }
    function getinos_detail($no_pasien, $no_reg, $kode_inos)
    {
        $this->db->select("i.*,ji.keterangan");
        $this->db->join("jenis_inos ji", "ji.kode=i.jenis_inos", "inner");
        $this->db->where("i.no_pasien", $no_pasien);
        $this->db->where("i.no_reg", $no_reg);
        $this->db->where("i.kode_inos", $kode_inos);
        $q = $this->db->get("inos i");
        return $q;
    }
    function simpaninos($aksi)
    {
        switch ($aksi) {
        case 'simpan':
        $data = array(
            'kode_inos'     => date("dmYHis"),
            'no_pasien'     => $this->input->post("no_pasien"),
            'no_reg'        => $this->input->post("no_reg"),
            'jenis_inos'    => $this->input->post("jenis_inos"),
            'spesialisasi'  => $this->input->post("spesialisasi"),
            'pasien_tirah'  => $this->input->post("pasien_tirah"),
            'oprasi'        => $this->input->post("oprasi"),
            'terpasang'     => $this->input->post("terpasang1") . "," . $this->input->post("terpasang2") . "," . $this->input->post("terpasang3") . "," . $this->input->post("terpasang4") . "," . $this->input->post("terpasang5") . "," . $this->input->post("terpasang6"),
            'tanggal' => date("Y-m-d H:i:s"),
        );
        $this->db->insert("inos", $data);
        break;
        case 'edit':
        $data = array(
            'kode_inos'     => date("dmYHis"),
            'no_pasien'     => $this->input->post("no_pasien"),
            "no_reg"        => $this->input->post("no_reg"),
            'jenis_inos'    => $this->input->post("jenis_inos"),
            'spesialisasi'  => $this->input->post("spesialisasi"),
            'pasien_tirah'  => $this->input->post("pasien_tirah"),
            'oprasi'        => $this->input->post("oprasi"),
            'terpasang'     => $this->input->post("terpasang1") . "," . $this->input->post("terpasang2") . "," . $this->input->post("terpasang3") . "," . $this->input->post("terpasang4") . "," . $this->input->post("terpasang5") . "," . $this->input->post("terpasang6"),
            'tanggal' => date("Y-m-d H:i:s"),

    );
    $this->db->where("kode_inos",$this->input->post("kode_inos"));
    $this->db->update("inos",$data);
    break;
  }
  return "success-Data berhasil disimpan";
  }
    function hapusinos($no_pasien, $no_reg, $kode_inos)
    {
        $this->db->where("no_pasien", $no_pasien);
        $this->db->where("no_reg", $no_reg);
        $this->db->where("kode_inos", $kode_inos);
        $this->db->delete("inos");
        return "danger-Data berhasil dihapus";
    }
    function getspesialisasi()
    {
        return $this->db->get("spesialisasi_ruanginap");
    }
    function getpasien_inos()
    {
        $kode_kelas = $this->session->userdata("kode_kelas");
        $kode_ruangan = $this->session->userdata("kode_ruangan");
        $tgl1 = $this->session->userdata("tgl1") == "" ? date("Y-m-d") : $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2") == "" ? date("Y-m-d") : $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $nama = $this->session->userdata("nama");
        $this->db->select("i.*,r.nama_ruangan,k.nama_kelas,p.alamat,p.no_bpjs,p.jenis_kelamin,m.nama as diagnosa_penyakit,p.tgl_lahir,n.spesialisasi,n.tanggal as tgl_inos,n.jenis_inos,s.keterangan as spesialisasi");
        // if ($no_pasien!="") {
        //  $no_pasien = "000000".$no_pasien;
        //  $this->db->where("i.no_rm",substr($no_pasien,-6));
        // }
        // if ($nama!="") {
        //  $this->db->like("p.nama_pasien",$nama);
        // }
        // if ($no_reg!="") {
        //  $this->db->where("no_reg",$no_reg);
        // }
        $this->db->group_start();
        $this->db->like("i.no_rm", $no_pasien);
        $this->db->or_like("i.no_reg", $no_pasien);
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
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("i.tgl_masuk>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("i.tgl_masuk<=", date("Y-m-d", strtotime($tgl2)));
        }
        // $this->db->order_by("no_reg","desc");
        $this->db->join("pasien p", "p.no_pasien=i.no_rm");
        $this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
        $this->db->join("master_icd m", "m.kode=i.diagnosa_masuk", "left");
        $this->db->join("inos n", "n.no_reg=i.no_reg", "inner");
        $this->db->join("spesialisasi_ruanginap s", "s.kode=n.spesialisasi", "left");
        $this->db->order_by("no_reg,no_rm", "desc");
        $query = $this->db->get("pasien_inap i");
        $data = array();
        foreach ($query->result() as $row) {
            $data["data"][$row->no_reg] = $row;
            $data["inos"][$row->no_reg][$row->jenis_inos] = $row;
        }
        return $data;
    }
    function getdiagnosa()
    {
        return $this->db->get("master_icd");
    }
    function namadiagnosa()
    {
        $q = $this->db->get_where("master_icd", ["kode" => $this->input->post("kode")]);
        if ($q->num_rows() > 0) return $q->row()->nama;
        else return "-";
    }

    function getdiagnosa1()
    {
        $q =  $this->db->get("master_icd");
        $data = array();
        foreach ($q->result() as $key => $value) {
            $data[] = array('kode' => $value->kode, 'nama' => $value->nama);
        }
        return $data;
    }
    function getanastesi()
    {
        return $this->db->get("jenis_anatesi");
    }
    function getasisten()
    {
        return $this->db->get("asisten_operasi");
    }
    function gettindakan_op($poli)
    {
        $this->db->where("kode_poli", $poli);
        $this->db->where("kategori", "tdk");
        return $this->db->get("tarif_ralan");
    }
    function gettindakan_opi()
    {
        return $this->db->get("tarif_inap");
    }
    function gettindakan_opi_array()
    {
        $data = array();
        $q = $this->db->get("tarif_inap");
        foreach ($q->result() as $key) {
          $data[$key->kode_tindakan] = $key->nama_tindakan;
        }
        return $data;
    }
    function getdokter_op($poli)
    {
        $this->db->select("dokter.*, k.nama_kelompok");
        $this->db->where("j.id_poli", $poli);
        $this->db->join("jadwal_dokter j", "j.id_dokter = dokter.id_dokter", "left");
        $this->db->join("kelompok_dokter k", "k.id_kelompok = dokter.kelompok_dokter", "left");
        return $this->db->get("dokter");
    }
    function simpanlaporan_tindakan()
    {
        $data = array(
            'diagnosa' => $this->input->post("diagnosa"),
            'tindakan_operasi' => $this->input->post("tindakan"),
            'dokter_operasi' => $this->input->post("dokter"),
            'asisten_operasi' => $this->input->post("asisten"),
            'jenis_anastesi' => $this->input->post("jenis_anastesi"),
            'pemeriksaan_penunjang' => $this->input->post("pemeriksaan_penunjang"),
            'tanggal_operasi' => date("Y-m-d", strtotime($this->input->post("tanggal"))),
            'tanggal_ulangan' => date("Y-m-d", strtotime($this->input->post("ulangan"))),
            'jam_masuk' => $this->input->post("jam_masuk"),
            'jam_keluar' => $this->input->post("jam_keluar"),
            'keterangan' => $this->input->post("keterangan"),
            'laporan_operasi' => $this->input->post("laporan_operasi"),
        );
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->update("pasien_ralan", $data);
        return "success-Data Berhasil ter Update...";
    }
    function simpanlaporan_tindakaninap()
    {
        $tindakan = $this->session->userdata("temptindakan");
        $koma = $tnd = $tgloperasi = $tglulangan = $jammulai = $jamselesai = $pmr = "";
        foreach ($tindakan as $key => $value) {
            $tnd .= $koma . $value["tindakan"];
            $tgloperasi .= $koma . $value["tanggal"];
            $tglulangan .= $koma . $value["ulangan"];
            $jammulai .= $koma . $value["jam_masuk"];
            $jamselesai .= $koma . $value["jam_keluar"];
            $pmr .= $koma . $value["pemeriksaanke"];
            $koma = ",";
        }
        $data = array(
            'diagnosa' => $this->input->post("diagnosa"),
            'tindakan_operasi' => $tnd,
            'dokter_operasi' => $this->input->post("dokter"),
            'asisten_operasi' => $this->input->post("asisten"),
            'jenis_anastesi' => $this->input->post("jenis_anastesi"),
            'pemeriksaan_penunjang' => $this->input->post("pemeriksaan_penunjang"),
            'tanggal_operasi' => $tgloperasi,
            'tanggal_ulangan' => $tglulangan,
            'jam_mulai' => $jammulai,
            'jam_selesai' => $jamselesai,
            'keterangan' => $this->input->post("keterangan"),
            'laporan_operasi' => $this->input->post("laporan_operasi"),
            'pemeriksaanke' => $pmr
        );
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->update("pasien_inap", $data);
        return "success-Data Berhasil ter Update...";
    }

    function simpanvisitinap()
    {
        $data = array(
            'id' => date("YmdHis"),
            'no_reg' => $this->input->post("no_reg"),
            's' => $this->input->post("s"),
            'o' => $this->input->post("o"),
            'a' => $this->input->post("a"),
            'p' => $this->input->post("p"),
            'jam' => date("H:i:s"),
            'dokter_visit' => $this->input->post("dokter"),
            'pemeriksaan' => "visit",
            'tanggal' => date("Y-m-d", strtotime($this->input->post("tanggal_soap"))),

        );
        $this->db->insert("riwayat_pasien_inap", $data);
        return "success-Data Berhasil ter Simpan...";
    }

    function simpankonsulinap()
    {
        $data = array(
            'id' => date("YmdHis"),
            'no_reg' => $this->input->post("no_reg"),
            'konsul' => $this->input->post("konsul"),
            'jam' => date("H:i:s"),
            'dokter_visit' => $this->input->post("dokter"),
            'dokter_konsul' => $this->input->post("dokter_konsul"),
            'pemeriksaan' => "konsul",
            'tanggal' => date("Y-m-d", strtotime($this->input->post("tanggal"))),
        );
        $this->db->insert("riwayat_pasien_inap", $data);
        return "success-Data Berhasil ter Simpan...";
    }
    function simpanjawabankonsulinap()
    {
        $data = array(
            'id' => date("YmdHis"),
            'no_reg' => $this->input->post("no_reg"),
            'konsul' => $this->input->post("konsul"),
            'jam' => date("H:i:s"),
            'dokter_visit' => $this->input->post("dokter"),
            'dokter_konsul' => $this->input->post("dokter_konsul"),
            'pemeriksaan' => "jawaban konsul",
            'tanggal' => date("Y-m-d", strtotime($this->input->post("tanggal"))),
        );
        $this->db->insert("riwayat_pasien_inap", $data);
        return "success-Data Berhasil ter Simpan...";
    }
    function getoka_detail($kode)
    {
        $this->db->select("o.*,r.nama_ruangan,kl.nama_kelas");
        $this->db->join("ruangan r", "r.kode_ruangan = o.ruangan", "left");
        $this->db->join("kelas kl", "kl.kode_kelas = o.kelas", "left");
        $this->db->where("no_reg", $kode);
        $q = $this->db->get("oka o");
        return $q->row();
    }
    function ambiltriage()
    {
        $this->db->order_by("kode");
        return $this->db->get("triage");
    }
    function getkeputusan()
    {
        return $this->db->get("keputusan");
    }
    function getpasien_triage($no_reg)
    {
        $this->db->select("pt.*");
        $this->db->where("pt.no_reg", $no_reg);
        $this->db->join("triage t", "t.nama = pt.triage", "left");
        $q = $this->db->get("pasien_triage pt");
        return $q->row();
    }
    function simpantriage($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_triage");
        if ($q->num_rows() > 0) {
            $data = array(
                'triage' => $this->input->post("triage"),
                'waktu' => $this->input->post("waktu"),
                'jalan_nafas' => $this->input->post("jalan_nafas"),
                'survei_primer' => $this->input->post("survei_primer"),
                'kesadaran' => $this->input->post("kesadaran"),
                'nama_pasien' => $this->input->post("nama_pasien"),
                'nyeri' => $this->input->post("nyeri"),
                'dokter_igd' => $this->input->post("dokter_igd"),
                'petugas_igd' => $this->input->post("petugas_igd"),
                'dokter_triage' => $this->input->post("dokter_triage"),
                'pernafasan' => $this->input->post("pernafasan"),
                'sirkulasi' => $this->input->post("sirkulasi"),
                'gangguan' => $this->input->post("gangguan"),
                'waktu_keputusan' => date("H:i:s", strtotime($this->input->post("waktu_keputusan"))),
                'keputusan' => $this->input->post("keputusan"),
                'anamnesis' => $this->input->post("anamnesis"),
                's' => $this->input->post("s"),
                'o' => $this->input->post("o"),
                'a' => $this->input->post("a"),
                'p' => $this->input->post("p"),
                'td' => $this->input->post("td"),
                'td2' => $this->input->post("td2"),
                'nadi' => $this->input->post("nadi"),
                'respirasi' => $this->input->post("respirasi"),
                'suhu' => $this->input->post("suhu"),
                'spo2' => $this->input->post("spo2"),
                'bb' => $this->input->post("bb"),
                'tb' => $this->input->post("tb"),
                'tanggal' => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                'jam' => date("H:i:s", strtotime($this->input->post("jam"))),
            );
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_triage", $data);
            if ($this->input->post("tindak_lanjut") == "ralan") {
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_inap", $data);
            } else {
                $data = array(
                    "tgl_masuk" => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                    "jam_masuk" => $this->input->post("waktu"),
                    'jam_periksa' => date("H:i:s", strtotime($this->input->post("waktu_keputusan")))
                );
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_igdinap", $data);
            }
        } else {
            $no_reg = date("dmYHis");
            $data = array(
                'no_reg' => $no_reg,
                'no_rm' => $no_reg,
                'triage' => $this->input->post("triage"),
                'nama_pasien' => $this->input->post("nama_pasien"),
                'waktu' => $this->input->post("waktu"),
                'jalan_nafas' => $this->input->post("jalan_nafas"),
                'survei_primer' => $this->input->post("survei_primer"),
                'kesadaran' => $this->input->post("kesadaran"),
                'nyeri' => $this->input->post("nyeri"),
                'dokter_igd' => $this->input->post("dokter_igd"),
                'petugas_igd' => $this->input->post("petugas_igd"),
                'dokter_triage' => $this->input->post("dokter_triage"),
                'pernafasan' => $this->input->post("pernafasan"),
                'sirkulasi' => $this->input->post("sirkulasi"),
                'gangguan' => $this->input->post("gangguan"),
                'waktu_keputusan' => date("H:i:s", strtotime($this->input->post("waktu_keputusan"))),
                'keputusan' => $this->input->post("keputusan"),
                'anamnesis' => $this->input->post("anamnesis"),
                'diagnosa' => $this->input->post("diagnosa"),
                'tindakan' => $this->input->post("tindakan"),
                's' => $this->input->post("s"),
                'o' => $this->input->post("o"),
                'a' => $this->input->post("a"),
                'p' => $this->input->post("p"),
                'td' => $this->input->post("td"),
                'td2' => $this->input->post("td2"),
                'nadi' => $this->input->post("nadi"),
                'respirasi' => $this->input->post("respirasi"),
                'suhu' => $this->input->post("suhu"),
                'spo2' => $this->input->post("spo2"),
                'bb' => $this->input->post("bb"),
                'tb' => $this->input->post("tb"),
                'tanggal' => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                'jam' => date("H:i:s", strtotime($this->input->post("jam"))),
            );
            $this->db->insert("pasien_triage", $data);
            $this->simpan_pasien($no_reg);
        }
        return "success-Data Berhasil ter Simpan...";
    }
    function getpetugas_igd()
    {
        // return $this->db->get("petugas_igd");
        $this->db->select("p.*, b.nama as bagian");
        $this->db->where("p.bagian", "0102030");
        $this->db->or_where("p.bagian", "pnk");
        $this->db->join("bagian b", "b.kode = p.bagian", "left");
        $q = $this->db->get("perawat p");
        return $q;
    }
    function getpasien_igd($no_reg)
    {
        $this->db->select("pi.*,pr.dokter_poli,p.no_pasien, p.nama_pasien as nama_pasien1,p.tgl_lahir");
        $this->db->join("pasien p", "p.no_pasien = pr.no_pasien", "left");
        $this->db->join("pasien_igd pi", "p.no_pasien = pi.no_rm and pi.no_reg=pr.no_reg", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $this->db->where("pr.tujuan_poli", "0102030");
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function getpasien_igdralan($no_reg)
    {
        $this->db->select("pi.*,pr.dokter_poli,p.no_pasien, p.nama_pasien as nama_pasien1,p.tgl_lahir");
        $this->db->join("pasien_vaksin p", "p.no_pasien = pr.no_pasien", "left");
        $this->db->join("pasien_igd_vaksin pi", "p.no_pasien = pi.no_rm and pi.no_reg=pr.no_reg", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan_vaksin pr");
        return $q->row();
    }
    function simpanigd($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_triage");
        $pemeriksaan_fisik = "";
        $kelainan = "";
        $pemeriksaan_fisikedit = "";
        $kelainanedit = "";
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $pemeriksaan_fisik .= $koma . "0";
            $koma = ",";
        }
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $kelainan .= $koma . " ";
            $koma = ",";
        }
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $pemeriksaan_fisikedit .= $koma . ($this->input->post("pemeriksaan_fisik" . $i) != "" ? $this->input->post("pemeriksaan_fisik" . $i) : 0);
            $koma = ",";
        }
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $kelainanedit .= $koma . ($this->input->post("kelainan" . $i) != "" ? $this->input->post("kelainan" . $i) : 0);
            $koma = ",";
        }
        if ($q->num_rows() > 0) {
            $data = array(
                'tanggal_masuk' => date("Y-m-d", strtotime($this->input->post("tanggal_masuk"))),
                'jam_masuk' => date("H:i:s", strtotime($this->input->post("jam_masuk"))),
                'jam_periksa' => date("H:i:s", strtotime($this->input->post("jam_periksa"))),
                'jam_keluar_igd' => date("H:i:s", strtotime($this->input->post("jam_keluar_igd"))),
                'nyeri' => $this->input->post("nyeri"),
                'jenis_nyeri' => $this->input->post("jenis_nyeri"),
                'resiko_jatuh' => $this->input->post("resiko_jatuh"),
                'kedatangan' => $this->input->post("kedatangan"),
                'diantar' => $this->input->post("diantar"),
                'skrining_gizi' => $this->input->post("skrining_gizi"),
                'skrining_gizi2' => $this->input->post("skrining_gizi2"),
                // 'dokter_igd' => $this->input->post("dokter_igd"),
                // 'petugas_igd' => $this->input->post("petugas_igd"),
                // 'dokter_triage' => $this->input->post("dokter_triage"),
                'keluhan_utama' => $this->input->post("keluhan_utama"),
                'kronologis_kejadian' => $this->input->post("kronologis_kejadian"),
                'anamnesa' => $this->input->post("anamnesa"),
                'riwayat_penyakit' => $this->input->post("riwayat_penyakit"),
                'obat_dikonsumsi' => $this->input->post("obat_dikonsumsi"),
                'pemeriksaan_penunjang' => $this->input->post("pemeriksaan_penunjang"),
                'diagnosis_kerja' => $this->input->post("diagnosis_kerja"),
                'dd' => $this->input->post("dd"),
                'terapi' => $this->input->post("terapi"),
                'observasi' => $this->input->post("observasi"),
                'waktu' => $this->input->post("waktu"),
                'assesment' => $this->input->post("assesment"),
                's' => $this->input->post("s"),
                'o' => $this->input->post("o"),
                'a' => $this->input->post("a"),
                'p' => $this->input->post("p"),
                'tindak_lanjut' => $this->input->post("tindak_lanjut"),
                'tindakan_radiologi' => $this->input->post("tindakan_radiologi"),
                'tindakan_lab' => $this->input->post("tindakan_lab"),
                'ruang' => $this->input->post("ruang"),
                'rujuk_ke' => $this->input->post("rujuk_ke"),
                'alasan_rujuk' => $this->input->post("alasan_rujuk"),
                'pemeriksaan_fisik' => $pemeriksaan_fisikedit,
                'kelainan' => $kelainanedit,
            );
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_igd", $data);
        } else {
            $data = array(
                'id' => date("YmdHis"),
                'tanggal_masuk' => date("Y-m-d", strtotime($this->input->post("tanggal_masuk"))),
                'jam_masuk' => date("H:i:s", strtotime($this->input->post("jam_masuk"))),
                'jam_periksa' => date("H:i:s", strtotime($this->input->post("jam_periksa"))),
                'jam_keluar_igd' => date("H:i:s", strtotime($this->input->post("jam_keluar_igd"))),
                'no_reg' => $this->input->post("no_reg"),
                'no_rm' => $this->input->post("no_rm"),
                'nyeri' => $this->input->post("nyeri"),
                'jenis_nyeri' => $this->input->post("jenis_nyeri"),
                'resiko_jatuh' => $this->input->post("resiko_jatuh"),
                'kedatangan' => $this->input->post("kedatangan"),
                'diantar' => $this->input->post("diantar"),
                'skrining_gizi' => $this->input->post("skrining_gizi"),
                'skrining_gizi2' => $this->input->post("skrining_gizi2"),
                // 'dokter_igd' => $this->input->post("dokter_igd"),
                // 'petugas_igd' => $this->input->post("petugas_igd"),
                // 'dokter_igd' => $this->input->post("dokter_igd"),
                'keluhan_utama' => $this->input->post("keluhan_utama"),
                'kronologis_kejadian' => $this->input->post("kronologis_kejadian"),
                'anamnesa' => $this->input->post("anamnesa"),
                'riwayat_penyakit' => $this->input->post("riwayat_penyakit"),
                'obat_dikonsumsi' => $this->input->post("obat_dikonsumsi"),
                'pemeriksaan_penunjang' => $this->input->post("penunjang"),
                'diagnosis_kerja' => $this->input->post("diagnosis_kerja"),
                'dd' => $this->input->post("dd"),
                'terapi' => $this->input->post("terapi"),
                'observasi' => $this->input->post("observasi"),
                'waktu' => $this->input->post("waktu"),
                'assesment' => $this->input->post("assesment"),
                's' => $this->input->post("s"),
                'o' => $this->input->post("o"),
                'a' => $this->input->post("a"),
                'p' => $this->input->post("p"),
                'tindak_lanjut' => $this->input->post("tindak_lanjut"),
                'tindakan_radiologi' => $this->input->post("tindakan_radiologi"),
                'tindakan_lab' => $this->input->post("tindakan_lab"),
                'ruang' => $this->input->post("ruang"),
                'rujuk_ke' => $this->input->post("rujuk_ke"),
                'alasan_rujuk' => $this->input->post("alasan_rujuk"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
            );
            $this->db->insert("pasien_igd", $data);
        }
        return "success-Data Berhasil ter Simpan...";
    }
    function gettarif_radiologi()
    {
        return $this->db->get("tarif_radiologi");
    }
    function gettarif_lab()
    {
        return $this->db->get("tarif_lab");
    }
    function getobat()
    {
        return $this->db->get("obat");
    }
    function addtindakan_inapradiologi()
    {
        $t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $this->input->post("tindakan_radiologi")]);
        $tanggal = date("Y-m-d");
        if ($t->num_rows() > 0) {
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
            }
            $data = array(
                "id" => date("dmyHis"),
                "no_reg" => $this->input->post("no_reg"),
                "kode_tarif" => $this->input->post("tindakan_radiologi"),
                "terima_radiologi" =>  date("Y-m-d H:i:s"),
                "qty" => 1,
                "jumlah" => $tarif
            );
            $this->db->insert("kasir_inap", $data);
        }
    }
    function addtindakan_inaplab()
    {
        $tindakan = $this->input->post("tindakan_lab");
        $id = date("dmyHis");
        $data = array();
        foreach ($tindakan as $key => $value) {
            $t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $value]);
            if ($t->num_rows() > 0) {
                $data = $t->row();
                $tarif = $data->reguler;
                $data = array(
                    "id" => $id,
                    "no_reg" => $this->input->post("no_reg"),
                    "qty" => 1,
                    "tanggal" => date("Y-m-d"),
                    "kode_tarif" => $value,
                    "jumlah" => $tarif,
                    "terima_lab" =>  date("Y-m-d H:i:s"),
                );
                $this->db->insert("kasir_inap", $data);
            }
            $id++;
        }
        $q = $this->getlabinap_normal($this->input->post("no_reg"));
        foreach ($q->result() as $row) {
            $item = array(
                'kode_labnormal' => $row->kode,
                'kode_tindakan' => $row->kode_tindakan,
                'no_reg' => $this->input->post("no_reg"),
                'hasil' => $row->hasil,
                'tanggal' => date("Y-m-d", strtotime($row->tanggal)),
                "pemeriksaan" => $row->pemeriksaan,
                'kode_judul' => $row->kode_judul
            );
        }
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->insert("ekspertisi_labinap", $item);
    }
    function gettarif_penunjang_medis()
    {
        return $this->db->get("tarif_penunjang_medis");
    }
    function simpanpasientriage_inap($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_triage");
        if ($q->num_rows() > 0) {
            $data = array(
                'nama_pasien' => $this->input->post("nama_pasien"),
            );
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_triage", $data);
        } else {
            $data = array(
                'no_rm' => date("YmdHis"),
                'no_reg' => date("YmdHis"),
                'nama_pasien' => $this->input->post("nama_pasien"),
            );
            $this->db->insert("pasien_triage", $data);
        }
        return "success-Data Berhasil ter Simpan...";
    }
    function simpan_pasien($noreg)
    {
        if ($this->input->post("tindak_lanjut") == "ralan") {
            $data = array(
                "id_pasien" => date("dmyHis"),
                "no_pasien" => $no,
                "id_gol" => $this->input->post("golpasien")
            );
            $this->db->insert("pasien", $data);
            $no_pasien = $noreg;
            $nama_pasien = $this->input->post("nama_pasien");
            $no_antrian = $this->getno_antrian();
            $p = $this->db->get_where("poliklinik", ["kode" => '0102030'])->row();
            $d = $this->db->get_where("dokter", ["id_dokter" => $this->input->post("dokter_igd")])->row();
            $data = array(
                "no_reg" => $noreg,
                "no_pasien" => $no_pasien,
                "no_antrian" => $no_antrian,
                "tujuan_poli" => '0102030',
                "dokter_poli" => $this->input->post("dokter_igd"),
                "tanggal" => date("Y-m-d H:i:s", strtotime($noreg)),
                "jenis" => "R"
            );
            $this->db->insert("pasien_ralan", $data);
            $r = $this->db->get_where("pasien_ralan", ["jenis" => "R", "tujuan_poli" => '0102030', "date(tanggal)" => date("Y-m-d")]);
            $jumlah_pasien = substr("0000" . $r->num_rows(), -3);
            $t = $this->db->get_where("tarif_ralan", ["kategori" => "pdf", "kode_poli" => '0102030']);
            if ($t->num_rows() > 0) {
                $data = $t->row();
                $tarif = $data->reguler;
                $dat = array(
                    "id" => date("dmyHis"),
                    "no_reg" => $noreg,
                    "kode_tarif" => $data->kode_tindakan,
                    "jumlah" => $tarif,
                    "bayar" => 0
                );
                $this->db->insert("kasir", $dat);
            }
        } else
        if ($this->input->post("tindak_lanjut") == "ranap") {
        }
        return array("tanggal" => date("d-m-Y H:i:s", strtotime($noreg)), "jumlah_pasien" => $jumlah_pasien, "no_pasien" => $no_pasien, "nama_pasien" => $nama_pasien, "kode_dokter" => $this->input->post("dokter"), "nama_dokter" => $d->nama_dokter, "no_antrian" => $no_antrian, "no_reg" => date("YmdHis", strtotime($noreg)), "jenis" => $this->input->post("jenis"), "poli" => $p->keterangan);
    }
    function persenpelayanan($bulan, $tahun)
    {
        $this->db->select("p.id_kota,c.name,count(*) as jumlah");
        $this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
        $this->db->join("regencies c", "c.id=p.id_kota", "inner");
        $this->db->group_by("p.id_kota");
        $this->db->where("month(pr.tanggal)", $bulan);
        $this->db->where("year(pr.tanggal)", $tahun);
        $this->db->where("pr.layan!=", "2");
        $q = $this->db->get("pasien_ralan pr");
        return $q;
    }
    function persenperwilayah($kota)
    {
        $data = array();
        $bln = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember");
        for ($i = 1; $i <= 12; $i++) {
            $this->db->select("p.id_kota,count(*) as jumlah");
            $this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
            $this->db->group_by("p.id_kota");
            $this->db->where("month(pr.tanggal)", $i);
            $this->db->where("pr.layan!=", "2");
            $this->db->where("year(pr.tanggal)", date("Y"));
            $q = $this->db->get("pasien_ralan pr");
            $jumlah = $selainjumlah = $total = 0;
            foreach ($q->result() as $row) {
                if ($row->id_kota == $kota) {
                    $jumlah += $row->jumlah;
                } else {
                    $selainjumlah += $row->jumlah;
                }
                $total += $row->jumlah;
            }
            $persen_jumlah = ($total == 0 ? 0 : round($jumlah / $total, 2)) * 100;
            $persen_selainjumlah = ($total == 0 ? 0 : round($selainjumlah / $total, 2)) * 100;
            $data[] = array("bulan" => $bln[$i], "jumlah" => $persen_jumlah, "selainjumlah" => $persen_selainjumlah);
        }
        return $data;
    }
    function persenpelayanan_inap($bulan, $tahun)
    {
        $this->db->select("p.id_kota,c.name,count(*) as jumlah");
        $this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
        $this->db->join("regencies c", "c.id=p.id_kota", "inner");
        $this->db->group_by("p.id_kota");
        $this->db->where("month(pr.tgl_masuk)", $bulan);
        $this->db->where("year(pr.tgl_masuk)", $tahun);
        $q = $this->db->get("pasien_inap pr");
        return $q;
    }
    function persenperwilayah_inap($kota)
    {
        $data = array();
        $bln = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember");
        for ($i = 1; $i <= 12; $i++) {
            $this->db->select("p.id_kota,count(*) as jumlah");
            $this->db->join("pasien p", "p.no_pasien=pr.no_rm", "inner");
            $this->db->group_by("p.id_kota");
            $this->db->where("month(pr.tgl_masuk)", $i);
            $this->db->where("year(pr.tgl_masuk)", date("Y"));
            $q = $this->db->get("pasien_inap pr");
            $jumlah = $selainjumlah = $total = 0;
            foreach ($q->result() as $row) {
                if ($row->id_kota == $kota) {
                    $jumlah += $row->jumlah;
                } else {
                    $selainjumlah += $row->jumlah;
                }
                $total += $row->jumlah;
            }
            $persen_jumlah = ($total == 0 ? 0 : round($jumlah / $total, 2)) * 100;
            $persen_selainjumlah = ($total == 0 ? 0 : round($selainjumlah / $total, 2)) * 100;
            $data[] = array("bulan" => $bln[$i], "jumlah" => $persen_jumlah, "selainjumlah" => $persen_selainjumlah);
        }
        return $data;
    }
    function getpasienujifungsi($no_reg)
    {
        $q = $this->db->get_where("pasien_ujifungsi", ["no_reg" => $no_reg]);
        return $q;
    }
    function getpasienujifungsi_sebelumnya($no_reg)
    {
        $this->db->select("no_reg_sebelumnya");
        $no_reg_seb = $this->db->get_where("pasien_ralan", ["no_reg" => $no_reg])->row()->no_reg_sebelumnya;
        $q = $this->db->get_where("pasien_ujifungsi", ["no_reg" => $no_reg_seb]);
        return $q;
    }
    function simpanujifungsi($action)
    {
        $no_reg = $this->input->post("no_reg");
        if ($this->input->post("suspek_kerja") == "0") {
            $keterangan_suspek = "";
        } else {
            $keterangan_suspek = $this->input->post("keterangan_suspek");
        }
        $kode_tarif = $this->input->post("kode_tarif");
        $koma = $kt = "";
        foreach ($kode_tarif as $key => $value) {
            $kt .= $koma . $value;
            $koma = ",";
        }
        $data = array(
            "no_reg" => $this->input->post("no_reg"),
            "tgl_pemeriksaan" => date("Y-m-d", strtotime($this->input->post("tgl_pemeriksaan"))),
            "id_tindakan" => $this->input->post("id_tindakan"),
            "tindakan" => $this->input->post("tindakan"),
            "koding" => $this->input->post("koding"),
            "kode_diagnosis_fungsional" => $this->input->post("kode_diagnosis_fungsional"),
            "kode_diagnosis_medis" => $this->input->post("kode_diagnosis_medis"),
            "hasil" => $this->input->post("hasil"),
            "kesimpulan" => $this->input->post("kesimpulan"),
            "rekomendasi" => $this->input->post("rekomendasi"),
            "evaluasi" => $this->input->post("evaluasi"),
            "suspek_kerja" => $this->input->post("suspek_kerja"),
            "kode_tarif" => $kt,
            "keterangan_suspek" => $keterangan_suspek
        );
        switch ($action) {
            case 'simpan':
                $this->db->insert("pasien_ujifungsi", $data);
                break;
            case 'edit':
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ujifungsi", $data);
                break;
        }
        return "success-Data berhasil disimpan";
    }
    function getpasienujifungsi2($no_rm, $parent = 1)
    {
        $this->db->select("pu.*,pr.no_reg_sebelumnya");
        $this->db->join("pasien_ralan pr", "pr.no_reg=pu.no_reg", "inner");
        $this->db->join("pasien p", "p.no_pasien=pr.no_pasien", "inner");
        if ($parent == 1) {
            $this->db->group_start();
            $this->db->where("pr.no_reg_sebelumnya", "");
            $this->db->or_where("pr.no_reg_sebelumnya", NULL);
            $this->db->group_end();
        } else $this->db->where("pr.no_reg_sebelumnya!=", "");
        $this->db->where("p.no_pasien", $no_rm);
        $this->db->order_by("pu.tgl_pemeriksaan", "desc");
        $q = $this->db->get("pasien_ujifungsi pu");
        if ($parent == 1) {
            return $q;
        } else {
            $data = array();
            foreach ($q->result() as $key) {
                $data[$key->no_reg_sebelumnya][] = $key;
            }
            return $data;
        }
    }
    function getkasir($no_reg)
    {
        $this->db->join("tarif_ralan t", "t.kode_tindakan=k.kode_tarif", "inner");
        $q = $this->db->get_where("kasir k", ["k.no_reg" => $no_reg]);
        return $q;
    }
    function gettarif_fisioterapi()
    {
        return $this->db->get_where("tarif_ralan", ["kode_poli" => "0102034"]);
    }
    function getmcu_detail($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        // $this->db->where("no_pemeriksaan",$no_pemeriksaan);
        $q = $this->db->get("pemeriksaan_fisik");
        return $q->row();
    }
    function getfoto_thorax($no_reg)
    {
        $this->db->where("no_reg_sebelumnya", $no_reg);
        $this->db->where("tujuan_poli", "0102025");
        $q = $this->db->get("pasien_ralan")->row();

        $this->db->where("no_reg", $q->no_reg);
        $this->db->where("id_tindakan", "R068");
        $q1 = $this->db->get("ekspertisi");

        return $q1->row();
    }
    function getekspertisilab_detail($no_reg)
    {
        $this->db->where("no_reg_sebelumnya", $no_reg);
        $this->db->where("tujuan_poli", "0102024");
        $q = $this->db->get("pasien_ralan")->row();

        $this->db->select("l.*,e.*,t.nama_tindakan,p.jenis_kelamin, j.judul,a.nama as namaanalys");
        $this->db->join("lab_normal l", "l.kode_tindakan=e.kode_tindakan and l.kode=e.kode_labnormal");
        $this->db->join("tarif_lab t", "t.kode_tindakan=e.kode_tindakan and t.kode_tindakan=l.kode_tindakan");
        $this->db->join("lab_judul j", "j.kode_judul=e.kode_judul and j.kode_judul=l.kode_judul");
        $this->db->join("pasien_ralan pr", "pr.no_reg=e.no_reg");
        $this->db->join("kasir kr", "kr.no_reg=e.no_reg and kr.kode_tarif = e.kode_tindakan");
        $this->db->join("analys a", "a.nip=kr.analys", "left");
        $this->db->join("pasien p", "p.no_pasien=pr.no_pasien");
        $this->db->where("e.no_reg", $q->no_reg);
        $this->db->order_by("l.no_urut");
        $q = $this->db->get("ekspertisi_lab e");
        return $q;
    }
    function getpasien_ralan_mcu($no_reg)
    {
        $this->db->select("d.*");
        $this->db->join("dokter d", "d.id_dokter=pr.dokter_poli");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function getidentitas_pasien($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan")->row();

        $this->db->select("p.*,pk.pekerjaan,pkt.keterangan as pangkat");
        $this->db->join("pekerjaan pk", "pk.idx=p.pekerjaan", "LEFT");
        $this->db->join("pangkat pkt", "pkt.id_pangkat=p.id_pangkat", "LEFT");
        $this->db->where("no_pasien", $q->no_pasien);
        $q1 = $this->db->get("pasien p");
        return $q1->row();
    }
    function getassesmen_perawat($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("assesmen_perawat");
        return $q->row();
    }
    function simpanmcu($aksi)
    {
        switch ($aksi) {
            case 'simpan':
                $data = array(
                    'no_reg'                => $this->input->post("no_reg"),
                    // 'nopemeriksaan'        => $this->input->post("no_pemeriksaan"),
                    'keluhan_penyakit'      => $this->input->post("keluhan_penyakit"),
                    'penyakit_berat'        => $this->input->post("penyakit_berat"),
                    'alergi'                => $this->input->post("alergi"),
                    'merokok'               => $this->input->post("merokok"),
                    'obat_rutin'            => $this->input->post("obat_rutin"),
                    'olahraga'              => $this->input->post("olahraga"),
                    'ket_riwayat_penyakitkel'   => $this->input->post("ket_riwayat_penyakitkel"),
                    'ket_keluhan_penyakit'      => $this->input->post("ket_keluhan_penyakit"),
                    'ket_penyakit_berat'        => $this->input->post("ket_penyakit_berat"),
                    'ket_alergi'                => $this->input->post("ket_alergi"),
                    'ket_merokok'               => $this->input->post("ket_merokok"),
                    'ket_obat_rutin'            => $this->input->post("ket_obat_rutin"),
                    'ket_riwayat_penyakitkel'   => $this->input->post("ket_riwayat_penyakitkel"),
                    'genetalia'   => $this->input->post("genetalia"),
                    'ket_genetalia'   => $this->input->post("ket_genetalia"),
                    'ket_makan_minum'   => $this->input->post("ket_makan_minum"),
                    'makan_minum'           => $this->input->post("makan_minum"),
                    'tinggi_badan'          => $this->input->post("tinggi_badan"),
                    'berat_badan'           => $this->input->post("berat_badan"),
                    'tekanan_darah'         => $this->input->post("tekanan_darah"),
                    'nadi'                  => $this->input->post("nadi"),
                    'anemik'                => $this->input->post("anemik"),
                    'respirasi'             => $this->input->post("respirasi"),
                    'ikterik'               => $this->input->post("ikterik"),
                    'rate_rr'               => $this->input->post("rate_rr"),
                    'kenal_warna'           => $this->input->post("kenal_warna"),
                    'visus_od'              => $this->input->post("visus_od"),
                    'visus_os'              => $this->input->post("visus_os"),
                    'juling'                => $this->input->post("juling"),
                    'ket_juling'                => $this->input->post("ket_juling"),
                    'telinga'               => $this->input->post("telinga"),
                    'mucosa'                => $this->input->post("mucosa"),
                    'tonsil'                => $this->input->post("tonsil"),
                    'ket_mucosa'                => $this->input->post("ket_mucosa"),
                    'ket_tonsil'                => $this->input->post("ket_tonsil"),
                    'gigi'                  => $this->input->post("gigi"),
                    'struma'                => $this->input->post("struma"),
                    'jvp'                   => $this->input->post("jvp"),
                    'perut'                 => $this->input->post("perut"),
                    'dinding_perut'         => $this->input->post("dinding_perut"),
                    'nyeri_tekan'           => $this->input->post("nyeri_tekan"),
                    'tumor'                 => $this->input->post("tumor"),
                    'hernia'                => $this->input->post("hernia"),
                    'hati'                  => $this->input->post("hati"),
                    'limpa'                 => $this->input->post("limpa"),
                    'suara_usus'            => $this->input->post("suara_usus"),
                    'bekas_operasi'         => $this->input->post("bekas_operasi"),
                    'kulit'                 => $this->input->post("kulit"),
                    'dinding_thorax'        => $this->input->post("dinding_thorax"),
                    'ket_struma'                => $this->input->post("ket_struma"),
                    'ket_jvp'                   => $this->input->post("ket_jvp"),
                    'ket_perut'                 => $this->input->post("ket_perut"),
                    'ket_dinding_perut'         => $this->input->post("ket_dinding_perut"),
                    'ket_nyeri_tekan'           => $this->input->post("ket_nyeri_tekan"),
                    'ket_tumor'                 => $this->input->post("ket_tumor"),
                    'ket_limpa'                 => $this->input->post("ket_limpa"),
                    'ket_suara_usus'            => $this->input->post("ket_suara_usus"),
                    'ket_bekas_operasi'         => $this->input->post("ket_bekas_operasi"),
                    'ket_kulit'                 => $this->input->post("ket_kulit"),
                    'ket_dinding_thorax'        => $this->input->post("ket_dinding_thorax"),
                    'diam'                  => $this->input->post("diam"),
                    'bernafas'              => $this->input->post("bernafas"),
                    'paru_paru'             => $this->input->post("paru_paru"),
                    'suara_nafas'           => $this->input->post("suara_nafas"),
                    'ronchi'                => $this->input->post("ronchi"),
                    'suara_jantung'         => $this->input->post("suara_jantung"),
                    'ket_paru_paru'             => $this->input->post("ket_paru_paru"),
                    'ket_suara_nafas'           => $this->input->post("ket_suara_nafas"),
                    'ket_ronchi'                => $this->input->post("ket_ronchi"),
                    'ket_suara_jantung'         => $this->input->post("ket_suara_jantung"),
                    'irama_jantung'         => $this->input->post("irama_jantung"),
                    'tonus_otot'            => $this->input->post("tonus_otot"),
                    'parese'                => $this->input->post("parese"),
                    'tremor'                => $this->input->post("tremor"),
                    'atrofi'                => $this->input->post("atrofi"),
                    'oederma'               => $this->input->post("oederma"),
                    'postur_tubuh'          => $this->input->post("postur_tubuh"),
                    'kaki'                  => $this->input->post("kaki"),
                    'tangan'                => $this->input->post("tangan"),
                    'hemoroid'              => $this->input->post("hemoroid"),
                    'varises'               => $this->input->post("varises"),
                    'ket_tonus_otot'            => $this->input->post("ket_tonus_otot"),
                    'ket_parese'                => $this->input->post("ket_parese"),
                    'ket_tremor'                => $this->input->post("ket_tremor"),
                    'ket_atrofi'                => $this->input->post("ket_atrofi"),
                    'ket_oederma'               => $this->input->post("ket_oederma"),
                    'ket_postur_tubuh'          => $this->input->post("ket_postur_tubuh"),
                    'ket_kaki'                  => $this->input->post("ket_kaki"),
                    'ket_tangan'                => $this->input->post("ket_tangan"),
                    'ket_hemoroid'              => $this->input->post("ket_hemoroid"),
                    'ket_varises'               => $this->input->post("ket_varises"),
                    'varicocel'             => $this->input->post("varicocel"),
                    'ekstremitas'           => $this->input->post("ekstremitas"),
                    'lien'                     => $this->input->post("lien"),
                    'hepar'                 => $this->input->post("hepar"),
                    'atas'                    => $this->input->post("atas"),
                    'bawah'                 => $this->input->post("bawah"),
                    'lab'                   => $this->input->post("lab"),
                    'kesimpulan'            => $this->input->post("kesimpulan"),
                    'ket_kenal_warna'               => $this->input->post("ket_kenal_warna"),
                    'ket_gigi'               => $this->input->post("ket_gigi"),
                    'ket_diam'               => $this->input->post("ket_diam"),
                    'ket_bernafas'               => $this->input->post("ket_bernafas"),
                    'ket_hernia'               => $this->input->post("ket_hernia"),
                    'ket_hati'               => $this->input->post("ket_hati"),
                    'ket_irama_jantung'               => $this->input->post("ket_irama_jantung"),
                    'ekg_jantung'         => $this->input->post("ekg_jantung"),
                    'treadmill'         => $this->input->post("treadmill"),
                    'ket_varicocel'             => $this->input->post("ket_varicocel"),
                    'ket_lien'                  => $this->input->post("ket_lien"),
                    'ket_hepar'                 => $this->input->post("ket_hepar"),
                    'ket_atas'                  => $this->input->post("ket_atas"),
                    'ket_bawah'                 => $this->input->post("ket_bawah"),
                );
                $this->db->insert("pemeriksaan_fisik", $data);
                return "success-Data berhasil disimpan";
                break;
            case 'edit':
                $data = array(
                    'ket_makan_minum'   => $this->input->post("ket_makan_minum"),
                    'keluhan_penyakit'      => $this->input->post("keluhan_penyakit"),
                    'penyakit_berat'        => $this->input->post("penyakit_berat"),
                    'alergi'                => $this->input->post("alergi"),
                    'merokok'               => $this->input->post("merokok"),
                    'obat_rutin'            => $this->input->post("obat_rutin"),
                    'olahraga'              => $this->input->post("olahraga"),
                    'ket_riwayat_penyakitkel'   => $this->input->post("ket_riwayat_penyakitkel"),
                    'ket_keluhan_penyakit'      => $this->input->post("ket_keluhan_penyakit"),
                    'ket_penyakit_berat'        => $this->input->post("ket_penyakit_berat"),
                    'ket_alergi'                => $this->input->post("ket_alergi"),
                    'ket_merokok'               => $this->input->post("ket_merokok"),
                    'ket_obat_rutin'            => $this->input->post("ket_obat_rutin"),
                    'ket_riwayat_penyakitkel'   => $this->input->post("ket_riwayat_penyakitkel"),
                    'makan_minum'           => $this->input->post("makan_minum"),
                    'tinggi_badan'          => $this->input->post("tinggi_badan"),
                    'berat_badan'           => $this->input->post("berat_badan"),
                    'tekanan_darah'         => $this->input->post("tekanan_darah"),
                    'nadi'                  => $this->input->post("nadi"),
                    'anemik'                => $this->input->post("anemik"),
                    'respirasi'             => $this->input->post("respirasi"),
                    'ikterik'               => $this->input->post("ikterik"),
                    'rate_rr'               => $this->input->post("rate_rr"),
                    'kenal_warna'           => $this->input->post("kenal_warna"),
                    'visus_od'              => $this->input->post("visus_od"),
                    'visus_os'              => $this->input->post("visus_os"),
                    'juling'                => $this->input->post("juling"),
                    'ket_juling'                => $this->input->post("ket_juling"),
                    'telinga'               => $this->input->post("telinga"),
                    'mucosa'                => $this->input->post("mucosa"),
                    'tonsil'                => $this->input->post("tonsil"),
                    'ket_mucosa'                => $this->input->post("ket_mucosa"),
                    'ket_tonsil'                => $this->input->post("ket_tonsil"),
                    'gigi'                  => $this->input->post("gigi"),
                    'struma'                => $this->input->post("struma"),
                    'jvp'                   => $this->input->post("jvp"),
                    'perut'                 => $this->input->post("perut"),
                    'dinding_perut'         => $this->input->post("dinding_perut"),
                    'nyeri_tekan'           => $this->input->post("nyeri_tekan"),
                    'tumor'                 => $this->input->post("tumor"),
                    'hernia'                => $this->input->post("hernia"),
                    'hati'                  => $this->input->post("hati"),
                    'limpa'                 => $this->input->post("limpa"),
                    'suara_usus'            => $this->input->post("suara_usus"),
                    'bekas_operasi'         => $this->input->post("bekas_operasi"),
                    'kulit'                 => $this->input->post("kulit"),
                    'dinding_thorax'        => $this->input->post("dinding_thorax"),
                    'ket_struma'                => $this->input->post("ket_struma"),
                    'ket_jvp'                   => $this->input->post("ket_jvp"),
                    'ket_perut'                 => $this->input->post("ket_perut"),
                    'ket_dinding_perut'         => $this->input->post("ket_dinding_perut"),
                    'ket_nyeri_tekan'           => $this->input->post("ket_nyeri_tekan"),
                    'ket_tumor'                 => $this->input->post("ket_tumor"),
                    'ket_limpa'                 => $this->input->post("ket_limpa"),
                    'ket_suara_usus'            => $this->input->post("ket_suara_usus"),
                    'ket_bekas_operasi'         => $this->input->post("ket_bekas_operasi"),
                    'ket_kulit'                 => $this->input->post("ket_kulit"),
                    'ket_dinding_thorax'        => $this->input->post("ket_dinding_thorax"),
                    'diam'                  => $this->input->post("diam"),
                    'bernafas'              => $this->input->post("bernafas"),
                    'paru_paru'             => $this->input->post("paru_paru"),
                    'suara_nafas'           => $this->input->post("suara_nafas"),
                    'ronchi'                => $this->input->post("ronchi"),
                    'suara_jantung'         => $this->input->post("suara_jantung"),
                    'ket_paru_paru'             => $this->input->post("ket_paru_paru"),
                    'ket_suara_nafas'           => $this->input->post("ket_suara_nafas"),
                    'ket_ronchi'                => $this->input->post("ket_ronchi"),
                    'ket_suara_jantung'         => $this->input->post("ket_suara_jantung"),
                    'irama_jantung'         => $this->input->post("irama_jantung"),
                    'tonus_otot'            => $this->input->post("tonus_otot"),
                    'parese'                => $this->input->post("parese"),
                    'tremor'                => $this->input->post("tremor"),
                    'atrofi'                => $this->input->post("atrofi"),
                    'oederma'               => $this->input->post("oederma"),
                    'postur_tubuh'          => $this->input->post("postur_tubuh"),
                    'kaki'                  => $this->input->post("kaki"),
                    'tangan'                => $this->input->post("tangan"),
                    'hemoroid'              => $this->input->post("hemoroid"),
                    'varises'               => $this->input->post("varises"),
                    'ket_tonus_otot'            => $this->input->post("ket_tonus_otot"),
                    'ket_parese'                => $this->input->post("ket_parese"),
                    'ket_tremor'                => $this->input->post("ket_tremor"),
                    'ket_atrofi'                => $this->input->post("ket_atrofi"),
                    'ket_oederma'               => $this->input->post("ket_oederma"),
                    'ket_postur_tubuh'          => $this->input->post("ket_postur_tubuh"),
                    'ket_kaki'                  => $this->input->post("ket_kaki"),
                    'ket_tangan'                => $this->input->post("ket_tangan"),
                    'ket_hemoroid'              => $this->input->post("ket_hemoroid"),
                    'ket_varises'               => $this->input->post("ket_varises"),
                    'varicocel'             => $this->input->post("varicocel"),
                    'ekstremitas'           => $this->input->post("ekstremitas"),
                    'lien'                     => $this->input->post("lien"),
                    'hepar'                 => $this->input->post("hepar"),
                    'atas'                    => $this->input->post("atas"),
                    'bawah'                 => $this->input->post("bawah"),
                    'lab'                   => $this->input->post("lab"),
                    'kesimpulan'            => $this->input->post("kesimpulan"),
                    'ket_kenal_warna'               => $this->input->post("ket_kenal_warna"),
                    'ket_gigi'               => $this->input->post("ket_gigi"),
                    'ket_diam'               => $this->input->post("ket_diam"),
                    'ket_bernafas'               => $this->input->post("ket_bernafas"),
                    'ket_hernia'               => $this->input->post("ket_hernia"),
                    'ket_hati'               => $this->input->post("ket_hati"),
                    'ket_irama_jantung'               => $this->input->post("ket_irama_jantung"),
                    'ekg_jantung'         => $this->input->post("ekg_jantung"),
                    'treadmill'         => $this->input->post("treadmill"),
                    'ket_varicocel'             => $this->input->post("ket_varicocel"),
                    'ket_lien'                  => $this->input->post("ket_lien"),
                    'ket_hepar'                 => $this->input->post("ket_hepar"),
                    'ket_atas'                  => $this->input->post("ket_atas"),
                    'ket_bawah'                 => $this->input->post("ket_bawah"),
                    'genetalia'   => $this->input->post("genetalia"),
                    'ket_genetalia'   => $this->input->post("ket_genetalia"),
                );
                $this->db->where("no_reg", $this->input->post("no_reg"));
                // $this->db->where("no_pemeriksaan",$this->input->post("no_pemeriksaan"));
                $this->db->update("pemeriksaan_fisik", $data);
                return "info-Data berhasil diubah";
                break;
        }
    }
    function getbmi($no_reg)
    {
        $q      = $this->getmcu_detail($no_reg);
        $tb     = ($q->tinggi_badan / 100);
        $bb     = $q->berat_badan;
        $tb2    = pow($tb, 2);
        $bmi    = round($bb / $tb2, 1);

        $this->db->where("min<=", $bmi);
        $this->db->where("max>=", $bmi);
        $q1 = $this->db->get("bmi")->row();
        return $q1;
    }
    function getdirawatke($no_rm, $no_reg)
    {
        $this->db->where("no_rm", $no_rm);
        $this->db->where("no_reg<=", $no_reg);
        $q = $this->db->get("pasien_inap");
        return $q->num_rows();
    }
    function getlistpindahkamar($no_rm, $no_reg)
    {
        $this->db->select("r.nama_ruangan,k.nama_kelas,p.kode_kamar_lama,p.no_bed_lama");
        $this->db->order_by("p.tanggal,p.jam");
        $this->db->join("ruangan r", "r.kode_ruangan=p.kode_ruangan_lama", "inner");
        $this->db->join("kelas k", "k.kode_kelas=p.kode_kelas_lama", "inner");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pindahkamar p");
        return $q;
    }
    function getinap_ringkasan($no_pasien, $no_reg)
    {
        $this->db->select("i.*,r.nama_ruangan,k.nama_kelas,kp.keterangan as keadaan_pulang,s.keterangan as status_pulang,r.dokter_ruangan");
        $this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "inner");
        $this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "inner");
        $this->db->join("keadaan_pulang kp", "kp.id=i.keadaan_pulang", "left");
        $this->db->join("status_pulang s", "s.id=i.status_pulang", "left");
        $this->db->where("i.no_reg", $no_reg);
        $this->db->where("i.no_rm", $no_pasien);
        $q = $this->db->get("pasien_inap i");
        return $q->row();
    }
    function getassesmen_dokter($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("riwayat_pasien_inap");
        return $q->row();
    }
    function getindeksinap_icd9_ringkasan($no_reg)
    {
        $this->db->where("i.no_reg", $no_reg);
        $q = $this->db->get("indeks_inap_icd9 i");
        return $q;
    }
    function getindeksinap_icd10_ringkasan($no_reg)
    {
        $this->db->where("i.no_reg", $no_reg);
        $q = $this->db->get("indeks_inap_icd10 i");
        return $q;
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
    function getdokter_ahli($no_reg)
    {
        $this->db->select("kode_petugas");
        $this->db->where("no_reg", $no_reg);
        $this->db->where("kode_tarif", "viss");
        $q = $this->db->get("kasir_inap");
        return $q;
    }
    function getartikel()
    {
        $q   = $this->db->get("halaman");
        return $q;
    }
    function gettindakan_medis()
    {
        $q   = $this->db->get("tindakan_medis");
        return $q;
    }
    function simpan_tindakan_medis()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $q = $this->db->get("pasien_tindakan_medis");
        $tindakan_kedokteran = $this->input->post("tindakan_kedokteran");
        $tk = "";
        $koma = "";
        foreach ($tindakan_kedokteran as $key => $value) {
            $tk .= $koma . $value;
            $koma = ",";
        }
        $tindakan_anestesi = $this->input->post("tindakan_anestesi");
        $ta = "";
        $koma = "";
        foreach ($tindakan_anestesi as $key => $value) {
            $ta .= $koma . $value;
            $koma = ",";
        }
        $tindakan_transfusi = $this->input->post("tindakan_transfusi");
        $tf = "";
        $koma = "";
        foreach ($tindakan_transfusi as $key => $value) {
            $tf .= $koma . $value;
            $koma = ",";
        }
        $pi = explode("/", $this->input->post("pemberi_informasi"));
        $pb = explode("/", $this->input->post("saksirs"));
        $data = array(
            'no_reg' => $this->input->post("no_reg"),
            'jenis' => $this->input->post("jenis"),
            'tindakan_kedokteran' => $tk,
            'tindakan_anestesi' => $ta,
            'tindakan_transfusi' => $tf,
            'keterangan_tindakan_kedokteran' => $this->input->post("keterangan_kedokteran"),
            'keterangan_tindakan_anestesi' => $this->input->post("keterangan_anestesi"),
            'keterangan_tindakan_transfusi' => $this->input->post("keterangan_transfusi"),
            'pelaksana_tindakan' => $this->input->post("pelaksana_tindakan"),
            'pemberi_informasi' => $pi[1],
            'saksirs' => $pb[1],
            'kategori_pemberi_informasi' => $pi[0],
            'kategori_saksirs' => $pb[0],
        );
        if ($q->num_rows() > 0) {
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("pasien_tindakan_medis", $data);
        } else {
            $this->db->insert("pasien_tindakan_medis", $data);
        }
        return "success-Data berhasil disimpan ";
    }
    function getdokterperawat()
    {
        $data = array();
        $q = $this->db->get("dokter");
        foreach ($q->result() as $row) {
            $data["dokter"][$row->id_dokter] = $row->nama_dokter;
        }
        $q = $this->db->get("perawat");
        foreach ($q->result() as $row) {
            $data["perawat"][$row->id_perawat] = $row->nama_perawat;
        }
        return $data;
    }
    function simpan_berita_perawatan()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("surat_masuk_perawatan");
        if ($q->num_rows() > 0) {
            $data = array(
                "kepada" => $this->input->post("kepada"),
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("surat_masuk_perawatan", $data);
        } else {
            $id = $this->getnosurat("BMP", "surat_masuk_perawatan");
            $data = array(
                "nomor_surat" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "no_pasien" => $this->input->post("no_pasien"),
                "tgl_insert" => date("Y-m-d H:i:s"),
                "tahun" => date("Y"),
                "bulan" => date("m"),
                "jenis" => $this->input->post("jenis"),
                "kepada" => $this->input->post("kepada"),
            );
            $this->db->insert("surat_masuk_perawatan", $data);
        }
        return "success-Data berhasil disimpan ";
    }
    function simpan_berita_lepas_perawatan()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("surat_lepas_perawatan");
        if ($q->num_rows() > 0) {
            $data = array(
                "kepada" => $this->input->post("kepada"),
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("surat_lepas_perawatan", $data);
        } else {
            $id = $this->getnosurat("BLP", "surat_lepas_perawatan");
            $data = array(
                "nomor_surat" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "no_pasien" => $this->input->post("no_pasien"),
                "tgl_insert" => date("Y-m-d H:i:s"),
                "tahun" => date("Y"),
                "bulan" => date("m"),
                "jenis" => $this->input->post("jenis"),
                "kepada" => $this->input->post("kepada")
            );
            $this->db->insert("surat_lepas_perawatan", $data);
        }
        return "success-Data berhasil disimpan ";
    }
    function simpan_surat_istirahat_sakit()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("surat_istirahat_sakit");
        if ($q->num_rows() > 0) {
            $data = array(
                "kepada" => $this->input->post("kepada"),
                "selama" => $this->input->post("selama"),
                "mulai" => date("Y-m-d", strtotime($this->input->post("mulai"))),
                "sampai" => date("Y-m-d", strtotime($this->input->post("sampai")))
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("surat_istirahat_sakit", $data);
        } else {
            $id = $this->getnosurat("IST", "surat_istirahat_sakit");
            $data = array(
                "nomor_surat" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "no_pasien" => $this->input->post("no_pasien"),
                "tgl_insert" => date("Y-m-d H:i:s"),
                "tahun" => date("Y"),
                "bulan" => date("m"),
                "jenis" => $this->input->post("jenis"),
                "kepada" => $this->input->post("kepada"),
                "selama" => $this->input->post("selama"),
                "mulai" => date("Y-m-d", strtotime($this->input->post("mulai"))),
                "sampai" => date("Y-m-d", strtotime($this->input->post("sampai")))
            );
            $this->db->insert("surat_istirahat_sakit", $data);
        }
        return "success-Data berhasil disimpan ";
    }
    function simpan_surat_keterangan_dokter()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("surat_keterangan_dokter");
        if ($q->num_rows() > 0) {
            $data = array(
                "hasil" => $this->input->post("hasil1") . "," . $this->input->post("hasil2") . "," . $this->input->post("hasil3"),
                "batastgl" => date("Y-m-d", strtotime($this->input->post("batastgl"))),
                "untuk" => $this->input->post("untuk"),
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("surat_keterangan_dokter", $data);
        } else {
            $id = $this->getnosurat("SKD", "surat_keterangan_dokter");
            $data = array(
                "nomor_surat" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "no_pasien" => $this->input->post("no_pasien"),
                "tgl_insert" => date("Y-m-d H:i:s"),
                "tahun" => date("Y"),
                "bulan" => date("m"),
                "jenis" => $this->input->post("jenis"),
                "untuk" => $this->input->post("untuk"),
                "hasil" => $this->input->post("hasil1") . "," . $this->input->post("hasil2") . "," . $this->input->post("hasil3"),
                "batastgl" => date("Y-m-d", strtotime($this->input->post("batastgl")))
            );
            $this->db->insert("surat_keterangan_dokter", $data);
        }
        return "success-Data berhasil disimpan ";
    }
    function simpan_ket_narkoba()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("surat_narkoba");
        if ($q->num_rows() > 0) {
            $data = array(
                "anamnesis" => $this->input->post("anamnesis"),
                "fisik" => $this->input->post("fisik"),
                "batastgl" => date("Y-m-d", strtotime($this->input->post("batastgl"))),
                "untuk" => $this->input->post("untuk"),
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("surat_narkoba", $data);
        } else {
            $id = $this->getnosurat("SKBN", "surat_narkoba");
            $data = array(
                "nomor_surat" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "no_pasien" => $this->input->post("no_pasien"),
                "tgl_insert" => date("Y-m-d H:i:s"),
                "tahun" => date("Y"),
                "bulan" => date("m"),
                "jenis" => $this->input->post("jenis"),
                "untuk" => $this->input->post("untuk"),
                "anamnesis" => $this->input->post("anamnesis"),
                "fisik" => $this->input->post("fisik"),
                "batastgl" => date("Y-m-d", strtotime($this->input->post("batastgl")))
            );
            $this->db->insert("surat_narkoba", $data);
        }
        return "success-Data berhasil disimpan ";
    }
    function simpan_keterangan_jiwa()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("surat_jiwa");
        if ($q->num_rows() > 0) {
            $data = array(
                "hasil1" => $this->input->post("hasil1"),
                "hasil2" => $this->input->post("hasil2"),
                "batastgl" => date("Y-m-d", strtotime($this->input->post("batastgl"))),
                "untuk" => $this->input->post("untuk"),
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("surat_jiwa", $data);
        } else {
            $id = $this->getnosurat("SKDJ", "surat_jiwa");
            $data = array(
                "nomor_surat" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "no_pasien" => $this->input->post("no_pasien"),
                "tgl_insert" => date("Y-m-d H:i:s"),
                "tahun" => date("Y"),
                "bulan" => date("m"),
                "jenis" => $this->input->post("jenis"),
                "untuk" => $this->input->post("untuk"),
                "hasil1" => $this->input->post("hasil1"),
                "hasil2" => $this->input->post("hasil2"),
                "batastgl" => date("Y-m-d", strtotime($this->input->post("batastgl")))
            );
            $this->db->insert("surat_jiwa", $data);
        }
        return "success-Data berhasil disimpan ";
    }
    function getnosurat($jenis_surat, $tabel)
    {
        $this->db->where("jenis_surat", $jenis_surat);
        $this->db->where("tahun", date("Y"));
        $q1 = $this->db->get("setup_nomor");
        $row = $q1->row();
        for ($i = $row->mulai_nomor; $i <= 999; $i++) {
            $n = substr("000" . $i, -3);
            $where = array(
                "tahun"         => date("Y"),
                "nomor_surat"  => $n,
            );
            $q = $this->db->get_where($tabel, $where);
            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function getlock($jenis, $no_reg)
    {
        if ($jenis == "ralan") {
            $q = $this->db->get_where("pasien_ralan", ["no_reg" => $no_reg]);
        } else {
            $q = $this->db->get_where("pasien_inap", ["no_reg" => $no_reg]);
        }
        return $q->row();
    }
    function tempat_vaksin(){
      $q = $this->db->get("tempat_vaksin");
      $data = array();
      foreach ($q->result() as $key) {
        $data[$key->id] = $key->tempat;
      }
      return $data;
    }
    function getskrining_detail($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("skrining_vaksin");
        return $q->row();
    }
    function simpanskrining_vaksin()
    {
      $ttd = str_replace('data:image/png;base64,', '', $this->input->post('ttd'));
      $this->db->where("no_reg", $this->input->post("no_reg"));
      $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("skrining_vaksin");
        if ($q->num_rows() > 0) {
            $data = array(
              "tgl_lahir"       => date("Y-m-d",strtotime($this->input->post('tgl_lahir'))),
              "tgl_periksa"     => date("Y-m-d",strtotime($this->input->post('tgl_periksa'))),
              "nik"             => $this->input->post("nik"),
              "jam"             => $this->input->post("jam"),
              "umur"            => $this->input->post("umur"),
              "alamat"          => $this->input->post("alamat"),
              "no_hp"           => $this->input->post("no_hp"),
              "suhu"            => $this->input->post("suhu"),
              "tekanan_darah"   => $this->input->post("tekanan_darah"),
              "pertanyaan1_1"   => $this->input->post("pertanyaan1_1"),
              "pertanyaan1_2"   => $this->input->post("pertanyaan1_2"),
              "pertanyaan2"     => $this->input->post("pertanyaan2"),
              "pertanyaan3"     => $this->input->post("pertanyaan3"),
              "pertanyaan4"     => $this->input->post("pertanyaan4"),
              "pertanyaan5"     => $this->input->post("pertanyaan5"),
              "pertanyaan6"     => $this->input->post("pertanyaan6"),
              "pertanyaan7_1"   => $this->input->post("pertanyaan7_1"),
              "pertanyaan7_2"   => $this->input->post("pertanyaan7_2"),
              "pertanyaan7_3"   => $this->input->post("pertanyaan7_3"),
              "pertanyaan7_4"   => $this->input->post("pertanyaan7_4"),
              "pertanyaan7_5"   => $this->input->post("pertanyaan7_5"),
              // "anamnesa"        => $this->input->post("anamnesa"),
              "bersedia"        => $this->input->post("bersedia"),
              "ttd" => $ttd,
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("skrining_vaksin", $data);
        } else {
            $data = array(
                "no_reg"          => $this->input->post("no_reg"),
                "no_pasien"       => $this->input->post("no_pasien"),
                "tgl_lahir"       => date("Y-m-d",strtotime($this->input->post('tgl_lahir'))),
                "tgl_periksa"     => date("Y-m-d",strtotime($this->input->post('tgl_periksa'))),
                "nik"             => $this->input->post("nik"),
                "jam"             => $this->input->post("jam"),
                "umur"            => $this->input->post("umur"),
                "alamat"          => $this->input->post("alamat"),
                "no_hp"           => $this->input->post("no_hp"),
                "suhu"            => $this->input->post("suhu"),
                "tekanan_darah"   => $this->input->post("tekanan_darah"),
                "pertanyaan1_1"   => $this->input->post("pertanyaan1_1"),
                "pertanyaan1_2"   => $this->input->post("pertanyaan1_2"),
                "pertanyaan2"     => $this->input->post("pertanyaan2"),
                "pertanyaan3"     => $this->input->post("pertanyaan3"),
                "pertanyaan4"     => $this->input->post("pertanyaan4"),
                "pertanyaan5"     => $this->input->post("pertanyaan5"),
                "pertanyaan6"     => $this->input->post("pertanyaan6"),
                "pertanyaan7_1"   => $this->input->post("pertanyaan7_1"),
                "pertanyaan7_2"   => $this->input->post("pertanyaan7_2"),
                "pertanyaan7_3"   => $this->input->post("pertanyaan7_3"),
                "pertanyaan7_4"   => $this->input->post("pertanyaan7_4"),
                "pertanyaan7_5"   => $this->input->post("pertanyaan7_5"),
                // "anamnesa"        => $this->input->post("anamnesa"),
                "bersedia"        => $this->input->post("bersedia"),
                "ttd" => $ttd,
            );
            $this->db->insert("skrining_vaksin", $data);
            $message  = "success-Data berhasil di simpan";
  					return $message;
        }
    }
    function getpasien_skrining($no_reg)
    {
      $this->db->select("pi.*,pr.tgl_layani,pr.dokter_poli,pr.tanggal,p.no_pasien, p.nama_pasien as nama_pasien1,p.tgl_lahir,p.nik,p.nohp,p.alamat,pi.suhu,pi.td");
      $this->db->join("pasien_vaksin p", "p.no_pasien = pr.no_pasien", "left");
      $this->db->join("pasien_igd_vaksin pi", "p.no_pasien = pi.no_rm and pi.no_reg=pr.no_reg", "left");
      $this->db->where("pr.no_reg", $no_reg);
      $q = $this->db->get("pasien_ralan_vaksin pr");
      return $q->row();
    }
}
?>
