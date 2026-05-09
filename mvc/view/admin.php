<?php
session_start();
require_once('../controller/SessionController.php');
$sessionController = new SessionController();
$sessionController->redirectIfAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admin_login.css?v=4">
</head>
<body class="admin-login-page">
	<div class="admin-login-overlay"></div>
	<main class="admin-login-shell">
		<section class="admin-login-card">
			<div class="admin-login-badge">Admin access</div>

			<h1>Admin</h1>
			<p class="admin-login-copy">Enter your administrator credentials to open the dashboard and manage products, orders, and stock.</p>

			<?php if (isset($_SESSION['message'])) { ?>
				<div class="login-alert admin-login-alert">
					<?php echo htmlspecialchars($_SESSION['message']); ?>
				</div>
				<?php unset($_SESSION['message']); ?>
			<?php } ?>

			<form class="admin-login-form" action="admin_login.php" method="post">
				<fieldset class="admin-login-fieldset">
					<div class="field">
						<label for="email">Login</label>
						<input type="email" id="email" name="email" placeholder="admin@example.com" required autofocus>
					</div>

					<div class="field">
						<label for="password">Password</label>
						<input type="password" id="password" name="password" placeholder="Your password" required>
					</div>
				</fieldset>

				<button type="submit">Login</button>
			</form>
		</section>
	</main>
</body>
</html>
