import mysql.connector
from mysql.connector import Error
from Joueur.user import User
from datetime import *
from Defis.defis import *
class DB:
    def __init__(self, host:str, user:str, passwd:str, database:str) -> None:
        try:
            self._host = host
            self._user = user
            self._passwd = passwd
            self._database = database
            self.connection = mysql.connector.connect(
            host=host,
            user=user,
            passwd=passwd,
            database=database
        )
            self.cursor = self.connection.cursor()
        except Error as e:
            self.connection = None
            print("Erreur lors de la connexion à MySQL", e)
    
    def update(self):
        self.connection.close()
        self.connection = mysql.connector.connect(
            host=self._host,
            user=self._user,
            passwd=self._passwd,
            database=self._database
        )
        self.cursor = self.connection.cursor()

    def get_classement(self, idUser:int = None) -> (int,int):
        if idUser:
            users = self.get_all_user_names()
            classement = 1
            for user in users:
                if user.id == idUser:
                    return (classement, len(users))
                classement += 1
            return (-1,len(users))          
        
    def get_all_defi(self, typeDefi:str = None, idDiscord:str = None, toshow:bool = False):
        if typeDefi is not None and idDiscord is not None:
            if toshow:
                self.cursor.execute("""
                                    SELECT D.*
                                    FROM defi AS D
                                    WHERE D.TYPE_DEFI = %s
                                    AND D.DATE_DEFI <= CURRENT_DATE
                                    AND NOT EXISTS (
                                        SELECT 1
                                        FROM realise AS R
                                        JOIN user AS U ON U.ID_JOUEUR = R.ID_JOUEUR
                                        WHERE R.ID_DEFI = D.ID_DEFI
                                        AND U.ID_DISCORD = %s
                                    );""", (typeDefi, idDiscord))
            else:
                self.cursor.execute("""
                                    SELECT D.*
                                    FROM defi AS D
                                    WHERE D.TYPE_DEFI = %s
                                    AND NOT EXISTS (
                                        SELECT 1
                                        FROM realise AS R
                                        JOIN user AS U ON U.ID_JOUEUR = R.ID_JOUEUR
                                        WHERE R.ID_DEFI = D.ID_DEFI
                                        AND U.ID_DISCORD = %s
                                    );""", (typeDefi,idDiscord))
        elif typeDefi is not None:
            if toshow:
                self.cursor.execute("""
                                SELECT *
                                FROM defi
                                WHERE TYPE_DEFI = %s AND DATE_DEFI <= CURRENT_DATE;
    """, (typeDefi,))
            else:
                self.cursor.execute("""
                                SELECT *
                                FROM defi
                                WHERE TYPE_DEFI = %s;
    """, (typeDefi,))
        else:
            if toshow:
                self.cursor.execute("""
                                SELECT *
                                FROM defi
                                WHERE DATE_DEFI <= CURRENT_DATE
    """)
            else:
                self.cursor.execute("""
                                SELECT *
                                FROM defi
    """)
        return [
            Defi(defi)
            for defi in self.cursor.fetchall()
        ]
    
    def get_jour_defi(self, typeDefi:str = None, idDiscord:str = None, toshow:bool = False) -> list[date]:
        if typeDefi is not None and idDiscord is not None:
            if toshow:
                self.cursor.execute("""
                                    SELECT D.DATE_DEFI
                                    FROM defi AS D
                                    WHERE D.TYPE_DEFI = %s
                                    AND D.DATE_DEFI <= CURRENT_DATE
                                    AND NOT EXISTS (
                                        SELECT 1
                                        FROM realise AS R
                                        JOIN user AS U ON U.ID_JOUEUR = R.ID_JOUEUR
                                        WHERE R.ID_DEFI = D.ID_DEFI
                                        AND U.ID_DISCORD = %s
                                    );""", (typeDefi, idDiscord))
            else:
                self.cursor.execute("""
                                    SELECT D.DATE_DEFI
                                    FROM defi AS D
                                    WHERE D.TYPE_DEFI = %s
                                    AND NOT EXISTS (
                                        SELECT 1
                                        FROM realise AS R
                                        JOIN user AS U ON U.ID_JOUEUR = R.ID_JOUEUR
                                        WHERE R.ID_DEFI = D.ID_DEFI
                                        AND U.ID_DISCORD = %s
                                    );""", (typeDefi,idDiscord))
        elif typeDefi is not None:
            if toshow:
                self.cursor.execute("""
                                SELECT DATE_DEFI
                                FROM defi
                                WHERE TYPE_DEFI = %s AND DATE_DEFI <= CURRENT_DATE;
    """, (typeDefi,))
            else:
                self.cursor.execute("""
                                SELECT DATE_DEFI
                                FROM defi
                                WHERE TYPE_DEFI = %s;
    """, (typeDefi,))
        else:
            if toshow:
                self.cursor.execute("""
                                SELECT DATE_DEFI
                                FROM defi
                                WHERE DATE_DEFI <= CURRENT_DATE
    """)
            else:

                self.cursor.execute("""
                                SELECT DATE_DEFI
                                FROM defi
    """)
        return [
            date[0]
            for date in self.cursor.fetchall()
        ]

    def get_detail_de_defi(self, idDefi:int, typeDefi:str):
        res = None
        if typeDefi == "qcm":
            self.cursor.execute("""
                                SELECT Q1, Q2, Q3, Q4, Q5
                                FROM defi_qcm
                                WHERE ID_DEFI_QCM = %s;

""",(idDefi,))
            res = self.cursor.fetchone()
        elif typeDefi == "presentiel":
            self.cursor.execute("""
                                SELECT CODE_VALID, CODE_QR
                                FROM defi_presentiel
                                WHERE ID_DEFI_PRESENTIEL = %s;

""",(idDefi,))
            res = self.cursor.fetchone()
        elif typeDefi == "multimedia":
            self.cursor.execute("""
                                SELECT CODE_VALID_MULTIMEDIA, MULTIMEDIA
                                FROM defi_multimedia
                                WHERE ID_DEFI_MULTIMEDIA = %s;

""",(idDefi,))
            res = self.cursor.fetchone()
        elif typeDefi == "code":
            self.cursor.execute("""
                                SELECT REPONCE_CODE, VERIFIER
                                FROM defi_codes
                                WHERE ID_DEFI_CODE = %s;

""",(idDefi,))
            res = self.cursor.fetchone()
        return res
    
    def get_defi_by_date(self, date:date) -> Defi:
        self.cursor.execute("""
                            SELECT *
                            FROM defi
                            WHERE DATE_DEFI = %s
""", (date,))
        res = self.cursor.fetchone()
        if res:
            detail = self.get_detail_de_defi(res[0], res[3])
            res = list(res) + list(detail)
            if res[3] == "multimedia":
                return DefiMultimedia(res, res[6], res[7])
            elif res[3] == "presentiel":
                return DefiPresentiel(res, res[6], res[7])
            elif res[3] == "code":
                return DefiCode(res, res[6], res[7])
            elif res[3] == "qcm":
                listeQuest = [res[i+6] for i in range(5)]
                return DefiQcm(res, listeQuest)

    def is_discord_user_in_db(self, idDiscord:str)->bool:
        self.cursor.execute("SELECT * FROM USER WHERE ID_DISCORD = %s",(idDiscord,))
        return self.cursor.fetchone() is not None

    def get_user_by_id(self, idUser:int) -> User:
        self.cursor.execute("""SELECT * FROM `USER` WHERE ID_JOUEUR = %s;""", (idUser,))
        res = self.cursor.fetchone()
        if res:
            self.cursor.execute("""
                                SELECT S.*
                                FROM SUCCES as S
                                JOIN OBTIENT as O ON O.ID_SUCCES = S.ID_SUCCES
                                JOIN USER as U ON O.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (idUser,))
            liste_succes = self.cursor.fetchall()
            if not liste_succes:
                liste_succes = []
            self.cursor.execute("""
                                SELECT A.*
                                FROM ADMIN as A
                                JOIN REPORT as R ON R.ID_ADMIN = A.ID_ADMIN
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (idUser,))
            idAdmin = self.cursor.fetchone()
            self.cursor.execute("""
                                SELECT D.*, R.NB_REUSSITE, R.HEURE_DE_REALISATION
                                FROM DEFI as D
                                JOIN REALISE as R ON R.ID_DEFI = D.ID_DEFI
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (idUser,))
            défis = self.cursor.fetchall()
            for i in range(len(défis)):
                defi = défis[i]
                detail = self.get_detail_de_defi(defi[0], defi[3])
                if detail:
                    defi += detail
                défis[i] =defi
            return User(res, idAdmin, liste_succes)
        return None
    
    def get_user_by_last_first_name(self, nom:str, prenom:str) -> User:
        self.cursor.execute("""SELECT DISTINCT * FROM `USER` WHERE NOM_USER = %s AND PRENOM_USER = %s;""", (nom,prenom))
        res = self.cursor.fetchone()
        if res:
            self.cursor.execute("""
                                SELECT S.*
                                FROM SUCCES as S
                                JOIN OBTIENT as O ON O.ID_SUCCES = S.ID_SUCCES
                                JOIN USER as U ON O.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            liste_succes = self.cursor.fetchall()
            if not liste_succes:
                liste_succes = []
            self.cursor.execute("""
                                SELECT A.*
                                FROM ADMIN as A
                                JOIN REPORT as R ON R.ID_ADMIN = A.ID_ADMIN
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            admin = self.cursor.fetchone()
            self.cursor.execute("""
                                SELECT D.*, R.NB_REUSSITE, R.HEURE_DE_REALISATION
                                FROM DEFI as D
                                JOIN REALISE as R ON R.ID_DEFI = D.ID_DEFI
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            défis = self.cursor.fetchall()
            for i in range(len(défis)):
                defi = défis[i]
                detail = self.get_detail_de_defi(defi[0], defi[3])
                if detail:
                    defi += detail
                défis[i] =defi
            return User(res, admin, liste_succes, défis)
        return None
    
    def get_user_by_pseudo(self, pseudo:str) -> User:
        self.cursor.execute("""SELECT DISTINCT * FROM `USER` WHERE PSEUDO = %s;""", (pseudo,))
        res = self.cursor.fetchone()
        if res:
            self.cursor.execute("""
                                SELECT S.*
                                FROM SUCCES as S
                                JOIN OBTIENT as O ON O.ID_SUCCES = S.ID_SUCCES
                                JOIN USER as U ON O.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            liste_succes = self.cursor.fetchall()
            if not liste_succes:
                liste_succes = []
            self.cursor.execute("""
                                SELECT A.*
                                FROM ADMIN as A
                                JOIN REPORT as R ON R.ID_ADMIN = A.ID_ADMIN
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            idAdmin = self.cursor.fetchone()
            self.cursor.execute("""
                                SELECT D.*, R.NB_REUSSITE, R.HEURE_DE_REALISATION
                                FROM DEFI as D
                                JOIN REALISE as R ON R.ID_DEFI = D.ID_DEFI
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            défis = self.cursor.fetchall()
            for i in range(len(défis)):
                defi = défis[i]
                detail = self.get_detail_de_defi(defi[0], defi[3])
                if detail:
                    defi += detail
                défis[i] =defi
            return User(res, idAdmin, liste_succes)
        return None
    
    def get_user_id_by_discord(self, idDiscord:str) -> User:
        self.cursor.execute("""SELECT DISTINCT ID_JOUEUR FROM `USER` WHERE ID_DISCORD = %s;""", (idDiscord,))
        res = self.cursor.fetchone()
        if res:
            return res[0]
        return None

    def get_user_by_discord(self, idDiscord:str) -> User:
        self.cursor.execute("""SELECT DISTINCT * FROM `USER` WHERE ID_DISCORD = %s;""", (idDiscord,))
        res = self.cursor.fetchone()
        if res:
            self.cursor.execute("""
                                SELECT S.*
                                FROM SUCCES as S
                                JOIN OBTIENT as O ON O.ID_SUCCES = S.ID_SUCCES
                                JOIN USER as U ON O.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            liste_succes = self.cursor.fetchall()
            if not liste_succes:
                liste_succes = []
            self.cursor.execute("""
                                SELECT A.*
                                FROM ADMIN as A
                                JOIN REPORT as R ON R.ID_ADMIN = A.ID_ADMIN
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            idAdmin = self.cursor.fetchone()
            self.cursor.execute("""
                                SELECT D.*, R.NB_REUSSITE, R.HEURE_DE_REALISATION
                                FROM DEFI as D
                                JOIN REALISE as R ON R.ID_DEFI = D.ID_DEFI
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (res[0],))
            défis = self.cursor.fetchall()
            for i in range(len(défis)):
                defi = défis[i]
                detail = self.get_detail_de_defi(defi[0], defi[3])
                if detail:
                    defi += detail
                défis[i] =defi
            return User(res, idAdmin, liste_succes, défis)
        return None

    def get_last_defi(self) -> str:
        self.cursor.execute("""select date_defi from defi ORDER BY date_defi DESC LIMIT 1;""")
        print(self.cursor.fetchone())

    def get_all_user_names(self) -> list[User]:
        ""
        self.cursor.execute("""SELECT * FROM `USER` ORDER BY NB_POINTS DESC""")
        res = self.cursor.fetchall()
        if len(res) > 0:
            return [
                User(user, None, [], [])
                for user in res
            ]

    def get_all_users(self) -> list[User]:
        self.cursor.execute("""SELECT * FROM `USER` ORDER BY NB_POINTS ASC;""")
        res = self.cursor.fetchall()
        if len(res) > 0:
            listeRes = []
            for user in res:
                self.cursor.execute("""
                                    SELECT S.*
                                    FROM SUCCES as S
                                    JOIN OBTIENT as O ON O.ID_SUCCES = S.ID_SUCCES
                                    JOIN USER as U ON O.ID_JOUEUR = U.ID_JOUEUR
                                    WHERE U.ID_JOUEUR = %s;
    """, (user[0],))
                liste_succes = self.cursor.fetchall()
                if not liste_succes:
                    liste_succes = []
                self.cursor.execute("""
                                    SELECT A.*
                                    FROM ADMIN as A
                                    JOIN REPORT as R ON R.ID_ADMIN = A.ID_ADMIN
                                    JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                    WHERE U.ID_JOUEUR = %s;
    """, (user[0],))
                idAdmin = self.cursor.fetchone()
                self.cursor.execute("""
                                SELECT D.*, R.NB_REUSSITE, R.HEURE_DE_REALISATION
                                FROM DEFI as D
                                JOIN REALISE as R ON R.ID_DEFI = D.ID_DEFI
                                JOIN USER as U ON R.ID_JOUEUR = U.ID_JOUEUR
                                WHERE U.ID_JOUEUR = %s;
""", (user[0],))
            défis = self.cursor.fetchall()
            for defi in défis:
                detail = self.get_detail_de_defi(defi[0], defi[3])
                if detail:
                    defi += detail
                listeRes.append(User(user, idAdmin, liste_succes, défis))
            return listeRes
        return None
    
    def get_number_rea_defi(self, idDefi: int = None, idUser: int = None) -> int:
        if idDefi is not None and idUser is not None:
            self.cursor.execute("SELECT count(*) FROM realise WHERE ID_DEFI = %s AND ID_JOUEUR = %s;", (idDefi, idUser))
        elif idDefi is not None:
            self.cursor.execute("SELECT count(*) FROM realise WHERE ID_DEFI = %s;", (idDefi,))
        elif idUser is not None:
            self.cursor.execute("SELECT count(*) FROM realise WHERE ID_JOUEUR = %s;", (idUser,))
        else:
            self.cursor.execute("SELECT count(*) FROM realise")

        result = self.cursor.fetchone()  # Récupère le résultat de la requête
        if result:
            return result[0]  # Retourne le premier élément du résultat (count(*))
        else:
            return 0  # Retourne 0 si aucun résultat n'est trouvé


    def user_realise_defi(self, idUser:int, idDefi:int, finish:bool, auj:bool):
        if finish:
            daterea = datetime.now()
            second = daterea.second
            if second < 10:
                second = f"0{second}"
            datereaSTR = f'{daterea.strftime("%Y-%m-%d %H:%M")}:{second}'
            nombre_reussite_defi_user = self.get_number_rea_defi(idDefi, idUser)

            if auj:
                if nombre_reussite_defi_user == 0:
                    nombre_reussite_defi = self.get_number_rea_defi(idDefi)
                    if nombre_reussite_defi < 10:
                        points = 20
                    elif nombre_reussite_defi < 20:
                        points = 18
                    elif nombre_reussite_defi < 50:
                        points = 16
                    elif nombre_reussite_defi < 100:
                        points = 12
                    else:
                        points = 10
                    self.cursor.execute("UPDATE user SET NB_PARTIE = NB_PARTIE + 1, NB_POINTS = NB_POINTS + %s, COMBOS = COMBOS + 1 WHERE ID_JOUEUR = %s;",(points, idUser))
                    self.cursor.execute("INSERT INTO realise(ID_JOUEUR,ID_DEFI,NB_REUSSITE,HEURE_DE_REALISATION) VALUES(%s,%s,%s,%s)", (idUser, idDefi, 1, datereaSTR))
                    self.connection.commit()
                else:
                    self.cursor.execute("UPDATE user SET NB_PARTIE = NB_PARTIE + 1 WHERE ID_JOUEUR = %s;",(idUser,))
                    self.cursor.execute("UPDATE realise SET NB_REUSSITE = NB_REUSSITE + 1, HEURE_DE_REALISATION = %s WHERE ID_JOUEUR = %s AND ID_DEFI = %s;",(datereaSTR, idUser, idDefi))
                    self.connection.commit()
            else:
                if nombre_reussite_defi_user == 0:
                    self.cursor.execute("INSERT INTO realise(ID_JOUEUR,ID_DEFI,NB_REUSSITE,HEURE_DE_REALISATION) VALUES(%s,%s,%s,%s)", (idUser, idDefi, 1, datereaSTR))
                    self.connection.commit()
                else:
                    self.cursor.execute("UPDATE realise SET NB_REUSSITE = NB_REUSSITE + 1, HEURE_DE_REALISATION = %s WHERE ID_JOUEUR = %s AND ID_DEFI = %s;",(datereaSTR, idUser, idDefi))
                    self.connection.commit()
        elif auj:
            self.cursor.execute("UPDATE user SET NB_PARTIE = NB_PARTIE + 1, COMBOS = 0 WHERE ID_JOUEUR = %s;",(idUser,))
            self.connection.commit()