window.addEventListener("load", getRandomMeme);
function getRandomMeme(){
     jQuery(document).ready(function(){
        jQuery.ajax({
            url: "http://localhost:10004/wp-json/RandomMeme/v1/meme",
            method: "GET",

            dataType : "json",

            success : function(data){
                let html = `<div>${data.title}</div>
                <div>${data.image}</div>`;
                jQuery("#test").html(html);
            }
        })
    });
}