from app.inputs.interfaces.IHandler import Ihandler
from app.pipelines.text_pipeline import TextPipeline


class TextHandler(Ihandler):

    def __init__(self):
        self.pipeline = TextPipeline()

    def handle(self, args):

        if not args.text:
            raise ValueError("Texto não informado")

        return self.pipeline.processar(args.text)
