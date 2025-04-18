import { Input, Select, Datepicker, initMDB } from './mdb.es.min.js';
// import colors from "tailwindcss/colors";

//Common Variables
let windowHeight = window.innerHeight;
let documentHeight = document.body.clientHeight;
let screenHeight = screen.height;
let scrolledPx = document.getElementById('app').scrollTop;
let navBar = document.getElementsByClassName('navbar')[0];

function updateAdultName() {
    let registree_input = document.getElementById('attending_adult_row_duplicate').querySelectorAll('input')[0];

    registree_input.classList.add('active');
    registree_input.value = document.getElementById('firstname').value;
}

function addNewRowNumber(attending) {
    initMDB({ Input });
    initMDB({ Select });

    let count = document.getElementById("attending_" + attending).value;
    let currentRows = document.getElementById('registration_form_table').querySelectorAll('.attending_' + attending + '_row:not(#attending_' + attending + '_row_default)');
    let currentPrice = document.getElementById("cost_per_" + attending);
    let currentTotalPrice = document.getElementById("total_" + attending);
    let totalPrice = document.getElementById("total_amount_due");

    // Remove new rows if any exist
    for (var x = 0; x < currentRows.length; x++) {
        currentRows[x].remove();
    }

    // Add new rows
    if(attending === 'adult') {
        count--;
    }

    for (var x = 0; x < count; x++) {
        let newRow = document.getElementById('attending_' + attending + '_row_default').cloneNode(true);

        //Remove disables
        newRow.classList.remove('d-none');
        newRow.id = '';
        newRow.querySelector('input').removeAttribute('disabled');
        newRow.querySelector('select').removeAttribute('disabled');

        //Add new row
        document.getElementsByClassName('attending_' + attending + '_row')[0].insertAdjacentElement('afterend', newRow);

        console.log(newRow.querySelector('input').parentElement);
        console.log(newRow.querySelector('input'));

        new Input(newRow.querySelector('input').parentElement).init();
        new Select(newRow.querySelector('select'));
    }

    //Update total price per person
    if(attending === 'adult') {
        count++;
    }
    currentTotalPrice.value = count * Number(currentPrice.value);

    //Update total price for family
    totalPrice.value = Number(document.getElementById("total_adult").value) + Number(document.getElementById("total_youth").value) + Number(document.getElementById("total_children").value);
}

function addNewRowFromBtn(taskTitle) {
    initMDB({ Input });
    initMDB({ Select });
    initMDB({ Datepicker });

    let newRow = document.getElementById('new_' + taskTitle + '_row_default').cloneNode(true);
    let selectForm, inputForm, textareaForm, datePickerForm;

    //Remove disables
    newRow.classList.remove('d-none');
    newRow.id = '';
    selectForm = newRow.querySelectorAll('select');
    inputForm = newRow.querySelectorAll('input:not(.myDatePicker)');
    textareaForm = newRow.querySelectorAll('textarea');
    datePickerForm = newRow.querySelectorAll('input.myDatePicker');

    //Add new row
    document.getElementById('new_' + taskTitle + '_row_default').insertAdjacentElement('afterend', newRow);

    if(selectForm.length > 0) {
        for (let i = 0; i < selectForm.length; i++) {
            selectForm[i].removeAttribute('disabled');
            new Select(selectForm[i]);
        }
    }

    if(inputForm.length > 0) {
        for (let i = 0; i < inputForm.length; i++) {
            inputForm[i].removeAttribute('disabled');
            new Input(inputForm[i].parentElement).init();
        }
    }

    if(textareaForm.length > 0) {
        for (let i = 0; i < textareaForm.length; i++) {
            textareaForm[i].removeAttribute('disabled');
            textareaForm[i].parentElement.setAttribute('data-mdb-input-init', '');
            new Input(textareaForm[i].parentElement).init();
        }
    }

    if(datePickerForm.length > 0) {
        for (let i = 0; i < datePickerForm.length; i++) {
            datePickerForm[i].removeAttribute('disabled');
            datePickerForm[i].parentElement.setAttribute('data-mdb-datepicker-init', '');
            datePickerForm[i].parentElement.setAttribute('data-mdb-input-init', '');
            new Input(datePickerForm[i].parentElement).init();
            new Datepicker(datePickerForm[i].parentElement);
        }
    }
}

function btnToggle(btnElement) {
    let otherBtn;

    if(btnElement.previousElementSibling != null) {
        otherBtn = btnElement.previousElementSibling;
    } else {
        otherBtn = btnElement.nextElementSibling;
    }

    if(!btnElement.classList.contains('active') && !otherBtn.classList.contains('active')) {
        btnElement.classList.toggle('btn-success');
        btnElement.classList.toggle('btn-outline-success');
        btnElement.classList.toggle('active');

        if(btnElement.querySelector('input') != null) {
            btnElement.querySelector('input').setAttribute('checked', true);
        }
    } else if(!btnElement.classList.contains('active')) {
        btnElement.classList.toggle('btn-success');
        btnElement.classList.toggle('btn-outline-success');
        btnElement.classList.toggle('active');

        if(btnElement.querySelector('input') != null) {
            btnElement.querySelector('input').setAttribute('checked', true);
        }

        otherBtn.classList.toggle('btn-success');
        otherBtn.classList.toggle('btn-outline-success');
        otherBtn.classList.toggle('active');

        if(otherBtn.querySelector('input') != null) {
            otherBtn.querySelector('input').removeAttribute('checked');
        }
    }
}

export { addNewRowNumber }
export { addNewRowFromBtn }
export { btnToggle }
export { updateAdultName }
