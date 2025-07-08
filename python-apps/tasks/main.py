from flask import Flask

app = Flask(__name__)

@app.route('/test')
def test_endpoint():
    return "success"

if __name__ == '__main__':
    app.run(debug=True)