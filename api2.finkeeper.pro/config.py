import os
from dotenv import load_dotenv

# Загружаем API-ключи из .env
load_dotenv()

ATOMA_API_KEY = os.getenv("ATOMA_API_KEY")
CHATGPT_API_KEY = os.getenv("CHATGPT_API_KEY")


# Выбираем LLM по умолчанию
LLM_PROVIDER = os.getenv("LLM_PROVIDER", "atoma")  # Можно менять на "atoma"
