<template>
  <section class="cart-block">
    <div class="container cart-block__container">
      <div class="cart-block__block-head">
        <i class="svg -svg-cart-grd -svg-24px margin--right-5px"></i>
        <span class="cart-block__block-title">Giỏ hàng của quý khách</span>
        <span class="cart-block__total-quantity">[ {{ getTotalCartQuantity() }} sản phẩm ]</span>
      </div>

      <ul class="cart-block__cart-tab-list">
        <li
          class="cart-block__cart-tab-item"
          :class="{'is-active': objTabActive[0]}"
          @click.prevent="changeTab(0, $event)"
        >
          Nhập khẩu chính ngạch <span class="cart-block__quantity">[ {{ getTotalCartQuantity(0) }} sản phẩm ]</span>
        </li>

        <li
          class="cart-block__cart-tab-item"
          :class="{'is-active': objTabActive[1]}"
          @click.prevent="changeTab(1, $event)"
        >
          Sản phẩm xuất hóa đơn <span class="cart-block__quantity">[ {{ getTotalCartQuantity(1) }} sản phẩm ]</span>
        </li>
      </ul>

      <div
        v-if="
          (!isEmpty(arrOutOfStockNoneEnterpriseProductCart) && getCurrTabActive() == CART_ENUM.INT_TYPE_DDP) ||
            (!isEmpty(arrOutOfStockEnterpriseProductCart) && getCurrTabActive() == CART_ENUM.INT_TYPE_CIF)
        "
        class="my-alert -alert-warning cart-block__out-of-stock-alert"
      >
        <template v-if="arrOutOfStockEnterpriseProductCart || arrOutOfStockNoneEnterpriseProductCart">
          <span class="cart-block__out-of-stock-alert__title">Những sản phẩm sau đã hết hàng, không thể báo giá được, mong quý khách thông cảm:</span>
          <template v-if="!getCurrTabActive()">
            <template v-for="(strOutOfStockProductNameCart,index) in arrOutOfStockNoneEnterpriseProductCart">
              <div
                :key="index"
                class="cart-block__out-of-stock-alert__content"
                v-html="'- ' + strOutOfStockProductNameCart"
              >
              </div>
            </template>
          </template>
          <template v-else>
            <template v-for="(strOutOfStockProductNameCart,index) in arrOutOfStockEnterpriseProductCart">
              <div
                :key="index"
                class="cart-block__out-of-stock-alert__content"
                v-html="'- ' + strOutOfStockProductNameCart"
              >
              </div>
            </template>
          </template>
        </template>
      </div>

      <table v-if="!emptyCart" class="cart-block__cart-tb">
        <thead>
          <tr>
            <th class="cart-block__web-th">Từ</th>
            <th class="cart-block__payment-th">Thanh<br />toán</th>
            <th class="cart-block__product-info-th">Thông tin sản phẩm / Link website</th>
            <th class="cart-block__delivery-method">Hình thức giao hàng</th>
            <th class="cart-block__product-price-th">Giá</th>
            <th class="cart-block__quantity-th">Số lượng</th>
            <th class="cart-block__price-th">Thành tiền</th>
            <th class="cart-block__control-th">Thao tác</th>
          </tr>
        </thead>

        <template v-for="(arrCartList, language) in arrCartData[intCartType]">
          <tbody :key="language" class="cart-block__web-tbody">
            <template v-for="(arrCart, keyCart) in arrCartList">
              <CartBlockProductTr
                v-if="arrCart"
                :key="`${intCartType}-${language}-${keyCart}`"
                :initial-cart.sync="arrCart"
                :int-cart-type="intCartType"
                :int-row="calculateTotalRows(language)"
                :str-language="language"
                :arr-favorite-product-data="arrFavoriteProductData"
                :str-key-cart="keyCart"
                :obj-total-rows="objTotalRows"
                :obj-item-selected="objItemSelected"
                :arr-cart-change="getDataCartChange(intCartType, language, keyCart)"
                @update-cart="updateCart(arrCart, $event)"
                @remove-cart="removeCart(arrCart, $event)"
                @move-cart-to-buy-later="moveCartToBuyLater(arrCart, $event)"
                @item-selected="itemSelected"
              />
            </template>
          </tbody>
        </template>
      </table>

      <div v-else>
        <div
          v-if="!arrCartData[0] && !arrCartData[1]"
          class="cart-block__empty-cart-segment"
        >
          <div class="cart-block__empty-cart-segment__title">Không có sản phẩm trong<br />giỏ hàng của quý khách</div>
        </div>

        <div
          v-else-if="!arrCartData[0] && objTabActive[0]"
          class="cart-block__empty-cart-segment"
        >
          <div class="cart-block__empty-cart-segment__title">Không có sản phẩm nhập khẩu chính ngạch<br />trong giỏ hàng của quý khách</div>
        </div>

        <div
          v-else-if="!arrCartData[1] && objTabActive[1]"
          class="cart-block__empty-cart-segment"
        >
          <div class="cart-block__empty-cart-segment__title">Không có sản phẩm xuất hóa đơn<br />trong giỏ hàng của quý khách</div>
        </div>
      </div>

      <template v-if="arrCartData[intCartType]">
        <div v-if="arrTotalCartData[intCartType]['totalNewPriceVNDInCart'] > 1000000" class="cart-block__free-ship-alert">
          Đơn hàng của quý khách đã đủ điều kiện được miễn phí giao hàng trong nước. Chọn [<a class="text-success" href="#" @click.prevent="startOrder">Tiến hành thanh toán</a>]
        </div>

        <div v-else class="my-alert -alert-warning margin--top-15px text--align-right">
          Miễn phí giao hàng trong nước cho đơn hàng từ 1,000,000 <sup>đ</sup>
        </div>
      </template>

      <div v-if="!emptyCart" class="cart-block__total-price-field">
        <span class="cart-block__total-price-lbl">Tổng tiền:</span>
        <span class="cart-block__total-price-val">{{ arrTotalCartData[intCartType]['totalNewPriceVNDInCart'] | formatVnPrice }} <sup>đ</sup></span>
      </div>

      <div v-if="!emptyCart" class="cart-block__btn-wrap">
        <a class="my-btn -btn-pill -btn-grd-border" href="/">
          <div class="my-btn__inner">Tiếp tục mua hàng</div>
        </a>

        <button class="my-btn -btn-pill -btn-grd-bg" @click.prevent="startOrder">
          Tiến hành thanh toán <i class="far fa-angle-double-right margin--left-5px"></i>
        </button>
      </div>

      <div v-if="!emptyCart" class="cart-block__price-increase-alert">
        * Quý khách nên thanh toán ngay để tránh sản phẩm bị tăng giá
      </div>
    </div><!-- .cart-block__container -->

    <form
      ref="startOrderForm"
      class="cart-block__order-form"
      :action="getOrderPageUrl"
      method="POST"
    >
      <input type="hidden" name="listAsin" :value="objItemSelected[intCartType]">
    </form>
  </section>
