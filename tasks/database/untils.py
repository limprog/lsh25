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
    work.insert_one(rec)

    return (0, "Task created")


def get_tasks() -> list:
    tasks = work.find()
    result = []
    for task in tasks:
        task["_id"] = str(task["_id"])
        result.append(task)
    return result


def delete_task(id: str) -> tuple:
    if work.delete_one({"_id": ObjectId(id)}).deleted_count == 1:
        return (0, "Task deleted")
    else:
        return (1, "Not exist")


def enumerate_list(d: dict):
    temp = []
    for index, item in enumerate(d):
        temp.append({"id": index, **item})
    return temp

