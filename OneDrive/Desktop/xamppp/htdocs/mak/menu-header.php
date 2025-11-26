<?php
session_start();

$server = "localhost";
$user = "root";
$pass = "";
$db_name = "restaurant_db";

$conn = mysqli_connect($server, $user, $pass, $db_name);

if (!$conn) {
    die("Database connection error");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu</title>

<style>

* {
  margin: 0; padding: 0;
  box-sizing: border-box;
}

body {
  background-image: url("uploads/v602-nunoon-40-rippednotes.jpg");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  background-attachment: fixed;
  font-family: Arial;
  padding: 20px;
  backdrop-filter: blur(1px);
}


.topbar {
  max-width: 1100px;
  margin: 0 auto 25px auto;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.search-box input {
  width: 100%;
  padding: 14px;
  font-size: 17px;
  border-radius: 12px;
  border: 2px solid #c9b48a;
  outline: none;
  background: #fff7e9;
  color: #3d2b00;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transition: .2s;
}

.search-box input:focus {
  border-color: #ffb74d;
  background: #fff2d7;
  box-shadow: 0 5px 18px rgba(0,0,0,0.15);
}

.filters {
  display: flex;
  gap: 12px;
}

.filters button {
  padding: 10px 18px;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  background: #ffe4b8;
  font-weight: bold;
  color: #4d3900;
  transition: 0.25s;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.filters button:hover {
  background: #ffcf7a;
}

.filters button.active {
  background: #ffae00;
  color: white;
  box-shadow: 0 4px 14px rgba(0,0,0,0.25);
}


#menu {
  max-width: 1100px;
  margin: auto;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 22px;
}


.dish {
  width: 270px;
  background: #eadabf;
  border: 2px solid #f3e0a8;
  border-radius: 14px;
  padding: 12px;
  transition: 0.3s ease;
  box-shadow: 0 7px 18px rgba(0,0,0,0.15);
}

.dish:hover {
  transform: translateY(-8px);
  box-shadow: 0 14px 28px rgba(0,0,0,0.25);
}

.dish img {
  width: 100%;
  height: 170px;
  object-fit: contain;
  border-radius: 12px;
  background: #fff4d3;
  padding: 8px;
}


.sud-an {
  background: linear-gradient(180deg, #ffbf4d, #ff9b00);
  padding: 8px 12px;
  border-radius: 10px;
  font-size: 18px;
  font-weight: bold;
  margin-top: 10px;
  color: #3d2a00;
  display: flex;
  justify-content: space-between;
  box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}

.price {
  font-weight: bold;
}


.dish p {
  margin-top: 5px;
  font-size: 14px;
  color: #5a4a26;
}


.qty-controls {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-top: 12px;
}

.qty-controls button {
  width: 45px;
  height: 45px;
  background: #ffe9c4;
  border: 2px solid #5a4a26;
  border-radius: 10px;
  font-size: 24px;
  cursor: pointer;
  transition: .15s;
}

.qty-controls button:hover {
  background: #ffdb98;
}

.qty-num {
  font-size: 22px;
  width: 40px;
  text-align: center;
  font-weight: bold;
  color: #3a2900;
}


.box3 {
  width: 100%;
  max-width: 500px;
  background: rgba(18, 18, 18, 0.65);
  padding: 20px;
  border-radius: 15px;
  margin: 30px auto;
  color: white;
  backdrop-filter: blur(4px);
  box-shadow: 0 18px 40px rgba(0,0,0,0.45);
}


.checkout {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.checkout button {
  font-size: 19px;
  padding: 10px 22px;
  border-radius: 10px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  transition: 0.2s;
}

#clearBtn {
  background: transparent;
  border: 1px solid rgba(255,255,255,.4);
  color: white;
}

#clearBtn:hover {
  background: rgba(255,255,255,.1);
}

#orderBtn {
  background: #ffae00;
  color: white;
  box-shadow: 0 6px 14px rgba(0,0,0,0.3);
}

#orderBtn:hover {
  background: #ffb932;
}

.side-ads {
  position: fixed;
  top: 120px;
  right: 25px;
  width: 260px;
  background: rgba(255, 246, 226, 0.92);
  padding: 22px;
  border-radius: 20px;
  box-shadow: 0 15px 35px rgba(0,0,0,0.25);
  backdrop-filter: blur(3px);
  border: 2px dashed #c9a86d;
  transform: rotate(0.8deg);
}


.side-ads h3 {
  font-size: 22px;
  margin-bottom: 14px;
  text-align: center;
  font-weight: bold;
  color: #5a3e00;
  text-shadow: 1px 1px 0px #f5e3b0;
}


