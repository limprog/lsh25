from fastapi import FastAPI, File, Response, Cookie
from fastapi.responses import JSONResponse
from database import utils
from models import CreatAiTask, Update, TaskId, MarkerUpdate, AiRequest
from ai_fun import markers_analysis


app = FastAPI()


# Part without AI
@app.post("/create-ai-task")
def create_task(task: CreatAiTask):
    task = task.dict()
    utils.create_ai_task(task)
    return {"ok": True}


@app.get("/get-ai-task")
def get_ai_task(task_id: str):
    res = utils.get_ai_task(task_id)
    if not res:
        return JSONResponse({"ok": False}, status_code=404)
    return res


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


#AI PART
@app.get("/markers-answer")
def get_markers_answer(task_id: str, text: str):
    task = get_ai_task(task_id)
    markers = task["markers"]
    if not markers:
        return JSONResponse(content={"ok": False, "mes": "Not markers"}, status_code=424)

    result = markers_analysis(task["markers"], text)
    return {"ok": True, "result": result}

