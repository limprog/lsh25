from fastapi import FastAPI, File, Response, Cookie
from fastapi.responses import JSONResponse
from database import untils
from models import Task, SubtaskСomplite
from fastapi import FastAPI
from fastapi.responses import JSONResponse


app = FastAPI()


@app.post("/create-task")
def create_task(task: Task):
    answer = untils.create_task(task, "!")

    if answer[0] != 0:
        return JSONResponse(content={"answer": answer[1], "ok": False}, status_code=400)
    return JSONResponse(content={"answer": answer[1], "id": str(answer[2]), "ok": True}, status_code=201)


# @app.post("/load-img")
# def load_img(image: File(), creater_login: str):
#     pass

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
    return JSONResponse(content={"answer": result[1], "ok": True}, status_code=200)


@app.patch("/update-task")
def update_task(id: str, task: Task):
    result = untils.update_task(id, task.dict())
    if result[0] != 0:
        return JSONResponse(content={"answer": result[1], "ok": False}, status_code=404)
    return JSONResponse(content={"answer": result[1], "ok": True}, status_code=200)


@app.put("/complete-subtask")
def complete_subtask(data: SubtaskСomplite):
    answer = untils.complete_subtask(data.dict())
    if answer[0] == 1:
        return JSONResponse(content={"answer": answer[1], "not_found_id": answer[2], "ok": False}, status_code=404)
    elif answer[0] == 2:
        return JSONResponse(content={"answer": answer[1], "key": answer[2], "ok": False}, status_code=415)
    return JSONResponse(content={"answer": answer[1], "full_complete": answer[2], "ok": True}, status_code=200)