.sticky {
  background: #fff4b8;
  padding: 14px;
  border-radius: 10px;
  border: 1px solid #e4c88c;
  box-shadow: 3px 3px 6px rgba(0,0,0,0.15);
  margin-bottom: 14px;
  transform: rotate(-1.5deg);
}

.sticky p {
  margin: 0;
  font-size: 14px;
  color: #4a3900;
  font-weight: bold;
}


.polaroid {
  background: white;
  padding: 8px;
  border-radius: 10px;
  width: 100%;
  box-shadow: 0 4px 10px rgba(0,0,0,0.22);
  margin-bottom: 14px;
  transform: rotate(1.4deg);
}

.polaroid img {
  width: 100%;
  border-radius: 8px;
  object-fit: cover;
}

.polaroid p {
  text-align: center;
  margin-top: 6px;
  font-size: 13px;
  color: #3d2a00;
}


.tape {
  width: 70px;
  height: 25px;
  background: #ffd88e;
  opacity: 0.8;
  position: absolute;
  top: -12px;
  left: 50%;
  transform: translateX(-50%) rotate(-6deg);
  border-radius: 4px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.2);
}


.side-left {
  position: fixed;
  top: 120px;
  left: 25px;
  width: 260px;
  background: rgba(255, 246, 226, 0.92);
  padding: 22px;
  border-radius: 20px;
  box-shadow: 0 15px 35px rgba(0,0,0,0.25);
  backdrop-filter: blur(3px);
  border: 2px dashed #c9a86d;
  transform: rotate(-0.8deg);
}


.tape-left {
  width: 70px;
  height: 25px;
  background: #ffd88e;
  opacity: 0.8;
  position: absolute;
  top: -12px;
  left: 50%;
  transform: translateX(-50%) rotate(6deg);
  border-radius: 4px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.2);
}

.side-left h3 {
  font-size: 22px;
  margin-bottom: 14px;
  text-align: center;
  font-weight: bold;
  color: #5a3e00;
  text-shadow: 1px 1px 0px #f5e3b0;
}

.sticky-left {
  background: #fff4b8;
  padding: 14px;
  border-radius: 10px;
  border: 1px solid #e4c88c;
  box-shadow: 3px 3px 6px rgba(0,0,0,0.15);
  margin-bottom: 14px;
  transform: rotate(1.8deg);
}

.sticky-left p {
  margin: 0;
  font-size: 14px;
  color: #4a3900;
  font-weight: bold;
}


.polaroid-left {
  background: white;
  padding: 8px;
  border-radius: 10px;
  width: 100%;
  box-shadow: 0 4px 10px rgba(0,0,0,0.22);
  margin-bottom: 14px;
  transform: rotate(-2.2deg);
}

.polaroid-left img {
  width: 100%;
  border-radius: 8px;
  object-fit: cover;
}

.polaroid-left p {
  text-align: center;
  margin-top: 6px;
  font-size: 13px;
  color: #3d2a00;
}


.menu-container {
    position: absolute;
    top: 20px;
    right: 30px;
    z-index: 3000;
}

.menu-btn {
    background: #fff3d6;
    padding: 10px 18px;
    border-radius: 12px;
    border: 2px solid #d8b57a;
    font-weight: bold;
    color: #5a3e00;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: .2s;
}

.menu-btn:hover {
    background: #ffe4b0;
}

.dropdown {
    display: none;
    position: absolute;
    top: 50px;
    right: 0;
    background: #fff6df;
    border-radius: 12px;
    border: 2px solid #d8b57a;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    width: 160px;
    overflow: hidden;
}

.dropdown a {
    display: block;
    padding: 12px 15px;
    color: #5a3e00;
    font-weight: bold;
    text-decoration: none;
    transition: .2s;
}

.dropdown a:hover {
    background: #ffe8c4;
}
















</style>
</head>
<body>

<?php if (isset($_SESSION['username'])): ?>
    <div style="
        position: absolute;
        top: 20px;
        left: 30px;
        background: #fff3d6;
        padding: 10px 18px;
        border-radius: 12px;
        border: 2px solid #d8b57a;
        font-weight: bold;
        color: #5a3e00;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 3000;
    ">
        👋 Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    </div>
<?php endif; ?>


<div class="menu-container">
    <div class="menu-btn">☰ Menu</div>
    <div class="dropdown" id="dropdownMenu">
        <a href="account.php">My Account</a>
        <a href="login-user.php">Logout</a>
    </div>
</div>

  

