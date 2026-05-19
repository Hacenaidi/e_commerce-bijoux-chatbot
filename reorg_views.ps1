$root = 'c:\xampp\htdocs\e_commerce-bijoux-chatbot\mvc\view'
$map = @{
  'about.php' = 'public/about.php'
  'cart.php' = 'public/cart.php'
  'checkout.php' = 'public/checkout.php'
  'contact-us.php' = 'public/contact-us.php'
  'index.php' = 'public/index.php'
  'service.php' = 'public/service.php'
  'shop.php' = 'public/shop.php'
  'my-account.php' = 'account/my-account.php'
  'login_security.php' = 'account/login_security.php'
  'orders.php' = 'account/orders.php'
  'client_login.php' = 'auth/client_login.php'
  'client_signup.php' = 'auth/client_signup.php'
  'admin.php' = 'admin/admin.php'
  'admin_login.php' = 'admin/admin_login.php'
  'admin_dashboard.php' = 'admin/admin_dashboard.php'
  'ajouterProduit.php' = 'admin/ajouterProduit.php'
  'ModifyProduit.php' = 'admin/ModifyProduit.php'
  'modify_action_produit.php' = 'admin/modify_action_produit.php'
  'addToCart.php' = 'actions/addToCart.php'
  'ajax_cancel_order.php' = 'actions/ajax_cancel_order.php'
  'ajax_change_password.php' = 'actions/ajax_change_password.php'
  'ajax_order_details.php' = 'actions/ajax_order_details.php'
  'deletepropannier.php' = 'actions/deletepropannier.php'
  'logout.php' = 'actions/logout.php'
  'placeOrder.php' = 'actions/placeOrder.php'
  'rechercheClient.php' = 'actions/rechercheClient.php'
  'supprimerorder.php' = 'actions/supprimerorder.php'
  'supprimerProduit.php' = 'actions/supprimerProduit.php'
  'updatepannier.php' = 'actions/updatepannier.php'
  'update_action_pannier.php' = 'actions/update_action_pannier.php'
}

foreach ($item in $map.GetEnumerator()) {
  $sourcePath = Join-Path $root $item.Key
  $destinationPath = Join-Path $root $item.Value
  Copy-Item -Path $sourcePath -Destination $destinationPath -Force

  $content = Get-Content -Path $destinationPath -Raw
  $content = $content -replace "\$viewHelpers = __DIR__ \. '/inc/view_helpers\.php';", "`$viewHelpers = __DIR__ . '/../inc/view_helpers.php';"
  Set-Content -Path $destinationPath -Value $content -Encoding UTF8

  $relative = $item.Value.Replace('\', '/')
  $wrapper = "<?php`r`nchdir(__DIR__);`r`nrequire __DIR__ . '/$relative';`r`n"
  Set-Content -Path $sourcePath -Value $wrapper -Encoding UTF8
}
