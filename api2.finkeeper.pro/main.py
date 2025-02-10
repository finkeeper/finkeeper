from fastapi import FastAPI
from routes.llm_routes import router as llm_router
from routes.nodejs_routes import router as node_router
from routes.navi_routes import router as navi_router  # ✅ Подключаем новый маршрут


app = FastAPI()

app.include_router(llm_router)
app.include_router(node_router)
app.include_router(navi_router)


@app.get("/")
def root():
    return {"message": "FastAPI LLM AI AGENT FINKEEPER + Node.js API работает!"}

