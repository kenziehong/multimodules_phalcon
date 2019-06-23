<template>
  <tr class="cart-block__product-tr" :class="{'is-hide' : isHideRow}">
    <td v-if="intRow == 1" class="cart-block__web-td" :rowspan="`${objTotalRows[strLanguage]}`">
      <i :class="`svg -svg-flag-circle-${strLanguage} -svg-24px`"></i>
      <div v-if="reachLimitRow" class="cart-block__view-more-field" @click.prevent="viewMore">
        <button class="my-btn -btn-pill -btn-sm -btn-grd-bg">
          Hiển thị thêm <i class="far fa-angle-double-down margin--left-5px"></i>
        </button>
      </div>
    </td>

    <td class="cart-block__payment-td">
      <label class="check-control -has-only-icon cart-block__payment-check-control">
        <input
          type="checkbox"
          class="check-control__check-input"
          :checked="isChecked"
          @click="selectItem"
        />

        <div class="check-control__indicator">
          <div class="check-control__check-icon"></div>
        </div>
      </label>
    </td>

    <td class="cart-block__product-info-td">
      <div class="cart-block__product-panel">
        <div class="cart-block__product-img-col">
          <a :href="productUrl"><img class="cart-block__product-img lazyload" :src="lazyLoadImage" :data-src="initialCart.image" alt="" target="_blank" /></a>
        </div>

        <div class="cart-block__product-info-col">
          <div class="cart-block__product-title">
            <a :href="productUrl" target="_blank">{{ htmlEntityDecode(initialCart.productName) }}</a>
          </div>

          <div v-if="initialCart.asin" class="cart-block__product-code-field">
            Mã sản phẩm: <span class="text-primary">{{ initialCart.asin }}</span>
          </div>

          <div v-if="merchantName" class="cart-block__product-merchant-field">
            Người bán: <a class="text-primary" target="_blank" :href="initialCart.merchantUrl">{{ merchantName }}</a>

            <span v-if="strProductCondition" class="cart-block__product-condition">
              Tình trạng sản phẩm: <span class="text-primary">{{ strProductCondition }}</span>
            </span>
          </div>

          <div v-if="productDescription" class="cart-block__product-desc-field" v-html="productDescription" />

          <div
            v-if="isShowAlertProductChangePrice"
            class="my-alert -alert-warning margin--top-5px -alert-sm margin--bottom-0"
          >
            <template v-if="arrCartChange.from != 'exists' && arrCartChange.to != 'removedByBanned'">
              Sản phẩm đã thay đổi đơn giá từ <b v-html="formatVnPriceWithUnit(arrCartChange.from)"></b> sang <b v-html="formatVnPriceWithUnit(arrCartChange.to)"></b>
            </template>
          </div>
        </div>
      </div>
    </td>

    <td v-if="arrShippingProviderList" class="cart-block__delivery-method-td">
      <select name="deliveryMethod" class="my-form-control -control-xs" @change="updateDeliveryMethod">
        <template v-for="(arrShippingProviderItem,index) in arrShippingProviderList">
          <option v-if="index == 0" :key="index" :selected="arrShippingProviderItem['shippingId'] == initialCart.shippingProviderId" :value="arrShippingProviderItem['shippingId']">{{ arrShippingProviderItem['shippingName'] }}</option>
        </template>
      </select>
    </td>

    <td class="cart-block__product-price-td">
      <div class="cart-block__product-curr-price">{{ initialCart.newPriceVND | formatVnPrice }} <sup>đ</sup></div>
      <div v-if="initialCart.oldPriceVND && initialCart.shippingProviderId != 4" class="cart-block__product-old-price">{{ initialCart.oldPriceVND | formatVnPrice }} <sup>đ</sup></div>
    </td>

    <td class="cart-block__quantity-td">
      <div class="my-quantity-control">
        <div class="my-quantity-control__btn -minus-btn" :class="{'is-disabled' : initialCart.quantity == 1}" @click.prevent="updateQuantity">-</div>
        <input :value="initialCart.quantity" type="number" class="my-quantity-control__input" min="1" max="99" @input="inputQuantity" />
        <div class="my-quantity-control__btn -plus-btn" @click.prevent="updateQuantity">+</div>
      </div>
    </td>

    <td class="cart-block__price-td">{{ initialCart.totalNewPriceVND | formatVnPrice }} <sup>đ</sup></td>

    <td class="cart-block__control-td">
      <CartBlockControlBtnFavorite
        :page="'cart-page'"
        :asin="initialCart.asin"
        :product-name="initialCart.productName"
        :product-image="initialCart.image"
        :lang="strLanguage"
        :merchant-id="merchantId"
        :product-url="initialCart.url"
        :note="initialCart.itemNote"
        :is-store="initialCart.isStore"
        :initial-favorite-id="favoritedId"
      />

      <button
        class="cart-block__control-btn"
        @click="moveCartToBuyLater"
      >
        <span class="cart-block__control-btn__icon"><i class="fal fa-cart-arrow-down"></i></span> Mua sau
      </button>

      <button
        class="cart-block__control-btn"
        :data-product_id="initialCart.asin"
        :data-product_sku="initialCart.asin"
        :data-merchant-id="merchantId"
        :data-product_price="initialCart.newPriceVND"
        :data-product_name="initialCart.productName"
        :data-product_quantity="initialCart.quantity"
        :data-product_brand="strBrand"
        :data-product_category="strCategory"
        :data-product_variation="strVariation"
        @click="removeItem"
      >
        <span class="cart-block__control-btn__icon"><i class="fal fa-trash-alt"></i></span> Xóa
      </button>
    </td>
  </tr>
