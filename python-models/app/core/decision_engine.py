class DecisionEngine:

    def decidir(self, analise: dict) -> dict:
        if analise["score"] >= 0.5:
            return {
                "detectado": True,
                "tipo_dado": analise["tipo_dado"] or "documento_pessoal",
                "confianca": analise["score"],
                "evidencias": analise["evidencias"]
            }

        return {
            "detectado": False,
            "confianca": analise["score"],
            "evidencias": analise["evidencias"]
        }
