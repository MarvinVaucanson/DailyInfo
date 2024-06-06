const menu = document.querySelector(".menu");
const menuItems = document.querySelectorAll(".menuItem");
const hamburger= document.querySelector(".hamburger");
const closeIcon= document.querySelector(".closeIcon");
const menuIcon = document.querySelector(".menuIcon");
function toggleMenu() {
    if (menu.classList.contains("showMenu")) {
        menu.classList.remove("showMenu");
        closeIcon.style.display = "none";
        menuIcon.style.display = "block";
    } else {
        menu.classList.add("showMenu");
        closeIcon.style.display = "block";
        menuIcon.style.display = "none";
    }
}
hamburger.addEventListener("click", toggleMenu);
menuItems.forEach(
    function(menuItem) {
        menuItem.addEventListener("click", toggleMenu);
    }
)


function loadFile (event) {
    var image = document.getElementById("output");
    image.src = URL.createObjectURL(event.target.files[0]);
  };
  

function updateMissionForm() {
    var selectedType = document.getElementById('typeMission').value;
    var missionForms = document.getElementsByClassName('missionForm');

    for (var i = 0; i < missionForms.length; i++) {
        missionForms[i].style.display = 'none';
    }

    document.getElementById(selectedType + 'Form').style.display = 'block';
};