from fastapi import FastAPI, File, Response, Cookie
from fastapi.responses import JSONResponse
from typing import List
from database import utils
from models import CreatAiTask, Update, TaskId, MarkerUpdate

app = FastAPI()


@app.post("/create-ai-task")
def create_task(task: CreatAiTask):
    task = task.dict()
    utils.create_ai_task(task)
    return {"ok": True}


@app.get("/get-ai-task")
def get_ai_task(task_id: str):
    return utils.get_ai_task(task_id)


@app.patch("/markers-update")
def update_markers(markers: MarkerUpdate):
    task_id, markers = markers.dict()["task_id"], markers.dict()["markers"]
    if utils.update_markers(task_id, markers):
        return {"ok": True}
    else:
        return JSONResponse(content={"ok": False}, status_code=404)


@app.patch("/update-ai-task")
def update_task(upd: Update):
    upd = upd.dict()
    task_id, classes = upd["task_id"], upd["classes"]
    utils.update_ai_task(task_id, classes)
    return {"ok": True}


@app.delete("/delete-ai-task")
def delete_ai_task(task_id: TaskId):
    utils.delete_ai_task(task_id.dict()["task_id"])
    return {"ok": True}