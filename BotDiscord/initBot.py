import discord
from discord.ext import commands
from datetime import date
from apscheduler.schedulers.asyncio import AsyncIOScheduler
import Commandes.classement as classement
import Commandes.defis as defis
import Commandes.profile as profile
import Commandes.fairedeficode as fairedeficode
import Commandes.fairedefiqcm as fairedefiqcm
import Commandes.fairedefimultimedia as fairedefimultimedia
import Commandes.liens as liens
import Commandes.choisirchanneldefi as choisirchanneldefi
import Commandes.help as help
import Commandes.info as info
import Commandes.regle as regle
from DataBase.connexion import DB
from datetime import datetime
import Commandes.config as config

import json


class DailyInfoBot(commands.Bot):
    def __init__(self, DataBase:DB) -> None:
        super().__init__(command_prefix="!", intents=discord.Intents.all())
        self.commandes = None
        self.DB = DataBase
        
        
    async def my_job(self):
        date_aujourdhui = datetime.now()
        date_formatee = date_aujourdhui.strftime("%Y-%m-%d")
        tmp = date_formatee.split("-")
        vraidate = date(int(tmp[0]), int(tmp[1]), int(tmp[2]))
        defi = self.DB.get_defi_by_date(vraidate)
        if defi:
            embed = discord.Embed()
            logo = config.imageLogo
            date_translate = f"{config.translate_id_jour[vraidate.weekday()]} {vraidate.day} {config.translate_id_mois[vraidate.month]} {vraidate.year}"
            embed.title = f"Défi du jour ! {date_translate}\n{defi.nom}\n[{defi.type}]"
            embed.color = int(config.embedColor[1:], 16)
            embed.set_thumbnail(url=f"{logo.path}")
            embed.description = f'{defi.description}\nDifficulte: {defi.difficulte}'
            # await channel.send(content=f"")
            chemin_fichier = 'affichage_nouv_defi_chann.json'
            try:
                with open(chemin_fichier, 'r') as fichier:
                    data = json.load(fichier)
            except FileNotFoundError:
                data = {}

            for guild in data:
                try:
                    await self.get_guild(int(guild)).get_channel(int(data[guild])).send(content="@everyone", embed=embed)
                except Exception as e:
                    print(e)
        else:
            chemin_fichier = 'affichage_nouv_defi_chann.json'
            try:
                with open(chemin_fichier, 'r') as fichier:
                    data = json.load(fichier)
            except FileNotFoundError:
                data = {}

            for guild in data:
                try:
                    await self.get_guild(int(guild)).get_channel(int(data[guild])).send("Le défi de ce jour n'a pas été publié")
                except Exception as e:
                    print(e)
    async def setup_hook(self) -> None:
        try:
            choisirchanneldefi.get(self)
            liens.get(self)
            fairedefimultimedia.get(self)
            fairedefiqcm.get(self)
            fairedeficode.get(self)
            classement.get(self)
            defis.get(self)
            profile.get(self)
            info.get(self)
            regle.get(self)
            help.get(self)
            self.commandes  = await self.tree.sync()
        except Exception as e:
            print(e)

    async def on_ready(self) -> None:
        scheduler = AsyncIOScheduler()

        async def scheduled_job():
            await self.my_job()  # Attendre la coroutine my_job

        scheduler.add_job(scheduled_job, 'cron', hour=8, minute=0)  # Planifie la tâche pour 8h00
        scheduler.start()
        print('Le Bot ' + self.user.display_name + ' Est Prêt !')
        print(f"Synced {len(self.commandes)} command(s)")