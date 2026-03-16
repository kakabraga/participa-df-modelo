class TextoHandler:

    def handle(self, texto: str):
        from app.pipelines.texto_pipeline import TextoPipeline

        pipeline = TextoPipeline()
        return pipeline.processar(texto)
