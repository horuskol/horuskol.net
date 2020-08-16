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

If you want to see an application of this ability, take a look at my [Tower of Hanoi] game.

## Building up a solution

<figure class="in-flow">
<img src="/assets/images/posts/20200815-dnd-touch/dnd-moving.gif" alt="Animation of moving an element from one parent to another">
</figure>

To get this working, we need a simple layout:

```html
<html>
    <div id="container">
        <div id="left-parent">
            <div id="movable-element"></div>
        </div>
    
        <div id="right-parent"></div>
    </div>
</html>
```

Okay, this is a bit bland (and empty), so we'll put in a bit of styling to get a visible layout.

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

Our objective is to enable the user to move the green element from the left parent to the right, and back again - while updating the document.

## How to pick up and make a move

We want the same interaction for the user whether they're using a mouse or using a touch device. So, we're going to programme both functionalities in tandem. This is helped by the fact that there are analogous events between both APIs:

* `touchstart` is equivalent to `mousedown`
* `touchend` is equivalent to `mouseup`
* `touchmove` is equivalent to `mousemove`

There's a couple of caveats. Touch has an additional `touchcancel` event which is triggered when the browsers decides something should interrupt the touch behaviour. Also, the touch events carry additional information because you can have multiple touchpoints, whereas the Mouse API only allows for a single mouse pointer.

All that consider, our first step is to allow users to 'pick up' the element. This is done by listening for `mousedown` and `touchstart` events on the movable element.

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

To do this we need to change the element's position to fixed, and also listen out for changes in the mouse/finger position, using `mousemove` and `touchmove`.

```html
<div id="movable-element"
     onmousedown="pickup(event)" 
     ontouchstart="pickup(event)" 
     onmousemove="move(event)" 
     ontouchmove="move(event)"
></div>
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

Now when we click on the element:

<figure class="in-flow">
<img src="/assets/images/posts/20200815-dnd-touch/dnd-blowout.gif" alt="Animation of an element suddenly blowing out in size when clicked on">
</figure>

Oh dear - what just happened?

The moving element uses relative height to fill the space available in its parent. When we change its positioning to fixed, the element attempts to fill the whole page, hence the blowout. This is easily fixed, though:

```javascript
function pickup(event) {
    moving = event.target;

    moving.style.height = moving.clientHeight;
    moving.style.width = moving.clientWidth;
    moving.style.position = 'fixed';
}
```

## Let's get moving

The tricky bit here is that `mousemove` and `touchmove` pass slightly different information in the event. This is because `touchmove` allows for multiple touchpoints to move around the screen (a feature that would allow us to do things like pinch-zoom and rotate, if we so wished).

```javascript
function move(event) {
    if (moving) {
        if (event.clientX) {
            // mousemove
            moving.style.left = event.clientX - moving.clientWidth/2;
            moving.style.top = event.clientY - moving.clientHeight/2;
        } else {
            // touchmove - assuming a single touchpoint
            moving.style.left = event.changedTouches[0].clientX - moving.clientWidth/2;
            moving.style.top = event.changedTouches[0].clientY - moving.clientHeight/2;
        }
    }
}
```

We use `clientX` and `clientY` here to account for the page being scrolled. The element is being positioned relative to the window's left and top edges, so we want to know where our mouse/finger is relative to the window's top-left corner.

Now we have our element tracking our mouse/finger movements, but there a couple more problems now:

1. The element sticks to the mouse pointer when we let go of the button.
2. The element just sits wherever we left it when we lift up our finger.

<figure class="in-flow">
<img src="/assets/images/posts/20200815-dnd-touch/dnd-sticky.gif" alt="Animation of an element sticking to the mouse pointer position">
</figure>

## Let it go!

What we need to do now is react to the user letting go of the element (`mouseup` and `touchend`):

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

<figure class="in-flow">
<img src="/assets/images/posts/20200815-dnd-touch/dnd-snapback.gif" alt="Animation of an element returning to its original position when mouse button is released">
</figure>

## Drop it like it's hot

The final piece of the puzzle is getting the element to actually move when we drop it where we want it to go.

So, we need to know where we've dropped it.

The problem is, because we've made our element move everywhere underneath our pointer/finger, the event's target information is just going to give us the element we're moving, and not any information about where we're trying to drop it.

To overcome this, we can set the z-index of our element so that it appears behind the elements we're moving between. Unfortunately, this hides the element and prevents the event listeners for moving and releasing the element from firing, so we have to make a few changes to where we place them.

```html
<html onmouseup="drop(event)" ontouchend="drop(event)">
    <div id="container" onmousemove="move(event)" ontouchmove="move(event)">
        <div id="left-parent" onmouseup="drop(event)" ontouchend="drop(event)">
            <div id="movable-element" onmousedown="pickup(event)" ontouchstart="pickup(event)"></div>
        </div>

        <div id="right-parent" onmouseup="drop(event)" ontouchend="drop(event)"></div>
    </div>
</html>
```

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

Putting the move listeners on the container constrains the movement to within that part of the page (if you want to be able to move everywhere, you can put the listeners on the `<html>` element instead).

We put the `mouseup` and `touchend` listeners on the `<html>` element so that it doesn't matter where we let go of the mouse or lift up our finger, the element will return to its original location (unless a different element's event listener prevents that). Finally, we put a `mouseup` and `touchend` listener on each target area (including the original parent for when we want to move back).

Now we're ready to move our element from one part of the document to another.

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

For some reason, on touch devices, `event.currentTarget` gives us the parent of the element we're moving - not the parent we're trying to move to. I don't understand the variation in behaviour here, because touch and mouse have been pretty consistent so far.

Luckily, there is native javascript function that tells us what element is under a specific point on the page - [elementFromPoint].

```javascript
function drop(event) {
    if (moving) {
        if (event.currentTarget.tagName !== 'HTML') {
            let target = null;
            if (event.clientX) {
                target = document.elementFromPoint(event.clientX, event.clientY);
            } else {
                target = document.elementFromPoint(event.changedTouches[0].clientX, event.changedTouches[0].clientY);
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

About the only problem with this solution is that setting a negative z-index on the moving element means it could get obscured by other elements that are not transparent as we move it around. There is an experimental extension to `elementFromPoint` - [elementsFromPoint] - but it hasn't been fully implemented by all browsers yet. There's also the issue of identifying which of the many elements under that point we want.

[ViewCrafter]: https://viewcrafter.com (ViewCrafter homepage)
[Drag and Drop API]: https://developer.mozilla.org/en-US/docs/Web/API/HTML_Drag_and_Drop_API (HTML Drag and Drop API on MDN)
[Touch API]: https://developer.mozilla.org/en-US/docs/Web/API/Touch_events (Touch API and Touch events on MDN)
[Tower of Hanoi]: https://apps.horuskol.net/tower-of-hanoi/ (Tower of Hanoi experiment on apps.horuskol.net)
[elementFromPoint]: https://developer.mozilla.org/en-US/docs/Web/API/DocumentOrShadowRoot/elementFromPoint (DocumentOrShadowRoot.elementFromPoint on MDN)
[elementsFromPoint]: https://developer.mozilla.org/en-US/docs/Web/API/DocumentOrShadowRoot/elementFromPoints (DocumentOrShadowRoot.elementFromPoints on MDN)