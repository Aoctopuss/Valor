# ***Valor***

**Valor is a free, open source password manager where you control your own data.**

Most password managers make you trust a company with your passwords. Valor works differently — everything gets encrypted on your device before it ever reaches a server. Even if someone broke into the database, they would get nothing useful.

## What it does

- Save, view, edit and delete passwords in your vault
- Everything is encrypted with AES-256 before it hits the database
- Simple, mobile-friendly interface built with Tailwind CSS
- Self-hostable — run it on your own server and your data stays yours

## What you need

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Node.js 18 or higher

## Installation

```bash
git clone https://github.com/Aoctopuss/Valor.git
cd Valor
mysql -u root -p < database/schema.sql
cp config.example.php config.php
npm install && npm run build
php -S localhost:8000 -t public
```

Fill in your database credentials in `config.php` before running.

## Licence

Licensed under the [European Union Public Licence v1.2 (EUPL-1.2)](https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12).



### temp notes 


add section tab

