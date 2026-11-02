# 🔄 លំហូរការងារ និងដំណើរការប្រព័ន្ធ (System Workflows & Lifecycle Diagrams)
### Smart Agro-Seed Marketplace (Grow2Growth)

---

## ១. លំហូរការទិញ និងទូទាត់ប្រាក់ (Shopping & Checkout Flow)

```mermaid
sequenceDiagram
    autonumber
    actor Farmer as 👨‍🌾 កសិករ / អតិថិជន
    participant Browser as 🌐 Browser / UI
    participant Session as 🗄️ Session Store
    participant DB as 💾 Database
    actor Admin as 🛡️ អ្នកគ្រប់គ្រង Admin

    Farmer->>Browser: ចុច "ដាក់ចូលកន្ត្រក" (Add to Cart)
    Browser->>Session: រក្សាទុកមុខទំនិញក្នុង Session
    Farmer->>Browser: បើកទំព័រកន្ត្រកទំនិញ (/cart)
    Farmer->>Browser: វាយបញ្ចូលកូដបញ្ចុះតម្លៃ (Apply Coupon)
    Browser->>DB: ផ្ទៀងផ្ទាត់កូដបញ្ចុះតម្លៃ (Coupons Table)
    DB-->>Browser: ត្រឡប់ចំនួនទឹកប្រាក់បញ្ចុះតម្លៃ
    Farmer->>Browser: ចុច "បន្តទៅការទូទាត់" (Checkout)
    Browser->>Browser: ពិនិត្យសិទ្ធិ Auth (តម្រូវឱ្យ Login)
    Farmer->>Browser: បំពេញអាសយដ្ឋានដឹកជញ្ជូន & លេខទូរស័ព្ទ
    Farmer->>Browser: ចុច "បញ្ជាក់ការបញ្ជាទិញ" (Submit Form)
    Browser->>DB: ចាប់ផ្តើម DB::transaction()
    DB->>DB: បង្កើតទិន្នន័យ Orders & OrderItems
    DB->>DB: កាត់ស្តុកគ្រាប់ពូជ (Decrement seeds.stock)
    DB->>DB: បូកចំនួនប្រើប្រាស់គូប៉ុង (Increment coupons.used_count)
    DB-->>Browser: Commit Transaction
    Browser->>Session: សម្អាតកន្ត្រកទំនិញ (Clear Cart)
    Browser-->>Farmer: បង្ហាញវិក្កយបត្រ និងសារជោគជ័យ
```

---

## ២. វដ្តជីវិតនៃស្ថានភាពបញ្ជាទិញ (Order Status Lifecycle)

ស្ថានភាពបញ្ជាទិញរបស់អតិថិជនឆ្លងកាត់ដំណាក់កាលដូចខាងក្រោម៖

```mermaid
stateDiagram-v2
    [*] --> 🟡_Pending : អតិថិជនទើប Checkout រួចរាល់
    🟡_Pending --> 🔵_Processing : Admin ពិនិត្យឃើញ & យល់ព្រមរៀបចំ
    🟡_Pending --> 🔴_Cancelled : អតិថិជនបោះបង់ ឬខ្វះស្តុក
    🔵_Processing --> 🚚_Shipping : ទំនិញត្រូវបានបញ្ជូនទៅក្រុមហ៊ុនដឹកជញ្ជូន
    🔵_Processing --> 🔴_Cancelled : មានបញ្ហារអាក់រអួល
    🚚_Shipping --> 🟢_Completed : អតិថិជនទទួលបានគ្រាប់ពូជជោគជ័យ
    
    state 🔴_Cancelled {
        [*] --> RestoreStock : ប្រព័ន្ធកាត់បន្ថយទំនិញខូច
        RestoreStock --> [*] : បូកស្តុកត្រឡប់ទៅ Database វិញស្វ័យប្រវត្ត
    }
```

### ការពិពណ៌នាអំពីស្ថានភាពនីមួយៗ (Status Details):
* **🟡 Pending (រង់ចាំការពិនិត្យ)**៖ ការបញ្ជាទិញថ្មីទើបត្រូវបានបង្កើតឡើង។
* **🔵 Processing (កំពុងរៀបចំទំនិញ)**៖ ឃ្លាំងកំពុងរៀបចំវេចខ្ចប់គ្រាប់ពូជតាម Packing Manifest។
* **🚚 Shipping (កំពុងដឹកជញ្ជូន)**៖ កញ្ចប់គ្រាប់ពូជត្រូវបានប្រគល់ឱ្យដៃគូដឹកជញ្ជូន (Virak Buntham, J&T, etc.) អមដោយលេខកូដតាមដាន។
* **🟢 Completed (បានបញ្ចប់ជោគជ័យ)**៖ កសិករបានទទួលកញ្ចប់គ្រាប់ពូជត្រឹមត្រូវ។
* **🔴 Cancelled (បានបោះបង់)**៖ ការកម្ម៉ង់ត្រូវបានលុបចោល ហើយ **ស្តុកទំនិញត្រូវបានបូកបញ្ចូលត្រឡប់ទៅឃ្លាំងវិញស្វ័យប្រវត្ត**។

