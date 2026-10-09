<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

class Regi extends CI_Controller {

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
        $this->load->library('form_validation');
        
        // Midtrans configuration
        \Midtrans\Config::$serverKey = 'SB-Mid-server-BS3VCXfr7pA0l0-dlq11_ETH';
        \Midtrans\Config::$isProduction = false; // Set to true for production
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function index() {
        $data['title'] = 'Registration Form - IDR';
        $data['currency'] = 'IDR';
        $data['prices'] = $this->idr_prices;
        $this->load->view('registration_form', $data);
    }

    public function aud() {
        $data['title'] = 'Registration Form - AUD';
        $data['currency'] = 'AUD';
        $data['prices'] = $this->aud_prices;
        $this->load->view('registration_form', $data);
    }

    public function process() {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('postalcode', 'Postal Code', 'required');
        $this->form_validation->set_rules('registration_type', 'Registration Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Get the currency from the form to determine which page to return to
            $currency = $this->input->post('currency');
            if ($currency == 'AUD') {
                $this->aud();
            } else {
                $this->index();
            }
        } else {
            $currency = $this->input->post('currency');
            $registration_type = $this->input->post('registration_type');
            
            // Get the correct price based on currency
            $amount = ($currency == 'AUD') ? $this->aud_prices[$registration_type] : $this->idr_prices[$registration_type];

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
                'currency' => $currency,
                'amount' => $amount,
                'payment_status' => 'pending'
            ];

            $registration_id = $this->Registration_model->save_registration($data);
            
            if ($registration_id) {
                // Prepare Midtrans transaction
                $transaction_details = [
                    'order_id' => 'REG-'.$registration_id.'-'.time(),
                    'gross_amount' => $amount
                ];

                $customer_details = [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'billing_address' => [
                        'address' => $data['address'],
                        'city' => $data['city'],
                        'postal_code' => $data['postal_code'],
                        'country_code' => 'IDN'
                    ]
                ];

                $params = [
                    'transaction_details' => $transaction_details,
                    'customer_details' => $customer_details,
                    'currency_conversion' => ($currency == 'AUD') ? ['currency' => 'AUD'] : null
                ];

                try {
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    
                    // Update registration with order ID
                    $this->Registration_model->update_payment_status($transaction_details['order_id'], 'pending');
                    
                    $data['snap_token'] = $snapToken;
                    $data['order_id'] = $transaction_details['order_id'];
                    $data['amount'] = $amount;
                    $data['currency'] = $currency;
                    
                    $this->load->view('payment_page', $data);
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', 'Payment gateway error: '.$e->getMessage());
                    redirect($currency == 'AUD' ? 'registration/aud' : 'registration');
                }
            } else {
                $this->session->set_flashdata('error', 'Failed to save registration data');
                redirect($currency == 'AUD' ? 'registration/aud' : 'registration');
            }
        }
    }

    public function payment_notification() {
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if ($result) {
            $order_id = $result->order_id;
            $transaction_status = $result->transaction_status;
            $fraud_status = $result->fraud_status;

            // Check if transaction is successful
            if ($transaction_status == 'capture') {
                if ($fraud_status == 'challenge') {
                    $status = 'challenge';
                } else if ($fraud_status == 'accept') {
                    $status = 'success';
                }
            } else if ($transaction_status == 'settlement') {
                $status = 'success';
            } else if ($transaction_status == 'pending') {
                $status = 'pending';
            } else if ($transaction_status == 'deny') {
                $status = 'denied';
            } else if ($transaction_status == 'expire') {
                $status = 'expired';
            } else if ($transaction_status == 'cancel') {
                $status = 'canceled';
            }

            // Update payment status in database
            if (isset($status)) {
                $this->Registration_model->update_payment_status($order_id, $status);
            }
        }
    }

    public function payment_success() {
        $order_id = $this->input->get('order_id');
        $data['registration'] = $this->Registration_model->get_registration_by_order_id($order_id);
        $this->load->view('payment_success', $data);
    }

    public function payment_failed() {
        $order_id = $this->input->get('order_id');
        $data['registration'] = $this->Registration_model->get_registration_by_order_id($order_id);
        $this->load->view('payment_failed', $data);
    }
}