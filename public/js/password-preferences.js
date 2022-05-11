document.addEventListener('DOMContentLoaded', () => {
    const FORM                      = document.getElementById('generate-password-form');
    const LENGTH_SELECT             = document.getElementById('length');
    const UPPERCASE_LETTERS_SELECT  = document.getElementById('uppercase-letters');
    const DIGITS_SELECT             = document.getElementById('digits');
    const SPECIAL_CHARACTERS_SELECT = document.getElementById('special-characters');

    const USER_PASSWORD_PREFERENCES = JSON.parse(localStorage.getItem('user_password_preferences'));

    if (USER_PASSWORD_PREFERENCES) {
        LENGTH_SELECT.value               = USER_PASSWORD_PREFERENCES.length;
        UPPERCASE_LETTERS_SELECT.checked  = USER_PASSWORD_PREFERENCES.uppercase_letters;
        DIGITS_SELECT.checked             = USER_PASSWORD_PREFERENCES.digits;
        SPECIAL_CHARACTERS_SELECT.checked = USER_PASSWORD_PREFERENCES.special_characters;
    }

    FORM.addEventListener('submit', () => {
        localStorage.setItem('user_password_preferences', JSON.stringify({
            length: parseInt(LENGTH_SELECT.value, 10),
            uppercase_letters: UPPERCASE_LETTERS_SELECT.checked,
            digits: DIGITS_SELECT.checked,
            special_characters: SPECIAL_CHARACTERS_SELECT.checked,
        }));
    });
});