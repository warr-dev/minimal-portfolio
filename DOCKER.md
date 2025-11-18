# Docker Deployment Guide

Complete Docker setup for the Warren Dalawampu Portfolio (Client + Server + Database).

## Overview

The portfolio is fully containerized with three services:
- **client**: React/Vite frontend (nginx)
- **server**: PHP REST API (Apache)
- **db**: MySQL database (optional)

## Quick Start

### Prerequisites
- Docker Desktop installed
- Docker Compose installed

### Start Everything

```bash
# From project root
docker-compose up -d
```

This will:
1. Build the React client
2. Build the PHP server
3. Start MySQL database
4. Create a network for all services

### Access the Application

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **API Health**: http://localhost:8000/api/health
- **Database**: localhost:3306

### Stop Everything

```bash
docker-compose down
```

### Stop and Remove Data

```bash
docker-compose down -v
```

## Individual Service Usage

### Client Only

```bash
cd client
docker build -t portfolio-client .
docker run -p 3000:80 portfolio-client
```

Access at: http://localhost:3000

### Server Only

```bash
cd server
docker build -t portfolio-server .
docker run -p 8000:80 portfolio-server
```

Access at: http://localhost:8000/api/health

## Docker Compose Services

### Client Service
- **Port**: 3000 → 80
- **Image**: nginx:alpine
- **Build**: Multi-stage (Node.js build + nginx serve)
- **Health Check**: Checks nginx is responding

### Server Service
- **Port**: 8000 → 80
- **Image**: php:8.2-apache
- **Volumes**: `./server/logs` (persisted)
- **Health Check**: Checks `/api/health` endpoint

### Database Service
- **Port**: 3306
- **Image**: mysql:8.0
- **Volume**: `db-data` (persisted)
- **Credentials**: See docker-compose.yml

## Development with Docker

### Live Development (Recommended)

For development, use native npm dev server instead of Docker:

```bash
# Client
cd client
npm run dev

# Server (PHP built-in)
cd server
php -S localhost:8000
```

### Production Build Testing

Test production builds locally with Docker:

```bash
docker-compose up --build
```

## Environment Variables

### Client (.env)
```bash
VITE_API_URL=http://localhost:8000/api/contact
```

### Server (.env)
```bash
DB_HOST=db
DB_NAME=portfolio_db
DB_USER=portfolio_user
DB_PASS=portfolio_password

SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your_email@gmail.com
SMTP_PASS=your_app_password
```

## Common Commands

### View Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f client
docker-compose logs -f server
docker-compose logs -f db
```

### Rebuild Services

```bash
# Rebuild all
docker-compose up --build

# Rebuild specific service
docker-compose up --build client
```

### Access Container Shell

```bash
# Client
docker exec -it portfolio-client sh

# Server
docker exec -it portfolio-server bash

# Database
docker exec -it portfolio-db mysql -u portfolio_user -p
```

### Check Service Health

```bash
docker-compose ps
```

## Volume Management

### View Volumes

```bash
docker volume ls
```

### Backup Database

```bash
docker exec portfolio-db mysqldump -u portfolio_user -pportfolio_password portfolio_db > backup.sql
```

### Restore Database

```bash
docker exec -i portfolio-db mysql -u portfolio_user -pportfolio_password portfolio_db < backup.sql
```

## Production Deployment

### Using Docker Compose on Server

1. **Copy files to server:**
   ```bash
   scp -P 65002 -r portfolio u102125202@151.106.124.61:~/
   ```

2. **SSH into server:**
   ```bash
   ssh -p 65002 u102125202@151.106.124.61
   ```

3. **Start services:**
   ```bash
   cd ~/portfolio
   docker-compose -f docker-compose.prod.yml up -d
   ```

### Using Pre-built Images

Build images locally and push to registry:

```bash
# Build and tag
docker build -t warrdev/portfolio-client:latest ./client
docker build -t warrdev/portfolio-server:latest ./server

# Push to Docker Hub
docker push warrdev/portfolio-client:latest
docker push warrdev/portfolio-server:latest

# Pull on server
docker pull warrdev/portfolio-client:latest
docker pull warrdev/portfolio-server:latest
```

## Nginx Configuration

The client uses a custom nginx.conf with:
- SPA routing (all routes → index.html)
- Gzip compression
- Static asset caching (1 year)
- Security headers
- Health check endpoint

## Apache Configuration

The server uses custom apache.conf with:
- Mod_rewrite enabled
- .htaccess support
- Security headers
- Error logging

## Troubleshooting

### Port Already in Use

```bash
# Find process using port 3000
lsof -i :3000

# Kill process
kill -9 <PID>

# Or change port in docker-compose.yml
```

### Build Fails

```bash
# Clear Docker cache
docker system prune -a

# Rebuild without cache
docker-compose build --no-cache
```

### Database Connection Issues

```bash
# Check if db service is running
docker-compose ps

# View db logs
docker-compose logs db

# Restart db service
docker-compose restart db
```

### Permission Issues (Logs)

```bash
# Fix log permissions
cd server
sudo chmod -R 755 logs
sudo chown -R $(whoami):$(whoami) logs
```

## Performance Optimization

### Multi-stage Build Benefits

**Client:**
- Stage 1: Node.js 22 builds React app (large)
- Stage 2: nginx serves static files (small)
- Final image: ~25MB (vs ~1GB without multi-stage)

**Server:**
- PHP 8.2 with only required extensions
- Apache pre-configured
- Final image: ~450MB

### Resource Limits

Add to docker-compose.yml:

```yaml
services:
  client:
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
```

## CI/CD Integration

### GitHub Actions Example

```yaml
- name: Build Docker Images
  run: |
    docker build -t portfolio-client ./client
    docker build -t portfolio-server ./server

- name: Test Images
  run: |
    docker run -d -p 3000:80 portfolio-client
    docker run -d -p 8000:80 portfolio-server
    sleep 5
    curl http://localhost:3000
    curl http://localhost:8000/api/health
```

## Security Best Practices

1. **Don't commit .env files** - Use .env.example
2. **Use secrets** - For production passwords
3. **Scan images** - `docker scan portfolio-client`
4. **Update base images** - Regularly rebuild
5. **Limit resources** - Prevent DoS
6. **Use non-root user** - In production Dockerfiles

## Monitoring

### Health Checks

Both services have built-in health checks:

```bash
# Check health status
docker inspect portfolio-client | grep Health -A 10
docker inspect portfolio-server | grep Health -A 10
```

### Metrics

```bash
# Resource usage
docker stats

# Specific service
docker stats portfolio-client
```

## Next Steps

1. Test locally: `docker-compose up`
2. Verify all services are healthy
3. Test contact form functionality
4. Review logs for errors
5. Optimize if needed

## Support

For issues:
- Check logs: `docker-compose logs`
- Review health: `docker-compose ps`
- Restart service: `docker-compose restart <service>`
- Rebuild: `docker-compose up --build`

---

**Docker Compose File:** [docker-compose.yml](docker-compose.yml)
**Client Dockerfile:** [client/Dockerfile](client/Dockerfile)
**Server Dockerfile:** [server/Dockerfile](server/Dockerfile)
