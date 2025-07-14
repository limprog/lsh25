from pydantic import BaseModel
from typing import List, Dict


class Subtask(BaseModel):
    description: str
    image: str | None = None


class ResponseFormat(BaseModel):
    content: str


class Task(BaseModel):
    userLogin: str
    createrLogin: str | None = None
    name: str
    subtasks: List[Subtask]
    description: str
    responseFormat: List[ResponseFormat]
    score: int


class SubtaskСomplite(BaseModel):
    task_id: str
    responseAnswer: Dict[str, str]