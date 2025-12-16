# NetFlow Analyzer

Real-time network traffic monitoring and analysis application by MonetX.

## Features

- Real-time NetFlow/sFlow/IPFIX traffic monitoring
- Device inventory management with SSH configuration
- Traffic analysis and reporting
- Alarm management
- PDF report generation
- User authentication

## Requirements

- Docker & Docker Compose
- Ubuntu 22.04 (or any Linux with Docker)
- 4GB RAM minimum
- Open ports: 8003 (web), 2055 (NetFlow UDP)

---

## Deployment Guide

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

# Edit with your settings
nano .env.docker
```

**Update these values in `.env.docker`:**

```bash
APP_URL=http://YOUR_SERVER_IP:8003
APP_KEY=base64:your-generated-key-here
DB_PASSWORD=your_secure_password
```

**Generate APP_KEY:**
```bash
openssl rand -base64 32
# Then add "base64:" prefix, e.g.: APP_KEY=base64:abc123...
```

### Step 3: Open Firewall Ports

```bash
sudo ufw allow 8003/tcp
sudo ufw allow 2055/udp
```

### Step 4: Build and Start

```bash
docker compose --env-file .env.docker up -d --build
```

### Step 5: Create Admin User

```bash
docker exec -it netflow_analyzer_app php artisan tinker
```

In tinker:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('your-password')
]);
```

Type `exit` to quit.

### Step 6: Access Application

Open browser: `http://YOUR_SERVER_IP:8003`

---

## Post-Installation

After login, go to **Settings** to configure:
- Collector IP Address
- NetFlow/sFlow/IPFIX Ports
- Retention settings

---

## Common Commands

```bash
# View logs
docker compose --env-file .env.docker logs -f

# Restart
docker compose --env-file .env.docker restart

# Stop
docker compose --env-file .env.docker down

# Rebuild
docker compose --env-file .env.docker up -d --build

# Run migrations
docker exec -it netflow_analyzer_app php artisan migrate

# Enter container
docker exec -it netflow_analyzer_app bash
```

---

## Updating

```bash
cd /opt/netflow-analyzer
git pull
docker compose --env-file .env.docker up -d --build
docker exec -it netflow_analyzer_app php artisan migrate --force
```

---

## Troubleshooting

**Container not starting:**
```bash
docker compose --env-file .env.docker logs netflow-app
```

**Database issues:**
```bash
docker compose --env-file .env.docker logs netflow-db
```

**Reset everything:**
```bash
docker compose --env-file .env.docker down -v
docker compose --env-file .env.docker up -d --build
```

---

## Support

Contact your MonetX representative for support.
