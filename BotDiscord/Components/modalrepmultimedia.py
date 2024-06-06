import discord
from discord.ui import TextInput
class Info():
    def __init__(self, idDefi:int, DB, userId:int, response:str, title:str, points:bool) -> None:
        self.userId = userId
        self.response = response
        self.title = title
        self.idDefi = idDefi
        self.DB = DB
        self.get_points = points
class TestMod(discord.ui.Modal):
    def __init__(self, info:Info) -> None:
        super().__init__(title=info.title)
        self.info:Info = info
        self.response = info.response.lower()
        print(self.response)
        self.add_item(TextInput(label="Entrez votre réponse", placeholder="La réponse est...", style=discord.TextStyle.short))

    async def on_submit(self, interaction: discord.Interaction):
        response = self.children[0].value
        if response.lower() == self.response:
            userId = self.info.DB.get_user_id_by_discord(str(interaction.user.id))
            if self.info.get_points:
                self.info.DB.user_realise_defi(userId, self.info.idDefi, True, True)
                embed = discord.Embed(title=f"Bonne Réponse", color=discord.Color.green())
            else:
                self.info.DB.user_realise_defi(userId, self.info.idDefi, True, False)
                embed = discord.Embed(title=f"Bonne Réponse", description="Non pris en compte car ce défi n'est pas d'aujourd'hui.", color=discord.Color.green())
        
        else:
            userId = self.info.DB.get_user_id_by_discord(str(interaction.user.id))
            if self.info.get_points:
                self.info.DB.user_realise_defi(userId, self.info.idDefi, False, True)
                embed = discord.Embed(title=f"Mauvaise Réponse", color=discord.Color.red())
            else:
                self.info.DB.user_realise_defi(userId, self.info.idDefi, False, False)
            
                embed = discord.Embed(title=f"Mauvaise Réponse", description="Non pris en compte car ce défi n'est pas d'aujourd'hui.", color=discord.Color.red())
        
        await interaction.response.send_message(embed=embed)

class MyView(discord.ui.View):
    def __init__(self, info:Info):
        super().__init__()  # Initialiser la classe de base
        self.info:Info = info

    @discord.ui.button(label="Votre Réponse", custom_id="open_modal", style=discord.ButtonStyle.primary)
    async def open_modal(self, interaction: discord.Interaction, button: discord.ui.Button):
        if self.info.userId == interaction.user.id:
            modal = TestMod(self.info)
            await interaction.response.send_modal(modal)

