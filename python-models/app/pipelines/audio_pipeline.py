from app.core.result import Result
import whisper

from app.core.analise_builder import AnaliseBuilder
from app.core.decision_engine import DecisionEngine
from app.core.result import Result
from app.core.nlp_service import NLPService


class AudioPipeline:
    def __init__(self):
        # Carregue o modelo apenas uma vez
        self.model = whisper.load_model("base")

    def processar(self, *, file_path: str) -> Result:

        self.model = whisper.load_model("base")

        texto = self.model.transcribe(file_path, language="pt")
        decisao = self.analisar_texto(texto)

        return Result.from_decisao(decisao, origem="modelo_nlp")

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
