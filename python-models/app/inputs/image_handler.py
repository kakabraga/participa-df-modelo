from app.pipelines.image_pipeline import ImagePipeline

class ImageHandler:

    def handle(self, file_path: str):
        pipeline = ImagePipeline()
        return pipeline.processarArquivo(file_path)


