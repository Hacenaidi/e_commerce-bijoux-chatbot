# 🚀 QUICK START - Installation & Démarrage (3 Étapes)

## 📋 Avant de commencer
- **Python 3.8+** installé
- **XAMPP** ou **WAMP** installé
- **MySQL** accès root disponible
- Tous les fichiers du projet dans le dossier Apache

---

## ⚡ ÉTAPE 1: Chatbot Python Setup (5 minutes)

### 1.1 Ouvrir PowerShell

```bash
cd "\chatbot"
```

### 1.2 Créer & Activer l'environnement virtual

```bash
python -m venv .venv
.\.venv\Scripts\Activate.ps1
```

### 1.3 Installer les dépendances

```bash
pip install -r requirements.txt
```

### 1.3.5 Créer le fichier `.env` avec Groq API Key

Créer fichier: `chatbot/.env`

```
GROQ_API_KEY=gsk_your_api_key_here
```

**Comment obtenir votre Groq API Key:**

1. Aller à: https://console.groq.com
2. Créer un compte ou se connecter
3. Aller à **"API Keys"** (dans le menu)
4. Cliquer **"Create New API Key"**
5. Copier la clé (commence par `gsk_`)
6. Coller dans le fichier `.env`

**Exemple complet `.env`:**
```
GROQ_API_KEY=gsk_abcd1234efgh5678ijkl9012mnop3456
```

**⚠️ Important:**
- La clé est **confidentielle** (ne pas partager)
- Ne pas commiter `.env` sur GitHub (ajouté dans `.gitignore`)
- Vérifier que `chatbot/.env` est créé

### 1.4 Démarrer le Chatbot API

```bash
uvicorn app:app --reload --host 0.0.0.0 --port 8000
```

**✅ Success:**
```
INFO:     Uvicorn running on http://0.0.0.0:8000
```

**⚠️ Laisser ce terminal OUVERT** (Ctrl+C pour arrêter plus tard)

---

## ⚡ ÉTAPE 2: Database MySQL Setup (5 minutes)

### 2.1 Ouvrir XAMPP/WAMP/movamp

**XAMPP:**
```bash
C:\xampp\xampp-control.exe
```
Cliquer **"Start"** sur Apache et MySQL

**WAMP:**
- Clic droit icône → **"Restart All Services"**

### 2.2 Ouvrir phpMyAdmin

```
http://localhost/phpmyadmin
```

### 2.3 Créer la base de données

1. Clic gauche **"New"** (haut-gauche)
2. Entrer: **`shopping`**
3. Clic **"Create"**

### 2.4 Importer le fichier SQL

1. Clic sur **`shopping`** (nouvelle base)
2. Aller à l'onglet **"Import"**
3. Clic **"Choose File"** → Sélectionner: `shopping.sql`
4. Clic **"Import"** (bas-droit)

**✅ Success:** Tables créées ✓



---

## ⚡ ÉTAPE 3: Démarrer Projet PHP (2 minutes)

### 3.1 Vérifier Apache est lancé

Dans XAMPP/WAMP/movamp: Apache doit être **"Running"** (vert)

### 3.2 Ouvrir le projet

```
http://localhost/mvc/view/index.php
```

### 3.3 Accéder à l'Admin Dashboard

```
http://localhost/mvc/view/admin.php
```

**Login:** (à vérifier dans BDD table `admin`)
- Email: (check admin table)
- Mot de passe: (check admin table)

### 3.4 Tester le Chatbot

1. Aller à: http://localhost/mvc/view/
2. Clic bouton chat 💬 (bas-droit)
3. Poser une question: "Quels bijoux avez-vous?"


## 📁 Fichiers importants

```
chatbot/
├── app.py (API FastAPI)
├── .env (Groq API key)
└── src/
    ├── pipeline.py (Document loading, embeddings)
    ├── prompts.py (LLM prompts, response formatting)
    └── config.py (Configuration constants)

mvc/view/
├── index.php (Page principale avec widget)
├── chatbot.php (Page chatbot complète)
├── css/
│   ├── chatbot-widget.css (Widget button & modal)
│   ├── chatbot-messages.css (Messages colorés) ⭐
│   └── response-format.css (Listes, sections)
└── js/
    ├── chatbot-widget.js (Widget logic)
    ├── chatbot.js (Full page logic)
    └── response-formatter.js (Formatting logic)
```
