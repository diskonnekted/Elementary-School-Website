# Favicon Documentation

## Design Overview

The website features a custom-designed favicon that represents elementary education and learning. The favicon uses a modern, clean design that scales well at all sizes.

### Design Elements

- **Graduation Cap**: Symbolizes achievement, learning goals, and educational excellence
- **Open Book**: Represents knowledge, education, and the foundation of learning
- **Color Scheme**: 
  - Blue gradient (#4F46E5 to #7C3AED) for the graduation cap
  - Green gradient (#10B981 to #059669) for the book
  - Golden tassel (#F59E0B) for accent
- **Background**: Clean white circle with subtle border for clarity

### Technical Implementation

#### Files Included

- `favicon.svg` - Primary SVG favicon (32x32, scalable)
- `favicon-16x16.png` - Small size for browser tabs
- `favicon-32x32.png` - Standard size for browser tabs
- `favicon-64x64.png` - Medium size for bookmarks
- `favicon-192x192.png` - Android home screen icon
- `favicon-512x512.png` - Large size for various applications
- `apple-touch-icon.png` - iOS home screen icon (180x180)
- `site.webmanifest` - Web App Manifest for PWA support
- `browserconfig.xml` - Windows tile configuration

#### Browser Support

- **Modern Browsers**: Uses SVG favicon for crisp display at any size
- **Legacy Browsers**: Falls back to PNG formats
- **Mobile Devices**: 
  - iOS: Apple Touch Icon (180x180)
  - Android: Various PNG sizes via manifest
- **Windows**: Tile icons for pinned sites

#### Implementation

All pages include favicon via `includes/favicon.php`:

```php
<?php include 'includes/favicon.php'; ?>
```

This includes all necessary `<link>` tags and metadata:

- Icon links for different formats and sizes
- Apple Touch Icon for iOS
- Web App Manifest reference
- Theme color metadata (#4F46E5)
- Browser configuration for Windows

### Usage

The favicon is automatically applied to:

- All frontend pages (homepage, profile, news, academic, etc.)
- Admin panel pages
- Login page
- Error pages

### Customization

To update the favicon:

1. Edit `images/favicon/favicon.svg` with your preferred design
2. Regenerate PNG files using the provided tools
3. Update theme colors in `includes/favicon.php` if needed
4. Test across different browsers and devices

### Design Principles

- **Simplicity**: Clean, minimal design that remains recognizable at small sizes
- **Relevance**: Elements directly relate to elementary education
- **Professionalism**: Appropriate for an educational institution
- **Scalability**: Vector-based design ensures quality at any size
- **Brand Consistency**: Colors align with website theme

The favicon serves as a visual identifier for the school website and enhances the professional appearance across all platforms and devices.
