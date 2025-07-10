from typing import List, Dict, Tuple
from pydantic import BaseModel


class CreatAiTask(BaseModel):
    task_id: str
    classes: List[str]
    markers: Dict[str, List[str]] | None = None


class Update(BaseModel):
    task_id: str
    classes: List[str]

class TaskId(BaseModel):
    task_id: str


class MarkerUpdate(BaseModel):
    task_id: str
    markers: Dict[str, List[str]]


class AiRequest(BaseModel):
        task_id: str
        text: str
        description: str | None = None
