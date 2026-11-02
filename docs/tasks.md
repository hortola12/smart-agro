# 📝 កំណត់ត្រាកិច្ចការ និងប្រវត្តិការអភិវឌ្ឍ (Tasks & Development Milestones)
### Smart Agro-Seed Marketplace (Grow2Growth)

---

## ១. ដំណាក់កាលដែលបានបញ្ចប់ជោគជ័យ (Completed Milestones)

### 🚀 ដំណាក់កាលទី ១៖ គ្រោងគ្រឹះប្រព័ន្ធ & ទីផ្សារស្នូល (Core Marketplace)
- [x] រៀបចំរចនាសម្ព័ន្ធគម្រោងលើ **Laravel 11.x & PHP 8.2+**
- [x] បង្កើត Database Migrations សម្រាប់តារាងស្នូល (`users`, `categories`, `seeds`)
- [x] អភិវឌ្ឍប្រព័ន្ធគ្រប់គ្រងគ្រាប់ពូជពេញលេញ (Seed CRUD: បង្កើត, កែសម្រួល, លុប)
- [x] ប្រព័ន្ធចាត់ថ្នាក់គ្រាប់ពូជតាមប្រភេទបន្លែ (Vegetable Categories with Seed Counts)
- [x] មុខងារស្វែងរកគ្រាប់ពូជឆ្លាតវៃតាមឈ្មោះ និងប្រភេទបន្លែ (Live Search & Category Filtering)

---

### 🔍 ដំណាក់កាលទី ២៖ ប្រព័ន្ធតាមដានប្រភពដើមតាម QR Code (Traceability System)
- [x] បង្កើតតារាង `seed_traceabilities` សម្រាប់កត់ត្រាលេខបាច់ (Batch Records)
- [x] គាំទ្រការស្កេន Dynamic QR Code ផ្ទាល់ពីកាមេរ៉ាទូរស័ព្ទដៃ
- [x] បង្ហាញព័ត៌មានលម្អិតកសិកម្ម៖ ទីតាំងកសិដ្ឋានតាក្រាម, អត្រាដំណុះ ៩៥%+, កម្រិត pH ដី, របបស្រោចទឹក
- [x] ទំព័រវិញ្ញាបនបត្រមន្ទីរពិសោធន៍កសិកម្មផ្លូវការ (Official Seed Lab Certificate `/seed-certificate/{id}`)
- [x] មុខងារបោះពុម្ពស្លាក Sticker គ្រាប់ពូជជាសន្លឹកសម្រាប់បិទលើកញ្ចប់ (`/admin/seeds/print-labels/{id}`)

---

### 🛒 ដំណាក់កាលទី ៣៖ ប្រព័ន្ធកន្ត្រកទំនិញ, ស្តុក Real-time & គូប៉ុង (Commerce & Integrity)
- [x] ប្រព័ន្ធកន្ត្រកទំនិញមានសុវត្ថិភាពខ្ពស់ផ្អែកលើ Session (`/cart`)
- [x] មុខងារកាត់ស្តុក Real-time ពេល Checkout ក្នុង `DB::transaction()`
- [x] មុខងារបូកស្តុកត្រឡប់ទៅឃ្លាំងវិញដោយស្វ័យប្រវត្តពេល Order ត្រូវបាន `cancelled`
- [x] ប្រព័ន្ធគូប៉ុងបញ្ចុះតម្លៃ (`coupons` table) គាំទ្រទាំង Percentage (%) និង Fixed Amount ($)
- [x] ប្រព័ន្ធគ្រាប់ពូជពេញចិត្ត (Wishlist API & Wishlist Page)
- [x] ទំព័រពិនិត្យវិក្កយបត្រជំហានចុងក្រោយ (Checkout) និងប្រវត្តិបញ្ជាទិញផ្ទាល់ខ្លួន (`/my-orders`)

---

### 👨‍🌾 ដំណាក់កាលទី ៤៖ បទពិសោធន៍អតិថិជន, ការវាយតម្លៃ & តាមដានការដឹកជញ្ជូន
- [x] មុខងារតាមដានការដឹកជញ្ជូនសាធារណៈតាមលេខទូរស័ព្ទ ឬលេខ Order (`/order-tracking`)
- [x] ប្រព័ន្ធវាយតម្លៃ និងសរសេរ Review គ្រាប់ពូជពីកសិករពិតប្រាកដ (`seed_reviews`)
- [x] ប្រព័ន្ធគាំទ្រពហុភាសាភ្លាមៗ (**🇰🇭 ភាសាខ្មែរ** និង **🇬🇧 ភាសាអង់គ្លេស**)
- [x] ប្រព័ន្ធការពារការទាយ Password ដោយ Rate Limiting `throttle:5,1` លើទំព័រ Login
- [x] ប្រព័ន្ធគ្រប់គ្រងសិទ្ធិអតិថិជន (Admin Customer Management & Role Promotion/Demotion)

