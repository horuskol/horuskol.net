const axios = require('axios');

document.getElementById("presentation-slide").addEventListener('click', (event) => {
    let target = document.getElementById("presentation-slide");

    slideIndex++;
    if (slideUrls[slideIndex]) {
        axios.get(slideUrls[slideIndex])
            .then((response) => {
                console.log(target);
                target.innerHTML = response.data;
            }, (response) => {
                console.log(response);
            });
    };
}, {capture: true});