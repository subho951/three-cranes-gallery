<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="canonical" href="{{ url()->current() }}" />
  {!! $head ?? '' !!}
  @yield('head')
</head>

<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframesrc="https://www.googletagmanager.com/ns.html?id=GTM-WMNF55CW"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->


  <!-- header start -->
  <div class="header">
    <?= $header ?>
  </div>
  <!-- header end -->
  <?= $maincontent ?>
  <!-- ============footer_start========== -->
  <div class="footer-sec" id="Footer">
    <?= $footer ?>
  </div>
  <!--===== footer-end ======-->
  <!-- js link     -->
  <script src="<?= env('FRONT_ASSETS_URL') ?>js/jquery.min.js"></script>
  <script src="<?= env('FRONT_ASSETS_URL') ?>js/bootstrap.bundle.min.js"></script>
  <script src="<?= env('FRONT_ASSETS_URL') ?>js/slick.min.js"></script>
  <script src="<?= env('FRONT_ASSETS_URL') ?>js/swiper-bundle.min.js"></script>
  <script src="<?= env('FRONT_ASSETS_URL') ?>js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
  <script>
    function toastAlert(type, message, redirectStatus = false, redirectUrl = '') {
      toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "3000",
        "hideDuration": "1000000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
      toastr[type](message);
      if (redirectStatus) {
        setTimeout(function() {
          window.location = redirectUrl;
        }, 3000);
      }
    }
  </script>
  <script>
    $(document).ready(function() {
      $('#subscribeForm').on('submit', function(e) {
        e.preventDefault(); // prevent default form submission

        const email = $('#subscribe_email').val();
        const base_url = '<?= url('/') ?>';
        if (email != '') {
          $.ajax({
            url: base_url + '/api/submit-subscriber', // 🔁 Change to your actual backend route
            type: 'POST',
            data: {
              key: '13ae7b7d7ba75ac286656a7a274905ca',
              source: 'android',
              email: email,
              _token: '<?= csrf_token() ?>' // for Laravel CSRF protection
            },
            success: function(response) {
              $('#subscribe_email').val('');
              toastAlert('success', response.message);
            },
            error: function(xhr, status, error) {
              toastAlert('error', response.message);
            }
          });
        } else {
          toastAlert('warning', 'Please enter email for subscribe !!!');
        }
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      let typingTimer;
      const delay = 300; // milliseconds

      $('#searchInput').on('input', function() {
        clearTimeout(typingTimer);

        const keyword = $(this).val().trim();
        if (keyword.length >= 2) {
          typingTimer = setTimeout(() => {
            search(keyword);
          }, delay);
        } else {
          $('#searchResults').empty(); // clear if less than 2 characters
        }
      });

      function search(keyword) {
        const base_url = '<?= url('/') ?>';
        $.ajax({
          url: base_url + '/api/search-suggestion', // 🔁 Change to your actual endpoint
          method: 'POST',
          data: {
            key: '13ae7b7d7ba75ac286656a7a274905ca',
            source: 'android',
            search_keyword: keyword,
            _token: '<?= csrf_token() ?>'
          },
          headers: {
            'key': '13ae7b7d7ba75ac286656a7a274905ca', // 🔁 Replace with your actual key
            'source': 'android' // 🔁 Replace with your actual source
          },
          success: function(response) {
            $('#searchResults').empty(); // clear old results
            var search_result = response.data;
            if (search_result.length === 0) {
              $('#searchResults').append('<li class="search-item">No results found</li>');
            } else {
              search_result.forEach(item => {
                $('#searchResults').append(`
                                            <li class="search-item">
                                              <a href="${item.frontend_product_link}"><img src="${item.cover_image}" alt="product" class="search-img"></a>
                                              <a href="${item.frontend_product_link}"><span class="search-text">${item.name}</span></a>
                                            </li>
                                          `);
              });
            }
          },
          error: function(xhr, status, error) {
            toastAlert('error', 'error');
          }
        });
      }
    });

    $(document).ready(function() {
      var QtyInput = (function() {
        var $qtyInputs = $(".qty-input");

        if (!$qtyInputs.length) {
          return;
        }

        var $inputs = $qtyInputs.find(".product-qty");
        var $countBtn = $qtyInputs.find(".qty-count");
        var qtyMin = parseInt($inputs.attr("min"));
        var qtyMax = parseInt($inputs.attr("max"));

        $inputs.change(function() {
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

        $countBtn.click(function() {
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
  </script>
</body>

</html>