"""Gestion des prompts et formatage des réponses"""

from langchain_core.prompts import PromptTemplate


def _language_instructions(language: str) -> tuple[str, str, str]:
    code = (language or "fr").strip().lower()
    if code.startswith("en"):
        return (
            "You are a smart assistant for a handmade jewelry store.",
            "Reply in English only.",
            "I could not find that information in the available data.",
        )

    return (
        "Tu es un assistant intelligent pour une boutique de bijoux handmade.",
        "Reponds en francais uniquement.",
        "Je ne trouve pas cette information dans les donnees disponibles.",
    )


def build_prompt(language: str = "fr"):
    """Construit le prompt système pour le chatbot"""
    intro, response_rule, fallback_text = _language_instructions(language)
    template = f"""
{intro}

Tu aides les clients a :
- choisir des bijoux
- connaitre les prix
- recommander des styles
- proposer des cadeaux
- expliquer la livraison

Consignes :
- {response_rule}
- Sois courte, elegante et professionnelle.
- Utilise uniquement les informations du contexte.
- Tu peux effectuer des calculs simples a partir des prix presents.
- Quand tu proposes plusieurs choix, utilise toujours une liste a puces claire.
- Chaque choix doit tenir sur une ligne courte: nom, prix si disponible, puis une breve description.
- Termine par une question courte pour aider le client a choisir.
- Si l'information n'existe pas, dis :
"{fallback_text}"

Contexte :
{{context}}

Question :
{{question}}
"""
    return PromptTemplate.from_template(template)


def format_context(docs):
    """Formate les documents récupérés en contexte lisible"""
    parts = []

    for i, doc in enumerate(docs, start=1):
        source = doc.metadata.get("source", "source_inconnue")
        page = doc.metadata.get("page", "?")
        if isinstance(page, int):
            page = page + 1

        parts.append(
            f"[Extrait {i} | source={source} | page={page}]\n{doc.page_content}"
        )

    return "\n\n".join(parts)


def format_sources(docs):
    """Extrait et formate les sources uniques des documents"""
    unique_sources = []

    for doc in docs:
        source = doc.metadata.get("source", "source_inconnue")
        page = doc.metadata.get("page", "?")
        if isinstance(page, int):
            page = page + 1

        item = f"{source} (page {page})"
        if item not in unique_sources:
            unique_sources.append(item)

    return ", ".join(unique_sources)


def answer_question(question, retriever, llm, language="fr"):
    """Répond à une question en utilisant le RAG"""
    docs = retriever.invoke(question)
    context = format_context(docs)
    sources = format_sources(docs)

    prompt = build_prompt(language)
    final_prompt = prompt.format(context=context, question=question)
    response = llm.invoke(final_prompt).content

    if "Sources :" not in response:
        response = response.strip() + f"\n\nSources : {sources}"

    return response, docs
