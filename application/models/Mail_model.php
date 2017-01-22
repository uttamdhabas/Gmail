<?php

/**
 * Created by PhpStorm.
 * User: youdi
 * Date: 18/01/17
 * Time: 8:04 PM
 */
class Mail_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    public function get_mails($userID,$start,$type){
        $this->db->limit(50,($start-1)*10);
        $this->db->select('*');
        $this->db->from('user AS u');// I use aliasing make joins easier
        if($type=="received") {
            $this->db->join('mail AS m', 'm.sender_id = u.id', 'INNER');
            $this->db->join('mail_received AS mr', 'm.id = mr.mail_id', 'INNER');
            $where = " (m.isDeleted=0 AND m.isDraft=0  AND $userID=mr.mail_receiver)";
            $this->db->where($where);
        }
        elseif($type=="sent"){
            $this->db->join('mail_received AS mr', 'u.id = mr.mail_receiver', 'INNER');
            $this->db->join('mail AS m', 'mr.mail_id = m.id', 'INNER');
            $this->db->where('m.sender_id',$userID);
            $where = " (m.isDeleted=0 AND m.isDraft=0  AND $userID=m.sender_id)";
            $this->db->where($where);
        }
        elseif($type=="deleted"){
            $this->db->join('mail_received AS mr', 'u.id = mr.mail_receiver', 'INNER');
            $this->db->join('mail AS m', 'mr.mail_id = m.id', 'INNER');
            $where = " (m.isDeleted=1 AND m.isDraft=0 ) AND ($userID=mr.mail_receiver OR m.sender_id)";
            $this->db->where($where);
           // $this->db->where('mr.mail_receiver',$userID);
            //$this->db->where('m.isDeleted =',1);


        }
        elseif ($type=="draft"){
            $this->db->join('mail_received AS mr', 'u.id = mr.mail_receiver', 'INNER');
            $this->db->join('mail AS m', 'mr.mail_id = m.id', 'INNER');
            $this->db->where('m.sender_id',$userID);
            $where = " (m.isDeleted=0 AND m.isDraft=1  AND $userID=m.sender_id)";
            $this->db->where($where);
        }






       // $result = $this->db->get();

       // $this->db->select('mail.id,mail.subject,mail.body','mail.sentAt');
       // $this->db->from('mail');
       // $this->db->join('mail_received', 'mail.id = mail_received.mail_id');


        //$this->db->where('m.isDraft =',0);
        $this->db->order_by('m.sentAt', 'DESC');
        $query = $this->db->get();
       // $query = $this->db->get_where('mail_received', array('mail_receiver' => $userID));
        return $query->result();
    }
    public function get_mail($id,$userID){
        $this->db->select('m.id,username,name,sentAt,sender_id,body,subject,isRead,parent_id');
        $where="((m.id=$id) AND ((m.sender_id=$userID) OR (mr.mail_receiver=$userID)))";
        $this->db->where($where);
        $this->db->from('mail as m');
        $this->db->join('mail_received AS mr', 'm.id = mr.mail_id', 'INNER');
        $this->db->join('user AS u', 'u.id = m.sender_id', 'INNER');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();

    }
    public function get_thread($id,$userID){
         $temp=$this->get_mail($id,$userID);
         $mails=array($temp);
         while($temp AND $temp[0]->parent_id){
              $temp=$this->get_mail($temp[0]->parent_id,$userID);
              array_push($mails,$temp);
        }
        return $mails;
    }
    public function get_receivers($id){
        $this->db->select('u.username,u.name');
        $this->db->from('user AS u');// I use aliasing make joins easier
        $this->db->join('mail_received AS mr', 'u.id = mr.mail_receiver', 'INNER');
        $this->db->where('mr.mail_id',$id);

        $query = $this->db->get();
        return $query->result();
    }



    public function get_unreadCount($userID){
        $this->db->where('mail_received.mail_receiver',$userID);
        $this->db->where('mail_received.isRead',0);
        $this->db->from('mail_received');
        //$this->db->count_all_results();
        return ($this->db->count_all_results());
    }
    public function get_receivedCount($userID){
        $this->db->where('mail_received.mail_receiver',$userID);
        $this->db->from('mail_received');
        //$this->db->count_all_results();
        return ($this->db->count_all_results());
    }
    public function get_draftCount($userID){
        $this->db->where('mail.sender_id',$userID);
        $this->db->where('mail.isDraft',1);
        $this->db->from('mail');
        return ($this->db->count_all_results());
    }
    public function get_sentCount($userID){
        $this->db->where('mail.sender_id',$userID);
        $this->db->where('mail.isDraft',0);
        $this->db->where('mail.isDeleted',0);
        $this->db->from('mail');
        return ($this->db->count_all_results());
    }
    public function update_deleted($id){
        $this->db->set('isDeleted',1);
        $this->db->where('id',$id);
        $this->db->update('mail');

    }
    public function update_read($id,$read,$userID){
        $this->db->set('isRead',$read);
        $this->db->where('mail_id',$id);
        $this->db->where('mail_receiver',$userID);
        $this->db->update('mail_received');

    }

    public function new_mail($userID,$subject,$body,$receivers,$isDraft,$id,$parent){


        if($isDraft){
            $this->db->set('isDraft',0);
            $this->db->set('body',$body);
            $this->db->set('subject',$subject);
            $this->db->set('parent_id',$parent);
            $this->db->where('id',$id);
            $this->db->update('mail');
            $res=$this->new_receiver($id,$receivers);
            return($res);


        }
        else{
            $mail= array(
                'subject'=>$subject,
                'body'=>$body,
                'sender_id'=>$userID,
                'parent_id'=>$parent

            );
            $this->db->set($mail);

            $this->db->insert('mail');
            $id=$this->db->insert_id();
            $res=$this->new_receiver($id,$receivers);
            return($res);
        }

    }
    public function new_receiver($id,$receivers){
        $receivers = explode(',', $receivers);
        foreach($receivers as $receiver){
           // error_log(print_r($receiver, TRUE));
            $query=$this->db->get_where('user',array('username'=>$receiver));
            $receiverID=$query->result_array();
            //$receiverID=$receiverID[0]
            if(count($receiverID)>0){
                $receiverID=$receiverID[0]['id'];
                $data = array(
                    'mail_id' => $id,
                    'mail_receiver' => $receiverID,

                );
                $this->db->set($data);
                $this->db->insert('mail_received');

            }
            else{
                return FALSE;
            }
        }
        return TRUE;
    }
}