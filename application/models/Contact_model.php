<?php

class Contact_model extends CI_Model {

    public function addMessage($data) {
        return $this->db->insert('contact_table', $data);
    }

    public function getMessages() {
        return $this->db
            ->order_by('submit_time', 'desc')
            ->get('contact_table');
    }

    public function getMessage($message_id) {
        return $this->db
            ->get_where('contact_table', array('message_id' => $message_id));
    }

    public function getNewMessagesCount() {
        return $this->db->get_where('contact_table', array("flag" => 0))->num_rows();
    }

    public function readMessage($message_id) {
        return $this->db->where('message_id', $message_id)->update('contact_table', array('flag' => 1));
    }
}