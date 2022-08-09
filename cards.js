function fetchData(sorting, keyword) {

    var apiUrl = 'getCards.php?sorting=' + sorting;

    if (keyword !== undefined ){
        
        apiUrl = apiUrl + '&search=' + keyword;
    }

    fetch(encodeURI(apiUrl))
        .then(response => response.json())
        .then(cards => processSearchResults(cards));
}


function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
    
}

function addCard(table, card) {
    const row = document.createElement("tr");
    const icon = document.createElement("td");
    const level = document.createElement("td");
    const category = document.createElement("td");
    const name = document.createElement("td");
    const hp = document.createElement("td");
    const atk = document.createElement("td");
    const def = document.createElement("td");
    const edit = document.createElement("td");
    const del = document.createElement("td")
    
    
    const show_a = document.createElement('a');
    const show_link = document.createTextNode(htmlDecode(card.name));
    show_a.appendChild(show_link);
    show_a.title = card.name;
    show_a.href = "javascript:readCard('" + card.card_id + "');";
    name.appendChild(show_a);

    level.align = 'center';
    category.align = 'center';
    level.innerHTML = card.level;
    category.innerHTML = card.category; 
    
    hp.align = 'center';
    atk.align = 'center';
    def.align = 'center';
    hp.innerHTML = card.hp;
    atk.innerHTML = card.atk;
    def.innerHTML = card.def;

    
    if (IsNullOrEmpty(card.icon_path)) {
        const icon_img = document.createElement("img");
        icon_img.align = 'center';
        icon_img.src = card.icon_path;
        icon_img.width = "50";
        icon.align = 'center';
        icon.appendChild(icon_img);
    }else
    {
        icon.align = 'center';
        icon.innerHTML = '-';
    }

    row.appendChild(icon);
    row.appendChild(level);
    row.appendChild(category);
    row.appendChild(name);

    row.appendChild(hp);
    row.appendChild(atk);
    row.appendChild(def);

    if (user_type !== undefined){
        const edit_a = document.createElement('a');
        const edit_link = document.createTextNode("Edit");
        edit_a.appendChild(edit_link);
        edit_a.title = "Edit";
        edit_a.href = "card_edit.php?id=" + card.card_id;
        edit.align = 'center';
        edit.appendChild(edit_a);

        const del_a = document.createElement('a');
        const del_link = document.createTextNode("Del");
        del_a.appendChild(del_link);
        del_a.title = "Del";
        //del_a.href = "card_del.php?id=" + card.card_id;
        del_a.href = "javascript:delCard('" + card.card_id + "');";
        del.align = 'center';
        del.appendChild(del_a);
        
        if(user_type == 'C'){
            edit.style="display:block;"
            del.style="display:block;"
        }else{
            edit.style="display:none;"
            del.style="display:none;"
        }

        row.appendChild(edit);
        row.appendChild(del);
    }

    table.appendChild(row);
}

function processSearchResults(cards) {
    content = document.getElementById('content');
    removeAllChildNodes(content);

    if (cards.length == 0) {
        const message = document.createElement("H2");
        message.innerHTML = `Could not find any cards.'.`
        content.appendChild(message);
    }
    else{

        const table = document.createElement("table");
        const theader = document.createElement("thead");
        const trHeader = document.createElement("tr");
        const icon = document.createElement("td");
        const level = document.createElement("td");
        const category = document.createElement("td");
        const name = document.createElement("td");
        const hp = document.createElement("td");
        const atk = document.createElement("td");
        const def = document.createElement("td");



        level.innerHTML = 'Level';
        category.innerHTML = "Category";
        icon.innerHTML = 'Icon';
        name.innerHTML = 'Card Name';
        hp.innerHTML = 'HP';
        atk.innerHTML = 'ATK';
        def.innerHTML = 'DEF';

        trHeader.appendChild(icon);
        trHeader.appendChild(level);
        trHeader.appendChild(category);
        trHeader.appendChild(name);
        trHeader.appendChild(hp);
        trHeader.appendChild(atk);
        trHeader.appendChild(def);

        if (user_type !== undefined){
            const create = document.createElement("td");
            const a = document.createElement('a');
            

            const link = document.createTextNode("Create");
            a.appendChild(link);
            a.title = "Create";
            a.href = "card_create.php";

            //create.colspan = "2";
            create.style = 'colspan = "2"';
            
            if(user_type == 'C'){
                create.style="display:block;"
            }else{
                create.style="display:none;"
            }
            
            create.appendChild(a);
            trHeader.appendChild(create);
        }
        
        theader.appendChild(trHeader);
        table.appendChild(theader);

        for (let card of cards) {
            addCard(table, card);
        }

        content.appendChild(table);
    }

}

function IsNullOrEmpty(value){
    // Check if value is null or empty string then return false, otherwise return true
    return !(value == null || value.trim() == "");
}

function readCard(id){
    window.open("card_show.php?id=" + id, "_blank", "width=1000,height=800,resizable=no");
    
}

function delCard(id){
    let confirmAct = confirm("Are you sure to delete this card?");

    if (confirmAct){
        let request = new XMLHttpRequest();
        request.open("GET", "card_delete.php?id="+id);
        request.send();
        location.reload();
    }
    
}

function htmlDecode(input) {
  var doc = new DOMParser().parseFromString(input, "text/html");
  return doc.documentElement.textContent;
}


function sorting() {
    var sorting = document.getElementById('sort').value;
    fetchData(sorting);
}

function search(eventKey) {
    if (eventKey === "Enter") {
        var sorting = document.getElementById('sort').value;
        var keyword = document.getElementById('keyword').value;
        fetchData(sorting, keyword);

    }
}

/*
	load function
 */
function load() {
    if (document.getElementById('sort')){
        var sorting = document.getElementById('sort').value;
    }else{
        var sorting = 'LVLCATN';
    }

    fetchData(sorting);
}

load();

if (IsNullOrEmpty(valid)) {
    // Add onchange event for call the sorting() after sort select changed.
    document.getElementById("sort").onchange = function(){
        sorting();
    };

    document.getElementById("search").addEventListener("keyup", (event) => {
      search(event.key);
    });
}