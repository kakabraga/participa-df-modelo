from app.core.result import Result


class AudioPipeline:

    def processar(self, *, file_path: str) -> Result:
        # ⚠️ pipeline FAKE por enquanto
        return Result(
            status="ok",
            resultado="Limpo",
            origem="audio_pipeline",
            tipo_dado=None,
            confianca=1.0,
            evidencias=[]
        )
