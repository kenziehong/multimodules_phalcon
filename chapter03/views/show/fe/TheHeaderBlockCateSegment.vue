<template>
  <div v-if="dataHeaderCate" class="header-block__cate-segment">
    <ul class="header-block__cate-list">
      <li
        v-for="(cateItemLv1, idxLv1) in dataHeaderCate[currCountryCodeHover].categoryChildren"
        :key="idxLv1"
        class="header-block__cate-list-item"
        :class="{'is-active': currCateListHover[currCountryCodeHover] == idxLv1 }"
        @mouseover="onHoverCateList(idxLv1)"
      >
        <a :href="cateItemLv1.url">
          <span class="header-block__cate-list-item-icon-cell">
            <template v-if="cateItemLv1.iconClassName">
              <i :class="cateItemLv1.iconClassName"></i>
            </template>
            <template v-else>
              <i class="fd fd-cate-all"></i>
            </template>
          </span>
          {{ htmlEntityDecode(cateItemLv1.title) }}
        </a>
      </li>

      <li v-if="currCountryCodeHover == COUNTRY_ENUM.STR_US_CODE" class="header-block__cate-list-item">
        <a href="/xem-tat-ca-danh-muc">
          <span class="header-block__cate-list-item-icon-cell"><i class="fd fd-cate-all"></i></span>
          Xem tất cả
        </a>
      </li>
    </ul>

    <div class="header-block__cate-panel-col">
      <div
        v-for="(cateItemLv2,idxLv2) in dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].categoryChildren"
        :key="idxLv2"
        class="header-block__cate-panel"
      >
        <a
          class="header-block__cate-panel-img-link"
          :href="cateItemLv2.url"
        >
          <img :src="cateItemLv2.banner" alt="" />
        </a>

        <div class="header-block__cate-panel-title">
          <a :href="cateItemLv2.url">{{ htmlEntityDecode(cateItemLv2.title) }}</a>
        </div>

        <ul class="header-block__sub-cate-list">
          <li
            v-for="(cateItemLv3,idxLv3) in cateItemLv2.categoryChildren"
            :key="idxLv3"
          >
            <a :href="cateItemLv3.url">{{ htmlEntityDecode(cateItemLv3.title) }}</a>
          </li>
        </ul>
      </div><!-- .header-block__cate-panel -->
    </div><!-- .header-block__cate-panel-col -->

    <div
      v-if="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[0]"
      class="header-block__cate-bner-col"
    >
      <a :href="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[0].url">
        <img :src="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[0].banner" alt="" />
      </a>
    </div><!-- .header-block__cate-bner-col -->

    <div class="header-block__cate-bner-col-2">
      <a
        v-if="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[1]"
        class="margin--bottom-10px"
        :href="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[1].url"
      >
        <img
          :src="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[1].banner"
          alt=""
        />
      </a>

      <a
        v-if="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[2]"
        :href="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[2].url"
      >
        <img
          :src="dataHeaderCate[currCountryCodeHover].categoryChildren[currCateListHover[currCountryCodeHover]].bannerChildren[2].banner"
          alt=""
        />
      </a>
    </div>
  </div><!-- .header-block__cate-segment -->
</template>

<script>
import $ from 'jquery';
import he from 'he';
import EventBus from '@jsBasePath/EventBus';
import { AJAX_URL, APPLICATION_VER } from '@jsBasePath/MyDefine';
import { COUNTRY_ENUM } from '@jsBasePath/CountryUtil';
import { isEmpty } from 'lodash';

export default {
  data() {
    return {
      dataHeaderCate: null,
      currCountryCodeHover: COUNTRY_ENUM.STR_US_CODE,
      applicationVer: APPLICATION_VER,
      currCateListHover: { // Kiểm tra xem đang hover danh mục con nào của countryCode
        us: 0,
        de: 0,
        jp: 0,
      },
      COUNTRY_ENUM,
    };
  },

  created() {
    let self = this;

    let _dataHeaderCate = localStorage.getItem('HEADER_CATE');
    if(!isEmpty(_dataHeaderCate)) {
      _dataHeaderCate = JSON.parse(_dataHeaderCate);

      if (_dataHeaderCate.applicationVer && _dataHeaderCate.applicationVer == APPLICATION_VER) {
        self.dataHeaderCate = _dataHeaderCate;
        setTimeout(() => {
          EventBus.emit('GET_HEADER_CATE', true);
        }, 300);
        return;
      }
    }

    $.ajax({
      type: 'GET',
      url: AJAX_URL.category.getCategoryList,
      dataType: 'json',
      success(dataResponse) {
        if (
          !dataResponse.success &&
          isEmpty(dataResponse.data)
        ) {
          return;
        }

        let dataHeaderCate = {};
        $.each(dataResponse.data,(idx, objCountryCate) => {
          dataHeaderCate[objCountryCate.countryCode] = objCountryCate;
        });

        self.dataHeaderCate = dataHeaderCate;

        localStorage.setItem('HEADER_CATE', JSON.stringify({
          ...dataHeaderCate,
          applicationVer: APPLICATION_VER,
        }));

        EventBus.emit('GET_HEADER_CATE', true);
      },
    });
  },// created()

  methods: {
    htmlEntityDecode(htmlContent) {
      if(!isEmpty(htmlContent)) {
        return he.decode(htmlContent);
      }

      return htmlContent;
    },

    onHoverCateList(idx) {
      this.currCateListHover[this.currCountryCodeHover] = idx;
      return;
    },
  },// methods
};
</script>

