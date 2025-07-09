from bson import ObjectId
from pymongo import MongoClient
from typing import List

client = MongoClient('localhost', 27017)
db = client.ai_db
table = db.ai


def create_ai_task(task: dict) -> bool:
    table.insert_one(task)
    return True


def get_ai_task(task_id: str):
    temp = table.find_one({'task_id': task_id})
    temp["_id"] = str(temp["_id"])
    return temp


def update_ai_task(task_id: str, classes: List[str]) -> bool:
    temp = table.find_one({'task_id': task_id})
    marker = temp["markers"]
    if not set(marker.keys()).issubset(set(classes)):
        set_delete = set(marker.keys()) - set(classes)
        for i in set_delete:
            marker.pop(i, None)
    table.update_one({'task_id': task_id}, {'$set': {"classes": classes, "markers": marker}})

    return True


def delete_ai_task(task_id: str):
    table.delete_one({"task_id": task_id})
    return True


def update_markers(task_id: str, markers) -> bool:
    temp = table.update_one({'task_id': task_id}, {"$set": {"markers": markers}})
    if temp.modified_count == 0:
        return False
    return True

