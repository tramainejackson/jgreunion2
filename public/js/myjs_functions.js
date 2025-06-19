import {Input, Select, Datepicker, initMDB} from './mdb.es.min.js';

function updateAdultName() {
    let registree_input = document.getElementById('attending_adult_row_duplicate').querySelectorAll('input')[0];
    let registree_input2 = document.getElementById('attending_adult_row_duplicate').querySelectorAll('input')[1];

    registree_input.classList.add('active');
    registree_input2.classList.add('active');
    registree_input.value = document.getElementById('first_name').value;
    registree_input2.value = document.getElementById('last_name').value;
}

function addNewRowNumber(attending) {
    initMDB({Input});
    initMDB({Select});

    let count = document.getElementById("attending_" + attending).value;
    let currentRows = document.getElementById('registration_form_table').querySelectorAll('.attending_' + attending + '_row:not(#attending_' + attending + '_row_default)');
    let currentRowCount = currentRows.length;
    let currentPrice = document.getElementById("cost_per_" + attending);
    let currentTotalPrice = document.getElementById("total_" + attending);
    let totalPrice = document.getElementById("total_amount_due");

    // Add new rows
    if (attending === 'adult') {
        count--;
    }

    if (count == currentRowCount) {
    } else if (count < currentRowCount) {
        let countDifference = currentRowCount - count;

        for (var x = 0; x < countDifference; x++) {
            let lastRow = currentRows[currentRowCount - (x + 1)];

            lastRow.remove();
        }
    } else {
        for (var x = 0; x < (count - currentRowCount); x++) {
            let newRow = document.getElementById('attending_' + attending + '_row_default').cloneNode(true);

            //Remove disables
            newRow.classList.remove('d-none');
            newRow.id = '';

            if (newRow.querySelectorAll('input').length > 1) {
                newRow.querySelectorAll('input').forEach(function (item) {
                    item.removeAttribute('disabled');
                });
            } else {
                newRow.querySelector('input').removeAttribute('disabled');
            }

            if (newRow.querySelectorAll('select').length > 1) {
                newRow.querySelectorAll('select').forEach(function (item) {
                    item.removeAttribute('disabled');
                });
            } else {
                newRow.querySelector('select').removeAttribute('disabled');
            }

            //Add new row
            if (currentRowCount != 0) {
                document.getElementsByClassName('attending_' + attending + '_row')[currentRowCount - 1].insertAdjacentElement('afterend', newRow);
            } else {
                document.getElementsByClassName('attending_' + attending + '_row')[0].insertAdjacentElement('afterend', newRow);
            }

            new Input(newRow.querySelector('input').parentElement).init();
            new Select(newRow.querySelector('select'));
        }
    }

    //Update total price per person
    if (attending === 'adult') {
        count++;
    }
    currentTotalPrice.value = count * Number(currentPrice.value);

    //Update total price for family
    totalPrice.value = Number(document.getElementById("total_adult").value) + Number(document.getElementById("total_youth").value) + Number(document.getElementById("total_children").value);
}

function addNewRowFromBtn(taskTitle) {
    initMDB({Input});
    initMDB({Select});
    initMDB({Datepicker});

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

    //Remove disabled form inputs from newly added row
    if (selectForm.length > 0) {
        for (let i = 0; i < selectForm.length; i++) {
            selectForm[i].removeAttribute('disabled');
            new Select(selectForm[i]);
        }
    }

    if (inputForm.length > 0) {
        for (let i = 0; i < inputForm.length; i++) {
            inputForm[i].removeAttribute('disabled');
            new Input(inputForm[i].parentElement).init();
        }
    }

    if (textareaForm.length > 0) {
        for (let i = 0; i < textareaForm.length; i++) {
            textareaForm[i].removeAttribute('disabled');
            textareaForm[i].parentElement.setAttribute('data-mdb-input-init', '');
            new Input(textareaForm[i].parentElement).init();
        }
    }

    if (datePickerForm.length > 0) {
        for (let i = 0; i < datePickerForm.length; i++) {
            datePickerForm[i].removeAttribute('disabled');
            datePickerForm[i].parentElement.setAttribute('data-mdb-datepicker-init', '');
            datePickerForm[i].parentElement.setAttribute('data-mdb-input-init', '');
            new Input(datePickerForm[i].parentElement).init();
            new Datepicker(datePickerForm[i].parentElement);
        }
    }

    if (newRow.querySelectorAll('button[class*="remove"]').length > 0) {
        //Add an addEventListener to the remove buttons of the new rows
        newRow.querySelectorAll('button[class*="remove"]')[0].addEventListener("click", (event) => removeNewRow(event.target));
    }
}

//Switch to edit/create view for the admin when registering another family member
function removeNewRow(removeBtn) {
    removeBtn.parentElement.parentElement.remove();
}

