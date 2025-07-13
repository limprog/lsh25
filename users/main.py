from fastapi import FastAPI, Response, Cookie, Body, status, HTTPException, APIRouter
import bcrypt
from pydantic import BaseModel
from datetime import time
from pymongo import MongoClient
import requests
import json
from pprint import pprint

def login(username: str, password: str) -> dict:
    endpoint_url = "http://89.169.146.136:8765/users/auth"

    reqData = {
        "username": username,
        "password": password
    }

    try:
        # Отправляем POST-запрос на внешний эндпоинт
        # Заголовок Content-Type: application/json указывает, что тело запроса - JSON
        response = requests.post(endpoint_url, json=reqData)

        # Проверяем, был ли запрос успешным (статус 2xx)
        response.raise_for_status()

        # Возвращаем JSON-ответ от эндпоинта
        return response.json()

    except requests.exceptions.HTTPError as http_err:
        # Обработка ошибок HTTP (например, 404, 500)
        return {"ok": False, "message": f"Ошибка HTTP: {http_err}", "details": response.text}
    except requests.exceptions.ConnectionError as conn_err:
        # Обработка ошибок подключения (например, сервер недоступен)
        return {"ok": False, "message": f"Ошибка подключения: {conn_err}"}
    except requests.exceptions.Timeout as timeout_err:
        # Обработка ошибок тайм-аута
        return {"ok": False, "message": f"Ошибка тайм-аута: {timeout_err}"}
    except requests.exceptions.RequestException as req_err:
        # Обработка любых других ошибок запроса
        return {"ok": False, "message": f"Произошла ошибка: {req_err}"}
    except json.JSONDecodeError:
        # Обработка случая, когда ответ не является валидным JSON
        return {"ok": False, "message": "Не удалось декодировать JSON из ответа", "raw_response": response.text}



class User(BaseModel):
    userLogin: str|None = None
    password: str|None = None
    score: int = 0
    streek: int = 0
    rights: str|None = None
    create_date: time|None = None
    email: str|None = None


user_client = MongoClient('localhost', 27017)['users']["user_db"]
app = FastAPI()


@app.post('/register')
def registration(new_user: User, response: Response) -> dict:
    new_user = new_user.dict()
    if user_client.find_one({'userLogin': new_user["userLogin"]}):
        return {'ok': False,
                'message': 'Имя пользователя уже занято!'}
    new_user["password"] = bcrypt.hashpw(new_user["password"].encode('utf-8'), bcrypt.gensalt())
    req = requests.post("http://89.169.146.136:8765/users/registration", json={"username": new_user["userLogin"], "password": str(new_user["password"]), "confirm_password": str(new_user["password"]), "email": new_user["email"]})
    if req.status_code == 400:
        return {'ok': False,
                'message': 'Имя пользователя уже занято!'}
    user_client.insert_one({'userLogin': new_user["userLogin"],
                            'password': new_user["password"],
                            'score': new_user["score"],
                            'streek': new_user["streek"],
                            'rights': "ROLE_ADMIN",
                            'create_date': new_user["create_date"].isoformat(),
                            "id": req.json()['id'],
                            "email": new_user["email"]
                            })
    response.set_cookie(key="userLogin", value=new_user["userLogin"])
    return {'ok': True,
            'message': 'Успешная регистрация!'}
    

@app.post('/login')
async def login_user(
    response: Response,
    username: str = Body(..., description="Имя пользователя"),
    password: str = Body(..., description="Пароль")
) -> dict:
    result = login(username, password)

    if result.get("token"):
        response.set_cookie(key = "bearerToken", value = result["token"], max_age = 3600)
        response.status_code = status.HTTP_200_OK
        return {
            "ok": True
        }
    else:
        response.status_code = status.HTTP_400_BAD_REQUEST
        return {
            "ok": False
        }


@app.get('/get-users')
def get_users():
    users = user_client.find()
    result = []
    for user in users:
        user['_id'] = str(user['_id'])
        result.append(user)
    return result


@app.get('/get-user-data')
def get_user_inf(login:str) -> dict:
    user = user_client.find_one({'userLogin': login})
    if user:
        user['_id'] = str(user['_id'])
        return user
    return {'message': 'Пользователь не найден!'}


@app.patch('/edit-password')
def update_password(login:str, new_pasword:str) -> dict:
    try:
        user = user_client.find_one({'userLogin': login})
        if user:
            new_password = bcrypt.hashpw(new_password.encode('utf-8'), bcrypt.gensalt())
            user_client.update_one({'userLogin': login}, {'$set': {'password': new_password}})
            return {'ok': True,
                    'message': 'Пароль изменен!'}
        return {'ok': False,
                'message': 'Пользователь не найден'}
    except:
        return {'ok': False,
                'message': 'Ошибка на сервере!'}


@app.patch('/user-change-role')
def update_role(login:str) -> dict:
    try:
        user = user_client.find_one({'userLogin': login})
        if user and user['rights'] == "ROLE_ADMIN":
            user_client.update_one({'userLogin': login}, {'$set': {'role': "ROLE_USER"}})
            user_id = user['id']
            r = requests.request(f"http://89.169.146.136:8081/users/user/{user_id}")
            if r.status_code == 200:
                return {'ok': True}
        return {'ok': False, "mes": "user not exist or his role user"}
    except:
        return {"ok": False, "mes": "server error"}


@app.patch("/user-update-score")
def update_score(login:str, add_score:int) -> dict:
    user = user_client.find_one({'userLogin': login})
    if not user:
        return {'ok': False,"mes": "user not exist"}
    score = user['score'] + add_score
    user_client.update_one({'userLogin': login}, {'$set': {'score': score}})
    return {'ok': True, "mes": "score updated successfully"}

