import Drift from "drift-zoom";
import GLightbox from "glightbox";

let galleryPreviewSlider;
let galleryThumbnailSlider;
let galleryPreviewLightbox;

export default {
    mounted() {
        galleryPreviewSlider = this.initGalleryPreviewSlider();
        galleryThumbnailSlider = this.initGalleryThumbnailSlider();
        galleryPreviewLightbox = this.initGalleryPreviewLightbox();
        this.initGalleryPreviewZoom();
        this.initUpSellProductsSlider();
    },

    methods: {
        initGalleryPreviewSlider() {
            return $(".product-gallery-preview").slick({
                rows: 0,
                speed: 200,
                fade: true,
                dots: false,
                swipe: false,
                arrows: false,
                infinite: false,
                draggable: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                rtl: window.FleetCart.rtl,
            });
        },

        initGalleryThumbnailSlider() {
            return $(".product-gallery-thumbnail")
                .on("setPosition", (_, slick) => {
                    if (slick.slideCount <= slick.options.slidesToShow) {
                        slick.$slideTrack.css("transform", "");
                    }
                })
                .slick({
                    rows: 0,
                    dots: false,
                    arrows: true,
                    infinite: false,
                    slidesToShow: 6,
                    slideToScroll: 1,
                    focusOnSelect: true,
                    rtl: window.FleetCart.rtl,
                    asNavFor: $(".product-gallery-preview"),
                    responsive: [
                        {
                            breakpoint: 1601,
                            settings: {
                                slidesToShow: 5,
                            },
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 6,
                            },
                        },
                        {
                            breakpoint: 577,
                            settings: {
                                slidesToShow: 5,
                            },
                        },
                        {
                            breakpoint: 451,
                            settings: {
                                slidesToShow: 4,
                            },
                        },
                    ],
                });
        },

        updateGallerySlider() {
            if (this.hasAnyMedia) {
                this.addNewGallerySlides();
                this.removeOldGallerySlides();
            } else {
                // Add empty placeholder slide if variant has no media
                this.addGalleryEmptySlide();
                this.removeOldGallerySlides();
            }

            this.addGalleryEventListeners();
        },

        addNewGallerySlides() {
            this.item.media.forEach(({ path }, index) => {
                this.addGalleryPreviewSlide(path, index);
                this.addGalleryThumbnailSlide(path, index);
            });
        },

        addGalleryPreviewSlide(filePath, slideIndex) {
            galleryPreviewSlider.slick(
                "slickAdd",
                this.galleryPreviewSlideTemplate(filePath),
                slideIndex,
                true
            );
        },

        addGalleryThumbnailSlide(filePath, slideIndex) {
            galleryThumbnailSlider.slick(
                "slickAdd",
                this.galleryThumbnailSlideTemplate(filePath),
                slideIndex,
                true
            );
        },

        addGalleryEmptySlide() {
            const filePath = `${FleetCart.baseUrl}/build/assets/image-placeholder.png`;

            galleryPreviewSlider.slick(
                "slickAdd",
                this.galleryPreviewEmptySlideTemplate(filePath),
                null,
                true
            );

            galleryThumbnailSlider.slick(
                "slickAdd",
                this.galleryThumbnailEmptySlideTemplate(filePath),
                null,
                true
            );
        },

        removeOldGallerySlides() {
            const slideCount =
                galleryPreviewSlider.slick("getSlick").slideCount - 1;

            [...Array(this.oldMediaLength)].forEach((_, index) => {
                const slideIndex = slideCount - index;

                galleryPreviewSlider.slick("slickRemove", slideIndex);
                galleryThumbnailSlider.slick("slickRemove", slideIndex);
            });
        },

        addGalleryEventListeners() {
            this.$nextTick(() => {
                galleryThumbnailSlider.slick("refresh");
                galleryPreviewLightbox.reload();
                this.initGalleryPreviewZoom();
            });
        },

        initGalleryPreviewZoom() {
            if (this.isMobileDevice()) {
                this.initGalleryPreviewMobileZoom();

                return;
            }

            this.initGalleryPreviewDesktopZoom();
        },

        initGalleryPreviewMobileZoom() {
            [
                ...document.querySelectorAll(".gallery-preview-item > img"),
            ].forEach((el) => {
                new Drift(el, {
                    namespace: "mobile-drift",
                    inlinePane: true,
                });
            });
        },

        initGalleryPreviewDesktopZoom() {
            [
                ...document.querySelectorAll(".gallery-preview-item > img"),
            ].forEach((el) => {
                new Drift(el, {
                    inlinePane: false,
                    hoverBoundingBox: true,
                    boundingBoxContainer: document.body,
                    paneContainer: document.querySelector(".product-gallery"),
                });
            });
        },

        initGalleryPreviewLightbox() {
            return GLightbox({
                zoomable: true,
                preload: false,
            });
        },

        galleryPreviewSlideTemplate(filePath) {
            return `
                <div class="gallery-preview-slide">
                    <div class="gallery-preview-item">
                        <img src="${filePath}" data-zoom="${filePath}" alt="${this.product.name}">
                        
                        <a href="${filePath}" data-gallery="product-gallery-preview" class="gallery-view-icon glightbox">
                            <i class="las la-search-plus"></i>
                        </a>
                    </div>
                </div>
            `;
        },

        galleryThumbnailSlideTemplate(filePath) {
            return `
                <div class="gallery-thumbnail-slide">
                    <div class="gallery-thumbnail-item">
                        <img src="${filePath}" alt="${this.product.name}">
                    </div>
                </div>
            `;
        },

        galleryPreviewEmptySlideTemplate(filePath) {
            return `
                <div class="gallery-preview-slide">
                    <div class="gallery-preview-item">
                        <img src="${filePath}" data-zoom="${filePath}" alt="${this.product.name}" class="image-placeholder">
                        
                        <a href="${filePath}" data-gallery="product-gallery-preview" class="gallery-view-icon glightbox">
                            <i class="las la-search-plus"></i>
                        </a>
                    </div>
                </div>
            `;
        },

        galleryThumbnailEmptySlideTemplate(filePath) {
            return `
                <div class="gallery-thumbnail-slide">
                    <div class="gallery-thumbnail-item">
                        <img src="${filePath}" alt="${this.product.name}" class="image-placeholder">
                    </div>
                </div>
            `;
        },
    },
};