</template>

<script>
import $ from 'jquery';
import Cookies from 'js-cookie';
import EventBus from '@jsBasePath/EventBus';
import bootbox from '@jsLibsPath/bootbox-custom';
import urijs from 'urijs';
import CartBlockProductTr from './CartBlockProductTr';
import { AJAX_URL, PAGE_INFO, MY_DEFINE } from '@jsBasePath/MyDefine';
import { CART_ENUM } from '@jsBasePath/CartUtil';
import { formatVnPrice } from '@jsBasePath/PriceUtil';
import { getOrderPageUrl } from '@jsBasePath/UrlUtil';
import { CartPageStore } from './cart-page-store';
import { isEmpty } from 'lodash';

const objUriPage = new urijs(location.search);
const objQueryString = objUriPage.search(true);

export default {
  filters: {
    formatVnPrice,
  },//end filters

  components: {
    CartBlockProductTr,
  },//end components

  data() {
    return {
      CART_ENUM,
      storeState: CartPageStore.state,
      intCartType: CART_ENUM.INT_TYPE_DDP,
      arrCartData: PAGE_INFO.arrCartInfo ? PAGE_INFO.arrCartInfo.cart_data : {},
      arrTotalCartData: PAGE_INFO.arrCartInfo ? PAGE_INFO.arrCartInfo.total_cart_data : {},
      arrCartDataChange: {},
      isRedirectingToOrderPage: false,
      arrFavoriteProductData: PAGE_INFO.arrCartInfo ? PAGE_INFO.arrCartInfo.favorite_product_data : {},
      objTotalRows: {},
      intCurrentCartEmpty: false,
      objItemSelected: {
        0: [],
        1: [],
      },
      objTabActive: {
        0: true,
        1: false,
      },
    };
  },//end data

  computed: {
    emptyCart() {
      return (!this.arrCartData[CART_ENUM.INT_TYPE_DDP] && !this.arrCartData[CART_ENUM.INT_TYPE_CIF]) ||
        this.intCurrentCartEmpty;
    },//end emptyCart

    intTotalQuantity() {
      const intTotalQuantityDDP = this.arrTotalCartData[CART_ENUM.INT_TYPE_DDP] ?
        parseInt(this.arrTotalCartData[CART_ENUM.INT_TYPE_DDP]['totalQuantityInCart']) :
        0;
      const intTotalQuantityCIF = this.arrTotalCartData[CART_ENUM.INT_TYPE_CIF] ?
        parseInt(this.arrTotalCartData[CART_ENUM.INT_TYPE_CIF]['totalQuantityInCart']) :
        0;

      return intTotalQuantityDDP + intTotalQuantityCIF;
    },//end intTotalQuantity

    loadingImage() {
      return MY_DEFINE.loadingImage;
    },//end loadingImage

    getOrderPageUrl() {
      return getOrderPageUrl(this.intCartType);
    },

    arrOutOfStockEnterpriseProductCart() {
      if(
        PAGE_INFO.arrOutOfStockProductCart &&
        !isEmpty(PAGE_INFO.arrOutOfStockProductCart['isEnterprise'])
      ) {
        return PAGE_INFO.arrOutOfStockProductCart['isEnterprise'];
      }

      return null;
    },

    arrOutOfStockNoneEnterpriseProductCart() {
      if(
        PAGE_INFO.arrOutOfStockProductCart &&
        !isEmpty(PAGE_INFO.arrOutOfStockProductCart['noneEnterprise'])
      ) {
        return PAGE_INFO.arrOutOfStockProductCart['noneEnterprise'];
      }

      return null;
    },
  },//end computed

  mounted() {
    const self = this;
    self.setTabCartOpen();
    self.itemSelectAll();
    self.resetTable();

    $.ajax({
      type: 'POST',
      url: AJAX_URL.cart.checkCart,
      dataType: 'json',
      success(objResult) {
        if (!objResult.success) return;
        if (objResult.data.hasOwnProperty('cart_data') && objResult.data.cart_data) {
          self.arrCartData[CART_ENUM.INT_TYPE_DDP] = objResult.data.cart_data[CART_ENUM.INT_TYPE_DDP];
          self.arrCartData[CART_ENUM.INT_TYPE_CIF] = objResult.data.cart_data[CART_ENUM.INT_TYPE_CIF];
        }

        if (objResult.data.hasOwnProperty('cart_data_change') && objResult.data.cart_data_change) {
          self.arrCartDataChange = objResult.data.cart_data_change;
        }

        self.syncQuantityCart(objResult.data);
        self.resetTable();
      },
    });

    EventBus.on('SYNC_CART', (arrData) => {
      this.arrCartData = arrData['cart_data'];
      this.arrFavoriteProductData = arrData['favorite_product_data'];
      this.syncQuantityCart(arrData);
      this.resetTable();
    });
  },//end mounted

  methods: {
    isEmpty,

    changeTab(tab) {
      if (!this.storeState.allowChangeTab) {
        return;
      }

      this.resetTable();

      if (tab == CART_ENUM.INT_TYPE_CIF) {
        this.intCartType = CART_ENUM.INT_TYPE_CIF;
        this.objTabActive[CART_ENUM.INT_TYPE_CIF] = true;
        this.objTabActive[CART_ENUM.INT_TYPE_DDP] = false;
        this.arrCartData[CART_ENUM.INT_TYPE_CIF] ? this.intCurrentCartEmpty = false : this.intCurrentCartEmpty = true;
        Cookies.set('cart-tab-active', CART_ENUM.INT_TYPE_CIF, { expries: 1, });
      } else {
        this.intCartType = CART_ENUM.INT_TYPE_DDP;
        this.objTabActive[CART_ENUM.INT_TYPE_CIF] = false;
        this.objTabActive[CART_ENUM.INT_TYPE_DDP] = true;
        this.arrCartData[CART_ENUM.INT_TYPE_DDP] ? this.intCurrentCartEmpty = false : this.intCurrentCartEmpty = true;
        Cookies.set('cart-tab-active', CART_ENUM.INT_TYPE_DDP, { expries: 1, });
      }

      this.syncBuyLater();
    },//end changeTab

    setTabCartOpen() {
      let cartTabActive = Cookies.get('cart-tab-active');

      if(objQueryString.cartType !== undefined) {
        cartTabActive = objQueryString.cartType;
      }

      if (cartTabActive == CART_ENUM.INT_TYPE_DDP) {
        this.intCartType = CART_ENUM.INT_TYPE_DDP;
        this.objTabActive[CART_ENUM.INT_TYPE_CIF] = false;
        this.objTabActive[CART_ENUM.INT_TYPE_DDP] = true;
      } else if (cartTabActive == CART_ENUM.INT_TYPE_CIF) {
        this.intCartType = CART_ENUM.INT_TYPE_CIF;
        this.objTabActive[CART_ENUM.INT_TYPE_CIF] = true;
        this.objTabActive[CART_ENUM.INT_TYPE_DDP] = false;
      }

      this.$nextTick().then(this.syncBuyLater);
    },//end setTabCartOpen

    syncBuyLater() {
      EventBus.emit('SYNC_BUY_LATER', {
        'cart_tab_active': this.intCartType,
        'cart_data': this.arrCartData,
        'total_cart_data': this.arrTotalCartData,
        'favorite_product_data': this.arrFavoriteProductData,
      });
    },//end syncBuyLater

    getTotalCartQuantity(type) {
      if (this.arrTotalCartData) {
        if (typeof type == 'undefined') {
          return this.intTotalQuantity;
        } else {
          return this.arrTotalCartData[type] ?
            parseInt(this.arrTotalCartData[type]['totalQuantityInCart']) :
            0;
        }
      }

      return 0;
    },//end getTotalCartQuantity

    calculateTotalRows(language) {
      this.objTotalRows[language] = this.objTotalRows[language] ? this.objTotalRows[language] + 1 : 1;
      return this.objTotalRows[language];
    },//end calculateTotalRows

    updateCart(arrCart, $event) {
      try {
        this.arrCartData[this.intCartType][arrCart.lang][$event.keyCart] = $event.cart_data;
      } catch (error) {
        this.syncQuantityCart($event);
      } finally {
        this.syncQuantityCart($event);
      }

    },//end updateCart

    removeCart(arrCart, $event) {
      this.arrCartData = $event.cart_data;

      try {
        delete this.arrCartDataChange[this.intCartType][arrCart.lang][$event.keyCart];
      } catch (error) {
        this.syncQuantityCart($event);
      } finally {
        this.syncQuantityCart($event);
      }

    },//end removeCart

    moveCartToBuyLater(arrCart, $event) {
      this.arrCartData = $event.cart_data;
      this.arrFavoriteProductData = $event.favorite_product_data;

      try {
        delete this.arrCartDataChange[this.intCartType][arrCart.lang][$event.keyCart];
      } catch (error) {
        this.syncQuantityCart($event);
      } finally {
        this.syncQuantityCart($event);
      }

      this.syncBuyLater();
    },//end moveCartToBuyLater

    syncQuantityCart($event) {
      if ($event.hasOwnProperty('total_cart_data') && $event.total_cart_data) {
        this.arrTotalCartData = $event.total_cart_data;
        this.resetTable();
        this.arrCartData[this.intCartType] ? this.intCurrentCartEmpty = false : this.intCurrentCartEmpty = true;
        EventBus.emit('UPDATE_CART_QUANTITY', this.intTotalQuantity);
      }
    },//end syncQuantityCart

    resetTable() {
      this.objTotalRows = {};
    },//end resetTable

    getTextCartChange(arrCartChange) {
      if (arrCartChange.from == 'exists' && arrCartChange.to == 'removedByBanned') {
        return `
          Sản phẩm <b>${ arrCartChange.productName }</b> đã được xóa khỏi giỏ hàng vì hiện tại ${ MY_DEFINE.brandName } không hổ trợ nhập khẩu sản phẩm này. Mong quý khách thông cảm.
        `;
      }

      return false;
    },//end getTextCartChange

    itemSelectAll() {
      const arrCartData = this.arrCartData;
      if (!this.intCurrentCartEmpty) {
        for (let intType in arrCartData) {
          if (intType == CART_ENUM.INT_TYPE_DDP || intType == CART_ENUM.INT_TYPE_CIF) {
            for (let strLanguage in arrCartData[intType]) {
              for (let strKeyCart in arrCartData[intType][strLanguage]) {
                this.objItemSelected[intType].push(strKeyCart);
              }
            }
          }
        }
      }
    },//end itemSelectAll

    itemSelected($event) {
      if ($event.isSelected) {
        this.objItemSelected[this.intCartType].push($event.keyCart);
      } else {
        let index = this.objItemSelected[this.intCartType].indexOf($event.keyCart);
        if (index > -1) {
          this.objItemSelected[this.intCartType].splice(index, 1);
        }
      }

      this.resetTable();
    },//end itemSelected

    startOrder() {
      if (this.objItemSelected[this.intCartType].length <= 0) {
        return bootbox.alert('Quý khách vui lòng chọn sản phẩm trước khi tiến hành thanh toán.');
      }

      this.$refs.startOrderForm.submit();
      this.isRedirectingToOrderPage = true;
    },//end startOrder

    getDataCartChange(intCartType, language, keyCart) {
      try {
        return this.arrCartDataChange[intCartType][language][keyCart];
      } catch (error) {
        return false;
      }
    },//end getDataCartChange

    getCurrTabActive() {
      if(this.objTabActive[CART_ENUM.INT_TYPE_DDP]) {
        return CART_ENUM.INT_TYPE_DDP;
      }

      return CART_ENUM.INT_TYPE_CIF;
    },
  },//end methods
};
</script>

