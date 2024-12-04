<!--**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *-->
<template>
  <button
  class="wishlist-button-add"
  :class="{
    'wishlist-button-product': isProduct,
    'likedp': isChecked
  }"
  @click="addToWishlist"
>
    <div class="heart-container" title="Like">
      <div class="svg-container">
        <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
          <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z"></path>
        </svg>
        <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
          <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z"></path>
        </svg>
        <svg class="svg-celebrate" xmlns="http://www.w3.org/2000/svg" width="100" height="100">
          <polygon points="10,10 20,20"></polygon>
          <polygon points="10,50 20,50"></polygon>
          <polygon points="20,80 30,70"></polygon>
          <polygon points="90,10 80,20"></polygon>
          <polygon points="90,50 80,50"></polygon>
          <polygon points="80,80 70,70"></polygon>
        </svg>
      </div>
    </div>
  </button>
</template>

<script>
  import removeFromList from '@graphqlFiles/mutations/removeFromList';
  import prestashop from 'prestashop';
  import EventBus from '@components/EventBus';

  export default {
    name: 'Button',
    props: {
      url: {
        type: String,
        required: true,
        default: '#',
      },
      productId: {
        type: Number,
        required: true,
        default: null,
      },
      productAttributeId: {
        type: Number,
        required: true,
        default: null,
      },
      checked: {
        type: Boolean,
        required: false,
        default: false,
      },
      isProduct: {
        type: Boolean,
        required: false,
        default: false,
      },
    },
    data() {
      return {
        isChecked: this.checked === 'true',
        idList: this.listId,
        idProductAttribute: this.productAttributeId,
      };
    },
    methods: {
      /**
       * Toggle the heart on this component, basically if the heart is filled,
       * then this product is inside a wishlist, else it's not in a wishlist
       */
      toggleCheck() {
        this.isChecked = !this.isChecked;
      },
      /**
       * If the product isn't in a wishlist, then open the "AddToWishlist" component modal,
       * if he's in a wishlist, then launch a removeFromList mutation to remote the product from a wishlist
       */
      async addToWishlist(event) {
        event.preventDefault();
        const quantity = document.querySelector(
          '.product-quantity input#quantity_wanted',
        );

        if (!prestashop.customer.is_logged) {
          EventBus.$emit('showLogin');

          return;
        }

        if (!this.isChecked) {
          EventBus.$emit('showAddToWishList', {
            detail: {
              productId: this.productId,
              productAttributeId: parseInt(this.idProductAttribute, 10),
              forceOpen: true,
              quantity: quantity ? parseInt(quantity.value, 10) : 0,
            },
          });
        } else {
          const {data} = await this.$apollo.mutate({
            mutation: removeFromList,
            variables: {
              productId: this.productId,
              url: this.url,
              productAttributeId: this.idProductAttribute,
              listId: this.idList ? this.idList : this.listId,
            },
          });

          const {removeFromList: response} = data;

          EventBus.$emit('showToast', {
            detail: {
              type: response.success ? 'success' : 'error',
              message: response.message,
            },
          });

          if (!response.error) {
            this.toggleCheck();
          }
        }
      },
    },
    mounted() {
      /**
       * Register to event addedToWishlist to toggle the heart if the product has been added correctly
       */
      EventBus.$on('addedToWishlist', (event) => {
        if (
          event.detail.productId === this.productId
          && parseInt(event.detail.productAttributeId, 10) === this.idProductAttribute
        ) {
          this.isChecked = true;
          this.idList = event.detail.listId;
        }
      });

      // eslint-disable-next-line
      const items = productsAlreadyTagged.filter(
        (e) => parseInt(e.id_product, 10) === this.productId
          && parseInt(e.id_product_attribute, 10) === this.idProductAttribute,
      );

      if (items.length > 0) {
        this.isChecked = true;
        this.idList = parseInt(items[0].id_wishlist, 10);
      }

      if (this.isProduct) {
        prestashop.on('updateProduct', ({eventType}) => {
          if (eventType === 'updatedProductQuantity') {
            this.isChecked = false;
          }
        });

        prestashop.on('updatedProduct', (args) => {
          const quantity = document.querySelector(
            '.product-quantity input#quantity_wanted',
          );

          this.idProductAttribute = parseInt(args.id_product_attribute, 10);

          // eslint-disable-next-line
          const itemsFiltered = productsAlreadyTagged.filter(
            (e) => parseInt(e.id_product, 10) === this.productId
              && e.quantity.toString() === quantity.value
              && parseInt(e.id_product_attribute, 10) === this.idProductAttribute,
          );

          if (itemsFiltered.length > 0) {
            this.isChecked = true;
            this.idList = parseInt(itemsFiltered[0].id_wishlist, 10);
          } else {
            this.isChecked = false;
          }
        });
      }
    },
  };
</script>

<style lang="scss" type="text/scss">
  .wishlist {
    &-button {
      &-product {
        margin-left: 1.25rem;
      }

      &-add {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 2.5rem;
        width: 2.5rem;
        min-width: 2.5rem;
        padding-top: 0.1875rem;
        border-radius: 50%;
        cursor: pointer;
        transition: 0.2s ease-out;
        border: none;
      }
    }
  }

  /* Container styling */
  .heart-container {
    --heart-color: rgb(255, 64, 129);
    display: inline-flex;
    width: 38px;
    height: 38px;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
  }

  .wishlist-button-add:hover .heart-container {
    transform: scale(1.1);
  }

  /* SVG container */
  .svg-container {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* Outline heart */
  .svg-outline {
    fill: black;
    transition: fill 0.3s;
  }

  /* Filled heart */
  .svg-filled {
    position: absolute;
    fill: var(--heart-color);
    transform: scale(0);
    animation: keyframes-svg-filled 0.5s forwards;
    display: none;
  }

  .wishlist-button-add.likedp .svg-celebrate,
  .wishlist-button-add.likedp .svg-filled
  {
    display: block;
  }

  /* Celebrate sparks */
  .svg-celebrate {
    position: absolute;
    stroke: var(--heart-color);
    fill: var(--heart-color);
    stroke-width: 2px;
    transform: scale(0);
    opacity: 0;
    animation: keyframes-svg-celebrate 0.5s forwards;
    display: none;
  }

  .wishlist-button-add:focus {
	outline: none !important;
  }

  /* Animations */
  @keyframes keyframes-svg-filled {
    0% {
      transform: scale(0);
    }
    50% {
      transform: scale(1.2);
    }
    100% {
      transform: scale(1);
    }
  }

  @keyframes keyframes-svg-celebrate {
    0% {
      transform: scale(0);
      opacity: 1;
    }
    50% {
      transform: scale(1.2);
      opacity: 1;
    }
    100% {
      transform: scale(1.4);
      opacity: 0;
    }
  }
</style>
