# 🚀 មគ្គុទ្ទេសក៍ការដាក់ឱ្យដំណើរការលើ Server (Production Deployment & Server Guide)
### Smart Agro-Seed Marketplace (Grow2Growth)

---

## ១. តម្រូវការប្រព័ន្ធម៉ាស៊ីនបម្រើការ (System & Server Requirements)

មុននឹងដំឡើងកម្មវិធីលើ Production Server (Cloud VPS ដូចជា DigitalOcean, Linode, AWS EC2, ឬ Google Cloud) ម៉ាស៊ីន Server ត្រូវមាន៖

| កម្មវិធី (Software) | កំណែអប្បបរមា (Minimum Version) | កំណែណែនាំ (Recommended) |
|---|---|---|
| **Operating System** | Ubuntu 22.04 LTS | Ubuntu 24.04 LTS |
| **PHP Engine** | PHP 8.2 | PHP 8.2+ ឬ PHP 8.3 |
| **PHP Extensions** | `bcmath, ctype, curl, dom, fileinfo, json, mbstring, openssl, pdo_mysql, tokenizer, xml, zip` |
| **Database Server** | MySQL 8.0 / MariaDB 10.6+ | MySQL 8.0+ |
| **Web Server** | Nginx 1.18+ / Apache 2.4+ | Nginx (Reverse Proxy & HTTP/2) |
| **Package Managers**| Composer 2.x, Node.js 18.x+, npm 9.x+ |
| **SSL Certificate** | Let's Encrypt (Certbot) ឥតគិតថ្លៃ |

---

## ២. ជំហានដំឡើងលើ Production (Step-by-Step Deployment)

### ជំហានទី ១៖ Clone Project មកកាន់ `/var/www/`
```bash
cd /var/www
sudo git clone https://github.com/hortola12/smart-agro.git
cd /var/www/smart-agro
```

### ជំហានទី ២៖ ដំឡើង Composer & Node Dependencies
```bash
# ដំឡើង PHP dependencies សម្រាប់ Production (គ្មាន Dev packages)
composer install --no-dev --optimize-autoloader

# Build Frontend Assets (CSS/JS)
npm install
npm run build
```

### ជំហានទី ៣៖ កំណត់ Configuration (`.env`)
```bash
cp .env.example .env
php artisan key:generate
```

កែសម្រួល `.env` សម្រាប់ Production៖
```env
APP_NAME="Smart Agro-Seed Marketplace"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://smartagro.example.com
APP_TIMEZONE=Asia/Phnom_Penh

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_agro_prod
DB_USERNAME=agro_user
DB_PASSWORD=SecurePassword_2026!
```

### ជំហានទី ៤៖ Database Migration & Storage Linking
```bash
# បង្កើតតារាងទាំងអស់
php artisan migrate --force

# បង្កើតទិន្នន័យដំបូង (Admin, Categories, Seeds)
php artisan db:seed --force

# Link Public Storage សម្រាប់រូបភាពគ្រាប់ពូជ
php artisan storage:link
```

### ជំហានទី ៥៖ កំណត់សិទ្ធិឯកសារ (File Permissions)
```bash
sudo chown -R www-data:www-data /var/www/smart-agro
sudo find /var/www/smart-agro -type f -exec chmod 644 {} \;
sudo find /var/www/smart-agro -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/smart-agro/storage
sudo chmod -R 775 /var/www/smart-agro/bootstrap/cache
```

---

## ៣. ការកំណត់រចនាសម្ព័ន្ធ Nginx (Nginx Server Configuration)

បង្កើតឯកសារ `/etc/nginx/sites-available/smart-agro`៖

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name smartagro.example.com;
    root /var/www/smart-agro/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;

    charset utf-8;

    # គ្រប់គ្រងរូបភាព និង Static Assets (Cache 1 ឆ្នាំ)
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf)$ {
        expires 365d;
        access_log off;
        add_header Cache-Control "public, no-transform";
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

បើកដំណើរការ Site & ដំឡើង SSL Let's Encrypt៖
```bash
sudo ln -s /etc/nginx/sites-available/smart-agro /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

# ដំឡើង SSL ឥតគិតថ្លៃ
sudo certbot --nginx -d smartagro.example.com
```

---

## ៤. ការបង្កើនល្បឿន Production (Performance Optimization)

ដំណើរការ Commands ខាងក្រោមដើម្បី Cache ឯកសារ Config, Routes និង Views ជួយឱ្យប្រព័ន្ធដើរលឿនជាងមុនរហូតដល់ ៣ ដង៖

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache compiled Blade views
php artisan view:cache

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

*ចំណាំ៖ រាល់ពេលកែប្រែកូដ ឬទាញ `git pull` ថ្មី សូមរត់ `php artisan optimize:clear` បន្ទាប់មកទើប Cache ឡើងវិញ។*

---

## ៥. យុទ្ធសាស្ត្រថតចម្លងបម្រុងទុកទិន្នន័យ (Backup Strategy)

### ៥.១ ស្គ្រីប Backup Database ប្រចាំថ្ងៃ (Daily Cron Job)
បង្កើត Script `/home/ubuntu/backup-agro.sh`៖
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/smart-agro"
DATE=$(date +"%Y%m%d_%H%M%S")
mkdir -p $BACKUP_DIR

# Backup MySQL Database
mysqldump -u agro_user -p'SecurePassword_2026!' smart_agro_prod > "$BACKUP_DIR/db_backup_$DATE.sql"
gzip "$BACKUP_DIR/db_backup_$DATE.sql"

# លុបឯកសារចាស់ៗដែលលើសពី ៣០ ថ្ងៃ
find $BACKUP_DIR -type f -name "*.gz" -mtime +30 -exec rm {} \;
```

ដាក់ក្នុង `crontab -e` ឱ្យដំណើរការរៀងរាល់ម៉ោង ២:០០ យប់ (ម៉ោងកម្ពុជា)៖
```cron
0 2 * * * /bin/bash /home/ubuntu/backup-agro.sh > /dev/null 2>&1
```

