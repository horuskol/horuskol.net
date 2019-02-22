const axios = require('axios');

let nextElement = null;
let clicksOff = false;

document.getElementById("presentation-slide").addEventListener('click', (event) => {
    let target = document.getElementById("presentation-slide");

    if (clicksOff) {
        return;
    }

    if (null === nextElement) {
        slideIndex++;
        if (slideUrls[slideIndex]) {
            axios.get(slideUrls[slideIndex])
                .then((response) => {
                    target.childNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            node.classList.add('invisible');
                        }
                    });

                    clicksOff = true;

                    setTimeout(() => {
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

                        clicksOff = false;
                    }, 1250);

                }, (response) => {
                    console.error(response);
                });
        }
    }

    if (nextElement) {
        nextElement.classList.remove('invisible');
        nextElement = nextElement.nextElementSibling;
    }
});

document.querySelectorAll(".presentation a").forEach((link) => {
    link.addEventListener("click", (event) => {
        event.stopPropagation();
        event.preventDefault();

        window.open(event.target.href, '');
    });
});