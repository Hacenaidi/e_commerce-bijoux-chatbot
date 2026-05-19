<?php
include_once('../../model/Client.php');
include_once('../../controller/ClientController.php');
include_once('../../controller/PannierController.php');
$controllerpannier = new PannierController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $clientController = new ClientController();
    $exists = $clientController->findByEmail($email);
    if ($exists && $exists->rowCount() > 0) {
        $message = "An account with this email already exists.";
    } else {
        $newClient = new Client('', $nom, $prenom, $password, $email);
        $clientController->createClient($newClient);
        header("Location: client_login.php?registered=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Glowear â€” Register</title>
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
            <span>âœ¦ Handmade with love</span><span>âœ¦ Elegant bijoux ðŸ’–</span>
            <span>âœ¦ Limited pieces ðŸ’Ž</span><span>âœ¦ Discover your sparkle ðŸŒ¸</span>
            <span>âœ¦ Glowear exclusive âœ¨</span><span>âœ¦ Wear your glow ðŸŒ™</span>
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
                <span class="auth-kicker">Client registration</span>
                <h1>Create your <em>Glow</em> account</h1>
                <p>Join Glowear to save your details, track your orders, and enjoy a smoother shopping experience across the boutique.</p>
                <div class="auth-points">
                    <div class="auth-point"><i class="fa fa-check-circle"></i><span>Fast checkout and saved cart</span></div>
                    <div class="auth-point"><i class="fa fa-check-circle"></i><span>Order history and updates</span></div>
                    <div class="auth-point"><i class="fa fa-check-circle"></i><span>Premium client access</span></div>
                </div>
            </div>

            <div class="auth-card">
                <h2>Create Account</h2>
                <?php if ($message): ?>
                    <div class="auth-error"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <form method="post" action="" class="auth-form">
                    <div class="auth-fields">
                        <div class="auth-field">
                            <label for="nom">First Name</label>
                            <input type="text" name="nom" id="nom" required placeholder="First name">
                        </div>
                        <div class="auth-field">
                            <label for="prenom">Last Name</label>
                            <input type="text" name="prenom" id="prenom" required placeholder="Last name">
                        </div>
                        <div class="auth-field">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" required placeholder="your@email.com">
                        </div>
                        <div class="auth-field">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
                        </div>
                    </div>
                    <button type="submit" class="auth-submit">Register</button>
                </form>
                <a href="client_login.php" class="auth-link">Already have an account? Login</a>
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
