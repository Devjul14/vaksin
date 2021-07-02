<?php

class Mdokter extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getjadwaldokter()
    {
        $this->db->select("jd.*, d.nama_dokter, p.keterangan as nama_poli");
        $this->db->join("dokter d", "d.id_dokter = jd.id_dokter");
        $this->db->join("poliklinik p", "p.kode = jd.id_poli");
        $q = $this->db->get("jadwal_dokter jd");
        return $q;
    }

    function getkel()
    {
        $q = $this->db->get("kelompok_dokter");
        return $q;
    }

    function getkelompok()
    {
        $q = $this->db->get("kelompok_dokter");
        return $q;
    }

    function getkelompokdetail($id)
    {
        $this->db->where("id_kelompok", $id);
        $q = $this->db->get("kelompok_dokter");
        return $q->row();
    }

    function getdokterdetail($id)
    {
        $this->db->where("id_dokter", $id);
        $q = $this->db->get("dokter");
        return $q->row();
    }
    function getdokterarray()
    {
        $this->db->select("id_dokter,nama_dokter");
        $q = $this->db->get("dokter");
        $data = array();
        foreach ($q->result() as $row) {
            $data[$row->id_dokter] = $row->nama_dokter;
        }
        return $data;
    }
    function gettindakanarray()
    {
        $this->db->select("id_tindakan,nama_tindakan");
        $q = $this->db->get("tarif_radiologi");
        $data = array();
        foreach ($q->result() as $row) {
            $data["radiologi"][$row->id_tindakan] = $row->nama_tindakan;
        }
        $this->db->select("kode_tindakan,nama_tindakan");
        $q = $this->db->get("tarif_lab");
        foreach ($q->result() as $row) {
            $data["lab"][$row->kode_tindakan] = $row->nama_tindakan;
        }
        $this->db->select("kode,ket");
        $q = $this->db->get("tarif_penunjang_medis");
        foreach ($q->result() as $row) {
            $data["penunjang"][$row->kode] = $row->ket;
        }
        return $data;
    }
    function getjadwaldokterdetail($id)
    {
        $this->db->order_by("id_dokter");
        $this->db->order_by("id_poli");
        $this->db->where("id_jdokter", $id);
        $q = $this->db->get("jadwal_dokter");
        return $q->row();
    }
    function getpuskesmas()
    {
        $this->db->order_by("id_kecamatan,id_puskesmas", "asc");
        $query = $this->db->get("puskesmas");
        return $query;
    }
    function getuser($id)
    {
        $q = $this->db->get_where("user", array("nip" => $id));
        return $q;
    }
    function simpandokter($aksi, $nama_file)
    {
        $nama_file = str_replace('data:image/jpg;base64,', '', $this->input->post("source_foto"));
        $nama_photo = str_replace('data:image/jpg;base64,', '', $this->input->post("source_photo"));
        if ($nama_file != "") {
            $data = array(
                "id_dokter" => $this->input->post('id_dokter'),
                "nama_dokter" => $this->input->post('nama_dokter'),
                "gelar_depan" => $this->input->post('gelar_depan'),
                "gelar_belakang" => $this->input->post('gelar_belakang'),
                "kelompok_dokter" => $this->input->post('kelompok_dokter'),
                "no_sip" => $this->input->post('no_sip'),
                "no_str" => $this->input->post('no_str'),
                "tgl_sip" => $this->input->post('tgl_sip'),
                "no_telp" => $this->input->post('no_telp'),
                "alamat" => $this->input->post('alamat')
            );
            $data2 = array(
                "id_dokter" => $this->input->post('id_dokter'),
                "ttd" => $nama_file,
                "photo" => $nama_photo
            );
        } else {
            $data = array(
                "id_dokter" => $this->input->post('id_dokter'),
                "nama_dokter" => $this->input->post('nama_dokter'),
                "gelar_depan" => $this->input->post('gelar_depan'),
                "gelar_belakang" => $this->input->post('gelar_belakang'),
                "kelompok_dokter" => $this->input->post('kelompok_dokter'),
                "no_sip" => $this->input->post('no_sip'),
                "no_str" => $this->input->post('no_str'),
                "tgl_sip" => $this->input->post('tgl_sip'),
                "no_telp" => $this->input->post('no_telp'),
                "alamat" => $this->input->post('alamat')
            );
            $data2 = array("photo" => $nama_photo);
        }
        switch ($aksi) {
            case 'simpan':
                $q = $this->getdokterdetail($this->input->post('id_dokter'));
                if ($q) {
                    $msg  = "danger-Data dokter sudah ada sebelumnya";
                    // return $msg;
                } else {
                    $this->db->insert("dokter", $data);
                    $msg  = "success-Data dokter berhasil di simpan";
                    // return $msg;
                }
                break;
            case 'edit':
                $this->db->where("id_dokter", $this->input->post('id_dokter'));
                $this->db->update("dokter", $data);
                $msg  = "success-Data Dokter berhasil di simpan";
                break;
        }
        $q = $this->db->get_where("dokter_ttd", ["id_dokter" => $this->input->post('id_dokter')]);
        if ($q->num_rows() <= 0) {
            $this->db->insert("dokter_ttd", $data2);
        } else {
            $this->db->where("id_dokter", $this->input->post('id_dokter'));
            $this->db->update("dokter_ttd", $data2);
        }
        if ($this->input->post('password') != "") {
            $data = array("password" => md5($this->input->post('password')));
            $this->db->where("id_dokter", $this->input->post('id_dokter'));
            $this->db->update("dokter", $data);
        }
        return $msg;
    }
    function hapusdokter($id)
    {
        $this->db->where("id_dokter", $id);
        $this->db->delete("dokter");
        return "danger-Data Dokter berhasil di hapus";
    }
    function simpanjadwaldokter($aksi)
    {
        $h = "";
        $koma = "";
        for ($i = 0; $i <= 6; $i++) {
            $hari = $this->input->post('hari' . $i) != "" ? $this->input->post('hari' . $i) : 0;
            $h .= $koma . $hari;
            $koma = ",";
        }
        $jam_senin = $this->input->post('jam_senin');
        $jam_selasa = $this->input->post('jam_selasa');
        $jam_rabu = $this->input->post('jam_rabu');
        $jam_kamis = $this->input->post('jam_kamis');
        $jam_jumat = $this->input->post('jam_jumat');
        $jam_sabtu = $this->input->post('jam_sabtu');
        $jam_minggu = $this->input->post('jam_minggu');

        $jm_senin = $this->input->post('jm_senin');
        $jm_selasa = $this->input->post('jm_selasa');
        $jm_rabu = $this->input->post('jm_rabu');
        $jm_kamis = $this->input->post('jm_kamis');
        $jm_jumat = $this->input->post('jm_jumat');
        $jm_sabtu = $this->input->post('jm_sabtu');
        $jm_minggu = $this->input->post('jm_minggu');

        switch ($aksi) {
            case 'simpan':
                $data = array(
                    "id_jdokter" => $this->input->post('id_jdokter'),
                    "id_dokter" => $this->input->post('nama_dokter'),
                    "id_poli" => $this->input->post('nama_poli'),
                    "waktu" => $this->input->post('waktu'),
                    "hari" => $h,
                    "jam" => $jam_minggu . ',' . $jam_senin . ',' . $jam_selasa . ',' . $jam_rabu . ',' . $jam_kamis . ',' . $jam_jumat . ',' . $jam_sabtu,
                    "jam2" => $jm_minggu . ',' . $jm_senin . ',' . $jm_selasa . ',' . $jm_rabu . ',' . $jm_kamis . ',' . $jm_jumat . ',' . $jm_sabtu
                );
                $q = $this->getjadwaldokterdetail($this->input->post('id_jdokter'));
                if ($q) {
                    $msg  = "danger-Data dokter sudah ada sebelumnya";
                    return $msg;
                } else {
                    $this->db->insert("jadwal_dokter", $data);
                    $msg  = "success-Data Jadwal Dokter berhasil di simpan";
                    return $msg;
                }
                break;
            case 'edit':
                $data = array(
                    "id_dokter" => $this->input->post('nama_dokter'),
                    "id_poli" => $this->input->post('nama_poli'),
                    "waktu" => $this->input->post('waktu'),
                    "hari" => $h,
                    "jam" => $jam_minggu . ',' . $jam_senin . ',' . $jam_selasa . ',' . $jam_rabu . ',' . $jam_kamis . ',' . $jam_jumat . ',' . $jam_sabtu,
                    "jam2" => $jm_minggu . ',' . $jm_senin . ',' . $jm_selasa . ',' . $jm_rabu . ',' . $jm_kamis . ',' . $jm_jumat . ',' . $jm_sabtu
                );
                $this->db->where("id_jdokter", $this->input->post('id_jdokter'));
                $this->db->update("jadwal_dokter", $data);
                break;
        }
        $msg  = "success-Data Jadwal Dokter berhasil di simpan";
        return $msg;
    }
    function hapusjadwaldokter($id)
    {
        $this->db->where("id_jdokter", $id);
        $this->db->delete("jadwal_dokter");
        return "danger-Data Jadwal Dokter berhasil di hapus";
    }
    function simpankelompok($aksi)
    {
        $data = array(
            "id_kelompok" => $this->input->post('id_kelompok'),
            "nama_kelompok" => $this->input->post('nama_kelompok'),


        );
        switch ($aksi) {
            case 'simpan':
                $q = $this->getkelompokdetail($this->input->post('id_kelompok'));
                if ($q) {
                    $msg  = "danger-Data kelompok sudah ada sebelumnya";
                    return $msg;
                } else {
                    $this->db->insert("kelompok_dokter", $data);
                    $msg  = "success-Data kelompok berhasil di simpan";
                    return $msg;
                }
                break;
            case 'edit':
                $this->db->where("id_kelompok", $this->input->post('id_kelompok'));
                $this->db->update("kelompok_dokter", $data);
                break;
        }
        $msg  = "success-Data kelompok berhasil di simpan";
        return $msg;
    }
    function hapuskelompok($id)
    {
        $this->db->where("id_kelompok", $id);
        $this->db->delete("kelompok_dokter");
        return "danger-Data kelompok berhasil di hapus";
    }
    function getkecamatan()
    {
        $this->db->order_by("nama_kecamatan", "asc");
        $query = $this->db->get("kecamatan");
        return $query;
    }
    function getkecamatandetail($id)
    {
        $query = $this->db->get_where("kecamatan", array("id_kecamatan" => $id));
        return $query;
    }
    function simpankecamatan($action)
    {
        $data = array("nama_kecamatan" => $this->input->post('nama_kecamatan'));
        switch ($action) {
            case 'simpan':
                $this->db->insert("kecamatan", $data);
                break;
            case 'edit':
                $this->db->where("id_kecamatan", $this->input->post('idlama'));
                $this->db->update("kecamatan", $data);
                break;
        }
        return "success-Data Kecamatan berhasil di simpan";
    }
    function hapuskecamatan($id)
    {
        $this->db->where("id_kecamatan", $id);
        $this->db->delete("kecamatan");
        return "info-Data berhasil di hapus";
    }
    function getkelurahan($id_kecamatan = NULL)
    {
        $this->db->select("a.*,b.nama_kecamatan");
        $this->db->join("kecamatan b", "b.id_kecamatan=a.id_kecamatan", "inner");
        if ($id_kecamatan <> NULL) $this->db->where("a.id_kecamatan", $id_kecamatan);
        $this->db->order_by("nama_kecamatan,nama_kelurahan", "asc");
        $query = $this->db->get("kelurahan a");
        return $query;
    }
    function getrw($id_kecamatan, $id_kelurahan)
    {
        $this->db->order_by("nama_rw", "asc");
        $query = $this->db->get_where("rw", array("id_kecamatan" => $id_kecamatan, "id_kelurahan" => $id_kelurahan));
        return $query;
    }
    function getkelurahandetail($id)
    {
        $query = $this->db->get_where("kelurahan", array("id_kelurahan" => $id));
        return $query;
    }
    function simpankelurahan($action)
    {
        switch ($action) {
            case 'simpan':
                $sql = "insert into kelurahan set
            id_kecamatan='" . $this->input->post('id_kecamatan') . "',
            nama_kelurahan='" . $this->input->post('nama_kelurahan') . "'";
                break;
            case 'edit':
                $sql = "update kelurahan set
            id_kecamatan='" . $this->input->post('id_kecamatan') . "',
            nama_kelurahan='" . $this->input->post('nama_kelurahan') . "' where id_kelurahan='" . $this->input->post('idlama') . "'";
                break;
        }
        $this->db->query($sql);
        $msg  = "<span class='message info'>Data berhasil di input</span>";
        return $msg;
    }
    function hapuskelurahan($id)
    {
        $sql = "delete from kelurahan where id_kelurahan='" . $id . "'";
        $this->db->query($sql);
        $msg  = "<span class='message info'>Data berhasil di hapus</span>";
        return $msg;
    }
    function getpuskesmas2($id_kecamatan = NULL)
    {
        $sql = "select a.*,b.nama_kecamatan from puskesmas a inner join kecamatan b on(b.id_kecamatan=a.id_kecamatan) ";
        if ($id_kecamatan <> NULL) $sql .= " where a.id_kecamatan='" . $id_kecamatan . "' ";
        $sql .= "order by nama_kecamatan,nama_puskesmas";
        $query = $this->db->query($sql);
        return $query;
    }
    function getpuskesmasdetail($id)
    {
        $sql = "select * from puskesmas where id_puskesmas='" . $id . "'";
        $query = $this->db->query($sql);
        return $query;
    }
    function simpanpuskesmas($aksi)
    {
        switch ($aksi) {
            case 'simpan':
                $sql = "insert into puskesmas set
            id_puskesmas='" . $this->input->post('id_puskesmas') . "',
            id_kecamatan='" . $this->input->post('id_kecamatan') . "',
            nama_puskesmas='" . $this->input->post('nama_puskesmas') . "',
            alamat='" . $this->input->post('alamat') . "',
            kepala='" . $this->input->post('kepala') . "',
            telepon='" . $this->input->post('telepon') . "',
            nip='" . $this->input->post('nip') . "'";
                break;
            case 'edit':
                $sql = "update puskesmas set
            id_kecamatan='" . $this->input->post('id_kecamatan') . "',
            nama_puskesmas='" . $this->input->post('nama_puskesmas') . "',
            alamat='" . $this->input->post('alamat') . "',
            kepala='" . $this->input->post('kepala') . "',
            telepon='" . $this->input->post('telepon') . "',
            nip='" . $this->input->post('nip') . "' where id_puskesmas='" . $this->input->post('idlama') . "'";
                break;
        }
        $this->db->query($sql);
        $msg  = "success-Data Puskesmas berhasil di simpan";
        return $msg;
    }
    function hapuspuskesmas($id)
    {
        $sql = "delete from puskesmas where id_puskesmas='" . $id . "'";
        $this->db->query($sql);
        $msg  = "danger-Data Puskesmas berhasil di hapus";
        return $msg;
    }
    function getlayanan()
    {
        $sql = "select * from layanan order by layanan";
        $query = $this->db->query($sql);
        return $query;
    }
    function getlayanandetail($id)
    {
        $sql = "select * from layanan where id_layanan='" . $id . "'";
        $query = $this->db->query($sql);
        return $query;
    }
    function simpanlayanan($aksi)
    {
        switch ($aksi) {
            case 'simpan':
                $sql = "insert into layanan set
            id_layanan='" . $this->input->post('id_layanan') . "',
            layanan='" . $this->input->post('nama_layanan') . "',
            karcis='" . $this->input->post('karcis') . "'";
                break;
            case 'edit':
                $sql = "update layanan set
            layanan='" . $this->input->post('nama_layanan') . "',
            karcis='" . $this->input->post('karcis') . "' where id_layanan='" . $this->input->post('idlama') . "'";
                break;
        }
        $this->db->query($sql);
        $msg  = "success-Data Layanan berhasil di simpan";
        return $msg;
    }

    function hapuslayanan($id)
    {

        $sql = "delete from layanan where id_layanan='" . $id . "'";

        $this->db->query($sql);

        $msg  = "danger-Data Layanan berhasil di hapus";

        return $msg;
    }

    function getstatususer()
    {

        $sql = "select * from status_user order by id";

        $query = $this->db->query($sql);

        return $query;
    }
    function getgelar_depan()
    {
        return $this->db->get("gelar_depan");
    }
    function getgelardepan_detail($id)
    {
        $this->db->where("id_gelar", $id);
        return $this->db->get("gelar_depan")->row();
    }
    function simpangelar_depan($aksi)
    {
        $data = array(
            "id_gelar" => $this->input->post('id_gelar'),
            "nama_gelar" => $this->input->post('nama_gelar'),


        );
        switch ($aksi) {
            case 'simpan':
                $q = $this->getgelardepan_detail($this->input->post('id_gelar'));
                if ($q) {
                    $msg  = "danger-Data sudah ada sebelumnya";
                    return $msg;
                } else {
                    $this->db->insert("gelar_depan", $data);
                    $msg  = "success-Data berhasil di simpan";
                    return $msg;
                }
                break;
            case 'edit':
                $this->db->where("id_gelar", $this->input->post('id_gelar'));
                $this->db->update("gelar_depan", $data);
                break;
        }
        $msg  = "success-Data kelompok berhasil di simpan";
        return $msg;
    }
    function hapusgelar_depan($id)
    {
        $this->db->where("id_gelar", $id);
        $this->db->delete("gelar_depan");
        return "danger-Data berhasil di hapus";
    }
    function getgelar_belakang()
    {
        return $this->db->get("gelar_belakang");
    }
    function getgelarbelakang_detail($id)
    {
        $this->db->where("id_gelar", $id);
        return $this->db->get("gelar_belakang")->row();
    }
    function simpangelar_belakang($aksi)
    {
        $data = array(
            "id_gelar" => $this->input->post('id_gelar'),
            "nama_gelar" => $this->input->post('nama_gelar'),


        );
        switch ($aksi) {
            case 'simpan':
                $q = $this->getgelarbelakang_detail($this->input->post('id_gelar'));
                if ($q) {
                    $msg  = "danger-Data sudah ada sebelumnya";
                    return $msg;
                } else {
                    $this->db->insert("gelar_belakang", $data);
                    $msg  = "success-Data berhasil di simpan";
                    return $msg;
                }
                break;
            case 'edit':
                $this->db->where("id_gelar", $this->input->post('id_gelar'));
                $this->db->update("gelar_belakang", $data);
                break;
        }
        $msg  = "success-Data kelompok berhasil di simpan";
        return $msg;
    }
    function hapusgelar_belakang($id)
    {
        $this->db->where("id_gelar", $id);
        $this->db->delete("gelar_belakang");
        return "danger-Data berhasil di hapus";
    }

    //-------------------------------


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
        $this->db->where("poli", $kode_poli);
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
            "telepon_pj" => $this->input->post('telepon_pj')
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
        $this->db->select("p.*,jk.keterangan as jenis_kelamin,k.nama as status_kawin,pen.pendidikan as pendidikan,g.keterangan as nama_golongan, p.no_pasien as no_rekmed, p.tanggal as trk, per.nama as nama_perusahaan, pan.keterangan as pangkat");
        $this->db->join("jenis_kelamin jk", "jk.jenis_kelamin=p.jenis_kelamin", "left");
        $this->db->join("kawin k", "k.kode=p.status_kawin", "left");
        $this->db->join("pendidikan pen", "pen.idx=p.pendidikan", "left");
        // $this->db->join("pekerjaan pek","pek.idx=p.pekerjaan","left");
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
        // if ($this->session->flashdata("no_pasien")!=""){
        // 	$no_pasien = "000000".$this->session->flashdata("no_pasien");
        // 	$this->db->where("no_pasien",substr($no_pasien,-6));
        // }
        // if ($this->session->flashdata("nama")!=""){
        // 	$this->db->like("nama_pasien",$this->session->flashdata("nama"));
        // }
        $this->db->like("nama_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("no_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("no_bpjs", $this->session->userdata("no_pasien"));
        $this->db->or_like("nip", $this->session->userdata("no_pasien"));
        $this->db->or_like("ktp", $this->session->userdata("no_pasien"));
        $this->db->order_by("no_pasien");
        $query = $this->db->get("pasien", $page, $offset);
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
        // if ($this->session->flashdata("no_pasien")!=""){
        // 	$this->db->where("no_pasien",$this->session->flashdata("no_pasien"));
        // }
        // if ($this->session->flashdata("nama")!=""){
        // 	$this->db->like("nama_pasien",$this->session->flashdata("nama"));
        // }
        $this->db->like("nama_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("no_pasien", $this->session->userdata("no_pasien"));
        $this->db->or_like("no_bpjs", $this->session->userdata("no_pasien"));
        $this->db->or_like("nip", $this->session->userdata("no_pasien"));
        $this->db->or_like("ktp", $this->session->userdata("no_pasien"));
        $query = $this->db->get("pasien");
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
        $this->db->select("pr.*,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, g.keterangan as gol_pasien");
        // if ($no_pasien!="") {
        // 	$no_pasien = "000000".$no_pasien;
        // 	$this->db->where("p.no_pasien",substr($no_pasien,-6));
        // }
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
        $this->db->or_like("p.nip", $this->session->userdata("no_pasien"));
        $this->db->or_like("p.ktp", $this->session->userdata("no_pasien"));
        $this->db->group_end();
        if ($poli_kode != "") {
            $this->db->where("pr.tujuan_poli", $poli_kode);
        }
        if ($kode_dokter != "") {
            $this->db->where("pr.dokter_poli", $kode_dokter);
        }
        if ($status_pasien != "ALL") {
            $this->db->where("pr.status_pasien", $status_pasien);
        }
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
        }
        $this->db->join("pasien p", "p.no_pasien=pr.no_pasien");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
        $this->db->join("poliklinik pol", "pol.kode=pr.dari_poli", "left");
        $this->db->join("poliklinik pol2", "pol2.kode=pr.tujuan_poli", "left");
        $query = $this->db->get("pasien_ralan pr");
        return $query->num_rows();
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
        $this->db->select("i.*");
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
        $this->db->or_like("nama_pasien", $no_pasien);
        $this->db->group_end();
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("i.tanggal>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("i.tanggal<=", date("Y-m-d", strtotime($tgl2)));
        }
        $this->db->order_by("no_reg", "desc");
        $this->db->order_by("no_reg,no_rm");
        $query = $this->db->get("pasien_triage i");
        return $query->num_rows();
    }
    function getpasien_rawatinapigd()
    {
        $kode_kelas = $this->session->userdata("kode_kelas");
        $kode_ruangan = $this->session->userdata("kode_ruangan");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $nama = $this->session->userdata("nama");
        $this->db->select("i.*");
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
        $this->db->like("i.no_pasien", $no_pasien);
        $this->db->or_like("no_reg", $no_pasien);
        $this->db->or_like("nama_pasien", $no_pasien);
        $this->db->group_end();
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("date(i.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("date(i.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
        }
        $this->db->where("tujuan_poli", "0102030");
        $this->db->order_by("no_reg", "desc");
        $this->db->order_by("no_reg,no_pasien");
        $query = $this->db->get("pasien_ralan i");
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
        $status_pasien = $this->session->userdata("status_pasien");
        $nama = $this->session->userdata("nama");
        $this->db->select("p.no_bpjs,pr.*,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien, g.keterangan as gol_pasien");
        $this->db->group_start();
        $this->db->like("p.no_pasien", $no_pasien);
        $this->db->or_like("no_reg", $no_pasien);
        $this->db->or_like("no_bpjs", $no_pasien);
        $this->db->or_like("no_sjp", $no_pasien);
        $this->db->or_like("p.nama_pasien", $no_pasien);
        $this->db->or_like("p.nip", $this->session->userdata("no_pasien"));
        $this->db->or_like("p.ktp", $this->session->userdata("no_pasien"));
        $this->db->group_end();
        if ($poli_kode != "") {
            $this->db->where("pr.tujuan_poli", $poli_kode);
        }
        if ($kode_dokter != "") {
            $this->db->where("pr.dokter_poli", $kode_dokter);
        }
        if ($status_pasien != "ALL") {
            $this->db->where("pr.status_pasien", $status_pasien);
        }
        if ($tgl1 != "" or $tgl2 != "") {
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
    function getpasien_inap($page, $offset)
    {
        $kode_kelas = $this->session->userdata("kode_kelas");
        $kode_ruangan = $this->session->userdata("kode_ruangan");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $nama = $this->session->userdata("nama");
        $this->db->select("i.*");
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
        $this->db->or_like("nama_pasien", $no_pasien);
        $this->db->group_end();
        $this->db->order_by("no_reg", "desc");
        $this->db->order_by("no_reg,no_rm");
        $query = $this->db->get("pasien_triage i", $page, $offset);
        return $query;
    }
    function getpasien_inapigd($page, $offset)
    {
        $kode_kelas = $this->session->userdata("kode_kelas");
        $kode_ruangan = $this->session->userdata("kode_ruangan");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $nama = $this->session->userdata("nama");
        $this->db->select("i.*,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan, p.nama_pasien as nama_pasien, g.keterangan as gol_pasien");
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
        $this->db->like("i.no_pasien", $no_pasien);
        $this->db->or_like("i.no_reg", $no_pasien);
        $this->db->or_like("p.nama_pasien", $no_pasien);
        $this->db->group_end();
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("date(i.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("date(i.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
        }
        $this->db->where("tujuan_poli", "0102030");
        $this->db->join("pasien p", "p.no_pasien=i.no_pasien", "left");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
        $this->db->join("poliklinik pol", "pol.kode=i.dari_poli", "left");
        $this->db->join("poliklinik pol2", "pol2.kode=i.tujuan_poli", "left");
        $this->db->order_by("no_reg", "desc");
        $this->db->order_by("no_reg,no_pasien");
        $query = $this->db->get("pasien_ralan i", $page, $offset);
        return $query;
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
        $this->db->select("p.*,pus.nama_puskesmas,prov.name as nama_provinsi,r.name as nama_kota,d.name as nama_kecamatan, v.name as nama_kelurahan,per.nama as nama_perusahaan,pa.keterangan as nama_pangkat,k.keterangan as nama_kesatuan");
        $this->db->join("puskesmas pus", "pus.id_puskesmas=p.id_puskesmas", "left");
        $this->db->join("provinces prov", "prov.id=p.id_provinsi", "left");
        $this->db->join("regencies r", "r.id=p.id_kota", "left");
        $this->db->join("districts d", "d.id=p.id_kecamatan", "left");
        $this->db->join("villages v", "v.id=p.id_kelurahan", "left");
        $this->db->join("perusahaan per", "per.kode=p.perusahaan", "left");
        $this->db->join("pangkat pa", "pa.id_pangkat=p.id_pangkat", "left");
        $this->db->join("kesatuan k", "k.id_kesatuan=p.id_kesatuan", "left");
        $this->db->where("no_pasien", $id_pasien);
        $q = $this->db->get("pasien p");
        return $q->row();
    }
    function datapasien($kode, $cari)
    {
        $sql = "select * from pasien where " . $kode . "='" . $cari . "'";
        $query = $this->db->query($sql);
        return $query->row();
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
        $this->db->select("p.*,g.keterangan,pe.nama as perusahaan,pe.kode as kode_perusahaan");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol");
        $this->db->join("perusahaan pe", "pe.kode=p.perusahaan", "left");
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
        $poli_kode = $this->session->userdata("poli_kode");
        $kode_dokter = $this->session->userdata("kode_dokter");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $status_pasien = $this->session->userdata("status_pasien");
        $nama = $this->session->userdata("nama");
        $this->db->select("pr.*,pol.keterangan as poli_asal,pol2.keterangan as poli_tujuan,p.nama_pasien as nama_pasien");
        if ($no_pasien != "") {
            $no_pasien = "000000" . $no_pasien;
            $this->db->where("p.no_pasien", substr($no_pasien, -6));
        }
        if ($no_reg != "") {
            $this->db->where("no_reg", $no_reg);
        }
        if ($nama != "") {
            $this->db->like("p.nama_pasien", $nama);
        }
        if ($poli_kode != "") {
            $this->db->where("pr.tujuan_poli", $poli_kode);
        }
        if ($kode_dokter != "") {
            $this->db->where("pr.dokter_poli", $kode_dokter);
        }
        if ($status_pasien != "ALL") {
            $this->db->where("pr.status_pasien", $status_pasien);
        }
        if ($tgl1 != "" or $tgl2 != "") {
            $this->db->where("date(pr.tanggal)>=", date("Y-m-d", strtotime($tgl1)));
            $this->db->where("date(pr.tanggal)<=", date("Y-m-d", strtotime($tgl2)));
        }
        if ($jenis == "LAYAN") {
            $this->db->where("layan<>", 2);
        } else {
            $this->db->where("layan", 2);
        }
        $this->db->join("pasien p", "p.no_pasien=pr.no_pasien");
        $this->db->join("poliklinik pol", "pol.kode=pr.dari_poli", "left");
        $this->db->join("poliklinik pol2", "pol2.kode=pr.tujuan_poli", "left");
        $query = $this->db->get("pasien_ralan pr");
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
        $q = $this->db->get("pasien_ralan")->row();
        if ($q->tgl_layani == "0000-00-00 00:00:00" || $q->tgl_layani === NULL) {
            if ($q->tgl_terimapasien == "0000-00-00 00:00:00" || $q->tgl_terimapasien === NULL) {
                return "warning-Pasien belum pulang";
            } else {
                $data = array('tgl_layani' => date("Y-m-d H:i:s"));
                $this->db->where("no_pasien", $no_rm);
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ralan", $data);
                return "success-Pasien berhasil dilayani";
            }
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
        $this->db->select("pr.*,pr.tanggal as tgl_masuk,p.telpon,p.no_bpjs,p.tgl_lahir,p.alamat,p.nama_pasien,pl.keterangan as poli,g.keterangan as ket_gol_pasien,g1.keterangan as ket_gol_pasien1, m.nama as diagnosa, an.nama as jenis_anastesi");
        $this->db->join("pasien_vaksin p", "pr.no_pasien=p.no_pasien");
        $this->db->join("poliklinik pl", "pl.kode=pr.tujuan_poli");
        $this->db->join("gol_pasien g", "g.id_gol=pr.gol_pasien", "left");
        $this->db->join("gol_pasien g1", "g1.id_gol=p.id_gol", "left");
        $this->db->join("master_icd m", "m.kode=pr.diagnosa", "left");
        $this->db->join("jenis_anatesi an", "an.kode=pr.jenis_anastesi", "left");
        // $this->db->join("poliklinik pol","pol.kode=pr.tujuan_poli","left");
        $this->db->where("pr.no_pasien", $no_pasien);
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan_vaksin pr");
        return $q->row();
    }
    function getlaporan_tindakaninap($no_pasien, $no_reg)
    {
        $this->db->select("pr.*,p.telpon,p.no_bpjs,p.tgl_lahir,p.alamat,p.nama_pasien,g.keterangan as ket_gol_pasien,g1.keterangan as ket_gol_pasien1, m.nama as diagnosa, an.nama as jenis_anastesi,r.nama_ruangan, k.nama_kelas, kam.nama_kamar,d.nama_dokter as dokter_dpjp");
        $this->db->join("pasien p", "pr.no_rm=p.no_pasien");
        $this->db->join("gol_pasien g", "g.id_gol=pr.id_gol", "left");
        $this->db->join("gol_pasien g1", "g1.id_gol=p.id_gol", "left");
        $this->db->join("master_icd m", "m.kode=pr.diagnosa", "left");
        $this->db->join("jenis_anatesi an", "an.kode=pr.jenis_anastesi", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=pr.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=pr.kode_kelas", "left");
        $this->db->join("kamar kam", "kam.kode_kamar=pr.kode_kamar and kam.kode_ruangan = r.kode_ruangan and k.kode_kelas = kam.kode_kelas", "left");
        $this->db->join("dokter d", "pr.dpjp=d.id_dokter", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $this->db->group_by("pr.no_reg");
        $q = $this->db->get("pasien_inap pr");
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
        $q = $this->db->get("inos i");
        return $q;
    }
    function simpaninos()
    {
        $data = array(
            'kode_inos' => date("dmYHis"),
            'no_pasien' => $this->input->post("no_pasien"),
            'no_reg' => $this->input->post("no_reg"),
            'jenis_inos' => $this->input->post("jenis_inos"),
            'spesialisasi' => $this->input->post("spesialisasi"),
            'tanggal' => date("Y-m-d H:i:s"),
        );
        $this->db->insert("inos", $data);
        return "success-Berhasil diinput";
    }
    function hapusinos($no_pasien, $no_reg, $jenis_inos)
    {
        $this->db->where("no_pasien", $no_pasien);
        $this->db->where("no_reg", $no_reg);
        $this->db->where("jenis_inos", $jenis_inos);
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
        $data = array(
            'diagnosa' => $this->input->post("diagnosa"),
            'tindakan_operasi' => $this->input->post("tindakan"),
            'dokter_operasi' => $this->input->post("dokter"),
            'asisten_operasi' => $this->input->post("asisten"),
            'jenis_anastesi' => $this->input->post("jenis_anastesi"),
            'pemeriksaan_penunjang' => $this->input->post("pemeriksaan_penunjang"),
            'tanggal_operasi' => date("Y-m-d", strtotime($this->input->post("tanggal"))),
            'tanggal_ulangan' => date("Y-m-d", strtotime($this->input->post("ulangan"))),
            'jam_mulai' => $this->input->post("jam_masuk"),
            'jam_selesai' => $this->input->post("jam_keluar"),
            'keterangan' => $this->input->post("keterangan"),
            'laporan_operasi' => $this->input->post("laporan_operasi"),
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
        return "success-Data Berhasil tersimpan...";
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
        return "success-Data Berhasil tersimpan...";
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
        return "success-Data Berhasil tersimpan...";
    }
    function getoka_detail($kode)
    {
        $this->db->select("o.*,r.nama_ruangan,kl.nama_kelas, t.nama_tindakan as nama_operasi");
        $this->db->join("tarif_operasi t", "t.kode = o.operasi", "left");
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
        $this->db->select("pt.*, t.doa");
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
                'doa' => $this->input->post("doa"),
                'td' => $this->input->post("td"),
                'td2' => $this->input->post("td2"),
                'nadi' => $this->input->post("nadi"),
                'respirasi' => $this->input->post("respirasi"),
                'suhu' => $this->input->post("suhu"),
                'spo2' => $this->input->post("spo2"),
                'bb' => $this->input->post("bb"),
                'tb' => $this->input->post("tb"),
                'lokasi' => $this->input->post("lokasi"),
                'frekuensi' => $this->input->post("frekuensi"),
                'durasi' => $this->input->post("durasi"),
                'tanggal' => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                'jam' => date("H:i:s", strtotime($this->input->post("jam"))),
                // 'tindak_lanjut' => $this->input->post("tindak_lanjut"),
                // 'rujuk_ke' => $this->input->post("rujuk_ke"),
                // 'alasan_rujuk' => $this->input->post("alasan_rujuk"),
            );
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_triage", $data);
            if ($this->input->post("tindak_lanjut") == "ralan") {
                $data = array(
                    "tanggal_masuk" => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                    "jam_masuk" => date("H:i:s", strtotime($this->input->post("jam"))),
                    'jam_periksa' => date("H:i:s", strtotime($this->input->post("waktu_keputusan")))
                );
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_igd", $data);
                $data = array(
                    "dokter_poli" => $this->input->post("dokter_igd")
                );
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_ralan", $data);
            } else {
                $data = array(
                    "tanggal_masuk" => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                    "jam_masuk" => date("H:i:s", strtotime($this->input->post("jam"))),
                    'jam_periksa' => date("H:i:s", strtotime($this->input->post("waktu_keputusan")))
                );
                $this->db->where("no_reg", $no_reg);
                $this->db->update("pasien_igdinap", $data);
            }
        } else {
            $no_reg = date("YmdHis");
            if ($this->input->post("tindak_lanjut") == "ralan") {
                $n = $this->db->get_where("pasien_ralan", ["no_reg" => $no_reg]);
                while ($n->num_rows() > 0) {
                    $no_reg++;
                    $n = $this->db->get_where("pasien_ralan", ["no_reg" => $no_reg]);
                }
            } else {
                $n = $this->db->get_where("pasien_inap", ["no_reg" => $no_reg]);
                while ($n->num_rows() > 0) {
                    $no_reg++;
                    $n = $this->db->get_where("pasien_inap", ["no_reg" => $no_reg]);
                }
            }
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
                'doa' => $this->input->post("doa"),
                'td' => $this->input->post("td"),
                'td2' => $this->input->post("td2"),
                'nadi' => $this->input->post("nadi"),
                'respirasi' => $this->input->post("respirasi"),
                'suhu' => $this->input->post("suhu"),
                'spo2' => $this->input->post("spo2"),
                'bb' => $this->input->post("bb"),
                'tb' => $this->input->post("tb"),
                'lokasi' => $this->input->post("lokasi"),
                'frekuensi' => $this->input->post("frekuensi"),
                'durasi' => $this->input->post("durasi"),
                'rujuk_ke' => $this->input->post("rujuk_ke"),
                'alasan_rujuk' => $this->input->post("alasan_rujuk"),
                'tanggal' => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                'jam' => date("H:i:s", strtotime($this->input->post("jam"))),
                'tindak_lanjut' => $this->input->post("tindak_lanjut"),
                'rujuk_ke' => $this->input->post("rujuk_ke"),
                'alasan_rujuk' => $this->input->post("alasan_rujuk"),
            );
            $this->db->insert("pasien_triage", $data);
            $this->simpan_pasien($no_reg);
        }
        return "success-Data Berhasil tersimpan...-" . $no_reg;
    }
    function getpetugas_igd()
    {
        return $this->db->get("petugas_igd");
    }
    function getpasien_igdralan($no_reg)
    {
        $this->db->select("pi.*,p.alergi as riwayat_alergi, p.nama_pasien as nama_pasien1");
        $this->db->join("pasien p", "p.no_pasien = pr.no_pasien", "left");
        $this->db->join("pasien_igd pi", "pi.no_rm=p.no_pasien and pi.no_reg = pr.no_reg", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function getpasien_igd($no_reg)
    {
        $this->db->select("pi.*,p.alergi as riwayat_alergi, p.nama_pasien as nama_pasien1");
        $this->db->join("pasien p", "p.no_pasien = pr.no_pasien", "left");
        $this->db->join("pasien_igd pi", "pi.no_rm=p.no_pasien and pi.no_reg = pr.no_reg", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $this->db->where("pr.tujuan_poli", "0102030");
        $q = $this->db->get("pasien_ralan pr");
        return $q->row();
    }
    function getpasien_igdinap($no_reg)
    {
        $this->db->select("pi.*,pr.dokter,pr.alergi as riwayat_alergi, p.nama_pasien as nama_pasien, p.nama_pasien as nama_pasien1,pt.dokter_igd , pt.tanggal as tanggal_masuk, pt.jam as jam_masuk,p.tgl_lahir,p.jenis_kelamin,p.no_pasien,pr.prosedur_masuk");
        $this->db->join("pasien p", "p.no_pasien = pr.no_rm", "left");
        $this->db->join("pasien_igdinap pi", "pi.no_reg = pr.no_reg", "left");
        $this->db->join("pasien_triage pt", "pt.no_reg = pi.no_reg", "left");
        $this->db->where("pr.no_reg", $no_reg);
        $q = $this->db->get("pasien_inap pr");
        return $q->row();
    }
    function getpasien_igdranap($no_reg)
    {
        $this->db->select("pi.*,p.alergi as riwayat_alergi, p.nama_pasien as nama_pasien1");
        $this->db->join("pasien p", "p.no_pasien = pi.no_rm", "left");
        $this->db->where("pi.no_reg", $no_reg);
        $q = $this->db->get("pasien_igdinap pi");
        return $q->row();
    }
    function simpanigd($no_reg, $asal = "assesmen")
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_igd_vaksin");
        $pemeriksaan_fisik = "";
        $kelainan = "";
        $kodetarif = "";
        $tindakan_radiologi1 = "";
        $tindakan_lab1 = "";
        $penunjang1 = "";
        $koma = "";
        if ($q->num_rows() > 0) {
            $data = array(
                'tanggal_masuk' => date("Y-m-d", strtotime($this->input->post("tanggal_masuk"))),
                'jadwal_vaksin2' => date("Y-m-d", strtotime($this->input->post("jadwal_vaksin2"))),
                'jam_masuk' => date("H:i:s", strtotime($this->input->post("jam_masuk"))),
                'jam_periksa' => date("H:i:s", strtotime($this->input->post("jam_periksa"))),
                // 'jam_keluar_igd' => date("H:i:s"),
                'nyeri' => $this->input->post("nyeri"),
                'no_batch' => $this->input->post("no_batch"),
                'pengirim' => $this->input->post("pengirim"),
                'nama_pasien' => $this->input->post("nama_pasien"),
                'jenis_nyeri' => $this->input->post("jenis_nyeri"),
                'resiko_jatuh' => $this->input->post("resiko_jatuh"),
                'kedatangan' => $this->input->post("kedatangan"),
                'diantar' => $this->input->post("diantar"),
                'skrining_gizi' => $this->input->post("skrining_gizi"),
                'skrining_gizi2' => $this->input->post("skrining_gizi2"),
                'keluhan_utama' => $this->input->post("keluhan_utama"),
                'kronologis_kejadian' => $this->input->post("kronologis_kejadian"),
                'anamnesa' => $this->input->post("anamnesa"),
                'riwayat_penyakit' => $this->input->post("riwayat_penyakit"),
                'riwayat_alergi' => $this->input->post("riwayat_alergi"),
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
                'tindakan_radiologi' => $tindakan_radiologi1,
                'tindakan_lab' => $tindakan_lab1,
                'penunjang' => $penunjang1,
                'ruang' => $this->input->post("ruang"),
                'rujuk_ke' => $this->input->post("rujuk_ke"),
                'alasan_rujuk' => $this->input->post("alasan_rujuk"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
                'lokasi' => $this->input->post("lokasi"),
                'frekuensi' => $this->input->post("frekuensi"),
                'durasi' => $this->input->post("durasi"),
                'td' => $this->input->post("td"),
                'td2' => $this->input->post("td2"),
                'nadi' => $this->input->post("nadi"),
                'respirasi' => $this->input->post("respirasi"),
                'suhu' => $this->input->post("suhu"),
                'spo2' => $this->input->post("spo2"),
                'bb' => $this->input->post("bb"),
                'tb' => $this->input->post("tb"),
                'jam_meninggal' => $this->input->post("jam_meninggal"),
                'kesadaran' => $this->input->post("kesadaran"),
                'e' => $this->input->post("e"),
                'v' => $this->input->post("v"),
                'm' => $this->input->post("m"),
                'gcs' => $this->input->post("gcs"),
                'nama_vaksin' => $this->input->post("nama_vaksin"),
                'dosis_vaksin' => $this->input->post("dosis_vaksin"),
                'petugas_vaksin' => $this->input->post("petugas_vaksin"),
            );
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_igd_vaksin", $data);
        } else {
            $this->db->select("no_rm");
            $this->db->where("no_reg", $no_reg);
            $no_rm = $this->db->get("pasien_triage")->row()->no_rm;
            if ($no_rm == "") $no_rm = $this->input->post("no_rm");
            $data = array(
                'tanggal_masuk' => date("Y-m-d", strtotime($this->input->post("tanggal_masuk"))),
                'jadwal_vaksin2' => date("Y-m-d", strtotime($this->input->post("jadwal_vaksin2"))),
                'jam_masuk' => date("H:i:s", strtotime($this->input->post("jam_masuk"))),
                'jam_periksa' => date("H:i:s", strtotime($this->input->post("jam_periksa"))),
                'jam_keluar_igd' => date("H:i:s"),
                'no_reg' => $this->input->post("no_reg"),
                'no_rm' => $no_rm,
                'pengirim' => $this->input->post("pengirim"),
                'nama_pasien' => $this->input->post("nama_pasien"),
                'nyeri' => $this->input->post("nyeri"),
                'no_batch' => $this->input->post("no_batch"),
                'jenis_nyeri' => $this->input->post("jenis_nyeri"),
                'resiko_jatuh' => $this->input->post("resiko_jatuh"),
                'kedatangan' => $this->input->post("kedatangan"),
                'diantar' => $this->input->post("diantar"),
                'skrining_gizi' => $this->input->post("skrining_gizi"),
                'skrining_gizi2' => $this->input->post("skrining_gizi2"),
                'keluhan_utama' => $this->input->post("keluhan_utama"),
                'kronologis_kejadian' => $this->input->post("kronologis_kejadian"),
                'anamnesa' => $this->input->post("anamnesa"),
                'riwayat_penyakit' => $this->input->post("riwayat_penyakit"),
                'riwayat_alergi' => $this->input->post("riwayat_alergi"),
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
                'tindakan_radiologi' => $tindakan_radiologi1,
                'tindakan_lab' => $tindakan_lab1,
                'penunjang' => $penunjang1,
                'ruang' => $this->input->post("ruang"),
                'rujuk_ke' => $this->input->post("rujuk_ke"),
                'alasan_rujuk' => $this->input->post("alasan_rujuk"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
                'lokasi' => $this->input->post("lokasi"),
                'frekuensi' => $this->input->post("frekuensi"),
                'durasi' => $this->input->post("durasi"),
                'td' => $this->input->post("td"),
                'td2' => $this->input->post("td2"),
                'nadi' => $this->input->post("nadi"),
                'respirasi' => $this->input->post("respirasi"),
                'suhu' => $this->input->post("suhu"),
                'spo2' => $this->input->post("spo2"),
                'bb' => $this->input->post("bb"),
                'tb' => $this->input->post("tb"),
                'jam_meninggal' => $this->input->post("jam_meninggal"),
                'gcs' => $this->input->post("gcs"),
                'nama_vaksin' => $this->input->post("nama_vaksin"),
                'dosis_vaksin' => $this->input->post("dosis_vaksin"),
                'petugas_vaksin' => $this->input->post("petugas_vaksin"),
            );
            $this->db->insert("pasien_igd_vaksin", $data);
        }
        return "success-Data Berhasil tersimpan...";
    }
    function migrasi()
    {
        $data = array();
        $no_reg = $this->input->post("no_reg");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan");
        foreach ($q->result() as $value) {
            $data = array(
                "no_reg" => $value->no_reg,
                "no_rm"  => $value->no_pasien,
                "id_gol"  => $value->gol_pasien,
                "status_bayar"  => $value->status_bayar,
                "tgl_masuk" => date("Y-m-d", strtotime($value->tanggal)),
                "jam_masuk" => date("H:i:s", strtotime($value->tanggal))
            );
            $this->db->insert("pasien_inap", $data);
            $this->db->where("no_reg", $value->no_reg);
            $this->db->delete("pasien_ralan");
        }
        $data = array();
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("kasir");
        foreach ($q->result() as $value) {
            $data[] = array(
                "id" => $value->id,
                "no_reg" => $value->no_reg,
                "kode_tarif"  => $value->kode_tarif,
                "kode_petugas"  => $value->kode_petugas,
                "analys"  => $value->analys,
                "nofoto"  => $value->nofoto,
                "ukuranfoto"  => $value->ukuranfoto,
                "pemeriksaan"  => $value->pemeriksaan,
                "dokter_pengirim"  => $value->dokter_pengirim,
                "depo"  => $value->depo,
                "kode"  => $value->kode
            );
        }
        $this->db->insert_batch("kasir_inap", $data);
        $this->db->where("no_reg", $value->no_reg);
        $this->db->delete("kasir");
        $this->db->where("no_reg", $no_reg);
        $this->db->update("pasien_triage", ["tindak_lanjut" => "ranap"]);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_igd");
        foreach ($q->result() as $data) {
            $dt = array(
                'no_rm' => $data->no_rm,
                'no_reg' => $data->no_reg,
                'nama_pasien' => $data->nama_pasien,
                'tanggal_masuk' => $data->tanggal_masuk,
                'jam_masuk' => $data->jam_masuk,
                'jam_periksa' => $data->jam_periksa,
                'jam_keluar_igd' => $data->jam_keluar_igd,
                'nyeri' => $data->nyeri,
                'resiko_jatuh' => $data->resiko_jatuh,
                'skrining_gizi' => $data->skrining_gizi,
                'keluhan_utama' => $data->keluhan_utama,
                'kronologis_kejadian' => $data->kronologis_kejadian,
                'anamnesa' => $data->anamnesa,
                'riwayat_penyakit' => $data->riwayat_penyakit,
                'riwayat_alergi' => $data->riwayat_alergi,
                'obat_dikonsumsi' => $data->obat_dikonsumsi,
                'pemeriksaan_fisik' => $data->pemeriksaan_fisik,
                'kelainan' => $data->kelainan,
                'pemeriksaan_penunjang' => $data->pemeriksaan_penunjang,
                'diagnosis_kerja' => $data->diagnosis_kerja,
                'dd' => $data->dd,
                'terapi' => $data->terapi,
                'observasi' => $data->observasi,
                'waktu' => $data->waktu,
                'assesment' => $data->assesment,
                's' => $data->s,
                'o' => $data->o,
                'a' => $data->a,
                'p' => $data->p,
                'tindak_lanjut' => $data->tindak_lanjut,
                'ruang' => $data->ruang,
                'rujuk_ke' => $data->rujuk_ke,
                'alasan_rujuk' => $data->alasan_rujuk,
                'lokasi' => $data->lokasi,
                'frekuensi' => $data->frekuensi,
                'durasi' => $data->durasi,
                'kedatangan' => $data->kedatangan,
                'jenis_nyeri' => $data->jenis_nyeri,
                'diantar' => $data->diantar,
                'skrining_gizi2' => $data->skrining_gizi2,
                'tindakan_radiologi' => $data->tindakan_radiologi,
                'tindakan_lab' => $data->tindakan_lab,
                'penunjang' => $data->penunjang,
                'pengirim' => $data->pengirim,
                'td' => $data->td,
                'td2' => $data->td2,
                'nadi' => $data->nadi,
                'respirasi' => $data->respirasi,
                'suhu' => $data->suhu,
                'spo2' => $data->spo2,
                'bb' => $data->bb,
                'tb' => $data->tb,
                'jam_meninggal' => $data->jam_meninggal
            );
            $this->db->insert("pasien_igdinap", $dt);
        }
        $this->db->where("no_reg", $no_reg);
        $this->db->delete("pasien_igd");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("kasir");
        foreach ($q->result() as $value) {
            $d = array(
                "id" => $value->id,
                "no_reg" => $value->no_reg,
                "kode_tarif" => $value->kode_tarif,
                "jumlah" => $value->jumlah,
                "tanggal" => substr($value->no_reg, 0, 4) . "-" . substr($value->no_reg, 4, 2) . "-" . substr($value->no_reg, 6, 2)
            );
            $this->db->insert("kasir_inap", $d);
        }

        // Insert ke Ekspertisi Lab  Inap, dan Hapus dari Ekspertisi Lab
        $this->db->where("no_reg", $no_reg);
        $el_ralan = $this->db->get("ekspertisi_lab");
        foreach ($el_ralan as $e1) {
            $ed1 = array(
                "no_reg" => $e1->no_reg,
                "kode_tindakan" => $e1->kode_tindakan,
                "kode_labnormal" => $e1->kode_labnormal,
                "hasil" => $e1->hasil,
                "n1" => $e1->n1,
                "n2" => $e1->n2,
                "rp" => $e1->rp,
                "jam" => date("H:i:s")
            );
            $this->db->insert("ekspertisi_labinap", $d);
        }
        $this->db->where("no_reg", $no_reg);
        $this->db->delete("ekspertisi_lab");


        // Insert ke Ekspertisi Radiologi Inap, dan Hapus dari Ekspertisi Radiologi
        $this->db->where("no_reg", $no_reg);
        $er_ralan = $this->db->get("ekspertisi");
        foreach ($er_ralan as $e2) {
            $ed1 = array(
                "no_pasien" => $e2->no_pasien,
                "no_reg" => $e2->no_reg,
                "no_foto" => $e2->no_foto,
                "hasil_pemeriksaan" => $e2->hasil_pemeriksaan,
                "id_tindakan" => $e2->id_tindakan,
                "kesan" => $e2->kesan,
                "pemeriksaan" => $e2->pemeriksaan
            );
            $this->db->insert("ekspertisi", $d);
        }
        $this->db->where("no_reg", $no_reg);
        $this->db->delete("ekspertisi");
    }
    function migrasi_inap()
    {
        $data = array();
        $no_reg = $this->input->post("no_reg");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_inap");
        $row1 = $q->row();
        $this->db->set("status_kamar", "KOSONG");
        $this->db->where("kode_kamar", $row1->kode_kamar);
        $this->db->where("kode_ruangan", $row1->kode_ruangan);
        $this->db->where("kode_kelas", $row1->kode_kelas);
        $this->db->where("no_bed", $row1->no_bed);
        $this->db->update("kamar");
        foreach ($q->result() as $value) {
            $data = array(
                "no_reg" => $value->no_reg,
                "no_pasien"  => $value->no_rm,
                "gol_pasien"  => $value->id_gol,
                "status_bayar"  => $value->status_bayar,
                "tujuan_poli" => "0102030",
                "tanggal" => date("Y-m-d H:i:s", strtotime($value->tgl_masuk . " " . $value->jam_masuk))
            );
            $this->db->insert("pasien_ralan", $data);
            $this->db->where("no_reg", $value->no_reg);
            $this->db->delete("pasien_inap");
        }
        $data = array();
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("kasir_inap");
        foreach ($q->result() as $value) {
            $data[] = array(
                "id" => $value->id,
                "no_reg" => $value->no_reg,
                "kode_tarif"  => $value->kode_tarif,
                "kode_petugas"  => $value->kode_petugas,
                "analys"  => $value->analys,
                "nofoto"  => $value->nofoto,
                "ukuranfoto"  => $value->ukuranfoto,
                "pemeriksaan"  => $value->pemeriksaan,
                "dokter_pengirim"  => $value->dokter_pengirim,
                "depo"  => $value->depo,
                "kode"  => $value->kode
            );
        }
        $this->db->insert_batch("kasir", $data);
        $this->db->where("no_reg", $value->no_reg);
        $this->db->delete("kasir_inap");
        $this->db->where("no_reg", $no_reg);
        $this->db->update("pasien_triage", ["tindak_lanjut" => "ralan"]);
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_igdinap");
        foreach ($q->result() as $data) {
            $dt = array(
                'no_rm' => $data->no_rm,
                'no_reg' => $data->no_reg,
                'nama_pasien' => $data->nama_pasien,
                'tanggal_masuk' => $data->tanggal_masuk,
                'jam_masuk' => $data->jam_masuk,
                'jam_periksa' => $data->jam_periksa,
                'jam_keluar_igd' => $data->jam_keluar_igd,
                'nyeri' => $data->nyeri,
                'resiko_jatuh' => $data->resiko_jatuh,
                'skrining_gizi' => $data->skrining_gizi,
                'keluhan_utama' => $data->keluhan_utama,
                'kronologis_kejadian' => $data->kronologis_kejadian,
                'anamnesa' => $data->anamnesa,
                'riwayat_penyakit' => $data->riwayat_penyakit,
                'riwayat_alergi' => $data->riwayat_alergi,
                'obat_dikonsumsi' => $data->obat_dikonsumsi,
                'pemeriksaan_fisik' => $data->pemeriksaan_fisik,
                'kelainan' => $data->kelainan,
                'pemeriksaan_penunjang' => $data->pemeriksaan_penunjang,
                'diagnosis_kerja' => $data->diagnosis_kerja,
                'dd' => $data->dd,
                'terapi' => $data->terapi,
                'observasi' => $data->observasi,
                'waktu' => $data->waktu,
                'assesment' => $data->assesment,
                's' => $data->s,
                'o' => $data->o,
                'a' => $data->a,
                'p' => $data->p,
                'tindak_lanjut' => $data->tindak_lanjut,
                'ruang' => $data->ruang,
                'rujuk_ke' => $data->rujuk_ke,
                'alasan_rujuk' => $data->alasan_rujuk,
                'lokasi' => $data->lokasi,
                'frekuensi' => $data->frekuensi,
                'durasi' => $data->durasi,
                'kedatangan' => $data->kedatangan,
                'jenis_nyeri' => $data->jenis_nyeri,
                'diantar' => $data->diantar,
                'skrining_gizi2' => $data->skrining_gizi2,
                'tindakan_radiologi' => $data->tindakan_radiologi,
                'tindakan_lab' => $data->tindakan_lab,
                'penunjang' => $data->penunjang,
                'pengirim' => $data->pengirim,
                'td' => $data->td,
                'td2' => $data->td2,
                'nadi' => $data->nadi,
                'respirasi' => $data->respirasi,
                'suhu' => $data->suhu,
                'spo2' => $data->spo2,
                'bb' => $data->bb,
                'tb' => $data->tb,
                'jam_meninggal' => $data->jam_meninggal
            );
            $this->db->insert("pasien_igd", $dt);
        }
        $this->db->where("no_reg", $no_reg);
        $this->db->delete("pasien_igdinap");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("kasir_inap");
        foreach ($q->result() as $value) {
            $d = array(
                "id" => $value->id,
                "no_reg" => $value->no_reg,
                "kode_tarif" => $value->kode_tarif,
                "jumlah" => $value->jumlah,
                "tanggal" => substr($value->no_reg, 0, 4) . "-" . substr($value->no_reg, 4, 2) . "-" . substr($value->no_reg, 6, 2)
            );
            $this->db->insert("kasir", $d);
        }

        // Insert ke Ekspertisi Lab, dan Hapus dari Ekspertisi Lab Inap
        $this->db->where("no_reg", $no_reg);
        $el_ralan = $this->db->get("ekspertisi_labinap");
        foreach ($el_ralan as $e1) {
            $ed1 = array(
                "no_reg" => $e1->no_reg,
                "kode_tindakan" => $e1->kode_tindakan,
                "kode_labnormal" => $e1->kode_labnormal,
                "hasil" => $e1->hasil,
                "n1" => $e1->n1,
                "n2" => $e1->n2,
                "rp" => $e1->rp,
                "jam" => date("H:i:s")
            );
            $this->db->insert("ekspertisi_lab", $d);
        }
        $this->db->where("no_reg", $no_reg);
        $this->db->delete("ekspertisi_labinap");


        // Insert ke Ekspertisi Radiologi, dan Hapus dari Ekspertisi Radiologi Inap
        $this->db->where("no_reg", $no_reg);
        $er_ralan = $this->db->get("ekspertisi_radinap");
        foreach ($er_ralan as $e2) {
            $ed1 = array(
                "no_pasien" => $e2->no_pasien,
                "no_reg" => $e2->no_reg,
                "no_foto" => $e2->no_foto,
                "hasil_pemeriksaan" => $e2->hasil_pemeriksaan,
                "id_tindakan" => $e2->id_tindakan,
                "kesan" => $e2->kesan,
                "pemeriksaan" => $e2->pemeriksaan
            );
            $this->db->insert("ekspertisi", $d);
        }
        $this->db->where("no_reg", $no_reg);
        $this->db->delete("ekspertisi_radinap");
    }
    function simpanigdinap($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_igdinap");
        $pemeriksaan_fisik = "";
        $kelainan = "";
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $pemeriksaan_fisik .= $koma . ($this->input->post("pemeriksaan_fisik" . $i) != "" ? $this->input->post("pemeriksaan_fisik" . $i) : 0);
            $koma = ",";
        }
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $kelainan .= $koma . ($this->input->post("kelainan" . $i) != "" ? $this->input->post("kelainan" . $i) : 0);
            $koma = "|";
        }
        $tindak_rad = $this->input->post("tindakan_radiologi");
        $koma = $tindakan_radiologi1 = $kodetarif = $koma2 = "";
        if (is_array($tindak_rad)) {
            foreach ($tindak_rad as $key => $value) {
                $tindakan_radiologi1 .= $koma . ($value != "" ? $value : 0);
                $kodetarif .= $koma2 . ($value != "" ? "'" . $value . "'" : "");
                $koma = ",";
                $koma2 = ",";
            }
        }
        $tindak_lab = $this->input->post("tindakan_lab");
        $koma = $tindakan_lab1 = "";
        if (is_array($tindak_lab)) {
            foreach ($tindak_lab as $key => $value) {
                $tindakan_lab1 .= $koma . ($value != "" ? $value : 0);
                $kodetarif .= $koma2 . ($value != "" ? "'" . $value . "'" : "");
                $koma = ",";
                $koma2 = ",";
            }
        }
        $penunjang = $this->input->post("penunjang");
        $koma = $penunjang1 = "";
        if (is_array($penunjang)) {
            foreach ($penunjang as $key => $value) {
                $penunjang1 .= $koma . ($value != "" ? $value : 0);
                $kodetarif .= $koma2 . ($value != "" ? "'" . $value . "'" : "");
                $koma = ",";
                $koma2 = ",";
            }
        }
        if ($q->num_rows() > 0) {
            $data = array(
                'tanggal_masuk' => date("Y-m-d", strtotime($this->input->post("tanggal_masuk"))),
                'jam_masuk' => date("H:i:s", strtotime($this->input->post("jam_masuk"))),
                'jam_periksa' => date("H:i:s", strtotime($this->input->post("jam_periksa"))),
                // 'jam_keluar_igd' => date("H:i:s",strtotime($this->input->post("jam_keluar_igd"))),
                'nyeri' => $this->input->post("nyeri"),
                'pengirim' => $this->input->post("pengirim"),
                'nama_pasien' => $this->input->post("nama_pasien"),
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
                'riwayat_alergi' => $this->input->post("riwayat_alergi"),
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
                'tindakan_radiologi' => $tindakan_radiologi1,
                'tindakan_lab' => $tindakan_lab1,
                'penunjang' => $penunjang1,
                'ruang' => $this->input->post("ruang"),
                'rujuk_ke' => $this->input->post("rujuk_ke"),
                'alasan_rujuk' => $this->input->post("alasan_rujuk"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
                'lokasi' => $this->input->post("lokasi"),
                'frekuensi' => $this->input->post("frekuensi"),
                'durasi' => $this->input->post("durasi"),
                'td' => $this->input->post("td"),
                'td2' => $this->input->post("td2"),
                'nadi' => $this->input->post("nadi"),
                'respirasi' => $this->input->post("respirasi"),
                'suhu' => $this->input->post("suhu"),
                'spo2' => $this->input->post("spo2"),
                'bb' => $this->input->post("bb"),
                'tb' => $this->input->post("tb"),
                'jam_meninggal' => $this->input->post("jam_meninggal"),
                'kesadaran' => $this->input->post("kesadaran"),
                'e' => $this->input->post("e"),
                'v' => $this->input->post("v"),
                'm' => $this->input->post("m"),
                'gcs' => $this->input->post("gcs"),
            );
            $this->db->where("no_reg", $no_reg);
            $this->db->update("pasien_igdinap", $data);
        } else {
            $this->db->select("no_rm");
            $this->db->where("no_reg", $no_reg);
            $no_rm = $this->db->get("pasien_triage")->row()->no_rm;
            $data = array(
                'tanggal_masuk' => date("Y-m-d", strtotime($this->input->post("tanggal_masuk"))),
                'jam_masuk' => date("H:i:s", strtotime($this->input->post("jam_masuk"))),
                'jam_periksa' => date("H:i:s", strtotime($this->input->post("jam_periksa"))),
                'jam_keluar_igd' => date("H:i:s"),
                'no_reg' => $this->input->post("no_reg"),
                'no_rm' => $no_rm,
                'nyeri' => $this->input->post("nyeri"),
                'pengirim' => $this->input->post("pengirim"),
                'nama_pasien' => $this->input->post("nama_pasien"),
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
                'riwayat_alergi' => $this->input->post("riwayat_alergi"),
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
                'tindakan_radiologi' => $tindakan_radiologi1,
                'tindakan_lab' => $tindakan_lab1,
                'penunjang' => $penunjang1,
                'ruang' => $this->input->post("ruang"),
                'rujuk_ke' => $this->input->post("rujuk_ke"),
                'alasan_rujuk' => $this->input->post("alasan_rujuk"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
                'lokasi' => $this->input->post("lokasi"),
                'frekuensi' => $this->input->post("frekuensi"),
                'durasi' => $this->input->post("durasi"),
                'td' => $this->input->post("td"),
                'td2' => $this->input->post("td2"),
                'nadi' => $this->input->post("nadi"),
                'respirasi' => $this->input->post("respirasi"),
                'suhu' => $this->input->post("suhu"),
                'spo2' => $this->input->post("spo2"),
                'bb' => $this->input->post("bb"),
                'tb' => $this->input->post("tb"),
                'jam_meninggal' => $this->input->post("jam_meninggal"),
                'kesadaran' => $this->input->post("kesadaran"),
                'e' => $this->input->post("e"),
                'v' => $this->input->post("v"),
                'm' => $this->input->post("m"),
                'gcs' => $this->input->post("gcs"),
            );
            $this->db->insert("pasien_igdinap", $data);
        }
        $data = array(
            "alergi" => $this->input->post("riwayat_alergi"),
            'cara_masuk' => $this->input->post("kedatangan"),
            'pengirim' => $this->input->post("pengirim"),
            'dokter' => $this->input->post("dokter_igd"),
        );
        $this->db->where("no_reg", $no_reg);
        $this->db->update("pasien_inap", $data);
        $this->db->where("depo", "igd");
        $this->db->where("no_reg", $no_reg);
        if ($kodetarif != "") {
            $this->db->where("kode_tarif not in (" . $kodetarif . ")", NULL, FALSE);
        }
        $this->db->delete("kasir_inap");
        if ($tindakan_radiologi1 != "")
            $this->simpankonsulrad_inap($this->input->post("no_rm"), $this->input->post("no_reg"), $this->input->post("tanggal_masuk"), $this->input->post("jam_masuk"));
        if ($tindakan_lab1 != "")
            $this->simpankonsullab_inap($this->input->post("no_rm"), $this->input->post("no_reg"), $this->input->post("tanggal_masuk"), $this->input->post("jam_masuk"));
        $this->simpandokterkonsul_inap($this->input->post("no_rm"), $this->input->post("no_reg"), $this->input->post("tanggal_masuk"), $this->input->post("jam_masuk"));
        $this->simpanbidan_inap($this->input->post("no_rm"), $this->input->post("no_reg"), $this->input->post("tanggal_masuk"), $this->input->post("jam_masuk"));
        return "success-Data Berhasil tersimpan...";
    }
    function simpanbidan_inap($no_rm, $no_reg, $tgl, $jam)
    {
        $q = $this->db->get_where("pasien_triage", ["no_reg" => $no_reg])->row();
        if ($q->keputusan == 6 || $q->keputusan == 7) {
            $n = $this->db->get_where("assesmen_perawat", ["no_reg" => $no_reg, "id" => "bd"]);
            if ($n->num_rows() > 0) {
                $n = $n->row();
                $s = $n->s;
                $o = $n->o;
                $a = $n->a;
                $p = $n->p;
                $data = array(
                    "s" => $s,
                    "o" => $o,
                    "a" => $a,
                    "p" => $p
                );
                $this->db->where("no_reg", $no_reg);
                $this->db->where("id", "bd");
                $this->db->update("assesmen_perawat", $data);
            } else {
                $s = $this->input->post("s");
                $o = $this->input->post("o");
                $a = $this->input->post("a");
                $p = $this->input->post("p");
                $data = array(
                    "no_reg" => $no_reg,
                    "id" => "bd",
                    "tanggal" => date("Y-m-d", strtotime($tgl)),
                    "jam" => date("H:i:s", strtotime($jam)),
                    "jenis" => "ranap",
                    "s" => $s,
                    "o" => $o,
                    "a" => $a,
                    "p" => $p
                );
                $this->db->insert("assesmen_perawat", $data);
            }
        }
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
    function getfarmasiobat()
    {
        $this->db->like("kode", $this->input->post("kode"));
        $this->db->or_like("nama", $this->input->post("kode"));
        $q = $this->db->get("farmasi_data_obat");
        $data = array();
        foreach ($q->result() as $key => $value) {
            $data[] = array('id' => $value->kode, 'label' => $value->nama);
        }
        return $data;
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
        return "success-Data Berhasil tersimpan...";
    }

    function getpasien_inapdokter($page, $offset)
    {
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
        $this->db->join("pasien p", "p.no_pasien=i.no_rm");
        $this->db->join("oka o", "o.no_reg=i.no_reg", "left");
        // $this->db->join("indeks_inap_icd10 in","in.no_reg=i.no_reg","left");
        $this->db->join("gol_pasien g", "g.id_gol=p.id_gol", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=i.kode_ruangan", "left");
        $this->db->join("kelas k", "k.kode_kelas=i.kode_kelas", "left");
        $this->db->order_by("i.no_reg,i.no_rm", "desc");
        $query = $this->db->get("pasien_inap i", $page, $offset);
        return $query;
    }
    function getpasieninap_detail($no_reg)
    {
        $this->db->select("pi.*,d.nama_dokter,r.nama_ruangan,k.nama_kelas,p.nama_pasien,kmr.nama_kamar,p.tgl_lahir");
        $this->db->join("dokter d", "pi.dpjp=d.id_dokter", "left");
        $this->db->join("ruangan r", "r.kode_ruangan=pi.kode_ruangan");
        $this->db->join("kelas k", "k.kode_kelas=pi.kode_kelas");
        $this->db->join("kamar kmr", "kmr.kode_kamar=pi.kode_kamar");
        $this->db->join("pasien p", "p.no_pasien=pi.no_rm");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_inap pi");
        return $q->row();
    }
    function getpasienralan_detail($no_reg)
    {
        $this->db->select("pi.*,k.keterangan as poli,p.tgl_lahir,d.nama_dokter");
        $this->db->join("dokter d", "pi.dokter_poli=d.id_dokter", "left");
        $this->db->join("poliklinik k", "k.kode=pi.tujuan_poli", "left");
        $this->db->join("pasien p", "p.no_pasien=pi.no_pasien");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_ralan pi");
        return $q->row();
    }
    function getpasien_rawatinapdokter()
    {
        $kode_kelas = $this->session->userdata("kode_kelas");
        $kode_ruangan = $this->session->userdata("kode_ruangan");
        $tgl1 = $this->session->userdata("tgl1");
        $tgl2 = $this->session->userdata("tgl2");
        $no_pasien = $this->session->userdata("no_pasien");
        $no_reg = $this->session->userdata("no_reg");
        $nama = $this->session->userdata("nama");
        $this->db->select("i.*,p.nama_pasien,r.nama_ruangan,k.nama_kelas, g.keterangan as gol_pasien");
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
    function simpan_pasien($noreg)
    {
        if ($this->input->post("tindak_lanjut") == "ralan" || $this->input->post("tindak_lanjut") == "rujuk") {
            $data = array(
                "id_pasien" => date("YmdHis"),
                "no_pasien" => $noreg,
                "nama_pasien" => $this->input->post("nama_pasien"),
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
                "tanggal" => date("Y-m-d H:i:s"),
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
                    "id" => date("YmdHis"),
                    "no_reg" => $noreg,
                    "kode_tarif" => $data->kode_tindakan,
                    "jumlah" => $tarif,
                    "bayar" => 0
                );
                $this->db->insert("kasir", $dat);
            }
            return array("tanggal" => date("d-m-Y H:i:s", strtotime($noreg)), "jumlah_pasien" => $jumlah_pasien, "no_pasien" => $no_pasien, "nama_pasien" => $nama_pasien, "kode_dokter" => $this->input->post("dokter"), "nama_dokter" => $d->nama_dokter, "no_antrian" => $no_antrian, "no_reg" => date("YmdHis", strtotime($noreg)), "jenis" => $this->input->post("jenis"), "poli" => $p->keterangan);
        } else
        if ($this->input->post("tindak_lanjut") == "ranap") {
            $data = array(
                "id_pasien" => date("YmdHis"),
                "no_pasien" => $noreg,
                "nama_pasien" => $this->input->post("nama_pasien"),
                "id_gol" => $this->input->post("golpasien")
            );
            $this->db->insert("pasien", $data);
            $data = array(
                "no_reg" => $noreg,
                "no_rm" => $noreg,
                "prosedur_masuk" => "UGD",
                "tgl_masuk" => date("Y-m-d"),
                "jam_masuk" => date("H:i:s"),
            );
            $this->db->insert("pasien_inap", $data);
            // return "";
        }
        // return array("tanggal" => date("d-m-Y H:i:s",strtotime($noreg)),"jumlah_pasien" => $jumlah_pasien,"no_pasien" => $no_pasien,"nama_pasien" => $nama_pasien,"kode_dokter" => $this->input->post("dokter"), "nama_dokter" => $d->nama_dokter,"no_antrian" => $no_antrian, "no_reg"=> date("YmdHis",strtotime($noreg)), "jenis"=>$this->input->post("jenis"),"poli" => $p->keterangan);
    }
    function getcetaktriage($no_reg)
    {
        $this->db->select("pt.*, dt.nama_dokter as dokter_triage, di.nama_dokter as dokter_igd , k.nama as keputusan, dt.id_dokter as id_dokter_triage, p.id_perawat as id_dokter_igd, p.nama_perawat as petugas_igd,ps.tgl_lahir");
        $this->db->where("no_reg", $no_reg);
        $this->db->join("keputusan k", "k.kode = pt.keputusan", "left");
        $this->db->join("dokter dt", "dt.id_dokter = pt.dokter_triage", "left");
        $this->db->join("dokter di", "di.id_dokter = pt.dokter_igd", "left");
        $this->db->join("perawat p", "p.id_perawat = pt.petugas_igd", "left");
        $this->db->join("pasien ps", "ps.no_pasien = pt.no_rm", "left");
        return $this->db->get("pasien_triage pt")->row();
    }
    function getcetakigd($no_reg)
    {
        $this->db->select("pt.*, dt.nama_dokter as dokter_triage, di.nama_dokter as dokter_igd,k.kode as kode_keputusan, k.nama as keputusan, pi.tanggal_masuk, pi.jam_masuk, pi.jam_periksa, pi.jam_keluar_igd, pi.*, p.alergi, pt.tindak_lanjut, pt.rujuk_ke, pt.alasan_rujuk, di.id_dokter as id_dokter_igd, ap.nama_obat, ap.aturan_pakai, ap.qty, ap.satuan,p.tgl_lahir");
        $this->db->where("pt.no_reg", $no_reg);
        $this->db->join("keputusan k", "k.kode = pt.keputusan", "left");
        $this->db->join("dokter dt", "dt.id_dokter = pt.dokter_triage", "left");
        $this->db->join("dokter di", "di.id_dokter = pt.dokter_igd", "left");
        $this->db->join("pasien p", "p.no_pasien = pt.no_rm", "left");
        $this->db->join("pasien_igd pi", "pi.no_reg = pt.no_reg", "left");
        $this->db->join("apotek ap", "ap.no_reg = pt.no_reg", "left");
        return $this->db->get("pasien_triage pt")->row();
    }
    function getcetakigd_ralan($no_reg)
    {
        $this->db->select("pt.*, dt.nama_dokter as dokter_triage, dt.nama_dokter as dokter_igd, pi.tanggal_masuk, pi.jam_masuk, pi.jam_periksa, pi.jam_keluar_igd, pi.*, p.alergi, 'ralan' as tindak_lanjut, dt.id_dokter as id_dokter_igd, ap.nama_obat, ap.aturan_pakai, ap.qty, ap.satuan,p.tgl_lahir");
        $this->db->where("pt.no_reg", $no_reg);
        $this->db->join("dokter dt", "dt.id_dokter = pt.dokter_poli", "left");
        $this->db->join("pasien p", "p.no_pasien = pt.no_pasien", "left");
        $this->db->join("pasien_igd pi", "pi.no_reg = pt.no_reg and pi.no_rm=pt.no_pasien", "left");
        $this->db->join("apotek ap", "ap.no_reg = pt.no_reg", "left");
        return $this->db->get("pasien_ralan pt")->row();
    }
    function getcetakigd_inap($no_reg)
    {
        $this->db->select("pt.*, dt.nama_dokter as dokter_triage, di.nama_dokter as dokter_igd,k.kode as kode_keputusan, k.nama as keputusan, p.alergi,pi.*, di.id_dokter as id_dokter_igd, ap.nama_obat, ap.aturan_pakai, ap.qty, ap.satuan,p.tgl_lahir,pt.tindak_lanjut");
        $this->db->where("pt.no_reg", $no_reg);
        $this->db->join("keputusan k", "k.kode = pt.keputusan", "left");
        $this->db->join("dokter dt", "dt.id_dokter = pt.dokter_triage", "left");
        $this->db->join("dokter di", "di.id_dokter = pt.dokter_igd", "left");
        $this->db->join("pasien p", "p.no_pasien = pt.no_rm", "left");
        $this->db->join("pasien_igdinap pi", "pi.no_reg = pt.no_reg and pi.no_rm=pt.no_rm", "left");
        $this->db->join("apotek ap", "ap.no_reg = pt.no_reg", "left");
        return $this->db->get("pasien_triage pt")->row();
    }
    function simpankonsulrad($no_rm, $no_reg_sebelumnya, $tgl_masuk, $jam_masuk)
    {
        $no_reg     = date("YmdHis");
        $q = $this->db->get_where("pasien_ralan", ["no_reg_sebelumnya" => $no_reg_sebelumnya, "tujuan_poli" => "0102025"]);
        $no_antrian = $this->getnoantrian("0102030");
        if ($q->num_rows() <= 0) {
            $data       = array(
                "no_reg" => $no_reg,
                "tanggal" => date("Y-m-d H:i:s", strtotime($tgl_masuk . " " . $jam_masuk)),
                "no_antrian" => $no_antrian,
                "no_pasien" => $no_rm,
                "dokter_pengirim" => $this->input->post('dokter_igd'),
                "dari_poli" => "0102030",
                "no_reg_sebelumnya" => $no_reg_sebelumnya,
                "jenis" => "R",
                "tujuan_poli" => "0102025",
                "diagnosa" => $this->input->post("a")
            );
            $this->db->insert("pasien_ralan", $data);
        } else {
            $row = $q->row();
            $no_reg = $row->no_reg;
        }
        $tindakan = $this->input->post("tindakan_radiologi");
        $id = date("dmyHis");
        $this->db->where("depo", "igd");
        $this->db->where("no_reg", $no_reg);
        $this->db->like("kode_tarif", "R", "after");
        $this->db->delete("kasir");
        foreach ($tindakan as $key => $value) {
            $t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $value]);
            if ($t->num_rows() > 0) {
                $data = $t->row();
                $tarif = $data->reguler;
                $q = $this->db->get_where("kasir", ["no_reg" => $no_reg, "kode_tarif" => $value, "depo" => "igd"]);
                if ($q->num_rows() <= 0) {
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "depo" => "igd",
                        "dokter_pengirim" => $this->input->post('dokter_igd'),
                        "diagnosa" => $this->input->post("a"),
                        "bayar" => 0
                    );
                    $this->db->insert("kasir", $d);
                    $id++;
                }
            }
        }
        return "success-Data berhasil di input";
    }
    function simpankonsulrad_inap($no_rm, $no_reg, $tgl_masuk, $jam_masuk)
    {
        $tindakan = $this->input->post("tindakan_radiologi");
        $id = date("dmyHis");
        foreach ($tindakan as $key => $value) {
            $t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $value]);
            if ($t->num_rows() > 0) {
                $data = $t->row();
                $tarif = $data->reguler;
                $q = $this->db->get_where("kasir_inap", ["no_reg" => $no_reg, "kode_tarif" => $value, "depo" => "igd"]);
                if ($q->num_rows() <= 0) {
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "depo" => "igd",
                        "dokter_pengirim" => $this->input->post('dokter_igd'),
                        "diagnosa" => $this->input->post("a"),
                        "tanggal" => substr($no_reg, 0, 4) . "-" . substr($no_reg, 4, 2) . "-" . substr($no_reg, 6, 2)
                    );
                    $this->db->insert("kasir_inap", $d);
                    $id++;
                }
            }
        }
        return "success-Data berhasil di input";
    }
    function simpankonsullab($no_rm, $no_reg_sebelumnya, $tgl_masuk, $jam_masuk)
    {
        $no_reg     = date("YmdHis") + 1;
        $q = $this->db->get_where("pasien_ralan", ["no_reg_sebelumnya" => $no_reg_sebelumnya, "tujuan_poli" => "0102024"]);
        $no_antrian = $this->getnoantrian("0102024");
        if ($q->num_rows() <= 0) {
            $data       = array(
                "no_reg" => $no_reg,
                "tanggal" => date("Y-m-d H:i:s", strtotime($tgl_masuk . " " . $jam_masuk)),
                "no_antrian" => $no_antrian,
                "no_pasien" => $no_rm,
                "dokter_pengirim" => $this->input->post('dokter_igd'),
                "dari_poli" => "0102030",
                "no_reg_sebelumnya" => $no_reg_sebelumnya,
                "jenis" => "R",
                "diagnosa" => $this->input->post("a"),
                "tujuan_poli" => "0102024"
            );
            $this->db->insert("pasien_ralan", $data);
        } else {
            $row = $q->row();
            $no_reg = $row->no_reg;
        }
        $tindakanlab = $this->input->post("tindakan_lab");
        $id = date("dmyHis");
        $this->db->where("depo", "igd");
        $this->db->where("no_reg", $no_reg);
        $this->db->like("kode_tarif", "L", "after");
        $this->db->delete("kasir");
        foreach ($tindakanlab as $key => $value) {
            $t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $value]);
            if ($t->num_rows() > 0) {
                $data = $t->row();
                $tarif = $data->reguler;
                $q = $this->db->get_where("kasir", ["no_reg" => $no_reg, "kode_tarif" => $value, "depo" => "igd"]);
                if ($q->num_rows() <= 0) {
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "depo" => "igd",
                        "dokter_pengirim" => $this->input->post('dokter_igd'),
                        "diagnosa" => $this->input->post("a"),
                        "bayar" => 0
                    );
                    $this->db->insert("kasir", $d);
                    $id++;
                }
            }
        }
        return "success-Data berhasil di input";
    }
    function simpankonsullab_inap($no_rm, $no_reg, $tgl_masuk, $jam_masuk)
    {
        $tindakan = $this->input->post("tindakan_lab");
        $id = date("dmyHis");
        foreach ($tindakan as $key => $value) {
            $t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $value]);
            if ($t->num_rows() > 0) {
                $data = $t->row();
                $tarif = $data->reguler;
                $q = $this->db->get_where("kasir_inap", ["no_reg" => $no_reg, "kode_tarif" => $value, "depo" => "igd"]);
                if ($q->num_rows() <= 0) {
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "depo" => "igd",
                        "dokter_pengirim" => $this->input->post('dokter_igd'),
                        "diagnosa" => $this->input->post("a"),
                        "tanggal" => substr($no_reg, 0, 4) . "-" . substr($no_reg, 4, 2) . "-" . substr($no_reg, 6, 2)
                    );
                    $this->db->insert("kasir_inap", $d);
                    $id++;
                }
            }
        }
        return "success-Data berhasil di input";
    }
    function simpandokterkonsul_inap($no_rm, $no_reg, $tgl_masuk, $jam_masuk)
    {
        $pemeriksaan_fisik = "";
        $kelainan = "";
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $pemeriksaan_fisik .= $koma . ($this->input->post("pemeriksaan_fisik" . $i) != "" ? $this->input->post("pemeriksaan_fisik" . $i) : 0);
            $koma = ",";
        }
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $kelainan .= $koma . ($this->input->post("kelainan" . $i) != "" ? $this->input->post("kelainan" . $i) : 0);
            $koma = "|";
        }
        $idbaru = date("YmdHis");
        for ($n = 1; $n <= 3; $n++) {
            if ($this->input->post("via" . $n) == "Whatsapp") {
                $dijawab[$n] = $this->input->post("dijawab" . $n);
            } else {
                $dijawab[$n] = 1;
            }
            if ($this->input->post("dokter" . $n . "_lama") != "") {
                $this->db->where("no_reg", $no_reg);
                $this->db->group_start();
                $this->db->where("id_terkait", "");
                $this->db->or_where("id_terkait is null", null, false);
                $this->db->group_end();
                $this->db->where("dokter_konsul", $this->input->post("dokter" . $n . "_lama"));
                $q = $this->db->get("riwayat_pasien_inap");
                if ($q->num_rows() > 0) {
                    if ($this->input->post("dokter" . $n) == "") {
                        $this->db->where("no_reg", $no_reg);
                        $this->db->where("dokter_konsul", $this->input->post("dokter" . $n . "_lama"));
                        $this->db->delete("riwayat_pasien_inap");
                    } else {
                        $id = $q->row()->id;
                        $data = array(
                            'dokter_visit' => $this->input->post("dokter_igd"),
                            'dokter_konsul' => $this->input->post("dokter" . $n),
                            'dijawab' => $dijawab[$n],
                            'via' => $this->input->post("via" . $n),
                            's' => $this->input->post("s"),
                            'o' => $this->input->post("o"),
                            'a' => $this->input->post("a"),
                            'p' => $this->input->post("p"),
                            'pemeriksaan_fisik' => $pemeriksaan_fisik,
                            'kelainan' => $kelainan,
                            'td' => $this->input->post("td"),
                            'td2' => $this->input->post("td2"),
                            'nadi' => $this->input->post("nadi"),
                            'respirasi' => $this->input->post("respirasi"),
                            'suhu' => $this->input->post("suhu"),
                            'spo2' => $this->input->post("spo2"),
                            'bb' => $this->input->post("bb"),
                            'tb' => $this->input->post("tb")
                        );
                        $this->db->where("id", $id);
                        $this->db->update("riwayat_pasien_inap", $data);
                    }
                } else {
                    $data = array(
                        'id' => $idbaru,
                        'no_reg' => $no_reg,
                        'pemeriksaan' => 'konsul',
                        'tanggal' => date("Y-m-d", strtotime($tgl_masuk)),
                        'jam' => $jam_masuk,
                        'dokter_visit' => $this->input->post("dokter_igd"),
                        'dokter_konsul' => $this->input->post("dokter" . $n),
                        'dijawab' => $this->input->post("dijawab" . $n),
                        'via' => $via[$n],
                        's' => $this->input->post("s"),
                        'o' => $this->input->post("o"),
                        'a' => $this->input->post("a"),
                        'p' => $this->input->post("p"),
                        'pemeriksaan_fisik' => $pemeriksaan_fisik,
                        'kelainan' => $kelainan,
                        'td' => $this->input->post("td"),
                        'td2' => $this->input->post("td2"),
                        'nadi' => $this->input->post("nadi"),
                        'respirasi' => $this->input->post("respirasi"),
                        'suhu' => $this->input->post("suhu"),
                        'spo2' => $this->input->post("spo2"),
                        'bb' => $this->input->post("bb"),
                        'tb' => $this->input->post("tb")
                    );
                    $this->db->insert("riwayat_pasien_inap", $data);
                    $idbaru++;
                }
            } else {
                if ($this->input->post("dokter" . $n) != "") {
                    $data = array(
                        'id' => $idbaru,
                        'no_reg' => $no_reg,
                        'pemeriksaan' => 'konsul',
                        'tanggal' => date("Y-m-d", strtotime($tgl_masuk)),
                        'jam' => $jam_masuk,
                        'dokter_konsul' => $this->input->post("dokter" . $n),
                        's' => $this->input->post("s"),
                        'o' => $this->input->post("o"),
                        'a' => $this->input->post("a"),
                        'p' => $this->input->post("p"),
                        'pemeriksaan_fisik' => $pemeriksaan_fisik,
                        'kelainan' => $kelainan,
                        'td' => $this->input->post("td"),
                        'td2' => $this->input->post("td2"),
                        'nadi' => $this->input->post("nadi"),
                        'respirasi' => $this->input->post("respirasi"),
                        'suhu' => $this->input->post("suhu"),
                        'spo2' => $this->input->post("spo2"),
                        'bb' => $this->input->post("bb"),
                        'tb' => $this->input->post("tb")
                    );
                    $this->db->insert("riwayat_pasien_inap", $data);
                }
                $idbaru++;
            }
        }
        return "success-Data berhasil di input";
    }
    function getnoantrian($poli_tujuan)
    {
        for ($i = 1; $i <= 999; $i++) {
            $n = substr("000" . $i, -3);
            $where = array(
                "dokter_poli" => $this->input->post("dokter_igd"),
                "jenis" => "R",
                "tujuan_poli" => $poli_tujuan,
                "date(tanggal)" => date("Y-m-d"),
                "no_antrian" => $n
            );
            $q = $this->db->get_where("pasien_ralan", $where);
            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function getkasir($noreg)
    {
        $n = $this->db->get_where("pasien_ralan", ["no_reg_sebelumnya" => $noreg, "tujuan_poli" => "0102025"]);
        if ($n->num_rows() > 0) {
            $row = $n->row();
            $no_reg = $row->no_reg;
        }
        $this->db->select("k.kode_tarif,t.nama_tindakan");
        $this->db->where("k.no_reg", $no_reg);
        $this->db->like("k.kode_tarif", "R", "after");
        $this->db->join("tarif_radiologi t", "t.id_tindakan=k.kode_tarif", "inner");
        $q = $this->db->get("kasir k");
        $data = array();
        foreach ($q->result() as $key) {
            $data["rad"][$key->kode_tarif] = $key->nama_tindakan;
        }
        $n = $this->db->get_where("pasien_ralan", ["no_reg_sebelumnya" => $noreg, "tujuan_poli" => "0102024"]);
        if ($n->num_rows() > 0) {
            $row = $n->row();
            $no_reg = $row->no_reg;
        }
        $this->db->select("k.kode_tarif,t.nama_tindakan");
        $this->db->where("k.no_reg", $no_reg);
        $this->db->like("k.kode_tarif", "L", "after");
        $this->db->join("tarif_lab t", "t.kode_tindakan=k.kode_tarif", "inner");
        $q = $this->db->get("kasir k");
        foreach ($q->result() as $key) {
            $data["lab"][$key->kode_tarif] = $key->nama_tindakan;
        }
        $this->db->select("p.penunjang");
        $this->db->where("p.no_reg", $noreg);
        $q = $this->db->get("pasien_igd p");
        if ($q->num_rows() > 0) {
            $lain = "'" . str_replace(",", "','", $q->row()->penunjang) . "'";
        } else {
            $lain = "''";
        }
        $this->db->where("kode in (" . $lain . ")");
        $l = $this->db->get("tarif_penunjang_medis");
        foreach ($l->result() as $key) {
            $data["lain"][$key->kode] = $key->ket;
        }
        return $data;
    }
    function getkasir_inap($no_reg)
    {
        $this->db->select("k.kode_tarif,t.nama_tindakan");
        $this->db->where("k.no_reg", $no_reg);
        $this->db->like("k.kode_tarif", "R", "after");
        $this->db->join("tarif_radiologi t", "t.id_tindakan=k.kode_tarif", "inner");
        $q = $this->db->get("kasir_inap k");
        $data = array();
        foreach ($q->result() as $key) {
            $data["rad"][$key->kode_tarif] = $key->nama_tindakan;
        }
        $this->db->select("k.kode_tarif,t.nama_tindakan");
        $this->db->where("k.no_reg", $no_reg);
        $this->db->like("k.kode_tarif", "L", "after");
        $this->db->join("tarif_lab t", "t.kode_tindakan=k.kode_tarif", "inner");
        $q = $this->db->get("kasir_inap k");
        foreach ($q->result() as $key) {
            $data["lab"][$key->kode_tarif] = $key->nama_tindakan;
        }
        $this->db->select("p.penunjang");
        $this->db->where("p.no_reg", $no_reg);
        $q = $this->db->get("pasien_igdinap p");
        if ($q->num_rows() > 0) {
            $lain = "'" . str_replace(",", "','", $q->row()->penunjang) . "'";
        } else {
            $lain = "''";
        }
        $this->db->where("kode in (" . $lain . ")");
        $l = $this->db->get("tarif_penunjang_medis");
        foreach ($l->result() as $key) {
            $data["lain"][$key->kode] = $key->ket;
        }
        return $data;
    }
    function addobat()
    {
        $obat = $this->input->post("obat");
        $id = date("dmyHis");
        $this->db->select("nama,pak2,hrg_jual");
        $t = $this->db->get_where("farmasi_data_obat", ["kode" => $obat]);
        if ($t->num_rows() > 0) {
            $q = $t->row();
            $data = array(
                "id" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "kode_obat" => $obat,
                "nama_obat" => $q->nama,
                "qty" => 1,
                "satuan" => $q->pak2,
                "jumlah" => $q->hrg_jual,
                "depo"    => "igd",
            );
            $this->db->insert("apotek", $data);
        }
    }
    function addobat_inap()
    {
        $obat = $this->input->post("obat");
        $asal = $this->input->post("asal");
        if ($asal == "assesmen") $depo = "igd";
        else $depo = "ranap";
        $id = date("dmyHis");
        $this->db->select("nama,pak2,hrg_jual");
        $t = $this->db->get_where("farmasi_data_obat", ["kode" => $obat]);
        if ($t->num_rows() > 0) {
            $q = $t->row();
            $data = array(
                "id" => $id,
                "tanggal" => date("Y-m-d", strtotime($this->input->post("tanggal"))),
                "no_reg" => $this->input->post("no_reg"),
                "kode_obat" => $obat,
                "nama_obat" => $q->nama,
                "qty" => 1,
                "satuan" => $q->pak2,
                "jumlah" => $q->hrg_jual,
                "dokter" => $this->input->post("iddokter"),
                "depo" => $depo
            );
            $this->db->insert("apotek_inap", $data);
        }
    }
    function getpasienruangan($no_reg)
    {
        $this->db->select("nama_ruangan,nama_kelas,nama_kamar,km.klasifikasi,pi.no_bed");
        $this->db->join("ruangan r", "r.kode_ruangan=pi.kode_ruangan");
        $this->db->join("kelas k", "k.kode_kelas=pi.kode_kelas");
        $this->db->join("kamar km", "km.kode_kamar=pi.kode_kamar and km.kode_ruangan=pi.kode_ruangan and km.kode_kelas=pi.kode_kelas");
        $this->db->where("pi.no_reg", $no_reg);
        $q = $this->db->get("pasien_inap pi");
        return $q->row();
    }
    function getkonsul_inap($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("riwayat_pasien_inap pi");
        return $q;
    }
    function getkonsul_inap2($no_reg)
    {
        $this->db->where("id_terkait is null");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("riwayat_pasien_inap");
        return $q;
    }
    function getdoktervisit_inap($id_terkait)
    {
        $this->db->select("r.*,d.nama_dokter");
        $this->db->where("id", $id_terkait);
        $this->db->join("dokter d", "d.id_dokter=r.dokter_visit", "left");
        $q = $this->db->get("riwayat_pasien_inap r");
        return $q;
    }
    function getdokterkonsul_inap($no_reg)
    {
        $this->db->select("r.*,d.nama_dokter");
        $this->db->where("no_reg", $no_reg);
        $this->db->join("dokter d", "d.id_dokter=r.dokter_konsul", "left");
        // $this->db->group_start();
        // $this->db->where("id_terkait");
        // $this->db->or_where("id_terkait","");
        // $this->db->group_end();
        $q = $this->db->get("riwayat_pasien_inap r");
        return $q;
    }
    function getdokterkonsultambahan_inap($no_reg, $id_terkait)
    {
        $this->db->select("r.*,d.nama_dokter");
        $this->db->where("no_reg", $no_reg);
        $this->db->join("dokter d", "d.id_dokter=r.dokter_konsul", "left");
        $this->db->where("id_terkait", $id_terkait);
        $q = $this->db->get("riwayat_pasien_inap r");
        return $q;
    }
    function simpantambahkonsul_inap()
    {
        $pemeriksaan_fisik = "";
        $kelainan = "";
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $pemeriksaan_fisik .= $koma . ($this->input->post("pemeriksaan_fisik" . $i . "_tambah") != "" ? $this->input->post("pemeriksaan_fisik" . $i . "_tambah") : 0);
            $koma = ",";
        }
        $koma = "";
        for ($n = 1; $n <= 15; $n++) {
            $kelainan .= $koma . ($this->input->post("kelainan" . $n . "_tambah") != "" ? $this->input->post("kelainan" . $n . "_tambah") : 0);
            $koma = "|";
        }
        if ($this->input->post("id_lama") == "") {
            $data = array(
                'id' => date("YmdHis"),
                'no_reg' => $this->input->post("no_reg_tambah"),
                'pemeriksaan' => 'konsul',
                'tanggal' => date("Y-m-d"),
                'jam' => date("H:i:s"),
                'dokter_visit' => $this->input->post("doktersp_tambah"),
                'dokter_konsul' => $this->input->post("id_dokter_tambah"),
                's' => $this->input->post("s_tambah"),
                'o' => $this->input->post("o_tambah"),
                'a' => $this->input->post("a_tambah"),
                'p' => $this->input->post("p_tambah"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
                'td' => $this->input->post("td_tambah"),
                'td2' => $this->input->post("td2_tambah"),
                'nadi' => $this->input->post("nadi_tambah"),
                'respirasi' => $this->input->post("respirasi_tambah"),
                'suhu' => $this->input->post("suhu_tambah"),
                'spo2' => $this->input->post("spo2_tambah"),
                'bb' => $this->input->post("bb_tambah"),
                'tb' => $this->input->post("tb_tambah"),
                'id_terkait' => $this->input->post("id_terkait")
            );
            $this->db->insert("riwayat_pasien_inap", $data);
        } else {
            $data = array(
                'dokter_konsul' => $this->input->post("id_dokter_tambah"),
                's' => $this->input->post("s_tambah"),
                'o' => $this->input->post("o_tambah"),
                'a' => $this->input->post("a_tambah"),
                'p' => $this->input->post("p_tambah"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
                'td' => $this->input->post("td_tambah"),
                'td2' => $this->input->post("td2_tambah"),
                'nadi' => $this->input->post("nadi_tambah"),
                'respirasi' => $this->input->post("respirasi_tambah"),
                'suhu' => $this->input->post("suhu_tambah"),
                'spo2' => $this->input->post("spo2_tambah"),
                'bb' => $this->input->post("bb_tambah"),
                'tb' => $this->input->post("tb_tambah")
            );
            $this->db->where("id", $this->input->post("id_lama"));
            $this->db->update("riwayat_pasien_inap", $data);
        }
    }
    function simpantambahvisit_inap()
    {
        $pemeriksaan_fisik = "";
        $kelainan = "";
        $koma = "";
        for ($i = 1; $i <= 15; $i++) {
            $pemeriksaan_fisik .= $koma . ($this->input->post("pemeriksaan_fisik" . $i . "_tambah") != "" ? $this->input->post("pemeriksaan_fisik" . $i . "_tambah") : 0);
            $koma = ",";
        }
        $koma = "";
        for ($n = 1; $n <= 15; $n++) {
            $kelainan .= $koma . ($this->input->post("kelainan" . $n . "_tambah") != "" ? $this->input->post("kelainan" . $n . "_tambah") : 0);
            $koma = "|";
        }
        $tindak_rad = $this->input->post("tindakan_radiologi");
        $koma = $tindakan_radiologi1 = "";
        if (is_array($tindak_rad)) {
            foreach ($tindak_rad as $key => $value) {
                $tindakan_radiologi1 .= $koma . ($value != "" ? $value : 0);
                $koma = ",";
            }
        }
        $tindak_lab = $this->input->post("tindakan_lab");
        $koma = $tindakan_lab1 = "";
        if (is_array($tindak_lab)) {
            foreach ($tindak_lab as $key => $value) {
                $tindakan_lab1 .= $koma . ($value != "" ? $value : 0);
                $koma = ",";
            }
        }
        $penunjang = $this->input->post("penunjang");
        $koma = $penunjang1 = "";
        if (is_array($penunjang)) {
            foreach ($penunjang as $key => $value) {
                $penunjang1 .= $koma . ($value != "" ? $value : 0);
                $koma = ",";
            }
        }
        if ($tindakan_radiologi1 != "")
            $this->simpantambahkonsulrad_inap($this->input->post("no_reg_tambah"));
        if ($tindakan_lab1 != "")
            $this->simpantambahkonsullab_inap($this->input->post("no_reg_tambah"));
        if ($this->input->post("id_lama") == "") {
            $data = array(
                'id' => date("YmdHis"),
                'no_reg' => $this->input->post("no_reg_tambah"),
                'pemeriksaan' => 'visit',
                'tanggal' => date("Y-m-d", strtotime($this->input->post("tanggal_tambah"))),
                'jam' => date("H:i:s"),
                'dokter_visit' => $this->input->post("doktersp_tambah"),
                's' => $this->input->post("s_tambah"),
                'o' => $this->input->post("o_tambah"),
                'a' => $this->input->post("a_tambah"),
                'p' => $this->input->post("p_tambah"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
                'td' => $this->input->post("td_tambah"),
                'td2' => $this->input->post("td2_tambah"),
                'nadi' => $this->input->post("nadi_tambah"),
                'respirasi' => $this->input->post("respirasi_tambah"),
                'suhu' => $this->input->post("suhu_tambah"),
                'spo2' => $this->input->post("spo2_tambah"),
                'bb' => $this->input->post("bb_tambah"),
                'tb' => $this->input->post("tb_tambah"),
                'tindakan_radiologi' => $tindakan_radiologi1,
                'tindakan_lab' => $tindakan_lab1,
                'tindakan_penunjang' => $penunjang1,
                'id_terkait' => $this->input->post("id_terkait")
            );
            $this->db->insert("riwayat_pasien_inap", $data);
        } else {
            $data = array(
                's' => $this->input->post("s_tambah"),
                'o' => $this->input->post("o_tambah"),
                'a' => $this->input->post("a_tambah"),
                'p' => $this->input->post("p_tambah"),
                'pemeriksaan_fisik' => $pemeriksaan_fisik,
                'kelainan' => $kelainan,
                'td' => $this->input->post("td_tambah"),
                'td2' => $this->input->post("td2_tambah"),
                'nadi' => $this->input->post("nadi_tambah"),
                'respirasi' => $this->input->post("respirasi_tambah"),
                'suhu' => $this->input->post("suhu_tambah"),
                'spo2' => $this->input->post("spo2_tambah"),
                'bb' => $this->input->post("bb_tambah"),
                'tb' => $this->input->post("tb_tambah")
            );
            $this->db->where("id", $this->input->post("id_lama"));
            $this->db->update("riwayat_pasien_inap", $data);
        }
    }
    function simpanjawabkonsul_inap()
    {
        $tindak_rad = $this->input->post("tindakan_radiologi");
        $koma = $tindakan_radiologi1 = "";
        if (is_array($tindak_rad)) {
            foreach ($tindak_rad as $key => $value) {
                $this->db->where("dokter_konsul!=", $this->input->post("doktersp_tambah"));
                $this->db->where("no_reg", $this->input->post("no_reg_tambah"));
                $this->db->like("tindakan_radiologi", $value);
                $q = $this->db->get("riwayat_pasien_inap");
                if ($q->num_rows() <= 0) {
                    $tindakan_radiologi1 .= $koma . ($value != "" ? $value : 0);
                    $koma = ",";
                }
            }
        }
        $tindak_lab = $this->input->post("tindakan_lab");
        $koma = $tindakan_lab1 = "";
        if (is_array($tindak_lab)) {
            foreach ($tindak_lab as $key => $value) {
                $this->db->where("dokter_konsul!=", $this->input->post("doktersp_tambah"));
                $this->db->where("no_reg", $this->input->post("no_reg_tambah"));
                $this->db->like("tindakan_lab", $value);
                $q = $this->db->get("riwayat_pasien_inap");
                if ($q->num_rows() <= 0) {
                    $tindakan_lab1 .= $koma . ($value != "" ? $value : 0);
                    $koma = ",";
                }
            }
        }
        $penunjang = $this->input->post("penunjang");
        $koma = $penunjang1 = "";
        if (is_array($penunjang)) {
            foreach ($penunjang as $key => $value) {
                $this->db->where("dokter_konsul!=", $this->input->post("doktersp_tambah"));
                $this->db->where("no_reg", $this->input->post("no_reg_tambah"));
                $this->db->like("tindakan_penunjang", $value);
                $q = $this->db->get("riwayat_pasien_inap");
                if ($q->num_rows() <= 0) {
                    $penunjang1 .= $koma . ($value != "" ? $value : 0);
                    $koma = ",";
                }
            }
        }
        $data = array(
            'a' => $this->input->post("a_jawab"),
            'p' => $this->input->post("p_jawab"),
            'tindakan_radiologi' => $tindakan_radiologi1,
            'tindakan_lab' => $tindakan_lab1,
            'tindakan_penunjang' => $penunjang1,
            'tgl_jawab' => date("Y-m-d"),
            'jam_jawab' => date("H:i:s"),
        );
        $this->db->where("id", $this->input->post("id_terkait"));
        $this->db->update("riwayat_pasien_inap", $data);
        $this->db->where("no_reg", $this->input->post("no_reg_tambah"));
        $this->db->like("kode_tarif", "R", "after");
        $this->db->where("dokter_pengirim", $this->input->post("doktersp_tambah"));
        $this->db->delete("kasir_inap");
        $this->db->where("no_reg", $this->input->post("no_reg_tambah"));
        $this->db->like("kode_tarif", "L", "after");
        $this->db->where("dokter_pengirim", $this->input->post("doktersp_tambah"));
        $this->db->delete("kasir_inap");
        if ($tindakan_radiologi1 != "")
            $this->simpantambahkonsulrad_inap($this->input->post("no_reg_tambah"));
        if ($tindakan_lab != "")
            $this->simpantambahkonsullab_inap($this->input->post("no_reg_tambah"));
    }
    function simpantambahkonsulrad_inap($no_reg)
    {
        $tindakan = $this->input->post("tindakan_radiologi");
        $id = date("dmyHis");
        foreach ($tindakan as $key => $value) {
            $this->db->where("dokter_konsul!=", $this->input->post("doktersp_tambah"));
            $this->db->where("no_reg", $no_reg);
            $this->db->like("tindakan_radiologi", $value);
            $q = $this->db->get("riwayat_pasien_inap");
            if ($q->num_rows() <= 0) {
                $t = $this->db->get_where("tarif_radiologi", ["id_tindakan" => $value]);
                if ($t->num_rows() > 0) {
                    $data = $t->row();
                    $tarif = $data->reguler;
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "dokter_pengirim" => $this->input->post("doktersp_tambah"),
                        "tanggal" => substr($no_reg, 0, 4) . "-" . substr($no_reg, 4, 2) . "-" . substr($no_reg, 6, 2)
                    );
                    $this->db->insert("kasir_inap", $d);
                    $id++;
                }
            }
        }
        return "success-Data berhasil di input";
    }
    function simpantambahkonsullab_inap($no_reg)
    {
        $tindakan = $this->input->post("tindakan_lab");
        $id = date("dmyHis");
        foreach ($tindakan as $key => $value) {
            $this->db->where("dokter_konsul!=", $this->input->post("doktersp_tambah"));
            $this->db->where("no_reg", $no_reg);
            $this->db->like("tindakan_lab", $value);
            $q = $this->db->get("riwayat_pasien_inap");
            if ($q->num_rows() <= 0) {
                $t = $this->db->get_where("tarif_lab", ["kode_tindakan" => $value]);
                if ($t->num_rows() > 0) {
                    $data = $t->row();
                    $tarif = $data->reguler;
                    $d = array(
                        "id" => $id,
                        "no_reg" => $no_reg,
                        "kode_tarif" => $value,
                        "jumlah" => $tarif,
                        "dokter_pengirim" => $this->input->post("doktersp_tambah"),
                        "tanggal" => substr($no_reg, 0, 4) . "-" . substr($no_reg, 4, 2) . "-" . substr($no_reg, 6, 2)
                    );
                    $this->db->insert("kasir_inap", $d);
                    $id++;
                }
            }
        }
        return "success-Data berhasil di input";
    }
    function getapotek_inap($no_reg, $iddokter, $tgl = "")
    {
        $this->db->select("apotek_inap.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
        $this->db->where("dokter", $iddokter);
        if ($tgl != "")
            $this->db->where("tanggal", date("Y-m-d", strtotime($tgl)));
        $this->db->join("waktu w", "w.kode = apotek_inap.waktu", "left");
        $this->db->join("waktu_lainnya wl", "wl.kode = apotek_inap.waktu_lainnya", "left");
        $this->db->join("aturan_pakai a", "a.kode = apotek_inap.aturan_pakai", "left");
        $this->db->order_by("tanggal,nama_obat");
        $q = $this->db->get_where("apotek_inap", ["no_reg" => $no_reg]);
        return $q;
    }
    function getterapiarray($no_reg)
    {
        $this->db->select("apotek_inap.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
        $this->db->join("waktu w", "w.kode = apotek_inap.waktu", "left");
        $this->db->join("waktu_lainnya wl", "wl.kode = apotek_inap.waktu_lainnya", "left");
        $this->db->join("aturan_pakai a", "a.kode = apotek_inap.aturan_pakai", "left");
        $this->db->order_by("tanggal,nama_obat");
        $q = $this->db->get_where("apotek_inap", ["no_reg" => $no_reg]);
        $data = array();
        foreach ($q->result() as $key) {
            $data[$key->dokter][$key->tanggal][] = $key;
        }
        return $data;
    }
    function cetak_konsul($id)
    {
        $this->db->select("r.*,p.no_rm,p.nama_pasien,ps.tgl_lahir,d.nama_dokter");
        $this->db->join("pasien_igdinap p", "p.no_reg=r.no_reg", "inner");
        $this->db->join("pasien ps", "ps.no_pasien=p.no_rm", "inner");
        $this->db->join("dokter d", "d.id_dokter=r.dokter_konsul", "left");
        $q = $this->db->get_where("riwayat_pasien_inap r", ["r.id" => $id]);
        $data = array();
        $row = $q->row();
        $data["data"] = $row;
        if ($row->id_terkait == "") {
            $this->db->select("p.*,t.tanggal,t.jam,t.no_reg,t.dokter_igd as id_dokter,d.nama_dokter");
            $this->db->join("dokter d", "d.id_dokter=t.dokter_igd", "left");
            $this->db->join("pasien_igdinap p", "p.no_reg=t.no_reg", "inner");
            $n = $this->db->get_where("pasien_triage t", ["t.no_reg" => $row->no_reg]);
            $data["dari"] = $n->row();
        } else {
            $this->db->select("t.tanggal,t.jam,t.dokter_konsul as id_dokter,d.nama_dokter");
            $this->db->join("dokter d", "d.id_dokter=t.dokter_konsul", "left");
            $n = $this->db->get_where("riwayat_pasien_inap t", ["t.id" => $row->id_terkait]);
            $data["dari"] = $n->row();
        }
        $q = $this->getapotek_inap($row->no_reg, $row->dokter_konsul);
        $data["terapi"] = $q->result();
        return $data;
    }
    function listvisit($no_reg, $iddokter)
    {
        $q = $this->db->get_where("riwayat_pasien_inap", ["no_reg" => $no_reg, "dokter_visit" => $iddokter, "pemeriksaan" => "visit"]);
        return $q;
    }
    function listvisit_detail($id)
    {
        $q = $this->db->get_where("riwayat_pasien_inap", ["id" => $id, "pemeriksaan" => "visit"]);
        return $q;
    }
    function getcppt($no_reg, $status = "")
    {
        if ($status != "") $this->db->where("pemeriksaan", $status);
        $q = $this->db->get_where("riwayat_pasien_inap", ["no_reg" => $no_reg]);
        return $q;
    }
    function getassesmen_perawat($no_reg, $shift = "igd")
    {
        $this->db->select("a.*,p.name as prov,r.name as kota");
        $this->db->join("provinces p", "p.id=a.prov", "left");
        $this->db->join("regencies r", "r.id=a.kota", "left");
        $q = $this->db->get_where("assesmen_perawat_vaksin a", ["a.no_reg" => $no_reg, "a.shift" => $shift]);
        return $q;
    }
    function getresume_pulang($no_pasien, $no_reg)
    {
        $q = $this->db->get_where("resume_pulang", ["no_reg" => $no_reg]);
        return $q;
    }
    function getrujukan_pasien($no_pasien, $no_reg)
    {
        $q = $this->db->get_where("buku_rujukan", ["no_reg" => $no_reg]);
        return $q;
    }
    function getsebab_kematian($no_rm, $no_reg)
    {
        $q = $this->db->get_where("sebab_kematian", ["no_reg" => $no_reg], ["no_rm" => $no_rm]);
        return $q->row();
    }
    function simpansebabkematian($aksi)
    {
        switch ($aksi) {
            case 'simpan':
                $data = array(
                    'tanggal'       => date("Y-m-d"),
                    'a'             => $this->input->post("a"),
                    'b'             => $this->input->post("b"),
                    'c'             => $this->input->post("c"),
                    'ii'            => $this->input->post("ii"),
                    'lamanya1'      => $this->input->post("lamanya1"),
                    'lamanya2'      => $this->input->post("lamanya2"),
                    'lamanya3'      => $this->input->post("lamanya3"),
                    'lamanya4'      => $this->input->post("lamanya4"),
                    'rudapaksa_a'   => $this->input->post("rudapaksa_a"),
                    'rudapaksa_b'   => $this->input->post("rudapaksa_b"),
                    'rudapaksa_c'   => $this->input->post("rudapaksa_c"),
                    'kelahiran_a'   => $this->input->post("kelahiran_a"),
                    'kelahiran_b'   => $this->input->post("kelahiran_b"),
                    'persalinan_a'  => $this->input->post("persalinan_a"),
                    'persalinan_b'  => $this->input->post("persalinan_b"),
                    'oprasi_a'      => $this->input->post("oprasi_a"),
                    'oprasi_b'      => $this->input->post("oprasi_b"),
                    'catatan'       => $this->input->post("catatan"),
                    'no_reg'        => $this->input->post("no_reg"),
                    'no_rm'         => $this->input->post("no_rm"),
                    'jenis'         => $this->input->post("jenis")
                );
                $this->db->insert("sebab_kematian", $data);
                return "success-Data berhasil disimpan";
                break;
            case 'edit':
                $data = array(
                    'tanggal'       => date("Y-m-d"),
                    'a'             => $this->input->post("a"),
                    'b'             => $this->input->post("b"),
                    'c'             => $this->input->post("c"),
                    'ii'            => $this->input->post("ii"),
                    'lamanya1'      => $this->input->post("lamanya1"),
                    'lamanya2'      => $this->input->post("lamanya2"),
                    'lamanya3'      => $this->input->post("lamanya3"),
                    'lamanya4'      => $this->input->post("lamanya4"),
                    'rudapaksa_a'   => $this->input->post("rudapaksa_a"),
                    'rudapaksa_b'   => $this->input->post("rudapaksa_b"),
                    'rudapaksa_c'   => $this->input->post("rudapaksa_c"),
                    'kelahiran_a'   => $this->input->post("kelahiran_a"),
                    'kelahiran_b'   => $this->input->post("kelahiran_b"),
                    'persalinan_a'  => $this->input->post("persalinan_a"),
                    'persalinan_b'  => $this->input->post("persalinan_b"),
                    'oprasi_a'      => $this->input->post("oprasi_a"),
                    'oprasi_b'      => $this->input->post("oprasi_b"),
                    'catatan'       => $this->input->post("catatan")
                );
                $this->db->where("no_reg", $this->input->post("no_reg"), "no_rm", $this->input->post("no_rm"));
                $this->db->update("sebab_kematian", $data);
                return "info-Data berhasil diubah";
                break;
        }
    }
    function simpanresume_inap($action)
    {
        $data = array(
            "no_reg" => $this->input->post("no_reg"),
            "diagnosa_akhir" => $this->input->post("diagnosa_akhir"),
            "diagnosa_tambahan" => $this->input->post("diagnosa_tambahan"),
            "komplikasi" => $this->input->post("komplikasi"),
            "perkembangan_perawatan" => $this->input->post("perkembangan_perawatan"),
            "pelayanan_puskesmas" => $this->input->post("pelayanan_puskesmas"),
            "riwayat_penyakit" => $this->input->post("riwayat_penyakit"),
            "pemeriksaan_fisik" => $this->input->post("pemeriksaan_fisik"),
            "alasan_masuk_rs" => $this->input->post("alasan_masuk_rs"),
            "riwayat_penyakit" => $this->input->post("riwayat_penyakit"),
            "diagnosa_masuk" => $this->input->post("diagnosa_masuk"),
            "ekg" => $this->input->post("ekg"),
        );
        switch ($action) {
            case 'simpan':
                $this->db->insert("resume_pulang", $data);
                break;
            case 'edit':
                $this->db->where("no_reg", $this->input->post("no_reg"));
                $this->db->update("resume_pulang", $data);
                break;
        }
        return "success-Data berhasil disimpan";
    }
    function simpanrujukan_pasien()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $this->db->where("no_pasien", $this->input->post("no_pasien"));
        $q = $this->db->get("buku_rujukan");
        if ($q->num_rows() > 0) {
            $data = array(
                'keluhan_utama' => $this->input->post("keluhan_utama"),
                'diagnosa' => $this->input->post("diagnosa"),
                'tindakan' => $this->input->post("tindakan"),
                'pengantar'           => $this->input->post("pengantar"),
                'telepon'             => $this->input->post("telepon"),
                'dikirim'             => $this->input->post("dikirim"),
                'penerima'            => $this->input->post("penerima"),
                'pemeriksaan_fisik'            => $this->input->post("pemeriksaan_fisik"),
                'alasan' => $this->input->post("alasan1") . "," . $this->input->post("alasan2") . "," . $this->input->post("alasan3") . "," . $this->input->post("alasan4"),
            );
            $this->db->where("no_pasien", $this->input->post("no_pasien"));
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("buku_rujukan", $data);
        } else {
            $id = $this->getnosurat("RP", "buku_rujukan");
            $data = array(
                "nomor_surat" => $id,
                "no_reg" => $this->input->post("no_reg"),
                "no_pasien" => $this->input->post("no_pasien"),
                "tgl_insert" => date("Y-m-d H:i:s"),
                "tahun" => date("Y"),
                "bulan" => date("m"),
                "jenis" => $this->input->post("jenis"),
                "keluhan_utama" => $this->input->post("keluhan_utama"),
                "diagnosa" => $this->input->post("diagnosa"),
                "tindakan" => $this->input->post("tindakan"),
                'pemeriksaan_fisik'            => $this->input->post("pemeriksaan_fisik"),
                'pengantar'           => $this->input->post("pengantar"),
                'telepon'             => $this->input->post("telepon"),
                'dikirim'             => $this->input->post("dikirim"),
                'penerima'            => $this->input->post("penerima"),
                'alasan' => $this->input->post("alasan1") . "," . $this->input->post("alasan2") . "," . $this->input->post("alasan3") . "," . $this->input->post("alasan4"),
            );
            $this->db->insert("buku_rujukan", $data);
        }
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
    function getassesmen_dokter($no_reg)
    {
        $q = $this->db->get_where("assesmen", ["no_reg" => $no_reg]);
        return $q;
    }
    function getokadetail($no_reg)
    {
        $this->db->select("p.nama_tindakan as nama_operasi");
        $this->db->join("tarif_operasi p", "p.kode = o.operasi", "left");
        $this->db->where("o.no_reg", $no_reg);
        $q = $this->db->get("oka o");
        return $q;
    }
    function getekspertisi_lab($no_reg)
    {
        $q = $this->db->get_where("ekspertisi_lab", ["no_reg" => $no_reg]);
        return $q;
    }
    function getapotekinap($no_reg)
    {
        $this->db->select("a.nama_obat,a.tanggal,a.aturan_pakai");
        $this->db->order_by("a.nama_obat");
        $this->db->join("farmasi_data_obat f", "f.kode=a.kode_obat", "inner");
        $q = $this->db->get_where("apotek_inap a", ["no_reg" => $no_reg, "f.kelkd!=" => "ALS"]);
        return $q;
    }
    function getriwayat_pasien_inap($no_reg)
    {
        $q = $this->db->get_where("riwayat_pasien_inap", ["no_reg" => $no_reg]);
        return $q->row();
    }
    function getradralan($no_reg)
    {
        $this->db->select("e.hasil_pemeriksaan,t.nama_tindakan");
        $this->db->join("tarif_radiologi t", "t.id_tindakan=e.id_tindakan", "inner");
        $this->db->group_by("e.id_tindakan");
        $q = $this->db->get_where("ekspertisi e", ["e.no_reg" => $no_reg]);
        return $q;
    }
    function getradinap($no_reg)
    {
        $this->db->select("e.hasil_pemeriksaan,e.tanggal,t.nama_tindakan");
        $this->db->join("tarif_radiologi t", "t.id_tindakan=e.id_tindakan", "inner");
        $this->db->group_by("e.id_tindakan,e.pemeriksaan,e.tanggal");
        $q = $this->db->get_where("ekspertisi_radinap e", ["e.no_reg" => $no_reg]);
        return $q;
    }
    function getparalan($no_reg)
    {
        $this->db->select("e.hasil_pemeriksaan,t.nama_tindakan");
        $this->db->join("tarif_pa t", "t.kode_tindakan=e.kode_tindakan", "inner");
        $this->db->group_by("e.kode_tindakan");
        $q = $this->db->get_where("ekspertisi_pa e", ["e.no_reg" => $no_reg]);
        return $q;
    }
    function getpainap($no_reg)
    {
        $this->db->select("e.hasil_pemeriksaan,e.tanggal,t.nama_tindakan");
        $this->db->join("tarif_pa t", "t.kode_tindakan=e.kode_tindakan", "inner");
        $this->db->group_by("e.kode_tindakan,e.pemeriksaan,e.tanggal");
        $q = $this->db->get_where("ekspertisi_painap e", ["e.no_reg" => $no_reg]);
        return $q;
    }
    function getstatuspulang()
    {
        $q = $this->db->get("status_pulang");
        $data = array();
        foreach ($q->result() as $value) {
            $data[$value->id] = $value->keterangan;
        }
        return $data;
    }
    function getkeadaanpulang()
    {
        $q = $this->db->get("keadaan_pulang");
        $data = array();
        foreach ($q->result() as $value) {
            $data[$value->id] = $value->keterangan;
        }
        return $data;
    }
    function getdpjp_poli()
    {
        $this->db->select("j.id_dokter,p.keterangan");
        $this->db->join("poliklinik p", "p.kode=j.id_poli", "inner");
        $q = $this->db->get_where("jadwal_dokter j", ["p.kontrol" => 1]);
        $data = array();
        foreach ($q->result() as $value) {
            $data[$value->id_dokter] = $value->keterangan;
        }
        return $data;
    }
    function getapotekralan_resume($no_reg)
    {
        $this->db->select("a.nama_obat,a.tanggal,a.aturan_pakai");
        $this->db->group_by("a.kode_obat");
        $this->db->join("farmasi_data_obat f", "f.kode=a.kode_obat", "inner");
        $q = $this->db->get_where("apotek a", ["no_reg" => $no_reg, "f.kelkd!=" => "ALS"]);
        return $q;
    }
    function getapotekinap_resume($no_reg)
    {
        $this->db->select("a.nama_obat,a.tanggal,a.aturan_pakai");
        $this->db->group_by("a.kode_obat");
        $this->db->join("farmasi_data_obat f", "f.kode=a.kode_obat", "inner");
        $q = $this->db->get_where("apotek_inap a", ["no_reg" => $no_reg, "f.kelkd!=" => "ALS"]);
        return $q;
    }
    function getpersetujuan($no_reg)
    {
        $q = $this->db->get_where("persetujuan", ["no_reg" => $no_reg]);
        return $q->row();
    }
    function getpengantar_terapi($no_reg)
    {
        $q = $this->db->get_where("pasien_ralan", ["no_reg" => $no_reg]);
        $row = $q->row();
        if ($row->no_reg_sebelumnya != "") {
            $n = $this->db->get_where("pengantar_terapi", ["no_reg" => $row->no_reg_sebelumnya]);
        } else {
            $n = $this->db->get_where("pengantar_terapi", ["no_reg" => $no_reg]);
        }
        return $n;
    }
    function simpanpengantarterapi($aksi)
    {
        $q = $this->db->get_where("pasien_ralan", ["no_reg" => $this->input->post("no_reg")]);
        $row = $q->row();
        if ($row->no_reg_sebelumnya != "") {
            $no_reg = $row->no_reg_sebelumnya;
        } else {
            $no_reg = $this->input->post("no_reg");
        }
        $data = array(
            "no_reg" => $no_reg,
            "no_rm" => $this->input->post("no_rm"),
            "diagnosa_kerja" => $this->input->post("diagnosa_kerja"),
            "rencana_terapi" => $this->input->post("rencana_terapi"),
            "jadwal_terapi" => $this->input->post("jadwal_terapi"),
            "persiapan" => $this->input->post("persiapan"),
            "rencana_pemeriksaan" => $this->input->post("rencana_pemeriksaan"),
            "catatan" => $this->input->post("catatan"),
        );
        switch ($aksi) {
            case 'simpan':
                $this->db->insert("pengantar_terapi", $data);
                break;
            case 'edit':
                $this->db->where("no_reg", $no_reg);
                $this->db->where("no_rm", $this->input->post("no_rm"));
                $this->db->update("pengantar_terapi", $data);
                break;
        }
        $msg  = "success-Data berhasil di simpan ";
        return $msg;
    }
    function getradioterapi_detail($no_reg)
    {
        $this->db->select("r.*,jr.keterangan as jenis");
        $this->db->join("jenis_radioterapi jr", "jr.kode=r.jenis");
        return $this->db->get_where("radioterapi r", ["no_reg" => $no_reg]);
    }
    function getitemradioterapi()
    {
        return $this->db->get("jenis_radioterapi");
    }
    function simpanradioterapi($aksi)
    {
        $q = $this->getitemradioterapi();
        $jenis = array();
        foreach ($q->result() as $row) {
            $jenis[$row->keterangan] =  $this->input->post($row->keterangan);
        }
        switch ($aksi) {
            case 'simpan':
                $data = array(
                    "no_pasien"             => $this->input->post("no_pasien"),
                    "no_reg"                => $this->input->post("no_reg"),
                    "keluhan_utama"         => $this->input->post("keluhan_utama"),
                    "riwayat_pekerjaan"     => $this->input->post("riwayat_pekerjaan"),
                    "merokok"               => $this->input->post("merokok"),
                    "alkohol"               => $this->input->post("alkohol"),
                    "anamnesa_khusus"       => $this->input->post("anamnesa_khusus"),
                    "anamnesa_umum"         => $this->input->post("anamnesa_umum"),
                    "jumlah_anak"           => $this->input->post("jumlah_anak"),
                    "keadaan_anak"          => $this->input->post("keadaan_anak"),
                    "keadaan_orangtua"      => $this->input->post("keadaan_orangtua"),
                    "riwayat_penyakit"      => $this->input->post("riwayat_penyakit"),
                    "jenis" => json_encode($jenis),

                );
                $this->db->insert("radioterapi", $data);
                break;
            case 'edit':
                $data = array(
                    "no_pasien"            => $this->input->post("no_pasien"),
                    "no_reg"           => $this->input->post("no_reg"),
                    "keluhan_utama"    => $this->input->post("keluhan_utama"),
                    "riwayat_pekerjaan" => $this->input->post("riwayat_pekerjaan"),
                    "merokok"          => $this->input->post("merokok"),
                    "alkohol"          => $this->input->post("alkohol"),
                    "anamnesa_khusus"  => $this->input->post("anamnesa_khusus"),
                    "anamnesa_umum"    => $this->input->post("anamnesa_umum"),
                    "jumlah_anak"      => $this->input->post("jumlah_anak"),
                    "keadaan_anak"     => $this->input->post("keadaan_anak"),
                    "keadaan_orangtua" => $this->input->post("keadaan_orangtua"),
                    "riwayat_penyakit" => $this->input->post("riwayat_penyakit"),
                    "jenis" => json_encode($jenis),
                );
                $this->db->where("no_reg", $this->input->post("no_reg"));
                $this->db->update("radioterapi", $data);
                break;
        }
        return "success-Data berhasil disimpan";
    }
    function getperawat(){
      $q = $this->db->get("perawat");
      return $q;
    }
    function getdosis(){
      $q = $this->db->get("dosis_vaksin");
      return $q;
    }
    function getnamavaksin(){
      $q = $this->db->get("nama_vaksin");
      return $q;
    }
}
