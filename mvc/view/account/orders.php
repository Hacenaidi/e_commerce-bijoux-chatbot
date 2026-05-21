<?php
session_start();
$prodCtrl = __DIR__ . '/../../controller/ProduitController.php';
if (file_exists($prodCtrl)) {
    include_once($prodCtrl);
}
$pannierCtrl = __DIR__ . '/../../controller/PannierController.php';
if (file_exists($pannierCtrl)) {
    include_once($pannierCtrl);
}
$orderCtrl = __DIR__ . '/../../controller/OrderController.php';
if (file_exists($orderCtrl)) {
    include_once($orderCtrl);
}
$viewHelpers = __DIR__ . '/../inc/view_helpers.php';
if (file_exists($viewHelpers)) {
    include_once($viewHelpers);
}
$controllerpannier = new PannierController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);
$controller = new ProduitController();
$ordersData = array();
if ($id) {
    $orderController = new OrderController();
    $ordersData = $orderController->listOrdersByClient($id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; Your Orders</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/chatbot-messages.css">
    <link rel="stylesheet" href="../css/response-format.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/my-account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
</head>
<body>

<div class="grain"></div>
<div id="glitter-layer"></div>
<div id="cur"></div>
<div id="cur-ring"></div>

<style>
.left-icons { list-style:none;padding:0;margin:0; }
.left-icons a { 
    display:flex;align-items:center;justify-content:center;
    width:42px;height:42px;
    border-radius:50%;
    background:rgba(192,57,43,.08);
    color:var(--red);
    transition:all .35s ease;
    text-decoration:none;
    font-size:14px;
}
.left-icons a:hover {
    background:rgba(192,57,43,.25);
    transform:scale(1.15);
}
@media(max-width:768px) { .left-icons { display:none; } }
</style>

<div class="search-ov" id="searchOv">
    <i class="fa fa-times search-ov-close" id="searchClose"></i>
    <input type="text" placeholder="Search bijoux...">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<!-- Side Cart -->
<div class="cart-overlay" id="cartOv" onclick="toggleCart()"></div>
<div class="side-cart" id="sideCart">
    <div class="sc-head">
        <span class="sc-title">Your Cart</span>
        <button class="sc-close" onclick="toggleCart()"><i class="fa fa-times"></i></button>
    </div>
    <?php
    $sc_total = 0;
    $lp_side  = $controllerpannier->listpannier($id);
    while ($l = $lp_side->fetch()):
        $pro = $controller->produit($l[2])->fetch();
        $cartImage = function_exists('view_safe_image_path') ? view_safe_image_path($pro[6] ?? '') : ($pro[6] ?? 'images/placeholder.png');
        $cartName = $pro[4] ?? '';
        $cartPrice = $pro[5] ?? '';
    ?>
    <div class="sc-item">
        <img src="<?php echo htmlspecialchars($cartImage); ?>" alt="<?php echo htmlspecialchars($cartName); ?>">
        <div>
            <div class="sc-item-name"><?php echo htmlspecialchars($cartName); ?></div>
            <div class="sc-item-detail"><?php echo htmlspecialchars($l[3]); ?> x <span style="color:var(--red2)">DT <?php echo htmlspecialchars($cartPrice); ?></span></div>
        </div>
    </div>
    <?php $sc_total += $l[5]; endwhile; ?>
    <div class="sc-total">
        <span class="sc-total-label">Total</span>
        <span class="sc-total-val">DT <?php echo $sc_total ?></span>
    </div>
    <a href="../public/cart.php" class="btn-red" style="display:block;text-align:center;margin-bottom:12px">View Cart</a>
    <a href="../public/checkout.php" class="btn-ghost" style="display:block;text-align:center">Checkout</a>
</div>

<nav class="nav-wrap" id="nav">
    <div class="nav-logo"><a href="../public/index.php"><img src="../images/logo.png" alt="Glowear"></a></div>
    <ul class="nav-links">
        <li><a href="../public/index.php">Home</a></li>
        <li><a href="../public/about.php">About</a></li>
        <li class="drop">
            <a href="#">Collections</a>
            <ul class="drop-menu">
                <li><a href="../public/shop.php?nom=Necklaces">Necklaces</a></li>
                <li><a href="../public/shop.php?nom=Earrings">Earrings</a></li>
                <li><a href="../public/shop.php?nom=Bracelets">Bracelets</a></li>
                <li><a href="../public/shop.php?nom=Rings">Rings</a></li>
                <li><a href="../public/shop.php?nom=Sets">Sets</a></li>
            </ul>
        </li>
        <li class="drop">
            <a href="#">Shop</a>
            <ul class="drop-menu">
                <li><a href="../public/cart.php">Cart</a></li>
                <li><a href="../public/checkout.php">Checkout</a></li>
                <li><a href="my-account.php">My Account</a></li>
            </ul>
        </li>
        <li><a href="../public/service.php">Services</a></li>
        <li><a href="../public/contact-us.php">Contact</a></li>
    </ul>
    <div class="nav-icons">
        <a href="#" id="searchBtn"><i class="fa fa-search"></i></a>
        <a href="#" onclick="toggleCart();return false">
            <i class="fa fa-shopping-bag"></i>
            <span class="cart-count"><?php echo $listpannier->rowCount() ?></span>
        </a>
        <?php if (isset($_SESSION['id'])): ?>
        <a href="my-account.php" style="color:var(--red)"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="../auth/client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- Page Hero -->
<div class="page-hero">
    <span class="page-hero-tag">Your Space</span>
    <h1>Your <em>Orders</em></h1>
    <div class="breadcrumb-row">
        <a href="../public/index.php">Home</a>
        <span>/</span>
        <a href="../public/shop.php">Shop</a>
        <span>/</span>
        <span class="active">Orders</span>
    </div>
</div>

<div class="account-wrap">
    <?php if (isset($_SESSION['id'])): ?>

    <section class="orders-history rev d2" id="ordersSection">
        <div class="orders-head">
            <h2>Your Orders</h2>
            <p>Track your orders and check their current status.</p>
        </div>

        <?php if (!empty($ordersData)): ?>
            <div class="orders-list">
                <?php foreach ($ordersData as $orderItem): ?>
                    <?php
                        $statusRaw = isset($orderItem['status']) ? strtolower(trim((string) $orderItem['status'])) : 'pending';
                        $statusText = $statusRaw !== '' ? ucfirst($statusRaw) : 'Pending';
                        $statusClass = 'st-pending';
                        if ($statusRaw === 'approved' || $statusRaw === 'completed' || $statusRaw === 'delivered') {
                            $statusClass = 'st-approved';
                        } elseif ($statusRaw === 'cancelled' || $statusRaw === 'rejected') {
                            $statusClass = 'st-cancelled';
                        } elseif ($statusRaw === 'shipped' || $statusRaw === 'processing') {
                            $statusClass = 'st-processing';
                        }
                    ?>
                    <article class="order-card-item" data-order-id="<?php echo htmlspecialchars((string)$orderItem['id']); ?>" tabindex="0">
                        <div class="order-main">
                            <div class="order-id">Order #<?php echo htmlspecialchars((string) $orderItem['id']); ?></div>
                            <div class="order-address"><?php echo htmlspecialchars((string) $orderItem['adress']); ?></div>
                        </div>
                        <div class="order-side">
                            <div class="order-total">DT <?php echo htmlspecialchars((string) $orderItem['total']); ?></div>
                            <span class="status-pill <?php echo htmlspecialchars($statusClass); ?>"><?php echo htmlspecialchars($statusText); ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="orders-empty">
                <span class="orders-empty-icon"><i class="fas fa-receipt"></i></span>
                <h3>No orders yet</h3>
                <p>You have not placed an order yet. Start exploring our latest collections.</p>
                <a href="../public/shop.php?nom=Necklaces" class="btn-red">Start Shopping</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Order Details Modal -->
    <div id="order-detail-modal" class="order-detail-modal" hidden>
        <div class="od-backdrop" onclick="closeOrderModal()"></div>
        <div class="od-shell">
            <button class="od-close" aria-label="Close" onclick="closeOrderModal()">Ã—</button>
            <div class="od-head">
                <h3>Order <span id="od-id"></span></h3>
                <div id="od-status" class="status-pill st-pending"></div>
            </div>
            <div class="od-body">
                <div class="od-items" id="od-items"></div>
                <div class="od-summary">
                    <div class="od-s-row"><strong>Total</strong><span id="od-total"></span></div>
                    <div class="od-actions">
                        <button id="od-cancel-btn" class="btn-red" onclick="cancelOrderNow()" hidden>Cancel Order</button>
                        <button class="btn-ghost" onclick="closeOrderModal()"><i class="fas fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>

    <div class="orders-empty">
        <h3>Please log in to view your orders</h3>
                <a href="../auth/client_login.php" class="btn-red">Log In</a>
    </div>

    <?php endif; ?>

</div>

<script>
document.addEventListener('click', function (e) {
    const card = e.target.closest('.order-card-item');
    if (card) {
        const id = card.getAttribute('data-order-id');
        if (id) {
            loadOrderDetails(id);
        }
    }
});

function loadOrderDetails(orderId) {
    const modal = document.getElementById('order-detail-modal');
    if (!modal) return;
    
    // Fetch order details via AJAX
    fetch('../actions/ajax_order_details.php?order_id=' + encodeURIComponent(orderId))
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                alert('Error loading order: ' + data.error);
                return;
            }
            const order = data.order || {};
            const items = data.items || [];
            
            // Set order ID and status
            document.getElementById('od-id').textContent = orderId;
            const statusEl = document.getElementById('od-status');
            const statusRaw = (order.status || 'pending').toLowerCase();
            const statusText = statusRaw !== '' ? statusRaw.charAt(0).toUpperCase() + statusRaw.slice(1) : 'Pending';
            statusEl.textContent = statusText;
            statusEl.className = 'status-pill ' + getStatusClass(statusRaw);
            
            // Build items HTML
            const itemsHtml = items.map(item => {
                const img = item.product_image || '../images/placeholder.png';
                return `<div class="od-item" style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.1);">
                    <img src="${img}" style="width:60px;height:60px;object-fit:cover;border-radius:4px;" alt="${item.product_name}">
                    <div style="flex:1;">
                        <div style="font-weight:600;">${item.product_name}</div>
                        <div style="font-size:12px;color:#aaa;">${item.quantity}x @ DT ${item.unit_price}</div>
                    </div>
                    <div style="font-weight:600;">DT ${item.line_total || item.quantity * item.unit_price}</div>
                </div>`;
            }).join('');
            document.getElementById('od-items').innerHTML = itemsHtml || '<p>No items found</p>';
            
            // Set total
            document.getElementById('od-total').textContent = 'DT ' + (order.total || 0);
            
            // Show/hide cancel button (pending orders only)
            const cancelBtn = document.getElementById('od-cancel-btn');
            if (cancelBtn) {
                cancelBtn.hidden = (statusRaw !== 'pending');
                cancelBtn.setAttribute('data-order-id', orderId);
            }
            
            // Show modal
            modal.hidden = false;
        })
        .catch(err => {
            console.error('Error loading order details:', err);
            alert('Failed to load order details');
        });
}

