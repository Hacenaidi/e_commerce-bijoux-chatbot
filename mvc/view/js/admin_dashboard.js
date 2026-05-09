(function () {
    "use strict";

    function setError(input, message) {
        clearError(input);

        var error = document.createElement('small');
        error.className = 'field-error';
        error.textContent = message;
        input.insertAdjacentElement('afterend', error);
        input.classList.add('input-error');
    }

    function clearError(input) {
        var next = input.nextElementSibling;
        if (next && next.classList && next.classList.contains('field-error')) {
            next.remove();
        }
        input.classList.remove('input-error');
    }

    function validateText(input, label) {
        if (!input || input.value.trim() === '') {
            setError(input, label + ' is required.');
            return false;
        }

        clearError(input);
        return true;
    }

    function validateEmail(input) {
        var value = input.value.trim();
        var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!pattern.test(value)) {
            setError(input, 'Enter a valid email address.');
            return false;
        }

        clearError(input);
        return true;
    }

    function validatePrice(input) {
        var value = Number(input.value);

        if (input.value.trim() === '' || Number.isNaN(value) || value <= 0) {
            setError(input, 'Price must be greater than zero.');
            return false;
        }

        clearError(input);
        return true;
    }

    function validateNonNegative(input, label) {
        var value = Number(input.value);

        if (input.value.trim() === '' || Number.isNaN(value) || value < 0) {
            setError(input, label + ' must be zero or greater.');
            return false;
        }

        clearError(input);
        return true;
    }

    function validateFile(input) {
        if (!input.files || input.files.length === 0) {
            setError(input, 'Please choose an image file.');
            return false;
        }

        var file = input.files[0];
        var allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        if (allowed.indexOf(file.type) === -1) {
            setError(input, 'Only JPG, PNG, WEBP or GIF images are allowed.');
            return false;
        }

        clearError(input);
        return true;
    }

    function bindValidation(form, validators) {
        if (!form) {
            return;
        }

        form.querySelectorAll('input').forEach(function (input) {
            input.addEventListener('input', function () {
                clearError(input);
            });
        });

        form.addEventListener('submit', function (event) {
            var valid = true;

            validators.forEach(function (validator) {
                valid = validator() && valid;
            });

            if (!valid) {
                event.preventDefault();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var productCreateForm = document.querySelector('form[action="ajouterProduit.php"]');
        bindValidation(productCreateForm, [
            function () { return validateText(productCreateForm.querySelector('#nom'), 'Name'); },
            function () { return validateText(productCreateForm.querySelector('#marque'), 'Brand'); },
            function () { return validatePrice(productCreateForm.querySelector('#prix')); },
            function () { return validateText(productCreateForm.querySelector('#couleur'), 'Color'); },
            function () { return validateText(productCreateForm.querySelector('#description'), 'Description'); },
            function () { return validateText(productCreateForm.querySelector('#status'), 'Status'); },
            function () { return validateText(productCreateForm.querySelector('#stock_taille'), 'Stock size'); },
            function () { return validateNonNegative(productCreateForm.querySelector('#stock_quantite'), 'Stock quantity'); },
            function () { return validateFile(productCreateForm.querySelector('#image')); }
        ]);

        var productEditForm = document.querySelector('form[action="modify_action_produit.php"]');
        bindValidation(productEditForm, [
            function () { return validateText(productEditForm.querySelector('#nom'), 'Name'); },
            function () { return validateText(productEditForm.querySelector('#marque'), 'Brand'); },
            function () { return validatePrice(productEditForm.querySelector('#prix')); },
            function () { return validateText(productEditForm.querySelector('#couleur'), 'Color'); },
            function () { return validateText(productEditForm.querySelector('#description'), 'Description'); },
            function () { return validateText(productEditForm.querySelector('#status'), 'Status'); },
            function () { return validateText(productEditForm.querySelector('#stock_taille'), 'Stock size'); },
            function () { return validateNonNegative(productEditForm.querySelector('#stock_quantite'), 'Stock quantity'); }
        ]);

        document.querySelectorAll('form[id^="client-form-"]').forEach(function (form) {
            var linkedInputs = document.querySelectorAll('input[form="' + form.id + '"]');

            bindValidation(form, [
                function () { return validateText(linkedInputs[0], 'Client name'); },
                function () { return validateText(linkedInputs[1], 'Client first name'); },
                function () { return validateEmail(linkedInputs[2]); }
            ]);
        });
    });
}());