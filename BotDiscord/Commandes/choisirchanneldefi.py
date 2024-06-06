import discord
from discord.ext import commands
from discord import app_commands
import Commandes.config as config
import typing
import json

def get(bot:commands.Bot):
    @bot.tree.command(name="choisir-channel-defi-du-jour", description="Choisi ton channel pour afficher le défi du jour")
    async def choosechanndefi(interaction: discord.Interaction, channel:str):
        await interaction.response.defer()
        embed = discord.Embed()
        if channel:
            chemin_fichier = 'affichage_nouv_defi_chann.json'
            try:
                with open(chemin_fichier, 'r') as fichier:
                    data = json.load(fichier)
            except FileNotFoundError:
                data = {}

            data[str(interaction.guild_id)] = channel

            with open(chemin_fichier, 'w') as fichier:
                json.dump(data, fichier, indent=4)
            embed.title = f'{bot.get_channel(int(channel)).name} a été choisi pour afficher les défis.'
            embed.color = discord.Colour.green()
            await interaction.followup.send(embed=embed)

    @choosechanndefi.autocomplete("channel")
    async def items_autocompletion(
        interaction: discord.Interaction,
        current:str
    ) -> typing.List[app_commands.Choice[str]]:
        liste = bot.get_guild(interaction.guild_id).channels
        data = []
        i = 0
        while len(data) < 25 and i < len(liste):
            data.append(app_commands.Choice(name=f"{liste[i].name}", value=str(liste[i].id)))
            i+=1
        return data