<div class="side-ads">

    <div class="tape"></div>

    <h3>📌 Specials & Notes</h3>

    <div class="sticky">
        <p>🔥 Buy 1 Take 1 — Iced Coffee Today!</p>
    </div>

    <div class="sticky" style="background:#ffe9e9; transform:rotate(2deg);">
        <p>💡 Tip: Try our new premium dessert!</p>
    </div>

    <div class="polaroid">
        <img src="uploads/Shrimp_Tempura___Easy_Tempura_Shrimp_Recipe_-removebg-preview.png" alt="promo">
        <p>Chef’s Recommendation</p>
    </div>

    <div class="polaroid" style="transform:rotate(-1deg);">
        <img src="uploads/Mango Milkshake – Tasty Oven.jpg" alt="promo2">
        <p>New Beverage Drop</p>
    </div>

</div>


<div class="side-left">
    <div class="tape-left"></div>

    <h3>🍽️ Today’s Picks</h3>

    <div class="sticky-left">
        <p>✨ Best Seller: Adobo!</p>
    </div>

    <div class="sticky-left" style="background:#ffe7c2; transform:rotate(1deg);">
        <p>⭐ Don’t miss our new Beverages!</p>
    </div>

    <div class="polaroid-left">
        <img src="uploads/Tamarind_Chicken_Adobo__Adobong_Manok_sa_Sampalok_-removebg-preview.png">
        <p>Customer Favorite</p>
    </div>

    <div class="polaroid-left" style="transform:rotate(-1.5deg);">
        <img src="uploads/Cool Down This Summer with 12 Fun Drink Recipes & Must-Try Summer Cocktails.jpg">
        <p>Sweet Refreshment</p>
    </div>
</div>





<div class="topbar">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search dishes or beverages...">
    </div>

    <div class="filters">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="starter">Starter</button>
        <button class="filter-btn" data-filter="main course">Main Course</button>
        <button class="filter-btn" data-filter="dessert">Dessert</button>
        <button class="filter-btn" data-filter="beverages">Beverages</button>
        <button class="filter-btn" data-filter="rice platters">Rice Platters</button>
    </div>
</div>


<div id="menu">
<?php
$sql = "SELECT * FROM menu_tbl";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $dishId = $row['dish_id'];
        $dishName = $row['Dish_Info'];
        $dishPrice = number_format($row['Price'], 2);
        $dishType = strtolower($row['Food_Type']); 
        $image = $row['image'];

        echo "
        <div class='dish' 
             data-id='{$dishId}' 
             data-price='{$row['Price']}'
             data-type='{$dishType}'
             data-name='".strtolower($dishName)."'>
             
            <img src='uploads/{$image}'>

            <div class='sud-an'>
                {$dishName}
                <div class='price'>₱{$dishPrice}</div>
            </div>

            <p>".ucfirst($dishType)."</p>

            <div class='qty-controls'>
                <button class='qty-decrease'>−</button>
                <div class='qty-num'>0</div>
                <button class='qty-increase'>+</button>
            </div>
        </div>";
    }
}
?>
</div>


<div class="box3">
    <div class="totals">
        <div>Total items: <span id="totalQty">0</span></div>
        <div>Total price: <strong id="totalPrice">₱0.00</strong></div>
    </div>

    <div class="checkout">
        <button id="clearBtn">Clear</button>
        <button id="orderBtn">Place Order</button>
    </div>
</div>





<script>


const menuBtn = document.querySelector(".menu-btn");
const dropdown = document.getElementById("dropdownMenu");

menuBtn.addEventListener("click", () => {
    dropdown.style.display =
        dropdown.style.display === "block" ? "none" : "block";
});

document.addEventListener("click", (e) => {
    if (!menuBtn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
    }
});



const formatCurrency = (num) => '₱' + Number(num || 0).toFixed(2);

const menuEl = document.getElementById('menu');
const totalQtyEl = document.getElementById('totalQty');
const totalPriceEl = document.getElementById('totalPrice');
const clearBtn = document.getElementById('clearBtn');
const orderBtn = document.getElementById('orderBtn');
const searchInput = document.getElementById('searchInput');

const dishes = [...menuEl.querySelectorAll('.dish')].map(el => ({
    id: el.dataset.id,
    price: parseFloat(el.dataset.price),
    type: el.dataset.type,
    name: el.dataset.name,
    el,
    qtyEl: el.querySelector('.qty-num'),
    qty: 0
}));

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(
        dishes.filter(d => d.qty > 0).map(d => ({
            id: d.id, name: d.name, price: d.price, qty: d.qty
        }))
    ));
}

