# 🧠 ការចងចាំគម្រោង និងការសម្រេចចិត្តបច្ចេកទេស (Project Memory & Architecture Decisions)
### Smart Agro-Seed Marketplace (Grow2Growth)

---

## ១. ការសម្រេចចិត្តបច្ចេកទេសស្នូល (Key Architectural Decisions - ADR)

### ADR-01: ការកំណត់ Timezone ស្តង់ដារកម្ពុជា (`Asia/Phnom_Penh`)
* **បរិបទ (Context)**៖ ប្រព័ន្ធដំណើរការសម្រាប់កសិករ និងសហគមន៍កសិកម្មនៅខេត្តបាត់ដំបង កម្ពុជា។ ការប្រើ Timezone UTC បណ្តាលឱ្យកាលបរិច្ឆេទវិក្កយបត្រ និងម៉ោងប្រមូលផលខុសគ្នា ៧ ម៉ោង។
* **ការសម្រេចចិត្ត (Decision)**៖ កំណត់ក្នុង `config/app.php` ឱ្យប្រើ `'timezone' => env('APP_TIMEZONE', 'Asia/Phnom_Penh')` (UTC+7) ជាសកល។
* **ផលជះ (Consequence)**៖ គ្រប់ទិន្នន័យ `created_at`, កាលបរិច្ឆេទលើវិក្កយបត្រ, Packing Manifest និងនាឡិកា Live Clock លើ Navbar បង្ហាញម៉ោងកម្ពុជាត្រឹមត្រូវ ១០០%។

---

### ADR-02: ប្រព័ន្ធពុម្ពអក្សរ និងសោភ័ណភាពខ្មែរ (Khmer Typography System)
* **បរិបទ (Context)**៖ ពុម្ពអក្សរចាស់ពិបាកមើល និងជាន់បន្ទាត់គ្នាពេលបង្ហាញលេខទូរស័ព្ទ ឬពាក្យបច្ចេកទេសកសិកម្ម។
* **ការសម្រេចចិត្ត (Decision)**៖ ជ្រើសរើស **Kantumruy Pro** ជាពុម្ពអក្សរស្នូល រួមជាមួយ **Koh Santepheap** (Google Fonts)។
* **អត្ថប្រយោជន៍ (Benefits)**៖
  - អក្សរមូលស្អាត មានដង្ហើមទូលាយ មិនជាន់ជើងអក្សរ
  - គាំទ្រទម្ងន់ច្បាស់លាស់ពី Light (300) រហូតដល់ Bold (700)
  - បង្ហាញរូបរាងដូចគ្នាទាំងស្រុងលើ macOS, Windows, iPhone និង Android។

---

### ADR-03: ការគ្រប់គ្រងស្តុកតាមរយៈ Atomic Database Transactions
* **បរិបទ (Context)**៖ ក្នុង eCommerce កសិកម្ម ការកាត់ស្តុកគ្រាប់ពូជមិនត្រូវឱ្យមាន Race Condition ឬការកាត់ស្តុកតែបង្កើត Order បរាជ័យឡើយ។
* **ការសម្រេចចិត្ត (Decision)**៖ ដំណើរការ Checkout ទាំងមូលត្រូវបានរុំព័ទ្ធក្នុង `DB::transaction()`៖
  - ប្រសិនបើមានជំហានណាមួយបរាជ័យ ប្រព័ន្ធនឹង Rollback ភ្លាមៗ។
  - ប្រសិនបើ Order ត្រូវបាន Cancel ក្រោយមក ប្រព័ន្ធនឹង Restore ស្តុកត្រឡប់មកវិញដោយស្វ័យប្រវត្ត។

---

### ADR-04: តុល្យភាព Navbar & ការពារការធ្លាក់ជួរ (Navbar Proportional Layout)
* **បរិបទ (Context)**៖ នៅលើអេក្រង់ Laptop (1024px – 1280px) ធាតុខាងស្តាំនៃ Navbar មានទំហំធំពេក (~710px) ធ្វើឱ្យធ្លាក់ជួរខូចទម្រង់។
* **ការសម្រេចចិត្ត (Decision)**៖
  1. ប្តូរពី `.container` មកជា `container-fluid px-3 px-xl-4 px-xxl-5` ជាមួយ `max-width: 1440px;`។
  2. ផ្លាស់ប្តូរ `តាមដានប្រភព QR` និង `តាមដានដឹកជញ្ជូន` មកដាក់ក្នុង Menu ចម្បង (`navbar-nav`)។
  3. បង្រួមប៊ូតុង Login/Register មកជា **Capsule Segmented Pill** (`[ ចូល | ចុះឈ្មោះ ]`) សល់ទទឹងត្រឹម 145px។
  4. បន្ថែម `d-none d-lg-inline-flex` លើ Desktop Cart ដើម្បីកុំឱ្យស្ទួនលើ Mobile Header។

