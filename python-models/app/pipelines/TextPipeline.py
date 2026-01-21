class TextPipeline:

    def process(self, texto: str) -> Result:
        texto = self.normalizar(texto)
        evidencias = self.analisar(texto)
        decisao = self.decidir(evidencias)

        return Result.from_decisao(decisao)

    def normalizar(self, texto: str) -> str:
        return texto.lower().strip()

    def analisar(self, texto: str):
        # regex + spaCy + heurística
        ...

    def decidir(self, evidencias):
        ...
