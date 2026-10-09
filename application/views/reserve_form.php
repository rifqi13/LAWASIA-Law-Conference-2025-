    <section class="hero-wrap hero-wrap-2" style="background-image: url('<?php echo base_url('assets/images/Jakarta-Skyline-10.jpg'); ?>');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-2 bread">Reservation Hotel</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Reservation Hotel  <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>
    

<section class="ftco-section ftco-no-pt ftco-no-pb mt-5 mb-5 contact-section">
			<div class="container">
				<div class="row d-flex align-items-stretch no-gutters">
					<div class="col-md-6 p-4 p-md-5 order-md-last bg-light">
						<form action="<?php echo site_url('reserve/process'); ?>" method="post">
                            <div class="form-group">
                                <input type="text" name="first_name" class="form-control" placeholder="First Name">
                                <span style="color: #f86c61"> <?= form_error('first_name'); ?>  </span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                                <span style="color: #f86c61"> <?= form_error('last_name'); ?>  </span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" placeholder="Email">
                                <span style="color: #f86c61"> <?= form_error('email'); ?>  </span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" placeholder="Phone">
                                <span style="color: #f86c61"> <?= form_error('phone'); ?>  </span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="address" class="form-control" placeholder="Address">
                                <span style="color: #f86c61"> <?= form_error('address'); ?>  </span>
                            </div>
                            <div class="form-group">
                                <select type="text" id="country" name="country" placeholder="" class="form-control" onblur="validate(4)">
                                        <option value=''>---- Select Country ----</option>
                                        <?php foreach ($country as $negara):?>
                                        <option value='<?= $negara['nicename'] ?>'><?= $negara['nicename'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <span style="color: #f86c61"> <?= form_error('country'); ?>  </span>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="room_type">
                                    <option value="">--------- Option Room ---------</option>
                                    <option value="King Bed with City View + Breakfast" <?= ($city_view_full) ? 'disabled' : '' ?>>
                                        King Bed with City View + Breakfast: Rp <?php echo number_format($prices['King Bed with City View + Breakfast'], 0, ',', '.'); ?>
                                        <?= ($city_view_full) ? '(Quota Full)' : '' ?>
                                    </option>
                                    
                                    <option value="King Bed with Bundaran HI View + Breakfast" <?= ($bundaran_hi_full) ? 'disabled' : '' ?>>
                                        King Bed with Bundaran HI View + Breakfast: Rp <?php echo number_format($prices['King Bed with Bundaran HI View + Breakfast'], 0, ',', '.'); ?>
                                        <?= ($bundaran_hi_full) ? '(Quota Full)' : '' ?>
                                    </option>
                                </select>
                                <span style="color: #f86c61"> <?= form_error('room_type'); ?>  </span>
                                <?php if ($city_view_full): ?>
                                <div class="alert alert-warning mt-2">
                                    The quota for King Bed with City View + Breakfast has been reached.
                                </div>
                                <?php endif; ?>
                                <?php if ($bundaran_hi_full): ?>
                                    <div class="alert alert-warning mt-2">
                                        The quota for King Bed with Bundaran HI View + Breakfast has been reached.
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label px-3">Checkin Date</label> 
                                <input type="date" class="form-control" id="checkin" name="checkin" min="2025-08-09" max="2025-08-11" required>
                                <span style="color: #f86c61"> <?= form_error('checkin'); ?>  </span>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label px-3">Checkout Date</label> 
                                <input type="date" class="form-control" id="checkout" name="checkout" min="2025-08-10" max="2025-08-12" required>
                                <span style="color: #f86c61"> <?= form_error('checkout'); ?>  </span>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <input type="submit" value="Reservetion Hotel" class="btn btn-primary py-3 px-5">
                            </div>
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
				<div class="col-md-6 d-flex align-items-stretch">
						<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                <img src="<?php echo base_url('assets/images/hyatt-1.jpg'); ?>" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 style="color: white;">Grand Hyatt Hotel, Jakarta</h5>
                                    <p></p>
                                </div>
                                </div>
                                <div class="carousel-item">
                                <img src="<?php echo base_url('assets/images/hyatt-2.jpg'); ?>" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 style="color: white;">Grand Hyatt Hotel, Jakarta</h5>
                                    <p></p>
                                </div>
                                </div>
                                <div class="carousel-item">
                                <img src="<?php echo base_url('assets/images/hyatt-3.jpg'); ?>" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 style="color: white;">Grand Hyatt Hotel, Jakarta</h5>
                                    <p></p>
                                </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" style="background-color: transparent;border:none;" type="button" data-target="#carouselExampleCaptions" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </button>
                            <button class="carousel-control-next" style="background-color: transparent;border:none;" type="button" data-target="#carouselExampleCaptions" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </button>
                            </div>
					</div>
				</div>
			</div>
		</section>