---

## ២. សៀវភៅរចនា UI/UX (Design System Guidelines)

| ធាតុ (Element) | លក្ខណៈបច្ចេកទេស (Specification) | ឧទាហរណ៍ប្រើប្រាស់ (Usage Example) |
|---|---|---|
| **Primary Color** | Eco-Green `#198754` | ប៊ូតុងចម្បង, របារ Navbar, Badge ជោគជ័យ |
| **Dark Forest Green**| `#113824` / `#163626` | Top Announcement Bar, Footer Background |
| **Accent Gold/Warning**| `#f59e0b` / `#ffc107` | ប៊ូតុងកន្ត្រក, Badge រដូវប្រាំង, ពិន្ទុផ្កាយ ★ |
| **Background Tint**| `#fafdff` / `#f8fafc` | ផ្ទៃខាងក្រោយទំព័រទូទៅ (Soft Clean Tone) |
| **Button Standard**| `height: 38px; border-radius: 50px;` | ប៊ូតុង និង Input ទាំងអស់លើ Header & Cards |
| **Active Indicators**| Glowing Dot Animation (`pulse-dot`) | សម្គាល់ស្ថានភាពសកម្មលើ Order និងរដូវកាល |

---

## ៣. កំណត់ត្រាដោះស្រាយបញ្ហាធំៗ (Resolved Bugs & Lessons Learned)

### 🐛 បញ្ហាទី ១៖ Logo រាងពងក្រពើ និង Margin ខុសលើ Navbar
* **មូលហេតុ**៖ កូដចាស់កំណត់ `style="width: 50px; height: 38px;"` លើ `.rounded-circle` និងប្រើ class `me-2.5` ដែលគ្មានក្នុង Bootstrap 5។
* **ដំណោះស្រាយ**៖ កំណត់មក `width: 38px; height: 38px;` ជាមួយ `flex-shrink-0` និងប្រើ `me-2` ត្រឹមត្រូវ។

### 🐛 បញ្ហាទី ២៖ កន្ត្រកស្ទួនលើទូរស័ព្ទដៃ (Duplicate Mobile Cart)
* **មូលហេតុ**៖ លើ Mobile Header មានប៊ូតុងកន្ត្រករួចហើយ តែពេលពង្រីក Hamburger Collapse ឃើញប៊ូតុងកន្ត្រកម្តងទៀត ព្រោះប៊ូតុងកន្ត្រក Desktop ខ្វះ `d-none d-lg-inline-flex`។
* **ដំណោះស្រាយ**៖ បន្ថែម `d-none d-lg-inline-flex` ធ្វើឱ្យប៊ូតុងនោះបង្ហាញតែលើ Desktop។

### 🐛 បញ្ហាទី ៣៖ HTML Div Unbalanced លើ Calendar Section
* **មូលហេតុ**៖ ការ copy កូដ Dropdown ពី Admin មកដាក់លើ `home.blade.php` ដោយបិទ `</div>` លើសចំនួន ធ្វើឱ្យ Header និង Card រដូវកាលធ្លាក់ចេញពី Container។
* **ដំណោះស្រាយ**៖ សម្អាត Div Tags និងបង្កើតជារបារ **Smart Agro Radar & 12-Month Timeline** ពេញលេញ។

---

## ៤. បញ្ជីពាក្យបញ្ជាចាំបាច់ (Command Cheat Sheet)

```bash
# ដំណើរការ Local Server
php artisan serve

# សម្អាត Cache ទាំងអស់ពេលកែទម្រង់ Blade ឬ Routes
php artisan optimize:clear

# ដំណើរការ Unit & Feature Tests ទាំងអស់
php artisan test

# រត់តែតេស្ត SmartAgroTest មួយគត់
php artisan test --filter=SmartAgroTest

# ត្រួតពិនិត្យ Code Style (Laravel Pint)
./vendor/bin/pint --test

# កែសម្រួល Code Style ស្វ័យប្រវត្ត
./vendor/bin/pint
```

