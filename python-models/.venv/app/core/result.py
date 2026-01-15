from typing import Optional, List


class Result:
    def __init__(
        self,
        *,
        status: str,
        resultado: str,
        origem: str,
        tipo_dado: Optional[str] = None,
        confianca: float = 0.0,
        evidencias: Optional[List[str]] = None
    ):
        self.status = status              # ok | error
        self.resultado = resultado        # Detectado | Limpo
        self.origem = origem              # image_pipeline | video_pipeline | etc
        self.tipo_dado = tipo_dado
        self.confianca = confianca
        self.evidencias = evidencias or []

    def to_dict(self) -> dict:
        return {
            "status": self.status,
            "resultado": self.resultado,
            "origem": self.origem,
            "tipo_dado": self.tipo_dado,
            "confianca": self.confianca,
            "evidencias": self.evidencias,
        }
