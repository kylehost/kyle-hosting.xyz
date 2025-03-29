document.getElementById('minifyButton').addEventListener('click', function() {
    const phpInput = document.getElementById('phpInput').value;
    const minifiedPhp = phpInput
    .replace(/\/\/.*?(\r?\n|$)/g, '')
    .replace(/\/\*.*?\*\//gs, '')
    .replace(/\s+/g, ' ')
    .replace(/\s*([{}();,:])\s*/g, '$1').trim();document.getElementById('minifiedOutput').value = minifiedPhp;});