PALAVRAS_DOCUMENTO = [
    "cpf",
    "rg",
    "registro geral",
    "data de nascimento",
    "nome",
    "filiação",
    "carteira de identidade"
]

import pytesseract
from PIL import Image
import re

from app.core.result import Result
import pytesseract

pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

class ImagePipeline:
    
    def processar(self, file_path):
        # 1. Extrair informação da imagem
        texto = self.extrair_texto(file_path)
        texto_normalizado = self.normalizar_texto(texto)
        analise = self.analisar_texto(texto_normalizado)
        # (OCR, metadata, heurística simples, etc)

        # 2. Decidir
        if analise:
            return Result(
                resultado="Detectado",
                origem="image_pipeline",
                tipo_dado="documento_pessoal",
                confianca=0.85,
                status ="ok",
                evidencias=["imagem_documento"]
            )

        return Result(
            resultado="Limpo",
            origem="image_pipeline",
            confianca=0.90
        )
        

    def extrair_texto(self, file_path: str) -> str:
        imagem = Image.open(file_path)
        texto = pytesseract.image_to_string(imagem, lang="por")
        return texto.strip()
    
    def normalizar_texto(self, texto: str) -> str:
        texto = texto.lower()
        texto = re.sub(r"\s+", " ", texto)
        texto = re.sub(r"[^a-z0-9áàâãéèêíïóôõöúç ]", "", texto)

        return texto.strip()
    def analisar_texto(self, texto):
        for palavra in PALAVRAS_DOCUMENTO:
            if palavra in texto:
                return True
        
    def decidir(self, analise):
        pass
