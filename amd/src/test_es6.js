export const init = name => {
    document.addEventListener('click', e => {
        const someNode = e.target.closest('.block_superframe_heading');
        if (someNode) {
            alert('Hello ' + name + ' you clicked');
        }
    });
};