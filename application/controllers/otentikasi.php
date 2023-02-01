<?php
    defined('BASEPATH') or exit('No direct script access allowed'); 
    require APPPATH . 'libraries/REST_Controller.php'; 
     

//constructor
class otentikasi extends CI_Controller{

   /* public function __construct(){
        parent::__construct();
        $this->load->model('OtentikasiModel');
    }*/

    function _construct() {
        parent::__construct();
        $this->load->database(); 
        }
    
    
    //Authorization
    public function login(){
        $jwt = new JWT();
        $jwtSecretKey = 'Secretkeycontohrestadam1';
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $result = $this->OtentikasiModel->check_login($email, $password);
        $token = $jwt->encode($result, $jwtSecretKey, 'HS256');
        echo json_encode($token);
    }


        //melihat data matakuliah
        function lihat(){ 
            $id = $this->get('user_id'); 
            if($id == ''){ 
                $user = $this->db->get('user')->result();
            }else{ 
                $this->db->where('user_id',$id); 
                $user = $this->db->get('user')->result();
            } 
            $this->response($user, REST_Controller::HTTP_OK);
        } 

    // untuk tambah data
    public function tambah(){ 
        
        $data = array( 
       
        'nama' => $this->post('nama'),
        'email' => $this->post('email'),
        'password' => $this->post('password'),
        'status' => $this->post('status'), 
        'role_id' => $this->post('role_id') 
    );  

    
    $insert = $this->db->insert('user', $data); 
                
    if($insert){ 
        $this->response($data, REST_Controller::HTTP_OK);
    }else{ 
        $this->response(array('status' => 'fail', 502));
    }

    }

    //untuk ubah data
    public function edit(){ 
        $id = $this->put('user_id'); 
        $data = array( 
        'user_id' => $this->put('user_id'), 
        'nama' => $this->put('nama'),
        'email' => $this->put('email'),
        'password' => $this->put('password'),
        'status' => $this->put('status'), 
        'role_id' => $this->put('role_id') 
    );  

    $this->db->where('user_id', $id); 
    $update = $this->db->update('user', $data); 
        
    if($update){ 
        $this->response($data, REST_Controller::HTTP_OK); 
    }else{ 
        $this->response(array('status' => 'fail', 502));
    }


    //untuk hapus data
    function hapus(){ 
        $id = $this->delete('user_id'); 
        $this->db->where('user_id', $id); 
        $delete = $this->db->delete('user'); 
        
        if($delete){  
            $this->response(array('status'=> 'success', 2011)); 
        }else{ 
            $this->response(array('status' => 'fail', 502));
        }
    }






    /*public function token(){
        $jwt = new JWT();

        $jwtSecretKey ='Secretkeycontohrestadam';
        $data = array(
            'userId' => 1,
            'email' => 'adamarnap@yahoo.com',
            'userType' => 'admin',
        );

        $token =$jwt->encode($data, $jwtSecretKey,'HS256');
        echo $token;
    }

    public function decode_token(){
        $token =$this->uri->segment(3);
        $jwt = new JWT();
        $jwtSecretKey = 'Secretkeycontohrestadam';
        $decoce_token = $jwt->decode($token, $jwtSecretKey, 'hs256');
        $token1 = $jwt->jsonEncode($decoce_token);
        echo $token1;
    }
*/
    }
}
