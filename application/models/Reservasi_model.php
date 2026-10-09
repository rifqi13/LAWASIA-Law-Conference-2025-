<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservasi_model extends CI_Model {

    public function generate_kode_booking()
    {
        $prefix = 'BOOK';
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        return $prefix.$random;
    }
    public function get_kamar_tersedia()
    {
        return $this->db->get('kamar')->result();
    }
    

    

    public function buat_reservasi($data)
    {
        $this->db->trans_start();
        
        $checkin = $data['tanggal_checkin'];
        $checkout = $data['tanggal_checkout'];
        $kamar_id = $data['kamar_id'];
        $session_id = $data['session_id'];
        
        // Insert reservasi
        $data['kode_booking'] = $this->generate_kode_booking();
        $this->db->insert('reservasi', $data);
        $reservasi_id = $this->db->insert_id();
        
        // Cek kondisi pengurangan stok
        $should_reduce_stock = false;
        
        // 1. Cek periode penuh (9-12)
        if ($checkin == '2025-08-09' && in_array($checkout, ['2025-08-12', '2025-08-13'])) {
            $should_reduce_stock = true;
        }
        
        // 2. Cek kombinasi reservasi dalam session yang sama
        if (!$should_reduce_stock && !empty($session_id)) {
            $all_dates_covered = $this->cek_kombinasi_reservasi($session_id, $kamar_id);
            $should_reduce_stock = $all_dates_covered;
        }
        
        if ($should_reduce_stock) {
            $this->db->set('stok_tersedia', 'stok_tersedia-1', FALSE);
            $this->db->where('id', $kamar_id);
            $this->db->update('kamar');
            
            // Tandai reservasi yang sudah mengurangi stok
            $this->db->set('is_part_of_group', 1);
            $this->db->where('session_id', $session_id);
            $this->db->update('reservasi');
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status() ? $data['kode_booking'] : false;
    }

    private function cek_kombinasi_reservasi($session_id, $kamar_id)
    {
        $this->db->where('session_id', $session_id);
        $this->db->where('kamar_id', $kamar_id);
        $this->db->where('is_part_of_group', 0); // Hanya yang belum terhitung
        $reservations = $this->db->get('reservasi')->result();
        
        $covered_dates = [];
        foreach ($reservations as $res) {
            $start = new DateTime($res->tanggal_checkin);
            $end = new DateTime($res->tanggal_checkout);
            
            while ($start < $end) {
                $covered_dates[$start->format('Y-m-d')] = true;
                $start->modify('+1 day');
            }
        }
        
        $required_dates = ['2025-08-09', '2025-08-10', '2025-08-11', '2025-08-12'];
        foreach ($required_dates as $date) {
            if (!isset($covered_dates[$date])) {
                return false;
            }
        }
        
        return true;
    }

    public function cek_ketersediaan($kamar_id, $checkin, $checkout)
    {
        $this->db->where('kamar_id', $kamar_id);
        $this->db->where('status', 'confirmed');
        $this->db->where("(
            (tanggal_checkin <= '$checkin' AND tanggal_checkout > '$checkin') OR
            (tanggal_checkin < '$checkout' AND tanggal_checkout >= '$checkout') OR
            (tanggal_checkin >= '$checkin' AND tanggal_checkout <= '$checkout')
        )");
        $reservasi_count = $this->db->get('reservasi')->num_rows();
        
        $this->db->select('stok_tersedia');
        $this->db->where('id', $kamar_id);
        $stok = $this->db->get('kamar')->row()->stok_tersedia;
        
        return ($reservasi_count < $stok);
    }
}