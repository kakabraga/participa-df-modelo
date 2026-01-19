import argparse
import json
import sys

from app.pipelines.image_pipeline import ImagePipeline
from app.pipelines.audio_pipeline import AudioPipeline
from app.pipelines.video_pipeline import VideoPipeline
from app.core.result import Result


IMAGE_TYPES = ["jpg", "jpeg"]
AUDIO_TYPES = ["mp3"]
VIDEO_TYPES = ["mp4"]


def main():
    parser = argparse.ArgumentParser(description="Processador de arquivos")
    args = montaParse(parser)

    pipeline = resolvePipeline(args.type)

    if pipeline is None:
        print(json.dumps({
            "status": "error",
            "message": "Pipeline não implementado",
            "tipo_arquivo": args.type
        }))
        sys.exit(1)

    result = pipeline.processar(file_path=args.file)

    print(json.dumps(result))


def montaParse(parser):
    parser.add_argument("--file", required=True)
    parser.add_argument("--type", required=True, choices=montaChoices())
    parser.add_argument("--pedido-id", required=True)
    return parser.parse_args()


def montaChoices():
    return IMAGE_TYPES + AUDIO_TYPES + VIDEO_TYPES + ["txt"]


def resolvePipeline(tipo):
    if tipo in IMAGE_TYPES:
        return ImagePipeline()

    if tipo in AUDIO_TYPES:
        return AudioPipeline()

    if tipo in VIDEO_TYPES:
        return VideoPipeline()
    
    if tipo in ["txt"]:
        return ImagePipeline()

    return None


if __name__ == "__main__":
    main()
