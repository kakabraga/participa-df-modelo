from app.pipelines.texto_pipeline import TextoPipeline

class TextoHandler:

    def handle(self, texto: str):
        pipeline = TextoPipeline()
        return pipeline.processar(texto)


