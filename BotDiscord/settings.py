import os
from dotenv import load_dotenv
load_dotenv()

DISCORD_API_SECRET = os.getenv("DISCORD_TOKEN")
HOST = os.getenv("HOST")
USER = os.getenv("USER")
PASSWD = os.getenv("PASSWD")
DATABASE = os.getenv("DATABASE")