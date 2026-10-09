<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// At the top of your Registration controller
require_once FCPATH . 'vendor/autoload.php';

class Registration extends CI_Controller {

    private $idr_prices = [
        'peradi member' => 5500000,
        'peradi young lawyers' => 3250000,
        'speakers' => 4750000,
        'non-peradi member' => 7000000
    ];

    private $aud_prices = [
        'lawasia member' => 520,
        'speakers' => 450,
        'lawasia young lawyers' => 330,
        'non-lawasia member' => 660
    ];

    // Harga baru setelah 15 Juli 2025
    private $new_idr_prices = [
        'peradi member' => 6050000,
        'peradi young lawyers' => 3575000,
        'speakers' => 5225000,
        'non-peradi member' => 7700000        
    ];

    private $new_aud_prices = [
        'lawasia member' => 575,
        'speakers' => 495,
        'lawasia young lawyers' => 365,
        'non-lawasia member' => 730           
    ];

    private $aud_to_idr_rate = 10700;


    public function __construct() {
        parent::__construct();
        $this->load->model('Registration_model');
        $this->load->library('form_validation');
        $this->check_price_adjustment();
        
        // Midtrans configuration
        \Midtrans\Config::$serverKey = 'SB-Mid-server-BS3VCXfr7pA0l0-dlq11_ETH';
        \Midtrans\Config::$isProduction = false; // Set to true for production
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

     private function check_price_adjustment() {
        $current_date = new DateTime();
        $threshold_date = new DateTime('2025-07-15');
        
        if ($current_date > $threshold_date) {
            $this->idr_prices = $this->new_idr_prices;
            $this->aud_prices = $this->new_aud_prices;
        }
    }

    private function convert_aud_to_idr($aud_price) {
        return $aud_price * $this->aud_to_idr_rate;
    }


    public function index() {
        $data['title'] = 'Registration Form';
        $data['prices'] = $this->idr_prices;

        

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
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
        $this->form_validation->set_rules('registration_type', 'Registration Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $registration_type = $this->input->post('registration_type');
            $amount = $this->idr_prices[$registration_type];

            // Generate order ID
            $order_id = 'REG-'.time().'-'.mt_rand(1000, 9999);

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
                'amount' => $amount,
                'midtrans_order_id' => $order_id,
                'payment_status' => 'pending'
            ];

            $registration_id = $this->Registration_model->save_registration($data);
            
            if ($registration_id) {
                $this->process_payment($registration_id, $data, $amount, $order_id);
            } else {
                $this->session->set_flashdata('error', 'Failed to save registration data');
                redirect('registration');
            }
        }
    }

    private function process_payment($registration_id, $customer_data, $amount, $order_id) {
        $transaction_details = [
            'order_id' => $order_id,
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
                'country_code' => ($customer_data['country'] == 'Indonesia') ? 'IDN' : 'OTH'
            ]
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $this->Registration_model->update_payment_status($order_id, 'pending');
            
            $data['snap_token'] = $snapToken;
            $data['order_id'] = $order_id;
            $data['amount'] = $amount;
            $data['customer'] = $customer_data;
            
            $this->load->view('payment_page', $data);
        } catch (Exception $e) {
            $this->Registration_model->update_payment_status($order_id, 'failed');
            $this->session->set_flashdata('error', 'Payment gateway error: '.$e->getMessage());
            redirect('registration');
        }
    }

    public function payment_notification() {
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if ($result) {
            $order_id = $result->order_id;
            $transaction_status = $result->transaction_status;
            $fraud_status = $result->fraud_status;

            $registration = $this->Registration_model->get_registration_by_order_id($order_id);
            
            if(!$registration) {
                log_message('error', 'Midtrans notification: Order ID not found - '.$order_id);
                exit;
            }

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
                $this->_log_transaction($order_id, 'Received: '.$transaction_status.'/'.$fraud_status);
            }
        }
    }

    private function _log_transaction($order_id, $message) {
        $log_message = date('Y-m-d H:i:s')." | ".$order_id." | ".$message."\n";
        file_put_contents(APPPATH.'logs/midtrans_'.date('Y-m-d').'.log', $log_message, FILE_APPEND);
    }

    public function update_payment_status()
{
    $order_id = $this->input->post('order_id');
    $status = $this->input->post('status');
    
    // Update database
    $this->db->where('midtrans_order_id', $order_id);
    $this->db->update('registrants', [
        'payment_status' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    echo json_encode(['success' => true]);
}

    public function payment_success() {
        $order_id = $this->input->get('order_id');
        
        if(!$order_id) {
            show_404();
        }
        
        $data['registration'] = $this->Registration_model->get_registration_by_order_id($order_id);
        
        if(!$data['registration']) {
            show_404();
        }
        
        $this->load->view('payment_success', $data);
    }

    public function payment_failed() {
        $order_id = $this->input->get('order_id');
        
        if(!$order_id) {
            show_404();
        }
        
        $data['registration'] = $this->Registration_model->get_registration_by_order_id($order_id);
        
        if(!$data['registration']) {
            show_404();
        }
        
        $this->load->view('payment_failed', $data);
    }

    public function test_payment($order_id) {
        $data['registration'] = $this->Registration_model->get_registration_by_order_id($order_id);
        
        if(!$data['registration']) {
            echo "Order ID not found";
            return;
        }
        
        echo "<pre>";
        print_r($data['registration']);
        echo "</pre>";
    }
}
    
