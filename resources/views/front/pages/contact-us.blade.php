<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Faq;
use App\Helpers\Helper;
?>
<section class="contact-details section-padding">
    <div class="container-xxl container-xl container-lg container-md container-sm container">
        <div class="row">
            @if(session('success_message'))
            <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
            @endif
            @if(session('error_message'))
            <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
            @endif
            <h2>Get In Touch</h2>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="contact_left">
                    <h2>Contact Information</h2>
                    <ul id="contact" class="contact-listing">
                        <li class="adress-details">
                            <div class="adress-icon">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </div>
                            <div class="adress-content">
                                <h5>Phone:</h5>
                                <p><a href="tel:<?= $generalSetting->site_phone ?>"><?= $generalSetting->site_phone ?></a></p>
                            </div>
                        </li>
                        <hr>
                        <li class="adress-details">
                            <div class="adress-icon">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </div>
                            <div class="adress-content">
                                <h5>Email:</h5>
                                <p><a href="mailto:<?= $generalSetting->site_mail ?>"><?= $generalSetting->site_mail ?></a></p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="contact_right">
                    <div class="row clearfix">
                        <form class="contact_form" id="frm" name="frm" method="POST" action="<?= url('contact') ?>">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="First Name" name="fname" id="fname" title="First Name is required" class="form-control requiredContact" data-check="First name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Last Name" name="lname" id="lname" title="Last Name is required" class="form-control requiredContact" data-check="Last name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" placeholder="Email" name="email" id="email" title="Email is required" class="form-control requiredContact" data-check="Email" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Phone" name="phone" id="phone" pattern="\S+" title="Phone is required" class="form-control requiredContact" data-check="Phone" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Subject" name="subject" id="subject" title="Subject is required" class="form-control requiredContact" data-check="Subject" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea placeholder="Message..." rows="5" name="message" id="message" title="Message is required" class="form-control requiredContact" data-check="Message" autocomplete="off"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="send_btn">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>