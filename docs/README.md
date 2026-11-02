# 📚 មជ្ឈមណ្ឌលឯកសារបច្ចេកទេស Smart Agro-Seed Marketplace (Grow2Growth)
### Project Documentation Center & System Reference Guide

---

## 🌾 អំពីគម្រោង (Project Overview)

**Smart Agro-Seed Marketplace (Grow2Growth)** គឺជាប្រព័ន្ធអេកូឡូស៊ីទីផ្សារគ្រាប់ពូជកសិកម្មឆ្លាតវៃ និងតាមដានប្រភពដើមសង្វាក់ផលិតកម្មកសិកម្ម (Smart Seed Marketplace & QR Batch Traceability System) ដំបូងគេនៅកម្ពុជា ដែលសហការជាមួយសហគមន៍កសិកម្មខេត្តបាត់ដំបង។

ប្រព័ន្ធនេះត្រូវបានបង្កើតឡើងដើម្បីដោះស្រាយបញ្ហាស្នូលធំៗចំនួន ៣ ក្នុងវិស័យកសិកម្ម៖
1. **គុណភាព និងទំនុកចិត្តលើគ្រាប់ពូជ (Seed Authenticity)**៖ ធានាគ្រាប់ពូជសុទ្ធ អត្រាដំណុះខ្ពស់ ៩៥%+ គ្មានការក្លែងបន្លំ។
2. **តម្លាភាពសង្វាក់ផលិតកម្ម (Supply Chain Traceability)**៖ តាមដានប្រភពកសិដ្ឋាន ប្រវត្តិប្រមូលផល កាលបរិច្ឆេទផុតកំណត់ និងវិញ្ញាបនបត្រមន្ទីរពិសោធន៍តាមរយៈ **Dynamic QR Code**។
3. **ការគ្រប់គ្រងពាណិជ្ជកម្មកសិកម្មបែបទំនើប (Digital Agro-Commerce)**៖ ប្រព័ន្ធកន្ត្រកទំនិញ Real-time Stock, ប្រព័ន្ធគូប៉ុងបញ្ចុះតម្លៃ, ការតាមដានការដឹកជញ្ជូន ២៥ ខេត្ត-ក្រុង, និងផ្ទាំងគ្រប់គ្រង (Admin Dashboard) ពេញលេញ។

---

## 🗂️ មាតិកាឯកសារបច្ចេកទេស (Documentation Index)

ឯកសារទាំងអស់នៅក្នុងថត `docs/` ត្រូវបានរៀបចំឡើងជាប្រព័ន្ធ ដើម្បីឱ្យអ្នកអភិវឌ្ឍន៍ (Developers), អ្នកគ្រប់គ្រងគម្រោង (Project Managers), និងអ្នកត្រួតពិនិត្យប្រព័ន្ធ (System Auditors) អាចបើកអាន និងយល់ពីប្រព័ន្ធទាំងមូលបានយ៉ាងរហ័ស៖

