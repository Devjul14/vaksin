<?php
class Mcppt extends CI_Model{
   function __construct()
    {
        parent::__construct();
    }
    function getcppt($no_reg,$status=""){
		if ($status!="") $this->db->where("pemeriksaan",$status);
		$q = $this->db->get_where("riwayat_pasien_inap",["no_reg"=>$no_reg]);
		return $q;
	}
	function getcppt_ralan($no_rm,$tgl1,$tgl2){
		$this->db->select("a.*,p.dokter_poli");
		$this->db->where("a.no_rm",$no_rm);
		$this->db->where("p.layan!=",2);
		$this->db->where("a.tanggal_masuk>=",date("Y-m-d",strtotime($tgl1)));
		$this->db->where("a.tanggal_masuk<=",date("Y-m-d",strtotime($tgl2)));
		$this->db->join("pasien_ralan p","p.no_reg=a.no_reg and p.no_pasien=a.no_rm","inner");
		$q = $this->db->get("pasien_igd a");
		return $q;
	}
	function getdokterarray(){
		$this->db->select("id_dokter,nama_dokter");
		$q = $this->db->get("dokter");
		$data = array();
		foreach($q->result() as $row){
			$data[$row->id_dokter] = $row->nama_dokter;
		}
		return $data;
	}
	function getpetugasgiziarray(){
		$this->db->select("nip,nama");
		$q = $this->db->get("petugas_gizi");
		$data = array();
		foreach($q->result() as $row){
			$data[$row->nip] = $row->nama;
		}
		return $data;
	}
	function gettindakanarray(){
		$this->db->select("id_tindakan,nama_tindakan");
		$q = $this->db->get("tarif_radiologi");
		$data = array();
		foreach($q->result() as $row){
			$data["radiologi"][$row->id_tindakan] = $row->nama_tindakan;
		}
		$this->db->select("kode_tindakan,nama_tindakan");
		$q = $this->db->get("tarif_lab");
		foreach($q->result() as $row){
			$data["lab"][$row->kode_tindakan] = $row->nama_tindakan;
		}
		$this->db->select("kode,ket");
		$q = $this->db->get("tarif_penunjang_medis");
		foreach($q->result() as $row){
			$data["penunjang"][$row->kode] = $row->ket;
		}
		return $data;
	}
	function getterapiarray($no_reg){
        $this->db->select("apotek_inap.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya");
		$this->db->join("waktu w","w.kode = apotek_inap.waktu","left");
		$this->db->join("waktu_lainnya wl","wl.kode = apotek_inap.waktu_lainnya","left");
		$this->db->join("aturan_pakai a","a.kode = apotek_inap.aturan_pakai","left");
		$this->db->order_by("tanggal,nama_obat");
		$q = $this->db->get_where("apotek_inap",["no_reg" => $no_reg]);
		$data = array();
		foreach ($q->result() as $key) {
			$data[$key->dokter][$key->tanggal][] = $key; 
		}
		return $data;
	}
	function getterapiarray_ralan($no_reg){
        $this->db->select("apotek.*,a.nama as aturan, w.nama as nwaktu,wl.nama as ket_waktulainnya,date(p.tanggal) as tanggal,p.dokter_poli as dokter");
		$this->db->join("waktu w","w.kode = apotek.waktu","left");
		$this->db->join("waktu_lainnya wl","wl.kode = apotek.waktu_lainnya","left");
		$this->db->join("aturan_pakai a","a.kode = apotek.aturan_pakai","left");
		$this->db->join("pasien_ralan p","p.no_reg = apotek.no_reg","inner");
		$this->db->order_by("tanggal,nama_obat");
		$q = $this->db->get_where("apotek",["apotek.no_reg" => $no_reg]);
		$data = array();
		foreach ($q->result() as $key) {
			$data[$key->dokter][$key->tanggal][] = $key; 
		}
		return $data;
	}
	function viewcppt($no_reg,$tgl1,$tgl2,$status=""){
		$dok = $this->getdokterarray();
		$gz = $this->getpetugasgiziarray();
		$tdk = $this->gettindakanarray();
		$trp = $this->getterapiarray($no_reg);
		$c = $this->getcppt($no_reg,$status);
		foreach ($c->result() as $key) {
			if ($key->pemeriksaan=="konsul"){
				$tanggal = date("Y-m-d H:i:s",strtotime($key->tanggal." ".$key->jam));
				$jenis = strtoupper($key->pemeriksaan);
				$soap = "<table>";
				$soap .= "  <tr>";
				$soap .= "      <td>".($key->td=="" ? "" : "<b>TD Kiri</b> : ".$key->td." mmHg&nbsp;&nbsp;");
				$soap .= "      ".($key->td2=="" ? "" : "<b>TD Kanan</b> : ".$key->td2." mmHg&nbsp;&nbsp;");
				$soap .= "      ".($key->nadi=="" ? "" : "<b>Nadi</b> : ".$key->nadi." x/ mnt&nbsp;&nbsp;");
				$soap .= "      ".($key->respirasi=="" ? "" : "<b>Respirasi</b> : ".$key->respirasi." x/ mnt&nbsp;&nbsp;");
				$soap .= "      ".($key->suhu=="" ? "" : "<b>Suhu</b> : ".$key->suhu." °C&nbsp;&nbsp;");
				$soap .= "      ".($key->spo2=="" ? "" : "<b>SPo2</b> : ".$key->spo2)."</td>";
				$soap .= "  </tr>"; 
				$soap .= "<tr>";
				$soap .= "<td>".($key->bb=="" ? "" : "<b>BB</b> : ".$key->bb." kg&nbsp;&nbsp;");
				$soap .= ($key->tb=="" ? "" : "<b>TB</b> : ".$key->tb." cm&nbsp;&nbsp;")."</td>";
				$soap .= "</tr>";
				$soap .= "</table>";
				$pem = explode(",",$key->pemeriksaan_fisik);
				$kelainan = explode("|",$key->kelainan);
				$ada = 0;
				for ($i=0;$i<=10;$i++){
				    if (!$pem[$i]){
				        $ada = 1;
				    }
				}
				if ($ada==1) {
				    $soap .= "<table>";
				    $soap .= "    <tr>";
				    $soap .= "        <th>Pemeriksaan</th>";
				    $soap .= "        <th>Kelainan</th>";
				    $soap .= "    </tr>";
				    if ($pem[0]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td width=200px>Kepala</td>";
				        $soap .= "    <td>".(isset($kelainan[0]) ? ($pem[0] == "1" ? "" : $kelainan[0]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[1]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Mata</td>";
				        $soap .= "    <td>".(isset($kelainan[1]) ? ($pem[1] == "1" ? "" : $kelainan[1]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[2]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>THT</td>";
				        $soap .= "    <td>".(isset($kelainan[2]) ? ($pem[2] == "1" ? "" : $kelainan[2]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[3]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Gigi Mulut</td>";
				        $soap .= "    <td>".(isset($kelainan[3]) ? ($pem[3] == "1" ? "" : $kelainan[3]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[4]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Leher</td>";
				        $soap .= "    <td>".(isset($kelainan[4]) ? ($pem[4] == "1" ? "" : $kelainan[4]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[5]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Thoraks</td>";
				        $soap .= "    <td>".(isset($kelainan[5]) ? ($pem[5] == "1" ? "" : $kelainan[5]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[6]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Abdomen</td>";
				        $soap .= "    <td>".(isset($kelainan[6]) ? ($pem[6] == "1" ? "" : $kelainan[6]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[7]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Ekstremitas Atas</td>";
				        $soap .= "    <td>".(isset($kelainan[7]) ? ($pem[7] == "1" ? "" : $kelainan[7]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[8]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Ekstremitas Bawah</td>";
				        $soap .= "    <td>".(isset($kelainan[8]) ? ($pem[8] == "1" ? "" : $kelainan[8]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[9]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Genitalia</td>";
				        $soap .= "    <td>".(isset($kelainan[9]) ? ($pem[9] == "1" ? "" : $kelainan[9]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[10]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Anus</td>";
				        $soap .= "    <td>".(isset($kelainan[10]) ? ($pem[10] == "1" ? "" : $kelainan[10]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    $soap .= "</table>";
				}
				$id_dokter = $key->dokter_visit;
				$nama_dokter = $dok[$key->dokter_visit];
				$nama_dpjp = $dok[$key->dokter_visit];
				$hasil[] = array(
							"no_reg" => $no_reg,
							"tanggal" => $tanggal,
							"petugas" => "DOKTER",
							"jenis" => $jenis,
							"soap" => $soap,
							"id_dokter" => $id_dokter,
							"nama_dokter" => $nama_dokter,
							"dpjp" => $key->dokter_visit,
							"nama_dpjp" => $nama_dpjp
						  );
				if ($key->a!="" || $key->p!="" || $key->tindakan_radiologi!="" || $key->tindakan_lab!="" || $key->tindakan_penunjang!=""){
				    $tanggal = date("Y-m-d H:i:s",strtotime($key->tgl_jawab." ".$key->jam_jawab));
				    $jenis = "JAWAB KONSUL";
				    $soap  = "<p>";
				    $soap .= "    A : </strong>".$key->a."<br>";
				    $soap .= "    P : </strong>".$key->p."<br>";
				    $soap .= "</p>";
				    if (count($trp[$key->dokter_konsul][$key->tgl_jawab])>0){
				        $soap .= "<table class='table'>";
				        $soap .= "<tr>";
				        $soap .= "    <th width='50' class='text-center'>No</th>";
				        $soap .= "    <th>Nama Obat</th>";
				        $soap .= "    <th width='150'>Aturan Pakai</th>";
				        $soap .= "    <th>Waktu</th>";
				        $soap .= "    <th>Cara</th>";
				        $soap .= "    <th class='text-center'>Qty</th>";
				        $soap .= "</tr>";
				        $n = 1;
				        foreach ($trp[$key->dokter_konsul][$key->tgl_jawab] as $data) {
				            $soap .= "<tr id='data'>";
				            $soap .= "<td class='text-center'>".($n++)."</td>";
				            $soap .= "<td>".$data->nama_obat."</td>";
				            $soap .= "<td>".$data->aturan."</td>";
				            $soap .= "<td>".$data->nwaktu."</td>";
				            $soap .= "<td>".$data->pagi."-".$data->siang."-".$data->sore."-".$data->malem."-".$data->ket_waktulainnya."</td>";
				            $soap .= "<td class='text-center'>".$data->qty." ".$data->satuan."</td>";
				            $soap .= "</tr>";
				        }
				        $soap .= "</table>";
				    }
				    if ($key->tindakan_radiologi!="" || $key->tindakan_lab!="" || $key->tindakan_penunjang!="") {
				        $soap .= "    <strong>Pemeriksaan Penunjang</strong>";
				        $soap .= "    <table class='table'>";
				        $soap .= "       <tr>";
				        $soap .= "           <td><b>Radiologi</b></td>";
				        $soap .= "           <td><b>Lab</b></td>";
				        $soap .= "           <td><b>Lain</b></td>";
				        $soap .= "       </tr>";
				        $n = 1;
				        if ($key->tindakan_radiologi!=""){
				            $rad = explode(",", $key->tindakan_radiologi);
				            $soap .="<tr id='data'>";
				            $soap .="<td valign='top'><ol style='padding-left:30px'>";
				            if (is_array($rad)){
				                foreach ($rad as $key1 => $value) {
				                    if ($value!="")
				                    $soap .="<li>".$tdk["radiologi"][$value]."</li>";
				                }
				            } else {
				                $soap .="<li>".$tdk["radiologi"][$key->tindakan_radiologi]."</li>";
				            }
				            $soap .="</ol></td>";
				        }
				        if ($key->tindakan_lab!=""){
				            $lab = explode(",", $key->tindakan_lab);
				            $soap .="<td valign='top'><ol style='padding-left:30px'>";
				            if (is_array($lab)){
				                foreach ($lab as $key1 => $value) {
				                    if ($value!="")
				                    $soap .="<li>".$tdk["lab"][$value]."</li>";
				                }
				            } else {
				                $soap .="<li>".$tdk["lab"][$key->tindakan_lab]."</li>";
				            }      
				            $soap .="</ol></td>";
				        }
				        if ($key->tindakan_penunjang!=""){
				            $penunjang = explode(",", $key->tindakan_penunjang);
				            $soap .="<td valign='top'><ol style='padding-left:30px'>";
				            if (is_array($penunjang)){
				                foreach ($penunjang as $key1 => $value) {
				                    if ($value!="")
				                    $soap .="<li>".$tdk["penunjang"][$value]."</li>";
				                }
				            } else {
				                $soap .="<li>".$tdk["penunjang"][$key->tindakan_penunjang]."</li>";
				            }  
				            $soap .="</ol></td>";
				        }
				        $soap .="</tr>";
				        $soap .="</table>";
				    }
				    $id_dokter = $key->dokter_konsul;
				    $nama_dokter = $dok[$key->dokter_konsul];
				    $nama_dpjp = $dok[$key->dokter_visit];
				    $hasil[] = array(
							"no_reg" => $no_reg,
							"tanggal" => $tanggal,
							"petugas" => "DOKTER",
							"jenis" => $jenis,
							"soap" => $soap,
							"id_dokter" => $id_dokter,
							"nama_dokter" => $nama_dokter,
							"dpjp" => $key->dokter_visit,
							"nama_dpjp" => $nama_dpjp
						  );
				}
			} else {
				$tanggal = date("Y-m-d",strtotime($key->tanggal." ".$key->jam));
				$jenis = strtoupper($key->pemeriksaan);
				$soap  = "<table>";
				$soap .= "  <tr>";
				$soap .= "      <td>".($key->td=="" ? "" : "<b>TD Kiri</b> : ".$key->td." mmHg&nbsp;&nbsp;");
				$soap .= "      ".($key->td2=="" ? "" : "<b>TD Kanan</b> : ".$key->td2." mmHg&nbsp;&nbsp;");
				$soap .= "      ".($key->nadi=="" ? "" : "<b>Nadi</b> : ".$key->nadi." x/ mnt&nbsp;&nbsp;");
				$soap .= "      ".($key->respirasi=="" ? "" : "<b>Respirasi</b> : ".$key->respirasi." x/ mnt&nbsp;&nbsp;");
				$soap .= "      ".($key->suhu=="" ? "" : "<b>Suhu</b> : ".$key->suhu." °C&nbsp;&nbsp;");
				$soap .= "      ".($key->spo2=="" ? "" : "<b>SPo2</b> : ".$key->spo2)."</td>";
				$soap .= "  </tr>"; 
				$soap .= "<tr>";
				$soap .= "    <td>".($key->bb=="" ? "" : "<b>BB</b> : ".$key->bb." kg&nbsp;&nbsp;");
				$soap .= "    ".($key->tb=="" ? "" : "<b>TB</b> : ".$key->tb." cm&nbsp;&nbsp;")."</td>";
				$soap .= "</tr>";
				$soap .= "</table>";
				$pem = explode(",",$key->pemeriksaan_fisik);
				$kelainan = explode("|",$key->kelainan);
				$ada = 0;
				for ($i=0;$i<=10;$i++){
				    if (!$pem[$i]){
				        $ada = 1;
				    }
				}
				if ($ada==1) {
				    $soap .= "<table>";
				    $soap .= "    <tr>";
				    $soap .= "        <th>Pemeriksaan</th>";
				    $soap .= "        <th>Kelainan</th>";
				    $soap .= "    </tr>";
				    if ($pem[0]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td width=200px>Kepala</td>";
				        $soap .= "    <td>".(isset($kelainan[0]) ? ($pem[0] == "1" ? "" : $kelainan[0]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[1]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Mata</td>";
				        $soap .= "    <td>".(isset($kelainan[1]) ? ($pem[1] == "1" ? "" : $kelainan[1]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[2]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>THT</td>";
				        $soap .= "    <td>".(isset($kelainan[2]) ? ($pem[2] == "1" ? "" : $kelainan[2]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[3]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Gigi Mulut</td>";
				        $soap .= "    <td>".(isset($kelainan[3]) ? ($pem[3] == "1" ? "" : $kelainan[3]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[4]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Leher</td>";
				        $soap .= "    <td>".(isset($kelainan[4]) ? ($pem[4] == "1" ? "" : $kelainan[4]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[5]!="1"){
				        echo "<tr>";
				        $soap .= "    <td>Thoraks</td>";
				        $soap .= "    <td>".(isset($kelainan[5]) ? ($pem[5] == "1" ? "" : $kelainan[5]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[6]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Abdomen</td>";
				        $soap .= "    <td>".(isset($kelainan[6]) ? ($pem[6] == "1" ? "" : $kelainan[6]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[7]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Ekstremitas Atas</td>";
				        $soap .= "    <td>".(isset($kelainan[7]) ? ($pem[7] == "1" ? "" : $kelainan[7]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[8]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Ekstremitas Bawah</td>";
				        $soap .= "    <td>".(isset($kelainan[8]) ? ($pem[8] == "1" ? "" : $kelainan[8]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[9]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Genitalia</td>";
				        $soap .= "    <td>".(isset($kelainan[9]) ? ($pem[9] == "1" ? "" : $kelainan[9]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    if ($pem[10]!="1"){
				        $soap .= "<tr>";
				        $soap .= "    <td>Anus</td>";
				        $soap .= "    <td>".(isset($kelainan[10]) ? ($pem[10] == "1" ? "" : $kelainan[10]) : '')."</td>";
				        $soap .= "</tr>";
				    }
				    $soap .= "</table>";
				}
				$soap .= "<br>";
				if ($key->a!="" || $key->p!="" || $key->tindakan_radiologi!="" || $key->tindakan_lab!="" || $key->tindakan_penunjang!=""){
				    $soap .= "    A : </strong>".$key->a."<br>";
				    $soap .= "    P : </strong>".$key->p."<br>";
				    if (count($trp[$key->dokter_visit][$key->tanggal])>0){
				        $soap .= "<table class='table'>";
				        $soap .= "<tr>";
				        $soap .= "    <th width='50' class='text-center'>No</th>";
				        $soap .= "    <th>Nama Obat</th>";
				        $soap .= "    <th width='150'>Aturan Pakai</th>";
				        $soap .= "    <th>Waktu</th>";
				        $soap .= "    <th>Cara</th>";
				        $soap .= "    <th class='text-center'>Qty</th>";
				        $soap .= "</tr>";
				        $n = 1;
				        foreach ($trp[$key->dokter_visit][$key->tanggal] as $data) {
				            $soap .= "<tr id='data'>";
				            $soap .= "<td class='text-center'>".($n++)."</td>";
				            $soap .= "<td>".$data->nama_obat."</td>";
				            $soap .= "<td>".$data->aturan."</td>";
				            $soap .= "<td>".$data->nwaktu."</td>";
				            $soap .= "<td>".$data->pagi."-".$data->siang."-".$data->sore."-".$data->malem."-".$data->ket_waktulainnya."</td>";
				            $soap .= "<td class='text-center'>".$data->qty." ".$data->satuan."</td>";
				            $soap .= "</tr>";
				        }
				        $soap .= "</table>";
				    }
				    if ($key->tindakan_radiologi!="" || $key->tindakan_lab!="" || $key->tindakan_penunjang!="") {
				        $soap .= "    <strong>Pemeriksaan Penunjang</strong>";
				        $soap .= "    <table class='table'>";
				        $soap .= "       <tr>";
				        $soap .= "           <td><b>Radiologi</b></td>";
				        $soap .= "           <td><b>Lab</b></td>";
				        $soap .= "           <td><b>Lain</b></td>";
				        $soap .= "       </tr>";
				        $n = 1;
				        if ($key->tindakan_radiologi!=""){
				            $rad = explode(",", $key->tindakan_radiologi);
				            $soap .= "<tr id='data'>";
				            $soap .= "<td valign='top'><ol style='padding-left:30px'>";
				            if (is_array($rad)){
								foreach ($rad as $key1 => $value) {
				    				if ($value!="")
				    					$soap .= "<li>".$tdk["radiologi"][$value]."</li>";
								}
				            } else {
								$soap .= "<li>".$tdk["radiologi"][$key->tindakan_radiologi]."</li>";
				            }
				            $soap .= "</ol></td>";
				        }
				        if ($key->tindakan_lab!=""){
				            $lab = explode(",", $key->tindakan_lab);
				            $soap .= "<td valign='top'><ol style='padding-left:30px'>";
				            if (is_array($lab)){
								foreach ($lab as $key1 => $value) {
								    if ($value!="")
								    	$soap .= "<li>".$tdk["lab"][$value]."</li>";
								}
				            } else {
								$soap .= "<li>".$tdk["lab"][$key->tindakan_lab]."</li>";
				            }      
				            $soap .= "</ol></td>";
				        }
				        if ($key->tindakan_penunjang!=""){
				            $penunjang = explode(",", $key->tindakan_penunjang);
				            $soap .= "<td valign='top'><ol style='padding-left:30px'>";
				            if (is_array($penunjang)){
								foreach ($penunjang as $key1 => $value) {
								    if ($value!="")
								    	$soap .= "<li>".$tdk["penunjang"][$value]."</li>";
								}
				            } else {
								$soap .= "<li>".$tdk["penunjang"][$key->tindakan_penunjang]."</li>";
				            }  
				            $soap .= "</ol></td>";
				        }
				        $soap .= "</tr>";
				        $soap .= "</table>";
				    }
				    $id_dokter = $key->dokter_visit;
				    $nama_dokter = $dok[$key->dokter_visit];
				    $nama_dpjp = $dok[$key->dokter_visit];
				    $hasil[] = array(
							"no_reg" => $no_reg,
							"tanggal" => $tanggal,
							"petugas" => "DOKTER",
							"jenis" => $jenis,
							"soap" => $soap,
							"id_dokter" => $id_dokter,
							"nama_dokter" => $nama_dokter,
							"dpjp" => $key->dokter_visit,
							"nama_dpjp" => $nama_dpjp
						  );
				}
			}
		}
		if ($this->session->userdata("full")==1 || $this->session->userdata("full")==""){
			$this->db->select("k.jam_radiologi as tanggal,e.hasil_pemeriksaan,k.kode_petugas,k.dokter_pengirim");
			$this->db->join("kasir_inap k","k.no_reg=e.no_reg and k.pemeriksaan=e.pemeriksaan and k.kode_tarif=e.id_tindakan","inner");
			$q = $this->db->get_where("ekspertisi_radinap e",["e.no_reg"=>$no_reg]);
			foreach($q->result() as $data){
				$id_dokter = $data->kode_petugas;
				$nama_dokter = $dok[$data->kode_petugas];
				$nama_dpjp = $dok[$data->dokter_pengirim];
				$hasil[] = array(
						"no_reg" => $no_reg,
						"tanggal" => $data->tanggal,
						"petugas" => "RADIOLOGI",
						"jenis" => "EKSPERTISI RADIOLOGI",
						"soap" => $data->hasil_pemeriksaan,
						"id_dokter" => $id_dokter,
						"nama_dokter" => $nama_dokter,
						"dpjp" => $data->dokter_pengirim,
						"nama_dpjp" => $nama_dpjp
					);
			}
		}
		if ($this->session->userdata("full")==1 || $this->session->userdata("full")==""){
			$this->db->select("k.tanggal,e.hasil_pemeriksaan,k.kode_petugas,k.dokter_pengirim");
			$this->db->join("kasir_inap k","k.no_reg=e.no_reg and k.pemeriksaan=e.pemeriksaan and k.kode_tarif=e.kode_tindakan","inner");
			$q = $this->db->get_where("ekspertisi_painap e",["e.no_reg"=>$no_reg]);
			foreach($q->result() as $data){
				$id_dokter = $data->kode_petugas;
				$nama_dokter = $dok[$data->kode_petugas];
				$nama_dpjp = $dok[$data->dokter_pengirim];
				$hasil[] = array(
						"no_reg" => $no_reg,
						"tanggal" => $data->tanggal,
						"petugas" => "PATOLOGI ANATOMI",
						"jenis" => "EKSPERTISI PATOLOGI ANATOMI",
						"soap" => $data->hasil_pemeriksaan,
						"id_dokter" => $id_dokter,
						"nama_dokter" => $nama_dokter,
						"dpjp" => $data->dokter_pengirim,
						"nama_dpjp" => $nama_dpjp
					);
			}
		}
		if ($this->session->userdata("full")==1 || $this->session->userdata("full")==""){
			$this->db->select("k.terima_lab,k.tanggal,k.kode_petugas,e.pemeriksaan,k.dokter_pengirim");
			$this->db->join("kasir_inap k","k.no_reg=e.no_reg and k.pemeriksaan=e.pemeriksaan and k.kode_tarif=e.kode_tindakan","inner");
			$this->db->order_by("k.tanggal,e.pemeriksaan");
			$this->db->group_by("k.tanggal,e.pemeriksaan");
			$q = $this->db->get_where("ekspertisi_labinap e",["e.no_reg"=>$no_reg]);
			foreach($q->result() as $data){
				$this->db->select("e.tanggal,l.nama,e.hasil,k.kode_petugas,t.nama_tindakan,l.satuan");
				$this->db->join("kasir_inap k","k.no_reg=e.no_reg and k.pemeriksaan=e.pemeriksaan and k.kode_tarif=e.kode_tindakan and e.tanggal=k.tanggal","inner");
				$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif","inner");
				$this->db->join("lab_normal l","l.kode_tindakan=t.kode_tindakan and l.kode=e.kode_labnormal","inner");
				$s = $this->db->get_where("ekspertisi_labinap e",["e.no_reg"=>$no_reg,"e.pemeriksaan"=>$data->pemeriksaan,"e.tanggal"=>date("Y-m-d",strtotime($data->tanggal))]);
				$soap = "<table>";
				$soap .= "<tr><td colspan='2'>Pemeriksaan ke-".$data->pemeriksaan."</td></tr>";
				foreach($s->result() as $row){
					$soap .= "<tr>";
					$soap .= "<td>".$row->nama."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					$soap .= "<td>".$row->hasil." ".$row->satuan."</td>";
					$soap .= "</tr>";
				}
				$soap .= "</table>";
				$id_dokter = $data->kode_petugas;
				$nama_dokter = $dok[$data->kode_petugas];
				$nama_dpjp = $dok[$data->dokter_pengirim];
				if ($data->terima_lab=="0000-00-00 00:00:00"){
					$terima_lab = $data->jam_lab;
				} else {
					$terima_lab = $data->terima_lab;
				}
				$hasil[] = array(
						"no_reg" => $no_reg,
						"tanggal" => date("Y-m-d",strtotime($terima_lab))." ".date("H:i:s",strtotime($terima_lab)),
						"petugas" => "LABOTARIUM",
						"jenis" => "EKSPERTISI LAB",
						"soap" => $soap,
						"id_dokter" => $kode_petugas,
						"nama_dokter" => $nama_dokter,
						"dpjp" => $data->dokter_pengirim,
						"nama_dpjp" => $nama_dpjp
					);
			}
		}
		$this->db->order_by("e.pemeriksaan");
		$this->db->group_by("e.pemeriksaan");
		$this->db->join("kasir_inap k","k.no_reg=e.no_reg and k.pemeriksaan=e.pemeriksaan and k.kode_tarif=e.kode_tindakan","inner");
		$q = $this->db->get_where("ekspertisi_giziinap e",["e.no_reg"=>$no_reg]);
		foreach($q->result() as $data){
			$this->db->select("a.nama,e.hasil_pemeriksaan,a.jenis");
			$this->db->join("asuhan_gizi a","a.kode=e.kode_asuhan","inner");
			$this->db->order_by("a.urutan,e.kode_asuhan");
			$s = $this->db->get_where("ekspertisi_giziinap e",["e.no_reg"=>$no_reg,"e.pemeriksaan"=>$data->pemeriksaan]);
			$soap = "<table width='100%'>";
			$jenis = "";
			foreach($s->result() as $row){
				if ($jenis!=$row->jenis){
					$soap .= "<tr><td colspan='2'>".$row->jenis."</td></tr>";
				}
				$soap .= "<tr>";
				$soap .= "<td width='30%'>&nbsp;&nbsp;&nbsp;".$row->nama."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				$soap .= "<td width='70%'>".$row->hasil_pemeriksaan."</td>";
				$soap .= "</tr>";
				$jenis = $row->jenis;
			}
			$soap .= "</table>";
			$id_dokter = $data->kode_petugas;
			$nama_dokter = $dok[$data->kode_petugas];
			$nama_dpjp = $dok[$data->dokter_pengirim];
			$hasil[] = array(
						"no_reg" => $no_reg,
						"tanggal" => date("Y-m-d",strtotime($data->tanggal))." ".date("H:i:s",strtotime($data->jam)),
						"petugas" => "PETUGAS GIZI",
						"jenis" => "EKSPERTISI GIZI",
						"soap" => $soap,
						"id_dokter" => $id_dokter,
						"nama_dokter" => $nama_dokter,
						"dpjp" => $data->dokter_pengirim,
						"nama_dpjp" => $nama_dpjp
					);
		}
		$this->db->select("dpjp");
		$q = $this->db->get_where("assesmen_perawat a",["a.no_reg"=>$no_reg,"shift"=>"malam"]);
		if ($q->num_rows()>0) $dpjp = $q->row()->dpjp; else $dpjp = "";
		$this->db->select("a.*,p.nama_perawat");
		$this->db->join("perawat p","p.id_perawat=a.pemberi","left");
		$q = $this->db->get_where("assesmen_perawat a",["a.no_reg"=>$no_reg]);
		foreach ($q->result() as $data) {
			if ($data->shift!="igd"){
				$soap  = "<table width='100%'>";
				$soap .= "<tr style='vertical-align:top'>";
				$soap .= "<td>";
				$soap .= "S : <br>".$data->s."<br>";
				$soap .= "O : <br>".$data->o;
				$soap .= "<div class='row'>";
				$soap .= "<div class='col-md-6'>T : ".($data->td=="" ? $data->td2 : $data->td)."</div>";
				$soap .= "<div class='col-md-6'>R : ".$data->respirasi."</div>";
				$soap .= "<div class='col-md-6'>N : ".$data->nadi."</div>";
				$soap .= "<div class='col-md-6'>S : ".$data->suhu."</div>"; 
				$soap .= "<div class='col-md-12'>O2 Saturasi : ".$data->spo2."</div>"; 
				$soap .= "</div><br>";
				$soap .= "A : <br>".$data->a."<br>";
				$soap .= "P : <br>".$data->p;
		        $soap .= "</td>";
		        $soap .= "</tr>";
		        $soap .= "</table>";
		        $nama_dpjp = $dok[$dpjp];
		        $hasil[] = array(
							"no_reg" => $no_reg,
							"tanggal" => date("Y-m-d",strtotime($data->tanggal))." ".date("H:i:s",strtotime($data->jam)),
							"petugas" => "PERAWAT",
							"jenis" => "ASSESMENT PERAWAT",
							"soap" => $soap,
							"id_dokter" => $data->pemberi,
							"nama_dokter" => $data->nama_perawat,
							"dpjp" => $dpjp,
							"nama_dpjp" => $nama_dpjp
							);
		    }
		}
		$n = $this->db->get_where("cppt",["no_reg"=>$no_reg]);
		if ($n->num_rows()>0) $this->db->delete("cppt",["no_reg"=>$no_reg]);
		$this->db->insert_batch("cppt",$hasil);
		$this->db->order_by("tanggal");
		$n = $this->db->get_where("cppt",["no_reg"=>$no_reg,"date(tanggal)>="=>date("Y-m-d",strtotime($tgl1)),"date(tanggal)<="=>date("Y-m-d",strtotime($tgl2))]);
		return $n;
	}
	function viewcppt_ralan($no_rm,$tgl1,$tgl2){
		$dok = $this->getdokterarray();
		$gz = $this->getpetugasgiziarray();
		$tdk = $this->gettindakanarray();
		$c = $this->getcppt_ralan($no_rm,$tgl1,$tgl2);
		$hasil = array();
		foreach ($c->result() as $key) {
			$trp = $this->getterapiarray_ralan($key->no_reg);
			$tanggal = date("Y-m-d H:i:s",strtotime($key->tanggal_masuk." ".$key->jam_masuk));
			$jenis = "VISIT";
			$soap = "";
			$pem = explode(",",$key->pemeriksaan_fisik);
			$kelainan = explode("|",$key->kelainan);
			$ada = 0;
			for ($i=0;$i<=10;$i++){
			    if (!$pem[$i]){
			        $ada = 1;
			    }
			}
			if ($key->a!="" || $key->p!="" || $key->tindakan_radiologi!="" || $key->tindakan_lab!="" || $key->tindakan_penunjang!=""){
				$soap .= "    S : </strong>".$key->s."<br>";
				$soap .= "    O : </strong>".$key->o."<br>";
				$soap .= "<table>";
				$soap .= "  <tr>";
				$soap .= "      <td>".($key->td=="" ? "" : "<b>TD Kiri</b> : ".$key->td." mmHg&nbsp;&nbsp;");
				$soap .= "      ".($key->td2=="" ? "" : "<b>TD Kanan</b> : ".$key->td2." mmHg&nbsp;&nbsp;");
				$soap .= "      ".($key->nadi=="" ? "" : "<b>Nadi</b> : ".$key->nadi." x/ mnt&nbsp;&nbsp;");
				$soap .= "      ".($key->respirasi=="" ? "" : "<b>Respirasi</b> : ".$key->respirasi." x/ mnt&nbsp;&nbsp;");
				$soap .= "      ".($key->suhu=="" ? "" : "<b>Suhu</b> : ".$key->suhu." °C&nbsp;&nbsp;");
				$soap .= "      ".($key->spo2=="" ? "" : "<b>SPo2</b> : ".$key->spo2)."</td>";
				$soap .= "  </tr>"; 
				$soap .= "<tr>";
				$soap .= "    <td>".($key->bb=="" ? "" : "<b>BB</b> : ".$key->bb." kg&nbsp;&nbsp;");
				$soap .= "    ".($key->tb=="" ? "" : "<b>TB</b> : ".$key->tb." cm&nbsp;&nbsp;")."</td>";
				$soap .= "</tr>";
				$soap .= "</table>";
				if ($ada==1) {
					$soap .= "<table width='100%'>";
					$soap .= "    <tr>";
					$soap .= "        <th>Pemeriksaan</th>";
					$soap .= "        <th>Kelainan</th>";
					$soap .= "    </tr>";
					if ($pem[0]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td width=200px>Kepala</td>";
						$soap .= "    <td>".(isset($kelainan[0]) ? ($pem[0] == "1" ? "" : $kelainan[0]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[1]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>Mata</td>";
						$soap .= "    <td>".(isset($kelainan[1]) ? ($pem[1] == "1" ? "" : $kelainan[1]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[2]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>THT</td>";
						$soap .= "    <td>".(isset($kelainan[2]) ? ($pem[2] == "1" ? "" : $kelainan[2]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[3]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>Gigi Mulut</td>";
						$soap .= "    <td>".(isset($kelainan[3]) ? ($pem[3] == "1" ? "" : $kelainan[3]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[4]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>Leher</td>";
						$soap .= "    <td>".(isset($kelainan[4]) ? ($pem[4] == "1" ? "" : $kelainan[4]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[5]!="1"){
						echo "<tr>";
						$soap .= "    <td>Thoraks</td>";
						$soap .= "    <td>".(isset($kelainan[5]) ? ($pem[5] == "1" ? "" : $kelainan[5]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[6]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>Abdomen</td>";
						$soap .= "    <td>".(isset($kelainan[6]) ? ($pem[6] == "1" ? "" : $kelainan[6]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[7]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>Ekstremitas Atas</td>";
						$soap .= "    <td>".(isset($kelainan[7]) ? ($pem[7] == "1" ? "" : $kelainan[7]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[8]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>Ekstremitas Bawah</td>";
						$soap .= "    <td>".(isset($kelainan[8]) ? ($pem[8] == "1" ? "" : $kelainan[8]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[9]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>Genitalia</td>";
						$soap .= "    <td>".(isset($kelainan[9]) ? ($pem[9] == "1" ? "" : $kelainan[9]) : '')."</td>";
						$soap .= "</tr>";
					}
					if ($pem[10]!="1"){
						$soap .= "<tr>";
						$soap .= "    <td>Anus</td>";
						$soap .= "    <td>".(isset($kelainan[10]) ? ($pem[10] == "1" ? "" : $kelainan[10]) : '')."</td>";
						$soap .= "</tr>";
					}
					$soap .= "</table>";
				}
				$soap .= "<br>";
				$soap .= "    A : </strong>".$key->a."<br>";
			    $soap .= "    P : </strong>".$key->p."<br>";
			    if (count($trp[$key->dokter_poli][$key->tanggal_masuk])>0){
			        $soap .= "<table class='table'>";
			        $soap .= "<tr>";
			        $soap .= "    <th width='50' class='text-center'>No</th>";
			        $soap .= "    <th>Nama Obat</th>";
			        $soap .= "    <th width='150'>Aturan Pakai</th>";
			        $soap .= "    <th class='text-center'>Qty</th>";
			        $soap .= "</tr>";
			        $n = 1;
			        foreach ($trp[$key->dokter_poli][$key->tanggal_masuk] as $data) {
			            $soap .= "<tr id='data'>";
			            $soap .= "<td class='text-center'>".($n++)."</td>";
			            $soap .= "<td>".$data->nama_obat."</td>";
			            $soap .= "<td>".$data->aturan."</td>";
			            $soap .= "<td class='text-center'>".$data->qty." ".$data->satuan."</td>";
			            $soap .= "</tr>";
			        }
			        $soap .= "</table>";
			    }
			    if ($key->tindakan_radiologi!="" || $key->tindakan_lab!="" || $key->penunjang!="") {
			        $soap .= "    <strong>Pemeriksaan Penunjang</strong>";
			        $soap .= "    <table class='table'>";
			        $soap .= "       <tr>";
			        $soap .= "           <td><b>Radiologi</b></td>";
			        $soap .= "           <td><b>Lab</b></td>";
			        $soap .= "           <td><b>Lain</b></td>";
			        $soap .= "       </tr>";
			        $n = 1;
			        if ($key->tindakan_radiologi!=""){
			            $rad = explode(",", $key->tindakan_radiologi);
			            $soap .= "<tr id='data'>";
			            $soap .= "<td valign='top'><ol style='padding-left:30px'>";
			            if (is_array($rad)){
							foreach ($rad as $key1 => $value) {
			    				if ($value!="")
			    					$soap .= "<li>".$tdk["radiologi"][$value]."</li>";
							}
			            } else {
							$soap .= "<li>".$tdk["radiologi"][$key->tindakan_radiologi]."</li>";
			            }
			            $soap .= "</ol></td>";
			        }
			        if ($key->tindakan_lab!=""){
			            $lab = explode(",", $key->tindakan_lab);
			            $soap .= "<td valign='top'><ol style='padding-left:30px'>";
			            if (is_array($lab)){
							foreach ($lab as $key1 => $value) {
							    if ($value!="")
							    	$soap .= "<li>".$tdk["lab"][$value]."</li>";
							}
			            } else {
							$soap .= "<li>".$tdk["lab"][$key->tindakan_lab]."</li>";
			            }      
			            $soap .= "</ol></td>";
			        }
			        if ($key->penunjang!=""){
			            $penunjang = explode(",", $key->penunjang);
			            $soap .= "<td valign='top'><ol style='padding-left:30px'>";
			            if (is_array($penunjang)){
							foreach ($penunjang as $key1 => $value) {
							    if ($value!="")
							    	$soap .= "<li>".$tdk["penunjang"][$value]."</li>";
							}
			            } else {
							$soap .= "<li>".$tdk["penunjang"][$key->penunjang]."</li>";
			            }  
			            $soap .= "</ol></td>";
			        }
			        $soap .= "</tr>";
			        $soap .= "</table>";
			    }
			    $id_dokter = $key->dokter_poli;
			    $nama_dokter = $dok[$key->dokter_poli];
			    $nama_dpjp = $dok[$key->dokter_poli];
			    $hasil[] = array(
						"no_reg" => $key->no_reg,
						"no_rm" => $no_rm,
						"tanggal" => $tanggal,
						"petugas" => "DOKTER",
						"jenis" => $jenis,
						"soap" => $soap,
						"id_dokter" => $id_dokter,
						"nama_dokter" => $nama_dokter,
						"dpjp" => $key->dokter_poli,
						"nama_dpjp" => $nama_dpjp
					  );
			}
			if ($this->session->userdata("full")==1 || $this->session->userdata("full")==""){
				$this->db->select("k.jam_radiologi as tanggal,e.hasil_pemeriksaan,k.kode_petugas,k.dokter_pengirim");
				$this->db->join("kasir k","k.no_reg=e.no_reg and k.pemeriksaan=e.pemeriksaan and k.kode_tarif=e.id_tindakan","inner");
				$q = $this->db->get_where("ekspertisi e",["e.no_reg"=>$key->no_reg]);
				foreach($q->result() as $data){
					$id_dokter = $data->kode_petugas;
					$nama_dokter = $dok[$data->kode_petugas];
					$nama_dpjp = $dok[$data->dokter_pengirim];
					$hasil[] = array(
							"no_reg" => $key->no_reg,
							"no_rm" => $no_rm,
							"tanggal" => $data->tanggal,
							"petugas" => "RADIOLOGI",
							"jenis" => "EKSPERTISI RADIOLOGI",
							"soap" => $data->hasil_pemeriksaan,
							"id_dokter" => $id_dokter,
							"nama_dokter" => $nama_dokter,
							"dpjp" => $data->dokter_pengirim,
							"nama_dpjp" => $nama_dpjp
						);
				}
			}
			if ($this->session->userdata("full")==1 || $this->session->userdata("full")==""){
				$this->db->select("date(p.tanggal) as tanggal,e.hasil_pemeriksaan,k.kode_petugas,k.dokter_pengirim");
				$this->db->join("kasir k","k.no_reg=e.no_reg and k.kode_tarif=e.kode_tindakan","inner");
				$this->db->join("pasien_ralan p","p.no_reg=e.no_reg","inner");
				$q = $this->db->get_where("ekspertisi_pa e",["e.no_reg"=>$key->no_reg]);
				foreach($q->result() as $data){
					$id_dokter = $data->kode_petugas;
					$nama_dokter = $dok[$data->kode_petugas];
					$nama_dpjp = $dok[$data->dokter_pengirim];
					$hasil[] = array(
							"no_reg" => $key->no_reg,
							"no_rm" => $no_rm,
							"tanggal" => $data->tanggal,
							"petugas" => "PATOLOGI ANATOMI",
							"jenis" => "EKSPERTISI PATOLOGI ANATOMI",
							"soap" => $data->hasil_pemeriksaan,
							"id_dokter" => $id_dokter,
							"nama_dokter" => $nama_dokter,
							"dpjp" => $data->dokter_pengirim,
							"nama_dpjp" => $nama_dpjp
						);
				}
				if ($this->session->userdata("full")==1 || $this->session->userdata("full")==""){
					$this->db->select("k.terima_lab,p.tanggal,k.kode_petugas,k.dokter_pengirim");
					$this->db->join("kasir k","k.no_reg=e.no_reg and k.kode_tarif=e.kode_tindakan","inner");
					$this->db->join("pasien_ralan p","p.no_reg=e.no_reg","inner");
					$this->db->order_by("p.tanggal");
					$this->db->group_by("p.tanggal");
					$q = $this->db->get_where("ekspertisi_lab e",["p.no_reg"=>$key->no_reg]);
					foreach($q->result() as $data){
						$this->db->select("p.tanggal,l.nama,e.hasil,k.kode_petugas,t.nama_tindakan,l.satuan");
						$this->db->join("kasir k","k.no_reg=e.no_reg and k.kode_tarif=e.kode_tindakan","inner");
						$this->db->join("tarif_lab t","t.kode_tindakan=k.kode_tarif","inner");
						$this->db->join("pasien_ralan p","p.no_reg=e.no_reg","inner");
						$this->db->join("lab_normal l","l.kode_tindakan=t.kode_tindakan and l.kode=e.kode_labnormal","inner");
						$s = $this->db->get_where("ekspertisi_lab e",["p.no_reg"=>$key->no_reg,"date(p.tanggal)"=>date("Y-m-d",strtotime($data->tanggal))]);
						$soap = "<table>";
						$soap .= "<tr><td colspan='2'>Pemeriksaan ke-1</td></tr>";
						foreach($s->result() as $row){
							$soap .= "<tr>";
							$soap .= "<td>".$row->nama."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
							$soap .= "<td>".$row->hasil." ".$row->satuan."</td>";
							$soap .= "</tr>";
						}
						$soap .= "</table>";
						$id_dokter = $data->kode_petugas;
						$nama_dokter = $dok[$data->kode_petugas];
						$nama_dpjp = $dok[$data->dokter_pengirim];
						$hasil[] = array(
								"no_reg" => $key->no_reg,
								"no_rm" => $no_rm,
								"tanggal" => $data->tanggal,
								"petugas" => "LABOTARIUM",
								"jenis" => "EKSPERTISI LAB",
								"soap" => $soap,
								"id_dokter" => $kode_petugas,
								"nama_dokter" => $nama_dokter,
								"dpjp" => $data->dokter_pengirim,
								"nama_dpjp" => $nama_dpjp
							);
					}
				}
				$this->db->join("kasir k","k.no_reg=e.no_reg and k.kode_tarif=e.kode_tindakan","inner");
				$q = $this->db->get_where("ekspertisi_gizi e",["e.no_reg"=>$key->no_reg]);
				foreach($q->result() as $data){
					$this->db->select("a.nama,e.hasil_pemeriksaan,a.jenis");
					$this->db->join("asuhan_gizi a","a.kode=e.kode_asuhan","inner");
					$this->db->order_by("a.urutan,e.kode_asuhan");
					$s = $this->db->get_where("ekspertisi_gizi e",["e.no_reg"=>$key->no_reg,"e.pemeriksaan"=>$data->pemeriksaan]);
					$soap = "<table width='100%'>";
					$jenis = "";
					foreach($s->result() as $row){
						$soap .= "<tr>";
						$soap .= "<td width='30%'>&nbsp;&nbsp;&nbsp;".$row->nama."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
						$soap .= "<td width='70%'>".$row->hasil_pemeriksaan."</td>";
						$soap .= "</tr>";
						$jenis = $row->jenis;
					}
					$soap .= "</table>";
					$id_dokter = $data->kode_petugas;
					$nama_dokter = $dok[$data->kode_petugas];
					$nama_dpjp = $dok[$data->dokter_pengirim];
					$hasil[] = array(
								"no_reg" => $key->no_reg,
								"no_rm" => $no_rm,
								"tanggal" => date("Y-m-d",strtotime($data->tanggal))." ".date("H:i:s",strtotime($data->jam)),
								"petugas" => "PETUGAS GIZI",
								"jenis" => "EKSPERTISI GIZI",
								"soap" => $soap,
								"id_dokter" => $id_dokter,
								"nama_dokter" => $nama_dokter,
								"dpjp" => $data->dokter_pengirim,
								"nama_dpjp" => $nama_dpjp
							);
				}
				$this->db->select("a.*,p.nama_perawat,pr.dokter_poli as dpjp");
				$this->db->join("perawat p","p.id_perawat=a.pemberi","left");
				$this->db->join("pasien_ralan pr","pr.no_reg=a.no_reg","inner");
				$this->db->group_by("a.shift");
				$this->db->order_by("a.id");
				$q = $this->db->get_where("assesmen_perawat a",["a.no_reg"=>$key->no_reg]);
				foreach ($q->result() as $data) {
					$dpjp = $data->dpjp;
					if ($data->shift=="igd" || $data->shift=="terimapasien"){
						if ($data->shift=="igd"){
							$this->db->select("t.petugas_igd,p.nama_perawat");
							$this->db->join("perawat p","p.id_perawat=t.petugas_igd","left");
							$p = $this->db->get_where("pasien_triage t",["no_reg"=>$key->no_reg]);
							$pr = $p->row();
							$id_perawat = $pr->petugas_igd;
							$nama_perawat = $pr->nama_perawat;
						} else {
							$id_perawat = $data->pemberi;
							$nama_perawat = $data->nama_perawat;
						}
						$soap  = "<table width='100%'>";
						$soap .= "<tr style='vertical-align:top'>";
						$soap .= "<td>";
						$soap .= "S : <br>".$data->s."<br>";
						$soap .= "O : <br>".$data->o;
						$soap .= "<div class='row'>";
						$soap .= "<div class='col-md-6'>T : ".($data->td=="" ? $data->td2 : $data->td)." mmHg</div>";
						$soap .= "<div class='col-md-6'>R : ".$data->respirasi." x/ mnt</div>";
						$soap .= "<div class='col-md-6'>N : ".$data->nadi." x/ mnt</div>";
						$soap .= "<div class='col-md-6'>S : ".$data->suhu." °C</div>"; 
						$soap .= "<div class='col-md-12'>O2 Saturasi : ".$data->spo2."</div>"; 
						$soap .= "</div><br>";
						$soap .= "A : <br>".$data->a."<br>";
						$soap .= "P : <br>".$data->p;
						$soap .= "</td>";
						$soap .= "</tr>";
						$soap .= "</table>";
						$nama_dpjp = $dok[$dpjp];
						$hasil[] = array(
									"no_reg" => $key->no_reg,
									"no_rm" => $no_rm,
									"tanggal" => date("Y-m-d",strtotime($data->tanggal))." ".date("H:i:s",strtotime($data->jam)),
									"petugas" => "PERAWAT",
									"jenis" => "ASSESMENT PERAWAT",
									"soap" => $soap,
									"id_dokter" => $id_perawat,
									"nama_dokter" => $nama_perawat,
									"dpjp" => $dpjp,
									"nama_dpjp" => $nama_dpjp
									);
					}
				}
			}
		}
		$n = $this->db->get_where("cppt",["no_rm"=>$no_rm]);
		if ($n->num_rows()>0) $this->db->delete("cppt",["no_rm"=>$no_rm]);
		if (count($hasil)>0) $this->db->insert_batch("cppt",$hasil);
		$this->db->order_by("tanggal");
		$n = $this->db->get_where("cppt",["no_rm"=>$no_rm,"date(tanggal)>="=>date("Y-m-d",strtotime($tgl1)),"date(tanggal)<="=>date("Y-m-d",strtotime($tgl2))]);
		return $n;
	}
}
?>