// Add a class to an node
function addClass(node, className) {
    if (!hasClass(node, className)) node.className += " " + className;
}


// Removes a class from an node
function removeClass(node, className) {
    if (hasClass(node, className)) {
        var reg = new RegExp('(\\s|^)' + className + '(\\s|$)');
        node.className = node.className.replace(reg, ' ');
    }
}

// Does the node have a class
function hasClass(node, className) {
    if (node.className) {
        return node.className.match(
            new RegExp('(\\s|^)' + className + '(\\s|$)'));
    } else {
        return false;
    }
}

//Toggle div expand/collapse
function toggleCollapse(IDS) {
  var node = document.getElementById(IDS);
  if (node.offsetHeight == 0) { 
    //clone div to get height
    var clone = node.cloneNode(true);
    clone.style.visibility = 'hidden';
    clone.style.position = 'absolute';
    node.appendChild(clone); 
    clone.style.height = 'auto';
    var height = clone.offsetHeight;
    clone.parentNode.removeChild(clone);
    
    //set height
    node.style.height = height+"px";
  } else { 
    node.style.height = 0+"px";
  }
}