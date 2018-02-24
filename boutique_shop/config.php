<?php
define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/boutique_shop/');
define('CART_COOKIE', '87AZ54ER65TY87YU');
define('CART_COOKIE_EXPIRE', time() + (86000 * 30));
define('TAXRATE', 0.087);

define('CURRENCY', 'USD');
define('CHECKOUTMODE', 'TEST');

if(CHECKOUTMODE == 'TEST'){  // changr to live if is exist
   define('STRIPE_PRIVATE', 'sk_test_UjI8RC4rfLAAuANNJM1dOSrE');
   define('STRIPE_PUBLIC', 'pk_test_qOP0wWUbMjOnADCFOFBTPWKi');

}
if(CHECKOUTMODE == 'LIVE'){
   define('STRIPE_PRIVATE', 'sk_live_ULbDXn3nBNAiWjNoIgPIc3Xs');
   define('STRIPE_PUBLIC', 'pk_live_s1xojjqo68kyuU8ONUbCuoXp');

}
