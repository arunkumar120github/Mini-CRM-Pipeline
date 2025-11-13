
# 🧩 Mini CRM Pipeline – Laravel 10 + Livewire 3

A simple **Kanban-style CRM Pipeline** built using **Laravel 10**, **Livewire 3**, **Tailwind CSS**, and **Alpine.js**.  
This project allows users to manage sales leads across multiple pipeline stages with **drag-and-drop**, **modals**, and **real-time updates** — all without page reloads.

---

## 🚀 Features

### 🧠 Authentication
- Built with **Laravel Breeze** + **Livewire 3**
- Includes: **Login**, **Register**, and **Password Reset**

### 📊 CRM Pipeline Board
- 4 stages:  
  - 🟡 **Lead**  
  - 🟠 **Contacted**  
  - 🔵 **Proposal Sent**  
  - 🟢 **Won**
- Responsive **Tailwind UI** layout
- Scrollable columns for mobile and tablet views

### ⚡️ Livewire Functionality
- **Drag & Drop:** Move leads between columns dynamically
- **Instant Updates:** Lead status updates instantly without page reload
- **Create / Edit / Delete:**
  - Add new leads (title, contact email, phone)
  - Edit existing leads inline via modal
  - Delete with confirmation popup
- **Modals with Alpine.js:** Add/Edit lead via modal window
- **Inline Details Toggle:** Expand/collapse lead details using Alpine.js
- **Highlight Animation:** Recently moved leads get smooth transition (Tailwind + Alpine)

---

## 🧱 Database Structure

### `users` Table
### `leads` Table

---

## 🧩 Bonus (Optional Enhancements)

✅ **Policies:** Only the lead owner can edit/delete it.  
✅ **UUIDs:** Leads use UUID instead of auto-increment IDs.  
✅ **Livewire Tests:** Basic test for drag & drop functionality.  
✅ **Alpine $persist:** Remembers last opened column between page reloads.  

---

## ⚙️ Installation Steps

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/yourusername/mini-crm-pipeline.git
cd mini-crm-pipeline

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve

