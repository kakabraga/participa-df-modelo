class AnaliseBuilder:

    def build(self, evidencias: list[dict]) -> dict:
        score = min(sum(e["score"] for e in evidencias), 1.0)

        tipo_dado = next(
            (e["valor"] for e in evidencias if e["tipo"] == "regex"),
            None
        )

        return {
            "evidencias": evidencias,
            "score": score,
            "tipo_dado": tipo_dado
        }
