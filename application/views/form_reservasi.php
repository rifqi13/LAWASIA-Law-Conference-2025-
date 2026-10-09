<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Kamar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Form Reservasi Kamar</h2>
        
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>
        
        <form action="<?= site_url('reservasi/proses') ?>" method="post">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Pemesan</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <!-- Tampilkan kode booking saja -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="kamar_id" class="form-label">Tipe Kamar</label>
                <select class="form-control" id="kamar_id" name="kamar_id" required>
                    <option value="">Pilih Kamar</option>
                    <?php foreach($kamars as $kamar): ?>
                        <option value="<?= $kamar->id ?>">
                            <?= $kamar->jenis_kamar ?> (Tersedia: <?= $kamar->stok_tersedia ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="checkin" class="form-label">Tanggal Check-in</label>
                <input type="date" class="form-control" id="checkin" name="checkin" min="2025-08-09" max="2025-08-11" required>
            </div>
            
            <div class="mb-3">
                <label for="checkout" class="form-label">Tanggal Check-out</label>
                <input type="date" class="form-control" id="checkout" name="checkout" min="2025-08-10" max="2025-08-12" required>
            </div>
            
            <div class="mb-3">
                <div id="availability_status"></div>
            </div>
            
            <button type="submit" class="btn btn-primary">Buat Reservasi</button>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        // Cek ketersediaan saat tanggal atau kamar berubah
        $('#kamar_id, #checkin, #checkout').change(function() {
            var kamar_id = $('#kamar_id').val();
            var checkin = $('#checkin').val();
            var checkout = $('#checkout').val();
            
            if (kamar_id && checkin && checkout) {
                $.ajax({
                    url: '<?= site_url("reservasi/cek_ketersediaan") ?>',
                    type: 'POST',
                    data: {
                        kamar_id: kamar_id,
                        checkin: checkin,
                        checkout: checkout
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.available) {
                            $('#availability_status').html('<div class="alert alert-success">Kamar tersedia untuk tanggal yang dipilih</div>');
                        } else {
                            $('#availability_status').html('<div class="alert alert-danger">Kamar tidak tersedia untuk tanggal yang dipilih</div>');
                        }
                    }
                });
            }
        });
        
        // Set min/max untuk checkout berdasarkan checkin
        $('#checkin').change(function() {
            var checkin = new Date($(this).val());
            var minCheckout = new Date(checkin);
            minCheckout.setDate(minCheckout.getDate() + 1);
            
            $('#checkout').attr('min', formatDate(minCheckout));
            
            // Jika checkout sebelum checkin, reset checkout
            var checkout = new Date($('#checkout').val());
            if (checkout <= checkin) {
                $('#checkout').val(formatDate(minCheckout));
            }
        });
        
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return [year, month, day].join('-');
        }
    });
    </script>
</body>
</html>