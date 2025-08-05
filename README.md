# 🧪 Symfony CLI – Subscription Manager

This take-home challenge is a CLI application written in PHP (Symfony Components) to manage subscriptions to digital products.

---

## 📦 Features

- Create users and assign them roles (e.g. customer)
- Add products and pricing options (monthly, yearly, etc.)
- Subscribe a customer to a pricing option
- Cancel a subscription (remains valid until end of period)
- List all active subscriptions for a customer
- Run a full demo of the system via CLI

---

## 🚀 Getting Started

### 1. Clone the repository

```bash
git clone git@github.com:leuzeus/user-manager.git
cd user-manager
```

### 2. Run the test with docker

```bash
docker compose up --build
```

The tests run via the entrypoint directly. There isn't container.
See the terminal for results

### 3. Alternative Run

You need to install minimum php __8.1__ or above and __Composer__. 
Check to configure php modules. You can look in the Dockerfile to have an idea.


Then run :

```bash
composer install 
php bin/phpunit --testdox
```
