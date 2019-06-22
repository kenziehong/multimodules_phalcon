<?php
// ! VIẾT LẠI TOÀN BỘ MODEL NÀY CHO ĐÚNG FORMAT
namespace App\Common\Model;

use App\Common\Enum\CartEnum;
use App\Common\Enum\CountryEnum;
use App\Common\Enum\ResponseEnum;
use App\Common\Util\GeneralUtil;
use App\Base\Model\Model;
use App\Common\Util\ResponseUtil;
use App\Common\Util\HttpUtil;

class CartModel extends Model {
  private static $instance = null;

  private $strApiUrl = API_URL;
  private $arrHeader = [];

  private function __construct() {
    parent::__construct();
  }

  private function __clone() {
  }

  private function __wakeup() {
  }

  /**
   * Get the value of strApiUrl
   */
  public function getApiUrl() {
    return $this->strApiUrl;
  }

  /**
   * Set the value of strApiUrl
   *
   * @return  self
   */
  public function setApiUrl($strApiUrl) {
    $this->strApiUrl = $strApiUrl;

    return $this;
  }

  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new CartModel();
    }

    return self::$instance;
  }

  /**
   * Get the value of arrHeader
   */
  public function getHeader() {
    $this->arrHeader[] = 'cartDataEncrypt: ' . $this->_session->get('cartDataEncrypt');

    return $this->arrHeader;
  }

  /**
   * Set the value of arrHeader
   * @param Array $arrHeader
   * @return self
   */
  public function setHeader($arrHeader) {
    if ($arrHeader) {
      foreach ($arrHeader as $strKey => $strHeader) {
        $this->arrHeader[] = $strKey . ': ' . $strHeader;
      }
    }

    return $this;
  }

  /**
   * API Origin url: http://staging.fado.vn/api/v3/order/add-cart?lang=us&asin=B07D1HMJ1B&merchant=ATVPDKIKX0DER&quantity=2&enterprise=0
   * Thêm sản phẩm vào giỏ hàng
   *
   * @param Array $arrParam
   * @return Array
   */
  public function addCart(array $arrReqParam) {
    $arrReqParam = [
      'asin' => $this->_filter->string($arrReqParam['asin']),
      'lang' => $this->_filter->string($arrReqParam['lang']),
      'merchant' => $this->_filter->string($arrReqParam['merchant']),
      'quantity' => $this->_filter->string($arrReqParam['quantity']),
      'enterprise' => $this->_filter->int($arrReqParam['enterprise']),
      'isStore' => $this->_filter->int($arrReqParam['isStore']),
      'isAVN' => $this->_filter->int($arrReqParam['isAVN']),
      'storeProductID' => $this->_filter->int($arrReqParam['storeProductID']),
      'strJsonEncrypted' => $this->_filter->string($arrReqParam['strJsonEncrypted']),
      'productName' => $this->_filter->string($arrReqParam['productName']),
      'productNote' => $this->_filter->string($arrReqParam['productNote']),
      'productImage' => $this->_filter->string($arrReqParam['productImage']),
      'productCondition' => $this->_filter->string($arrReqParam['productCondition']),
      'shippingProviderId' => $this->_filter->int($arrReqParam['shippingProviderId']),
    ];

    $arrError = [];

    if (!CountryEnum::isValidCode($arrReqParam['lang'])) {
      $arrError['lang'] = 'Mã quốc gia sản phẩm không hợp lệ';
    }

    if (!$arrReqParam['asin']) {
      $arrError['asin'] = 'Mã sản phẩm không hợp lệ';
    }

    if (!$arrReqParam['productName']) {
      $arrError['productName'] = 'Tên sản phẩm không hợp lệ';
    }

    if (!$arrReqParam['strJsonEncrypted']) {
      $arrError['strJsonEncrypted'] = 'Tham số strJsonEncrypted không hợp lệ';
    }

    if ($arrError) {
      return ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_REQUEST_PARAM, null, [
        'error' => $arrError,
      ]);
    }

    $strRequestUrl = $this->strApiUrl . "/order/add-cart";

    $arrReqResult = HttpUtil::sendRequest('POST', $strRequestUrl, $arrReqParam, $this->getHeader());
    $dataResponse = $arrReqResult['dataResponse'];

    if ($dataResponse && $dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_SUCCESS, 'Thêm sản phẩm vào giỏ hàng thành công', [
        'data' => $dataResponse['data'],
      ]);
    } elseif ($dataResponse && !$dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_REQUEST_PARAM, $dataResponse['message'], [
        'error' => $dataResponse['error'],
      ]);
    } else {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_API, 'Yêu cầu xử lý dữ liệu không thành công');
    }

    $arrConfig = $this->getArrConfig([]);
    if ($arrConfig['enableDebugField']) {
      $dataResponse['debug'] = $arrReqResult['requestDebug'];
    }

    if ($dataResponse['success']) {
      $this->setCartInfo($dataResponse['data']);
    }

    return $dataResponse;
  }

  /**
   * API Origin url: http://staging.fado.vn/api/v3/order/get-cart
   * lấy tất cả sản phẩm trong giò hàng
   *
   * @param Array $arrParam
   * @return Array
   */
  public function getCart($arrParam = []) {
    $strRequestUrl = $this->strApiUrl . "/order/get-cart";

    $arrReqRsult = HttpUtil::sendRequest('POST', $strRequestUrl, [
      'listAsin' => $this->_filter->string($arrParam['listAsin']),
      'cartType' => $this->_filter->int($arrParam['cartType']),
    ], $this->getHeader());

    $dataResponse = $arrReqRsult['dataResponse'];

    if ($dataResponse && $dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_SUCCESS, 'Lấy giỏ hàng thành công', [
        'data' => $dataResponse['data'],
      ]);
    } elseif ($dataResponse && !$dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_REQUEST_PARAM, $dataResponse['message'], [
        'error' => $dataResponse['data'],
      ]);
    } else {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_API, 'Yêu cầu xử lý dữ liệu không thành công');
    }

    if ($dataResponse['success']) {
      $this->setCartInfo($dataResponse['data']);
    }

    return $dataResponse;
  }

  /**
   * API Origin url: http://staging.fado.vn/api/v3/order/remove-cart
   * xóa sản phẩm trong giò hàng
   *
   * @param Array $arrParam
   * @return Array
   */
  public function removeCart($arrParam = []) {
    $arrParamQuery = [
      'lang' => $this->_filter->string($arrParam['lang']),
      'enterprise' => $this->_filter->int($arrParam['enterprise']),
      'buyLater' => $this->_filter->int($arrParam['buyLater']),
      'isStore' => $this->_filter->int($arrParam['isStore']),
      'isAVN' => $this->_filter->int($arrParam['isAVN']),
      'keyCart' => $this->_filter->string($arrParam['keyCart']),
    ];

    $strRequestUrl = $this->strApiUrl . "/order/remove-cart";

    $arrReqResult = HttpUtil::sendRequest('GET', $strRequestUrl, $arrParamQuery, $this->getHeader());
    $dataResponse = $arrReqResult['dataResponse'];

    if ($dataResponse && $dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_SUCCESS, 'Xóa giỏ hàng thành công', [
        'data' => $dataResponse['data'],
      ]);
    } elseif ($dataResponse && !$dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_REQUEST_PARAM, $dataResponse['message'], [
        'error' => $dataResponse['data'],
      ]);
    } else {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_API, 'Yêu cầu xử lý dữ liệu không thành công');
    }

    if ($dataResponse['success']) {
      $this->setCartInfo($dataResponse['data']);
    }

    return $dataResponse;
  }

  /**
   * API Origin url: http://staging.fado.vn/api/v3/order/update-cart
   * cập nhật sản phẩm trong giò hàng
   *
   * @param Array $arrParam
   * @return Array
   */
  public function updateCart($arrParam = []) {
    $arrParamQuery = [
      'lang' => $this->_filter->string($arrParam['lang']),
      'quantity' => $this->_filter->string($arrParam['quantity']),
      'enterprise' => $this->_filter->int($arrParam['enterprise']),
      'isStore' => $this->_filter->int($arrParam['isStore']),
      'isAVN' => $this->_filter->int($arrParam['isAVN']),
      'keyCart' => $this->_filter->string($arrParam['keyCart']),
      'shippingProviderId' => $this->_filter->int($arrParam['shippingProviderId']),
    ];

    $strRequestUrl = $this->strApiUrl . "/order/update-cart";
    $arrReqResult = HttpUtil::sendRequest('GET', $strRequestUrl, $arrParamQuery, $this->getHeader());
    $dataResponse = $arrReqResult['dataResponse'];

    if ($dataResponse && $dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_SUCCESS, 'Cập nhật giỏ hàng thành công', [
        'data' => $dataResponse['data'],
      ]);
    } elseif ($dataResponse && !$dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_REQUEST_PARAM, $dataResponse['message'], [
        'error' => $dataResponse['data'],
      ]);
    } else {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_API, 'Yêu cầu xử lý dữ liệu không thành công');
    }

    if ($dataResponse['success']) {
      $this->setCartInfo($dataResponse['data']);
    }

    return $dataResponse;
  }

  /**
   * API Origin url: http://staging.fado.vn/api/v3/order/cart-to-buy-later
   * chuyển sản phẩm trong giỏ hàng sang mua sau
   *
   * @param Array $arrParam
   * @return Array
   */
  public function cartToBuyLater($arrParam = []) {
    $arrParamQuery = [
      'lang' => $this->_filter->string($arrParam['lang']),
      'enterprise' => $this->_filter->int($arrParam['enterprise']),
      'isStore' => $this->_filter->int($arrParam['isStore']),
      'isAVN' => $this->_filter->int($arrParam['isAVN']),
      'keyCart' => $this->_filter->string($arrParam['keyCart']),
    ];

    $strRequestUrl = $this->strApiUrl . "/order/cart-to-buy-later";
    $arrReqResult = HttpUtil::sendRequest('GET', $strRequestUrl, $arrParamQuery, $this->getHeader());
    $dataResponse = $arrReqResult['dataResponse'];

    if ($dataResponse && $dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_SUCCESS, 'Thêm sản phẩm vào danh sách mua sau thành công', [
        'data' => $dataResponse['data'],
      ]);
    } elseif ($dataResponse && !$dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_REQUEST_PARAM, $dataResponse['message'], [
        'error' => $dataResponse['data'],
      ]);
    } else {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_API, 'Yêu cầu xử lý dữ liệu không thành công');
    }

    if ($dataResponse['success']) {
      $this->setCartInfo($dataResponse['data']);
    }

    return $dataResponse;
  }

  /**
   * API Origin url: http://staging.fado.vn/api/v3/order/buy-later-to-cart
   * chuyển sản phẩm mua sau sang giỏ hàng
   *
   * @param Array $arrParam
   * @return Array
   */
  public function buyLaterToCart($arrParam = []) {
    $arrParamQuery = [
      'lang' => $this->_filter->string($arrParam['lang']),
      'enterprise' => $this->_filter->int($arrParam['enterprise']),
      'isStore' => $this->_filter->int($arrParam['isStore']),
      'isAVN' => $this->_filter->int($arrParam['isAVN']),
      'keyCart' => $this->_filter->string($arrParam['keyCart']),
    ];

    $strRequestUrl = $this->strApiUrl . "/order/buy-later-to-cart";
    $arrReqResult = HttpUtil::sendRequest('GET', $strRequestUrl, $arrParamQuery, $this->getHeader());
    $dataResponse = $arrReqResult['dataResponse'];

    if ($dataResponse && $dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_SUCCESS, 'Thêm sản phẩm vào giỏ hàng thành công', [
        'data' => $dataResponse['data'],
      ]);
    } elseif ($dataResponse && !$dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_REQUEST_PARAM, $dataResponse['message'], [
        'error' => $dataResponse['data'],
      ]);
    } else {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_API, 'Yêu cầu xử lý dữ liệu không thành công');
    }

    if ($dataResponse['success']) {
      $this->setCartInfo($dataResponse['data']);
    }

    return $dataResponse;
  }

  /**
   * API Origin url: http://staging.fado.vn/api/v3/order/check-cart
   * kiểm tra sản phẩm trong giỏ hàng có thay đổi giá hay không
   *
   * @param Array $arrParam
   * @return Array
   */
  public function checkCart($arrParam = []) {
    $strRequestUrl = $this->strApiUrl . "/order/check-cart";
    $arrReqResult = HttpUtil::sendRequest('GET', $strRequestUrl, [], $this->getHeader());

    $dataResponse = $arrReqResult['dataResponse'];

    if ($dataResponse && $dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_SUCCESS, 'kiểm tra giỏ hàng thành công', [
        'data' => $dataResponse['data'],
      ]);
    } elseif ($dataResponse && !$dataResponse['success']) {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_REQUEST_PARAM, $dataResponse['message'], [
        'error' => $dataResponse['data'],
      ]);
    } else {
      $dataResponse = ResponseUtil::getArrResponse(ResponseEnum::INT_CODE_ERROR_API, 'Yêu cầu xử lý dữ liệu không thành công');
    }

    if ($dataResponse['success']) {
      $this->setCartInfo($dataResponse['data']);
    }

    return $dataResponse;
  }

  /**
   * lấy số lượng sản phẩm trong giỏ hàng
   * type = 1: sản phẩm nhập khẩu DDP
   * type = 2: sản phẩm nhập khẩu CIF
   * type = 3: sản phẩm mua sau nhập khẩu DDP
   * type = 4: sản phẩm mua sau nhập khẩu CIF
   * @param Array $arrCartData
   * @param Mixed $type
   * @return int totalQuantity
   */
  public function getTotalQuantity($type = '') {
    $session = $this->_session;

    if (!$session->has('totalCartData')) {
      return 0;
    }

    $arrTotalCartData = $session->get('totalCartData');
    /**
     * Mặc định sẽ lấy tổng sản phẩm trong giỏ hàng DDP
     * và CIF, không tính sản phẩm mua sau trong giỏ hàng
     */
    if (!$type) {
      return (int) $arrTotalCartData[CartEnum::INT_TYPE_DDP]['totalQuantityInCart'] + (int) $arrTotalCartData[CartEnum::INT_TYPE_CIF]['totalQuantityInCart'];
    }

    $intTotalQuantity = 0;
    if (is_array($type)) {
      foreach ($type as $t) {
        $intTotalQuantity += (int) $arrTotalCartData[$t]['totalQuantityInCart'];
      }
    } else {
      $intTotalQuantity = (int) $arrTotalCartData[$type]['totalQuantityInCart'];
    }

    return $intTotalQuantity;
  }

  public function setCartInfo($arrNewCartInfo) {
    if (!$arrNewCartInfo) {
      return;
    }

    $session = $this->_session;

    if ($arrNewCartInfo['total_cart_data']) {
      $session->set('totalCartData', $arrNewCartInfo['total_cart_data']);
    }

    if ($arrNewCartInfo['cart_data_encrypt']) {
      $session->set('cartDataEncrypt', $arrNewCartInfo['cart_data_encrypt']);
    }
  }
}
