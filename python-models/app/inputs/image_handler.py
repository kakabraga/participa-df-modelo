class ImageHandler:

    def handle(self, file_path: str):
        from app.pipelines.image_pipeline import ImagePipeline

        pipeline = ImagePipeline()
        return pipeline.processarArquivo(file_path)
