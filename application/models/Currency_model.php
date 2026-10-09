<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency_model extends CI_Model {

    private $conversion_rate = 10000; // Contoh: 1 AUD = 10,000 IDR

    public function __construct() {
        parent::__construct();
    }

    public function convert_to_idr($aud_amount) {
        return $aud_amount * $this->conversion_rate;
    }

    public function get_conversion_rate() {
        return $this->conversion_rate;
    }

    // Untuk mendapatkan rate dari API eksternal (opsional)
    public function update_conversion_rate() {
        // Contoh menggunakan API (Anda perlu mengganti dengan API nyata)
        $api_url = "https://api.exchangerate-api.com/v4/latest/AUD";
        $response = file_get_contents($api_url);
        $data = json_decode($response, true);
        
        if(isset($data['rates']['IDR'])) {
            $this->conversion_rate = $data['rates']['IDR'];
            return true;
        }
        return false;
    }
}