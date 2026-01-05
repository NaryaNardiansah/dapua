# 📋 USE CASE DOCUMENTATION - DAPUR SAKURA E-COMMERCE PLATFORM

## 🎯 OVERVIEW

**Nama Sistem**: Dapur Sakura E-commerce Platform  
**Deskripsi**: Platform e-commerce modern untuk kuliner Jepang dan lokal dengan sistem pengiriman canggih yang terintegrasi dengan tracking real-time, payment gateway, dan notifikasi otomatis.

**Tipe Sistem**: Web Application (Laravel 11)  
**Domain**: E-commerce & Food Delivery

---

## 👥 ACTORS (AKTOR)

### 1. **Customer (Pelanggan)**
- User yang membeli produk makanan
- Dapat melacak pesanan secara real-time
- Dapat memberikan feedback dan rating
- Dapat chat dengan driver

### 2. **Driver (Kurir)**
- User yang bertugas mengantar pesanan
- Dapat melihat dan mengambil order
- Update status pengiriman
- Upload bukti foto pengiriman
- Chat dengan customer
- Update lokasi real-time

### 3. **Admin (Administrator)**
- Mengelola seluruh sistem
- Manajemen produk, kategori, dan user
- Assign driver ke order
- Monitoring delivery dan analytics
- Kelola zona pengiriman
- Mengelola settings sistem

### 4. **System (Sistem)**
- Midtrans Payment Gateway
- SMS/WhatsApp Notification Service
- Weather API
- Social Login (Google, Facebook)

---

## 📊 USE CASE DIAGRAM

```
┌─────────────────────────────────────────────────────────────────┐
│                    DAPUR SAKURA E-COMMERCE                      │
└─────────────────────────────────────────────────────────────────┘

CUSTOMER                          DRIVER                    ADMIN
   │                                │                          │
   ├─ UC-001: Register/Login        ├─ UC-020: Login          ├─ UC-040: Login
   ├─ UC-002: Browse Products       ├─ UC-021: View Orders    ├─ UC-041: Dashboard
   ├─ UC-003: Search Products       ├─ UC-022: Accept Order   ├─ UC-042: Manage Products
   ├─ UC-004: View Product Detail   ├─ UC-023: Update Status  ├─ UC-043: Manage Categories
   ├─ UC-005: Add to Cart           ├─ UC-024: Upload Photo   ├─ UC-044: Manage Orders
   ├─ UC-006: Manage Cart           ├─ UC-025: Update GPS     ├─ UC-045: Manage Users
   ├─ UC-007: Checkout              ├─ UC-026: Chat Customer  ├─ UC-046: Manage Drivers
   ├─ UC-008: Select Delivery       ├─ UC-027: Complete       ├─ UC-047: Assign Driver
   ├─ UC-009: Payment               │          Delivery        ├─ UC-048: View Analytics
   ├─ UC-010: Track Order           │                          ├─ UC-049: Manage Zones
   ├─ UC-011: View Order History    │                          ├─ UC-050: Export Reports
   ├─ UC-012: Give Rating           │                          ├─ UC-051: Settings
   ├─ UC-013: Chat Driver           │                          └─ UC-052: Notifications
   ├─ UC-014: Cancel Order          │
   ├─ UC-015: Wishlist              │
   ├─ UC-016: Write Review          │
   └─ UC-017: Profile Management    │
```

---

## 📝 DETAILED USE CASES

---

## 🛒 **MODUL CUSTOMER (UC-001 hingga UC-019)**

---

### **UC-001: Register/Login**

**Aktor**: Customer, System (Google/Facebook OAuth)  
**Tujuan**: Customer dapat membuat akun atau login ke sistem  
**Prekondisi**: -  
**Postkondisi**: Customer berhasil login dan dapat mengakses fitur

**Main Flow**:
1. Customer mengakses halaman registrasi/login
2. System menampilkan form login dan opsi social login
3. Customer memilih metode:
   - **3a. Email/Password**:
     - Customer mengisi email dan password
     - System memvalidasi kredensial
     - Jika valid, redirect ke homepage
   - **3b. Google Login**:
     - Customer klik tombol "Login with Google"
     - System redirect ke Google OAuth
     - Google meminta persetujuan
     - System menerima data user dari Google
     - System membuat/update akun customer
     - Redirect ke homepage
   - **3c. Facebook Login**:
     - Customer klik tombol "Login with Facebook"
     - System redirect ke Facebook OAuth
     - Facebook meminta persetujuan
     - System menerima data user dari Facebook
     - System membuat/update akun customer
     - Redirect ke homepage
4. System menyimpan session customer
5. Customer berhasil login

**Alternative Flow**:
- **A1**: Kredensial salah → tampilkan error message
- **A2**: Email belum terdaftar → arahkan ke registrasi
- **A3**: Email belum terverifikasi → kirim email verifikasi
- **A4**: Akun diblokir → tampilkan pesan akun suspended

**Business Rules**:
- Email harus unique
- Password minimal 8 karakter
- Email verification wajib untuk fitur tertentu
- Social login auto-create account jika belum ada

**Database**: `users` table

---

### **UC-002: Browse Products (Melihat Katalog Produk)**

**Aktor**: Customer (atau Guest)  
**Tujuan**: Melihat daftar produk yang tersedia  
**Prekondisi**: -  
**Postkondisi**: Sistem menampilkan produk sesuai filter

**Main Flow**:
1. Customer mengakses halaman `/menu`
2. System menampilkan daftar produk dengan:
   - Gambar produk
   - Nama produk
   - Harga
   - Rating (jika ada)
   - Status ketersediaan
3. System menampilkan filter:
   - Kategori (Makanan, Minuman, Dessert, dll)
   - Harga (range)
   - Rating
   - Sort by (newest, popular, price)
4. Customer dapat scroll/pagination untuk melihat lebih banyak
5. System menampilkan produk dengan animasi AOS

**Alternative Flow**:
- **A1**: Tidak ada produk → tampilkan "Belum ada produk"
- **A2**: Filter menghasilkan 0 produk → tampilkan "Tidak ditemukan"

**Business Rules**:
- Hanya tampilkan produk yang `in_stock = true`
- Produk diurutkan berdasarkan pilihan user (default: newest)
- Pagination 12 produk per halaman

**Database**: `products`, `categories`

---

### **UC-003: Search Products (Pencarian Produk)**

**Aktor**: Customer  
**Tujuan**: Mencari produk berdasarkan keyword  
**Prekondisi**: Customer ada di halaman menu  
**Postkondisi**: System menampilkan hasil pencarian

**Main Flow**:
1. Customer mengetik keyword di search box
2. System melakukan pencarian real-time (debounce 300ms)
3. System mencari di:
   - Nama produk
   - Deskripsi produk
   - Kategori
