# NetFlow Analyzer - DevOps Deployment Guide

This guide provides detailed instructions for deploying the NetFlow Analyzer application to production.

## Prerequisites

- Ubuntu 22.04 LTS (or any Linux with Docker support)
- Docker Engine 24.0+
- Docker Compose v2.0+
- Git
- Minimum 4GB RAM
- Root or sudo access

---

## Server Preparation

### 1. Install Docker

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
sudo apt install -y docker.io docker-compose-v2

# Enable and start Docker
sudo systemctl enable docker
sudo systemctl start docker

# Add user to docker group (optional, for non-root access)
sudo usermod -aG docker $USER
```

### 2. Install Git

```bash
sudo apt install -y git
```

### 3. Configure Firewall

```bash
# Allow web access
sudo ufw allow 8003/tcp

# Allow NetFlow/sFlow/IPFIX UDP traffic
sudo ufw allow 2055/udp

# If using SSH
sudo ufw allow 22/tcp

# Enable firewall
sudo ufw enable
```

---

## Deployment Steps

### Step 1: Clone Repository

```bash
sudo mkdir -p /opt/netflow-analyzer
cd /opt/netflow-analyzer
sudo git clone <REPOSITORY_URL> .
sudo chown -R $USER:$USER /opt/netflow-analyzer
```

### Step 2: Configure Environment

```bash
# Copy the example environment file
cp .env.docker.example .env.docker

# Edit configuration
nano .env.docker
```

**Required Configuration:**

| Variable | Description | Example |
|----------|-------------|---------|
| `APP_URL` | Full URL to access the application | `http://10.1.1.100:8003` |
| `APP_KEY` | Laravel application key (generate below) | `base64:abc123...` |
| `DB_PASSWORD` | Secure database password | `MySecureP@ssw0rd!` |

**Generate APP_KEY:**

```bash
# Generate a random key
openssl rand -base64 32

# Add "base64:" prefix to the result
# Example: APP_KEY=base64:xYz123AbC456...
```

**Optional Mail Configuration (for password reset):**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### Step 3: Build and Start

```bash
cd /opt/netflow-analyzer
docker compose --env-file .env.docker up -d --build
```

Wait for the build to complete (first build takes 3-5 minutes).

### Step 4: Verify Containers

```bash
docker ps
```

Expected output:
```
NAMES                  STATUS                  PORTS
netflow_analyzer_app   Up X minutes            0.0.0.0:8003->80/tcp, 0.0.0.0:2055->2055/udp
netflow_analyzer_db    Up X minutes (healthy)  0.0.0.0:5433->5432/tcp
```

### Step 5: Create Admin User

```bash
docker exec -it netflow_analyzer_app php artisan tinker
```

In the tinker console:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@yourcompany.com',
    'password' => bcrypt('YourSecurePassword123!')
]);
```

Type `exit` to quit tinker.

### Step 6: Access Application

Open browser: `http://YOUR_SERVER_IP:8003`

Login with the admin credentials you created.

---

## Post-Deployment Configuration

After logging in, navigate to **Settings** to configure:

1. **Collector IP Address** - The IP address of this server (where devices will send NetFlow data)
2. **NetFlow Port** - UDP port for NetFlow (default: 2055)
3. **sFlow Port** - UDP port for sFlow (if used)
4. **IPFIX Port** - UDP port for IPFIX (if used)
5. **Data Retention** - How long to keep flow data

---

## Management Commands

### View Logs

```bash
# All containers
docker compose --env-file .env.docker logs -f

# Application only
docker compose --env-file .env.docker logs -f netflow-app

# Database only
docker compose --env-file .env.docker logs -f netflow-db
```

### Restart Services

```bash
docker compose --env-file .env.docker restart
```

### Stop Services

```bash
docker compose --env-file .env.docker down
```

### Rebuild (after code updates)

```bash
docker compose --env-file .env.docker up -d --build
```

### Run Database Migrations

```bash
docker exec -it netflow_analyzer_app php artisan migrate --force
```

### Clear Application Cache

```bash
docker exec -it netflow_analyzer_app php artisan cache:clear
docker exec -it netflow_analyzer_app php artisan config:clear
docker exec -it netflow_analyzer_app php artisan view:clear
```

### Enter Container Shell

```bash
docker exec -it netflow_analyzer_app bash
```

---

## Updating the Application

```bash
cd /opt/netflow-analyzer

# Pull latest code
git pull origin main

# Rebuild and restart
docker compose --env-file .env.docker up -d --build

# Run migrations
docker exec -it netflow_analyzer_app php artisan migrate --force

# Clear caches
docker exec -it netflow_analyzer_app php artisan cache:clear
```

---

## Backup and Restore

### Backup Database

```bash
docker exec netflow_analyzer_db pg_dump -U netflow_user netflow_traffic_analyzer > backup_$(date +%Y%m%d).sql
```

### Restore Database

```bash
cat backup_20241216.sql | docker exec -i netflow_analyzer_db psql -U netflow_user netflow_traffic_analyzer
```

### Backup Entire Application

```bash
# Stop containers
docker compose --env-file .env.docker down

# Backup
tar -czvf netflow-backup-$(date +%Y%m%d).tar.gz /opt/netflow-analyzer

# Restart
docker compose --env-file .env.docker up -d
```

---

## Troubleshooting

### Container Won't Start

```bash
# Check logs
docker compose --env-file .env.docker logs netflow-app

# Common issues:
# - Missing APP_KEY
# - Invalid DB_PASSWORD
# - Port already in use
```

### Database Connection Issues

```bash
# Check database container
docker compose --env-file .env.docker logs netflow-db

# Test connection
docker exec -it netflow_analyzer_db psql -U netflow_user -d netflow_traffic_analyzer
```

### Application Errors

```bash
# Check Laravel logs
docker exec -it netflow_analyzer_app cat storage/logs/laravel.log | tail -100
```

### Reset Everything

```bash
# WARNING: This deletes all data!
docker compose --env-file .env.docker down -v
docker compose --env-file .env.docker up -d --build
```

---

## Security Recommendations

1. **Change default ports** if exposed to internet
2. **Use HTTPS** with a reverse proxy (nginx/traefik)
3. **Strong passwords** for database and admin accounts
4. **Regular backups** of database
5. **Firewall rules** to restrict access
6. **Keep updated** with latest releases

---

## Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Docker Host                           │
│  ┌─────────────────┐       ┌─────────────────┐         │
│  │  netflow-app    │       │   netflow-db    │         │
│  │  (PHP/Nginx)    │──────▶│  (PostgreSQL)   │         │
│  │  Port: 8003     │       │  Port: 5433     │         │
│  │  UDP: 2055      │       │                 │         │
│  └─────────────────┘       └─────────────────┘         │
│           │                                             │
│           ▼                                             │
│    ┌─────────────┐                                     │
│    │  Volumes    │                                     │
│    │  - storage  │                                     │
│    │  - db_data  │                                     │
│    └─────────────┘                                     │
└─────────────────────────────────────────────────────────┘
```

---

## Support

For issues or questions, contact your MonetX representative.
