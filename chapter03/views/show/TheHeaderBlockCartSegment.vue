<template>
  <div id="header-block__cart-segment" class="header-block__cart-segment">
    <a class="header-block__cart-link" :href="strViewMoreUrl">
      <i class="svg -svg-cart-gray -svg-32px"></i>
      <span class="header-block__cart-number">{{ intProductQuantity }}</span>
    </a>
  </div>
</template>

<script>
import { getCartPageUrl } from '@jsBasePath/UrlUtil';
import EventBus from '@jsBasePath/EventBus';

export default {
  data() {
    return {
      intProductQuantity: window.fado.totalCartQuantity,
      strViewMoreUrl: getCartPageUrl(),
    };
  },
  mounted() {
    const self = this;

    EventBus.on('UPDATE_CART_QUANTITY', function(value) {
      self.intProductQuantity = value;
    });
  },
  methods: {
  },
};
</script>

