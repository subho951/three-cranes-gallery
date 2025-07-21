$("document").ready(function ($) {
    var nav = $('.nav-section');

    $(window).scroll(function () {
        if ($(this).scrollTop() > 200) {
            nav.addClass("f-nav");
        } else {
            nav.removeClass("f-nav");
        }
    });
});
$(document).ready(function () {
    var scrollTop = $(".scrollTop");
    $(window).scroll(function () {
        var topPos = $(this).scrollTop();
        if (topPos > 100) {
            $(scrollTop).css("opacity", " 0.9");

        } else {
            $(scrollTop).css("opacity", "0");
        }
    });
    $(scrollTop).click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 800);
        return false;

    });
});
$(document).ready(function ($) {
    $('.menu-home-container').stellarNav({
        breakpoint: 667,
        position: 'right',
    });
});

$(document).ready(function () {

    var $links = $('.navigation-section .menu-home-container li a');
    $links.click(function () {
        $links.removeClass('active');
        $(this).addClass('active');
    });

});

$(document).ready(function () {
    $(".group1").colorbox({ rel: 'group1', maxWidth: '100%', maxHeight: '100%' });
    $('.non-retina').colorbox({ rel: 'group5', transition: 'none' })
    $('.retina').colorbox({ rel: 'group5', transition: 'none', retinaImage: true, retinaUrl: true });

    //Example of preserving a JavaScript event for inline calls.
    $("#click").click(function () {
        $('#click').css({ "background-color": "#f00", "color": "#fff", "cursor": "inherit" }).text("Open this window again and this message will still be here.");
        return false;
    });
});
$(document).ready(function () {
    const glightbox = GLightbox({
        selector: '.glightbox'
    });
});

$(document).ready(function () {
    $("#banner-slider").owlCarousel({
        lazyLoad: true,
        lazyFollow: true,
        loop: true,
        mouseDrag: true,
        touchDrag: true,
        pullDrag: false,
        rewind: true,
        autoplay: true,
        margin: 0,
        dots: false,
        slideSpeed: 100,
        paginationSpeed: 800,
        rewindSpeed: 2000,
        responsive: true,
        responsiveRefreshRate: 200,
        responsiveBaseWidth: window,
        nav: true,
        navText: ["<img class= 'arrow' src = 'public/material/frontend/images/icon7.png' > ", "<img class='arrow' src='public/material/frontend/images/icon8.png'>"],
       
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 1
              },
        
              1024: {
                items: 1
              },
        
              1399: {
                items: 1
              }
        }
    });

});

$(document).ready(function () {
    $("#testimonial-slider").owlCarousel({
        lazyLoad: true,
        lazyFollow: true,
        loop: true,
        mouseDrag: true,
        touchDrag: true,
        pullDrag: false,
        autoplay: true,
        dots: false,
        slideSpeed: 100,
        responsive: true,
         nav: true,
        navText: ["<img class= 'arrow' src = 'public/material/frontend/images/icon10.png' > ", "<img class='arrow' src='public/material/frontend/images/icon11.png'>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
              },
        
              1024: {
                items: 3
              },
        
              1366: {
                items: 3
              }
        }
    });

});
$(document).ready(function () {
    $("#popular-product-slider").owlCarousel({
        lazyLoad: true,
        lazyFollow: true,
        loop: true,
        mouseDrag: true,
        touchDrag: true,
        pullDrag: false,
        autoplay: true,
   
        dots: false,
        slideSpeed: 100,
        responsive: true,
         nav: true,
        navText: ["<img class= 'arrow' src = 'public/material/frontend/images/icon10.png' > ", "<img class='arrow' src='public/material/frontend/images/icon11.png'>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
              },
        
              1024: {
                items: 3
              },
        
              1366: {
                items: 4
              }
        }
    });

});



$(document).ready(function () {
    $("#best-sellers-slider").owlCarousel({
        lazyLoad: true,
        lazyFollow: true,
        loop: true,
        mouseDrag: true,
        touchDrag: true,
        pullDrag: false,
        autoplay: true,
    
        dots: false,
        slideSpeed: 100,
        responsive: true,
         nav: true,
        navText: ["<img class= 'arrow' src = 'public/material/frontend/images/icon7.png' > ", "<img class='arrow' src='public/material/frontend/images/icon8.png'>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
              },
        
              1024: {
                items: 3
              },
        
              1366: {
                items: 4
              }
        }
    });

});
$(document).ready(function () {
    $("#latest-products-section-slider").owlCarousel({
        lazyLoad: true,
        lazyFollow: true,
        loop: true,
        mouseDrag: true,
        touchDrag: true,
        pullDrag: false,
        autoplay: true,
   
        dots: false,
        slideSpeed: 100,
        responsive: true,
         nav: true,
        navText: ["<img class= 'arrow' src = 'public/material/frontend/images/icon10.png' > ", "<img class='arrow' src='public/material/frontend/images/icon11.png'>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
              },
        
              1024: {
                items: 3
              },
        
              1366: {
                items: 4
              }
        }
    });

});

