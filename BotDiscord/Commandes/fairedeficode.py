import discord
from discord.ext import commands
from discord import app_commands
import Commandes.config as config
import typing
from datetime import date, datetime
import sys
import io
import os

def run_python_code(expression):
    old_stdout = sys.stdout
    new_stdout = io.StringIO()
    sys.stdout = new_stdout
    try:
        exec(expression)
        sys.stdout.seek(0)
        return new_stdout.read().strip()
    except Exception as e:
        return str(e)
    finally:
        sys.stdout = old_stdout

def get(bot:commands.Bot):
    @bot.tree.command(name="verifier-defi-code", description="Vous pouvez faire un défi de code que vous n'avez pas encore fait.")
    @app_commands.describe(jour="Le jour du défi")
    @app_commands.describe(fichier="Fichier python qui vérifie le code demandé")
    async def deficode(interaction: discord.Interaction, jour:str, fichier: discord.Attachment):
        await interaction.response.defer()
        bot.DB.update()
        if bot.DB.is_discord_user_in_db(str(interaction.user.id)):
            if not jour == "Aucun jour":
                if fichier.filename.endswith('.py'):
                    
                    # Sauvegardez le fichier localement

                    file_path = f"fichiers_telecharge/"
                    if not os.path.exists(file_path):
                        os.makedirs(file_path)
                        
                    await fichier.save(f"{file_path}{fichier.filename}")

                    # Ouvrez le fichier et lisez son contenu
                    with open(file_path+fichier.filename, 'r', encoding='utf-8') as py_file:
                        content = py_file.read()
                    tmp = jour.split("/")
                    vraidate = date(int(tmp[2]), int(tmp[1]), int(tmp[0]))
                    date_aujourdhui = datetime.now()
                    date_formatee = date_aujourdhui.strftime("%Y-%m-%d")
                    tmp = date_formatee.split("-")
                    date_aujourdhui = date(int(tmp[0]), int(tmp[1]), int(tmp[2]))
                    if date_aujourdhui == vraidate:
                        points = True
                    else:
                        points = False

                    defi = bot.DB.get_defi_by_date(vraidate)
                    content += f"\n\n\n{defi.verifier}"
                    logo = config.imageLogo
                    embed = discord.Embed()
                    embed.title = "Résultat"
                    embed.color = int(config.embedColor[1:], 16)
                    embed.set_thumbnail(url=f"{logo.path}")
                    output = run_python_code(content)
                    userId = bot.DB.get_user_id_by_discord(str(interaction.user.id))

                    if output is not None:
                        if "print" in output:
                            embed.description = f"Vous n'avez pas le droit d'utiliser print()"
                            if points:
                                bot.DB.user_realise_defi(userId, defi.id, False, True)
                            else:
                                bot.DB.user_realise_defi(userId, defi.id, False, False)
                                embed.description += "\n**Score pas pris en compte car ce défi n'est pas d'aujourd'hui**"

                        elif output == "":
                            embed.description = f"Votre code a passé tous les tests !"

                            if points:
                                bot.DB.user_realise_defi(userId, defi.id, True, True)
                            else:
                                bot.DB.user_realise_defi(userId, defi.id, True, False)
                                embed.description += "\n**Score pas pris en compte car ce défi n'est pas d'aujourd'hui**"
                        else:
                            embed.description = f"```py\n{output}```"
                            if points:
                                bot.DB.user_realise_defi(userId, defi.id, False, True)
                            else:
                                bot.DB.user_realise_defi(userId, defi.id, False, False)
                                embed.description += "\n**Score pas pris en compte car ce défi n'est pas d'aujourd'hui**"
                    else:
                        embed.description = f"erreur"
                    await interaction.followup.send(embed=embed)
                else:
                    # Si le fichier n'est pas un .txt, utilisez également followup.send pour informer l'utilisateur
                    await interaction.followup.send("Veuillez télécharger un fichier .py.")
            else:
                await interaction.followup.send(content="Vous avez fini tous les défis de code jusqu'à ce jour.")
        else:
            await interaction.followup.send(content="Vous devez lier votre compte discord à votre compte Daily Info")
        return
    @deficode.autocomplete("jour")
    async def items_autocompletion(
        interaction: discord.Interaction,
        current:str
    ) -> typing.List[app_commands.Choice[str]]:
        defis = bot.DB.get_all_defi("code", str(interaction.user.id), True)
        if len(defis) == 0:
            return [app_commands.Choice(name="Aucun jour", value="Aucun jour")]
        data = []
        i = 0
        while len(data) < 25 and i < len(defis):
            if current.lower() in f"{defis[i].nom} - {defis[i].date.strftime('%d/%m/%Y')}".lower():
                data.append(app_commands.Choice(name=f"{defis[i].nom} - {defis[i].date.strftime('%d/%m/%Y')}", value=defis[i].date.strftime("%d/%m/%Y")))
            i+=1
        return data