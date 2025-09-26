# Tech-Shack fejlesztői dokumentáció

Ez a dokumentáció a Tech-Shack webalkalmazás fejlesztői számára készült. Célja, hogy átfogó képet adjon a rendszer felépítéséről, főbb funkcióiról, API végpontjairól, adatfolyamairól és fejlesztési irányelveiről.

---

## 1. Rendszer áttekintése

A Tech-Shack egy Laravel (backend) + Vue.js (frontend) alapú webalkalmazás, amely Docker konténerekben fut. Több domain támogatott (pl. vm1.test, vm2.test), a session és CSRF kezelés Laravel Sanctumon keresztül történik.

---

## 2. Főbb funkciók

- Felhasználói regisztráció, bejelentkezés, kijelentkezés
- Kosárkezelés, rendelés leadása
- Checkout magyar cím és telefonszám validációval
- Felhasználói profil mentése rendeléskor
- Adminisztrációs felület (ha van)
- E-mail értesítések (ha beállítva)

---

## 3. Technológiai stack

- **Backend:** Laravel 10, Sanctum, Eloquent ORM
- **Frontend:** Vue 3, Vite, Pinia, Axios, TailwindCSS
- **Konténerizáció:** Docker, Docker Compose
- **Tesztelés:** PHPUnit, Vitest

---

## 4. Főbb API végpontok

| Módszer | Útvonal                      | Leírás                        | Auth         |
|---------|------------------------------|-------------------------------|--------------|
| POST    | /api/auth/register           | Regisztráció                  | -            |
| POST    | /api/auth/login              | Bejelentkezés                 | -            |
| POST    | /api/auth/logout             | Kijelentkezés                 | igen         |
| GET     | /api/auth/me                 | Saját adatok lekérdezése      | igen         |
| GET     | /api/products                | Termékek listázása            | -            |
| GET     | /api/products/{id}           | Termék részletei              | -            |
| POST    | /api/orders                  | Rendelés leadása              | igen/guest   |
| GET     | /api/orders                  | Saját rendelések lekérdezése  | igen         |
| GET     | /sanctum/csrf-cookie         | CSRF token lekérése           | -            |
| GET     | /admin/products              | Termékek listázása (admin)    | admin        |
| POST    | /admin/products              | Termék létrehozása (admin)    | admin        |
| PUT     | /admin/products/{id}         | Termék frissítése (admin)     | admin        |
| DELETE  | /admin/products/{id}         | Termék törlése (admin)        | admin        |
| GET     | /admin/orders                | Összes rendelés listázása     | admin        |
| PUT     | /admin/orders/{id}           | Rendelés státusz frissítése   | admin        |
| DELETE  | /admin/orders/{id}           | Rendelés törlése              | admin        |
| GET     | /admin/users                 | Felhasználók listázása        | admin        |
| GET     | /admin/users/{id}            | Felhasználó részletei         | admin        |
| PUT     | /admin/users/{id}            | Felhasználó frissítése        | admin        |
| DELETE  | /admin/users/{id}            | Felhasználó törlése           | admin        |

---

## 5. Adatfolyamok

### 5.1. Bejelentkezés/Regisztráció

- Frontend: `/auth/login` vagy `/auth/register` → backend
- Backend: Session cookie + XSRF-TOKEN beállítása
- Frontend: Axios interceptor minden kéréshez hozzáadja az XSRF-TOKEN-t

### 5.2. Rendelés leadása

- Frontend: Kosár tartalma + címadatok elküldése `/api/orders` végpontra
- Backend: Rendelés mentése, felhasználói profil frissítése (ha bejelentkezett)
- Backend: Validáció magyar címre, telefonszámra

---

## 6. Jogosultságok

- **Vendég:** Böngészés, regisztráció, bejelnetkezés, rendelés leadása
- **Felhasználó:** Saját rendelések, profil szerkesztése
- **Admin:** Termékek, rendelések, felhasználók teljes kezelése az `/admin/*` végpontokon

---

## 7. Konfiguráció és domain kezelés

- Minden domainhez külön `.env` szükséges, pl. `SESSION_DOMAIN=.vm1.test`
- `SANCTUM_STATEFUL_DOMAINS` és CORS configban minden frontend/backend domain szerepeljen
- Frontend Axios/HTTP kliens dinamikusan állítsa be a backend URL-t a domain alapján

---

## 8. Hibakezelés

- **CSRF/session hibák:** Ellenőrizd a domain beállításokat, config cache törlése (`php artisan config:clear`)
- **Migrációs hibák:** Ellenőrizd, hogy minden szükséges oszlop szerepel-e a táblákban
- **Cookie hibák:** Csak valós subdomainre állítsd a SESSION_DOMAIN-t, `.test` nem működik

---

## 9. Bővíthetőség

- Új admin végpont: `routes/api.php` + admin middleware
- Új admin funkció: controller metódus + admin route

---

## 10. Tesztelés

- Backend: `php artisan test`
- Frontend: `npm run test`

---

## 11. Hasznos parancsok

```bash
# Backend migrációk futtatása
docker compose exec backend php artisan migrate --seed

# Backend újraindítása
docker compose restart backend

# Frontend fejlesztői szerver
cd frontend && npm run dev
```

---

## 12. További információ

- Projektleírás: `README.md` a projekt gyökérben
- Frontend fejlesztési útmutató: `frontend/README.md` a frontend mappában

---

Ha kérdésed van vagy hibát találsz, jelezd a projekt GitHub oldalán vagy a fejlesztői csatornán!