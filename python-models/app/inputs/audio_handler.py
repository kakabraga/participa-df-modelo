class AudioHandler:
    def handle(self, file_path: str):
        from app.pipelines.audio_pipeline import AudioPipeline

        pipeline = AudioPipeline()
        return pipeline.processarArquivo(file_path)
