# 🍜 Dapur Sakura - E-commerce Platform

Platform e-commerce modern untuk kuliner Jepang dan lokal dengan fitur pengiriman canggih.

## ✨ Fitur Utama

### 🛒 **E-commerce Features**
- **Homepage**: Hero section dengan animasi, kategori produk, best seller, produk terbaru
- **Product Catalog**: Pencarian, filter kategori, pagination
- **Shopping Cart**: Real-time updates, estimasi ongkir otomatis
- **Checkout**: Integrasi Midtrans untuk pembayaran
- **User Authentication**: Login/register dengan social login (Google, Facebook)

### 🚚 **Advanced Delivery System**

#### **Priority 1: Quick Wins** ✅
- **SMS/WhatsApp Integration**: Notifikasi tracking otomatis ke customer
- **Photo Proof Delivery**: Upload foto bukti pengiriman
- **Delivery Zones**: Zona pengiriman dengan tarif berbeda
- **Weather Integration**: Info cuaca untuk perencanaan pengiriman

#### **Priority 2: Medium Impact** ✅
- **Delivery Heatmap**: Visualisasi area dengan pesanan terbanyak
- **Route Optimization**: Algoritma untuk multiple delivery
- **Driver Assignment**: Sistem penugasan kurir otomatis
- **Customer Feedback**: Rating dan review pengiriman

#### **Priority 3: Long-term** ✅
- **Real-time Tracking**: Live location tracking kurir
- **Analytics Dashboard**: Statistik lengkap pengiriman dan revenue
- **Advanced Analytics**: Export data, performance metrics

## 🏗️ **Arsitektur Teknis**

### **Backend (Laravel 11)**
- **Models**: Order, User, DeliveryZone, Product, Category
- **Controllers**: PaymentController, TrackingController, DeliveryController, AnalyticsController
- **Services**: NotificationService, WeatherService, RouteOptimizationService, AnalyticsService
- **Middleware**: AdminMiddleware untuk proteksi route admin

### **Frontend**
- **Tailwind CSS**: Styling dengan tema pink-white
- **Alpine.js**: Interaktivitas
- **AOS**: Animasi scroll
- **Leaflet.js**: Maps dan heatmap
- **Chart.js**: Grafik analytics

### **Database Schema**
```sql
-- Orders dengan tracking fields
orders: id, user_id, status, tracking_code, tracking_url, driver_id, 
        delivery_photo, delivery_notes, delivery_rating, delivery_zone

-- Delivery zones dengan polygon coordinates
delivery_zones: id, name, polygon_coordinates, base_rate, per_km_rate, multiplier

-- Users dengan driver fields
users: id, name, email, is_driver, vehicle_type, current_latitude, current_longitude
```

## 🚀 **Installation & Setup**

### **1. Clone Repository**
```bash
git clone <repository-url>
cd dapua
```

### **2. Install Dependencies**
```bash
composer install
npm install
```

### **3. Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

### **4. Database Setup**
```bash
php artisan migrate
php artisan db:seed --class=DeliveryZoneSeeder
```

### **5. Build Assets**
```bash
npm run build
```

### **6. Run Application**
```bash
php artisan serve
```

## ⚙️ **Configuration**

### **Required Environment Variables**
```env
# Store Location
STORE_LAT=-0.947100
STORE_LNG=100.417200

# Shipping
SHIPPING_BASE=5000
SHIPPING_PER_KM=2000
MAX_SHIPPING_DISTANCE=100

# Midtrans
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key

# Social Login
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret

# Notifications
SMS_API_KEY=your_sms_api_key
WHATSAPP_TOKEN=your_whatsapp_token

# Weather
WEATHER_API_KEY=your_openweathermap_api_key
```

## 📱 **API Endpoints**

### **Public APIs**
- `GET /tracking/{code}` - Customer tracking page
- `GET /api/tracking/{code}` - Tracking data API
- `POST /tracking/{code}/feedback` - Customer feedback

### **Admin APIs**
- `GET /admin/delivery` - Delivery management
- `POST /admin/orders/{id}/assign-driver` - Assign driver
- `POST /admin/orders/{id}/mark-delivered` - Mark delivered
- `GET /admin/analytics` - Analytics dashboard
- `GET /admin/analytics/heatmap` - Heatmap data

## 🎯 **Key Features Implementation**

### **1. SMS/WhatsApp Integration**
```php
// NotificationService
$notificationService = app(NotificationService::class);
$success = $notificationService->sendTrackingNotification($order);
```

### **2. Delivery Zones**
```php
// Check zone and calculate shipping
$deliveryZone = DeliveryZone::where('is_active', true)
    ->get()->first(function ($zone) use ($lat, $lng) {
        return $zone->containsPoint($lat, $lng);
    });
```

### **3. Route Optimization**
```php
// RouteOptimizationService
$optimizedRoute = $this->optimizeRoute($orders, $driver);
$routeInfo = $this->estimateDeliveryTime($route, $startLat, $startLng);
```

### **4. Weather Integration**
```php
// WeatherService
$weather = $weatherService->getCurrentWeather($latitude, $longitude);
$recommendations = $weatherService->getDeliveryRecommendations($lat, $lng);
```

### **5. Analytics & Heatmap**
```php
// AnalyticsService
$heatmapData = $this->generateDeliveryHeatmap($startDate, $endDate);
$performance = $this->getDeliveryPerformance($startDate, $endDate);
```

## 🔧 **Services Architecture**

### **NotificationService**
- SMS/WhatsApp integration
- Template messages untuk tracking, confirmation, delivery
- Fallback mechanism (WhatsApp → SMS)

### **WeatherService**
- OpenWeatherMap API integration
- Delivery suitability checking
- Weather-based recommendations
- Caching untuk performance

### **RouteOptimizationService**
- Nearest Neighbor Algorithm
- Priority-based routing
- Multi-driver optimization
- Batch delivery suggestions

### **AnalyticsService**
- Delivery performance metrics
- Revenue analytics
- Heatmap generation
- Customer satisfaction tracking

## 📊 **Admin Dashboard Features**

### **Delivery Management**
- Real-time order tracking
- Driver assignment
- Photo proof delivery
- Status updates

### **Analytics Dashboard**
- Delivery heatmap
- Performance metrics
- Revenue analytics
- Customer satisfaction
- Export functionality

### **Zone Management**
- Create/edit delivery zones
- Polygon coordinate mapping
- Tarif configuration
- Zone performance tracking

## 🎨 **UI/UX Features**

### **Design System**
- Pink-white color scheme
- Luxurious animations
- Responsive design
- Micro-interactions

### **Animations**
- AOS scroll animations
- Page transitions
- Hover effects
- Loading states

### **Interactive Elements**
- Real-time cart updates
- Map integration
- Photo uploads
- Rating system

## 🔒 **Security Features**

- CSRF protection
- Rate limiting
- Admin middleware
- Input validation
- SQL injection prevention

## 📈 **Performance Optimizations**

- Database indexing
- Query optimization
- Caching strategies
- Asset minification
- Lazy loading

## 🧪 **Testing**

```bash
# Run tests
php artisan test

# Coverage
php artisan test --coverage
```

## 📝 **Documentation**

- API documentation tersedia di `/api/docs`
- Database schema di `/docs/database.md`
- Deployment guide di `/docs/deployment.md`

## 🤝 **Contributing**

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📄 **License**

MIT License - see LICENSE file for details.

## 🆘 **Support**

Untuk pertanyaan atau dukungan, silakan buat issue di repository ini.

---
**Dapur Sakura** - Modern Japanese & Local Cuisine E-commerce Platform 🍜✨
