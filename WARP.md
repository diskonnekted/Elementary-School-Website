# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a static website for "SD Cerdas Ceria" (an Indonesian elementary school). The website is built using vanilla HTML5, CSS3, and JavaScript with a focus on modern design, responsive layout, and user experience. The site uses Indonesian language throughout and follows educational institution web design patterns.

## Common Development Commands

### Serving the Website Locally
```bash
# Simple HTTP server using Python
python -m http.server 8000

# Alternative using Node.js (if http-server is installed)
npx http-server -p 8000

# Alternative using PHP (recommended for backend functionality)
php -S localhost:8000
```

### Backend Dashboard Commands
```bash
# Setup database (run once)
mysql -u root -p < admin/config/database_setup.sql

# Start development server with PHP (required for backend)
php -S localhost:8000

# Access admin dashboard
# URL: http://localhost:8000/admin/
# Default login: admin / admin123

# Create uploads directory
mkdir uploads
chmod 755 uploads
```

### File Structure Validation
```bash
# Check all HTML files for syntax validation
find . -name "*.html" -exec echo "Checking {}" \; -exec xmllint --html --noout {} \;

# Validate CSS
find . -name "*.css" -exec echo "Checking {}" \; -exec csslint {} \;
```

### Development Workflow
```bash
# Start development server and open in browser (Windows)
python -m http.server 8000 && start http://localhost:8000

# Start development server and open in browser (macOS)
python3 -m http.server 8000 && open http://localhost:8000

# Start development server and open in browser (Linux)
python3 -m http.server 8000 && xdg-open http://localhost:8000
```

## Architecture & Code Structure

### File Organization
```
/
├── index.html              # Main homepage with hero section, features, stats
├── profil.html             # School profile with vision, mission, values
├── berita.html             # News section (now dynamic via API)
├── akademik.html           # Academic programs (now dynamic via API)  
├── info.html               # General information (now dynamic via API)
├── transparansi.html       # Transparency/financial reports
├── inovasi.html            # Innovation/teaching methods (now dynamic via API)
├── kontak.html             # Contact page with form and details
├── css/
│   └── styles.css          # Single comprehensive CSS file
├── js/
│   └── script.js           # Single JavaScript file with all functionality
├── admin/                  # Backend dashboard (PHP + Tailwind CSS)
│   ├── config/            # Database configuration
│   ├── includes/          # Common functions, header, footer
│   ├── models/            # Database models (News, Academic, etc.)
│   ├── views/             # Admin page templates
│   ├── uploads/           # File upload directory
│   ├── index.php          # Main dashboard with statistics
│   ├── login.php          # Admin authentication
│   ├── news.php           # News CRUD management
│   └── README.md          # Backend setup instructions
├── api/                   # REST API endpoints for frontend
│   ├── news.php           # News API (list, featured, detail, categories)
│   ├── academic.php       # Academic programs API
│   ├── innovations.php    # Innovations API
│   └── contact.php        # Contact form handler
└── uploads/               # Media files uploaded via admin
```

### CSS Architecture
The `css/styles.css` file uses a modular approach with:
- **CSS Custom Properties** (CSS variables) for theming and consistency
- **Mobile-first responsive design** with breakpoints for tablet and desktop
- **CSS Grid and Flexbox** for layouts
- **Modern CSS features** like `backdrop-filter` and custom gradients
- **Component-based organization** (header, hero, features, footer, etc.)

Key CSS variables defined in `:root`:
- Color scheme with primary (`--primary-color: #6366f1`) and gradient variables
- Shadow utilities (`--shadow-sm` through `--shadow-xl`)
- Consistent spacing and typography scales

### JavaScript Architecture  
The `js/script.js` file contains modular functionality:
- **Mobile Navigation**: Hamburger menu toggle with animations
- **Scroll Effects**: Header background changes and parallax effects
- **Animation Systems**: Counter animations, fade-in on scroll, button ripple effects
- **Form Validation**: Reusable form validation with Indonesian-specific patterns
- **Utility Functions**: Toast notifications, lazy loading, back-to-top button

Key JavaScript patterns:
- Event delegation for better performance
- Intersection Observer API for scroll-triggered animations
- Modular functions that can be reused across pages
- CSS-in-JS for dynamic styling

### Page Structure Patterns
Each page follows a consistent structure:
1. **Header with Navigation** - Fixed header with responsive navigation
2. **Page Header Section** - Title, description, and breadcrumb navigation
3. **Main Content Sections** - Feature-specific content with consistent styling
4. **Footer** - Contact information and links

