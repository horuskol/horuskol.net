---
extends: _layouts.post
title: Drag and drop elements on touch devices
author: Stuart Jones
date: 2020-08-15
section: post
tags: [html, javascript, user-interface]
image: https://horuskol.net/assets/images/horuskol-ring.png
description: Want to allow users to move elements around a page no matter what device they're using? Here's a quick guide to implementing drag and drop for mouse and touch users. 
---

I've been playing around with dragging and dropping stuff in web browsers for a while. [ViewCrafter] relies on the [Drag and Drop API], since it enables me to pass data easily to drop targets in different windows. I'll probably do a blog post about that at some point.

This blog post is about being able to move an element by dragging it around on a touch screen. Unfortunately, the Drag and Drop API isn't supported all that well on touch devices, and so I've had to dig a bit into the [Touch API] to provide an experience for the user that works on touch and on traditional desktop browsers.

If you want to look at a working solution, you can take a look at my [Tower of Hanoi] game experiment.

## Building up a solution

<figure class="in-flow">
<img src="/assets/images/posts/20200815-dnd-touch/dnd-moving.gif" alt="Animation of moving an element from one parent to another">
</figure>

Let's start with a simple layout:

```html
<div id="container">
    <div id="left-parent">
        <div id="movable-element"></div>
    </div>

    <div id="right-parent"></div>
</div>
```

Okay, this is a bit bland, so we'll put a little bit of styling around it so we can see the different elements.

```css
* {
  box-sizing: border-box;
}
#container {
  display: flex;
}

#container > div {
  border: 1px solid gray;
  padding: 1em;

  height: 10em;
  width: 50%;
}

#movable-element {
  border: 1px solid green;
  background-color: #00ff0033;
  height: 100%;
  width: 100%;
}
```

Our objective is to now enable the user to move the green 'movable' element from the left parent to the right, and back again.

## How to pick up and make a move

We want the same interaction for the user whether they're using a mouse or using a touch device. So, we're going to do programme in tandem.

First up, we want to be able to 'pick up' the element we want to move.

```html
<div id="movable-element" onmousedown="pickup(event)" ontouchstart="pickup(event)"></div>
```

```javascript
let moving = null;

function pickup(event) {
    moving = event.target;
}
```

Nothing much will happen yet, since we also need to track our mouse/finger movements and move the element to match.

To do this we need to change the element's position to fixed, and also listen out for changes in the mouse/finger position.

```html
<div id="movable-element" onmousedown="pickup(event)" ontouchstart="pickup(event)" onmousemove="move(event)" ontouchmove="move(event)"></div>
```

```javascript
let moving = null;

function pickup(event) {
    moving = event.target;
    
    moving.style.position = 'fixed';
}

function move(event) {
    if (moving) {
        // track movement
    }
}
```

<figure class="in-flow">
<img src="/assets/images/posts/20200815-dnd-touch/dnd-blowout.gif" alt="Animation of an element suddenly blowing out in size when clicked on">
</figure>

Oh dear - what just happened?

The moving element uses relative height to fill the space available in its parent. When we change its positioning to fixed, the element attempts to fill the whole page. Easily fixed, though:

```javascript
function pickup(event) {
    moving = event.target;

    moving.style.height = moving.clientHeight;
    moving.style.width = moving.clientWidth;
    moving.style.position = 'fixed';
}
```

## Let's get moving

The tricky bit here is that `mousemove` and `touchmove` pass slightly different information in the event. This is because `touchmove` allows for multiple touchpoints to move around the screen - using this would allow us to do things like pinch-zoom and rotate.

So, we need to know what we're looking for:

```javascript
function move(event) {
    if (moving) {
        if (event.pageX) {
            // mousemove
            moving.style.left = event.pageX - moving.clientWidth/2;
            moving.style.top = event.pageY - moving.clientHeight/2;
        } else {
            // touchmove - assuming a single touchpoint
            moving.style.left = event.changedTouches[0].pageX - moving.clientWidth/2;
            moving.style.top = event.changedTouches[0].pageY - moving.clientHeight/2;
        }
    }
}
```

Now we have our element tracking our mouse/finger movements. Two problems now:

1. The element is stuck to the mouse pointer when we let go of the button.
2. The element just sits wherever we left it when we lift up our finger.

<figure class="in-flow">
<img src="/assets/images/posts/20200815-dnd-touch/dnd-sticky.gif" alt="Animation of an element sticking to the mouse pointer position">
</figure>

## Let it go!

What we need to do now is react to the user letting go of the element:

```html
<div id="movable-element"
     onmousedown="pickup(event)"
     ontouchstart="pickup(event)"
     onmousemove="move(event)"
     ontouchmove="move(event)"
     onmouseup="drop(event)"
     ontouchend="drop(event)"
></div>
```

