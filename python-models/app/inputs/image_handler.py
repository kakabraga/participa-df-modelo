from app.pipelines.image_pipeline import ImagePipeline

class ImageHandler:

    def handle_file(self, file_path: str):
        pipeline = ImagePipeline()
        return pipeline.processarArquivo(file_path)

    def handle_text(self, text: str):
        raise RuntimeError("ImageHandler não suporta texto direto")

