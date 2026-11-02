# 🛣️ បញ្ជីផ្លូវ API និង Web Routes (Route Reference & Endpoints)
### Smart Agro-Seed Marketplace (Grow2Growth)

---

## ១. ផ្លូវសាធារណៈ (Public / Frontend Routes)

ផ្លូវទាំងនេះមិនតម្រូវឱ្យមានការ Login ឡើយ ភ្ញៀវទូទៅអាចចូលមើល និងប្រើប្រាស់បានគ្រប់ពេលវេលា៖

| វិធីសាស្ត្រ (Method) | ផ្លូវ (URI) | ឈ្មោះផ្លូវ (Route Name) | ការពិពណ៌នា និងគោលបំណង (Description) |
|---|---|---|---|
| `GET` | `/` | - | ទំព័រដើម Frontend (Hero, Categories, Featured Seeds, Seasonal Calendar, Calculator) |
| `GET` | `/seed-detail/{id}` | - | ទំព័រព័ត៌មានលម្អិតនៃគ្រាប់ពូជបន្លែនីមួយៗ |
| `POST` | `/seed-detail/{id}/review` | `seed.review.store` | បញ្ជូនមតិយោបល់ និងការវាយតម្លៃផ្កាយ (តម្រូវឱ្យ Login) |
| `GET` | `/seed-certificate/{id}` | `seed.certificate` | ទំព័រវិញ្ញាបនបត្រមន្ទីរពិសោធន៍កសិកម្មផ្លូវការ (Official Lab Certificate) |
| `GET` | `/change-language/{locale}` | - | ប្តូរភាសាប្រព័ន្ធរវាងខ្មែរ និងអង់គ្លេស (`kh` ឬ `en`) |
| `GET` | `/search` | - | ស្វែងរកគ្រាប់ពូជតាមពាក្យគន្លឹះ និងតម្រងតាមប្រភេទបន្លែ (`category_id`) |
| `GET` | `/trace` | - | ស្វែងរកបាច់គ្រាប់ពូជតាមរយៈ URL ឬ Query Parameter |
| `GET` | `/trace-batch` | - | ស្វែងរកបាច់គ្រាប់ពូជតាមរយៈលេខបាច់ QR |
| `GET` | `/traceability` | - | ទម្រង់បែបបទសម្រាប់វាយបញ្ចូលលេខបាច់ស្វែងរកដោយដៃ |
| `GET` | `/order-tracking` | `order.tracking` | តាមដានស្ថានភាពការដឹកជញ្ជូនតាមលេខទូរស័ព្ទ ឬលេខវិក្កយបត្រ |

---

## ២. ផ្លូវកន្ត្រកទំនិញ & Wishlist (Cart & Wishlist Routes)

| វិធីសាស្ត្រ (Method) | ផ្លូវ (URI) | ឈ្មោះផ្លូវ (Route Name) | ការពិពណ៌នា និងគោលបំណង (Description) |
|---|---|---|---|
| `GET` | `/cart` | - | បើកមើលកន្ត្រកទំនិញបច្ចុប្បន្ន (Session-based) |
| `POST` | `/add-to-cart/{id}` | - | ដាក់គ្រាប់ពូជចូលកន្ត្រក (គាំទ្រ Quantity តាមចិត្ត) |
| `PATCH` | `/update-cart` | - | កែប្រែចំនួនគ្រាប់ពូជក្នុងកន្ត្រក (+) ឬ (-) |
| `DELETE` | `/remove-from-cart` | - | លុបមុខទំនិញចេញពីកន្ត្រក |
| `POST` | `/cart/apply-coupon` | `cart.apply_coupon` | អនុវត្តកូដបញ្ចុះតម្លៃលើកន្ត្រកទំនិញ |
| `POST` | `/cart/remove-coupon` | `cart.remove_coupon` | ដកកូដបញ្ចុះតម្លៃចេញពីកន្ត្រកទំនិញ |
| `GET` | `/wishlist` | `wishlist.show` | ទំព័រមើលបញ្ជីគ្រាប់ពូជពេញចិត្ត |
| `POST` | `/wishlist/toggle/{id}` | `wishlist.toggle` | Toggle បន្ថែម/ដកគ្រាប់ពូជពី Wishlist (AJAX JSON) |
| `POST` | `/wishlist/move-to-cart/{id}` | `wishlist.move_to_cart` | ផ្ទេរគ្រាប់ពូជពី Wishlist ចូលទៅកាន់កន្ត្រកទំនិញ |
| `POST` | `/wishlist/clear` | `wishlist.clear` | សម្អាតបញ្ជីគ្រាប់ពូជពេញចិត្តទាំងអស់ |

