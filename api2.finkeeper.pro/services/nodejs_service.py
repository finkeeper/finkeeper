import asyncio
import os

# Определяем путь к index.js (на уровень выше)
NODE_JS_SCRIPT = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", "index.js"))

async def run_js(command, *args):
    """Асинхронный запуск Node.js-скрипта"""
    process = await asyncio.create_subprocess_exec(
        "node", NODE_JS_SCRIPT, command, *args,
        stdout=asyncio.subprocess.PIPE,
        stderr=asyncio.subprocess.PIPE
    )
    
    stdout, stderr = await process.communicate()

    if stderr:
        return {"error": stderr.decode().strip()}
    
    return stdout.decode().strip()