| ឯកសារ (Document) | ខ្លឹមសារ និងគោលបំណងចម្បង (Key Content & Purpose) |
|---|---|
| [**architecture.md**](architecture.md) | **ស្ថាបត្យកម្មប្រព័ន្ធ & បច្ចេកវិទ្យា**<br>ពន្យល់ពី MVC Pattern, Tech Stack (Laravel 11, PHP 8.2+, MySQL), រចនាសម្ព័ន្ធ Database Schema, Model Relationships (ERD) និងស្រទាប់សុវត្ថិភាព (Security Layers)។ |
| [**rules.md**](rules.md) | **វិធាន និងលក្ខខណ្ឌអាជីវកម្ម (Business Rules)**<br>ច្បាប់កាត់ស្តុក-បញ្ចូលស្តុក Real-time, លក្ខខណ្ឌគូប៉ុងបញ្ចុះតម្លៃ, វិធានលេខបាច់ Batch Numbering, លក្ខខណ្ឌផុតកំណត់ និងសុពលភាពកាលបរិច្ឆេទ។ |
| [**roles.md**](roles.md) | **តួនាទី និងសិទ្ធិប្រើប្រាស់ (Roles & Permissions - RBAC)**<br>បែងចែកសិទ្ធិរវាង Guest (ភ្ញៀវ), Customer (កសិករ/អ្នកទិញ) និង Admin (អ្នកគ្រប់គ្រងសហគមន៍) អមដោយ Permissions Matrix Table។ |
| [**flows.md**](flows.md) | **លំហូរការងារ និងដំណើរការប្រព័ន្ធ (Workflows & Lifecycle)**<br>ដ្យាក្រាមដំណើរការបញ្ជាទិញ (Order Flow), វដ្តជីវិតនៃការដឹកជញ្ជូន (Order Lifecycle), លំហូរស្កេន QR Code Traceability និងដំណើរការបង្កើតគ្រាប់ពូជរបស់ Admin។ |
| [**tasks.md**](tasks.md) | **កំណត់ត្រាកិច្ចការ និងប្រវត្តិការអភិវឌ្ឍ (Tasks & Milestones)**<br>សង្ខេបកិច្ចការគ្រប់ដំណាក់កាលដែលបានបញ្ចប់ (Phase 1–5), ស្ថិតិតេស្តស្វ័យប្រវត្ត (37 Unit/Feature Tests), និងផែនការអភិវឌ្ឍទៅមុខ (Roadmap)។ |
| [**memory.md**](memory.md) | **ការចងចាំគម្រោង & ការសម្រេចចិត្តបច្ចេកទេស (Project Memory)**<br>ស្តង់ដារ Timezone (`Asia/Phnom_Penh`), Design System (ពុម្ពអក្សរ Kantumruy Pro, ពណ៌ #198754), ដំណោះស្រាយបញ្ហាធំៗដែលធ្លាប់ឆ្លងកាត់ និង Commands ប្រើញឹកញាប់។ |
| [**routes.md**](routes.md) | **បញ្ជីផ្លូវ API & Web Routes (Route Reference)**<br>តារាងផ្លូវ URL ទាំងអស់ក្នុងប្រព័ន្ធ (Public Routes, Auth Routes, Protected Admin Routes), HTTP Methods និង Controller Methods ត្រូវគ្នា។ |
| [**deployment.md**](deployment.md) | **ការដាក់ឱ្យដំណើរការលើ Server (Production Deployment)**<br>មគ្គុទ្ទេសក៍ដំឡើង Nginx, Let's Encrypt SSL, PHP-FPM, File Permissions, Performance Optimization, និង Backup Strategy។ |
| [**testing.md**](testing.md) | **ការធ្វើតេស្តគុណភាព & QA (Testing Guide)**<br>ដំណើរការតេស្តស្វ័យប្រវត្ត PHPUnit, ការបកស្រាយ ៣៥+ Test Cases, បញ្ជី Manual QA Checklist, និង GitHub Actions CI/CD។ |
| [**farming_domain.md**](farming_domain.md) | **ចំណេះដឹងកសិកម្ម & ស្តង់ដារគ្រាប់ពូជ (Agro Domain Knowledge)**<br>ប្រវត្តិកសិដ្ឋានតាក្រាមបាត់ដំបង, រដូវកាលទាំង ៣ នៅកម្ពុជា, ស្តង់ដារអត្រាដំណុះ ៩៥%+, និងរូបមន្តគណនាទំហំដី/គ្រាប់ពូជ។ |
| [**troubleshooting.md**](troubleshooting.md) | **ការដោះស្រាយបញ្ហាបច្ចេកទេស & FAQ (Troubleshooting)**<br>វិធីដោះស្រាយបញ្ហាធំៗ (Error 419, Error 403, Broken Images, Rate Limit 429, Database Connection Errors)។ |
| [**localization.md**](localization.md) | **ការគ្រប់គ្រងពហុភាសា (Multi-Language & i18n)**<br>រចនាសម្ព័ន្ធភាសាខ្មែរ (KH) និងអង់គ្លេស (EN), JSON Language Files, Dual-Language Database Columns, និងរបៀបបន្ថែមភាសាថ្មី។ |

---

## ⚡ របៀបចាប់ផ្តើមដំណើរការគម្រោង (Quick Start Guide)

```bash
# ១. Clone និងចូលទៅកាន់ Directory
git clone https://github.com/hortola12/smart-agro.git
cd smart-agro

# ២. ដំឡើង Dependencies
composer install
npm install && npm run build

# ៣. កំណត់ Configuration
cp .env.example .env
php artisan key:generate

# ៤. Migrate និង Seed Database
php artisan migrate --seed

# ៥. ដំណើរការ Server
php artisan serve
```

---

## 🧪 ការផ្ទៀងផ្ទាត់គុណភាពកូដ (Verification & Quality Assurance)

```bash
# រត់ Feature Tests ទាំងអស់ (37 Tests / 186 Assertions)
php artisan test

# ពិនិត្យ Code Style តាមស្តង់ដារ PSR-12
./vendor/bin/pint --test

# កែសម្រួល Code Style ស្វ័យប្រវត្ត
./vendor/bin/pint
```