```javascript
function drop(event) {
    if (moving) {
        // reset our element
        moving.style.left = '';
        moving.style.top = '';
        moving.style.height = '';
        moving.style.width = '';
        moving.style.position = '';

        moving = null;
    }
}
```

## Drop it like it's hot

The final piece of the puzzle is getting the element to actually move when we drop it where we want it to go.

So, we need to know where we've dropped it.

The problem is, because we've made our element move everywhere underneath our pointer/finger, the event's target information is just going to give use the element we're moving.

We can set the z-index of our element so that it appears behind the elements we're moving between - but this breaks our move listeners, so we have to make a few changes there, too.

```javascript
function pickup(event) {
    moving = event.target;

    moving.style.height = moving.clientHeight;
    moving.style.width = moving.clientWidth;
    moving.style.position = 'fixed';
    moving.style.zIndex = '-10';
}

function drop(event) {
    if (moving) {
        // reset our element
        moving.style.left = '';
        moving.style.top = '';
        moving.style.height = '';
        moving.style.width = '';
        moving.style.position = '';
        moving.style.zIndex = '';

        moving = null;
    }
}
```

```html
<html onmouseup="drop(event)" ontouchend="drop(event)">
    <div id="container" onmousemove="move(event)" ontouchmove="move(event)">

        <div id="left-parent" onmouseup="drop(event)" ontouchend="drop(event)">
            <div id="movable-element" onmousedown="pickup(event)" ontouchstart="pickup(event)">
                
            </div>
        </div>

        <div id="right-parent" onmouseup="drop(event)" ontouchend="drop(event)">

        </div>
    </div>
</html>
```

Putting the move listeners on the container constrains the movement to within that part of the page (if you want to be able to move everywhere, you can put the listeners on the `<html>` element instead).

We put the `mouseup` and `touchend` listeners on the `<html>` element so that it doesn't matter where we let go of the mouse or lift up our finger, the element will return to it's default location.

Finally, we put a `mouseup` and `touchend` listener on each target area (including the original parent for when we want to move back).

Now we're ready to move our element.

```javascript
function drop(event) {
    if (moving) {
        if (event.currentTarget.tagName !== 'HTML') {
            event.currentTarget.appendChild(moving);
        }

        // reset our element
        moving.style.left = '';
        moving.style.top = '';
        moving.style.height = '';
        moving.style.width = '';
        moving.style.position = '';
        moving.style.zIndex = '';

        moving = null;
    }
}
```

`event.currentTarget` tells us which element the event triggered on. `appendChild` moves the element from it's original parent to the new one. At least, it works on desktops. We have to do something else to get it to work on touch screens.

## Touchy touch screens

For some weird reason, on touch devices, `event.currentTarget` gives us the parent of the element we're moving - not the parent we're trying to move to. I don't understand the variation in behaviour here, because touch and mouse have been pretty consistent so far.

Luckily, there is native javascript function that tells us what element is under a specific point on the page - [elementFromPoint].

```javascript
function drop(event) {
    if (moving) {
        if (event.currentTarget.tagName !== 'HTML') {
            let target = null;
            if (event.pageX) {
                target = document.elementFromPoint(event.pageX, event.pageY);
            } else {
                target = document.elementFromPoint(event.changedTouches[0].pageX, event.changedTouches[0].pageY);
            }

            target.appendChild(moving);
        }

        // reset our element
        moving.style.left = '';
        moving.style.top = '';
        moving.style.height = '';
        moving.style.width = '';
        moving.style.position = '';
        moving.style.zIndex = '';

        moving = null;
    }
}
```

## That's all

<figure class="in-flow">
<img src="/assets/images/posts/20200815-dnd-touch/dnd-done.gif" alt="Animation of an element changing parents when dragged around the page">
</figure>

So, there we go, we can now move an element from one parent to another by dragging it with a finger. 

[ViewCrafter]: https://viewcrafter.com (ViewCrafter homepage)
[Drag and Drop API]: https://developer.mozilla.org/en-US/docs/Web/API/HTML_Drag_and_Drop_API (HTML Drag and Drop API on MDN)
[Touch API]: https://developer.mozilla.org/en-US/docs/Web/API/Touch_events (Touch API and Touch events on MDN)
[Tower of Hanoi]: https://apps.horuskol.net/tower-of-hanoi/ (Tower of Hanoi experiment on apps.horuskol.net)
[elementFromPoint]: https://developer.mozilla.org/en-US/docs/Web/API/DocumentOrShadowRoot/elementFromPoint (DocumentOrShadowRoot.elementFromPoint on MDN)