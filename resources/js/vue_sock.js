//Add a description to the data object with the value "A pair of warm, fuzzy socks". 
//Then display the description using an expression in an p element, underneath the h1.

var app = new Vue({
    el: '#app',
    data: {
      product: 'Socks',
      image: '../resources/image/sock-green.jpg',
      inStock: true,
      inventory: 8,
    } 
  })