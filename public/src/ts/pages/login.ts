import './../../sass/main.scss';
import './../../sass/pages/login.scss';


import {formData} from './../components/forms';

const form = document.querySelector('form')!;

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const data = formData(form);
    console.log(data);
});