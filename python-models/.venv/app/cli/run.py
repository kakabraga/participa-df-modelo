import argparse
import json
import os
import sys

from app.pipelines.image_pipeline import ImagePipeline

def main():
    parser = argparse.ArgumentParser(description="Processador de arquivos")  
    args   = montaParse(parser)
    pipeline = implementePipelinePorTipoArquivo(args.type)
    # result = pipeline.processar(file_path=args.file)
    result = pipeline.processar(file_path=args.file)

    print(json.dumps(result.to_dict()))

def montaRespostaPositiva(args, parser):
     return  {
        "status": "ok",
        "pedido_id": args.pedido_id,
        "file": args.file,
        "type": args.type,
        "descricao": parser.description
    }
def montaResultado(args, parser):
     return 

def montaParse(parser): 
    parser.add_argument("--file", required=True)
    parser.add_argument("--type", required=True, choices=montaChoices())
    parser.add_argument("--pedido-id", required=True)
    args = parser.parse_args()
    return args

def montaChoices():
    return [
        "txt", 
        "jpg",
        "jpeg",
        "mp3",
        "mp4"
    ]

def implementePipelinePorTipoArquivo():
    if args in montaChoices():
       return ImagePipeline()
   
    return print(json.dumps({
            "status": "error",
            "message": "Pipeline não implementado"
        }))

if __name__ == "__main__":
    main()
