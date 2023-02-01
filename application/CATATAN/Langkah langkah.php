<?php

//----------  MEMBUAT OTENTIKASI MODEL DI FOLDER MODELS
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna_model extends CI_Model{

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
            $data = $this->dbget('pengguna')
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

}


//-------------- MEMBUAT CONTROLLER PENGGUNA
<?php
defined('BASEPATH') OR exit('No direct script acess allowed');

//pemanggilan library REST_Controller
require APPPATH . 'libraries/REST_Controller.php';

class Pengguna extends REST_Controller{

    //deklarasi constructor
    function __construct(){
        parent::__construct();
        $this->load->model('pengguna_model');
    }

    // function untuk menampilkan data pengguna (GET)
    function index_get(){
        //value parameter nama
        $nama = $this->get('nama');
        //memanggil function getPengguna dari model
        $data = $this->pengguna_model->getPengguna($nama);
        
        //penambahan response terhadap nama yang kita cari
        $result = array(
            'success' => true,
            'message' => 'data ditemukan',
            'data' => array('pengguna' => $data)
        );

        //menampilkan response
        $this->response($result, REST_Controller::HTTP_OK);
 
    }

        //function untuk menambah data (POST)
    function index_post(){
        $data = array(
            'username' => $this->post('username'),
            'nama' => $this->post('nama'),
            'level' => $this->post('level'),
            'password' => $this->post('password')
        );

        //memanggil function insertPengguna dari model pengguna_model
        $result = $this->pengguna_model->insertPengguna($data);

        // penambahan response terhadap data yang kita tambahakan
        if(empty($result)){      //jika data yang ditambahkan ada yang sama maka tidak bisa di tambahkan karena data 
            $output = array(     // sudah tersedia
                'success' => false,
                'pesan' => 'data yang ditambahkan sudah tersedia !',
                'data' => null
            );            
        }else{
            $output = array(
                'success' => true,
                'pesan' => 'data berhasil di inputkan',
                'data' => array(
                    'pengguna' => $result
                )
            );
        }
        $this->response($output,REST_Controller::HTTP_OK);
    }

}

//