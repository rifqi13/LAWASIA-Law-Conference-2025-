<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sponsorship extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
        $this->load->model('Register_model');
        $this->load->library('form_validation');
        $this->load->library('upload');        
    }
    
    public function index() {
        // Data paket sponsorship
        $data['packages'] = array(
            array(
                'id' => 1,
                'name' => 'GOLD',
                'price' => 'Rp100.000.000,-',
                'available' => true
            ),
            array(
                'id' => 2,
                'name' => 'SILVER',
                'price' => 'Rp80.000.000,-',
                'available' => true
            ),
            array(
                'id' => 3,
                'name' => 'SESSION',
                'price' => 'Rp55.000.000,-',
                'available' => true
            ),
            array(
                'id' => 4,
                'name' => 'BOOTH',
                'price' => 'Rp25.000.000,-',
                'available' => true
            )
        );
        
        $this->load->view('template/header');
        $this->load->view('sponsorship', $data);
        $this->load->view('template/footer');
    }


    public function select($package_id) {
        // Dapatkan data paket berdasarkan ID
        $packages = array(
            1 => array('name' => 'GOLD', 'price' => 'Rp100.000.000,-', 'equiv' => 'or equivalent to US$6,150'),
            2 => array('name' => 'SILVER', 'price' => 'Rp80.000.000,-', 'equiv' => 'or equivalent to US$4,900'),
            3 => array('name' => 'SESSION', 'price' => 'Rp55.000.000,-', 'equiv' => 'or equivalent to US$3,375'),
            4 => array('name' => 'BOOTH', 'price' => 'Rp25.000.000,-', 'equiv' => 'or equivalent to US$1,530')
        );
        
        if (!isset($packages[$package_id])) {
            redirect('sponsorship');
        }
        
        // Simpan pilihan paket di session
        $this->session->set_userdata('selected_package', $packages[$package_id]);
        
        $data['country'] = $this->Register_model->getCountry();

        $data['package'] = $this->session->userdata('selected_package');
        
        $this->form_validation->set_rules('name', 'Contact Name', 'required');
        $this->form_validation->set_rules('position', 'Position', 'required');
        $this->form_validation->set_rules('organisation', 'Organisation', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State/Province', 'required');
        $this->form_validation->set_rules('postcode', 'Postcode', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('email', 'Contact Email Address', 'required|valid_email');
        $this->form_validation->set_rules('contact_email', 'Contact Email Address', 'valid_email');
        $this->form_validation->set_rules('telephone', 'Telephone', 'required');
        $this->form_validation->set_rules('website', 'Website', 'required');
        $this->form_validation->set_rules('logo', 'Logo', 'uploaded[logo]');
        $this->form_validation->set_rules('bukti', 'Transfer payment slip', 'uploaded[bukti]|max_size[bukti,1024]|is_image[bukti]|mime_in[bukti,image/jpg,image/jpeg,image/png]');

        
        
        if ($this->form_validation->run() == FALSE){

        $this->load->view('template/header');
        $this->load->view('sponsorship_form', $data);
        $this->load->view('template/footer');

        }else{

            // Konfigurasi dasar
            $config['upload_path']   = './assets/images/sponsor/';
            $config['allowed_types'] = 'jpg|png|jpeg|svg';
            
            // Proses upload file pertama (max 1MB)
            $config['max_size'] = 1024; // 1MB
            $config['file_name'] = 'bukti1_' . time();
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('bukti')) {
                $file1_data = $this->upload->data();
                $bukti = $file1_data['file_name']; // Ambil nama file yang terupload
            } else {
                $error_file1 = $this->upload->display_errors();
                $error = array('error' => $this->upload->display_errors());
                // Anda bisa menampilkan error ke view atau menyimpannya di flashdata
                $this->session->set_flashdata('error_upload', $this->upload->display_errors());
                log_message('error', 'Upload failed: ' . $this->upload->display_errors());

                // Redirect kembali ke form atau tampilkan view dengan error
                $this->load->view('template/header');
                $this->load->view('sponsorship_form', array_merge($data, $error)); // Gabungkan data country dengan error
                $this->load->view('template/footer');
                return; // Penting: Hentikan eksekusi jika upload gagal
            }
            
            // Proses upload file kedua (max 5MB)
            $config['max_size'] = 5120; // 5MB
            $config['file_name'] = 'logo' . time();
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('logo')) {
                $file2_data = $this->upload->data();
                $logo = $file2_data['file_name']; // Ambil nama file yang terupload
            } else {
                $error_file2 = $this->upload->display_errors();
                $error = array('error' => $this->upload->display_errors());
                // Anda bisa menampilkan error ke view atau menyimpannya di flashdata
                $this->session->set_flashdata('error_upload', $this->upload->display_errors());
                log_message('error', 'Upload failed: ' . $this->upload->display_errors());

                // Redirect kembali ke form atau tampilkan view dengan error
                $this->load->view('template/header');
                $this->load->view('sponsorship_form', array_merge($data, $error)); // Gabungkan data country dengan error
                $this->load->view('template/footer');
                return; // Penting: Hentikan eksekusi jika upload gagal
            }

                // --- Siapkan semua data, termasuk nama file, untuk model ---
            $insert_data = [
                "sponsor"               => $this->input->post('sponsor', TRUE),
                "name"                  => $this->input->post('name', TRUE),
                "position"              => $this->input->post('position', TRUE),
                "organisation"          => $this->input->post('organisation', TRUE),
                "address"               => $this->input->post('address', TRUE),
                "city"                  => $this->input->post('city', TRUE),
                "state"                 => $this->input->post('state', TRUE),
                "postcode"              => $this->input->post('postcode', TRUE),
                "country"               => $this->input->post('country'),
                "email"                 => $this->input->post('email', TRUE),
                "telephone"             => $this->input->post('telephone', TRUE),
                "website"               => $this->input->post('website', TRUE),
                "contact_name"          => $this->input->post('contact_name', TRUE),
                "contact_email"         => $this->input->post('contact_email', TRUE),
                "contact_telephone"     => $this->input->post('contact_telephone', TRUE),
                "logo"                  => $logo,
                "bukti"                 => $bukti // <<< Gunakan nama file yang sudah didapatkan
            ];

            // Panggil model dan teruskan array $insert_data sebagai parameter
            $this->Register_model->TambahSponsor($insert_data);
            $this->session->set_flashdata('success_message', 'been sent.');
            redirect('sponsorship'); // Redirect setelah semua proses selesai

        }

    }

	
}
