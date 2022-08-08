//File upload script for image upload.

const imageFolder = 'image_upload/'
const iconFolder = 'icon_upload/'
const iconElement = document.getElementById("icon_filepath");

if (iconElement){
    iconElement.onchange = () => {
        document.getElementById("icon_path").value = iconFolder + iconElement.files[0].name;


        const iconFile = iconElement.files[0];
        const iconImg = document.createElement('img');
        iconImg.classList.add("obj");
        iconImg.file =  iconFile;
        iconImg.width = "100";
        while (document.getElementById("img_icon").firstChild) {
            document.getElementById("img_icon").removeChild(document.getElementById("img_icon").firstChild);
        }
        document.getElementById("img_icon").appendChild(iconImg);

        const iconReader = new FileReader();
        iconReader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(iconImg);
        iconReader.readAsDataURL(iconFile);
        
    };
}

const imageElement = document.getElementById("image_filepath");

if (imageElement){
    imageElement.onchange = () => {
        document.getElementById("image_path").value = imageFolder + imageElement.files[0].name;

        const imageFile = imageElement.files[0];
        const imageImg = document.createElement('img');
        imageImg.classList.add("obj");
        imageImg.file =  imageFile;
        imageImg.width = "100";
        while (document.getElementById("img_image").firstChild) {
            document.getElementById("img_image").removeChild(document.getElementById("img_image").firstChild);
        }
        document.getElementById("img_image").appendChild(imageImg);

        const imageReader = new FileReader();
        imageReader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(imageImg);
        imageReader.readAsDataURL(imageFile);
        
    };
}