$(document).ready(function () {
var QtyInput = (function () {
    var $qtyInputs = $(".qty-input");

    if (!$qtyInputs.length) {
        return;
    }

    var $inputs = $qtyInputs.find(".product-qty");
    var $countBtn = $qtyInputs.find(".qty-count");
    var qtyMin = parseInt($inputs.attr("min"));
    var qtyMax = parseInt($inputs.attr("max"));

    $inputs.change(function () {
        var $this = $(this);
        var $minusBtn = $this.siblings(".qty-count--minus");
        var $addBtn = $this.siblings(".qty-count--add");
        var qty = parseInt($this.val());

        if (isNaN(qty) || qty <= qtyMin) {
            $this.val(qtyMin);
            $minusBtn.attr("disabled", true);
        } else {
            $minusBtn.attr("disabled", false);

            if (qty >= qtyMax) {
                $this.val(qtyMax);
                $addBtn.attr('disabled', true);
            } else {
                $this.val(qty);
                $addBtn.attr('disabled', false);
            }
        }
    });

    $countBtn.click(function () {
        var operator = this.dataset.action;
        var $this = $(this);
        var $input = $this.siblings(".product-qty");
        var qty = parseInt($input.val());

        if (operator == "add") {
            qty += 1;
            if (qty >= qtyMin + 1) {
                $this.siblings(".qty-count--minus").attr("disabled", false);
            }

            if (qty >= qtyMax) {
                $this.attr("disabled", true);
            }
        } else {
            qty = qty <= qtyMin ? qtyMin : (qty -= 1);

            if (qty == qtyMin) {
                $this.attr("disabled", true);
            }

            if (qty < qtyMax) {
                $this.siblings(".qty-count--add").attr("disabled", false);
            }
        }

        $input.val(qty);
    });
})();

});


$(document).ready(function () {
      jQuery(document).ready(function () {
         var sync1 = jQuery("#sync1");
         var sync2 = jQuery("#sync2");
         var slidesPerPage = 5;
         var syncedSecondary = true;

         sync1
            .owlCarousel({
               items: 1,
               slideSpeed: 3000,
               nav: true,

               //   animateOut: 'fadeOut',
               animateIn: "fadeIn",
               autoplayHoverPause: true,
               autoplaySpeed: 1400,
               dots: false,
               loop: true,
               responsiveClass: true,
               responsive: {
                  0: {
                     item: 1,
                     autoplay: false
                  },
                  600: {
                     items: 1,
                     autoplay: true
                  }
               },
               responsiveRefreshRate: 200,
               navText: [
                  '<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #fff;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>',
                  '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #fff;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'
               ]
            })
            .on("changed.owl.carousel", syncPosition);

         sync2
            .on("initialized.owl.carousel", function () {
               sync2
                  .find(".owl-item")
                  .eq(0)
                  .addClass("current");
            })
            .owlCarousel({
               items: slidesPerPage,
               dots: true,
               //   nav: true,
               smartSpeed: 1000,
               slideSpeed: 1000,
               slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
               responsiveRefreshRate: 100
            })
            .on("changed.owl.carousel", syncPosition2);

         function syncPosition(el) {
            //if you set loop to false, you have to restore this next line
            //var current = el.item.index;

            //if you disable loop you have to comment this block
            var count = el.item.count - 1;
            var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

            if (current < 0) {
               current = count;
            }
            if (current > count) {
               current = 0;
            }

            //end block

            sync2
               .find(".owl-item")
               .removeClass("current")
               .eq(current)
               .addClass("current");
            var onscreen = sync2.find(".owl-item.active").length - 1;
            var start = sync2
               .find(".owl-item.active")
               .first()
               .index();
            var end = sync2
               .find(".owl-item.active")
               .last()
               .index();

            if (current > end) {
               sync2.data("owl.carousel").to(current, 100, true);
            }
            if (current < start) {
               sync2.data("owl.carousel").to(current - onscreen, 100, true);
            }
         }

         function syncPosition2(el) {
            if (syncedSecondary) {
               var number = el.item.index;
               sync1.data("owl.carousel").to(number, 100, true);
            }
         }

         sync2.on("click", ".owl-item", function (e) {
            e.preventDefault();
            var number = jQuery(this).index();
            sync1.data("owl.carousel").to(number, 300, true);
         });
      });
});
$(':radio').change(function() {
  // console.log('New star rating: ' + this.value);
});