import requests
from config import CHATGPT_API_KEY

def ask_chatgpt(user_input: str) -> str:
    url = "https://api.openai.com/v1/chat/completions"
    headers = {"Authorization": f"Bearer {CHATGPT_API_KEY}"}
    payload = {"model": "gpt-4", "messages": [{"role": "user", "content": user_input}]}

    response = requests.post(url, json=payload, headers=headers)

    # Логируем полный ответ от ChatGPT
    print("ChatGPT API Response:", response.status_code, response.text)

    try:
        return response.json()["choices"][0]["message"]["content"]
    except KeyError:
        return {"error": "Unexpected response format", "response": response.text}
