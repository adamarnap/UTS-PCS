<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pengguna_model extends CI_Model{

    //deklarasi constructor
    function __construct(){
        parent::__construct();
        
        //untuk akses database
        $this->load->database();
    }

    //function untuk menampilkan data pengguna (GET) & Search by nama/username
    public function getPengguna($nama){
        if($nama==''){
            //jikka kolom nama kosong maka akan memperlihatkan semua data
            $data = $this->db->get('pengguna');
        }else{
            //jika kolom nama terisi atau mencari data dengan nama (using like)
            $this->db->like('nama', $nama);
            $this->db->or_like('username', $nama);
            $data = $this->db->get('pengguna');
        }
        return $data->result_array();
    }


        //function insert data pengguna
        public function insertPengguna($data){
            //cek apakah username yang di inputkan sudah ada atau belum
            $this->db->where('username', $data['username']);
            $check_data = $this->db->get('pengguna');
            $result = $check_data->result_array();
            
            if(empty($result)){
                //jika username belum ada maka data akan bisa di tambahkan ke table pengguna
                $this->db->insert('pengguna',$data);
            }else{
                $data = array();
            }
            return $data; 
        }
        

        //function update data pengguna
        public function updatePengguna($data, $username){  //update data akan terjadi dimana jika usernamenya terpenuhi
          $this->db->where('username',$username);// ini syaratnya jika username sudah terpenuhi maka dapat melakukan update
          $this->db->update('pengguna', $data);

          $result = $this->db->get_where('pengguna',array('username'=>$username));

          return $result->row_array();
        }


        //function hapus data pengguna
        public function deletePengguna($username){
        $result = $this->db->get_where('pengguna', array('username' => $username));
        
        //delete data
        $this->db->where('username', $username);
        $this->db->delete('pengguna');

        return $result->row_array();

        }



        //funtion login 
        public function login($username, $password){
            //cek data
            $this->db->where('username', $username);
            $this->db->where('password', $password);
            $data = $this->db->get('pengguna');

            return $data->row_array();
        }

}