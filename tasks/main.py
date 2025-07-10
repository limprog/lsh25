from fastapi import FastAPI, File, Response, Cookie
from fastapi.responses import JSONResponse
from typing import Dict, List, Tuple
from database import untils
from models import Task, SubtaskСomplite
from fastapi.responses import JSONResponse
import requests


app = FastAPI()
AI_API = "http://127.0.0.1:8001/"


@app.post("/create-task")
def create_task(task: Task, userLogin = Cookie(), markers: Dict[str, List[str]] | None = None):
    answer = untils.create_task(task, userLogin)
    task = task.dict()
    print(answer)
    if answer[0] != 0:
        return JSONResponse(content={"answer": answer[1], "ok": False}, status_code=400)

    if not (markers is None or set(markers.keys()).issubset([cl["content"] for cl in task["responseFormat"]])):
        return JSONResponse(content={"answer": "markers error", "ok": False}, status_code=404)

    r = requests.post(AI_API + "create-ai-task", json={"task_id": str(answer[2]),
                                                       "classes": [cl["content"] for cl in task["responseFormat"]], "markers": markers,
                                                       "description": task["description"],
                                                       "name": task["name"]})

    if r.status_code == 200:
        return JSONResponse(content={"answer": answer[1], "id": str(answer[2]), "ok": True}, status_code=201)
    delete_task(answer[2])
    return JSONResponse(content={"answer": "ai modul is not working", "ok": False})


@app.get("/get-tasks")
def get_tasks():
    tasks = untils.get_tasks()
    return tasks


@app.get("/get-history")
def get_histary():
    tasks = untils.get_history()
    return tasks


@app.get("/get-user-tasks")
def get_tasks(userLogin = Cookie()):
    print(userLogin)
    tasks = untils.get_user_tasks(userLogin)
    return tasks


@app.get("/get-creater-task")
def get_creator_task(userLogin = Cookie()):
    print(userLogin)
    tasks = untils.get_creator_task(userLogin)
    return tasks


@app.delete("/delete-task")
def delete_task(id: str):
    result = untils.delete_task(id)
    if result[0] != 0:
        return JSONResponse(content={"answer": result[1], "ok": False}, status_code=404)
    r = requests.delete(AI_API + "/delete-ai-task", json={"task_id": id})
    if r.status_code != 200:
        return JSONResponse(content={"answer": "AI IS NOT WORKING", "ok": False}, status_code=404)

    return JSONResponse(content={"answer": result[1], "ok": True}, status_code=200)


@app.patch("/update-task")
def update_task(id: str, task: Task):
    task = task.dict()
    result = untils.update_task(id, task)
    if result[0] != 0:
        return JSONResponse(content={"answer": result[1], "ok": False}, status_code=404)
    r = requests.patch(AI_API + "/update-ai-task", json={"task_id": id, "classes": [cl["content"] for cl in task["responseFormat"]]})
    if r.status_code != 200:
        return JSONResponse(content={"answer": "AI IS NOT WORKING", "ok": False}, status_code=404)
    return JSONResponse(content={"answer": result[1], "ok": True}, status_code=200)


@app.put("/complete-subtask")
def complete_subtask(data: SubtaskСomplite):
    answer = untils.complete_subtask(data.dict())
    if answer[0] == 1:
        return JSONResponse(content={"answer": answer[1], "not_found_id": answer[2], "ok": False}, status_code=404)
    elif answer[0] == 2:
        return JSONResponse(content={"answer": answer[1], "key": answer[2], "ok": False}, status_code=415)
    return JSONResponse(content={"answer": answer[1], "full_complete": answer[2], "ok": True}, status_code=200)





