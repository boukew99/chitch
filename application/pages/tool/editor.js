// © 2025 Chitch-Maintainers, Licensed under the EUPL

const textarea = document.getElementById('editor');
const saveButton = document.getElementById('save-button');
let isDirty = false;

// Mark the textarea as dirty when changes are made
textarea.addEventListener('input', () => {
    isDirty = true;
});

// Reset the dirty flag when the save button is clicked
saveButton.addEventListener('click', () => {
    isDirty = false;
});

// Warn the user if they try to leave the page with unsaved changes
window.addEventListener('beforeunload', (e) => {
    if (isDirty) {
        e.preventDefault();
    }
});

function navigateToWhitespaceLine(lines, currentLineIndex, direction) {
    const step = direction === 'up' ? -1 : 1;
    for (let i = currentLineIndex + step; i >= 0 && i < lines.length; i += step) {
        if (lines[i].trim() === '') {
            return i;
        }
    }
    return currentLineIndex; // No whitespace line found
}

function moveCursorToLine(textarea, targetLineIndex, lines) {
    const targetPos = lines.slice(0, targetLineIndex).join('\n').length + (targetLineIndex > 0 ? 1 : 0);
    textarea.setSelectionRange(targetPos, targetPos);
    textarea.scrollTop = textarea.scrollHeight * (targetLineIndex / lines.length); // Ensure scrolling
}

textarea.addEventListener('keydown', (e) => {
    const lines = textarea.value.split('\n');
    const cursorPos = textarea.selectionStart;
    const currentLineIndex = textarea.value.substr(0, cursorPos).split('\n').length - 1;

    if (e.ctrlKey && (e.key === 'ArrowUp' || e.key === 'ArrowDown')) {
        e.preventDefault(); // Prevent default behavior
        const direction = e.key === 'ArrowUp' ? 'up' : 'down';
        const targetLineIndex = navigateToWhitespaceLine(lines, currentLineIndex, direction);
        moveCursorToLine(textarea, targetLineIndex, lines);
    }
});

// TODO: word selection with arrow key?
