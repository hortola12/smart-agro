<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# 🌾 Smart Agro-Seed Marketplace

គម្រោងប្រព័ន្ធទីផ្សារគ្រាប់ពូជកសិកម្មឆ្លាតវៃ សរសេរឡើងដោយប្រើប្រាស់ **Laravel Framework**។ គម្រោងនេះផ្តោតសំខាន់ទៅលើការទិញលក់ និងតាមដានប្រភពគ្រាប់ពូជ (Smart Seed Traceability)។

---

## 📸 ផ្ទាំងកម្មវិធី (Interface Screenshots)

នៅផ្នែកនេះ បង្ហាញពីផ្ទាំង Interface នៃកម្មវិធីដែលបានឌីហ្សាញ និងកំពុងអភិវឌ្ឍ៖

### ១. ទំព័រដើម (Homepage)
![Homepage](screenshots/home.png)

### ២. ទីផ្សារគ្រាប់ពូជ (Marketplace)
![Marketplace](screenshots/marketplace.png)

---

## 🚀 មុខងារចម្បងៗ (Features)

- **Authentication:** ប្រព័ន្ធចុះឈ្មោះ និងឡុកអ៊ិនសម្រាប់អ្នកប្រើប្រាស់។
- **Seed Marketplace:** ផ្ទាំងបង្ហាញ ស្វែងរក និងទិញលក់គ្រាប់ពូជកសិកម្ម (ឧទាហរណ៍៖ គ្រាប់ពូជសាឡាត់ - Lettuce Seeds)។
- **Smart Seed Traceability:** ប្រព័ន្ធតាមដានប្រភព និងព័ត៌មានលម្អិតរបស់គ្រាប់ពូជ។

---

## 🛠️ របៀបដំឡើង និងដំណើរការ (How to Run Project)

ប្រសិនបើចង់យក Project នេះទៅដំណើរការនៅលើម៉ាស៊ីន Local (កុំព្យូទ័រផ្សេងទៀត) សូមធ្វើតាមជំហានខាងក្រោម៖

១. ទាញយកកូដ ឬ Clone Project:
```bash
git clone [https://github.com/hortola12/smart-agro.git](https://github.com/hortola12/smart-agro.git)
cd smart-agro

២. ដំឡើង Dependencies (Composer & NPM):

composer install
npm install && npm run dev

៣. បង្កើត File .env និង Generate Key:

cp .env.example .env
php artisan key:generate

៤. បើកដំណើរការ Server:

php artisan serve
