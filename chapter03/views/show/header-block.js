import $ from 'jquery';
import Cookies from 'js-cookie';
import Headroom from '@jsLibsPath/headroom';
import Vue from 'vue';
import hoverintent from '@jsLibsPath/hoverintent.min';
import EventBus from '@jsBasePath/EventBus';
import bootbox from '@jsLibsPath/bootbox-custom';
import { OBJ_RULE_VALIDATE } from '@jsBasePath/MyJqueryValidation';
import TheHeaderBlockCateSegment from '@vueGlobalPath/TheHeaderBlockCateSegment';
import TheHeaderBlockSearchForm from '@vueGlobalPath/TheHeaderBlockSearchForm';
import TheHeaderBlockCartSegment from '@vueGlobalPath/TheHeaderBlockCartSegment';
import { AJAX_URL, PAGE_INFO } from '@jsBasePath/MyDefine';
import { COUNTRY_ENUM } from '@jsBasePath/CountryUtil';
import { getSearchOrderPageUrl } from '@jsBasePath/UrlUtil';
import { getCartPageUrl } from '@jsBasePath/UrlUtil';
import Language from '@jsBasePath/Language';

export default (() => {
  const _elHeaderBlock = document.getElementById('header-block');

  const _bindStickyHeaderBlock = () => {
    if (!document.body.classList.contains('home-page')) {
      return;
    }

    new Headroom(document.getElementById('header-block'), {
      offset: 190,
      tolerance: {
        up: 1000,
        down: 1000,
      },
    }).init();
  }; // end _bindStickyHeaderBlock()

  const _processTransSegment = () => {
    let elTransSegment = document.getElementById('header-block__trans-segment');
    let ckGgTranslateActived = Cookies.get('GG_TRANSLATE_ACTIVED');
    let isGgTranslateLoaded = false;

    window.addOnloadEvent(() => {
      if (ckGgTranslateActived) {
        setTimeout(() => {
          $.getScript('//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit',
            () => {
              document.getElementById('gg-translate__loading').remove();
              isGgTranslateLoaded = true;
            });
        }, 1000);
      }
    });

    document.getElementById('header-block__trans-segment__head').addEventListener('click', () => {
      if (elTransSegment.classList.contains('is-uncollapse')) {
        elTransSegment.classList.remove('is-uncollapse');
        return;
      }

      // Load gooogle translate selectbox
      if (!ckGgTranslateActived && !isGgTranslateLoaded) {
        $.getScript('//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit',
          () => {
            document.getElementById('gg-translate__loading').remove();
            Cookies.set('GG_TRANSLATE_ACTIVED', true, { expires: 0.1, });
            isGgTranslateLoaded = true;
          });
      }

      elTransSegment.classList.add('is-uncollapse');
    });

    elTransSegment.addEventListener('click', (e) => {
      e.stopPropagation();
    });

    // Hide dropdown is showing...
    window.addEventListener('click', () => {
      if (elTransSegment.classList.contains('is-uncollapse')) {
        elTransSegment.classList.remove('is-uncollapse');
      }
    });
  }; // end _processTransSegment()

  const _processCateSegment = () => {
    let vTheHeaderBlockCateSegment = null;

    vTheHeaderBlockCateSegment = new Vue({
      render: (h) => h(TheHeaderBlockCateSegment),
    }).$mount('#header-block__cate-segment');

    //Show/hide cate segment
    EventBus.once('GET_HEADER_CATE', () => {
      let elWebCol = document.getElementById('header-block__web-col');
      let elWebSegmentUs = document.getElementById('header-block__web-segment--us');
      let elWebSegmentJp = document.getElementById('header-block__web-segment--jp');
      let elWebSegmentDe = document.getElementById('header-block__web-segment--de');
      let elWebSegmentUk = document.getElementById('header-block__web-segment--uk');

      elWebCol.addEventListener('mouseleave', () => {
        elWebCol.classList.remove('is-uncollapse');
        elWebSegmentUs.classList.remove('is-hover');
        elWebSegmentJp.classList.remove('is-hover');
        elWebSegmentDe.classList.remove('is-hover');
      });

      hoverintent(elWebSegmentUs,
        () => {
          elWebSegmentJp.classList.remove('is-hover');
          elWebSegmentDe.classList.remove('is-hover');
          vTheHeaderBlockCateSegment.$children[0].currCountryCodeHover = COUNTRY_ENUM.STR_US_CODE;
          elWebCol.classList.add('is-uncollapse');
          elWebSegmentUs.classList.add('is-hover');
        },
        () => {});

      hoverintent(elWebSegmentJp,
        () => {
          elWebSegmentUs.classList.remove('is-hover');
          elWebSegmentDe.classList.remove('is-hover');
          vTheHeaderBlockCateSegment.$children[0].currCountryCodeHover = COUNTRY_ENUM.STR_JP_CODE;
          elWebCol.classList.add('is-uncollapse');
          elWebSegmentJp.classList.add('is-hover');
        },
        () => {});

      hoverintent(elWebSegmentDe,
        () => {
          elWebSegmentUs.classList.remove('is-hover');
          elWebSegmentJp.classList.remove('is-hover');
          vTheHeaderBlockCateSegment.$children[0].currCountryCodeHover = COUNTRY_ENUM.STR_DE_CODE;
          elWebCol.classList.add('is-uncollapse');
          elWebSegmentDe.classList.add('is-hover');
        },
        () => {});

      hoverintent(elWebSegmentUk,
        () => {
          elWebSegmentUs.classList.remove('is-hover');
          elWebSegmentJp.classList.remove('is-hover');
          elWebSegmentDe.classList.remove('is-hover');
          elWebCol.classList.remove('is-uncollapse');
        },
        () => {});
    });
  }; // end _processCateSegment()

  const _bindToggleOrderSegment = () => {
    let elOrderSegment = document.getElementById('header-block__order-segment');
    let elOrderSegmentHead = document.getElementById('header-block__order-segment__head');

    // Bind Toggle
    (() => {
      elOrderSegmentHead.addEventListener('click', () => {
        if (elOrderSegment.classList.contains('is-uncollapse')) {
          elOrderSegment.classList.remove('is-uncollapse');
          return;
        }

        elOrderSegment.classList.add('is-uncollapse');
      });

      elOrderSegment.addEventListener('click', (e) => {
        e.stopPropagation();
      });

      // Hide dropdown is showing...
      window.addEventListener('click', () => {
        if (elOrderSegment.classList.contains('is-uncollapse')) {
          elOrderSegment.classList.remove('is-uncollapse');
        }
      });
    })();
  }; // end _bindToggleOrderSegment()

  const _processSearchOrderForm = () => {
    let $searchOrderForm = $('#header-block__search-order-form');
    let $alertHtml = null;

    $searchOrderForm.validate({
      onkeyup: false,
      rules: {
        orderID: {
          required: true,
        },
        phone: OBJ_RULE_VALIDATE.phone,
      },
      submitHandler($form) {
        $form = $($form);

        $.ajax({
          type: 'GET',
          url: AJAX_URL.order.searchOrder,
          data: $form.serialize(),
          dataType: 'json',
          beforeSend() {
            if ($alertHtml && $alertHtml.length) {
              $alertHtml.remove();
            }

            $form.addClass('is-loading');
            $form.blur();
          },

          error() {
            $alertHtml = `<div class="my-alert -alert-danger -alert-sm">${ Language.getGlobal('error_connection') }</div>`;

            $alertHtml = $($alertHtml);
            $form.prepend($alertHtml);
          },

          success(objResponse) {
            $alertHtml = null;

            if (!objResponse.success) {
              $alertHtml = '<div class="my-alert -alert-danger -alert-sm">';
              $alertHtml += '<b>' + Language.getGlobal('error_one_or_more_occurred') + ':</b><br/>';
              $.each(objResponse.error, (idx, errorItem) => {
                $alertHtml += `<div>- ${ errorItem }</div>`;
              });
              $alertHtml += '</div>';

              $alertHtml = $($alertHtml);
              $form.prepend($alertHtml);
              return;
            }

            $alertHtml = `<div class="my-alert -alert-success -alert-sm">${ Language.getGlobal('text_header_track_order_block_order_found') }</div>`;

            $alertHtml = $($alertHtml);
            $form.prepend($alertHtml);

            let redirectUrl = getSearchOrderPageUrl({
              orderId: $form.find('#header-block__search-order-form__order-id-input').val(),
              phone: $form.find('#header-block__search-order-form__phone-input').val(),
            });

            setTimeout(() => {
              window.location.href = redirectUrl;
            }, 100);
          },

          complete() {
            $form.removeClass('is-loading');
          },
        }); // end ajax()
      }, // end submitHandler()
    }); // end validate()
  }; // end _processSearchOrderForm()

  const _showLoginError = () => {
    if (PAGE_INFO.arrLoginError && PAGE_INFO.arrLoginError.length > 0) {
      let strLoginError;
      if (PAGE_INFO.arrLoginError.length === 1) {
        strLoginError = PAGE_INFO.arrLoginError[0];
      } else {
        strLoginError = PAGE_INFO.arrLoginError.join('<br>');
      }
      bootbox.alert(strLoginError);
    }
  };

  const _renderSearchForm = () => {
    new Vue({
      render: (h) => h(TheHeaderBlockSearchForm),
    }).$mount('#header-block__search-form');
  };

  const _renderCartSegment = () => {
    new Vue({
      render: (h) => h(TheHeaderBlockCartSegment),
    }).$mount('#header-block__cart-segment');
  };

  const _backToTop = (completeScrollCb) => {
    $('html, body').animate({
        scrollTop: 0,
      },
      '300',
      'swing',
      completeScrollCb);
  };

  /**
   * Chữ cái đầu sau dấu _ đối với 1 class sẽ là
   */
  const _AddToCartSegment = (() => {
    let __isAppendToDom = false;
    let __timerShowBlock = null;

    const __blockHtml = `
      <section id="header-block__alert-add-cart-segment" class="header-block__alert-add-cart-segment">
        <div class="header-block__alert-add-cart-segment__inner">
          <div class="header-block__alert-add-cart-segment__close-btn"><i class="fal fa-times"></i></div>

          <div class="header-block__alert-add-cart-segment__desc">
            <i class="fas fa-check-circle text-success"></i> ${ Language.getGlobal('text_header_cart_block_add_to_cart_success') }
          </div>

          <a class="my-btn -btn-grd-bg -btn-sm -btn-pill" href="${ getCartPageUrl() }">${ Language.getGlobal('text_header_cart_block_go_to_cart_page') }</a>
        </div>
      </section>
    `;
    const __$blockHtml = $(__blockHtml);

    const __appendToDom = () => {
      if (__isAppendToDom) {
        __$blockHtml.show();
        return;
      }

      __isAppendToDom = true;
      $(_elHeaderBlock).append(__$blockHtml);

      __$blockHtml.on('click', '.header-block__alert-add-cart-segment__close-btn', () => {
        __$blockHtml.hide();
      });
    };

    const __showAlertAddToCart = (msToHide = 4000) => {
      __appendToDom();

      __timerShowBlock = setTimeout(() => {
        __$blockHtml.hide();
      }, msToHide);
    };

    const __backToTopAddToCart = () => {
      _backToTop(__showAlertAddToCart);
    };

    return {
      backToTopShowAddToCartSegment: __backToTopAddToCart,
      showAddToCartSegment: __showAlertAddToCart,
      hideAddToCartSegment() {
        if (!__isAppendToDom) {
          return;
        }

        if (__timerShowBlock) {
          clearTimeout(__timerShowBlock);
        }

        __$blockHtml.hide();
      },
    };
  })(); // end _AddToCartSegment class

  return {
    init() {
      _bindStickyHeaderBlock();
      _processTransSegment();
      _processCateSegment();
      _renderSearchForm();
      _bindToggleOrderSegment();
      _processSearchOrderForm();
      _renderCartSegment();
      _showLoginError();
    },

    backToTopShowAddToCartSegment: _AddToCartSegment.backToTopShowAddToCartSegment,
    showAddToCartSegment: _AddToCartSegment.showAddToCartSegment,
    hideAddToCartSegment: _AddToCartSegment.hideAddToCartSegment,
  };
})();
