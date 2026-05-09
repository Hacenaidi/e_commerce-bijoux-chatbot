# Chatbot RAG avec Groq et LangChain

Assistant intelligent pour une boutique de bijoux utilisant le Retrieval Augmented Generation (RAG).

## Structure du projet

```
chatbot/
├── src/
│   ├── __init__.py       # Package init
│   ├── config.py         # Constantes de configuration
│   ├── pipeline.py       # Traitement des documents et vectorstore
│   ├── prompts.py        # Gestion des prompts et formatage
│   └── cli.py            # Interface utilisateur interactive
├── data/                 # Fichiers texte pour le RAG
│   ├── delivery.txt
│   ├── style_guide.txt
│   └── lulu_collection.txt
├── rag_pdf_groq.py       # Point d'entrée principal
├── requirements.txt      # Dépendances Python
├── .env.example          # Exemple de fichier .env
└── README.md             # Ce fichier
```

## Installation

### 1. Créer l'environnement virtuel

**Sous Windows (PowerShell):**
```powershell
python -m venv .venv
.\.venv\Scripts\Activate.ps1
```

**Sous Windows (Cmd):**
```cmd
python -m venv .venv
.venv\Scripts\activate.bat
```

**Sous macOS/Linux:**
```bash
python3 -m venv .venv
source .venv/bin/activate
```

### 2. Installer les dépendances

```bash
pip install -r requirements.txt
```

### 3. Configuration

1. Copier `.env.example` en `.env`:
```bash
cp .env.example .env
```

2. Éditer le fichier `.env` et ajouter votre clé API Groq:
```
GROQ_API_KEY=votre_cle_api_groq_ici
```

Obtenir une clé API: https://console.groq.com/

## Utilisation

Lancer le chatbot:
```bash
python rag_pdf_groq.py
```

Puis tapez vos questions. Le système va:
1. Charger les fichiers texte du dossier `data/`
2. Créer des embeddings avec HuggingFace
3. Indexer les documents avec FAISS
4. Répondre aux questions en utilisant Groq LLM

## Modules

- **config.py**: Configuration globale (modèles, paramètres, répertoires)
- **pipeline.py**: Chargement, splitting et vectorisation des documents
- **prompts.py**: Construction de prompts et formatage des réponses
- **cli.py**: Boucle interactive du chatbot

## Dépendances principales

- `langchain`: Framework RAG
- `langchain-groq`: Intégration Groq
- `langchain-huggingface`: Embeddings HuggingFace
- `faiss-cpu`: Index vectoriel
- `python-dotenv`: Gestion des variables d'environnement
