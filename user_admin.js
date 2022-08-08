function del_user(id) {
    let confirmAct = confirm("Are you sure to delete this user?");

    if (confirmAct){
        let request = new XMLHttpRequest();
        request.open("GET", "user_admin_delete.php?id="+id);
        request.send();
        location.reload();
    }

}
