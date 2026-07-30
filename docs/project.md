# Ferry Sirilanta Admin

ระบบ Admin สำหรับจัดการจองตั๋วเรือเฟอร์รี่ **Sirilanta** — จัดการ booking, agent, broker, employee, credit/wallet, รายงาน และการพิมพ์ตั๋ว

---

## Tech Stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Auth | Laravel Breeze (session) |
| Frontend UI | **[Vuexy HTML Admin Template](https://demos.pixinvent.com/vuexy-html-admin-template/documentation/)** (Bootstrap + Tabler icons) |
| PDF | `barryvdh/laravel-dompdf` |
| Activity log | `spatie/laravel-activitylog` |
| Models helper | `reliese/laravel` |
| Build | Vite 6, Alpine.js |
| Database | MySQL (production/UAT), SQLite (local optional) |

### UI Theme — Vuexy

โปรเจกต์ใช้ธีม **Vuexy** จาก Pixinvent เป็นฐานของ layout, components, utility classes และ pattern ของหน้า admin

- Documentation: [Vuexy HTML Admin Template Docs](https://demos.pixinvent.com/vuexy-html-admin-template/documentation/)
- Layout หลัก: `resources/views/layouts/default.blade.php`
- Menu: `resources/views/layouts/section/nav.blade.php`
- Components ที่ใช้บ่อย: card, form floating, datatable, badge `bg-label-*`, icon `ti tabler-*`

เมื่อออกแบบหน้าใหม่ ให้ยึด pattern จาก Vuexy docs (Cards, Forms, Datatables, Buttons) และของที่มีอยู่แล้วในโปรเจกต์

---

## Roles

| Role | คำอธิบาย | เมนูหลัก |
| --- | --- | --- |
| `ADMIN` | ผู้ดูแลระบบ | Bookings, Routes, Reports, Setting (Agent / Broker / Employee / Station ฯลฯ) |
| `agent` | เอเจนต์ขาย | Bookings, Wallet |
| `broker` | นายหน้า / โบรกเกอร์ | Bookings, ประวัติการทำรายการ, พนักงานขาย |
| `employee` | พนักงานขาย (ใต้ sales partner) | Bookings, Your Point |
| `broker_employee` | พนักงานภายใต้ broker | (ขึ้นกับการตั้งค่า user) |

สิทธิ์หลายส่วนถูกกรองด้วย `sales_partner_id` ของ user ที่ล็อกอิน (ยกเว้น `ADMIN`)

---

## โมดูลหลัก

### Booking
- รายการจอง / ค้นหา / filter ตามวันที่ (booking date / travel date)
- สร้าง booking (เลือกเส้นทาง → บันทึก draft → ชำระเงิน)
- ดูรายละเอียด, พิมพ์ตั๋ว A4 / detail
- Export Excel (CSV) และ PDF ตาม filter ปัจจุบัน
- ตารางแสดงผลแยกตาม role: `agent` / `broker` / `employee` / `default`
- Payment link (เมื่อยังไม่ชำระ): ใช้ `PAYMENT_URL`

### Agent / Sales Partner
- จัดการ agent, wallet / top-up, approve top-up
- Sales partner ประเภท agent / broker

### Broker
- Credit limit / credit used
- Discount (`discount`, `discount_type`: `per_ticket` / `per_seat`)
- Users ภายใต้ broker
- Transaction history (`broker.transactions`)

### Employee
- Point จาก booking ที่ชำระแล้ว (`ispayment = Y`)
- ถอน point / ดูรายการรอถอน

### Master data (ADMIN)
- Station, Section, Route, SubRoute
- Route schedule / calendar
- Price strategy
- Tag, News, Templates, Info images
- Financial (fee / fare / promotion)

### Reports & Print
- Report booking / account
- Print ticket / detail ผ่าน DomPDF

---

## โครงสร้างโฟลเดอร์สำคัญ

```
app/
  Http/Controllers/     # Controllers ตามโมดูล
  Models/               # Eloquent models
  Services/             # BookingService, RouteService ฯลฯ
  Helpers/              # UtilHelper
  View/Components/      # Blade components (PHP class)
resources/views/
  layouts/              # Vuexy layouts
  pages/                # หน้าตามโมดูล (booking, agent, broker, …)
  components/           # Blade components
  print/                # PDF ticket templates
routes/
  web.php               # Web routes (+ auth middleware)
  auth.php              # Breeze auth routes
public/                 # Assets / Vuexy theme assets
```

---

## Environment Variables

ค่าสำคัญที่โปรเจกต์ใช้ (ตั้งใน `.env`):

| Key | ความหมาย |
| --- | --- |
| `APP_URL` | URL ของ admin |
| `AGENT_ID` | Agent หลักที่ใช้ filter booking |
| `API_URL` / `API_KEY` | เชื่อมต่อ API ภายนอก |
| `WEB_URL` | URL หน้าเว็บจอง (ปุ่ม Book Now) |
| `PAYMENT_URL` | Base URL ลิงก์ชำระเงิน |
| `PDF_API` | (ถ้ามี) PDF service |
| `DB_*` | การเชื่อมต่อฐานข้อมูล |

อย่า commit ไฟล์ `.env` ที่มี credentials จริง

---

## การรันโปรเจกต์

```bash
composer install
cp .env.example .env   # แล้วตั้งค่า DB / key
php artisan key:generate

npm install
npm run build          # หรือ npm run dev

php artisan serve --port=8002
```

หรือใช้ script รวม:

```bash
composer run dev
```

---

## Conventions ที่ควรรู้

1. **UI** — ใช้ Vuexy patterns (`bg-label-primary`, floating form, `x-card`, DataTables) ไม่ invent design ใหม่ที่ไม่เข้าธีม
2. **Role-based views** — หน้า booking แยก include ตาม role ใน `pages/booking/table/*` และ dashboard ใน `pages/booking/dashboard/*`
3. **Booking query** — filter ด้วย `b.agent_id = AGENT_ID` และถ้าไม่ใช่ ADMIN จะจำกัด `sales_partner_id`
4. **Export booking** — server-side ผ่าน query params `export=excel` หรือ `ispdf=Y` ใน `BookingController@index`
5. **PDF** — ใช้ `Barryvdh\DomPDF\Facade\Pdf` และ view ภายใต้ `resources/views/pages/.../pdf` หรือ `resources/views/print`

---

## Links

- [Vuexy Documentation](https://demos.pixinvent.com/vuexy-html-admin-template/documentation/)
- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [DomPDF (barryvdh)](https://github.com/barryvdh/laravel-dompdf)
