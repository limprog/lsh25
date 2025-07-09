from fastapi import FastAPI
import bcrypt
from pydantic import BaseModel
from datetime import data
from pymongo import MongoClient
import os


class User(BaseModel):
    userLogin: str|None = None
    password: str|None = None
    score: int = 0
    streek: int = 0
    rights: str|None = None
    create_date: data|None = None

user_client = MongoClient(os.getenv("MONGO_USERS_URI"))
app = FastAPI()

@app.post('/api/users/register')
def registration(new_user: User) -> dict:
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
        return {'ok': True,
                'message': 'Успешная регистрация!'}
    except:
        return {'ok': False,
                'message': 'Ошибка на сервере!'}
    

@app.get('/api/users/login')
def authorization(user: User) -> dict:
    try:
        orig_user = user_client.find_one({'userLogin': user.userLogin})
        if not orig_user:
            if bcrypt.checkpw(user.password.encode(), orig_user[password]):
                return {'ok': True,
                        'message:': 'Успешная авторизация!'}
            return {'ok': False,
                    'message': 'Неверный пароль!'}
        return {'ok:': False,
                'message': 'Несуществующий пользователь!'}
    except:
        return {'ok': False,
                'message': 'Ошибка на сервере!'}


@app.get('/api/users/get-users')
def get_users():
    users = [user for user in user_client.find()]
    return users


@app.get('/api/users/get-user-data')
def get_user_inf(login:str) -> dict:
    user = user_client.find_one({'userLogin': login})
    if user:
        return user_client.find_one({'userLogin': login})
    return {'message': 'Пользователь не найден!'}


@app.patch('/api/users/edit-password')
def update_password(login:str, new_pasword:str) -> dict:
    try:
        user = user_client.find_one({'userLogin': login})
        if user:
            new_password = bcrypt.hashpw(new_user.password.encode('utf-8'), bcrypt.gensalt())
            user_client.update_one({'_id': user.id}, {'$set': {'password': 'new_password'}})
            return {'ok': True,
                    'message': 'Пароль изменен!'}
    except:
        return {'ok': False,
                'message': 'Пользователь не найден!'}