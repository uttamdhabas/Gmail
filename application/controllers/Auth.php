<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;


class Auth extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('user_model');
        // $this->load->library('session');

    }

    public function index_post()
    {
        $username = $this->post('username');
        $password=$this->post('password');
        if ($username === NULL and $password==NULL) {
            if (isset($this->session->userdata['logged_in'])) {
                $res = array('auth' => 1);
                $this->response($res, REST_Controller::HTTP_OK);


            }
            else {
                $res = array('auth' => 0);
                $this->response($res, REST_Controller::HTTP_OK);
            }

        }
        else {



            $result = $this->user_model->get_user($username,$password);

            if (count($result)>0) {


                //$result = $this->user_model->read_user_information($username);
                if (count($result) >0) {
                    $session_data = array(
                        'userID'=>$result['id'],
                        'username' => $result['name'],
                        'email' => $result['email'],
                    );
// Add user data in session

                    $this->session->set_userdata('logged_in', $session_data);
                    $res = array('auth' => 1);
                    $this->response($res, REST_Controller::HTTP_OK);
                }
            } else {
                $data = array(
                    'error_message' => 'Invalid Username or Password'
                );

                $this->response($data, REST_Controller::HTTP_OK);
            }
        }

    }
    public function index_get() {

// Removing session data
        $this->session->sess_destroy();
        $data['message_display'] = 'Successfully Logout';
        $this->response($data, REST_Controller::HTTP_OK);
    }






}