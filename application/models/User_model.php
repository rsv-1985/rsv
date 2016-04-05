<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_model class.
 *
 * @extends CI_Model
 */
class User_model extends Default_model {
    public $table = 'user';

    public function is_login($redirect = false){
        if($this->session->user_id){
            return $this->session->user_id;
        }else{
            if($redirect){
                redirect($redirect);
            }
            return false;
        }
    }

    public function login($email, $password){
        $this->db->where('email',$email);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $existingHashFromDb = $query->row_array()['password'];
            $isPasswordCorrect = password_verify($password, $existingHashFromDb);
            if($isPasswordCorrect){
                return $query->row_array();
            }
        }
        return false;
    }
}