import { useEffect } from 'react';
import portfolioData from '../data/portfolio.json';

const SEO = () => {
  const { seo, personal } = portfolioData;

  useEffect(() => {
    // Update document title
    document.title = seo.title;

    // Update meta tags
    const updateMetaTag = (name, content, attribute = 'name') => {
      let element = document.querySelector(`meta[${attribute}="${name}"]`);
      if (!element) {
        element = document.createElement('meta');
        element.setAttribute(attribute, name);
        document.head.appendChild(element);
      }
      element.setAttribute('content', content);
    };

    // Basic meta tags
    updateMetaTag('description', seo.description);
    updateMetaTag('keywords', seo.keywords);
    updateMetaTag('author', personal.name);

    // Open Graph tags for social media
    updateMetaTag('og:title', seo.title, 'property');
    updateMetaTag('og:description', seo.description, 'property');
    updateMetaTag('og:type', 'website', 'property');
    updateMetaTag('og:url', seo.siteUrl, 'property');
    updateMetaTag('og:image', seo.image, 'property');

    // Twitter Card tags
    updateMetaTag('twitter:card', 'summary_large_image');
    updateMetaTag('twitter:site', seo.twitterHandle);
    updateMetaTag('twitter:creator', seo.twitterHandle);
    updateMetaTag('twitter:title', seo.title);
    updateMetaTag('twitter:description', seo.description);
    updateMetaTag('twitter:image', seo.image);

    // Additional SEO tags
    updateMetaTag('robots', 'index, follow');
    updateMetaTag('language', 'English');
    updateMetaTag('revisit-after', '7 days');

    // Canonical link
    let canonical = document.querySelector('link[rel="canonical"]');
    if (!canonical) {
      canonical = document.createElement('link');
      canonical.setAttribute('rel', 'canonical');
      document.head.appendChild(canonical);
    }
    canonical.setAttribute('href', seo.siteUrl);
  }, [seo, personal.name]);

  return null;
};

export default SEO;
