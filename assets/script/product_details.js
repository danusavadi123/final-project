     var $j = jQuery.noConflict();
  $j("form[action='add_to_cart.php']").submit(function (e) {
  e.preventDefault();
  var form = $j(this);
  var productId = form.find("input[name='product_id']").val();
  var quantity = form.find("input[name='quantity']").val();

  $j.ajax({
    url: "../buyer/cart_handler.php?action=add",
    method: "POST",
    data: { product_id: productId, quantity: quantity },
    success: function (res) {
      if (res.status === 'success') {
        $j("#cart-count").text(res.count);
      }
    }
  });
});

  const qtyInput = document.getElementById('quantityInput');
  const increaseBtn = document.getElementById('increaseQty');
  const decreaseBtn = document.getElementById('decreaseQty');
  const orderNowQty = document.getElementById('order-now-qty'); // sync hidden input

  increaseBtn.addEventListener('click', () => {
    let value = parseInt(qtyInput.value);
    if (value < parseInt(qtyInput.max)) {
      qtyInput.value = value + 1;
      orderNowQty.value = qtyInput.value;
    }
  });

  decreaseBtn.addEventListener('click', () => {
    let value = parseInt(qtyInput.value);
    if (value > parseInt(qtyInput.min)) {
      qtyInput.value = value - 1;
      orderNowQty.value = qtyInput.value;
    }
  });

  qtyInput.addEventListener('input', () => {
    orderNowQty.value = qtyInput.value;
  });