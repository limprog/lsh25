from fastapi import FastAPI, Response, Cookie
import bcrypt
from pydantic import BaseModel
from datetime import time
from pymongo import MongoClient


class User(BaseModel):
    userLogin: str|None = None
    password: str|None = None
    score: int = 0
    streek: int = 0
    rights: str|None = None
    create_date: time|None = None


user_client = MongoClient('url')['tests']
app = FastAPI()


@app.post('/register')
def registration(new_user: User, response: Response) -> dict:
    try:
        if user_client.find_one({'userLogin': new_user.userLogin}):
            return {'ok': False,
                    'message': 'Имя пользователя уже занято!'}
        new_user.password = bcrypt.hashpw(new_user.password.encode('utf-8'), bcrypt.gensalt())
        user_client.insert_one({'userLogin': new_user.userLogin,
                                'password': new_user.password,
                                'score': new_user.score,
                                'streek': new_user.streek,
                                'rights': new_user.rights,
                                'create_date': new_user.create_date,
                                })
        response.set_cookie(key="userLogin", value=new_user.userLogin)
        return {'ok': True,
                'message': 'Успешная регистрация!'}
    except:
        return {'ok': False,
                'message': 'Ошибка на сервере!'}
    

@app.get('/login')
def authorization(user: User, responce: Response, userLogin: str | None = Cookie(default=None)) -> dict:
    try:
        if userLogin:
            orig_user = user_client.find_one({'userLogin': userLogin})
        else:
            orig_user = user_client.find_one({'userLogin': user.userLogin})
        if orig_user:
            if bcrypt.checkpw(user.password.encode('utf-8'), orig_user[password]):
                response.set_cookie(key="userLogin", value=orig_user.userLogin)
                return {'ok': True,
                        'message:': 'Успешная авторизация!'}
            return {'ok': False,
                    'message': 'Неверный пароль!'}
        return {'ok:': False,
                'message': 'Несуществующий пользователь!'}
    except:
        return {'ok': False,
                'message': 'Ошибка на сервере!'}


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