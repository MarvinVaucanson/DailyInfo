
class Image:
    def __init__(self, name:str, path:str) -> None:
        self.path = path
        self.name = name

liste_images = []
liste_images.append(Image("Daily_Info_4.png", "https://www.aht.li/3808847/Daily_Info_4.png"))
liste_images.append(Image("Daily_Info_5.png", "https://www.aht.li/3808848/Daily_Info_5.png"))
liste_images.append(Image("Daily_Info_13.png", "https://www.aht.li/3812401/Daily_Info_13.png"))

def get_image_by_name(name:str) -> Image:
    for image in liste_images:
        if image.name.split(".")[0] == name:
            return image
    return None