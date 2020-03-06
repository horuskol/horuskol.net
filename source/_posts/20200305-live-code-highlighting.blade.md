---
extends: _layouts.post
title: Live code highlighting in the browser with vanilla JavaScript
author: Stuart Jones
date: 2020-03-05
section: post
tags: [php, phpunit, testing]
image: https://horuskol.net/assets/images/horuskol-ring.png
description: A cross-platform, vanilla JavaScript text editor that can provide code highlighting (or any other kind of highlighting).
---

I wanted to be able to highlight code that users were writing into a textarea - particularly, I wanted to highlight potential problems with their code, like invalid syntax. I also think it would be nice if users can write code and have it highlight in much the same way as it might in a code editor.

The problem is that there is no way to style separate parts of the text inside a textarea. It is just a blob of text after all.

So, I went looking for a solution, and found quite a smart approach on [Coder's Block]. But his approach uses jQuery, and is several years old. I decided to adapt it to use vanilla JavaScript so it would be a more flexible example (actually, I first wrote it inside a VueJS component, and then revisited it this week to write up the blog post).

## The editor form

```html
<form>
  <select id="language-selector" aria-controls="code-highlighter">
    <option value="html">HTML</option>
    <option value="javascript" selected>JavaScript</option>
    <option value="php">PHP</option>
  </select>

  <fieldset class="editor">
    <textarea id="code-input" aria-controls="code-highlighter" class="input" autocapitalize="off" spellcheck="false"></textarea>
    <output id="code-output" role="status" class="highlighted-output javascript"></output>
  </fieldset>
</form>
```

If you haven't come across the `output` element before, here's a good [blog post][blog post on output element]. Here's the definition from the [HTML specification][HTML specification for output element]:

> The output element represents the result of a calculation performed by the application, or the result of a user action.

Unfortunately, the element still isn't quite universally supported, but a workaround is to include the ARIA attribute `role="status"` to inform any browsers that it is a "live" region. The `aria-controls` attributes on the language selector and code input elements inform browsers that changes to these elements will result in a change in the output element.

Turning off the autocapitalize and spellcheck will prevent browsers marking almost everything we type in the textarea as a spelling error (browsers don't understand code in textareas). 

## The styles

```css
.editor {
  position: relative;
  height: 30rem;
  padding: 0;
  border: none;
  margin: 2rem 0;
}

.editor .input,
.editor .highlighted-output {
  box-sizing: border-box;
  position: absolute;
  height: 100%;
  width: 90%;
  padding: 0.5em;
  border: 1px solid black;
  font-size: 1rem;
  line-height: 1.3rem;
  font-family: monospace;
  white-space: pre;
  word-wrap: break-word;
}

.editor .input {
  z-index: 1;
  color: transparent;
  caret-color: black;
  background-color: transparent;
}

.editor .highlighted-output {
  z-index: 0;
}
```

`position: absolute` and `position: relative` are fun. Relative positioning means an element will display where it would have anyway, until you set top, bottom, left or right values. Since we're happy with the editor fieldset where it is, we don't set any of those four properties. Absolute positioning means an element will display relative (huh?) to the position of the first non-statically positioned parent. Static positioning is the default.

Putting `position: absolute` on the textarea and output elements, means they are both positioned relative to the fieldset, which has `position: relative` set. Since we also don't set any top, bottom, left or right values on these two elements, they will occupy the same space in the page.

`box-sizing: border-box` overrides the strategy which the browser uses to draw the element's box. Textareas and outputs seem to have different defaults (in Chrome, at least), and I prefer border-box, which means the width of an element includes everything except the outside margin (whereas content-box means the borders and padding are _added_ to the height and width).

We want to make sure the text lines up, so we set the same font and line-height properties for the two elements. Of course, you can set whatever font family, sizes, or other styles here, as long as they're the same. Since this is a code editor, I suggest using a monospace font (I've left it to the system default).

Similarly, since this is intended to be a code editor, we want the textarea and output to use the same whitespace and wrapping rules that a `pre` element would.

Setting the `z-index` makes sure the textarea is above the output by setting a higher z-index value. This is important, since we want the user to be able to interact with textarea.

Finally, we want to see the highlighted code through the textarea - which is easily done by making both the `color` and `background-color` transparent. This also makes the caret (the pipe symbol which shows where the typing cursor is) transparent, but we can fix that by setting the `caret-color` property.

## Highlighting

We could roll own own highlighting code - but there's an amazingly comprehensive library available that supports 185 languages, and comes with almost 100 styles ready to choose from. [highlight.js] can be brought in as an npm module, or loaded in from a CDN (which only includes the top 34 languages). It is also possible to build a custom selection of languages into a minified script - so if you only need a handful of languages, you can limit the file size this way. The package includes all the available styles as separate CSS files, so we just have to select the one we want.

I included the stylesheet and highlighting script directly after the form:

```html
<link rel="stylesheet" href="./css/a11y-light.css" type="text/css">
<script src="./js/highlight.pack.js"></script>
```

The code to handle the highlighting is pretty simple (since we're using the library):

```javascript
const codeInput = document.querySelector('#code-input');
const codeOutput = document.querySelector('#code-output');

// initialise the highlighted output with whatever is in the input
codeOutput.textContent = codeInput.value;
hljs.highlightBlock(codeOutput);

codeInput.addEventListener('input', (event) => {
  codeOutput.textContent = codeInput.value;
  hljs.highlightBlock(codeOutput);
});
```

So, whenever the text in the textarea changes, we update the `textContent` of the output area and then apply the highlighting. It's important to use `textContent` here, since we need to escape any HTML characters (<, >, &, " and '), or else the browser will try and render it as HTML in the output element.

## Dealing with scrolling

Inevitably, a user is going to type a line that is too long for the available textarea, and since we've prevented line wrapping in our stylesheet, the textarea is going to start scrolling - but the output will not. Similarly, if the user writes more lines than the textarea's height can accommodate, the same thing will happen.

Thankfully, we can set the scrolling position of an element programmatically, and since we've taken pains to ensure the textarea and output have the same dimensions and padding and so on, it's trivial to calculate the output's scrolling position from the textarea's:

```javascript
codeInput.addEventListener('scroll', (event) => {
  codeOutput.scrollTop = codeInput.scrollTop;
  codeOutput.scrollLeft = codeInput.scrollLeft;
});
```

## Dealing with resizing

Textareas are, by default, resizable. We could make our life easy, and turn off that ability. However, I don't like it when I can't resize a textarea on a form (especially as I can be rather verbose), and I'm sure a lot of other users wouldn't appreciate it either.

To keep the output to the same size as the textarea, we need to use a `ResizeObserver`:

```javascript
const resizeObserver = new ResizeObserver((entries) => {
    for (let entry of entries) {
        if (entry.target === codeInput) {
            // match the height and width of the output area to the input area
            codeOutput.style.height = (codeInput.offsetHeight) + 'px';
            codeOutput.style.width = (codeInput.offsetWidth) + 'px';
            
            // provide some padding in the output area to allow for any scroll bars or other decoration in the input area
            // offsetWidth/offsetHeight is the full width/height of the element
            // clientWidth/clientHeight is the width/height inside any decoration, like a scrollbar
            codeOutput.style.paddingRight = (codeInput.offsetWidth - codeInput.clientWidth) + 'px';
            
            codeOutput.style.paddingBottom = (codeInput.offsetHeight - codeInput.clientHeight) + 'px';
        }
    }
});

resizeObserver.observe(codeInput);
```

## Switching languages

Just for fun, I wanted to be able to switch the editor between a set of different languages:

```javascript
languageSelector.addEventListener('change', (event) => {
  codeOutput.className = 'highlighted-output ' + languageSelector.value;

  // replace the current formatting
  codeOutput.textContent = codeInput.value;
  hljs.highlightBlock(codeOutput);
});
```

## Extra credit

I suppose we could add in a drop down that lets the user pick the style - there are ways to manage what files/styles are loaded in.

Another enhancement would be reacting to certain specific keys for a bit of code completion. We could catch the `tab` key to indent instead of changing focus to the next input (although, that will affect how some people navigate the form/page), or catch a `{` or `(` to then add the closing pair.

We could go even further and start linting for the user - but that will need a custom highlighting function to introduce good descriptive markup. With title attributes on the highlighting markup we could even include a description of the error or whatever. Although, so far I'm having trouble getting the tooltips to display through the textarea - the z-index settings block the mouseover event from passing through.

[Coder's Block]: https://codersblock.com/blog/highlight-text-inside-a-textarea/
[blog post on output element]: https://www.scottohara.me/blog/2019/07/10/the-output-element.html
[HTML specification for output element]: https://html.spec.whatwg.org/multipage/form-elements.html#the-output-element
[highlight.js]: https://highlightjs.org/download/