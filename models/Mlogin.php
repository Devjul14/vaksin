<?php
	class Mlogin extends CI_Model{
		function __construct(){
			parent::__construct();
		}
		function ceklogin($username,$password){
			$sql = "select a.*,b.id as idstatus,b.indeks,b.status_user,b.controller from user a 
					inner join status_user b on(b.id=a.status_user)
					where nip='".$username."' and pwd=md5('".$password."')";
            $query = $this->db->query($sql);
            $row = $query->row();
            if ($query->num_rows() > 0){
                $userdata = array (
                        'username' => $username,
                        'password' => $password,
                        'idstatus' => $row->idstatus,
						'status_user' => $row->status_user,
					    'id_layanan' => $row->id_layanan,
					    'id_puskesmas' => $row->id_puskesmas,
                        'nama_user' => $row->nama_user,
						'controller' => $row->controller,
						'ind' => $row->indeks
                        );
                $query->free_result();
                $this->session->set_userdata($userdata);
                return TRUE;
            } else return FALSE;
		}
		function getarea(){
			$q = $this->db->get("area");
			return $q->num_rows();
		}
		function getpelanggan(){
			$q = $this->db->get("anggota");
			return $q->num_rows();
		}
	}
?>