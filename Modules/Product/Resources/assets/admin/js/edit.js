import Vue from "vue";
import ProductMixin from "./mixins/ProductMixin";
import Errors from "@admin/js/Errors";

Vue.prototype.route = route;

new Vue({
    el: "#app",

    mixins: [ProductMixin],

    data: {
        formSubmissionType: null,
        form: {
            brand_id: "",
            categories: [],
            tags: [],
            media: [],
            name: "lorem ipsum",
            description: "<p>lorem ipsum dolor</p>",
            is_active: 1,
            price: 999,
            tax_class_id: "",
            is_virtual: 0,
            manage_stock: 0,
            in_stock: 1,
            special_price_type: "fixed",
            meta: {},
            variations: [],
            variants: [],
            attributes: [],
            options: [],
            downloads: [],
        },
        errors: new Errors(),
        selectizeConfig: {
            plugins: ["remove_button"],
        },
        searchableSelectizeConfig: {},
        categoriesSelectizeConfig: {
            plugins: ["remove_button"],
            onItemAdd(value) {
                this.getItem(value)[0].innerHTML = this.getItem(
                    value
                )[0].innerHTML.replace(/¦––\s/g, "");
            },
        },
        flatPickrConfig: {
            mode: "single",
            enableTime: true,
            altInput: true,
        },
    },

    created() {
        this.setFormData();
        this.setSearchableSelectizeConfig();
        this.setDefaultVariantUid();
        this.setVariantsLength();
    },

    methods: {
        prepareFormData(formData) {
            this.prepareAttributes(formData.attributes);
            this.prepareVariations(formData.variations);
            this.prepareVariants(formData.variants);
            this.prepareOptions(formData.options);

            return formData;
        },

        setFormData() {
            this.form = { ...this.prepareFormData(FleetCart.data["product"]) };
        },

        setDefaultVariantUid() {
            if (this.hasAnyVariant) {
                this.defaultVariantUid = this.form.variants.find(
                    ({ is_default }) => is_default === true
                ).uid;
            }
        },

        setVariantsLength() {
            this.variantsLength = this.form.variants.length;
        },

        submit({ submissionType }) {
            this.formSubmissionType = submissionType;

            $.ajax({
                type: "PUT",
                url: route("admin.products.update", {
                    id: this.form.id,
                    ...(submissionType === "save_and_exit" && {
                        exit_flash: true,
                    }),
                }),
                data: this.transformData(this.form),
                dataType: "json",
                success: (response) => {
                    if (submissionType === "save_and_exit") {
                        location.href = route("admin.products.index");

                        return;
                    }

                    this.form = { ...response.product_resource };

                    this.errors.reset();
                    this.prepareFormData(this.form);
                    this.resetBulkEditVariantFields();

                    toaster(response.message, {
                        type: "success",
                    });
                },
            })
                .catch((error) => {
                    this.formSubmissionType = null;

                    toaster(error.responseJSON.message, {
                        type: "default",
                    });

                    if (error.status === 422) {
                        this.errors.reset();
                        this.errors.record(error.responseJSON.errors);
                        this.scrollToFirstErrorField(this.$refs.form.elements);

                        return;
                    }

                    if (this.hasAnyVariant) {
                        this.regenerateVariationsAndVariantsUid();
                    }
                })
                .always(() => {
                    if (submissionType === "save") {
                        this.formSubmissionType = null;
                    }
                });
        },
    },
});
