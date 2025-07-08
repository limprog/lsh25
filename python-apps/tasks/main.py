from flask import Flask
import os

app = Flask(__name__)

@app.route('/test')
def test_endpoint():
    return [os.getenv("MONGO_TASKS_URI"), os.getenv("MONGO_USERS_URI")]

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)