function btnToggle(btnElement) {
    let otherBtn;

    if (btnElement.previousElementSibling != null) {
        otherBtn = btnElement.previousElementSibling;
    } else {
        otherBtn = btnElement.nextElementSibling;
    }

    if (!btnElement.classList.contains('active') && !otherBtn.classList.contains('active')) {
        btnElement.classList.toggle('btn-success');
        btnElement.classList.toggle('btn-outline-success');
        btnElement.classList.toggle('active');

        if (btnElement.querySelector('input') != null) {
            btnElement.querySelector('input').setAttribute('checked', true);
        }
    } else if (!btnElement.classList.contains('active')) {
        btnElement.classList.toggle('btn-success');
        btnElement.classList.toggle('btn-outline-success');
        btnElement.classList.toggle('active');

        if (btnElement.querySelector('input') != null) {
            btnElement.querySelector('input').setAttribute('checked', true);
        }

        otherBtn.classList.toggle('btn-success');
        otherBtn.classList.toggle('btn-outline-success');
        otherBtn.classList.toggle('active');

        if (otherBtn.querySelector('input') != null) {
            otherBtn.querySelector('input').removeAttribute('checked');
        }
    }
}

//Preview images before being uploaded on images page and new location page
function filePreview(input) {

    if (input.files && input.files[0]) {
        if (input.files.length > 1) {
            var imgCount = input.files.length
            $('.imgPreview').remove();

            for (var x = 0; x < imgCount; x++) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('<img class="imgPreview img-thumbnail m-1" src="' + e.target.result + '" width="350" height="200"/>').appendTo('.uploadsView');
                }
                reader.readAsDataURL(input.files[x]);
            }
        } else {
            let reader = new FileReader();

            reader.onload = function (e) {
                const previewImage = document.createElement('img');
                const imageDiv = document.getElementById('profile_photo');

                imageDiv.setAttribute('src', e.target.result);
                imageDiv.classList.add('img-thumbnail', 'img-fluid');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}

//Switch to edit/create view for the admin when registering another family member
function createNewRegistration(id) {
    let newHost = location.origin;

    document.getElementById('member_registration_link').href = newHost + '/registrations/create?member=' + id.value;
}

//Update modal with selected model information to remove
function updateModalToRemoveModel(deleteBtn) {
    let forModalInfo = deleteBtn.parentElement.parentElement.parentElement.parentElement.querySelectorAll('[class*="forModalInfo"]')[0].cloneNode(true);
    let deleteModal = document.getElementById('delete_registration');
    let deleteModalBody = deleteModal.querySelectorAll('.modal-body')[0];
    let deleteModalForm = deleteModal.querySelectorAll('form')[0];

    if (deleteModalBody.hasChildNodes()) {
        if (deleteModalBody.childElementCount > 0) {
            let elementCount = deleteModalBody.childElementCount;

            for (let i = 0; i < elementCount; i++) {
                deleteModalBody.firstElementChild.remove();
            }
        }
    }

    //Update the action string for sending the form
    let newStr = deleteModalForm.action.slice(0, deleteModalForm.action.lastIndexOf('/') + 1);
    let regID = forModalInfo.classList.item(2).replace('forModalInfo', '');
    newStr += regID;
    deleteModalForm.action = newStr;

    //Remove d-none before appending to modal
    forModalInfo.classList.remove('d-none');

    //Append node to modal
    deleteModalBody.appendChild(forModalInfo);
}

//Toggle input value and button color when deleting a committee member
function deleteCommitteeMemberBtn(deleteBtn) {
    let inputValue = deleteBtn.firstElementChild;

    if (deleteBtn.classList.contains('btn-outline-danger')) {
        deleteBtn.classList.remove('btn-outline-danger');
        deleteBtn.classList.add('btn-danger');
        inputValue.value = 'Y';
    } else {
        deleteBtn.classList.add('btn-outline-danger');
        deleteBtn.classList.remove('btn-danger');
        inputValue.value = 'N';
    }
}

function checkErrors(form) {
    event.preventDefault();

    let formInput = form.querySelectorAll('input:enabled, select:enabled');
    let errorCount = 0;

    formInput.forEach(function (item) {
        if (item.value == '' && item.id == '') {
            errorCount++;

            let errorSpan = document.createElement('span')
            errorSpan.innerHTML = 'This field cannot be empty';
            errorSpan.classList.add('text-danger');

            item.parentElement.insertAdjacentElement('afterend', errorSpan);
        }
    });

    if (errorCount == 0) {
        if (form.querySelector('#total_amount_due') !== undefined) {
            form.querySelector('#total_amount_due').removeAttribute('disabled');
        }

        form.submit();
    }
}

export {addNewRowNumber}
export {addNewRowFromBtn}
export {btnToggle}
export {updateAdultName}
export {filePreview}
export {removeNewRow}
export {createNewRegistration}
export {updateModalToRemoveModel}
export {deleteCommitteeMemberBtn}
export {checkErrors}
