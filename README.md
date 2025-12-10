# 🛒 Laravel E-Commerce Application

A full-featured e-commerce platform built with Laravel 12, featuring a modern admin panel, shopping cart, order management, and payment processing.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)
![License](https://img.shields.io/badge/license-MIT-blue?style=flat-square)

## ✨ Features

### Customer Features
- 🏪 **Product Catalog** - Browse products with search and category filtering
- 🛍️ **Shopping Cart** - Session-based cart with add, update, and remove functionality
- ⚡ **Buy Now** - Quick checkout option for immediate purchases
- 📦 **Order Management** - Track orders with status updates and history
- 💳 **Payment Processing** - Multiple payment methods (Credit Card, PayPal, Bank Transfer)
- 🔍 **Product Search** - Search products by name
- 📱 **Responsive Design** - Mobile-friendly interface

### Admin Features
- 📊 **Dashboard** - Analytics with sales charts, top products, and order statistics
- 🎨 **Product Management** - Full CRUD with multiple image support
- 📁 **Category Management** - Hierarchical categories (parent/child relationships)
- 📋 **Order Management** - View, update status, add notes, and generate invoices
- 🧾 **PDF Invoices** - Generate downloadable PDF invoices for orders
- 👥 **User Management** - Admin role system with access control

### Technical Features
- 🔐 **Authentication** - User registration, login, and email verification
- 🖼️ **Multiple Product Images** - Support for multiple images per product with primary image selection
- 📦 **Stock Management** - Real-time stock tracking and updates
- 🗂️ **Soft Deletes** - Products can be soft-deleted
- 📝 **Order History** - Complete audit trail of order status changes
- 🎯 **Featured Products** - Mark products as featured

## 🚀 Technology Stack

- **Backend**: Laravel 12.x
- **Frontend**: 
  - Tailwind CSS 4.0
  - Material-UI Components
  - Bootstrap (for some components)
  - Vite (build tool)
- **Database**: SQLite (default, easily configurable for MySQL/PostgreSQL)
- **PDF Generation**: barryvdh/laravel-dompdf
- **Authentication**: Laravel UI

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (or MySQL/PostgreSQL)

## 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/anonyname5/e-commerce.git
   cd e-commerce
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   
   Edit `.env` file and set your database configuration:
   ```env
   DB_CONNECTION=sqlite
   # Or use MySQL/PostgreSQL
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=ecommerce
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed admin user** (optional)
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Build assets**
   ```bash
   npm run build
   # Or for development
   npm run dev
   ```

10. **Start the server**
    ```bash
    php artisan serve
    ```

    Visit `http://localhost:8000` in your browser.

## 🎯 Usage

### Default Admin Credentials

If you ran the seeder, you can log in with:
- **Email**: Check `database/seeders/AdminUserSeeder.php` for default credentials
- **Password**: Check the seeder file

### Creating Products

1. Log in as admin
2. Navigate to `/admin/products`
3. Click "Create Product"
4. Fill in product details and upload images
5. Save the product

### Customer Flow

1. Browse products at `/products`
2. Click on a product to view details
3. Add to cart or use "Buy Now"
4. Proceed to checkout
5. Complete payment
6. Track order status

## 📁 Project Structure

```
E-Commerce/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   ├── CartController.php
│   │   │   ├── OrderController.php
│   │   │   ├── PaymentController.php
│   │   │   └── ProductController.php
│   │   └── Middleware/
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/             # Admin panel views
│   │   ├── auth/              # Authentication views
│   │   ├── cart/              # Shopping cart views
│   │   ├── orders/            # Order views
│   │   ├── products/          # Product views
│   │   └── shop/              # Shop/catalog views
│   ├── css/
│   └── js/
├── routes/
│   └── web.php                # Web routes
└── storage/
    └── app/
        └── public/            # Public storage (images, etc.)
```

## 🔐 Admin Routes

All admin routes are protected by `AdminMiddleware`:

- `/admin/dashboard` - Admin dashboard
- `/admin/products` - Product management
- `/admin/categories` - Category management
- `/admin/orders` - Order management

## 🛣️ Key Routes

### Public Routes
- `/` - Product catalog
- `/products` - Product listing
- `/products/{product}` - Product detail page
- `/category/{category}` - Category view

### Authenticated Routes
- `/cart` - Shopping cart
- `/checkout` - Checkout process
- `/orders` - Order history
- `/orders/{order}/track` - Order tracking

## 🗄️ Database Schema

### Main Models
- **User** - Customers and admins
- **Product** - Products with soft deletes
- **ProductImage** - Multiple images per product
- **Category** - Hierarchical categories
- **Order** - Customer orders
- **OrderItem** - Order line items
- **OrderHistory** - Order status/note history
- **Payment** - Payment transactions

## 🎨 Customization

### Changing App Name
Edit `config/app.php`:
```php
'name' => env('APP_NAME', 'Your Store Name'),
```

### Payment Integration
The current payment system is a dummy implementation. To integrate real payment gateways:

1. Edit `app/Services/PaymentService.php`
2. Integrate with Stripe, PayPal, or your preferred gateway
3. Update `app/Http/Controllers/PaymentController.php`

### Styling
- Main styles: `resources/css/app.css`
- Tailwind config: `tailwind.config.js`
- Views use Blade templates with Tailwind CSS

## 🧪 Testing

```bash
php artisan test
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📧 Support

For support, email your-email@example.com or open an issue in the repository.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [Material-UI](https://mui.com) - React component library
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) - PDF generation

---

⭐ If you find this project helpful, please consider giving it a star!
