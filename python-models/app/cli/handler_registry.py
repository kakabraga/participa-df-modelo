from app.inputs.image_handler import ImageHandler
from app.inputs.text_handler import TextHandler
from app.inputs.audio_handler import AudioHandler

HANDLERS = {
    "image": ImageHandler,
    "text": TextHandler,
    "audio": AudioHandler,
}


def get_handler(handler_type: str):
    handler_class = HANDLERS.get(handler_type)

    if not handler_class:
        raise ValueError(f"Handler não suportado: {handler_type}")

    return handler_class()
