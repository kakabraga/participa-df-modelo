import spacy

class NLPService:
    def __init__(self):
        # Carrega o modelo em português
        self.nlp = spacy.load("pt_core_news_sm")

    def analisar(self, texto: str) -> list[dict]:
        """
        Recebe um texto e retorna entidades PII ou padrões que queremos detectar
        Exemplo de retorno:
        [
            {"tipo": "entidade", "valor": "João da Silva", "score": 0.8},
            {"tipo": "entidade", "valor": "01/01/1990", "score": 0.5}
        ]
        """
        doc = self.nlp(texto)
        evidencias = []

        for ent in doc.ents:
            # Filtros básicos: podemos escolher tipos de entidade
            if ent.label_ in ["PER", "DATE", "ORG"]:
                evidencias.append({
                    "tipo": "entidade",
                    "valor": ent.text,
                    "score": 0.5  # score base, você pode ajustar
                })

        return evidencias
