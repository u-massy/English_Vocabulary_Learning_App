function change(number){
    let id = 'change_' + number;
    var change = document.getElementById(id);

    if(change.style.color == "white"){
        change.style.color = "black";
    }else{
        change.style.color = "white";
    }
}

function red_change(number){
    let id = 'red_change_' + number;
    var change = document.getElementById(id);

    if(change.style.color === "white"){
        change.style.color = "red";
        change.style.textDecorationColor = "red"
        //change.style.text-decoration-color = "red";
    }else{
        change.style.color = "white";
        change.style.textDecorationColor = "white"
        //change.style.text-decoration-color = "white";
    }

}
