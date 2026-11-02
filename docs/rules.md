# 📋 វិធាន និងលក្ខខណ្ឌអាជីវកម្ម (Business Rules & Logic Constraints)
### Smart Agro-Seed Marketplace (Grow2Growth)

---

## ១. វិធានគ្រប់គ្រងស្តុកគ្រាប់ពូជ (Stock Management Rules)

### ១.១ ការកាត់ស្តុកពេលបញ្ជាទិញ (Stock Deduction at Checkout)
* **Real-time Deduction**: ស្តុកទំនិញត្រូវបានកាត់ចេញពី Database ដោយស្វ័យប្រវត្តនៅពេលដែលអតិថិជនចុច Submit លើទម្រង់ Checkout (`/checkout`)។
* **Atomic Transaction**: ការកាត់ស្តុកត្រូវធ្វើឡើងក្នុង `DB::transaction()`៖
  $$\text{Stock}_{\text{new}} = \text{Stock}_{\text{current}} - \text{Quantity}_{\text{purchased}}$$
* **Stock Exhaustion**: ប្រសិនបើចំនួនគ្រាប់ពូជដែលនៅសល់ក្នុងស្តុកមានតិចជាងចំនួនដែលអតិថិជនចង់ទិញ (`Stock < Quantity`) ប្រព័ន្ធនឹងបដិសេធប្រតិបត្តិការ និងជូនដំណឹងទៅអតិថិជនភ្លាមៗ។

### ១.២ ការបញ្ចូលស្តុកត្រឡប់មកវិញ (Stock Restoration on Cancellation)
* នៅពេល Admin ឬប្រព័ន្ធប្តូរស្ថានភាព Order ទៅជា **`cancelled` (បានបោះបង់)** នោះរាល់ចំនួនមុខទំនិញទាំងអស់ក្នុង Order នោះ នឹងត្រូវបានបូកបញ្ចូលត្រឡប់ទៅក្នុងតារាង `seeds.stock` វិញភ្លាមៗដោយស្វ័យប្រវត្ត៖
  $$\text{Stock}_{\text{restored}} = \text{Stock}_{\text{current}} + \text{Quantity}_{\text{cancelled}}$$

### ១.៣ ការគ្រប់គ្រងស្តុកក្នុងកន្ត្រក (Cart Stock Validation)
* អតិថិជនមិនអាចបន្ថែមចំនួនទំនិញក្នុងកន្ត្រកលើសពីចំនួនស្តុកជាក់ស្តែងក្នុងឃ្លាំងបានឡើយ។
* ប្រសិនបើទំនិញអស់ស្តុក (`stock <= 0`) ប៊ូតុង "ដាក់ចូលកន្ត្រក" នឹងត្រូវបិទដំណើរការ (`disabled`) ដោយស្វ័យប្រវត្ត អមដោយផ្លាក `អស់ស្តុក (Out of Stock)`។

---

## ២. វិធានគូប៉ុងបញ្ចុះតម្លៃ (Coupon Rules & Validation)

ដើម្បីឱ្យគូប៉ុងមួយអាចយកទៅអនុវត្តបានជោគជ័យ (`Apply Coupon`) វាត្រូវបំពេញតាមលក្ខខណ្ឌតឹងរ៉ឹងដូចខាងក្រោម៖

| លក្ខខណ្ឌ (Condition) | ការផ្ទៀងផ្ទាត់ (Validation Rule) | សារ Error ពេលមិនគ្រប់លក្ខខណ្ឌ |
|---|---|---|
| **១. ភាពសកម្ម (Active Status)** | `is_active === true` | "លេខកូដបញ្ចុះតម្លៃនេះត្រូវបានផ្អាកដំណើរការ។" |
| **២. សុពលភាពកាលបរិច្ឆេទ (Expiry Date)** | `expiry_date >= Today` | "លេខកូដបញ្ចុះតម្លៃនេះបានផុតកំណត់សុពលភាពហើយ។" |
| **៣. តម្លៃបញ្ជាទិញអប្បបរមា (Min Spend)**| `CartSubtotal >= min_spend` | "ទាល់តែទិញចាប់ពី $X.XX ឡើងទៅទើបអាចប្រើកូដនេះបាន។" |
| **៤. ចំនួនដងប្រើប្រាស់អតិបរមា (Max Uses)**| `used_count < max_uses` (បើ max_uses > 0) | "លេខកូដបញ្ចុះតម្លៃនេះត្រូវបានប្រើអស់ចំនួនកំណត់ហើយ។" |

### រូបមន្តគណនាការបញ្ចុះតម្លៃ (Discount Calculation):
1. **ប្រភេទ Percentage (%)**:
   $$\text{Discount} = \text{Subtotal} \times \left(\frac{\text{DiscountValue}}{100}\right)$$
2. **ប្រភេទ Fixed Amount ($)**:
   $$\text{Discount} = \min(\text{DiscountValue}, \text{Subtotal})$$
3. **ការតាមដានការប្រើប្រាស់ (Usage Increment)**:
   - នៅពេល Checkout ជោគជ័យ ចំនួន `used_count` របស់គូប៉ុងនឹងត្រូវបូកបន្ថែមមួយ (`+1`) ដោយស្វ័យប្រវត្ត។

