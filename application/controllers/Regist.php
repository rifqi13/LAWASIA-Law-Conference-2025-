<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// At the top of your Registration controller
require_once FCPATH . 'vendor/autoload.php';
require_once FCPATH . 'vendor/phpmailer/class.phpmailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Midtrans\Notification;

class Regist extends CI_Controller {

    private $idr_prices = [
        'peradi member' => 5500000,
        'peradi young lawyers' => 3250000,
        'speakers' => 4750000,
        'non-peradi member' => 7000000
    ];

    private $aud_prices = [
        'lawasia member' => 520,
        'speakers/moderators' => 450,
        'lawasia young lawyers' => 330,
        'non-lawasia member' => 660,
        'closing dinner' => 103
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
        'speakers/moderators' => 495,
        'lawasia young lawyers' => 365,
        'non-lawasia member' => 730,
        'closing dinner' => 103           
    ];

    private $aud_to_idr_rate = 10700;

    public function __construct() {
        parent::__construct();
        $this->load->model('Registration_model');
        $this->load->model('Register_model');
        $this->load->library('form_validation');
        $this->load->library('email'); // Load email library here
        $this->check_price_adjustment();
        
        // Midtrans configuration
        \Midtrans\Config::$serverKey = 'SB-Mid-server-BS3VCXfr7pA0l0-dlq11_ETH';
        \Midtrans\Config::$isProduction = false; // Set ke true untuk production
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

	require APPPATH.'libraries/phpmailer/src/Exception.php';
        require APPPATH.'libraries/phpmailer/src/PHPMailer.php';
        require APPPATH.'libraries/phpmailer/src/SMTP.php';
    }

    private function check_price_adjustment() {
        $current_date = new DateTime();
        $threshold_date = new DateTime('2026-07-15');
        
        if ($current_date > $threshold_date) {
            $this->idr_prices = $this->new_idr_prices;
            $this->aud_prices = $this->new_aud_prices;
        }
    }

    private function convert_aud_to_idr($aud_price) {
        return $aud_price * $this->aud_to_idr_rate;
    }

