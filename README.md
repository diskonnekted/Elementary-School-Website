# Elementary School Website

A modern, responsive website system for elementary schools with comprehensive content management features and integrity-focused design.

## Features

### Frontend
- Modern responsive design with Tailwind CSS
- Dynamic content rendering from database
- News/articles system with categories
- Academic programs showcase
- Innovation and school information pages
- Contact form with validation
- Multi-page navigation system

### Backend Administration
- Secure admin panel with role-based access
- User management (Super Admin, Admin, Teacher, Demo)
- News/content management system
- Academic programs management
- Innovation showcase management
- School settings configuration
- File upload and media management
- Contact messages management
- Activity logging and monitoring

### Key Capabilities
- Dynamic school information (name, address, contact) across all pages
- Centralized settings management
- Database-driven content
- Image upload and management
- Search and filtering functionality
- Mobile-responsive design
- Multi-user system with different permission levels

## Technology Stack

- **Frontend**: HTML5, CSS3, Tailwind CSS, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Server**: Apache/Nginx
- **Dependencies**: Font Awesome, Google Fonts

## Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.3+
- Apache or Nginx web server
- mod_rewrite enabled (for Apache)

### Setup Steps

1. Clone the repository:
```bash
git clone https://github.com/diskonnekted/Elementary-School-Website.git
cd Elementary-School-Website
```

2. Configure database:
   - Create a new MySQL database
   - Import the database schema from `admin/config/database_setup.sql`
   - Update database credentials in `admin/config/database.php`

3. Set up file permissions:
```bash
chmod 755 admin/uploads/
chmod 755 admin/uploads/news/
chmod 755 admin/uploads/academic/
chmod 755 admin/uploads/innovations/
```

4. Configure web server:
   - Point document root to the project directory
   - Ensure mod_rewrite is enabled (Apache)
   - Configure virtual host if needed

5. Initialize settings:
   - Access `/admin/setup_settings.php` to create initial school settings
   - Or manually run the setup scripts in the admin directory

## Configuration

### Database Configuration
Update `admin/config/database.php` with your database credentials:

```php
private $host = "localhost";
private $db_name = "your_database_name";
private $username = "your_username"; 
private $password = "your_password";
```

### Initial Admin User
Create an initial admin user through the setup script or by accessing the admin panel setup page.

## Usage

### Admin Panel Access
Navigate to `/admin/` and login with your admin credentials.

**Default Demo Credentials:**
- Username: demo
- Password: demo123

### Managing Content

#### School Settings
1. Go to Admin Panel > Settings
2. Update school name, address, contact information
3. Upload school logo and principal photo
4. Configure social media links
5. Save settings - changes reflect immediately on frontend

#### News Management
1. Navigate to Admin Panel > News
2. Create, edit, or delete news articles
3. Set categories (General, Achievement, Activity, Announcement)
4. Upload featured images
5. Set publish status and scheduling

#### Academic Programs
1. Access Admin Panel > Academic Programs
2. Add program details, descriptions, and images
3. Organize by categories or years
4. Manage program visibility and ordering

#### User Management
Available for Admin and Super Admin roles:
1. Go to Admin Panel > User Management
2. Create new users with appropriate roles
3. Manage user status and permissions
4. Reset passwords and update profiles

### Frontend Navigation
- **Home**: Main landing page with school overview
- **Profile**: School information, vision, mission, values
- **News**: Latest school news and announcements
- **Academic**: Educational programs and curriculum
- **Information**: General school information and policies
- **Transparency**: School transparency and public information
- **Innovation**: Educational innovations and achievements
- **Contact**: Contact information and inquiry form

## File Structure

```
/
├── admin/                    # Admin panel
│   ├── config/              # Database and configuration files
│   ├── includes/            # Shared admin components
│   ├── models/              # Database models
│   ├── uploads/             # File uploads directory
│   └── *.php               # Admin pages
├── includes/                # Shared frontend components
├── css/                     # Stylesheets
├── js/                      # JavaScript files
├── images/                  # Static images
├── api/                     # API endpoints
├── *.php                   # Frontend pages
└── README.md               # This file
```

## Database Schema

Main tables:
- `users` - User accounts and authentication
- `news` - News articles and announcements
- `academic_programs` - Educational programs
- `innovations` - School innovations showcase
- `school_settings` - Dynamic school configuration
- `contact_messages` - Contact form submissions
- `general_info` - General information pages

## Security Features

- SQL injection prevention with prepared statements
- XSS protection with input sanitization
- CSRF token validation
- Role-based access control
- Password hashing with PHP password_hash()
- File upload validation and restrictions
- Session security and timeout management

## Development

### Adding New Content Types
1. Create database table for the content type
2. Create model class in `admin/models/`
3. Add admin pages for management
4. Create frontend display pages
5. Update navigation and permissions

### Customizing Design
- Modify Tailwind CSS classes in templates
- Add custom CSS in `css/styles.css`
- Update color scheme in Tailwind config
- Customize layout in include files

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

Please ensure your code follows the existing style and includes appropriate comments.

## License

This project is open source and available under the MIT License.

## Support

For issues and questions:
1. Check the documentation
2. Search existing issues on GitHub
3. Create a new issue with detailed description
4. Include system information and error messages

## Changelog

### Recent Updates
- Added dynamic settings system for school information
- Implemented role-based user management
- Enhanced security features
- Improved responsive design
- Added content management capabilities
- Integrated file upload system

## Credits

Developed for elementary schools to provide a modern, manageable web presence with focus on educational content and administrative efficiency.
