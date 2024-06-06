import discord
from discord.ext import commands
from discord import app_commands
from Components.Bouton.PageSuivPreClassement import BtnSuivPre
import Commandes.config as config
import typing
def get(bot:commands.Bot):
    @bot.tree.command(name="classement", description="Affiche le classement général ou celui d'un joueur choisi")
    async def classement(interaction: discord.Interaction, joueur:str = None):
        await interaction.response.defer()
        bot.DB.update()
        embed = discord.Embed()
        embed.color = int(config.embedColor[1:], 16)
        if joueur:
            joueur = joueur.split(" ")
            if len(joueur) == 2:

                nom = joueur[1]
                prenom = joueur[0]
                infoJoueur = bot.DB.get_user_by_last_first_name(nom, prenom)
                print(infoJoueur)
                if infoJoueur:
                    classement = bot.DB.get_classement(infoJoueur.id)
                    logo = config.imageLogo
                    embed.set_footer(icon_url=logo.path, text="Daily Info")
                    embed.title = f"Classement de {prenom} {nom}"
                    
                    if infoJoueur.pseudo:
                        embed.description = f"{infoJoueur.pseudo}"
                    else:
                        embed.description = ""
                    trouve = False
                    if infoJoueur.idDiscord:
                        for member in bot.get_all_members():
                            if not member.bot and member.id == int(infoJoueur.idDiscord):
                                trouve = True
                                break
                        if trouve:
                            embed.description += f" {member.mention}"
                            url = member.avatar.url
                    embed.description += f"\nClassement : {classement[0]}/{classement[1]}"
                    hasPersoImg = False
                    if infoJoueur.photo and isinstance(infoJoueur.photo, str):
                        hasPersoImg = True
                        imgName = infoJoueur.photo.split("/")[1]
                        file = discord.File(f"../home/vue/{infoJoueur.photo}", filename=imgName)
                        embed.set_thumbnail(url=f"attachment://{imgName}")
                    elif not trouve:
                        embed.set_thumbnail(url="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Unknown_person.jpg/694px-Unknown_person.jpg")
                    else:
                        embed.set_thumbnail(url=url)
                    embed.add_field(name="Stats", value=f"{infoJoueur.points} points\n{infoJoueur.nbrPartie} parties\n{infoJoueur.combos} combos")
                    if infoJoueur.color != "":
                        embed.color = int(infoJoueur.color[1:], 16)
                    if hasPersoImg:
                        await interaction.followup.send(embed=embed, file=file)
                    else:
                        await interaction.followup.send(embed=embed)
                else:
                    embed.color = discord.Colour.red()
                    embed.title = "Aucun utilisateur trouvé"
                    await interaction.followup.send(embed=embed)
            else:
                embed.color = discord.Colour.red()
                embed.title = "Aucun utilisateur trouvé"
                await interaction.followup.send(embed=embed)
        else:
            logo = config.imageLogo
            embed.title = f"Classement de défis réussis"
            users = bot.DB.get_all_user_names()
            classement = 0
            if len(users) < 20:
                embed.description = f"Top 1 à {len(users)}\n>>> "
            else:
                embed.description = "Top 1 à 20\n>>> "
            while classement < 20 and classement < len(users):
                embed.description += f"{classement+1}. {users[classement].prenom} {users[classement].nom}"
                if users[classement].pseudo:
                    embed.description += f" (**{users[classement].pseudo}**)"
                
                embed.description += f" {users[classement].points} pts\n"
                classement += 1
            embed.set_thumbnail(url=f"{logo.path}")
            await interaction.followup.send(embed=embed, view=BtnSuivPre(bot, embed, (len(users)//20)+1, interaction.user.id, users, classement))


    @classement.autocomplete("joueur")
    async def items_autocompletion(
        interaction: discord.Interaction,
        current:str
    ) -> typing.List[app_commands.Choice[str]]:
        liste_tmp = bot.DB.get_all_user_names()
        liste_joueur = [f"{user.prenom} {user.nom}" for user in liste_tmp]
        data = []
        i = 0
        while len(data) < 25 and i < len(liste_joueur):
            if current.lower() in liste_joueur[i].lower():
                data.append(app_commands.Choice(name=liste_joueur[i], value=liste_joueur[i]))
            i+=1
        return data