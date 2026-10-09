<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Tour Pending</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .pending-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .pending-icon {
            font-size: 72px;
            color: #fd7e14;
            margin-bottom: 20px;
        }
        .instructions {
            background-color: #fff3e0;
            border-left: 4px solid #fd7e14;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container pending-container">
        <div class="pending-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h2>Payment Tour Pending</h2>
        <p class="lead">Your payment tour is being processed</p>
        
        <div class="instructions">
            <h5><i class="fas fa-info-circle"></i> What to do next:</h5>
            
                Complete your bank transfer using the Virtual Account number provided

        </div>
        
        <?php if ($registration): ?>
            <div class="card mt-4">
                <div class="card-body text-start">
                    <h5 class="card-title">Registration Details</h5>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($registration->first_name); ?><?php echo htmlspecialchars($registration->last_name); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($registration->email); ?></p>
                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($registration->midtrans_order_id); ?></p>
                    <p><strong>Amount:</strong> <?php echo 'Rp '.number_format($registration->amount, 0, ',', '.'); ?></p>
                    <?php if (!empty($registration->va_number)): ?>
                        <p><strong>Virtual Account:</strong> <?php echo htmlspecialchars($registration->va_number); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
    <button id="status-button" onclick="checkPaymentStatus()" class="btn btn-warning me-md-2">
        <i class="fas fa-sync-alt"></i> Check Payment Status
    </button>
    <a href="<?php echo site_url('tour/regist'); ?>" class="btn btn-outline-secondary">
        <i class="fas fa-home"></i> Return to Home
    </a>
</div>
<div id="status-message"></div>    </div>
!-- Tambahkan script berikut sebelum </body> -->
<script>
function checkPaymentStatus() {
    const orderId = '<?php echo $registration->midtrans_order_id; ?>';
    const statusBtn = document.getElementById('status-button');
    const statusMessage = document.getElementById('status-message');
    
    statusBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...';
    
    fetch('<?php echo site_url("tour/check_payment_status"); ?>?order_id=' + orderId)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'settlement' || data.status === 'success') {
                window.location.href = '<?php echo site_url("tour/payment_success?order_id="); ?>' + orderId;
            } else if (data.status === 'pending') {
                statusMessage.innerHTML = `
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-info-circle"></i> ${data.message}
                    </div>
                `;
            } else {
                window.location.href = '<?php echo site_url("tour/payment_failed?order_id="); ?>' + orderId;
            }
        })
        .catch(error => {
            statusMessage.innerHTML = `
                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-triangle"></i> Error checking payment status
                </div>
            `;
        })
        .finally(() => {
            statusBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Check Again';
        });
}

// Auto check every 30 seconds
setInterval(checkPaymentStatus, 30000);
</script>

</body>
</html>