

---

### ជំហានអនុវត្ត៖

សូម **Copy កូដទាំងស្រុងនៅក្នុងប្រអប់ខាងក្រោមនេះ** យកទៅជំនួស (Replace) ក្នុង File `README.md` របស់អ្នក រួចចុច **Save**៖

```markdown
# 🌾 Smart Agro-Seed Marketplace (Grow2Growth)

[![Laravel Version](https://img.shields.io/badge/Laravel-v11.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-v8.2+-777BB4?logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**Smart Agro-Seed Marketplace (Grow2Growth)** គឺជាប្រព័ន្ធផ្សារកសិកម្មឌីជីថលវៃឆ្លាត និងតាមដានប្រភពដើមគ្រាប់ពូជបន្លែ ដែលត្រូវបានបង្កើតឡើងដោយប្រើប្រាស់ស៊ុមការងារ **Laravel Framework**។ គម្រោងនេះផ្តោតសំខាន់លើការផ្គត់ផ្គង់គ្រាប់ពូជបន្លែដែលមានគុណភាពខ្ពស់ (ដូចជា Premium Lettuce Seeds) រួមទាំងប្រព័ន្ធគ្រប់គ្រងស្តុកកន្ត្រកទំនិញ Real-time និងប្រព័ន្ធវិទ្យាសាស្ត្រតាមដានប្រភពដើមសង្វាក់ផលិតកម្មកសិកម្មតាមរយៈ Dynamic QR Code។

---

## 📸 ផ្ទាំង Interface នៃកម្មវិធី (Application Screenshots)

### ជួរទី ១៖ ទំព័រដើមគេហទំព័រ (Homepage Frontend)
![Smart Agro Homepage](screenshots/home.png)

### ជួរទី ២៖ ទីផ្សារគ្រាប់ពូជបន្លែ (Seed Marketplace)
![Seed Marketplace](screenshots/marketplace.png)

---

## 🚀 មុខងារចម្បងៗនៃប្រព័ន្ធ (Core Features)

### ១. ប្រព័ន្ធសមាជិក និងការគ្រប់គ្រងសិទ្ធិ (Authentication & RBAC)
* **Customer Role:** អាចចូលមើលគ្រាប់ពូជ ស្វែងរក ដាក់ទំនិញចូលកន្ត្រក បញ្ជាទិញ ប្តូរភាសាប្រព័ន្ធ និងមើលប្រវត្តិវិក្កយបត្រផ្ទាល់ខ្លួន។
* **Admin Role:** មានផ្ទាំងគ្រប់គ្រងខាងក្រោយ (Admin Dashboard) ដាច់ដោយឡែកសម្រាប់គ្រប់គ្រងគ្រាប់ពូជ វគ្គផលិតកម្ម (Batch) និងស្ថានភាពដឹកជញ្ជូន។

### ២. ទីផ្សារគ្រាប់ពូជ និងប្រព័ន្ធកន្ត្រកទំនិញ (Seed Marketplace & Smart Cart)
* **Real-time Stock Management:** ប្រព័ន្ធកាត់ស្តុកដោយស្វ័យប្រវត្តភ្លាមៗ (Real-time Decrement) នៅពេលអតិថិជនចុច "ដាក់ចូលកន្ត្រកទំនិញ" ឬចុចប៊ូតុង (+) និងបូកស្តុកត្រឡប់ទៅ Database វិញ (Increment) ពេលចុច (-) ឬលុបទំនិញពីកន្ត្រក។
* **One-stop Admin Seed Form:** ផ្ទាំងបន្ថែមគ្រាប់ពូជដ៏ឆ្លាតវៃសម្រាប់ Admin ដែលអនុញ្ញាតឱ្យបញ្ចូលព័ត៌មានទូទៅរបស់គ្រាប់ពូជ និងបង្កើតវគ្គផលិតកម្ម (Traceability Batch) ក្នុងពេលតែមួយ។

### ៣. ប្រព័ន្ធតាមដានប្រភពដើមវៃឆ្លាត (Smart Seed Traceability)
* **Batch Tracking System:** អនុញ្ញាតឱ្យកសិករ ឬអ្នកទិញបំពេញលេខកូដបាច់វគ្គផលិត (Batch Number) ដើម្បីដេញជើងស្វែងរកព័ត៌មានកសិកម្ម។
* **Agricultural Transparency:** បង្ហាញព័ត៌មានលម្អិតពីប្រភពកសិដ្ឋានដើម, អត្រាដុះលូតលាស់ (Germination Rate), កម្រិត pH ដីសមស្រប, របបស្រោចទឹក និងសៀវភៅណែនាំបច្ចេកទេសដាំដុះ (Cultivation Guide)។

### ៤. ប្រព័ន្ធគាំទ្រពហុភាសា (Localization Multi-language)
* គាំទ្រការផ្លាស់ប្តូរភាសាយ៉ាងរហ័សរវាង **ភាសាខ្មែរ (🇰🇭 KH)** និង **ភាសាអង់គ្លេស (🇬🇧 EN)** តាមរយៈ Middleware និង Session ចាប់ព័ត៌មាន។

---

## 🛠️ បច្ចេកវិទ្យាដែលប្រើប្រាស់ (Technologies Used)
* **Backend Core:** Laravel 11.x & PHP 8.2+
* **Database:** MySQL / PostgreSQL
* **Frontend UI:** Blade Template Engine, Bootstrap 5.3, FontAwesome 6.4, Google Fonts (Kantumruy Pro, Koh Santepheap)
* **Interactive Components:** JavaScript (Fetch API / AJAX), SweetAlert2 (សម្រាប់ផ្ទាំងលោតសារជោគជ័យ)

---

## 💻 របៀបដំឡើង និងដំណើរការកម្មវិធីក្នុងម៉ាស៊ីន (How to Setup & Run)

សូមអនុវត្តតាមជំហាននីមួយៗខាងក្រោមនៅក្នុង Terminal នៅលើម៉ាស៊ីន MacBook របស់អ្នក៖

### ១. ទាញយកគម្រោងពី GitHub (Clone Project)
```bash
git clone [https://github.com/hortola12/smart-agro.git](https://github.com/hortola12/smart-agro.git)
cd smart-agro

```

### ២. ដំឡើងកញ្ចប់បណ្ណាល័យជំនួយ (Install Dependencies)

```bash
composer install
npm install && npm run dev

```

### ៣. បង្កើតឯកសារកំណត់ប្រព័ន្ធ (Setup Environment Config)

```bash
cp .env.example .env
php artisan key:generate

```

### ៤. កំណត់ Database នៅក្នុងឯកសារ .env

សូមបើកឯកសារ `.env` រួចកែសម្រួលព័ត៌មាន Database ឱ្យត្រូវតាមម៉ាស៊ីនរបស់អ្នក៖

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_agro_db
DB_USERNAME=root
DB_PASSWORD=your_password

```

### ៥. បង្កើតតារាង និងចាក់ទិន្នន័យគំរូ (Database Migration & Seeding)

```bash
php artisan migrate --seed

```

### ៦. បង្កើត Symlink សម្រាប់រក្សារូបភាពគ្រាប់ពូជ (Storage Link)

```bash
php artisan storage:link

```

### ៧. ជម្រះ Cache និងដំណើរការប្រព័ន្ធ (Clear Cache & Run Server)

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan serve

```

ឥឡូវនេះ កម្មវិធីនឹងដំណើរការនៅលើ URL: http://127.0.0.1:8000 

---

## 👥 អភិវឌ្ឍដោយ (Developed By)

* **Developer:** @hortola12 (IT Student - Year 3)
* **Project Name:** Smart Agro-Seed Marketplace
* **Organization:** Grow2Growth Agri-Tech Initiative

```




