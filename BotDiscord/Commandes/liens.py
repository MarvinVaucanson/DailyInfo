import discord
from discord.ext import commands
import Commandes.config as config
def get(bot:commands.Bot):
    @bot.tree.command(name="liens", description="Affiche les liens de Daily Info")
    async def liens(interaction: discord.Interaction):
        await interaction.response.defer()
        embed = discord.Embed(title="Les liens",color=discord.Colour.blue())
        logo = config.imageLogo
        embed.set_thumbnail(url=f"{logo.path}")
        embed.description = "> **Inviter Bot**\nhttps://discord.com/api/oauth2/authorize?client_id=1164841675278008331&permissions=8&scope=bot\n"
        embed.description += "> **GitLab**\nhttps://forge.univ-lyon1.fr/p2202150/daily-info\n"
        embed.description += "> **Trailer Vidéo**\nhttps://www.youtube.com/watch?v=dQw4w9WgXcQ\n"
        embed.description += "> **Site Web de l'IUT Lyon 1**\nhttps://iut.univ-lyon1.fr/"
        await interaction.followup.send(embed=embed)