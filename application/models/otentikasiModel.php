<?php
class OtentikasiModel extends CI_Model{

    function check_login($email, $password){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('email',$email);
        $this->db->where('password', $password);
        //$this->db->status('status',0);

        $query = $this->db->get();
        
        if($query->num_rows()>0){
            return $query->result_array();
        }else{
            return "Pengguna tidak dituemukan ! ";
        }
    }

}