import store from "../../store";
import Errors from "../../Errors";
import GalleryMixin from "./show/mixins/GalleryMixin";
import VariationMixin from "./show/mixins/VariationMixin";
import VariantMixin from "./show/mixins/VariantMixin";
import OptionMixin from "./show/mixins/OptionMixin";
import QuantityMixin from "./show/mixins/QuantityMixin";
import ReviewMixin from "./show/mixins/ReviewMixin";
import ProductCardMixin from "../../mixins/ProductCardMixin";

export default {
    components: {
        VPagination: () => import("../VPagination.vue"),
        RelatedProducts: () => import("./show/RelatedProducts.vue"),
    },

    mixins: [
        GalleryMixin,
        VariationMixin,
        VariantMixin,
        OptionMixin,
        QuantityMixin,
        ReviewMixin,
        ProductCardMixin,
    ],

    props: ["product", "reviewCount", "avgRating"],

    data() {
        return {
            item: {
                ...(this.product.variant ? this.product.variant : this.product),
            },
            oldMediaLength: 0,
            activeVariationValues: {},
            variationImagePath: null,
            price: this.product.formatted_price,
            activeTab: "description",
            currentReviewPage: 1,
            fetchingReviews: false,
            reviews: {},
            addingNewReview: false,
            reviewForm: {},
            cartItemForm: {
                product_id: this.product.id,
                qty: 1,
                variations: {},
                options: {},
            },
            errors: new Errors(),
        };
    },

    computed: {
        productUrl() {
            return route("products.show", {
                slug: this.product.slug,
                ...(this.hasAnyVariant && {
                    variant: this.item.uid,
                }),
            });
        },

        isActiveItem() {
            return this.item.is_active === true;
        },

        isAddToCartDisabled() {
            return this.item.is_out_of_stock ?? true;
        },
    },

    methods: {
        isMobileDevice() {
            return window.matchMedia("only screen and (max-width: 991px)")
                .matches;
        },

        addToCart() {
            if (this.isAddToCartDisabled) return;

            this.addingToCart = true;

            axios
                .post(route("cart.items.store"), {
                    ...this.cartItemForm,
                    ...(this.hasAnyVariant && { variant_id: this.item.id }),
                })
                .then((response) => {
                    store.updateCart(response.data);

                    $(".header-cart").trigger("click");
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors.record(response.data.errors);

                        return;
                    }

                    this.$notify(response.data.message);
                })
                .finally(() => {
                    this.addingToCart = false;
                });
        },

        initUpSellProductsSlider() {
            $(this.$refs.upSellProducts).slick({
                rows: 0,
                dots: false,
                arrows: true,
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                rtl: window.FleetCart.rtl,
            });
        },
    },
};