---

## ៣. លំហូរតាមដានប្រភពដើមគ្រាប់ពូជតាម QR Code (Traceability Flow)

```mermaid
flowchart TD
    A["📱 កសិករស្កេន QR Code លើកញ្ចប់គ្រាប់ពូជ"] --> B{"តើស្កេនតាមអ្វី?"}
    B -- ស្កេនកាមេរ៉ាទូរស័ព្ទ --> C["បើក URL ផ្ទាល់ /trace-batch?batch=..."]
    B -- វាយបញ្ចូលលេខបាច់ដោយដៃ --> D["ទំព័រស្វែងរក /traceability"]
    
    D --> E["បញ្ចូលលេខបាច់ ឧ. BTB-202611-001"]
    E --> F["SeedController::traceBatch()"]
    C --> F

    F --> G{"តើមានលេខបាច់ក្នុង Database?"}
    G -- រកមិនឃើញ --> H["បង្ហាញសារ Error: លេខបាច់មិនត្រឹមត្រូវ"]
    G -- រកឃើញ --> I["Eager Load: Seed + Category + Traceability Record"]

    I --> J["📄 បង្ហាញទំព័រ Traceability Result"]
    J --> K1["📍 ទីតាំងកសិដ្ឋាន (កសិដ្ឋានតាក្រាម បាត់ដំបង)"]
    J --> K2["🌱 អត្រាដំណុះ ៩៥%+ & កាលបរិច្ឆេទប្រមូលផល"]
    J --> K3["🔬 កម្រិត pH ដីសមស្រប & របបទឹកស្រោច"]
    J --> K4["📜 វិញ្ញាបនបត្រមន្ទីរពិសោធន៍ (Lab Certificate)"]
```

---

## ៤. លំហូរការគ្រប់គ្រងគ្រាប់ពូជ & បាច់ផលិតកម្ម (Admin Seed Creation Flow)

ដើម្បីសម្រួលការងាររបស់ Admin ឱ្យមានប្រសិទ្ធភាព ប្រព័ន្ធបានរៀបចំទម្រង់បែប **One-Stop Form**៖

```mermaid
flowchart LR
    A["ចូលទំព័រ /admin/seeds/create"] --> B["បំពេញព័ត៌មានគ្រាប់ពូជ (ឈ្មោះ, ប្រភេទ, តម្លៃ, ស្តុក, រូបភាព)"]
    B --> C["បំពេញព័ត៌មានបាច់កសិកម្ម (លេខបាច់, កសិដ្ឋាន, ថ្ងៃប្រមូលផល, ថ្ងៃផុតកំណត់)"]
    C --> D["ជ្រើសរើស Preset Shortcut ថ្ងៃខែ (+៦ខែ, +១ឆ្នាំ, +១៨ខែ)"]
    D --> E["ប្រព័ន្ធគណនារយៈពេលសុពលភាព (Shelf-life) ស្វ័យប្រវត្ត"]
    E --> F["ចុចរក្សាទុក (Submit Form)"]
    F --> G["DB Transaction: បង្កើត Seed + បង្កើត SeedTraceability ក្នុងពេលតែមួយ"]
    G --> H["ប្រព័ន្ធបង្កើត QR Code Link ស្វ័យប្រវត្ត"]
    H --> I["Admin អាចចុចបោះពុម្ពស្លាក Sticker Packaging Labels ភ្លាមៗ"]
```

---

## ៥. លំហូរការតាមដានការដឹកជញ្ជូនសាធារណៈ (Public Order Tracking Flow)

កសិករ ឬអ្នកទិញមិនចាំបាច់មានគណនី Login ក៏អាចដឹងពីស្ថានភាពកញ្ចប់ទំនិញរបស់ខ្លួនបានដែរ៖

1. ចូលទៅកាន់ `/order-tracking`។
2. វាយបញ្ចូល **លេខទូរស័ព្ទដែលបានកុម្ម៉ង់** (ឧ. `0883322318`) ឬ **លេខកូដវិក្កយបត្រ (Order ID)**។
3. ប្រព័ន្ធនឹងទាញយកព័ត៌មានមកបង្ហាញ៖
   - បញ្ជីគ្រាប់ពូជក្នុងកញ្ចប់
   - ស្ថានភាពបច្ចុប្បន្ន (Timeline ពី Pending ដល់ Completed)
   - អាសយដ្ឋានដឹកជញ្ជូន
   - ឈ្មោះ និងលេខទូរស័ព្ទអ្នកទទួល

