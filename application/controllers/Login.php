<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: youdi
 * Date: 18/01/17
 * Time: 4:18 PM
 */
class Login extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        // Load form helper library
        $this->load->helper('form');
        $this->load->helper('url');
        // Load form validation library
        $this->load->library('form_validation');

        // Load session library
        $this->load->library('session');

        // Load database
        $this->load->model('user_model');
    }

    public function index(){
        $this->load->view('login');
    }

    public function check() {

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            if(isset($this->session->userdata['logged_in'])){
                $this->load->view('index');
               // header("location:" .base_url()."welcome");
                //redirect('welcome','refresh');
            }else{
                $this->load->view('login');
            }
        } else {

                $username = $this->input->post('username');
                $password= $this->input->post('password');

            $result = $this->user_model->get_user($username,$password);

            if (count($result)>0) {

                $username = $this->input->post('username');
                //$result = $this->user_model->read_user_information($username);
                if ($result >0) {
                    $session_data = array(
                        'userID'=>$result['id'],
                        'username' => $result['name'],
                        'email' => $result['email'],
                    );
// Add user data in session

                    $this->session->set_userdata('logged_in', $session_data);
                    $this->load->view('index');
                }
            } else {
                $data = array(
                    'error_message' => 'Invalid Username or Password'
                );
                $this->load->view('login', $data);
            }
        }
    }

// Logout from admin page
    public function logout() {

// Removing session data
        $this->session->sess_destroy();
        $data['message_display'] = 'Successfully Logout';
        $this->load->view('login', $data);
    }

}