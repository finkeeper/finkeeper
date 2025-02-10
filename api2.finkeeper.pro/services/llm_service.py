from config import LLM_PROVIDER
from services.atoma import ask_atoma
from services.chatgpt import ask_chatgpt
import json

PORTFOLIO_KEYWORDS = ["портфолио", "портфель", "portfolio", "analysis", "assets analysis", "investment", "holdings", "crypto assets"]


def analyze_portfolio(portfolio: dict) -> dict:
    """🔍 Анализ крипто-портфеля через Atoma"""

    portfolio_json = json.dumps(portfolio, ensure_ascii=False, indent=2)

    # 📝 **Новый промпт для Atoma (обычный текст, без JSON)**
    portfolio_analysis_prompt = (
        f"Analyze this crypto asset portfolio:\n\n{portfolio_json}\n\n"
        "Provide a structured analysis with clear sections and use emojis to improve readability. Use the following format:\n\n"
        "### 1️⃣ Diversification 🌍\n"
        "- List blockchains, exchanges, and wallets used.\n"
        "- Highlight the main blockchain where most assets are stored.\n\n"
        "### 2️⃣ Risks ⚠️\n"
        "- Mention any over-concentration of assets.\n"
        "- Identify any missing diversification.\n\n"
        "### 3️⃣ Stablecoins 💰\n"
        "- Total stablecoins in the portfolio.\n"
        "- Estimated monthly passive income at 10% APY.\n\n"
        "### 4️⃣ Summary 🚀\n"
        "- Key takeaways.\n"
        "- Recommendations for improving portfolio balance.\n\n"
        "Ensure the response follows this format **exactly** and uses **bold text for key data**."
        )



    portfolio_analysis_response = ask_atoma(portfolio_analysis_prompt)
    #portfolio_analysis_response = ask_chatgpt(portfolio_analysis_prompt)

    print(f"🔍 [DEBUG] Atoma Response: {portfolio_analysis_response}")  # Логируем ответ Atoma

    return portfolio_analysis_response  # Просто возвращаем текст, как есть





