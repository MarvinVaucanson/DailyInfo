import discord
from discord.ui import Select, View, Button

class BtnSuivPre(View):
    def __init__(self, bot, embed: discord.Embed, nb_page, interaUser, liste_joueur, classementAc):
        super().__init__()
        self.bot = bot
        self.page = 1
        self.embed = embed
        self.nb_page = nb_page
        self.interaUser = interaUser
        self.liste_joueur = liste_joueur
        self.classementAc = classementAc
        print(self.nb_page)
        if (self.nb_page == 1):
            self.children[1].disabled = True

    @discord.ui.button(label="⏮️", style=discord.ButtonStyle.primary, disabled=True)
    async def beforepage(self, interaction: discord.Interaction, button: Button,):
        if (interaction.user.id == self.interaUser):
            if (self.page != 1):
                self.page -= 1
                long = 20 + (20 * (self.page - 1))
                if (self.page == 1):
                    mini = 0
                else:
                    mini = 20 * (self.page - 1)
                i = mini
                top = (mini, min(long, len(self.liste_joueur)))
                self.embed.description = f"Top {top[0]} à {top[1]}\n>>> "
                while i < long and i < len(self.liste_joueur):
                    self.embed.description += f"{i+1}. {self.liste_joueur[i].prenom} {self.liste_joueur[i].nom}"
                    if self.liste_joueur[i].pseudo:
                        self.embed.description += f" (**{self.liste_joueur[i].pseudo}**)"
                    self.embed.description += f" {self.liste_joueur[i].points} pts\n"
                    i += 1
                self.embed.set_footer(text=f"{self.page}/{self.nb_page}")
                button_after = self.children[1]
                button_after.disabled = False
                if (self.page == 1):
                    button.disabled = True
                await interaction.response.edit_message(embed=self.embed, view=self)
        else:
            await interaction.response.edit_message(embed=self.embed, view=self)


    @discord.ui.button(label="⏭️", style=discord.ButtonStyle.primary)
    async def nextpage(self, interaction: discord.Interaction, button: Button):
        if (interaction.user.id == self.interaUser):
            if (self.page != self.nb_page):
                self.page += 1
                if (len(self.liste_joueur) < 10 * self.page):
                    long = len(self.liste_joueur) + (10 * (self.page - 1))
                else:
                    long = 20 + (20 * (self.page - 1))
                mini = 20 * (self.page - 1)
                i = mini
                top = (mini, min(long, len(self.liste_joueur)))
                self.embed.description = f"Top {top[0]} à {top[1]}\n>>> "
                while i < long and i < len(self.liste_joueur):
                    self.embed.description += f"{i+1}. {self.liste_joueur[i].prenom} {self.liste_joueur[i].nom}"
                    if self.liste_joueur[i].pseudo:
                        self.embed.description += f" (**{self.liste_joueur[i].pseudo}**)"
                    self.embed.description += f" {self.liste_joueur[i].points} pts\n"
                    i += 1
                self.embed.set_footer(text=f"{self.page}/{self.nb_page}")
                button_before = self.children[0]
                button_before.disabled = False
                if (self.page == self.nb_page):
                    button.disabled = True
                await interaction.response.edit_message(embed=self.embed, view=self)
        else:
            await interaction.response.edit_message(embed=self.embed, view=self)