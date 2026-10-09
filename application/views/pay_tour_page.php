<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-Js9CNU5N6RVb1EX4"></script>
    <style>
        .payment-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .payment-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        #snap-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container payment-container">
        <h2 class="text-center mb-4">Payment Details</h2>
        
        <div class="payment-details">
            <h4>Order Summary</h4>
            <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
            <p><strong>Amount:</strong> 
                <?= ($currency ?? 'IDR') == 'IDR' 
                    ? 'Rp ' . number_format($amount, 0, ',', '.') 
                    : 'Rp ' . number_format($amount, 2) 
                ?>
                </p>
            <p>Please complete your payment below:</p>
        </div>
        
        <div id="snap-container">
            <div id="snap-element"></div>
        </div>
    </div>

    <script>
    var snapToken = '<?php echo $snap_token; ?>';
    
    snap.pay(snapToken, {
        onSuccess: function(result){
            // Update database status via AJAX first
            fetch('<?php echo site_url("tour/update_payment_status"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=<?php echo $order_id; ?>&status=success'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update status');
                }
                window.location.href = '<?php echo site_url("tour/payment_success?order_id="); ?>' + '<?php echo $order_id; ?>';
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = '<?php echo site_url("tour/payment_success?order_id="); ?>' + '<?php echo $order_id; ?>';
            });
        },
        onPending: function(result){
            // Update pending status
            fetch('<?php echo site_url("tour/update_payment_status"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=<?php echo $order_id; ?>&status=pending'
            });
            window.location.href = '<?php echo site_url("tour/payment_pending?order_id="); ?>' + '<?php echo $order_id; ?>';
        },
        onError: function(result){
            // Update failed status
            fetch('<?php echo site_url("tour/update_payment_status"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=<?php echo $order_id; ?>&status=failed'
            });
            window.location.href = '<?php echo site_url("tour/payment_failed?order_id="); ?>' + '<?php echo $order_id; ?>';
        },
	onClose: function(result){
    	/* You may add your own implementation here */
   	 alert('you closed the popup without finishing the payment');
  	},
onClose: function(){
    	/* You may add your own implementation here */
   	 alert('you closed the popup without finishing the payment');
  	},


    });
</script>
</body>
</html>