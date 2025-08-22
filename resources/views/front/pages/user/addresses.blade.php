<style type="text/css">
    .pac-container {
        z-index: 10000 !important;
    }
</style>
<script>
    let autocomplete;
    let address1Field;
    let address2Field;
    let postalField;

    function initAutocomplete() {
        address1Field = document.querySelector("#address1");
        address2Field = document.querySelector("#street_no1");
        postalField = document.querySelector("#zipcode1");
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            componentRestrictions: {
                country: ["us", "ca"]
            },
            fields: ["address_components", "geometry", "formatted_address"],
            types: ["address"],
        });
        address1Field.focus();
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";
        for (const component of place.address_components) {
            const componentType = component.types[0];
            switch (componentType) {
                case "postal_code": {
                    postcode = `${component.long_name}${postcode}`;
                    break;
                }
                case "postal_code_suffix": {
                    postcode = `${postcode}-${component.long_name}`;
                    break;
                }
                case "street_number": {
                    document.querySelector("#street_no1").value = component.long_name;
                    break;
                }
                case "route": {
                    document.querySelector("#locality1").value = component.long_name;
                    break;
                }
                case "locality": {
                    document.querySelector("#city1").value = component.long_name;
                    break;
                }
                case "administrative_area_level_1": {
                    document.querySelector("#state1").value = component.short_name;
                    break;
                }
                case "country":
                    document.querySelector("#country1").value = component.short_name;
                    break;
            }
        }
        address1Field.value = place.formatted_address;
        postalField.value = postcode;
        document.querySelector("#lat1").value = place.geometry.location.lat();
        document.querySelector("#lng1").value = place.geometry.location.lng();
        address2Field.focus();
    }
    window.initAutocomplete = initAutocomplete;
</script>
<?php

use App\Helpers\Helper;
?>
<h6 class="add-h6">The following address will be used on the checkout page by default</h6>
@if(session('success_message'))
<h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
@endif
@if(session('error_message'))
<h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
@endif
<div class="row">
    <div class="col-lg-6">
        <h4 class="bill-add">Billing Address</h4>
        <button class="btn btn-warning shadow" data-bs-toggle="modal" data-bs-target="#billingAddressModal">Add</button>
        <?php if (count($getBillingAddrs) > 0) {
            foreach ($getBillingAddrs as $row) { ?>
                <div class="card my-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <p><?= $row->title ?></p>
                        <a href="<?= url('user/addresses-delete/' . Helper::encoded($row->id)) ?>" onclick="return confirm('Do you want to delete this billing address');">
                            <button class="btn btn-sm btn-danger">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash" class="svg-inline--fa fa-trash "
                                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="currentColor"
                                        d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z">
                                    </path>
                                </svg>
                            </button>
                        </a>
                    </div>
                    <div class="card-body">
                        <p><?= $row->address ?> <?= $row->street_no ?>, <?= $row->locality ?>, <?= $row->city ?> <?= $row->zipcode ?>, <?= $row->state ?>, <?= $row->country ?></p>
                    </div>
                </div>
            <?php }
        } else { ?>
            <P>You have not set up this type of address yet.</P>
        <?php } ?>
    </div>
    <div class="col-lg-6">
        <h4 class="bill-add">Shipping Address</h4>
        <button class="btn btn-warning shadow" data-bs-toggle="modal" data-bs-target="#billingAddressModal">Add</button>
        <?php if (count($getShippingAddrs) > 0) {
            foreach ($getShippingAddrs as $row) { ?>
                <div class="card my-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <p><?= $row->title ?></p>
                        <a href="<?= url('user/addresses-delete/' . Helper::encoded($row->id)) ?>" onclick="return confirm('Do you want to delete this billing address');">
                            <button class="btn btn-sm btn-danger">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash" class="svg-inline--fa fa-trash "
                                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="currentColor"
                                        d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z">
                                    </path>
                                </svg>
                            </button>
                        </a>
                    </div>
                    <div class="card-body">
                        <p><?= $row->address ?> <?= $row->street_no ?>, <?= $row->locality ?>, <?= $row->city ?> <?= $row->zipcode ?>, <?= $row->state ?>, <?= $row->country ?></p>
                    </div>
                </div>
            <?php }
        } else { ?>
            <P>You have not set up this type of address yet.</P>
        <?php } ?>
    </div>
</div>

<!-- address-model -->
<div class="modal fade" id="billingAddressModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title h4">Add Billing/Shipping Address</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mode" value="address">
                    <div class="row form-style1">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="type1">Type *</label>
                                <select name="type" id="type1" class="form-control form-control-lg" required>
                                    <option value="" selected>Select Type</option>
                                    <option value="BILLING">BILLING</option>
                                    <option value="SHIPPING">SHIPPING</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="title1">Title *</label>
                                <input type="text" name="title" id="title1" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="address1">Address *</label>
                                <input type="text" name="address" id="address1" class="form-control form-control-lg" required>
                                <input type="hidden" name="lat" id="lat1">
                                <input type="hidden" name="lng" id="lng1">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="country1">Country *</label>
                                <input type="text" name="country" id="country1" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="state1">State *</label>
                                <input type="text" name="state" id="state1" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="city1">City *</label>
                                <input type="text" name="city" id="city1" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="locality1">Locality *</label>
                                <input type="text" name="locality" id="locality1" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="street_no1">Street No *</label>
                                <input type="text" name="street_no" id="street_no1" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="zipcode1">Zipcode *</label>
                                <input type="text" name="zipcode" id="zipcode1" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary ms-2 cus-btn" type="submit">Add Address</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMbNCogNokCwVmJCRfefB6iCYUWv28LjQ&libraries=places&callback=initAutocomplete&libraries=places&v=weekly"></script>