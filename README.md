# Nepali Chulo - Cloud Kitchen Management System

A comprehensive web-based cloud kitchen management system for "Nepali Chulo" (D-MAGG Cloud Kitchen), specializing in authentic Nepali cuisine delivery and online ordering.

## 🍽️ About

Nepali Chulo is a cloud kitchen platform that brings the authentic taste of Nepal to your doorstep. The system allows customers to order their favorite Nepali dishes online while providing restaurant administrators with powerful tools to manage orders, menu items, and customer interactions.

**Tagline:** "Order Your Favourite Nepali Food Anytime Anywhere!"

## ✨ Features

### Customer Features
- **User Registration & Authentication** with OTP verification
- **Menu Browsing** with categories (Veg, Non-Veg, Dal Bhat, Momo)
- **Shopping Cart** functionality
- **Online Ordering** system
- **Order Tracking** and history
- **Multiple Payment Options** including eSewa integration
- **Responsive Design** for mobile and desktop
- **Contact & About** pages

### Admin Features
- **Admin Dashboard** with comprehensive analytics
- **Menu Management** (Add, Edit, Delete items)
- **Category Management**
- **Order Management** with status updates
- **Customer Message Management**
- **Sales Analytics** and reporting
- **Image Upload** for menu items

### Technical Features
- **Secure Authentication** with password hashing
- **Email Integration** using PHPMailer
- **Payment Gateway** integration (eSewa)
- **Database Management** with MySQL
- **Session Management**
- **File Upload** functionality
- **Responsive UI** with modern CSS

## 🛠️ Technology Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Email:** PHPMailer
- **Payment:** eSewa Payment Gateway
- **Styling:** Custom CSS with responsive design
- **Icons:** Font Awesome

## 📋 Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHPMailer dependencies)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone [repository-url]
   cd cloudkitchen
   ```

2. **Set up the database**
   - Create a MySQL database named `cloudkitchen`
   - Import the database schema (SQL file should be created from the existing structure)
   - Update database credentials in `db/conn.php`

3. **Configure database connection**
   ```php
   // Edit db/conn.php
   $servername = "localhost";
   $username = "your_db_username";
   $password = "your_db_password";
   $dbname = "cloudkitchen";
   ```

4. **Set up email configuration**
   - Configure PHPMailer settings in `signup.php`
   - Update email credentials for OTP verification

5. **Configure payment gateway**
   - Set up eSewa merchant credentials
   - Update payment configuration in `esewa-master/` directory

6. **Set permissions**
   ```bash
   chmod 755 admin/uploads/
   chmod 755 Frontend/home/img/
   ```

## 📁 Project Structure

```
cloudkitchen/
├── admin/                  # Admin panel
│   ├── images/            # Admin interface images
│   ├── uploads/           # Uploaded menu item images
│   ├── dashboard.php      # Admin dashboard
│   ├── manage_item.php    # Menu item management
│   ├── manage_order.php   # Order management
│   └── ...
├── Frontend/              # Customer-facing interface
│   └── home/             # Main customer pages
│       ├── img/          # Frontend images
│       ├── home.php      # Homepage
│       ├── menu.php      # Menu page
│       ├── cart.php      # Shopping cart
│       ├── checkout.php  # Checkout process
│       └── ...
├── db/                   # Database configuration
│   └── conn.php         # Database connection
├── PHPMailer/           # Email library
├── esewa-master/        # Payment gateway integration
├── login.php           # User login
├── signup.php          # User registration
├── verify_otp.php      # OTP verification
└── logout.php          # User logout
```

## 🗄️ Database Schema

The system uses the following main tables:
- `registration1` - User accounts and authentication
- `add_items` - Menu items and details
- `add_categories` - Food categories
- `orders` - Active orders
- `orders_archive` - Completed orders
- `message` - Customer messages/inquiries

## 🔧 Configuration

### Email Settings
Update the email configuration in `signup.php`:
```php
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
$mail->setFrom('your-email@gmail.com', 'Your Name');
```

### Payment Gateway
Configure eSewa settings in the `esewa-master/` directory according to your merchant account.

## 🎯 Usage

### For Customers
1. Register an account with OTP verification
2. Browse the menu by categories
3. Add items to cart
4. Proceed to checkout
5. Make payment through eSewa
6. Track order status

### For Administrators
1. Access admin panel at `/admin/admin_login.php`
2. Manage menu items and categories
3. Process and update orders
4. View sales analytics
5. Respond to customer messages

## 🏃‍♂️ Running the Application

1. Start your web server (Apache/Nginx)
2. Ensure MySQL is running
3. Access the application at `http://localhost/cloudkitchen/`
4. Admin panel: `http://localhost/cloudkitchen/admin/`

## 🔒 Security Features

- Password hashing with PHP's `password_hash()`
- SQL injection prevention with prepared statements
- Session management for authentication
- OTP verification for registration
- Input validation and sanitization

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📝 License

This project is developed for Nepali Chulo Cloud Kitchen. All rights reserved.

## 📞 Support

For technical support or business inquiries, please contact through the application's contact form or reach out to the development team.

---

**Nepali Chulo** - Bringing authentic Nepali flavors to your doorstep! 🇳🇵🍛