    public function index() {
        $data['title'] = 'Registration Form - AUD';
        $data['currency'] = 'AUD';
        $data['prices'] = $this->aud_prices;
        
        // Tambahkan konversi harga ke IDR
        $data['converted_prices'] = [
            'lawasia member' => $this->convert_aud_to_idr($this->aud_prices['lawasia member']),
            'speakers/moderators' => $this->convert_aud_to_idr($this->aud_prices['speakers/moderators']),
            'lawasia young lawyers' => $this->convert_aud_to_idr($this->aud_prices['lawasia young lawyers']),
            'non-lawasia member' => $this->convert_aud_to_idr($this->aud_prices['non-lawasia member']),
            'closing dinner' => $this->convert_aud_to_idr($this->aud_prices['closing dinner']),
        ];
        
        $data['country'] = $this->Register_model->getCountry();
        
        $this->load->view('template/header');
        $this->load->view('form_coba', $data);
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
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('registration_type', 'Registration Type', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $registration_type = $this->input->post('registration_type');
            $amount = $this->aud_prices[$registration_type];
            $amount = $amount * 10700;

            // Generate order ID
            $order_id = 'REG-'.time().'-'.mt_rand(1000, 9999);

            $data = [
                'title'          => $this->input->post('title', TRUE),
                'fullname'       => $this->input->post('name', TRUE),
                'organisation'   => $this->input->post('organisation', TRUE),
                'position'       => $this->input->post('job', TRUE),
                'email'          => $this->input->post('email', TRUE),
                'firsnamebadge'  => $this->input->post('FirsName', TRUE),
                'surnamebadge'   => $this->input->post('SurName', TRUE),
                'Addline1'       => $this->input->post('AddLine1', TRUE),
                'Addline2'       => $this->input->post('AddLine2', TRUE),
                'city'           => $this->input->post('city', TRUE),
                'state'          => $this->input->post('state', TRUE),
                'postcode'       => $this->input->post('postcode', TRUE),
                'country'        => $this->input->post('country'),
                'requirements'   => $this->input->post('requirements', TRUE),
                'phone'          => $this->input->post('phone', TRUE),
                'registration_type' => $registration_type,
                'amount' => $amount,
                'midtrans_order_id' => $order_id,
                'payment_status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $registration_id = $this->Registration_model->save_registration($data);
            
            if ($registration_id) {
                $this->process_payment($registration_id, $data, $amount, $order_id);
            } else {
                $this->session->set_flashdata('error', 'Failed to save registration data');
                redirect('regist');
            }
        }
    }

    private function process_payment($registration_id, $customer_data, $amount, $order_id) {
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $amount
        ];

        // Ambil data negara dari model
        $country_name = $this->input->post('country');

        // Panggil model untuk mendapatkan data country berdasarkan nama
        $country_data = $this->Register_model->getCountryByName($country_name);

        // Ambil iso3 dari hasil query, default 'IDN' jika tidak ditemukan
        $country_code = $country_data ? $country_data->iso3 : 'IDN';
        
        $customer_details = [
                    'first_name' => $customer_data['firsnamebadge'],
                    'last_name' => $customer_data['surnamebadge'],
                    'email' => $customer_data['email'],
                    'phone' => $customer_data['phone'],
                    'billing_address' => [
                        'address' => $customer_data['Addline1'],
                        'city' => $customer_data['city'],
                        'postalcode' => $customer_data['postcode'],
                        'country_code' => $country_code
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
             // Dapatkan payment method dari Midtrans

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

        $mail->isHTML(true);
 
		//Set isi content email
		$mail->Body    = "
        

<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                color: #0066cc;
                font-size: 24px;
                margin-bottom: 20px;
            }
			.text-center{
				text-align: center !important;
			}
            .details {
                background-color: #f9f9f9;
                padding: 15px;
                border-radius: 5px;
                margin: 20px 0;
            }
            .footer {
                margin-top: 30px;
                font-size: 14px;
                color: #666;
            }
			.registration-table {
            width: 100%;
            max-width: 600px;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .registration-table th, 
        .registration-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .registration-table th {
            background-color: #0070c0;
			color:white;
            font-weight: bold;
            width: 40%;
        }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header text-center'><b>REGISTRATION CONFIRMATION</b></div>
            
            <p class='text-center'>Dear, $data->title $data->firsnamebadge $data->surnamebadge</p>

			<p class='text-center'>Your registration for the <b>PERADI - LAWASIA Belt and Road Initiative & Employment Law Conference 2025</b> has been successful.</p>

			<p class='text-center'>Get ready to embark on a journey of navigating the legal landscape in the digital age, as we gather in the vibrant city of Jakarta, Indonesia!</p>

			<p class='text-center'><b><u>DATE</u></b><br>Sunday 10 to Monday 11 August 2025</p>
			<p class='text-center'><b><u>VENUE</u></b><br>Grand Hyatt Hotel<br> Jalan M.H. Thamrin Kav. 28 - 30, Jakarta, Indonesia 10350</p>

			<p class='text-center'><b><u>ONSITE REGISTRATION & WELCOMING RECEPTION AND NETWORKING NIGHT</u></b><br>
			Sunday, 10 August 2025<br>
			16:30 - 19:00 | Grand Ballroom<br>
			Please find below the summary of your registration.</p>


			<p class='text-center'><b><u>CLOSING DINNER & NETWORKING NIGHT</u></b><br>
			Monday, 11 August 2025<br>
			18:30 - 21:30 | Grand Ballroom</p>

			<p class='text-center'><b><u>OPTIONAL POST-CONFERENCE TOUR</u></b><br>	
			Tuesday, 12 August 2025</p>
			
			
						<p class='text-center'><b>Option 1:</b><br>
						Sightseeing and lunch 
						at Taman Mini Indonesia Indah 
						(https://tamanmini.com/taman_jelajah_indonesia/)</p>
					
						<p class='text-center'><b>Option 2:</b><br>
						Sightseeing Istiqlal Mosque (https://istiqlal.or.id/), 
						Katedral Jakarta (https://katedraljakarta.or.id/), 
						and Museum Nasional (https://www.museumnasional.or.id/), 
						and lunch
						</p>

			<p class='text-center'>Please find below the summary of your registration.</p>
            
            <p class='text-center'>We can't wait to welcome you to Jakarta for what promises to be an unforgettable conference experience!<br>
			In the meantime, please visit the <a href='https://peradi.or.id/lawasia-conference'>conference website</a> for updates.</p>
			
			<p class='text-center'>Kind regards <br><b>PERADI & LAWASIA</b></p>
			
			<p>&nbsp;</p>

            <p><b>Registration Data</b></p>
            <table class='registration-table'>
				<tr>
					<th>Registration Category</th>
					<td>" . htmlspecialchars($data->registration_type) . "</td>
				</tr>
				<tr>
					<th>Name Badge</th>
					<td> " . $data->firsnamebadge . " " . $data->surnamebadge . "</td>
				</tr>
				<tr>
					<th>Date of Payment</th>
					<td>" . htmlspecialchars($data->created_at) . "</td>
				</tr>
				<tr>
					<th>Registration ID</th>
					<td>" . $data->midtrans_order_id . "</td>
				</tr>
				<tr>
					<th>Amount (IDR)</th>
					<td class='currency'>Rp" . number_format($data->amount, 0, ',', '.') . "</td>
				</tr>
				<tr>
					<th>Payment Status</th>
					<td class='payment-success'>" . htmlspecialchars($data->payment_status) . "</td>
				</tr>
			</table>

            
            <p>If you have any queries, please contact the organisers and we will be happy to assist you:</p>
            
            <div class='footer'>
                <p><b>Registration queries:</b><br>
				PERADI-LAWASIA Conference & Events Team<br>
				Email: <a href='mailto:conference@peradi.or.id'>conference@peradi.or.id</a> / <a href='mailto:dpn.peradi.slipi@gmail.com'>dpn.peradi.slipi@gmail.com</a></p>
            </div>
        </div>
    </body>
    </html>	";	
		
 
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
    
    $this->load->view('payment_pending', $data);
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