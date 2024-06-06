import discord
from discord.ext import commands
from discord import app_commands
import Commandes.config as config
import typing
from datetime import date
def get(bot:commands.Bot):
    @bot.tree.command(name="défis", description="Affiche la description d'un défi choisi")
    async def defi(interaction: discord.Interaction, jour:str):
        await interaction.response.defer()
        bot.DB.update()
        embed = discord.Embed()
        if jour:
            tmp = jour.split("/")
            vraidate = date(int(tmp[2]), int(tmp[1]), int(tmp[0]))
            defi = bot.DB.get_defi_by_date(vraidate)
            logo = config.imageLogo
            date_translate = f"{config.translate_id_jour[vraidate.weekday()]} {vraidate.day} {config.translate_id_mois[vraidate.month]} {vraidate.year}"
            embed.title = f"{defi.nom}"
            embed.color = int(config.embedColor[1:], 16)
            embed.set_thumbnail(url=f"{logo.path}")
            embed.add_field(name="Date", value=date_translate, inline=False)
            embed.add_field(name="Type Défi", value=defi.type)
            embed.add_field(name="Difficulté", value=f"{defi.difficulte}/3")
            embed.add_field(name="Question", value=defi.description, inline=False)
            await interaction.followup.send(embed=embed)

    @defi.autocomplete("jour")
    async def items_autocompletion(
        interaction: discord.Interaction,
        current:str
    ) -> typing.List[app_commands.Choice[str]]:
        defis = bot.DB.get_all_defi(toshow=True)
        data = []
        i = 0
        while len(data) < 25 and i < len(defis):
            print()
            if current.lower() in f"{defis[i].nom} - {defis[i].date.strftime('%d/%m/%Y')}".lower():
                data.append(app_commands.Choice(name=f"{defis[i].nom} - {defis[i].date.strftime('%d/%m/%Y')}", value=defis[i].date.strftime("%d/%m/%Y")))
            i+=1
        return data