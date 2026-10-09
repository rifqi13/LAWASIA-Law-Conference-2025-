<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $client_key ?>"></script>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Pembayaran Reservasi</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Detail Reservasi</h5>
                                <p class="mb-1"><strong>Kode Booking:</strong> <?= $reservasi->kode_booking ?></p>
                                <p class="mb-1"><strong>Kamar:</strong> <?= $kamar->nama_kamar ?></p>
                                <p class="mb-1"><strong>Check In:</strong> <?= date('d M Y', strtotime($reservasi->check_in)) ?></p>
                                <p class="mb-1"><strong>Check Out:</strong> <?= date('d M Y', strtotime($reservasi->check_out)) ?></p>
                                <p class="mb-1"><strong>Durasi:</strong> 
                                    <?= floor((strtotime($reservasi->check_out) - strtotime($reservasi->check_in)) / (60 * 60 * 24)) ?> Malam
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5>Detail Pemesan</h5>
                                <p class="mb-1"><strong>Nama:</strong> <?= $reservasi->nama_pemesan ?></p>
                                <p class="mb-1"><strong>Email:</strong> <?= $reservasi->email ?></p>
                                <p class="mb-1"><strong>No HP:</strong> <?= $reservasi->no_hp ?></p>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5 class="alert-heading">Total Pembayaran</h5>
                            <h3 class="mb-0">Rp <?= number_format($reservasi->total_harga, 0, ',', '.') ?></h3>
                        </div>
                        
                        <button id="pay-button" class="btn btn-primary btn-lg w-100">Bayar Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('pay-button').onclick = function(){
            snap.pay('<?= $snap_token ?>', {
                onSuccess: function(result){
                    window.location = '<?= site_url('reservasi/finish?order_id='.$reservasi->kode_booking) ?>';
                },
                onPending: function(result){
                    window.location = '<?= site_url('reservasi/finish?order_id='.$reservasi->kode_booking) ?>';
                },
                onError: function(result){
                    window.location = '<?= site_url('reservasi/finish?order_id='.$reservasi->kode_booking) ?>';
                }
            });
        };
    </script>
</body>
</html>