4. System menampilkan hasil dengan highlight keyword
5. Customer dapat klik hasil untuk ke detail

**Alternative Flow**:
- **A1**: Tidak ada hasil → tampilkan "Produk tidak ditemukan" dengan saran produk terkait

**Business Rules**:
- Search case-insensitive
- Minimal 2 karakter untuk trigger search
- Maksimal 50 hasil ditampilkan

**Database**: `products`, `categories`

---

### **UC-004: View Product Detail (Lihat Detail Produk)**

**Aktor**: Customer  
**Tujuan**: Melihat informasi lengkap produk  
**Prekondisi**: Customer memilih produk  
**Postkondisi**: System menampilkan detail produk

**Main Flow**:
1. Customer klik produk dari katalog
2. System redirect ke `/menu/{slug}`
3. System menampilkan:
   - Gambar produk (slider jika multiple)
   - Nama produk
   - Harga
   - Deskripsi lengkap
   - Stok tersedia
   - Rating & jumlah review
   - Kategori
   - Review dari customer lain
4. System menampilkan tombol:
   - "Add to Cart"
   - "Add to Wishlist"
5. System menampilkan "Related Products"

**Alternative Flow**:
- **A1**: Produk tidak ditemukan → 404 page
- **A2**: Produk out of stock → disable "Add to Cart", tampilkan "Out of Stock"

**Business Rules**:
- Slug harus unique
- Review ditampilkan dengan rating tertinggi dulu
- Related products berdasarkan kategori yang sama

**Database**: `products`, `reviews`, `categories`

---

### **UC-005: Add to Cart (Tambah ke Keranjang)**

**Aktor**: Customer  
**Tujuan**: Menambahkan produk ke keranjang  
**Prekondisi**: Customer melihat produk  
**Postkondisi**: Produk masuk keranjang

**Main Flow**:
1. Customer klik tombol "Add to Cart"
2. System menampilkan quantity selector (default: 1)
3. Customer pilih jumlah qty
4. Customer konfirmasi
5. System menyimpan ke session cart (guest) atau database (logged in)
6. System menampilkan notifikasi sukses
7. System update cart badge counter

**Alternative Flow**:
- **A1**: Qty melebihi stok → tampilkan error "Stok tidak mencukupi"
- **A2**: Produk sudah ada di cart → update qty (tambahkan)

**Business Rules**:
- Guest: simpan di session
- Logged in: simpan di database
- Minimal qty: 1
- Maksimal qty: stok tersedia atau 99 (yang lebih kecil)
- Auto-sync cart saat login (merge session + database)

**Database**: `cart` (session untuk guest)

---

### **UC-006: Manage Cart (Kelola Keranjang)**

**Aktor**: Customer  
**Tujuan**: Melihat dan mengubah isi keranjang  
**Prekondisi**: Customer memiliki item di cart  
**Postkondisi**: Cart terupdate

**Main Flow**:
1. Customer klik icon cart atau akses `/cart`
2. System menampilkan daftar produk di cart dengan:
   - Gambar produk
   - Nama produk
   - Harga satuan
   - Quantity selector
   - Subtotal per item
   - Tombol remove
3. System menampilkan ringkasan:
   - Subtotal items
   - Estimasi ongkir (jika alamat sudah dipilih)
   - Total
4. Customer dapat:
   - Update qty
   - Remove item
   - Clear all cart
   - Continue shopping
   - Proceed to checkout

**Alternative Flow**:
- **A1**: Cart kosong → tampilkan "Keranjang kosong" dengan CTA "Belanja Sekarang"
- **A2**: Produk di cart out of stock → tampilkan notifikasi dan auto-remove

**Business Rules**:
- Real-time update total setiap ada perubahan
- Validasi stok sebelum checkout
- Session cart expire dalam 7 hari

**Database**: Session atau `carts` table

---

### **UC-007: Checkout (Proses Pemesanan)**

**Aktor**: Customer, System  
**Tujuan**: Melakukan pemesanan produk  
**Prekondisi**: 
- Customer sudah login
- Cart tidak kosong
**Postkondisi**: Order dibuat, menunggu pembayaran

**Main Flow**:
1. Customer klik "Checkout" dari cart
2. System redirect ke halaman checkout
3. System menampilkan form:
   - **Delivery Information**:
     - Nama penerima
     - Nomor telepon
     - Alamat lengkap
     - Catatan pengiriman (optional)
   - **Delivery Address Selection**:
     - Pilih dari alamat tersimpan, atau
     - Input alamat baru dengan map picker
4. System auto-calculate ongkir berdasarkan:
   - Jarak dari toko (lat/lng)
   - Zona pengiriman
5. System menampilkan order summary:
   - List items
   - Subtotal
   - Ongkir
   - Total pembayaran
6. Customer review dan klik "Proceed to Payment"
7. System validasi:
   - Stok masih tersedia
   - Alamat dalam zona pengiriman
8. System membuat order dengan status `pending`
9. System redirect ke halaman payment

**Alternative Flow**:
- **A1**: Alamat di luar zona → tampilkan error "Alamat tidak tercover"
- **A2**: Stok berubah saat checkout → update cart dan notifikasi
- **A3**: Customer belum login → redirect ke login, kemudian balik ke checkout

**Business Rules**:
- Minimal order: Rp 10.000
- Maksimal jarak pengiriman: 100 km
- Ongkir = base_rate + (distance * per_km_rate) * zone_multiplier
- Generate `tracking_code` unique 12 karakter

**Database**: `orders`, `order_items`

---

### **UC-008: Payment (Pembayaran)**

**Aktor**: Customer, Midtrans System  
**Tujuan**: Melakukan pembayaran order  
**Prekondisi**: Order sudah dibuat dengan status pending  
**Postkondisi**: Pembayaran berhasil, order status `paid`

**Main Flow**:
1. System menampilkan halaman payment dengan informasi:
   - Order ID
   - Total pembayaran
   - Tombol "Bayar Sekarang"
2. Customer klik "Bayar Sekarang"
3. System request Snap Token ke Midtrans:
   ```
   POST /snap/transactions
   {
     order_id, gross_amount, customer_details, item_details
   }
   ```
4. System menerima snap_token
5. System membuka Midtrans Snap modal
6. Customer memilih metode pembayaran:
   - Credit/Debit Card
   - Bank Transfer (BCA, BNI, Mandiri, dll)
   - E-wallet (GoPay, OVO, QRIS)
   - Convenience Store (Alfamart, Indomaret)
7. Customer menyelesaikan pembayaran
8. Midtrans mengirim notifikasi ke webhook
9. System menerima webhook notification
10. System update order status:
    - `settlement` → `paid` (confirmed)
    - `pending` → tetap pending
    - `expire` → `cancelled`
