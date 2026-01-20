import spacy
nlp = spacy.load("pt_core_news_sm")
doc = nlp("João da Silva nasceu em 1990")
for ent in doc.ents:
    print(ent.text, ent.label_)