---

## ៣. ផ្លូវចូលប្រើប្រាស់ និងចុះឈ្មោះ (Authentication Routes)

| វិធីសាស្ត្រ (Method) | ផ្លូវ (URI) | ឈ្មោះផ្លូវ (Route Name) | ការពិពណ៌នា និងគោលបំណង (Description) |
|---|---|---|---|
| `GET` | `/login` | `login` | បើកទំព័រទម្រង់ Login ចូលប្រព័ន្ធ |
| `POST` | `/login` | - | ផ្ទៀងផ្ទាត់គណនី (ការពារដោយ Rate Limiting `throttle:5,1`) |
| `GET` | `/register` | `register` | បើកទំព័រចុះឈ្មោះសមាជិក/កសិករថ្មី |
| `POST` | `/register` | - | បង្កើតគណនីថ្មី (Default Role: `customer`) |
| `POST` | `/logout` | `logout` | ចាកចេញពីប្រព័ន្ធ និងបំផ្លាញ Session (POST CSRF Protected) |

---

## ៤. ផ្លូវការពារសម្រាប់អតិថិជន (Protected Customer Routes - `auth`)

| វិធីសាស្ត្រ (Method) | ផ្លូវ (URI) | ឈ្មោះផ្លូវ (Route Name) | ការពិពណ៌នា និងគោលបំណង (Description) |
|---|---|---|---|
| `GET` | `/checkout` | `checkout` | ទំព័រត្រួតពិនិត្យ និងបំពេញអាសយដ្ឋានដឹកជញ្ជូនចុងក្រោយ |
| `POST` | `/checkout` | - | Submit បញ្ជាទិញ កាត់ស្តុក និងបង្កើតវិក្កយបត្រ (DB Transaction) |
| `GET` | `/my-orders` | `my.orders` | មើលប្រវត្តិវិក្កយបត្រ និងទាញយកព័ត៌មានទិញដូរផ្ទាល់ខ្លួន |

---

## ៥. ផ្លូវការពារសម្រាប់អ្នកគ្រប់គ្រង (Protected Admin Routes - `AdminMiddleware`)

ផ្លូវទាំងអស់នេះតម្រូវឱ្យអ្នកប្រើប្រាស់មានសិទ្ធិជា `admin`៖

