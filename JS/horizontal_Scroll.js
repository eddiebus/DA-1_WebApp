function horizontalScrollInit(){
    let elements = document.getElementsByClassName("horizontalMenu");
    for (let i = 0; i < elements.length; i++)
    {
        elements[i].addEventListener('mousedown',horizontalScroll_MouseDown)
        elements[i].addEventListener('mouseup',horizontalScroll_MouseUp)
        elements[i].addEventListener('mouseleave',horizontalScroll_MouseUp)
        elements[i].addEventListener('mousemove',horizontalScroll_MouseMove)
    }
}

let horizontalScroll_Mouse = {
    mouseDown: false,
    startX: 0,
}

function horizontalScroll_MouseMove(eventObj) {
    //mouse not pressed. do nothing
    if (!horizontalScroll_Mouse.mouseDown){return;}
    else
    {
        let eventElement = eventObj.target;
        let currentX = eventObj.pageX - eventElement.offsetLeft;
        let scrollDelta = currentX - horizontalScroll_Mouse.startX;
        eventElement.scrollLeft = eventElement.scrollLeft - scrollDelta;
    }
}

function horizontalScroll_MouseDown(eventObj) {

    console.log("Mouse Down!");
    let eventElement = eventObj.target;

    horizontalScroll_Mouse.mouseDown = true;
    horizontalScroll_Mouse.startX = eventObj.pageX - eventElement.offsetLeft;

}

function horizontalScroll_MouseUp(eventObj) {
    console.log("Mouse Up")
    horizontalScroll_Mouse.mouseDown = false;
}