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


@app.get("/api/tasks/get-tasks")
def get_tasks():
    tasks = untils.get_tasks()
    return tasks


@app.delete("/api/tasks/delete-task")
def delete_task(id: str):
    result = untils.delete_task(id)
    if result[0] != 0:
        return JSONResponse(content={"answer": result[1]}, status_code=400)
    return JSONResponse(content={"answer": result[1]}, status_code=200)