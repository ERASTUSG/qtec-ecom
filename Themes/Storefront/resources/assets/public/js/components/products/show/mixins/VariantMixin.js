import md5 from "blueimp-md5";

export default {
    methods: {
        doesVariantExist(uid) {
            return this.product.variants.some(({ uids }) => uids.includes(uid));
        },

        setOldMediaLength() {
            this.oldMediaLength = this.hasAnyMedia ? this.item.media.length : 1;
        },

        setVariant() {
            const selectedUids = Object.values(this.cartItemForm.variations)
                .sort()
                .join(".");

            const variant = this.product.variants.find(
                (variant) => variant.uids === selectedUids
            );

            if (variant !== undefined) {
                this.item = { ...variant };

                this.reduceToMaxQuantity();

                return;
            }

            // Set empty variant data if variant does not exist
            const uid = md5(
                Object.values(this.cartItemForm.variations).sort().join(".")
            );

            this.item = {
                uid,
                media: [],
            };
        },

        setVariantSlug() {
            const url = route("products.show", {
                slug: this.product.slug,
                variant: this.item.uid,
            });

            window.history.replaceState({}, "", url);
        },

        updateVariantDetails() {
            this.setOldMediaLength();
            this.setVariant();
            this.setVariantSlug();
            this.updateGallerySlider();
        },
    },
};
