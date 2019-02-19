const axios = require('axios');

let nextElement = null;

document.getElementById("presentation-slide").addEventListener('click', (event) => {
    let target = document.getElementById("presentation-slide");

    if (null === nextElement) {
        slideIndex++;
        if (slideUrls[slideIndex]) {
            axios.get(slideUrls[slideIndex])
                .then((response) => {
                    let buffer = document.createElement('div');
                    buffer.innerHTML = response.data;

                    buffer.childNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            node.classList.add('invisible');
                        }
                    });

                    target.innerHTML = buffer.innerHTML;
                    target.firstElementChild.classList.remove('invisible');
                    nextElement = target.firstElementChild.nextElementSibling;

                }, (response) => {
                    console.error(response);
                });
        }
    } else {
        nextElement.classList.remove('invisible');
        nextElement = nextElement.nextElementSibling;
    }
}, {capture: true});