function getStatusClass(status) {
    if (status === 'approved' || status === 'completed' || status === 'delivered') {
        return 'st-approved';
    } else if (status === 'cancelled' || status === 'rejected') {
        return 'st-cancelled';
    } else if (status === 'shipped' || status === 'processing') {
        return 'st-processing';
    }
    return 'st-pending';
}

function closeOrderModal(){
    const modal = document.getElementById('order-detail-modal');
    if(modal) modal.hidden = true;
}

function cancelOrderNow() {
    const cancelBtn = document.getElementById('od-cancel-btn');
    if (!cancelBtn) return;
    const orderId = cancelBtn.getAttribute('data-order-id');
    if (!orderId) return;
    
    // Use a themed confirm modal if available
    function doCancel() {
        fetch('../actions/ajax_cancel_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'order_id=' + encodeURIComponent(orderId)
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            return;
        }
        // success â€” close modal and refresh list without a blocking alert
        closeOrderModal();
        // Optionally reload orders list
        location.reload();
    })
    .catch(err => {
        console.error('Error cancelling order:', err);
        alert('Failed to cancel order');
    });
    }

    // If page has the admin confirm modal API, use it
    var modal = document.getElementById('confirm-modal');
    if (modal && modal.showConfirm) {
        modal.showConfirm('Cancel order', 'Are you sure you want to cancel this order?').then(function(ok){ if (ok) doCancel(); });
    } else {
        if (confirm('Are you sure you want to cancel this order?')) {
            doCancel();
        }
    }
}

