<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

class Regis extends CI_Controller {

    private $idr_prices = [
        'standard' => 500000,
        'premium' => 1000000,
        'vip' => 2000000
    ];

    private $aud_prices = [
        'standard' => 50,
        'premium' => 100,
        'vip' => 200
    ];

    public function __construct() {
        parent::__construct();
        $this->load->model('Registration_model');
        $this->load->model('Currency_model');
        $this->load->library('form_validation');
        
        // Midtrans configuration
        \Midtrans\Config::$serverKey = 'SB-Mid-server-BS3VCXfr7pA0l0-dlq11_ETH';
        \Midtrans\Config::$isProduction = false; // Set true for production
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    // Halaman Registrasi IDR
    public function idr() {
        $data['title'] = 'Registration (IDR)';
        $data['currency'] = 'IDR';
        $data['prices'] = $this->idr_prices;
        $this->load->view('registration_idr', $data);
    }

    // Halaman Registrasi AUD
    public function aud() {
        $data['title'] = 'Registration (AUD)';
        $data['currency'] = 'AUD';
        $data['prices'] = $this->aud_prices;
        $data['conversion_rate'] = $this->Currency_model->get_conversion_rate();
        $this->load->view('registration_aud', $data);
    }

    // Proses Registrasi IDR
    public function process_idr() {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[registrants.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
        $this->form_validation->set_rules('registration_type', 'Registration Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->idr();
        } else {
            $registration_type = $this->input->post('registration_type');
            $amount = $this->idr_prices[$registration_type];

            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'country' => $this->input->post('country'),
                'postal_code' => $this->input->post('postal_code'),
                'registration_type' => $registration_type,
                'currency' => 'IDR',
                'original_amount' => $amount,
                'conversion_rate' => 1,
                'amount' => $amount,
                'payment_status' => 'pending'
            ];

            $registration_id = $this->Registration_model->save_registration($data);
            
            if ($registration_id) {
                $this->process_payment($registration_id, $data, $amount, 'IDR');
            } else {
                $this->session->set_flashdata('error', 'Failed to save registration data');
                redirect('registration/idr');
            }
        }
    }

    // Proses Registrasi AUD dengan Konversi ke IDR
    public function process_aud() {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[registrants.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
        $this->form_validation->set_rules('registration_type', 'Registration Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->aud();
        } else {
            $registration_type = $this->input->post('registration_type');
            $aud_amount = $this->aud_prices[$registration_type];
            $conversion_rate = $this->Currency_model->get_conversion_rate();
            $idr_amount = $this->Currency_model->convert_to_idr($aud_amount);

            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'country' => $this->input->post('country'),
                'postal_code' => $this->input->post('postal_code'),
                'registration_type' => $registration_type,
                'currency' => 'AUD',
                'original_amount' => $aud_amount,
                'conversion_rate' => $conversion_rate,
                'amount' => $idr_amount,
                'payment_status' => 'pending'
            ];

            $registration_id = $this->Registration_model->save_registration($data);
            
            if ($registration_id) {
                $this->process_payment($registration_id, $data, $idr_amount, 'AUD');
            } else {
                $this->session->set_flashdata('error', 'Failed to save registration data');
                redirect('registration/aud');
            }
        }
    }

    // Proses Pembayaran Umum
    private function process_payment($registration_id, $customer_data, $amount, $currency) {
        $transaction_details = [
            'order_id' => 'REG-'.$registration_id.'-'.time(),
            'gross_amount' => $amount
        ];

        $customer_details = [
            'first_name' => $customer_data['first_name'],
            'last_name' => $customer_data['last_name'],
            'email' => $customer_data['email'],
            'phone' => $customer_data['phone'],
            'billing_address' => [
                'address' => $customer_data['address'],
                'city' => $customer_data['city'],
                'postal_code' => $customer_data['postal_code'],
                'country_code' => ($customer_data['country'] == 'Australia') ? 'AUS' : 'IDN'
            ]
        ];

        // Tambahkan info konversi untuk AUD
        $custom_fields = [];
        if ($currency == 'AUD') {
            $custom_fields = [
                'custom_field1' => 'AUD',
                'custom_field2' => $customer_data['original_amount'],
                'custom_field3' => $customer_data['conversion_rate']
            ];
        }

        $params = array_merge([
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details
        ], $custom_fields);

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $this->Registration_model->update_payment_status($transaction_details['order_id'], 'pending');
            
            $data['snap_token'] = $snapToken;
            $data['order_id'] = $transaction_details['order_id'];
            $data['amount'] = $amount;
            $data['original_amount'] = $customer_data['original_amount'];
            $data['currency'] = $currency;
            $data['conversion_rate'] = $customer_data['conversion_rate'];
            $data['customer'] = $customer_data;
            
            $this->load->view('payment_page', $data);
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Payment gateway error: '.$e->getMessage());
            redirect('registration/'.strtolower($currency));
        }
    }

    // Handle Notifikasi dari Midtrans
    public function payment_notification() {
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if ($result) {
            $order_id = $result->order_id;
            $transaction_status = $result->transaction_status;
            $fraud_status = $result->fraud_status;

            // Determine payment status
            $status_map = [
                'capture' => ($fraud_status == 'accept') ? 'success' : 'challenge',
                'settlement' => 'success',
                'pending' => 'pending',
                'deny' => 'denied',
                'expire' => 'expired',
                'cancel' => 'canceled'
            ];

            if (isset($status_map[$transaction_status])) {
                $this->Registration_model->update_payment_status($order_id, $status_map[$transaction_status]);
            }
        }
    }

    // Halaman Sukses Pembayaran
    public function payment_success() {
        $order_id = $this->input->get('order_id');
        $data['registration'] = $this->Registration_model->get_registration_by_order_id($order_id);
        $this->load->view('payment_success', $data);
    }

    // Halaman Gagal Pembayaran
    public function payment_failed() {
        $order_id = $this->input->get('order_id');
        $data['registration'] = $this->Registration_model->get_registration_by_order_id($order_id);
        $this->load->view('payment_failed', $data);
    }
}