11. System kirim notifikasi ke customer via WhatsApp/SMS
12. System redirect customer ke tracking page

**Alternative Flow**:
- **A1**: Payment cancelled → status tetap pending, customer dapat retry
- **A2**: Payment expired → order auto-cancel setelah 24 jam
- **A3**: Payment failed → tampilkan error dan opsi retry

**Business Rules**:
- Payment expire: 24 jam
- Support multiple payment methods
- Webhook validation dengan signature key
- Auto-cancel order jika tidak dibayar dalam 24 jam

**Database**: `orders`, webhook log

**External System**: Midtrans Payment Gateway

---

### **UC-009: Track Order (Lacak Pesanan)**

**Aktor**: Customer  
**Tujuan**: Melihat status dan lokasi pesanan real-time  
**Prekondisi**: Order sudah dibuat  
**Postkondisi**: Customer mendapat informasi tracking

**Main Flow**:
1. Customer akses `/tracking/{trackingCode}`
2. System menampilkan tracking page dengan:
   - **Order Status Timeline**:
     - ✅ Order Confirmed
     - ⏳ Processing
     - 🚚 Picked Up
     - 📍 On Delivery
     - ✅ Delivered
   - **Real-time Map**:
     - Lokasi toko (marker)
     - Lokasi customer (marker)
     - Lokasi driver real-time (marker bergerak)
     - Rute pengiriman (polyline)
   - **Driver Information** (jika sudah assigned):
     - Nama driver
     - Foto driver
     - Nomor kendaraan
     - Rating driver
     - Tombol "Chat Driver"
     - Tombol "Call/WhatsApp Driver"
   - **Order Details**:
     - Order ID
     - Tanggal order
     - List items
     - Total pembayaran
   - **Estimated Time**:
     - ETA pengiriman
     - Jarak tersisa
3. System auto-refresh lokasi driver setiap 10 detik
4. System menampilkan weather info untuk area pengiriman

**Alternative Flow**:
- **A1**: Tracking code invalid → 404 page
- **A2**: Driver belum assigned → tampilkan "Menunggu driver"
- **A3**: Order cancelled → tampilkan status cancelled dengan alasan

**Business Rules**:
- Tracking page public (tidak perlu login)
- Driver location update setiap 10-30 detik
- ETA dihitung berdasarkan jarak dan kecepatan rata-rata
- Weather info dari OpenWeatherMap API

**Database**: `orders`, `driver_location`, `order_timeline`

---

### **UC-010: Chat with Driver**

**Aktor**: Customer, Driver  
**Tujuan**: Komunikasi real-time dengan driver  
**Prekondisi**: Order sudah di-assign ke driver  
**Postkondisi**: Pesan terkirim

**Main Flow**:
1. Customer klik "Chat Driver" dari tracking page
2. System membuka chat interface (modal atau panel)
3. System load chat history
4. Customer ketik pesan dan klik send
5. System menyimpan pesan ke database
6. System broadcast pesan ke driver via WebSocket/polling
7. Driver menerima notifikasi dan pesan
8. Driver dapat balas pesan
9. System real-time update chat untuk kedua pihak
10. Customer dapat:
    - Kirim text message
    - Kirim lokasi (mis: "Saya di sini")
    - Lihat status read/unread

**Alternative Flow**:
- **A1**: Driver offline → pesan tetap terkirim, driver dapat baca nanti
- **A2**: Connection error → retry send otomatis

**Business Rules**:
- Chat tersedia saat status: processing, picked_up, on_delivery
- Chat tidak bisa edit/delete setelah terkirim
- Chat history disimpan permanent
- Mark as read otomatis saat dibuka
- Notifikasi unread message

**Database**: `order_chats`

---

### **UC-011: Give Rating & Review**

**Aktor**: Customer  
**Tujuan**: Memberikan feedback setelah pesanan selesai  
**Prekondisi**: Order status = delivered  
**Postkondisi**: Rating & review tersimpan

**Main Flow**:
1. System menampilkan popup/notifikasi "Berikan Rating"
2. Customer klik "Beri Rating"
3. System menampilkan form:
   - **Delivery Rating** (1-5 stars):
     - Kecepatan pengiriman
     - Kondisi produk
     - Profesionalitas driver
   - **Product Review**:
     - Rating produk (1-5 stars)
     - Review text
     - Upload foto (optional, max 3)
4. Customer isi form dan submit
5. System validasi:
   - Rating wajib diisi
   - Review text minimal 10 karakter (optional)
6. System simpan rating dan review
7. System update:
   - Product rating average
   - Driver rating average
8. System tampilkan "Terima kasih atas feedback Anda"

**Alternative Flow**:
- **A1**: Customer skip → bisa beri rating nanti dari order history
- **A2**: Customer sudah pernah beri rating → tampilkan rating sebelumnya

**Business Rules**:
- Satu order hanya bisa diberi rating 1 kali
- Rating dapat diedit dalam 7 hari
- Review ditampilkan setelah moderasi (opsional)
- Foto review max 3 file @ 2MB

**Database**: `reviews`, `orders` (delivery_rating)

---

### **UC-012: View Order History**

**Aktor**: Customer  
**Tujuan**: Melihat riwayat pesanan  
**Prekondisi**: Customer sudah login  
**Postkondisi**: Menampilkan daftar order

**Main Flow**:
1. Customer akses `/my-orders`
2. System menampilkan daftar order dengan:
   - Order ID & tracking code
   - Tanggal order
   - Status (badge colored)
   - Total pembayaran
   - Thumbnail produk
   - Tombol "View Details"
3. Customer dapat filter by:
   - Status (All, Pending, Processing, Delivered, Cancelled)
   - Date range
4. Customer klik "View Details"
5. System menampilkan detail order (modal):
   - Full order info
   - Timeline
   - Items
   - Payment info
   - Delivery info
   - Tombol "Track Order"
   - Tombol "Download Invoice"
   - Tombol "Reorder"

**Alternative Flow**:
- **A1**: Belum ada order → tampilkan empty state dengan CTA "Belanja Sekarang"

**Business Rules**:
- Pagination 20 orders per page
- Default sort: newest first
- Invoice download format PDF

**Database**: `orders`, `order_items`

---

### **UC-013: Cancel Order**

**Aktor**: Customer  
**Tujuan**: Membatalkan pesanan  
**Prekondisi**: 
- Order belum di-pickup driver
- Status: pending atau processing
**Postkondisi**: Order cancelled

**Main Flow**:
1. Customer klik "Cancel Order" dari tracking/order detail
2. System tampilkan konfirmasi dialog:
   - "Apakah Anda yakin ingin membatalkan pesanan ini?"
   - Dropdown pilihan alasan pembatalan
