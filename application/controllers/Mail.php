<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;


class Mail extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('mail_model');
       // $this->load->library('session');

    }

    public function mails_get()
    {
        if (isset($this->session->userdata['logged_in'])) {
            $userID = ($this->session->userdata['logged_in']['userID']);
            $user=array('name'=>$this->session->userdata['logged_in']['username'],
                'email'=>$this->session->userdata['logged_in']['email']);

            $id = $this->get('id');
            $page=$this->get('page');
            $type=$this->get('type');
            if($type==NULL)$type='received';
            if($page==NULL or $page<1)$page=1;

            $mails=$this->mail_model->get_mails($userID,$page,$type);
            $count=array('unread'=>$this->mail_model->get_unreadCount($userID),
                'draft'=>$this->mail_model->get_draftCount($userID),
                'sent'=>$this->mail_model->get_sentCount($userID),
                'received'=>$this->mail_model->get_receivedCount($userID));
            if ($id === NULL)
            {
                // Check if the users data store contains users (in case the database result returns NULL)
                if ($mails)
                {
                    // Set the response and exit
                    $this->response(['user'=>$user,'count'=>$count,'mails'=>$mails], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
                else
                {
                    // Set the response and exit
                    $this->response(['user'=>$user,'count'=>$count,'status'=>0], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                }
            }


            else {
                $id = (int) $id;

                // Validate the id.
                if ($id <= 0)
                {
                    // Invalid id, set the response and exit.
                    $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
                }
                $mail=$mails=$this->mail_model->get_mail($userID);
                if ($mail)
                {
                    // Set the response and exit
                    $this->response($mail, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
                else
                {
                    // Set the response and exit
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No mails were found'
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }


            }

        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Access not allowed'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }


        // If the id parameter doesn't exist return all the users



        // Find and return a single record for a particular user.

    }

    public function mails_post()
    {
        if (isset($this->session->userdata['logged_in'])) {
            $userID = ($this->session->userdata['logged_in']['userID']);
            $body = $this->post('body');
            $subject = $this->post('subject');
            $receivers = $this->post('receivers');
            $isDraft = $this->post('isDraft');
            $id = $this->post('id');
            $res=$this->mail_model->new_mail($userID,$subject,$body,$receivers,$isDraft,$id);

            $this->set_response($res, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Access not allowed'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }





    }

    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}