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

## Tech Stack

- **Frontend**: React + Vite
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
npm run build
```

The production-ready files will be in the `dist` folder.

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

The contact form is ready to connect to a backend. The server folder contains a basic Express setup:

1. Navigate to the server directory:
```bash
cd server
```

2. Install dependencies:
```bash
npm install
```

3. Create a `.env` file with your email credentials:
```
EMAIL_USER=your-email@gmail.com
EMAIL_PASS=your-app-password
PORT=3001
```

4. Update [server/server.js](server/server.js) to enable email sending (uncomment the email code)

5. Update [client/src/components/Contact.jsx](client/src/components/Contact.jsx) to use the API endpoint

6. Start the server:
```bash
npm start
```

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
