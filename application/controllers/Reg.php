<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// At the top of your Registration controller
require_once FCPATH . 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Reg extends CI_Controller {

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
        $this->load->model('Register_model');
        $this->load->library('form_validation');
        $this->check_price_adjustment();
        
        // Midtrans configuration
        \Midtrans\Config::$serverKey = '';
        \Midtrans\Config::$isProduction = false; // Set to true for production
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

     private function check_price_adjustment() {
        $current_date = new DateTime();
        $threshold_date = new DateTime('2026-07-27');
        
        if ($current_date > $threshold_date) {
            $this->idr_prices = $this->new_idr_prices;
            $this->aud_prices = $this->new_aud_prices;
        }
    }

    


    public function index() {
        $data['title'] = 'Registration Form - IDR';
        $data['currency'] = 'IDR';
        $data['prices'] = $this->idr_prices;

        // Hitung per kategori
    $data['young_lawyers_count'] = $this->Registration_model->count_by_category('peradi young lawyers');
    $data['limited_categories_count'] = $this->Registration_model->count_limited_categories();
    $data['all_limited_count'] = $this->Registration_model->count_all_limited();
    
    $data['young_lawyers_full'] = ($data['young_lawyers_count'] >= 20);
    $data['limited_categories_full'] = ($data['limited_categories_count'] >= 130);
    $data['all_limited_full'] = ($data['all_limited_count'] >= 150);
        
        $this->load->view('template/header');
        $this->load->view('form_coba_lokal', $data);
        $this->load->view('template/footer');
        
    }

    

    public function process() {
        
        $this->form_validation->set_rules('title', 'Title/Salutation', 'required');
        $this->form_validation->set_rules('name', 'Full Name', 'required');
        $this->form_validation->set_rules('organisation', 'Organisation', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('email-ver', 'Email', 'required|valid_email|matches[email]');
        $this->form_validation->set_rules('FirsName', 'Preferred First Name', 'required');
        $this->form_validation->set_rules('SurName', 'Preferred Surname', 'required');
        $this->form_validation->set_rules('AddLine1', 'Address Line 1', 'required');
        $this->form_validation->set_rules('postcode', 'Poscode', 'numeric');
        $this->form_validation->set_rules('registration_type', 'Registration Type', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {

            $registration_type = $this->input->post('registration_type');
    
    // Daftar kategori yang dibatasi
    $limited_categories = ['peradi young lawyers', 'peradi member', 'speakers', 'non-peradi member'];
    
    if (in_array($registration_type, $limited_categories)) {
        $young_lawyers_count = $this->Registration_model->count_by_category('peradi young lawyers');
        $limited_categories_count = $this->Registration_model->count_limited_categories();
        $all_limited_count = $this->Registration_model->count_all_limited();
        
        // Validasi kuota
        if ($all_limited_count >= 150) {
            $this->session->set_flashdata('error', 'Registration for limited categories is closed (150 participants maximum reached)');
            redirect('reg');
            return;
        }
        
        if ($registration_type == 'peradi young lawyers' && $young_lawyers_count >= 20) {
            $this->session->set_flashdata('error', 'The quota for PERADI Young Lawyers has been reached (20 participants maximum)');
            redirect('reg');
            return;
        }
        
        if (in_array($registration_type, ['peradi member', 'speakers', 'non-peradi member']) && $limited_categories_count >= 130) {
            $this->session->set_flashdata('error', 'The quota for PERADI Member, Speakers, and Non-PERADI Member has been reached (130 participants maximum)');
            redirect('reg');
            return;
        }
    }

            $registration_type = $this->input->post('registration_type');
            $amount = $this->idr_prices[$registration_type];

            // Generate order ID
            $order_id = 'REG-'.time().'-'.mt_rand(1000, 9999);

            $data = [
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
                "nia"            => $this->input->post('peradi_number', TRUE),
                "country"        => "indonesia",
                "requirements"   => $this->input->post('requirements', TRUE),
                'phone'          => $this->input->post('phone', TRUE),
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
                redirect('reg');
            }
        }
    }

    private function process_payment($registration_id, $customer_data, $amount, $order_id) {
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $amount
        ];

        
        
        $customer_details = [
                    'first_name' => $customer_data['firsnamebadge'],
                    'last_name' => $customer_data['surnamebadge'],
                    'email' => $customer_data['email'],
                    'phone' => $customer_data['phone'],
                    'billing_address' => [
                        'address' => $customer_data['Addline1'],
                        'city' => $customer_data['city'],
                        'postalcode' => $customer_data['postcode'],
                        'country_code' => 'IDN'
                    ]
                ];
        

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details
        ];

        

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $this->Registration_model->update_payment_status($transaction_details['order_id'], 'pending');
            
            $data['snap_token'] = $snapToken;
            $data['order_id'] = $transaction_details['order_id'];
            $data['amount'] = $amount;
            $data['customer'] = $customer_data;

            
            $this->load->view('payment_page', $data);
        } catch (Exception $e) {
            $this->Registration_model->update_payment_status($order_id, 'failed');
            $this->session->set_flashdata('error', 'Payment gateway error: '.$e->getMessage());
            redirect('regist');
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

    private function _log_transaction($order_id, $message) {
        $log_message = date('Y-m-d H:i:s')." | ".$order_id." | ".$message."\n";
        file_put_contents(APPPATH.'logs/midtrans_'.date('Y-m-d').'.log', $log_message, FILE_APPEND);
    }

    public function update_payment_status() {
        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');
        
        // If status is success, send confirmation email
        if ($status == 'success') {
            $registration = $this->Registration_model->get_registration_by_order_id($order_id);
            
        	$this->send_confirmation_email($registration);

            
        }

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
	
$this->send_confirmation_email($data);
        
        if(!$data['registration']) {
            show_404();
        }
        
        $this->load->view('pembayaran_berhasil', $data);
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


public function send_confirmation_email($order_id) {
    
    $order_id = $this->input->get('order_id');
    
    $data = $this->Registration_model->get_registration_by_order_id($order_id);


    $mail = new PHPMailer();
 
		//Memberitahukan PHPMailer untuk menggunakan SMTP
		$mail->SMTPDebug = 0; // Aktifkan debugging
        	$mail->isSMTP(); 
		//Set the hostname of the mail server
		$mail->Host = 'mail.peradi.or.id';
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Gunakan TLS
$mail->Port = 587; // Port alternatif
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]
];
 
		
 
		//Set true jika ingin menggunakan SMTP auth
		$mail->SMTPAuth = true;
 
		//Username untuk SMTP Auth - menggunakan alamat email yang akan dijadikan relay
		$mail->Username = 'conference@peradi.or.id';
 
		//Password untuk SMTP Auth - password aplikasi yang digenerate melalui Google Account
		$mail->Password = 'DPNP3r4d!!conference'; 
 
		//Set alamat email dan username siapa yang mengirimkan email ini
		$mail->setFrom('conference@peradi.or.id', 'PERADI-LAWASIA CONFERENCE');
 
		//Set alamat email dan username tujuan
		$mail->addAddress($data->email);
 
		//Set subject email
		$mail->Subject = 'Registration confirmation email for the PERADI-LAWASIA Conference';
 
		//Set isi content email
		$mail->Body    = '

Congratulations! Your payment has been successfully verified. Here is your registration information:
		
			Fullname:  ' . $data->fullname . '
			Email:  ' . $data->email . '
			Registration ID: '.$data->midtrans_order_id.'
			Register as: '.$data->registration_type.' 
Thank you
Conference Committee		
		';
 
		//send the message, check for errors
		if (!$mail->send()) {
    		echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
    		echo 'Message sent!';
		}

}

public function payment_pending() {
    $order_id = $this->input->get('order_id');
    
    if(!$order_id) {
        show_404();
    }
    
    $data['registration'] = $this->Registration_model->get_registration_by_order_id($order_id);
    
    if(!$data['registration']) {
        show_404();
    }
    
    $this->load->view('pambayaran_pending', $data);
}

public function check_payment_status() {
    $order_id = $this->input->get('order_id');
    
    if (!$order_id) {
        echo json_encode(['error' => 'Order ID is required']);
        return;
    }

    try {
        // Cek status dari Midtrans
        $status = \Midtrans\Transaction::status($order_id);
        
        // Update status di database
        $this->Registration_model->update_payment_status($order_id, $status->transaction_status);
        
        // Siapkan response
        $response = [
            'status' => $status->transaction_status,
            'message' => $this->get_status_message($status->transaction_status),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        // Jika error, cek status dari database
        $registration = $this->Registration_model->get_registration_by_order_id($order_id);
        
        $response = [
            'status' => $registration ? $registration->payment_status : 'not_found',
            'message' => $registration ? $this->get_status_message($registration->payment_status) : 'Order not found',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($response);
    }
}

private function get_status_message($status) {
    $messages = [
        'pending' => 'Payment is still being processed',
        'success' => 'Payment has been successfully verified',
        'settlement' => 'Payment has been successfully completed',
        'deny' => 'Payment was denied',
        'expire' => 'Payment has expired',
        'cancel' => 'Payment was canceled'
    ];
    
    return $messages[$status] ?? 'Unknown payment status';
}


}
    
