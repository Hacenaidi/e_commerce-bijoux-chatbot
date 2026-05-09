import os
from contextlib import asynccontextmanager
from pathlib import Path

from dotenv import load_dotenv
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from langchain_groq import ChatGroq

from src.config import DATA_DIR, TOP_K, GROQ_MODEL
from src.pipeline import (
    load_text_documents,
    split_documents,
    create_vectorstore,
)
from src.prompts import build_prompt, answer_question

class AskRequest(BaseModel):
    question: str

class SourceItem(BaseModel):
    source: str
    page: str

class AskResponse(BaseModel):
    answer: str
    sources: list[SourceItem]
state = {
"retriever": None,
"llm": None,
"prompt": None,
}

@asynccontextmanager
async def lifespan(app: FastAPI):
    load_dotenv()
    groq_api_key = os.getenv("GROQ_API_KEY")
    if not groq_api_key:
        raise RuntimeError("GROQ_API_KEY is missing")

    documents = load_text_documents(Path(DATA_DIR))
    chunks = split_documents(documents)
    vectorstore = create_vectorstore(chunks)

    state["retriever"] = vectorstore.as_retriever(search_kwargs={"k": TOP_K})
    state["llm"] = ChatGroq(
        model=GROQ_MODEL,
        temperature=0,
        api_key=groq_api_key,
    )
    state["prompt"] = build_prompt()
    yield
app = FastAPI(title="RAG Jewelry API", lifespan=lifespan)

# CORS middleware pour accepter les requêtes depuis PHP/JS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/health")
def health():
    return {"status": "ok"}

@app.post("/ask", response_model=AskResponse)
def ask(req: AskRequest):
    question = req.question.strip()
    if not question:
        raise HTTPException(status_code=400, detail="Question cannot be empty")

    answer, docs = answer_question(
        question,
        state["retriever"],
        state["llm"],
        state["prompt"],
    )

    sources = []
    for d in docs:
        source = d.metadata.get("source", "source_inconnue")
        page = d.metadata.get("page", "?")
        if isinstance(page, int):
            page = page + 1
        sources.append({"source": source, "page": str(page)})

    return {"answer": answer, "sources": sources}

