function validateCarForm(){
    let price = document.getElementById("price").value;
    let image = document.getElementById("image").files[0];

    if(price <= 0){
        alert("Price must be greater than 0");
        return false;
    }

    if(image){
        let allowed = ["image/jpeg","image/png"];
        if(!allowed.includes(image.type)){
            alert("Only JPG/PNG allowed");
            return false;
        }

        if(image.size > 2 * 1024 * 1024){
            alert("Max 2MB allowed");
            return false;
        }
    }

    return true;
}