<?php
$pannierCtrl = __DIR__ . '/../../controller/PannierController.php';
if (file_exists($pannierCtrl)) include_once($pannierCtrl);
$controller = new PannierController();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id > 0) {
	$controller->deletepannier($id);
}

$requested = isset($_GET['redirect']) ? basename((string) $_GET['redirect']) : 'cart.php';
$redirectMap = [
	'cart.php' => '../public/cart.php',
	'checkout.php' => '../public/checkout.php',
	'shop.php' => '../public/shop.php',
	'index.php' => '../public/index.php',
	'about.php' => '../public/about.php',
	'service.php' => '../public/service.php',
	'contact-us.php' => '../public/contact-us.php',
	'my-account.php' => '../account/my-account.php',
	'orders.php' => '../account/orders.php',
	'login_security.php' => '../account/login_security.php',
	'updatepannier.php' => 'updatepannier.php'
];

if (array_key_exists($requested, $redirectMap)) {
	$redirect = $redirectMap[$requested];
} else {
	$redirect = '../public/cart.php';
}

header('Location: ' . $redirect);
exit;

?>
