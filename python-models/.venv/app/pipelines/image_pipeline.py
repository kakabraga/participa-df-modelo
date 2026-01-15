from app.core.result import Result


class ImagePipeline:

    def processar(self, *, file_path: str) -> Result:
        # ⚠️ pipeline FAKE por enquanto
        return Result(
            status="ok",
            resultado="Limpo",
            origem="image_pipeline",
            tipo_dado=None,
            confianca=1.0,
            evidencias=[]
        )
