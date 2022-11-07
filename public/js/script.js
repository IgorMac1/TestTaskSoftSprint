$(function () {
    const warningText = document.getElementById('warning');

    let selectedIds;
    let selectedDeleteId;

    $('form#ajax_form').submit(function (event) {
        let formData = parseFormData($(this).serializeArray());
        event.preventDefault();
        if (!formData.role_id){
            $('h6#warning-role').attr('hidden', false);
            return false;
        }else if (formData.name.length < 2 || formData.name.length > 50){
            $('h6#warning-name').attr('hidden', false);
            return false;
        }else if (formData.surname.length < 2 || formData.surname.length > 50){
            $('h6#warning-surname').attr('hidden', false);
            return false;
        }
        let id = $('form#ajax_form .submit-button').attr('id');
        if (id) {
            editUser(id, formData);
        } else {
            addUser(formData);
        }

    });
    function parseFormData(formData) {
        let userData = {};
        $.each(formData, function (i, field) {
            userData[field.name] = field.value;
        });
        if (!userData.status) {
            userData.status = 'off';
        }
        return userData;
    }

    function addUser(userData) {
        console.log(userData)
        sendRequest(
            '/api/users',
            userData,
            function (result) {
                setAllCheckboxesOff();
                let userRow = getUserRow(result.user);
                $('#table-users').append(userRow);
                $('#user-form-modal').modal('hide');
            });
    }

    function editUser(userId, userData) {
        sendRequest(
            '/api/users/' + userId,
            userData,
            function (result) {
                setAllCheckboxesOff();
                if (result.status === false){
                    $('h6#warning-user-not-found').attr('hidden', false);
                    $('.submit-button').attr('disabled',true)
                    return false
                }
                $('#user-form-modal').modal('hide')
                let userRow = getUserRow(result.user);
                $('tr#' + userId).replaceWith(userRow);
            }, function () {

            }, 'PUT');
    }

    $('button.ok').on('click',function (){
        let checkboxes = [];
        let userData = {};
        $('input:checkbox:checked').each(function(){
            if (this.value !== 'on'){
                checkboxes.push(this.value);
            }return true
        });
        let selectedAction = $('select.select-action.top').val();
        if ($(this).hasClass('bottom')) {
            selectedAction = $('select.select-action.bottom').val();
        }

        if (checkboxes.length === 0) {
            modalWarning('Choose users');
            return false;
        }
        switch (selectedAction) {
            case 'active':
            case 'inactive': {
                setUserStatus(selectedAction);
                setAllCheckboxesOff();
                break;
            }
            case 'delete': {
                deleteUsers();
                $('.confirmDelete').attr('disabled',false);
                break;
            }
            case null: {
                modalWarning('Choose action');
                break;
            }
        }

        function deleteUsers() {
            selectedIds = checkboxes;
            let userNames = [];
            for (let i in selectedIds) {
                userNames.push($('.user#' + selectedIds[i] + ' td[dataField="surname"]').text());
            }
            $('#warningModal').click();
            $('button.confirmDelete').attr('hidden', false);
            warningText.innerText = 'Are you sure you want to delete these users?' + "\n" + userNames.join("\n");
        }

        function setUserStatus (status) {
            userData['status'] = status;
            userData['ids'] = checkboxes;
            sendRequest(
                '/api/users/change-status',
                userData,
                function (result) {
                    if (result.status === false){
                        modalWarning('Users : ' + "\n" + userNotFound(result).join("\n") + "\n" + ' not found');
                        $('button.confirmDelete').attr('hidden', true);
                        return false;
                    }
                    let userIconStatus = status === 'active' ? 'on' : 'off';
                    for (let id in checkboxes) {
                        let statusBlock = $('tr#' + checkboxes[id] + ' td.user-status');
                        statusBlock.attr('dataValue',userIconStatus)
                        statusBlock.empty().append('<i class="fa fa-circle ' + userIconStatus + '-circle"></i>');
                    }
                },
                function () {

                });
        }
    })

    $(document).on('click', 'button.confirmDelete', function () {
        setAllCheckboxesOff();
        $('h6#warning-user-not-found').attr('hidden', true);
        if (selectedDeleteId) {
            sendRequest(
                '/api/users/' + selectedDeleteId,
                {},
                function (result) {
                    if (result.status === false){
                        let userName = $('.user#' + selectedDeleteId + ' td[dataField="surname"]').text();
                        warningText.innerText = 'User ' + userName + ' not found' ;
                        $('.confirmDelete').attr('disabled',true);
                        $('tr#' + selectedDeleteId).remove();
                        selectedDeleteId = undefined;

                    }else if (result.user !== false){
                        $('#deleteModal').modal('hide');
                        $('tr#' + selectedDeleteId).remove();
                        selectedDeleteId = undefined;
                    }
                    },
                function () {

                },
                'DELETE');
        } else {
            let requestData = {ids: selectedIds};
            sendRequest(
                '/api/users/delete',
                requestData,
                function (result) {
                    if (result.status === false){
                        warningText.innerText = 'Users : ' + "\n" + userNotFound(result).join("\n") + "\n" + ' not found' ;
                        $('.confirmDelete').attr('disabled',true);
                        for (let id in selectedIds) {
                            let userRow = $('tr#' + selectedIds[id]);
                            userRow.remove();
                        }

                    }else if (result.status !== false){
                        for (let id in selectedIds) {
                            let userRow = $('tr#' + selectedIds[id]);
                            userRow.remove();
                        }
                        $('#deleteModal').modal('hide');
                        selectedIds = undefined;
                    }
                    },
                function () {

                },
                'DELETE');
        }

    })
    function initializeButtonAction() {
        $(document).on('click', 'button.deleteUser', function () {
            $('.confirmDelete').attr('disabled',false)
            selectedDeleteId = $(this).attr('id').split(['-'])[1];
            let userName = $('.user#' + selectedDeleteId + ' td[dataField="surname"]').text();
            warningText.innerText = 'Are you sure you want to delete this user: ' + userName + ' ?' ;
            $('button.confirmDelete').attr('hidden', false);
            $('#warningModal').click();


        });
        $(document).on('click', 'button.edit', function () {
            $('.submit-button').attr('disabled',false);
            $('h6#warning-user-not-found').attr('hidden', true);
            $('h6#warning-name').attr('hidden', true);
            $('h6#warning-surname').attr('hidden', true);
            $('h6#warning-role').attr('hidden', true);
            let id = $(this).attr('id').split(['-'])[1];
            $('form#ajax_form .submit-button').attr('id', id);
            $('h5.modal-title').text('Edit User');
            $('tr#' + id).find('td').each(function () {
                if ($(this).attr('dataField')) {
                    let fieldName = $(this).attr('dataField');
                    let fieldValue = $(this).attr('dataValue');
                    if (fieldName === 'status') {
                        $('form#ajax_form [name="' + fieldName + '"]').prop('checked', (fieldValue === 'on'));
                    } else {
                        $('form#ajax_form [name="' + fieldName + '"]').val(fieldValue);
                    }
                }
            });
        });
        $(document).on('click', 'button.addUser', function () {
            $('.submit-button').attr('disabled',false);
            $('h6#warning-user-not-found').attr('hidden', true);
            $('h6#warning-name').attr('hidden', true);
            $('h6#warning-surname').attr('hidden', true);
            $('h6#warning-role').attr('hidden', true);
            $('form#ajax_form .submit-button').attr('id', '');
            $('h5.modal-title').text('Add User');
            $('form#ajax_form')[0].reset();
        });
        $(document).on('click', '#all-items', function() {
            if(this.checked) {
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                setAllCheckboxesOff()
            }
        });
        $(document).on('click', '.td-checkbox', function() {
            if(!this.checked) {
                $('#all-items').prop('checked', false);
            } else {
                let allChecked = true;
                $('.td-checkbox').each(function () {
                    if(!this.checked) {
                        allChecked = false;
                    }
                });
                if(allChecked) {
                    $('#all-items').prop('checked', 'checked');
                }
            }
        });
    }
    initializeButtonAction();

    function sendRequest(url, data, onSuccess, onError, method = 'POST') {
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function (result) {
                onSuccess(result);
            },
            error: function (result) {
                console.log('error');
                console.log(result);
                onError(result);
            }
        });
    }

    function getUserRow(userData){
        return `
        <tr class="user" id="${userData.id}">
            <td dataField="name" dataValue="${userData.name}" class="align-middle">
                <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                    <input type="checkbox" class="custom-control-input checkbox id td-checkbox"
                           name="id" value="${userData.id}"
                           id="item-${userData.id}">
                    <label class="custom-control-label"
                           for="item-${userData.id}"></label>
                </div>
            </td>
            <td dataField="surname" dataValue="${userData.surname}" class="text-nowrap align-middle">${userData.full_name}</td>
            <td dataField="role_id" dataValue="${userData.role_id}" class="text-nowrap align-middle">
                <span>${userData.role}</span></td>
            <td dataField="status" dataValue="${userData.status}" class="user-status text-center align-middle">
                <i class="fa fa-circle ${userData.status}-circle"></i>
            </td>
            <td class="text-center align-middle">
                <div class="btn-group align-top">
                    <button class="btn btn-sm btn-outline-secondary badge edit"
                            type="submit" data-toggle="modal"
                            data-target="#user-form-modal" id="edit-${userData.id}" >Edit
                    </button>
                    <button class="btn btn-sm btn-outline-secondary badge deleteUser "
                            data-target="#user-delete-modal"
                            data-toggle="modal"
                            id="delete-${userData.id}"
                            type="submit"><i class="fa fa-trash" ></i></button>
                </div>
            </td>
        </tr>`
    }

    function modalWarning(text = '',confirmButton = true){
        warningText.innerText = text;
        document.getElementsByClassName('confirmDelete').hidden = confirmButton ;
        document.getElementById('warningModal').click();
    }

    function setAllCheckboxesOff(){
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }

    function userNotFound(result){
        let usersId = result.notFoundId
        let userNames = [];
        if (usersId.length > 0){
            for(let i in usersId){
                $('.user').each(function (){
                    if (usersId[i] === $(this).attr('id')){
                        userNames.push($('.user#' + usersId[i] + ' td[dataField="surname"]').text());
                    }
                })

            }
        }
        return userNames
    }
})








