# NetFlow Analyzer

Real-time network traffic monitoring and analysis application by MonetX.

## Features

- Real-time NetFlow v5/v9/IPFIX traffic monitoring
- Device inventory management with SNMP & SSH configuration
- Deep Packet Inspection (DPI) for application identification
- Traffic analysis with protocol/application breakdown
- Geolocation mapping with MaxMind GeoIP2
- Alarm management and alerting
- Professional PDF report generation
- User authentication with Sanctum API tokens
- WebSocket real-time updates

## System Requirements

- Docker & Docker Compose v2.0+
- Linux server (Ubuntu 22.04+ recommended)
- Minimum 4GB RAM, 2 CPU cores
- 20GB+ disk space for traffic data
- Open ports: 8003 (web), 2055 (NetFlow UDP), 8080 (WebSocket)

---

## Production Deployment Guide

### Step 1: Clone Repository

```bash
sudo mkdir -p /opt/netflow-analyzer
cd /opt/netflow-analyzer
git clone <repository-url> .
```

### Step 2: Configure Environment

```bash
# Copy example environment file
cp .env.docker.example .env.docker

# Edit with your production settings
nano .env.docker
```

**Required configuration in `.env.docker`:**

```bash
# Application (REQUIRED)
APP_URL=http://YOUR_SERVER_IP:8003
APP_KEY=base64:your-generated-key-here

# Database (REQUIRED - use strong password)
DB_PASSWORD=your_very_secure_database_password

# Admin User (REQUIRED - for initial setup)
ADMIN_EMAIL=admin@your-company.com
ADMIN_PASSWORD=your_secure_admin_password

# WebSocket (OPTIONAL - generate random values for production)
REVERB_APP_KEY=your-random-key-here
REVERB_APP_SECRET=your-random-secret-here
```

**Generate secure keys:**
```bash
# Generate APP_KEY
echo "base64:$(openssl rand -base64 32)"

# Generate REVERB keys
openssl rand -hex 16  # For REVERB_APP_KEY
openssl rand -hex 32  # For REVERB_APP_SECRET
```

### Step 3: Configure Firewall

```bash
# Allow required ports
sudo ufw allow 8003/tcp    # Web interface
sudo ufw allow 2055/udp    # NetFlow data
sudo ufw allow 8080/tcp    # WebSocket (optional)
```

### Step 4: Build and Start

```bash
# Build and start all services
docker compose --env-file .env.docker up -d --build

# Check status
docker compose --env-file .env.docker ps

# View logs
docker compose --env-file .env.docker logs -f
```

### Step 5: Access Application

1. Open browser: `http://YOUR_SERVER_IP:8003`
2. Login with credentials from your `.env.docker`:
   - Email: `ADMIN_EMAIL` value
   - Password: `ADMIN_PASSWORD` value
3. **IMPORTANT:** Change your password after first login!

### Step 6: Configure NetFlow Collection

After login, go to **Settings** and configure:
- **Collector IP Address**: Your server's IP (that devices will send NetFlow to)
- **NetFlow Port**: 2055 (default)
- **Retention Days**: How long to keep flow data (default: 7 days)

### Step 7: Configure Network Devices

Point your network devices to send NetFlow/sFlow/IPFIX to:
- **Destination**: Your server's IP address
- **Port**: 2055/UDP

Example Cisco IOS configuration:
```
flow exporter NETFLOW-EXPORT
 destination YOUR_SERVER_IP
 transport udp 2055
 export-protocol netflow-v9
```

---

## Post-Installation

### Enable GeoIP (Optional)

For geolocation features, obtain a MaxMind license key:
1. Register at https://www.maxmind.com/en/geolite2/signup
2. Generate a license key
3. Add to `.env.docker`: `MAXMIND_LICENSE_KEY=your-key`
4. Restart: `docker compose --env-file .env.docker restart`

### Configure Email (Optional)

For password reset functionality, configure SMTP in `.env.docker`:
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your-smtp-user
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

---

## Common Commands

```bash
# View all logs
docker compose --env-file .env.docker logs -f

# View specific service logs
docker compose --env-file .env.docker logs -f netflow-app
docker compose --env-file .env.docker logs -f netflow-db

# Restart services
docker compose --env-file .env.docker restart

# Stop all services
docker compose --env-file .env.docker down

# Rebuild and restart
docker compose --env-file .env.docker up -d --build

# Run Laravel commands
docker exec -it netflow_analyzer_app php artisan migrate
docker exec -it netflow_analyzer_app php artisan cache:clear
docker exec -it netflow_analyzer_app php artisan config:clear

# Enter container shell
docker exec -it netflow_analyzer_app bash

# Database backup
docker exec netflow_analyzer_db pg_dump -U netflow_user netflow_traffic_analyzer > backup.sql

# Database restore
docker exec -i netflow_analyzer_db psql -U netflow_user netflow_traffic_analyzer < backup.sql
```

---

## Updating

```bash
cd /opt/netflow-analyzer

# Pull latest changes
git pull

# Rebuild and restart
docker compose --env-file .env.docker up -d --build

# Run any new migrations
docker exec -it netflow_analyzer_app php artisan migrate --force

# Clear caches
docker exec -it netflow_analyzer_app php artisan optimize:clear
```

---

## Troubleshooting

### Container Not Starting

```bash
# Check logs
docker compose --env-file .env.docker logs netflow-app

# Common issues:
# - Missing APP_KEY: Will be auto-generated on first run
# - Database connection: Check DB_PASSWORD matches in both services
# - Port conflict: Check if ports 8003, 2055, 5433 are available
```

### Database Issues

```bash
# Check database logs
docker compose --env-file .env.docker logs netflow-db

# Reset database (WARNING: destroys all data)
docker compose --env-file .env.docker down -v
docker compose --env-file .env.docker up -d --build
```

### No NetFlow Data

1. Verify UDP port 2055 is open: `sudo netstat -ulnp | grep 2055`
2. Check NetFlow listener: `docker logs netflow_analyzer_app | grep -i netflow`
3. Test with packet capture: `sudo tcpdump -i any port 2055`
4. Verify device is sending data to correct IP/port

### Performance Issues

```bash
# Check resource usage
docker stats

# For large deployments, increase PostgreSQL memory:
# Add to netflow-db environment in docker-compose.yml:
# - POSTGRES_SHARED_BUFFERS=256MB
# - POSTGRES_WORK_MEM=64MB
```

---

## Security Best Practices

1. **Change default passwords** immediately after installation
2. **Use HTTPS** in production (configure reverse proxy like nginx/traefik)
3. **Restrict network access** to management ports (8003, 8080)
4. **Regular backups** of database
5. **Keep system updated** with latest security patches

---

## Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Docker Network                        │
├─────────────────────────────────────────────────────────┤
│  ┌──────────────────┐    ┌──────────────────┐          │
│  │   netflow-app    │    │   netflow-db     │          │
│  │   (PHP 8.2-FPM)  │◄───│   (PostgreSQL)   │          │
│  │   + Nginx        │    │                  │          │
│  │   + Supervisor   │    │                  │          │
│  └────────┬─────────┘    └──────────────────┘          │
│           │                                             │
│  Ports:   │                                             │
│  - 80 (HTTP)                                            │
│  - 2055/UDP (NetFlow)                                   │
│  - 8080 (WebSocket)                                     │
└───────────┴─────────────────────────────────────────────┘
```

---

## Support

For enterprise support, contact your MonetX representative.
