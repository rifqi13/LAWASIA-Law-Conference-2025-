<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-Js9CNU5N6RVb1EX4"></script>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Reservation Payment</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Reservation Details</h5>
                                <p class="mb-1"><strong>ID:</strong> <?= $midtrans_order_id ?></p>
                                <p class="mb-1"><strong>Room Type:</strong> <?= $room_type ?></p>
                                <p class="mb-1"><strong>Check In:</strong> <?= date('d M Y', strtotime($tanggal_checkin)) ?></p>
                                <p class="mb-1"><strong>Check Out:</strong> <?= date('d M Y', strtotime($tanggal_checkout)) ?></p>
                                <p class="mb-1"><strong>Duration:</strong> 
                                    <?= floor((strtotime($tanggal_checkout) - strtotime($tanggal_checkin)) / (60 * 60 * 24)) ?> Malam
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5>Order Details</h5>
                                <p class="mb-1"><strong>Name:</strong> <?= $first_name ?> <?= $last_name ?></p>
                                <p class="mb-1"><strong>Email:</strong> <?= $email ?></p>
                                <p class="mb-1"><strong>Handphone:</strong> <?= $phone ?></p>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5 class="alert-heading">Total Payment</h5>
                            <h3 class="mb-0">Rp <?= number_format($amount, 0, ',', '.') ?></h3>
                        </div>
                        
                        <button id="pay-button" class="btn btn-primary btn-lg w-100">Pay Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
               
        document.getElementById('pay-button').onclick = function(){
            snap.pay('<?= $snap_token ?>', {
                onSuccess: function(result){
                    window.location = '<?= site_url('reserve/payment_success?order_id='.$midtrans_order_id) ?>';
                },
                onPending: function(result){
                    window.location = '<?= site_url('reserve/payment_pending?order_id='.$midtrans_order_id) ?>';
                },
                onError: function(result){
                    window.location = '<?= site_url('reserve/payment_failed?order_id='.$midtrans_order_id) ?>';
                }
            });
        };
    </script>
</body>
</html>