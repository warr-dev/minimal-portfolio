# Deployment Guide - Hostinger CI/CD

This guide will help you set up automatic deployment to Hostinger using GitHub Actions via SSH.

## Prerequisites

1. A Hostinger hosting account with SSH access
2. A GitHub repository for your portfolio
3. SSH access enabled on your Hostinger account

## Setup Instructions

### 1. Enable SSH Access on Hostinger

1. Log in to your Hostinger control panel (hPanel)
2. Go to **Advanced** → **SSH Access**
3. Enable SSH access if not already enabled
4. Note down the SSH details:
   - SSH Host/IP address
   - SSH Port (usually 22 or 65002)
   - SSH Username

### 2. Generate SSH Key Pair

On your local machine, generate an SSH key pair:

```bash
# Generate SSH key (don't add passphrase for CI/CD)
ssh-keygen -t ed25519 -C "github-actions@portfolio" -f ~/.ssh/hostinger_deploy

# This creates two files:
# ~/.ssh/hostinger_deploy (private key)
# ~/.ssh/hostinger_deploy.pub (public key)
```

### 3. Add Public Key to Hostinger

1. Copy the public key content:
   ```bash
   cat ~/.ssh/hostinger_deploy.pub
   ```

2. In Hostinger hPanel:
   - Go to **Advanced** → **SSH Access**
   - Click **Manage SSH Keys**
   - Add new SSH key and paste the public key content
   - Or add it to `~/.ssh/authorized_keys` on the server

### 4. Test SSH Connection

Test the connection from your local machine:

```bash
ssh -i ~/.ssh/hostinger_deploy -p PORT USERNAME@HOST
```

Replace PORT, USERNAME, and HOST with your actual SSH details.

### 5. Configure GitHub Secrets

1. Go to your GitHub repository
2. Navigate to **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret** and add the following secrets:

   - **SSH_PRIVATE_KEY**: Content of `~/.ssh/hostinger_deploy` (private key)
     ```bash
     cat ~/.ssh/hostinger_deploy
     # Copy everything including -----BEGIN and -----END lines
     ```

   - **SSH_HOST**: Your SSH host address (e.g., `123.456.789.0` or `ssh.yourdomain.com`)

   - **SSH_USER**: Your SSH username (e.g., `u123456789`)

   - **SSH_PORT**: SSH port number (e.g., `22` or `65002`)

   - **DEPLOY_PATH**: Path to your web directory (e.g., `/home/u123456789/public_html` or `~/public_html`)

### 6. Verify Deployment Path

SSH into your server and verify the correct path:

```bash
ssh -i ~/.ssh/hostinger_deploy -p PORT USERNAME@HOST
pwd  # See your home directory
ls -la public_html  # Verify public_html exists
```

### 7. Push to GitHub

```bash
git add .
git commit -m "Add CI/CD deployment"
git push origin main
```

The deployment will automatically trigger on every push to the `main` branch.

## Manual Deployment

To trigger deployment manually:

1. Go to **Actions** tab in your GitHub repository
2. Select **Deploy to Hostinger** workflow
3. Click **Run workflow**
4. Select branch and click **Run workflow**

## What Happens During Deployment

1. **Checkout code**: GitHub Actions pulls your latest code
2. **Setup Node.js**: Installs Node.js 20
3. **Install dependencies**: Runs `npm ci` in the client folder
4. **Build project**: Runs `npm run build` to create production build
5. **Deploy via SSH**: Securely transfers the `dist` folder contents to your Hostinger server using rsync over SSH

## Verify Deployment

After deployment completes:

1. Visit your domain (e.g., https://warrdev.tech)
2. Check that the latest changes are live
3. Test all sections and links
4. Verify SEO meta tags using browser dev tools

## Updating Portfolio Content

To update your portfolio:

1. Edit `client/src/data/portfolio.json`
2. Commit and push changes
3. Deployment happens automatically

## Important Files

- `.github/workflows/deploy.yml` - GitHub Actions workflow
- `client/vite.config.js` - Build configuration
- `client/public/.htaccess` - Server configuration (HTTPS redirect, caching, security headers)
- `client/public/sitemap.xml` - Update domain to match your actual domain
- `client/src/data/portfolio.json` - All portfolio content

## Post-Deployment Checklist

- [ ] Update `sitemap.xml` with your actual domain
- [ ] Create and upload OG image (1200x630px) for social sharing
- [ ] Test all navigation links
- [ ] Verify contact form (add backend endpoint)
- [ ] Test on mobile devices
- [ ] Check page load speed
- [ ] Verify SEO tags using Google Search Console

## Troubleshooting

### Build Fails
- Check GitHub Actions logs for errors
- Test build locally: `cd client && npm run build`
- Verify all dependencies are in package.json

### SSH Connection Fails
- Verify SSH credentials in GitHub Secrets
- Test SSH connection locally with the same credentials
- Check SSH port is correct (22 or 65002)
- Ensure SSH access is enabled in Hostinger hPanel
- Verify public key is added to server's authorized_keys

### SSH Key Permission Issues
- Make sure private key includes BEGIN/END lines
- Check no extra spaces or newlines in the key
- Verify key format is correct (ed25519 or rsa)

### Site Not Loading
- Check .htaccess file is uploaded
- Verify files are in correct directory (public_html)
- Check Hostinger error logs in hPanel

### Changes Not Visible
- Clear browser cache (Ctrl+Shift+R)
- Check deployment completed successfully in Actions tab
- Verify correct files uploaded via Hostinger File Manager

## Alternative Deployment Methods

### Manual SSH Deployment

If you prefer manual deployment via SSH:

```bash
# Build the project
cd client
npm run build

# Deploy via rsync over SSH
rsync -avz -e "ssh -p PORT -i ~/.ssh/hostinger_deploy" \
  ./dist/ USERNAME@HOST:/home/USERNAME/public_html/
```

### Manual FTP/File Manager

Or upload via Hostinger File Manager:
1. Build locally: `cd client && npm run build`
2. Go to Hostinger hPanel → Files → File Manager
3. Navigate to `public_html`
4. Upload contents of `client/dist/` folder

## Hostinger-Specific Notes

- **SSH Access**: Available on most Hostinger plans (Business, Premium, some shared hosting)
- **SSH Port**: Usually port 65002 (check your hPanel)
- **Directory**: Most Hostinger accounts use `public_html` as the web root
- **HTTPS**: Hostinger provides free SSL certificates (enable in hPanel)
- **Performance**: Enable Cloudflare integration in hPanel for better performance
- **Caching**: The .htaccess file includes browser caching rules
- **Domain**: Update DNS if using custom domain (A record or CNAME)

## Security Recommendations

1. **Never commit private keys** to repository - use GitHub Secrets only
2. **Use SSH keys without passphrase** for CI/CD automation
3. **Enable two-factor authentication** on both GitHub and Hostinger
4. **Rotate SSH keys periodically** (every 6-12 months)
5. **Regularly update dependencies**: `npm update`
6. **Monitor GitHub Actions logs** for unauthorized access attempts
7. **Limit SSH key permissions** - create deployment-specific keys

## Support

For issues:
- GitHub Actions: Check Actions tab logs
- Hostinger: Contact Hostinger support via live chat
- Build errors: Check browser console and network tab
