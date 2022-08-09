function del_user(id) {
    let confirmAct = confirm("Are you sure to delete this user?");

    if (confirmAct){
        let request = new XMLHttpRequest();
        request.open("GET", "user_admin_delete.php?id="+id);
        request.send();
        location.reload();
    }

}


function search(eventKey) {
    if (eventKey === "Enter") {
        var keyword = document.getElementById('keyword').value;
        window.location.replace("user_admin.php?keyword=" + keyword);

    }
}

document.getElementById("search").addEventListener("keyup", (event) => {
  search(event.key);
});