function updateTotals() {
    let totalQty = 0, totalPrice = 0;
    dishes.forEach(d => {
        totalQty += d.qty;
        totalPrice += d.qty * d.price;
    });

    totalQtyEl.textContent = totalQty;
    totalPriceEl.textContent = formatCurrency(totalPrice);
    saveCart();
}

function setQty(dish, qty) {
    dish.qty = Math.max(0, qty);
    dish.qtyEl.textContent = dish.qty;
    updateTotals();
}

dishes.forEach(dish => {
    dish.el.querySelector('.qty-increase')
        .addEventListener('click', () => setQty(dish, dish.qty + 1));

    dish.el.querySelector('.qty-decrease')
        .addEventListener('click', () => setQty(dish, dish.qty - 1));
});

clearBtn.addEventListener('click', () => {
    dishes.forEach(d => setQty(d, 0));
    localStorage.removeItem('cart');
});

orderBtn.addEventListener('click', () => {
    const items = dishes.filter(d => d.qty > 0);
    if (!items.length) return alert("Your cart is empty.");
    saveCart();
    window.location.href = "order1.php";
});


(function loadCart() {
    const saved = JSON.parse(localStorage.getItem('cart') || "[]");
    saved.forEach(item => {
        const d = dishes.find(x => x.id === item.id);
        if (d) setQty(d, item.qty);
    });
})();


searchInput.addEventListener('input', () => {
    const q = searchInput.value.toLowerCase();
    dishes.forEach(d => {
        d.el.style.display =
            d.name.includes(q) ? "block" : "none";
    });
});


document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {

        document.querySelectorAll('.filter-btn')
            .forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const type = btn.dataset.filter;

        dishes.forEach(d => {
            d.el.style.display =
                type === "all" || d.type === type ? "block" : "none";
        });
    });
});
</script>


<div style="
    width: 100%;
    background: rgba(255, 246, 226, 0.95);
    padding: 35px 20px;
    margin-top: 60px;
    border-top: 3px dashed #c9a86d;
    box-shadow: 0 -6px 18px rgba(0,0,0,0.25);
    text-align: center;
    position: relative;
">

    <div style="
        width: 90px;
        height: 30px;
        background: #ffd88e;
        opacity: 0.8;
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%) rotate(-4deg);
        border-radius: 5px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    "></div>

    <h2 style="
        color: #5a3e00;
        text-shadow: 1px 1px 0px #f5e3b0;
        font-size: 26px;
        margin-bottom: 10px;
    ">About Us</h2>

    <p style="
        max-width: 700px;
        margin: auto;
        font-size: 15px;
        color: #4a3900;
        line-height: 1.6;
        font-weight: bold;
    ">
        Welcome to Sizzle Spot restaurant! We serve delicious meals crafted with passion and care.  
        From classic favorites to refreshing beverages, our mission is to bring joy and comfort 
        through every dish. Thank you for supporting our small business! 🍽️❤️
    </p>

    <p style="margin-top: 15px; color:#866b34; font-size:13px;">
        © 2025 Our Restaurant — All Rights Reserved.
    </p>
</div>

<section style="padding: 50px 0; background:#f5f5f5;">
    <div style="text-align:center; margin-bottom:40px;">
        <h2 style="font-size:32px; font-weight:700;">About Us</h2>
        <p style="font-size:18px; color:#444;">Meet the developers behind the system</p>
    </div>

    <div style="display:flex; justify-content:center; gap:60px; flex-wrap:wrap;">

       
        <div style="background:white; padding:25px; width:280px; border-radius:12px; 
                    box-shadow:0 4px 10px rgba(0,0,0,0.15); text-align:center;">
            <img src="/mak/" 
                 style="width:180px; height:auto; margin-bottom:15px;">
            <h3 style="margin:0; font-size:22px;">Mark Mendoza</h3>
            <p style="color:#777; margin:5px 0 15px;">BSIT 2101</p>
            <span style="background:#007bff; color:white; padding:6px 14px; border-radius:20px; font-size:14px;">
                Front-End Developer
            </span>
        </div>

      
        <div style="background:white; padding:25px; width:280px; border-radius:12px; 
                    box-shadow:0 4px 10px rgba(0,0,0,0.15); text-align:center;">
            <img src="/mak//584922979_1380031433818599_4683446264866623248_n-removebg-preview.png" 
                 style="width:180px; height:auto; margin-bottom:15px;">
            <h3 style="margin:0; font-size:22px;">Angelica Bulante</h3>
            <p style="color:#777; margin:5px 0 15px;">BSIT 2101</p>
            <span style="background:#28a745; color:white; padding:6px 14px; border-radius:20px; font-size:14px;">
               Database Developer
            </span>
        </div>

    </div>
</section>



</body>
</html>
