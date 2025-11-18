# Portfolio Website

A modern, professional portfolio website built with React, Tailwind CSS, and Framer Motion.

## Features

- **Responsive Design**: Works seamlessly on all devices
- **Smooth Animations**: Beautiful scroll animations and transitions using Framer Motion
- **Modern UI**: Clean, minimal, and professional design with Tailwind CSS
- **Sections Included**:
  - Hero section with call-to-action buttons
  - About Me section
  - Skills & Technologies showcase
  - Featured Projects with hover effects
  - Contact form (ready for backend integration)
  - Footer with social links

## Project Structure

```
portfolio/
├── client/              # React frontend (Vite + Tailwind CSS)
├── server/              # PHP REST API backend
├── .github/workflows/   # CI/CD deployment
└── docker-compose.yml   # Docker orchestration
```

## Tech Stack

- **Frontend**: React + Vite (in `client/` folder)
- **Backend**: PHP REST API (in `server/` folder)
- **Styling**: Tailwind CSS
- **Animations**: Framer Motion
- **Icons**: SVG icons

## Getting Started

### Prerequisites

- Node.js (v16 or higher)
- npm or yarn

### Installation

1. Navigate to the client directory:
```bash
cd client
```

2. Install dependencies:
```bash
npm install
```

3. Start the development server:
```bash
npm run dev
```

4. Open your browser and visit: `http://localhost:5173`

### Build for Production

```bash
cd client
npm run build
```

The production-ready files will be in the `client/dist` folder.

## Customization

### Easy Content Updates with JSON

All portfolio content is centralized in a single JSON file for easy updates:

**Edit [client/src/data/portfolio.json](client/src/data/portfolio.json)** to customize:

1. **Personal Information**:
   - `name`: Your full name
   - `title`: Your job title/role
   - `tagline`: Your professional tagline
   - `email`: Your contact email
   - `location`: Your city/country
   - `initials`: Your initials for the About section avatar

2. **About Section**:
   - `heading`: About section heading
   - `paragraphs`: Array of paragraphs describing yourself
   - `traits`: Array of your professional traits/qualities

3. **Skills**:
   - Array of skill categories (Frontend, Backend, Tools, etc.)
   - Each category has a list of skills/technologies

4. **Projects**:
   - Array of your projects with:
     - `title`: Project name
     - `description`: Brief description
     - `tech`: Array of technologies used
     - `github`: GitHub repository URL
     - `demo`: Live demo URL

5. **Social Links**:
   - Array of social media links (GitHub, LinkedIn, Twitter)
   - Update the `url` for each platform

**Example:**
```json
{
  "personal": {
    "name": "John Doe",
    "title": "Full Stack Developer",
    "email": "john@example.com"
  }
}
```

Simply update the JSON file and your changes will automatically reflect across all components!

### Colors

The primary color is blue. To change it, update the color classes in the components:
- `bg-blue-600` → `bg-[your-color]-600`
- `text-blue-600` → `text-[your-color]-600`
- etc.

## SEO Optimization

This portfolio is fully optimized for search engines with:

### Included SEO Features:

1. **Meta Tags**:
   - Title, description, keywords
   - Open Graph tags for Facebook/LinkedIn
   - Twitter Card tags
   - Robots and canonical tags

2. **Structured Data (JSON-LD)**:
   - Person schema (your profile)
   - Portfolio/Creative Work schema
   - Website schema
   - Helps Google understand your content

3. **Technical SEO**:
   - Sitemap.xml
   - Robots.txt
   - Semantic HTML structure
   - Mobile-responsive design
   - Fast loading times

### Update SEO Settings:

Edit the `seo` section in [client/src/data/portfolio.json](client/src/data/portfolio.json):

```json
"seo": {
  "title": "Your Name - Full Stack Developer Portfolio",
  "description": "Full Stack Developer specializing in React, Node.js...",
  "keywords": "full stack developer, react, node.js, portfolio",
  "siteUrl": "https://yourwebsite.com",
  "image": "https://yourwebsite.com/og-image.jpg",
  "twitterHandle": "@yourusername"
}
```

**Important**:
- Update `siteUrl` with your actual domain
- Create an OG image (1200x630px) and update the `image` URL
- Update `sitemap.xml` with your domain after deployment

### SEO Best Practices:

1. **After Deployment**:
   - Submit your sitemap to Google Search Console
   - Verify your site with Google Search Console
   - Submit to Bing Webmaster Tools

2. **Create OG Image**:
   - Size: 1200x630px
   - Include your name and title
   - Place in `public/` folder

3. **Regular Updates**:
   - Update `lastmod` in sitemap.xml when you update content
   - Keep your projects and skills current

## Backend Integration

The contact form is ready to connect to a backend. The server folder contains a PHP REST API:

### Running the Server Locally

1. Navigate to the server directory:
```bash
cd server
```

2. Start the PHP development server with the router:
```bash
php -S localhost:8000 router.php
```

**Important:** You must use `router.php` to properly handle API routing and CORS headers.

3. Test the API:
```bash
# Health check
curl http://localhost:8000/api/health

# Test contact form
curl -X POST http://localhost:8000/api/contact \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@example.com","message":"Hello"}'
```

4. The client is already configured to use `http://localhost:8000/api/contact` in development

### Email Configuration (Optional)

By default, the server uses PHP's `mail()` function. For better email deliverability:

1. Copy `.env.example` to `.env` in the server folder
2. Configure SMTP settings (Gmail or Hostinger email)
3. Update [server/config/config.php](server/config/config.php) if needed

## Deployment

### Deploy Frontend (Vercel/Netlify)

1. Build the project:
```bash
cd client
npm run build
```

2. Deploy the `dist` folder to your preferred hosting service

### Deploy Backend (Render/Railway)

1. Push your code to GitHub
2. Connect your repository to Render/Railway
3. Set environment variables
4. Deploy!

## License

MIT License - feel free to use this for your own portfolio!

## Support

If you have any questions or need help customizing your portfolio, feel free to reach out!
