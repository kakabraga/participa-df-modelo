import whisper
import re

from app.core.result import Result
from app.core.analise_builder import AnaliseBuilder
from app.core.decision_engine import DecisionEngine
from app.core.nlp_service import NLPService


class AudioPipeline:
    def __init__(self):
        self.model = whisper.load_model("base")
        self.nlp_service = NLPService()
        self.decision_engine = DecisionEngine()

    def processar(self, file_path: str) -> Result:
        try:
            resultado = self.model.transcribe(file_path, language="pt")
            texto = resultado["text"]

            if not texto.strip():
                raise ValueError("Não foi possível extrair texto do áudio")

            texto = self.normalizar_texto(texto)

            decisao = self.analisar_texto(texto)

            return Result.from_decisao(decisao, origem="modelo_nlp")

        except Exception as e:
            return Result.erro(f"Erro ao processar áudio: {str(e)}")


    def analisar_texto(self, texto: str) -> dict:
        evidencias = []

        evidencias.extend(self.nlp_service.analisar(texto))

        builder = AnaliseBuilder()
        analise = builder.build(evidencias)

        return self.decision_engine.decidir(analise)

    def normalizar_texto(self, texto: str) -> str:
        texto = texto.lower()
        texto = re.sub(r"\s+", " ", texto)
        texto = re.sub(r"[^a-z0-9áàâãéèêíïóôõöúç ]", "", texto)

        return texto.strip()
