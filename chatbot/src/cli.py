"""Interface CLI du chatbot RAG"""

import os

from dotenv import load_dotenv
from langchain_groq import ChatGroq

from .config import DATA_DIR, GROQ_MODEL
from .pipeline import create_vectorstore, get_retriever, load_text_documents, split_documents
from .prompts import answer_question, build_prompt


def run_cli():
    """Lance le chatbot en mode interactif"""
    load_dotenv()

    groq_api_key = os.getenv("GROQ_API_KEY")
    if not groq_api_key:
        raise EnvironmentError(
            "La variable GROQ_API_KEY est absente. "
            "Ajoutez-la dans un fichier .env ou dans vos variables d'environnement."
        )

    print("Chargement des fichiers texte...")
    documents = load_text_documents(DATA_DIR)
    print(f"Nombre total de pages chargees : {len(documents)}")

    print("Decoupage en chunks...")
    chunks = split_documents(documents)
    print(f"Nombre total de chunks : {len(chunks)}")

    print("Creation des embeddings et de l'index FAISS...")
    vectorstore = create_vectorstore(chunks)
    retriever = get_retriever(vectorstore)

    llm = ChatGroq(
        model=GROQ_MODEL,
        temperature=0,
        api_key=groq_api_key,
    )
    prompt = build_prompt()

    print("\nAssistant RAG pret.")
    print("Tapez votre question ou 'quit' pour quitter.\n")

    while True:
        question = input("Question > ").strip()

        if not question:
            print("Veuillez saisir une question.\n")
            continue

        if question.lower() in {"quit", "exit", "q"}:
            print("Fin du programme.")
            break

        answer, docs = answer_question(question, retriever, llm, prompt)

        print("\n--- Reponse ---")
        print(answer)

        print("\n--- Chunks recuperes ---")
        for i, doc in enumerate(docs, start=1):
            source = doc.metadata.get("source", "source_inconnue")
            page = doc.metadata.get("page", "?")
            if isinstance(page, int):
                page = page + 1

            preview = doc.page_content[:250].replace("\n", " ")
            print(f"{i}. {source} | page {page}")
            print(f" {preview}...")

        print()
