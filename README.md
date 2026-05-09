# Valor — Password Manager

A school project built for my eindproject. Valor is a web-based password manager made with PHP, MySQL and Tailwind CSS.

## What it does

- Store, edit and delete passwords in a personal vault
- Passwords are encrypted with AES-256 before they hit the database
- Users can organise entries into custom categories
- Includes password and username generation
- Checks if a password has appeared in known data breaches via the Have I Been Pwned API

## How it works

When a user registers, their master password is run through PBKDF2 to create an encryption key. That key is used to encrypt all vault passwords using AES-256-GCM. The key never gets stored in the database — only in the session while the user is logged in. This means even if the database were stolen, the passwords inside it would be unreadable.

## Built with

- PHP 8.1
- MySQL 8.0
- Tailwind CSS v4
- JavaScript

## Running it locally

Paste the schema.sql into mysql and run apache.

Fill in your database credentials in `db.php` before running.

## Licence

Licensed under the [European Union Public Licence v1.2 (EUPL-1.2)](https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12).