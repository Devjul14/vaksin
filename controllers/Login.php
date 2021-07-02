<?php
	class Login extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->model('Mlogin');
			if(($this->session->userdata('username') != NULL)||($this->session->userdata('password') != NULL)){
					$this->session->sess_destroy();
			}
		}
		function index(){
			$this->load->view('vlogin');
		}
		function login_process(){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if($this->Mlogin->ceklogin($username,$password)){
				redirect($this->session->userdata('ind'),'refresh');
				// echo "Login Berhasil";
			} else {
				$message = "danger-Gagal login, Silahkan coba lagi..";
				$this->session->set_flashdata("message", $message);
				redirect('login');
				// echo "Login Gagal";
			}
		}
		function logout(){
            $sess_array = $this->session->all_userdata();
			foreach($sess_array as $key =>$val){
			   if($key!='session_id'
			      && $key!='last_activity'  
			      && $key!='ip_address'  
			      && $key!='user_agent'  
			      && $key!='RESERVER_KEY_HERE')$this->session->unset_userdata($key);
			}
            redirect('login');
    	}
	}
?>