function toggleCart(){
    const cart = document.getElementById('sideCart');
    const overlay = document.getElementById('cartOv');
    if(cart) cart.classList.toggle('open');
    if(overlay) overlay.classList.toggle('open');
}
</script>

<!-- Instagram Feed -->
<section class="insta-sec">
    <div class="insta-head rev">
        <p class="insta-handle"><i class="fab fa-instagram"></i> @glowear</p>
        <h2 class="sec-title">As Seen <em>On</em></h2>
    </div>
    <div class="insta-grid">
        <?php
        $instaImgs = ['sun4','baby4','strass','pink2','baby6'];
        $delays    = ['','d1','d2','d3','d4'];
        foreach($instaImgs as $i => $img): ?>
        <div class="insta-item rev <?php echo $delays[$i] ?>">
            <img src="../images/<?php echo $img ?>.png" alt="">
            <div class="insta-ov"><i class="fab fa-instagram"></i></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="ft-grid">
        <div>
            <div class="ft-logo"><img src="../images/logo.png" alt="Glowear"></div>
            <p class="ft-desc">Handcrafted bijoux that celebrate your unique glow. Each piece is made with intention, love, and the finest materials.</p>
            <div class="ft-social">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
                <a href="#"><i class="fab fa-pinterest-p"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div>
            <h4 class="ft-col-title">Collections</h4>
            <ul class="ft-links">
                <li><a href="../public/shop.php?nom=Necklaces">Necklaces</a></li>
                <li><a href="../public/shop.php?nom=Earrings">Earrings</a></li>
                <li><a href="../public/shop.php?nom=Bracelets">Bracelets</a></li>
                <li><a href="../public/shop.php?nom=Rings">Rings</a></li>
                <li><a href="../public/shop.php?nom=Sets">Sets</a></li>
            </ul>
        </div>
        <div>
            <h4 class="ft-col-title">Information</h4>
            <ul class="ft-links">
                <li><a href="../public/about.php">About Us</a></li>
                <li><a href="#">Care Guide</a></li>
                <li><a href="#">Returns Policy</a></li>
                <li><a href="#">Shipping Info</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
        <div>
            <h4 class="ft-col-title">Contact</h4>
            <div class="ft-contact">
                <span><i class="fas fa-map-marker-alt"></i>Nabeul Mrezgua, El Wafa, Tunisia</span>
                <span><i class="fas fa-phone"></i><a href="tel:+21656725104" style="color:var(--grey);text-decoration:none">+216 56 725 104</a></span>
                <span><i class="fas fa-envelope"></i><a href="mailto:glowear@gmail.com" style="color:var(--grey);text-decoration:none">glowear@gmail.com</a></span>
            </div>
        </div>
    </div>
    <div class="ft-bottom">
        <p class="ft-copy">&copy; 2026 Glowear &mdash; All rights reserved</p>
        <div class="ft-bottom-links"><a href="#">Terms</a><a href="#">Privacy</a><a href="#">Cookies</a></div>
    </div>
