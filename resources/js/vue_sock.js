//Add a description to the data object with the value "A pair of warm, fuzzy socks". 
//Then display the description using an expression in an p element, underneath the h1.

var app = new Vue({
    el: '#app',
    data: {
      product: 'Socks',
      image: '../resources/image/sock-green.jpg',
      inStock: true,
      inventory: 8,
      details: ["80% cotton", "20% polyester", "Gender-neutral"],
      variants: [
          {
            variantId: 2234,
            variantColor: "green",
            variantImage: '../resources/image/sock-green.jpg',
          },
          {
            variantId: 2235,
            variantColor: "blue",
            variantImage: '../resources/image/sock-blue.jpg',
          },
      ],
      cart: 0,
      activeClass: "active",
      errorClass: "text-danger",
      classObject: {
          active: true,
          "text-danger": false,
      },
      isActive: true,
    },
    methods: {
        addToCart: function () {
            this.cart +=1 
        },
        updateProduct: function (variantImage) {
            this.image = variantImage
        }
    } 
  })