<?php
session_start();
include_once('../controller/ClientController.php');
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $cc = new ClientController();
    $res = $cc->authenticateClient($mail, $password);
    if ($res && $res->rowCount() == 1) {
        $l = $res->fetch();
        $_SESSION['id'] = $l[0];
        $_SESSION['nom'] = $l[1];
        $_SESSION['prenom'] = $l[2];
        $_SESSION['email'] = $l[4];
        $_SESSION['admin'] = false;
        header("Location: checkout.php");
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
    <title>Glowear — Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/chatbot-widget.css">
    <link rel="stylesheet" href="css/chatbot-messages.css">
    <link rel="stylesheet" href="css/response-format.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
    <style>
        body { background: #080808; color: #F0EAE0; font-family: 'Montserrat', sans-serif; }
        .auth-row { max-width: 520px; margin: 90px auto; }
        .auth-panel { background: #181818; padding: 48px 44px; border-radius: 10px; box-shadow: 0 4px 32px rgba(0,0,0,0.18); }
        .auth-title { font-family: 'Cormorant Garamond', serif; font-size: 34px; color: #fff; margin-bottom: 22px; }
        .gl-field { margin-bottom: 20px; }
        .gl-field label { font-size: 11px; letter-spacing: 3px; color: #C0392B; text-transform: uppercase; margin-bottom: 7px; display: block; }
        .gl-field input { width: 100%; padding: 13px 14px; background: #101010; border: 1px solid #333; color: #fff; border-radius: 4px; font-size: 15px; }
        .btn-red { background: #C0392B; color: #fff; border: none; padding: 13px 0; width: 100%; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; border-radius: 4px; margin-top: 10px; }
        .btn-red:hover { background: #E74C3C; }
        .login-link { display: block; margin-top: 18px; color: #C9A84C; text-align: center; text-decoration: none; }
        .error-msg { color: #E74C3C; margin-bottom: 14px; text-align: center; }
    </style>
</head>
<body>
    <div class="auth-row">
        <div class="auth-panel">
            <h2 class="auth-title">Sign In</h2>
            <?php if ($message): ?>
                <div class="error-msg"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="gl-field">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" required placeholder="your@email.com">
                </div>
                <div class="gl-field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-red">Login</button>
            </form>
            <a href="client_signup.php" class="login-link">Don't have an account? Register</a>
        </div>
    </div>
</body>
</html>