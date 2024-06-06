import discord
from discord.ext import commands
from discord import app_commands
import Commandes.config as config
import typing
from datetime import date
def get(bot:commands.Bot):
    @bot.tree.command(name="aide", description="Affiche l'aide de la commande choisie")
    @app_commands.describe(commande="La commande choisie")
    @app_commands.choices(commande=[app_commands.Choice(name=command.name, value=command.name) for command in bot.tree.get_commands() if isinstance(command, discord.app_commands.Command)])
    async def aide(interaction: discord.Interaction, commande:app_commands.Choice[str] = None):
        if commande:
            embed = discord.Embed(title=f"Commande {commande.value}", color=discord.Color.blue())
            logo = config.imageLogo
            embed.set_thumbnail(url=f"{logo.path}")
            for command in bot.tree.get_commands():
                if command.name == commande.value:
                    embed.add_field(name=f"/{command.name}", value=command.description or "Pas de description", inline=False)
                    break
            await interaction.response.send_message(embed=embed)
        else:
            embed = discord.Embed(title="Commandes disponibles", color=discord.Color.blue())
            logo = config.imageLogo
            embed.set_thumbnail(url=f"{logo.path}")
            for command in bot.tree.get_commands():
                if isinstance(command, discord.app_commands.Command):
                    embed.add_field(name=f"/{command.name}", value=command.description or "Pas de description", inline=False)

            await interaction.response.send_message(embed=embed)