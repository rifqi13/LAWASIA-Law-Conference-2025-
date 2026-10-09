<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservasi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reservasi_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['kamars'] = $this->Reservasi_model->get_kamar_tersedia();
        $this->load->view('form_reservasi', $data);
    }

    public function proses()
{
    $this->form_validation->set_rules('nama', 'Nama', 'required');
    $this->form_validation->set_rules('kamar_id', 'Tipe Kamar', 'required');
    $this->form_validation->set_rules('checkin', 'Tanggal Check-in', 'required');
    $this->form_validation->set_rules('checkout', 'Tanggal Check-out', 'required');
    
    if ($this->form_validation->run() == FALSE) {
        $this->index();
    } else {
        // Generate session ID unik untuk pengunjung
        $session_id = $this->session->userdata('session_id');
        if (empty($session_id)) {
            $session_id = md5(uniqid(rand(), true));
            $this->session->set_userdata('session_id', $session_id);
        }
        
        $kamar_id = $this->input->post('kamar_id');
        $checkin = $this->input->post('checkin');
        $checkout = $this->input->post('checkout');
        
        // Validasi tanggal
        $min_date = '2025-08-09';
        $max_date = '2025-08-12';
        
        if (strtotime($checkin) < strtotime($min_date) || 
            strtotime($checkout) > strtotime('2025-08-13') ||
            strtotime($checkin) >= strtotime($checkout)) {
            $this->session->set_flashdata('error', 'Tanggal reservasi hanya berlaku dari 9-12 Agustus 2025');
            redirect('reservasi');
        }
        
        if ($this->Reservasi_model->cek_ketersediaan($kamar_id, $checkin, $checkout)) {
            $data = array(
                'kamar_id' => $kamar_id,
                'nama_pemesan' => $this->input->post('nama'),
                'session_id' => $session_id,
                'tanggal_checkin' => date('Y-m-d', strtotime($checkin)),
                'tanggal_checkout' => date('Y-m-d', strtotime($checkout)),
                'status' => 'confirmed',
                'is_part_of_group' => 0
            );
            
            $kode_booking = $this->Reservasi_model->buat_reservasi($data);
            
            if ($kode_booking) {
                $message = 'Reservasi berhasil! Kode Booking: <strong>'.$kode_booking.'</strong>';
                
                $is_periode_penuh = ($checkin == '2025-08-09' && in_array($checkout, ['2025-08-12', '2025-08-13']));
                
                if ($is_periode_penuh) {
                    $message .= '<br>Stok kamar berkurang 1 (periode penuh)';
                } else {
                    $kombinasi_lengkap = $this->Reservasi_model->cek_kombinasi_reservasi($session_id, $kamar_id);
                    if ($kombinasi_lengkap) {
                        $message .= '<br>Stok kamar berkurang 1 (kombinasi reservasi lengkap)';
                    }
                }
                
                $this->session->set_flashdata('success', $message);
            } else {
                $this->session->set_flashdata('error', 'Gagal membuat reservasi');
            }
        } else {
            $this->session->set_flashdata('error', 'Kamar tidak tersedia untuk tanggal yang dipilih');
        }
        
        redirect('reservasi');
    }
}

}