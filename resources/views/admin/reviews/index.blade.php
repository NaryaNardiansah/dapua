@extends('layouts.admin')

@section('content')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="luxury-reviews-page">
        <!-- Hero Section -->
        <x-admin-hero icon="fas fa-comment-dots" title="Manajemen Ulasan"
            subtitle="Kelola ulasan dan rating produk dari pelanggan"
            description="Pantau ulasan pelanggan untuk menjaga kualitas layanan" :showCircle="true" />

        <!-- Status Alert -->
        @if(session('status'))
            <div class="status-alert" data-aos="fade-down">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span class="alert-text">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Modern Filter Section -->
        <div class="modern-filter-section" data-aos="fade-up">
            <form method="get" class="modern-filter-form">
                <div class="filter-row">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <select name="product_id" class="modern-select" onchange="this.closest('form').submit()">
                            <option value="">Semua Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-actions">
                        <a href="{{ route('admin.reviews.index') }}" class="modern-btn secondary" title="Reset">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Reviews Table -->
        <x-admin-content-card title="Daftar Ulasan" icon="fas fa-comments">
            <div class="table-container">
                <table class="luxury-table">
                    <thead>
                        <tr>
                            <th class="table-header-cell">Pelanggan</th>
                            <th class="table-header-cell">Produk</th>
                            <th class="table-header-cell">Rating</th>
                            <th class="table-header-cell">Komentar</th>
                            <th class="table-header-cell">Tanggal</th>
                            <th class="table-header-cell text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr class="table-row">
                                <td class="table-cell">
                                    <div class="user-info">
                                        <div class="user-name">{{ $review->user->name }}</div>
                                        <div class="user-email text-xs text-gray-500">{{ $review->user->email }}</div>
                                    </div>
                                </td>
                                <td class="table-cell">
                                    <div class="product-name font-semibold">{{ $review->product->name }}</div>
                                </td>
                                <td class="table-cell">
                                    <div class="rating-display">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"
                                                style="color: {{ $i <= $review->rating ? '#fbbf24' : '#d1d5db' }}; font-size: 0.75rem;"></i>
                                        @endfor
                                        <span class="ml-1 text-xs font-bold">{{ $review->rating }}.0</span>
                                    </div>
                                </td>
                                <td class="table-cell">
                                    <div class="review-comment text-sm italic">
                                        "{{ Str::limit($review->comment, 100) }}"
                                    </div>
                                </td>
                                <td class="table-cell">
                                    <span class="text-xs text-gray-500">
                                        {{ $review->created_at->format('d M Y, H:i') }}
                                    </span>
                                </td>
                                <td class="table-cell text-center">
                                    <div class="action-buttons flex justify-center gap-2">
                                        <form id="delete-review-{{ $review->id }}"
                                            action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                            class="no-loading" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="action-btn delete" title="Hapus Ulasan"
                                            onclick="deleteReview({{ $review->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500">
                                    <i class="far fa-comment-alt text-4xl mb-4 opacity-20 block"></i>
                                    Belum ada ulasan yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
                <div class="pagination-container mt-6">
                    {{ $reviews->appends(request()->query())->links() }}
                </div>
            @endif
        </x-admin-content-card>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteReview(reviewId) {
            Swal.fire({
                title: 'Hapus Ulasan?',
                text: "Ulasan ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ec4899',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                borderRadius: '16px',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-review-' + reviewId);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }

        // Show success message if exists
        @if(session('status'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('status') }}",
                icon: 'success',
                confirmButtonColor: '#ec4899',
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    </script>

    <style>
        .luxury-reviews-page {
            padding-bottom: 3rem;
        }

        .luxury-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header-cell {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .table-row {
            border-bottom: 1px solid var(--gray-100);
            transition: all 0.2s ease;
        }

        .inline-form {
            display: inline-block;
            margin: 0;
            padding: 0;
            line-height: normal;
        }

        .table-row:hover {
            background: var(--light-pink);
        }

        .table-cell {
            padding: 1rem;
            vertical-align: middle;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .action-btn.delete:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .modern-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            border-radius: 10px;
            outline: none;
            cursor: pointer;
        }

        .modern-select:focus {
            border-color: var(--primary-pink);
        }
    </style>
@endsection