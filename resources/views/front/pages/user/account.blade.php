<?php

use App\Helpers\Helper;
?>
@if(session('success_message'))
<h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
@endif
@if(session('error_message'))
<h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
@endif
<form method="POST" action="" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="mode" value="profile">
    <div class="card mb-4">
        <div class="card-body bg-light form-style">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-label" for="first_name">First Name *</label>
                        <input type="text" name="first_name" id="first_name" placeholder="John" class="form-control form-control-lg" required value="<?= $getUser->first_name ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-label" for="last_name">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Smith" class="form-control form-control-lg" required value="<?= $getUser->last_name ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-label" for="display_name">Display Name *</label>
                        <input type="text" name="display_name" id="display_name" placeholder="John" class="form-control form-control-lg" required value="<?= $getUser->display_name ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone *</label>
                        <input type="text" name="phone" id="phone" placeholder="Smith" class="form-control form-control-lg" required value="<?= $getUser->phone ?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label" for="email">Email *</label>
                        <input type="email" name="email" id="email" placeholder="test@gmail.com" class="form-control form-control-lg" required value="<?= $getUser->email ?>" readonly>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="profile-pic mb-3">
                        <?php if ($getUser->profile_image != '') { ?>
                            <img src="<?= env('UPLOADS_URL') . 'user/' . $getUser->profile_image ?>" style="width: 100px; height: 100px; border-radius: 50%;">
                        <?php } ?>
                        <div class="file-upload">
                            <input accept="image/*" type="file" name="profile_image" id="profile_image">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="camera" class="svg-inline--fa fa-camera " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M149.1 64.8L138.7 96 64 96C28.7 96 0 124.7 0 160L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64l-74.7 0L362.9 64.8C356.4 45.2 338.1 32 317.4 32L194.6 32c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group mb-2"><button class="themeBtn" type="submit"> Update Profile</button></div>
                </div>
            </div>
        </div>
    </div>
</form>
<h4 class="mb-3">Change Password</h4>
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form method="POST" action="<?= url('user/change-password') ?>">
    <input type="hidden" name="mode" value="change_password">
    <input type="hidden" name="id" value="<?= $getUser->id ?>">
    @csrf
    <div class="card">
        <div class="card-body form-style">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label" for="old_password">Current password (leave blank to leave unchanged)</label>
                        <input type="password" name="old_password" id="old_password" placeholder="************" class="form-control form-control-lg">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label" for="new_password">New password (leave blank to leave unchanged)</label>
                        <input type="password" name="new_password" id="new_password" placeholder="************" class="form-control form-control-lg">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm new password</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="************" class="form-control form-control-lg">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group mb-2"><button class="themeBtn" type="submit"> Save Password</button></div>
                </div>
            </div>
        </div>
    </div>
</form>