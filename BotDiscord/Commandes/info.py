import discord
from discord.ext import commands
import Commandes.config as config
def get(bot:commands.Bot):
    @bot.tree.command(name="infos", description="Affiche les informations supplémentaire du jeu.")
    async def infos(interaction: discord.Interaction):
        embed = discord.Embed(title=f"En savoir plus", color=discord.Color.blue())
        logo = config.imageLogo
        embed.set_thumbnail(url=f"{logo.path}")
        embed.add_field(name="Concept du jeu", value="Bienvenue dans le jeu quotidien! Chaque jour, vous recevrez une mission unique à accomplir sur le site. Remplissez la mission pour gagner des points et grimper dans le classement du tableau des scores.",inline=False)
        embed.add_field(name="Créateurs",value="Baptiste Rousselot\nArnaud Jin\nEmmanuel Ardoin\nClément Carvalho",inline=False)
        await interaction.response.send_message(embed=embed)