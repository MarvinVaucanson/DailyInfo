from Joueur.admin import Admin
from Joueur.succes import Succes
from Defis.defis import *
class User:
    ""
    def __init__(self, info:tuple, infoAdmin:tuple, listeSucces:list, listeDefis:list) -> None:
        self.id = info[0]
        self.idDiscord = info[1]
        self.nom = info[2]
        self.prenom = info[3]
        self.email = info[4]
        self.pseudo = info[5]
        self.photo = info[7]
        self.color = info[8]
        self.nbrPartie = info[9]
        self.combos = info[10]
        self.points = info[11]
        if infoAdmin:
            self.admin = Admin(
                infoAdmin[0],
                infoAdmin[1],
                infoAdmin[2]
            )
        else:
            self.admin = None
        self.listeSucces = [Succes(
            succes[0],
            succes[1]
        ) for succes in listeSucces]
        self.listeDefis = {}
        for defi in listeDefis:
            if defi[3] == "multimedia":
                self.listeDefis[defi[0]] = {
                    "données" : DefiMultimedia(defi, defi[8], defi[9]),
                    "reussites" : defi[6],
                    "date_rea" : defi[7]
                }
            elif defi[3] == "presentiel":
                self.listeDefis[defi[0]] = {
                    "données" : DefiPresentiel(defi, defi[8], defi[9]),
                    "reussites" : defi[6],
                    "date_rea" : defi[7]
                }
            elif defi[3] == "code":
                self.listeDefis[defi[0]] = {
                    "données" : DefiCode(defi, defi[8], defi[9]),
                    "reussites" : defi[6],
                    "date_rea" : defi[7]
                }
            elif defi[3] == "qcm":
                ""
                listeQuest = [defi[i+8] for i in range(5)]
                self.listeDefis[defi[0]] = {
                    "données" : DefiQcm(defi, listeQuest),
                    "reussites" : defi[6],
                    "date_rea" : defi[7]
                }
        
    def get_succes_by_id(self, idSucces:int):
        for i in range(len(self.listeSucces)):
            if self.listeSucces[i] == idSucces:
                return 
        return None
