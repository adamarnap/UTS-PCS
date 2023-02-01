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

    // FUNCTION UNTUK MENAMPILKAN DATA (GET)


    function index_get(){
        //panggil token check
        $this->token_check();

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

    
    //FUNCTION UNTUK MENAMBAH DATA (POST)
    function index_post(){
        //panggil token check
       $this->token_check();

        //validasi jika inputan kosong / formatnya tidak sesuai
        $validasi_message = [];


        //jika usernamekosng
        if($this->post('username') == ''){
            array_push($validasi_message,'Email (username) tidak boleh kosong !');
        }

        //jika format username (email)  tidak sesuai
        if($this->post('username') != '' && !filter_var($this->post('username'),FILTER_VALIDATE_EMAIL)){
            array_push($validasi_message,'Email tidak sah !');
        }

        //jika kolom nama kosong
        if($this->post('nama') == ''){
            array_push($validasi_message,'Nama tidak boleh kosong');
        }

        //jika kolom level kosong
        if($this->post('level') == ''){
            array_push($validasi_message,'Level tidak boleh kosong');
        }

        //jika kolom Password kosong
        if($this->post('password') == ''){
            array_push($validasi_message,'Password tidak boleh kosong');
        }

        //menampilkan validasi
        if(count($validasi_message) > 0){
            $output = array(
                'success' => false,
                'pesan' => 'Input data gagal, data tidak benar',
                'data' => $validasi_message
            );
            $this->response($output,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //menambah data (POST)
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


    //FUNCTION UNTUK MENGUPDATE DATA

    function index_put(){

        //panggil token check
       $this->token_check();


        //validasi jika inputan kosong / formatnya tidak sesuai
        $validasi_message = [];


        //jika usernamekosng
        if($this->put('username') == ''){
            array_push($validasi_message,'Email (username) tidak boleh kosong !');
        }

        //jika format username (email)  tidak sesuai
        if($this->put('username') != '' && !filter_var($this->put('username'),FILTER_VALIDATE_EMAIL)){
            array_push($validasi_message,'Email tidak sah !');
        }

        //jika kolom nama kosong
        if($this->put('nama') == ''){
            array_push($validasi_message,'Nama tidak boleh kosong');
        }

        //jika kolom level kosong
        if($this->put('level') == ''){
            array_push($validasi_message,'Level tidak boleh kosong');
        }

        //jika kolom Password kosong
        if($this->put('password') == ''){
            array_push($validasi_message,'Password tidak boleh kosong');
        }

        //menampilkan validasi
        if(count($validasi_message) > 0){
            $output = array(
                'success' => false,
                'pesan' => 'Update data gagal, data tidak benar',
                'data' => $validasi_message
            );
            $this->response($output,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //data yang akan diubah
        $data = array(
            'nama'  => $this->put('nama'),
            'level' =>$this->put('level'),
            'password' => $this->put('password')
        );

        //panggil function update pengguna dari model
        $result = $this -> pengguna_model->updatePengguna($data, $this->put('username') );

        //response
        $output = array(
            'success' => true,
            'pesan' => 'Data berhasil di ubah',
            'data' => array( 
                'pengguna' => $result
            )
        );
        $this->response($output,REST_Controller::HTTP_OK);

    }

    // FUNCTION UNTUK MENGHAPUS DATA


    function index_delete(){

        //panggil token check
      $this->token_check();

        $validasi_message = [];


        //jika usernamekosng
        if($this->delete('username') == ''){
            array_push($validasi_message,'Email (username) tidak boleh kosong !');
        }

        //jika format username (email)  tidak sesuai
        if($this->delete('username') != '' && !filter_var($this->delete('username'),FILTER_VALIDATE_EMAIL)){
            array_push($validasi_message,'Email tidak sah !');
        }

        //menampilkan validasi
        if(count($validasi_message) > 0){
            $output = array(
                'success' => false,
                'pesan' => 'Hapus data gagal, email/username data tidak benar',
                'data' => $validasi_message
            );
            $this->response($output,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }


        //Panggil function delete pengguna dari model
        $result = $this->pengguna_model->deletePengguna($this->delete('username'));

        //respons
        if(empty($result)){
            $output = array(
                'success' => false,
                'pesan' => 'Hapus data gagal, email/username data tidak ditemukan',
                'data' => null
            );
        }else{
            $output = array(
                'success' => true,
                'pesan' => 'Hapus data berhasil',
                'data' => array(
                    'pengguna' => $result
                )
            );
        }
        $this->response($output,REST_Controller::HTTP_OK);
    }



    //FUNCTION UNTUK LOGIN
    function login_post(){
        //isi value parameter username dan password
        $username = $this->post('username');
        $password = $this->post('password');

        //Load model ->login function
        $data = $this->pengguna_model->login($username, $password);


        //respon
        if(empty($data)){
            $output = array(
                'success' => false,
                'pesan' => 'username / password salah !',
                'data' => null,
                'error_code' => 1308
            );
            $this->response($output,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }else{//jika berhasil login maka

            //Instance ke class jwt di (helpers/jwt_helper)
            $jwt = new JWT();

            //deklarasi secret key
            $secret_key = 'ffjgbj4uJhJBFUgeujnrnk';

            //get date now (curent time) --> mengambil waktu saat ini untuk melakukan login agar waktu login terbatas
            $date = new DateTime();

            //set Payload -->  untuk membatasi waktu saat sudah login
            $payload = array(
                'username' => $data['username'],
                'name' => $data ['nama'],
                'user type' => $data['level'],
                'login_time' =>$date->getTimeStamp(),
                'experied_time' => $date->getTimeStamp() + 1800
            );

            //proses Encode 
            $result = array(
                'success' => true,
                'pesan' => 'Login Berhasil !',
                'data' => $data,
                'token' => $jwt->encode($payload, $secret_key) //berfungsi untuk meng ENCODE menjadi token
            );
            $this->response($result,REST_Controller::HTTP_OK);
        }
    }


    //FUNCTION CEK TOKEM --> berfungsi untuk mengambil token yang berasal dari header bagian autorize
    function token_check(){
        try{
            $token = $this->input->get_request_header('Authorization');

            if(!empty($token)){
                $token = explode(' ', $token)[1];
            }

            

            $jwt = new JWT(); //instance mengambil dari class jwt yang ada di helper/jwt_helper

            //panggil deklarasi secret key
            $secret_key = 'ffjgbj4uJhJBFUgeujnrnk';

            $token_decode = $jwt->decode($token, $secret_key);

        }catch(Exception $e){
            $result = array(
                'success' =>false,
                'pesan' => 'token tidak cocok !',
                'data' => null,
                'error_code' => 1204
            );
            $this->response($result, REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }
    }
} 