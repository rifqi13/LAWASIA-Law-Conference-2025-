<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// At the top of your Registration controller
require_once FCPATH . 'vendor/autoload.php';
require_once FCPATH . 'vendor/phpmailer/class.phpmailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Midtrans\Notification;

class Reserve extends CI_Controller {

    private $hotel_prices = [
        'King Bed with City View + Breakfast' => 2800000,
        'King Bed with Bundaran HI View + Breakfast' => 2800000          
    ];

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('Register_model');
        $this->load->model('Registration_model');
        $this->load->library('form_validation');

        // Midtrans configuration
        \Midtrans\Config::$serverKey = 'SB-Mid-server-BS3VCXfr7pA0l0-dlq11_ETH';
        \Midtrans\Config::$isProduction = false; // Set ke true untuk production
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

	    require APPPATH.'libraries/phpmailer/src/Exception.php';
        require APPPATH.'libraries/phpmailer/src/PHPMailer.php';
        require APPPATH.'libraries/phpmailer/src/SMTP.php';
    }

    public function index()
	{
        $data['title'] = 'Reservasi Hotel';
        $data['prices'] = $this->hotel_prices;

        $data['country'] = $this->Register_model->getCountry();

        $data['King Bed with City View + Breakfast'] = $this->Registration_model->count_by_hotel('King Bed with City View + Breakfast');
        $data['King Bed with Bundaran HI View + Breakfast'] = $this->Registration_model->count_by_hotel('King Bed with Bundaran HI View + Breakfast');

        $data['city_view_full'] = ($data['King Bed with City View + Breakfast'] >= 17);
        $data['bundaran_hi_full'] = ($data['King Bed with Bundaran HI View + Breakfast'] >= 3);

		$this->load->view('template/header');
        $this->load->view('reserve_form', $data);
        $this->load->view('template/footer');
	}
    public function process() {

        
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('room_type', 'Hotel Room Type', 'required');
        $this->form_validation->set_rules('checkin', 'Checkin Date', 'required');
        $this->form_validation->set_rules('checkout', 'Checkout Date', 'required');
        

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {

            $tour_type = $this->input->post('room_type');
            $amount = $this->hotel_prices[$tour_type];

            $amount = $this->Registration_model->hitung_total_harga(
                      $this->input->post('checkin'),
                      $this->input->post('checkout')
            );

            $order_id = 'RSV-'.time().'-'.mt_rand(1000, 9999);

            $data = [
                'first_name'            => $this->input->post('first_name', TRUE),
                'last_name'             => $this->input->post('last_name', TRUE),
                'email'                 => $this->input->post('email', TRUE),
                'phone'                 => $this->input->post('phone', TRUE),
                'address'               => $this->input->post('address', TRUE),
                'country'               => $this->input->post('country'),
                'tanggal_checkin'       => $this->input->post('checkin'),
                'tanggal_checkout'      => $this->input->post('checkout'),
                'room_type'             => $tour_type,
                'amount'                => $amount,
                'midtrans_order_id'     => $order_id,
                'payment_status'        => 'pending',
                'created_at'            => date('Y-m-d H:i:s')
            ];

            $registration_id = $this->Registration_model->save_hotel($data);
            
            if ($registration_id) {
            // 3. Generate Snap Token (hanya untuk view)
            $country_data = $this->Register_model->getCountryByName($data['country']);
            $country_code = $country_data ? $country_data->iso3 : 'IDN';

            $transaction_details = [
                'order_id' => $order_id,
                'gross_amount' => $amount
            ];

            $customer_details = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'billing_address' => [
                    'address' => $data['address'],
                    'country_code' => $country_code
                ]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken([
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details
            ]);

            // 4. Siapkan data untuk view (DENGAN snap_token)
            $view_data = $data;
            $view_data['snap_token'] = $snapToken; // Hanya untuk view

            $this->load->view('payment_room', $view_data);
            } else {
                $this->session->set_flashdata('error', 'Failed to save registration data');
                redirect('reserve');
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
                    'first_name' => $customer_data['first_name'],
                    'last_name' => $customer_data['last_name'],
                    'email' => $customer_data['email'],
                    'phone' => $customer_data['phone'],
                    'billing_address' => [
                        'address' => $customer_data['address'],
                        'country_code' => $country_code
                    ]
                ];
        

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $this->Registration_model->update_hotel_status($transaction_details['order_id'], 'pending');
            
            $data['snap_token'] = $snapToken;
            $data['order_id'] = $transaction_details['order_id'];
            $data['amount'] = $amount;
            $data['customer'] = $customer_data;

            
            $this->load->view('pay_tour_page', $data);
        } catch (Exception $e) {
            $this->Registration_model->update_hotel_status($order_id, 'failed');
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
                $this->Registration_model->update_hotel_status($order_id, $status);
            }
        }
    }


    public function update_payment_status() {
        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');

	// Update database
        $this->db->where('midtrans_order_id', $order_id);
        $this->db->update('hotel', [
            'payment_status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        echo json_encode(['success' => true]);
    }

    public function payment_success() {
        $order_id = $this->input->get('order_id');
        $status = 'success';
        
        if(!$order_id) {
            show_404();
        }
        
        
        $data['registration'] = $this->Registration_model->get_registration_by_hotel_id($order_id);

        $this->Registration_model->update_hotel_status($order_id, $status);

        $this->send_confirmation_email($data);

        if(!$data['registration']) {
            show_404();
        }
        
        $this->load->view('reserve_success', $data);
    }

    public function payment_failed() {
        $order_id = $this->input->get('order_id');
        
        if(!$order_id) {
            show_404();
        }
        
        $data['registration'] = $this->Registration_model->get_registration_by_hotel_id($order_id);
        
        if(!$data['registration']) {
            show_404();
        }
        
        $this->load->view('payment_failed', $data);
    }

    public function payment_pending() {
    $order_id = $this->input->get('order_id');
    
    if(!$order_id) {
        show_404();
    }
    
    $data['registration'] = $this->Registration_model->get_registration_by_hotel_id($order_id);
    
    if(!$data['registration']) {
        show_404();
    }
    
    $this->load->view('reserve_pending', $data);
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


public function send_confirmation_email($order_id) {
    
    $order_id = $this->input->get('order_id');
    
    $data = $this->Registration_model->get_registration_by_hotel_id($order_id);

    
    

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
		$mail->Subject = 'Reservation Hotel confirmation email for the PERADI-LAWASIA Conference';

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
            <div class='header text-center'><b>RESERVATION CONFIRMATION</b></div>
            
            <p class='text-center'>Dear, $data->first_name $data->last_name</p>

			<p class='text-center'>Your reservation hotel for the <b>PERADI - LAWASIA Belt and Road Initiative & Employment Law Conference 2025</b> 
			has been successful.</p>
            <p class='text-center'><b><u>GRAND HYATT HOTEL, JAKARTA</u></b><br><a href='https://www.google.com/maps/place/Grand+Hyatt+Jakarta/@-6.1936508,106.8195954,886m/data=!3m2!1e3!5s0x2e69f4211614e4ed:0x7aefc3305250ccc5!4m9!3m8!1s0x2e69f421118d0d03:0x8e32bdf52d13087c!5m2!4m1!1i2!8m2!3d-6.1936508!4d106.8221703!16s%2Fm%2F0wfjxjd!5m1!1e2?entry=ttu&g_ep=EgoyMDI1MDYwMi4wIKXMDSoASAFQAw%3D%3D'>View on Maps</a></p>
			<p class='text-center'><b><u>DATE CHECKIN</u></b><br>" . htmlspecialchars($data->tanggal_checkin) . "</p>
			<p class='text-center'><b><u>DATE CHECKOUT</u></b><br>" . htmlspecialchars($data->tanggal_checkout) . "</p>
			
			<p class='text-center'>Kind regards <br><b>PERADI & LAWASIA</b></p>
			
			<p>&nbsp;</p>

            <p><b>Reservation Data</b></p>
            <table class='registration-table'>
				<tr>
					<th>Reservation Category</th>
					<td>" . htmlspecialchars($data->room_type) . "</td>
				</tr>
				<tr>
					<th>Name</th>
					<td>$data->first_name $data->last_name</td>
				</tr>
				<tr>
					<th>Date of Payment</th>
					<td>" . htmlspecialchars($data->created_at) . "</td>
				</tr>
				<tr>
					<th>Reservetion ID</th>
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
                <p><b>Reservation queries:</b><br>
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
    


}