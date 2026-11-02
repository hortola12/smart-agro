# 🧪 មគ្គុទ្ទេសក៍ការធ្វើតេស្តគុណភាពប្រព័ន្ធ (Quality Assurance & Testing Guide)
### Smart Agro-Seed Marketplace (Grow2Growth)

---

## ១. ទស្សនវិជ្ជានៃការធ្វើតេស្ត (Testing Philosophy)

ប្រព័ន្ធ **Smart Agro-Seed Marketplace** ត្រូវបានធានាគុណភាពកូដតាមរយៈ **Automated Feature Testing** លើ **PHPUnit 11.x** ដោយផ្តោតលើភាពត្រឹមត្រូវនៃទិន្នន័យពាណិជ្ជកម្មកសិកម្ម (Data Integrity) ដូចជា៖
* ការកាត់ស្តុក និងបូកស្តុកត្រឡប់វិញ
* ការគណនាការបញ្ចុះតម្លៃរបស់គូប៉ុង
* ការពារការលួចចូលប្រព័ន្ធ និងការបំពានសិទ្ធិ (Security & RBAC)
* ភាពត្រឹមត្រូវនៃប្រព័ន្ធតាមដានលេខបាច់ QR (Traceability)

---

## ២. របៀបដំណើរការតេស្តស្វ័យប្រវត្ត (Running Automated Tests)

```bash
# ១. ដំណើរការតេស្តទាំងអស់ក្នុងប្រព័ន្ធ
php artisan test

# ២. ដំណើរការតែ Test Class របស់ Smart Agro
php artisan test --filter=SmartAgroTest

# ៣. ដំណើរការតេស្តជាក់លាក់ណាមួយ (ឧ. តេស្តកាត់ស្តុក)
php artisan test --filter=test_stock_is_deducted_at_checkout_and_restored_on_cancellation

# ៤. បង្ហាញព័ត៌មានលម្អិតពេលតេស្ត (Verbose Mode)
php artisan test -v
```

### លទ្ធផលតេស្តស្តង់ដារបច្ចុប្បន្ន (Current Status):
```
PASS  Tests\Feature\SmartAgroTest
✓ homepage loads successfully with seeds
✓ trace route finds batch by batch number and search query
✓ checkout creates order with authenticated user id
✓ login rate limiting blocks after too many attempts
✓ admin seed list is paginated
✓ stock is deducted at checkout and restored on cancellation
✓ admin can manage categories
✓ admin can view customers list
✓ search supports category filter
✓ add to cart supports custom quantity
✓ admin can quick restock seed
✓ admin can export orders and seeds csv
✓ admin can view print labels sheet
✓ seed certificate view loads successfully with lab parameters
✓ public order tracking finds order by phone and order id
✓ admin orders filter by date and displays kpi cards
✓ admin can view daily packing manifest
✓ admin dashboard displays expiring batches alert
✓ customer can submit review for seed
✓ seed detail displays reviews and average rating
✓ admin can view and delete reviews
✓ user can toggle wishlist via post and json
✓ wishlist page displays saved seeds
✓ user can move wishlist seed to cart and clear wishlist
✓ user can apply and remove coupon in cart
✓ checkout applies discount and tracks coupon usage
✓ admin can manage coupons
✓ admin navbar notification bell displays pending orders and alerts
✓ admin can view and update settings
✓ admin can view and bulk update seed stocks and prices
✓ admin can view reports and export csv
✓ admin can promote and demote user roles
✓ admin orders table renders modern status pills and quick view
✓ admin create seed page has modern date picker and presets
✓ admin navbar renders cambodia live clock and calendar

Tests:    37 passed (186 assertions)
Duration: ~1.8s
```

---

## ៣. បញ្ជីសេណារីយ៉ូតេស្តដោយដៃ (Manual QA Checklist)

សម្រាប់ Tester ឬអ្នកត្រួតពិនិត្យប្រព័ន្ធ សូមអនុវត្តតាម Checklist ខាងក្រោមមុនពេល Release កំណែថ្មី៖

