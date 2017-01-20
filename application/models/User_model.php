<?php

/**
 * Created by PhpStorm.
 * User: youdi
 * Date: 18/01/17
 * Time: 4:07 PM
 */
class User_model extends CI_Model
{
  public function __construct()
  {
      $this->load->database();
  }
  public function get_user($user,$pass){
      $query = $this->db->get_where('user', array('username' => $user,'password'=>$pass));
      return $query->row_array();
  }

}