### Backend Architecture (Admin Dashboard)
The admin dashboard follows MVC pattern dengan PHP backend:
- **Models**: Database operations dengan PDO untuk security
- **Views**: Tailwind CSS untuk responsive UI
- **Controllers**: Business logic dan form handling
- **Authentication**: Session-based dengan role management
- **File Upload**: Secure file handling dengan validation
- **API Layer**: RESTful endpoints untuk frontend integration

Key features:
- **CRUD Operations**: Berita, Program Akademik, Inovasi, Informasi Umum
- **Media Management**: Upload dan organize files
- **Contact Messages**: Handle form submissions
- **Site Settings**: Configurable school information
- **User Management**: Multi-role admin access
- **Security**: CSRF protection, input sanitization, prepared statements

### Content Status
- **Static Pages**: `index.html`, `profil.html`, `transparansi.html`, `kontak.html`
- **Dynamic Pages**: `berita.html`, `akademik.html`, `info.html`, `inovasi.html` (connected to backend API)
- **Backend**: Full CRUD dashboard untuk content management

## Key Features & Functionality

### Responsive Design System
- **Breakpoints**: Mobile-first design with tablet (768px) and desktop (1024px) breakpoints
- **Grid Systems**: CSS Grid for complex layouts, Flexbox for component alignment
- **Typography Scale**: Uses Poppins font family with consistent weight hierarchy

### Interactive Components
- **Navigation**: Dropdown menus, mobile hamburger menu with smooth animations
- **Forms**: Contact form with real-time validation and error handling
- **Animations**: Scroll-triggered animations, counter animations, button hover effects
- **Accessibility**: Semantic HTML, proper ARIA labels, keyboard navigation support

### Indonesian Localization
- All content in Bahasa Indonesia
- Indonesian phone number validation pattern: `^(\+62|62|0)[0-9]{9,13}$`
- Indonesian educational terminology and cultural context
- Currency formatting in Indonesian Rupiah (Rp)

## Development Guidelines

### Adding New Pages
1. Copy the structure from `profil.html` as a template
2. Update the `<title>` and `<h1>` elements
3. Update navigation active states (`nav-link active`)
4. Follow the consistent section structure pattern
5. Add the page to footer navigation if needed

### Styling New Components
1. Use existing CSS custom properties for colors and spacing
2. Follow the existing naming conventions (BEM-like approach)
3. Ensure mobile-first responsive design
4. Test across different screen sizes
5. Use consistent box-shadow and border-radius values

### JavaScript Enhancements
- All interactive functionality is in `js/script.js`
- Use existing utility functions when possible (`showToast`, `validateForm`)
- Follow the established event handling patterns
- Test animations on different devices and browsers
- Maintain accessibility considerations

### Form Development
- Use the contact form as a reference for validation patterns
- Indonesian phone and email validation is already implemented
- Error messages are in Indonesian
- Form styling follows the established design system

## Browser Compatibility
- Modern browsers (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)
- Uses modern CSS features (CSS Grid, Custom Properties, `backdrop-filter`)
- JavaScript uses ES6+ features (arrow functions, const/let, template literals)
- Graceful degradation for older browsers where applicable

## Backend Development Guidelines

### Database Operations
- Always use prepared statements (implemented in models)
- Follow the established model patterns in `admin/models/`
- Use transactions for multi-table operations
- Implement proper error handling dan logging

### Security Best Practices
- Validate dan sanitize all inputs using `sanitizeInput()`
- Use CSRF tokens untuk forms: `generateCSRFToken()` dan `validateCSRFToken()`
- Implement role-based access control
- Hash passwords dengan `password_hash()` dan `password_verify()`
- Escape output dengan `htmlspecialchars()`

### API Development
- Follow RESTful conventions
- Return consistent JSON responses
- Implement proper HTTP status codes
- Add CORS headers untuk frontend access
- Validate request parameters

### File Management
- Use `uploadFile()` function untuk secure uploads
- Implement file type validation
- Generate unique filenames dengan timestamp
- Store files outside web root when possible

### Adding New CRUD Modules
1. Create database table dengan proper indexes
2. Build model class extending base patterns
3. Create controller dengan CRUD operations
4. Design responsive views dengan Tailwind CSS
5. Add navigation links in `includes/header.php`
6. Create corresponding API endpoint
7. Update frontend to consume API

## Performance Considerations
- Single CSS and JS files to minimize HTTP requests
- Optimized animations using CSS transforms and opacity
- Intersection Observer for efficient scroll animations
- Lazy loading implementation ready for images
- Minimal external dependencies (Font Awesome and Google Fonts only)
- Database query optimization dengan proper indexing
- File caching untuk frequently accessed data
- Image optimization before upload
