from spacy import load
from spacy.lang.ru import Russian
from typing import List, Dict

nlp = Russian()
load_model = load("ru_core_news_md")


def markers_analysis(markers: Dict[str, List[str]], text) -> str:
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
    return doc[0].lemma_

