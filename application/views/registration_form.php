<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .registration-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
        .currency-switcher {
            text-align: right;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container registration-container">
        <div class="currency-switcher">
            <?php if ($currency == 'IDR'): ?>
                <a href="<?php echo site_url('registration/aud'); ?>" class="btn btn-outline-primary">Switch to AUD</a>
            <?php else: ?>
                <a href="<?php echo site_url('registration'); ?>" class="btn btn-outline-primary">Switch to IDR</a>
            <?php endif; ?>
        </div>
        
        <h2 class="text-center mb-4"><?php echo $title; ?></h2>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        
        <form action="<?php echo site_url('regi/process'); ?>" method="post">
            <input type="hidden" name="currency" value="<?php echo $currency; ?>">
            
            <div class="form-section">
                <h4>Personal Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Address Information</h4>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="postal_code" class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Registration Type</h4>
                <div class="mb-3">
                    <label for="registration_type" class="form-label">Select Registration Package</label>
                    <select class="form-select" id="registration_type" name="registration_type" required>
                        <option value="">-- Select Package --</option>
                        <option value="standard">Standard - <?php echo $currency == 'IDR' ? 'Rp '.number_format($prices['standard'], 0, ',', '.') : 'A$ '.number_format($prices['standard'], 2); ?></option>
                        <option value="premium">Premium - <?php echo $currency == 'IDR' ? 'Rp '.number_format($prices['premium'], 0, ',', '.') : 'A$ '.number_format($prices['premium'], 2); ?></option>
                        <option value="vip">VIP - <?php echo $currency == 'IDR' ? 'Rp '.number_format($prices['vip'], 0, ',', '.') : 'A$ '.number_format($prices['vip'], 2); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Continue to Payment</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>