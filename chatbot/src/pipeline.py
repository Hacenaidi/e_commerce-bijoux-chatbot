"""Fonctions de traitement des documents et création du vectorstore"""

from pathlib import Path

from langchain_community.document_loaders import TextLoader
from langchain_community.vectorstores import FAISS
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_text_splitters import RecursiveCharacterTextSplitter

from .config import CHUNK_SIZE, CHUNK_OVERLAP, EMBEDDING_MODEL, TOP_K


def load_text_documents(data_dir: Path):
    """Charge tous les fichiers .txt du dossier data"""
    txt_paths = sorted(data_dir.glob("*.txt"))

    if not txt_paths:
        raise FileNotFoundError(
            "Aucun fichier texte trouve dans le dossier 'data'. "
            "Ajoutez par exemple style_guide.txt et delivery.txt."
        )

    documents = []

    for txt_path in txt_paths:
        loader = TextLoader(str(txt_path), encoding="utf-8")
        pages = loader.load()

        for page in pages:
            page.metadata["source"] = txt_path.name

        documents.extend(pages)

    return documents


def split_documents(documents):
    """Divise les documents en chunks pour le RAG"""
    splitter = RecursiveCharacterTextSplitter(
        chunk_size=CHUNK_SIZE,
        chunk_overlap=CHUNK_OVERLAP,
        separators=["\n\n", "\n", ". ", " ", ""],
    )
    return splitter.split_documents(documents)


def create_vectorstore(chunks):
    """Crée un vectorstore FAISS avec les embeddings HuggingFace"""
    embeddings = HuggingFaceEmbeddings(model_name=EMBEDDING_MODEL)
    return FAISS.from_documents(chunks, embeddings)


def get_retriever(vectorstore):
    """Crée un retriever à partir du vectorstore"""
    return vectorstore.as_retriever(search_kwargs={"k": TOP_K})
