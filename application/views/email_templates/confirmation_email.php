<h2>Selamat!</h2>
<p>Pembayaran Anda telah berhasil diverifikasi. Berikut adalah detail pendaftaran Anda:</p>
<table>
    <tr><td>Nama</td><td>: <?php echo $fullname; ?></td></tr>
    <tr><td>Nomor Pendaftaran</td><td>: <?php echo $midtrans_order_id; ?></td></tr>
    <tr><td>Status Pembayaran</td><td>: Berhasil</td></tr>
    <tr><td>Jumlah Pembayaran</td><td>: <?php echo number_format($amount, 0, ',', '.'); ?> IDR</td></tr>
</table>
<p>Terima kasih telah mendaftar. Kami akan menginformasikan jadwal acara lebih lanjut.</p>
<p>Salam,<br>Tim Panitia</p>