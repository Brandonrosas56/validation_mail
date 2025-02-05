import './bootstrap';


document.addEventListener("DOMContentLoaded", function() {
    var hoverChangeButtons = document.querySelectorAll(".hover-change-button");
  
    hoverChangeButtons.forEach(function(hoverChangeButton) {
        hoverChangeButton.addEventListener("mouseenter", function() {
            var changeButtons = this.querySelectorAll(".change-button");
            changeButtons.forEach(function(changeButton) {
                changeButton.classList.remove("hidden");
            });
        });
      
        hoverChangeButton.addEventListener("mouseleave", function() {
            var changeButtons = this.querySelectorAll(".change-button");
            changeButtons.forEach(function(changeButton) {
                changeButton.classList.add("hidden");
            });
        });
    });
});

  