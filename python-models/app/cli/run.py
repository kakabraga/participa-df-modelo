import argparse
import json
import sys

from app.inputs.image_handler import ImageHandler
from app.inputs.texto_handler import TextoHandler
from app.core.result import Result


def main():
    try:
        args = parse_args()
        handler = resolve_handler(args.type)

        if args.file:
            result = handler.handle(args.file)
        elif args.text:
            result = handler.handle(args.text)
        else:
            raise ValueError("Informe --file ou --text")

        imprimir_resultado(result)

    except Exception as e:
        print(json.dumps({
            "status": "error",
            "message": str(e)
        }))
        sys.exit(1)


def parse_args():
    parser = argparse.ArgumentParser(description="Processador multimodal")

    parser.add_argument("--type", choices=["image", "texto"])
    parser.add_argument("--file")
    parser.add_argument("--text")
    parser.add_argument("--pedido_id")

    args = parser.parse_args()

    if not args.type:
            if args.text:
                args.type = "text"
            elif args.file:
                args.type = "image"
            else:
                raise ValueError("Informe --text ou --file")

    return args


def resolve_handler(tipo: str):
    handlers = {
        "image": ImageHandler,
        # "audio": AudioHandler,
        # "video": VideoHandler,
        "text": TextoHandler,
    }

    if tipo not in handlers:
        raise ValueError(f"Tipo não suportado: {tipo}")

    return handlers[tipo]()


def imprimir_resultado(result: Result):
    print(json.dumps(result.to_dict()))


if __name__ == "__main__":
    main()
 