class Succes:
    ""
    def __init__(self, id:int, descnom:str) -> None:
        self.id = id
        tmp = descnom.split(";")
        self.nom = tmp[0]
        self.description = tmp[1]