3. Customer pilih alasan dan konfirmasi
4. System update order status → `cancelled`
5. System proses refund (jika sudah bayar):
   - Create refund request ke Midtrans
   - Update payment status
6. System kirim notifikasi:
   - Ke customer: "Pesanan dibatalkan"
   - Ke admin: "Order #XXX cancelled"
7. System kembalikan stok produk
8. System tampilkan "Pesanan berhasil dibatalkan"

**Alternative Flow**:
- **A1**: Order sudah di-pickup → tidak bisa cancel, tampilkan error
- **A2**: Refund gagal → create ticket untuk customer service

**Business Rules**:
- Hanya bisa cancel jika status: pending, processing
- Tidak bisa cancel jika status: picked_up, on_delivery, delivered
- Refund diproses dalam 3-7 hari kerja
- Stok produk dikembalikan otomatis

**Database**: `orders`, `products`, refund_logs

---

### **UC-014: Manage Wishlist**

**Aktor**: Customer  
**Tujuan**: Menyimpan produk favorit untuk dibeli nanti  
**Prekondisi**: Customer sudah login  
**Postkondisi**: Produk di-save/remove dari wishlist

**Main Flow - Add to Wishlist**:
1. Customer klik icon heart pada produk
2. System toggle wishlist:
   - Jika belum ada → add ke wishlist (icon filled)
   - Jika sudah ada → remove dari wishlist (icon outline)
3. System tampilkan toast notification
4. System update wishlist counter

**Main Flow - View Wishlist**:
1. Customer akses `/wishlist`
2. System menampilkan grid produk di wishlist
3. Setiap item menampilkan:
   - Gambar produk
   - Nama & harga
   - Status stok
   - Tombol "Add to Cart"
   - Tombol "Remove"
4. Customer dapat:
   - Add to cart
   - Remove dari wishlist
   - Klik untuk view detail

**Alternative Flow**:
- **A1**: Wishlist kosong → tampilkan empty state

**Business Rules**:
- Guest tidak bisa akses wishlist
- Unlimited wishlist items
- Produk yang out of stock ditampilkan dengan badge

**Database**: `wishlists` (pivot table)

---

### **UC-015: Manage Profile**

**Aktor**: Customer  
**Tujuan**: Update informasi profil  
**Prekondisi**: Customer login  
**Postkondisi**: Profil terupdate

**Main Flow**:
1. Customer akses `/profile`
2. System menampilkan form profil:
   - Foto profil
   - Nama lengkap
   - Email (read-only jika dari social login)
   - Nomor telepon
   - Alamat default
   - Tombol "Change Password"
3. Customer edit informasi
4. Customer klik "Save Changes"
5. System validasi input
6. System update database
7. System tampilkan "Profil berhasil diupdate"

**Alternative Flow**:
- **A1**: Change password:
  - Input old password
  - Input new password
  - Confirm new password
  - Validasi dan update
- **A2**: Upload foto profil:
  - Max 2MB
  - Format: JPG, PNG
  - Auto-crop square

**Business Rules**:
- Email unique
- Phone number format: +62xxx
- Password minimal 8 karakter

**Database**: `users`

---

## 🚚 **MODUL DRIVER (UC-020 hingga UC-029)**

---

### **UC-020: Driver Login**

**Aktor**: Driver  
**Tujuan**: Login ke driver dashboard  
**Prekondisi**: User memiliki role driver  
**Postkondisi**: Driver masuk ke dashboard

**Main Flow**:
1. Driver akses `/login`
2. Driver input email & password
3. System validasi kredensial
4. System cek role = driver
5. System redirect ke `/driver` dashboard
6. System request izin GPS location
7. Driver grant location permission
8. System save initial location

**Alternative Flow**:
- **A1**: Bukan driver → redirect ke homepage
- **A2**: GPS permission denied → tampilkan warning

**Business Rules**:
- Driver harus aktif `is_available = true`
- GPS wajib aktif untuk dapat menerima order

**Database**: `users` (role driver)

---

### **UC-021: View Available Orders**

**Aktor**: Driver  
**Tujuan**: Melihat daftar order yang bisa diambil  
**Prekondisi**: Driver login dan available  
**Postkondisi**: Menampilkan list order

**Main Flow**:
1. System menampilkan `/driver/orders`
2. System query orders dengan kriteria:
   - Status = `processing` (pending assignment)
   - Dalam radius area driver (10 km)
   - Sort by: nearest first
3. System menampilkan setiap order:
   - Order ID
   - Customer name
   - Pickup address (toko)
   - Delivery address
   - Distance dari posisi driver
   - Estimated delivery time
   - Delivery fee
   - Order total
   - Items count
   - Tombol "Accept Order"
4. System auto-refresh list setiap 30 detik
5. System tampilkan map dengan markers order

**Alternative Flow**:
- **A1**: Tidak ada order → tampilkan "Tidak ada order tersedia"
- **A2**: Driver sudah punya active order → hide list, fokus ke active order

**Business Rules**:
- Driver hanya bisa accept 1 order at a time
- Order auto-assigned jika tidak ada driver dalam 15 menit → ke driver terdekat
- Prioritas ke driver dengan rating tertinggi

**Database**: `orders`, `driver_location`

---

### **UC-022: Accept Order**

**Aktor**: Driver  
**Tujuan**: Menerima order untuk dikirim  
**Prekondisi**: Ada order available  
**Postkondisi**: Order assigned ke driver

**Main Flow**:
1. Driver klik "Accept Order"
2. System validasi:
   - Order masih available
   - Driver belum punya active order
3. System assign order:
   - Update `order.driver_id` = driver ID
   - Update `order.status` = `picked_up`
4. System create timeline event: "Driver accepted"
5. System send notification:
   - Ke customer: "Driver [Name] sedang menuju lokasi Anda"
   - Include driver info & contact
6. System redirect driver ke order detail page
7. System start real-time GPS tracking

**Alternative Flow**:
- **A1**: Order sudah diambil driver lain → tampilkan "Order sudah diambil"
- **A2**: Driver reject → kembali ke list

**Business Rules**:
- First come first serve
- Driver bisa reject order tanpa penalty (max 3x/hari)
- Jika reject lebih dari 3x → auto set unavailable

**Database**: `orders`, `order_timeline`

---

### **UC-023: Update Order Status**

**Aktor**: Driver  
**Tujuan**: Update progress pengiriman  
**Prekondisi**: Driver memiliki active order  
**Postkondisi**: Status terupdate, customer dapat notifikasi

