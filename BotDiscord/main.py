import settings
from initBot import DailyInfoBot
from DataBase.connexion import DB

bot = DailyInfoBot(DB(settings.HOST, "root", settings.PASSWD, settings.DATABASE))
if __name__ == "__main__":
    bot.run(settings.DISCORD_API_SECRET)