</template>

<script>
import $ from 'jquery';
import bootbox from '@jsLibsPath/bootbox-custom';
import he from 'he';
import EventBus from '@jsBasePath/EventBus';
import { formatVnPrice, formatVnPriceWithUnit } from '@jsBasePath/PriceUtil';
import { MyNoty } from '@jsBasePath/MyNoty';
import { CartPageStore } from './cart-page-store';
import { isEmpty as _isEmpty } from 'lodash';
import { MY_DEFINE, AJAX_URL, APPLICATION_ENV, PAGE_INFO } from '@jsBasePath/MyDefine';
import { CART_ENUM } from '@jsBasePath/CartUtil';
import CartBlockControlBtnFavorite from './CartBlockControlBtnFavorite';
import Gtm from '@jsBasePath/Gtm';
import { getArrVariationFromProductSkuList } from '@jsBasePath/GtmUtil';

export default {
  components: {
    CartBlockControlBtnFavorite,
  },//end components

  filters: {
    formatVnPrice,
  },//end filters

  props: {
    initialCart: {
      type: Object,
      default: null,
    },
    intCartType: {
      type: Number,
      default: CART_ENUM.INT_TYPE_DDP,
    },
    strLanguage: {
      type: String,
      default: 'gb',
    },
    intRow: {
      type: Number,
      default: 0,
    },
    objTotalRows: {
      type: Object,
      default: null,
    },
    arrFavoriteProductData: {
      type: Array,
      default: null,
    },
    objItemSelected: {
      type: Object,
      default: null,
    },
    strKeyCart: {
      type: String,
      default: null,
    },
    arrCartChange: {
      type: [Object, Array, Boolean],
      default: null,
    },
  },

  data() {
    return {
      storeState: CartPageStore.state,
      forceShowRow: false,
      APPLICATION_ENV,
      arrShippingProviderList: PAGE_INFO.arrShippingProviderList,
    };
  },//end data

  computed: {
    lazyLoadImage() {
      return MY_DEFINE.lazyloadImage;
    },//end lazyLoadImage

    productUrl() {
      return this.initialCart.url;
    },//end productUrl

    isHideRow() {
      return this.intRow > 3 && !this.forceShowRow;
    },//end isHideRow

    reachLimitRow() {
      return this.objTotalRows[this.strLanguage] > 3 && this.intRow === 1 && !this.forceShowRow;
    },//end reachLimitRow

    favoritedId() {
      let favoritedId = 0;
      this.arrFavoriteProductData.forEach((arrData) => {
        if(arrData.asin_code == this.initialCart.asin) {
          favoritedId = arrData.favourite_id;
        }
      });

      return parseInt(favoritedId);
    },//end favoritedId

    merchantId() {
      let merchantId = '';

      if(
        this.initialCart.quoteMerchant &&
        this.initialCart.quoteMerchant.merchantID
      ) {
        merchantId = this.initialCart.quoteMerchant.merchantID;
      }

      return merchantId;
    },//end merchantId

    merchantName() {
      let merchantName = '';

      try {
        merchantName = this.initialCart.quoteMerchant.merchantName;
      } catch (error) {
        merchantName = '';
      }

      return merchantName;
    },//end merchantName

    itemNoteFormatted() {
      let itemNote = '';

      if(this.initialCart.itemNote) {
        itemNote = this.initialCart.itemNote;
        itemNote = this.initialCart.itemNote.replace(',', '|');
      }

      return itemNote;
    },//end itemNoteFormatted

    productDescription() {
      let itemNote = '';
      if(this.initialCart.itemNote) {
        this.initialCart.itemNote.split(',').forEach((value) => {
          let [lbl, val] = value.split(':');
          if(val) {
            itemNote += `
              <div class="cart-block__product-desc-item">
                ${ lbl }: <span class="text-primary">${ val }</span>
              </div>
            `;
          }
        });
      }

      return itemNote;
    },//end productDescription

    isChecked() {
      return this.objItemSelected[this.intCartType].indexOf(this.strKeyCart) > -1;
    },//end isChecked

    // Kiểm tra hiển thị thông báo thay đổi thông tin giá sản phẩm
    isShowAlertProductChangePrice() {
      if(this.arrCartChange && this.arrCartChange.from != 'exists' && this.arrCartChange.to != 'removedByBanned') {
        return true;
      }

      return false;
    },

    // Tình trạng sản phẩm
    strProductCondition() {
      if(
        this.initialCart &&
        this.initialCart.quoteMerchant &&
        this.initialCart.quoteMerchant.condition &&
        this.initialCart.quoteMerchant.condition.condition &&
        this.initialCart.quoteMerchant.condition.condition != 'New'
      ) {
        return this.initialCart.quoteMerchant.condition.condition;
      }

      return null;
    },

    strBrand() {
      let strBrand = '';

      if (this.initialCart
        && this.initialCart.brand
        && this.initialCart.brand.name) {
        strBrand = this.initialCart.brand.name.trim();
      }

      return strBrand;
    },

    strCategory() {
      let strCategory = '';

      if (this.initialCart
        && this.initialCart.categoryInfo
        && this.initialCart.categoryInfo.browseNode
        && this.initialCart.categoryInfo.browseNode.length > 0) {
        let intLength = this.initialCart.categoryInfo.browseNode.length;
        if (this.initialCart.categoryInfo.browseNode[intLength - 1]['categoryName']) {
          strCategory = this.initialCart.categoryInfo.browseNode[intLength - 1]['categoryName'].trim();
        }
      }

      return strCategory;
    },

    strVariation() {
      let strVariation = '';

      let arrVariation = [];

      if (this.initialCart
        && this.initialCart.productSkuList) {
        arrVariation = getArrVariationFromProductSkuList(this.initialCart.productSkuList);
      }

      strVariation = arrVariation.join('|');

      return strVariation;
    },
  },//end computed

  created() {
    EventBus.on(`FORCE_SHOW_ROW_${ this.strLanguage }`, () => {
      this.forceShowRow = true;
    });

    EventBus.on(`FORCE_HIDE_ROW_${ this.strLanguage }`, () => {
      this.forceShowRow = false;
    });
  },//end mounted

  methods: {
    formatVnPriceWithUnit,
    htmlEntityDecode(htmlContent) {
      if(!_isEmpty(htmlContent)) {
        return he.decode(htmlContent);
      }

      return htmlContent;
    },

    viewMore() {
      EventBus.emit(`FORCE_SHOW_ROW_${ this.strLanguage }`);
    },//end viewMore

    formatVnPrice(price) {
      return formatVnPrice(price);
    },//end formatVnPrice

    inputQuantity(e) {
      setTimeout(this.updateQuantity, 1500, e);
    },//end inputQuantity

    updateQuantity(e) {
      const self = this;
      const $parentTr = $(e.target).parents('tr');

      let intQuantity = 0;
      if(e.target.classList.contains('-plus-btn')) {
        intQuantity = parseInt(this.initialCart.quantity) + 1;
      }else if(e.target.classList.contains('-minus-btn')) {
        intQuantity = parseInt(this.initialCart.quantity) - 1;
      }else{
        intQuantity = parseInt(e.target.value);
      }

      intQuantity = intQuantity < 1 ? this.initialCart.quantity : intQuantity;

      $.ajax({
        type: 'POST',
        url: AJAX_URL.cart.updateCart,
        data: {
          quantity: intQuantity,
          enterprise: self.initialCart.isEnterprise,
          lang: self.initialCart.lang,
          isStore: self.initialCart.isStore,
          isAVN: self.initialCart.isAVN,
          keyCart: self.strKeyCart,
          shippingProviderId: self.initialCart.shippingProviderId,
        },
        dataType: 'json',
        beforeSend() {
          self.storeState.allowChangeTab = false;
          $parentTr.addClass('is-loading');
        },

        success(objResult) {
          if(!objResult.success) {
            if (objResult.message) {
              return bootbox.alert(objResult.message);
            }
            return MyNoty.alert('Đã có lỗi xảy ra, vui lòng thử lại', 'danger').show();
          }

          self.$emit('update-cart', {
            cart_data: objResult.data.cart_data[self.intCartType][self.initialCart.lang][self.strKeyCart],
            keyCart: self.strKeyCart,
            total_cart_data: objResult.data.total_cart_data,
          });

          self.syncViewMore();
        },

        complete() {
          self.storeState.allowChangeTab = true;
          $parentTr.removeClass('is-loading');
        },
      });
    },// end updateQuantity

    updateDeliveryMethod(e) {
      const self = this;
      const $elParentTr = $(e.target).parents('tr');

      $.ajax({
        type: 'POST',
        url: AJAX_URL.cart.updateCart,
        data: {
          quantity: self.initialCart.quantity,
          enterprise: self.initialCart.isEnterprise,
          lang: self.initialCart.lang,
          isStore: self.initialCart.isStore,
          isAVN: self.initialCart.isAVN,
          keyCart: self.strKeyCart,
          shippingProviderId: e.target.value,
        },
        dataType: 'json',
        beforeSend() {
          self.storeState.allowChangeTab = false;
          $elParentTr.addClass('is-loading');
        },

        success(objResult) {
          if(!objResult.success) {
            if (objResult.message) {
              return bootbox.alert(objResult.message);
            }
            return MyNoty.alert('Đã có lỗi xảy ra, vui lòng thử lại', 'danger').show();
          }

          self.$emit('update-cart', {
            cart_data: objResult.data.cart_data[self.intCartType][self.initialCart.lang][self.strKeyCart],
            keyCart: self.strKeyCart,
            total_cart_data: objResult.data.total_cart_data,
          });

          self.syncViewMore();
        },

        complete() {
          self.storeState.allowChangeTab = true;
          $elParentTr.removeClass('is-loading');
        },
      });
    },

    removeItem(e) {
      const self = this;
      const target = e.target;
      const $target = $(target);
      const $parentTr = $target.parents('tr');

      bootbox.confirm('Quý khách có muốn xóa sản phẩm này ra khỏi giỏ hàng không?', function(isConfirm) {
        if(isConfirm) {
          $.ajax({
            type: 'POST',
            url: AJAX_URL.cart.removeCart,
            data: {
              enterprise: self.initialCart.isEnterprise,
              lang: self.initialCart.lang,
              isStore: self.initialCart.isStore,
              isAVN: self.initialCart.isAVN,
              keyCart: self.strKeyCart,
            },
            dataType: 'json',
            beforeSend() {
              self.storeState.allowChangeTab = false;
              $parentTr.addClass('is-loading');
            },

            success(objResult) {
              if(!objResult.success) {
                return MyNoty.alert('Đã có lỗi xảy ra, vui lòng thử lại', 'danger').show();
              }

              $parentTr.remove();
              MyNoty.alert(`<div>Xoá sản phẩm<br /><span class="text-success">${ self.initialCart.productName }</span> <br />khỏi giỏ hàng thành công.</div>`, 'success').show();
              self.$emit('remove-cart', {
                cart_data: objResult.data.cart_data,
                keyCart: self.strKeyCart,
                total_cart_data: objResult.data.total_cart_data,
              });

              try {
                Gtm.removeFromCart({
                  'id': self.initialCart.asin,
                  'name': self.initialCart.productName,
                  'price': self.initialCart.newPriceVND,
                  'quantity': self.initialCart.quantity,
                  'brand': self.strBrand,
                  'category': self.strCategory,
                  'variant': self.strVariation,
                });
              } catch (e) {}
            },

            complete() {
              self.storeState.allowChangeTab = true;
              $parentTr.removeClass('is-loading');
            },
          });
        }
      });
    }, // end removeItem

    moveCartToBuyLater(e) {
      const self = this;
      const $parentTr = $(e.target).parents('tr');

      $.ajax({
        type: 'POST',
        url: AJAX_URL.cart.cartToBuyLater,
        data: {
          enterprise: self.initialCart.isEnterprise,
          lang: self.initialCart.lang,
          isStore: self.initialCart.isStore,
          isAVN: self.initialCart.isAVN,
          keyCart: self.strKeyCart,
        },
        dataType: 'json',
        beforeSend() {
          self.storeState.allowChangeTab = false;
          $parentTr.addClass('is-loading');
        },

        success(objResult) {
          if(!objResult.success || !objResult.data.cart_data) {
            return MyNoty.alert('Đã có lỗi xảy ra, vui lòng thử lại', 'danger').show();
          }

          MyNoty.alert(`<div>Thêm sản phẩm<br /><span class="text-success">${ self.initialCart.productName }</span> <br />vào danh sách mua sau thành công.</div>`, 'success').show();
          self.$emit('move-cart-to-buy-later', {
            cart_data: objResult.data.cart_data,
            total_cart_data: objResult.data.total_cart_data,
            favorite_product_data: objResult.data.favorite_product_data,
            keyCart: self.strKeyCart,
          });
          self.syncViewMore();
        },

        complete() {
          self.storeState.allowChangeTab = true;
          $parentTr.removeClass('is-loading');
        },
      });
    },// end moveCartToBuyLater

    syncViewMore() {
      if(this.forceShowRow == true) {
        EventBus.emit(`FORCE_SHOW_ROW_${ this.strLanguage }`);
      }else {
        EventBus.emit(`FORCE_HIDE_ROW_${ this.strLanguage }`);
      }
    },//end syncViewMore

    selectItem() {
      this.$emit('item-selected', {
        keyCart: this.strKeyCart,
        isSelected: !this.isChecked,
      });
    },//end selectItem
  },//end methods
};
</script>

