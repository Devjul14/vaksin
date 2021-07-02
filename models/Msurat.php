<?php
class Msurat extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function getpemulasaran_detail($no_reg)
    {
        $this->db->select("p.*,pr.nama as prm");
        $this->db->join("petugas_rm pr", "pr.nip=p.petugas_rm", "left");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pemulasaran p");
        return $q->row();
    }
    function getpasien_detail($no_pasien)
    {
        $this->db->select("ps.*,k.keterangan as nama_kesatuan,p.keterangan as nama_pangkat,pek.pekerjaan as nama_pekerjaan");
        $this->db->join("pangkat p", "p.id_pangkat=ps.id_pangkat", "left");
        $this->db->join("kesatuan k", "k.id_kesatuan=ps.id_kesatuan", "left");
        $this->db->join("pekerjaan pek", "pek.idx=ps.pekerjaan", "left");
        $this->db->where("no_pasien", $no_pasien);
        $q = $this->db->get("pasien ps");
        return $q->row();
    }
    function getpasieninap_detail($no_reg)
    {
        $this->db->select("pi.*,r.nama_ruangan,k.nama_kelas,p.nama_pasien,kmr.nama_kamar,kmr.no_bed,d.id_dokter,d.nama_dokter,d2.nama_dokter as nama_dpjp,pr.nama_perawat,d.no_sip,d2.no_sip as no_sip_dpjp,pi.tgl_masuk");
        $this->db->join("ruangan r", "r.kode_ruangan=pi.kode_ruangan");
        $this->db->join("kelas k", "k.kode_kelas=pi.kode_kelas");
        $this->db->join("kamar kmr", "kmr.kode_kamar=pi.kode_kamar");
        $this->db->join("pasien p", "p.no_pasien=pi.no_rm");
        $this->db->join("dokter d", "d.id_dokter=pi.dokter", "left");
        $this->db->join("dokter d2", "d2.id_dokter=pi.dpjp", "left");
        $this->db->join("perawat pr", "pr.id_perawat=pi.petugas_telapakkaki", "left");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pasien_inap pi");
        return $q->row();
    }
    function getpasienralan_detail($no_reg)
    {
        $this->db->select("pi.*,pl.keterangan as nama_ruangan,p.nama_pasien,d.id_dokter,d.nama_dokter,d2.nama_dokter as nama_dpjp,d.no_sip,d2.no_sip as no_sip_dpjp,pi.tanggal as tgl_masuk,date(pi.tanggal) as tgl_keluar");
        $this->db->join("pasien p", "p.no_pasien=pi.no_pasien");
        $this->db->join("dokter d", "d.id_dokter=pi.dokter_poli", "left");
        $this->db->join("dokter d2", "d2.id_dokter=pi.dokter_poli", "left");
        $this->db->join("poliklinik pl", "pl.kode=pi.tujuan_poli", "left");
        $this->db->where("no_reg", $no_reg);
        $this->db->or_where("no_reg_sebelumnya", $no_reg);
        $q = $this->db->get("pasien_ralan pi");
        return $q->row();
    }
    function getpulangpaksa_detail($no_reg)
    {
        $this->db->select("p.*,pr.nama as prm");
        $this->db->join("petugas_rm pr", "pr.nip=p.petugas_rm", "left");
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("pulang_paksa p");
        return $q->row();
    }
    function getrujukanpasien_detail($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("buku_rujukan");
        return $q->row();
    }
    function getrujukan_pasien($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("buku_rujukan");
        return $q->row();
    }
    function simpanpulangpaksa($aksi)
    {
        $q = $this->db->get_where("pasien_ttd", ["no_pasien" => $this->input->post("no_pasien")]);
        if ($q->num_rows() <= 0) {
            $d = array(
                "no_pasien" => $this->input->post("no_pasien"),
                "ttd" => "data:image/png;base64," . $this->input->post("ttd_pernyataan"),
                "ttd2" => "data:image/png;base64," . $this->input->post("ttd_saksi")
            );
            $this->db->insert("pasien_ttd", $d);
        }
        switch ($aksi) {
            case 'simpan':
                $data = array(
                    'nama'           => $this->input->post("nama"),
                    'nama_pasien'    => $this->input->post("nama_pasien"),
                    'jk'             => $this->input->post("jk"),
                    'pekerjaan'      => $this->input->post("pekerjaan"),
                    'alamat'         => $this->input->post("alamat"),
                    'hubungan'       => $this->input->post("hubungan"),
                    'saksi'          => $this->input->post("saksi"),
                    'umur'           => $this->input->post("umur"),
                    'no_reg'         => $this->input->post("no_reg"),
                    'no_pasien'      => $this->input->post("no_pasien"),
                    'alasan'           => $this->input->post("alasan"),
                    'ttd_saksi'      => $this->input->post("ttd_saksi"),
                    'ttd_pernyataan' => $this->input->post("ttd_pernyataan"),
                );
                $this->db->insert("pulang_paksa", $data);
                return "success-Data berhasil disimpan";
                break;
            case 'edit':
                $data = array(
                    'nama'           => $this->input->post("nama"),
                    'nama_pasien'    => $this->input->post("nama_pasien"),
                    'jk'             => $this->input->post("jk"),
                    'pekerjaan'      => $this->input->post("pekerjaan"),
                    'alamat'         => $this->input->post("alamat"),
                    'hubungan'       => $this->input->post("hubungan"),
                    'saksi'          => $this->input->post("saksi"),
                    'umur'           => $this->input->post("umur"),
                    'alasan'         => $this->input->post("alasan"),
                    'ttd_saksi'      => $this->input->post("ttd_saksi"),
                    'ttd_pernyataan' => $this->input->post("ttd_pernyataan"),
                );
                $this->db->where("no_reg", $this->input->post("no_reg"));
                $this->db->update("pulang_paksa", $data);
                return "info-Data berhasil diubah";
                break;
        }
    }
    function simpanpemulasaran($aksi)
    {
        $q = $this->db->get_where("pasien_ttd", ["no_pasien" => $this->input->post("no_pasien")]);
        if ($q->num_rows() <= 0) {
            $d = array(
                "no_pasien" => $this->input->post("no_pasien"),
                "ttd" => "data:image/png;base64," . $this->input->post("ttd_pernyataan"),
                "ttd2" => "data:image/png;base64," . $this->input->post("ttd_saksi")
            );
            $this->db->insert("pasien_ttd", $d);
        }
        switch ($aksi) {
            case 'simpan':
                $data = array(
                    'nama'           => $this->input->post("nama"),
                    'nama_pasien'    => $this->input->post("nama_pasien"),
                    'jk'             => $this->input->post("jk"),
                    // 'pekerjaan'      => $this->input->post("pekerjaan"),
                    'alamat'         => $this->input->post("alamat"),
                    'hubungan'       => $this->input->post("hubungan"),
                    'saksi'          => $this->input->post("saksi"),
                    'umur'           => $this->input->post("umur"),
                    'no_reg'         => $this->input->post("no_reg"),
                    'no_pasien'      => $this->input->post("no_pasien"),
                    'setuju'      => $this->input->post("setuju"),
                    'alasan'      => $this->input->post("alasan_tdksetuju"),
                    'ttd_saksi'      => $this->input->post("ttd_saksi"),
                    'ttd_pernyataan' => $this->input->post("ttd_pernyataan"),
                );
                $this->db->insert("pemulasaran", $data);
                return "success-Data berhasil disimpan";
                break;
            case 'edit':
                $data = array(
                    'nama'           => $this->input->post("nama"),
                    'nama_pasien'    => $this->input->post("nama_pasien"),
                    'jk'             => $this->input->post("jk"),
                    // 'pekerjaan'      => $this->input->post("pekerjaan"),
                    'alamat'         => $this->input->post("alamat"),
                    'hubungan'       => $this->input->post("hubungan"),
                    'saksi'          => $this->input->post("saksi"),
                    'umur'           => $this->input->post("umur"),
                    'setuju'      => $this->input->post("setuju"),
                    'alasan'      => $this->input->post("alasan_tdksetuju"),
                    'ttd_saksi'      => "data:image/png;base64," . $this->input->post("ttd_saksi"),
                    'ttd_pernyataan' => "data:image/png;base64," . $this->input->post("ttd_pernyataan"),
                );
                $this->db->where("no_reg", $this->input->post("no_reg"));
                $this->db->update("pemulasaran", $data);
                return "info-Data berhasil diubah";
                break;
        }
    }
    function simpankematian($no_reg, $no_pasien, $jenis)
    {
        $id = $this->getnosurat_kematian();
        $q = $this->ceksurat_kematian($no_reg);
        if ($q) {
            return $q->id;
        } else {
            $data = array(
                'no_pasien'     => $no_pasien,
                'no_reg'        => $no_reg,
                'bulan'         => date('m'),
                'tahun'         => date('Y'),
                'tgl_insert'    => date("Y-m-d H:i:s"),
                'nomor_surat'   => $id,
                'jenis'         => $jenis,
            );
            $this->db->insert("surat_kematian", $data);
            return $id;
        }
    }
    function getnosurat_kematian()
    {
        $this->db->where("jenis_surat", "SM");
        $this->db->where("tahun", date("Y"));
        $q1 = $this->db->get("setup_nomor");
        $row = $q1->row();

        for ($i = $row->mulai_nomor; $i <= 999; $i++) {
            $n = substr("000" . $i, -3);
            $where = array(
                "tahun"         => date("Y"),
                "nomor_surat"  => $n,
            );
            $q = $this->db->get_where("surat_kematian", $where);
            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function ceksurat_kematian($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        // $this->db->where("jenis","ranap");
        $q = $this->db->get("surat_kematian");
        return $q->row();
    }
    function getkematian_detail($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("surat_kematian");
        return $q->row();
    }
    function getberitamasuk_detail($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("surat_masuk_perawatan");
        return $q->row();
    }
    function getberitamasukperawatan($no_rm, $no_reg)
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
    function getkelahiran_detail($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("surat_kelahiran");
        return $q->row();
    }
    function getnarkoba_detail($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("surat_narkoba");
        return $q->row();
    }
    function getjiwa_detail($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        $q = $this->db->get("surat_jiwa");
        return $q->row();
    }
    function getsetup_rs()
    {
        $q = $this->db->get("setup_rs");
        return $q->row();
    }
    function simpankelahiran($no_reg, $no_pasien, $jenis)
    {
        $id = $this->getnosurat_kelahiran();
        $q = $this->ceksurat_kelahiran($no_reg);
        if ($q) {
            return $q->id;
        } else {
            $data = array(
                'no_pasien'     => $no_pasien,
                'no_reg'        => $no_reg,
                'bulan'         => date('m'),
                'tahun'         => date('Y'),
                'tgl_insert'    => date("Y-m-d H:i:s"),
                'nomor_surat'   => $id,
                'jenis'         => $jenis,
            );
            $this->db->insert("surat_kelahiran", $data);
            return $id;
        }
    }
    function getnosurat_kelahiran()
    {
        $this->db->where("jenis_surat", "SKK");
        $this->db->where("tahun", date("Y"));
        $q1 = $this->db->get("setup_nomor");
        $row = $q1->row();

        for ($i = $row->mulai_nomor; $i <= 999; $i++) {
            $n = substr("000" . $i, -3);
            $where = array(
                "tahun"         => date("Y"),
                "nomor_surat"  => $n,
            );
            $q = $this->db->get_where("surat_kelahiran", $where);
            if ($q->num_rows() <= 0) {
                return $n;
                break;
            }
        }
    }
    function ceksurat_kelahiran($no_reg)
    {
        $this->db->where("no_reg", $no_reg);
        // $this->db->where("jenis","ranap");
        $q = $this->db->get("surat_kelahiran");
        return $q->row();
    }
    function getttd($no_reg, $jenis)
    {
        $this->db->where("no_reg", $no_reg);
        $this->db->where("jenis", $jenis);
        $q = $this->db->get("pasien_ttd_tindakan");
        $ttd = "";
        if ($q->num_rows() > 0) {
            $row = $q->row();
            $ttd = $row;
        }
        return $ttd;
    }
    function getpasien_tindakan($no_reg, $jenis)
    {
        $this->db->where("no_reg", $no_reg);
        $this->db->where("jenis", $jenis);
        $q = $this->db->get("pasien_tindakan_medis");
        return $q->row();
    }
    function getpasien_masukperawatan($no_reg, $jenis)
    {
        $this->db->where("no_reg", $no_reg);
        $this->db->where("jenis", $jenis);
        $q = $this->db->get("surat_masuk_perawatan");
        return $q->row();
    }
    function getpasien_lepasperawatan($no_reg, $jenis)
    {
        $this->db->where("no_reg", $no_reg);
        $this->db->where("jenis", $jenis);
        $q = $this->db->get("surat_lepas_perawatan");
        return $q->row();
    }
    function getpasien_keterangandokter($no_reg, $jenis)
    {
        $this->db->where("no_reg", $no_reg);
        $this->db->where("jenis", $jenis);
        $q = $this->db->get("surat_keterangan_dokter");
        return $q->row();
    }
    function getpasien_suratistirahatsakit($no_reg, $jenis)
    {
        $this->db->where("no_reg", $no_reg);
        $this->db->where("jenis", $jenis);
        $q = $this->db->get("surat_istirahat_sakit");
        return $q->row();
    }
    function getassesmen_perawat($no_reg, $jenis)
    {
        $this->db->where("no_reg", $no_reg);
        $this->db->where("jenis", $jenis);
        $q = $this->db->get("assesmen_perawat");
        return $q->row();
    }
    function getassesmen_dokter($no_reg, $jenis)
    {
        if ($jenis == "ralan") {
            $this->db->where("no_reg", $no_reg);
            $p = $this->db->get("pasien_ralan")->row();
            if ($p->tujuan_poli == "0102029") {
                $this->db->where("no_reg", $no_reg);
                $q = $this->db->get("pemeriksaan_fisik");
            } else {
                $this->db->where("no_reg", $no_reg);
                $q = $this->db->get("assesmen_perawat");
            }
        }
        return $q->row();
    }
    function gettindakan_medis()
    {
        $q   = $this->db->get("tindakan_medis");
        $data = array();
        foreach ($q->result() as $row) {
            $data[$row->id] = $row->keterangan;
        }
        return $data;
    }
    function simpantindakanmedis()
    {
        $this->db->where("no_reg", $this->input->post("no_reg"));
        $q = $this->db->get("pasien_tindakan_medis");
        $data = array(
            'nama' => $this->input->post("nama"),
            'nama_saksi' => $this->input->post("nama_saksi"),
            'umur' => $this->input->post("umur"),
            'jk' => $this->input->post("jk"),
            'alamat' => $this->input->post("alamat"),
            'status_tindakan_kedokteran' => $this->input->post("status_tindakan_kedokteran"),
            'status_tindakan_anestesi' => $this->input->post("status_tindakan_anestesi"),
            'status_tindakan_transfusi' => $this->input->post("status_tindakan_transfusi"),
            'hubungan' => $this->input->post("hubungan"),
            'lock' => 1,
            'tanggal' => date("Y-m-d H:i:s")
        );
        if ($q->num_rows() > 0) {
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->update("pasien_tindakan_medis", $data);
        } else {
            $this->db->insert("pasien_tindakan_medis", $data);
        }
        $q = $this->db->get_where("pasien_ttd_tindakan", ["no_reg" => $this->input->post("no_reg"), "jenis" => $this->input->post("jenis")]);
        if ($q->num_rows() <= 0) {
            $d = array(
                "no_reg" => $this->input->post("no_reg"),
                "jenis" => $this->input->post("jenis"),
                "ttd" => $this->input->post("ttd_pernyataan"),
                "ttd2" => $this->input->post("ttd_saksi"),
            );
            $this->db->insert("pasien_ttd_tindakan", $d);
        } else {
            $d = array(
                "ttd" => $this->input->post("ttd_pernyataan"),
                "ttd2" => $this->input->post("ttd_saksi"),
            );
            $this->db->where("no_reg", $this->input->post("no_reg"));
            $this->db->where("jenis", $this->input->post("jenis"));
            $this->db->update("pasien_ttd_tindakan", $d);
        }
        return "success-Data berhasil disimpan ";
    }
    function resumepasien($no_reg)
    {
        $this->db->where("r.no_reg", $no_reg);
        $q = $this->db->get("resume_pulang r");
        return $q->row();
    }
}