def ask_llm(user_input: str, portfolio: dict = None) -> dict:
    """Анализирует запрос пользователя: определяет команду перевода или запрашивает анализ портфеля"""

    # 🔍 **1. Проверяем, запрашивается ли анализ портфеля**
    print(f"✅ [ask_llm] Входной текст: {user_input}")
    print(f"📊 [ask_llm] Портфолио: {json.dumps(portfolio, indent=2, ensure_ascii=False)}")  # Логируем портфель в JSON

    # 🔍 **Проверяем, запрашивается ли анализ портфеля**
    if any(keyword.lower() in user_input.lower() for keyword in ["портфолио", "portfolio", "assets analysis"]):
        if portfolio:
            analysis_text = analyze_portfolio(portfolio)
            return analysis_text
        return "⚠️ No portfolio data provided. Please send your crypto portfolio for analysis."



    

    # 🔍 **Запрос к Atoma для анализа текста перевода SUI **
    atoma_check_prompt = f"Найди в запросе пользователя '{user_input}' слова, связанные с переводом SUI (например: отправить, переслать, перевести, send, tranfer, move). " \
                         "НЕ НУЖНО НИКАК РАССУЖДЕНИЙ, Если есть команда, верни только JSON:\n\n" \
                         '{"function": {"function": 1, "name": "transfer"}}' \
                         "\n\nЕсли таких слов нет, верни JSON: {}"

    atoma_check_response = ask_chatgpt(atoma_check_prompt)

    try:
        atoma_data = json.loads(atoma_check_response)
    except json.JSONDecodeError:
        atoma_data = {}

    # ✅ **Если в запросе есть команда перевода**
    if "function" in atoma_data:
        atoma_check_prompt2 = (
            f"Найди в запросе пользователя '{user_input}' сумму перевода (amount) и адрес получателя (address). "
            "Сумма должна быть числом, а адрес — это строка, похожая на блокчейн-адрес. "
            "НЕ НУЖНО НИКАК РАССУЖДЕНИЙ, Верни только JSON в формате:\n\n"
            '{"function": 1, "name": "transfer", "amount": 100, "address": "0x123456789abcdef"}'
            '\n\nЕсли таких данных нет, верни только JSON: {"function": 1, "name": "transfer"}'
        )

        atoma_check_response2 = ask_chatgpt(atoma_check_prompt2)
        
        try:
            atoma_data2 = json.loads(atoma_check_response2)
        except json.JSONDecodeError:
            atoma_data2 = {}
        
        return {
            "response": "⚡ Find transfer SUI comand. Specify address and amount.",
            # "function": atoma_check_response2 
            "function": atoma_data2
        }


    # ✅ **Find DEPOSIT TO NAVI promt**

    atoma_check_deposit_prompt = (
        f"Identify if the user request '{user_input}' contains words related to depositing SUI "
        "(e.g., deposit, stake, transfer, navi, earn on, lend, income, earn from, yield, generate income with or similar). "
        "Do not provide any reasoning. If a deposit command is detected, return only this JSON:\n\n"
        '{"function": {"function": 2, "name": "deposit", "amount": "NUMBER_HERE"}}\n\n'
        "Replace NUMBER_HERE with the detected deposit amount. If no amount is found, set it to null.\n\n"
        "If no deposit-related words are found, return only this JSON: {}"
    )
    
    atoma_check_response3 = ask_chatgpt(atoma_check_deposit_prompt)
    
    print(f"🔍 [DEBUG] Deposit Prompt Sent: {atoma_check_deposit_prompt}")  # Проверяем, что реально отправляем
    print(f"🔍 [DEBUG] Atoma Deposit Response: {atoma_check_response3}")  # Смотрим, что отвечает Atoma

    try:
        atoma_data3 = json.loads(atoma_check_response3)
    except json.JSONDecodeError:
        atoma_data3 = {}
    if "function" in atoma_data3:
        return {
            "response": "⚡ Find deposit SUI command",
            # "function": atoma_check_response2 
            "function": atoma_data3
        }
        
    # ✅ **Find withdraw from NAVI promt**
    
    atoma_check_withdraw_prompt = (
        f"Identify if the user request '{user_input}' contains words related to withdrawing SUI "
        "(e.g., withdraw from navi, cash out, send, transfer out, remove, redeem, payout, claim, release, or similar). "
        "Do not provide any reasoning. If a withdrawal command is detected, return only this JSON:\n\n"
        '{"function": {"function": 3, "name": "withdraw", "amount": "NUMBER_HERE"}}\n\n'
        "Replace NUMBER_HERE with the detected withdrawal amount. If no amount is found, set it to null.\n\n"
        "If no withdrawal-related words are found, return only this JSON: {}"
        )

    
    atoma_check_response4 = ask_chatgpt(atoma_check_withdraw_prompt)
    
    print(f"🔍 [DEBUG] Deposit Prompt Sent: {atoma_check_withdraw_prompt}")  # Проверяем, что реально отправляем
    print(f"🔍 [DEBUG] Atoma Deposit Response: {atoma_check_response4}")  # Смотрим, что отвечает Atoma

    try:
        atoma_data4 = json.loads(atoma_check_response4)
    except json.JSONDecodeError:
        atoma_data4 = {}
    if "function" in atoma_data4:
        return {
            "response": "⚡ Find withdraw SUI command",
            # "function": atoma_check_response2 
            "function": atoma_data4
        }
    
    
    # 🔄 **Если команды нет, запрашиваем обычный ответ у Atoma/ChatGPT**
    if LLM_PROVIDER == "atoma":
        return ask_atoma(user_input)

    elif LLM_PROVIDER == "chatgpt":
        return ask_chatgpt(user_input)

    return {"error": "Ошибка: Неподдерживаемая LLM-модель."}
