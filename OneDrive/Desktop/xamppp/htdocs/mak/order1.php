<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Your Order</title>

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: linear-gradient(135deg, #1a1a1a, #2b2b2b);
      margin: 0;
      padding: 20px;
      color: #eee;
    }

    .card {
      max-width: 900px;
      margin: 40px auto;
      background: #212121;
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(255, 153, 0, 0.2);
      border: 1px solid rgba(255, 153, 0, 0.3);
    }

    h1 {
      margin: 0 0 18px 0;
      color: #ff9900;
      text-shadow: 0 0 10px rgba(255, 153, 0, 0.5);
    }

    .item {
      display: flex;
      justify-content: space-between;
      padding: 14px 0;
      border-bottom: 1px solid #333;
    }

    .item .left {
      max-width: 75%;
    }

    .small {
      font-size: 0.9rem;
      color: #b5b5b5;
    }

    .order-type-box {
      margin: 18px 0;
    }

    select {
      padding: 12px;
      width: 100%;
      border-radius: 8px;
      border: 1px solid #444;
      background: #2c2c2c;
      color: #fff;
      font-size: 15px;
      outline: none;
      margin-top: 6px;
    }

    .controls {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      margin-top: 18px;
    }

    button {
      padding: 12px 16px;
      border-radius: 8px;
      border: 0;
      cursor: pointer;
      font-weight: bold;
      font-size: 14px;
      transition: 0.2s;
    }

    .primary {
      background: #ff9900;
      color: #000;
    }

    .primary:hover {
      background: #ff7700;
    }

    .muted {
      background: #444;
      color: #ddd;
    }

    .muted:hover {
      background: #555;
    }

    .danger {
      background: #d9534f;
      color: #fff;
    }

    .danger:hover {
      background: #c7433d;
    }

    .empty {
      padding: 28px;
      text-align: center;
      color: #bbb;
    }
  </style>
</head>
<body>

  <div class="card">
    <h1>Your Order</h1>

    <div id="orderList"></div>
    <div id="summary" style="margin-top:14px;font-weight:700;color:#ffb84d"></div>

    <!-- ORDER TYPE -->
    <div class="order-type-box">
            
    <!-- DELIVERY INFO (Hidden unless user selects Delivery) -->
<div id="deliveryFields" style="display:none; margin-top:15px;">
  <label style="font-weight:600; color:#ff9900;">Customer Name:</label>
  <input type="text" id="customerName" 
         style="width:100%; padding:12px; background:#2c2c2c; border:1px solid #444; border-radius:8px; color:white; margin-top:6px;">

  <label style="font-weight:600; color:#ff9900; margin-top:12px;">Delivery Address:</label>
  <textarea id="deliveryAddress" 
            style="width:100%; padding:12px; background:#2c2c2c; border:1px solid #444; border-radius:8px; color:white; margin-top:6px; height:70px;"></textarea>
</div>


      <label style="font-weight:600; color:#ff9900;">Choose Order Type:</label>
      <select id="orderType">
        <option value="">-- Select Order Type --</option>
        <option value="Dine-in">Dine-in</option>
        <option value="Take-out">Take-out</option>
        <option value="Delivery">Delivery</option>
      </select>
    </div>

    <div class="controls">
      <button id="backBtn" class="muted">Go back &amp; edit</button>
      <button id="clearOrderBtn" class="danger">Clear order</button>
      <button id="checkoutBtn" class="primary">Checkout</button>
    </div>

    <div style="margin-top:10px" class="small">
      Note: This is non-refundable.
    </div>
  </div>

  <script>

  // SHOW / HIDE DELIVERY FIELDS
  document.getElementById("orderType").addEventListener("change", function () {
    const deliveryBox = document.getElementById("deliveryFields");
    if (this.value === "Delivery") {
      deliveryBox.style.display = "block";
    } else {
      deliveryBox.style.display = "none";
    }
  });

  const orderList = document.getElementById('orderList');
  const summaryEl = document.getElementById('summary');
  const backBtn = document.getElementById('backBtn');
  const clearOrderBtn = document.getElementById('clearOrderBtn');
  const checkoutBtn = document.getElementById('checkoutBtn');

  function loadCart() {
    try {
      return JSON.parse(localStorage.getItem('cart') || '[]');
    } catch {
      return [];
    }
  }

  function formatCurrency(n) {
    return '₱' + Number(n || 0).toFixed(2);
  }

  function render() {
    const items = loadCart();
    orderList.innerHTML = '';

    if (!items.length) {
      orderList.innerHTML = '<div class="empty">Your cart is empty.</div>';
      summaryEl.textContent = '';
      checkoutBtn.disabled = true;
      clearOrderBtn.disabled = true;
      return;
    }

    checkoutBtn.disabled = false;
    clearOrderBtn.disabled = false;

    let totalQty = 0, totalPrice = 0;

    items.forEach(it => {
      const lineTotal = it.qty * it.price;
      totalQty += it.qty;
      totalPrice += lineTotal;

      const row = document.createElement('div');
      row.className = 'item';
      row.innerHTML = `
        <div class="left">
          <div style="font-weight:700">${it.name}</div>
          <div class="small">${it.qty} x ${formatCurrency(it.price)}</div>
        </div>
        <div style="text-align:right">${formatCurrency(lineTotal)}</div>`;
      orderList.appendChild(row);
    });

    summaryEl.textContent =
      `Total items: ${totalQty}   •   Total price: ${formatCurrency(totalPrice)}`;
  }

  backBtn.addEventListener('click', () => {
    window.location.href = document.referrer || 'menu.html';
  });

  clearOrderBtn.addEventListener('click', () => {
    if (!confirm('Clear your saved order?')) return;
    localStorage.removeItem('cart');
    render();
  });

  checkoutBtn.addEventListener('click', () => {
    const items = loadCart();
    const orderType = document.getElementById('orderType').value;

    if (!items.length) return alert("Cart empty");
    if (!orderType) return alert("Please select an order type.");

    let deliveryName = "";
    let deliveryAddress = "";

    // DELIVERY VALIDATION
    if (orderType === "Delivery") {
      deliveryName = document.getElementById("customerName").value.trim();
      deliveryAddress = document.getElementById("deliveryAddress").value.trim();

      if (!deliveryName) return alert("Please enter customer name.");
      if (!deliveryAddress) return alert("Please enter delivery address.");
    }

    let total = 0;
    items.forEach(i => total += i.qty * i.price);

    // SEND ORDER DATA
    fetch("place_order.php", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({
        order_type: orderType,
        items: items,
        total_price: total,
        customer_name: deliveryName,
        delivery_address: deliveryAddress
      })
    })
    .then(res => res.json())
.then(data => {
  if (data.success) {
    localStorage.removeItem("cart");
    window.location.href = "receipt.php?order_id=" + data.order_id;
  } else {
    alert("Error: " + data.message);
  }
});

  });

  render();
</script>


</body>
</html>