**Main Flow**:
1. Driver di halaman order detail
2. System menampilkan current status dan tombol next action:
   - **Status: Processing** → Tombol "Mark as Picked Up"
   - **Status: Picked Up** → Tombol "Mark as On Delivery"
   - **Status: On Delivery** → Tombol "Mark as Delivered"
3. Driver klik tombol update status
4. System tampilkan konfirmasi
5. Driver konfirmasi
6. System update `order.status`
7. System create timeline event
8. System send notification to customer:
   - WhatsApp/SMS dengan link tracking
9. System tampilkan next instruction

**Alternative Flow**:
- **A1**: GPS tidak aktif → tampilkan warning "Aktifkan GPS"

**Business Rules**:
- Status harus sequential (tidak bisa skip)
- Setiap update status tercatat di timeline dengan timestamp
- Notifikasi otomatis ke customer setiap perubahan status

**Database**: `orders`, `order_timeline`, `notifications`

---

### **UC-024: Upload Proof of Delivery**

**Aktor**: Driver  
**Tujuan**: Upload foto bukti pengiriman  
**Prekondisi**: Status = on_delivery  
**Postkondisi**: Foto terupload, order completed

**Main Flow**:
1. Driver di halaman order detail
2. Driver klik "Mark as Delivered"
3. System tampilkan form:
   - Upload foto (camera/gallery)
   - Catatan pengiriman (optional)
   - Checkbox "Confirm delivered"
4. Driver ambil foto bukti:
   - Produk di lokasi
   - Dengan customer (opsional)
5. Driver input catatan (mis: "Diterima oleh ibu rumah")
6. Driver centang confirm dan submit
7. System upload foto ke storage
8. System update order:
   - Status → `delivered`
   - `delivery_photo` → photo URL
   - `delivery_notes` → catatan
   - `delivered_at` → timestamp
9. System create timeline event: "Order delivered"
10. System send notification:
    - Customer: "Pesanan telah sampai! Berikan rating"
    - Admin: "Order #XXX completed"
11. System set driver available untuk order baru
12. System tampilkan feedback form rating customer (opsional)

**Alternative Flow**:
- **A1**: Upload failed → retry atau skip foto (harus ada alasan)
- **A2**: Customer tidak ada di lokasi → opsi "Leave at door" dengan foto lokasi

**Business Rules**:
- Foto wajib untuk bukti pengiriman
- Max file size: 5MB
- Format: JPG, PNG
- Auto-compress untuk performance
- Foto disimpan permanent

**Database**: `orders`, `order_timeline`

---

### **UC-025: Update Driver Location**

**Aktor**: Driver, System  
**Tujuan**: Update GPS location real-time  
**Prekondisi**: Driver sedang delivery  
**Postkondisi**: Location tersimpan untuk tracking

**Main Flow**:
1. System background service auto-run di driver app
2. System request current GPS coordinates setiap 15 detik
3. Driver device memberikan lat/lng
4. System kirim ke API:
   ```
   POST /driver/location/update
   {
     latitude, longitude, order_id
   }
   ```
5. System save ke database
6. System broadcast location ke customer (WebSocket/polling)
7. System update ETA berdasarkan:
   - Jarak tersisa
   - Kecepatan rata-rata
   - Traffic condition (jika ada)

**Alternative Flow**:
- **A1**: GPS signal lost → gunakan last known location
- **A2**: Battery saver mode → reduce update frequency to 30s

**Business Rules**:
- Update interval: 15 detik saat on delivery
- History disimpan untuk analytics
- Auto-stop tracking setelah delivered
- Precision: 10 meter

**Database**: `driver_location`, `location_history`

---

### **UC-026: Chat with Customer**

**Aktor**: Driver, Customer  
**Tujuan**: Komunikasi dengan customer  
**Prekondisi**: Driver assigned to order  
**Postkondisi**: Chat terkirim

**Main Flow**:
1. Driver klik "Chat Customer" dari order detail
2. System buka chat interface
3. System load chat history dengan customer ini
4. Driver ketik pesan
5. Driver bisa:
   - Send text message
   - Send current location "Saya sudah di depan"
   - Send estimated time "5 menit lagi sampai"
6. Customer receive notification
7. Customer baca dan balas
8. System real-time update chat untuk keduanya

**Alternative Flow**:
- **A1**: Customer offline → message tersimpan, customer baca nanti

**Business Rules**:
- Chat tersedia selama order aktif
- Template message tersedia untuk quick reply
- Chat history permanent untuk dispute handling

**Database**: `order_chats`

---

### **UC-027: View Delivery History**

**Aktor**: Driver  
**Tujuan**: Lihat riwayat pengiriman  
**Prekondisi**: Driver login  
**Postkondisi**: Menampilkan history

**Main Flow**:
1. Driver akses `/driver/history`
2. System menampilkan list deliveries:
   - Hari ini
   - Minggu ini
   - Bulan ini
3. Setiap entry menampilkan:
   - Order ID
   - Customer name
   - Delivery address
   - Distance
   - Earnings (delivery fee)
   - Time completed
   - Rating dari customer
4. System tampilkan statistics:
   - Total deliveries
   - Total earnings
   - Average rating
   - Total distance

**Database**: `orders`, reviews

---

### **UC-028: Driver Availability Toggle**

**Aktor**: Driver  
**Tujuan**: Set status online/offline  
**Prekondisi**: Driver login  
**Postkondisi**: Status availability berubah

**Main Flow**:
1. Driver klik toggle "Available/Offline"
2. System update `users.is_available`:
   - ON → driver bisa menerima order
   - OFF → driver tidak muncul di assign list
3. System tampilkan notifikasi status
4. Jika OFF:
   - Stop GPS tracking
   - Hide dari available drivers
5. Jika ON:
   - Start GPS tracking
   - Show di map untuk admin

**Business Rules**:
- Tidak bisa toggle OFF jika ada active order
- Auto-set OFF jika idle > 12 jam

**Database**: `users`

---

## 👨‍💼 **MODUL ADMIN (UC-040 hingga UC-059)**

---

### **UC-040: Admin Login**

**Aktor**: Admin  
**Tujuan**: Login ke admin panel  
**Prekondisi**: User memiliki role admin  
**Postkondisi**: Admin masuk dashboard

**Main Flow**:
1. Admin akses `/admin`
2. System cek auth:
   - Jika belum login → redirect ke `/login`
   - Jika sudah login → cek role
3. Admin input kredensial
4. System validasi role = admin
5. System redirect ke `/admin` dashboard
6. System log admin activity

**Alternative Flow**:
- **A1**: Bukan admin → error 403 Forbidden

**Business Rules**:
- Admin access log semua aktivitas
- Session timeout: 2 jam

**Database**: `users`, `admin_logs`

---

### **UC-041: Admin Dashboard**

