from pymongo import MongoClient
from bson.objectid import ObjectId
import pprint

client = MongoClient('localhost', 27017)
db = client.task_db
work = db.task_work
history = db.task_history


def create_task(rec) -> tuple:
    rec = rec.dict()
    rec['subtasks'] = enumerate_list(rec['subtasks'])
    rec["responseFormat"] = enumerate_list(rec["responseFormat"])

    rec["responseCount"] = f"0/{len(rec['subtasks'])}"
    res = work.insert_one(rec)

    return 0, "Task created", res.inserted_id


def get_tasks() -> list:
    tasks = work.find()
    result = []
    for task in tasks:
        task["_id"] = str(task["_id"])
        result.append(task)
    return result


def get_history() -> list:
    tasks = history.find()
    result = []
    for task in tasks:
        task["_id"] = str(task["_id"])
        result.append(task)
    return result


def get_user_tasks(userLogin: str) -> list:
    tasks = work.find({"userLogin": userLogin})
    result = []

    for task in tasks:
        task["_id"] = str(task["_id"])
        result.append(task)

    return result


def get_creator_task(createrLogin: str) -> list:
    tasks = work.find({"createrLogin": createrLogin})
    result = []

    for task in tasks:
        task["_id"] = str(task["_id"])
        result.append(task)

    return result


def delete_task(id: str) -> tuple:
    if work.delete_one({"_id": ObjectId(id)}).deleted_count == 1:
        return 0, "Task deleted"
    else:
        return 1, "Not exist"


def update_task(id: str, task: dict) -> tuple:

    task['subtasks'] = enumerate_list(task['subtasks'])
    task["responseFormat"] = enumerate_list(task["responseFormat"])

    task["responseCount"] = f"0/{len(task['subtasks'])}"
    res = work.update_one({"_id": ObjectId(id)}, {"$set": task})
    if res.matched_count == 1:
        return 0, "Task updated"
    else:
        return 1, "Not exist"


def complete_subtask(data: dict) -> tuple:
    task = work.find_one({"_id": ObjectId(data["task_id"])})
    subtasks = task["subtasks"]
    compile_subtask_count = int(task["responseCount"].split("/")[0])

    if task is None:
        return 1, "Not exist", "TASK"
    answer_to_problems = data["responseAnswer"]

    for key in answer_to_problems:
        try:
            int_key = int(key)
        except ValueError:
            return 2, "ValueError", key
        try:
            task["subtasks"][int_key]["content"] = answer_to_problems[key]
        except IndexError:
            return 1, f"subtask {key} not exist", int_key
        compile_subtask_count += 1

    # if task complite
    if compile_subtask_count >= len(subtasks):
        work.delete_one({"_id": ObjectId(data["task_id"])})
        task["subtasks"] = subtasks
        task["responseCount"] = f"{compile_subtask_count}/{compile_subtask_count}"
        history.insert_one(task)
        return 0, "Task completed", 1

    work.update_one({"_id": ObjectId(data["task_id"])}, {"$set": {"subtasks": subtasks, "responseCount": f"{compile_subtask_count}/{len(subtasks)}"}})
    return 0, "ok", 0


def enumerate_list(d: dict):
    temp = []
    for index, item in enumerate(d):
        temp.append({"id": index, **item})
    return temp

