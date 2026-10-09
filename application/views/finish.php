<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Status Reservasi</h4>
                    </div>
                    <div class="card-body text-center">
                        <?php if($reservasi->status_pembayaran == 'success'): ?>
                            <div class="alert alert-success">
                                <h4 class="alert-heading">Pembayaran Berhasil!</h4>
                                <p>Terima kasih telah melakukan reservasi.</p>
                            </div>
                        <?php elseif($reservasi->status_pembayaran == 'pending'): ?>
                            <div class="alert alert-warning">
                                <h4 class="alert-heading">Menunggu Pembayaran</h4>
                                <p>Silakan selesaikan pembayaran Anda.</p>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <h4 class="alert-heading">Pembayaran Gagal</h4>
                                <p>Silakan coba lagi atau hubungi kami.</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card mb-4">
                            <div class="card-body text-start">
                                <h5 class="card-title">Detail Reservasi</h5>
                                <p class="mb-1"><strong>Kode Booking:</strong> <?= $reservasi->kode_booking ?></p>
                                <p class="mb-1"><strong>Kamar:</strong> <?= $kamar->nama_kamar ?></p>
                                <p class="mb-1"><strong>Tanggal:</strong> <?= date('d M Y', strtotime($reservasi->check_in)) ?> - <?= date('d M Y', strtotime($reservasi->check_out)) ?></p>
                                <p class="mb-1"><strong>Total Pembayaran:</strong> Rp <?= number_format($reservasi->total_harga, 0, ',', '.') ?></p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    <span class="badge bg-<?= $reservasi->status_pembayaran == 'success' ? 'success' : ($reservasi->status_pembayaran == 'pending' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($reservasi->status_pembayaran) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <button id="status-button" onclick="checkPaymentStatus()" class="btn btn-warning me-md-2">
                            <i class="fas fa-sync-alt"></i> Check Payment Status
                        </button>
                        <a href="<?= site_url() ?>" class="btn btn-primary">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
function checkPaymentStatus() {
    const orderId = '<?php echo $reservasi->kode_booking; ?>';
    const statusBtn = document.getElementById('status-button');
    const statusMessage = document.getElementById('status-message');
    
    statusBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...';
    
    fetch('<?php echo site_url("reservasi/check_payment_status"); ?>?order_id=' + orderId)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'settlement' || data.status === 'success') {
                window.location.href = '<?php echo site_url("reservasi/payment_success?order_id="); ?>' + orderId;
            } else if (data.status === 'pending') {
                statusMessage.innerHTML = `
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-info-circle"></i> ${data.message}
                    </div>
                `;
            } else {
                window.location.href = '<?php echo site_url("reservasi/payment_failed?order_id="); ?>' + orderId;
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
</html>