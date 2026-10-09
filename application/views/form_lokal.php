<section class="hero-wrap hero-wrap-2" style="background-image: url('<?php echo base_url('assets/images/Jakarta-Skyline-9.jpg'); ?>');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-2 bread">Form Local Registration</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Registration for Local <i class="ion-ios-arrow-forward"></i>  Form Local Registration</span></p>
          </div>
        </div>
      </div>
    </section>



    <section class="ftco-section testimony-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-12 text-center heading-section ftco-animate">
                    <span class="subheading">REGISTRATION FORM - LOCAL DELEGATES </span>
                    <h2 class="mb-4" style="font-family: 'Roboto', sans-serif;font-weight: 900;">
                        PERADI-LAWASIA Belt and Road Initiative & <br>
                        Employment Law Conference 2025!</h2>
                    
                    <p>Welcome to the online local registration site for the<br> 
                    <b>PERADI-LAWASIA Belt and Road Initiative & Employment Law Conference 2025</b><br>
                    Please complete the details below and click <b>‘Next’</b> to proceed with your registration.</p>
                
                
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
                            <h4 class="h4 text-left">Contact Details</h4>
                        </div>
                    </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Title/Salutation<span class="text-danger"> *</span></label> 
                                <input type="text" id="title" name="title" placeholder="">
                                <span style="color: #f86c61"> <?= form_error('title'); ?> </span>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Full Name<span class="text-danger"> *</span></label> 
                                <input type="text" id="name" name="name" placeholder="" onblur="validate(2)"> 
                                <span style="color: #f86c61"> <?= form_error('name'); ?> </span>
                            </div>
                        </div>
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Company/Firm<span class="text-danger"> *</span></label> 
                                <input type="text" id="organisation" name="organisation" placeholder="" onblur="validate(3)"> 
                                <span style="color: #f86c61"> <?= form_error('organisation'); ?> </span>
                            </div>
                            
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Position/Job Title</label> 
                                <input type="text" id="job" name="job" placeholder="" onblur="validate(4)"> 
                                <span style="color: #f86c61"> <?= form_error('job'); ?> </span>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Email Address<span class="text-danger"> *</span></label> 
                                <input type="text" id="email" name="email" placeholder="" onblur="validate(5)">
                                <span style="color: #f86c61"> <?= form_error('email'); ?>  </span>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Verify Email Address<span class="text-danger"> *</span></label> 
                                <input type="text" id="email-ver" name="email-ver" placeholder="" onblur="validate(4)"> 
                                <span style="color: #f86c61"> <?= form_error('email-ver'); ?>  </span>
                            </div>
                                
                        </div>
                        
                        <div class="row d-flex mt-5 mb-3 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Name Badge</h4>
                                <p class="text-left">These details will be used to create your name badge. Please indicate if different from personal details above.</p>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Preferred First Name<span class="text-danger"> *</span></label> 
                                <input type="text" id="FirsName" name="FirsName" placeholder="" onblur="validate(3)"> 
                                <span style="color: #f86c61"> <?= form_error('FirsName'); ?>  </span>
                            </div>
                            
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Preferred Surname<span class="text-danger"> *</span></label> 
                                <input type="text" id="SurName" name="SurName" placeholder="" onblur="validate(4)"> 
                                <span style="color: #f86c61"> <?= form_error('SurName'); ?> </span>
                            </div>
                        </div>


                        <div class="row d-flex mt-5 mb-3 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Primary Address </h4>
                                <p class="text-left">Details provided below will appear on your billing invoice/receipt.</p>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Line 1</label> 
                                <input type="text" id="AddLine1" name="AddLine1" placeholder="" onblur="validate(3)"> 
                                <span style="color: #f86c61"> <?= form_error('AddLine1'); ?> </span>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Line 2</label> 
                                <input type="text" id="AddLine2" name="AddLine2" placeholder="" onblur="validate(4)">
                                <span style="color: #f86c61"> <?= form_error('AddLine2'); ?>  </span>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">City</label> 
                                <input type="text" id="city" name="city" placeholder="" onblur="validate(3)"> 
                                <span style="color: #f86c61"> <?= form_error('city'); ?> </span>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">Province</label> 
                                <input type="text" id="state" name="state" placeholder="" onblur="validate(4)"> 
                                <span style="color: #f86c61"> <?= form_error('state'); ?> </span>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                <label class="form-control-label px-3">ZIP / Postcode</label> 
                                <input type="text" id="postcode" name="postcode" placeholder="" onblur="validate(3)"> 
                                <span style="color: #f86c61"> <?= form_error('postcode'); ?> </span>
                            </div>

                            
                        </div>


                        <div class="row d-flex mt-5 mb-1 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Additional Requirements </h4>
                                <p class="text-left">Please list any additional requirements (i.e. wheelchair access) and/or dietary requirements / food allergies you may have due to religious or health reasons only (i.e. Vegan, Halal, Vegetarian, Kosher, Pescatarian, any food allergy). If none, please leave blank. </p>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-12 flex-column d-flex"> 
                                <label class="form-control-label px-3"> </label> 
                                <textarea type="text" id="requirments" name="requirements" placeholder="" onblur="validate(3)"></textarea> 
                                <span style="color: #f86c61"> <?= form_error('requirements'); ?> </span>
                            </div>
                            
                        </div>


                        <div class="row d-flex mt-5 mb-1 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Payment of Registration Fee </h4>
                                <p class="text-left">Payment may be made by way of transfer to the following bank account:<br>
                                Account Bank: <b>PT Bank Central Asia Tbk</b><br>
                                Account Name: <b>Perhimpunan Advokat Indonesia</b><br>
                                Account Number: <b>3353024708</b><br><br>
                                Please make the following note when making the transfer: <b>“PERADI-LAWASIA 2025 Seminar”</b><br>
                                </p>
                                <h5 class="text-left"><label class="form-control-label px-3"><span class="text-danger"> <b>[Payment with Credit Card, Gopay, etc is coming soon.]</b></span></label> </h5>
                                
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-12 flex-column d-flex"> 
                                <label class="form-control-label px-3">Category<span class="text-danger"> *</span></label> 
                                <select type="text" id="registration_type" name="registration_type" placeholder="" onblur="validate(4)">
                                        <option value="">-- Select Package --</option>
                                        <option value="standard">Standard - <?php echo $currency == 'IDR' ? 'Rp '.number_format($prices['standard'], 0, ',', '.') : 'A$ '.number_format($prices['standard'], 2); ?></option>
                                        <option value="premium">Premium - <?php echo $currency == 'IDR' ? 'Rp '.number_format($prices['premium'], 0, ',', '.') : 'A$ '.number_format($prices['premium'], 2); ?></option>
                                        <option value="vip">VIP - <?php echo $currency == 'IDR' ? 'Rp '.number_format($prices['vip'], 0, ',', '.') : 'A$ '.number_format($prices['vip'], 2); ?></option>        
                                
                                
                                </select>
                                <p>&nbsp;</p>
                            </div>
                            
                            <div class="form-group col-sm-12 flex-column d-flex"> 
                                <label class="form-control-label px-3">Transfer payment slip (upload file size max 1 MB)<span class="text-danger"> *</span></label> 
                                <input type="file" name="bukti" placeholder="" onblur="validate(3)"> </div>
                                <span style="color: #f86c61"> <?= form_error('requirements'); ?> </span>
                        </div>
                        
                        <br><br>

                        <div class="row d-flex mt-3 mb-3 contact-info">
                            <div class="col-md-12 mb-4">
                                <h4 class="h4 text-left">Conference Information</h4>
                                <ul class="text-left">
                                    <li><b>Visas</b></li>
                                        <p>A Visa to enter Indonesia is the sole responsibility of participants. Please ensure that you have a valid visa to enter Indonesia.</p>
                                    <li><b>Insurance</b></li>
                                        <p>Participants are advised to effect their own insurance to cover all risks including (but not limited to) costs of 
                                        travel, accommodation, registration, injury / death, health and medical expenses, personal property and all other risks. 
                                        PERADI, LAWASIA and the conference organiser are not liable for claims under any circumstances. 
                                        This includes participation in any pre and post activities undertaken by participants and accompanying persons.</p>
                                        
                                    <li><b>Language</b></li>
                                        <p>All working sessions and materials will be in English, save for any address or speech by Indonesian Government Officials.</p>
                                        
                                    <li><b>Registration includes</b></li>
                                            <ul>
                                                <li>Access to Opening Reception</li>
                                                <li>Access to all conference sessions</li>
                                                <li>Conference materials, including any available speakers' papers</li>
                                                <li>Tea and coffee and light refreshments during session breaks</li>
                                                <li>Closing Dinner</li>
                                            </ul>
                                </ul>
                                <p class="text-left">Accommodation, Flights, Visas, and social activities are not included in the registration fee.</p>

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