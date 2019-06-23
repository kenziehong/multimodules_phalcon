<?php

namespace App\Module\Desktop\Controller\Order;

use App\Base\Controller\Controller;
use App\Common\Util\UrlUtil;

class OrderController extends Controller {
  public function indexAction() {
    $arrReqParam = array_merge($this->arrDispatcherParam, $_POST);
    $providerModel = $this->model->get('providerModel');
    $arrResCartInfoModel = $this->model->get('cart')->getCart($arrReqParam);
    $arrResponseShippingProviderList = $providerModel->getShippingProviderList();

    $arrCartInfo = $arrResCartInfoModel['data'];

    $this->di->set('gtmTracker', function () use ($arrCartInfo) {
      return [
        'arrCartInfo' => $arrCartInfo,
      ];
    });

    $this->view->setVars([
      'strBodyClass' => 'order-page',
      'strPageHeadTitle' => $this->language->get('page_order_head_title'),
      'arrResCartInfo' => $arrResCartInfoModel,
      'arrParam' => ['fromRoute' => $arrReqParam, 'fromPost' => $_POST],
      'arrShippingProviderList' => $arrResponseShippingProviderList['data']['arrShippingProvider'],
    ]);
  }

  public function checkoutCompleteAction() {
    $arrReqParam = $_GET;

    if (empty($arrReqParam)) {
      return $this->response->redirect(BASE_URL);
    }

    $arrReqParam = $arrReqParam + [
      'paymentGateway' => $this->arrDispatcherParam['paymentGateway'],
    ];

    if (MY_DEBUG == 'php') {
      unset($arrReqParam['myDebug']);
    }

    $arrResCheckoutBankingComplete = $this->model->get('order')->postCheckoutBankingCompleteInfo($arrReqParam);

    if (MY_DEBUG == 'php') {
      var_dump('$arrResCheckoutBankingComplete', $arrResCheckoutBankingComplete);
      die();
    }

    // Lưu lại tin nhắn báo lỗi của payment gateway để hiển thị cho khách biết
    $this->session->set('arrErrorPaymentBanking', $arrResCheckoutBankingComplete['error']);

    if (
      !empty($arrResCheckoutBankingComplete['data']['strOrderId']) &&
      !empty($arrResCheckoutBankingComplete['data']['phone'])
    ) {
      $this->session->set('allowViewOrderFinishPage', true);

      $strRedirectUrl = $this->url->get(['for' => 'orderFinishPage'], [
        'orderId' => $arrResCheckoutBankingComplete['data']['strOrderId'],
        'phone' => $arrResCheckoutBankingComplete['data']['phone'],
      ]);

      return $this->response->redirect(UrlUtil::decodeWithPlus($strRedirectUrl));
    }

    if ($this->arrLoginInfo) {
      $strRedirectUrl = $this->url->get(['for' => 'userOrderHistoryPage']);

      return $this->response->redirect($strRedirectUrl);
    } else {
      return $this->response->redirect(BASE_URL);
    }
  }

  public function finishAction() {
    if (
      !$this->session->get('allowViewOrderFinishPage') &&
      MY_DEBUG != 'php'
    ) {
      $this->response->redirect(BASE_URL);

      return;
    }

    $this->session->remove('allowViewOrderFinishPage');

    $arrErrorPaymentBanking = $this->session->get('arrErrorPaymentBanking');
    $this->session->remove('arrErrorPaymentBanking');

    $strOrderId = $_GET['orderId'];
    $strPhone = $_GET['phone'];

    if (empty($strOrderId) || empty($strPhone)) {
      $this->response->redirect(BASE_URL);

      return;
    }

    if ($this->arrLoginInfo) {
      $arrResOrderDetailModel = $this->model->get('order')->getOrderDetail([
        'orderID' => $strOrderId,
        'phone' => $strPhone,
      ]);
    } else {
      $arrResOrderDetailModel = $this->model->get('order')->searchOrder([
        'orderID' => $strOrderId,
        'phone' => $strPhone,
      ]);
    }

    if (MY_DEBUG == 'php') {
      var_dump('$arrResOrderDetailModel');
      var_dump($arrResOrderDetailModel);
      die();
    }

    if (!$arrResOrderDetailModel['success']) {
      $this->response->redirect(BASE_URL);

      return;
    }

    // settup GTM DI tiếp nhận dữ liệu
    $this->di->set('gtmTracker', function () use ($arrResOrderDetailModel) {
      return [
        'arrOrderInfo' => $arrResOrderDetailModel['data'],
      ];
    });

    #region - setup dataFado tracker
    $arrDataFado = [
      'publishers' => [
        'name' => 'fado',
      ],
      'page' => [
        'type' => 'order',
      ],
      'user' => [
        'email' => $this->arrLoginInfo['email'],
      ],
      'order' => [
        'order_id' => $strOrderId,
        'items' => [],
      ],
    ];

    foreach ($arrResOrderDetailModel['data']['arrOrderItemList'] as $arrProductItemList) {
      foreach ($arrProductItemList as $arrProductItem) {
        $arrCategoryList = [];

        foreach ($arrProductItem['dataProductInfo']['categoryInfo']['browseNode'] as $arrCategoryItem) {
          $arrCategoryList[] = [
            'category_id' => $arrCategoryItem['nodeID'],
            'category_name' => $arrCategoryItem['categoryName'],
          ];
        }

        $arrDataFado['order']['items'][] = [
          'order_id' => $strOrderId,
          'order_item_id' => $arrProductItem['orderItemID'],
          'id' => $arrProductItem['asin'],
          'name' => $arrProductItem['productName'],
          'price' => $arrProductItem['totalPrice'],
          'quantity' => $arrProductItem['intQuantity'],
          'category' => $arrCategoryList,
          'product_url' => $arrProductItem['strURL'],
          'lang' => $arrProductItem['language'],
        ];
      }
    }
    #endregion

    $this->view->setVars([
      'strBodyClass' => 'order-finish-page',
      'strPageHeadTitle' => 'Tạo đơn hàng thành công | ' . BRAND_NAME,
      'arrOrderDetailData' => $arrResOrderDetailModel['data'],
      'arrDataFado' => $arrDataFado,
      'arrErrorPaymentBanking' => $arrErrorPaymentBanking,
    ]);
  }
}
