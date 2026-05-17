function deleteCar(id){
    if(!confirm("Delete this car?")) return;

    fetch("/admin/cars/delete?id=" + id)
    .then(res => res.json())
    .then(data => {
        if(data.status === "success"){
            alert("Deleted");
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function deleteMember(id){
    if(!confirm("Delete this member?")) return;

    fetch("/admin/members/delete?id=" + id)
    .then(res => res.json())
    .then(() => location.reload());
}