</footer>

<a href="#" id="btt" title="Back to top"><i class="fas fa-chevron-up"></i></a>

<!-- JS -->
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/response-formatter.js"></script>
<script src="../js/chatbot.js?v=20260517.1"></script>
<script src="../js/confirm-modal.v9.js"></script>

<script>
const cur  = document.getElementById('cur');
const curR = document.getElementById('cur-ring');
let mx = window.innerWidth / 2, my = window.innerHeight / 2;
let rx = mx, ry = my;
document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
(function animCursor() {
    if (!cur || !curR) return;
    cur.style.left = mx + 'px'; cur.style.top = my + 'px';
    rx += (mx - rx) * 0.14; ry += (my - ry) * 0.14;
    curR.style.left = rx + 'px'; curR.style.top = ry + 'px';
    requestAnimationFrame(animCursor);
})();
document.querySelectorAll('a, button, .account-card, .insta-item').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});


window.addEventListener('scroll', () => {
    const nav = document.getElementById('nav');
    if(nav) nav.classList.toggle('stuck', window.scrollY > 60);
    const btt = document.getElementById('btt'); if(btt) btt.style.display = window.scrollY > 400 ? 'block' : 'none';
});


const allRev = document.querySelectorAll(
    '.rev, .account-card, .welcome-strip, .guest-banner'
);
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
    });
}, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
allRev.forEach(r => obs.observe(r));

const searchBtn = document.getElementById('searchBtn');
if(searchBtn){ searchBtn.addEventListener('click', e => { e.preventDefault(); const ov = document.getElementById('searchOv'); if(ov) ov.style.display='block'; }); }
const searchBtnSide = document.getElementById('searchBtnSide');
if(searchBtnSide){ searchBtnSide.addEventListener('click', e => { e.preventDefault(); const ov = document.getElementById('searchOv'); if(ov) ov.style.display='block'; }); }
const searchClose = document.getElementById('searchClose');
if(searchClose){ searchClose.addEventListener('click', e => { const ov = document.getElementById('searchOv'); if(ov) ov.style.display='none'; }); }
</script>

</body>
</html>

