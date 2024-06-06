import discord
from discord.ext import commands
import Commandes.config as config
def get(bot:commands.Bot):
    @bot.tree.command(name="regles", description="Affiche les règles du jeu")
    async def regle(interaction: discord.Interaction):
        embed = discord.Embed(title=f"Règles du jeu", color=discord.Color.blue())
        logo = config.imageLogo
        embed.set_thumbnail(url=f"{logo.path}")
        embed.description ='"*Ces règles sont obligatoires*" - Tutel'
        embed.add_field(name="Édition du Profil", value="Personnalisez votre profil à votre guise. Ajoutez une photo, éditez votre pseudo, et suivez votre progression à travers le nombre de parties jouées et vos combos réalisés. Attention pas d'image ou de pseudo offensant.",inline=False)
        await interaction.response.send_message(embed=embed)