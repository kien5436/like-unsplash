Element.prototype.css = function (style) {
   for (prop in style) {
      if (style.hasOwnProperty(prop))
         this.style.setProperty(prop, style[prop]);
   }
}
Element.prototype.hasClass = function (className) {
   return this.classList.contains(className) || this.parentNode.classList.contains(className);
}