**Aktor**: Admin  
**Tujuan**: Melihat ringkasan sistem  
**Prekondisi**: Admin login  
**Postkondisi**: Menampilkan metrics

**Main Flow**:
1. System tampilkan dashboard dengan:
   
   **📊 Key Metrics (Cards)**:
   - Total Orders (hari ini / bulan ini)
   - Total Revenue
   - Active Drivers
   - Pending Orders
   
   **📈 Charts**:
   - Revenue Chart (7 hari terakhir)
   - Orders by Status (Pie Chart)
   - Popular Products (Bar Chart)
   - Delivery Heatmap
   
   **📋 Tables**:
   - Recent Orders (10 terakhir)
   - Top Selling Products
   - Driver Performance
   
   **🚨 Alerts**:
   - Orders perlu assign driver
   - Low stock products
   - Customer complaints

**Database**: `orders`, `products`, `users`, `analytics`

---

### **UC-042: Manage Products**

**Aktor**: Admin  
**Tujuan**: CRUD produk  
**Prekondisi**: Admin login  
**Postkondisi**: Produk ter-manage

**Main Flow - Create**:
1. Admin akses `/admin/products/create`
2. System tampilkan form:
   - Nama produk
   - Slug (auto-generate)
   - Kategori (dropdown)
   - Deskripsi
   - Harga
   - Stok
   - Gambar (upload)
   - Status (Active/Inactive)
3. Admin isi form dan upload image
4. Admin submit
5. System validasi:
   - Slug unique
   - Harga > 0
   - Image max 2MB
6. System save product
7. System tampilkan success message

**Main Flow - Update**:
1. Admin klik "Edit" pada produk
2. System load data ke form
3. Admin ubah data
4. Admin submit
5. System update database

**Main Flow - Delete**:
1. Admin klik "Delete"
2. System tampilkan konfirmasi
3. Admin konfirmasi
4. System soft delete (tidak permanent)

**Main Flow - Bulk Actions**:
1. Admin centang multiple products
2. Admin pilih action:
   - Activate
   - Deactivate
   - Delete
3. System execute bulk action

**Alternative Flow**:
- **A1**: Duplicate product → create copy dengan "[Copy] Product Name"

**Business Rules**:
- Soft delete (bisa restore)
- Product history tercatat
- Auto-generate slug dari nama
- SEO-friendly URLs

**Database**: `products`

---

### **UC-043: Manage Categories**

**Aktor**: Admin  
**Tujuan**: CRUD kategori produk  
**Prekondisi**: Admin login  
**Postkondisi**: Kategori ter-manage

**Main Flow - Create**:
1. Admin akses `/admin/categories/create`
2. System tampilkan form:
   - Nama kategori
   - Slug
   - Deskripsi
   - Icon (upload)
   - Parent Category (untuk subcategory)
   - Display Order
3. Admin submit
4. System save category

**Main Flow - View Analytics**:
1. Admin klik "Category Analytics"
2. System tampilkan:
   - Total sales per category
   - Popular categories (chart)
   - Category performance

**Business Rules**:
- Support nested categories (parent-child)
- Auto-count products per category
- Cannot delete category dengan products

**Database**: `categories`

---

### **UC-044: Manage Orders**

**Aktor**: Admin  
**Tujuan**: Monitor dan manage semua orders  
**Prekondisi**: Admin login  
**Postkondisi**: Orders ter-manage

**Main Flow - View Orders**:
1. Admin akses `/admin/orders`
2. System tampilkan table orders:
   - Order ID & Tracking Code
   - Customer Name
   - Total Amount
   - Status (badge colored)
   - Payment Status
   - Driver (jika assigned)
   - Date
   - Actions (View, Edit, Delete)
3. Admin dapat filter:
   - By Status
   - By Date Range
   - By Payment Status
   - By Driver
4. Admin dapat search by:
   - Order ID
   - Customer name
   - Tracking code

**Main Flow - View Order Detail**:
1. Admin klik "View" pada order
2. System tampilkan detail lengkap:
   - Customer info
   - Items ordered
   - Payment info
   - Delivery info
   - Timeline history
   - Driver info
   - Chat history
   - Map (lokasi delivery)

**Main Flow - Update Status**:
1. Admin klik "Update Status"
2. System tampilkan dropdown status
3. Admin pilih status baru
4. System update dan kirim notifikasi

**Main Flow - Check Payment**:
1. Admin klik "Check Payment Status"
2. System query ke Midtrans API
3. System tampilkan real payment status
4. System sync jika ada perbedaan

**Business Rules**:
- Admin bisa manual update status
- Admin bisa reassign driver
- Admin bisa cancel order dengan refund
- Export orders to Excel/CSV

**Database**: `orders`, `order_items`, `order_timeline`

---

### **UC-045: Manage Users**

**Aktor**: Admin  
**Tujuan**: Manage customer accounts  
**Prekondisi**: Admin login  
**Postkondisi**: Users ter-manage

**Main Flow**:
1. Admin akses `/admin/users`
2. System tampilkan table users:
   - ID
   - Name
   - Email
   - Role
   - Status (Active/Blocked)
   - Registered Date
   - Total Orders
   - Actions
3. Admin dapat:
   - View user detail
   - Block/Unblock user
   - Assign/Remove role
   - Delete user

**Main Flow - Block User**:
1. Admin klik "Block"
2. System konfirmasi
3. Admin pilih alasan block
4. System set `is_blocked = true`
5. User tidak bisa login

**Main Flow - Assign Role**:
1. Admin klik "Assign Role"
2. System tampilkan dropdown role:
   - Customer
   - Driver
   - Admin
3. Admin pilih role
4. System sync role

**Business Rules**:
- User hanya punya 1 primary role
- Blocked user tidak bisa login
- Admin tidak bisa delete self
- User deletion = soft delete

**Database**: `users`, `roles`, `user_roles`

---

### **UC-046: Manage Drivers**

**Aktor**: Admin  
**Tujuan**: Manage driver accounts dan performance  
**Prekondisi**: Admin login  
**Postkondisi**: Drivers ter-manage

**Main Flow - View Drivers**:
1. Admin akses `/admin/drivers`
2. System tampilkan table drivers:
   - Photo
   - Name
   - Phone
   - Vehicle Info
   - Status (Available/Busy/Offline)
   - Current Location (map)
   - Rating
   - Total Deliveries
   - Actions
3. Admin dapat:
   - Add new driver
   - Edit driver info
   - View driver performance
   - Toggle availability
   - Delete driver

**Main Flow - Add Driver**:
1. Admin klik "Add Driver"
2. System tampilkan form:
   - Personal Info (name, email, phone, photo)
   - Vehicle Info (type, license number, plate)
   - License verification (upload KTP, SIM)
