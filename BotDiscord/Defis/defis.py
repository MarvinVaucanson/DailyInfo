class Defi:
    ""
    def __init__(self, info:tuple) -> None:
        self.id = info[0]
        self.nom = info[1]
        self.date = info[2]
        self.type = info[3]
        self.description = info[4]
        self.difficulte = info[5]

class DefiPresentiel(Defi):
    def __init__(self, info: tuple, code_valid:str, code_qr:str) -> None:
        super().__init__(info)
        self.code_valid = code_valid
        self.code_qr = code_qr

class DefiMultimedia(Defi):
    def __init__(self, info: tuple, code_valid_multimedia:str, multimedia:str) -> None:
        super().__init__(info)
        self.code_valid_multimedia = code_valid_multimedia
        self.multimedia = multimedia

class DefiQcm(Defi):
    def __init__(self, info: tuple, questions:list) -> None:
        super().__init__(info)
        self.questions = []
        self.reponses = []
        for question in questions:
            tmp = question.split(";")
            self.questions.append(tmp[0])
            self.reponses.append(tmp[1])

class DefiCode(Defi):
    def __init__(self, info: tuple, reponse:str, verifier:str) -> None:
        super().__init__(info)
        self.reponse = reponse
        self.verifier = verifier