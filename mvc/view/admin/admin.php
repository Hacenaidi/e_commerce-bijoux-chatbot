<?php
session_start();
require_once('../../controller/SessionController.php');
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
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/base.css">
  <link rel="stylesheet" href="../css/admin_login.css?v=4">
</head>
<body class="admin-login-page">
	<div class="grain" aria-hidden="true"></div>
	<div id="glitter-layer" aria-hidden="true"></div>
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

	<script>
	(function initGoldSnow(){
		var layer = document.getElementById('glitter-layer');
		if (!layer) return;
		function createGold(){
			var glitter = document.createElement('span');
			glitter.className = 'glitter';
			var size = 2 + Math.random() * 4;
			glitter.style.width = size + 'px';
			glitter.style.height = size + 'px';
			glitter.style.left = (Math.random() * 100) + 'vw';
			glitter.style.top = (Math.random() * 100) + 'vh';
			glitter.style.animationDuration = (1.6 + Math.random() * 2.4) + 's';
			layer.appendChild(glitter);
			setTimeout(function(){ try{ glitter.remove(); }catch(e){} }, 4200);
		}
		var gid = setInterval(createGold, 420);
		document.addEventListener('visibilitychange', function(){ if (document.hidden) clearInterval(gid); });
	})();
	</script>
</body>
</html>

