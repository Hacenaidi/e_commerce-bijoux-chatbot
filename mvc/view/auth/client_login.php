<?php
session_start();
include_once('../../controller/ClientController.php');
include_once('../../controller/PannierController.php');
$controllerpannier = new PannierController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $cc = new ClientController();
    $user = $cc->authenticateClient($mail, $password);
    if ($user) {
        // user is associative array
        $_SESSION['id'] = $user['id'] ?? null;
        $_SESSION['nom'] = $user['nom'] ?? '';
        $_SESSION['prenom'] = $user['prenom'] ?? '';
        $_SESSION['email'] = $user['email'] ?? '';
        $_SESSION['admin'] = false;
        header("Location: ../public/checkout.php");
        exit;
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Glowear &mdash; Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/chatbot-messages.css">
    <link rel="stylesheet" href="../css/response-format.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/client_auth.css?v=1">
</head>
<body class="auth-page">
    <div class="grain" aria-hidden="true"></div>
    <div id="glitter-layer" aria-hidden="true"></div>
    <div id="cur"></div>
    <div id="cur-ring"></div>

    <div class="ticker">
        <div class="ticker-inner">
            <span>&mdash; Handmade with love&mdash;</span><span>&mdash; Elegant bijoux &mdash;</span>
            <span>&mdash; Limited pieces &mdash;</span><span>&mdash; Discover your sparkle &mdash;</span>
            <span>&mdash; Glowear exclusive &mdash;</span><span>&mdash; Wear your glow &mdash;</span>
        </div>
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
                    <li><a href="../account/my-account.php">My Account</a></li>
                </ul>
            </li>
            <li><a href="../public/service.php">Services</a></li>
            <li><a href="../public/contact-us.php">Contact</a></li>
        </ul>
        <div class="nav-icons">
            <a href="#"><i class="fa fa-search"></i></a>
            <a href="../public/cart.php"><i class="fa fa-shopping-bag"></i><span class="cart-count"><?php echo $listpannier ? $listpannier->rowCount() : 0; ?></span></a>
            <a href="client_login.php"><i class="fa fa-user"></i></a>
        </div>
    </nav>

    <main class="auth-wrap">
        <div class="auth-grid">
            <div class="auth-copy">
                <span class="auth-kicker">Client access</span>
                <h1>Sign in to <em>Glow</em></h1>
                <p>Access your account to track orders, continue checkout, and keep your favorite bijoux close at hand.</p>
                <div class="auth-points">
                    <div class="auth-point"><i class="fa fa-check-circle"></i><span>Quick checkout with saved details</span></div>
                    <div class="auth-point"><i class="fa fa-check-circle"></i><span>Order tracking and account history</span></div>
                    <div class="auth-point"><i class="fa fa-check-circle"></i><span>Exclusive client experience</span></div>
                </div>
            </div>

            <div class="auth-card">
                <h2>Sign In</h2>
                <?php if (isset($_GET['registered']) && $_GET['registered'] == '1'): ?>
                    <div class="auth-success">Account created successfully. Please sign in.</div>
                <?php endif; ?>
                <?php if ($message): ?>
                    <div class="auth-error"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <form method="post" action="" class="auth-form">
                    <div class="auth-fields">
                        <div class="auth-field">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" required placeholder="your@email.com">
                        </div>
                        <div class="auth-field">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required placeholder="Password">
                        </div>
                    </div>
                    <button type="submit" class="auth-submit">Login</button>
                </form>
                <a href="client_signup.php" class="auth-link">Don't have an account? Register</a>
            </div>
        </div>
    </main>

    <script>
    (function createGlitter(){
        var layer = document.getElementById('glitter-layer');
        if (!layer) return;
        function spawn(){
            var s = document.createElement('span');
            s.className = 'glitter';
            var size = 2 + Math.random() * 4;
            s.style.width = size + 'px';
            s.style.height = size + 'px';
            s.style.left = Math.random() * 100 + 'vw';
            s.style.top = Math.random() * 100 + 'vh';
            s.style.animationDuration = (1.5 + Math.random() * 2.5) + 's';
            layer.appendChild(s);
            setTimeout(function(){ try { s.remove(); } catch (e) {} }, 4000);
        }
        setInterval(spawn, 140);
    })();
    </script>
</body>
</html>
