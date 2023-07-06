// Bakcground molón
const fondo = document.getElementById("bg-img")
const fondo_color = document.getElementById("bg-color")
var aleatorio = Math.floor(Math.random() * 3) + 1
switch(aleatorio) {
    case 1:
        colorcito = "#ffdfc5";
        break;
    case 2:
        colorcito = "#B141B7";
        break;
    case 3:
        colorcito = "#c1b3d4";
        break;
}
fondo_color.style.backgroundColor = colorcito
fondo.style.backgroundImage = "url(../Vista/img/background/"+aleatorio+".png)"

// Iconito del ojo
const iconEye = document.querySelector(".icon-eye");
if(iconEye)
iconEye.addEventListener("click", function () {

    const icon = this.querySelector("i");

    if(this.nextElementSibling.type=== "password"){
        this.nextElementSibling.type = "text";
        icon.classList.remove("bi-eye-slash-fill");
        icon.classList.add("bi-eye-fill");
    }else{
        this.nextElementSibling.type = "password";
        icon.classList.remove("bi-eye-fill");
        icon.classList.add("bi-eye-slash-fill");
    }
})

// Comprobación de mayoría de edad del registro
function comprobar_mayor_edad(){
    var fecha = document.getElementById("fecha_nac").value;
    var today = new Date();
    var birthDate = new Date(fecha);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }    
    if(age < 18){
        // window.alert("Debes ser mayor de edad")
        agregarAviso({tipo: 'error', titulo: 'CUIDADO', desc: 'Necesitas ser mayor de edad para poder registrarte en esta aplicación.'})
        return false;
    }
    return true;

}
  

