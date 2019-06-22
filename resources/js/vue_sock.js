Vue.component('product',{
    props: {
        premium: {
            type: Boolean,
            requried: true,
        }
    },
    template: `
        <div class="product">
            <div class="product-image">
                <img :src="image" />
            </div>

            <div class="product-info">
                <h1>{{ title }}</h1>
                <p v-show="inStock">In Stock</p>
                <p v-if="inventory<=10 && inventory>0">Almost sold out!</p>
                <p v-else>Out of Stock</p>
                <p>User is premium: {{ premium }}</p>
                <p>Shipping: {{ shipping }}</p>

                <ul>
                    <li v-for="detail in details">{{ detail }}</li>
                </ul>

                <div v-for="(variant, index) in variants" 
                    :key="variant.variantId"
                    class="color-box"
                    :style="{backgroundColor: variant.variantColor}"
                    @mouseover="updateProduct(index)"
                    >
                </div>

                <button v-on:click="addToCart" 
                        :disable="!inStock"
                        :class="{disabledButton: !inStock}">Add to Cart
                </button>
                
            </div>
        </div>
    `,
    data() {
        return {
            brand: "Vue Mastery",  
            product: 'Socks',
            selectedVariant: 0,
            inventory: 8,
            details: ["80% cotton", "20% polyester", "Gender-neutral"],
            variants: [
                {
                    variantId: 2234,
                    variantColor: "green",
                    variantImage: '../resources/image/sock-green.jpg',
                    variantQuantity: 10,
                },
                {
                    variantId: 2235,
                    variantColor: "blue",
                    variantImage: '../resources/image/sock-blue.jpg',
                    variantQuantity: 0,
                },
            ],
            activeClass: "active",
            errorClass: "text-danger",
            classObject: {
                active: true,
                "text-danger": false,
            },
            isActive: true,
        }
    },
    methods: {
        addToCart: function () {
            this.$emit('add-to-cart', this.variants[this.selectedVariant].variantId)
        },
        updateProduct: function (index) {
            this.selectedVariant = index
        }
    },
    computed: {
        title() {
            return this.brand + ' ' + this.product
        },
        image() {
            return this.variants[this.selectedVariant].variantImage
        },
        inStock() {
            return this.variants[this.selectedVariant].variantQuantity
        },
        shipping() {
            if (this.premium) {
                return "Free"
            }
            return 2.99
        }
    } 
})

//Add a description to the data object with the value "A pair of warm, fuzzy socks". 
//Then display the description using an expression in an p element, underneath the h1.

var app = new Vue({
    el: '#app',
    data: {
        premium: true,
        cart: [],
    },
    methods: {
        updateCart(id) {
            this.cart.push(id)
        }
    }
  })