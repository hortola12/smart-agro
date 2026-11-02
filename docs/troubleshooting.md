# 🛠️ ការដោះស្រាយបញ្ហាបច្ចេកទេស និងសំណួរញឹកញាប់ (Troubleshooting & FAQ)
### Smart Agro-Seed Marketplace (Grow2Growth)

---

## ១. បញ្ហាបច្ចេកទេសទូទៅ និងដំណោះស្រាយ (Common Issues & Fixes)

### ⚠️ បញ្ហាទី ១៖ `419 Page Expired` នៅពេល Submit Form
* **រោគសញ្ញា**៖ ពេលចុច Checkout, Login, ឬ Submit Form ណាមួយ ប្រព័ន្ធលោតចេញផ្ទាំង `419 Page Expired`។
* **មូលហេតុ**៖
  1. ទំព័រនៅចោលយូរពេក ធ្វើឱ្យ CSRF Session Token ផុតកំណត់។
  2. Form ខ្វះ Tag `@csrf`។
* **ដំណោះស្រាយ**៖
  - ពិនិត្យមើលថាតើក្នុងទម្រង់ `<form>` មានដាក់ `@csrf` រួចរាល់ហើយឬនៅ។
  - Refresh ទំព័រឡើងវិញ ឬសម្អាត Browser Cookies។

---

### ⚠️ បញ្ហាទី ២៖ `403 Forbidden` ឬត្រូវបានបញ្ជូនមកទំព័រដើម (`Redirect to /`)
* **រោគសញ្ញា**៖ ព្យាយាមបើក `/admin/dashboard` តែប្រព័ន្ធរុញមកកាន់ទំព័រដើម (`/`) វិញជាមួយសារ Alert ក្រហម។
* **មូលហេតុ**៖ គណនីដែលកំពុង Login មាន Role ជា `customer` មិនមែនជា `admin` ឡើយ។
* **ដំណោះស្រាយ**៖
  - ចូលទៅកែប្រែក្នុង Database ដោយផ្ទាល់ ឬតាមរយៈ `tinker`៖
    ```bash
    php artisan tinker
    > $u = App\Models\User::where('email', 'your_email@gmail.com')->first();
    > $u->role = 'admin';
    > $u->save();
    ```

---

### ⚠️ បញ្ហាទី ៣៖ រូបភាពគ្រាប់ពូជមិនបង្ហាញ (Broken Images / 404 on Storage)
* **រោគសញ្ញា**៖ គ្រាប់ពូជដែលបាន Upload រូបភាពរួច មិនបង្ហាញរូបភាពលើទំព័រដើមឡើយ។
* **មូលហេតុ**៖ មិនទាន់បានបង្កើត Symbolic Link រវាង `storage/app/public` ទៅកាន់ `public/storage`។
* **ដំណោះស្រាយ**៖
  ```bash
  php artisan storage:link
  ```

---

### ⚠️ បញ្ហាទី ៤៖ `429 Too Many Requests` ពេលព្យាយាម Login
* **រោគសញ្ញា**៖ ប្រព័ន្ធរារាំងមិនឱ្យ Login និងបង្ហាញសារ `Too Many Requests`។
* **មូលហេតុ**៖ វាយ Password ខុសលើសពី ៥ ដងក្នុងរយៈពេល ១ នាទី (Brute-Force Rate Limiting `throttle:5,1`)។
* **ដំណោះស្រាយ**៖
  - រង់ចាំរយៈពេល ៦០ វិនាទី ទើបព្យាយាមម្តងទៀត។
  - ឬសម្អាត Cache តាមរយៈ Terminal៖
    ```bash
    php artisan cache:clear
    ```

---

### ⚠️ បញ្ហាទី ៥៖ `SQLSTATE[HY000] [2002] Connection refused`
* **រោគសញ្ញា**៖ មិនអាចទាញទិន្នន័យពី Database បានឡើយ។
* **មូលហេតុ**៖ សេវាកម្ម MySQL/MariaDB មិនទាន់ដំណើរការ ឬព័ត៌មានក្នុង `.env` ខុស។
* **ដំណោះស្រាយ**៖
  - ពិនិត្យសេវាកម្ម MySQL៖
    ```bash
    sudo systemctl status mysql
    sudo systemctl start mysql
    ```
  - ផ្ទៀងផ្ទាត់ `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` ក្នុងឯកសារ `.env`។

---

### ⚠️ បញ្ហាទី ៦៖ ការកែសម្រួលកូដ Blade ឬ Routes មិនឃើញផ្លាស់ប្តូរ
* **រោគសញ្ញា**៖ បានកែសម្រួលកូដក្នុង View ឬ Route រួចរាល់ តែ Browser នៅតែបង្ហាញទម្រង់ចាស់។
* **មូលហេតុ**៖ Laravel បាន Cache ឯកសារចាស់ទុកក្នុង Memory។
* **ដំណោះស្រាយ**៖
  ```bash
  php artisan optimize:clear
  ```

---

## ២. របៀបពិនិត្យមើល Error Log (Inspecting Logs)

នៅពេលមាន Error `500 Server Error` កើតឡើង សូមពិនិត្យមើលឯកសារ Log របស់ Laravel ដើម្បីដឹងពីមូលហេតុពិតប្រាកដ៖

```bash
# មើល Log ចុងក្រោយគេបង្អស់ (Tail Live Log)
tail -f storage/logs/laravel.log

# ឬបើកមើល ១០០ បន្ទាត់ចុងក្រោយ
tail -n 100 storage/logs/laravel.log
```

