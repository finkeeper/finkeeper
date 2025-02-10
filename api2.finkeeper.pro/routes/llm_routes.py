from fastapi import APIRouter, HTTPException
from services.llm_service import ask_llm
from services.atoma import ask_atoma
from services.chatgpt import ask_chatgpt

router = APIRouter()

@router.post("/chat")
def chat(input_text: str, portfolio: dict):
    """Универсальный вызов LLM (автоопределение модели) с передачей портфолио"""
    try:
        # Логируем входные данные
        print(f"🔹 Входной текст: {input_text}")
        print(f"📊 Портфолио: {portfolio}")

        # Пока что просто возвращаем портфолио для отладки
        #return {"portfolio": portfolio}

        # Позже можно передавать portfolio в LLM
        response = ask_llm(input_text, portfolio)
        return {"response": response}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/chat/atoma")
def chat_atoma(input_text: str):
    """Прямой вызов Atoma"""
    try:
        response = ask_atoma(input_text)
        return {"response": response}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/chat/chatgpt")
def chat_chatgpt(input_text: str):
    """Прямой вызов ChatGPT"""
    try:
        response = ask_chatgpt(input_text)
        return {"response": response}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
