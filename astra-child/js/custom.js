document.addEventListener('DOMContentLoaded', function() {
    let btn = document.createElement("button");
    btn.innerHTML = "↑ Top";
    btn.id = "backToTopBtn";
    btn.style.cssText = "position:fixed;bottom:20px;right:20px;display:none;padding:10px 15px;background:#0073aa;color:#fff;border:none;border-radius:5px;cursor:pointer;z-index:9999;";
    document.body.appendChild(btn);

    window.onscroll = function() {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            btn.style.display = "block";
        } else {
            btn.style.display = "none";
        }
    };

    btn.onclick = function() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    };
});
