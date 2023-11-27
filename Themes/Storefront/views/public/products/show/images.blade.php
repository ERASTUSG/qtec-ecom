<div class="product-gallery position-relative align-self-start">
    <div
        class="product-gallery-preview-wrap position-relative overflow-hidden"
        :class="{ 'visible-variation-image': hasAnyVariationImage }"
    >
        <img v-cloak v-if="hasAnyVariationImage" :src="variationImagePath" class="variation-image" alt="{{ $product->name }}">

        <div class="product-gallery-preview">
            @if ($item->media->isNotEmpty())
                @foreach ($item->media as $media)
                    <div class="gallery-preview-slide">
                        <div class="gallery-preview-item">
                            <img src="{{ $media->path }}" data-zoom="{{ $media->path }}" alt="{{ $product->name }}">

                            <a v-cloak href="{{ $media->path }}" data-gallery="product-gallery-preview" class="gallery-view-icon glightbox">
                                <i class="las la-search-plus"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="gallery-preview-slide">
                    <div class="gallery-preview-item">
                        <img src="{{ asset('build/assets/image-placeholder.png') }}" data-zoom="{{ asset('build/assets/image-placeholder.png') }}" alt="{{ $product->name }}" class="image-placeholder">

                        <a v-cloak href="{{ asset('build/assets/image-placeholder.png') }}" data-gallery="product-gallery-preview" class="gallery-view-icon glightbox">
                            <i class="las la-search-plus"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="product-gallery-thumbnail" v-cloak>
        @if ($item->media->isNotEmpty())
            @foreach ($item->media as $media)
                <div class="gallery-thumbnail-slide">
                    <div class="gallery-thumbnail-item">
                        <img src="{{ $media->path }}" alt="{{ $product->name }}">
                    </div>
                </div>
            @endforeach
        @else
            <div class="gallery-thumbnail-slide">
                <div class="gallery-thumbnail-item">
                    <img src="{{ asset('build/assets/image-placeholder.png') }}" alt="{{ $product->name }}" class="image-placeholder">
                </div>
            </div>
        @endif
    </div>
</div>
