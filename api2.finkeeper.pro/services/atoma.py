import requests
from config import ATOMA_API_KEY

def ask_atoma(user_input: str) -> str:
    url = "https://api.atoma.network/v1/chat/completions"
    headers = {"Authorization": f"Bearer {ATOMA_API_KEY}"}
    payload = {
        "model": "meta-llama/Llama-3.3-70B-Instruct", 
        "messages": [{"role": "user", "content": user_input}],
        # "max_tokens": 600
        }

    response = requests.post(url, json=payload, headers=headers)

    # Логируем полный ответ от Atoma
    print("Atoma API Response:", response.status_code, response.text)

    try:
        return response.json()["choices"][0]["message"]["content"]
    except KeyError:
        return {"error": "Unexpected response format", "response": response.text}
