from pymongo import MongoClient
import pprint

client = MongoClient('localhost', 27017)
db = client.task_db
work = db.task_work
history = db.task_history


def create_task(rec):
    rec = rec.dict()

    rec['subtasks'] = enumerate_list(rec['subtasks'])
    rec["responseFormat"] = enumerate_list(rec["responseFormat"])

    rec["responseCount"] = f"0/{len(rec['subtasks'])}"
    work.insert_one(rec)

    return (0, "Task created")


def enumerate_list(d: dict):
    temp = []
    for index, item in enumerate(d):
        temp.append({"id": index, **item})
    return temp