| # | ផ្នែកដែលត្រូវតេស្ត (Test Area) | សកម្មភាពអនុវត្ត (Test Steps) | លទ្ធផលរំពឹងទុក (Expected Result) |
|---|---|---|---|
| **១** | **កន្ត្រកទំនិញ (Cart)** | បន្ថែមគ្រាប់ពូជចូលកន្ត្រក ចុច (+) និង (-) | ចំនួន និងតម្លៃសរុបប្រែប្រួលត្រឹមត្រូវ មិនឱ្យ (-) ក្រោម ១ |
| **២** | **ទំនិញអស់ស្តុក** | សាកល្បងចុចទិញទំនិញដែល `stock = 0` | ប៊ូតុង disabled និងមានផ្លាក `អស់ស្តុក` មិនអាចដាក់ចូលកន្ត្រកបាន |
| **៣** | **គូប៉ុងផុតកំណត់** | វាយបញ្ចូលកូដគូប៉ុងដែលផុតកំណត់ ឬមិនទាន់ដល់ Min Spend | បង្ហាញសារ Error ពន្យល់ច្បាស់លាស់ មិនកាត់ទឹកប្រាក់ឡើយ |
| **៤** | **Checkout កាត់ស្តុក** | បញ្ជាទិញគ្រាប់ពូជចំនួន ២ កញ្ចប់ (ពីស្តុក ១០) | ស្តុកក្នុង Database ធ្លាក់សល់ ៨ ភ្លាមៗ |
| **៥** | **បោះបង់ការកម្ម៉ង់** | Admin ប្តូរ Status Order ទៅជា `cancelled` | ស្តុកគ្រាប់ពូជ ២ កញ្ចប់ បូកត្រឡប់មក ១០ វិញស្វ័យប្រវត្ត |
| **៦** | **ស្កេន QR តាមដាន** | ស្កេន QR ឬវាយលេខបាច់ក្នុង `/traceability` | បង្ហាញព័ត៌មានកសិដ្ឋាន អត្រាដំណុះ និងទាញយក Certificate បាន |
| **៧** | **តាមដានដឹកជញ្ជូន** | វាយលេខទូរស័ព្ទក្នុង `/order-tracking` | បង្ហាញរាល់ Orders ទាំងអស់របស់លេខនោះ អមដោយ Timeline |
| **៨** | **Rate Limiting** | វាយ Password ខុស ៦ ដងជាប់គ្នាលើ `/login` | ប្រព័ន្ធ Lock ស្នើសុំឱ្យរង់ចាំ ៦០ វិនាទី (429 Error) |
| **៩** | **សិទ្ធិ Admin** | ចូលគណនី Customer ហើយព្យាយាមបើក `/admin/dashboard` | ត្រូវបញ្ជូនត្រឡប់មក Homepage វិញជាមួយសារ Alert ក្រហម |
| **១០**| **ប្តូរភាសា** | ចុចប្តូរ 🇰🇭 KH / 🇬🇧 EN លើរបារ Navbar | អក្សរលើ Menu, Banner, និងប៊ូតុងប្តូរភាសាត្រូវគ្នាភ្លាមៗ |

---

## ៤. ការរៀបចំ CI/CD (Continuous Integration with GitHub Actions)

ឯកសារកំណត់ `.github/workflows/tests.yml` សម្រាប់ឱ្យ GitHub រត់តេស្តស្វ័យប្រវត្តរាល់ពេលមាន `git push` ឬ `Pull Request`៖

```yaml
name: Run Automated Tests

on:
  push:
    branches: [ "main", "Developer" ]
  pull_request:
    branches: [ "main", "Developer" ]

jobs:
  laravel-tests:
    runs-on: ubuntu-latest

    steps:
    - uses: actions/checkout@v4

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        extensions: mbstring, xml, ctype, iconv, intl, pdo_sqlite, dom, filter, gd, json
        coverage: none

    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"

    - name: Install Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

    - name: Generate key
      run: php artisan key:generate

    - name: Directory Permissions
      run: chmod -R 777 storage bootstrap/cache

    - name: Run Tests
      run: php artisan test
```

