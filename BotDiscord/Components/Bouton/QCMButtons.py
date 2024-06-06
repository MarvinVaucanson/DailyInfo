from discord.ui import View, Button
import discord
class Info():
    def __init__(self, idDefi:int, userId:int, DB, embed:discord.Embed, questions:list, reponses:list, points:bool) -> None:
        self.embed = embed
        self.userId = userId
        self.questions = questions
        self.reponses = reponses
        self.idDefi = idDefi
        self.DB = DB
        self.get_points = points

class Buttons(View):
    def __init__(self, info:Info):
        super().__init__()
        self.info = info
        self.current = 0
        self.reponses_juste = []
    @discord.ui.button(label="Vrai", style=discord.ButtonStyle.success)
    async def semainePreBtn(self, interaction:discord.Interaction, button:Button):
        if self.info.userId == interaction.user.id:
            if self.info.reponses[self.current] == "vrai":
                self.reponses_juste.append({
                    "estJuste": True,
                    "réponse" : "vrai"
                })
            else:
                self.reponses_juste.append({
                    "estJuste": False,
                    "réponse" : "vrai"
                })
            self.current += 1
            if self.current >= len(self.info.questions):
                self.info.embed.title = "Résultat"
                desc = ""
                for key in range(len(self.reponses_juste)) :
                    desc += f"{self.info.questions[key]}\n"
                    if self.reponses_juste[key]["estJuste"]:
                        desc += f"> :green_circle: La réponse est bien **{self.info.reponses[key]}**"
                    else:
                        desc += f"> :red_circle: La réponse est **{self.info.reponses[key]}**, vous avez répondu **{self.reponses_juste[key]['réponse']}**"
                    desc += "\n\n"
                self.info.embed.description = desc
                if ":red_circle:" not in desc:
                    userId = self.info.DB.get_user_id_by_discord(str(interaction.user.id))
                    if self.info.get_points:
                        self.info.DB.user_realise_defi(userId, self.info.idDefi, True, True)
                    else:
                        self.info.DB.user_realise_defi(userId, self.info.idDefi, True, False)
                        self.info.embed.description += "\n\n**Score pas pris en compte car ce défi n'est pas d'aujourd'hui**."
                elif self.info.get_points:
                    userId = self.info.DB.get_user_id_by_discord(str(interaction.user.id))
                    self.info.DB.user_realise_defi(userId, self.info.idDefi, False, True)
                else:
                    userId = self.info.DB.get_user_id_by_discord(str(interaction.user.id))
                    self.info.DB.user_realise_defi(userId, self.info.idDefi, False, False)
                    self.info.embed.description += "\n\n**Score pas pris en compte car ce défi n'est pas d'aujourd'hui**."
                    
                await interaction.response.edit_message(embed=self.info.embed,view=None)
            else:
                self.info.embed.title = f"Question {self.current+1}"
                self.info.embed.description = self.info.questions[self.current]
                await interaction.response.edit_message(embed=self.info.embed)
        else:
            await interaction.response.edit_message(embed=self.info.embed)

    @discord.ui.button(label="Faux", style=discord.ButtonStyle.red)
    async def semaineSuivBtn(self, interaction:discord.Interaction, button:Button):
        if self.info.userId == interaction.user.id:

            if self.info.reponses[self.current] == "faux":
                self.reponses_juste.append({
                    "estJuste": True,
                    "réponse" : "faux"
                })
            else:
                self.reponses_juste.append({
                    "estJuste": False,
                    "réponse" : "faux"
                })
            self.current += 1
            if self.current >= len(self.info.questions):
                self.info.embed.title = "Résultat"
                desc = ""
                for key in range(len(self.reponses_juste)) :
                    desc += f"{self.info.questions[key]}\n"
                    if self.reponses_juste[key]["estJuste"]:
                        desc += f"> :green_circle: La réponse est bien **{self.info.reponses[key]}**"
                    else:
                        desc += f"> :red_circle: La réponse est **{self.info.reponses[key]}**, vous avez répondu **{self.reponses_juste[key]['réponse']}**"
                    desc += "\n\n"
                self.info.embed.description = desc
                if ":red_circle:" not in desc:
                    userId = self.info.DB.get_user_id_by_discord(str(interaction.user.id))
                    if self.info.get_points:
                        self.info.DB.user_realise_defi(userId, self.info.idDefi, True, True)
                    else:
                        self.info.DB.user_realise_defi(userId, self.info.idDefi, True, False)
                        self.info.embed.description += "\n\n**Score pas pris en compte car ce défi n'est pas d'aujourd'hui**."

                elif self.info.get_points:
                    userId = self.info.DB.get_user_id_by_discord(str(interaction.user.id))
                    self.info.DB.user_realise_defi(userId, self.info.idDefi, False, True)
                else:
                    userId = self.info.DB.get_user_id_by_discord(str(interaction.user.id))
                    self.info.DB.user_realise_defi(userId, self.info.idDefi, False, False)
                    self.info.embed.description += "\n\n**Score pas pris en compte car ce défi n'est pas d'aujourd'hui**."
                    
                await interaction.response.edit_message(embed=self.info.embed, view=None)
            else:
                self.info.embed.title = f"Question {self.current+1}"
                self.info.embed.description = self.info.questions[self.current]
                await interaction.response.edit_message(embed=self.info.embed)
        else:
            await interaction.response.edit_message(embed=self.info.embed)
