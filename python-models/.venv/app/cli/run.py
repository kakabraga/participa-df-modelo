import argparse
import json
import os
import sys

def main():
    parser = argparse.ArgumentParser(description="Processador de arquivos")

    parser.add_argument("--file", required=True)
    parser.add_argument("--type", required=True, choices=["image", "video"])
    parser.add_argument("--pedido-id", required=True)

    args = parser.parse_args()

    if not os.path.exists(args.file):
        print(json.dumps({
            "status": "error",
            "message": "Arquivo não encontrado"
        }))
        sys.exit(1)

    # Por enquanto: só confirma recebimento
    response = {
        "status": "ok",
        "pedido_id": args.pedido_id,
        "file": args.file,
        "type": args.type
    }

    print(json.dumps(response))


if __name__ == "__main__":
    main()
