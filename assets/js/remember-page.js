

jQuery(document).ready(($)=>{

});

function hideMoreStoryText(e) {
    e.preventDefault();

    e.target.parentElement.parentElement.children[0].setAttribute("style","display:block;");
    e.target.parentElement.parentElement.children[1].setAttribute("style","display:none;");

    e.target.setAttribute("style","display:none;");
    e.target.parentElement.children[1].setAttribute("style","display:block;");
}

function showMoreStoryText(e) {
    e.preventDefault();
   
    e.target.parentElement.parentElement.children[0].setAttribute("style","display:none;");
    e.target.parentElement.parentElement.children[1].setAttribute("style","display:block;");

    e.target.setAttribute("style","display:none;");
    e.target.parentElement.children[2].setAttribute("style","display:block;");
}