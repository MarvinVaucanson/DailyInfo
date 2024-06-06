import discord
from discord.ext import commands
from discord import app_commands
import Commandes.config as config
import typing
from urllib.parse import quote


def get(bot:commands.Bot):
    @bot.tree.command(name="profile", description="Affiche le profil d'un utilisateur")
    async def profile(interaction: discord.Interaction, joueur:str):
        await interaction.response.defer()
        try:

            bot.DB.update()
            embed = discord.Embed()
            if joueur:
                joueur = joueur.split(" ")
                nom = joueur[1]
                prenom = joueur[0]
                infoJoueur = bot.DB.get_user_by_last_first_name(nom, prenom)
                logo = config.imageLogo
                embed.set_footer(icon_url=logo.path, text="Daily Info")
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
                        embed.set_thumbnail(url=member.avatar.url)
                
                hasPersoImg = False
                if infoJoueur.photo and isinstance(infoJoueur.photo, str):
                    hasPersoImg = True
                    file = discord.File(f"../home/vue/{infoJoueur.photo}", filename="zizi.png")
                    embed.set_thumbnail(url=f"attachment://zizi.png")
                elif not trouve:
                    embed.set_thumbnail(url="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Unknown_person.jpg/694px-Unknown_person.jpg")
                embed.add_field(name="Stats", value=f"{infoJoueur.points} points\n{infoJoueur.nbrPartie} parties\n{infoJoueur.combos} combos")

                msg_succes = ""
                i = 0
                while i < 5 and i < len(infoJoueur.listeSucces):
                    msg_succes += f"{infoJoueur.listeSucces[i].nom}\n"
                    i+=1
                if msg_succes == "":
                    embed.add_field(name="Succès",value="Aucun succès")
                else:
                    embed.add_field(name="Succès (5 plus récent)",value=msg_succes)

                msg_defis = ""
                i = 0
                for value in infoJoueur.listeDefis.values():
                    if i >= 5:
                        break
                    msg_defis += f"{value['reussites']}x ({value['données'].nom})\n"
                    i+=1
                if msg_defis == "":
                    embed.add_field(name="Défis Réalisés", value="Aucun Défi",inline=False)
                else:
                    embed.add_field(name="Défis Réalisés", value=msg_defis,inline=False)
                if infoJoueur.admin:
                    embed.color = int(config.adminColor[1:], 16)
                    embed.title = f"[Admin]\n{infoJoueur.prenom} {infoJoueur.nom}"
                else:
                    embed.color = int(config.userColor[1:], 16)
                    embed.title = f"[Utilisateur]\n{infoJoueur.prenom} {infoJoueur.nom}"
                # embed.add_field(name="Défis réussis Total : 10000 Points",value=">>> QCM **253** Points\nRéponse Ouverte **40** Points\nCodes **5** Points\nMultimédia **0** Points\nPrésentiel **600** Points")
                if infoJoueur.color != "":
                    embed.color = int(infoJoueur.color[1:], 16)
                if hasPersoImg:
                    await interaction.followup.send(embed=embed, file=file)
                else:
                    await interaction.followup.send(embed=embed)
        except Exception as e:
            print(e)
            embed.title = "Aucun utilisateur trouvé"
            embed.color = discord.Color.red()
            await interaction.followup.send(embed=embed)
    @profile.autocomplete("joueur")
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