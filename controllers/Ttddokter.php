<?php
class Ttddokter extends CI_Controller{
    function __construct(){
        parent::__construct();
    }
    function index(){
        echo "";
    }
    function getttdpasien($no_reg){
        $q = $this->db->get_where("skrining_vaksin",["no_reg"=>$no_reg]);
        $image = "data:image/gif;base64,".$q->row()->ttd;
        echo "<img src='".$image."' alt='Product Image' class='img-thumbnail'>";
    }
    function getttdpetugas($id){
        $q = $this->db->get_where("perawat",["id_perawat"=>$id]);
        $image = "data:image/gif;base64,".$q->row()->ttd;
        echo "<img src='".$image."' alt='Product Image' class='img-thumbnail'>";
    }
}
?>