| វិធីសាស្ត្រ (Method) | ផ្លូវ (URI) | ឈ្មោះផ្លូវ (Route Name) | ការពិពណ៌នា និងគោលបំណង (Description) |
|---|---|---|---|
| `GET` | `/admin/dashboard` | - | ផ្ទាំងគ្រប់គ្រងទូទៅ គណនាស្ថិតិលក់ និង Alert គ្រាប់ពូជជិតផុតកំណត់ |
| `GET` | `/admin/seeds` | - | បញ្ជីគ្រប់គ្រងគ្រាប់ពូជទាំងអស់ (Paginated + Search) |
| `GET` | `/admin/seeds/create` | - | ទម្រង់បន្ថែមគ្រាប់ពូជ & បាច់ Traceability ក្នុងពេលតែមួយ |
| `POST` | `/admin/seeds/store` | - | រក្សាទុកគ្រាប់ពូជថ្មីចូល Database |
| `GET` | `/admin/seeds/edit/{id}` | - | ទម្រង់កែសម្រួលព័ត៌មានគ្រាប់ពូជ |
| `PUT` | `/admin/seeds/update/{id}` | - | កែប្រែទិន្នន័យគ្រាប់ពូជ |
| `DELETE`| `/admin/seeds/delete/{id}` | - | លុបគ្រាប់ពូជចេញពីប្រព័ន្ធ |
| `GET` | `/admin/seeds/bulk-update` | `admin.seeds.bulk_edit` | ទំព័រកែសម្រួលស្តុក និងតម្លៃគ្រាប់ពូជម្តងទាំងអស់ (Spreadsheet style) |
| `POST` | `/admin/seeds/bulk-update` | `admin.seeds.bulk_update`| រក្សាទុកការកែប្រែ Bulk Update |
| `POST` | `/admin/seeds/quick-restock/{id}` | - | ប៊ូតុងបន្ថែមស្តុកបន្ទាន់ (+10, +50, +100) លើតារាងគ្រាប់ពូជ |
| `GET` | `/admin/seeds/print-labels/{id}` | - | បោះពុម្ពស្លាក Sticker Packaging Labels ជាសន្លឹក |
| `GET` | `/admin/seeds/export` | - | ទាញយករបាយការណ៍បញ្ជីគ្រាប់ពូជជាឯកសារ CSV |
| `GET` | `/admin/traceability` | - | បញ្ជីគ្រប់គ្រងបាច់ Traceability ទាំងអស់ |
| `GET` | `/admin/seeds/traceability/{seed_id}` | - | បង្កើតបាច់ Traceability បន្ថែមសម្រាប់គ្រាប់ពូជជាក់លាក់ |
| `POST` | `/admin/seeds/traceability/store/{seed_id}` | - | រក្សាទុកបាច់ Traceability ថ្មី |
| `PUT` | `/admin/traceability/update/{id}` | - | កែប្រែព័ត៌មានបាច់ Traceability |
| `DELETE`| `/admin/traceability/delete/{id}` | - | លុបបាច់ Traceability |
| `GET` | `/admin/traceability/show/{id}` | - | មើលព័ត៌មានលម្អិតនៃបាច់ Traceability ជាក់លាក់ |
| `GET` | `/admin/orders` | - | បញ្ជីគ្រប់គ្រងការកម្ម៉ង់ទិញសរុប (Filter តាមកាលបរិច្ឆេទ & Status) |
| `POST` | `/admin/orders/status/{id}` | - | ប្តូរស្ថានភាព Order (បូកស្តុកត្រឡប់វិញបើ Cancelled) |
| `DELETE`| `/admin/orders/delete/{id}` | - | លុបវិក្កយបត្រចេញពីប្រព័ន្ធ |
| `GET` | `/admin/orders/packing-manifest` | - | បោះពុម្ពបញ្ជីរៀបចំទំនិញប្រចាំថ្ងៃសម្រាប់ឃ្លាំង (Daily Packing Sheet) |
| `GET` | `/admin/orders/export` | - | ទាញយករបាយការណ៍បញ្ជី Orders ជាឯកសារ CSV |
| `GET` | `/admin/categories` | - | គ្រប់គ្រងប្រភេទបន្លែ (CRUD Categories) |
| `POST` | `/admin/categories/store` | - | បង្កើតប្រភេទបន្លែថ្មី |
| `PUT` | `/admin/categories/update/{id}` | - | កែប្រែប្រភេទបន្លែ |
| `DELETE`| `/admin/categories/delete/{id}` | - | លុបប្រភេទបន្លែ |
| `GET` | `/admin/customers` | - | បញ្ជីអតិថិជន និងកសិករទាំងអស់ |
| `POST` | `/admin/customers/role/{id}` | `admin.customers.update_role` | ដំឡើង ឬទម្លាក់សិទ្ធិ User (`customer` <-> `admin`) |
| `GET` | `/admin/reviews` | - | គ្រប់គ្រង និងលុបមតិយោបល់អតិថិជន (Review Moderation) |
| `DELETE`| `/admin/reviews/delete/{id}` | - | លុបមតិដែលមិនសមរម្យ |
| `GET` | `/admin/coupons` | - | គ្រប់គ្រងកូដបញ្ចុះតម្លៃទាំងអស់ (Coupons CRUD) |
| `POST` | `/admin/coupons` | - | បង្កើតកូដបញ្ចុះតម្លៃថ្មី |
| `POST` | `/admin/coupons/{id}/toggle` | - | បើក ឬបិទដំណើរការគូប៉ុង |
| `DELETE`| `/admin/coupons/{id}` | - | លុបកូដបញ្ចុះតម្លៃ |
| `GET` | `/admin/settings` | `admin.settings` | ទំព័រការកំណត់ទូទៅសហគមន៍ & ប្រព័ន្ធ |
| `POST` | `/admin/settings` | `admin.settings.update` | រក្សាទុកការកំណត់ទូទៅ |
| `GET` | `/admin/reports` | `admin.reports` | របាយការណ៍ហិរញ្ញវត្ថុ និងស្ថិតិលក់កម្រិតខ្ពស់ |
| `GET` | `/admin/reports/export` | `admin.reports.export` | ទាញយករបាយការណ៍ហិរញ្ញវត្ថុជា CSV |

