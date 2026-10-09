<section class="hero-wrap hero-wrap-2" style="background-image: url('<?php echo base_url('assets/images/Jakarta-Skyline-7.jpg'); ?>');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-2 bread">Form Registration Sponsorship</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Form Registration Sponsorship <i class="ion-ios-arrow-forward"></i>  Form International Registration</span></p>
          </div>
        </div>
      </div>
    </section>



    <section class="ftco-section testimony-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-12 text-center heading-section ftco-animate">
                    <span class="subheading">REGISTRATION SPONSORSHIP</span>
                    <h2 class="mb-4" style="font-family: 'Roboto', sans-serif;font-weight: 900;">
                        REGISTRATION FORM SPONSORSHIP  <?php echo $package['name']; ?></h2>
                    
                    <p>Welcome to the online registration site for the<br> 
                    <b>Spnsorship PERADI-LAWASIA Belt and Road Initiative & Employment Law Conference 2025</b><br>
                    Please complete the details below and click <b>'Submit Data</b> to proceed with your Registration Sponsorship.
                    </p>
                    <?php if ( $this->session->flashdata('success_message') ) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Selamat, data **peserta** berhasil **<?= $this->session->flashdata('success_message'); ?>**.
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
                



                <div class="col-md-12 text-center mt-5 heading-section ftco-animate">
                    <form class="form-card" action="" method="post" enctype="multipart/form-data">

                    <div class="row d-flex mb-2 contact-info">
                        <div class="col-md-12 mb-4">
                            <h4 class="h4 text-left">Sponsorship Package Selected</h4>
                        </div>
                        <div class="form-group col-sm-12 flex-column d-flex"> 
                            <input type="text" id="title" name="title" placeholder="" value="<?php echo $package['name']; ?> - <?php echo $package['price']; ?> (<?php echo $package['equiv']; ?>)" disabled>
                            <input type="hidden" id="sponsor" name="sponsor" placeholder="" value="<?php echo $package['name']; ?> - <?php echo $package['price']; ?> (<?php echo $package['equiv']; ?>)">
                        </div>
                        
                    </div>
                        
                        <div class="row d-flex mt-5 mb-3 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Organization Contact Information</h4>
                                <p class="text-left">Please indicate how you would prefer your organization's name and contact information to be listed in the conference materials.</p>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Contact Name<span class="text-danger"> *</span></label> 
                                <input type="text" id="name" name="name" placeholder="" onblur="validate(3)">
                                <span style="color: #f86c61"> <?= form_error('name'); ?>  </span>
                            </div>
                            
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Position<span class="text-danger"> *</span></label> 
                                <input type="text" id="position" name="position" placeholder="" onblur="validate(4)">
                                <span style="color: #f86c61"> <?= form_error('position'); ?>  </span> 
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Organization<span class="text-danger"> *</span></label> 
                                <input type="text" id="organisation" name="organisation" placeholder="" onblur="validate(3)">
                                <span style="color: #f86c61"> <?= form_error('organisation'); ?>  </span>
                            </div>
                            
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Address<span class="text-danger"> *</span></label> 
                                <input type="text" id="address" name="address" placeholder="" onblur="validate(4)">
                                <span style="color: #f86c61"> <?= form_error('address'); ?>  </span> 
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">City<span class="text-danger"> *</span></label> 
                                <input type="text" id="city" name="city" placeholder="" onblur="validate(3)">
                                <span style="color: #f86c61"> <?= form_error('city'); ?>  </span>
                            </div>
                            
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">State/Province<span class="text-danger"> *</span></label> 
                                <input type="text" id="state" name="state" placeholder="" onblur="validate(4)">
                                <span style="color: #f86c61"> <?= form_error('state'); ?>  </span> 
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Postcode<span class="text-danger"> *</span></label> 
                                <input type="text" id="postcode" name="postcode" placeholder="" onblur="validate(3)">
                                <span style="color: #f86c61"> <?= form_error('postcode'); ?>  </span>
                            </div>
                            
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Country<span class="text-danger"> *</span></label> 
                                <select type="text" id="country" name="country" placeholder="" onblur="validate(4)">
                                    <option value=''>---- Select Country ----</option>
                                        <?php foreach ($country as $negara):?>
                                        <option value='<?= $negara['nicename'] ?>'><?= $negara['nicename'] ?></option>
                                        <?php endforeach; ?>
                                </select>
                                <span style="color: #f86c61"> <?= form_error('country'); ?>  </span>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Contact Email Address<span class="text-danger"> *</span></label> 
                                <input type="text" id="FirsName" name="email" placeholder="" onblur="validate(3)">
                                <span style="color: #f86c61"> <?= form_error('email'); ?>  </span>
                            </div>
                            
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Telephone<span class="text-danger"> *</span></label> 
                                <input type="text" id="SurName" name="telephone" placeholder="" onblur="validate(4)"> 
                                <span style="color: #f86c61"> <?= form_error('telephone'); ?>  </span>
                            </div>
                        </div>


                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Website<span class="text-danger"> *</span></label> 
                                <input type="text" id="FirsName" name="website" placeholder="" onblur="validate(3)">
                                <span style="color: #f86c61"> <?= form_error('website'); ?>  </span>
                            </div>
                            
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Upload logo<span class="text-danger"> *</span></label> 
                                <input type="file" id="logo" name="logo" placeholder="" onblur="validate(4)">
                                <p>Please supply your organization logo (<b>high resolution, jpg, jpeg, png or svg format</b>)</p> 
                                <span style="color: #f86c61"> <?= form_error('logo'); ?>  </span>
                            </div>
                        </div>


                        <div class="row d-flex mt-5 mb-3 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Fulfilment Contact Information </h4>
                                <p class="text-left">Please provide a contact for sponsorship / exhibitor fulfillment (if different from above).</p>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Contact Name	</label> 
                                <input type="text" id="contact_name" name="contact_name" placeholder="" onblur="validate(3)">
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Contact Email Address</label> 
                                <input type="text" id="contact_email" name="contact_email" placeholder="" onblur="validate(4)">
                                <span style="color: #f86c61"> <?= form_error('contact_email'); ?>  </span>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Telephone</label> 
                                <input type="text" id="contact_telepon" name="contact_telephone" placeholder="" onblur="validate(3)"> 
                            </div>

                        </div>

                        
                        <div class="row d-flex mt-5 mb-1 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Payment of Registration Fee </h4>
                                <p class="text-left">Payment may be made by way of transfer to the following bank account:<br>
                                Account Bank: <b>PT Bank Central Asia Tbk</b><br>
                                Account Name: <b>Perhimpunan Advokat Indonesia</b><br> 
                                Account Number: <b>3353024708</b><br>
                                Swift Code: <b>CENAIDJA</b><br>
                                Bank Address: Menara BCA Grand Indonesia, Jl. M.H. Thamrin No.1, Menteng, Jakarta Pusat, Daerah Khusus Jakarta 10310, Indonesia<br>
                                Please make the following note when making the transfer: <b>“PERADI-LAWASIA 2025 Seminar”</b><br><br>
                                </p>
                            </div>
                        </div>

                         <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-12 flex-column d-flex"> 
                                <label class="form-control-label px-3">Transfer payment slip (upload file size max 1 MB)<span class="text-danger"> *</span></label> 
                                <input type="file" name="bukti" placeholder="" onblur="validate(3)"> </div>
                                <span style="color: #f86c61"> <?= form_error('bukti'); ?>  </span>
                            </div>
                            
                            
                        
                        <br>

                        <div class="row d-flex mt-3 mb-3 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Acceptance of Terms and Conditions</h4>
                            
                                <ol class="text-left strong">
                                    <li class="text-left">
                                        <b>General</b><br>
                                        <p class="text-left">The term “Organizers” refers to, collectively, Perhimpunan Advokat Indonesia (Indonesian Advocates Association or PERADI) and members of the conference organizing committee.</p>
                                        <p class="text-left">The term “Sponsor” refers to the organization / firm listed on the Application Form.</p>
                                        <p class="text-left">A “Contract” is formed between the Organizers and the Sponsor when the Organizers accept the signed Application Form and/or receive the payment of the sponsorship. By submitting a Sponsorship Application Form you are accepting these terms and conditions.</p>
                                    </li>

                                    <li class="text-left">
                                        <b>Acceptance of Application</b><br>
                                        <p class="text-left">PERADI will issue an acknowledgment of receipt once a Sponsorship Application Form has been submitted and the payment has been received.</p>
                                    </li>
                                    <li class="text-left">
                                        <b>Payment</b><br>
                                        <p class="text-left">Payment is required within 3 (three) days of the submission of the Sponsorship Application Form and before the conference commences. Sponsorship entitlements do not commence until payment has been received.</p>
                                    </li>
                                    <li class="text-left">
                                        <b>Cancellation of Sponsorship</b><br>
                                        <p class="text-left">Booking Withdrawal – if a Sponsor withdraws its commitment, it needs to notify the Organizers in writing and it will be liable for the following payments in accordance with the below:</p>
                                        <ul type="square">
                                            <li>1 month or more prior to the first day of the event – 50% refund on sponsorship and exhibition amount;</li>
                                            <li>̵Less than 1 month prior to the first day of the event – No refund on sponsorship and exhibition amount; and</li>
                                            <li>If notice of cancellation is received and full payment for funds owing has not been received, the Sponsor shall pay the difference to satisfy the above and below cancellation and refund policy. Payments must be made within 3 (three) days of receipt of a request from the Organizers.</li>
                                        </ul>
                                    </li>
                                    <li class="text-left">
                                        <b>Terms and Conditions</b>
                                        <ol type="a">
                                            <li>
                                                <b>Cancellation or postponement of conference</b><br>
                                                If the event is cancelled or postponed due to unforeseen circumstances, the Organizers will not be liable to the Sponsor for any loss incurred by the Sponsor as a result of the cancellation. However, all monies paid for any sponsorship and exhibition will be refunded or transferred to the postponed date. An unforeseen event / circumstances could include, but is not limited to, an infectious disease outbreak; industrial disruptions; service provider failures; governmental restrictions or regulations; war or apparent act of war; terrorism or apparent act of terrorism; disaster; civil disorder, disturbance, and/or riots; curtailment, or any other emergency.
                                            </li>
                                            <li>
                                                <b>Disclaimer and changes</b><br>
                                                Every effort has been made to present all the information accurately. However, no liability is accepted for any inaccuracy and the Organizers reserve the right to change any published information. The Organizers reserve the right to amend, update or delete sponsorship packages.
                                            </li>
                                            <li>
                                                <b>Correspondence</b>
                                                When booking please appoint a single point of contact within your organization. All correspondence will be sent to and liaison with respect to Sponsorship entitlements will be with this person.
                                            </li>
                                            <li>
                                                <b>Representatives</b><br>
                                                Anyone from your organization attending the conference – other than those entitled to complimentary attendance as part of your package, if any, – or guests you wish to invite to the conference must register for the conference via the official conference registration site.
                                            </li>
                                        </ol>
                                    </li>


                                </ol>

                                <p class="text-left">By submit this Application and Contract, the Sponsor / Exhibitor agrees to abide by all terms as printed in the Terms & Conditions.</p>
                            </div>
                        </div>


                        
                        <div class="row justify-content-end">
                            <div class="form-group col-sm-6 float-lft"> <button type="submit" class="btn-block btn-primary">Submit</button> </div>
                        </div>
                    </form>
                </div>
                    
                </div>
            </div>
        </div>
    </section>