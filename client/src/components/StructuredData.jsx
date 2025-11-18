import { useEffect } from 'react';
import portfolioData from '../data/portfolio.json';

const StructuredData = () => {
  const { seo, personal, skills, projects } = portfolioData;

  useEffect(() => {
    // Person schema
    const personSchema = {
      '@context': 'https://schema.org',
      '@type': 'Person',
      name: personal.name,
      jobTitle: personal.title,
      email: personal.email,
      url: seo.siteUrl,
      address: {
        '@type': 'PostalAddress',
        addressLocality: personal.location
      },
      sameAs: portfolioData.social.map(s => s.url),
      knowsAbout: skills.flatMap(category => category.items)
    };

    // Portfolio schema
    const portfolioSchema = {
      '@context': 'https://schema.org',
      '@type': 'ItemList',
      itemListElement: projects.map((project, index) => ({
        '@type': 'ListItem',
        position: index + 1,
        item: {
          '@type': 'CreativeWork',
          name: project.title,
          description: project.description,
          url: project.demo,
          author: {
            '@type': 'Person',
            name: personal.name
          }
        }
      }))
    };

    // Website schema
    const websiteSchema = {
      '@context': 'https://schema.org',
      '@type': 'WebSite',
      name: seo.title,
      description: seo.description,
      url: seo.siteUrl,
      author: {
        '@type': 'Person',
        name: personal.name
      }
    };

    // Insert or update script tags
    const updateStructuredData = (id, data) => {
      let script = document.getElementById(id);
      if (!script) {
        script = document.createElement('script');
        script.setAttribute('id', id);
        script.setAttribute('type', 'application/ld+json');
        document.head.appendChild(script);
      }
      script.textContent = JSON.stringify(data);
    };

    updateStructuredData('person-schema', personSchema);
    updateStructuredData('portfolio-schema', portfolioSchema);
    updateStructuredData('website-schema', websiteSchema);

    // Cleanup function
    return () => {
      ['person-schema', 'portfolio-schema', 'website-schema'].forEach(id => {
        const script = document.getElementById(id);
        if (script) {
          script.remove();
        }
      });
    };
  }, [seo, personal, skills, projects]);

  return null;
};

export default StructuredData;