---

### 🌟 ដំណាក់កាលទី ៥៖ ទំនើបកម្ម UI/UX & ឧបករណ៍កសិកម្មឆ្លាតវៃ
- [x] **ម៉ាស៊ីនគណនាគ្រាប់ពូជតាមទំហំដី (Smart Land & Seed Calculator)**៖ គណនាចំនួនកញ្ចប់ ចន្លោះគុម្ព និងទិន្នផលប៉ាន់ស្មាន
- [x] **ប្រតិទិនដាំដុះកសិកម្មឆ្លាតវៃ (Smart Agro Seasonal Planting Calendar)**៖
  - របារ Agro Radar បង្ហាញកាលបរិច្ឆេទ & ម៉ោងកម្ពុជាដើរជាក់ស្តែង
  - Timeline ១២ ខែ និងការ Highlight រដូវកាលសកម្មស្វ័យប្រវត្ត (វិច្ឆិកា = រដូវរំហើយ & ប្រាំង)
  - Clickable Seed Tags ភ្ជាប់ទៅស្វែងរកគ្រាប់ពូជភ្លាមៗ
- [x] **ការកែលម្អកាលបរិច្ឆេទក្នុង Admin Form**៖
  - HTML5 Date Picker ជាមួយ Shortcut Presets (+៦ខែ, +១ឆ្នាំ ★, +១៨ខែ)
  - ផ្ទាំងគណនារយៈពេលសុពលភាព Shelf-life Banner ស្វ័យប្រវត្ត
- [x] **ការកែលម្អតារាង Admin Orders Status**៖
  - Modern Interactive Status Dropdown Pills (🟡 Pending, 🔵 Processing, 🚚 Shipping, 🟢 Completed, 🔴 Cancelled)
  - Modal មើលព័ត៌មានរហ័ស (Quick View Modal) និងប៊ូតុងបោះពុម្ពវិក្កយបត្រ (Print Invoice)
- [x] **ការកែសម្រួលគម្លាត Sticky Navbar**៖
  - រៀបចំតុល្យភាពរវាង Menu និង Action Controls មិនឱ្យធ្លាក់ជួរ (No wrapping)
  - ប៊ូតុង Capsule ចូលប្រើ/ចុះឈ្មោះ ទំនើប និងតូចល្មម
- [x] **បញ្ជីរៀបចំវេចខ្ចប់ទំនិញប្រចាំថ្ងៃ (Daily Packing Manifest)** សម្រាប់ផ្នែកឃ្លាំង
- [x] **របាយការណ៍ហិរញ្ញវត្ថុ & ការទាញយក CSV (CSV Exports)** សម្រាប់ Orders និង Seeds

---

## ២. ស្ថានភាពតេស្តស្វ័យប្រវត្ត (Test Suite Coverage)

```
Test Suites: 1 passed
Tests:       37 passed (100%)
Assertions:  186 passed
Duration:    ~1.8s
```

* រាល់ Feature សំខាន់ៗទាំងអស់សុទ្ធតែមាន Test Case គ្របដណ្តប់ក្នុង `tests/Feature/SmartAgroTest.php`។

---

## 🔮 ផែនការអភិវឌ្ឍបន្ត (Future Roadmap)

- [ ] **KHQR & ABA PayWay Integration**: ភ្ជាប់ប្រព័ន្ធស្កេនទូទាត់ប្រាក់តាម KHQR ដោយផ្ទាល់ពេល Checkout
- [ ] **Telegram Bot Notifications**: បាញ់សារ Notification ទៅកាន់ Telegram Group សហគមន៍ភ្លាមៗពេលមានការកម្ម៉ង់ថ្មី
- [ ] **SMS Notification**: ផ្ញើសារ SMS ទៅកាន់ទូរស័ព្ទកសិករពេលទំនិញចេញដំណើរដឹកជញ្ជូន
- [ ] **IoT Farm Integration**: ភ្ជាប់ទិន្នន័យសីតុណ្ហភាព និងសំណើមពិតប្រាកដពីឧបករណ៍ Sensor ក្នុងកសិដ្ឋានតាក្រាម

