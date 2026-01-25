import pytesseract
import re

from app.core.analise_builder import AnaliseBuilder
from app.core.decision_engine import DecisionEngine
from app.core.result import Result
from app.core.nlp_service import NLPService
pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

PALAVRAS_CHAVE_DOCUMENTO = [
    "cpf",
    "rg",
    "carteira de identidade",
    "data de nascimento",
    "filiação",
    "nome",
    "assinatura",
]

REGEX_PADROES = {
    "cpf": r"\b\d{3}\.\d{3}\.\d{3}-\d{2}\b|\b\d{11}\b",
    "data_nascimento": r"\b\d{2}/\d{2}/\d{4}\b",
}


class TextoPipeline:
    
    def __init__(self):
        self.decision_engine = DecisionEngine()
        self.nlp_service = NLPService()

    def processar(self, texto):
        texto = self.normalizar_texto(texto)

        decisao = self.analisar_texto(texto)

        return Result.from_decisao(
            decisao,
            origem="modelo_nlp"
        )

    def normalizar_texto(self, texto: str) -> str:
        texto = texto.lower()
        texto = re.sub(r"\s+", " ", texto)
        texto = re.sub(r"[^a-z0-9áàâãéèêíïóôõöúç ]", "", texto)
        return texto

        
    def analisar_texto(self, texto: str) -> dict:
        evidencias = []
        evidencias.extend(self.verificarPalavraChave(texto)["evidencias"])
        evidencias.extend(self.verificarRegex(texto)["evidencias"])
        evidencias.extend(self.nlp_service.analisar(texto))
        builder = AnaliseBuilder()
        analise = builder.build(evidencias)
        return self.decision_engine.decidir(analise)
    
    def verificarPalavraChave(self, texto: str) -> dict:
        evidencias = []

        for palavra in PALAVRAS_CHAVE_DOCUMENTO:
            if palavra in texto:
                evidencias.append({
                    "tipo": "palavra",
                    "valor": palavra,
                    "score": 0.15
                })

        return {"evidencias": evidencias}
    def verificarRegex(self, texto: str) -> dict:
        evidencias = []
        tipo_dado = None

        for tipo, pattern in REGEX_PADROES.items():
            if re.search(pattern, texto):
                evidencias.append({
                    "tipo": "regex",
                    "valor": tipo,
                    "score": 0.5
                })
                tipo_dado = tipo

        return {
            "evidencias": evidencias,
            "tipo_dado": tipo_dado
        }
