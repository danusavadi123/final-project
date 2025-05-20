  const toggleBtn = document.getElementById('menu-toggle');
  const navbarMenu = document.getElementById('navbar-menu');
  const icon = toggleBtn.querySelector('i');

  toggleBtn.addEventListener('click', () => {
    navbarMenu.classList.toggle('active');
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
  });

const profileDropdown = document.getElementById('profile-dropdown');
const avatar = document.getElementById('avatar');

avatar.addEventListener('click', () => {
  profileDropdown.classList.toggle('active');
});

// Optional: Close dropdown if clicked outside
document.addEventListener('click', (e) => {
  if (!profileDropdown.contains(e.target)) {
    profileDropdown.classList.remove('active');
  }
});



  var $j = jQuery.noConflict();

  $j(document).ready(function () {
    // Handle search suggestions
    $j("#search").on("input", function () {
      var search = $j(this).val().trim();

      if (search !== '') {
        $j(".suggestionbox").css("display", "block");
      } else {
        $j(".suggestionbox").css("display", "none");
      }

      $j.ajax({
        url: "../buyer/search_suggestions.php",
        method: "POST",
        data: { search: search },
        success: function (res) {
          $j("#dropdata").html(res);
        },
      });
    });

    // Open cart popup
    $j(".cart-icon").on("click", function () {
      loadCartItems();
      $j("#cart-popup").css("display", "flex");
    });

    // Close cart popup
    $j("#close-cart").on("click", function () {
      $j("#cart-popup").css("display", "none");
    });

    // Close on outside click
    $j(document).on("click", function (e) {
      if ($j(e.target).is("#cart-popup")) {
        $j("#cart-popup").css("display", "none");
      }
    });
  });

  // Load items into cart popup
  function loadCartItems() {
    $j.ajax({
      url: "../buyer/cart_handler.php",
      method: "GET",
      success: function (data) {
        $j("#cart-items").html(data);

        if (data.includes("Your cart is empty")) {
          $j(".cart-actions .btn-success").prop("disabled", true).addClass("disabled");
        } else {
          $j(".cart-actions .btn-success").prop("disabled", false).removeClass("disabled");
        }
      },
      error: function () {
        $j("#cart-items").html("<p class='text-danger'>Failed to load cart.</p>");
        $j(".cart-actions .btn-success").prop("disabled", true).addClass("disabled");
      }
    });
  }
  
  

  function removeFromCart(id) {
    $j.ajax({
      url: "../buyer/cart_handler.php",
      method: "GET",
      data: { action: "remove", id: id },
      success: function (res) {
        loadCartItems();
        updateCartCount();
      }
    });
  }

  function putdata(data) {
    $j("#search").val(data);
    $j(".suggestionbox").hide();
    $j(".search-wrapper").submit();
  }

  $j(document).ready(function () {
    updateCartCount();
  });

  function updateCartCount() {
    $j.ajax({
      url: "../buyer/cart_handler.php",
      method: "GET",
      data: { action: "count" },
      success: function (count) {
        $j("#cart-count").text(count);
      },
      error: function () {
        $j("#cart-count").text("0");
      }
    });
  }
  
  //seller notifications

  $j(document).ready(function () {
    const notificationIcon = $j(".notification-icon");
    const notificationPopup = $j("#notification-popup");
    const notificationItems = $j("#notification-items");
  
    // Toggle popup
    notificationIcon.on("click", function () {
      notificationPopup.toggle();
    });
  
    // Close popup
    $j("#close-notification").on("click", function () {
      notificationPopup.hide();
    });
  
    // Load notifications
    function loadNotifications() {
      $j.ajax({
          url: '../seller/notification.php',
          method: 'GET',
          dataType: 'json',
          success: function (res) {
              if (res.success) {
                  const orders = res.orders;
                  const count = res.count;
  
                  $j('#notification-items').empty();
                  $j('#notification-count').text(count);
  
                  if (count > 0) {
                      $j('#notification-count').show(); // Show badge
                      orders.forEach(order => {
                          const orderEl = $j(`
                              <div class="notification-item">
                                  <p><strong>Order #${order.id}</strong></p>
                                  <p>Product: ${order.product_name}</p>
                                  <p>Quantity: ${order.quantity}</p>
                                  <button class="update-status btn btn-outline-primary" data-id="${order.id}">Mark as Processed</button>
                              </div>
                          `);
                          $j('#notification-items').append(orderEl);
                      });
                  } else {
                      $j('#notification-items').html("<p>No pending orders.</p>");
                      $j('#notification-count').hide(); // Hide badge
                  }
              } else {
                  console.error('Failed to load notifications:', res.message);
              }
          },
          error: function (xhr, status, error) {
              console.error('AJAX error:', error);
          }
      });
  }
  
  
    
  
    // Handle status update
    notificationItems.on("click", ".update-status", function () {
      const orderId = $j(this).data("id");
      $j.ajax({
        url: '../seller/order_status.php',
        method: 'POST',
        data: { id: orderId, status: 'Processed' },
        success: function () {
          loadNotifications(); // reload after update
        }
      });
    });
  
    $j(document).ready(function () {
      loadNotifications(); // Initial load
  
  
      // Refresh every 60 seconds
      setInterval(loadNotifications, 60000);
  });
  
    
  });
  