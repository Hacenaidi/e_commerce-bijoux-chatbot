"""Configuration et constantes pour le chatbot RAG"""

from pathlib import Path

# Répertoire des données
DATA_DIR = Path("data")

# Modèle d'embedding HuggingFace
EMBEDDING_MODEL = "sentence-transformers/all-MiniLM-L6-v2"

# Modèle LLM Groq
GROQ_MODEL = "llama-3.3-70b-versatile"

# Paramètres de chunking
CHUNK_SIZE = 1000
CHUNK_OVERLAP = 200

# Nombre de documents à récupérer
TOP_K = 2
