from fastapi import FastAPI
from fastapi.responses import JSONResponse
from database import untils
from models import Task

app = FastAPI()


@app.post("/api/tasks/create-task")
def read_root(task: Task):
    answer = untils.create_task(task)
    if answer[0] != 0:
        return JSONResponse(content={"answer": answer[1]}, status_code=400)
    return JSONResponse(content={"answer": answer[1]}, status_code=201)




