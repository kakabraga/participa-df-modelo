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
    try:
        args, tipo_entrada = parse_args()
        handler = resolve_handler_por_args(args, tipo_entrada)
        result = executar_handler(handler, args, tipo_entrada)
        imprimir_resultado(result)

    except Exception as e:
        print(json.dumps({
            "status": "error",
            "message": str(e)
        }))
        sys.exit(1)

def detectar_tipo_entrada(argv: list[str]) -> str:
    if any(arg.startswith("--file") for arg in argv):
        return "file"

    if any(arg.startswith("--text") for arg in argv):
        return "text"

    return "unknown"


def parse_args():
    tipo = detectar_tipo_entrada(sys.argv)

    if tipo == "file":
        return monta_parse_arquivo(), "file"

    if tipo == "text":
        return monta_parse_texto(), "text"

    raise ValueError("Informe --file ou --text")

def monta_parse_arquivo():
    parser = argparse.ArgumentParser(description="Processador de arquivos")
    parser.add_argument("--type", required=True)
    parser.add_argument("--file", required=True)
    parser.add_argument("--pedido_id", required=True)

    return parser.parse_args()

def monta_parse_texto():
    parser = argparse.ArgumentParser(description="Processador de texto")

    parser.add_argument("--text", required=True)

    return parser.parse_args()

def resolve_handler_por_args(args, tipo_entrada: str):
    if tipo_entrada == "file":
        return resolveHandler(args.type)

    if tipo_entrada == "text":
        return resolveHandler("text")

    raise RuntimeError("Handler não implementado")


def executar_handler(handler, args, tipo_entrada: str):
    if tipo_entrada == "file":
        return handler.handle_file(args.file)

    if tipo_entrada == "text":
        return handler.handle_text(args.text)

    raise RuntimeError("Tipo de execução inválido")



def montaChoices():
    return IMAGE_TYPES + AUDIO_TYPES + VIDEO_TYPES + ["txt"]

def imprimir_resultado(result: Result):
    print(json.dumps(result.to_dict()))


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
