# ***Valor***

**Valor is a free, open source password manager where you control your own data.**

Most password managers make you trust a company with your passwords. Valor works differently — everything gets encrypted on your device before it goes anywhere near a server. That means even if someone broke into the database, they would get nothing useful.

## What it does

- Save, view, edit and delete passwords in your vault
- Encrypts everything with AES-256 before it hits the database
- Simple, mobile-friendly interface built with Tailwind CSS
- Self-hostable — run it on your own server and your data stays yours

## What you need

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Node.js 18 or higher (only needed to compile the CSS)

## Installation

### 1. Clone the repo
```bash
git clone https://github.com/Aoctopuss/Valor.git
cd Valor
```

### 2. Check PHP is ready
Run this to make sure the encryption extension is active:
```bash
php -m | grep sodium
```
### 3. Set up the database
```bash
mysql -u root -p < database/schema.sql
cp config.example.php config.php
```

### 5. Run it
```bash
php -S localhost:8000 -t public
```

## Licence

Licensed under the [European Union Public Licence v1.2 (EUPL-1.2)](https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12).