---

## ៣. វិធានតាមដានប្រភពដើម & លេខបាច់ (Traceability & Batch Numbering)

### ៣.១ ទម្រង់លេខបាច់ (Batch Number Format)
* លេខបាច់ផលិតកម្មត្រូវតែមានលក្ខណៈពិសេស (Unique) មិនស្ទួនគ្នាក្នុងប្រព័ន្ធឡើយ។
* ស្តង់ដារកំណត់៖ `BTH-YYYYMMDD-XXXX` (ឧទាហរណ៍៖ `BTH-20261102-0042` ឬ `BTB-LET-2026-001`)។

### ៣.២ ស្តង់ដារគុណភាពគ្រាប់ពូជតាមក្បួនកសិកម្ម (Agro Quality Standards)
រាល់បាច់គ្រាប់ពូជដែលអនុញ្ញាតឱ្យចេញវិញ្ញាបនបត្រ (Lab Certificate) ត្រូវឆ្លងកាត់ការពិសោធន៍៖
* **អត្រាដំណុះ (Germination Rate)**៖ ត្រូវតែ $\ge ៨៥\%$ (កសិដ្ឋានតាក្រាមធានា $៩៥\%+$)
* **ភាពសុទ្ធនៃពូជ (Purity Percentage)**៖ ត្រូវតែ $\ge ៩៨\%$
* **សំណើមគ្រាប់ (Moisture Content)**៖ ត្រូវតែ $\le ១០\%$ (ការពារការដុះផ្សិតក្នុងកញ្ចប់)

### ៣.៣ វិធានសុពលភាពកាលបរិច្ឆេទ (Date Integrity Rules)
* **កាលបរិច្ឆេទប្រមូលផល (Harvest Date)** ត្រូវតែតូចជាង ឬស្មើ **កាលបរិច្ឆេទវេចខ្ចប់ (Packaging Date)**។
* **កាលបរិច្ឆេទផុតកំណត់ (Expiry Date)** ត្រូវតែធំជាងកាលបរិច្ឆេទប្រមូលផលយ៉ាងហោចណាស់ ៩០ ថ្ងៃ។
* **ការជូនដំណឹងគ្រាប់ពូជជិតផុតកំណត់ (Expiring Batches Warning)**៖
  - នៅលើ Admin Dashboard ប្រព័ន្ធនឹងគណនា `expiry_date - Today`៖
  - ប្រសិនបើរយៈពេលនៅសល់ $\le ៦០$ ថ្ងៃ ប្រព័ន្ធនឹងបង្ហាញផ្លាក Alert ពណ៌លឿង/ក្រហមបន្ទាន់ ដើម្បីឱ្យ Admin បញ្ចុះតម្លៃពិសេស ឬផ្លាស់ប្តូរស្តុកចេញ។

---

## ៤. វិធានការវាយតម្លៃ និងមតិយោបល់ (Seed Reviews & Ratings)

1. **ការកំណត់អ្នកវាយតម្លៃ (Authorized Reviewers)**:
   - អតិថិជនត្រូវតែចូលគណនី (Authenticated User) ទើបអាចសរសេរ Review បាន។
2. **កម្រិតពិន្ទុ (Rating Scale)**:
   - ពិន្ទុផ្កាយត្រូវតែនៅចន្លោះពី **១ ដល់ ៥ ផ្កាយ** (`integer: 1-5`)។
3. **ការគណនាពិន្ទុមធ្យម (Average Rating Calculation)**:
   $$\text{AverageRating} = \frac{\sum \text{Ratings}}{\text{TotalReviews}}$$
   - បង្ហាញជាទម្រង់ទសភាគ ១ ខ្ទង់ (ឧ. `4.8 ★ / 5.0`) លើកាតគ្រាប់ពូជ និងទំព័រព័ត៌មានលម្អិត។
4. **សិទ្ធិលុបមតិ (Moderation Rights)**:
   - Admin មានសិទ្ធិលុបមតិដែលមិនសមរម្យចេញពីផ្ទាំង `/admin/reviews`។

---

## ៥. វិធានសុវត្ថិភាព និងការគ្រប់គ្រង Session (Security & Session Rules)

1. **Login Brute-Force Rate Limiting**:
   - អនុញ្ញាតឱ្យបញ្ចូល Password ខុសត្រឹមតែ **៥ ដងក្នុងរយៈពេល ១ នាទី**។ លើសពីនេះ ប្រព័ន្ធនឹង Lock IP រយៈពេល ៦០ វិនាទី (HTTP 429 Too Many Requests)។
2. **Cart & Wishlist Persistence**:
   - កន្ត្រកទំនិញត្រូវបានរក្សាទុកក្នុង **Server-side Session** (`session('cart')`) ដែលមានសុវត្ថិភាព និងមិនងាយបាត់បង់ពេល Refresh ទំព័រ។
   - Wishlist ត្រូវបាន sync តាមរយៈ AJAX/JSON API ដោយមិនបាច់ Reload ទំព័រឡើយ។

