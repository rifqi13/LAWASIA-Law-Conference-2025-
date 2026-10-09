<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 72px;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container success-container">
        <div class="success-icon">✓</div>
        <h2>Payment Successful!</h2>
        <p class="lead">Thank you for your registration.</p>
        
        <?php if ($registration): ?>
            <div class="card mt-4">
                <div class="card-body text-start">
                    <h5 class="card-title">Registration Details</h5>
                    <p><strong>Name:</strong> <?php echo $registration->first_name; ?> <?php echo $registration->last_name; ?></p>
                    <p><strong>Email:</strong> <?php echo $registration->email; ?></p>
                    <p><strong>Order ID:</strong> <?php echo $registration->midtrans_order_id; ?></p>
                    <p><strong>Amount:</strong> <?php echo 'Rp '.number_format($registration->amount, 0, ',', '.'); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <a href="<?php echo site_url(); ?>/reserve" class="btn btn-primary mt-4">Return to Home</a>
    </div>
</body>
</html>