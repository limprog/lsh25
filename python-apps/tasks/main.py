from fastapi import FastAPI, File, UploadFile, Form
from fastapi.responses import JSONResponse
from database import untils
from models import Task, SubtaskСomplite

app = FastAPI()


@app.post("/create-task")
def create_task(task: Task):
    answer = untils.create_task(task)
    if answer[0] != 0:
        return JSONResponse(content={"answer": answer[1], "ok": False}, status_code=400)
    return JSONResponse(content={"answer": answer[1], "id": str(answer[2])}, status_code=201)


@app.post("/load-img")
def load_img(image: UploadFile = File(...), creater_login: str = Form(...)):
    return {"message": "File received", "filename": image.filename, "creater_login": creater_login}

@app.get("/get-tasks")
def get_tasks():
    tasks = untils.get_tasks()
    return tasks


@app.get("/get-history")
def get_histary():
    tasks = untils.get_history()
    return tasks


@app.get("/get-user-tasks")
def get_tasks(userLogin: str):
    tasks = untils.get_user_tasks(userLogin)
    return tasks


@app.get("/get-creater-task")
def get_creator_task(createrLogin: str):
    tasks = untils.get_creator_task(createrLogin)
    return tasks


@app.delete("/delete-task")
def delete_task(id: str):
    result = untils.delete_task(id)
    if result[0] != 0:
        return JSONResponse(content={"answer": result[1]}, status_code=404)
    return JSONResponse(content={"answer": result[1]}, status_code=200)


@app.patch("/update-task")
def update_task(id: str, task: Task):
    result = untils.update_task(id, task.dict())
    if result[0] != 0:
        return JSONResponse(content={"answer": result[1]}, status_code=404)
    return JSONResponse(content={"answer": result[1]}, status_code=200)


@app.put("/complete-subtask")
def complete_subtask(data: SubtaskСomplite):
    answer = untils.complete_subtask(data.dict())
    if answer[0] == 1:
        return JSONResponse(content={"answer": answer[1], "not_found_id": answer[2]}, status_code=404)
    elif answer[0] == 2:
        return JSONResponse(content={"answer": answer[1], "key": answer[2]}, status_code=415)
    return JSONResponse(content={"answer": answer[1], "full_complete": answer[2]}, status_code=200)
