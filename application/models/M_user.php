<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_user extends CI_Model{

    public function __construct(){
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
    }

    public function saveUser(){
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        //from input
        $nama       = trim($this->input->post('nama','true'));
        $email      = trim($this->input->post('email','true'));
        $username   = trim($this->input->post('username','true'));
        $password   = md5(trim($this->input->post('password','true')));
        $level      = trim($this->input->post('level','true'));

        //check data
        $checkData = $this->db->where('email',$email)
                              ->or_where('username',$username)
                              ->get('user')
                              ->num_rows();

        $this->db->trans_begin();
        $this->db->insert('user',[
            'nama'       => $nama,
            'email'      => $email,
            'username'   => $username,
            'password'   => $password,
            'level'      => $level,
            'created_at' => $created_at,
			'updated_at' => $updated_at,
        ]);
        
        if($this->db->trans_status() === FALSE || $checkData > 0):
            $this->db->trans_rollback();
            if($checkData > 0):
                $txt    =  'Username atau email sudah terdaftar';
            else:
                $txt    = 'Terjadi kesalahan saat menyimpan data';
            endif;
            $msg = ['msg' => $txt,'status'=>FALSE];
		else:
            $msg = ['status'=>TRUE];
			$this->db->trans_commit();
        endif;
        return $msg;
    }

    public function updateUser($data){
        $created_at = date('Y-m-d H:i:s');
        $updated_at = ['updated_at'=>date('Y-m-d H:i:s')];

        $datas      = array_merge($data,$updated_at);

        //check data
        $checkData  = $this->db->where('id',$data['id'])
                              ->get('user')
                              ->num_rows();

        $this->db->trans_begin();
        $this->db->where(['id'=>$data['id']])
                ->update('user',$datas);
        if($this->db->trans_status() === FALSE || $checkData <= 0):
			$this->db->trans_rollback();
			if($checkData <= 0):
                $txt    =  'Data tidak ditemukan';
            else:
                $txt    = 'Terjadi kesalahan saat menyimpan data';
            endif;
            $msg = ['msg' => $txt,'status'=>FALSE];
		else:
            $this->db->trans_commit();
            $msg = ['status'=>TRUE];
        endif;
        return $msg;
    }

    public function viewUser(){
        return $this->db->order_by('id','desc')
                        ->get('user');
    }

    public function deleteUser($id){
        $checkData = $this->db->get_where('user',['id'=>$id])->num_rows();
        
        $this->db->trans_begin();
        $this->db->delete('user',['id'=>$id]);
        if($this->db->trans_status() === FALSE && $checkData == 0){
			$this->db->trans_rollback();
			return FALSE;
		}else{
			$this->db->trans_commit();
			return TRUE;
		}
    }

    public function getUserById($id){
        return $this->db->get_where('user',['id'=>$id]);
    }
}