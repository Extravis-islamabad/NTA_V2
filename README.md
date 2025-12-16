# NetFlow Analyzer

Real-time network traffic monitoring and analysis application by MonetX.

## Features

- Real-time NetFlow/sFlow/IPFIX traffic monitoring
- Device inventory management
- Traffic analysis and reporting
- Alarm management
- PDF report generation
- SSH device configuration

## Requirements

- Docker & Docker Compose
- Ubuntu 22.04 (recommended)
- 4GB RAM minimum
- PostgreSQL 16

---

## Deployment Guide

### Step 1: Clone Repository

```bash
# Create app directory
sudo mkdir -p /opt/netflow-analyzer
cd /opt/netflow-analyzer

# Clone repository
git clone https://github.com/YOUR_ORG/netflow-analyzer.git .
```

### Step 2: Configure Environment

```bash
# Copy example environment file
cp .env.docker.example .env.docker

# Edit with your settings
nano .env.docker
```

**Required settings in `.env.docker`:**

```bash
# Application
APP_URL=http://your-server-ip:8003
APP_KEY=base64:your-generated-key-here

# Database (use a strong password!)
DB_PASSWORD=your_secure_database_password

# Ports
APP_PORT=8003
NETFLOW_PORT=2055
```

Generate an APP_KEY:
```bash
php artisan key:generate --show
# Or use: openssl rand -base64 32
```

### Step 3: Open Firewall Ports

```bash
sudo ufw allow 8003/tcp comment 'NetFlow Analyzer Web'
sudo ufw allow 2055/udp comment 'NetFlow Data'
```

### Step 4: Build and Start

```bash
# Build and start containers
docker compose --env-file .env.docker up -d --build

# Check status
docker compose ps
```

### Step 5: Create Admin User

```bash
# Enter the container
docker exec -it netflow_analyzer_app bash

# Run migrations and create user
php artisan migrate
php artisan tinker

# In tinker, create admin user:
\App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('your-password')]);
exit
```

### Step 6: Access Application

Open browser: `http://your-server-ip:8003`

Login with the admin credentials you created.

---

## Post-Installation Configuration

After logging in, go to **Settings** to configure:

1. **Collector IP Address** - The IP address of your NetFlow collector
2. **NetFlow Port** - UDP port for receiving flows (commonly 2055)
3. **sFlow Port** - UDP port for sFlow (commonly 6343)
4. **IPFIX Port** - UDP port for IPFIX (commonly 4739)

---

## Network Device Configuration

Configure your network devices to send flow data to this collector. Examples are shown in the Settings page after you configure the collector IP and port.

### General Settings

- **Destination IP**: Your collector server IP
- **Destination Port**: As configured in Settings
- **Protocol**: UDP
- **Version**: NetFlow v5, v9, or IPFIX

---

## Useful Commands

```bash
# Go to app directory
cd /opt/netflow-analyzer

# View running containers
docker compose ps

# View logs (live)
docker compose logs -f

# View app logs only
docker compose logs -f netflow-app

# Restart application
docker compose restart

# Stop application
docker compose down

# Rebuild and restart
docker compose down && docker compose build && docker compose up -d

# Enter container shell
docker exec -it netflow_analyzer_app bash

# Run artisan commands
docker exec -it netflow_analyzer_app php artisan migrate:status

# Access database
docker exec -it netflow_analyzer_db psql -U netflow_user -d netflow_traffic_analyzer
```

---

## Troubleshooting

### App not loading?

```bash
docker compose ps
docker compose logs netflow-app
docker compose restart
```

### Database connection error?

```bash
docker compose logs netflow-db
docker exec -it netflow_analyzer_db psql -U netflow_user -d netflow_traffic_analyzer -c "SELECT 1"
```

### Port already in use?

```bash
sudo ss -tlnp | grep 8003
sudo ss -ulnp | grep 2055
```

### Container won't start?

```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

---

## Security Notes

- Always use strong passwords for database and admin accounts
- Keep the `.env.docker` file secure and never commit it to version control
- Use HTTPS in production (configure a reverse proxy like Nginx)
- Regularly update Docker images and the application

---

## Support

For support, please contact your MonetX representative.
