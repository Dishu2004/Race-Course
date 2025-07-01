# 🏇 Race Course – Web-Based Horse Betting System

A fully functional web-based **horse race betting simulation platform** featuring a **coupon code-based wallet system**. Users can bet on races, manage their wallets with admin-generated coupon codes, and experience the thrill of racing in a fun and secure environment.

---

## 🚀 Features

### 🎮 User Features
- Register/Login to your personal dashboard
- View available races and horses with odds
- Place bets using wallet credits
- Redeem coupon codes to top up wallet
- Track bet history and wallet transactions
- Get race results and winning notifications

### 🛠️ Admin Features
- Create and manage horse races and race data
- Generate and manage coupon codes (one-time or multi-use)
- View all user transactions and betting history
- Control race outcomes (for simulation purposes)

---

## 🧱 Tech Stack

| Layer      | Technology               |
|------------|--------------------------|
| Frontend   | HTML5, CSS3, JavaScript  |
| Backend    | PHP (Core PHP or Laravel optional) |
| Database   | PostgreSQL               |
| Other Tools| AJAX, Bootstrap (optional for UI) |

---

## 🗃️ Database Structure (PostgreSQL)

```sql
users(user_id, name, email, password, wallet_balance)
races(race_id, title, start_time, status)
horses(horse_id, race_id, name, odds)
bets(bet_id, user_id, horse_id, race_id, amount, status)
coupons(code, amount, status, expiry_date)
transactions(transaction_id, user_id, type, amount, timestamp)
