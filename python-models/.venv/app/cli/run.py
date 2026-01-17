import argparse
import json
import os
import sys

from app.pipelines.image_pipeline import ImagePipeline

def main():
    parser = argparse.ArgumentParser(description="Processador de arquivos")  
    args   = montaParse(parser)
    pipeline = implementaPipelinePorTipoArquivo(args.type)
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
        "jpg",
        "jpeg",
        "mp3",
        "mp4"
    ]
    
IMAGE_TYPES = ["jpg", "jpeg"]
AUDIO_TYPES = ["mp3"]
VIDEO_TYPES = ["mp4"]

def implementaPipelinePorTipoArquivo(tipo):
    if tipo in IMAGE_TYPES:
        return implementaPipelinePorImagem(tipo)

    if tipo in AUDIO_TYPES:
        return implementaPipelinePorAudio(tipo)

    if tipo in VIDEO_TYPES:
        return implementaPipelinePorVideo(tipo)

    return montaMensagemDeErrorPipeline(tipo)


def montaMensagemDeErrorPipeline(tipo):
    return {
        "status": "error",
        "message": "Pipeline não implementado",
        "tipo_arquivo": tipo
    }
    
def implementePipelinePorImagem():
    return ImagePipeline(type)
if __name__ == "__main__":
    main()