3. Admin submit
4. System create user dengan role driver
5. System send email credentials ke driver

**Main Flow - View Performance**:
1. Admin klik driver name
2. System tampilkan:
   - Delivery statistics
   - Earnings
   - Rating history
   - Customer feedback
   - Map of delivery routes

**Business Rules**:
- Driver harus punya vehicle info
- License verification wajib
- Rating < 3.0 → auto flag for review
- Driver dapat di-suspend temporary

**Database**: `users` (role driver), `driver_info`

---

### **UC-047: Assign Driver to Order**

**Aktor**: Admin  
**Tujuan**: Manual assign driver ke order  
**Prekondisi**: 
- Order status = processing
- Ada available driver
**Postkondisi**: Driver assigned

**Main Flow**:
1. Admin di halaman `/admin/delivery`
2. System tampilkan orders tanpa driver  
3. Admin klik "Assign Driver" pada order
4. System tampilkan modal:
   - Map dengan:
     - Order location (marker)
     - Available drivers (markers)
     - Distance dari setiap driver
   - List available drivers:
     - Name, photo
     - Distance
     - Rating
     - Current status
     - Tombol "Assign"
5. Admin pilih driver dan klik "Assign"
6. System update:
   - `order.driver_id`
   - `order.status` → `picked_up`
7. System send notification:
   - To driver: "New order assigned"
   - To customer: "Driver [Name] akan mengantar pesanan Anda"
8. System tampilkan success message

**Alternative Flow**:
- **A1**: Auto-assign → system pilih driver terdekat dengan rating tertinggi
- **A2**: Driver reject → admin dapat reassign

**Business Rules**:
- Priority nearest driver dengan rating > 4.0
- Driver hanya bisa handle 1 order at a time
- Notifikasi otomatis ke driver

**Database**: `orders`, `users`, `notifications`

---

### **UC-048: View Analytics Dashboard**

**Aktor**: Admin  
**Tujuan**: Melihat analytics dan insights  
**Prekondisi**: Admin login  
**Postkondisi**: Menampilkan analytics

**Main Flow**:
1. Admin akses `/admin/analytics`
2. System menampilkan:
   
   **📊 Revenue Analytics**:
   - Total revenue (today, week, month, year)
   - Revenue growth %
   - Revenue chart (line/bar)
   - Revenue by category
   
   **📦 Order Analytics**:
   - Total orders & growth
   - Orders by status
   - Average order value
   - Conversion rate
   
   **🚚 Delivery Analytics**:
   - Total deliveries
   - Average delivery time
   - On-time delivery rate %
   - Delivery heatmap (area with most orders)
   
   **👥 Customer Analytics**:
   - New customers
   - Returning customers %
   - Customer lifetime value
   - Top customers
   
   **⭐ Performance Metrics**:
   - Average rating
   - Customer satisfaction
   - Driver performance
   
3. Admin dapat:
   - Filter by date range
   - Export reports (PDF/Excel)
   - Drill-down ke detail

**Business Rules**:
- Real-time data updates
- Heatmap berbasis koordinat delivery
- Export dengan custom columns

**Database**: All tables, `analytics_cache`

---

### **UC-049: Manage Delivery Zones**

**Aktor**: Admin  
**Tujuan**: Kelola zona pengiriman dan tarif  
**Prekondisi**: Admin login  
**Postkondisi**: Zones ter-manage

**Main Flow - Create Zone**:
1. Admin akses `/admin/zones`
2. Admin klik "Create Zone"
3. System tampilkan map editor
4. Admin:
   - Draw polygon di map untuk define area
   - Input nama zone
   - Set tarif:
     - Base rate (Rp)
     - Per km rate (Rp)
     - Multiplier (untuk surge pricing)
   - Set status (Active/Inactive)
5. Admin save
6. System save polygon coordinates
7. System tampilkan zona di map

**Main Flow - Edit Zone**:
1. Admin klik zona di map
2. System load zona editor
3. Admin ubah polygon/tarif
4. Admin save
5. System update

**Main Flow - View Zone Performance**:
1. Admin klik zona
2. System tampilkan:
   - Total orders dari zona ini
   - Revenue dari zona
   - Average delivery time
   - Popular products di zona

**Business Rules**:
- Zona bisa overlap (pilih yang lebih murah)
- Tarif bisa beda per zona
- Surge pricing saat peak hours

**Database**: `delivery_zones`

---

### **UC-050: Manage Settings**

**Aktor**: Admin  
**Tujuan**: Configure sistem settings  
**Prekondisi**: Admin login  
**Postkondisi**: Settings updated

**Main Flow**:
1. Admin akses `/admin/settings`
2. System tampilkan tabs:
   
   **General Settings**:
   - Site name
   - Logo
   - Contact info
   - Store location (lat/lng)
   
   **Shipping Settings**:
   - Base shipping fee
   - Per km rate
   - Max distance
   - Free shipping threshold
   
   **Payment Settings**:
   - Midtrans credentials
   - Payment methods enabled
   
   **Notification Settings**:
   - SMS API key
   - WhatsApp token
   - Email SMTP config
   - Notification templates
   
   **Integration Settings**:
   - Google Maps API key
   - Weather API key
   - Social login credentials
   
3. Admin edit settings
4. Admin klik "Test Email/SMS" untuk validasi
5. Admin save
6. System update config

**Business Rules**:
- Sensitive data encrypted
- Test function untuk validasi credentials
- Backup before major changes

**Database**: `settings`, `shipping_settings`

---

### **UC-051: Export Reports**

**Aktor**: Admin  
**Tujuan**: Export data untuk reporting  
**Prekondisi**: Admin login  
**Postkondisi**: File downloaded

**Main Flow**:
1. Admin di halaman data (orders/products/analytics)
2. Admin klik "Export"
3. System tampilkan export options:
   - Format: Excel / CSV / PDF
   - Date range
   - Filter (status, category, dll)
   - Columns to include
4. Admin pilih options dan klik "Export"
5. System generate file
6. System auto-download file
7. System log export activity

**Business Rules**:
- Max 10,000 rows per export
- Export history tersimpan
- Scheduled export (daily/weekly) available

**Database**: Export logs

---

### **UC-052: Send Bulk Notifications**

**Aktor**: Admin  
**Tujuan**: Kirim notifikasi massal  
**Prekondisi**: Admin login  
**Postkondisi**: Notifications sent

**Main Flow**:
1. Admin akses `/admin/tracking`
2. Admin klik "Send Bulk Notification"
3. System tampilkan form:
   - Target audience:
     - All customers
     - Customers with active orders
     - Customers by location/zone
     - Specific customer list
   - Message template:
     - Promotion
     - Announcement
     - Custom message
   - Channels:
     - WhatsApp
     - SMS
     - Email
     - In-app notification
