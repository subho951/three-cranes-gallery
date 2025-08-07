<?php
use App\Models\Category;
use App\Models\Faq;
use App\Helpers\Helper;
?>
<section class="cart-details-list section-padding">
    <div class=" container-xxl container-xl container-lg container-md container-sm container">
        <h3><?=$page_header?></h3>
        <div class="row align-items-center mt-4 wow slideInRight" data-wow-delay="0.7s" data-wow-duration="2.5s" style="visibility: visible; animation-duration: 2.5s; animation-delay: 0.7s; animation-name: slideInRight;">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                <nav>
                    <div class="nav nav-tabs " id="nav-tab" role="tablist">
                        <?php if ($faqCats) {
                            $sl = 1;
                            foreach ($faqCats as $faqCat) { ?>
                                <button class="nav-link <?= (($sl == 1) ? 'active' : '') ?>" id="nav-<?= $sl ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?= $sl ?>" type="button" role="tab" aria-controls="nav-<?= $sl ?>" aria-selected="<?= (($sl == 1) ? 'true' : 'false') ?>"><?= $faqCat->name ?></button>
                        <?php $sl++;
                            }
                        } ?>
                    </div>
                </nav>
                <div class="tab-content p-3 border bg-light bt-none" id="nav-tabContent">
                    <?php if ($faqCats) {
                        $sl = 1;
                        foreach ($faqCats as $faqCat) { ?>
                            <div class="tab-pane fade <?= (($sl == 1) ? 'show active' : '') ?>" id="nav-<?= $sl ?>" role="tabpanel" aria-labelledby="nav-<?= $sl ?>-tab">
                                <div class="accordion" id="accordionExample">
                                    <?php
                                    $faqs = Faq::where('status', '=', 1)->where('faq_category_id', '=', $faqCat->id)->orderBy('rank', 'ASC')->get();
                                    if ($faqs) {
                                        $i = 1;
                                        foreach ($faqs as $faq) {
                                    ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $sl ?>-<?= $i ?>" aria-expanded="true" aria-controls="collapse<?= $sl ?>-<?= $i ?>"><?= $faq->question ?></button>
                                                </h2>
                                                <div id="collapse<?= $sl ?>-<?= $i ?>" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                                <div class="tab-description">
                                                                    <div class="course-list">
                                                                        <p><?= $faq->answer ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php $i++;
                                        }
                                    } ?>
                                </div>
                            </div>
                    <?php $sl++;
                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</section>