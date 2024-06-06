import discord
from discord.ext import commands
from discord import app_commands
import Commandes.config as config
import typing
from datetime import date, datetime
import Components.modalrepmultimedia as modalrepmultimedia

def get(bot:commands.Bot):
    @bot.tree.command(name="verifier-defi-multimedia", description="Vous pouvez faire un défi multimédia que vous n'avez pas encore fait.")
    @app_commands.describe(jour="Le jour du défis")
    async def defimultimedia(interaction: discord.Interaction, jour:str):
        await interaction.response.defer()
        bot.DB.update()
        if bot.DB.is_discord_user_in_db(str(interaction.user.id)):
            logo = config.imageLogo
            if not jour == "Aucun jour":
                tmp = jour.split("/")
                vraidate = date(int(tmp[2]), int(tmp[1]), int(tmp[0]))
                defi = bot.DB.get_defi_by_date(vraidate)

                date_aujourdhui = datetime.now()
                date_formatee = date_aujourdhui.strftime("%Y-%m-%d")
                tmp = date_formatee.split("-")
                date_aujourdhui = date(int(tmp[0]), int(tmp[1]), int(tmp[2]))
                if date_aujourdhui == vraidate:
                    points = True
                else:
                    points = False
                embed = discord.Embed(
                    title=defi.nom
                )
                if "http" in defi.multimedia and ("jpg" in defi.multimedia or "png" in defi.multimedia or "photo" in defi.multimedia):
                    embed.description = defi.description
                    embed.set_image(url=defi.multimedia)
                else:
                    embed.description = defi.description+"\n\n"+defi.multimedia
                embed.set_thumbnail(url=f"{logo.path}")
                await interaction.followup.send(embed=embed, view=modalrepmultimedia.MyView(modalrepmultimedia.Info(defi.id, bot.DB, interaction.user.id, defi.code_valid_multimedia, defi.nom, points)))
            else:
                await interaction.followup.send(content="Vous avez fini tous les défis multimédia jusqu'à ce jour")
        else:
            await interaction.followup.send(content="Vous devez lier votre compte discord à votre compte Daily Info")

    @defimultimedia.autocomplete("jour")
    async def items_autocompletion(
        interaction: discord.Interaction,
        current:str
    ) -> typing.List[app_commands.Choice[str]]:
        defis = bot.DB.get_all_defi("multimedia", str(interaction.user.id), True)
        if len(defis) == 0:
            return [app_commands.Choice(name="Aucun jour", value="Aucun jour")]
        data = []
        i = 0
        while len(data) < 25 and i < len(defis):
            if current.lower() in f"{defis[i].nom} - {defis[i].date.strftime('%d/%m/%Y')}".lower():
                data.append(app_commands.Choice(name=f"{defis[i].nom} - {defis[i].date.strftime('%d/%m/%Y')}", value=defis[i].date.strftime("%d/%m/%Y")))
            i+=1
        return data
    