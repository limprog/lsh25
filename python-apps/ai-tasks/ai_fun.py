from spacy import load
from spacy.lang.ru import Russian
from typing import List, Dict
from openai import OpenAI
import os


nlp = Russian()
load_model = load("ru_core_news_md")
client = OpenAI(
    api_key=os.getenv("AI_KEY"),
    base_url="https://api.proxyapi.ru/openai/v1")


def markers_analysis(markers: Dict[str, List[str]], text: str) -> str:
    text = text.lower()
    score_dict = dict.fromkeys(markers, 0)
    print(markers)
    doc = load_model(text)
    for token in doc:
        for marker in markers:
            if token.lemma_ in markers[marker]:
                score_dict[marker] += 1
    return max(score_dict, key=score_dict.get)


def lemmatize(text: str) -> str:
    text = text.lower()
    doc = load_model(text)
    text_output = " ".join([token.lemma_ for token in doc])
    return text_output


def llm_answer(text: str, task: Dict) -> str:
    answer = client.responses.create(
        model="gpt-4.1-nano",
        input=f"У меня есть задача с названием: '{task['name']}' \n"
              f"У нее такое описание : '{task['description']}' \nТебе необходимо проанализировать сообщение "
              f"ниже и отнести его к одному из классов: {task['classes']}, \n"
              f"Тебе дано следующее сообщение: '{text}' \n"
              f"тебе нужно ответить только классом из списка выше. НИКАКИХ РАЗМЫШЛЕНИЙ И ДОВОДОВ."
    )
    return answer.output_text


def get_ai_markers(task: Dict) -> Dict[str, List[str]]:
    answer = client.responses.create(
        model="gpt-4.1-nano",
        input=f'Мне нужно чтобы ты помог мне решить задачу. У меня есть задача с названием {task["name"]}. '
              f'У нее такое описание:  {task["description"]} Существуют следующие классы: {task["classes"]}'
              f'Тебе нужно определить слова маркеры в текстах, с помощью  которых текст на русском можно '
              f'отнести к какому-то из классов, на каждый класс придумай 5 маркеров. Ты должен вывести ответ в такой форме '
              f'class1:marker1_class1,marker2_class2;class2:marker1_class2 и так далее'
              f'В выводе ты должен написать только так, без размышлений и доводов ответ должен быть одной строчко'
    )
    return str_to_markers(answer.output_text)


def str_to_markers(text: str) -> Dict[str, List[str]]:
    result = dict()
    for class_markers in text.split(";"):
        cl, markers = class_markers.split(":")
        result[cl] = markers.split(",")
    return result


def llm_img_answer(text: str, task: Dict, img) -> str:
    pass