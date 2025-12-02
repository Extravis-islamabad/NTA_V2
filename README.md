# NetFlow Analyzer

Real-time network traffic monitoring and analysis application.

## Quick Reference

| Item | Value |
|------|-------|
| **Web URL** | http://192.168.10.7:8003 |
| **NetFlow Port** | 2055/UDP |
| **Database Port** | 5433 |
| **SSH User** | monetx |

---

## DEPLOYMENT GUIDE (Using PuTTY)

### Prerequisites

- PuTTY installed on your Windows PC
- GitHub account
- Server: 192.168.10.7 (Ubuntu 22.04)

---

## PART 1: First-Time Server Setup

### Step 1.1: Connect via PuTTY

1. Open **PuTTY**
2. Host Name: `192.168.10.7`
3. Port: `22`
4. Click **Open**
5. Login: `monetx`
6. Enter your password

### Step 1.2: Create Application Directory

Copy and paste these commands one by one:

```bash
# Create app directory
sudo mkdir -p /opt/netflow-analyzer
sudo chown monetx:monetx /opt/netflow-analyzer
cd /opt/netflow-analyzer
```

### Step 1.3: Open Firewall Ports

```bash
# Open required ports
sudo ufw allow 8003/tcp comment 'NetFlow Analyzer Web'
sudo ufw allow 2055/udp comment 'NetFlow Data'
sudo ufw status
```

---

## PART 2: GitHub Repository Setup

### Step 2.1: Create GitHub Repository

1. Go to https://github.com/new
2. Repository name: `netflow-analyzer`
3. Set to **Private** (recommended)
4. Click **Create repository**
5. Copy the repository URL (e.g., `https://github.com/YOUR_USERNAME/netflow-analyzer.git`)

### Step 2.2: Push Code to GitHub (From Your PC)

Open **PowerShell** or **Command Prompt** on your Windows PC:

```powershell
# Navigate to project folder
cd "D:\Extravis\Custom Solutions\netflow-analyzer"

# Initialize git (if not already)
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit - NetFlow Analyzer"

# Add remote (replace YOUR_USERNAME)
git remote add origin https://github.com/YOUR_USERNAME/netflow-analyzer.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### Step 2.3: Add GitHub Secrets for CI/CD

1. Go to your GitHub repository
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add these 3 secrets:

| Name | Value |
|------|-------|
| `SERVER_IP` | `192.168.10.7` |
| `SERVER_USER` | `monetx` |
| `SERVER_PASSWORD` | `your_ssh_password` |

---

## PART 3: First Deployment (Manual)

### Step 3.1: Clone Repository on Server

In PuTTY (connected to server):

```bash
cd /opt/netflow-analyzer

# Clone your repository (replace YOUR_USERNAME)
git clone https://github.com/YOUR_USERNAME/netflow-analyzer.git .
```

Enter your GitHub username and **Personal Access Token** when prompted.

> **Note**: GitHub no longer accepts passwords. Create a Personal Access Token:
> 1. Go to GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
> 2. Generate new token with `repo` scope
> 3. Use this token as your password

### Step 3.2: Build and Start Application

```bash
# Build Docker images (takes 5-10 minutes first time)
docker-compose build

# Start the application
docker-compose up -d

# Check if containers are running
docker-compose ps
```

### Step 3.3: Verify Deployment

```bash
# Check container status
docker ps | grep netflow

# Check logs
docker-compose logs -f

# Test web interface
curl http://localhost:8003
```

Open browser: **http://192.168.10.7:8003**

---

## PART 4: CI/CD - Automatic Deployments

After the first manual deployment, any push to `main` branch will automatically deploy.

### How it works:

1. You push code to GitHub
2. GitHub Actions runs the deployment workflow
3. Server pulls latest code and restarts containers

### Test CI/CD:

1. Make a small change to any file on your PC
2. Commit and push:

```powershell
git add .
git commit -m "Test CI/CD"
git push
```

3. Go to GitHub → Actions tab to see deployment progress

---

## Useful Commands

### In PuTTY (on server):

```bash
# Go to app directory
cd /opt/netflow-analyzer

# View running containers
docker-compose ps

# View logs (live)
docker-compose logs -f

# View logs (app only)
docker-compose logs -f netflow-app

# Restart application
docker-compose restart

# Stop application
docker-compose down

# Start application
docker-compose up -d

# Rebuild and restart
docker-compose down && docker-compose build && docker-compose up -d

# Enter container shell
docker exec -it netflow_analyzer_app bash

# Run artisan commands
docker exec -it netflow_analyzer_app php artisan migrate:status

# Check database
docker exec -it netflow_analyzer_db psql -U netflow_user -d netflow_traffic_analyzer
```

---

## Troubleshooting

### App not loading?

```bash
# Check container status
docker-compose ps

# Check logs for errors
docker-compose logs netflow-app

# Restart containers
docker-compose restart
```

### Database connection error?

```bash
# Check database container
docker-compose logs netflow-db

# Test database connection
docker exec -it netflow_analyzer_db psql -U netflow_user -d netflow_traffic_analyzer -c "SELECT 1"
```

### Port already in use?

```bash
# Check what's using the port
sudo ss -tlnp | grep 8003
sudo ss -ulnp | grep 2055
```

### Container won't start?

```bash
# Remove and rebuild
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

---

## Network Device Configuration

Configure your network devices to send NetFlow/sFlow data to:

- **Destination IP**: 192.168.10.7
- **Destination Port**: 2055 (UDP)

### Cisco Router Example:

```
flow exporter NETFLOW-EXPORT
 destination 192.168.10.7
 transport udp 2055
 source GigabitEthernet0/0
!
flow monitor NETFLOW-MONITOR
 exporter NETFLOW-EXPORT
 record netflow ipv4 original-input
!
interface GigabitEthernet0/1
 ip flow monitor NETFLOW-MONITOR input
 ip flow monitor NETFLOW-MONITOR output
```

### Palo Alto Example:

```
set deviceconfig system netflow exporter-1 server 192.168.10.7
set deviceconfig system netflow exporter-1 port 2055
```

---

## Database Credentials

| Item | Value |
|------|-------|
| Host | `netflow-db` (internal) or `192.168.10.7:5433` (external) |
| Database | `netflow_traffic_analyzer` |
| Username | `netflow_user` |
| Password | `NetFlow@Secure#2024!` |

---

## Support

- **Logs Location**: `docker-compose logs`
- **Application Logs**: `docker exec -it netflow_analyzer_app cat storage/logs/laravel.log`
