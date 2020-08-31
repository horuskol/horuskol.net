---
extends: _layouts.post
title: Convert SVG images in the browser using JavaScript and the Canvas API
author: Stuart Jones
date: 2020-08-31
section: post
tags: [javascript, image]
image: https://horuskol.net/assets/images/posts/20200831-svg-png-javascript/svg-to-png.png
description: It is possible to convert an SVG image into PNG, JPEG, or even WebP, in the browser.
---

SVG ([Scalable Vector Graphics]) have a few advantages over the usual image formats we use on websites. Firstly, it is possible to style them using CSS, making them very flexible. For some applications, they can also be smaller in filesize than the equivalent high quality PNG or JPEG. They can also be animated. Finally, just as it says in the name, they are scalable. This means they can be enlarged or shrunk down without pixelation or other shenanigans that you would get with a PNG or JPEG image - although photographs and other high-detail images are not suitable for SVG.

<figure class="in-flow">
<img height="50" width="50" src="/assets/images/posts/20200831-svg-png-javascript/example.svg" alt="">
<img height="100" width="100" src="/assets/images/posts/20200831-svg-png-javascript/example.svg" alt=""><br />
<img height="50" width="50" src="/assets/images/posts/20200831-svg-png-javascript/example.png" alt="">
<img height="100" width="100" src="/assets/images/posts/20200831-svg-png-javascript/example.png" alt="">
<figcaption>SVG (top) and PNG (bottom) images at 50 (left) and 100 (right) pixels.</figcaption>
</figure>

However, I found myself at the limit of the usefulness of SVG recently (although, that's not SVGs fault).

## The problem

I've been putting together a [Game of Life] board together as one of my experiments, and wanted to replace the solid black squares I was using with something with little more texture to it.

<figure class="in-flow">
<img height="20" width="40" src="/assets/images/posts/20200831-svg-png-javascript/tile.svg" alt=""><br />
<img height="100" width="200" src="/assets/images/posts/20200831-svg-png-javascript/tile.svg" alt="">
<figcaption>Comparing the old black tile on the left with a new "textured" tile on the right, at different sizes.</figcaption>
</figure>

The game uses a `<canvas>` element, which can be manipulated with the [Canvas API], and the original code for drawing a cell looked this (the project is built in Vue.js and the source is available on [GitHub]):

```javascript
drawCell(x, y, state) {
    let context = this.$refs.canvas.getContext('2d');

    switch (state) {
        case DEAD:
            context.fillStyle = 'white';
            break;

        case ALIVE:
            context.fillStyle = 'black';
            break;
    }

    // fill the grid square but leave the grid outline
    context.fillRect(
        (x * this.cellSize) + 1,
        (y * this.cellSize) + 1,
        this.cellSize - 2,
        this.cellSize - 2
    );
}
```

## Cloning images

As a first pass, I replaced that method with this much shorter one:

```javascript
drawCell(x, y, state) {
    let context = this.$refs.canvas.getContext('2d');

    context.drawImage(this.images[state], (x * this.cellSize), (y * this.cellSize), this.cellSize, this.cellSize);
}
```

`drawImage(Image, x, y, height, width)` accepts any previously loaded image data - including PNG, JPEG, and SVG.

Unfortunately, because the cell size can be controlled by the user, any PNG image I used would be subjected to the usual problems that come from resizing images, so I went ahead with using SVG.

I used the component's `mounted` method to preload the two images:

```javascript
async mounted() {
    this.images = {
        'alive': await loadImage("./img/alive.svg"),
        'dead': await loadImage("./img/dead.svg"),
    }

    this.initialiseMap();
}
```

`loadImage` is a simple function which allows the images to be loaded asynchronously, and so ensures that the images are available before the component tries to render them.

```javacript
function loadImage(url, height, width) {
    return new Promise((resolve, reject) => {
        let image = new Image();

        image.onload = () => {
            resolve(image);
        }
        image.onerror = reject;

        image.src = url;
    });
}
```

## Ground to a halt

Unfortunately, this implementation caused the whole drawing cycle to grind down drastically, and was taking tens of seconds to redraw each generation. This was quite the setback, considering the old filled square method was lightning fast, even on quite large boards.

I had hoped to fend off the worst performance issues by preloading the SVGs - but it seems the resizing and drawing for each cell was still a pretty expensive operation.

Was I doomed to have flat black tiles? I had one more thing to try.

## Converting SVG to PNG

Remember that `drawImage` can accept _any_ loaded image data? This data doesn't have to come directly from a file. It's possible to extract image data from another `canvas` element and use that where any other image can be used, including `drawImage`.

I updated my `loadImage` function:

```javascript
function loadImageAsPNG(url, height, width) {
    return new Promise((resolve, reject) => {
        let sourceImage = new Image();

        sourceImage.onload = () => {
            let png = new Image();
            let cnv = document.createElement('canvas'); // doesn't actually create an element until it's appended to a parent, 
                                                        // so will be discarded once this function has done it's job
            cnv.height = height;
            cnv.width = width;

            let ctx = cnv.getContext('2d');

            ctx.drawImage(sourceImage, 0, 0, height, width);
            png.src = cnv.toDataURL(); // defaults to image/png
            resolve(png);
        }
        image.onerror = reject;

        image.src = url;
    });
}
```

The `toDataURL(type, encoderOptions)` method here accepts a mime-type string (image/png, image/jpeg, etc), and return base-64 encoded image data that can be used as the source for another `Image`. If it doesn't support the requested mime-type, or if you don't provide one, it returns a PNG. The second parameter is used to control image quality for "lossy" types like JPEG. This takes a number between 0 and 1 - the default is 0.92.

Finally, `toDataURL` can also output the newer WebP type - but only in Chrome (which is okay because it will default to PNG in other browsers).

## Final thoughts

This isn't ideal - drawImage is still a costly function, and larger boards are still quite slow, so I need to search for other optimisations.

However, if you ever need to convert from one image type to any other (supported) type in a browser, think about the Canvas API and its `toDataURL` method. The `toDataURL` can even be used to get the image data from a Canvas that has been drawn on by the user (if your app supports that) and send it up to a server to be saved as a file.

[Scalable Vector Graphics]: https://css-tricks.com/using-svg/ (Using SVG on css-tricks.com)
[Game of Life]: https://apps.horuskol.net/conway-life-vue/ (Game of Life on apps.horuskol.net)
[GitHub]: https://github.com/horuskol/conway-life-vue (Game of Life on GitHub)
[Canvas API]: https://developer.mozilla.org/en-US/docs/Web/API/Canvas_API (Canvas API on MDN)