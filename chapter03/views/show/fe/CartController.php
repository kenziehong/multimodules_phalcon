<?php
namespace App\Module\Desktop\Controller\Main;

use App\Base\Controller\Controller;

class CartController extends Controller {
  public function indexAction() {
    $cartModel = $this->model->get('cart');
    $providerModel = $this->model->get('providerModel');
    $arrResponse = $cartModel->getCart();
    $arrResponseShippingProviderList = $providerModel->getShippingProviderList();

    if ($arrResponse['success']) {
      $cartModel->setCartInfo($arrResponse['data']);
    }

    $arrCartInfo = $arrResponse['data'];

    $this->di->set('gtmTracker', function () use ($arrCartInfo) {
      return [
        'arrCartInfo' => $arrCartInfo,
      ];
    });

    // Setup debug
    if (MY_DEBUG == 'php') {
      var_dump('$arrResponse', $arrResponse);
      die();
    }

    $this->view->setVars([
      'strPageHeadTitle' => 'Giỏ hàng | ' . BRAND_DOMAIN_NAME,
      'strBodyClass' => 'cart-page',
      'arrCartInfo' => $arrCartInfo,
      'arrShippingProviderList' => $arrResponseShippingProviderList['data']['arrShippingProvider'],
    ]);
  }
}