4. Admin compose message
5. Admin klik "Send"
6. System queue notifications
7. System process queue dan kirim
8. System tampilkan send report

**Business Rules**:
- Max 1000 recipients per batch
- Rate limiting untuk prevent spam
- Unsubscribe option wajib

**Database**: `notifications`, `notification_queue`

---

## 🔄 **SYSTEM USE CASES**

---

### **UC-060: Auto-assign Driver**

**Aktor**: System  
**Trigger**: Order paid dan status = processing selama 15 menit tanpa driver

**Main Flow**:
1. System cron job check setiap 5 menit
2. System query orders:
   ```sql
   status = 'processing' 
   AND driver_id IS NULL
   AND created_at < NOW() - 15 minutes
   ```
3. Untuk setiap order:
   - Get order location
   - Query available drivers dalam radius 10km
   - Sort by: rating DESC, distance ASC
   - Assign ke driver teratas
4. System send notification ke driver & customer
5. Update status → `picked_up`

**Business Rules**:
- Auto-assign setelah 15 menit
- Radius maksimal 10 km
- Prioritas: rating > distance

---

### **UC-061: Auto-cancel Unpaid Orders**

**Aktor**: System  
**Trigger**: Order pending > 24 jam

**Main Flow**:
1. System cron job daily
2. System query:
   ```sql
   status = 'pending' 
   AND created_at < NOW() - 24 hours
   ```
3. Untuk setiap order:
   - Update status → `cancelled`
   - Return product stock
   - Send notification to customer
4. System log cancellations

---

### **UC-062: Weather-based Delivery Recommendations**

**Aktor**: System  
**Trigger**: Driver assigned to order

**Main Flow**:
1. System get delivery location coords
2. System call Weather API
3. System analyze weather:
   - Rain → warning "Hujan, pesanan mungkin terlambat"
   - Storm → suggest delay
   - Normal → OK
4. System tampilkan recommendation ke driver & customer

---

### **UC-063: Calculate Shipping Cost**

**Aktor**: System  
**Trigger**: Customer input address saat checkout

**Main Flow**:
1. System terima lat/lng destination
2. System get store location from settings
3. System calculate distance (Haversine formula)
4. System check delivery zones:
   - If inside zone → use zone rates
   - If outside → use default rates
5. System calculate:
   ```
   shipping_cost = zone.base_rate + (distance * zone.per_km_rate) * zone.multiplier
   ```
6. System return shipping cost ke frontend

**Business Rules**:
- Max distance: 100 km
- Free shipping jika order > Rp 100.000 (configurable)

---

### **UC-064: Generate Tracking Code**

**Aktor**: System  
**Trigger**: Order created

**Main Flow**:
1. System generate unique code:
   ```
   Format: [PREFIX][YEAR][MONTH][RANDOM6DIGIT]
   Example: DS2601123456
   ```
2. System check uniqueness
3. If duplicate → regenerate
4. System save to order.tracking_code

---

### **UC-065: Send Order Notifications**

**Aktor**: System  
**Trigger**: Order status changed

**Main Flow**:
1. System detect status change
2. System prepare notification based on status:
   - **Paid** → "Terima kasih! Pesanan Anda sedang diproses"
   - **Picked Up** → "Driver sedang menuju lokasi Anda"
   - **On Delivery** → "Pesanan dalam perjalanan! ETA 15 menit"
   - **Delivered** → "Pesanan telah sampai! Beri rating"
3. System get customer phone
4. System send via:
   - WhatsApp (priority)
   - Fallback to SMS if WhatsApp fail
5. System log notification

**Business Rules**:
- Include tracking link
- Personalized dengan nama customer & driver
- Template stored in database

---

## 🎯 **USE CASE PRIORITIES**

### **Priority 1 - MVP (Must Have)**
- UC-001: Register/Login ✅
- UC-002: Browse Products ✅
- UC-005: Add to Cart ✅
- UC-007: Checkout ✅
- UC-008: Payment ✅
- UC-009: Track Order ✅
- UC-042: Manage Products ✅
- UC-044: Manage Orders ✅

### **Priority 2 - Important**
- UC-010: Chat Driver ✅
- UC-012: Rating & Review ✅
- UC-022: Driver Accept Order ✅
- UC-024: Upload Proof ✅
- UC-047: Assign Driver ✅
- UC-049: Manage Zones ✅

### **Priority 3 - Nice to Have**
- UC-015: Wishlist
- UC-048: Analytics
- UC-050: Export Reports
- UC-052: Bulk Notifications

---

## 📈 **USE CASE METRICS**

| Module | Total Use Cases | Status |
|--------|----------------|--------|
| Customer | 17 | ✅ Implemented |
| Driver | 9 | ✅ Implemented |
| Admin | 13 | ✅ Implemented |
| System | 6 | ✅ Implemented |
| **TOTAL** | **45** | **100%** |

---

## 🔗 **USE CASE DEPENDENCIES**

```
UC-001 (Login)
  └─> UC-002 (Browse)
       └─> UC-005 (Add to Cart)
            └─> UC-007 (Checkout)
                 └─> UC-008 (Payment)
                      └─> UC-009 (Track)
                           └─> UC-010 (Chat)
                                └─> UC-012 (Rating)

UC-047 (Assign Driver)
  └─> UC-022 (Driver Accept)
       └─> UC-023 (Update Status)
            └─> UC-025 (Update GPS)
                 └─> UC-024 (Upload Proof)
```

---

## 📌 **KESIMPULAN**

Dokumentasi use case ini mencakup **45 use cases** lengkap yang menggambarkan seluruh fungsionalitas sistem **Dapur Sakura E-commerce Platform**. 

**Fitur Utama yang Tercakup**:
✅ E-commerce (Browse, Cart, Checkout, Payment)  
✅ Real-time Order Tracking dengan GPS  
✅ Driver Management & Assignment  
✅ Chat System (Customer-Driver)  
✅ Rating & Review System  
✅ Delivery Zones & Dynamic Pricing  
✅ Analytics & Reporting  
✅ Notification System (WhatsApp/SMS)  
✅ Admin Dashboard & Management  
✅ Social Login Integration  
✅ Payment Gateway (Midtrans)  

**Teknologi yang Digunakan**:
- **Backend**: Laravel 11
- **Frontend**: Tailwind CSS, Alpine.js, AOS
- **Maps**: Leaflet.js
- **Charts**: Chart.js
- **Payment**: Midtrans
- **Notifications**: WhatsApp API, SMS Gateway
- **Auth**: Laravel Sanctum + Socialite

---

**Generated**: 2026-01-05  
**Version**: 1.0  
**Project**: Dapur Sakura E-commerce Platform  
