from pydantic import BaseModel
from typing import List, Dict


class Subtask(BaseModel):
    description: str


class ResponseFormat(BaseModel):
    content: str


class Task(BaseModel):
    userLogin: str
    createrLogin: str
    name: str
    subtasks: List[Subtask]
    description: str
    responseFormat: List[ResponseFormat]


class SubtaskСomplite(BaseModel):
    task_id: str
    responseAnswer: Dict[str, str]