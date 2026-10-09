<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Method yang sudah ada
    public function save_registration($data) {
        $this->db->insert('registrants', $data);
        return $this->db->insert_id();
    }

    public function get_registration($id) {
        return $this->db->get_where('registrants', array('id' => $id))->row();
    }

    // Method yang sebelumnya hilang - inilah penyebab error
    public function get_registration_by_order_id($order_id) {
        return $this->db->get_where('registrants', array('midtrans_order_id' => $order_id))->row();
    }


    public function update_payment_status($order_id, $status, $va_number = null) {
    
    if ($va_number) {
        $data['va_number'] = $va_number;
    }
    $this->db->where('midtrans_order_id', $order_id);
    $result = $this->db->update('registrants', [
        'payment_status' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    // Log hasil update
    log_message('debug', 'Update status: '.$order_id.' to '.$status.' - Affected rows: '.$this->db->affected_rows());
    
    return $result;
    }

    public function get_registrant($id) {
        $this->db->where('id', $id);
        return $this->db->get('registrants')->row();
    }

public function count_by_category($category) {
    $this->db->where('registration_type', $category);
    $this->db->where('payment_status', 'success');
    return $this->db->count_all_results('registrants');
}

public function count_limited_categories() {
    $this->db->where_in('registration_type', ['peradi member', 'speakers/moderators', 'non-peradi member']);
    $this->db->where('payment_status', 'success');
    return $this->db->count_all_results('registrants');
}

public function count_all_limited() {
    $this->db->where_in('registration_type', ['peradi young lawyers', 'peradi member', 'speakers/moderators', 'non-peradi member']);
    $this->db->where('payment_status', 'success');
    return $this->db->count_all_results('registrants');
}




public function save_tour($data) {
        $this->db->insert('tour', $data);
        return $this->db->insert_id();
    }

    // Method yang sebelumnya hilang - inilah penyebab error
    public function get_registration_by_tour_id($order_id) {
        return $this->db->get_where('tour', array('midtrans_order_id' => $order_id))->row();
    }


    public function update_tour_status($order_id, $status, $va_number = null) {
    
    if ($va_number) {
        $data['va_number'] = $va_number;
    }
    $this->db->where('midtrans_order_id', $order_id);
    $result = $this->db->update('tour', [
        'payment_status' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    // Log hasil update
    log_message('debug', 'Update status: '.$order_id.' to '.$status.' - Affected rows: '.$this->db->affected_rows());
    
    return $result;
    }


    public function get_kamar_by_id($id) {
        return $this->db->get_where('kamar', ['id' => $id])->row();
    }

     // Hitung total harga
    public function hitung_total_harga($check_in, $check_out) {
    // Fixed price per night (example: Rp 2.8 juta)
    $harga_per_malam = 2800000;
    
    // Calculate night count
    $start = new DateTime($check_in);
    $end = new DateTime($check_out);
    $interval = $start->diff($end);
    $jumlah_malam = $interval->days;
    
    // Total = nights × fixed price
    return $jumlah_malam * $harga_per_malam;
}

    public function count_by_hotel($category) {
    $this->db->where('room_type', $category);
    $this->db->where('payment_status', 'success');
    return $this->db->count_all_results('hotel');
}

public function save_hotel($data) {
        $this->db->insert('hotel', $data);
        return $this->db->insert_id();
    }


       // Method yang sebelumnya hilang - inilah penyebab error
    public function get_registration_by_hotel_id($order_id) {
        return $this->db->get_where('hotel', array('midtrans_order_id' => $order_id))->row();
    }


    public function update_hotel_status($order_id, $status, $va_number = null) {
    
    if ($va_number) {
        $data['va_number'] = $va_number;
    }
    $this->db->where('midtrans_order_id', $order_id);
    $result = $this->db->update('hotel', [
        'payment_status' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);

}

}