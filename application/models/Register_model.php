<?php


class Register_model extends CI_Model{

    public function getCountry()
    {
        return $this->db->get('country')->result_array();
    }

	public function getCountryByName($country_name) {
    $this->db->select('iso3'); // Pilih field yang diperlukan
    $this->db->from('country'); // Ganti dengan nama tabel country Anda
    $this->db->where('nicename', $country_name); // Asumsi ada kolom 'name' yang menyimpan nama negara
    $query = $this->db->get();
    
    return $query->row(); // Mengembalikan single row sebagai object
	}

    public function rules()
	{
		return [
			[
				'field' => 'title', 
				'label' => 'Title/Salutation', 
				'rules' => 'required|max_length[15]'
			],
            [
				'field' => 'name', 
				'label' => 'Full Name', 
				'rules' => 'required'
			],
            [
				'field' => 'organisation', 
				'label' => 'Organisation', 
				'rules' => 'required'
			],
            [
				'field' => 'job', 
				'label' => 'Position/Job Title', 
				'rules' => ''
			],
            [
				'field' => 'email', 
				'label' => 'Email', 
				'rules' => 'required|valid_email',
                'valid_email'     => 'Format %s not valid.'
			],
            [
				'field' => 'email-ver', 
				'label' => 'Confirmation Email', 
				'rules' => 'required|valid_email|matches[email]',
                'matches' => 'Not Matches'
			],
            [
				'field' => 'FirsName', 
				'label' => 'Preferred First Name', 
				'rules' => 'required'
			],
             [
				'field' => 'SurName', 
				'label' => 'Preferred Surname', 
				'rules' => 'required'
			],
             
             [
				'field' => 'postcode', 
				'label' => 'Postcode', 
				'rules' => 'numeric'
			],

             [
				'field' => 'requirements', 
				'label' => 'Requirements', 
				'rules' => 'alpha_numeric'
             ],
              [
				'field' => 'bukti', 
				'label' => 'Bukti Transfer', 
				'rules' => 'uploaded[bukti]|max_size[bukti,1024]|is_image[bukti]|mime_in[bukti,image/jpg,image/jpeg,image/png]',
                'uploaded'=> 'Silahkan Pilih Bukti Transfer',
                'max_size'=> 'Ukuran Gambar Terlalu Besar',
                'is_image'=> 'Yang Anda Pilih Bukan Gambar',
                'mime_in'=> 'Yang Anda Pilih Bukan Gambar'
             ]
			
		];
	}

    public function TambahDataLocal($insert_data)
    {
        $this->db->insert('registration_lokal', $insert_data);
    }

    public function TambahDataInter($insert_data)
    {
        $this->db->insert('registration_inter', $insert_data);
    }

	public function TambahSponsor($insert_data)
    {
        $this->db->insert('sponsorship', $insert_data);
    }
    
}