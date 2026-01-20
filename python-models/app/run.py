import argparse
import json
import sys

from app.inputs.image_handler import ImageHandler
# from app.inputs.audio_handler import  AudioHandler
# from app.inputs.video_handler import  VideoHandler
from app.core.result import Result


IMAGE_TYPES = ["jpg", "jpeg"]
AUDIO_TYPES = ["mp3"]
VIDEO_TYPES = ["mp4"]


def main():
    parser = argparse.ArgumentParser(description="Processador de arquivos")
    args = montaParse(parser)

    handler = resolveHandler(args.type)

    if handler is None:
        print(json.dumps({
            "status": "error",
            "message": "Pipeline não implementado",
            "tipo_arquivo": args.type
        }))
        sys.exit(1)

    result = handler.handle(file_path=args.file)

    print(json.dumps(result.to_dict()))


def montaParse(parser):
    parser.add_argument("--file", required=True)
    parser.add_argument("--type", required=True, choices=montaChoices())
    parser.add_argument("--pedido-id", required=True)
    return parser.parse_args()


def montaChoices():
    return IMAGE_TYPES + AUDIO_TYPES + VIDEO_TYPES + ["txt"]


def resolveHandler(tipo):
    if tipo in IMAGE_TYPES:
        return ImageHandler()

    if tipo in AUDIO_TYPES:
        return Audiohandler()

    if tipo in VIDEO_TYPES:
        return Videohandler()
    
    if tipo in ["txt"]:
        return ImagePipeline()

    return None


if __name__ == "__main__":
    main()
