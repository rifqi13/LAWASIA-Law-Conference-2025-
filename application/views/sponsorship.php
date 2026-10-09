<style>
  .check{
    font-size:24px;font-weight:800
  }
</style>

<section class="hero-wrap hero-wrap-2" style="background-image: url('<?php echo base_url('assets/images/Jakarta-Skyline-7.jpg'); ?>');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-2 bread">Sponsorship Opportunities</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Sponsorship Opportunities <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
		  	<div class="container">
			  	<div class="row">
            <div class="col-lg-12 ftco-animate fadeInUp ftco-animated">

            <?php if ( $this->session->flashdata('success_message') ) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Congratulation, your sponsorship data has <?= $this->session->flashdata('success_message'); ?>**.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>

                    <?php if ( $this->session->flashdata('error_upload') ) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Terjadi kesalahan : **<?= $this->session->flashdata('error_upload'); ?>**.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>
              <h2 class="mb-3">SPONSORSHIP OPPORTUNITIES</h2>
              <p>On behalf of Perhimpunan Advokat Indonesia (Indonesia Advocates Association, or PERADI), 
                we are pleased to invite your esteemed organization to become a sponsor for the upcoming 
                PERADI - LAWASIA Belt and Road Initiative & Employment Law Conference 2025, 
                scheduled to take place on 10 and 11 August 2025 at Grand Hyatt Hotel, Jakarta, Indonesia.</p>

                <p>The Conference is hosted by PERADI in collaboration with LAWASIA, 
                  an international association of lawyers.</p>

                <p>We are also pleased to inform you that our PERADI President who is also the 
                  Vice Coordinating Minister of Law, Human Rights, Immigration and Correctional 
                  Facilities will be the Keynote Speaker and officially open the Conference.</p>

                <p>This event promises to bring together Belt & Road Initiative leading lawyers and 
                  prominent employment/labor lawyers in Asia to discuss policy updates on the Belt & Road 
                  Initiative as well as development and updates on current trend of employment laws and 
                  regulations in Asian countries. Your sponsorship would be instrumental in the success 
                  of this vital gathering, offering unparalleled visibility and engagement opportunities 
                  with a highly relevant audience.</p>

                <p><a href="<?php echo base_url('assets/PROPOSAL SPONSORSHIP.pdf'); ?>" class="btn btn-primary px-4 py-3 mt-3" download>DOWNLOAD SPONSORHIP PROPOSAL</a></p>

                <p>&nbsp;</p>

                <h2 class="mb-3">Sponsorship Packages Options:</h2>
            <div class="row">
                <div class="card col-lg-3 m-3">
                  <div class="card-body">
                    <h4 class="card-title text-center text-warning"><b>GOLD SPONSORSHIP</b></h4>
                    <h5 class="card-text text-center">Rp100.000.000,-</h5>
                    <p class="text-center">(or equivalent to US$6,150)</p>
                    <a href="<?php echo site_url('sponsorship/select/1'); ?>" class="btn btn-primary col-lg-12">Select</a>
                  </div>
                </div>

                <div class="card col-lg-3 m-3">
                  <div class="card-body">
                    <h4 class="card-title text-center text-secondary"><b>SILVER SPONSORSHIP</b></h4>
                    <h5 class="card-text text-center">Rp80.000.000,-</h5>
                    <p class="text-center">(or equivalent to US$4,900)</p>
                    <a href="<?php echo site_url('sponsorship/select/2'); ?>" class="btn btn-primary col-lg-12">Select</a>
                  </div>
                </div>

                <div class="card col-lg-3 m-3">
                  <div class="card-body">
                    <h4 class="card-title text-center text-info"><b>SESSION SPONSORSHIP</b></h4>
                    <h5 class="card-text text-center">Rp55.000.000,-</h5>
                    <p class="text-center">(or equivalent to US$3,375)</p>
                    <a href="<?php echo site_url('sponsorship/select/3'); ?>" class="btn btn-primary col-lg-12">Select</a>
                  </div>
                </div>

                

                <div class="card col-lg-3 m-3">
                  <div class="card-body">
                    <h4 class="card-title text-center text-success"><b>SOUVENIR SPONSORSHIP</b></h4>
                    <h5 class="card-text text-center">Rp45.000.000,-</h5>
                    <p class="text-center">(or equivalent to US$2,750)</p>
                    <button href="#" class="btn btn-primary col-lg-12" disabled>Select</button>
                    <span style="font-size:12px;color:red;line-height:0.5 !important;text-align:center !important;">Unfortunately, this category is no longer available.</span>
                  </div>
                </div>

                <div class="card col-lg-3 m-3">
                  <div class="card-body">
                    <h4 class="card-title text-center text-danger"><b>LANYARD SPONSORSHIP</b></h4>
                    <h5 class="card-text text-center">Rp15.000.000,-</h5>
                    <p class="text-center">(or equivalent to US$920)</p>
                    <button href="#" class="btn btn-primary col-lg-12" disabled>Select</button>
                    <span style="font-size:12px;color:red;line-height:0.5 !important;text-align:center !important;">Unfortunately, this category is no longer available.</span>
                  </div>
                </div>

                <div class="card col-lg-3 m-3">
                  <div class="card-body">
                    <h4 class="card-title text-center text-primary"><b>BOOTH SPONSORSHIP</b></h4>
                    <h5 class="card-text text-center">
                      Rp25.000.000,-
                    </h5>
                    <p class="text-center">(or equivalent to US$1,530)</p>
                    <a href="<?php echo site_url('sponsorship/select/4'); ?>" class="btn btn-primary col-lg-12">Select</a>
                  </div>
                </div>


            </div>

                
                <p>&nbsp;</p>
                
                <h2 class="mb-3">Contact us to discuss your sponsorship opportunities!</h2>
                <p>If you wish to sponsor the conference or would like more information, please contact:</p>
                
                <p>
                  <b>Ms. Lia Alizia</b><br>
                  <a href="mailto:l.alizia@alizialawoffice.com">l.alizia@alizialawoffice.com</a>
                </p>

                <p>
                  <b>Ms. Rima Baskoro</b><br>
                  <a href="mailto:rima.g.baskoro@gmail.com">rima.g.baskoro@gmail.com</a>
                </p>

                <p>&nbsp;</p>
                
                <h2 class="mb-3">Conference Sponsorship Packages</h2>

                <table style='width:100%;border:1px solid #000;' class="table">
                  <tr>
                    <td><b>Category</b></td>
                    <td class="table-warning text-center">Gold Sponsor</td>
                    <td class="table-active text-center">Silver Sponsor</td>
                    <td class="table-info   text-center">Session Sponsor</td>
                    <td class="table-success text-center">Souvenir Sponsor</td>
                    <td class="table-danger text-center">Lanyard Sponsor</td>
                    <td class="table-primary text-center">Booth Sponsor</td>
                  </tr>
                  <tr>
                    <td><b>Sponsorship amount</b></td>
                    <td class="table-warning text-center">Rp100.000.000,-<p>(or equivalent to US$6,150)</p></td>
                    <td class="table-active text-center">Rp80.000.000,-<p>(or equivalent to US$4,900)</p></td>
                    <td class="table-info text-center">Rp55.000.000,-<p>(or equivalent to US$3,375)</p></td>
                    <td class="table-success text-center">Rp45.000.000,-<p>(or equivalent to US$2,750)</p></td>
                    <td class="table-danger text-center">Rp15.000.000,-<p>(or equivalent to US$920)</p></td>
                    <td class="table-primary text-center">Rp25.000.000,-<p>(or equivalent to US$1,530)</p></td>
                  </tr>
                  <tr>
                    <td><b>Maximum number of sponsorship</b></td>
                    <td class="table-warning text-center">2</td>
                    <td class="table-active text-center">3</td>
                    <td class="table-info text-center">12</td>
                    <td class="table-success text-center">1</td>
                    <td class="table-danger text-center">1</td>
                    <td class="table-primary text-center">3</td>
                  </tr>
                  <tr>
                    <td><b>Logo on conference website</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">✓</span> </td>
                    <td class="table-info text-center"> <span class="check">✓</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Logo on acknowledgement slide of the conference </b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">✓</span> </td>
                    <td class="table-info text-center"> <span class="check">✓</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Displayed in final Conference Program Session</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">✓</span> </td>
                    <td class="table-info text-center"> <span class="check">✓</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Bring the merchandise  - insert to be supplied</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">-</span> </td>
                    <td class="table-info text-center"> <span class="check">✓</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Opportunity to include one insert in the delegate satchel</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">-</span> </td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">✓</span> </td>
                    <td class="table-danger text-center"> <span class="check">✓</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Logo displayed on all promotional</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">✓</span> </td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Display of your organization's marketing material</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">✓</span> </td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">✓</span></td>
                  </tr>
                  <tr>
                    <td><b>Logo printed on the Souvenir </b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">✓</span> </td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">✓</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Logo printed on the Conference lanyards</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">✓</span> </td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">✓</span> </td>
                    <td class="table-danger text-center"> <span class="check">✓</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>A booth with standard chairs and table, and a television located outside the Ballroom</b></td>
                    <td class="table-warning text-center"> <span class="check">-</span> </td>
                    <td class="table-active text-center"> <span class="check">-</span> </td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">✓</span> </td>
                  </tr>
                  <tr>
                    <td><b>Compilentary delegate pass</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> (2 Person)</td>
                    <td class="table-active text-center"> <span class="check">✓</span> (1 Person)</td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Complimentary Gala Dinner pass</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> (2 Person)</td>
                    <td class="table-active text-center"> <span class="check">✓</span> (1 Person)</td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                  <tr>
                    <td><b>Airtime at the Closing Dinner and Networking Night</b></td>
                    <td class="table-warning text-center"> <span class="check">✓</span> </td>
                    <td class="table-active text-center"> <span class="check">✓</span> </td>
                    <td class="table-info text-center"> <span class="check">-</span> </td>
                    <td class="table-success text-center"> <span class="check">-</span> </td>
                    <td class="table-danger text-center"> <span class="check">-</span> </td>
                    <td class="table-primary text-center"><span class="check">-</span></td>
                  </tr>
                </table>

                     
                      <p>&nbsp;</p><p>&nbsp;</p>

                      <p>Please refer to the pdf of the <a href="<?php echo base_url('assets/PROPOSAL SPONSORSHIP.pdf'); ?>">Sponsorhip Proposal</a>.</p>
              
            </div>
          </div>
        </div>
    </section>