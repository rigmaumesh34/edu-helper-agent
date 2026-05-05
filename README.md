# 🤖 EduHelperAgent - Laravel AI Chatbot

## 📌 Project Overview

EduHelperAgent is a simple AI-powered educational chatbot built using **Laravel** and **LarAgent**.
It helps school students learn basic concepts in a friendly and interactive way.

The chatbot is designed with controlled responses and supports only selected topics to ensure focused learning.

---

## 🎯 Features

* 👋 Polite greeting for users
* 📚 Supports limited educational topics:

  * Solar System
  * Fractions
  * Water Cycle
* ✍️ Provides short answers (max 60 words)
* 🚫 Restricts unsupported topics
* 🧠 Maintains conversation memory (session-based)
* ⚡ Fast API-based responses using OpenAI

---

## 🛠️ Tech Stack

* **Backend:** Laravel 12
* **AI Integration:** LarAgent + OpenAI API
* **Language:** PHP 8.3
* **Frontend (Optional):** Blade / JavaScript

---

## ⚙️ Installation Guide

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/your-username/edu-helper-agent.git
cd edu-helper-agent
```
---

## 🧠 How the Agent Works

1. User sends a message via chat UI or API
2. The message is passed to **EduHelperAgent**
3. The agent follows predefined rules:

   * Checks topic validity
   * Limits response length
   * Generates simple educational answers
4. If topic is unsupported → returns restriction message
5. Conversation history is maintained using session memory

---

## 🤖 Agent Rules

* Only answers:

  * Solar System
  * Fractions
  * Water Cycle
* Maximum response length: **60 words**
* If question is خارج supported topics:

```
I can only help with Solar System, Fractions, or Water Cycle for now 😊
```

---

## 📁 Project Structure

```
app/
 ├── Agents/
 │    └── EduHelperAgent.php
 ├── Http/
 │    └── Controllers/
 │         └── ChatController.php

routes/
 └── web.php

resources/views/
 └── chat.blade.php
```

---

## 🔗 API Endpoint

### POST `/chat`

#### Request:

```json
{
  "message": "What is solar system?"
}
```

#### Response:

```json
{
  "reply": "The solar system consists of the Sun and planets..."
}
```

---

## 🧪 Example Usage

### ✅ Supported

* "Explain fractions"
* "What is water cycle?"

### ❌ Unsupported

* "What is Artificial Intelligence?"

Response:

```
I can only help with Solar System, Fractions, or Water Cycle for now 😊
```



## 🚀 Future Enhancements

* Add more subjects
* Store chat history in database
* Add voice input/output
* Improve UI with chat design
* Multi-language support

---

## 👩‍💻 Author

**Rigma Umesh N K**

---

## 📄 License

This project is for educational/demo purposes.
