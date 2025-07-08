from pydantic import BaseModel
from typing import List


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