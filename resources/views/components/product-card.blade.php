@props(['product'])
<style>
    .product-card-pink {
        position: relative;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(252, 231, 243, 0.5));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 18px;
        border: 2px solid rgba(236, 72, 153, 0.15);
        box-shadow:
            0 10px 30px rgba(236, 72, 153, 0.12),
            0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        cursor: pointer;
    }

    .product-card-pink:hover {
        transform: translateY(-8px);
        box-shadow:
            0 20px 50px rgba(236, 72, 153, 0.2),
            0 10px 25px rgba(236, 72, 153, 0.15);
        border-color: rgba(236, 72, 153, 0.3);
        background: linear-gradient(135deg, rgba(255, 255, 255, 1), rgba(252, 231, 243, 0.7));
    }

    .product-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .product-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 16px 16px 0 0;
        transition: transform 0.3s ease;
    }

    .product-card-pink:hover .product-card-image {
        transform: scale(1.05);
    }

    .product-card-badge {
        position: absolute;
        top: 0.875rem;
        left: 0.875rem;
        z-index: 10;
        background: linear-gradient(135deg, #ec4899, #f472b6);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow:
            0 6px 20px rgba(236, 72, 153, 0.4),
            0 3px 10px rgba(236, 72, 153, 0.3);
        border: 2px solid rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .product-card-badge i {
        font-size: 0.625rem;
    }

    .product-card-content {
        padding: 1.25rem;
        background: transparent;
    }

    .product-card-name {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.4;
        margin-bottom: 0.625rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }

    .product-card-pink:hover .product-card-name {
        color: #ec4899;
    }

    .product-card-price {
        font-size: 1.25rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ec4899, #db2777);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.2rem;
    }

    .original-price {
        font-size: 0.875rem;
        color: var(--gray-400);
        text-decoration: line-through;
        margin-left: 0.5rem;
        font-weight: 500;
        -webkit-text-fill-color: var(--gray-400);
        /* Reset gradient for strikethrough */
    }

    .sale-badge {
        position: absolute;
        top: 0.875rem;
        right: 0.875rem;
        z-index: 10;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .product-card-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.625rem;
        margin-top: 0.875rem;
    }

    .product-card-detail-link {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-600);
        text-decoration: none;
        transition: all 0.3s ease;
        pointer-events: none;
        opacity: 0.7;
    }

    .product-card-add-btn {
        background: linear-gradient(135deg, #ec4899, #f472b6);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        position: relative;
        z-index: 10;
    }

    .product-card-add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        background: linear-gradient(135deg, #db2777, #ec4899);
    }

    .product-card-wishlist {
        margin-top: 0.625rem;
        text-align: right;
    }

    .product-card-wishlist-btn {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #ec4899;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        position: relative;
        z-index: 10;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .product-card-wishlist-btn:hover {
        color: #db2777;
        text-decoration: underline;
    }

    .product-card-wishlist-btn i {
        font-size: 0.75rem;
    }

    .product-card-image-container {
        position: relative;
        cursor: pointer;
        overflow: hidden;
        border-radius: 16px 16px 0 0;
    }

    .product-card-actions,
    .product-card-wishlist {
        position: relative;
        z-index: 10;
    }

    .product-card-placeholder {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(252, 231, 243, 0.4), rgba(255, 255, 255, 0.3));
        border-radius: 16px 16px 0 0;
    }

    .product-card-placeholder i {
        font-size: 3rem;
        color: rgba(236, 72, 153, 0.3);
    }
</style>

<a href="{{ route('products.show', $product->slug) }}" class="product-card-link">
    <div class="product-card-pink">
        @if($product->is_best_seller)
            <div class="product-card-badge">
                <i class="fas fa-crown"></i> Best Seller
            </div>
        @endif
        @if($product->has_active_discount)
            <div class="sale-badge">
                <i class="fas fa-tag"></i> DISKON {{ $product->discount_percentage }}%
            </div>
        @endif
        <div class="product-card-image-container w-full">
            @if($product->image_path)
                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="product-card-image">
            @else
                <div class="product-card-placeholder">
                    <i class="fas fa-image"></i>
                </div>
            @endif
        </div>
        <div class="product-card-content">
            <h3 class="product-card-name">{{ $product->name }}</h3>
            <div class="product-card-price">
                <span>Rp {{ number_format($product->current_price, 0, ',', '.') }}</span>
                @if($product->has_active_discount)
                    <span class="original-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>
            <div class="product-card-actions" onclick="event.stopPropagation();">
                <span class="product-card-detail-link">Detail</span>
                <form action="{{ route('cart.add', $product) }}" method="post" onclick="event.stopPropagation();">
                    @csrf
                    <button type="submit" class="product-card-add-btn">
                        <i class="fas fa-shopping-cart"></i> Tambah
                    </button>
                </form>
            </div>
            <div class="product-card-wishlist" onclick="event.stopPropagation();">
                <form action="{{ route('wishlist.toggle', $product) }}" method="post"
                    onclick="event.stopPropagation();">
                    @csrf
                    <button type="submit" class="product-card-wishlist-btn">
                        @auth
                            @if($product->isWishlistedBy(auth()->user()))
                                <i class="fas fa-heart"></i>
                            @else
                                <i class="far fa-heart"></i>
                            @endif
                        @else
                            <i class="far fa-heart"></i>
                        @endauth
                        Wishlist
                    </button>
                </form>
            </div>
        </div>
    </div>
</a>