<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Local extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Register_model');
        $this->load->library('form_validation');
        $this->load->helper('url'); 
        $this->load->library('upload');

    }

	public function index()
	{
        
       
		$this->load->view('template/header');
        $this->load->view('lokal');
        $this->load->view('template/footer');
	}

    
    
    
    public function form()
	{
        
        $data['country'] = $this->Register_model->getCountry();

		$rules = $this->Register_model->rules();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() == FALSE){
            $this->load->view('template/header');
            $this->load->view('form_lokal', $data);
            $this->load->view('template/footer');
        }else{

            // --- Konfigurasi Upload ---
            $config['upload_path']   = './assets/images/bukti/'; // Folder tujuan upload
            $config['allowed_types'] = 'jpg|png|jpeg'; // Tipe file yang diizinkan
            $config['max_size']      = 1024; // Ukuran maksimum file dalam KB (contoh: 2MB)
            $config['file_name']     = 'bukti_' . time(); // Nama file yang unik (opsional)

            // Inisialisasi library upload dengan konfigurasi
            $this->upload->initialize($config);

            // --- Proses Upload File ---
            if ( ! $this->upload->do_upload('bukti')){ // 'bukti' adalah nama field input file dari form
                // Jika upload gagal
                $error = array('error' => $this->upload->display_errors());
                // Anda bisa menampilkan error ke view atau menyimpannya di flashdata
                $this->session->set_flashdata('error_upload', $this->upload->display_errors());
                log_message('error', 'Upload failed: ' . $this->upload->display_errors());

                // Redirect kembali ke form atau tampilkan view dengan error
                $this->load->view('template/header');
                $this->load->view('form_lokal', array_merge($data, $error)); // Gabungkan data country dengan error
                $this->load->view('template/footer');
                return; // Penting: Hentikan eksekusi jika upload gagal
            } else {
                // Jika upload berhasil
                $upload_data = $this->upload->data();
                $file_name = $upload_data['file_name']; // Ambil nama file yang terupload

                // --- Siapkan semua data, termasuk nama file, untuk model ---
            $insert_data = [
                "title"          => $this->input->post('title', TRUE),
                "fullname"       => $this->input->post('name', TRUE),
                "organisation"   => $this->input->post('organisation', TRUE),
                "position"       => $this->input->post('job', TRUE),
                "email"          => $this->input->post('email', TRUE),
                "firsnamebadge"  => $this->input->post('FirsName', TRUE),
                "surnamebadge"   => $this->input->post('SurName', TRUE),
                "Addline1"       => $this->input->post('AddLine1', TRUE),
                "Addline2"       => $this->input->post('AddLine2', TRUE),
                "city"           => $this->input->post('city', TRUE),
                "state"          => $this->input->post('state', TRUE),
                "postcode"       => $this->input->post('postcode', TRUE),
                "country"        => $this->input->post('country'),
                "requirements"   => $this->input->post('requirements', TRUE),
                "bukti"          => $file_name // <<< Gunakan nama file yang sudah didapatkan
            ];

            // Panggil model dan teruskan array $insert_data sebagai parameter
            $this->Register_model->TambahDataLocal($insert_data);
            $this->session->set_flashdata('success_message', 'Disimpan');
            redirect('local/form'); // Redirect setelah semua proses selesai
            }

        }
	
    }


}
