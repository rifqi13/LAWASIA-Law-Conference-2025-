    <section class="hero-wrap hero-wrap-2" style="background-image: url('<?php echo base_url('assets/images/tour.jpg'); ?>');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-2 bread">Registration City Tour  </h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Registration City Tour   <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>
    

<section class="ftco-section ftco-no-pt ftco-no-pb mt-5 mb-5 contact-section">
			<div class="container">
				<div class="row d-flex align-items-stretch no-gutters">
					<div class="col-md-6 p-4 p-md-5 order-md-last bg-light">
						<form action="<?php echo site_url('tour/process'); ?>" method="post">
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
                                <select class="form-control" name="tour_type">
                                    <option value="">--------- Option Tour ---------</option>
                                    <option value="Option 1 - Sightseeing Taman Mini">Option 1 - Sightseeing Taman Mini: Rp <?php echo number_format($prices['Option 1 - Sightseeing Taman Mini'], 0, ',', '.'); ?></option>
                                    <option value="Option 2 - Sightseeing Istiqlal Mosque, Katedral Jakarta, and Museum Nasional">Option 2 - Sightseeing Istiqlal Mosque, Katedral Jakarta, and Museum Nasional: Rp <?php echo number_format($prices['Option 2 - Sightseeing Istiqlal Mosque, Katedral Jakarta, and Museum Nasional'], 0, ',', '.'); ?></option>
                                </select>
                                <span style="color: #f86c61"> <?= form_error('tour_type'); ?>  </span>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <input type="submit" value="Registration Tour" class="btn btn-primary py-3 px-5">
                            </div>
                        </form>
                    </div>
				<div class="col-md-6 d-flex align-items-stretch">
						<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                <img src="<?php echo base_url('assets/images/tmii.jpg'); ?>" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 style="color: white;">Taman Mini Indonesia Indah</h5>
                                    <p></p>
                                </div>
                                </div>
                                <div class="carousel-item">
                                <img src="<?php echo base_url('assets/images/istiqlal-katedral.jpg'); ?>" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 style="color: white;">Istiqlal Mosque & Katedral Jakarta</h5>
                                    <p></p>
                                </div>
                                </div>
                                <div class="carousel-item">
                                <img src="<?php echo base_url('assets/images/museum-nasional.jpg'); ?>" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 style="color: white;">Museum Nasional</h5>
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

