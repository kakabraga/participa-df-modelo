import argparse
import json
import os
import sys

def main():
    parser = argparse.ArgumentParser(description="Processador de arquivos")  
    args   = montaParse(parser)
    
    # response = montaRespostaPadrao(args, parser)

    if verificaArquivo(args):
        response = montaRespostaPadrao(args, parser)

    
    print(json.dumps(response))

def montaRespostaPositiva(args, parser):
     return  {
        "status": "ok",
        "pedido_id": args.pedido_id,
        "file": args.file,
        "type": args.type,
        "descricao": parser.description
    }
def montaRespostaPadrao(args, parser):
     return  {
        "status": "recebido",
        "file": args.file,
        "type": args.type,
        "descricao": parser.description
    }

def montaParse(parser): 
    parser.add_argument("--file", required=True)
    parser.add_argument("--type", required=True, choices=montaChoices())
    parser.add_argument("--pedido-id", required=True)
    args = parser.parse_args();
    return args

def montaChoices():
    return [
        "txt", 
        "jpg",
        "jpe",
        "mp3",
        "mp4"
    ]

def verificaArquivo(args):
    return True

if __name__ == "__main__":
    main()
