from abc import ABC, abstractmethod
from app.core.result import Result

class Ihandler(ABC):

    @abstractmethod
    def handle(self, input_data) -> Result:
        pass
